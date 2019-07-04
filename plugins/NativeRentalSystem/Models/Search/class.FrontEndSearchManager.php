<?php
/**

 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Search;
use NativeRentalSystem\Models\Booking\BookingsObserver;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\iObserver;
use NativeRentalSystem\Models\Location\Location;
use NativeRentalSystem\Models\Location\LocationsObserver;
use NativeRentalSystem\Models\Pricing\DistanceFeeManager;
use NativeRentalSystem\Models\Tax\TaxesObserver;
use NativeRentalSystem\Models\Tax\TaxManager;
use NativeRentalSystem\Models\Pricing\LocationFeeManager;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Formatting\StaticFormatter;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Booking\Booking;
use NativeRentalSystem\Models\Prepayment\PrepaymentManager;

class FrontEndSearchManager extends AbstractSearchManager implements iObserver, iSearchManager
{
	public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings)
	{
		parent::__construct($paramConf, $paramLang, $paramSettings);
	}

    /**
     * SET BOOKING CODE IF PASSED
     * @param $paramRequired
     */
	public function setBookingCode($paramRequired)
    {
        // Process booking ID (figure out if it's new reservation or existing reservation
        if(isset($_REQUEST[$this->conf->getExtensionPrefix().'do_search4']) || isset($_REQUEST['booking_code']) || isset($_SESSION['booking_code']))
        {
            if(
                (isset($_REQUEST['booking_code']) && $_REQUEST['booking_code'] == $this->lang->getText('NRS_I_HAVE_BOOKING_CODE_TEXT')) ||
                (isset($_SESSION['booking_code']) && $_SESSION['booking_code'] == $this->lang->getText('NRS_I_HAVE_BOOKING_CODE_TEXT'))
            ) {
                // Flush booking code
                $this->bookingCode = "";
            } else
            {
                $this->bookingCode = $this->getValidValueInput(array('POST', 'GET', 'SESSION'), 'booking_code', '', $paramRequired, 'guest_text_validation');
            }
        }

        // If we provided a booking code from _POST or _GET
        if($this->bookingCode != '')
        {
            // Then we need to flush all session data, to not allow to overwrite it
            $this->removeSessionVariables();
        }
    }

    public function setVariablesByBookingId($paramBookingId)
    {
        $objBooking = new Booking($this->conf, $this->lang, $this->settings, $paramBookingId);
        $bookingDetails = $objBooking->getDetails();
        if(!is_null($bookingDetails))
        {
            // These variables are the regular params
            $this->couponCode = $bookingDetails['coupon_code']; // used in all steps
            $objLocationsObserver = new LocationsObserver($this->conf, $this->lang, $this->settings);
            $this->pickupLocationId = $objLocationsObserver->getIdByCode($bookingDetails['pickup_location_code']); // used in all steps
            $this->returnLocationId = $objLocationsObserver->getIdByCode($bookingDetails['return_location_code']); // used in all steps
            $this->pickupTimestamp = $bookingDetails['pickup_timestamp'];
            $this->returnTimestamp = $bookingDetails['return_timestamp'];
            $this->partnerId = $bookingDetails['partner_id']; // used in step 2 (3)
            $this->manufacturerId = $bookingDetails['manufacturer_id']; // used in step 2 (3)
            $this->bodyTypeId = $bookingDetails['body_type_id']; // used in step 2 (3)
            $this->transmissionTypeId = $bookingDetails['transmission_type_id']; // used in step 2 (3)
            $this->fuelTypeId = $bookingDetails['fuel_type_id']; // used in step 2 (3)
            // These arrays are the regular params
            $this->itemIds = $bookingDetails['item_ids'];
            $this->itemUnits = $bookingDetails['item_units'];
            $this->itemOptions = $bookingDetails['item_options'];
            $this->extraIds = $bookingDetails['extra_ids'];
            $this->extraUnits = $bookingDetails['extra_units'];
            $this->extraOptions = $bookingDetails['extra_options'];

            if($this->debugMode)
            {
                echo "<br />Class variables set from booking code. ";
                //echo "<br />Class variables set from booking code. Class variables: ";
                //echo nl2br(print_r($this->getSearchInputDataArray(), TRUE));
            }
        } else
        {
            // This booking has errors - find them
            if($objBooking->getId() == 0)
            {
                // Booking code passed, but not exist in database
                $this->errorMessages[] = $this->lang->getText('NRS_ERROR_INVALID_BOOKING_CODE_TEXT');
            } else if($objBooking->isDeparted())
            {
                $this->errorMessages[] = sprintf($this->lang->getText('NRS_ERROR_DEPARTED_TEXT'), $this->bookingCode);
            } else if($objBooking->isCancelled())
            {
                $this->errorMessages[] = sprintf($this->lang->getText('NRS_ERROR_CANCELLED_TEXT'), $this->bookingCode);
            } else if($objBooking->isRefunded())
            {
                $this->errorMessages[] = sprintf($this->lang->getText('NRS_ERROR_REFUNDED_TEXT'), $this->bookingCode);
            } else if($objBooking->isValid() === FALSE)
            {
                $this->errorMessages[] = $this->lang->getText('NRS_ERROR_OTHER_BOOKING_ERROR_TEXT');
            }
        }
    }

    /**
     * Step no. 1 - Show reservation box. (optional) + show car
     * Step no. 2 - (optional) Select car, if no car provided
     * Step no. 3 - Select car extras
     * Step no. 4 - Show booking details
     * Step no. 5 - Process booking
     * Step no. 6 - PayPal payment
     */
    public function setVariablesByRequestOrSessionParams()
    {
        /*******************************************************************/
        /************* Get visible & required search fields list ***********/
        /*******************************************************************/
        if(isset($this->settings['conf_search_pickup_date_visible'], $this->settings['conf_search_return_date_visible']))
        {
            // Search fields visibility settings
            $pickupDateVisible = $this->settings['conf_search_pickup_date_visible'] == 1 ? TRUE : FALSE;
            $returnDateVisible = $this->settings['conf_search_return_date_visible'] == 1 ? TRUE : FALSE;
        } else
        {
            // Search fields visibility settings
            $pickupDateVisible = FALSE;
            $returnDateVisible = FALSE;
        }

        if(isset(
            $this->settings['conf_search_coupon_code_required'],
            $this->settings['conf_search_pickup_location_required'],
            $this->settings['conf_search_pickup_date_required'],
            $this->settings['conf_search_return_location_required'],
            $this->settings['conf_search_return_date_required'],
            $this->settings['conf_search_partner_required'],
            $this->settings['conf_search_manufacturer_required'],
            $this->settings['conf_search_body_type_required'],
            $this->settings['conf_search_transmission_type_required'],
            $this->settings['conf_search_fuel_type_required']
        )) {
            // Search fields requirement settings
            $couponCodeRequired = $this->settings['conf_search_coupon_code_required'] == 1 ? TRUE : FALSE;
            $pickupLocationRequired = $this->settings['conf_search_pickup_location_required'] == 1 ? TRUE : FALSE;
            $pickupDateRequired = $this->settings['conf_search_pickup_date_required'] == 1 ? TRUE : FALSE;
            $returnLocationRequired = $this->settings['conf_search_return_location_required'] == 1 ? TRUE : FALSE;
            $returnDateRequired = $this->settings['conf_search_return_date_required'] == 1 ? TRUE : FALSE;
            $partnerRequired = $this->settings['conf_search_partner_required'] == 1 ? TRUE : FALSE;
            $manufacturerRequired = $this->settings['conf_search_manufacturer_required'] == 1 ? TRUE : FALSE;
            $bodyTypeRequired = $this->settings['conf_search_body_type_required'] == 1 ? TRUE : FALSE;
            $transmissionTypeRequired = $this->settings['conf_search_transmission_type_required'] == 1 ? TRUE : FALSE;
            $fuelTypeRequired = $this->settings['conf_search_fuel_type_required'] == 1 ? TRUE : FALSE;
        } else
        {
            // Search fields requirement settings
            $couponCodeRequired = FALSE;
            $pickupLocationRequired = FALSE;
            $pickupDateRequired = FALSE;
            $returnLocationRequired = FALSE;
            $returnDateRequired = FALSE;
            $partnerRequired = FALSE;
            $manufacturerRequired = FALSE;
            $bodyTypeRequired = FALSE;
            $transmissionTypeRequired = FALSE;
            $fuelTypeRequired = FALSE;
        }

        // Important: Process only if this is NOT _POST or _GET.
        /*******************************************************************/
        /********************** PARAMETERS FROM STEP1 **********************/
        /*******************************************************************/

        // 0 - Coupon Code
        if(isset($_REQUEST[$this->conf->getExtensionPrefix().'do_search4']) || isset($_REQUEST['coupon_code']) || isset($_SESSION['coupon_code']))
        {
            if(
                (isset($_REQUEST['coupon_code']) && $_REQUEST['coupon_code'] == $this->lang->getText('NRS_I_HAVE_COUPON_CODE_TEXT')) ||
                (isset($_SESSION['coupon_code']) && $_SESSION['coupon_code'] == $this->lang->getText('NRS_I_HAVE_COUPON_CODE_TEXT'))
            ) {
                // Flush coupon code
                $this->couponCode = "";
            } else
            {
                $this->couponCode = $this->getValidValueInput(array('POST', 'GET', 'SESSION'), 'coupon_code', '', $couponCodeRequired, 'guest_text_validation');
            }
        }

        // 1 - Pickup Location Id
        if(isset($_REQUEST[$this->conf->getExtensionPrefix().'do_search4']) || isset($_REQUEST['pickup_location_id']) || isset($_SESSION['pickup_location_id']))
        {
            $this->pickupLocationId = $this->getValidValueInput(array('POST', 'GET', 'SESSION'), 'pickup_location_id', 0, $pickupLocationRequired, 'positive_integer');
        }

        // 2/3 - Pickup Date & Time
        if($pickupDateVisible && (
                isset($_REQUEST[$this->conf->getExtensionPrefix().'do_search4']) ||
                isset($_REQUEST['pickup_date'], $_REQUEST['pickup_time']) ||
                isset($_SESSION['pickup_date'], $_SESSION['pickup_time']))
        ) {
            $defaultPickupTimestamp = time() + $this->minPeriodUntilPickup + get_option( 'gmt_offset' ) * 3600;
            $customerInputPickupDate = $this->getValidValueInput(array('POST', 'GET', 'SESSION'), 'pickup_date', date_i18n($this->shortDateFormat, $defaultPickupTimestamp, TRUE), $pickupDateRequired, $this->shortDateFormat);
            $customerInputPickupTime = $this->getValidValueInput(array('POST', 'GET', 'SESSION'), 'pickup_time', date_i18n("H:i:s", $defaultPickupTimestamp, TRUE), $pickupDateRequired, 'time_validation');
            $this->pickupTimestamp = StaticValidator::getUTCTimestampFromLocalISODateTime($customerInputPickupDate, $customerInputPickupTime);
        } else if($pickupDateVisible == FALSE)
        {
            // Pickup date & time is not visible, so we set default value, which is current time plus minimum period until pickup
            $this->pickupTimestamp = time() + $this->minPeriodUntilPickup;
        }

        // 4 - Return Location Id
        if(isset($_REQUEST[$this->conf->getExtensionPrefix().'do_search4']) || isset($_REQUEST['return_location_id']) || isset($_SESSION['return_location_id']))
        {
            $this->returnLocationId = $this->getValidValueInput(array('POST', 'GET', 'SESSION'), 'return_location_id', 0, $returnLocationRequired, 'positive_integer');
        }

        // 5 - If booking duration is passed, it will override return date and time
        if($returnDateVisible && (
                isset($_REQUEST[$this->conf->getExtensionPrefix().'do_search4']) ||
                isset($_REQUEST['booking_period']) || isset($_SESSION['booking_period']) ||
                isset($_REQUEST['return_date'], $_REQUEST['return_time']) ||
                isset($_SESSION['return_date'], $_SESSION['return_time'])
            )) {
            if(isset($_REQUEST['booking_period']) || isset($_SESSION['booking_period']))
            {
                // Return date is a integer duration in seconds, and has to be added to pickup date
                $validBookingPeriod = $this->getValidValueInput(array('POST', 'GET', 'SESSION'), 'booking_period', 0, $returnDateRequired, 'positive_integer');
                $this->returnTimestamp = $this->pickupTimestamp + $validBookingPeriod;
            } else
            {
                // 6/7 - Return Date & Time
                $defaultReturnTimestamp = $this->pickupTimestamp + $this->minBookingPeriod;
                $customerInputReturnDate = $this->getValidValueInput(array('POST', 'GET', 'SESSION'), 'return_date', date_i18n($this->shortDateFormat, $defaultReturnTimestamp, TRUE), $returnDateRequired, $this->shortDateFormat);
                $customerInputReturnTime = $this->getValidValueInput(array('POST', 'GET', 'SESSION'), 'return_time', date("H:i:s"), $returnDateRequired, 'time_validation');
                $this->returnTimestamp = StaticValidator::getUTCTimestampFromLocalISODateTime($customerInputReturnDate, $customerInputReturnTime);
            }
        } else if($returnDateVisible == FALSE)
        {
            // Return date & time is not visible, so we set default value, which is a pickup timestamp plus minimum booking period
            $this->returnTimestamp = $this->pickupTimestamp + $this->minBookingPeriod;
        }

        // 8 - Partner Id
        if(isset($_REQUEST[$this->conf->getExtensionPrefix().'do_search4']) || isset($_REQUEST['partner_id']) || isset($_SESSION['partner_id']))
        {
            $this->partnerId = $this->getValidValueInput(array('POST', 'GET', 'SESSION'), 'partner_id', -1, $partnerRequired, 'intval');
        }

        // 9 - Manufacturer Id
        if(isset($_REQUEST[$this->conf->getExtensionPrefix().'do_search4']) || isset($_REQUEST['manufacturer_id']) || isset($_SESSION['manufacturer_id']))
        {
            $this->manufacturerId = $this->getValidValueInput(array('POST', 'GET', 'SESSION'), 'manufacturer_id', -1, $manufacturerRequired, 'intval');
        }

        // 10 - Body Type Id
        if(isset($_REQUEST[$this->conf->getExtensionPrefix().'do_search4']) || isset($_REQUEST['body_type_id']) || isset($_SESSION['body_type_id']))
        {
            $this->bodyTypeId = $this->getValidValueInput(array('POST', 'GET', 'SESSION'), 'body_type_id', -1, $bodyTypeRequired, 'intval');
        }

        // 11 - Transmission Type Id
        if(isset($_REQUEST[$this->conf->getExtensionPrefix().'do_search4']) || isset($_REQUEST['transmission_type_id']) || isset($_SESSION['transmission_type_id']))
        {
            $this->transmissionTypeId = $this->getValidValueInput(array('POST', 'GET', 'SESSION'), 'transmission_type_id', -1, $transmissionTypeRequired, 'intval');
        }

        // 12 - Fuel Type Id
        if(isset($_REQUEST[$this->conf->getExtensionPrefix().'do_search4']) || isset($_REQUEST['fuel_type_id']) || isset($_SESSION['fuel_type_id']))
        {
            $this->fuelTypeId = $this->getValidValueInput(array('POST', 'GET', 'SESSION'), 'fuel_type_id', -1, $fuelTypeRequired, 'intval');
        }

        //////////////////////////////////////////////////////////////////
        // FOR STEP3
        /******************* ITEM - IDs, UNITS, OPTIONS *****************/

        // 13 - Items Ids
        if(isset($_REQUEST[$this->conf->getExtensionPrefix().'do_search4']) || isset($_REQUEST['item_ids']) || isset($_SESSION['item_ids']))
        {
            // came back to step3 from step3->step2->step edit mode
            $this->itemIds = $this->getValidArrayInput(array('POST', 'GET', 'SESSION'), 'item_ids', array(), FALSE, 'positive_integer');
        }

        // 14 - Item Units
        if(isset($_REQUEST[$this->conf->getExtensionPrefix().'do_search4']) || isset($_REQUEST['item_units']) || isset($_SESSION['item_units']))
        {
            // came back to step2 from step3
            // positive_integer validation here protects us from allowing to block all (-1) item units from front-end
            $this->itemUnits = $this->getValidArrayInput(array('POST', 'GET', 'SESSION'), 'item_units', array(), FALSE, 'positive_integer');
        }

        // 15 - Item Options
        if(isset($_REQUEST[$this->conf->getExtensionPrefix().'do_search4']) || isset($_REQUEST['item_options']) || isset($_SESSION['item_options']))
        {
            // came back to step3 from step3->step2->step edit mode
            $this->itemOptions = $this->getValidArrayInput(array('POST', 'GET', 'SESSION'), 'item_options', array(), FALSE, 'positive_integer');
        }

        /******************* EXTRA - IDs, UNITS, OPTIONS *****************/

        // 16 - Extra Ids
        if(isset($_REQUEST[$this->conf->getExtensionPrefix().'do_search4']) || isset($_REQUEST['extra_ids']) || isset($_SESSION['extra_ids']))
        {
            $this->extraIds = $this->getValidArrayInput(array('POST', 'GET', 'SESSION'), 'extra_ids', array(), FALSE, 'positive_integer');
        }

        // 17 - Extra Units
        if(isset($_REQUEST[$this->conf->getExtensionPrefix().'do_search4']) || isset($_REQUEST['extra_units']) || isset($_SESSION['extra_units']))
        {
            // positive_integer validation here protects us from allowing to block all (-1) extra units from front-end
            $this->extraUnits = $this->getValidArrayInput(array('POST', 'GET', 'SESSION'), 'extra_units', array(), FALSE, 'positive_integer');
        }

        // 18 - Extra Options
        if(isset($_REQUEST[$this->conf->getExtensionPrefix().'do_search4']) || isset($_REQUEST['extra_options']) || isset($_SESSION['extra_options']))
        {
            $this->extraOptions = $this->getValidArrayInput(array('POST', 'GET', 'SESSION'), 'extra_options', array(), FALSE, 'positive_integer');
        }

        if($this->debugMode == 2)
        {
            echo "<br />Final stage of class variables after setRequestParams() was called.";
            echo "<br />".nl2br(print_r($this->getSearchInputDataArray(), TRUE));
        }
    }

    /**
     * @return array
     */
    public function getPriceSummary()
    {
        // Set default values, to avoid not existing key errors
        $retPrices 											= array();
        $retPrices['items'] 								= array();
        $retPrices['extras'] 								= array();
        $retPrices['pickup']								= array();
        $retPrices['return']								= array();
        // Counted defaults from price element
        $retPrices['tax_percentage']					    = 0.00;
        $retPrices['item_totals'] 							= array();
        $retPrices['extra_totals'] 							= array();
        $retPrices['overall'] 								= array();
        // Counted default from price element
        $retPrices['overall']['discounted_total'] 			= 0.00;
        $retPrices['overall']['discounted_tax_amount'] 		= 0.00;
        $retPrices['overall']['discounted_total_with_tax'] 	= 0.00;
        $retPrices['overall']['fixed_item_deposit_amount'] 	= 0.00;
        $retPrices['overall']['fixed_extra_deposit_amount']	= 0.00;
        $retPrices['overall']['fixed_deposit_amount'] 		= 0.00;
        // Counted prepayments
        $retPrices['prepayment']['item_pay_now']            = 0.00;
        $retPrices['prepayment']['item_deposit_pay_now']    = 0.00;
        $retPrices['prepayment']['extra_pay_now']           = 0.00;
        $retPrices['prepayment']['extra_deposit_pay_now']   = 0.00;
        $retPrices['prepayment']['pickup_fee_pay_now']      = 0.00;
        $retPrices['prepayment']['distance_fee_pay_now']    = 0.00;
        $retPrices['prepayment']['return_fee_pay_now']      = 0.00;
        // Counted overalls
        $retPrices['overall']['gross_total']     			= 0.00;
        $retPrices['overall']['total_tax']     				= 0.00;
        $retPrices['overall']['grand_total']   				= 0.00;
        $retPrices['overall']['total_pay_now']   			= 0.00;
        $retPrices['overall']['total_pay_later']   			= 0.00;

        // 1 - LOAD THE DATA
        $objPrepaymentManager = new PrepaymentManager($this->conf, $this->lang, $this->settings);
        $prepaymentDetails = $objPrepaymentManager->getPrepaymentDetailsByInterval($this->pickupTimestamp, $this->returnTimestamp);
        $objBookingsObserver = new BookingsObserver($this->conf, $this->lang, $this->settings);
        $objBooking = new Booking($this->conf, $this->lang, $this->settings, $objBookingsObserver->getIdByCode($this->bookingCode));
        $objTaxesObserver = new TaxesObserver($this->conf, $this->lang, $this->settings);
        $objTaxManager = new TaxManager($this->conf, $this->lang, $this->settings);
        $taxPercentage = $objTaxManager->getTaxPercentage($this->pickupLocationId, $this->returnLocationId);
        $objPickupLocation = new Location($this->conf, $this->lang, $this->settings, $this->pickupLocationId);
        $locationCode = $objPickupLocation->getCode(); // We use pickup location code for availability checks
        $objReturnLocation = new Location($this->conf, $this->lang, $this->settings, $this->returnLocationId);
        $objSearchManagerForItems = new ItemSearchManager(
            $this->conf, $this->lang, $this->settings, $taxPercentage, $locationCode,
            $objBooking->getId(), $this->couponCode, $this->itemIds, $this->itemUnits, $this->itemOptions
        );
        $objSearchManagerForExtras = new ExtraSearchManager(
            $this->conf, $this->lang, $this->settings, $taxPercentage, $locationCode,
            $objBooking->getId(), $this->itemIds, $this->extraIds, $this->extraUnits, $this->extraOptions
        );

        // Get selected ids
        $availableItemIds = $objSearchManagerForItems->getAvailableItemIds(
                $this->pickupLocationId,
                $this->returnLocationId,
                $this->partnerId,
                $this->manufacturerId,
                $this->bodyTypeId,
                $this->transmissionTypeId,
                $this->fuelTypeId
            );
        $availableExtraIds = $objSearchManagerForExtras->getAvailableExtraIds();

        // Get selected ids
        $selectedItemIds = $objSearchManagerForItems->getExistingSelectedItemIds($this->itemIds, $availableItemIds);
        $selectedExtraIds = $objSearchManagerForExtras->getExistingSelectedExtraIds($this->extraIds, $availableExtraIds);

        $objDistanceFeeManager = new DistanceFeeManager($this->conf, $this->lang, $this->settings, $this->pickupLocationId, $this->returnLocationId, $taxPercentage);
        $objPickupFeeManager = new LocationFeeManager($this->conf, $this->lang, $this->settings, $this->pickupLocationId, $taxPercentage);
        $objReturnFeeManager = new LocationFeeManager($this->conf, $this->lang, $this->settings, $this->returnLocationId, $taxPercentage);

        $distanceUnitFee = $objDistanceFeeManager->getUnitFee();
        $distanceUnitFeeWithTax = $distanceUnitFee * ($taxPercentage / 100);

        // Load the main information - tax percentage, items, extras, afterhours
        $retPrices['tax_percentage'] = $taxPercentage;
        $retPrices['items'] = $objSearchManagerForItems->getItemsWithPricesAndOptions(
            $selectedItemIds, $this->itemUnits, $this->itemOptions,
            $this->pickupTimestamp, $this->returnTimestamp, TRUE
        );
        $retPrices['extras'] = $objSearchManagerForExtras->getExtrasWithPricesAndOptions(
            $selectedExtraIds, $this->extraUnits, $this->extraOptions,
            $this->pickupTimestamp, $this->returnTimestamp,TRUE
        );
        $retPrices['pickup_in_afterhours'] = $objPickupLocation->isAfterHoursTime($this->getLocalPickupDayOfWeek(), $this->getLocalPickupTime());
        $retPrices['return_in_afterhours'] = $objReturnLocation->isAfterHoursTime($this->getLocalReturnDayOfWeek(), $this->getLocalReturnTime());

        /************************************************************************************************************/
        // Count total selected units and check and set if we are in call for price mode
        $quoteOnly = FALSE;
        $itemsTotalSelectedUnits = 0;
        foreach($retPrices['items'] AS $item)
        {
            $itemsTotalSelectedUnits += $item['selected_quantity'];
            if($item['price_group_id'] == 0)
            {
                $quoteOnly = TRUE;
            }
        }

        /************************************************************************************************************/
        // Load pickup and return
        $pickupFees = $objPickupFeeManager->getDetails($distanceUnitFee, $itemsTotalSelectedUnits, $retPrices['pickup_in_afterhours']);
        $returnFees = $objReturnFeeManager->getDetails($distanceUnitFee, $itemsTotalSelectedUnits, $retPrices['return_in_afterhours']);
        $retPrices['pickup'] = array_merge($objPickupLocation->getDetails(TRUE), $pickupFees);
        $retPrices['return'] = array_merge($objReturnLocation->getDetails(TRUE), $returnFees);

        /************************************************************************************************************/
        // Update the overall price array by adding current element multiplied prices to overall prices
        foreach($retPrices['items'] AS $item)
        {
            foreach($item['multiplied'] AS $key => $multipliedPrice)
            {
                if(!isset($retPrices['overall'][$key]))
                {
                    // Set first price to that specific key
                    $retPrices['overall'][$key] = $multipliedPrice;
                } else
                {
                    // Add prices one by one to that specific key
                    $retPrices['overall'][$key] += $multipliedPrice;
                }

                // We use item_totals only for hover details text
                if(!isset($retPrices['item_totals'][$key]))
                {
                    $retPrices['item_totals'][$key] = $multipliedPrice;
                } else
                {
                    $retPrices['item_totals'][$key] += $multipliedPrice;
                }
            }
        }

        // Add extras prices
        foreach($retPrices['extras'] AS $extra)
        {
            foreach($extra['multiplied'] AS $key => $multipliedPrice)
            {
                if(!isset($retPrices['overall'][$key]))
                {
                    // Set first price to that specific key
                    $retPrices['overall'][$key] = $multipliedPrice;
                } else
                {
                    // Add prices one by one to that specific key
                    $retPrices['overall'][$key] += $multipliedPrice;
                }

                // We use extra_totals only for hover details text
                if(!isset($retPrices['extra_totals'][$key]))
                {
                    $retPrices['extra_totals'][$key] = $multipliedPrice;
                } else
                {
                    $retPrices['extra_totals'][$key] += $multipliedPrice;
                }
            }
        }

        // Deposits blank items set for both scenarios - default value if no items/extras selected, and number if exist
        if(isset($retPrices['item_totals']['fixed_deposit_amount']))
        {
            $retPrices['overall']['fixed_item_deposit_amount'] 	= $retPrices['item_totals']['fixed_deposit_amount'];
        }
        if(isset($retPrices['extra_totals']['fixed_deposit_amount']))
        {
            $retPrices['overall']['fixed_extra_deposit_amount'] = $retPrices['extra_totals']['fixed_deposit_amount'];
        }

        /* -------------------------------- Calculate totals ------------------------------------ */
        // Add total item+extras discounted total to totals
        $retPrices['overall']['gross_total'] = $retPrices['overall']['discounted_total'];
        $retPrices['overall']['total_tax'] 	 = $retPrices['overall']['discounted_tax_amount'];
        $retPrices['overall']['grand_total'] = $retPrices['overall']['discounted_total_with_tax'];
        // Add total pickup fee to totals
        if(is_array($retPrices['pickup']['multiplied']))
        {
            $retPrices['overall']['gross_total'] += $retPrices['pickup']['multiplied']['pickup_fee'];
            $retPrices['overall']['total_tax'] 	 += $retPrices['pickup']['multiplied']['pickup_tax_amount'];
            $retPrices['overall']['grand_total'] += $retPrices['pickup']['multiplied']['pickup_fee_with_tax'];
        }
        // Add total return fee to totals
        if(is_array($retPrices['return']['multiplied']))
        {
            $retPrices['overall']['gross_total'] += $retPrices['return']['multiplied']['return_with_distance_fee'];
            $retPrices['overall']['total_tax'] 	 += $retPrices['return']['multiplied']['return_with_distance_tax_amount'];
            $retPrices['overall']['grand_total'] += $retPrices['return']['multiplied']['return_with_distance_fee_with_tax'];
        }

        /* -------------------------------- Calculate taxes ------------------------------------ */
        $retPrices['taxes'] = $objTaxesObserver->getTaxesForPrice($this->pickupLocationId, $this->returnLocationId, $retPrices['overall']['gross_total']);

        /* -------------------------------- Calculate prepayment ------------------------------------ */
        if($objPrepaymentManager->isPrepaymentEnabled())
        {
            if(isset($prepaymentDetails['prepayment_percentage']) && $prepaymentDetails['prepayment_percentage'] > 0)
            {
                $prepaymentPercentage = $prepaymentDetails['prepayment_percentage'];
                if($prepaymentDetails['item_prices_included'] == 1 && sizeof($retPrices['item_totals']) > 0)
                {
                    $retPrices['prepayment']['item_pay_now'] = $retPrices['item_totals']['discounted_total_with_tax'] * ($prepaymentPercentage / 100);
                }
                if($prepaymentDetails['item_deposits_included'] == 1 && sizeof($retPrices['item_totals']) > 0)
                {
                    $retPrices['prepayment']['item_deposit_pay_now'] = $retPrices['item_totals']['fixed_deposit_amount'] * ($prepaymentPercentage / 100);
                }
                if($prepaymentDetails['extra_prices_included'] == 1 && sizeof($retPrices['extra_totals']) > 0)
                {
                    $retPrices['prepayment']['extra_pay_now'] = $retPrices['extra_totals']['discounted_total_with_tax'] * ($prepaymentPercentage / 100);
                }
                if($prepaymentDetails['extra_deposits_included'] == 1 && sizeof($retPrices['extra_totals']) > 0)
                {
                    $retPrices['prepayment']['extra_deposit_pay_now'] = $retPrices['extra_totals']['fixed_deposit_amount'] * ($prepaymentPercentage / 100);
                }
                if($prepaymentDetails['pickup_fees_included'] == 1 && is_array($retPrices['pickup']['multiplied']))
                {
                    $retPrices['prepayment']['pickup_fee_pay_now'] = $retPrices['pickup']['multiplied']['pickup_fee_with_tax'] * ($prepaymentPercentage / 100);
                }
                if($prepaymentDetails['distance_fees_included'] == 1 && $distanceUnitFeeWithTax > 0)
                {
                    $retPrices['prepayment']['distance_fee_pay_now'] = $distanceUnitFeeWithTax * ($prepaymentPercentage / 100);
                }
                if($prepaymentDetails['return_fees_included'] == 1 && is_array($retPrices['return']['multiplied']))
                {
                    $retPrices['prepayment']['return_fee_pay_now'] = $retPrices['return']['multiplied']['return_fee_with_tax'] * ($prepaymentPercentage / 100);
                }
            }
            // Add all prepayments to one
            foreach($retPrices['prepayment'] AS $key => $payNow)
            {
                $retPrices['overall']['total_pay_now'] += $payNow;
            }
        }
        $retPrices['overall']['total_pay_later'] = $retPrices['overall']['grand_total'] - $retPrices['overall']['total_pay_now'];

        // Overall price prints
        $retPrices['overall_tiny_print'] = $this->getFormattedPriceArray($retPrices['overall'], "tiny", $quoteOnly);
        $retPrices['overall_tiny_without_fraction_print'] = $this->getFormattedPriceArray($retPrices['overall'], "tiny_without_fraction", $quoteOnly);
        $retPrices['overall_print'] = $this->getFormattedPriceArray($retPrices['overall'], "regular", $quoteOnly);
        $retPrices['overall_without_fraction_print'] = $this->getFormattedPriceArray($retPrices['overall'], "regular_without_fraction", $quoteOnly);
        $retPrices['overall_long_print'] = $this->getFormattedPriceArray($retPrices['overall'], "long", $quoteOnly);
        $retPrices['overall_long_without_fraction_print'] = $this->getFormattedPriceArray($retPrices['overall'], "long_without_fraction", $quoteOnly);

        // Print percentages
        $retPrices['print_tax_percentage'] = StaticFormatter::getFormattedPercentage($retPrices['tax_percentage'], "regular");

        // Print totals (for hover text)
        $retPrices['item_totals_print'] = $this->getFormattedPriceArray($retPrices['item_totals'], "regular", FALSE);
        $retPrices['extra_totals_print'] = $this->getFormattedPriceArray($retPrices['extra_totals'], "regular", FALSE);

        // DEBUG
        if($this->debugMode)
        {
            echo '<br /><span style="font-weight: bold; color: black; font-size: 18px;">Item totals:</span>';
            echo '<br />'.nl2br(esc_html(print_r($retPrices['item_totals'], TRUE)));

            echo '<br /><span style="font-weight: bold; color: black; font-size: 18px;">Pick-up totals</span>';
            echo '<br />'.nl2br(esc_html(print_r($retPrices['pickup']['multiplied'], TRUE)));

            echo '<br /><span style="font-weight: bold; color: black; font-size: 18px;">Return totals:</span>';
            echo '<br />'.nl2br(esc_html(print_r($retPrices['return']['multiplied'], TRUE)));

            echo '<br /><span style="font-weight: bold; color: black; font-size: 18px;">Extra totals:</span>';
            echo '<br />'.nl2br(esc_html(print_r($retPrices['extra_totals'], TRUE)));

            echo '<br /><span style="font-weight: bold; color: black; font-size: 18px;">Price totals:</span>';
            echo '<br />'.nl2br(esc_html(print_r($retPrices['overall'], TRUE)));

            echo '<br /><span style="font-weight: bold; color: black; font-size: 18px;">Prepayments:</span>';
            echo '<br />'.nl2br(esc_html(print_r($retPrices['prepayment'], TRUE)));

            if($this->debugMode >= 2)
            {
                echo nl2br(esc_html(print_r($retPrices, TRUE)));
            }
        }

        return $retPrices;
    }
}