<?php
/**
 * NRS Location Fee Manager
 * Abstract class cannot be inherited anymore. We use them when creating new instances
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

class LocationFeeManager
{
    protected $conf 	                = NULL;
    protected $lang 		            = NULL;
    protected $debugMode 	            = 0;
    protected $settings 	            = array();
    protected $locationId 	            = 0;
    protected $pickupLocationId 	    = 0;
    protected $returnLocationId 	    = 0;

    protected $currencySymbol		    = '$';
    protected $currencyCode			    = 'USD';
    protected $currencySymbolLocation	= 0;
    // Dynamic tax percentage
    protected $taxPercentage		    = 0.00;
    protected $showPriceWithTaxes	    = 0;

    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings, $paramLocationId, $paramTaxPercentage)
    {
        $this->locationId = StaticValidator::getValidValue($paramLocationId, 'positive_integer', 0);
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;
        // Set saved settings
        $this->settings = $paramSettings;

        $this->currencySymbol = StaticValidator::getValidSetting($paramSettings, 'conf_currency_symbol', "textval", "$");
        $this->currencyCode = StaticValidator::getValidSetting($paramSettings, 'conf_currency_code', "textval", "USD");
        $this->currencySymbolLocation = StaticValidator::getValidSetting($paramSettings, 'conf_currency_symbol_location', 'positive_integer', 0, array(0, 1));
        // Dynamic tax percentage
        $this->taxPercentage = floatval($paramTaxPercentage);
        $this->showPriceWithTaxes = StaticValidator::getValidSetting($paramSettings, 'conf_show_price_with_taxes', 'positive_integer', 1, array(0, 1));
    }


    /**
     * Get location fees from MySQL database
     * @param int $paramLocationId - primary it's this class unique id, with some exceptions when we call for afterhours id
     * @return mixed
     */
    private function getFeesById($paramLocationId)
    {
        $validLocationId = StaticValidator::getValidPositiveInteger($paramLocationId);
        $sqlQuery = "
			SELECT
				pickup_fee, return_fee, afterhours_pickup_fee, afterhours_return_fee
			FROM {$this->conf->getPrefix()}locations
			WHERE location_id='{$validLocationId}'
		";
        $retFees = $this->conf->getInternalWPDB()->get_row($sqlQuery, ARRAY_A);

        return $retFees;
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

    public function getUnitDetails($paramDistanceFee = 0.00, $paramIsAfterHours = FALSE)
    {
        return $this->getDetails($paramDistanceFee, 1, $paramIsAfterHours);
    }

    public function getDetails($paramDistanceFee = 0.00, $paramMultiplier = 1, $paramIsAfterHours = FALSE)
    {
        $validMultiplier = StaticValidator::getValidPositiveInteger($paramMultiplier, 1);

        $locationFees = $this->getFeesById($this->locationId);
        // Calculate pickup, return and distance fees with taxes
        if(!is_null($locationFees))
        {
            $pickupFee = $locationFees['pickup_fee'];
            $returnFee = $locationFees['return_fee'];
            $returnWithDistanceFee = $locationFees['return_fee'] + floatval($paramDistanceFee);
            $afterHoursPickupFee = $locationFees['afterhours_pickup_fee'];
            $afterHoursReturnFee = $locationFees['afterhours_return_fee'];
            $afterHoursReturnWithDistanceFee = $locationFees['afterhours_return_fee'] + floatval($paramDistanceFee);
        } else
        {
            $pickupFee = 0.00;
            $returnFee = 0.00;
            $returnWithDistanceFee = 0.00;
            $afterHoursPickupFee = 0.00;
            $afterHoursReturnFee = 0.00;
            $afterHoursReturnWithDistanceFee = 0.00;
        }

        $pickupFeeWithTax = $pickupFee * (1 + $this->taxPercentage / 100);
        $pickupFeeDynamic = $this->showPriceWithTaxes == 1 ? $pickupFeeWithTax : $pickupFee;
        $pickupTaxAmount = $pickupFeeWithTax - $pickupFee;

        $returnFeeWithTax = $returnFee * (1 + $this->taxPercentage / 100);
        $returnFeeDynamic = $this->showPriceWithTaxes == 1 ? $returnFeeWithTax : $returnFee;
        $returnTaxAmount = $returnFeeWithTax - $returnFee;

        $returnWithDistanceFeeWithTax = $returnWithDistanceFee * (1 + $this->taxPercentage / 100);
        $returnWithDistanceFeeDynamic = $this->showPriceWithTaxes == 1 ? $returnWithDistanceFeeWithTax : $returnWithDistanceFee;
        $returnWithDistanceTaxAmount = $returnWithDistanceFeeWithTax - $returnWithDistanceFee;

        $afterHoursPickupFeeWithTax = $afterHoursPickupFee * (1 + $this->taxPercentage / 100);
        $afterHoursPickupFeeDynamic = $this->showPriceWithTaxes == 1 ? $afterHoursPickupFeeWithTax : $afterHoursPickupFee;
        $afterHoursPickupTaxAmount = $afterHoursPickupFeeWithTax - $afterHoursPickupFee;

        $afterHoursReturnFeeWithTax = $afterHoursReturnFee * (1 + $this->taxPercentage / 100);
        $afterHoursReturnFeeDynamic = $this->showPriceWithTaxes == 1 ? $afterHoursReturnFeeWithTax : $afterHoursReturnFee;
        $afterHoursReturnTaxAmount = $afterHoursReturnFeeWithTax - $afterHoursReturnFee;

        $afterHoursReturnWithDistanceFeeWithTax = $afterHoursReturnWithDistanceFee * (1 + $this->taxPercentage / 100);
        $afterHoursReturnWithDistanceFeeDynamic = $this->showPriceWithTaxes == 1 ? $afterHoursReturnWithDistanceFeeWithTax : $afterHoursReturnWithDistanceFee;
        $afterHoursReturnWithDistanceTaxAmount = $afterHoursReturnWithDistanceFeeWithTax - $afterHoursReturnWithDistanceFee;

        $currentPickupFee = $paramIsAfterHours ? $afterHoursPickupFee : $pickupFee;
        $currentPickupFeeWithTax = $currentPickupFee * (1 + $this->taxPercentage / 100);
        $currentPickupFeeDynamic = $this->showPriceWithTaxes == 1 ? $currentPickupFeeWithTax : $currentPickupFee;
        $currentPickupTaxAmount = $currentPickupFeeWithTax - $currentPickupFee;

        $currentReturnFee = $paramIsAfterHours ? $afterHoursReturnFee : $returnFee;
        $currentReturnFeeWithTax = $currentReturnFee * (1 + $this->taxPercentage / 100);
        $currentReturnFeeDynamic = $this->showPriceWithTaxes == 1 ? $currentReturnFeeWithTax : $currentReturnFee;
        $currentReturnTaxAmount = $currentReturnFeeWithTax - $currentReturnFee;

        $currentReturnWithDistanceFee = $paramIsAfterHours ? $afterHoursReturnWithDistanceFee : $returnWithDistanceFee;
        $currentReturnWithDistanceFeeWithTax = $currentReturnWithDistanceFee * (1 + $this->taxPercentage / 100);
        $currentReturnWithDistanceFeeDynamic = $this->showPriceWithTaxes == 1 ? $currentReturnWithDistanceFeeWithTax : $currentReturnWithDistanceFee;
        $currentReturnWithDistanceTaxAmount = $currentReturnWithDistanceFeeWithTax - $currentReturnWithDistanceFee;

        // Fee details
        $printCurrentPickupFeeDetails = "";
        $printCurrentReturnFeeDetails = "";
        $printCurrentReturnWithDistanceFeeDetails = "";
        $printMultipliedCurrentPickupFeeDetails = "";
        $printMultipliedCurrentReturnFeeDetails = "";
        $printMultipliedCurrentReturnWithDistanceFeeDetails = "";
        if($this->taxPercentage > 0)
        {
            $printCurrentPickupFeeDetails .= $this->getFormattedPrice($currentPickupFee, 'regular').' '.$this->lang->getText('NRS_WITHOUT_TAX_TEXT').' + ';
            $printCurrentPickupFeeDetails .= $this->getFormattedPrice($currentPickupTaxAmount, 'regular').' '.$this->lang->getText('NRS_TAX_TEXT').' = ';
            $printCurrentPickupFeeDetails .= $this->getFormattedPrice($currentPickupFeeWithTax, 'regular');

            $printCurrentReturnFeeDetails .= $this->getFormattedPrice($currentReturnFee, 'regular').' '.$this->lang->getText('NRS_WITHOUT_TAX_TEXT').' + ';
            $printCurrentReturnFeeDetails .= $this->getFormattedPrice($currentReturnTaxAmount, 'regular').' '.$this->lang->getText('NRS_TAX_TEXT').' = ';
            $printCurrentReturnFeeDetails .= $this->getFormattedPrice($currentReturnFeeWithTax, 'regular');

            $printCurrentReturnWithDistanceFeeDetails .= $this->getFormattedPrice($currentReturnWithDistanceFee, 'regular').' '.$this->lang->getText('NRS_WITHOUT_TAX_TEXT').' + ';
            $printCurrentReturnWithDistanceFeeDetails .= $this->getFormattedPrice($currentReturnWithDistanceTaxAmount, 'regular').' '.$this->lang->getText('NRS_TAX_TEXT').' = ';
            $printCurrentReturnWithDistanceFeeDetails .= $this->getFormattedPrice($currentReturnWithDistanceFeeWithTax, 'regular');

            $printMultipliedCurrentPickupFeeDetails .= $this->getFormattedPrice($currentPickupFee * $validMultiplier, 'regular').' '.$this->lang->getText('NRS_WITHOUT_TAX_TEXT').' + ';
            $printMultipliedCurrentPickupFeeDetails .= $this->getFormattedPrice($currentPickupTaxAmount * $validMultiplier, 'regular').' '.$this->lang->getText('NRS_TAX_TEXT').' = ';
            $printMultipliedCurrentPickupFeeDetails .= $this->getFormattedPrice($currentPickupFeeWithTax * $validMultiplier, 'regular');

            $printMultipliedCurrentReturnFeeDetails .= $this->getFormattedPrice($currentReturnFee * $validMultiplier, 'regular').' '.$this->lang->getText('NRS_WITHOUT_TAX_TEXT').' + ';
            $printMultipliedCurrentReturnFeeDetails .= $this->getFormattedPrice($currentReturnTaxAmount * $validMultiplier, 'regular').' '.$this->lang->getText('NRS_TAX_TEXT').' = ';
            $printMultipliedCurrentReturnFeeDetails .= $this->getFormattedPrice($currentReturnFeeWithTax * $validMultiplier, 'regular');

            $printMultipliedCurrentReturnWithDistanceFeeDetails .= $this->getFormattedPrice($currentReturnWithDistanceFee * $validMultiplier, 'regular').' '.$this->lang->getText('NRS_WITHOUT_TAX_TEXT').' + ';
            $printMultipliedCurrentReturnWithDistanceFeeDetails .= $this->getFormattedPrice($currentReturnWithDistanceTaxAmount * $validMultiplier, 'regular').' '.$this->lang->getText('NRS_TAX_TEXT').' = ';
            $printMultipliedCurrentReturnWithDistanceFeeDetails .= $this->getFormattedPrice($currentReturnWithDistanceFeeWithTax * $validMultiplier, 'regular');
        }


        // Fee details
        $retFees = array();
        $retFees['unit'] = array(
            "pickup_fee" => $pickupFee,
            "pickup_fee_with_tax" => $pickupFeeWithTax,
            "pickup_fee_dynamic" => $pickupFeeDynamic,
            "pickup_tax_amount" => $pickupTaxAmount,

            "return_fee" => $returnFee,
            "return_fee_with_tax" => $returnFeeWithTax,
            "return_fee_dynamic" => $returnFeeDynamic,
            "return_tax_amount" => $returnTaxAmount,

            "return_with_distance_fee" => $returnWithDistanceFee,
            "return_with_distance_fee_with_tax" => $returnWithDistanceFeeWithTax,
            "return_with_distance_fee_dynamic" => $returnWithDistanceFeeDynamic,
            "return_with_distance_tax_amount" => $returnWithDistanceTaxAmount,

            "afterhours_pickup_fee" => $afterHoursPickupFee,
            "afterhours_pickup_fee_with_tax" => $afterHoursPickupFeeWithTax,
            "afterhours_pickup_fee_dynamic" => $afterHoursPickupFeeDynamic,
            "afterhours_pickup_tax_amount" => $afterHoursPickupTaxAmount,

            "afterhours_return_fee" => $afterHoursReturnFee,
            "afterhours_return_fee_with_tax" => $afterHoursReturnFeeWithTax,
            "afterhours_return_fee_dynamic" => $afterHoursReturnFeeDynamic,
            "afterhours_return_tax_amount" => $afterHoursReturnTaxAmount,

            "afterhours_return_with_distance_fee" => $afterHoursReturnWithDistanceFee,
            "afterhours_return_with_distance_fee_with_tax" => $afterHoursReturnWithDistanceFeeWithTax,
            "afterhours_return_with_distance_fee_dynamic" => $afterHoursReturnWithDistanceFeeDynamic,
            "afterhours_return_with_distance_tax_amount" => $afterHoursReturnWithDistanceTaxAmount,

            "current_pickup_fee" => $currentPickupFee,
            "current_pickup_fee_with_tax" => $currentPickupFeeWithTax,
            "current_pickup_fee_dynamic" => $currentPickupFeeDynamic,
            "current_pickup_tax_amount" => $currentPickupTaxAmount,

            "current_return_fee" => $currentReturnFee,
            "current_return_fee_with_tax" => $currentReturnFeeWithTax,
            "current_return_fee_dynamic" => $currentReturnFeeDynamic,
            "current_return_tax_amount" => $currentReturnTaxAmount,

            "current_return_with_distance_fee" => $currentReturnWithDistanceFee,
            "current_return_with_distance_fee_with_tax" => $currentReturnWithDistanceFeeWithTax,
            "current_return_with_distance_fee_dynamic" => $currentReturnWithDistanceFeeDynamic,
            "current_return_with_distance_tax_amount" => $currentReturnWithDistanceTaxAmount,
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
        $retFees['print_tax_percentage'] = StaticFormatter::getFormattedPercentage($retFees['tax_percentage'], "regular");
        $retFees['print_current_pickup_fee_details'] = $printCurrentPickupFeeDetails;
        $retFees['print_current_return_fee_details'] = $printCurrentReturnFeeDetails;
        $retFees['print_current_return_with_distance_fee_details'] = $printCurrentReturnWithDistanceFeeDetails;
        $retFees['print_multiplied_current_pickup_fee_details'] = $printMultipliedCurrentPickupFeeDetails;
        $retFees['print_multiplied_current_return_fee_details'] = $printMultipliedCurrentReturnFeeDetails;
        $retFees['print_multiplied_current_return_with_distance_fee_details'] = $printMultipliedCurrentReturnWithDistanceFeeDetails;

        return $retFees;
    }
}