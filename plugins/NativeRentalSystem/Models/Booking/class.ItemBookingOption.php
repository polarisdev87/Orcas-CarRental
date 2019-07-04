<?php
/**
 * Item Processor

 * @package NRS
 * @uses NRSDepositManager, NRSDiscountManager, NRSPrepaymentManager
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Booking;
use NativeRentalSystem\Models\AbstractElement;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;

class ItemBookingOption extends AbstractElement
{
    private $conf 	                = NULL;
    private $lang 		            = NULL;
    private $debugMode 	            = 0;
    private $itemSKU                = "";
    private $bookingId              = 0;

    /**
     * Item constructor.
     * @param ExtensionConfiguration &$paramConf
     * @param Language &$paramLang
     * @param array $paramOptionalSettings
     * @param $paramItemSKU
     * @param $paramBookingId
     */
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramOptionalSettings, $paramItemSKU, $paramBookingId)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;

        $this->itemSKU = sanitize_text_field($paramItemSKU);
        $this->bookingId = StaticValidator::getValidPositiveInteger($paramBookingId, 0);
    }

    /**
     * @param $paramBookingId
     * @param $paramItemSKU
     * @return array|null
     */
    private function getDataFromDatabaseById($paramBookingId, $paramItemSKU)
    {
        $validItemSKU = esc_sql(sanitize_text_field($paramItemSKU)); // for sql queries only
        $validBookingId = StaticValidator::getValidPositiveInteger($paramBookingId, 0);
        $row = $this->conf->getInternalWPDB()->get_row("
            SELECT *
            FROM {$this->conf->getPrefix()}booking_options
            WHERE booking_id='{$validBookingId}' AND item_sku='{$validItemSKU}'
        ", ARRAY_A);

        return $row;
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    public function getDetails($paramIncludeUnclassified = FALSE)
    {
        $ret = $this->getDataFromDatabaseById($this->bookingId, $this->itemSKU);
        return $ret;
    }

    /**
     * @param int $paramOptionId - for no option/default use 0
     * @param int $paramQuantity - for all use -1
     * @return false|int
     */
    public function save($paramOptionId = 0, $paramQuantity = -1)
    {
        $ok = TRUE;
        $saved = FALSE;

        $validItemSKU       = esc_sql(sanitize_text_field($this->itemSKU)); // for sql queries only
        $validBookingId     = StaticValidator::getValidPositiveInteger($this->bookingId, 0);
        $validOptionId      = StaticValidator::getValidPositiveInteger($paramOptionId, 0);
        $validQuantity      = StaticValidator::getValidInteger($paramQuantity, -1);

        if($validBookingId == 0 || $validItemSKU == '' || $validQuantity == 0)
        {
            $ok = FALSE;
            $this->errorMessages[] = sprintf($this->lang->getText('NRS_ITEM_BOOKING_ID_QUANTITY_OPTION_SKU_ERROR_TEXT'), $validBookingId, $validItemSKU, $validQuantity);
        }

        if($ok)
        {
            // -1 units_booked means - all units of that item been blocked
            $sqlInsertQuery = "
                INSERT INTO {$this->conf->getPrefix()}booking_options
                (
                    booking_id, item_sku, extra_sku, option_id, units_booked, blog_id
                ) VALUES
                (
                    '{$validBookingId}', '{$validItemSKU}', '', '{$validOptionId}', '{$validQuantity}', '{$this->conf->getBlogId()}'
                )
            ";
            //echo "<br />[Item Booking Option Insert: {$sqlInsertQuery}]";
            //die("<br />END");
            //

            // DB INSERT
            $saved = $this->conf->getInternalWPDB()->query($sqlInsertQuery);

            if($saved === FALSE || $saved === 0)
            {
                $this->errorMessages[] = sprintf($this->lang->getText('NRS_ITEM_BOOKING_OPTION_INSERT_ERROR_TEXT'), $validItemSKU);
            } else
            {
                $this->okayMessages[] = sprintf($this->lang->getText('NRS_ITEM_BOOKING_OPTION_INSERTED_TEXT'), $validItemSKU);
            }
        }

        return $saved;
    }

    public function delete()
    {
        $validItemSKU       = esc_sql(sanitize_text_field($this->itemSKU)); // for sql queries only
        $validBookingId     = StaticValidator::getValidPositiveInteger($this->bookingId, 0);

        $deleted =  $this->conf->getInternalWPDB()->query("
            DELETE FROM {$this->conf->getPrefix()}booking_options
            WHERE booking_id='{$validBookingId}' AND item_sku='{$validItemSKU}' AND blog_id='{$this->conf->getBlogId()}'
        ");

        if($deleted === FALSE || $deleted === 0)
        {
            $this->errorMessages[] = $this->lang->getText('NRS_ITEM_BOOKING_OPTION_DELETE_ERROR_TEXT');
        } else
        {
            $this->okayMessages[] = $this->lang->getText('NRS_ITEM_BOOKING_OPTION_DELETED_TEXT');
        }

        return $deleted;
    }
}