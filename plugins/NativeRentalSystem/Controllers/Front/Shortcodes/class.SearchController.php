<?php
/**
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Front\Shortcodes;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Controllers\Front\Booking\CancelBookingController;
use NativeRentalSystem\Controllers\Front\Booking\EditBookingController;
use NativeRentalSystem\Controllers\Front\Booking\Step1ItemSearchController;
use NativeRentalSystem\Controllers\Front\Booking\Step2SearchResultsController;
use NativeRentalSystem\Controllers\Front\Booking\Step3BookingOptionsController;
use NativeRentalSystem\Controllers\Front\Booking\Step4BookingDetailsController;
use NativeRentalSystem\Controllers\Front\Booking\Step5BookingProcessController;

final class SearchController
{
    private $conf       = NULL;
    private $lang 	    = NULL;

    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;
    }

    private function destroySession()
    {
        // NOTE: Maybe this->objSearch->isValidSearch() check is needed here (previously used)
        // If it's desired to kill the session, also delete the session cookie.
        // Note: This will destroy the session, and not just the session data!
        // This has to be moved out of the code before headers
        /*if (ini_get('session.use_cookies'))
        {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }*/

        // Finally, destroy the session
        // TODO: At least in local host the session destroy bellow sometimes (not always) produces this error:
        // Warning: session_destroy(): Session object destruction failed in <..>class.Step5BookingProcessController.php on line 215
        // Not sure why it does that, because booking appears to work well.
        session_destroy();
    }

    public function getNewContent($paramStepsLayoutArray = array("Form", "List", "List", "Table", "Table"), $paramArrLimitations = array())
    {
        $retContent = '';

        $step1Layout = isset($paramStepsLayoutArray[0]) ? $paramStepsLayoutArray[0] : "Form";
        $step2Layout = isset($paramStepsLayoutArray[1]) ? $paramStepsLayoutArray[1] : "List";
        $step3Layout = isset($paramStepsLayoutArray[2]) ? $paramStepsLayoutArray[2] : "List";
        $step4Layout = isset($paramStepsLayoutArray[3]) ? $paramStepsLayoutArray[3] : "Table";
        $step5Layout = isset($paramStepsLayoutArray[4]) ? $paramStepsLayoutArray[4] : "Table";

        // Separate steps 1 to 8
        if(
            !isset($_REQUEST[$this->conf->getExtensionPrefix().'do_search0']) && !isset($_REQUEST[$this->conf->getExtensionPrefix().'do_search']) && !isset($_REQUEST[$this->conf->getExtensionPrefix().'do_search2'])
            && !isset($_REQUEST[$this->conf->getExtensionPrefix().'do_search3']) && !isset($_REQUEST[$this->conf->getExtensionPrefix().'do_search4'])
        ) {
            // If no requests are passed - start from search form
            // Booking step no. 1
            $objSearchController = new Step1ItemSearchController($this->conf, $this->lang, $paramArrLimitations);
            $retContent = $objSearchController->getNewContent($step1Layout);
        } else if(isset($_REQUEST[$this->conf->getExtensionPrefix().'do_search0']))
        {
            // If there is a call back to step 1 from step 2 / 3 / 4
            // Booking step no. 1
            $objSearchController = new Step1ItemSearchController($this->conf, $this->lang, $paramArrLimitations);
            $retContent = $objSearchController->getEditContent($step1Layout);
        } else if(isset($_REQUEST[$this->conf->getExtensionPrefix().'do_search']) && (
                !isset($_POST['booking_code']) || isset($_POST['booking_code'])
                && (strlen($_POST['booking_code']) == 0 || $_POST['booking_code'] == $this->lang->getText('NRS_I_HAVE_BOOKING_CODE_TEXT'))
            )
        ) {
            // Regular call from step 1 to step 2
            // Booking step no. 2
            $objSearchController = new Step2SearchResultsController($this->conf, $this->lang, $paramArrLimitations);
            $retContent = $objSearchController->getContent($step2Layout);
        } else if(isset($_REQUEST[$this->conf->getExtensionPrefix().'do_search']) && isset($_POST['booking_code']) && strlen($_POST['booking_code']) > 0)
        {
            // Call from step 1 with booking code entered to step 3
            // Booking step no. 3
            $objSearchController = new Step3BookingOptionsController($this->conf, $this->lang, $paramArrLimitations);
            $retContent = $objSearchController->getContent($step3Layout);
        } else if(isset($_REQUEST[$this->conf->getExtensionPrefix().'do_search2']))
        {
            // Call from step 1b to step 3
            // Booking step no. 3
            $objSearchController = new Step3BookingOptionsController($this->conf, $this->lang, $paramArrLimitations);
            $retContent = $objSearchController->getContent($step3Layout);
        } else if(isset($_REQUEST[$this->conf->getExtensionPrefix().'do_search3']))
        {
            // Booking step no. 4
            $objSearchController = new Step4BookingDetailsController($this->conf, $this->lang, $paramArrLimitations);
            $retContent = $objSearchController->getContent($step4Layout);
        } else if(isset($_REQUEST[$this->conf->getExtensionPrefix().'do_search4']))
        {
            // Booking step no. 5
            $objSearchController = new Step5BookingProcessController($this->conf, $this->lang, $paramArrLimitations);
            $retContent = $objSearchController->getContent($step5Layout);
            $this->destroySession();
        } else if(isset($_REQUEST[$this->conf->getExtensionPrefix().'cancel_booking']))
        {
            // Cancel Booking
            $objCancelBookingController = new CancelBookingController($this->conf, $this->lang, $paramArrLimitations);
            $retContent = $objCancelBookingController->getContent();
        }

        return $retContent;
    }

    public function getEditContent($paramStepsLayoutArray = array("Form", "List", "List", "Table"), $paramArrLimitations = array())
    {
        $step1Layout = isset($paramStepsLayoutArray[0]) ? $paramStepsLayoutArray[0] : "Form";
        $step2Layout = isset($paramStepsLayoutArray[1]) ? $paramStepsLayoutArray[1] : "List";
        $step3Layout = isset($paramStepsLayoutArray[2]) ? $paramStepsLayoutArray[2] : "List";
        $step4Layout = isset($paramStepsLayoutArray[3]) ? $paramStepsLayoutArray[3] : "Table";
        $step5Layout = isset($paramStepsLayoutArray[4]) ? $paramStepsLayoutArray[4] : "Table";

        // Separate steps 1 to 8
        if(isset($_REQUEST[$this->conf->getExtensionPrefix().'edit_booking']))
        {
            // Call from edit booking to step 3
            // Booking step no. 3
            $objSearchController = new Step3BookingOptionsController($this->conf, $this->lang, $paramArrLimitations);
            $retContent = $objSearchController->getContent($step3Layout);
        } else if(isset($_REQUEST[$this->conf->getExtensionPrefix().'do_search0']))
        {
            // If there is a call back to step 1 from step 2 / 3 / 4
            // Booking step no. 1
            $objSearchController = new Step1ItemSearchController($this->conf, $this->lang, $paramArrLimitations);
            $retContent = $objSearchController->getEditContent($step1Layout);
        } else if(isset($_REQUEST[$this->conf->getExtensionPrefix().'do_search']))
        {
            // Regular call from step 1 to step 2
            // Booking step no. 2
            $objSearchController = new Step2SearchResultsController($this->conf, $this->lang, $paramArrLimitations);
            $retContent = $objSearchController->getContent($step2Layout);
        } else if(isset($_REQUEST[$this->conf->getExtensionPrefix().'do_search2']))
        {
            // Call from edit booking to step 3
            // Booking step no. 3
            $objSearchController = new Step3BookingOptionsController($this->conf, $this->lang, $paramArrLimitations);
            $retContent = $objSearchController->getContent($step3Layout);
        } else if(isset($_REQUEST[$this->conf->getExtensionPrefix().'do_search3']))
        {
            // Booking step no. 4
            $objSearchController = new Step4BookingDetailsController($this->conf, $this->lang, $paramArrLimitations);
            $retContent = $objSearchController->getContent($step4Layout);
        } else if(isset($_REQUEST[$this->conf->getExtensionPrefix().'do_search4']))
        {
            // Booking step no. 5
            $objSearchController = new Step5BookingProcessController($this->conf, $this->lang, $paramArrLimitations);
            $retContent = $objSearchController->getContent($step5Layout);
            $this->destroySession();
        } else if(isset($_REQUEST[$this->conf->getExtensionPrefix().'cancel_booking']))
        {
            // Cancel Booking
            $objCancelBookingController = new CancelBookingController($this->conf, $this->lang, $paramArrLimitations);
            $retContent = $objCancelBookingController->getContent();
        } else
        {
            // Edit Booking
            $objEditBookingController = new EditBookingController($this->conf, $this->lang, $paramArrLimitations);
            $retContent = $objEditBookingController->getContent($step1Layout);
        }

        return $retContent;
    }
}