<?php
/**
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Admin\Booking;
use NativeRentalSystem\Controllers\Admin\AbstractController;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Customer\CustomersObserver;
use NativeRentalSystem\Models\Validation\StaticValidator;

final class CustomerSearchResultsController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    public function getContent()
    {
        // Create mandatory object instances
        $objCustomersObserver = new CustomersObserver($this->conf, $this->lang, $this->dbSettings->getSettings());

        // Get back to url params
        $paramBookingType = isset($_GET['booking_type']) ? $_GET['booking_type'] : '';
        $paramFromDate = isset($_GET['from_date']) ? $_GET['from_date'] : '';
        $paramToDate = isset($_GET['to_date']) ? $_GET['to_date'] : '';

        if($paramFromDate != '')
        {
            $localFromISODate   = StaticValidator::getValidISODate($paramFromDate, $this->dbSettings->getSetting('conf_short_date_format'));
            $localFromTimestamp = StaticValidator::getUTCTimestampFromLocalISODateTime($localFromISODate, '00:00:00');
            $fromDate           = date_i18n($this->dbSettings->getSetting('conf_short_date_format'), $localFromTimestamp + get_option('gmt_offset') * 3600, TRUE);
        } else
        {
            $localFromISODate   = '';
            $localFromTimestamp = -1;
            $fromDate = '';
        }
        if($paramToDate != '')
        {
            $localToISODate     = StaticValidator::getValidISODate($paramToDate, $this->dbSettings->getSetting('conf_short_date_format'));
            $localToTimestamp   = StaticValidator::getUTCTimestampFromLocalISODateTime($localToISODate, '23:59:59');
            $toDate             = date_i18n($this->dbSettings->getSetting('conf_short_date_format'), $localToTimestamp + get_option('gmt_offset') * 3600, TRUE);
        } else
        {
            $localToISODate     = '';
            $localToTimestamp   = -1;
            $toDate   = '';
        }

        $backToUrlPart = "";
        $backToUrlPart .= "&backto_page={$this->conf->getURLPrefix()}customer-search-results";
        $backToUrlPart .= "&backto_tab=customers";
        $backToUrlPart .= "&backto_booking_type=".intval($paramBookingType);
        $backToUrlPart .= "&backto_from_date={$localFromISODate}";
        $backToUrlPart .= "&backto_to_date={$localToISODate}";

        // Customer list: Start
        if($paramBookingType == 0)
        {
            $customerTableTitle = $this->lang->getText('NRS_ADMIN_CUSTOMERS_BY_REGISTRATION_TEXT');
            $customersHTML = $objCustomersObserver->getAdminListByFirstBooking($localFromTimestamp, $localToTimestamp, $backToUrlPart);
        } else
        {
            $customerTableTitle = $this->lang->getText('NRS_ADMIN_CUSTOMERS_BY_LAST_VISIT_TEXT');
            $customersHTML = $objCustomersObserver->getAdminListByLastBooking($localFromTimestamp, $localToTimestamp, $backToUrlPart);
        }
        // Customer list: End

        // Set the view variables
        $this->view->customerTableTitle = $customerTableTitle;
        $this->view->fromDate = $fromDate;
        $this->view->toDate = $toDate;
        $this->view->customersHTML = $customersHTML;

        // Get the template
        $retContent = $this->getTemplate('Booking', 'CustomerSearchResults', 'Tabs');

        return $retContent;
    }
}
