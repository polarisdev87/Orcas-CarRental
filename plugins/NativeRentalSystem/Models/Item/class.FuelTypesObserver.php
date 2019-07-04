<?php
/**
 * NRS Items Observer (no setup for single item)

 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Item;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\iObserver;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Validation\StaticValidator;

class FuelTypesObserver implements iObserver
{
    protected $conf 	                = NULL;
    protected $lang 		            = NULL;
    protected $settings		            = array();
    protected $debugMode 	            = 0;

    /**
     * FuelTypesObserver constructor.
     * @param ExtensionConfiguration $paramConf
     * @param Language $paramLang
     * @param array $paramSettings
     */
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;
        // Set saved settings
        $this->settings = $paramSettings;
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    public function getAllIds()
    {
        $searchSQL = "
            SELECT fuel_type_id
            FROM {$this->conf->getPrefix()}fuel_types
            WHERE blog_id='{$this->conf->getBlogId()}'
            ORDER BY fuel_type_title ASC
		";

        //DEBUG
        //echo nl2br($searchSQL)."<br /><br />";

        $searchResult = $this->conf->getInternalWPDB()->get_col($searchSQL);

        return $searchResult;
    }

    public function getTranslatedDropDownOptions($paramSelectedFuelTypeId = -1, $paramValueForAll = -1, $selectLabel = "")
    {
        return $this->getDropDownOptions($paramSelectedFuelTypeId, $paramValueForAll, $selectLabel, TRUE);
    }

    /**
     * Get item fuel type - petrol, diesel etc.
     * @param int $paramSelectedFuelTypeId - for edit mode
     * @param int $paramDefaultValue
     * @param string $paramDefaultLabel
     * @param bool $paramTranslated
     * @return string type drop-down html
     */
    public function getDropDownOptions($paramSelectedFuelTypeId = -1, $paramDefaultValue = -1, $paramDefaultLabel = "", $paramTranslated = FALSE)
    {
        $validDefaultValue = StaticValidator::getValidInteger($paramDefaultValue, -1);
        $sanitizedDefaultLabel = sanitize_text_field($paramDefaultLabel);
        $defaultSelected = $paramSelectedFuelTypeId == $validDefaultValue ? ' selected="selected"' : '';
        $fuelTypeIds = $this->getAllIds();

        $fuelTypesHTML = '';
        $fuelTypesHTML .= '<option value="'.$validDefaultValue.'"'.$defaultSelected.'>'.$sanitizedDefaultLabel.'</option>';
        foreach($fuelTypeIds AS $fuelTypeId)
        {
            $objFuelType = new FuelType($this->conf, $this->lang, $this->settings, $fuelTypeId);
            $fuelTypeDetails = $objFuelType->getDetails();
            $fuelTypeTitle = $paramTranslated ? $fuelTypeDetails['translated_fuel_type_title'] : $fuelTypeDetails['fuel_type_title'];
            $selected = $fuelTypeDetails['fuel_type_id'] == $paramSelectedFuelTypeId ? ' selected="selected"' : '';

            $fuelTypesHTML .= '<option value="'.$fuelTypeDetails['fuel_type_id'].'"'.$selected.'>'.$fuelTypeTitle.'</option>';
        }

        return $fuelTypesHTML;
    }

    /*******************************************************************************/
    /********************** METHODS FOR ADMIN ACCESS ONLY **************************/
    /*******************************************************************************/

    public function getAdminList()
    {
        $fuelTypesHTML = '';
        $fuelTypeIds = $this->getAllIds();
        foreach ($fuelTypeIds AS $fuelTypeId)
        {
            $objFuelType = new FuelType($this->conf, $this->lang, $this->settings, $fuelTypeId);
            $fuelTypeDetails = $objFuelType->getDetails();

            $printTranslatedFuelTypeTitle = $fuelTypeDetails['print_fuel_type_title'];
            if($this->lang->canTranslateSQL())
            {
                $printTranslatedFuelTypeTitle .= '<br /><span class="not-translated" title="'.$this->lang->getText('NRS_ADMIN_WITHOUT_TRANSLATION_TEXT').'">('.$fuelTypeDetails['print_fuel_type_title'].')</span>';
            }

            $fuelTypesHTML .= '<tr>';
            $fuelTypesHTML .= '<td style="width: 1%">'.$fuelTypeId.'</td>';
            $fuelTypesHTML .= '<td>'.$printTranslatedFuelTypeTitle.'</td>';
            $fuelTypesHTML .= '<td align="right">';
            if(current_user_can('manage_'.$this->conf->getExtensionPrefix().'all_inventory'))
            {
                $fuelTypesHTML .= '<a href="'.admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-fuel-type&amp;fuel_type_id='.$fuelTypeId).'">'.$this->lang->getText('NRS_ADMIN_EDIT_TEXT').'</a> || ';
                $fuelTypesHTML .= '<a href="javascript:;" onclick="javascript:delete'.$this->conf->getExtensionFolder().'FuelType(\''.$fuelTypeId.'\')">'.$this->lang->getText('NRS_ADMIN_DELETE_TEXT').'</a>';
            } else
            {
                $fuelTypesHTML .= '--';
            }
            $fuelTypesHTML .= '</td>';
            $fuelTypesHTML .= '</tr>';
        }

        return $fuelTypesHTML;
    }
}