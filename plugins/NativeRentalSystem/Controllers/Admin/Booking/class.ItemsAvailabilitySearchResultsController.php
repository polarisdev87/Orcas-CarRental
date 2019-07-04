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
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Item\ItemsObserver;
use NativeRentalSystem\Models\Calendar\ItemsCalendar;

final class ItemsAvailabilitySearchResultsController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    public function getContent()
    {
        // Create mandatory object instances
        $objItemsObserver = new ItemsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objItemsCalendar = new ItemsCalendar($this->conf, $this->lang, $this->dbSettings->getSettings());

        // Get search params
        $paramFromDate = isset($_GET['from_date']) ? $_GET['from_date'] : '';
        $paramToDate = isset($_GET['to_date']) ? $_GET['to_date'] : '';
        if($paramFromDate != '')
        {
            $localFromISODate = StaticValidator::getValidISODate($paramFromDate, $this->dbSettings->getSetting('conf_short_date_format'));
            $localFromTimestamp = StaticValidator::getUTCTimestampFromLocalISODateTime($localFromISODate, '00:00:00');
            $printFromDate = date_i18n($this->dbSettings->getSetting('conf_short_date_format'), $localFromTimestamp + get_option('gmt_offset') * 3600, TRUE);
        } else
        {
            $localFromISODate   = '';
            $printFromDate = $this->lang->getText('NRS_ADMIN_PAST_TEXT');
        }
        if($paramToDate != '')
        {
            $localToISODate = StaticValidator::getValidISODate($paramToDate, $this->dbSettings->getSetting('conf_short_date_format'));
            $localToTimestamp = StaticValidator::getUTCTimestampFromLocalISODateTime($localToISODate, '23:59:59');
            $printToDate = date_i18n($this->dbSettings->getSetting('conf_short_date_format'), $localToTimestamp + get_option('gmt_offset') * 3600, TRUE);
        } else
        {
            $localToISODate     = '';
            $printToDate   = $this->lang->getText('NRS_ADMIN_UPCOMING_TEXT');
        }

        // +1 because we want to include current month
        $localTotalMonths = StaticValidator::getTotalMonthsBetweenTwoISODates($localFromISODate, $localToISODate)+1;

        // Calendar table: Start
        $arrItemsCalendars = array();
        for($localMonthDiff = 0; $localMonthDiff <= $localTotalMonths; $localMonthDiff++)
        {
            $localSelectedTimestamp = strtotime("{$localFromISODate} + {$localMonthDiff} month");
            $localSelectedYear = date("Y", $localSelectedTimestamp);
            $localSelectedMonth = date("m", $localSelectedTimestamp);

            $arrItemsCalendars[] = $objItemsCalendar->getMonthlyCalendar(-1, -1, -1, -1, -1, -1, -1, -1, -1, $localSelectedYear, $localSelectedMonth);
        }
        // Calendar table: End

        // Set the view variables
        $this->view->noonTime = date_i18n(get_option('time_format'), strtotime(date("Y-m-d")." ".$this->dbSettings->getSetting('conf_noon_time')), TRUE);
        $this->view->fromDate = $printFromDate;
        $this->view->toDate = $printToDate;
        $this->view->classifiedItems = $objItemsObserver->areItemsClassified();
        $this->view->arrItemsCalendars = $arrItemsCalendars;

        // Get the template
        $retContent = $this->getTemplate('Booking', 'ItemsAvailabilitySearchResults', 'Tabs');

        return $retContent;
    }
}
