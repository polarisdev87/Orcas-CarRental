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
use NativeRentalSystem\Models\Booking\Invoice;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Booking\BookingsObserver;
use NativeRentalSystem\Models\Customer\Customer;
use NativeRentalSystem\Models\EMail\EMailsObserver;
use NativeRentalSystem\Models\Validation\StaticValidator;

final class BookingSearchResultsController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    public function getContent()
    {
        // Create mandatory object instances
        $objBookingsObserver = new BookingsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());

        // Get back to url params
        $paramBackToTab = isset($_GET['backto_tab']) ? $_GET['backto_tab'] : ''; // pickups, returns, bookings
        $paramFromDate = isset($_GET['from_date']) ? $_GET['from_date'] : '';
        $paramToDate = isset($_GET['to_date']) ? $_GET['to_date'] : '';
        $paramCustomerId = isset($_GET['customer_id']) ? $_GET['customer_id'] : -1;

        if($paramFromDate != '')
        {
            $localFromISODate   = StaticValidator::getValidISODate($paramFromDate, $this->dbSettings->getSetting('conf_short_date_format'));
            $localFromTimestamp = StaticValidator::getUTCTimestampFromLocalISODateTime($localFromISODate, '00:00:00');
            $localPrintFromDate = date_i18n($this->dbSettings->getSetting('conf_short_date_format'), $localFromTimestamp + get_option('gmt_offset') * 3600, TRUE);
        } else
        {
            $localFromISODate   = '';
            $localFromTimestamp = -1;
            $localPrintFromDate = $this->lang->getText('NRS_ADMIN_PAST_TEXT');
        }
        if($paramToDate != '')
        {
            $localToISODate     = StaticValidator::getValidISODate($paramToDate, $this->dbSettings->getSetting('conf_short_date_format'));
            $localToTimestamp   = StaticValidator::getUTCTimestampFromLocalISODateTime($localToISODate, '23:59:59');
            $localPrintToDate   = date_i18n($this->dbSettings->getSetting('conf_short_date_format'), $localToTimestamp + get_option('gmt_offset') * 3600, TRUE);
        } else
        {
            $localToISODate     = '';
            $localToTimestamp   = -1;
            $localPrintToDate   = $this->lang->getText('NRS_ADMIN_UPCOMING_TEXT');
        }

        $sanitizedBackToTab = sanitize_key($paramBackToTab);
        $validBackToTab = esc_html($sanitizedBackToTab);
        $validCustomerId = StaticValidator::getValidPositiveInteger($paramCustomerId, -1);

        $backToUrlPart = "";
        $backToUrlPart .= "&tab={$sanitizedBackToTab}";
        $backToUrlPart .= "&backto_page={$this->conf->getURLPrefix()}booking-search-results";
        $backToUrlPart .= "&backto_tab={$sanitizedBackToTab}";
        $backToUrlPart .= "&backto_from_date={$localFromISODate}";
        $backToUrlPart .= "&backto_to_date={$localToISODate}";
        $backToUrlPart .= "&backto_customer_id={$validCustomerId}";

        // Booking list: Start
        $bookingsTableTitle = "";
        $customerDescription = "";
        $bookingsHTML = "";

        if(isset($_GET['search_pickup_date']))
        {
            $bookingsTableTitle = sprintf($this->lang->getText('NRS_ADMIN_PICKUPS_PERIOD_FROM_TO_TEXT'), $localPrintFromDate, $localPrintToDate);
            $bookingsHTML = $objBookingsObserver->getAdminPickups($localFromTimestamp, $localToTimestamp, $paramCustomerId, $backToUrlPart);
        } else if(isset($_GET['search_return_date']))
        {
            $bookingsTableTitle = sprintf($this->lang->getText('NRS_ADMIN_RETURNS_PERIOD_FROM_TO_TEXT'), $localPrintFromDate, $localPrintToDate);
            $bookingsHTML = $objBookingsObserver->getAdminReturns($localFromTimestamp, $localToTimestamp, $paramCustomerId, $backToUrlPart);
        } else if(isset($_GET['search_booking_date']))
        {
            $bookingsTableTitle = sprintf($this->lang->getText('NRS_ADMIN_BOOKINGS_PERIOD_FROM_TO_TEXT'), $localPrintFromDate, $localPrintToDate);
            $bookingsHTML = $objBookingsObserver->getAdminBookings($localFromTimestamp, $localToTimestamp, $paramCustomerId, $backToUrlPart);
        }
        if($paramCustomerId > 0)
        {
            $objCustomer = new Customer($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramCustomerId);
            $customerDetails = $objCustomer->getDetails();
            if(!is_null($customerDetails))
            {
                $customerDescription = sprintf($this->lang->getText('NRS_ADMIN_BOOKINGS_BY_TEXT'), $customerDetails['print_full_name']);
            } else
            {
                $customerDescription = $this->lang->getText('NRS_ADMIN_CUSTOMER_BOOKINGS_TEXT');
            }
        }
        // Booking list: End

        // Set the view variables
        $this->view->bookingsTableTitle = $bookingsTableTitle;
        $this->view->customerDescription = $customerDescription;
        $this->view->bookingsHTML = $bookingsHTML;
        $this->view->backToTab = $validBackToTab;

        // Get the template
        $retContent = $this->getTemplate('Booking', 'BookingSearchResults', 'Tabs');

        return $retContent;
    }
}
