<?php
/**
 * NRS Item Calendar

 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Calendar;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Formatting\StaticFormatter;
use NativeRentalSystem\Models\Item\BodyType;
use NativeRentalSystem\Models\Item\BodyTypesObserver;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Item\Item;
use NativeRentalSystem\Models\Item\ItemsObserver;
use NativeRentalSystem\Models\Unit\ItemUnitManager;

class ItemsCalendar implements iCalendar
{
    protected $conf 	                = NULL;
    protected $lang 		            = NULL;
    protected $debugMode 	            = 0;
    protected $settings                 = array();
    protected $noonTime	                = '12:00:00';

	public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, $paramSettings = array())
	{
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;
        $this->settings = $paramSettings;
        $this->noonTime = StaticValidator::getValidSetting($paramSettings, 'conf_show_price_with_taxes', "time_format", "12:00:00");
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    public function get30DaysCalendar(
        $paramItemId = -1, $paramExtraId = -1, $paramPickupLocationId = -1, $paramReturnLocationId = -1, $paramPartnerId = -1,
        $paramManufacturerId = -1, $paramBodyTypeId = -1, $paramTransmissionTypeId = -1, $paramFuelTypeId = -1,
        $paramYear = "current", $paramMonth = "current", $paramDay = "current"
    ) {
        return $this->getCalendar(
            $paramItemId, $paramPickupLocationId, $paramReturnLocationId, $paramPartnerId,
                $paramManufacturerId, $paramBodyTypeId, $paramTransmissionTypeId, $paramFuelTypeId,
                $paramYear, $paramMonth, $paramDay, TRUE, FALSE
        );
    }

    public function getMonthlyCalendar(
        $paramItemId = -1, $paramExtraId = -1, $paramPickupLocationId = -1, $paramReturnLocationId = -1, $paramPartnerId = -1,
        $paramManufacturerId = -1, $paramBodyTypeId = -1, $paramTransmissionTypeId = -1, $paramFuelTypeId = -1,
        $paramYear = "current", $paramMonth = "current"
    ) {
        return $this->getCalendar(
            -1, -1, -1, -1,
            -1, -1, -1, -1,
                $paramYear, $paramMonth, '01', FALSE, FALSE
        );
    }

    /**
     * Get the calendar
     * @param int $paramItemId
     * @param int $paramPickupLocationId
     * @param int $paramReturnLocationId
     * @param int $paramPartnerId
     * @param int $paramManufacturerId - manufacturer
     * @param int $paramBodyTypeId - body type id
     * @param int $paramTransmissionTypeId - transmission type id
     * @param int $paramFuelTypeId - fuel type id
     * @param string $paramYear - Year
     * @param string $paramMonth - Month
     * @param string $paramDay = Day
     * @param bool $param30Days = FALSE
     * Return example: calendar = array("got_search_result" => TRUE, "body_types" => array());
     * Return example: calendar['body_types'][0] = array("items" => array());
     * Return example: calendar['body_types'][0]['items'][0]['transmission_type_title'] = "Manual";
     * Return example: calendar['body_types'][0]['items'][0]['day_list'][0]['print_day'] = "1";
     * Return example: calendar['body_types'][0]['items'][0]['day_list'][0]['units_in_stock'] = "5";
     * @param bool $paramUseDashes
     * @return array
     * @internal param int $paramExtraId - not used for items
     */
	private function getCalendar(
        $paramItemId = -1, $paramPickupLocationId = -1, $paramReturnLocationId = -1, $paramPartnerId = -1, $paramManufacturerId = -1,
        $paramBodyTypeId = -1, $paramTransmissionTypeId = -1, $paramFuelTypeId = -1,
        $paramYear = "current", $paramMonth = "current", $paramDay = "current", $param30Days = FALSE, $paramUseDashes = TRUE
    ) {
        $valid30Days = $param30Days === TRUE ? TRUE : FALSE;
		$objItemsObserver = new ItemsObserver($this->conf, $this->lang, $this->settings);
		$validYear = $paramYear == "current" ? date("Y", StaticValidator::getLocalCurrentTimestamp()) : StaticValidator::getValidPositiveInteger($paramYear, "2000");
		$validMonth = $paramMonth == "current" ? date("m", StaticValidator::getLocalCurrentTimestamp()) : StaticValidator::getValidPositiveInteger($paramMonth, "01");
        $validDay = $paramMonth == "current" ? date("d", StaticValidator::getLocalCurrentTimestamp()) : StaticValidator::getValidPositiveInteger($paramDay, "01");

        // Last True means that we convert to GMT for time, because it's a strict date provided
        if($paramYear == "current" && $paramMonth == "current")
        {
            $printNameOfMonth = date_i18n("F", StaticValidator::getLocalCurrentTimestamp(), TRUE);
        } else
        {
            $printNameOfMonth = date_i18n("F", strtotime("{$validYear}-{$validMonth}-01 00:00:00"), TRUE);
        }
        $printNamesOfMonths = $printNameOfMonth;
        $twoMonths = FALSE;
        if($param30Days === TRUE)
        {
            if($paramYear == "current" && $paramMonth == "current")
            {
                $startTimestamp = StaticValidator::getLocalCurrentTimestamp();
                $timestampAfter30Days = StaticValidator::getLocalCurrentTimestamp() + (30 * 86400);
            } else
            {
                $startTimestamp = strtotime("{$validYear}-{$validMonth}-{$validDay} 00:00:00");
                $timestampAfter30Days = strtotime("{$validYear}-{$validMonth}-{$validDay} 00:00:00 + 30 day");
            }
            $startMonth = date("m", $startTimestamp);
            $monthAfter30Days = date("m", $timestampAfter30Days);
            if($monthAfter30Days != $startMonth)
            {
                $twoMonths = TRUE;
            }
            $printNameOfMonthAfter30Days = date_i18n("F", $timestampAfter30Days, TRUE);
            $printNamesOfMonths .= ", {$printNameOfMonthAfter30Days}";

            // No SQL executed inside
            $arrDaysOfMonth = StaticFormatter::getNext30DaysArray($validYear, $validMonth, $validDay);
        } else
        {
            // No SQL executed inside
            $arrDaysOfMonth = StaticFormatter::getAllDaysOfTheMonthArray($validYear, $validMonth);
        }

		$gotSearchResult = false;
		$itemTypesWithItems = array();
		// Includes items with no type
		if($objItemsObserver->areItemsClassified())
		{
            // Fast SQL
            $objBodyTypes = new BodyTypesObserver($this->conf, $this->lang, $this->settings);
            $bodyTypeIds = $objBodyTypes->getAllIds(TRUE);
			foreach($bodyTypeIds AS $bodyTypeId)
			{
                if($paramBodyTypeId == -1 || ($paramBodyTypeId >= 0 && $paramBodyTypeId == $bodyTypeId))
                {
                    $objBodyType = new BodyType($this->conf, $this->lang, $this->settings, $bodyTypeId);
                    $type = $objBodyType->getDetails(TRUE);
                    $itemIds = $objItemsObserver->getAvailableIdsForCalendar(
                        $paramPartnerId, $paramManufacturerId, $bodyTypeId, $paramTransmissionTypeId,
                        $paramFuelTypeId, $paramItemId, $paramPickupLocationId, $paramReturnLocationId
                    );
                    $type['items'] = array();
                    $type['got_search_result'] = false;
                    foreach($itemIds AS $itemId)
                    {
                        $objItem = new Item($this->conf, $this->lang, $this->settings, $itemId);
                        $itemDetails = $objItem->getExtendedDetails();
                        // Add days data to item row
                        $itemDetails['day_list'] = $this->getMonthDaysWithQuantity(
                            $itemDetails['item_sku'], $validYear, $validMonth, $validDay, $param30Days, $paramUseDashes
                        );
                        $type['items'][] = $itemDetails;
                        $type['got_search_result'] = TRUE;
                        $gotSearchResult = TRUE;
                    }
                    // Add to stack
                    $itemTypesWithItems[] = $type;
                }
			}
		} else
		{
			// Same, just everything added to param zero
			$itemIds = $objItemsObserver->getAvailableIdsForCalendar(
			    $paramPartnerId, $paramManufacturerId, $paramBodyTypeId, $paramTransmissionTypeId,
                $paramFuelTypeId, $paramItemId, $paramPickupLocationId, $paramReturnLocationId
            );
			$type['items'] = array();
			$type['got_search_result'] = false;
			foreach($itemIds AS $itemId)
			{
				$objItem = new Item($this->conf, $this->lang, $this->settings, $itemId);
                $itemDetails = $objItem->getExtendedDetails();
				// Add days data to item row
                $itemDetails['day_list'] = $this->getMonthDaysWithQuantity(
                    $itemDetails['item_sku'], $validYear, $validMonth, $validDay, $param30Days, $paramUseDashes
                );
				$type['items'][] = $itemDetails;
				$type['got_search_result'] = true;
			}
			// Add to stack
			$itemTypesWithItems[0] = $type;
		}

		$calendar = array(
			"30_days" => $valid30Days,
			"2_months" => $twoMonths,
			"print_year" => $validYear,
			"print_month" => $validMonth,
			"print_month_name" => $printNameOfMonth,
			"print_month_names" => $printNamesOfMonths,
			"print_days" => $arrDaysOfMonth,
			"total_days" => sizeof($arrDaysOfMonth),
			"body_types" => $itemTypesWithItems,
			"got_search_result" => $gotSearchResult,
		);

		if($this->debugMode)
		{
			echo "Year: {$validYear}, Month: {$validMonth}, Name of Month: {$printNameOfMonth}<br />";
		}

		return $calendar;
	}

	private function getMonthDaysWithQuantity($paramItemSKU, $paramYear = "current", $paramMonth = "current", $paramDay = "current", $param30Days = FALSE, $paramUseDashes = TRUE)
	{
        if($this->debugMode)
        {
            echo "<strong>[START] getMonthDaysWithQuantity for Item SKU: {$paramItemSKU}</strong><br />";
            echo "30 days: ".($param30Days ? "YES" : "NO")."<br />";
            echo "-----------------------------------------------------------------------<br />";
        }

		$validYear = $paramYear == "current" ? date("Y", StaticValidator::getLocalCurrentTimestamp()) : StaticValidator::getValidPositiveInteger($paramYear, "2000");
		$validMonth = $paramMonth == "current" ? date("m", StaticValidator::getLocalCurrentTimestamp()) : StaticValidator::getValidPositiveInteger($paramMonth, "01");
		$validDay = $paramDay == "current" ? date("d", StaticValidator::getLocalCurrentTimestamp()) : StaticValidator::getValidPositiveInteger($paramDay, "01");

		$days = array();
        if($param30Days === TRUE)
        {
            // No SQL executed inside
            $arrDaysOfMonth = StaticFormatter::getNext30DaysArray($validYear, $validMonth, $validDay);
        } else
        {
            // No SQL executed inside
            $arrDaysOfMonth = StaticFormatter::getAllDaysOfTheMonthArray($validYear, $validMonth);
        }
        $year = $validYear;
        $month = $validMonth;
        $prevSelectedDay = isset($arrDaysOfMonth[0]) ? $arrDaysOfMonth[0] : "01";
		foreach($arrDaysOfMonth AS $selectedDay)
		{
            /* - DEBUG - */ if($this->debugMode) { echo "-&gt; CHECK: $prevSelectedDay &gt; $selectedDay. "; }
            if($prevSelectedDay > $selectedDay)
            {
                $month++;
                if($month > 12)
                {
                    $year++;
                    $month = "01";
                }

                /* - DEBUG - */ if($this->debugMode) { echo "RESULT: CORRECT. New month set to: {$month}, year: {$year}"; }
            }
            $prevSelectedDay = $selectedDay;
            $localStartOfDayTimestamp = StaticValidator::getStartOfDayTimestampInWebsiteTimezone("{$year}-{$month}-{$selectedDay}");
            $localNoonOfDayTimestamp = StaticValidator::getLocalNoonOfDayTimestampInWebsiteTimezone("{$year}-{$month}-{$selectedDay}", $this->noonTime);
            $localEndOfDayTimestamp = StaticValidator::getLocalEndOfDayTimestampInWebsiteTimezone("{$year}-{$month}-{$selectedDay}");
			$itemUnitsManager = new ItemUnitManager(
                $this->conf, $this->lang, $this->settings, $paramItemSKU, $localStartOfDayTimestamp, $localEndOfDayTimestamp
			);
            $partialItemUnitsManager = new ItemUnitManager(
                $this->conf, $this->lang, $this->settings, $paramItemSKU, $localNoonOfDayTimestamp, $localEndOfDayTimestamp
            );

            // How many units of one item we have (in stock/available/booked)
            $arrTotalUnits = $itemUnitsManager->getTotalUnits();

            // How many units of one item is in stock
            $totalUnitsInStock = $arrTotalUnits['units_in_stock'];

            // How many units of one item is available for a full day (00:00:00 [LOCAL TIME] - 23:59:59 [LOCAL TIME])
            $unitsAvailable = $arrTotalUnits['units_available'];

            // How many units of one item is available in 2nd half of the day (CONFIG 'noon_time' (DEFAULT - 12:00:00 [LOCAL TIME]) - 23:59:59 [LOCAL TIME])
            $partialUnitsAvailable = $partialItemUnitsManager->getTotalUnitsAvailable();

            $quantityClass = $unitsAvailable == 0 ? "all-taken" : "has-available";
            if($paramUseDashes)
            {
                $printUnitsAvailable = StaticValidator::getDashInsteadOfNumberIfTimestampIsPast($unitsAvailable, $localEndOfDayTimestamp);
                $printPartialUnitsAvailable = StaticValidator::getDashInsteadOfNumberIfTimestampIsPast($partialUnitsAvailable, $localEndOfDayTimestamp);
            } else
            {
                $printUnitsAvailable = $unitsAvailable;
                $printPartialUnitsAvailable = $partialUnitsAvailable;
            }

			$printOrderExtension = $this->lang->getOrderExtensionByPosition(
                (int) $selectedDay,
                $this->lang->getText('NRS_ON_ST_TEXT'),
                $this->lang->getText('NRS_ON_ND_TEXT'),
                $this->lang->getText('NRS_ON_RD_TEXT'),
                $this->lang->getText('NRS_ON_TH_TEXT')
            );

            $printSelectedMonthName = date_i18n("F", strtotime("{$year}-{$month}-01 00:00:00"), TRUE);
            $printSelectedDay = ((int) $selectedDay).$printOrderExtension;

            $days[] = array(
				"units_in_stock" 				=> $totalUnitsInStock,
				"units_available" 				=> $unitsAvailable,
                "partial_units_available" 		=> $partialUnitsAvailable,
                "print_year"                    => $year,
                "print_month" 					=> $month,
                "print_month_name" 				=> $printSelectedMonthName,
                "print_day" 					=> $printSelectedDay,
				"print_quantity_class"  		=> $quantityClass,
				"print_units_available"  		=> $printUnitsAvailable,
				"print_partial_units_available"	=> $printPartialUnitsAvailable,
			);

			if($this->debugMode)
			{
				echo "<br />";
				echo "-&gt;  Year: {$year} Month: {$month}, Day: {$selectedDay}, ";
                echo "Units (avail./part. avail./in stock): {$unitsAvailable}/{$partialUnitsAvailable}/{$totalUnitsInStock}<br />";
				echo "-&gt;  Timestamps in Local TMZ (start/noon/end): {$localStartOfDayTimestamp} - {$localNoonOfDayTimestamp} - {$localEndOfDayTimestamp}<br />";
                echo "<br />";
			}
		}

		return $days;
	}
}