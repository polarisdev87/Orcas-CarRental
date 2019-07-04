<?php
/**
 * Booking processor

 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Block;
use NativeRentalSystem\Models\AbstractElement;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;

class Block extends AbstractElement
{
    protected $conf 	                = NULL;
    protected $lang 		            = NULL;
    protected $settings		            = array();
    protected $debugMode 	            = 0;
    protected $blockId                  = 0;
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

        // Set block id
        $this->blockId = StaticValidator::getValidPositiveInteger($paramBookingId, 0);
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
     * @param $paramBlockId
     * @return mixed
     */
    private function getDataFromDatabaseById($paramBlockId)
    {
        $validBlockId = StaticValidator::getValidPositiveInteger($paramBlockId, 0);
        $sqlData = "
            SELECT booking_id AS block_id, pickup_location_code AS location_code, block_name,
            booking_timestamp AS block_timestamp,
            pickup_timestamp AS start_timestamp, return_timestamp AS end_timestamp, blog_id
            FROM {$this->conf->getPrefix()}bookings
            WHERE booking_id='{$validBlockId}' AND is_block='1'
        ";

        // DEBUG
        //echo nl2br($sqlData);
        $blockData = $this->conf->getInternalWPDB()->get_row($sqlData, ARRAY_A);

        return $blockData;
    }

    public function getId()
    {
        return $this->blockId;
    }

    /**
     * Allow to edit block if at least one item in block owned by partner or it is a manager
     * Checks if current user can edit the element
     * @return bool
     */
    public function canEdit()
    {
        $validBlockId = StaticValidator::getValidPositiveInteger($this->blockId, 0);
        $validPartnerId = StaticValidator::getValidPositiveInteger(get_current_user_id(), 0);
        $canEdit = FALSE;

        if($this->blockId > 0)
        {
            $bookedItemsSQL = "
                SELECT bop.item_sku
                FROM {$this->conf->getPrefix()}booking_options bop
                JOIN {$this->conf->getPrefix()}items it ON it.item_sku=bop.item_sku
                WHERE bop.booking_id='{$validBlockId}' AND it.partner_id='{$validPartnerId}'
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

    /**
     * Check weather this block has any assigned items or extras to it or not
     * @return bool
     */
    public function isEmpty()
    {
        $validBlockId = StaticValidator::getValidPositiveInteger($this->blockId, 0);

        $relatedRows = $this->conf->getInternalWPDB()->get_var("
            SELECT booking_id
            FROM {$this->conf->getPrefix()}booking_options
            WHERE booking_id='{$validBlockId}' AND blog_id='{$this->conf->getBlogId()}'
        ");
        // If no related elements found to this block
        if(!is_null($relatedRows))
        {
            return FALSE;
        } else
        {
            return TRUE;
        }
    }

    /**
     * Used as a initializer and data puller of existing block BEFORE search engine functions
     * @return mixed
     */
    public function getDetails($paramIncludeUnclassified = FALSE)
    {
        $ret = $this->getDataFromDatabaseById($this->blockId);
        if(!is_null($ret))
        {
            // Make raw
            $ret['location_code'] = stripslashes($ret['location_code']);
            $ret['block_name'] = stripslashes($ret['block_name']);

            if($ret['block_timestamp'] > 0)
            {
                $ret['block_date'] = date_i18n($this->shortDateFormat, $ret['block_timestamp'] + get_option('gmt_offset') * 3600, TRUE);
                $ret['block_time'] = date_i18n('H:i', $ret['block_timestamp'] + get_option('gmt_offset') * 3600, TRUE);
                $printBlockDate = date_i18n(get_option('date_format'), $ret['block_timestamp'] + get_option('gmt_offset') * 3600, TRUE);
                $printBlockTime = date_i18n(get_option('time_format'), $ret['block_timestamp'] + get_option('gmt_offset') * 3600, TRUE);
            } else
            {
                $ret['block_date'] = '';
                $ret['block_time'] = '';
                $printBlockDate = '';
                $printBlockTime = '';
            }

            if($ret['start_timestamp'] > 0)
            {
                $ret['start_date'] = date_i18n($this->shortDateFormat, $ret['start_timestamp'] + get_option('gmt_offset') * 3600, TRUE);
                $ret['start_time'] = date_i18n('H:i', $ret['start_timestamp'] + get_option('gmt_offset') * 3600, TRUE);
                $printStartDate = date_i18n(get_option('date_format'), $ret['start_timestamp'] + get_option('gmt_offset') * 3600, TRUE);
                $printStartTime = date_i18n(get_option('time_format'), $ret['start_timestamp'] + get_option('gmt_offset') * 3600, TRUE);
            } else
            {
                $ret['start_date'] = '';
                $ret['start_time'] = '';
                $printStartDate = '';
                $printStartTime = '';
            }

            if($ret['end_timestamp'] > 0)
            {
                $ret['end_date'] = date_i18n($this->shortDateFormat, $ret['end_timestamp'] + get_option('gmt_offset') * 3600, TRUE);
                $ret['end_time'] = date_i18n('H:i', $ret['end_timestamp'] + get_option('gmt_offset') * 3600, TRUE);
                $printEndDate = date_i18n(get_option('date_format'), $ret['end_timestamp'] + get_option('gmt_offset') * 3600, TRUE);
                $printEndTime = date_i18n(get_option('time_format'), $ret['end_timestamp'] + get_option('gmt_offset') * 3600, TRUE);
            } else
            {
                $ret['end_date'] = '';
                $ret['end_time'] = '';
                $printEndDate = '';
                $printEndTime = '';
            }

            $validBlockId = intval($ret['block_id']);
            $blockedItemsSQL = "
                SELECT bo.units_booked AS units_blocked,
                it.item_id, it.manufacturer_id, it.body_type_id, it.transmission_type_id, it.fuel_type_id
                FROM {$this->conf->getPrefix()}booking_options bo
                JOIN {$this->conf->getPrefix()}items it ON it.item_sku=bo.item_sku AND it.blog_id='{$ret['blog_id']}'
                WHERE booking_id='{$validBlockId}'
            ";
            $blockedExtrasSQL = "
                SELECT ex.extra_id, bo.units_booked AS units_blocked
                FROM {$this->conf->getPrefix()}booking_options bo
                JOIN {$this->conf->getPrefix()}extras ex ON ex.extra_sku=bo.extra_sku AND ex.blog_id='{$ret['blog_id']}'
                WHERE booking_id='{$validBlockId}'
            ";
            $blockedItems = $this->conf->getInternalWPDB()->get_results($blockedItemsSQL, ARRAY_A);
            $blockedExtras = $this->conf->getInternalWPDB()->get_results($blockedExtrasSQL, ARRAY_A);

            // Cars and Car Units
            $ret['item_ids'] = array();
            $ret['item_units'] = array();
            $ret['items'] = array();
            foreach($blockedItems AS $reservedItem)
            {
                $ret['item_ids'][] = $reservedItem['item_id'];
                $ret['item_units'][$reservedItem['item_id']] = $reservedItem['units_blocked'];
                $ret['items'][] = array(
                    "item_id" => $reservedItem['item_id'],
                    "manufacturer_id" => $reservedItem['manufacturer_id'],
                    "body_type_id" => $reservedItem['body_type_id'],
                    "transmission_type_id" => $reservedItem['transmission_type_id'],
                    "fuel_type_id" => $reservedItem['fuel_type_id'],
                    "units_blocked" => $reservedItem['units_blocked'],
                );
            }

            // Extras and Extra Units
            $ret['extra_ids'] = array();
            $ret['extra_units'] = array();
            $ret['extras'] = array();
            foreach($blockedExtras AS $reservedExtra)
            {
                $ret['extra_ids'][] = $reservedExtra['extra_id'];
                $ret['extra_units'][$reservedExtra['extra_id']] = $reservedExtra['units_blocked'];
                $ret['extras'][] = array(
                    "extra_id"      => $reservedExtra['extra_id'],
                    "units_blocked"  => $reservedExtra['units_blocked'],
                );
            }

            // Prepare output for print
            $ret['print_block_date'] = $printBlockDate;
            $ret['print_block_time'] = $printBlockTime;
            $ret['print_start_date'] = $printStartDate;
            $ret['print_start_time'] = $printStartTime;
            $ret['print_end_date'] = $printEndDate;
            $ret['print_end_time'] = $printEndTime;
            $ret['print_location_code'] = esc_html($ret['location_code']);
            $ret['print_block_name'] = esc_html($ret['block_name']);

            // Prepare output for edit
            $ret['edit_location_code'] = esc_attr($ret['location_code']); // for input field
            $ret['edit_block_name'] = esc_attr($ret['block_name']); // for input field
        }

        return $ret;
    }

    /**
     * Element-specific method
     * @param string $paramBlockName
     * @param string $paramLocationCode
     * @param int $paramPickupTimestamp
     * @param int $paramReturnTimestamp
     * @return int
     */
    public function save($paramBlockName, $paramLocationCode, $paramPickupTimestamp, $paramReturnTimestamp)
    {
        $sanitizedBlockName      	= sanitize_text_field($paramBlockName);
        $validBlockName      	    = esc_sql($sanitizedBlockName);
        $validPickupTimestamp       = StaticValidator::getValidPositiveInteger($paramPickupTimestamp, 0);
        $validReturnTimestamp       = StaticValidator::getValidPositiveInteger($paramReturnTimestamp, 0);
        $validLocationCode          = esc_sql(sanitize_text_field($paramLocationCode)); // for sql queries only

        // For blocks payments are always successful
        $sqlInsertQuery = "
          INSERT INTO {$this->conf->getPrefix()}bookings
          (
                booking_timestamp, pickup_timestamp, return_timestamp,
                pickup_location_code, return_location_code,
                customer_id, is_block, payment_successful, block_name, blog_id
          ) VALUES
          (
                '".time()."', '{$validPickupTimestamp}', '{$validReturnTimestamp}',
                '{$validLocationCode}', '{$validLocationCode}',
                '0', '1',  '1', '{$validBlockName}', '{$this->conf->getBlogId()}'
          )
        ";
        //echo "<br />[Insert: {$sqlInsertQuery}]";
        //die("<br />END");

        // DB INSERT
        $saved = $this->conf->getInternalWPDB()->query($sqlInsertQuery);

        if($saved !== FALSE)
        {
            // Set object id for future use
            $this->blockId = $this->conf->getInternalWPDB()->insert_id;
        }

        if($saved === FALSE || $saved === 0)
        {
            $this->errorMessages[] = $this->lang->getText('NRS_BLOCK_INSERT_ERROR_TEXT');
        } else
        {
            $this->okayMessages[] = $this->lang->getText('NRS_BLOCK_INSERTED_TEXT');
        }

        return $saved;
    }

    public function delete()
    {
        $validBlockId = StaticValidator::getValidPositiveInteger($this->blockId);
        $deleted = $this->conf->getInternalWPDB()->query("
            DELETE FROM {$this->conf->getPrefix()}bookings
            WHERE booking_id='{$validBlockId}' AND is_block='1' AND blog_id='{$this->conf->getBlogId()}'
        ");

        if($deleted === FALSE || $deleted === 0)
        {
            $this->errorMessages[] = $this->lang->getText('NRS_BLOCK_DELETE_ERROR_TEXT');
        } else
        {
            $this->okayMessages[] = $this->lang->getText('NRS_BLOCK_DELETED_TEXT');
        }

        return $deleted;
    }

    /**
     * Element-specific method
     * Delete all block options related with this block id
     */
    public function deleteAllOptions()
    {
        $validBookingId = StaticValidator::getValidPositiveInteger($this->blockId);
        $deleted = $this->conf->getInternalWPDB()->query("
            DELETE FROM {$this->conf->getPrefix()}booking_options
            WHERE booking_id='{$validBookingId}' AND is_block='1' AND blog_id='{$this->conf->getBlogId()}'
        ");

        if($deleted === FALSE || $deleted === 0)
        {
            $this->errorMessages[] = $this->lang->getText('NRS_BLOCK_DELETE_OPTIONS_ERROR_TEXT');
        } else
        {
            $this->okayMessages[] = $this->lang->getText('NRS_BLOCK_OPTIONS_DELETED_TEXT');
        }

        return $deleted;
    }
}