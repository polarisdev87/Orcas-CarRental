<?php
/**
 * NRS API controller class to handle all API/payment callback requests
 * Final class cannot be inherited anymore. We use them when creating new instances
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Front;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Customer\Customer;
use NativeRentalSystem\Models\Customer\CustomersObserver;
use NativeRentalSystem\Models\Logging\Log;
use NativeRentalSystem\Models\Logging\LogsObserver;
use NativeRentalSystem\Models\Settings\SettingsObserver;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\PaymentMethod\PaymentMethod;

final class APIController
{
    private $conf 	                = NULL;
    private $lang 		            = NULL;
    private $debugMode 	            = 0;

    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    /**
     * @note - We use $_REQUEST here to support both - jQuery.get and jQuery.post AJAX
     * This is where we handle incoming api requests
     * @param $paramAction
     */
    public function handleAPIRequest($paramAction)
    {
        $objSettings = new SettingsObserver($this->conf, $this->lang);
        $objSettings->setSettings();
        $objLogsObserver = new LogsObserver($this->conf, $this->lang, $objSettings->getSettings());
        $objLog = new Log($this->conf, $this->lang, $objSettings->getSettings(), 0);

        switch($paramAction)
        {
            case "payment-callback":
                // For payment callback we DO NOT use _nonce
                $paramPaymentMethodId = isset($_REQUEST['payment_method_id']) ? $_REQUEST['payment_method_id'] : "";
                $objPaymentMethod = new PaymentMethod($this->conf, $this->lang, $objSettings->getSettings(), $paramPaymentMethodId);

                // Process
                $paymentMethodDetails = $objPaymentMethod->getDetails();
                if(!is_null($paymentMethodDetails))
                {
                    $paymentFolderName = $paymentMethodDetails['class_name'];
                    $paymentClassName = $paymentMethodDetails['class_name'];
                    $paymentFolderPathWithFileName = $this->conf->getLibrariesPath().$paymentFolderName.DIRECTORY_SEPARATOR.$paymentClassName.'.php';
                    if ($paymentClassName != "" && is_readable($paymentFolderPathWithFileName))
                    {
                        require_once $paymentFolderPathWithFileName;
                        if(class_exists($paymentClassName))
                        {
                            $objPayment = new $paymentClassName($this->conf, $this->lang, $objSettings->getSettings(), $paramPaymentMethodId);
                            // This is ok that the functions are not found
                            if(method_exists($objPayment, 'processAPI'))
                            {
                                $objPayment->processAPI();
                            }
                        }
                    }
                }
                break;

            case "customer-lookup":
                // For customer lookup ONLY we use _nonce
                // Check if the call is coming from right place. 'ajax_security' here is a _GET parameter to check encrypted nonce
                // Note - dies on failure
                check_ajax_referer($this->conf->getURLPrefix().'frontend-ajax-nonce', 'ajax_security');

                $paramEmail = isset($_REQUEST['email']) ? $_REQUEST['email'] : "";
                $paramYearOfBirth = isset($_REQUEST['year']) ? $_REQUEST['year'] : "0000";
                $requireYearOfBirth = $objSettings->getCustomerFieldStatus("birthdate", "REQUIRED") ? TRUE : FALSE;
                $objCustomersObserver = new CustomersObserver($this->conf, $this->lang, $objSettings->getSettings());
                $customerId = $objCustomersObserver->getIdByEmail($paramEmail);
                $objCustomer = new Customer($this->conf, $this->lang, $objSettings->getSettings(), $customerId);
                $customerDetails = $objCustomer->getDetails();

                // Debug
                //die("EMAIL: ".$paramEmail.", YEAR: ".$paramYearOfBirth.", CUSTOMER ID: ".$customerId);

                if (!is_null($customerDetails) && (!$requireYearOfBirth || ($requireYearOfBirth && $customerDetails['birth_year'] == $paramYearOfBirth)))
                {
                    $JSONParams = array(
                        "error" => 0,
                        "message" => $this->lang->getText('NRS_ERROR_CUSTOMER_DETAILS_NO_ERROR_TEXT'),
                        "title" => $objCustomer->getTitleDropDownOptions($customerDetails['title'], "", $this->lang->getText('NRS_SELECT_DROPDOWN_TEXT')),
                        "first_name" => $customerDetails['edit_first_name'],
                        "last_name" => $customerDetails['edit_last_name'],
                        "birth_year" => $customerDetails['birth_year'],
                        "birth_month" => $customerDetails['birth_month'],
                        "birth_day" => $customerDetails['birth_day'],
                        "street_address" => $customerDetails['edit_street_address'],
                        "city" => $customerDetails['edit_city'],
                        "state" => $customerDetails['edit_state'],
                        "zip_code" => $customerDetails['edit_zip_code'],
                        "country" => $customerDetails['edit_country'],
                        "phone" => $customerDetails['edit_phone'],
                        "email" => $customerDetails['edit_email'],
                        "comments" => $customerDetails['edit_comments'],
                    );
                } else
                {
                    // NOTE: For better security to protect from email database attacks, we don't want to disclose that the year is not valid.
                    // That's why we just give default message
                    $JSONParams = array(
                        "error" => 1,
                        "message" => $this->lang->getText('NRS_ERROR_CUSTOMER_DETAILS_NOT_FOUND_TEXT'),
                    );
                }

                $isFailed = $JSONParams['error'] == 0 ? FALSE : TRUE;
                $totalRequestsLeft = $objLog->getTotalRequestsLeft();
                $failedRequestsLeft = $objLog->getFailedRequestsLeft();
                $emailAttemptsLeft = $objLog->getFailedEmailAttemptsLeft($paramEmail);

                if($totalRequestsLeft == 0 || $failedRequestsLeft == 0 || $emailAttemptsLeft == 0)
                {
                    // Override default return
                    $JSONParams = array(
                        "error" => 1,
                        "message" => $this->lang->getText('NRS_ERROR_EXCEEDED_CUSTOMER_LOOKUP_ATTEMPTS_TEXT'),
                    );
                }
                // First - delete expired logs
                $objLogsObserver->deleteExpiredCustomerLookupLogs();

                // Save log
                $objLog->save('customer-lookup', $paramEmail, $paramYearOfBirth, $requireYearOfBirth, $isFailed, '', '');
                echo json_encode($JSONParams);
                break;

            default:
                $JSONParams = array(
                    "error" => 99,
                    "message" => $this->lang->getText('NRS_ERROR_CUSTOMER_DETAILS_UNKNOWN_ERROR_TEXT'),
                );
                echo json_encode($JSONParams);
        }
    }
}