<?php
/**
 * Extra's Deposit Manager

 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Deposit;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Formatting\StaticFormatter;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;

class ExtraDepositManager implements iDepositManager
{
    protected $conf 	                = NULL;
    protected $lang 		            = NULL;
    protected $debugMode 	            = 0;
    protected $settings                 = array();
    protected $depositEnabled 		    = 1;
    protected $currencySymbol		    = '$';
    protected $currencyCode			    = 'USD';
    protected $currencySymbolLocation	= 0;
    protected $extraId			        = 0;
    
	public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, $paramSettings, $paramExtraId)
	{
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;
        $this->settings = $paramSettings;

        $this->extraId = StaticValidator::getValidValue($paramExtraId, 'positive_integer', 0);

        $this->currencySymbol = StaticValidator::getValidSetting($paramSettings, 'conf_currency_symbol', "textval", "$");
        $this->currencyCode = StaticValidator::getValidSetting($paramSettings, 'conf_currency_code', "textval", "USD");
        $this->currencySymbolLocation = StaticValidator::getValidSetting($paramSettings, 'conf_currency_symbol_location', 'positive_integer', 0, array(0, 1));

        if(isset($paramSettings['conf_deposit_enabled']))
        {
            // Set deposit status
            $this->depositEnabled = StaticValidator::getValidPositiveInteger($paramSettings['conf_deposit_enabled'], 1) == 1 ? TRUE : FALSE;
        }
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

	/**
	 * @return int
	 */
    public function getAmount()
	{
		$fixedRentalDeposit = 0;

		if($this->depositEnabled)
		{
			$validExtraId = StaticValidator::getValidPositiveInteger($this->extraId, 0);

			$query = "
			  SELECT fixed_rental_deposit
			  FROM {$this->conf->getPrefix()}extras
			  WHERE extra_id='{$validExtraId}'
			";
			$row = $this->conf->getInternalWPDB()->get_row($query, ARRAY_A);

			// DEBUG
			//echo nl2br($query);

			if(!is_null($row))
			{
				$fixedRentalDeposit = $row['fixed_rental_deposit'];
			}
		}

		return $fixedRentalDeposit;
	}

	public function getDetails()
	{
        $retDeposit = array();
        $unitFixedDepositAmount = $this->getAmount();
        // We need the line bellow, to not have printed out 1.019 as 1.01. We always want to print 1.02 instead.
        $roundedDeposit = round($unitFixedDepositAmount, 2);

        $retDeposit['unit']['fixed_deposit_amount'] = $unitFixedDepositAmount;

        // Unit prints
        if($roundedDeposit == 0.00)
        {
            $retDeposit['unit_tiny_print']['fixed_deposit_amount'] = $this->lang->getText('NRS_NOT_REQ_TEXT');
            $retDeposit['unit_tiny_without_fraction_print']['fixed_deposit_amount'] = $this->lang->getText('NRS_NOT_REQ_TEXT');
            $retDeposit['unit_print']['fixed_deposit_amount'] = $this->lang->getText('NRS_NOT_REQ_TEXT');
            $retDeposit['unit_without_fraction_print']['fixed_deposit_amount'] = $this->lang->getText('NRS_NOT_REQ_TEXT');
            $retDeposit['unit_long_print']['fixed_deposit_amount'] = $this->lang->getText('NRS_NOT_REQUIRED_TEXT');
            $retDeposit['unit_long_without_fraction_print']['fixed_deposit_amount'] = $this->lang->getText('NRS_NOT_REQUIRED_TEXT');
        } else
        {
            $retDeposit['unit_tiny_print']['fixed_deposit_amount'] = StaticFormatter::getFormattedPrice($roundedDeposit, "tiny", $this->currencySymbol, $this->currencyCode, $this->currencySymbolLocation);
            $retDeposit['unit_tiny_without_fraction_print']['fixed_deposit_amount'] = StaticFormatter::getFormattedPrice($roundedDeposit, "tiny_without_fraction", $this->currencySymbol, $this->currencyCode, $this->currencySymbolLocation);
            $retDeposit['unit_print']['fixed_deposit_amount'] = StaticFormatter::getFormattedPrice($roundedDeposit, "regular", $this->currencySymbol, $this->currencyCode, $this->currencySymbolLocation);
            $retDeposit['unit_without_fraction_print']['fixed_deposit_amount'] = StaticFormatter::getFormattedPrice($roundedDeposit, "regular_without_fraction", $this->currencySymbol, $this->currencyCode, $this->currencySymbolLocation);
            $retDeposit['unit_long_print']['fixed_deposit_amount'] = StaticFormatter::getFormattedPrice($roundedDeposit, "long", $this->currencySymbol, $this->currencyCode, $this->currencySymbolLocation);
            $retDeposit['unit_long_without_fraction_print']['fixed_deposit_amount'] = StaticFormatter::getFormattedPrice($roundedDeposit, "long_without_fraction", $this->currencySymbol, $this->currencyCode, $this->currencySymbolLocation);
        }

        return $retDeposit;
	}
}
