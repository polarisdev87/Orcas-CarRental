<?php
/**
 * Abstract classes can't be created with new instance. It is only possible if they are extended by childs
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
*/
namespace NativeRentalSystem\Models\Block;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Validation\StaticValidator;

abstract class AbstractBlockManager
{
    protected $conf 	            = NULL;
    protected $lang 		        = NULL;
    protected $settings             = array();
    protected $debugMode 	        = 0;
    protected $bookingCode			= ""; // Unique identifier
    protected $okayMessages         = array();
    protected $errorMessages        = array();

	// settings from DB
	/**
	 * @var int - admin setting in seconds - minimum block period between two bookings for same car
	 */
	protected $minBookingPeriod 	= 0;
	/**
	* @var int - admin setting in seconds - maximum reservation period
	*/
	protected $maxBookingPeriod 	= 0;
	/**
	 * @var int - price calculation: 1 - daily, 2 - hourly, 3 - mixed (daily+hourly)
	 */
	protected $blockPeriod 			= 0;
	/**
	 * @var int - admin setting in seconds - minimum period until pickup
	 */
	protected $minPeriodUntilPickup	= 0;
	/**
	 * @var int - admin setting - price calculation type
	 */
	protected $priceCalculationType = 1;

	// pre-filled in step 1
	protected $startTimestamp  	    = 0;
	protected $endTimestamp 	    = 0;
	protected $shortDateFormat		= 'Y-m-d';

	/**
     * @param ExtensionConfiguration &$paramConf
     * @param Language &$paramLang
	 * @param array $paramSettings
	 */
	public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings)
	{
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;
        $this->settings = $paramSettings;

		$this->minBookingPeriod = StaticValidator::getValidSetting($paramSettings, 'conf_minimum_booking_period', 'positive_integer', 0);
		$this->maxBookingPeriod = StaticValidator::getValidSetting($paramSettings, 'conf_maximum_booking_period', 'positive_integer', 0);
		$this->blockPeriod = StaticValidator::getValidSetting($paramSettings, 'conf_minimum_block_period_between_bookings', 'positive_integer', 0);
		$this->minPeriodUntilPickup = StaticValidator::getValidSetting($paramSettings, 'conf_minimum_period_until_pickup', 'positive_integer', 0);
		$this->priceCalculationType = StaticValidator::getValidSetting($paramSettings, 'conf_price_calculation_type', 'positive_integer', 1, array(1, 2, 3));

		// Extra settings used for location manager, not by the class itself
		$this->shortDateFormat = StaticValidator::getValidSetting($paramSettings, 'conf_short_date_format', "date_format", "Y-m-d");
		$this->currencySymbol = StaticValidator::getValidSetting($paramSettings, 'conf_currency_symbol', "textval", "$");
		$this->currencyCode = StaticValidator::getValidSetting($paramSettings, 'conf_currency_code', "textval", "USD");
        $this->currencySymbolLocation = StaticValidator::getValidSetting($paramSettings, 'conf_currency_symbol_location', 'positive_integer', 0, array(0, 1));
	}

	/**
	 * Most modern parameter setup
	 * @param array $varTypes - array where to search for data SESSION, POST, SERVER, ...
	 * @param string $varParam
	 * @param $defaultValue
	 * @param bool $required
	 * @param string $validate
	 * @return bool
	 */
	protected function getValidValueInput($varTypes = array(), $varParam = "", $defaultValue, $required = false, $validate = "guest_text_validation")
	{
		$tmpValue = FALSE;
		$varTypeUsed = "";
		foreach($varTypes AS $varType)
		{
			$stack = array();
			switch($varType)
			{
				case "POST":
					$stack = $_POST;
					break;
				case "GET":
					$stack = $_GET;
					break;
				case "SESSION":
					$stack = $_SESSION;
					break;
				case "REQUEST":
					$stack = $_REQUEST;
					break;
				case "SERVER":
					$stack = $_SERVER;
					break;
			}

			if(isset($stack[$varParam]))
			{
				$varTypeUsed = $varType;
				$tmpValue = $stack[$varParam];
				// break when first success was found - that's why order - POST, GET, SESSION - is important
				break;
			}
		}

		// Make a error note if we still have FALSE after all process and this field is required
		if($required == TRUE && $tmpValue === FALSE)
		{
			// Invalidate the request. Like in try/catch/finally, but without it
			$this->addInputError($varParam);
		}

		if(is_array($tmpValue))
		{
			// NOT ALLOWED! This will happen when we pass an array, when we strict to not arrays only
			// It's ok to pass default value here twice in this situation
			$ret = StaticValidator::getValidValue($defaultValue, $validate, $defaultValue);
		} else
		{
			// OK
			$ret = StaticValidator::getValidValue($tmpValue, $validate, $defaultValue);
		}

		// Make a error note if we do not yet been got it from getRequestedMemberValue(), this means that it didn't returned false
		if($required == TRUE && ($ret === FALSE || $ret == "" || $ret == "0" || $ret == "0000-00-00"))
		{
			// Invalidate the request. Like in try/catch/finally, but without it
			$this->addInputError($varParam);
		}

		if($this->debugMode == 1)
		{
			echo "<br /><strong>[Security]</strong> ";
			//echo "Types: [".implode(", ", $varTypes)."], ";
			echo "Used: {$varTypeUsed}, ";
			echo "Param: $varParam, ";
			echo "Validation: {$validate}, Required: ".var_export($required, TRUE).", Value: ".var_export($ret, TRUE);
		}

		return $ret;
	}

	/**
	 * Most modern parameter setup
	 * @param array $varTypes - array where to search for data SESSION, POST, SERVER, ...
	 * @param string $varParam
	 * @param $defaultArray
	 * @param bool $required
	 * @param string $validate
	 * @return array
	 */
	protected function getValidArrayInput($varTypes = array(), $varParam = '', $defaultArray, $required = false, $validate = "guest_text_validation")
	{
		$varTypeUsed = "";
		$tmpArray = array();
		foreach($varTypes AS $varType)
		{
			$stack = array();
			switch($varType)
			{
				case "POST":
					$stack = $_POST;
					break;
				case "GET":
					$stack = $_GET;
					break;
				case "SESSION":
					$stack = $_SESSION;
					break;
				case "REQUEST":
					$stack = $_REQUEST;
					break;
				case "SERVER":
					$stack = $_SERVER;
					break;
			}

			if(isset($stack[$varParam]))
			{
				$varTypeUsed = $varType;
				$tmpArray = $stack[$varParam];
				// break when first success was found - that's why order - POST, GET, SESSION - is important
				break;
			}
		}

		// Make a error note if we still have FALSE after all process and this field is required
		if($required == TRUE && sizeof($tmpArray) == 0)
		{
			// Invalidate the request. Like in try/catch/finally, but without it
			$this->addInputError($varParam);
		}

		if(is_array($tmpArray))
		{
			// OK
			$ret = StaticValidator::getValidArray($tmpArray, $validate, $defaultArray);
		} else
		{
			// NOT ALLOWED! This will happen when we pass not array, when we strict to arrays only
			// It's ok to pass default value here twice in this situation
			$ret = StaticValidator::getValidArray($defaultArray, $validate, $defaultArray);
		}

		// Make a error note if we do not yet been got it from getRequestedMemberValue(), this means that it didn't returned false
		if($required == TRUE && sizeof($ret) == 0)
		{
			// Invalidate the request. Like in try/catch/finally, but without it
			$this->addInputError($varParam);
		}

		if($this->debugMode == 1)
		{
			echo "<br /><strong>[Security]</strong> ";
			//echo "Types: [".implode(", ", $varTypes)."], ";
			echo "Used: {$varTypeUsed}, ";
			echo "Param: $varParam, ";
			echo "Validation: {$validate}, Required: ".var_export($required, TRUE).", Array: "; var_export($ret, TRUE);
		}

		return $ret;
	}

	protected function addInputError($brokenParam)
	{
		$brokenInputFieldTitle = ucfirst(str_replace("_", " ", $brokenParam));
		$this->errorMessages[] = $this->lang->getText('NRS_ERROR_REQUIRED_FIELD_TEXT')." ({$brokenInputFieldTitle}) ".$this->lang->getText('NRS_ERROR_IS_EMPTY_TEXT');
	}

	/**************************************************************/
	/************************* SETTINGS ***************************/
	/**************************************************************/

	public function inDebug()
	{
		return ($this->debugMode >= 1 ? TRUE : FALSE);
	}

	public function isValidBlock()
	{
	    if(sizeof($this->errorMessages) == 0)
        {
            return TRUE;
        } else
        {
            return FALSE;
        }
	}

    public function flushMessages()
    {
        $this->okayMessages = array();
        $this->errorMessages = array();
    }

    public function getOkayMessages()
    {
        return $this->okayMessages;
    }

    public function getErrorMessages()
    {
        return $this->errorMessages;
    }

	/**************************************************************/
	/************************** GET PRINTS ************************/
	/**************************************************************/

	public function getPrintStartDate()
	{
		// WordPress bug
		// BAD: return date_i18n(get_option('date_format'), $this->pickupTimestamp);
		// OK: return date(get_option('date_format'), $this->pickupTimestamp + get_option( 'gmt_offset' ) * 3600);

		// WordPress bug WorkAround
		return date_i18n(get_option('date_format'), $this->startTimestamp + get_option( 'gmt_offset' ) * 3600, TRUE);
	}

	public function getPrintStartTime()
	{
		// WordPress bug
		// BAD: return date_i18n(get_option('time_format'), $this->pickupTimestamp);
		// OK: return date(get_option('time_format'), $this->pickupTimestamp + get_option( 'gmt_offset' ) * 3600);
		return date_i18n(get_option('time_format'), $this->startTimestamp + get_option( 'gmt_offset' ) * 3600, TRUE);
	}

	public function getPrintEndDate()
	{
		// WordPress bug
		// BAD: return date_i18n(get_option('date_format'), $this->returnTimestamp);
		// OK: return date(get_option('date_format'), $this->returnTimestamp + get_option( 'gmt_offset' ) * 3600);
		return date_i18n(get_option('date_format'), $this->endTimestamp + get_option( 'gmt_offset' ) * 3600, TRUE);
	}

	public function getPrintEndTime()
	{
		// WordPress bug
		// BAD: return date_i18n(get_option('time_format'), $this->returnTimestamp);
		// OK: return date(get_option('time_format'), $this->returnTimestamp + get_option( 'gmt_offset' ) * 3600);
		return date_i18n(get_option('time_format'), $this->endTimestamp + get_option( 'gmt_offset' ) * 3600, TRUE);
	}

    /**
     * Can be used for edit, or as a raw data.
     * @return string
     */
	public function getShortStartDate()
	{
		// WordPress workaround
		return date_i18n($this->shortDateFormat, $this->startTimestamp + get_option( 'gmt_offset' ) * 3600, TRUE);
	}

    /**
     * Can be used for edit, or as a raw data.
     * @return string
     */
	public function getShortStartTime()
	{
		// WordPress workaround
		return date_i18n("H:i:s", $this->startTimestamp + get_option( 'gmt_offset' ) * 3600, TRUE);
	}

    /**
     * Can be used for edit, or as a raw data.
     * @return string
     */
	public function getShortEndDate()
	{
		// WordPress workaround
		return date_i18n($this->shortDateFormat, $this->endTimestamp + get_option( 'gmt_offset' ) * 3600, TRUE);
	}

    /**
     * Can be used for edit, or as a raw data.
     * @return string
     */
	public function getShortEndTime()
	{
		// WordPress workaround
		return date_i18n("H:i:s", $this->endTimestamp + get_option( 'gmt_offset' ) * 3600, TRUE);
	}

	public function getPrintBlockDuration()
	{
		return $this->lang->getPrintCeilDurationByPeriod($this->priceCalculationType, StaticValidator::getPeriod($this->startTimestamp, $this->endTimestamp, FALSE));
	}

	/**************************************************************/
	/************** Just an methods abbreviation ******************/
	/**************************************************************/
	public function getLocalStartDate()
	{
		return StaticValidator::getLocalDateByTimestamp($this->startTimestamp, "Y-m-d");
	}

	public function getLocalStartTime()
	{
		return StaticValidator::getLocalDateByTimestamp($this->startTimestamp, "H:i:s");
	}

	public function getLocalEndDate()
	{
		return StaticValidator::getLocalDateByTimestamp($this->endTimestamp, "Y-m-d");
	}

	public function getLocalEndTime()
	{
		return StaticValidator::getLocalDateByTimestamp($this->endTimestamp, "H:i:s");
	}

	public function getLocalStartDayOfWeek()
	{
		return StaticValidator::getLocalDateByTimestamp($this->startTimestamp, "D");
	}

	public function getLocalEndDayOfWeek()
	{
		return StaticValidator::getLocalDateByTimestamp($this->endTimestamp, "D");
	}

	public function getBlockPeriod()
	{
		return StaticValidator::getPeriod($this->startTimestamp, $this->endTimestamp, FALSE);
	}

	/**********************************************************************************************/
	/************************************* ACTUAL ELEMENTS ****************************************/
	/**********************************************************************************************/

	public function getStartTimestamp()
	{
		return $this->startTimestamp;
	}

	public function getEndTimestamp()
	{
		return $this->endTimestamp;
	}

	/**************************************************************/
	/******************** Advanced methods **********************/
	/**************************************************************/

	/**
	 * @param $pickupTimestamp
	 * @param $returnTimestamp
	 * @return bool
	 */
    public function validateTimeInput($pickupTimestamp, $returnTimestamp)
	{
		if(StaticValidator::getPeriod($pickupTimestamp, $returnTimestamp, TRUE) < 0)
		{
			$this->errorMessages[] = $this->lang->getText('NRS_ERROR_OUT_BEFORE_IN_TEXT');
		} else if(StaticValidator::getPeriod($pickupTimestamp, $returnTimestamp, FALSE) < $this->minBookingPeriod)
		{
			$errorMessage = $this->lang->getText('NRS_ERROR_MINIMUM_NUMBER_OF_NIGHT_SHOULD_NOT_BE_LESS_THAN_TEXT').' ';
			$errorMessage .= $this->lang->getPrintCeilDurationByPeriod($this->priceCalculationType, $this->minBookingPeriod).'. '. $this->lang->getText('NRS_ERROR_PLEASE_MODIFY_YOUR_SEARCH_CRITERIA_TEXT');
			$this->errorMessages[] = $errorMessage;
		} else if(StaticValidator::getPeriod($pickupTimestamp, $returnTimestamp, FALSE) > $this->maxBookingPeriod)
		{
			$errorMessage = $this->lang->getText('NRS_ERROR_MAXIMUM_BOOKING_LENGTH_CANT_BE_MORE_THAN_TEXT').' ';
			$errorMessage .= $this->lang->getPrintCeilDurationByPeriod($this->priceCalculationType, $this->maxBookingPeriod).'. '. $this->lang->getText('NRS_ERROR_PLEASE_MODIFY_YOUR_SEARCH_CRITERIA_TEXT');
			$this->errorMessages[] = $errorMessage;
		}

		// Both members bellow are in GMT+0
		if($this->startTimestamp < time())
		{
			$currentDateTime = date_i18n(get_option('date_format').' '.get_option('time_format'));
			$errorMessage = $this->lang->getText('NRS_ERROR_PICKUP_IS_NOT_POSSIBLE_ON_TEXT').' ';
			$errorMessage .= $this->getPrintStartDate().' '.$this->getPrintStartTime().'. ';
			$errorMessage .= $this->lang->getText('NRS_ERROR_PLEASE_MODIFY_YOUR_PICKUP_TIME_BY_WEBSITE_TIME_TEXT').' <br />';
			$errorMessage .= $this->lang->getText('NRS_ERROR_CURRENT_DATE_TIME_TEXT') .' '.$currentDateTime;

			$this->errorMessages[] = $errorMessage;
		} else if($this->startTimestamp < (time() + $this->minPeriodUntilPickup))
		{
			$earliestPossibleStartLocalTime = time() + $this->minPeriodUntilPickup + get_option('gmt_offset') * 3600;
			$earliestPossibleStartDateTime = date_i18n(get_option('date_format').' '.get_option('time_format'), $earliestPossibleStartLocalTime, TRUE);
			$errorMessage = $this->lang->getText('NRS_ERROR_PICKUP_IS_NOT_POSSIBLE_ON_TEXT').' ';
			$errorMessage .= $this->getPrintStartDate().' '.$this->getPrintStartTime().'. <br />';
			$errorMessage .= $this->lang->getText('NRS_ERROR_EARLIEST_POSSIBLE_PICKUP_DATE_TIME_TEXT') .' '.$earliestPossibleStartDateTime.' ';
			$errorMessage .= $this->lang->getText('NRS_ERROR_OR_NEXT_BUSINESS_HOURS_OF_PICKUP_LOCATION_TEXT').'.';
			if($this->debugMode)
			{
				$errorMessage .= ' ('.$earliestPossibleStartLocalTime.')';
			}

			$this->errorMessages[] = $errorMessage;
		}

		return $this->isValidBlock();
	}
}