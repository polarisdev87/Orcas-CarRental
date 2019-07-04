<?php
/**
 * NRS Bookings Observer (no setup for single booking)
 * Abstract class cannot be inherited anymore. We use them when creating new instances
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Booking;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\iObserver;
use NativeRentalSystem\Models\Option\ItemOption;
use NativeRentalSystem\Models\PaymentMethod\PaymentMethod;
use NativeRentalSystem\Models\PaymentMethod\PaymentMethodsObserver;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Customer\Customer;
use NativeRentalSystem\Models\Item\Item;

class BookingsObserver implements iObserver
{
    protected $conf 	                = NULL;
    protected $lang 		            = NULL;
    protected $debugMode 	            = 0;
    protected $settings                 = array();
    protected $minBookingPeriod         = 0;
    protected $maxBookingPeriod         = 0;

    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;
        $this->settings = $paramSettings;

        // set minimum booking period
        $this->minBookingPeriod = StaticValidator::getValidSetting($paramSettings, 'conf_minimum_booking_period', 'positive_integer', 0);
        // Set maximum booking period
        $this->maxBookingPeriod = StaticValidator::getValidSetting($paramSettings, 'conf_maximum_booking_period', 'positive_integer', 0);
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    public function getMinPeriod()
    {
        return $this->minBookingPeriod;
    }

    public function getMaxPeriod()
    {
        return $this->maxBookingPeriod;
    }

    public function getIdByCode($paramBookingCode)
    {
        $retBookingId = 0;
        $validBookingCode = esc_sql(sanitize_text_field($paramBookingCode)); // For sql query only

        $bookingData = $this->conf->getInternalWPDB()->get_row("
                SELECT booking_id
                FROM {$this->conf->getPrefix()}bookings
                WHERE booking_code='{$validBookingCode}'
            ", ARRAY_A);
        if(!is_null($bookingData))
        {
            $retBookingId = $bookingData['booking_id'];
        }

        return $retBookingId;
    }

    /**
     * @note - We don't use a BLOG_ID parameter here, as we want to return items from all blog in network!
     * @param int $paramTimestampFrom
     * @param int $paramTimestampTo
     * @param int $paramCustomerId
     * @param string $paramList - "BOOKINGS", "PICKUPS", "RETURNS"
     * @return array
     */
    public function getAllIds($paramTimestampFrom = -1, $paramTimestampTo = -1, $paramCustomerId = -1, $paramList = "BOOKINGS")
    {
        $validCustomerId = StaticValidator::getValidInteger($paramCustomerId, -1);
        $validTimestampFrom = StaticValidator::getValidInteger($paramTimestampFrom, -1);
        $validTimestampTo = StaticValidator::getValidInteger($paramTimestampTo, -1);

        // DEBUG
        //echo "<br />FROM: $validTimestampFrom ($paramTimestampFrom), TO: $validTimestampTo ($paramTimestampTo)";

        // If not filled - return all bookings
        $sqlCondition = "";
        $sqlOrderBy = "booking_timestamp ASC";
        // If there is a listing property set
        if($paramList == "PICKUPS")
        {
            if($validTimestampFrom > 0 && $validTimestampTo > 0)
            {
                $sqlCondition .= " AND pickup_timestamp BETWEEN {$validTimestampFrom} AND {$validTimestampTo}";
            } else if($validTimestampFrom > 0)
            {
                $sqlCondition .= " AND pickup_timestamp >= {$validTimestampFrom}";
            } else if($validTimestampTo > 0)
            {
                $sqlCondition .= " AND pickup_timestamp <= {$validTimestampTo}";
            }
            $sqlOrderBy = "pickup_timestamp ASC";
        } else if($paramList == "RETURNS")
        {
            if($validTimestampFrom > 0 && $validTimestampTo > 0)
            {
                $sqlCondition .= " AND return_timestamp BETWEEN {$validTimestampFrom} AND {$validTimestampTo}";
            } else if($validTimestampFrom > 0)
            {
                $sqlCondition .= " AND return_timestamp >= {$validTimestampFrom}";
            } else if($validTimestampTo > 0)
            {
                $sqlCondition .= " AND return_timestamp <= {$validTimestampTo}";
            }
            $sqlOrderBy = "return_timestamp ASC";
        } else if($paramList == "BOOKINGS")
        {
            if($validTimestampFrom > 0 && $validTimestampTo > 0)
            {
                $sqlCondition .= " AND booking_timestamp BETWEEN {$validTimestampFrom} AND {$validTimestampTo}";
            } else if($validTimestampFrom > 0)
            {
                $sqlCondition .= " AND booking_timestamp >= {$validTimestampFrom}";
            } else if($validTimestampTo > 0)
            {
                $sqlCondition .= " AND booking_timestamp <= {$validTimestampTo}";
            }
            $sqlOrderBy = "booking_timestamp ASC";
        }

        // If there is a customer id set
        if($validCustomerId > 0)
        {
            $sqlCondition .= " AND customer_id='{$validCustomerId}'";
        }

        $sql = "
            SELECT booking_id
            FROM {$this->conf->getPrefix()}bookings
            WHERE is_block='0'
            {$sqlCondition}
            ORDER BY {$sqlOrderBy}
        ";

        // DEBUG
        //echo nl2br($sql);

        $bookingIds = $this->conf->getInternalWPDB()->get_col($sql);

        return $bookingIds;
    }

    /**
     * Get reservation period list, if not date and time used
     * @param string $fieldText
     * @param int $selectedReservationPeriod
     * @param string $emptyItemValue
     * @return string
     * @internal param string $fieldID
     * @internal param string $fieldName
     */
    public function getPeriodDropDownOptions($fieldText = "", $selectedReservationPeriod = 7200, $emptyItemValue = "0")
    {
        $selectPeriods = '';

        // For up to 1 week
        $hourSpeedUpLimit1 = 1; // hours
        $hourSpeedUpLimit2 = 3; // hours
        $hourSpeedUpLimit3 = 24; // hours
        for($hour = 0; $hour <= 720; $hour++)
        {
            if($hour > $hourSpeedUpLimit3)
            {
                // For more than two days, we have to advance by 24 hours
                $hour = $hour+23;
            }
            for($minute = 0; $minute < 60; $minute = $minute+15)
            {
                //echo "hour: $hour, MIN: $minute<br />";
                if($hour == 0 && $minute == 0)
                {
                    // Special case for first item
                    if($emptyItemValue == "0")
                    {
                        $selectedPeriodZero = $selectedReservationPeriod == 0 ? ' selected="selected"' : '';
                        $selectPeriods .= '<option value="0"'.$selectedPeriodZero.'>'.$fieldText.'</option>';
                    } else
                    {
                        $selectedPeriodEmpty = $selectedReservationPeriod == "" ? ' selected="selected"' : '';
                        $selectPeriods .= '<option value=""'.$selectedPeriodEmpty.'>'.$fieldText.'</option>';
                    }
                } else
                {
                    if($hour >= $hourSpeedUpLimit2 && $minute > 0)
                    {
                        // For more than a day, we have to advance not by 30 minutes, but by the whole hour
                        break;
                    }
                    if($hour < $hourSpeedUpLimit1 || ($hour >= $hourSpeedUpLimit1 && ($minute == 0 || $minute == 30)))
                    {
                        // For more than 60 minutes, we advance by 30 more minutes

                        // All other items
                        $currentDay = $hour > 23 ? floor($hour/24) : 0;
                        $currentHour = $hour > 23 ? $hour - $currentDay*24 : $hour;
                        $currentMinute = $minute;
                        $currentFullTimeInMinutes = $hour*60 + $minute;

                        $daysText = $this->lang->getTimeTextByPeriod($currentDay, $this->lang->getText('NRS_DAY_TEXT'), $this->lang->getText('NRS_DAYS_TEXT'), $this->lang->getText('NRS_DAYS2_TEXT'));
                        $hoursText = $this->lang->getTimeTextByPeriod($currentHour, $this->lang->getText('NRS_HOUR_TEXT'), $this->lang->getText('NRS_HOURS_TEXT'), $this->lang->getText('NRS_HOURS2_TEXT'));
                        $minutesText = $this->lang->getTimeTextByPeriod($currentMinute, $this->lang->getText('NRS_MINUTE_TEXT'), $this->lang->getText('NRS_MINUTES_TEXT'), $this->lang->getText('NRS_MINUTES2_TEXT'));

                        $optionTitle = "";
                        if($hour >= $hourSpeedUpLimit3)
                        {
                            $optionTitle .= $currentDay." ".$daysText;
                        } else if($hour >= $hourSpeedUpLimit2)
                        {
                            $optionTitle .= $hour." ".$hoursText;
                        } else
                        {
                            $optionTitle .= $currentFullTimeInMinutes." ".$minutesText;
                        }
                        $optionValueInSeconds = $currentDay*86400 + $currentHour*3600 + $currentMinute*60;
                        $selected = ($optionValueInSeconds == $selectedReservationPeriod ? ' selected="selected"' : '');

                        if(
                            $optionValueInSeconds >= $this->minBookingPeriod &&
                            $optionValueInSeconds <= $this->maxBookingPeriod
                        ){
                            // Output
                            $selectPeriods .= '<option value="'.$optionValueInSeconds.'"'.$selected.'>'.$optionTitle.'</option>';
                        }
                    }
                }
            }
        }

        return $selectPeriods;
    }


    /**
     * @param $paramLocationId
     * @return array
     */
    public function getUpcomingIdsByLocationId($paramLocationId)
    {
        $validLocationId= StaticValidator::getValidPositiveInteger($paramLocationId, 0);
        // OPTIMIZED SQL: We used Php function time() instead of UNIX_TIMESTAMP() to perform more faster search
        // If there exists booking with this pickup or return location id
        $sql = "
              SELECT booking_id
              FROM {$this->conf->getPrefix()}bookings
              WHERE pickup_timestamp>='".time()."' AND
              (pickup_location_id='{$validLocationId}' OR return_location_id='{$validLocationId}')
              AND is_block='0' AND blog_id='{$this->conf->getBlogId()}'
        ";

        $bookingIds = $this->conf->getInternalWPDB()->get_col($sql);

        return $bookingIds;
    }

    /**
     * @param $paramCustomerId
     * @return array
     */
    public function getUpcomingIdsByCustomerId($paramCustomerId)
    {
        $validCustomerId = StaticValidator::getValidPositiveInteger($paramCustomerId, 0);
        $sql = "
            SELECT booking_id
            FROM {$this->conf->getPrefix()}bookings
            WHERE customer_id='{$validCustomerId}' AND pickup_timestamp>='".time()."' AND is_block='0' AND blog_id='{$this->conf->getBlogId()}'
        ";
        $bookingIds = $this->conf->getInternalWPDB()->get_col($sql);

        return $bookingIds;
    }

    /**
     * @we don't use blog_id here, as we want to clear for all sites
     */
    public function clearExpired()
    {
        // OPTIMIZED - we don't use UNIX_TIMESTAMP() here is SQL WHERE, to save resource and not to recalculate time every time
        // so we use PHP's time() function instead, which does the work pretty well,
        // and do not needs to be calculated on every SQL query
        $sqlRows = $this->conf->getInternalWPDB()->get_results("
			SELECT b.booking_id
			FROM {$this->conf->getPrefix()}bookings b
			JOIN {$this->conf->getPrefix()}payment_methods pm ON b.payment_method_code=pm.payment_method_code
			WHERE b.payment_successful='0' AND pm.expiration_time!='0'
			AND (
				(b.booking_timestamp < (".time()."-pm.expiration_time))
				OR (b.pickup_timestamp < (".(time()-86400)."))
			) AND is_block='0'
		", ARRAY_A);

        foreach ($sqlRows AS $currentRow)
        {
            $this->conf->getInternalWPDB()->query("
				DELETE FROM {$this->conf->getPrefix()}bookings
				WHERE booking_id='".$currentRow['booking_id']."' AND is_block='0'
			");
            $this->conf->getInternalWPDB()->query("
				DELETE FROM {$this->conf->getPrefix()}booking_options
				WHERE booking_id='".$currentRow['booking_id']."'
			");
            $this->conf->getInternalWPDB()->query("
				DELETE FROM {$this->conf->getPrefix()}invoices
				WHERE booking_id='".$currentRow['booking_id']."'
			");
        }
    }

    /**
     * Update pickup and return location code in bookings table for specific blog_id
     * @param $paramOldCode
     * @param $paramNewCode
     */
    public function changeLocationCode($paramOldCode, $paramNewCode)
    {
        $validOldCode = esc_sql(sanitize_text_field($paramOldCode)); // For sql queries only
        $validNewCode = esc_sql(sanitize_text_field($paramNewCode)); // For sql queries only

        $pickupUpdateQuery = "
            UPDATE {$this->conf->getPrefix()}bookings SET pickup_location_code='{$validNewCode}'
            WHERE pickup_location_code='{$validOldCode}' AND blog_id='{$this->conf->getBlogId()}'
        ";
        $returnUpdateQuery = "
            UPDATE {$this->conf->getPrefix()}bookings SET return_location_code='{$validNewCode}'
            WHERE return_location_code='{$validOldCode}' AND blog_id='{$this->conf->getBlogId()}'
        ";

        $this->conf->getInternalWPDB()->query($pickupUpdateQuery);
        $this->conf->getInternalWPDB()->query($returnUpdateQuery);
    }

    /**
     * Update payment method code in bookings table for specific blog_id
     * @param $paramOldCode
     * @param $paramNewCode
     */
    public function changePaymentMethodCode($paramOldCode, $paramNewCode)
    {
        $validOldCode = esc_sql(sanitize_text_field($paramOldCode)); // For sql queries only
        $validNewCode = esc_sql(sanitize_text_field($paramNewCode)); // For sql queries only

        $updateQuery = "
            UPDATE {$this->conf->getPrefix()}bookings SET payment_method_code='{$validNewCode}'
            WHERE payment_method_code='{$validOldCode}' AND blog_id='{$this->conf->getBlogId()}'
        ";

        $this->conf->getInternalWPDB()->query($updateQuery);
    }

    /**
     * Update item SKU in booking_options table for specific blog_id
     * @param $paramOldSKU
     * @param $paramNewSKU
     */
    public function changeItemSKU($paramOldSKU, $paramNewSKU)
    {
        $validOldSKU = esc_sql(sanitize_text_field($paramOldSKU)); // For sql queries only
        $validNewSKU = esc_sql(sanitize_text_field($paramNewSKU)); // For sql queries only

        $updateQuery = "
            UPDATE {$this->conf->getPrefix()}booking_options SET item_sku='{$validNewSKU}'
            WHERE item_sku='{$validOldSKU}' AND blog_id='{$this->conf->getBlogId()}'
        ";

        $this->conf->getInternalWPDB()->query($updateQuery);
    }

    /**
     * Update extra SKU in booking_options table for specific blog_id
     * @param $paramOldSKU
     * @param $paramNewSKU
     */
    public function changeExtraSKU($paramOldSKU, $paramNewSKU)
    {
        $validOldSKU = esc_sql(sanitize_text_field($paramOldSKU)); // For sql queries only
        $validNewSKU = esc_sql(sanitize_text_field($paramNewSKU)); // For sql queries only

        $updateQuery = "
            UPDATE {$this->conf->getPrefix()}booking_options SET extra_sku='{$validNewSKU}'
            WHERE extra_sku='{$validOldSKU}' AND blog_id='{$this->conf->getBlogId()}'
        ";

        $this->conf->getInternalWPDB()->query($updateQuery);
    }

    /*******************************************************************************/
    /********************** METHODS FOR ADMIN ACCESS ONLY **************************/
    /*******************************************************************************/

    /**
     * @param int $paramTimestampFrom
     * @param int $paramTimestampTo
     * @param int $paramCustomerId
     * @param string $paramBackToURLPart
     * @return string
     */
    public function getAdminPickups($paramTimestampFrom = -1, $paramTimestampTo = -1, $paramCustomerId = -1, $paramBackToURLPart = "")
    {
        return $this->getAdminList($paramTimestampFrom, $paramTimestampTo, $paramCustomerId, $paramBackToURLPart, "PICKUPS");
    }

    public function getAdminReturns($paramTimestampFrom = -1, $paramTimestampTo = -1, $paramCustomerId = -1, $paramBackToURLPart = "")
    {
        return $this->getAdminList($paramTimestampFrom, $paramTimestampTo, $paramCustomerId, $paramBackToURLPart, "RETURNS");
    }

    public function getAdminBookings($paramTimestampFrom = -1, $paramTimestampTo = -1, $paramCustomerId = -1, $paramBackToURLPart = "")
    {
        return $this->getAdminList($paramTimestampFrom, $paramTimestampTo, $paramCustomerId, $paramBackToURLPart, "BOOKINGS");
    }

    /**
     * @note - we don't use blog_id here, because we want to see booking from all sites
     * @param int $paramTimestampFrom
     * @param int $paramTimestampTo
     * @param int $paramCustomerId
     * @param string $paramBackToURLPart - Optional back to ulr part, i.e. &backto_from_date=1234567890&backto_to_date=2234567890
     * @param string $paramList
     * @return string
     */
    public function getAdminList($paramTimestampFrom = -1, $paramTimestampTo = -1, $paramCustomerId = -1, $paramBackToURLPart = "", $paramList = "BOOKINGS")
    {
        // Create mandatory instances
        $objPaymentMethodsObserver = new PaymentMethodsObserver($this->conf, $this->lang, $this->settings);

        $sanitizedBackToURLPart = sanitize_text_field($paramBackToURLPart); // TEST: do not escape it, as it is for url redirect
        //$validBackToURLPart = esc_attr($sanitizedBackToURLPart); // escaped, as it is attribute for JS

        $html = '';

        $bookingIds = $this->getAllIds($paramTimestampFrom, $paramTimestampTo, $paramCustomerId, $paramList);
        $i = 0;
        foreach($bookingIds AS $bookingId)
        {
            $i++;
            $objBooking = new Booking($this->conf, $this->lang, $this->settings, $bookingId);

            $canEdit = $objBooking->canEdit();
            if($canEdit || current_user_can('view_'.$this->conf->getExtensionPrefix().'all_bookings'))
            {
                $objCustomer = new Customer($this->conf, $this->lang, $this->settings, $objBooking->getCustomerId());
                $objInvoice = new Invoice($this->conf, $this->lang, $this->settings, $bookingId);
                $bookingDetails = $objBooking->getDetails();
                $customerDetails = $objCustomer->getDetails();
                $invoiceDetails = $objInvoice->getDetails();

                $paymentMethodId = $objPaymentMethodsObserver->getIdByCode($bookingDetails['payment_method_code']);
                $objPaymentMethod = new PaymentMethod($this->conf, $this->lang, $this->settings, $paymentMethodId);

                $arrBookedItemNames = array();
                $counter = 0;
				
				$paymentMethodDetails = $objPaymentMethod->getDetails();

                // Check and set if we are in call for price mode
                foreach($bookingDetails['items'] AS $bookedItems)
                {
                    $counter++;
                    $objItem = new Item($this->conf, $this->lang, $this->settings, $bookedItems['item_id']);

                    // Process item details & prices
                    $itemDetails = $objItem->getExtendedDetails();
                    $objSelectedOption = new ItemOption($this->conf, $this->lang, $this->settings, $bookedItems['option_id']);
                    $selectedOptionDetails = $objSelectedOption->getDetails();
                    $printTranslatedSelectedOptionName = isset($selectedOptionDetails['print_translated_option_name']) ? $selectedOptionDetails['print_translated_option_name'] : "";
                    $printTranslatedItemWithSelectedOption = $counter.'. '.$itemDetails['print_translated_manufacturer_title'].' '.$itemDetails['print_translated_model_name'];
                    $printTranslatedItemWithSelectedOption .= $itemDetails['partner_profile_url'] ? ' '.$itemDetails['print_via_partner'] : '';
                    $printTranslatedItemWithSelectedOption .= $itemDetails['print_translated_body_type_title'] ? ', '.$itemDetails['print_translated_body_type_title'] : '';
                    $printTranslatedItemWithSelectedOption .= $printTranslatedSelectedOptionName ? ', '.$printTranslatedSelectedOptionName.' '.$itemDetails['print_options_measurement_unit'] : '';
                    if($bookedItems['units_booked'] > 1)
                    {
                        $printTranslatedItemWithSelectedOption .= ' x '.$bookedItems['units_booked'];
                    }

                    // Add item name to booked items list
                    $arrBookedItemNames[] = $printTranslatedItemWithSelectedOption;
                }

                // Prints
                $printBookedItems = implode("<br />", $arrBookedItemNames);

                if($objPaymentMethod->isOnlinePayment())
                {
                    $payNowText = $this->lang->getText('NRS_STEP5_PAY_ONLINE_TEXT');
                } else
                {
                    $payNowText = $this->lang->getText('NRS_STEP5_PAY_AT_PICKUP_TEXT');
                }

                if(is_multisite() && $customerDetails['blog_id'] != $this->conf->getBlogId())
                {
                    switch_to_blog($customerDetails['blog_id']);
                }

                $actions = "";
                if($canEdit && $bookingDetails['return_timestamp'] >= time() && $bookingDetails['is_cancelled'] == 0)
                {
                    if($bookingDetails['payment_successful'] == 1)
                    {
                        $actions .= '<a href="javascript:;" onclick="javascript:refund'.$this->conf->getExtensionFolder().'Booking('.$bookingId.', \''.$sanitizedBackToURLPart.'\');" class="bodytext">'.$this->lang->getText('NRS_ADMIN_REFUND_TEXT').'</a><br />';
                    } else if($bookingDetails['payment_successful'] == 0)
                    {
                        $actions .= '<a href="javascript:;" onclick="javascript:markPaid'.$this->conf->getExtensionFolder().'Booking('.$bookingId.', \''.$sanitizedBackToURLPart.'\');" class="bodytext">'.$this->lang->getText('NRS_ADMIN_MARK_PAID_TEXT').'</a><br />';
                    }
                    // Early return button section
                    if($bookingDetails['pickup_timestamp'] <= time() && $bookingDetails['is_completed_early'] == 0)
                    {
                        $actions .= '<a href="javascript:;" onclick="javascript:mark'.$this->conf->getExtensionFolder().'CompletedEarly('.$bookingId.', \''.$sanitizedBackToURLPart.'\');" class="bodytext">'.$this->lang->getText('NRS_ADMIN_MARK_COMPLETED_EARLY_TEXT').'</a><br />';
                    }
                    $actions .= '<a href="javascript:;" onClick="javascript:cancel'.$this->conf->getExtensionFolder().'Booking('.$bookingId.', \''.$sanitizedBackToURLPart.'\');">'.$this->lang->getText('NRS_ADMIN_CANCEL_TEXT').'</a>';
                } else if($canEdit && $bookingDetails['return_timestamp'] < time() && $bookingDetails['is_cancelled'] == 0)
                {
                    if($bookingDetails['payment_successful'] == 1)
                    {
                        $actions .= '<a href="javascript:;" onclick="javascript:refund'.$this->conf->getExtensionFolder().'Booking('.$bookingId.', \''.$sanitizedBackToURLPart.'\');" class="bodytext">'.$this->lang->getText('NRS_ADMIN_REFUND_TEXT').'</a><br />';
                    } else if($bookingDetails['payment_successful'] == 0)
                    {
                        $actions .= '<a href="javascript:;" onclick="javascript:markPaid'.$this->conf->getExtensionFolder().'Booking('.$bookingId.', \''.$sanitizedBackToURLPart.'\');" class="bodytext">'.$this->lang->getText('NRS_ADMIN_MARK_PAID_TEXT').'</a><br />';
                    }
                    $actions .= '<a href="javascript:;" onclick="javascript:delete'.$this->conf->getExtensionFolder().'Booking('.$bookingId.', \''.$sanitizedBackToURLPart.'\');" class="bodytext">'.$this->lang->getText('NRS_ADMIN_DELETE_TEXT').'</a>';
                } else if($canEdit)
                {
                    // Cancelled (is_cancelled = 1)
                    $actions .= '<a href="javascript:;" onclick="javascript:delete'.$this->conf->getExtensionFolder().'Booking('.$bookingId.',  \''.$sanitizedBackToURLPart.'\');" class="bodytext">'.$this->lang->getText('NRS_ADMIN_DELETE_TEXT').'</a>';
                }
				$depositChargeIdLink='';
				if($bookingDetails['deposit_charge_id']!=NULL){
					$depositChargeIdLink='<a href="#" data-name="'.$customerDetails['print_full_name'].'" data-preAutorized="'.$invoiceDetails['print_fixed_deposit_amount'].'" data-bookingId="'.$bookingDetails['booking_id'].'" data-bookingStripeSecretKey="'.$paymentMethodDetails['private_key'].'" data-bookingDepositChargeId="'.$bookingDetails['deposit_charge_id'].'" class="bodytext open-modal" id="open-modal">Security Deposit</a><br />';
				}

                $html .= '<tr>
                    <td>'.$i.'</td>
                    <td>'.$bookingDetails['booking_code'].'<br /><hr />'.$customerDetails['print_full_name'].'<br />'.$printBookedItems.'<br /><a target="_blank" href="https://dashboard.stripe.com/payments/'.$bookingDetails["payment_transaction_id"].'">'.$bookingDetails["payment_transaction_id"].'</a></td>
                    <td>'.$bookingDetails['print_pickup_date'].' '.$bookingDetails['print_pickup_time'].'<br /><hr />'.$invoiceDetails['pickup_location'].'</td>
                    <td>'.$bookingDetails['print_return_date'].' '.$bookingDetails['print_return_time'].'<br /><hr />'.$invoiceDetails['return_location'].'</td>
                    <td>'.$bookingDetails['print_booking_date'].' '.$bookingDetails['print_booking_time'].'<br /><hr />
                        <span style="font-weight: bold;color:'.$bookingDetails['payment_status_color'].';">'.$bookingDetails['print_payment_status'].'</span>,
                        <span style="font-weight: bold;color:'.$bookingDetails['booking_status_color'].';">'.$bookingDetails['print_booking_status'].'</span>
                    </td>
                    <td>
                        '.$this->lang->getText('NRS_TOTAL_TEXT').': '.$invoiceDetails['print_grand_total'].'<br /><hr />
                        '.$this->lang->getText('NRS_DEPOSIT_TEXT').': '.$invoiceDetails['print_fixed_deposit_amount'].'<br />
                        '.$payNowText.': '.$invoiceDetails['print_total_pay_now'].'<br />
                        '.$this->lang->getText('NRS_PAY_ON_RETURN_TEXT').': '.$invoiceDetails['print_total_pay_later'].'<br />
                    </td>
                    <td align="right">
                        <a href="'.admin_url('admin.php?page='.$this->conf->getURLPrefix().'view-details&amp;booking_id='.$bookingId).'" class="bodytext">'.$this->lang->getText('NRS_ADMIN_VIEW_DETAILS_TEXT').'</a><br />
						'.$depositChargeIdLink.'
                        <a href="javascript:;" onclick="javascript:print'.$this->conf->getExtensionFolder().'InvoicePopup(\''.$bookingId.'\', \''.$bookingDetails['booking_code'].'\');" class="bodytext">'.$this->lang->getText('NRS_ADMIN_PRINT_INVOICE_TEXT').'</a><br />
                        '.$actions.'
                    </td>
                 </tr>';

                if(is_multisite())
                {
                    // Switch back to current blog id. Restore current blog won't work here, as it would just restore to previous blog of the long loop
                    switch_to_blog($this->conf->getBlogId());
                }
            }
        }

        return $html;
    }
}