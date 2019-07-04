<?php
/**

 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
*/
//error_reporting(0); - this should always be controlled by WordPress
namespace NativeRentalSystem\Models\EMail;
use NativeRentalSystem\Models\AbstractElement;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\iElement;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;

class EMail extends AbstractElement implements iElement
{
    private $conf 	                        = NULL;
    private $lang 		                    = NULL;
    private $debugMode 	                    = 0;
	private $emailId 	                    = 0;
	private $companyName 	                = "";
    private $companyPhone 	                = "";
	private $companyEmail 	                = "";
	private $sendEmails 	                = FALSE;

	public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings, $paramEmailId)
	{
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;

        $this->companyName = StaticValidator::getValidSetting($paramSettings, 'conf_company_name', "textval", "");
        $this->companyPhone = StaticValidator::getValidSetting($paramSettings, 'conf_company_phone', "textval", "");
        $this->companyEmail = StaticValidator::getValidSetting($paramSettings, 'conf_company_email', "email", "");
        if(isset($paramSettings['conf_send_emails']))
        {
            $this->sendEmails = $paramSettings['conf_send_emails'] == 1 ? TRUE : FALSE;
        }

        $this->emailId = StaticValidator::getValidPositiveInteger($paramEmailId);
	}

    /**
     * @param int $paramEmailId
     * @return mixed
     */
    private function getDataFromDatabaseById($paramEmailId)
    {
        $validEmailId = StaticValidator::getValidPositiveInteger($paramEmailId, 0);

        $retData = $this->conf->getInternalWPDB()->get_row("
            SELECT *
            FROM {$this->conf->getPrefix()}emails
            WHERE email_id='{$validEmailId}'
        ", ARRAY_A);

        return $retData;
    }

    public function getId()
    {
        return $this->emailId;
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    public function canEdit()
    {
        $canEdit = current_user_can('manage_'.$this->conf->getExtensionPrefix().'all_settings');

        return $canEdit;
    }

    public function getDetails($paramIncludeUnclassified = FALSE)
    {
        $ret = $this->getDataFromDatabaseById($this->emailId);

        if(!is_null($ret))
        {
            // Make raw
            $ret['email_subject'] = stripslashes($ret['email_subject']);
            $ret['email_body'] = stripslashes($ret['email_body']);

            // Retrieve translation
            $ret['translated_email_subject'] = $this->lang->getTranslated("em{$ret['email_type']}_email_subject", $ret['email_subject']);
            $ret['translated_email_body'] = $this->lang->getTranslated("em{$ret['email_type']}_email_body", $ret['email_body']);

            // Make print
            $ret['print_email_subject'] = esc_html($ret['email_subject']);
            $ret['print_email_body'] = nl2br(implode("\n", array_map('esc_html', explode("\n", $ret['email_body']))));

            $ret['print_translated_email_subject'] = esc_html($ret['translated_email_subject']);
            $ret['print_translated_email_body'] = nl2br(implode("\n", array_map('esc_html', explode("\n", $ret['translated_email_body']))));

            // Prepare output for edit
            $ret['edit_email_subject'] = esc_attr($ret['email_subject']); // for input field
            $ret['edit_email_body'] = esc_textarea($ret['email_body']); // for textarea field
        } else if($paramIncludeUnclassified == TRUE)
        {
            $ret = array(
                'email_type' 		    => 0,
                'email_subject'         => '',
                'email_body' 	        => '',
                'print_email_subject'   => '',
                'print_email_body' 	    => '',
                'edit_email_subject'    => '',
                'edit_email_body' 	    => '',
            );
        }

        return $ret;
    }

    /**
     * Admin email content preview
     * @param int $paramEmailWidth
     * @return array
     */
    public function getPreview($paramEmailWidth = 840)
    {
        $validEmailWidth 	= StaticValidator::getValidPositiveInteger($paramEmailWidth, $paramEmailWidth);
        $emailContent   	= $this->getDetails(TRUE);
        $bookingCode 		= "DEMO2015A0001";// Demo
        $customerId 		= 1001; // Demo
        $locationName       = $this->lang->getText('NRS_EMAIL_DEMO_LOCATION_NAME_TEXT');
        $locationPhone      = $this->lang->getText('NRS_EMAIL_DEMO_LOCATION_PHONE_TEXT');
        $locationEmail      = $this->lang->getText('NRS_EMAIL_DEMO_LOCATION_EMAIL_TEXT');

        // Select the newest invoice for testing. Invoice with booking id=0 will exist there by default as a demo
        $invoiceDetails = $this->conf->getInternalWPDB()->get_row("
			SELECT *
			FROM {$this->conf->getPrefix()}invoices
			WHERE blog_id='{$this->conf->getBlogId()}'
			ORDER BY booking_id DESC LIMIT 1
		", ARRAY_A);

        // If there exists booking under this booking id
        $sql = "
            SELECT booking_id, customer_id, booking_code
            FROM {$this->conf->getPrefix()}bookings
            WHERE booking_id='{$invoiceDetails['booking_id']}'
        ";

        $bookingData = $this->conf->getInternalWPDB()->get_row($sql, ARRAY_A);

        if(!is_null($bookingData))
        {
            $bookingCode = $bookingData['booking_code'];
            $customerId = $bookingData['customer_id'];
        }

        // 1 - Replace shortcodes in email subject
        $printEmailSubject = $this->replaceBBCodesInEmailSubject(
            $emailContent['print_email_subject'], $bookingCode, $invoiceDetails['customer_name'], $locationName
        );
        // 2 - Replaces bb codes in email body
        $printEmailBody = $this->replaceBBCodesInEmailBody(
            $emailContent['print_email_body'], $bookingCode, $customerId, $invoiceDetails['customer_name'],
            $locationName, $locationPhone, $locationEmail, $invoiceDetails['invoice']
        );
        // 3 - Put email data in a div, to all center and other tags looks well.
        $printEmailBody = '<div style="width:'.$validEmailWidth.'px;">'.$printEmailBody.'</div>';

        // 4 - Replace shortcodes in translated email subject
        $printTranslatedEmailSubject = $this->replaceBBCodesInEmailSubject(
            $emailContent['print_translated_email_subject'], $bookingCode, $invoiceDetails['customer_name'], $locationName
        );
        // 5 - Replaces bb codes in translated email body
        $printTranslatedEmailBody = $this->replaceBBCodesInEmailBody(
            $emailContent['print_translated_email_body'], $bookingCode, $customerId, $invoiceDetails['customer_name'],
            $locationName, $locationPhone, $locationEmail, $invoiceDetails['invoice']
        );
        // 6 - Put translated email data in a div, to all center and other tags looks well.
        $printTranslatedEmailBody = '<div style="width:'.$validEmailWidth.'px;">'.$printTranslatedEmailBody.'</div>';

        $preview = array(
            'print_email_subject' => $printEmailSubject,
            'print_email_body' => $printEmailBody,
            'print_translated_email_subject' => $printTranslatedEmailSubject,
            'print_translated_email_body' => $printTranslatedEmailBody,
        );

        return $preview;
    }

    /**
     * @return bool|false|int
     */
    public function save()
    {
        $saved = FALSE;
        $ok = TRUE;
        $validEmailId = StaticValidator::getValidPositiveInteger($this->emailId, 0);
        $sanitizedEmailSubject = sanitize_text_field($_POST['email_subject']);
        $validEmailSubject = esc_sql($sanitizedEmailSubject);
        $sanitizedEmailBody =  implode( "\n", array_map( 'sanitize_text_field', explode( "\n", $_POST['email_body'] ) ) );
        $validEmailBody = esc_sql($sanitizedEmailBody);

        $subjectExists = $this->conf->getInternalWPDB()->get_row("
			SELECT email_type
			FROM {$this->conf->getPrefix()}emails
			WHERE email_subject='{$validEmailSubject}'
			AND email_id!='{$validEmailId}' AND blog_id='{$this->conf->getBlogId()}'
        ", ARRAY_A);

        if(!is_null($subjectExists))
        {
            $ok = FALSE;
            $this->errorMessages[] = $this->lang->getText('NRS_EMAIL_SUBJECT_EXISTS_ERROR_TEXT');
        }

        if($validEmailId > 0 && $ok)
        {
            $saved = $this->conf->getInternalWPDB()->query("
				UPDATE {$this->conf->getPrefix()}emails SET
				email_subject='{$validEmailSubject}', email_body='{$validEmailBody}'
				WHERE email_id='{$validEmailId}' AND blog_id='{$this->conf->getBlogId()}'
		   ");

            if($saved === FALSE)
            {
                $this->errorMessages[] = $this->lang->getText('NRS_EMAIL_UPDATE_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('NRS_EMAIL_UPDATED_TEXT');
            }
        }

        return $saved;
    }

    public function registerForTranslation()
    {
        $emailDetails = $this->getDetails();
        if(!is_null($emailDetails))
        {
            $this->lang->register("em{$this->emailId}_email_subject", $emailDetails['email_subject']);
            $this->lang->register("em{$this->emailId}_email_body", $emailDetails['email_body']);
            $this->okayMessages[] = $this->lang->getText('NRS_EMAIL_REGISTERED_TEXT');
        }
    }

    /**
     * @note - Emails are not allowed to be deleted at all
     * @return false
     */
    public function delete()
    {
        // Not allowed
        return FALSE;
    }


    /*******************************************************************************/
    /************************* ELEMENT SPECIFIC FUNCTIONS **************************/
    /*******************************************************************************/

    /**
     * @param $paramRecipientEmail
     * @param array $paramData
     * @param int $paramEmailWidth - email width in pixels
     * @return bool
     */
    public function sendTranslatedBookingEmail($paramRecipientEmail, $paramData = array(), $paramEmailWidth = 840)
    {
        return $this->sendBookingEmail($paramRecipientEmail, $paramData, $paramEmailWidth, TRUE);
    }

    /**
     * @param $paramRecipientEmail
     * @param array $paramData
     * @param int $paramEmailWidth - email width in pixels
     * @param bool $paramTranslated
     * @return bool
     */
    public function sendBookingEmail(
        $paramRecipientEmail, $paramData = array(), $paramEmailWidth = 840, $paramTranslated =  FALSE
    )
    {
        $validRecipientEmail = sanitize_email($paramRecipientEmail);
        $validEmailWidth = StaticValidator::getValidPositiveInteger($paramEmailWidth, 840);

        $bookingCode = isset($paramData['booking_code']) ? $paramData['booking_code']: '';
        $paramCustomerId = isset($paramData['customer_id']) ? $paramData['customer_id']: '';
        $paramCustomerName = isset($paramData['customer_name']) ? $paramData['customer_name']: '';
        $paramCustomerEmail = isset($paramData['customer_email']) ? $paramData['customer_email']: '';
        $paramLocationName = isset($paramData['location_name']) ? $paramData['location_name']: '';
        $paramLocationPhone = isset($paramData['location_phone']) ? $paramData['location_phone']: '';
        $paramLocationEmail = isset($paramData['location_email']) ? $paramData['location_email']: '';
        $paramTrustedInvoiceHTML = isset($paramData['invoice_html']) ? $paramData['invoice_html']: '';

        $emailContent = $this->getDetails(TRUE);

        $printEmailSubject = $emailContent[$paramTranslated ? 'print_translated_email_subject' : 'print_email_subject'];
        $printEmailBody = $emailContent[$paramTranslated ? 'print_translated_email_body' : 'print_email_body'];

        // 1 - Replace shortcodes in email title
        $parsedEmailSubject = $this->replaceBBCodesInEmailSubject(
            $printEmailSubject, $bookingCode, $paramCustomerName, $paramLocationName
        );

        // 2 - Replaces bb codes in email body
        $parsedEmailBody = $this->replaceBBCodesInEmailBody(
            $printEmailBody, $bookingCode, $paramCustomerId, $paramCustomerName,
            $paramLocationName, $paramLocationPhone, $paramLocationEmail, $paramTrustedInvoiceHTML
        );

        // 2 - Put email data in a div, to all center and other tags looks well.
        $parsedEmailBody = '<div style="width:'.$validEmailWidth.'px;">'.$parsedEmailBody.'</div>';

        if($this->sendEmails == TRUE)
        {
            // Send a summary email to customer
            $emailSent = $this->send(
                $paramRecipientEmail, $parsedEmailSubject, $parsedEmailBody,
                $paramCustomerEmail, $paramCustomerName
            );

            if($emailSent === FALSE)
            {
                $this->errorMessages[] = sprintf($this->lang->getText('NRS_EMAIL_SENDING_ERROR_TEXT'), $validRecipientEmail);
            } else
            {
                $this->okayMessages[] = sprintf($this->lang->getText('NRS_EMAIL_SENT_TEXT'), $validRecipientEmail);
            }
        } else
        {
            $emailSent = TRUE;
        }


        return $emailSent;
    }

    /**
     * Replace bb codes in email subject
     * @param $trustedEmailSubject
     * @param $paramBookingCode
     * @param $paramCustomerName
     * @param $paramLocationName
     * @return string
     */
    private function replaceBBCodesInEmailSubject($trustedEmailSubject, $paramBookingCode, $paramCustomerName, $paramLocationName)
    {
        $printBookingCode = esc_html(sanitize_text_field($paramBookingCode));
        $printCustomerName = esc_html(sanitize_text_field($paramCustomerName));
        $printCompanyName = esc_html(sanitize_text_field($this->companyName));
        $printLocationName = esc_html(sanitize_text_field($paramLocationName));
        $from = array(
            "[BOOKING_CODE]", "[CUSTOMER_NAME]", "[COMPANY_NAME]", "[LOCATION_NAME]",
        );
        $to = array(
            $printBookingCode, $printCustomerName, $printCompanyName, $printLocationName,
        );
        $modifiedEmailSubject = str_replace($from, $to, $trustedEmailSubject);

        return $modifiedEmailSubject;
    }

    /**
     * Replace bb codes in email body
     * @param $trustedEmailBody
     * @param $paramBookingCode
     * @param $paramCustomerId
     * @param $paramCustomerName
     * @param $paramLocationName
     * @param $paramLocationPhone
     * @param $paramLocationEmail
     * @param $trustedInvoice
     * @return string
     */
    private function replaceBBCodesInEmailBody(
        $trustedEmailBody, $paramBookingCode, $paramCustomerId, $paramCustomerName,
        $paramLocationName, $paramLocationPhone, $paramLocationEmail, $trustedInvoice
    ) {
        $printBookingCode = esc_html(sanitize_text_field($paramBookingCode));
        $validCustomerId = StaticValidator::getValidPositiveInteger($paramCustomerId, 0);
        $printCustomerName = esc_html(sanitize_text_field($paramCustomerName));
        $printCompanyName = esc_html(sanitize_text_field($this->companyName));
        $printCompanyPhone = esc_html(sanitize_text_field($this->companyPhone));
        $printCompanyEmail = esc_html(sanitize_text_field($this->companyEmail));
        $printLocationName = esc_html(sanitize_text_field($paramLocationName));
        $printLocationPhone = esc_html(sanitize_text_field($paramLocationPhone));
        $printLocationEmail = esc_html(sanitize_email($paramLocationEmail));

        // 3 - Replace Site url
        $modifiedEmailBody = str_replace('[SITE_URL]', site_url(), $trustedEmailBody);

        // 4 - Replace basic bb code
        $modifiedEmailBody = preg_replace('#\[S\](.*?)\[/S\]#si', '<strong>\1</strong>', $modifiedEmailBody);
        $modifiedEmailBody = preg_replace('#\[EM\](.*?)\[/EM\]#si', '<em>\1</em>', $modifiedEmailBody);
        $modifiedEmailBody = preg_replace('#\[CENTER\](.*?)\[/CENTER\]#si', '<center>\1</center>', $modifiedEmailBody);
        $modifiedEmailBody = preg_replace('#\[HR\]#si', '<hr />', $modifiedEmailBody);

        // 5 - Auto replace links
        $modifiedEmailBody = preg_replace_callback(
            '#(^|[\n ])([\w]+?://[ąčęėįšųūžĄČĘĖĮŠŲŪŽ\w\#$%&~/.\-;:=,?@\(?\)?\]\|+]*)#si',
            function ($matches)
            {
                return $matches[1].'<a href="'.$matches[2].'" target="_blank">'.$matches[2].'</a>';
            },
            $modifiedEmailBody
        );
        $modifiedEmailBody = preg_replace_callback(
            '#(^|[\n ])((www|ftp)\.[ąčęėįšųūžĄČĘĖĮŠŲŪŽ\w\#$%&~/.\-;:=,?@\(?\)?\]\|+]*)#si',
            function ($matches)
            {
                return $matches[1].'<a href="'.$matches[2].'" target="_blank">'.$matches[2].'</a>';
            },
            $modifiedEmailBody
        );

        // 6 - Auto replace links
        $modifiedEmailBody = preg_replace_callback(
            '#(^|[\n ])([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)#si',
            function ($matches)
            {
                return $matches[1].'<a href="mailto:'.$matches[2].'@'.$matches[3].'">'.$matches[2].'@'.$matches[3].'</a>';
            },
            $modifiedEmailBody
        );

        // 7 - Replace image shortcodes in email body - [IMG]URL[/IMG]
        $modifiedEmailBody = preg_replace_callback(
            '#\[IMG\]([\r\n]*)(http://|ftp://|https://|ftps://)([ąčęėįšųūžĄČĘĖĮŠŲŪŽ\w\#$%~/.\-;:=,&?@\(?\)?\[\]\|+]*)([\r\n]*)\[/IMG\]#si',
            function ($matches)
            {
                return '<img src="'.$matches[2].$matches[3].'" alt="'.$this->lang->getText('NRS_IMAGE_ALT_TEXT').'" />';
            },
            $modifiedEmailBody
        );
        // For more strict (image extension only) matches - $matches[2].$matches[3].$matches[4] use this code:
        // '#\[ZF\-LIKE\]([\r\n]*)(http://|ftp://|https://|ftps://)([ąčęėįšųūžĄČĘĖĮŠŲŪŽ\w\#$%~/.\-;:=,&?@\(?\)?\[\]\|+]*)(\.(jpg|jpeg|gif|png|JPG|JPEG|GIF|PNG))([\r\n]*)\[/ZF\-LIKE\]#si'

        // 9 - Replace all other shortcodes in email body
        $from = array(
            "[BOOKING_CODE]", "[CUSTOMER_ID]", "[CUSTOMER_NAME]",
            "[INVOICE]",
            "[COMPANY_NAME]", "[COMPANY_PHONE]", "[COMPANY_EMAIL]",
            "[LOCATION_NAME]", "[LOCATION_PHONE]", "[LOCATION_EMAIL]",
        );
        $to = array(
            $printBookingCode, $validCustomerId, $printCustomerName,
            $trustedInvoice,
            $printCompanyName, $printCompanyPhone, '<a href="mailto:'.$printCompanyEmail.'">'.$printCompanyEmail.'</a>',
            $printLocationName, $printLocationPhone, '<a href="mailto:'.$printLocationEmail.'">'.$printLocationEmail.'</a>',
        );
        $modifiedEmailBody = str_replace($from, $to, $modifiedEmailBody);

        return $modifiedEmailBody;
    }

    private function send($paramEmailTo, $paramEmailSubject, $paramEmailBody, $paramReplyToEmail = "", $paramReplyToFullName = "")
    {

        $emailSentSuccessfully = false;
        $validEmailTo = esc_html(sanitize_email($paramEmailTo));
        $validEmailSubject = esc_html(sanitize_text_field($paramEmailSubject));
        $validReplyToEmail = esc_html(sanitize_email($paramReplyToEmail));
        $validReplyToFullName = esc_html(sanitize_text_field($paramReplyToFullName));
        $emailHeaders = array();
        $emailHeaders[] = 'From: '.$this->companyName.' <'.$this->companyEmail.'>';
        if($validReplyToEmail != "" && $validReplyToFullName != "")
        {
            $emailHeaders[] = 'Reply-To: '.$validReplyToFullName.' <'.$validReplyToEmail.'>';
        }
        $emailHeaders[] = 'Content-Type: text/html; charset=UTF-8';

        $weTrustThatItsClearEmailBody = $paramEmailBody;
        if($validEmailTo != "")
        {
            $emailSentSuccessfully = wp_mail( $validEmailTo, $validEmailSubject, $weTrustThatItsClearEmailBody, $emailHeaders );
        }

        if($this->debugMode)
        {
            $debugMessage = "<br />Send email to: ".$validEmailTo;
            $debugMessage .= "<br />Email subject: ".$validEmailSubject;
            $debugMessage .= "<br />Reply-To full name: ".$validReplyToFullName;
            $debugMessage .= "<br />Reply-To email: ".$validReplyToEmail;
            $debugMessage .= "<br />Email headers: ".nl2br(esc_html(print_r($emailHeaders, TRUE)));
            $debugMessage .= "<br />Email sent successfully: ".var_export($emailSentSuccessfully, TRUE);
            $this->debugMessages[] = $debugMessage;
            echo $debugMessage;
        }

        return $emailSentSuccessfully;
    }
}