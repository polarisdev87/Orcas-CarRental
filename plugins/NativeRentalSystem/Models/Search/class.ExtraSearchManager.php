<?php
/**
 * Extras Observer (array)

 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Search;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Formatting\StaticFormatter;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Option\ExtraOption;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Extra\Extra;
use NativeRentalSystem\Models\Option\ExtraOptionManager;
use NativeRentalSystem\Models\Pricing\ExtraPriceManager;
use NativeRentalSystem\Models\Unit\ExtraUnitManager;

class ExtraSearchManager
{
    protected $conf 	            = NULL;
    protected $lang 		        = NULL;
    protected $debugMode 	        = 0;
    protected $settings             = array();

    protected $bookingId			= 0;
    protected $multiMode            = FALSE;
    // Extras may be dependant on items
    protected $itemIds              = FALSE;

    /**
     * @param ExtensionConfiguration &$paramConf
     * @param Language &$paramLang
     * @param array $paramSettings
     * @param $paramTaxPercentage
     * @param $paramLocationCode
     * @param int $paramBookingId - used because we need to support booking edits
     * @param array $paramItemIds
     */
    public function __construct(
        ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings, $paramTaxPercentage, $paramLocationCode,
        $paramBookingId, array $paramItemIds
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
        $this->itemIds = StaticValidator::getValidArray($paramItemIds, 'positive_integer', 0);
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    /**
     * Verify if those selected extra ids really exists
     * @param array $paramSelectedExtraIds
     * @param array $paramAvailableExtraIds
     * @return array
     */
    public function getExistingSelectedExtraIds(array $paramSelectedExtraIds, array $paramAvailableExtraIds)
    {
        $retExtraIds = array();
        foreach($paramSelectedExtraIds AS $extraId)
        {
            if(in_array($extraId, $paramAvailableExtraIds))
            {
                $retExtraIds[] = $extraId;
            }
        }

        if($this->debugMode == 1)
        {
            echo "<br />SELECTED EXTRA IDs BEFORE: ".print_r($paramSelectedExtraIds, TRUE);
            echo "<br />SELECTED EXTRA IDs AFTER: ".print_r($retExtraIds, TRUE);
        }

        return $retExtraIds;
    }

    public function getAvailableExtraIds()
    {
        $addQuery = "";

        $searchSQL = "
            SELECT extra_id
            FROM {$this->conf->getPrefix()}extras
            WHERE units_in_stock > 0 AND max_units_per_booking > 0
            {$addQuery} AND blog_id='{$this->conf->getBlogId()}'
			ORDER BY extra_name ASC
		";
        //echo "<br />".$searchSQL."<br />"; //die;

        $sqlRows = $this->conf->getInternalWPDB()->get_col($searchSQL);

        if($this->debugMode == 2)
        {
            echo "<br />TOTAL AVAILABLE EXTRAS FOUND: " . sizeof($sqlRows);
            echo "<br />AVAILABLE EXTRA IDs: ".print_r($sqlRows, TRUE);
            echo "<br /><em>(Note: the number is not final, it does not check for booked or blocked extras)</em>";
        }

        return $sqlRows;
    }

    /**
     * @param array $paramExtraIds
     * @param array $paramExtraUnits
     * @param array $paramExtraOptions
     * @param int $paramPickupTimestamp
     * @param int $paramReturnTimestamp
     * @param bool $paramValidateQuantity
     * @return array
     */
	public function getExtrasWithPricesAndOptions(
        array $paramExtraIds, array $paramExtraUnits, array $paramExtraOptions, $paramPickupTimestamp, $paramReturnTimestamp, $paramValidateQuantity = FALSE
    ) {
        $validExtraIds = StaticValidator::getValidArray($paramExtraIds, 'positive_integer', 0);
        $validExtraUnits = StaticValidator::getValidArray($paramExtraUnits, 'positive_integer', 0);
        $validExtraOptions = StaticValidator::getValidArray($paramExtraOptions, 'positive_integer', 0);

		$retExtras = array();

        foreach($validExtraIds AS $extraId)
        {
            // 1 - Process extra details
            $objExtra = new Extra($this->conf, $this->lang, $this->settings, $extraId);
            $extraDetails = $objExtra->getDetailsWithItemAndPartner();
			$objUnitsManager = new ExtraUnitManager(
                $this->conf, $this->lang, $this->settings, $extraDetails['extra_sku'], $paramPickupTimestamp, $paramReturnTimestamp
			);

			$availableUnits = $objUnitsManager->getTotalUnitsAvailable($this->locationCode, $this->bookingId);
			// If there is more items in stock than booked, and more items in stock than min quantity for booking
			if($availableUnits > 0)
			{
			    if($this->multiMode)
                {
                    // Multi-mode (more than 1 different item allowed to select)
                    $fitsToItem = sizeof($this->itemIds) > 0 && in_array($extraDetails['item_id'], $this->itemIds) ? TRUE : FALSE;
                } else
                {
                    // Single mode (only one different item allowed to select)
                    $fitsToItem = isset($this->itemIds[0]) ? $extraDetails['item_id'] == $this->itemIds[0] : FALSE;
                }

                if($extraDetails['item_id'] == 0 || $extraDetails['item_id'] > 0 && $fitsToItem)
                {
                    $objOptionsManager = new ExtraOptionManager($this->conf, $this->lang, $this->settings, $extraId);
                    $objPriceManager = new ExtraPriceManager($this->conf, $this->lang, $this->settings, $extraId, $this->taxPercentage);

                    // 1 - Get selected units
                    $maxAllowedUnits = $objUnitsManager->getMaxAllowedUnitsForBooking($this->locationCode, $this->bookingId);
                    $unitsSelected = 0;
                    if(isset($validExtraUnits[$extraId]) && $validExtraUnits[$extraId] > 0)
                    {
                        $unitsSelected = ($maxAllowedUnits > $validExtraUnits[$extraId]) ? $validExtraUnits[$extraId] : $maxAllowedUnits;
                    }

                    // 3 - Process extra prices
                    $extraPriceDetails = $objPriceManager->getMultipliedPriceDetailsByInterval($paramPickupTimestamp, $paramReturnTimestamp, $unitsSelected);

                    // 4 - Process extra options
                    $totalOptions = $objOptionsManager->getTotalOptions();
                    if($totalOptions == 1)
                    {
                        // Auto-select first option
                        $selectedOptionId = $objOptionsManager->getFirstIds();
                    } else
                    {
                        $selectedOptionId = 0;
                        if(isset($validExtraOptions[$extraId]) && $validExtraOptions[$extraId] > 0)
                        {
                            $selectedOptionId = $validExtraOptions[$extraId];
                        }
                    }
                    $objSelectedOption = new ExtraOption($this->conf, $this->lang, $this->settings, $selectedOptionId);
                    $selectedOptionDetails = $objSelectedOption->getDetails();
                    $selectedOptionName = "";
                    $translatedSelectedOptionName = "";
                    $optionsHTML = "";
                    if($totalOptions > 1)
                    {
                        $selectedOptionName = $selectedOptionDetails['option_name'];
                        $translatedSelectedOptionName = $selectedOptionDetails['translated_option_name'];
                        if($extraDetails['options_display_mode'] == 1)
                        {
                            $optionsHTML = $objOptionsManager->getTranslatedDropDown($selectedOptionId, $extraDetails['options_measurement_unit']);
                        } else
                        {
                            //echo nl2br(print_r($extraDetails, TRUE));
                            //echo "Extra ID: $extraId, Measurement Unit: {$extraDetails['options_measurement_unit']}";
                            $optionsHTML = $objOptionsManager->getSlider($selectedOptionId, $extraDetails['options_measurement_unit']);
                        }
                    } else if($totalOptions == 1)
                    {
                        $selectedOptionName = $selectedOptionDetails['option_name'];
                        $translatedSelectedOptionName = $selectedOptionDetails['translated_option_name'];
                        $optionsHTML = $selectedOptionDetails['option_name'];
                    }

                    if($this->multiMode)
                    {
                        // Only if items (!) are in multi mode, we need to display dependant item title and partner name (if exist)
                        $printExtra  = $extraDetails['print_extra_name_with_dependant_item'];
                        $printExtra .= ' '.$extraDetails['print_via_partner'];

                        $printTranslatedExtra  = $extraDetails['print_translated_extra_name_with_dependant_item'];
                        $printTranslatedExtra .= ' '.$extraDetails['print_via_partner'];

                        $printExtraWithOption  = $extraDetails['print_extra_name_with_dependant_item'];
                        $printExtraWithOption .= $selectedOptionName ? ', '.$selectedOptionName.' '.$extraDetails['print_options_measurement_unit'] : '';
                        $printExtraWithOption .= ' '.$extraDetails['print_via_partner'];

                        $printTranslatedExtraWithOption  = $extraDetails['print_translated_extra_name_with_dependant_item'];
                        $printTranslatedExtraWithOption .= $translatedSelectedOptionName ? ', '.$translatedSelectedOptionName.' '.$extraDetails['print_options_measurement_unit'] : '';
                        $printTranslatedExtraWithOption .= ' '.$extraDetails['print_via_partner'];
                    } else
                    {
                        $printExtra  = $extraDetails['print_extra_name'];
                        $printTranslatedExtra  = $extraDetails['print_translated_extra_name'];
                        $printExtraWithOption  = $extraDetails['print_extra_name'];
                        $printExtraWithOption .= $selectedOptionName ? ', '.$selectedOptionName.' '.$extraDetails['print_options_measurement_unit'] : '';
                        $printTranslatedExtraWithOption  = $extraDetails['print_extra_name'];
                        $printTranslatedExtraWithOption .= $selectedOptionName ? ', '.$selectedOptionName.' '.$extraDetails['print_options_measurement_unit'] : '';
                    }

                    // 5 - Extend the $extra output with new details
                    $extraDetails['selected'] = $unitsSelected > 0 ? TRUE : FALSE;
                    $extraDetails['selected_quantity'] = $unitsSelected;
                    $extraDetails['quantity_dropdown_options'] = StaticFormatter::generateDropDownOptions(0, $maxAllowedUnits, $unitsSelected, "", "", FALSE, "");
                    $extraDetails['max_allowed_units'] = $maxAllowedUnits;
                    $extraDetails['selected_option_id'] = $selectedOptionId;
                    $extraDetails['selected_option_name'] = $selectedOptionName;
                    $extraDetails['options_html'] = $optionsHTML;
                    $extraDetails['total_options'] = $totalOptions;
                    $extraDetails['print_checked'] = $unitsSelected > 0 ? ' checked="checked"' : '';
                    $extraDetails['print_selected'] = $unitsSelected > 0 ? 'selected' : '';
                    $extraDetails['print_extra'] = $printExtra;
                    $extraDetails['print_translated_extra'] = $printTranslatedExtra;
                    $extraDetails['print_extra_with_option'] = $printExtraWithOption;
                    $extraDetails['print_translated_extra_with_option'] = $printTranslatedExtraWithOption;

                    if(($paramValidateQuantity && $unitsSelected > 0) || $paramValidateQuantity === FALSE)
                    {
                        // 6 - Add to stack only if extra is selected or if we return all extras
                        $retExtras[] = array_merge($extraDetails, $extraPriceDetails);
                    }

                    if($this->debugMode == 1)
                    {
                        echo "<br /><br />Extra with ID={$extraId} is <span style='color:green;font-weight:bold;'>AVAILABLE</span> for booking ";
                        if($extraDetails['item_id'] > 0)
                        {
                            echo "with dependency on item ID={$extraDetails['item_id']}, which is in the list of selected items: ";
                            print_r($this->itemIds);
                        } else
                        {
                            echo ", is not dependant on any item (Item ID=0) from the list of selected items: ";
                            print_r($this->itemIds);
                        }
                        echo "<br />and has {$unitsSelected} units selected of {$availableUnits} units available, with total {$extraDetails['units_in_stock']} units in stock, ";
                        echo "<br />with maximum {$maxAllowedUnits} units allowed per booking, ";
                        echo "and booking max unit limits set to: {$extraDetails['max_units_per_booking']}, ";
                        echo "including current booking #{$this->bookingId}";
                    }
                } else
                {
                    if($this->debugMode == 1)
                    {
                        echo "<br /><br />Extra with ID={$extraId} is <span style='color:blue;font-weight:bold;'>AVAILABLE</span> for booking, ";
                        echo "but is dependant on item ID={$extraDetails['item_id']}, which is not in the list of selected items: ";
                        print_r($this->itemIds);
                        echo "<br / >and currently has {$availableUnits} units available, ";
                        echo "including current booking #{$this->bookingId}";
                    }
                }
			} else
			{
				if($this->debugMode == 1)
				{
					echo "<br /><br />Extra with ID={$extraId} is <span style='color:red;font-weight:bold;'>NOT AVAILABLE</span> for booking ";
					echo "and currently has {$availableUnits} units available, ";
					echo "including current booking #{$this->bookingId}";
				}
			}
		}

		return $retExtras;
	}
}
