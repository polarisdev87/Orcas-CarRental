<?php
/**
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Admin\Location;
use NativeRentalSystem\Models\Booking\Booking;
use NativeRentalSystem\Models\Booking\Invoice;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Formatting\StaticFormatter;
use NativeRentalSystem\Models\Location\LocationsObserver;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Booking\BookingsObserver;
use NativeRentalSystem\Models\Location\Location;
use NativeRentalSystem\Controllers\Admin\AbstractController;
use NativeRentalSystem\Models\EMail\EMailsObserver;

final class AddEditLocationController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    private function processDelete($paramLocationId)
    {
        $objLocation = new Location($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramLocationId);
        $objBookingsObserver = new BookingsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objLocation->delete();
        $bookingIds = $objBookingsObserver->getUpcomingIdsByLocationId($paramLocationId);

        foreach($bookingIds AS $bookingId)
        {
            $objEMailsObserver = new EMailsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
            $objBooking = new Booking($this->conf, $this->lang, $this->dbSettings->getSettings(), $bookingId);
            $objInvoice = new Invoice($this->conf, $this->lang, $this->dbSettings->getSettings(), $bookingId);
            if($objBooking->isCancelled() === FALSE)
            {
                // First - cancel
                // And send e-mails to disappointed customers if needed
                $objBooking->cancel();
                $objEMailsObserver->sendBookingCancellationEmail($objBooking->getId(), FALSE);
            }
            $objBooking->delete();
            $objBooking->deleteAllOptions();
            $objInvoice->delete();
        }

        $this->processDebugMessages($objLocation->getDebugMessages());
        $this->processOkayMessages($objLocation->getOkayMessages());
        $this->processErrorMessages($objLocation->getErrorMessages());

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'location-manager&tab=locations');
        exit;
    }

    private function processSave($paramLocationId)
    {
        $objBookingsObserver = new BookingsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objLocation = new Location($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramLocationId);
        $oldCode = $objLocation->getCode();
        $saved = $objLocation->save();
        $newCode = $objLocation->getCode();
        if($paramLocationId > 0 && $saved && $oldCode != '' && $newCode != $oldCode)
        {
            $objBookingsObserver->changeLocationCode($oldCode, $newCode);
        }
        if($saved && $this->lang->canTranslateSQL())
        {
            $objLocation->registerForTranslation();
        }

        $this->processDebugMessages($objLocation->getDebugMessages());
        $this->processOkayMessages($objLocation->getOkayMessages());
        $this->processErrorMessages($objLocation->getErrorMessages());

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'location-manager&tab=locations');
        exit;
    }

    public function getContent()
    {
        // Create mandatory instances
        $objLocationsObserver = new LocationsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());

        if(isset($_GET['delete_location'])) { $this->processDelete($_GET['delete_location']); }
        if(isset($_POST['save_location']) && isset($_POST['location_id'])) { $this->processSave($_POST['location_id']); }

        $paramLocationId = isset($_GET['location_id']) ? $_GET['location_id'] : "";
        $objLocation = new Location($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramLocationId);
        $localDetails = $objLocation->getDetails();

        // Set the view variables
        $this->view->backToListURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'location-manager&tab=locations');
        $this->view->formAction = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-location&noheader=true');
        if(!is_null($localDetails))
        {
            $this->view->locationId = $localDetails['location_id'];
            $this->view->locationCode = $localDetails['edit_location_code'];
            $this->view->locationName = $localDetails['edit_location_name'];
            $this->view->locationPagesDropDown = $objLocationsObserver->getPagesDropDown($localDetails['location_page_id'], "location_page_id", "location_page_id");
            $this->view->streetAddress = $localDetails['edit_street_address'];
            $this->view->city = $localDetails['edit_city'];
            $this->view->state = $localDetails['edit_state'];
            $this->view->zipCode = $localDetails['edit_zip_code'];
            $this->view->country = $localDetails['edit_country'];
            $this->view->phone = $localDetails['edit_phone'];
            $this->view->email = $localDetails['edit_email'];
            $this->view->pickupFee = $localDetails['pickup_fee'];
            $this->view->returnFee = $localDetails['return_fee'];

            $this->view->openMondays = $localDetails['open_mondays'] == 1 ? ' checked="checked"' : '';
            $this->view->openTuesdays = $localDetails['open_tuesdays'] == 1 ? ' checked="checked"' : '';
            $this->view->openWednesdays = $localDetails['open_wednesdays'] == 1 ? ' checked="checked"' : '';
            $this->view->openThursdays = $localDetails['open_thursdays'] == 1 ? ' checked="checked"' : '';
            $this->view->openFridays = $localDetails['open_fridays'] == 1 ? ' checked="checked"' : '';
            $this->view->openSaturdays = $localDetails['open_saturdays'] == 1 ? ' checked="checked"' : '';
            $this->view->openSundays = $localDetails['open_sundays'] == 1 ? ' checked="checked"' : '';

            $this->view->openTimeMondaysDropDownOptions = StaticFormatter::getTimeDropDownOptions($localDetails['open_time_mon'], "00:00:00", "23:59:59", $this->lang->getText('NRS_MIDNIGHT_TEXT'), $this->lang->getText('NRS_NOON_TEXT'));
            $this->view->openTimeTuesdaysDropDownOptions = StaticFormatter::getTimeDropDownOptions($localDetails['open_time_tue'], "00:00:00", "23:59:59", $this->lang->getText('NRS_MIDNIGHT_TEXT'), $this->lang->getText('NRS_NOON_TEXT'));
            $this->view->openTimeWednesdaysDropDownOptions = StaticFormatter::getTimeDropDownOptions($localDetails['open_time_wed'], "00:00:00", "23:59:59", $this->lang->getText('NRS_MIDNIGHT_TEXT'), $this->lang->getText('NRS_NOON_TEXT'));
            $this->view->openTimeThursdaysDropDownOptions = StaticFormatter::getTimeDropDownOptions($localDetails['open_time_thu'], "00:00:00", "23:59:59", $this->lang->getText('NRS_MIDNIGHT_TEXT'), $this->lang->getText('NRS_NOON_TEXT'));
            $this->view->openTimeFridaysDropDownOptions = StaticFormatter::getTimeDropDownOptions($localDetails['open_time_fri'], "00:00:00", "23:59:59", $this->lang->getText('NRS_MIDNIGHT_TEXT'), $this->lang->getText('NRS_NOON_TEXT'));
            $this->view->openTimeSaturdaysDropDownOptions = StaticFormatter::getTimeDropDownOptions($localDetails['open_time_sat'], "00:00:00", "23:59:59", $this->lang->getText('NRS_MIDNIGHT_TEXT'), $this->lang->getText('NRS_NOON_TEXT'));
            $this->view->openTimeSundaysDropDownOptions = StaticFormatter::getTimeDropDownOptions($localDetails['open_time_sun'], "00:00:00", "23:59:59", $this->lang->getText('NRS_MIDNIGHT_TEXT'), $this->lang->getText('NRS_NOON_TEXT'));

            $this->view->closeTimeMondaysDropDownOptions = StaticFormatter::getTimeDropDownOptions($localDetails['close_time_mon'], "00:00:00", "23:59:59", $this->lang->getText('NRS_MIDNIGHT_TEXT'), $this->lang->getText('NRS_NOON_TEXT'));
            $this->view->closeTimeTuesdaysDropDownOptions = StaticFormatter::getTimeDropDownOptions($localDetails['close_time_tue'], "00:00:00", "23:59:59", $this->lang->getText('NRS_MIDNIGHT_TEXT'), $this->lang->getText('NRS_NOON_TEXT'));
            $this->view->closeTimeWednesdaysDropDownOptions = StaticFormatter::getTimeDropDownOptions($localDetails['close_time_wed'], "00:00:00", "23:59:59", $this->lang->getText('NRS_MIDNIGHT_TEXT'), $this->lang->getText('NRS_NOON_TEXT'));
            $this->view->closeTimeThursdaysDropDownOptions = StaticFormatter::getTimeDropDownOptions($localDetails['close_time_thu'], "00:00:00", "23:59:59", $this->lang->getText('NRS_MIDNIGHT_TEXT'), $this->lang->getText('NRS_NOON_TEXT'));
            $this->view->closeTimeFridaysDropDownOptions = StaticFormatter::getTimeDropDownOptions($localDetails['close_time_fri'], "00:00:00", "23:59:59", $this->lang->getText('NRS_MIDNIGHT_TEXT'), $this->lang->getText('NRS_NOON_TEXT'));
            $this->view->closeTimeSaturdaysDropDownOptions = StaticFormatter::getTimeDropDownOptions($localDetails['close_time_sat'], "00:00:00", "23:59:59", $this->lang->getText('NRS_MIDNIGHT_TEXT'), $this->lang->getText('NRS_NOON_TEXT'));
            $this->view->closeTimeSundaysDropDownOptions = StaticFormatter::getTimeDropDownOptions($localDetails['close_time_sun'], "00:00:00", "23:59:59", $this->lang->getText('NRS_MIDNIGHT_TEXT'), $this->lang->getText('NRS_NOON_TEXT'));

            $this->view->lunchEnabled = $localDetails['lunch_enabled'] == 1 ? ' checked="checked"' : '';
            $this->view->lunchStartTimeDropDownOptions = StaticFormatter::getTimeDropDownOptions($localDetails['lunch_start_time'], "00:00:00", "23:59:59", $this->lang->getText('NRS_MIDNIGHT_TEXT'), $this->lang->getText('NRS_NOON_TEXT'));
            $this->view->lunchEndTimeDropDownOptions = StaticFormatter::getTimeDropDownOptions($localDetails['lunch_end_time'], "00:00:00", "23:59:59", $this->lang->getText('NRS_MIDNIGHT_TEXT'), $this->lang->getText('NRS_NOON_TEXT'));

            $this->view->locationImage1 = $localDetails['location_image_1'];
            $this->view->locationImage2 = $localDetails['location_image_2'];
            $this->view->locationImage3 = $localDetails['location_image_3'];
            $this->view->locationImage4 = $localDetails['location_image_4'];
            $this->view->demoLocationImage1 = $localDetails['demo_location_image_1'];
            $this->view->demoLocationImage2 = $localDetails['demo_location_image_2'];
            $this->view->demoLocationImage3 = $localDetails['demo_location_image_3'];
            $this->view->demoLocationImage4 = $localDetails['demo_location_image_4'];

            $this->view->afterHoursPickupAllowedChecked = $localDetails['afterhours_pickup_allowed'] == 1 ? ' checked="checked"' : '';
            $this->view->afterHoursPickupDropDownOptions = $objLocationsObserver->getTranslatedLocationsDropDownOptions(
                "BOTH", 0, $localDetails['afterhours_pickup_location_id'], 0, $this->lang->getText('NRS_IN_THIS_LOCATION_TEXT'), $localDetails['location_id']
            );
            $this->view->afterHoursPickupFee = $localDetails['afterhours_pickup_fee'];

            $this->view->afterHoursReturnAllowedChecked = $localDetails['afterhours_return_allowed'] == 1 ? ' checked="checked"' : '';
            $this->view->afterHoursReturnDropDownOptions = $objLocationsObserver->getTranslatedLocationsDropDownOptions(
                "BOTH", 0, $localDetails['afterhours_return_location_id'], 0, $this->lang->getText('NRS_IN_THIS_LOCATION_TEXT'), $localDetails['location_id']
            );
            $this->view->afterHoursReturnFee = $localDetails['afterhours_return_fee'];
            $this->view->locationOrder = $localDetails['location_order'];
        } else
        {
            // Set the view variables
            $this->view->locationId = 0;
            $this->view->locationCode = $objLocation->generateCode(); // Generate new code
            $this->view->locationName = '';
            $this->view->locationPagesDropDown = '';
            $this->view->streetAddress = '';
            $this->view->city = '';
            $this->view->state = '';
            $this->view->zipCode = '';
            $this->view->country = '';
            $this->view->phone = '';
            $this->view->email = '';
            $this->view->pickupFee = '0.00';
            $this->view->returnFee = '0.00';

            $this->view->openMondays = ' checked="checked"';
            $this->view->openTuesdays = ' checked="checked"';
            $this->view->openWednesdays = ' checked="checked"';
            $this->view->openThursdays = ' checked="checked"';
            $this->view->openFridays = ' checked="checked"';
            $this->view->openSaturdays = ' checked="checked"';
            $this->view->openSundays = ' checked="checked"';

            $this->view->openTimeMondaysDropDownOptions = StaticFormatter::getTimeDropDownOptions('08:00:00', "00:00:00", "23:59:59", $this->lang->getText('NRS_MIDNIGHT_TEXT'), $this->lang->getText('NRS_NOON_TEXT'));
            $this->view->openTimeTuesdaysDropDownOptions = StaticFormatter::getTimeDropDownOptions('08:00:00', "00:00:00", "23:59:59", $this->lang->getText('NRS_MIDNIGHT_TEXT'), $this->lang->getText('NRS_NOON_TEXT'));
            $this->view->openTimeWednesdaysDropDownOptions = StaticFormatter::getTimeDropDownOptions('08:00:00', "00:00:00", "23:59:59", $this->lang->getText('NRS_MIDNIGHT_TEXT'), $this->lang->getText('NRS_NOON_TEXT'));
            $this->view->openTimeThursdaysDropDownOptions = StaticFormatter::getTimeDropDownOptions('08:00:00', "00:00:00", "23:59:59", $this->lang->getText('NRS_MIDNIGHT_TEXT'), $this->lang->getText('NRS_NOON_TEXT'));
            $this->view->openTimeFridaysDropDownOptions = StaticFormatter::getTimeDropDownOptions('08:00:00', "00:00:00", "23:59:59", $this->lang->getText('NRS_MIDNIGHT_TEXT'), $this->lang->getText('NRS_NOON_TEXT'));
            $this->view->openTimeSaturdaysDropDownOptions = StaticFormatter::getTimeDropDownOptions('08:00:00', "00:00:00", "23:59:59", $this->lang->getText('NRS_MIDNIGHT_TEXT'), $this->lang->getText('NRS_NOON_TEXT'));
            $this->view->openTimeSundaysDropDownOptions = StaticFormatter::getTimeDropDownOptions('08:00:00', "00:00:00", "23:59:59", $this->lang->getText('NRS_MIDNIGHT_TEXT'), $this->lang->getText('NRS_NOON_TEXT'));

            $this->view->closeTimeMondaysDropDownOptions = StaticFormatter::getTimeDropDownOptions('19:00:00', "00:00:00", "23:59:59", $this->lang->getText('NRS_MIDNIGHT_TEXT'), $this->lang->getText('NRS_NOON_TEXT'));
            $this->view->closeTimeTuesdaysDropDownOptions = StaticFormatter::getTimeDropDownOptions('19:00:00', "00:00:00", "23:59:59", $this->lang->getText('NRS_MIDNIGHT_TEXT'), $this->lang->getText('NRS_NOON_TEXT'));
            $this->view->closeTimeWednesdaysDropDownOptions = StaticFormatter::getTimeDropDownOptions('19:00:00', "00:00:00", "23:59:59", $this->lang->getText('NRS_MIDNIGHT_TEXT'), $this->lang->getText('NRS_NOON_TEXT'));
            $this->view->closeTimeThursdaysDropDownOptions = StaticFormatter::getTimeDropDownOptions('19:00:00', "00:00:00", "23:59:59", $this->lang->getText('NRS_MIDNIGHT_TEXT'), $this->lang->getText('NRS_NOON_TEXT'));
            $this->view->closeTimeFridaysDropDownOptions = StaticFormatter::getTimeDropDownOptions('19:00:00', "00:00:00", "23:59:59", $this->lang->getText('NRS_MIDNIGHT_TEXT'), $this->lang->getText('NRS_NOON_TEXT'));
            $this->view->closeTimeSaturdaysDropDownOptions = StaticFormatter::getTimeDropDownOptions('19:00:00', "00:00:00", "23:59:59", $this->lang->getText('NRS_MIDNIGHT_TEXT'), $this->lang->getText('NRS_NOON_TEXT'));
            $this->view->closeTimeSundaysDropDownOptions = StaticFormatter::getTimeDropDownOptions('19:00:00', "00:00:00", "23:59:59", $this->lang->getText('NRS_MIDNIGHT_TEXT'), $this->lang->getText('NRS_NOON_TEXT'));

            $this->view->lunchEnabled = '';
            $this->view->lunchStartTimeDropDownOptions = StaticFormatter::getTimeDropDownOptions('12:00:00', "00:00:00", "23:59:59", $this->lang->getText('NRS_MIDNIGHT_TEXT'), $this->lang->getText('NRS_NOON_TEXT'));
            $this->view->lunchEndTimeDropDownOptions = StaticFormatter::getTimeDropDownOptions('13:00:00', "00:00:00", "23:59:59", $this->lang->getText('NRS_MIDNIGHT_TEXT'), $this->lang->getText('NRS_NOON_TEXT'));

            $this->view->locationImage1 = '';
            $this->view->locationImage2 = '';
            $this->view->locationImage3 = '';
            $this->view->locationImage4 = '';
            $this->view->demoLocationImage1 = 0;
            $this->view->demoLocationImage2 = 0;
            $this->view->demoLocationImage3 = 0;
            $this->view->demoLocationImage4 = 0;

            $this->view->afterHoursPickupAllowedChecked = '';
            $this->view->afterHoursPickupDropDownOptions = $objLocationsObserver->getTranslatedLocationsDropDownOptions(
                "BOTH", 0, 0, 0, $this->lang->getText('NRS_IN_THIS_LOCATION_TEXT'), 0
            );
            $this->view->afterHoursPickupFee = '';

            $this->view->afterHoursReturnAllowedChecked = '';
            $this->view->afterHoursReturnDropDownOptions = $objLocationsObserver->getTranslatedLocationsDropDownOptions(
                "BOTH", 0, 0, 0, $this->lang->getText('NRS_IN_THIS_LOCATION_TEXT'), 0
            );
            $this->view->afterHoursReturnFee = '';
            $this->view->locationOrder = '';
        }

        // Get the template
        $retContent = $this->getTemplate('Location', 'AddEditLocation', 'Form');

        return $retContent;
    }
}
