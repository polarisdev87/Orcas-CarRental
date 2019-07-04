<?php
/**
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Admin\Booking;
use NativeRentalSystem\Controllers\Admin\AbstractController;
use NativeRentalSystem\Models\Booking\Booking;
use NativeRentalSystem\Models\Booking\BookingsObserver;
use NativeRentalSystem\Models\Booking\Invoice;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\EMail\EMailsObserver;
use NativeRentalSystem\Models\Formatting\StaticFormatter;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Customer\Customer;
use NativeRentalSystem\Models\Validation\StaticValidator;

final class AddEditCustomerController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    private function processDelete($paramCustomerId)
    {
        $objBookingsObserver = new BookingsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objCustomer = new Customer($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramCustomerId);
        $deleted = $objCustomer->delete();
        if($deleted)
        {
            $bookingIds = $objBookingsObserver->getUpcomingIdsByCustomerId($paramCustomerId);

            foreach($bookingIds AS $bookingId)
            {
                $objEMailsObserver = new EMailsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
                $objBooking = new Booking($this->conf, $this->lang, $this->dbSettings->getSettings(), $bookingId);
                $objInvoice = new Invoice($this->conf, $this->lang, $this->dbSettings->getSettings(), $bookingId);
                if($objBooking->isCancelled() === FALSE)
                {
                    // First - cancel
                    $objBooking->cancel();
                    $objEMailsObserver->sendBookingCancellationEmail($objBooking->getId(), FALSE);
                }
                $objBooking->delete();
                $objBooking->deleteAllOptions();
                $objInvoice->delete();

                $this->processDebugMessages($objBooking->getDebugMessages());
                $this->processDebugMessages($objInvoice->getDebugMessages());
                $this->processDebugMessages($objEMailsObserver->getSavedDebugMessages());
                $this->processOkayMessages($objBooking->getOkayMessages());
                $this->processOkayMessages($objInvoice->getOkayMessages());
                $this->processOkayMessages($objEMailsObserver->getSavedOkayMessages());
                $this->processErrorMessages($objBooking->getErrorMessages());
                $this->processErrorMessages($objInvoice->getErrorMessages());
                $this->processErrorMessages($objEMailsObserver->getSavedErrorMessages());
            }
        }
        $this->processDebugMessages($objCustomer->getDebugMessages());
        $this->processOkayMessages($objCustomer->getOkayMessages());
        $this->processErrorMessages($objCustomer->getErrorMessages());

        // Get back to url params
        $sanitizedBackToPage = isset($_GET['backto_page']) ? sanitize_key($_GET['backto_page']) : '';
        $sanitizedBackToTab = isset($_GET['backto_tab']) ? sanitize_key($_GET['backto_tab']) : ''; // pickups, returns, bookings
        $validBackToBookingType = isset($_GET['backto_booking_type']) ? intval($_GET['backto_booking_type']) : 0;
        $paramBackToFromDate = isset($_GET['backto_from_date']) ? $_GET['backto_from_date'] : '';
        $paramBackToToDate = isset($_GET['backto_to_date']) ? $_GET['backto_to_date'] : '';
        $validBackToFromISODate = $paramBackToFromDate != '' ? StaticValidator::getValidISODate($paramBackToFromDate, $this->dbSettings->getSetting('conf_short_date_format')) : '';
        $validBackToToISODate = $paramBackToToDate != '' ? StaticValidator::getValidISODate($paramBackToToDate, $this->dbSettings->getSetting('conf_short_date_format')) : '';

        // Create back to URL
        $backToUrl = "admin.php";
        $backToUrl .= "?page={$sanitizedBackToPage}";
        $backToUrl .= "&tab={$sanitizedBackToTab}";
        $backToUrl .= "&booking_type={$validBackToBookingType}";
        $backToUrl .= "&from_date={$validBackToFromISODate}";
        $backToUrl .= "&to_date={$validBackToToISODate}";

        wp_safe_redirect($backToUrl);
        exit;
    }

    private function processSave($paramCustomerId)
    {
        $objCustomer = new Customer($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramCustomerId);
        // We set update last visit timestamp to FALSE here, because we don't want that admins would make an impact on customers last visit details
        $objCustomer->save();

        $this->processDebugMessages($objCustomer->getDebugMessages());
        $this->processOkayMessages($objCustomer->getOkayMessages());
        $this->processErrorMessages($objCustomer->getErrorMessages());

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'booking-manager&tab=customers');
        exit;
    }

    public function getContent()
    {
        // Process actions
        if(isset($_GET['delete_customer'])) { $this->processDelete($_GET['delete_customer']); }
        if(isset($_POST['save_customer'], $_POST['customer_id'])) { $this->processSave($_POST['customer_id']); }

        $paramCustomerId = isset($_GET['customer_id']) ? $_GET['customer_id'] : 0;
        $objCustomer = new Customer($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramCustomerId);
        $localDetails = $objCustomer->getDetails();

        // Set the view variables -Customer fields visibility settings
        $this->view->titleVisible = $this->dbSettings->getCustomerFieldStatus("title", "VISIBLE");
        $this->view->firstNameVisible = $this->dbSettings->getCustomerFieldStatus("first_name", "VISIBLE");
        $this->view->lastNameVisible = $this->dbSettings->getCustomerFieldStatus("last_name", "VISIBLE");
        $this->view->birthdateVisible = $this->dbSettings->getCustomerFieldStatus("birthdate", "VISIBLE");
        $this->view->streetAddressVisible = $this->dbSettings->getCustomerFieldStatus("street_address", "VISIBLE");
        $this->view->cityVisible = $this->dbSettings->getCustomerFieldStatus("city", "VISIBLE");
        $this->view->stateVisible = $this->dbSettings->getCustomerFieldStatus("state", "VISIBLE");
        $this->view->zipCodeVisible = $this->dbSettings->getCustomerFieldStatus("zip_code", "VISIBLE");
        $this->view->countryVisible = $this->dbSettings->getCustomerFieldStatus("country", "VISIBLE");
        $this->view->phoneVisible = $this->dbSettings->getCustomerFieldStatus("phone", "VISIBLE");
        $this->view->emailVisible = $this->dbSettings->getCustomerFieldStatus("email", "VISIBLE");
        $this->view->commentsVisible = $this->dbSettings->getCustomerFieldStatus("comments", "VISIBLE");

        // Set the view variables - If it is not visible, then if will not be required (function will always return false of 'required+not visible')
        $this->view->titleRequired = $this->dbSettings->getCustomerFieldStatus("title", "REQUIRED") ? ' required' : '';
        $this->view->firstNameRequired = $this->dbSettings->getCustomerFieldStatus("first_name", "REQUIRED") ? ' required' : '';
        $this->view->lastNameRequired = $this->dbSettings->getCustomerFieldStatus("last_name", "REQUIRED") ? ' required' : '';
        $this->view->birthdateRequired = $this->dbSettings->getCustomerFieldStatus("birthdate", "REQUIRED") ? ' required' : '';
        $this->view->streetAddressRequired = $this->dbSettings->getCustomerFieldStatus("street_address", "REQUIRED") ? ' required' : '';
        $this->view->cityRequired = $this->dbSettings->getCustomerFieldStatus("city", "REQUIRED") ? ' required' : '';
        $this->view->stateRequired = $this->dbSettings->getCustomerFieldStatus("state", "REQUIRED") ? ' required' : '';
        $this->view->zipCodeRequired = $this->dbSettings->getCustomerFieldStatus("zip_code", "REQUIRED") ? ' required' : '';
        $this->view->countryRequired = $this->dbSettings->getCustomerFieldStatus("country", "REQUIRED") ? ' required' : '';
        $this->view->phoneRequired = $this->dbSettings->getCustomerFieldStatus("phone", "REQUIRED") ? ' required' : '';
        $this->view->emailRequired = $this->dbSettings->getCustomerFieldStatus("email", "REQUIRED") ? ' required' : '';
        $this->view->commentsRequired = $this->dbSettings->getCustomerFieldStatus("comments", "REQUIRED") ? ' required' : '';

        // Set the view variables - other
        $this->view->backToListURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'booking-manager&tab=customers');
        $this->view->formAction = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-customer&noheader=true');
        if(!is_null($localDetails))
        {
            $this->view->customerId = $localDetails['customer_id'];
            $this->view->existingCustomer = $localDetails['existing_customer'] > 0 ? TRUE : FALSE;
            $this->view->titleDropDownOptions = $objCustomer->getTitleDropDownOptions($localDetails['title'], "", $this->lang->getText('NRS_SELECT_DROPDOWN_TEXT'));
            $this->view->firstName = $localDetails['edit_first_name'];
            $this->view->lastName = $localDetails['edit_last_name'];
            $this->view->birthYearDropDownOptions = StaticFormatter::generateDropDownOptions(current_time("Y") - 80, current_time("Y") - 10, $localDetails['birth_year'], "", $this->lang->getText('NRS_SELECT_YEAR_TEXT'), TRUE);
            $this->view->birthMonthDropDownOptions = StaticFormatter::generateDropDownOptions(1, 12, $localDetails['birth_month'], "", $this->lang->getText('NRS_SELECT_MONTH_TEXT'), TRUE);
            $this->view->birthDayDropDownOptions = StaticFormatter::generateDropDownOptions(1, 31, $localDetails['birth_day'], "", $this->lang->getText('NRS_SELECT_DAY_TEXT'), TRUE);
            $this->view->streetAddress = $localDetails['edit_street_address'];
            $this->view->city = $localDetails['edit_city'];
            $this->view->state = $localDetails['edit_state'];
            $this->view->zipCode = $localDetails['edit_zip_code'];
            $this->view->country = $localDetails['edit_country'];
            $this->view->phone = $localDetails['edit_phone'];
            $this->view->email = $localDetails['edit_email'];
            $this->view->comments = $localDetails['edit_comments'];
            $this->view->ip = $localDetails['edit_ip'];
        } else
        {
            $this->view->customerId = 0;
            $this->view->existingCustomer = FALSE;
            $this->view->titleDropDownOptions = $objCustomer->getTitleDropDownOptions($localDetails['title'], "", $this->lang->getText('NRS_SELECT_DROPDOWN_TEXT'));
            $this->view->firstName =  "";
            $this->view->lastName = "";
            $this->view->birthYearDropDownOptions = StaticFormatter::generateDropDownOptions(current_time("Y") - 80, current_time("Y") - 10, "", "", $this->lang->getText('NRS_SELECT_YEAR_TEXT'), TRUE);
            $this->view->birthMonthDropDownOptions = StaticFormatter::generateDropDownOptions(1, 12, "", "", $this->lang->getText('NRS_SELECT_MONTH_TEXT'), TRUE);
            $this->view->birthDayDropDownOptions = StaticFormatter::generateDropDownOptions(1, 31, "", "", $this->lang->getText('NRS_SELECT_DAY_TEXT'), TRUE);
            $this->view->streetAddress = "";
            $this->view->city = "";
            $this->view->state = "";
            $this->view->zipCode = "";
            $this->view->country = "";
            $this->view->phone = "";
            $this->view->email = "";
            $this->view->comments = "";
            $this->view->ip = "";
        }

        // Get the template
        $retContent = $this->getTemplate('Booking', 'AddEditCustomer', 'Form');

        return $retContent;
    }
}
