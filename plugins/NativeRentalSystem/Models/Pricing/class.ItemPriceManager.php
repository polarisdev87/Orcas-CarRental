<?php
/**
 * Item Price Manager

 * @package NRS
 * @uses NRSDepositManager, NRSDiscountManager, NRSPrepaymentManager
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Pricing;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Formatting\StaticFormatter;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Deposit\ItemDepositManager;
use NativeRentalSystem\Models\Discount\PricePlanDiscountManager;

class ItemPriceManager
{
    protected $conf 	                = NULL;
    protected $lang 		            = NULL;
    protected $debugMode 	            = 0; // 0 - off, 1 - regular, 2 - deep items, 3 - deep extras
    protected $settings                 = array();
    // Price calculation type: 1 - daily, 2 - hourly, 3 - mixed (daily+hourly)
    protected $priceCalculationType     = 1;
    protected $currencySymbol		    = '$';
    protected $currencyCode			    = 'USD';
    protected $currencySymbolLocation	= 0;
    // Dynamic tax percentage
    protected $taxPercentage		    = 0.00;
    protected $showPriceWithTaxes	    = 0;
    protected $itemId			        = 0;
    protected $priceGroupId		        = 0;
    protected $couponCode		        = "";

    public function __construct(
        ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings, $paramItemId, $paramPriceGroupId, $paramCouponCode, $paramTaxPercentage
    ) {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;
        $this->settings = $paramSettings;

        $this->itemId = StaticValidator::getValidPositiveInteger($paramItemId, 0);
        $this->priceGroupId = StaticValidator::getValidPositiveInteger($paramPriceGroupId, 0);
        $this->couponCode = sanitize_text_field($paramCouponCode);

        $this->priceCalculationType = StaticValidator::getValidSetting($paramSettings, 'conf_price_calculation_type', 'positive_integer', 1, array(1, 2, 3));
        $this->currencySymbol = StaticValidator::getValidSetting($paramSettings, 'conf_currency_symbol', "textval", "$");
        $this->currencyCode = StaticValidator::getValidSetting($paramSettings, 'conf_currency_code', "textval", "USD");
        $this->currencySymbolLocation = StaticValidator::getValidSetting($paramSettings, 'conf_currency_symbol_location', 'positive_integer', 0, array(0, 1));

        // Dynamic tax percentage
        $this->taxPercentage = floatval($paramTaxPercentage);
        $this->showPriceWithTaxes = StaticValidator::getValidSetting($paramSettings, 'conf_show_price_with_taxes', 'positive_integer', 1, array(0, 1));
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    /**
     * Get's a item price for specified period of time without any VAT, and without discounts applied
     * Moved out from search result of single element
     * @param int $paramPickupTimestamp
     * @param int $paramReturnTimestamp
     * @param $paramDiscountPeriodFrom
     * @param $paramDiscountPeriodTill
     * @param array $paramArrDiscountsToCalculate - array("DURATION_WITH_MIN_ADVANCE_WITHOUT_COUPON", "DURATION_WITH_MAX_ADVANCE_WITHOUT_COUPON", "MAX_WITHOUT_COUPON", "PERIOD")
     * @return array
     */
	private function getUnitDataByInterval($paramPickupTimestamp, $paramReturnTimestamp, $paramDiscountPeriodFrom, $paramDiscountPeriodTill, $paramArrDiscountsToCalculate = array("PERIOD"))
	{
		$totalPrice 			        = 0;
		$totalDiscountAmountForPeriod	= 0;
		$totalDiscountAmountWithMinAdvance    = 0;
		$totalDiscountAmountWithMaxAdvance    = 0;
		$totalMaxDiscountAmount     	= 0;
		$validPickupTimestamp	        = StaticValidator::getValidPositiveInteger($paramPickupTimestamp, 0);
		$validReturnTimestamp	        = StaticValidator::getValidPositiveInteger($paramReturnTimestamp, 0);
        $validPeriodUntilPickup			= StaticValidator::getPeriod(time(), $paramPickupTimestamp, FALSE);
        $pricesPerDay = array();

        $pricesPerHour= array();

		if($this->priceCalculationType == 1)
		{
			
			// Price by days only
			$arrDateRangeTimestamps = StaticValidator::getDateRangeTimestampArray($validPickupTimestamp, $validReturnTimestamp);
			foreach($arrDateRangeTimestamps AS $currentDateTimestamp)
			{
				$validCurrentDateTimestamp = StaticValidator::getValidPositiveInteger($currentDateTimestamp);
				$currentDayOfWeekSqlField = 'daily_rate_'.strtolower(date('D', $validCurrentDateTimestamp + get_option( 'gmt_offset' ) * 3600));
                $arrPlanIdAndRate = $this->getPlanIdAndRateByTimestamp($currentDayOfWeekSqlField, $validCurrentDateTimestamp);

                // 3 - Total Price and Single Item Discount Amount (W/out VAT)
                $totalPrice += $arrPlanIdAndRate['price_plan_rate'];

                // Discounts calculation
                $objDiscountManager = new PricePlanDiscountManager($this->conf, $this->lang, $this->settings, $arrPlanIdAndRate['price_plan_id']);
                if(in_array("DURATION_WITH_MIN_ADVANCE_WITHOUT_COUPON", $paramArrDiscountsToCalculate))
                {
                    $totalDiscountAmountWithMinAdvance += $arrPlanIdAndRate['price_plan_rate'] * ($objDiscountManager->getTotalPercentageWithMinAdvanceWithoutCoupon($paramDiscountPeriodFrom) / 100);
                }
                if(in_array("DURATION_WITH_MAX_ADVANCE_WITHOUT_COUPON", $paramArrDiscountsToCalculate))
                {
                    $totalDiscountAmountWithMaxAdvance += $arrPlanIdAndRate['price_plan_rate'] * ($objDiscountManager->getTotalPercentageWithMaxAdvanceWithoutCoupon($paramDiscountPeriodTill) / 100);
                }
                if(in_array("MAX_WITHOUT_COUPON", $paramArrDiscountsToCalculate))
                {
                    $totalMaxDiscountAmount += $arrPlanIdAndRate['price_plan_rate'] * ($objDiscountManager->getMaxTotalPercentageWithoutCoupon() / 100);
                }
                if(in_array("PERIOD", $paramArrDiscountsToCalculate))
                {
                    $minTotalDiscountPercentage = $objDiscountManager->getTotalPercentageByPeriod($validPeriodUntilPickup, $paramDiscountPeriodTill);
                    $totalDiscountAmountForPeriod += $arrPlanIdAndRate['price_plan_rate'] * ($minTotalDiscountPercentage / 100);
                }
			}

		} elseif($this->priceCalculationType == 2)
		{
			// Price by hours
			$arrHourRangeTimestamps = StaticValidator::getHourRangeTimestampArray($validPickupTimestamp, $validReturnTimestamp);
			foreach($arrHourRangeTimestamps AS $currentHourTimestamp)
			{
				$validCurrentHourTimestamp = StaticValidator::getValidPositiveInteger($currentHourTimestamp);
				$currentDayOfWeekSqlField = 'hourly_rate_'.strtolower(date('D', $validCurrentHourTimestamp + get_option( 'gmt_offset' ) * 3600));
                $arrPlanIdAndRate = $this->getPlanIdAndRateByTimestamp($currentDayOfWeekSqlField, $validCurrentHourTimestamp);

                // 3 - Total Price and Single Item Discount Amount (W/out VAT)
                $totalPrice += $arrPlanIdAndRate['price_plan_rate'];

                // Discounts calculation
                $objDiscountManager = new PricePlanDiscountManager($this->conf, $this->lang, $this->settings, $arrPlanIdAndRate['price_plan_id']);
                if(in_array("DURATION_WITH_MIN_ADVANCE_WITHOUT_COUPON", $paramArrDiscountsToCalculate))
                {
                    $totalDiscountAmountWithMinAdvance += $arrPlanIdAndRate['price_plan_rate'] * ($objDiscountManager->getTotalPercentageWithMinAdvanceWithoutCoupon($paramDiscountPeriodFrom) / 100);
                }
                if(in_array("DURATION_WITH_MAX_ADVANCE_WITHOUT_COUPON", $paramArrDiscountsToCalculate))
                {
                    $totalDiscountAmountWithMaxAdvance += $arrPlanIdAndRate['price_plan_rate'] * ($objDiscountManager->getTotalPercentageWithMaxAdvanceWithoutCoupon($paramDiscountPeriodTill) / 100);
                }
                if(in_array("MAX_WITHOUT_COUPON", $paramArrDiscountsToCalculate))
                {
                    $totalMaxDiscountAmount += $arrPlanIdAndRate['price_plan_rate'] * ($objDiscountManager->getMaxTotalPercentageWithoutCoupon() / 100);
                }
                if(in_array("PERIOD", $paramArrDiscountsToCalculate))
                {
                    $minTotalDiscountPercentage = $objDiscountManager->getTotalPercentageByPeriod($validPeriodUntilPickup, $paramDiscountPeriodTill);
                    $totalDiscountAmountForPeriod += $arrPlanIdAndRate['price_plan_rate'] * ($minTotalDiscountPercentage / 100);
                }
                
			}

		} elseif($this->priceCalculationType == 3)
		{
			
			// Combined - price by days & hours

			/************************************************************************************/
			// A - Count price for all days except last one
			$arrDateAndHourRangeTimestamps = StaticValidator::getDayRangeAndHourRangeTimestampArray($validPickupTimestamp, $validReturnTimestamp);

			foreach($arrDateAndHourRangeTimestamps['days'] AS $currentDateTimestamp)
			{
				$validCurrentDateTimestamp = StaticValidator::getValidPositiveInteger($currentDateTimestamp);
				$currentDayOfWeekSqlField = 'daily_rate_'.strtolower(date('D', $validCurrentDateTimestamp + get_option( 'gmt_offset' ) * 3600));
                $arrPlanIdAndRate = $this->getPlanIdAndRateByTimestamp($currentDayOfWeekSqlField, $validCurrentDateTimestamp);

                // 3 - Total Price and Single Item Discount Amount (W/out VAT)
                $totalPrice += $arrPlanIdAndRate['price_plan_rate'];
                array_push($pricesPerDay, $arrPlanIdAndRate['price_plan_rate']);
                
                // Discounts calculation
                $objDiscountManager = new PricePlanDiscountManager($this->conf, $this->lang, $this->settings, $arrPlanIdAndRate['price_plan_id']);
                if(in_array("DURATION_WITH_MIN_ADVANCE_WITHOUT_COUPON", $paramArrDiscountsToCalculate))
                {
                    $totalDiscountAmountWithMinAdvance += $arrPlanIdAndRate['price_plan_rate'] * ($objDiscountManager->getTotalPercentageWithMinAdvanceWithoutCoupon($paramDiscountPeriodFrom) / 100);
                }
                if(in_array("DURATION_WITH_MAX_ADVANCE_WITHOUT_COUPON", $paramArrDiscountsToCalculate))
                {
                    $totalDiscountAmountWithMaxAdvance += $arrPlanIdAndRate['price_plan_rate'] * ($objDiscountManager->getTotalPercentageWithMaxAdvanceWithoutCoupon($paramDiscountPeriodTill) / 100);
                }
                if(in_array("MAX_WITHOUT_COUPON", $paramArrDiscountsToCalculate))
                {
                    $totalMaxDiscountAmount += $arrPlanIdAndRate['price_plan_rate'] * ($objDiscountManager->getMaxTotalPercentageWithoutCoupon() / 100);
                }
                if(in_array("PERIOD", $paramArrDiscountsToCalculate))
                {
                    $minTotalDiscountPercentage = $objDiscountManager->getTotalPercentageByPeriod($validPeriodUntilPickup, $paramDiscountPeriodTill);
                    $totalDiscountAmountForPeriod += $arrPlanIdAndRate['price_plan_rate'] * ($minTotalDiscountPercentage / 100);
                }
			}

			/************************************************************************************/
			// B - Count the whole day price for last day by using daily price (0th price plan is existing for sure)
			$totalPriceForLastDayByDailyPrice = 0;
			$totalDiscountAmountWithMinAdvanceForLastDayByDailyPrice = 0;
			$totalDiscountAmountWithMaxAdvanceForLastDayByDailyPrice = 0;
			$totalMaxDiscountAmountForLastDayByDailyPrice = 0;
			$totalDiscountAmountForPeriodForLastDayByDailyPrice = 0;
			if(isset($arrDateAndHourRangeTimestamps['hours'][0]))
			{
				$validLastDateTimestamp = StaticValidator::getValidPositiveInteger($arrDateAndHourRangeTimestamps['hours'][0]);
				$lastDateDayOfWeekSqlField = 'daily_rate_'.strtolower(date('D', $validLastDateTimestamp + get_option( 'gmt_offset' ) * 3600));
                $arrPlanIdAndRate = $this->getPlanIdAndRateByTimestamp($lastDateDayOfWeekSqlField, $validLastDateTimestamp);

                // 3 - Total Price and Single Item Discount Amount (W/out VAT)
                $totalPriceForLastDayByDailyPrice = $arrPlanIdAndRate['price_plan_rate'];
                array_push($pricesPerDay, $arrPlanIdAndRate['price_plan_rate']);
                // Discounts calculation
                $objDiscountManager = new PricePlanDiscountManager($this->conf, $this->lang, $this->settings, $arrPlanIdAndRate['price_plan_id']);
                if(in_array("DURATION_WITH_MIN_ADVANCE_WITHOUT_COUPON", $paramArrDiscountsToCalculate))
                {
                    $totalDiscountAmountWithMinAdvanceForLastDayByDailyPrice = $arrPlanIdAndRate['price_plan_rate'] * ($objDiscountManager->getTotalPercentageWithMinAdvanceWithoutCoupon($paramDiscountPeriodFrom) / 100);
                }
                if(in_array("DURATION_WITH_MAX_ADVANCE_WITHOUT_COUPON", $paramArrDiscountsToCalculate))
                {
                    $totalDiscountAmountWithMaxAdvanceForLastDayByDailyPrice = $arrPlanIdAndRate['price_plan_rate'] * ($objDiscountManager->getTotalPercentageWithMaxAdvanceWithoutCoupon($paramDiscountPeriodTill) / 100);
                }
                if(in_array("MAX_WITHOUT_COUPON", $paramArrDiscountsToCalculate))
                {
                    $totalMaxDiscountAmountForLastDayByDailyPrice = $arrPlanIdAndRate['price_plan_rate'] * ($objDiscountManager->getMaxTotalPercentageWithoutCoupon() / 100);
                }
                if(in_array("PERIOD", $paramArrDiscountsToCalculate))
                {
                    $minTotalDiscountPercentage = $objDiscountManager->getTotalPercentageByPeriod($validPeriodUntilPickup, $paramDiscountPeriodTill);
                    $totalDiscountAmountForPeriodForLastDayByDailyPrice = $arrPlanIdAndRate['price_plan_rate'] * ($minTotalDiscountPercentage / 100);
                }
			}

			/************************************************************************************/
			// C - Count price for all hours on last day
			$totalPriceForLastDayByHourlyPrice = 0;
			$totalDiscountAmountWithMinAdvanceForLastDayByHourlyPrice = 0;
			$totalDiscountAmountWithMaxAdvanceForLastDayByHourlyPrice = 0;
			$totalMaxDiscountAmountForLastDayByHourlyPrice = 0;
			$totalDiscountAmountForPeriodForLastDayByHourlyPrice = 0;
			foreach($arrDateAndHourRangeTimestamps['hours'] AS $currentHourTimestamp)
			{
				$validCurrentHourTimestamp = StaticValidator::getValidPositiveInteger($currentHourTimestamp);
				$currentDayOfWeekSqlField = 'hourly_rate_'.strtolower(date('D', $validCurrentHourTimestamp + get_option( 'gmt_offset' ) * 3600));
                $arrPlanIdAndRate = $this->getPlanIdAndRateByTimestamp($currentDayOfWeekSqlField, $validCurrentHourTimestamp);

                // 3 - Total Price and Single Item Discount Amount (W/out VAT)
                $totalPriceForLastDayByHourlyPrice += $arrPlanIdAndRate['price_plan_rate'];
                
                array_push($pricesPerHour, $arrPlanIdAndRate['price_plan_rate']);
                // Discounts calculation
                $objDiscountManager = new PricePlanDiscountManager($this->conf, $this->lang, $this->settings, $arrPlanIdAndRate['price_plan_id']);
                if(in_array("DURATION_WITH_MIN_ADVANCE_WITHOUT_COUPON", $paramArrDiscountsToCalculate))
                {
                    $totalDiscountAmountWithMinAdvanceForLastDayByHourlyPrice += $arrPlanIdAndRate['price_plan_rate'] * ($objDiscountManager->getTotalPercentageWithMinAdvanceWithoutCoupon($paramDiscountPeriodFrom) / 100);
                }
                if(in_array("DURATION_WITH_MAX_ADVANCE_WITHOUT_COUPON", $paramArrDiscountsToCalculate))
                {
                    $totalDiscountAmountWithMaxAdvanceForLastDayByHourlyPrice += $arrPlanIdAndRate['price_plan_rate'] * ($objDiscountManager->getTotalPercentageWithMaxAdvanceWithoutCoupon($paramDiscountPeriodTill) / 100);
                }
                if(in_array("MAX_WITHOUT_COUPON", $paramArrDiscountsToCalculate))
                {
                    $totalMaxDiscountAmountForLastDayByHourlyPrice += $arrPlanIdAndRate['price_plan_rate'] * ($objDiscountManager->getMaxTotalPercentageWithoutCoupon() / 100);
                }
                if(in_array("PERIOD", $paramArrDiscountsToCalculate))
                {
                    $minTotalDiscountPercentage = $objDiscountManager->getTotalPercentageByPeriod($validPeriodUntilPickup, $paramDiscountPeriodTill);
                    $totalDiscountAmountForPeriodForLastDayByHourlyPrice += $arrPlanIdAndRate['price_plan_rate'] * ($minTotalDiscountPercentage / 100);
                }
			}

			/************************************************************************************/
			// D - Add either all hours price or all day price, whichever is cheaper

           

            $countedPerDayPriceArray = array();
            $countedPerHourPiceArray = array();

			if($totalPriceForLastDayByHourlyPrice > $totalPriceForLastDayByDailyPrice)
			{
				// Add whole day price
				$totalPrice += $totalPriceForLastDayByDailyPrice;
                $totalDiscountAmountWithMinAdvance += $totalDiscountAmountWithMinAdvanceForLastDayByDailyPrice;
                $totalDiscountAmountWithMaxAdvance += $totalDiscountAmountWithMaxAdvanceForLastDayByDailyPrice;
                $totalMaxDiscountAmount += $totalMaxDiscountAmountForLastDayByDailyPrice;
                $totalDiscountAmountForPeriod += $totalDiscountAmountForPeriodForLastDayByDailyPrice;
                //here we are counting diffrent prices per day and writing number of occurences of the same price in array
                $countedPerDayPriceArray= array_count_values($pricesPerDay);
			} else
			{
				// Add hours price
				$totalPrice += $totalPriceForLastDayByHourlyPrice;
                $totalDiscountAmountWithMinAdvance += $totalDiscountAmountWithMinAdvanceForLastDayByHourlyPrice;
                $totalDiscountAmountWithMaxAdvance += $totalDiscountAmountWithMaxAdvanceForLastDayByHourlyPrice;
                $totalMaxDiscountAmount += $totalMaxDiscountAmountForLastDayByHourlyPrice;
                $totalDiscountAmountForPeriod += $totalDiscountAmountForPeriodForLastDayByHourlyPrice;
                //here we are counting diffrent prices per day and writing number of occurences of the same price in array and the same for the hour
                if(!($totalPriceForLastDayByHourlyPrice == $totalPriceForLastDayByDailyPrice)){
                    array_pop($pricesPerDay);
                }
                $countedPerDayPriceArray= array_count_values($pricesPerDay);
                $countedPerHourPiceArray= array_count_values($pricesPerHour);
			}
		}
           
            $returnPricePerDayString='';
            $returnPricePerHourString='';

            if (!empty($countedPerDayPriceArray)){
                foreach ($countedPerDayPriceArray as $key => $value) {
                    $returnPricePerDayString.=$value.' x $'.$key.'/day </br>';
                }
            }

            if (!empty($countedPerHourPiceArray)){
                foreach ($countedPerHourPiceArray as $key => $value) {
                    $returnPricePerHourString.=$value.' x $'.$key.'/hr';
                }
            }



        // We must round price and all discount amounts with 2 chars after comma, to avoid issues with multiplied amounts print and we are retuning explonation 
        // of how the price is caclucated
        return array(
            'price' => round($totalPrice, 2),
            'discount_amount_with_min_advance' => round($totalDiscountAmountWithMinAdvance, 2),
            'discount_amount_with_max_advance' => round($totalDiscountAmountWithMaxAdvance, 2),
            'max_discount_amount' => round($totalMaxDiscountAmount, 2),
            'discount_amount_for_period' => round($totalDiscountAmountForPeriod, 2),
            'price_per_day_conted' => $returnPricePerDayString,
            'price_per_hour_conted' => $returnPricePerHourString,
        );
	}


    /**
     * Internal function to split-and-manage the code easier for getUnitPriceAndDiscountAmountByInterval(..) method
     * @param $paramDayOfWeekSqlField
     * @param $paramTimestamp
     * @return array
     */
    private function getPlanIdAndRateByTimestamp($paramDayOfWeekSqlField, $paramTimestamp)
    {
        $pricePlanId = 0;
        $pricePlanRate = 0;
        $validPriceGroupId 	 = StaticValidator::getValidPositiveInteger($this->priceGroupId, 0);
        $validCouponCode 	 = esc_sql(sanitize_text_field($this->couponCode));
        $validDayOfWeekField = sanitize_key($paramDayOfWeekSqlField);
        $validTimestamp 	 = StaticValidator::getValidPositiveInteger($paramTimestamp, 0);

        // SPEED OPTIMIZATION NOTE: For better system speed we combine two queries to one
        // Seasonal and regular price SQL - order by DESC LIMIT 1 here gives use seasonal price first if exists
        // First check by coupon code, then by seasonal price
        $sqlSeasonalOrRegularPrice = "
						SELECT price_plan_id, {$validDayOfWeekField} AS price_plan_rate, seasonal_price
						FROM {$this->conf->getPrefix()}price_plans
						WHERE price_group_id='{$validPriceGroupId}' AND 
						(
						    seasonal_price='0' OR
						    (
						        seasonal_price='1'
						        AND ('{$validTimestamp}' BETWEEN start_timestamp AND end_timestamp)
						    )
						) AND (coupon_code='{$validCouponCode}' OR coupon_code='') 
						AND blog_id='{$this->conf->getBlogId()}'
						ORDER BY coupon_code!='' DESC, seasonal_price DESC LIMIT 1
					";

        // Query for seasonal or regular prices
        $seasonalOrRegularPriceData = $this->conf->getInternalWPDB()->get_row($sqlSeasonalOrRegularPrice, ARRAY_A);
        if(!is_null($seasonalOrRegularPriceData))
        {
            $pricePlanId = $seasonalOrRegularPriceData['price_plan_id'];
            $pricePlanRate = $seasonalOrRegularPriceData['price_plan_rate'];
        }

        if($this->debugMode)
        {
            echo "<br />-&gt;  Price Group Id/Plan Id: {$validPriceGroupId}/{$pricePlanId} , ";
            echo "Weekday: {$paramDayOfWeekSqlField}, ";
            echo "Local Date/Hour: ".date("Y-m-d H:i:s", $paramTimestamp + get_option( 'gmt_offset' ) * 3600)." (Unix, GMT: {$paramTimestamp}), ";
            echo "Price w/o VAT (seasonal or regular): ".$pricePlanRate.", ";
            echo "Price type: ".(isset($seasonalOrRegularPriceData['seasonal_price']) && $seasonalOrRegularPriceData['seasonal_price'] == 1 ? "Seasonal" : "Regular");
        }

        return array(
            'price_plan_id' => $pricePlanId,
            'price_plan_rate' => $pricePlanRate,
        );
    }

	public function getUnitPriceDetailsByInterval($paramPickupTimestamp, $paramReturnTimestamp)
	{
		return $this->getPriceDetails($paramPickupTimestamp, $paramReturnTimestamp, 1, FALSE);
	}

	public function getMultipliedPriceDetailsByInterval($paramPickupTimestamp, $paramReturnTimestamp, $paramMultiplier)
	{
		return $this->getPriceDetails($paramPickupTimestamp, $paramReturnTimestamp, $paramMultiplier, FALSE);
	}

    /**
     * NOTE: FASTEST METHOD - does not include deposit and prepayment details
     */
    public function getWeekCheapestDayMinimalPriceDetails()
    {
        $weekPriceDetails = $this->getWeekMinimalPriceDetails();
        return $weekPriceDetails['cheapest_day'];
    }

    public function getWeekPriciestDayMinimalPriceDetails()
    {
        $weekPriceDetails = $this->getWeekMinimalPriceDetails();
        return $weekPriceDetails['priciest_day'];
    }


    /**
     * SQL optimized method
     * @return array
     */
    private function getWeekMinimalPriceDetails()
    {
        $localPickupTimestamp = time() + get_option( 'gmt_offset' ) * 3600;
        $localReturnTimestamp = ($this->priceCalculationType == "2" ? time()+3600 : time()+86400) + get_option( 'gmt_offset' ) * 3600;

        $arrPricesDetails = array(
            0 => $this->getPriceDetails($localPickupTimestamp, $localReturnTimestamp, 1, TRUE),
            1 => $this->getPriceDetails($localPickupTimestamp + 86400 * 1, $localReturnTimestamp + 86400 * 1, 1, TRUE),
            2 => $this->getPriceDetails($localPickupTimestamp + 86400 * 2, $localReturnTimestamp + 86400 * 2, 1, TRUE),
            3 => $this->getPriceDetails($localPickupTimestamp + 86400 * 3, $localReturnTimestamp + 86400 * 3, 1, TRUE),
            4 => $this->getPriceDetails($localPickupTimestamp + 86400 * 4, $localReturnTimestamp + 86400 * 4, 1, TRUE),
            5 => $this->getPriceDetails($localPickupTimestamp + 86400 * 5, $localReturnTimestamp + 86400 * 5, 1, TRUE),
            6 => $this->getPriceDetails($localPickupTimestamp + 86400 * 6, $localReturnTimestamp + 86400 * 6, 1, TRUE),
        );

        $arrPrices = array(
            0 => $arrPricesDetails[0]['unit']['subtotal_price_dynamic'],
            1 => $arrPricesDetails[1]['unit']['subtotal_price_dynamic'],
            2 => $arrPricesDetails[2]['unit']['subtotal_price_dynamic'],
            3 => $arrPricesDetails[3]['unit']['subtotal_price_dynamic'],
            4 => $arrPricesDetails[4]['unit']['subtotal_price_dynamic'],
            5 => $arrPricesDetails[5]['unit']['subtotal_price_dynamic'],
            6 => $arrPricesDetails[6]['unit']['subtotal_price_dynamic'],
        );

        $minPriceIds = array_keys($arrPrices, min($arrPrices));
        $minPriceDetails = $arrPricesDetails[$minPriceIds[0]];

        $maxPriceIds = array_keys($arrPrices, max($arrPrices));
        $maxPriceDetails = $arrPricesDetails[$maxPriceIds[0]];

        $minimalPriceDetails = array(
            "cheapest_day" => $minPriceDetails,
            "priciest_day" => $maxPriceDetails,
        );

        return $minimalPriceDetails;
    }

    /**
     * @Note - Items has different prices for each day of week
     * @param $paramDiscountPeriodFrom
     * @param $paramDiscountPeriodTill
     * @return mixed
     */
    public function getPriceWithoutCouponDataInWeek($paramDiscountPeriodFrom, $paramDiscountPeriodTill)
    {
        $localPickupTimestamp = time() + get_option( 'gmt_offset' ) * 3600;
        $localReturnTimestamp = ($this->priceCalculationType == "2" ? time()+3600 : time()+86400) + get_option( 'gmt_offset' ) * 3600;

        // Get Minimal Dynamic Unit Price without Discount By Interval for today and 6 more days
        $arrUnitData = array(
            0 => $this->getUnitDataByInterval(
                $localPickupTimestamp, $localReturnTimestamp, $paramDiscountPeriodFrom, $paramDiscountPeriodTill,
                array("DURATION_WITH_MIN_ADVANCE_WITHOUT_COUPON", "DURATION_WITH_MAX_ADVANCE_WITHOUT_COUPON")
            ),
            1 => $this->getUnitDataByInterval(
                $localPickupTimestamp + 86400 * 1, $localReturnTimestamp + 86400 * 1, $paramDiscountPeriodFrom, $paramDiscountPeriodTill,
                array("DURATION_WITH_MIN_ADVANCE_WITHOUT_COUPON", "DURATION_WITH_MAX_ADVANCE_WITHOUT_COUPON")
            ),
            2 => $this->getUnitDataByInterval(
                $localPickupTimestamp + 86400 * 2, $localReturnTimestamp + 86400 * 2, $paramDiscountPeriodFrom, $paramDiscountPeriodTill,
                array("DURATION_WITH_MIN_ADVANCE_WITHOUT_COUPON", "DURATION_WITH_MAX_ADVANCE_WITHOUT_COUPON")
            ),
            3 => $this->getUnitDataByInterval(
                $localPickupTimestamp + 86400 * 3, $localReturnTimestamp + 86400 * 3, $paramDiscountPeriodFrom, $paramDiscountPeriodTill,
                array("DURATION_WITH_MIN_ADVANCE_WITHOUT_COUPON", "DURATION_WITH_MAX_ADVANCE_WITHOUT_COUPON")
            ),
            4 => $this->getUnitDataByInterval(
                $localPickupTimestamp + 86400 * 4, $localReturnTimestamp + 86400 * 4, $paramDiscountPeriodFrom, $paramDiscountPeriodTill,
                array("DURATION_WITH_MIN_ADVANCE_WITHOUT_COUPON", "DURATION_WITH_MAX_ADVANCE_WITHOUT_COUPON")
            ),
            5 => $this->getUnitDataByInterval(
                $localPickupTimestamp + 86400 * 5, $localReturnTimestamp + 86400 * 5, $paramDiscountPeriodFrom, $paramDiscountPeriodTill,
                array("DURATION_WITH_MIN_ADVANCE_WITHOUT_COUPON", "DURATION_WITH_MAX_ADVANCE_WITHOUT_COUPON")
            ),
            6 => $this->getUnitDataByInterval(
                $localPickupTimestamp + 86400 * 6, $localReturnTimestamp + 86400 * 6, $paramDiscountPeriodFrom, $paramDiscountPeriodTill,
                array("DURATION_WITH_MIN_ADVANCE_WITHOUT_COUPON", "DURATION_WITH_MAX_ADVANCE_WITHOUT_COUPON")
            ),
        );

        $arrPricesWithMaxDiscount = array(
            0 => $arrUnitData[0]['price'] - $arrUnitData[0]['discount_amount_with_max_advance'],
            1 => $arrUnitData[1]['price'] - $arrUnitData[1]['discount_amount_with_max_advance'],
            2 => $arrUnitData[2]['price'] - $arrUnitData[2]['discount_amount_with_max_advance'],
            3 => $arrUnitData[3]['price'] - $arrUnitData[3]['discount_amount_with_max_advance'],
            4 => $arrUnitData[4]['price'] - $arrUnitData[4]['discount_amount_with_max_advance'],
            5 => $arrUnitData[5]['price'] - $arrUnitData[5]['discount_amount_with_max_advance'],
            6 => $arrUnitData[6]['price'] - $arrUnitData[6]['discount_amount_with_max_advance'],
        );
        $arrPricesWithMinDiscount = array(
            0 => $arrUnitData[0]['price'] - $arrUnitData[0]['discount_amount_with_min_advance'],
            1 => $arrUnitData[1]['price'] - $arrUnitData[1]['discount_amount_with_min_advance'],
            2 => $arrUnitData[2]['price'] - $arrUnitData[2]['discount_amount_with_min_advance'],
            3 => $arrUnitData[3]['price'] - $arrUnitData[3]['discount_amount_with_min_advance'],
            4 => $arrUnitData[4]['price'] - $arrUnitData[4]['discount_amount_with_min_advance'],
            5 => $arrUnitData[5]['price'] - $arrUnitData[5]['discount_amount_with_min_advance'],
            6 => $arrUnitData[6]['price'] - $arrUnitData[6]['discount_amount_with_min_advance'],
        );

        $minPriceIds = array_keys($arrPricesWithMaxDiscount, min($arrPricesWithMaxDiscount));
        $minPriceDetails = $arrUnitData[$minPriceIds[0]];

        $maxPriceIds = array_keys($arrPricesWithMinDiscount, max($arrPricesWithMinDiscount));
        $maxPriceDetails = $arrUnitData[$maxPriceIds[0]];


        if($this->showPriceWithTaxes == 1)
        {
            $minPrice = ($minPriceDetails['price'] - $minPriceDetails['discount_amount_with_max_advance']) * (1 + $this->taxPercentage / 100);
            $maxPrice = ($maxPriceDetails['price'] - $maxPriceDetails['discount_amount_with_min_advance']) * (1 + $this->taxPercentage / 100);
            $discountForMinPrice = $minPriceDetails['discount_amount_with_max_advance'] * (1 + $this->taxPercentage / 100);
            $discountForMaxPrice = $maxPriceDetails['discount_amount_with_min_advance'] * (1 + $this->taxPercentage / 100);
        } else
        {
            $minPrice = $minPriceDetails['price'] - $minPriceDetails['discount_amount_with_max_advance'];
            $maxPrice = $maxPriceDetails['price'] - $maxPriceDetails['discount_amount_with_min_advance'];
            $discountForMinPrice = $minPriceDetails['discount_amount_with_max_advance'];
            $discountForMaxPrice = $maxPriceDetails['discount_amount_with_min_advance'];
        }

        // Texts
        $printMinPriceLong = number_format_i18n($minPrice, 2);
        $printMinPrice = number_format_i18n($minPrice, 0);
        $printDiscountForMinPrice = number_format_i18n(round($discountForMinPrice, 1), 1);

        // Only duration discount applied
        $printMaxPriceLong = number_format_i18n($maxPrice, 2);
        $printMaxPrice = number_format_i18n($maxPrice, 0);
        $printDiscountForMaxPrice = number_format_i18n(round($discountForMaxPrice, 1), 1);

        if($this->priceGroupId == 0)
        {
            $printPriceDescription = $this->lang->getText('NRS_GET_A_QUOTE_TEXT');
            $printPrice = $this->lang->getText('NRS_INQUIRE_TEXT');
        } else
        {
            $printPriceDescription = '';
            $printPriceDescription .= ($this->priceCalculationType == 2 ? $this->lang->getText('NRS_PRICE_FOR_HOUR_FROM_TEXT') : $this->lang->getText('NRS_PRICE_FOR_DAY_FROM_TEXT')).' ';
            if($minPrice < $maxPrice)
            {
                $printPriceDescription .= $this->currencySymbol.' '.$printMinPriceLong.', ';
                $printPriceDescription .= $this->lang->getText('NRS_PRICE_WITH_APPLIED_TEXT').' ';
                $printPriceDescription .= $this->currencySymbol.' '.$printDiscountForMinPrice.' '.$this->lang->getText('NRS_WITH_APPLIED_DISCOUNT_TEXT');

                $printPriceDescription .= ' - '.$this->currencySymbol.' '.$printMaxPriceLong.', ';
                $printPriceDescription .= $this->lang->getText('NRS_PRICE_WITH_APPLIED_TEXT').' ';
                $printPriceDescription .= $this->currencySymbol.' '.$printDiscountForMaxPrice.' '.$this->lang->getText('NRS_WITH_APPLIED_DISCOUNT_TEXT');

                $printPrice = $printMinPrice.'-'.$printMaxPrice.' '.$this->currencyCode;
            } else
            {
                $printPriceDescription = $this->currencySymbol.' '.$printMinPriceLong.', ';
                $printPriceDescription .= $this->lang->getText('NRS_PRICE_WITH_APPLIED_TEXT').' '.$this->currencySymbol.' '.$printDiscountForMinPrice.' ';
                $printPriceDescription .= $this->lang->getText('NRS_WITH_APPLIED_DISCOUNT_TEXT');
                $printPrice = $printMinPrice.' '.$this->currencyCode;
            }
        }


        if($this->debugMode)
        {
            echo "-&gt;  Local pickup/return timestamps range: {$localPickupTimestamp} - {$localReturnTimestamp} --&gt; ".($localPickupTimestamp + 86400 * 6)." - ".($localReturnTimestamp + 86400 * 6).",<br />";

            echo "Week&#39;s Cheapest Day Price Without Coupon (w/out VAT): {$minPriceDetails['price']} - {$minPriceDetails['max_discount_amount']} = ";
            echo ($minPriceDetails['price'] - $minPriceDetails['max_discount_amount']).",<br />";

            echo "Week&#39;s Priciest Day Price Without Coupon (w/out VAT): {$maxPriceDetails['price']} - {$maxPriceDetails['min_discount_amount']} =  ";
            echo ($maxPriceDetails['price'] - $maxPriceDetails['min_discount_amount'])."<br /><br />";
        }
        $priceDataInWeek['print_price_description'] = $printPriceDescription;
        $priceDataInWeek['print_price'] = $printPrice;

        return $priceDataInWeek;
    }

    /**
     * Most advanced price plan pricing function EVER. Final
     * @param int $paramPickupTimestamp
     * @param int $paramReturnTimestamp
     * @param int $paramMultiplier - how many times multiply price for ['multiplied'] stack
     * @param bool $paramMinimalPrice - should we use a minimal price instead of period
     * @return array
     */
	private function getPriceDetails($paramPickupTimestamp, $paramReturnTimestamp, $paramMultiplier = 1, $paramMinimalPrice = FALSE)
	{
		// 0. Item Id

		// 1: Single Item Subtotal (W/out VAT & w/out Discount)				= I.e. 100 EUR (4x25 EUR Daily Price)      / getPriceByPeriod()
		// --> 1A: Single Item Subtotal (With VAT)
		// --> 1B: Single Item Subtotal (Dynamic - with or w/out VAT)
		// 2: Single Item Discount Percentage      							= I.e. 10%                                 / getTotalDiscountPercentageByPeriod()
		// 3: Single Item Discount Amount (W/out VAT)	 					= I.e. 10 EUR (100 EUR * 10%)
		// --> 3A: Single Item Discount Amount (With VAT)
		// --> 3B: Single Item Discount Amount (Dynamic - with or w/out VAT)
		// 4: Single Item Discounted Total (W/out VAT)     					= I.e. 90 EUR (100 EUR - 10%)
		// --> 4A: Single Item Discounted Total (With VAT)					= I.e. 108.90 EUR (90 EUR + 18.90 EUR)
		// --> 4B: Single Item Discounted Total (Dynamic - with or w/out VAT)
        // 5: Single Item Fixed Deposit Amount (No VAT Exist)				= I.e. 250 EUR 						       / getFixedDepositAmountByPeriod()
		// 6: Single Item Subtotal VAT Amount (Tax)          				= I.e. 21 EUR (100 EUR * TAX_PERCENTAGE)
		// 7: Single Item Discounted VAT Amount (Tax) 						= I.e. 18.90 EUR (90 EUR * TAX_PERCENTAGE)

        if($this->debugMode == 2)
        {
            echo "<br />---------------------------------------------------------------------------------------------------------";
        }

		// Part 1:  Valid Item Id
		$validItemId 					    = StaticValidator::getValidPositiveInteger($this->itemId, 0);
		$validPickupTimestamp				= StaticValidator::getValidPositiveInteger($paramPickupTimestamp, 0);
		$validReturnTimestamp				= StaticValidator::getValidPositiveInteger($paramReturnTimestamp, 0);
        $validPeriod                        = StaticValidator::getPeriod($validPickupTimestamp, $validReturnTimestamp, FALSE);
		$validMultiplier					= StaticValidator::getValidPositiveInteger($paramMultiplier, 1);


		// Part 3: Item Prices
		// 1, 1A, 1B - Single Item Subtotal (W/out VAT), (With VAT),(Dynamic - with or w/out VAT) and get the returned explonation of how the price is caclucated
        $arrDiscountsToCalculate            = $paramMinimalPrice ? array("MAX_WITHOUT_COUPON") : array("PERIOD");
        $arrUnitData                        = $this->getUnitDataByInterval($validPickupTimestamp, $validReturnTimestamp, $validPeriod, $validPeriod, $arrDiscountsToCalculate);
        $howThePriceWasCalculated= $arrUnitData['price_per_day_conted'];
        $howThePriceWasCalculated.= $arrUnitData['price_per_hour_conted'];
		$unitSubtotalPriceWithoutTax  		= $arrUnitData['price'];
		$unitSubtotalPriceWithTax 	 		= $unitSubtotalPriceWithoutTax * (1 + $this->taxPercentage / 100);
		$unitSubtotalPriceDynamic 	 		= $this->showPriceWithTaxes == 1 ? $unitSubtotalPriceWithTax : $unitSubtotalPriceWithoutTax;

		// 2 - Single Item Discount Percentage
        // Processed in the price plan

		// 3, 3A, 3B - Single Item Discount Amount (W/out VAT), (With VAT),(Dynamic - with or w/out VAT)
		$unitDiscountAmountWithoutTax		= $arrUnitData[$paramMinimalPrice ? 'max_discount_amount' : 'discount_amount_for_period'];
		$unitDiscountAmountWithTax			= $unitDiscountAmountWithoutTax + $unitDiscountAmountWithoutTax * ($this->taxPercentage / 100);
		$unitDiscountAmountDynamic			= $this->showPriceWithTaxes == 1 ? $unitDiscountAmountWithTax : $unitDiscountAmountWithoutTax;

		// 4, 4A, 4B - Single Item Discounted Total (W/out VAT), (With VAT),(Dynamic - with or w/out VAT)
		$unitDiscountedTotalWithoutTax 		=  $unitSubtotalPriceWithoutTax - $unitDiscountAmountWithoutTax;
		$unitDiscountedTotalWithTax    		=  $unitSubtotalPriceWithTax - $unitDiscountAmountWithTax;
		$unitDiscountedTotalDynamic			=  $unitSubtotalPriceDynamic - $unitDiscountAmountDynamic;
        
        // 5 - Single Item Fixed Deposit Amount (VAT for deposit do not exist)
        $objDepositManager                  = new ItemDepositManager($this->conf, $this->lang, $this->settings, $validItemId);
        $unitFixedDepositAmount 		    = $objDepositManager->getAmount();

		// 6 - Single Item Subtotal VAT Amount (Tax)
		$unitSubtotalTaxAmount 	  			= $unitSubtotalPriceWithTax - $unitSubtotalPriceWithoutTax;

		// 7 - Single Item Discounted VAT Amount (Tax)
		$unitDiscountedTaxAmount 			= $unitDiscountedTotalWithTax - $unitDiscountedTotalWithoutTax;

        // PRICE DETAILS
		$arrPriceDetails = array();
		$arrPriceDetails['element_id'] = $validItemId;
		$arrPriceDetails['element_type'] = "item";
        // We must round price and all discount amounts with 2 chars after comma, to avoid issues with multiplied amounts print
		$arrPriceDetails['unit'] = array(
			"subtotal_price" 			=> $unitSubtotalPriceWithoutTax,
			"subtotal_price_with_tax" 	=> $unitSubtotalPriceWithTax,
			"subtotal_price_dynamic" 	=> $unitSubtotalPriceDynamic,
			"discount_amount"			=> $unitDiscountAmountWithoutTax,
			"discount_amount_with_tax"	=> $unitDiscountAmountWithTax,
			"discount_amount_dynamic"	=> $unitDiscountAmountDynamic,
			"discounted_total"			=> $unitDiscountedTotalWithoutTax,
			"discounted_total_with_tax" => $unitDiscountedTotalWithTax,
			"discounted_total_dynamic"	=> $unitDiscountedTotalDynamic,
			"subtotal_tax_amount" 		=> $unitSubtotalTaxAmount,
			"discounted_tax_amount" 	=> $unitDiscountedTaxAmount,
            "fixed_deposit_amount"      => $unitFixedDepositAmount,
		);
        $arrPriceDetails['unit_per_period'] = StaticFormatter::getPerPeriodPricesArray($arrPriceDetails['unit'], $validPeriod, $this->priceCalculationType);
        $arrPriceDetails['tax_percentage'] = $this->taxPercentage;
        
        // Multiplied prices
        $arrPriceDetails['multiplier'] = $validMultiplier;
        $arrPriceDetails['multiplied'] = StaticFormatter::getMultipliedNumberArray($arrPriceDetails['unit'], $validMultiplier);
        $arrPriceDetails['multiplied_per_period'] = StaticFormatter::getMultipliedNumberArray($arrPriceDetails['unit_per_period'], $validMultiplier);

        if($this->debugMode >= 1)
        {
            echo "<br /><strong>Unit Data from \$this-&gt;getUnitDataByInterval(..., ".print_r($arrDiscountsToCalculate, TRUE)."):</strong> ".nl2br(print_r($arrUnitData, TRUE));
        }
        if($this->debugMode == 2)
        {
            // We want to do a debug here to avoid mess with printed data
            $pickupTMZStamp = $validPickupTimestamp + get_option( 'gmt_offset' ) * 3600;
            $returnTMZStamp = $validReturnTimestamp + get_option( 'gmt_offset' ) * 3600;
            $printPickupDate = date_i18n( get_option( 'date_format' ).' '.get_option( 'time_format' ), $pickupTMZStamp, TRUE);
            $printReturnDate = date_i18n( get_option( 'date_format' ).' '.get_option( 'time_format' ), $returnTMZStamp, TRUE);
            // Deep debug
            echo "<br /><strong>Returned price details for Price Plan Id={$validItemId}:</strong>";
            echo "<br />Minimal price only: ".var_export($paramMinimalPrice, TRUE);
            echo "<br />Dates: {$printPickupDate} - {$printReturnDate}";
            echo "<br />UNIX, GMT period: {$validPickupTimestamp} - {$validReturnTimestamp}";
            echo "<br />Unit discount amount (without tax): {$unitDiscountAmountWithoutTax}";
            echo "<br />Unit discount amount (with tax): {$unitDiscountAmountWithTax}";
            echo "<br />Unit fixed deposit amount: {$unitFixedDepositAmount}";
            echo '<div style="font-size:12px;line-height:18px;color:navy;">'.nl2br(print_r($arrPriceDetails, TRUE)).'</div>';
            echo "---------------------------------------------------------------------------------------------------------";
            echo "<br /><br />";
        }

        // Unit prints
		$arrPriceDetails['unit_tiny_print'] = $this->getFormattedPriceArray($arrPriceDetails['unit'], "tiny");
		$arrPriceDetails['unit_tiny_without_fraction_print'] = $this->getFormattedPriceArray($arrPriceDetails['unit'], "tiny_without_fraction");
		$arrPriceDetails['unit_print'] = $this->getFormattedPriceArray($arrPriceDetails['unit'], "regular");
		$arrPriceDetails['unit_without_fraction_print'] = $this->getFormattedPriceArray($arrPriceDetails['unit'], "regular_without_fraction");
		$arrPriceDetails['unit_long_print'] = $this->getFormattedPriceArray($arrPriceDetails['unit'], "long");
		$arrPriceDetails['unit_long_without_fraction_print'] = $this->getFormattedPriceArray($arrPriceDetails['unit'], "long_without_fraction");

        // Unit per period prints
		$arrPriceDetails['unit_per_period_tiny_print'] = $this->getFormattedPriceArray($arrPriceDetails['unit_per_period'], "tiny");
		$arrPriceDetails['unit_per_period_tiny_without_fraction_print'] = $this->getFormattedPriceArray($arrPriceDetails['unit_per_period'], "tiny_without_fraction");
		$arrPriceDetails['unit_per_period_print'] = $this->getFormattedPriceArray($arrPriceDetails['unit_per_period'], "regular");
		$arrPriceDetails['unit_per_period_without_fraction_print'] = $this->getFormattedPriceArray($arrPriceDetails['unit_per_period'], "regular_without_fraction");
		$arrPriceDetails['unit_per_period_long_print'] = $this->getFormattedPriceArray($arrPriceDetails['unit_per_period'], "long");
		$arrPriceDetails['unit_per_period_long_without_fraction_print'] = $this->getFormattedPriceArray($arrPriceDetails['unit_per_period'], "long_without_fraction");

		// Multiplied prints
		$arrPriceDetails['multiplied_tiny_print'] = $this->getFormattedPriceArray($arrPriceDetails['multiplied'], "tiny");
		$arrPriceDetails['multiplied_tiny_without_fraction_print'] = $this->getFormattedPriceArray($arrPriceDetails['multiplied'], "tiny_without_fraction");
		$arrPriceDetails['multiplied_print'] = $this->getFormattedPriceArray($arrPriceDetails['multiplied'], "regular");
		$arrPriceDetails['multiplied_without_fraction_print'] = $this->getFormattedPriceArray($arrPriceDetails['multiplied'], "regular_without_fraction");
		$arrPriceDetails['multiplied_long_print'] = $this->getFormattedPriceArray($arrPriceDetails['multiplied'], "long");
		$arrPriceDetails['multiplied_long_without_fraction_print'] = $this->getFormattedPriceArray($arrPriceDetails['multiplied'], "long_without_fraction");

        // Multiplied per period prints
        $arrPriceDetails['multiplied_per_period_tiny_print'] = $this->getFormattedPriceArray($arrPriceDetails['multiplied_per_period'], "tiny");
        $arrPriceDetails['multiplied_per_period_tiny_without_fraction_print'] = $this->getFormattedPriceArray($arrPriceDetails['multiplied_per_period'], "tiny_without_fraction");
        $arrPriceDetails['multiplied_per_period_print'] = $this->getFormattedPriceArray($arrPriceDetails['multiplied_per_period'], "regular");
        $arrPriceDetails['multiplied_per_period_without_fraction_print'] = $this->getFormattedPriceArray($arrPriceDetails['multiplied_per_period'], "regular_without_fraction");
        $arrPriceDetails['multiplied_per_period_long_print'] = $this->getFormattedPriceArray($arrPriceDetails['multiplied_per_period'], "long");
        $arrPriceDetails['multiplied_per_period_long_without_fraction_print'] = $this->getFormattedPriceArray($arrPriceDetails['multiplied_per_period'], "long_without_fraction");

		// Word prints
		$arrPriceDetails['time_extension_print'] = $this->getPricePeriodText("SHORT");
		$arrPriceDetails['time_extension_long_print'] = $this->getPricePeriodText("LONG");

		// Percentage prints
		$arrPriceDetails['print_tax_percentage'] = StaticFormatter::getFormattedPercentage($arrPriceDetails['tax_percentage'], "regular");

        $arrPriceDetails['how_the_price_was_calculated'] =$howThePriceWasCalculated;
		return $arrPriceDetails;
	}

	/**
	 * @param string $paramType - SHORT OR LONG
	 * @return string
	 */
	public function getPricePeriodText($paramType = "LONG")
	{
	    // Is price displayed by days
		if(in_array($this->priceCalculationType, array(1, 3)))
		{
			$text = $paramType == "LONG" ? $this->lang->getText('NRS_PER_DAY_TEXT') : $this->lang->getText('NRS_PER_DAY_SHORT_TEXT');
		} else
		{
			$text = $paramType == "LONG" ? $this->lang->getText('NRS_PER_HOUR_TEXT') : $this->lang->getText('NRS_PER_HOUR_SHORT_TEXT');
		}

		return $text;
	}

    private function getFormattedPriceArray($paramArray, $paramFormatType)
    {
        $retArray = array();
        foreach($paramArray AS $key => $price)
        {
            $showLongText = in_array($paramFormatType, array('long', 'long_without_fraction')) ? TRUE : FALSE;
            if($key == "fixed_deposit_amount" && $price == 0.00)
            {
                $formattedPrice = $this->lang->getText($showLongText ? 'NRS_NOT_REQUIRED_TEXT' : 'NRS_NOT_REQ_TEXT');
            } else if($this->priceGroupId == 0)
            {
                $formattedPrice = $this->lang->getText($showLongText ? 'NRS_GET_A_QUOTE_TEXT' : 'NRS_INQUIRE_TEXT');
            } else
            {
                $formattedPrice = StaticFormatter::getFormattedPrice($price, $paramFormatType, $this->currencySymbol, $this->currencyCode, $this->currencySymbolLocation);
            }
            $retArray[$key] = $formattedPrice;
        }

        return $retArray;
    }
}
