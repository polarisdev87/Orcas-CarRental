<?php
/**
 * Booking processor

 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Booking;
use NativeRentalSystem\Models\AbstractElement;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;

class Booking extends AbstractElement
{
    protected $conf 	                = NULL;
    protected $lang 		            = NULL;
    protected $settings		            = array();
    protected $debugMode 	            = 0;
    protected $bookingId                = 0;
    protected $shortDateFormat          = "Y-m-d";

    /**
     * Booking constructor.
     * @param ExtensionConfiguration &$paramConf
     * @param Language &$paramLang
     * @param array $paramSettings
     * @param int $paramBookingId
     */
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings, $paramBookingId)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;
        // Set saved settings
        $this->settings = $paramSettings;

        // Set booking id
        $this->bookingId = StaticValidator::getValidPositiveInteger($paramBookingId, 0);
        if(isset($paramSettings['conf_short_date_format']))
        {
            $this->shortDateFormat = sanitize_text_field($paramSettings['conf_short_date_format']);
        }
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    /**
     * For internal class use only
     * @param $paramBookingId
     * @return mixed
     */
    private function getDataFromDatabaseById($paramBookingId)
    {
        $validBookingId = StaticValidator::getValidPositiveInteger($paramBookingId, 0);
        $sqlData = "
            SELECT *
            FROM {$this->conf->getPrefix()}bookings
            WHERE booking_id='{$validBookingId}' AND is_block='0'
        ";

        $bookingData = $this->conf->getInternalWPDB()->get_row($sqlData, ARRAY_A);

        return $bookingData;
    }
	
	/**
     * Get all boking that are in the futurte
     * @param $paramBookingId
     * @return mixed
     */
	
	public function getAllBukingFields($paramBookingId)
    {
		$validBookingId = StaticValidator::getValidPositiveInteger($paramBookingId, 0);
        $sqlData = "
            SELECT *
            FROM {$this->conf->getPrefix()}bookings
            WHERE booking_id='{$validBookingId}' AND is_block='0'
        ";

        $bookingData = $this->conf->getInternalWPDB()->get_row($sqlData, ARRAY_A);

        return $bookingData;
    }

    public function getId()
    {
        return $this->bookingId;
    }

    /**
     * Take the customer Id data from booking data
     * @return int
     */
    public function getCustomerId()
    {
        $customerId = 0;
        $bookingData = $this->getDataFromDatabaseById($this->bookingId);
        if(!is_null($bookingData))
        {
            $customerId = $bookingData['customer_id'];
        }

        return $customerId;
    }
	
	public function getPaymentUser($costumer_id)
    {
			$sqlData = "
            SELECT *
            FROM {$this->conf->getPrefix()}customers
            WHERE customer_id='{$costumer_id}' 
			";

			$userCredentials = $this->conf->getInternalWPDB()->get_row($sqlData, ARRAY_A);
			
			
			return $userCredentials;
    }

    public function getCode()
    {
        $bookingCode = "";
        $bookingData = $this->getDataFromDatabaseById($this->bookingId);
        if(!is_null($bookingData))
        {
            $bookingCode = stripslashes($bookingData['booking_code']); // Make raw
        }

        return $bookingCode;
    }

    public function getPrintCode()
    {
        return esc_html($this->getCode());
    }

    public function getEditCode()
    {
        return esc_attr($this->getCode());
    }

    public function getCouponCode()
    {
        $couponCode = "";
        $bookingData = $this->getDataFromDatabaseById($this->bookingId);
        if(!is_null($bookingData))
        {
            $couponCode = stripslashes($bookingData['coupon_code']); // Make raw
        }

        return $couponCode;
    }

    public function getPrintCouponCode()
    {
        return esc_html($this->getCouponCode());
    }

    public function getEditCouponCode()
    {
        return esc_attr($this->getCouponCode());
    }

    public function getPaymentMethodCode()
    {
        $paymentMethodCode = "";
        $bookingData = $this->getDataFromDatabaseById($this->bookingId);
        if(!is_null($bookingData))
        {
            $paymentMethodCode = stripslashes($bookingData['payment_method_code']); // Make raw
        }

        return $paymentMethodCode;
    }

    public function getPrintPaymentMethodCode()
    {
        return esc_html($this->getCode());
    }

    public function getEditPaymentMethodCode()
    {
        return esc_attr($this->getCode());
    }

    /**
     * If will get next insert id of next booking id by running SQL, not via param
     * @return string
     */
    private function generateNewCode()
    {
        $validNextMySQLInsertId = 1;
        $row = $this->conf->getInternalWPDB()->get_row("SHOW TABLE STATUS LIKE '{$this->conf->getPrefix()}bookings'", ARRAY_A);
        if(!is_null($row))
        {
            $validNextMySQLInsertId = $row['Auto_increment']; // This is current max it+1, capital first letter is ok - it is there like that
        }
        $newBookingCode = "R".$validNextMySQLInsertId."A".$this->getIncrementalHash(5);

        return $newBookingCode;
    }

    private function getIncrementalHash($length = 5)
    {
        //$charset = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        $charset = "ABCDEFGHIJKLMNPRSTUVYZ"; // fits LT & EN, O is skipped to similarity to Zero
        $charsetLength = strlen($charset);
        $result = '';

        $microTimesList = explode(' ', microtime());
        $now = $microTimesList[1];
        while ($now >= $charsetLength)
        {
            $i = $now % $charsetLength;
            $result = $charset[$i] . $result;
            $now /= $charsetLength;
        }
        return substr($result, -$length);
    }

    /**
     * Allow to edit booking if at least one item in booking owned by partner or it is a manager
     * Checks if current user can edit the element
     * @return bool
     */
    public function canEdit()
    {
        $validBookingId = StaticValidator::getValidPositiveInteger($this->bookingId, 0);
        $validPartnerId = StaticValidator::getValidPositiveInteger(get_current_user_id(), 0);
        $canEdit = FALSE;

        if($this->bookingId > 0)
        {
            $bookedItemsSQL = "
                SELECT bop.item_sku
                FROM {$this->conf->getPrefix()}booking_options bop
                JOIN {$this->conf->getPrefix()}items it ON it.item_sku=bop.item_sku
                WHERE bop.booking_id='{$validBookingId}' AND it.partner_id='{$validPartnerId}'
            ";
            $resultsExists = $this->conf->getInternalWPDB()->get_row($bookedItemsSQL, ARRAY_A);
            if(!is_null($resultsExists) && current_user_can('manage_'.$this->conf->getExtensionPrefix().'partner_bookings'))
            {
                $canEdit = TRUE;
            } else if(current_user_can('manage_'.$this->conf->getExtensionPrefix().'all_bookings'))
            {
                $canEdit = TRUE;
            }
        }

        return $canEdit;
    }


    public function isValid()
    {
        $bookingData = $this->getDataFromDatabaseById($this->bookingId);
        if(!is_null($bookingData))
        {
            if($bookingData['pickup_timestamp'] > time() && $bookingData['is_cancelled'] == 0 && in_array($bookingData['payment_successful'], array(0, 1)))
            {
                return TRUE;
            } else
            {
                return FALSE;
            }
        } else
        {
            return FALSE;
        }
    }

    public function isDeparted()
    {
        $bookingData = $this->getDataFromDatabaseById($this->bookingId);
        if(!is_null($bookingData) && $bookingData['pickup_timestamp'] < time())
        {
            return TRUE;
        } else
        {
            return FALSE;
        }
    }

    public function isCancelled()
    {
        $bookingData = $this->getDataFromDatabaseById($this->bookingId);
        if(!is_null($bookingData) && $bookingData['is_cancelled'] == 1)
        {
            return TRUE;
        } else
        {
            return FALSE;
        }
    }

    public function isPaid()
    {
        $bookingData = $this->getDataFromDatabaseById($this->bookingId);
        if(!is_null($bookingData) && $bookingData['payment_successful'] == 1)
        {
            return TRUE;
        } else
        {
            return FALSE;
        }
    }

    public function isCompletedEarly()
    {
        $bookingData = $this->getDataFromDatabaseById($this->bookingId);
        if(!is_null($bookingData) && $bookingData['is_completed_early'] == 1)
        {
            return TRUE;
        } else
        {
            return FALSE;
        }
    }

    public function isRefunded()
    {
        $bookingData = $this->getDataFromDatabaseById($this->bookingId);
        if(!is_null($bookingData)&& $bookingData['payment_successful'] == 2)
        {
            return TRUE;
        } else
        {
            return FALSE;
        }
    }


    /**
     * Check weather this booking has any assigned items or extras to it or not
     * @return bool
     */
    public function isEmpty()
    {
        $validBookingId = StaticValidator::getValidPositiveInteger($this->bookingId, 0);

        $relatedRows = $this->conf->getInternalWPDB()->get_var("
            SELECT booking_id
            FROM {$this->conf->getPrefix()}booking_options
            WHERE booking_id='{$validBookingId}' AND blog_id='{$this->conf->getBlogId()}'
        ");
        // If no related elements found to this booking
        if(!is_null($relatedRows))
        {
            return FALSE;
        } else
        {
            return TRUE;
        }
    }
	
	public function getDepositRetriveFields($paramIncludeUnclassified = FALSE){
		
	}

    /**
     * Used as a initializer and data puller of existing booking BEFORE search engine functions
     * @return mixed
     */
    public function getDetails($paramIncludeUnclassified = FALSE)
    {
        $ret = $this->getDataFromDatabaseById($this->bookingId);
		
        if(!is_null($ret))
        {
            // Make raw
            $ret['booking_code'] = stripslashes($ret['booking_code']);
            $ret['coupon_code'] = stripslashes($ret['coupon_code']);
            $ret['pickup_location_code'] = stripslashes($ret['pickup_location_code']);
            $ret['return_location_code'] = stripslashes($ret['return_location_code']);
            $ret['payment_method_code'] = stripslashes($ret['payment_method_code']);

            if($ret['booking_timestamp'] > 0)
            {
                $ret['booking_date'] = date_i18n($this->shortDateFormat, $ret['booking_timestamp'] + get_option('gmt_offset') * 3600, TRUE);
                $ret['booking_time'] = date_i18n('H:i', $ret['booking_timestamp'] + get_option('gmt_offset') * 3600, TRUE);
                $printBookingDate = date_i18n(get_option('date_format'), $ret['booking_timestamp'] + get_option('gmt_offset') * 3600, TRUE);
                $printBookingTime = date_i18n(get_option('time_format'), $ret['booking_timestamp'] + get_option('gmt_offset') * 3600, TRUE);
            } else
            {
                $ret['booking_date'] = '';
                $ret['booking_time'] = '';
                $printBookingDate = '';
                $printBookingTime = '';
            }

            if($ret['pickup_timestamp'] > 0)
            {
                $ret['pickup_date'] = date_i18n($this->shortDateFormat, $ret['pickup_timestamp'] + get_option('gmt_offset') * 3600, TRUE);
                $ret['pickup_time'] = date_i18n('H:i', $ret['pickup_timestamp'] + get_option('gmt_offset') * 3600, TRUE);
                $printPickupDate = date_i18n(get_option('date_format'), $ret['pickup_timestamp'] + get_option('gmt_offset') * 3600, TRUE);
                $printPickupTime = date_i18n(get_option('time_format'), $ret['pickup_timestamp'] + get_option('gmt_offset') * 3600, TRUE);
            } else
            {
                $ret['pickup_date'] = '';
                $ret['pickup_time'] = '';
                $printPickupDate = '';
                $printPickupTime = '';
            }

            if($ret['return_timestamp'] > 0)
            {
                $ret['return_date'] = date_i18n($this->shortDateFormat, $ret['return_timestamp'] + get_option('gmt_offset') * 3600, TRUE);
                $ret['return_time'] = date_i18n('H:i', $ret['return_timestamp'] + get_option('gmt_offset') * 3600, TRUE);
                $printReturnDate = date_i18n(get_option('date_format'), $ret['return_timestamp'] + get_option('gmt_offset') * 3600, TRUE);
                $printReturnTime = date_i18n(get_option('time_format'), $ret['return_timestamp'] + get_option('gmt_offset') * 3600, TRUE);
            } else
            {
                $ret['return_date'] = '';
                $ret['return_time'] = '';
                $printReturnDate = '';
                $printReturnTime = '';
            }

            $validBookingId = intval($ret['booking_id']);
            $bookedItemsSQL = "
                SELECT bo.option_id, bo.units_booked,
                it.item_id, it.manufacturer_id, it.body_type_id, it.transmission_type_id, it.fuel_type_id
                FROM {$this->conf->getPrefix()}booking_options bo
                JOIN {$this->conf->getPrefix()}items it ON it.item_sku=bo.item_sku AND it.blog_id='{$ret['blog_id']}'
                WHERE booking_id='{$validBookingId}'
            ";
            $bookedExtrasSQL = "
                SELECT ex.extra_id, bo.option_id, bo.units_booked
                FROM {$this->conf->getPrefix()}booking_options bo
                JOIN {$this->conf->getPrefix()}extras ex ON ex.extra_sku=bo.extra_sku AND ex.blog_id='{$ret['blog_id']}'
                WHERE booking_id='{$validBookingId}'
            ";
            $bookedItems = $this->conf->getInternalWPDB()->get_results($bookedItemsSQL, ARRAY_A);
            $bookedExtras = $this->conf->getInternalWPDB()->get_results($bookedExtrasSQL, ARRAY_A);

            // DEBUG
            // echo "<br />BOOKING: ".$validBookingId.", ITEMS: ".nl2br(print_r($bookedItems, TRUE));

            // Cars and Car Units
            $ret['item_ids'] = array();
            $ret['item_units'] = array();
            $ret['item_options'] = array();
            $ret['items'] = array();
            foreach($bookedItems AS $reservedItem)
            {
                $ret['item_ids'][] = $reservedItem['item_id'];
                $ret['item_units'][$reservedItem['item_id']] = $reservedItem['units_booked'];
                $ret['item_options'][$reservedItem['item_id']] = $reservedItem['option_id'];
                $ret['items'][] = array(
                    "item_id" => $reservedItem['item_id'],
                    "manufacturer_id" => $reservedItem['manufacturer_id'],
                    "body_type_id" => $reservedItem['body_type_id'],
                    "transmission_type_id" => $reservedItem['transmission_type_id'],
                    "fuel_type_id" => $reservedItem['fuel_type_id'],
                    "option_id" => $reservedItem['option_id'],
                    "units_booked" => $reservedItem['units_booked'],
                );
            }

            // Extras and Extra Units
            $ret['extra_ids'] = array();
            $ret['extra_options'] = array();
            $ret['extra_units'] = array();
            $ret['extras'] = array();
            foreach($bookedExtras AS $reservedExtra)
            {
                $ret['extra_ids'][] = $reservedExtra['extra_id'];
                $ret['extra_options'][$reservedExtra['extra_id']] = $reservedExtra['option_id'];
                $ret['extra_units'][$reservedExtra['extra_id']] = $reservedExtra['units_booked'];
                $ret['extras'][] = array(
                    "extra_id"      => $reservedExtra['extra_id'],
                    "option_id"     => $reservedExtra['option_id'],
                    "units_booked"  => $reservedExtra['units_booked'],
                );
            }

            // Get payment status text and color
            $ret['print_payment_status'] = "";
            $ret['payment_status_color'] = "#FF0000";
            if($ret['payment_successful'] == 0)
            {
                $ret['print_payment_status'] = $this->lang->getText('NRS_ADMIN_BOOKING_STATUS_UNPAID_TEXT');
                $ret['payment_status_color'] = "#FF0000";
            } else if($ret['payment_successful'] == 1)
            {
                $ret['print_payment_status'] = $this->lang->getText('NRS_ADMIN_BOOKING_STATUS_PAID_TEXT');
                $ret['payment_status_color'] = "black";
            } else if($ret['payment_successful'] == 2)
            {
                $ret['print_payment_status'] = $this->lang->getText('NRS_ADMIN_BOOKING_STATUS_REFUNDED_TEXT');
                $ret['payment_status_color'] = "navy";
            }

            // Get booking status text and color
            $ret['print_booking_status'] = "";
            $ret['booking_status_color'] = "black";
            if($ret['is_cancelled'] == 0 && $ret['return_timestamp'] >= time())
            {
                if($ret['pickup_timestamp'] <= time())
                {
                    // Departed
                    $ret['print_booking_status'] = $this->lang->getText('NRS_ADMIN_BOOKING_STATUS_DEPARTED_TEXT');
                    $ret['booking_status_color'] = "blue";
                } else
                {
                    // Upcoming
                    $ret['print_booking_status'] = $this->lang->getText('NRS_ADMIN_BOOKING_STATUS_UPCOMING_TEXT');
                    $ret['booking_status_color'] = "green";
                }
            } else if($ret['is_cancelled'] == 0 && $ret['return_timestamp'] < time())
            {
                if($ret['is_completed_early'] == 1)
                {
                    $ret['print_booking_status'] = $this->lang->getText('NRS_ADMIN_BOOKING_STATUS_COMPLETED_EARLY_TEXT');
                    $ret['booking_status_color'] = "black";
                } else
                {
                    $ret['print_booking_status'] = $this->lang->getText('NRS_ADMIN_BOOKING_STATUS_COMPLETED_TEXT');
                    $ret['booking_status_color'] = "black";
                }
            } else if($ret['is_cancelled'] == 1)
            {
                $ret['print_booking_status'] = $this->lang->getText('NRS_ADMIN_BOOKING_STATUS_CANCELLED_TEXT');
                $ret['booking_status_color'] = "red";
            }


            // Prepare output for print
            $ret['print_booking_date'] = $printBookingDate;
            $ret['print_booking_time'] = $printBookingTime;
            $ret['print_pickup_date'] = $printPickupDate;
            $ret['print_pickup_time'] = $printPickupTime;
            $ret['print_return_date'] = $printReturnDate;
            $ret['print_return_time'] = $printReturnTime;
            $ret['print_booking_code'] = esc_html($ret['booking_code']);
            $ret['print_coupon_code'] = esc_html($ret['coupon_code']);
            $ret['print_pickup_location_code'] = esc_html($ret['pickup_location_code']);
            $ret['print_return_location_code'] = esc_html($ret['return_location_code']);
            $ret['print_payment_method_code'] = esc_html($ret['payment_method_code']);
            $ret['print_block_name'] = esc_html($ret['block_name']);

            // Prepare output for edit
            $ret['edit_booking_code'] = esc_attr($ret['booking_code']); // for input field
            $ret['edit_coupon_code'] = esc_attr($ret['coupon_code']); // for input field
            $ret['edit_pickup_location_code'] = esc_attr($ret['pickup_location_code']); // for input field
            $ret['edit_return_location_code'] = esc_attr($ret['return_location_code']); // for input field
            $ret['edit_payment_method_code'] = esc_attr($ret['payment_method_code']); // for input field
			
			
        }

        return $ret;
    }

    /**
     * Save booking data
     * @param int $paramCustomerId
     * @param string $paramPaymentMethodCode
     * @param string $pickupLocationCode
     * @param string $returnLocationCode
     * @param array $paramDataArray
     * @return false|int
     */
    public function save($paramCustomerId, $paramPaymentMethodCode, $pickupLocationCode, $returnLocationCode, $paramDataArray = array())
    {
        $validBookingId = StaticValidator::getValidPositiveInteger($this->bookingId, 0);
        $validCustomerId = StaticValidator::getValidPositiveInteger($paramCustomerId, 0);

        if($this->debugMode == 1)
        {
            echo "<br /><strong>Reservation Id:</strong> {$validBookingId}, ";
            echo "<br /><strong>Customer Id:</strong> {$validCustomerId} ";
            echo "<br /><strong>Search params array:</strong> "; echo nl2br(print_r($paramDataArray, TRUE));
        }

        $validPaymentMethodCode = esc_sql(sanitize_key($paramPaymentMethodCode)); // only for sql query
        $sanitizedCouponCode = isset($paramDataArray['coupon_code']) ? StaticValidator::getValidValue($paramDataArray['coupon_code'], 'guest_text_validation', '') : "";
        $validCouponCode = esc_sql($sanitizedCouponCode); // only for sql query
        $validPickupTimestamp = isset($paramDataArray['pickup_timestamp']) ? intval($paramDataArray['pickup_timestamp']) : 0;
        $validReturnTimestamp = isset($paramDataArray['return_timestamp']) ? intval($paramDataArray['return_timestamp']) : 0;
        $validPickupLocationCode = esc_sql(sanitize_text_field($pickupLocationCode)); // for sql queries only
        $validReturnLocationCode = esc_sql(sanitize_text_field($returnLocationCode)); // for sql queries only
        $validPartnerId = isset($paramDataArray['partner_id']) ? intval($paramDataArray['partner_id']) : -1;
        $validManufacturerId = isset($paramDataArray['manufacturer_id']) ? intval($paramDataArray['manufacturer_id']) : -1;
        $validBodyTypeId = isset($paramDataArray['body_type_id']) ? intval($paramDataArray['body_type_id']) : -1;
        $validTransmissionTypeId = isset($paramDataArray['transmission_type_id']) ? intval($paramDataArray['transmission_type_id']) : -1;
        $validFuelTypeId = isset($paramDataArray['fuel_type_id']) ? intval($paramDataArray['fuel_type_id']) : -1;

        // If this is an existing booking and payment method is not online payment
        if($validBookingId > 0)
        {
            if($this->debugMode == 1)
            {
                echo "<br /><strong>FINALIZE BOOKING EDIT:</strong> UPDATE EVERYTHING EXCEPT BOOKING TIME. USE EDIT_TIME INSTEAD OF THAT";
            }
            // UPDATE EVERYTHING EXCEPT BOOKING TIMESTAMP. UPDATES EDIT_TIMESTAMP INSTEAD
            $updateSQL = "
				UPDATE {$this->conf->getPrefix()}bookings SET
				last_edit_timestamp='".time()."',
				coupon_code='{$validCouponCode}',
				pickup_timestamp='{$validPickupTimestamp}',
				return_timestamp='{$validReturnTimestamp}',
				pickup_location_code='{$validPickupLocationCode}',
				return_location_code='{$validReturnLocationCode}',
				partner_id='{$validPartnerId}',
				manufacturer_id='{$validManufacturerId}',
				body_type_id='{$validBodyTypeId}',
				transmission_type_id='{$validTransmissionTypeId}',
				fuel_type_id='{$validFuelTypeId}',
				customer_id='{$validCustomerId}',
				payment_method_code='{$validPaymentMethodCode}'
				WHERE booking_id='{$validBookingId}' AND blog_id='{$this->conf->getBlogId()}'
			";
            $saved = $this->conf->getInternalWPDB()->query($updateSQL);

            if($saved === FALSE)
            {
                $this->errorMessages[] = $this->lang->getText('NRS_BOOKING_UPDATE_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('NRS_BOOKING_UPDATED_TEXT');
            }
        } else
        {
            if($this->debugMode == 1)
            {
                echo "<br /><strong>FINALIZE NEW BOOKING:</strong> INSERT NEW DATA TO DATABASE.";
            }
            $validNewBookingCode = esc_sql(sanitize_text_field($this->generateNewCode()));

            $insertSQL = "
				INSERT INTO {$this->conf->getPrefix()}bookings
				(
					booking_code, coupon_code, booking_timestamp,
					pickup_timestamp, return_timestamp,
					pickup_location_code, return_location_code,
					partner_id, manufacturer_id,
					body_type_id, transmission_type_id, fuel_type_id,
					customer_id,
					payment_method_code, blog_id
				) VALUES
				(
					'{$validNewBookingCode}', '{$validCouponCode}', '".time()."',
					'{$validPickupTimestamp}', '{$validReturnTimestamp}',
					'{$validPickupLocationCode}', '{$validReturnLocationCode}',
					'{$validPartnerId}', '{$validManufacturerId}', 
					'{$validBodyTypeId}', '{$validTransmissionTypeId}', '{$validFuelTypeId}',
					'{$validCustomerId}',
					'{$validPaymentMethodCode}', '{$this->conf->getBlogId()}'
				)
			";

            //echo "<br />INSERT BOOKING: ".nl2br($insertSQL); die();
            $saved = $this->conf->getInternalWPDB()->query($insertSQL);

            if($saved)
            {
                // Assign new object id for future use
                $this->bookingId = $this->conf->getInternalWPDB()->insert_id;
            }

            if($saved === FALSE || $saved === 0)
            {
                $this->errorMessages[] = $this->lang->getText('NRS_BOOKING_INSERT_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('NRS_BOOKING_INSERTED_TEXT');
            }
        }

        return $saved;
    }

    /**
     * @return bool
     */
    public function cancel()
    {
        $cancelled = FALSE;
        $bookingData = $this->getDataFromDatabaseById($this->bookingId);
        // If there exists unpaid booking under this booking id
        if(!is_null($bookingData) && $bookingData['is_cancelled'] == 0)
        {
            $cancelled = $this->conf->getInternalWPDB()->query("
                  UPDATE {$this->conf->getPrefix()}bookings SET
                  is_cancelled='1'
                  WHERE booking_id='{$bookingData['booking_id']}' AND blog_id='{$this->conf->getBlogId()}'
            ");
        }

        if($cancelled === FALSE || $cancelled === 0)
        {
            $this->errorMessages[] = $this->lang->getText('NRS_BOOKING_CANCEL_ERROR_TEXT');
        } else
        {
            $this->okayMessages[] = $this->lang->getText('NRS_BOOKING_CANCELLED_TEXT');
        }

        return $cancelled;
    }

    public function delete()
    {
        $validBookingId = StaticValidator::getValidPositiveInteger($this->bookingId);
        $deleted = $this->conf->getInternalWPDB()->query("
            DELETE FROM {$this->conf->getPrefix()}bookings
            WHERE booking_id='{$validBookingId}' AND blog_id='{$this->conf->getBlogId()}'
        ");

        if($deleted === FALSE || $deleted === 0)
        {
            $this->errorMessages[] = $this->lang->getText('NRS_BOOKING_DELETE_ERROR_TEXT');
        } else
        {
            $this->okayMessages[] = $this->lang->getText('NRS_BOOKING_DELETED_TEXT');
        }

        return $deleted;
    }

    /**
     * Element-specific method
     * Delete all booking options related with this booking id
     */
    public function deleteAllOptions()
    {
        $validBookingId = StaticValidator::getValidPositiveInteger($this->bookingId);
        $deleted = $this->conf->getInternalWPDB()->query("
            DELETE FROM {$this->conf->getPrefix()}booking_options
            WHERE booking_id='{$validBookingId}' AND blog_id='{$this->conf->getBlogId()}'
        ");

        if($deleted === FALSE || $deleted === 0)
        {
            $this->errorMessages[] = $this->lang->getText('NRS_BOOKING_DELETE_OPTIONS_ERROR_TEXT');
        } else
        {
            $this->okayMessages[] = $this->lang->getText('NRS_BOOKING_OPTIONS_DELETED_TEXT');
        }

        return $deleted;
    }
	
	

    public function markPaid($paramPaymentTransactionId = "", $paramPayerEmail = "")
    {
        $markedAsPaid = FALSE;
        $sanitizedPaymentTransactionId = sanitize_text_field($paramPaymentTransactionId); // Optional
        $validPaymentTransactionId = esc_sql($sanitizedPaymentTransactionId); // Optional, for sql query only
        $sanitizedPayerEmail = sanitize_email($paramPayerEmail); // Optional
        $validPayerEmail = esc_sql($sanitizedPayerEmail); // Optional, for sql query only

        $bookingData = $this->getDataFromDatabaseById($this->bookingId);
        // If there exists unpaid booking under this booking id
        if(!is_null($bookingData) && $bookingData['payment_successful'] == 0)
        {
            $markedAsPaid = $this->conf->getInternalWPDB()->query("
                  UPDATE {$this->conf->getPrefix()}bookings SET
                  payment_successful='1', payment_transaction_id='{$validPaymentTransactionId}',
                  payer_email='{$validPayerEmail}'
                  WHERE booking_id='{$bookingData['booking_id']}' AND is_block='0' AND blog_id='{$this->conf->getBlogId()}'
            ");
            // Note - we don't use blog_id here, to make it site-independent
            $this->conf->getInternalWPDB()->query("
                UPDATE {$this->conf->getPrefix()}customers SET
                existing_customer='1'
                WHERE customer_id='{$bookingData['customer_id']}'
            ");
        }

        if($markedAsPaid === FALSE || $markedAsPaid === 0)
        {
            $this->errorMessages[] = $this->lang->getText('NRS_BOOKING_MARK_AS_PAID_ERROR_TEXT');
        } else
        {
            $this->okayMessages[] = $this->lang->getText('NRS_BOOKING_MARKED_AS_PAID_TEXT');
        }

        return $markedAsPaid;
    }
	
	/*Saving cardId to custumer table*/
	
	public function saveCardIdToCostumer($paramCostumerId = "", $paramCardId = "")
    {
        $markedAsPaid = FALSE;
        $sanitizedCostumerId = sanitize_text_field($paramCostumerId); // Optional
		$sanitizedCardId = sanitize_text_field($paramCardId);


        $bookingData = $this->getDataFromDatabaseById($this->bookingId);
        // If there exists unpaid booking under this booking id
       
        $savedCostumerCard=$this->conf->getInternalWPDB()->query("
                UPDATE {$this->conf->getPrefix()}customers SET
                cotumer_stripe_id='{$sanitizedCardId}'
                WHERE customer_id='{$sanitizedCostumerId}'
            ");
        

        if($savedCostumerCard === FALSE || $savedCostumerCard === 0)
        {
            $this->errorMessages[] = 'Stripe Costumer is not saved!';
        } else
        {
            $this->okayMessages[] = 'Stripe Costumer is  saved!';
        }

        return $savedCostumerCard;
    }

    public function markCompletedEarly()
    {
        $markedAsCompletedEarly = FALSE;

        $bookingData = $this->getDataFromDatabaseById($this->bookingId);
        // If there exists unpaid booking under this booking id
        if(!is_null($bookingData) && $bookingData['pickup_timestamp'] <= time() && $bookingData['is_completed_early'] == 0)
        {
            $markedAsCompletedEarly = $this->conf->getInternalWPDB()->query("
                  UPDATE {$this->conf->getPrefix()}bookings SET
                  return_timestamp='".time()."', is_completed_early='1'
                  WHERE booking_id='{$bookingData['booking_id']}' AND is_block='0' AND blog_id='{$this->conf->getBlogId()}'
            ");
        }

        if($markedAsCompletedEarly === FALSE || $markedAsCompletedEarly === 0)
        {
            $this->errorMessages[] = $this->lang->getText('NRS_BOOKING_MARK_COMPLETED_EARLY_ERROR_TEXT');
        } else
        {
            $this->okayMessages[] = $this->lang->getText('NRS_BOOKING_MARKED_COMPLETED_EARLY_TEXT');
        }

        return $markedAsCompletedEarly;
    }

    /**
     * Money were sent back to the customer
     * @return bool|false|int
     */
    public function refund()
    {
        $refunded = FALSE;
        $bookingData = $this->getDataFromDatabaseById($this->bookingId);

        // If there exists unpaid booking under this booking id
        if(!is_null($bookingData) && $bookingData['payment_successful'] == 1)
        {
            $refunded = $this->conf->getInternalWPDB()->query("
                  UPDATE {$this->conf->getPrefix()}bookings SET
                  payment_successful='2', is_cancelled='1'
                  WHERE booking_id='{$bookingData['booking_id']}' AND is_block='0' AND blog_id='{$this->conf->getBlogId()}'
            ");
        }

        if($refunded === FALSE || $refunded === 0)
        {
            $this->errorMessages[] = $this->lang->getText('NRS_BOOKING_REFUND_ERROR_TEXT');
        } else
        {
            $this->okayMessages[] = $this->lang->getText('NRS_BOOKING_REFUNDED_TEXT');
        }

        return $refunded;
    }
	
	public function preAutorize($paramBookingId = "",$paramPreAutorizeTransactionId = "")
    {
        $autorized = FALSE;
		
		$sanitizedBookingId = sanitize_text_field($paramBookingId);
		$sanitizedPreAutorizeTransactionId = sanitize_text_field($paramPreAutorizeTransactionId);
		
		
        

        // If there exists unpaid booking under this booking id

            $autorized = $this->conf->getInternalWPDB()->query("
                  UPDATE {$this->conf->getPrefix()}bookings SET
                  deposit_charge_id='{$sanitizedPreAutorizeTransactionId}'
                  WHERE booking_id='{$sanitizedBookingId}' AND is_block='0'
            ");
        

        if($autorized === FALSE || $autorized === 0)
        {
            $this->errorMessages[] = $this->lang->getText('NRS_BOOKING_REFUND_ERROR_TEXT');
        } else
        {
            $this->okayMessages[] = $this->lang->getText('NRS_BOOKING_REFUNDED_TEXT');
        }

        return $autorized;
    }
	
	public function preAutorizeRefound($paramBookingId = "",$paramPreAutorizeTransactionId=NULL)
    {
        $autorized = FALSE;
		
		$sanitizedBookingId = sanitize_text_field($paramBookingId);
		$sanitizedPreAutorizeTransactionId = $paramPreAutorizeTransactionId;
		
		
        

        // If there exists unpaid booking under this booking id

            $autorized = $this->conf->getInternalWPDB()->query("
                  UPDATE {$this->conf->getPrefix()}bookings SET
                  deposit_charge_id=NULL
                  WHERE booking_id='{$sanitizedBookingId}' AND is_block='0'
            ");
        

        if($autorized === FALSE || $autorized === 0)
        {
            $this->errorMessages[] = $this->lang->getText('NRS_BOOKING_REFUND_ERROR_TEXT');
        } else
        {
            $this->okayMessages[] = $this->lang->getText('NRS_BOOKING_REFUNDED_TEXT');
        }

        return $autorized;
    }
	
	public function preAutorizeRetrieve($paramBookingId = "",$paramPreAutorizeTransactionId='')
    {
        $autorized = FALSE;
		
		$sanitizedBookingId = sanitize_text_field($paramBookingId);
		$sanitizedPreAutorizeTransactionId = sanitize_text_field($paramPreAutorizeTransactionId);
		
		
        

        // If there exists unpaid booking under this booking id

           $autorized = $this->conf->getInternalWPDB()->query("
                  UPDATE {$this->conf->getPrefix()}bookings SET
                  deposit_retrieve_fields='{$sanitizedPreAutorizeTransactionId}'
                  WHERE booking_id='{$sanitizedBookingId}' AND is_block='0'
            ");
        

        if($autorized === FALSE || $autorized === 0)
        {
            $this->errorMessages[] = $this->lang->getText('NRS_BOOKING_REFUND_ERROR_TEXT');
        } else
        {
            $this->okayMessages[] = $this->lang->getText('NRS_BOOKING_REFUNDED_TEXT');
        }

        return $autorized;
    }
}