<?php
/**
 * Abstract classes can't be created with new instance. It is only possible if they are extended by childs
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
*/
namespace NativeRentalSystem\Models\Search;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Formatting\StaticFormatter;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Location\Location;

abstract class AbstractSearchManager
{
    protected $conf 	            = NULL;
    protected $lang 		        = NULL;
    protected $settings             = array();
    protected $debugMode 	        = 0;
    protected $bookingCode			= ""; // Unique identifier
    protected $okayMessages         = array();
    protected $errorMessages        = array();

	// settings from DB
	protected $searchEnabled 		= FALSE;
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
	protected $couponCode  	        = "";
	protected $pickupTimestamp  	= 0;
	protected $returnTimestamp 	    = 0;
	protected $pickupLocationId   	= 0;
	protected $returnLocationId  	= 0;
	protected $partnerId      	    = -1; // USE ONLY IN STEP1
	protected $manufacturerId      	= -1; // USE ONLY IN STEP1
	protected $bodyTypeId         	= -1; // USE ONLY IN STEP1
	protected $transmissionTypeId 	= -1; // USE ONLY IN STEP1
	protected $fuelTypeId     		= -1; // USE ONLY IN STEP1

	// pre-filled in step 2 (or step1 if booked from car page)
	protected $itemIds            	= array();
	protected $itemUnits        	= array();
	protected $itemOptions        	= array();

	// pre-filled in step 3
	protected $extraIds		   		= array();
	protected $extraUnits      		= array();
	protected $extraOptions    		= array();

	// Extra settings used for location manager, not by the class itself
	protected $shortDateFormat		= 'Y-m-d';
	protected $currencySymbol		= '$';
	protected $currencyCode			= 'USD';
    protected $currencySymbolLocation	= 0;

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

        // Set customer fields requirement status
        if(isset($paramSettings['conf_customer_title_required']))
        {
            $this->searchEnabled = $paramSettings['conf_search_enabled'] == 1 ? TRUE : FALSE;
        }
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
	 * @param bool $paramRequired
	 * @param string $paramValidate
	 * @return array
	 */
	protected function getValidArrayInput($varTypes = array(), $varParam = '', $defaultArray, $paramRequired = FALSE, $paramValidate = "guest_text_validation")
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
		if($paramRequired == TRUE && sizeof($tmpArray) == 0)
		{
			// Invalidate the request. Like in try/catch/finally, but without it
			$this->addInputError($varParam);
		}

		if(is_array($tmpArray))
		{
			// OK
			$ret = StaticValidator::getValidArray($tmpArray, $paramValidate, $defaultArray);
		} else
		{
			// NOT ALLOWED! This will happen when we pass not array, when we strict to arrays only
			// It's ok to pass default value here twice in this situation
			$ret = StaticValidator::getValidArray($defaultArray, $paramValidate, $defaultArray);
		}

		// Make a error note if we do not yet been got it from getRequestedMemberValue(), this means that it didn't returned false
		if($paramRequired == TRUE && sizeof($ret) == 0)
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
			echo "Validation: {$paramValidate}, Required: ".var_export($paramRequired, TRUE).", Array: ".var_export($ret, TRUE);
		}

		return $ret;
	}

	protected function addInputError($brokenParam)
	{
		$brokenInputFieldTitle = ucfirst(str_replace("_", " ", $brokenParam));
		$this->errorMessages[] = $this->lang->getText('NRS_ERROR_REQUIRED_FIELD_TEXT')." ({$brokenInputFieldTitle}) ".$this->lang->getText('NRS_ERROR_IS_EMPTY_TEXT');
	}

    public function setMySessionVars()
	{
		// filled data in step 1, pre-filled in step 2, 3, 4, 5, 6
        $_SESSION['coupon_code']   	  	= $this->couponCode;
		$_SESSION['pickup_location_id']	= $this->pickupLocationId;
		$_SESSION['pickup_date']   	  	= $this->getShortPickupDate();
		$_SESSION['pickup_time']   		= $this->getISOPickupTime();
		$_SESSION['return_location_id']= $this->returnLocationId;
		$_SESSION['return_date']  	  	= $this->getShortReturnDate();
		$_SESSION['return_time']  		= $this->getISOReturnTime();

		// Used only in step 1
		$_SESSION['partner_id']     	= $this->partnerId;
		$_SESSION['manufacturer_id']   	= $this->manufacturerId;
		$_SESSION['body_type_id']     	= $this->bodyTypeId;
		$_SESSION['transmission_type_id']= $this->transmissionTypeId;
		$_SESSION['fuel_type_id']  		= $this->fuelTypeId;

		// filled in step 2 (or step1 if booked from car page), pre-filled in step 3, 4, 5, 6
		$_SESSION['item_ids']   		= $this->itemIds;
		$_SESSION['item_units']   	  	= $this->itemUnits;
		$_SESSION['item_options']   	= $this->itemOptions;

		// filled in step 3, pre-filled in step 4, 5, 6
		$_SESSION['extra_ids']   	  	= $this->extraIds;
		$_SESSION['extra_units']   		= $this->extraUnits;
		$_SESSION['extra_options']   	= $this->extraOptions;

		// set in step 5 (booking process) or came from booking edit
		$_SESSION['booking_code']   	= $this->bookingCode;

		// DEBUG
        //echo "UPDATED SESSION VARS: ".nl2br(print_r($_SESSION, TRUE));
	}

	public function removeSessionVariables()
	{
        if(isset($_SESSION['coupon_code']))
        {
            unset($_SESSION['coupon_code']);
        }

        //  USE ONLY IN STEP1. After saving used in edit only
        if(isset($_SESSION['partner_id']))
        {
            unset($_SESSION['partner_id']);
        }

        //  USE ONLY IN STEP1. After saving used in edit only
        if(isset($_SESSION['manufacturer_id']))
        {
            unset($_SESSION['manufacturer_id']);
        }

		//  USE ONLY IN STEP1. After saving used in edit only
		if(isset($_SESSION['body_type_id']))
		{
			unset($_SESSION['body_type_id']);
		}

		//  USE ONLY IN STEP1. After saving used in edit only
		if(isset($_SESSION['transmission_type_id']))
		{
			unset($_SESSION['transmission_type_id']);
		}

		//  USE ONLY IN STEP1. After saving used in edit only
		if(isset($_SESSION['fuel_type_id']))
		{
			unset($_SESSION['fuel_type_id']);
		}

		// filled data in step1
		if(isset($_SESSION['pickup_location_id']))
		{
			unset($_SESSION['pickup_location_id']);
		}
		if(isset($_SESSION['pickup_date']))
		{
			unset($_SESSION['pickup_date']);
		}
		if(isset($_SESSION['pickup_time']))
		{
			unset($_SESSION['pickup_time']);
		}

		if(isset($_SESSION['return_location_id']))
		{
			unset($_SESSION['return_location_id']);
		}
		if(isset($_SESSION['return_date']))
		{
			unset($_SESSION['return_date']);
		}
		if(isset($_SESSION['return_time']))
		{
			unset($_SESSION['return_time']);
		}


		// filled in step 2 (or step1 if booked from car page)
		if(isset($_SESSION['item_ids']))
		{
			unset($_SESSION['item_ids']);
		}

		// filled in step 3
		if(isset($_SESSION['item_units']))
		{
			unset($_SESSION['item_units']);
		}

		if(isset($_SESSION['item_options']))
		{
			unset($_SESSION['item_options']);
		}

		if(isset($_SESSION['extra_ids']))
		{
			unset($_SESSION['extra_ids']);
		}

		if(isset($_SESSION['extra_units']))
		{
			unset($_SESSION['extra_units']);
		}

		if(isset($_SESSION['extra_options']))
		{
			unset($_SESSION['extra_options']);
		}

		// set in step 5 (booking process) or came from booking edit
		if(isset($_SESSION['booking_code']))
		{
			unset($_SESSION['booking_code']);
		}
	}

	/**************************************************************/
	/************************* SETTINGS ***************************/
	/**************************************************************/

	public function inDebug()
	{
		return ($this->debugMode >= 1 ? TRUE : FALSE);
	}

	public function searchEnabled()
    {
        return $this->searchEnabled;
    }

	public function isValidSearch()
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

	public function getPrintPickupDate()
	{
		// WordPress bug
		// BAD: return date_i18n(get_option('date_format'), $this->pickupTimestamp);
		// OK: return date(get_option('date_format'), $this->pickupTimestamp + get_option( 'gmt_offset' ) * 3600);

		// WordPress bug WorkAround
		return date_i18n(get_option('date_format'), $this->pickupTimestamp + get_option( 'gmt_offset' ) * 3600, TRUE);
	}

	public function getPrintPickupTime()
	{
		// WordPress bug
		// BAD: return date_i18n(get_option('time_format'), $this->pickupTimestamp);
		// OK: return date(get_option('time_format'), $this->pickupTimestamp + get_option( 'gmt_offset' ) * 3600);
		return date_i18n(get_option('time_format'), $this->pickupTimestamp + get_option( 'gmt_offset' ) * 3600, TRUE);
	}

	public function getPrintReturnDate()
	{
		// WordPress bug
		// BAD: return date_i18n(get_option('date_format'), $this->returnTimestamp);
		// OK: return date(get_option('date_format'), $this->returnTimestamp + get_option( 'gmt_offset' ) * 3600);
		return date_i18n(get_option('date_format'), $this->returnTimestamp + get_option( 'gmt_offset' ) * 3600, TRUE);
	}

	public function getPrintReturnTime()
	{
		// WordPress bug
		// BAD: return date_i18n(get_option('time_format'), $this->returnTimestamp);
		// OK: return date(get_option('time_format'), $this->returnTimestamp + get_option( 'gmt_offset' ) * 3600);
		return date_i18n(get_option('time_format'), $this->returnTimestamp + get_option( 'gmt_offset' ) * 3600, TRUE);
	}

    /**
     * Can be used for edit, or as a raw data.
     * @return string
     */
	public function getShortPickupDate()
	{
		// WordPress workaround
		return date_i18n($this->shortDateFormat, $this->pickupTimestamp + get_option( 'gmt_offset' ) * 3600, TRUE);
	}

    /**
     * Can be used for edit, or as a raw data.
     * @return string
     */
	public function getISOPickupTime()
	{
		// WordPress workaround
		return date_i18n("H:i:s", $this->pickupTimestamp + get_option( 'gmt_offset' ) * 3600, TRUE);
	}

    /**
     * Can be used for edit, or as a raw data.
     * @return string
     */
	public function getShortReturnDate()
	{
		// WordPress workaround
		return date_i18n($this->shortDateFormat, $this->returnTimestamp + get_option( 'gmt_offset' ) * 3600, TRUE);
	}

    /**
     * Can be used for edit, or as a raw data.
     * @return string
     */
	public function getISOReturnTime()
	{
		// WordPress workaround
		return date_i18n("H:i:s", $this->returnTimestamp + get_option( 'gmt_offset' ) * 3600, TRUE);
	}

	public function getPrintBookingDuration()
	{
		return $this->lang->getPrintCeilDurationByPeriod($this->priceCalculationType, StaticValidator::getPeriod($this->pickupTimestamp, $this->returnTimestamp, FALSE));
	}

	/**************************************************************/
	/************** Just an methods abbreviation ******************/
	/**************************************************************/
	public function getLocalPickupDate()
	{
		return StaticValidator::getLocalDateByTimestamp($this->pickupTimestamp, "Y-m-d");
	}

	public function getLocalPickupTime()
	{
		return StaticValidator::getLocalDateByTimestamp($this->pickupTimestamp, "H:i:s");
	}

	public function getLocalReturnDate()
	{
		return StaticValidator::getLocalDateByTimestamp($this->returnTimestamp, "Y-m-d");
	}

	public function getLocalReturnTime()
	{
		return StaticValidator::getLocalDateByTimestamp($this->returnTimestamp, "H:i:s");
	}

	public function getLocalPickupDayOfWeek()
	{
		return StaticValidator::getLocalDateByTimestamp($this->pickupTimestamp, "D");
	}

	public function getLocalReturnDayOfWeek()
	{
		return StaticValidator::getLocalDateByTimestamp($this->returnTimestamp, "D");
	}

	public function getBookingPeriod()
	{
		return StaticValidator::getPeriod($this->pickupTimestamp, $this->returnTimestamp, FALSE);
	}

	/**********************************************************************************************/
	/************************************* ACTUAL ELEMENTS ****************************************/
	/**********************************************************************************************/

	public function getPickupTimestamp()
	{
		return $this->pickupTimestamp;
	}

	public function getReturnTimestamp()
	{
		return $this->returnTimestamp;
	}

	/**************************************************************/
	/********* Methods to retrieve booking location, item, ********/
	/********* customer and booking details ***********************/
	/**************************************************************/

	public function isNewBooking()
	{
		return $this->bookingCode == "" ? TRUE : FALSE;
	}

	public function getBookingCode()
	{
		return $this->bookingCode;
	}

    public function getPrintBookingCode()
    {
        return esc_html($this->bookingCode);
    }

    public function getEditBookingCode()
    {
        return esc_attr($this->bookingCode);
    }

    public function getCouponCode()
    {
        return $this->couponCode;
    }

    public function getPrintCouponCode()
    {
        return esc_html($this->couponCode);
    }

    public function getEditCouponCode()
    {
        return esc_attr($this->couponCode);
    }

	public function getPickupLocationId()
	{
		return $this->pickupLocationId;
	}

    public function getReturnLocationId()
	{
		return $this->returnLocationId;
	}

    public function getPartnerId()
    {
        return $this->partnerId;
    }

    public function getManufacturerId()
    {
        return $this->manufacturerId;
    }
    
	public function getBodyTypeId()
	{
		return $this->bodyTypeId;
	}

	public function getTransmissionTypeId()
	{
		return $this->transmissionTypeId;
	}

	public function getFuelTypeId()
	{
		return $this->fuelTypeId;
	}

    public function getSearchInputDataArray()
    {
        $searchInput = array(
            "coupon_code" => $this->couponCode,
            "pickup_timestamp" => $this->pickupTimestamp,
            "return_timestamp" => $this->returnTimestamp,
            "pickup_location_id" => $this->pickupLocationId,
            "return_location_id" => $this->returnLocationId,
            "partner_id" => $this->partnerId,
            "manufacturer_id" => $this->manufacturerId,
            "body_type_id" => $this->bodyTypeId,
            "transmission_type_id" => $this->transmissionTypeId,
            "fuel_type_id" => $this->fuelTypeId,
        );

        return $searchInput;
    }

    public function getItemIds()
    {
        return $this->itemIds;
    }

    public function getItemUnits()
    {
        return $this->itemUnits;
    }

    public function getItemOptions()
    {
        return $this->itemOptions;
    }

    public function getExtraIds()
    {
        return $this->extraIds;
    }

    public function getExtraUnits()
    {
        return $this->extraUnits;
    }

    public function getExtraOptions()
    {
        return $this->extraOptions;
    }

    public function getItemQuantity($paramItemId)
    {
        return isset($this->itemUnits[$paramItemId]) ? $this->itemUnits[$paramItemId] : 0;
    }

    public function getItemOption($paramItemId)
    {
        return isset($this->itemOptions[$paramItemId]) ? $this->itemOptions[$paramItemId] : 0;
    }

    public function getExtraQuantity($paramExtraId)
    {
        return isset($this->extraUnits[$paramExtraId]) ? $this->extraUnits[$paramExtraId] : 0;
    }

    public function getExtraOption($paramExtraId)
    {
        return isset($this->extraOptions[$paramExtraId]) ? $this->extraOptions[$paramExtraId] : 0;
    }

	/**************************************************************/
	/******************** Advanced methods 1 **********************/
	/**************************************************************/

    public function getItemsTotalSelectedQuantity()
    {
        $totalUnitsSelected = 0;
        foreach($this->itemIds AS $itemId)
        {
            $itemUnitsSelected = isset($this->itemUnits[$itemId]) ? $this->itemUnits[$itemId] : 0;
            // Add current units amount
            $totalUnitsSelected += $itemUnitsSelected;
        }

        return $totalUnitsSelected;
    }

    public function getExtrasTotalQuantity()
    {
        $totalUnitsSelected = 0;
        foreach($this->extraIds AS $extraId)
        {
            $extraUnitsSelected = isset($this->extraUnits[$extraId]) ? $this->extraUnits[$extraId] : 0;
            // Add current units amount
            $totalUnitsSelected += $extraUnitsSelected;
        }

        return $totalUnitsSelected;
    }

    /**
     * @param $paramArray
     * @param $paramFormatType
     * @param bool $paramQuoteOnly
     * @return array
     */
	protected function getFormattedPriceArray($paramArray, $paramFormatType, $paramQuoteOnly = FALSE)
	{
        $retArray = array();
        foreach($paramArray AS $key => $price)
        {
            $showLongText = in_array($paramFormatType, array('long', 'long_without_fraction')) ? TRUE : FALSE;
            if($key == "fixed_deposit_amount" && $price == 0.00)
            {
                $formattedPrice = $this->lang->getText($showLongText ? 'NRS_NOT_REQUIRED_TEXT' : 'NRS_NOT_REQ_TEXT');
            } else if(in_array($key, array("grand_total", "total_pay_later")) && $paramQuoteOnly)
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

	/**
	 * @param $pickupTimestamp
	 * @param $returnTimestamp
	 * @return bool
	 */
    public function validateTimeInput($pickupTimestamp, $returnTimestamp)
	{
        // We use isValidSearch check here to avoid too many errors printed out for customers
        if($this->isValidSearch())
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
            if($this->pickupTimestamp < time())
            {
                $currentDateTime = date_i18n(get_option('date_format').' '.get_option('time_format'));
                $errorMessage = $this->lang->getText('NRS_ERROR_PICKUP_IS_NOT_POSSIBLE_ON_TEXT').' ';
                $errorMessage .= $this->getPrintPickupDate().' '.$this->getPrintPickupTime().'. ';
                $errorMessage .= $this->lang->getText('NRS_ERROR_PLEASE_MODIFY_YOUR_PICKUP_TIME_BY_WEBSITE_TIME_TEXT').' <br />';
                $errorMessage .= $this->lang->getText('NRS_ERROR_CURRENT_DATE_TIME_TEXT') .' '.$currentDateTime;

                $this->errorMessages[] = $errorMessage;
            } else if($this->pickupTimestamp < (time() + $this->minPeriodUntilPickup))
            {
                $earliestPossiblePickupLocalTime = time() + $this->minPeriodUntilPickup + get_option('gmt_offset') * 3600;
                $earliestPossiblePickupDateTime = date_i18n(get_option('date_format').' '.get_option('time_format'), $earliestPossiblePickupLocalTime, TRUE);
                $errorMessage = $this->lang->getText('NRS_ERROR_PICKUP_IS_NOT_POSSIBLE_ON_TEXT').' ';
                $errorMessage .= $this->getPrintPickupDate().' '.$this->getPrintPickupTime().'. <br />';
                $errorMessage .= $this->lang->getText('NRS_ERROR_EARLIEST_POSSIBLE_PICKUP_DATE_TIME_TEXT') .' '.$earliestPossiblePickupDateTime.' ';
                $errorMessage .= $this->lang->getText('NRS_ERROR_OR_NEXT_BUSINESS_HOURS_OF_PICKUP_LOCATION_TEXT').'.';
                if($this->debugMode)
                {
                    $errorMessage .= ' ('.$earliestPossiblePickupLocalTime.')';
                }

                $this->errorMessages[] = $errorMessage;
            }
        }

		return $this->isValidSearch();
	}

    public function validatePickupInput($paramLocationId, $paramTimestamp)
	{
		return $this->validateLocationInput("PICKUP", $paramLocationId, $paramTimestamp);
	}

	public function validateReturnInput($paramLocationId, $paramTimestamp)
	{
		return $this->validateLocationInput("RETURN", $paramLocationId, $paramTimestamp);
	}

	/**
	 * Checks the location
	 * @param string $paramType - "PICKUP" or "RETURN"
	 * @param $paramLocationId
	 * @param $paramTimestamp
	 * @return bool
	 */
	private function validateLocationInput($paramType = "PICKUP", $paramLocationId, $paramTimestamp)
	{
		$validType = $paramType == "RETURN" ? "RETURN" : "PICKUP";
		$validTimestamp = StaticValidator::getValidPositiveInteger($paramTimestamp);
        $date = StaticValidator::getLocalDateByTimestamp($validTimestamp, "Y-m-d");
        $time = StaticValidator::getLocalDateByTimestamp($validTimestamp, "H:i:s");
        $dayOfWeek = StaticValidator::getLocalDateByTimestamp($validTimestamp, "D");
		$lowercaseType = strtolower($validType);

        // WordPress bug WorkAround
        $printDate = date_i18n(get_option('date_format'), $validTimestamp + get_option( 'gmt_offset' ) * 3600, TRUE);
        $printTime = date_i18n(get_option('time_format'), $validTimestamp + get_option( 'gmt_offset' ) * 3600, TRUE);

		/********************************************************************/
        $field = $validType == "RETURN" ? 'conf_search_return_location_required' : 'conf_search_pickup_location_required';
        $locationRequired = FALSE;
        if(isset($this->settings[$field]))
        {
            $locationRequired = $this->settings[$field] == 1 ? TRUE : FALSE;
        }
		/********************************************************************/

		// Location validation
		$objLocation = new Location($this->conf, $this->lang, $this->settings, $paramLocationId);
        $validationProcessed = FALSE;

		// We use isValidSearch check here to avoid too many errors printed out for customers
		if($this->isValidSearch() && ($objLocation->getId() > 0 || $locationRequired))
        {
            $validationProcessed = TRUE;
            // Get location details
            $locationDetails = $objLocation->getWeekdayDetails($dayOfWeek);
            if(is_null($locationDetails))
            {
                // Selected location do not exists

                // As because this is probably a hack, we don't give any error notice of 'location does not exists'
                $this->errorMessages[] = $this->lang->getText($paramType == 'RETURN' ? 'NRS_RETURN_LOCATION_ALERT_TEXT' : 'NRS_PICKUP_LOCATION_ALERT_TEXT');
            } else if($objLocation->isOpenAtDate($date) === FALSE)
            {
                // Location is closed at selected date, because this date is marked as holidays 'Holidays day'

                // Add new error message to error messages stack
                $this->errorMessages[]  = sprintf(
                    $this->lang->getText('NRS_ERROR_'.$validType.'_LOCATION_IS_CLOSED_AT_THIS_DATE_TEXT'),
                    $locationDetails['print_translated_location_name'], $locationDetails['print_full_address'], $printDate
                );



            } else if($objLocation->isOpenAtTime($time, $dayOfWeek) === FALSE)
            {
                if($locationDetails['afterhours_'.$lowercaseType.'_allowed'] == 0)
                {
                    // Afterhours pickup/return is not allowed, and current time is in afterhours
                    $printBusinessHours = $objLocation->getPrintBusinessHoursWithDayName();
                    $printLunchHours = $objLocation->getPrintLunchHours();

                    $errorMessage = "";
                    $errorMessage .= sprintf(
                        $this->lang->getText('NRS_ERROR_'.$validType.'_LOCATION_IS_CLOSED_AT_THIS_TIME_TEXT'),
                        $locationDetails['print_translated_location_name'], $locationDetails['print_full_address'], $printTime
                    );
                    $errorMessage .= "<br />".$this->lang->getText('NRS_ERROR_AFTERHOURS_'.$validType.'_IS_NOT_ALLOWED_AT_LOCATION_TEXT');
                    $errorMessage .= "<br />".sprintf(
                        $this->lang->getText('NRS_ERROR_LOCATION_OPEN_HOURS_ARE_TEXT'),
                        $dayOfWeek, $printDate, $locationDetails['print_open_hours']
                    );
                    $errorMessage .= "<br />".$this->lang->getText('NRS_ERROR_LOCATION_WEEKLY_OPEN_HOURS_ARE_TEXT')."<br />";
                    $errorMessage .= $printBusinessHours;
                    $errorMessage .= $printLunchHours != '' ? '</br />-----------------------------<br />'.$printLunchHours : '';

                    // Add new error message to error messages stack
                    $this->errorMessages[] = $errorMessage;
                } else if($locationDetails['afterhours_'.$lowercaseType.'_allowed'] == 1 && $locationDetails['afterhours_'.$lowercaseType.'_location_id'] == 0)
                {
                    // All ok - open 24/7 in same location in afterhours time
                    // No error
                } else if($locationDetails['afterhours_'.$lowercaseType.'_allowed'] == 1 && $locationDetails['afterhours_'.$lowercaseType.'_location_id'] > 0)
                {
                    // Afterhours pickup/return is allowed, and current time is in afterhours
                    $objAfterHoursLocation = new Location($this->conf, $this->lang, $this->settings, $paramLocationId);

                    if($objAfterHoursLocation->isOpenAtTime($time, $dayOfWeek) === FALSE)
                    {
                        // This is ok here to use Weekday details for after-hours knowing given purpose
                        $afterHoursLocationDetails = $objAfterHoursLocation->getWeekdayDetails(StaticValidator::getLocalDateByTimestamp($validTimestamp, "D"));

                        if(!is_null($afterHoursLocationDetails) && $locationDetails['afterhours_'.$lowercaseType.'_allowed'] == 1 && $afterHoursLocationDetails['afterhours_'.$lowercaseType.'_location_id'] == 0)
                        {
                            // All ok - afterhours location works 24/7
                            // No error
                        } else
                        {
                            // Afterhours location not exist or is not working 24/7 in that place and is closed during search hours
                            $printBusinessHours = $objLocation->getPrintBusinessHoursWithDayName();
                            $printLunchHours = $objLocation->getPrintLunchHours();

                            $errorMessage = "";
                            $errorMessage .= sprintf(
                                $this->lang->getText('NRS_ERROR_'.$validType.'_LOCATION_IS_CLOSED_AT_THIS_TIME_TEXT'),
                                $locationDetails['print_translated_location_name'], $locationDetails['print_full_address'], $printTime
                            );
                            if(is_null($afterHoursLocationDetails))
                            {
                                $errorMessage .= "<br />".$this->lang->getText('NRS_ERROR_AFTERHOURS_'.$validType.'_IS_NOT_ALLOWED_AT_LOCATION_TEXT');
                            }
                            $errorMessage .= "<br />".sprintf(
                                    $this->lang->getText('NRS_ERROR_LOCATION_OPEN_HOURS_ARE_TEXT'),
                                    $dayOfWeek, $printDate, $locationDetails['print_open_hours']
                                );
                            $errorMessage .= "<br />".$this->lang->getText('NRS_ERROR_LOCATION_WEEKLY_OPEN_HOURS_ARE_TEXT')."<br />";
                            $errorMessage .= $printBusinessHours;
                            $errorMessage .= $printLunchHours != '' ? '</br />-----------------------------<br />'.$printLunchHours : '';
                            if(!is_null($afterHoursLocationDetails))
                            {
                                $errorMessage .= "<br />".sprintf(
                                    $this->lang->getText('NRS_ERROR_AFTERHOURS_'.$validType.'_LOCATION_IS_CLOSED_AT_THIS_TIME_TEXT'),
                                    $afterHoursLocationDetails['print_translated_location_name'],
                                    $afterHoursLocationDetails['print_full_address']
                                );
                                $errorMessage .= "<br />".sprintf(
                                    $this->lang->getText('NRS_ERROR_AFTERHOURS_'.$validType.'_LOCATION_OPEN_HOURS_ARE_TEXT'),
                                    $afterHoursLocationDetails['print_open_hours']
                                );
                            }

                            // Add new error message to error messages stack
                            $this->errorMessages[] = $errorMessage;
                        }
                    }
                }
            }
        }

		if($this->debugMode)
		{
            echo "<br /><strong>[VALIDATE]</strong> Location: ".intval($paramLocationId)." [{$validType}], Processed: ".var_export($validationProcessed, TRUE).", ";
			echo "Date: ".StaticValidator::getLocalDateByTimestamp($validTimestamp, "Y-m-d").", ";
			echo "Time: ".StaticValidator::getLocalDateByTimestamp($validTimestamp, "H:i:s").", ";
			echo "Day of Week: ".StaticValidator::getLocalDateByTimestamp($validTimestamp, "D").", ";
            echo "Unix Timestamp: ".$validTimestamp."";
			//echo nl2br(print_r($locationData, TRUE));
		}

		return $this->isValidSearch();
	}
}