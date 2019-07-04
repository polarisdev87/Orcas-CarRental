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

class ExtraBookingOption extends AbstractElement
{
    private $conf 	                = NULL;
    private $lang 		            = NULL;
    private $debugMode 	            = 0;
    private $extraSKU               = "";
    private $bookingId              = 0;

    /**
     * Item constructor.
     * @param ExtensionConfiguration &$paramConf
     * @param Language &$paramLang
     * @param array $paramSettings
     * @param $paramExtraSKU
     * @param $paramBookingId
     */
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings, $paramExtraSKU, $paramBookingId)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;

        $this->extraSKU = sanitize_text_field($paramExtraSKU);
        $this->bookingId = StaticValidator::getValidPositiveInteger($paramBookingId, 0);
    }

    /**
     * @param $paramBookingId
     * @param $paramExtraSKU
     * @return array|null
     */
    private function getDataFromDatabaseById($paramBookingId, $paramExtraSKU)
    {
        $validExtraSKU = esc_sql(sanitize_text_field($paramExtraSKU)); // for sql queries only
        $validBookingId = StaticValidator::getValidPositiveInteger($paramBookingId, 0);
        $row = $this->conf->getInternalWPDB()->get_row("
            SELECT *
            FROM {$this->conf->getPrefix()}booking_options
            WHERE booking_id='{$validBookingId}' AND extra_sku='{$validExtraSKU}'
        ", ARRAY_A);

        return $row;
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    public function getDetails($paramIncludeUnclassified = FALSE)
    {
        $ret = $this->getDataFromDatabaseById($this->bookingId, $this->extraSKU);
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
        $validExtraSKU      = esc_sql(sanitize_text_field($this->extraSKU)); // for sql queries only
        $validBookingId     = StaticValidator::getValidPositiveInteger($this->bookingId, 0);
        $validOptionId      = StaticValidator::getValidPositiveInteger($paramOptionId, 0);
        $validQuantity      = StaticValidator::getValidInteger($paramQuantity, -1); // Block for all (-1) is supported here

        if($validBookingId == 0 || $validExtraSKU == '' || $validQuantity == 0)
        {
            $ok = FALSE;
            $this->errorMessages[] = sprintf($this->lang->getText('NRS_EXTRA_BOOKING_ID_QUANTITY_OPTION_SKU_ERROR_TEXT'), $validBookingId, $validExtraSKU, $validQuantity);
        }

        if($ok)
        {
            // -1 units_booked means - all car units of that car been blocked
            $sqlInsertQuery = "
                INSERT INTO {$this->conf->getPrefix()}booking_options
                (
                    booking_id, item_sku, extra_sku, option_id, units_booked, blog_id
                ) VALUES
                (
                    '{$validBookingId}', '', '{$validExtraSKU}', '{$validOptionId}', '{$validQuantity}', '{$this->conf->getBlogId()}'
                )
            ";
            //echo "<br />[Extra Booking Option Insert: {$sqlInsertQuery}]";
            //die("<br />END");

            // DB INSERT
            $saved = $this->conf->getInternalWPDB()->query($sqlInsertQuery);

            if($saved === FALSE || $saved === 0)
            {
                $this->errorMessages[] = sprintf($this->lang->getText('NRS_EXTRA_BOOKING_OPTION_INSERT_ERROR_TEXT'), $validExtraSKU);
            } else
            {
                $this->okayMessages[] = sprintf($this->lang->getText('NRS_EXTRA_BOOKING_OPTION_INSERTED_TEXT'), $validExtraSKU);
            }
        }

        return $saved;
    }

    public function delete()
    {
        $validExtraSKU      = esc_sql(sanitize_text_field($this->extraSKU)); // for sql queries only
        $validBookingId     = StaticValidator::getValidPositiveInteger($this->bookingId, 0);

        $deleted = $this->conf->getInternalWPDB()->query("
            DELETE FROM {$this->conf->getPrefix()}booking_options
            WHERE booking_id='{$validBookingId}' AND extra_sku='{$validExtraSKU}' AND blog_id='{$this->conf->getBlogId()}'
        ");

        if($deleted === FALSE || $deleted === 0)
        {
            $this->errorMessages[] = $this->lang->getText('NRS_EXTRA_BOOKING_OPTION_DELETE_ERROR_TEXT');
        } else
        {
            $this->okayMessages[] = $this->lang->getText('NRS_EXTRA_BOOKING_OPTION_DELETED_TEXT');
        }

        return $deleted;
    }
}