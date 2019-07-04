<?php
/**
 * @package NRS
 * @note Variables prefixed with 'local' are not used in templates
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Admin\Booking;
use NativeRentalSystem\Controllers\Admin\AbstractController;
use NativeRentalSystem\Models\Booking\Booking;
use NativeRentalSystem\Models\Booking\Invoice;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\EMail\EMailsObserver;
use NativeRentalSystem\Models\Validation\StaticValidator;

final class AddEditBookingController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    private function processDelete($paramBookingId, $paramBackToURL)
    {
        $objEMailsObserver = new EMailsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objBooking = new Booking($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramBookingId);
        $objInvoice = new Invoice($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramBookingId);
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

        $this->processDebugMessages($objBooking->getDebugMessages());
        $this->processDebugMessages($objEMailsObserver->getSavedDebugMessages());
        $this->processOkayMessages($objBooking->getOkayMessages());
        $this->processOkayMessages($objEMailsObserver->getSavedOkayMessages());
        $this->processErrorMessages($objBooking->getErrorMessages());
        $this->processErrorMessages($objEMailsObserver->getSavedErrorMessages());

        wp_safe_redirect($paramBackToURL);
        exit;
    }

    private function processCancel($paramBookingId, $paramBackToURL)
    {
        $objEMailsObserver = new EMailsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objBooking = new Booking($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramBookingId);
        $objBooking->cancel();
        $objEMailsObserver->sendBookingCancellationEmail($objBooking->getId(), FALSE);

        $this->processDebugMessages($objBooking->getDebugMessages());
        $this->processDebugMessages($objEMailsObserver->getSavedDebugMessages());
        $this->processOkayMessages($objBooking->getOkayMessages());
        $this->processOkayMessages($objEMailsObserver->getSavedOkayMessages());
        $this->processErrorMessages($objBooking->getErrorMessages());
        $this->processErrorMessages($objEMailsObserver->getSavedErrorMessages());

        wp_safe_redirect($paramBackToURL);
        exit;
    }

    private function processMarkPaid($paramBookingId, $paramBackToURL)
    {
        $objEMailsObserver = new EMailsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objBooking = new Booking($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramBookingId);
        $objBooking->markPaid();
        $objEMailsObserver->sendBookingConfirmationEmail($objBooking->getId(), FALSE);

        $this->processDebugMessages($objBooking->getDebugMessages());
        $this->processDebugMessages($objEMailsObserver->getSavedDebugMessages());
        $this->processOkayMessages($objBooking->getOkayMessages());
        $this->processOkayMessages($objEMailsObserver->getSavedOkayMessages());
        $this->processErrorMessages($objBooking->getErrorMessages());
        $this->processErrorMessages($objEMailsObserver->getSavedErrorMessages());

        wp_safe_redirect($paramBackToURL);
        exit;
    }

    private function processCompletedEarly($paramBookingId, $paramBackToURL)
    {
        $objBooking = new Booking($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramBookingId);
        $objBooking->markCompletedEarly();

        $this->processDebugMessages($objBooking->getDebugMessages());
        $this->processOkayMessages($objBooking->getOkayMessages());
        $this->processErrorMessages($objBooking->getErrorMessages());

        wp_safe_redirect($paramBackToURL);
        exit;
    }

    private function processRefund($paramBookingId, $paramBackToURL)
    {
        $objEMailsObserver = new EMailsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objBooking = new Booking($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramBookingId);
        $objBooking->refund();
        $objEMailsObserver->sendBookingCancellationEmail($paramBookingId, FALSE);

        $this->processDebugMessages($objBooking->getDebugMessages());
        $this->processDebugMessages($objEMailsObserver->getSavedDebugMessages());
        $this->processOkayMessages($objBooking->getOkayMessages());
        $this->processOkayMessages($objEMailsObserver->getSavedOkayMessages());
        $this->processErrorMessages($objBooking->getErrorMessages());
        $this->processErrorMessages($objEMailsObserver->getSavedErrorMessages());

        wp_safe_redirect($paramBackToURL);
        exit;
    }

    public function getContent()
    {
        // Get back to url params
        $sanitizedBackToPage = isset($_GET['backto_page']) ? sanitize_key($_GET['backto_page']) : '';
        $sanitizedBackToTab = isset($_GET['backto_tab']) ? sanitize_key($_GET['backto_tab']) : ''; // pickups, returns, bookings
        $paramBackToFromDate = isset($_GET['backto_from_date']) ? $_GET['backto_from_date'] : '';
        $paramBackToToDate = isset($_GET['backto_to_date']) ? $_GET['backto_to_date'] : '';
        $validBackToCustomerId = isset($_GET['backto_customer_id']) ? StaticValidator::getValidInteger($_GET['backto_customer_id'], -1) : -1;
        $validBackToFromISODate = $paramBackToFromDate != '' ? StaticValidator::getValidISODate($paramBackToFromDate, $this->dbSettings->getSetting('conf_short_date_format')) : '';
        $validBackToToISODate = $paramBackToToDate != '' ? StaticValidator::getValidISODate($paramBackToToDate, $this->dbSettings->getSetting('conf_short_date_format')) : '';

        // Create back to URL
        $backToUrl = "admin.php";
        $backToUrl .= "?page={$sanitizedBackToPage}";
        $backToUrl .= "&tab={$sanitizedBackToTab}";
        $backToUrl .= "&from_date={$validBackToFromISODate}";
        $backToUrl .= "&to_date={$validBackToToISODate}";
        $backToUrl .= "&customer_id={$validBackToCustomerId}";

        // Process actions
        if(isset($_GET['delete_booking'])) { $this->processDelete($_GET['delete_booking'], $backToUrl); }
        if(isset($_GET['cancel_booking'])) { $this->processCancel($_GET['cancel_booking'], $backToUrl); }
        if(isset($_GET['mark_paid_booking'])) { $this->processMarkPaid($_GET['mark_paid_booking'], $backToUrl); }
        if(isset($_GET['mark_completed_early'])) { $this->processCompletedEarly($_GET['mark_completed_early'], $backToUrl); }
        if(isset($_GET['refund_booking'])) { $this->processRefund($_GET['refund_booking'], $backToUrl); }

        // There is no content for booking edit
        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'booking-manager&tab=bookings');
        return '';
    }
}
