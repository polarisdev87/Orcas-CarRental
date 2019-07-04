<?php
/**
 * NRS Items Observer (no setup for single item)

 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Item;
use NativeRentalSystem\Models\AbstractElement;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\iElement;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;

class FuelType extends AbstractElement implements iElement
{
    protected $conf 	                = NULL;
    protected $lang 		            = NULL;
    protected $debugMode 	            = 0;
    protected $fuelTypeId               = 0;

    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings, $paramFuelTypeId)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;
        $this->fuelTypeId = StaticValidator::getValidValue($paramFuelTypeId, 'positive_integer', 0);
    }

    /**
     * @param $paramFuelTypeId
     * @return mixed
     */
    private function getDataFromDatabaseById($paramFuelTypeId)
    {
        $validFuelTypeId = StaticValidator::getValidPositiveInteger($paramFuelTypeId, 0);

        $retData = $this->conf->getInternalWPDB()->get_row("
            SELECT *
            FROM {$this->conf->getPrefix()}fuel_types
            WHERE fuel_type_id='{$validFuelTypeId}'
        ", ARRAY_A);

        return $retData;
    }

    public function getId()
    {
        return $this->fuelTypeId;
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    /**
     * @note Do not translate title here - it is used for editing
     * @param bool $paramIncludeUnclassified - not used
     * @return mixed
     */
    public function getDetails($paramIncludeUnclassified = FALSE)
    {
        $ret = $this->getDataFromDatabaseById($this->fuelTypeId);
        if(!is_null($ret))
        {
            // Make raw
            $ret['fuel_type_title'] = stripslashes($ret['fuel_type_title']);

            // Retrieve translation
            $ret['translated_fuel_type_title'] = $this->lang->getTranslated("ft{$ret['fuel_type_id']}_fuel_type_title", $ret['fuel_type_title']);

            // Prepare output for print
            $ret['print_fuel_type_title'] = esc_html($ret['fuel_type_title']);
            $ret['print_translated_fuel_type_title'] = esc_html($ret['translated_fuel_type_title']);

            // Prepare output for edit
            $ret['edit_fuel_type_title'] = esc_attr($ret['fuel_type_title']); // for input field
        }

        return $ret;
    }

    /**
     * @return bool|false|int
     */
    public function save()
    {
        $saved = FALSE;
        $ok = TRUE;
        $validFuelTypeId = StaticValidator::getValidPositiveInteger($this->fuelTypeId, 0);
        $sanitizedFuelTypeTitle = sanitize_text_field($_POST['fuel_type_title']);
        $validFuelTypeTitle = esc_sql($sanitizedFuelTypeTitle);

        $titleExistsQuery = "
            SELECT fuel_type_id
            FROM {$this->conf->getPrefix()}fuel_types
            WHERE fuel_type_title='{$validFuelTypeTitle}' AND fuel_type_id!='{$validFuelTypeId}'
            AND blog_id='{$this->conf->getBlogId()}'
        ";
        $titleExists = $this->conf->getInternalWPDB()->get_row($titleExistsQuery, ARRAY_A);

        if(!is_null($titleExists))
        {
            $ok = FALSE;
            $this->errorMessages[] = $this->lang->getText('NRS_FUEL_TYPE_TITLE_EXISTS_ERROR_TEXT');
        }

        if($validFuelTypeId > 0 && $ok)
        {
            $saved = $this->conf->getInternalWPDB()->query("
                  UPDATE {$this->conf->getPrefix()}fuel_types SET
                  fuel_type_title='{$validFuelTypeTitle}'
                  WHERE fuel_type_id='{$validFuelTypeId}' AND blog_id='{$this->conf->getBlogId()}'
            ");

            if($saved === FALSE)
            {
                $this->errorMessages[] = $this->lang->getText('NRS_FUEL_TYPE_UPDATE_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('NRS_FUEL_TYPE_UPDATED_TEXT');
            }

        } else if($ok)
        {
            $saved = $this->conf->getInternalWPDB()->query("
                INSERT INTO {$this->conf->getPrefix()}fuel_types
                (fuel_type_title, blog_id)
                VALUES
                ('{$validFuelTypeTitle}', '{$this->conf->getBlogId()}')
            ");

            if($saved)
            {
                // Update object id with newly inserted id for future work
                $this->fuelTypeId = $this->conf->getInternalWPDB()->insert_id;
            }

            if($saved === FALSE || $saved === 0)
            {
                $this->errorMessages[] = $this->lang->getText('NRS_FUEL_TYPE_INSERT_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('NRS_FUEL_TYPE_INSERTED_TEXT');
            }
        }

        return $saved;
    }

    public function registerForTranslation()
    {
        $fuelTypeDetails = $this->getDetails();
        if(!is_null($fuelTypeDetails))
        {
            $this->lang->register("ft{$this->fuelTypeId}_fuel_type_title", $fuelTypeDetails['fuel_type_title']);
            $this->okayMessages[] = $this->lang->getText('NRS_FUEL_TYPE_REGISTERED_TEXT');
        }
    }

    /**
     * @return false|int
     */
    public function delete()
    {
        $validFuelTypeId = StaticValidator::getValidPositiveInteger($this->fuelTypeId, 0);
        $deleted = $this->conf->getInternalWPDB()->query("
            DELETE FROM {$this->conf->getPrefix()}fuel_types
            WHERE fuel_type_id='{$validFuelTypeId}' AND blog_id='{$this->conf->getBlogId()}'
        ");

        if($deleted === FALSE || $deleted === 0)
        {
            $this->errorMessages[] = $this->lang->getText('NRS_FUEL_TYPE_DELETE_ERROR_TEXT');
        } else
        {
            $this->okayMessages[] = $this->lang->getText('NRS_FUEL_TYPE_DELETED_TEXT');
        }

        return $deleted;
    }
}