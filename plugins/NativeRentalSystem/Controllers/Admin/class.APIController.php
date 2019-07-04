<?php
/**
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Admin;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Location\ClosedDatesObserver;
use NativeRentalSystem\Models\Location\Location;
use NativeRentalSystem\Models\EMail\EMail;
use NativeRentalSystem\Models\Pricing\PricePlansObserver;
use NativeRentalSystem\Models\Tax\TaxManager;
use NativeRentalSystem\Models\Settings\SettingsObserver;

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
     * @param $paramExtensionAction
     */
    public function handleAPIRequest($paramExtensionAction)
    {
        // For any admin ajax we use nonce check
        // Check if the call is coming from right place. 'ajax_security' here is a _POST parameter to check encrypted nonce
        // Note - dies on failure
        check_ajax_referer($this->conf->getURLPrefix().'admin-ajax-nonce', 'ajax_security');

        $objSettings = new SettingsObserver($this->conf, $this->lang);
        $objSettings->setSettings();

        switch($paramExtensionAction)
        {
            case "email":
                $paramEmailId = isset($_REQUEST['email_id']) ? $_REQUEST['email_id'] : 0;
                $objEMail = new EMail($this->conf, $this->lang, $objSettings->getSettings(), $paramEmailId);
                $emailContent = $objEMail->getDetails();

                if(!is_null($emailContent))
                {
                    $JSONParams = array(
                        "error" => 0,
                        "message" => "OK",
                        "email_id" => $emailContent['email_id'],
                        "email_subject" => $emailContent['email_subject'],
                        "email_body" => $emailContent['email_body'],
                    );
                } else
                {
                    $JSONParams = array(
                        "error" => 1,
                        "message" => $this->lang->getText('NRS_ADMIN_AJAX_EMAIL_DOES_NOT_EXIST_ERROR_TEXT'),
                    );
                }
                echo json_encode($JSONParams);
                break;

            case "closed-dates":
                $objLocationsObserver = new ClosedDatesObserver($this->conf, $this->lang, $objSettings->getSettings());

                $paramLocationId = isset($_REQUEST['location_id']) ? $_REQUEST['location_id'] : -1;
                $paramSelectedDates = isset($_REQUEST['selected_dates']) ? $_REQUEST['selected_dates'] : "";
                $objLocation = new Location($this->conf, $this->lang, $objSettings->getSettings(), $paramLocationId);
                if(current_user_can('manage_'.$this->conf->getExtensionPrefix().'all_locations'))
                {
                    $objLocationsObserver->saveClosedDates($objLocation->getCode(), $paramSelectedDates);
                    $JSONParams = array(
                        "error" => 0,
                        "message" => "OK"
                    );
                } else
                {
                    $JSONParams = array(
                        "error" => 1,
                        "message" => $this->lang->getText('NRS_ADMIN_AJAX_CLOSE_DATE_ACCESS_ERROR_TEXT'),
                    );
                }

                echo json_encode($JSONParams);
                break;

            case "price-plans":
                $objTaxManager = new TaxManager($this->conf, $this->lang, $objSettings->getSettings());
                $taxPercentage = $objTaxManager->getTaxPercentage(0, 0);
                $paramPriceGroupId = isset($_REQUEST['price_group_id']) ? $_REQUEST['price_group_id'] : 0;
                $objPricePlansObserver = new PricePlansObserver($this->conf, $this->lang, $objSettings->getSettings());
                $pricePlansHTML = $objPricePlansObserver->getAdminList($paramPriceGroupId, $taxPercentage);

                if($pricePlansHTML != '')
                {
                    $JSONParams = array(
                        "error" => 0,
                        "message" => $pricePlansHTML,
                    );
                } else
                {
                    $JSONParams = array(
                        "error" => 1,
                        "message" => $this->lang->getText('NRS_ADMIN_AJAX_PRICE_PLAN_DOES_NOT_EXIST_ERROR_TEXT'),
                    );
                }
                echo json_encode($JSONParams);
                break;

            default:
                $JSONParams = array(
                    "error" => 99,
                    "message" => $this->lang->getText('NRS_ADMIN_AJAX_UNKNOWN_ERROR_TEXT'),
                );
                echo json_encode($JSONParams);
        }
    }
}

