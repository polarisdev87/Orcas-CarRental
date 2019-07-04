<?php
/**
 * Prepayment processor. Used in administration side only

 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Tax;
use NativeRentalSystem\Models\AbstractElement;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Formatting\StaticFormatter;
use NativeRentalSystem\Models\iElement;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;

class Tax extends AbstractElement implements iElement
{
    protected $conf 	    = NULL;
    protected $lang 		= NULL;
    protected $debugMode 	= 0;
    protected $taxId        = 0;

    protected $currencySymbol		    = '$';
    protected $currencyCode			    = 'USD';
    protected $currencySymbolLocation	= 0;

    /**
     * Tax constructor.
     * @param ExtensionConfiguration &$paramConf
     * @param Language &$paramLang
     * @param array $paramSettings - not used
     * @param array $paramTaxId
     */
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings, $paramTaxId)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;

        // Set tax id
        $this->taxId = StaticValidator::getValidPositiveInteger($paramTaxId, 0);

        $this->currencySymbol = StaticValidator::getValidSetting($paramSettings, 'conf_currency_symbol', "textval", "$");
        $this->currencyCode = StaticValidator::getValidSetting($paramSettings, 'conf_currency_code', "textval", "USD");
        $this->currencySymbolLocation = StaticValidator::getValidSetting($paramSettings, 'conf_currency_symbol_location', 'positive_integer', 0, array(0, 1));
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    public function getId()
    {
        return $this->taxId;
    }

    /**
     * For internal class use only
     * @param $paramTaxId
     * @return mixed
     */
    private function getDataFromDatabaseById($paramTaxId)
    {
        $validTaxId = StaticValidator::getValidPositiveInteger($paramTaxId, 0);
        $sqlData = "
            SELECT *
            FROM {$this->conf->getPrefix()}taxes
            WHERE tax_id='{$validTaxId}'
        ";

        $taxData = $this->conf->getInternalWPDB()->get_row($sqlData, ARRAY_A);

        return $taxData;
    }

    /**
     * @param bool $paramIncludeUnclassified - not used
     * @return mixed
     */
    public function getDetails($paramIncludeUnclassified = FALSE)
    {
        return $this->getAllDetails(FALSE);
    }

    public function getDetailsWithAmountForPrice($paramPrice)
    {
        return $this->getAllDetails(TRUE, $paramPrice);
    }

    private function getAllDetails($paramWithAmount = FALSE, $paramPrice = 0.00)
    {
        $ret = $this->getDataFromDatabaseById($this->taxId);

        if(!is_null($ret))
        {
            // Make raw
            $ret['tax_name'] = stripslashes($ret['tax_name']);

            // Process new fields
            $ret['translated_tax_name'] = $this->lang->getTranslated("ta{$ret['tax_id']}_tax_name", $ret['tax_name']);

            // Prepare output for print
            $ret['print_tax_name'] = esc_html($ret['tax_name']);
            $ret['print_translated_tax_name'] = esc_html($ret['translated_tax_name']);
            $ret['print_tax_percentage'] =  StaticFormatter::getFormattedPercentage($ret['tax_percentage'], "regular");

            if($paramWithAmount === TRUE)
            {
                $ret['tax_amount'] = floatval($paramPrice) * ($ret['tax_percentage'] / 100);
                $ret['print_tax_amount'] = StaticFormatter::getFormattedPrice(
                    $ret['tax_amount'], "regular", $this->currencySymbol, $this->currencyCode, $this->currencySymbolLocation
                );
            }

            // Prepare output for edit
            $ret['edit_tax_name'] = esc_attr($ret['tax_name']); // for input field
        }

        return $ret;
    }

    /**
     * @note - Always use blog_id for save (insert / update) and delete, to avoid access rights violation
     * @return bool|false|int
     */
    public function save()
    {
        $ok = TRUE;
        $saved = FALSE;
        $validTaxId = StaticValidator::getValidPositiveInteger($this->taxId, 0);
        $sanitizedTaxName = isset($_POST['tax_name']) ? sanitize_text_field($_POST['tax_name']) : '';
        $validTaxName = esc_sql($sanitizedTaxName); // for sql query only
        $validLocationId = isset($_POST['location_id']) ? StaticValidator::getValidPositiveInteger($_POST['location_id'], 0) : 0;
        $validLocationType = isset($_POST['location_type']) && $_POST['location_type'] == 1 ? 1 : 2;
        $validTaxPercentage = isset($_POST['tax_percentage']) && $_POST['tax_percentage'] >= 0.00 ? floatval($_POST['tax_percentage']) : 0.00;

        // Do not allow to have prepayments more than 100%
        if($validTaxPercentage > 100)
        {
            $validTaxPercentage = 100;
        }

        if($validTaxId > 0 && $ok)
        {
            $saved = $this->conf->getInternalWPDB()->query("
                UPDATE {$this->conf->getPrefix()}taxes SET
                tax_name='{$validTaxName}', location_id='{$validLocationId}', location_type='{$validLocationType}',
                tax_percentage='{$validTaxPercentage}'
                WHERE tax_id='{$validTaxId}' AND blog_id='{$this->conf->getBlogId()}'
            ");
            if($saved === FALSE)
            {
                $this->errorMessages[] = $this->lang->getText('NRS_TAX_UPDATE_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('NRS_TAX_UPDATED_TEXT');
            }
        } else if($ok)
        {
            $saved = $this->conf->getInternalWPDB()->query("
                INSERT INTO {$this->conf->getPrefix()}taxes
                (
                    tax_name, location_id, location_type, tax_percentage, blog_id
                ) VALUES
                (
                    '{$validTaxName}', '{$validLocationId}', '{$validLocationType}', '{$validTaxPercentage}', '{$this->conf->getBlogId()}'
                )
            ");

            if($saved)
            {
                // Update object id with newly inserted tax id for future work
                $this->taxId = $this->conf->getInternalWPDB()->insert_id;
            }

            if($saved === FALSE || $saved === 0)
            {
                $this->errorMessages[] = $this->lang->getText('NRS_TAX_INSERT_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('NRS_TAX_INSERTED_TEXT');
            }
        }

        return $saved;
    }

    public function registerForTranslation()
    {
        $taxDetails = $this->getDetails();
        if(!is_null($taxDetails))
        {
            $this->lang->register("ta{$this->taxId}_tax_name", $taxDetails['tax_name']);
            $this->okayMessages[] = $this->lang->getText('NRS_TAX_REGISTERED_TEXT');
        }
    }

    public function delete()
    {
        $validTaxId = StaticValidator::getValidPositiveInteger($this->taxId, 0);
        $deleted = $this->conf->getInternalWPDB()->query("
          DELETE FROM {$this->conf->getPrefix()}taxes
          WHERE tax_id='{$validTaxId}' AND blog_id='{$this->conf->getBlogId()}'
        ");

        if($deleted === FALSE || $deleted === 0)
        {
            $this->errorMessages[] = $this->lang->getText('NRS_TAX_DELETE_ERROR_TEXT');
        } else
        {
            $this->okayMessages[] = $this->lang->getText('NRS_TAX_DELETED_TEXT');
        }

        return $deleted;
    }
}