<?php
/**
 * PayPal process class
 * Final class cannot be inherited anymore. We use them when creating new instances
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Logging\Log;
use NativeRentalSystem\Models\PaymentMethod\PaymentMethod;
use NativeRentalSystem\Models\PaymentResource\iPaymentResource;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;

class NRSPayPal implements iPaymentResource
{
    const PRODUCTION_HOST               = 'www.paypal.com';
    const SANDBOX_HOST                  = 'www.sandbox.paypal.com';
    protected $conf 	                = NULL;
    protected $lang 		            = NULL;
    protected $settings                 = array();
    protected $debugMode 	            = 0;

    protected $paymentMethodId          = 0;

    // Array holds the fields to submit to PayPal
    protected $fields                   = array();
    protected $use_ssl                  = TRUE;

    protected $businessEmail            = "";
    // www.sandbox.paypal.com (TRUE) or www.paypal.com (FALSE)
    protected $useSandbox               = FALSE;
    protected $checkCertificate         = FALSE;
    protected $currencySymbol	        = '$';
    protected $currencyCode		        = 'USD';
    protected $companyName              = "";
    protected $confirmationPageId       = 0;
    protected $cancelledPaymentPageId   = 0;

    protected $bookingCode              = "";
    protected $totalPayNow              = 0.00;

    /**
     * @param ExtensionConfiguration &$paramConf
     * @param Language &$paramLang
     * @param array $paramSettings
     * @param int $paramPaymentMethodId
     */
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings, $paramPaymentMethodId)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;
        // Set saved settings
        $this->settings = $paramSettings;

        $this->paymentMethodId = StaticValidator::getValidPositiveInteger($paramPaymentMethodId, 0);

        $objPaymentMethod = new PaymentMethod($this->conf, $this->lang, $paramSettings, $paramPaymentMethodId);
        $paymentMethodDetails = $objPaymentMethod->getDetails();
        $this->businessEmail = isset($paymentMethodDetails['payment_method_email']) ? sanitize_email($paymentMethodDetails['payment_method_email']) : "";
        $this->useSandbox = !empty($paymentMethodDetails['sandbox_mode']) ? TRUE : FALSE;
        $this->checkCertificate = !empty($paymentMethodDetails['check_certificate']) ? TRUE : FALSE;
        // Process to PayPal order page
        $this->currencySymbol = StaticValidator::getValidSetting($paramSettings, 'conf_currency_symbol', "textval", "$");
        $this->currencyCode = StaticValidator::getValidSetting($paramSettings, 'conf_currency_code', "textval", "USD");
        $this->companyName = StaticValidator::getValidSetting($paramSettings, 'conf_company_name', "textval", "");
        $this->confirmationPageId = StaticValidator::getValidSetting($paramSettings, 'conf_confirmation_page_id', 'positive_integer', 0);
        $this->cancelledPaymentPageId = StaticValidator::getValidSetting($paramSettings, 'conf_cancelled_payment_page_id', 'positive_integer', 0);

        // populate $fields array with a few default values.  See the paypal
        // documentation for a list of fields and their data types. These default
        // values can be overwritten by the calling script.
        $this->addField('rm','2');           // Return method = POST
        $this->addField('cmd','_xclick');
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    /******************************************************************************************/
    /* Default methods                                                                        */
    /******************************************************************************************/

    /**
     * @param $paramCurrentDescription
     * @param $paramTotalPayNow = '0.00'
     * @return string
     */
    public function getDescriptionHTML($paramCurrentDescription, $paramTotalPayNow = '0.00')
    {
        return $paramCurrentDescription;
    }

    public function setProcessingPage($paramBookingCode, $paramTotalPayNow = '0.00')
    {
        $this->bookingCode = sanitize_text_field($paramBookingCode);
        $this->totalPayNow = floatval($paramTotalPayNow);

        $sanitizedBookingCode = sanitize_text_field($paramBookingCode);
        // Process to PayPal order page
        if ($this->businessEmail != "")
        {
            $confirmURL = $this->confirmationPageId > 0 ? get_permalink($this->confirmationPageId) : site_url();
            $cancelURL = $this->cancelledPaymentPageId > 0 ? get_permalink($this->cancelledPaymentPageId) : site_url();
            $notifyURL = site_url().'/?__'.$this->conf->getExtensionPrefix().'api=1&extension='.$this->conf->getExtensionFolder();
            $notifyURL .= '&extension_action=payment-callback&payment_method_id='.$this->paymentMethodId;

            // If there is a valid business email set, process the transfer to PayPal payment page
            $this->addField('business', $this->businessEmail);
            $this->addField('notify_url', $notifyURL);
            $this->addField('return', $confirmURL);
            $this->addField('cancel_return', $cancelURL);
            $this->addField('item_name', $this->companyName);
            $this->addField('invoice', $sanitizedBookingCode);
            $this->addField('currency_code', $this->currencyCode);
            $this->addField('amount', number_format(floatval($paramTotalPayNow), 2, '.', ''));
            //$this->objPayPal->submitPayPalPost(); // submit the fields to paypal
            //$this->objPayPal->dumpFields();      // for debugging, output a table of all the fields
        }
    }

    public function getProcessingPageContent()
    {
        $ret = '<h2>'.$this->lang->getText('NRS_PROCESSING_PAYMENT_TEXT').'</h2>
                <div class="booking-content">
                    '.$this->lang->getText('NRS_BOOKING_CODE_TEXT').': '.$this->bookingCode.'.<br />
                    '.$this->lang->getText('NRS_PLEASE_WAIT_UNTIL_PAYMENT_WILL_BE_PROCESSED_TEXT').'
                    <form method="post" name="paypal_form" action="'.$this->getFormSubmitURL().'">
                    '.$this->getFormFields().'
                    </form>
                    <script type="text/javascript">setTimeout("document.paypal_form.submit()",1500);</script>
                </div>';

        return $ret;
    }

    /**
     * We prefer not to check certificate in this method, because we don't want to have a buggy plugin because of expired certificate or so,
     * there is much less damage in getting marked as paid without certificate authorization than not making it paid at all
     */
    public function processAPI()
    {
        $errorMessage = '';
        $debugLog = '';
        $verified = FALSE;
        $processFailed = FALSE;
        $payerEmail = '';
        $objPayPalIPN = NULL;

        // Process API
        $paymentFolderPathWithFileName = $this->conf->getLibrariesPath().'NRSPayPalIPN'.DIRECTORY_SEPARATOR.'NRSPayPalIPN.php';
        if (is_readable($paymentFolderPathWithFileName))
        {
            require_once $paymentFolderPathWithFileName;
            $objPayPalIPN = new NRSPayPalIPN($this->getWebHost());

            /*
             * API CALLBACK PATH: http://yourdomain.com/?__[EXTENSION_PREFIX]api=1&extension=[EXTENSION_FOLDER]&extension_action=payment-callback&payment_method_id=1
             * i.e.
             *      http://nativerental.com/?__[EXTENSION_PREFIX]api=1&extension=CarRental&extension_action=payment-callback&payment_method_id=1
             * NOTES:
             *      Since this script is executed on the back end between the PayPal server and this
             *      script, you will want to log errors to a file or email. Do not try to use echo
             *      or print--it will not work!
             */
            try
            {
                $objPayPalIPN->requirePostMethod();
                $verified = $objPayPalIPN->processIpn();
            } catch (Exception $e)
            {
                $processFailed = TRUE;
                $errorMessage .= $e->getMessage() . "\n\n";
            }
        }

        /*
        The processIpn() method returned true if the IPN was "VERIFIED" and false if it
        was "INVALID".
        */
        if (($verified && $processFailed != FALSE) || $this->checkCertificate == FALSE)
        {
            /*
            Once you have a verified IPN you need to do a few more checks on the POST
            fields--typically against data you stored in your database during when the
            end user made a purchase (such as in the "success" page on a web payments
            standard button). The fields PayPal recommends checking are:

                1. Check the $_POST['payment_status'] is "Completed"
                2. Check that $_POST['txn_id'] has not been previously processed
                3. Check that $_POST['invoice'] is your Invoice Id is correct
                4. Check that $_POST['receiver_email'] is your Primary PayPal email
                5. Check that $_POST['payment_amount'] and $_POST['payment_currency']
                   are correct

            Since implementations on this varies, I will leave these checks out of this
            example and just send an email using the getTextReport() method to get all
            of the details about the IPN with 'Verified IPN' and $listener->getTextReport();
            */

            $arrIPNData = $objPayPalIPN->getIPNData();
            $paramBookingCode = $arrIPNData['invoice'];
            $validBookingCode = esc_html(sanitize_text_field($paramBookingCode));
            $sanitizedPaymentStatus = isset($arrIPNData['payment_status']) ? sanitize_text_field($arrIPNData['payment_status']) : '';
            $validPaymentStatus = esc_html($sanitizedPaymentStatus);
            $payerEmail = isset($arrIPNData['payer_email']) ? $arrIPNData['payer_email'] : '';

            $objEMailsObserver = new \NativeRentalSystem\Models\EMail\EMailsObserver($this->conf, $this->lang, $this->settings);
            $objBookingsObserver = new \NativeRentalSystem\Models\Booking\BookingsObserver($this->conf, $this->lang, $this->settings);
            $objBooking = new \NativeRentalSystem\Models\Booking\Booking(
                $this->conf, $this->lang, $this->settings, $objBookingsObserver->getIdByCode($paramBookingCode)
            );
            $objInvoice = new \NativeRentalSystem\Models\Booking\Invoice($this->conf, $this->lang, $this->settings, $objBooking->getId());
            if($objBooking->isValid())
            {
                if(isset($arrIPNData['payment_status'], $arrIPNData['payer_email'], $arrIPNData['txn_id']))
                {
                    if($arrIPNData['payment_status'] == "Completed" || $arrIPNData['payment_status'] == "Pending")
                    {
                        $printPayerEmail = esc_html(sanitize_text_field($arrIPNData['payer_email']));
                        $printTransactionId = esc_html(sanitize_text_field($arrIPNData['txn_id']));

                        $payPalHtmlToAppend = '<!-- PAYPAL PAYMENT DETAILS -->
<br /><br />
<table style="font-family:Verdana, Geneva, sans-serif; font-size: 12px; background-color:#eeeeee; width:840px; border:none;" cellpadding="4" cellspacing="1">
<tr>
<td align="left" width="30%" style="font-weight:bold; font-variant:small-caps; background-color:#ffffff; padding-left:5px;">'.$this->lang->getText('NRS_PAYER_EMAIL_TEXT').'</td>
<td align="left" style="background-color:#ffffff; padding-left:5px;">'.$printPayerEmail.'</td>
</tr>
<tr>
<td align="left" style="font-weight:bold; font-variant:small-caps; background-color:#ffffff; padding-left:5px;">'.$this->lang->getText('NRS_TRANSACTION_ID_TEXT').'</td>
<td align="left" style="background-color:#ffffff; padding-left:5px;">'.$printTransactionId.'</td>
</tr>
</table>';
                        $appended = $objInvoice->append($payPalHtmlToAppend);

                        $markedAsPaid = $objBooking->markPaid($arrIPNData['txn_id'], $arrIPNData['payer_email']);
                        $emailProcessed = $objEMailsObserver->sendBookingConfirmationEmail($objBooking->getId(), TRUE);
                        if($markedAsPaid && $emailProcessed === FALSE)
                        {
                            $errorMessage .= 'Failed: Reservation was marked as paid, but system was unable to send the confirmation email!';
                        } else if($markedAsPaid === FALSE)
                        {
                            $errorMessage .= 'Failed: Reservation was not marked as paid!';
                        } else if($appended === FALSE)
                        {
                            $errorMessage .= 'Failed: Transaction data was not appended to invoice!';
                        }
                    } else if ($arrIPNData['payment_status'] == "Refunded" || $arrIPNData['payment_status'] == "Reversed")
                    {
                        $refunded = $objBooking->refund();
                        $objEMailsObserver->sendBookingCancellationEmail($objBooking->getId(), TRUE);

                        if($refunded === FALSE)
                        {
                            $errorMessage .= 'Failed: Reservation was not refunded!';
                        }
                    } else
                    {
                        $errorMessage .= "Payment status - ".$validPaymentStatus." - is unknown.\n";
                    }
                } else
                {
                    $errorMessage .= "Payment status, payer email or transaction id parameter is missing.\n";
                }
            } else
            {
                $errorMessage .= "Booking code - {$validBookingCode} - is invalid.\n\n";
            }
        } else
        {
            $errorMessage .= "Invalid IPN\n";
            /*
            An Invalid IPN *may* be caused by a fraudulent transaction attempt. It's
            a good idea to have a developer or sys admin manually investigate any
            invalid IPN with 'Invalid IPN' and, $listener->getTextReport().
            */
        }

        $isFailed = $verified == FALSE;
        $debugLog .= $verified ? "Verified IPN\n" : "Invalid IPN\n";
        if(!is_null($objPayPalIPN))
        {
            $debugLog .= $objPayPalIPN->getTextReport()."\n\n";
        }

        // Save log
        $objLog = new Log($this->conf, $this->lang, $this->settings, 0);
        $objLog->save('payment-callback', $payerEmail, '', '', $isFailed, $errorMessage, $debugLog);
    }
    /******************************************************************************************/


    public function getWebHost()
    {
        if ($this->useSandbox)
        {
            return self::SANDBOX_HOST;
        } else
        {
            return self::PRODUCTION_HOST;
        }
    }

    private function addField($field, $value)
    {
        // adds a key=>value pair to the fields array, which is what will be
        // sent to paypal as POST variables.  If the value is already in the
        // array, it will be overwritten.
        $this->fields["$field"] = $value;
    }

    /**
     * Special method for Native rental system
     */
    private function getFormSubmitURL()
    {
        if ($this->use_ssl)
        {
            $uri = 'https://'.$this->getWebHost().'/cgi-bin/webscr';
        } else
        {
            $uri = 'http://'.$this->getWebHost().'/cgi-bin/webscr';
        }

        return $uri;
    }

    /**
     * Special method (more short version of 'submitPayPalPost') for Native rental system
     */
    private function getFormFields()
    {
        $ret = "";
        foreach ($this->fields as $name => $value)
        {
            $ret .= '<input type="hidden" name="'.esc_attr($name).'" value="'.esc_attr($value).'">';
        }

        return $ret;
    }
}
 
