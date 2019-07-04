<?php
/**
 * NRS Item Price Table

 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\PriceTable;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Discount\PricePlanDiscount;
use NativeRentalSystem\Models\Item\BodyType;
use NativeRentalSystem\Models\Item\BodyTypesObserver;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Deposit\ItemDepositManager;
use NativeRentalSystem\Models\Discount\PricePlanDiscountsObserver;
use NativeRentalSystem\Models\Item\Item;
use NativeRentalSystem\Models\Item\ItemsObserver;
use NativeRentalSystem\Models\Tax\TaxManager;
use NativeRentalSystem\Models\Pricing\ItemPriceManager;
use NativeRentalSystem\Models\Validation\StaticValidator;

class ItemsPriceTable implements iPriceTable
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
     * @param int $paramExtraId - not used for items
     * @param int $paramPickupLocationId
     * @param int $paramReturnLocationId
     * @param int $paramPartnerId
     * @param int $paramManufacturerId - manufacturer id
     * @param int $paramBodyTypeId - body type id
     * @param int $paramTransmissionTypeId - transmission type id
     * @param int $paramFuelTypeId - fuel type id
     * Return example: priceTable = array("got_search_result" => TRUE, "body_types" => array());
     * Return example: priceTable['body_types'][0] = array("items" => array());
     * Return example: priceTable['body_types'][0]['items'][0]['transmission_type_title'] = "Manual";
     * Return example: priceTable['body_types'][0]['items'][0]['period_list'][0]['period_from'] = "1234567890";
     * Return example: priceTable['body_types'][0]['items'][0]['period_list'][0]['print_dynamic_period_label'] = "10-20 Hours";
     * @return array
     */
	public function getPriceTable(
        $paramItemId = -1, $paramExtraId = -1, $paramPickupLocationId = -1, $paramReturnLocationId = -1, $paramPartnerId = -1,
        $paramManufacturerId = -1, $paramBodyTypeId = -1, $paramTransmissionTypeId = -1, $paramFuelTypeId = -1
    ) {
		$objItemsObserver = new ItemsObserver($this->conf, $this->lang, $this->settings);
		$objDiscountsObserver = new PricePlanDiscountsObserver($this->conf, $this->lang, $this->settings);
        $objTaxManager = new TaxManager($this->conf, $this->lang, $this->settings);
        $taxPercentage = $objTaxManager->getTaxPercentage($paramPickupLocationId, $paramReturnLocationId);

        // Get all discount periods: START
        // @note - In case if discounts are disabled, for price table and maybe somewhere else, we will need a default discount
        $discountIds = $objDiscountsObserver->getGroupedIds("BOOKING_DURATION", TRUE, -1, 0); // We return only admin's discount periods
        $allDiscountPeriods = array();
        foreach ($discountIds AS $discountId)
        {
            $objDiscount = new PricePlanDiscount($this->conf, $this->lang, $this->settings, $discountId);
            $allDiscountPeriods[] = $objDiscount->getDetails(FALSE);
        }
        if(sizeof($allDiscountPeriods) == 0)
        {
            $objDiscount = new PricePlanDiscount($this->conf, $this->lang, $this->settings, 0);
            // Include default discount
            $allDiscountPeriods[] = $objDiscount->getDetails(TRUE);
        }
        // Get all discount periods: END

		$gotSearchResult = false;
		$itemTypesWithItems = array();
		// Includes items with no type
		if($objItemsObserver->areItemsClassified())
		{
            $objBodyTypes = new BodyTypesObserver($this->conf, $this->lang, $this->settings);
            $bodyTypeIds = $objBodyTypes->getAllIds(TRUE);
			foreach($bodyTypeIds AS $bodyTypeId)
			{
                if($paramBodyTypeId == -1 || ($paramBodyTypeId >= 0 && $paramBodyTypeId == $bodyTypeId))
                {
                    $objBodyType = new BodyType($this->conf, $this->lang, $this->settings, $bodyTypeId);
                    $type = $objBodyType->getDetails(TRUE);
                    $itemIds = $objItemsObserver->getAvailableIdsForPriceTable(
                        $paramPartnerId, $paramManufacturerId, $bodyTypeId, $paramTransmissionTypeId,
                        $paramFuelTypeId, $paramItemId, $paramPickupLocationId, $paramReturnLocationId
                    );

                    $type['items'] = array();
                    $type['got_search_result'] = false;
                    foreach($itemIds AS $itemId)
                    {
                        $objItem = new Item($this->conf, $this->lang, $this->settings, $itemId);
                        $objDepositManager = new ItemDepositManager($this->conf, $this->lang, $this->settings, $itemId);
                        $item = array_merge($objItem->getExtendedDetails(), $objDepositManager->getDetails());

                        // Add periods data to item row
                        $item['period_list'] = array();
                        foreach($allDiscountPeriods as $discountPeriod)
                        {
                            $objPriceManager = new ItemPriceManager(
                                $this->conf, $this->lang, $this->settings, $paramItemId, $item['price_group_id'], "", $taxPercentage
                            );
                            $item['period_list'][] = $objPriceManager->getPriceWithoutCouponDataInWeek($discountPeriod['period_from'], $discountPeriod['period_till']);
                        }

                        $type['items'][] = $item;
                        $type['got_search_result'] = true;
                        $gotSearchResult = true;
                    }
                    // Add to stack
                    $itemTypesWithItems[] = $type;
                }
			}
		} else
		{
			// Same, just everything added to param zero
			$itemIds = $objItemsObserver->getAvailableIdsForPriceTable(
			    $paramPartnerId, $paramManufacturerId, $paramBodyTypeId, $paramTransmissionTypeId,
                $paramFuelTypeId, $paramItemId, $paramPickupLocationId, $paramReturnLocationId
            );
			$type['items'] = array();
			$type['got_search_result'] = false;
			foreach($itemIds AS $itemId)
			{
				$objItem = new Item($this->conf, $this->lang, $this->settings, $itemId);
                $objDepositManager = new ItemDepositManager($this->conf, $this->lang, $this->settings, $itemId);
                $item = array_merge($objItem->getExtendedDetails(), $objDepositManager->getDetails());

                // Add periods data to item row
                $item['period_list'] = array();
                foreach($allDiscountPeriods as $discountPeriod)
                {
                    $objPriceManager = new ItemPriceManager(
                        $this->conf, $this->lang, $this->settings, $paramItemId, $item['price_group_id'], "", $taxPercentage
                    );
                    $item['period_list'][] = $objPriceManager->getPriceWithoutCouponDataInWeek($discountPeriod['period_from'], $discountPeriod['period_till']);
                }

				$type['items'][] = $item;
				$type['got_search_result'] = true;
				$gotSearchResult = true;
			}
			// Add to stack
			$itemTypesWithItems[0] = $type;
		}

        $printDynamicPeriodLabel = $this->lang->getText($this->priceCalculationType == 2 ? 'NRS_HOUR_PRICE_TEXT' : 'NRS_DAY_PRICE_TEXT');

		$priceTable = array(
			"print_periods" => $allDiscountPeriods,
			"total_periods" => sizeof($allDiscountPeriods),
            "print_dynamic_period_label" => $printDynamicPeriodLabel,
			"body_types" => $itemTypesWithItems,
			"got_search_result" => $gotSearchResult,
		);

		if($this->debugMode)
        {
            echo "<br />[Items Price Table] Discount periods: ".nl2br(print_r($allDiscountPeriods, TRUE));
            echo "<br />[Items Price Table] Total periods: ".sizeof($allDiscountPeriods);
            echo "<br />[Items Price Table] Got search results: ".($gotSearchResult ? "Yes" : "No");
        }

		return $priceTable;
	}
}