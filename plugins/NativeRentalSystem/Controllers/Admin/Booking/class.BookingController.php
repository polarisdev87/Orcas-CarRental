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
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Booking\BookingsObserver;
use NativeRentalSystem\Models\Customer\CustomersObserver;
use NativeRentalSystem\Models\Item\ItemsObserver;
use NativeRentalSystem\Models\Calendar\ItemsCalendar;
use NativeRentalSystem\Models\Calendar\ExtrasCalendar;
use NativeRentalSystem\Models\Logging\LogsObserver;
use NativeRentalSystem\Models\Validation\StaticValidator;

final class BookingController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    public function getContent()
    {
        // Create mandatory instances
        $objCustomersObserver = new CustomersObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objBookingsObserver = new BookingsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objItemsObserver = new ItemsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objItemsCalendar = new ItemsCalendar($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objExtrasCalendar = new ExtrasCalendar($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objLogsObserver = new LogsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());

        // First - delete expired logs
        $objLogsObserver->deleteExpiredCustomerLookupLogs();

        // Booking lists: start
        $todayStartTimestamp = StaticValidator::getTodayStartTimestamp();
        // DEBUG
        //echo "<br />TIME: ".time().", TODAY&#39;S START: {$todayStartTimestamp}";

        $backToPickupsUrlPart = "&backto_page={$this->conf->getURLPrefix()}booking-manager&backto_tab=pickups";
        $backToReturnsUrlPart = "&backto_page={$this->conf->getURLPrefix()}booking-manager&backto_tab=returns";
        $backToBookingsUrlPart = "&backto_page={$this->conf->getURLPrefix()}booking-manager&backto_tab=bookings";
        $pickupsHTML = $objBookingsObserver->getAdminPickups($todayStartTimestamp, -1, 0, $backToPickupsUrlPart);
        $returnsHTML = $objBookingsObserver->getAdminReturns($todayStartTimestamp, -1, 0, $backToReturnsUrlPart);
        $bookingsHTML = $objBookingsObserver->getAdminBookings($todayStartTimestamp, -1, 0, $backToBookingsUrlPart);
        // Booking lists: end

        // Customer List: Start
        $backToCustomersUrlPart = "&backto_page={$this->conf->getURLPrefix()}booking-manager&backto_tab=customers";
        $customersHTML = $objCustomersObserver->getAdminList($backToCustomersUrlPart);
        // Customer List: End

        // Item Calendar table: Start
        $localSelectedTimestamp = strtotime(date("Y-m-d")." 00:00:00");
        $localSelectedYear = date("Y", $localSelectedTimestamp);
        $localSelectedMonth = date("m", $localSelectedTimestamp);
        $localSelectedDay = date("d", $localSelectedTimestamp);
        $itemsCalendar = $objItemsCalendar->get30DaysCalendar(-1, -1, -1, -1, -1, -1, -1, -1, -1, $localSelectedYear, $localSelectedMonth, $localSelectedDay);
        // Item Calendar table: End

        // Extra Calendar table: Start
        $localSelectedTimestamp = strtotime(date("Y-m-d")." 00:00:00");
        $localSelectedYear = date("Y", $localSelectedTimestamp);
        $localSelectedMonth = date("m", $localSelectedTimestamp);
        $localSelectedDay = date("d", $localSelectedTimestamp);
        $extrasCalendar = $objExtrasCalendar->get30DaysCalendar(-1, -1, -1, -1, -1, -1, -1, -1, -1, $localSelectedYear, $localSelectedMonth, $localSelectedDay);
        // Extra Calendar table: End

        // Get the tab values
        $tabs = $this->getTabParams(
            array('pickups', 'returns', 'bookings', 'customers', 'items-availability', 'extras-availability', 'api-log'),
            'pickups'
        );

        // 1. Set the view variables - Tab settings
        $this->view->pickupsTabChecked = !empty($tabs['pickups']) ? ' checked="checked"' : '';
        $this->view->returnsTabChecked = !empty($tabs['returns']) ? ' checked="checked"' : '';
        $this->view->bookingsTabChecked = !empty($tabs['bookings']) ? ' checked="checked"' : '';
        $this->view->customersTabChecked = !empty($tabs['customers']) ? ' checked="checked"' : '';
        $this->view->itemsAvailabilityTabChecked = !empty($tabs['items-availability']) ? ' checked="checked"' : '';
        $this->view->extrasAvailabilityTabChecked = !empty($tabs['extras-availability']) ? ' checked="checked"' : '';
        $this->view->apiLogTabChecked = !empty($tabs['api-log']) ? ' checked="checked"' : '';

        // 2. Set the view variables - other variables
        $this->view->classifiedItems = $objItemsObserver->areItemsClassified();
        $this->view->html = "";
        $this->view->bookingTableTitle = "";
        $this->view->noonTime = date_i18n(get_option('time_format'), strtotime(date("Y-m-d")." ".$this->dbSettings->getSetting('conf_noon_time')), TRUE);
        $this->view->pickupsHTML = $pickupsHTML;
        $this->view->returnsHTML = $returnsHTML;
        $this->view->bookingsHTML = $bookingsHTML;
        $this->view->customersHTML = $customersHTML;
        $this->view->itemsCalendar = $itemsCalendar;
        $this->view->extrasCalendar = $extrasCalendar;
        $this->view->logList = $objLogsObserver->getAdminListForCustomers();

        // Get the template
        $retContent = $this->getTemplate('Booking', 'BookingManager', 'Tabs');

        return $retContent;
    }
}
