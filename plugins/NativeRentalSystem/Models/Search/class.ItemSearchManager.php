<?php
/**
 * Items Observer (array)

 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Search;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Formatting\StaticFormatter;
use NativeRentalSystem\Models\Item\FeaturesObserver;
use NativeRentalSystem\Models\Option\ItemOption;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Item\Item;
use NativeRentalSystem\Models\Option\ItemOptionManager;
use NativeRentalSystem\Models\Pricing\ItemPriceManager;
use NativeRentalSystem\Models\Unit\ItemUnitManager;

class ItemSearchManager
{
    protected $conf 	            = NULL;
    protected $lang 		        = NULL;
    protected $debugMode 	        = 0;
    protected $settings             = array();

    protected $bookingId			= 0;
    protected $couponCode			= "";
    protected $multiMode            = FALSE;

    /**
     * @param ExtensionConfiguration &$paramConf
     * @param Language &$paramLang
     * @param array $paramSettings
     * @param $paramTaxPercentage
     * @param $paramLocationCode
     * @param int $paramBookingId - used because we need to support booking edits
     * @param string $paramCouponCode
     */
    public function __construct(
        ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings, $paramTaxPercentage, $paramLocationCode,
        $paramBookingId, $paramCouponCode
    ) {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;
        $this->settings = $paramSettings;

        // Dynamic tax percentage
        $this->taxPercentage = floatval($paramTaxPercentage);
        // Location code
        $this->locationCode = sanitize_text_field($paramLocationCode);

        $this->multiMode = isset($paramSettings['conf_booking_model']) && $paramSettings['conf_booking_model'] == 2 ? TRUE : FALSE;
        $this->bookingId = StaticValidator::getValidPositiveInteger($paramBookingId, 0);
        $this->couponCode = sanitize_text_field($paramCouponCode);
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    /**
     * Verify if those selected item ids really exists
     * @param array $paramSelectedItemIds
     * @param array $paramAvailableItemIds
     * @return array
     */
    public function getExistingSelectedItemIds(array $paramSelectedItemIds, array $paramAvailableItemIds)
    {
        $retItemIds = array();
        $counter = 0;
        foreach($paramSelectedItemIds AS $itemId)
        {
            if(in_array($itemId, $paramAvailableItemIds))
            {
                $counter++;
                if($counter > 1 && $this->multiMode == FALSE)
                {
                    // Do not allow to have more than 1 different SELECTED item if it is not multi-mode
                    break;
                }
                $retItemIds[] = $itemId;
            }
        }

        if($this->debugMode >= 1)
        {
            echo "<br />SELECTED ITEM IDs BEFORE: ".print_r($paramSelectedItemIds, TRUE);
            echo "<br />SELECTED ITEM IDs AFTER: ".print_r($retItemIds, TRUE);
        }

        return $retItemIds;
    }

    /**
     * @param $paramPickupLocationId
     * @param $paramReturnLocationId
     * @param $paramPartnerId
     * @param $paramManufacturerId
     * @param $paramBodyTypeId
     * @param $paramTransmissionTypeId
     * @param $paramFuelTypeId
     * @return array
     */
    public function getAvailableItemIds(
        $paramPickupLocationId, $paramReturnLocationId, $paramPartnerId, $paramManufacturerId,
        $paramBodyTypeId, $paramTransmissionTypeId, $paramFuelTypeId
    ) {
        $addQuery = '';

        $validPickupLocationId = StaticValidator::getValidPositiveInteger($paramPickupLocationId, 0);
        $validReturnLocationId = StaticValidator::getValidPositiveInteger($paramReturnLocationId, 0);
        $validPartnerId = StaticValidator::getValidInteger($paramPartnerId, -1);
        $validManufacturerId = StaticValidator::getValidInteger($paramManufacturerId, -1);
        $validBodyTypeId = StaticValidator::getValidInteger($paramBodyTypeId, -1);
        $validTransmissionTypeId = StaticValidator::getValidInteger($paramTransmissionTypeId, -1);
        $validFuelTypeId = StaticValidator::getValidInteger($paramFuelTypeId, -1);

        if($validPartnerId >= 0)
        {
            $addQuery .= " AND it.partner_id='{$validPartnerId}' ";
        }

        if($validManufacturerId >= 0)
        {
            $addQuery .= " AND it.manufacturer_id='{$validManufacturerId}' ";
        }

        if($validBodyTypeId >= 0)
        {
            $addQuery .= " AND it.body_type_id='{$validBodyTypeId}' ";
        }

        if($validTransmissionTypeId >= 0)
        {
            $addQuery .= " AND it.transmission_type_id='{$validTransmissionTypeId}' ";
        }

        if($validFuelTypeId >= 0)
        {
            $addQuery .= " AND it.fuel_type_id='{$validFuelTypeId}' ";
        }

        if($validPickupLocationId > 0)
        {
            $addQuery .= "
				AND it.item_id IN
				(
					SELECT item_id
					FROM {$this->conf->getPrefix()}item_locations
					WHERE location_id='{$validPickupLocationId}' AND location_type='1'
				)";
        }

        if($validReturnLocationId > 0)
        {
            $addQuery .= "AND it.item_id IN
			(
				SELECT item_id
				FROM {$this->conf->getPrefix()}item_locations
				WHERE location_id='{$validReturnLocationId}' AND location_type='2'
			)";
        }

        $searchSQL = "
			SELECT it.item_id
			FROM {$this->conf->getPrefix()}items it
			LEFT JOIN {$this->conf->getPrefix()}manufacturers mf ON it.manufacturer_id=mf.manufacturer_id
			WHERE it.units_in_stock > 0 AND it.enabled = '1'
			{$addQuery} AND it.blog_id='{$this->conf->getBlogId()}'
			ORDER BY manufacturer_title ASC, model_name ASC
			";

        //echo "<br />".nl2br($searchSQL)."<br />"; //die;

        $sqlRows = $this->conf->getInternalWPDB()->get_col($searchSQL);

        if($this->debugMode >= 1)
        {
            echo "<br /><br />TOTAL AVAILABLE ITEMS FOUND: " . sizeof($sqlRows).", ";
            echo "BODY TYPE ID: ".($validBodyTypeId >= 0 ? $validBodyTypeId : "ANY");
            echo "<br />AVAILABLE ITEM IDs: ".print_r($sqlRows, TRUE);
            echo "<br /><em>(Note: the candidate number is not final, it does not check for booked or blocked items)</em>";
        }

        return $sqlRows;
    }

    /**
     * @param array $paramItemIds
     * @param array $paramItemUnits
     * @param array $paramItemOptions
     * @param int $paramPickupTimestamp
     * @param int $paramReturnTimestamp
     * @param bool $paramValidateQuantity
     * @return array
     */
	public function getItemsWithPricesAndOptions(
        array $paramItemIds, array $paramItemUnits, array $paramItemOptions, $paramPickupTimestamp, $paramReturnTimestamp, $paramValidateQuantity = FALSE
    ) {
        $validItemIds = StaticValidator::getValidArray($paramItemIds, 'positive_integer', 0);
        $validItemUnits = StaticValidator::getValidArray($paramItemUnits, 'positive_integer', 0);
        $validItemOptions = StaticValidator::getValidArray($paramItemOptions, 'positive_integer', 0);
		$retItems = array();


        foreach($validItemIds AS $itemId)
        {
            // 1 - Process full item details
            $objItem = new Item($this->conf, $this->lang, $this->settings, $itemId);
            $itemDetails = $objItem->getExtendedDetails();
			$objUnitsManager = new ItemUnitManager(
                $this->conf, $this->lang, $this->settings, $itemDetails['item_sku'], $paramPickupTimestamp, $paramReturnTimestamp
			);

			$availableUnits = $objUnitsManager->getTotalUnitsAvailable($this->locationCode, $this->bookingId);

			// If there is more items in stock than booked, and more items in stock than min quantity for booking
			if($availableUnits > 0)
			{
				$objOptionsManager = new ItemOptionManager($this->conf, $this->lang, $this->settings, $itemId);
				$objPriceManager = new ItemPriceManager(
                    $this->conf, $this->lang, $this->settings, $itemId, $itemDetails['price_group_id'], $this->couponCode, $this->taxPercentage
				);

				// 2 - Process item prices
				$maxAllowedUnits = $objUnitsManager->getMaxAllowedUnitsForBooking($this->locationCode, $this->bookingId);
				$unitsSelected = 0;
                if(isset($validItemUnits[$itemId]) && $validItemUnits[$itemId] > 0)
                {
                    $unitsSelected = ($maxAllowedUnits > $validItemUnits[$itemId]) ? $validItemUnits[$itemId] : $maxAllowedUnits;
                }

			    $itemPriceDetails = $objPriceManager->getMultipliedPriceDetailsByInterval($paramPickupTimestamp, $paramReturnTimestamp, $unitsSelected);

				// 3 - Process item options
				$totalOptions = $objOptionsManager->getTotalOptions();
				if($totalOptions == 1)
				{
					// Auto-select first option
                    $selectedOptionId = $objOptionsManager->getFirstIds();
				} else
				{
					$selectedOptionId = 0;
                    if(isset($validItemOptions[$itemId]) && $validItemOptions[$itemId] > 0)
                    {
                        $selectedOptionId = $validItemOptions[$itemId];
                    }
				}
				$objSelectedOption = new ItemOption($this->conf, $this->lang, $this->settings, $selectedOptionId);
				$selectedOptionDetails = $objSelectedOption->getDetails();
				$printSelectedOptionName = "";
				$printTranslatedSelectedOptionName = "";
				$optionsHTML = "";
				if($totalOptions > 1)
				{
					$printSelectedOptionName = $selectedOptionDetails['option_name'];
					$printTranslatedSelectedOptionName = $selectedOptionDetails['translated_option_name'];
					if($itemDetails['options_display_mode'] == 1)
					{
						$optionsHTML = $objOptionsManager->getTranslatedDropDown($selectedOptionId, $itemDetails['options_measurement_unit']);
					} else
					{
						//echo nl2br(print_r($itemDetails, TRUE));
						//echo "Item ID: $itemId, Measurement Unit: {$itemDetails['options_measurement_unit']}";
						$optionsHTML = $objOptionsManager->getSlider($selectedOptionId, $itemDetails['options_measurement_unit']);
					}
				} else if($totalOptions == 1)
				{
					$printSelectedOptionName = $selectedOptionDetails['option_name'];
					$printTranslatedSelectedOptionName = $selectedOptionDetails['translated_option_name'];
					$optionsHTML = $selectedOptionDetails['option_name'];
				}

				$printItemWithOption = $itemDetails['manufacturer_title'].' '.$itemDetails['model_name'];
				$printItemWithOption .= $itemDetails['body_type_title'] ? ', '.$itemDetails['body_type_title'] : '';
				$printItemWithOption .= $printSelectedOptionName ? ', '.$printSelectedOptionName.' '.$itemDetails['options_measurement_unit'] : '';
                $printItemWithOption .= ' '.$itemDetails['print_via_partner'];

                $printTranslatedItemWithOption = $itemDetails['translated_manufacturer_title'].' '.$itemDetails['translated_model_name'];
                $printTranslatedItemWithOption .= $itemDetails['translated_body_type_title'] ? ', '.$itemDetails['translated_body_type_title'] : '';
                $printTranslatedItemWithOption .= $printTranslatedSelectedOptionName ? ', '.$printTranslatedSelectedOptionName.' '.$itemDetails['options_measurement_unit'] : '';
                $printTranslatedItemWithOption .= ' '.$itemDetails['print_via_partner'];
                ///////////////////////////////////////////////////////////////////////////////
                // FEATURES: START
                $objFeaturesObserver = new FeaturesObserver($this->conf, $this->lang, $this->settings);
                $features = $objFeaturesObserver->getTranslatedSelectedFeaturesByItemId($itemId, FALSE);
                $itemDetails['show_features'] = sizeof($features) > 0 ? TRUE : FALSE;
                $itemDetails['features'] = $features;
                // FEATURES: END
                ///////////////////////////////////////////////////////////////////////////////

				// 4 - Extend the $item output with new details
				$itemDetails['selected'] = $unitsSelected > 0 ? TRUE : FALSE;
				$itemDetails['selected_quantity'] = $unitsSelected;
                $itemDetails['quantity_dropdown_options'] = StaticFormatter::generateDropDownOptions(1, $maxAllowedUnits, $unitsSelected, "", "", FALSE, "");
                $itemDetails['max_allowed_units'] = $maxAllowedUnits;
				$itemDetails['selected_option_id'] = $selectedOptionId;
				$itemDetails['selected_option_name'] = $printSelectedOptionName;
				$itemDetails['options_html'] = $optionsHTML;
				$itemDetails['total_options'] = $totalOptions;
				$itemDetails['print_checked'] = $unitsSelected > 0 ? ' checked="checked"' : '';
				$itemDetails['print_selected'] = $unitsSelected > 0 ? 'selected' : '';
				$itemDetails['print_item_with_option'] = $printItemWithOption;
				$itemDetails['print_translated_item_with_option'] = $printTranslatedItemWithOption;

                if(($paramValidateQuantity && $unitsSelected > 0) || $paramValidateQuantity === FALSE)
                {
                    // Add to stack only if item is selected or if we return all items
                    $retItems[] = array_merge($itemDetails, $itemPriceDetails);
                }

				if($this->debugMode == 1)
				{
					echo "<br /><br />Item with ID={$itemId} is <span style='color:green;font-weight:bold;'>AVAILABLE</span> for booking ";
					echo "has {$unitsSelected} units selected of {$availableUnits} units available, with total {$itemDetails['units_in_stock']} units in stock, ";
					echo "<br />with maximum {$maxAllowedUnits} units allowed per booking, ";
					echo "and booking min/max unit limits set to: 0/{$itemDetails['max_units_per_booking']}, ";
					echo "including current booking #{$this->bookingId}";
				}
			} else
				/*if there are no cars aviable find the next free time for the selected car*/
				/*var_dump($paramPickupTimestamp);
				echo "<br /><br />";
				var_dump($paramReturnTimestamp);
				echo "<br /><br />";
				var_dump($itemId);*/
				$returnedDate='';
                if(empty($retItems)){
                    $returnedDate = $objUnitsManager->findTheFirstFreeDate($this->locationCode, $this->bookingId);
                    

                    $_SESSION['next_aviable_date'] =$returnedDate;
                }       
							
				
				
				
				
				
			{
				if($this->debugMode == 1)
				{
					echo "<br /><br />Item with ID={$itemId} is <span style='color:red;font-weight:bold;'>NOT AVAILABLE</span> for booking ";
					echo "and currently has {$availableUnits} units test available, ";
					echo "including current booking #{$this->bookingId}";
				}
			}
		}

		return $retItems;
	}
}
