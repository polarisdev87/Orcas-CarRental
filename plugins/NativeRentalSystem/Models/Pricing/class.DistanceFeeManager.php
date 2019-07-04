<?php
/**
 * NRS Distance Manager
 *
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Pricing;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Formatting\StaticFormatter;
use NativeRentalSystem\Models\Language\Language;

class DistanceFeeManager
{
    protected $conf 	                = NULL;
    protected $lang 		            = NULL;
    protected $debugMode 	            = 0;
    protected $settings 	            = array();
    protected $pickupLocationId 	    = 0;
    protected $returnLocationId 	    = 0;

    protected $currencySymbol		    = '$';
    protected $currencyCode			    = 'USD';
    protected $currencySymbolLocation	= 0;
    // Dynamic tax percentage
    protected $taxPercentage		    = 0.00;
    protected $showPriceWithTaxes	    = 0;

    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings, $paramPickupLocationId, $paramReturnLocationId, $paramTaxPercentage)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;
        // Set saved settings
        $this->settings = $paramSettings;

        $this->pickupLocationId = StaticValidator::getValidValue($paramPickupLocationId, 'positive_integer', 0);
        $this->returnLocationId = StaticValidator::getValidValue($paramReturnLocationId, 'positive_integer', 0);

        $this->currencySymbol = StaticValidator::getValidSetting($paramSettings, 'conf_currency_symbol', "textval", "$");
        $this->currencyCode = StaticValidator::getValidSetting($paramSettings, 'conf_currency_code', "textval", "USD");
        $this->currencySymbolLocation = StaticValidator::getValidSetting($paramSettings, 'conf_currency_symbol_location', 'positive_integer', 0, array(0, 1));
        // Dynamic tax percentage
        $this->taxPercentage = floatval($paramTaxPercentage);
        $this->showPriceWithTaxes = StaticValidator::getValidSetting($paramSettings, 'conf_show_price_with_taxes', 'positive_integer', 1, array(0, 1));
    }

    /**
     * Get distance fee from MySQL database
     * @note - MUST BE PRIVATE. FOR INTERNAL USE ONLY
     * @param $paramPickupLocationId
     * @param $paramReturnLocationId
     * @return float
     */
    private function getFeeByTwoLocations($paramPickupLocationId, $paramReturnLocationId)
    {
        $retFee = 0.00;

        // For all items reservation
        $validPickupLocationId = StaticValidator::getValidPositiveInteger($paramPickupLocationId);
        $validReturnLocationId = StaticValidator::getValidPositiveInteger($paramReturnLocationId);
        $sqlQuery = "
			SELECT distance_fee
			FROM {$this->conf->getPrefix()}distances
			WHERE pickup_location_id='{$validPickupLocationId}' AND return_location_id='{$validReturnLocationId}'
			AND blog_id='{$this->conf->getBlogId()}'
		";
        $dbFee = $this->conf->getInternalWPDB()->get_var($sqlQuery);

        if(!is_null($dbFee))
        {
            $retFee = $dbFee;
        }

        return $retFee;
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    protected function getFormattedPrice($paramPrice, $paramFormatType)
    {
        return StaticFormatter::getFormattedPrice($paramPrice, $paramFormatType, $this->currencySymbol, $this->currencyCode, $this->currencySymbolLocation);
    }

    protected function getFormattedPriceArray($paramArray, $paramFormatType)
    {
        $retArray = StaticFormatter::getFormattedPriceArray($paramArray, $paramFormatType, $this->currencySymbol, $this->currencyCode, $this->currencySymbolLocation);
        return $retArray;
    }

    public function getUnitFee()
    {
        return $this->getFeeByTwoLocations($this->pickupLocationId, $this->returnLocationId);
    }

    public function getFee($paramMultiplier = 1)
    {
        $unitFee = $this->getFeeByTwoLocations($this->pickupLocationId, $this->returnLocationId);
        $totalFee = $unitFee * $paramMultiplier;

        return $totalFee;
    }

    public function getUnitDetails()
    {
        return $this->getDetails(1);
    }

    public function getDetails($paramMultiplier = 1)
    {
        $validMultiplier = StaticValidator::getValidPositiveInteger($paramMultiplier, 1);

        // Calculate distance fees with taxes
        $distanceFee = $this->getFeeByTwoLocations($this->pickupLocationId, $this->returnLocationId);
        $distanceFeeWithTax = $distanceFee * (1 + $this->taxPercentage / 100);
        $distanceFeeDynamic = $this->showPriceWithTaxes == 1 ? $distanceFeeWithTax : $distanceFee;
        $distanceTaxAmount = $distanceFeeWithTax - $distanceFee;

        // Fee details
        $retFees = array();
        $retFees['unit'] = array(
            "distance_fee" => $distanceFee,
            "distance_fee_with_tax" => $distanceFeeWithTax,
            "distance_dynamic" => $distanceFeeDynamic,
            "distance_tax_amount" => $distanceTaxAmount,
        );

        $retFees['tax_percentage'] = $this->taxPercentage;
        $retFees['multiplier'] = $validMultiplier;
        $retFees['multiplied'] = StaticFormatter::getMultipliedNumberArray($retFees['unit'], $validMultiplier);

        // Unit prints
        $retFees['unit_tiny_print'] = $this->getFormattedPriceArray($retFees['unit'], "tiny");
        $retFees['unit_tiny_without_fraction_print'] = $this->getFormattedPriceArray($retFees['unit'], "tiny_without_fraction");
        $retFees['unit_print'] = $this->getFormattedPriceArray($retFees['unit'], "regular");
        $retFees['unit_without_fraction_print'] = $this->getFormattedPriceArray($retFees['unit'], "regular_without_fraction");
        $retFees['unit_long_print'] = $this->getFormattedPriceArray($retFees['unit'], "long");
        $retFees['unit_long_without_fraction_print'] = $this->getFormattedPriceArray($retFees['unit'], "long_without_fraction");

        // Multiplied prints
        $retFees['multiplied_tiny_print'] = $this->getFormattedPriceArray($retFees['multiplied'], "tiny");
        $retFees['multiplied_tiny_without_fraction_print'] = $this->getFormattedPriceArray($retFees['multiplied'], "tiny_without_fraction");
        $retFees['multiplied_print'] = $this->getFormattedPriceArray($retFees['multiplied'], "regular");
        $retFees['multiplied_without_fraction_print'] = $this->getFormattedPriceArray($retFees['multiplied'], "regular_without_fraction");
        $retFees['multiplied_long_print'] = $this->getFormattedPriceArray($retFees['multiplied'], "long");
        $retFees['multiplied_long_without_fraction_print'] = $this->getFormattedPriceArray($retFees['multiplied'], "long_without_fraction");

        // Print percentages
        $retPrices['print_tax_percentage'] = StaticFormatter::getFormattedPercentage($retFees['tax_percentage'], "regular");

        return $retFees;
    }
}