<?php
/**
 * NRS Extra Price Table

 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\PriceTable;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Discount\ExtraDiscount;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Deposit\ExtraDepositManager;
use NativeRentalSystem\Models\Discount\ExtraDiscountsObserver;
use NativeRentalSystem\Models\Extra\Extra;
use NativeRentalSystem\Models\Extra\ExtrasObserver;
use NativeRentalSystem\Models\Tax\TaxManager;
use NativeRentalSystem\Models\Pricing\ExtraPriceManager;
use NativeRentalSystem\Models\Validation\StaticValidator;

class ExtrasPriceTable implements iPriceTable
{
    protected $conf 	                = NULL;
    protected $lang 		            = NULL;
    protected $debugMode 	            = 0;
    protected $settings                 = array();
    // Price calculation type: 1 - daily, 2 - hourly, 3 - mixed (daily+hourly)
    protected $priceCalculationType 	= 1;
    protected $currencySymbol		    = '$';
    protected $currencyCode			    = 'USD';

	public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings)
	{
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;
        $this->settings = $paramSettings;

        $this->priceCalculationType = StaticValidator::getValidSetting($paramSettings, 'conf_price_calculation_type', 'positive_integer', 1, array(1, 2, 3));
        $this->currencySymbol = StaticValidator::getValidSetting($paramSettings, 'conf_currency_symbol', "textval", "$");
        $this->currencyCode = StaticValidator::getValidSetting($paramSettings, 'conf_currency_code', "textval", "USD");
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    /**
     * Get the price table
     * @param int $paramItemId
     * @param int $paramExtraId
     * @param int $paramPickupLocationId (used for tax percentage calculation)
     * @param int $paramReturnLocationId (used for tax percentage calculation)
     * @param int $paramPartnerId
     * @param int $paramManufacturerId - not used for extras
     * @param int $paramBodyTypeId - not used for extras
     * @param int $paramTransmissionTypeId - not used for extras
     * @param int $paramFuelTypeId - not used for extras
     * Return example: priceTable = array("got_search_result" => TRUE, "extras" => array());
     * Return example: priceTable['extras'][0]['extra_name'] = "GPS";
     * Return example: priceTable['extras'][0]['period_list'][0]['period_from'] = "1234567890";
     * Return example: priceTable['extras'][0]['period_list'][0]['print_dynamic_period_label'] = "10-20 Hours";
     * @return array
     */
	public function getPriceTable(
        $paramItemId = -1, $paramExtraId = -1, $paramPickupLocationId = -1, $paramReturnLocationId = -1, $paramPartnerId = -1,
        $paramManufacturerId = -1, $paramBodyTypeId = -1, $paramTransmissionTypeId = -1, $paramFuelTypeId = -1
    ) {
        $objExtrasObserver = new ExtrasObserver($this->conf, $this->lang, $this->settings);
		$objDiscountsObserver = new ExtraDiscountsObserver($this->conf, $this->lang, $this->settings);
        $objTaxManager = new TaxManager($this->conf, $this->lang, $this->settings);
        $taxPercentage = $objTaxManager->getTaxPercentage($paramPickupLocationId, $paramReturnLocationId);

		// Get all discount periods: START
        // @note - In case if discounts are disabled, for price table and maybe somewhere else, we will need a default discount
        $discountIds = $objDiscountsObserver->getAllIds("BOOKING_DURATION", -1); // We return only admin's discount periods
        $allDiscountPeriods = array();
        foreach ($discountIds AS $discountId)
        {
            $objDiscount = new ExtraDiscount($this->conf, $this->lang, $this->settings, $discountId);
            $allDiscountPeriods[] = $objDiscount->getDetails(TRUE);
        }
        if(sizeof($allDiscountPeriods) == 0)
        {
            $objDiscount = new ExtraDiscount($this->conf, $this->lang, $this->settings, 0);
            // Include default discount
            $allDiscountPeriods[] = $objDiscount->getDetails(TRUE);
        }
        // Get all discount periods: END

		$extraIds = $objExtrasObserver->getAvailableIds($paramPartnerId, $paramExtraId, $paramItemId);
		$gotSearchResult = false;
		$extras = array();
		foreach ($extraIds AS $extraId)
		{
			$objExtra = new Extra($this->conf, $this->lang, $this->settings, $extraId);
			$objDepositManager = new ExtraDepositManager($this->conf, $this->lang, $this->settings, $extraId);
			$extra = array_merge($objExtra->getDetailsWithItemAndPartner(), $objDepositManager->getDetails());

            // Add periods data to extra row
            $extra['period_list'] = array();
            foreach($allDiscountPeriods as $discountPeriod)
            {
                $objPriceManager = new ExtraPriceManager($this->conf, $this->lang, $this->settings, $extraId, $taxPercentage);
                $extra['period_list'][] = $objPriceManager->getPriceDataInWeek($discountPeriod['period_from'], $discountPeriod['period_till']);
            }

			$extras[] = $extra;
			$gotSearchResult = true;
		}

        $printDynamicPeriodLabel = $this->lang->getText($this->priceCalculationType == 2 ? 'NRS_HOUR_PRICE_TEXT' : 'NRS_DAY_PRICE_TEXT');

        $priceTable = array(
			"print_periods" => $allDiscountPeriods,
			"total_periods" => sizeof($allDiscountPeriods),
            "print_dynamic_period_label" => $printDynamicPeriodLabel,
			"extras" => $extras,
			"got_search_result" => $gotSearchResult,
		);

        if($this->debugMode)
        {
            echo "<br />[Extras Price Table] Discount periods: ".nl2br(print_r($allDiscountPeriods, TRUE));
            echo "<br />[Extras Price Table] Total periods: ".sizeof($allDiscountPeriods);
            echo "<br />[Extras Price Table] Got search results: ".($gotSearchResult ? "Yes" : "No");
        }

		return $priceTable;
	}
}