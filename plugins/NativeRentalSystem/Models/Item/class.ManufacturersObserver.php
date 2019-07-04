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

class ManufacturersObserver implements iObserver
{
    protected $conf 	                = NULL;
    protected $lang 		            = NULL;
    protected $settings		            = array();
    protected $debugMode 	            = 0;

    /**
     * ManufacturersObserver constructor.
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
            SELECT manufacturer_id
            FROM {$this->conf->getPrefix()}manufacturers
            WHERE blog_id='{$this->conf->getBlogId()}'
            ORDER BY manufacturer_title ASC
		";

        //DEBUG
        //echo nl2br($searchSQL)."<br /><br />";

        $searchResult = $this->conf->getInternalWPDB()->get_col($searchSQL);

        return $searchResult;
    }

    public function getTranslatedDropDownOptions($paramSelectedManufacturerId = -1, $paramDefaultValue = -1, $paramDefaultLabel = "")
    {
        return $this->getDropDownOptions($paramSelectedManufacturerId, $paramDefaultValue, $paramDefaultLabel, TRUE);
    }

    /**
     * @param int $paramSelectedManufacturerId
     * @param int $paramDefaultValue
     * @param string $paramDefaultLabel
     * @param bool $paramTranslated
     * @return string
     */
    public function getDropDownOptions($paramSelectedManufacturerId = -1, $paramDefaultValue = -1, $paramDefaultLabel = "", $paramTranslated = FALSE)
    {
        $validDefaultValue = StaticValidator::getValidInteger($paramDefaultValue, -1);
        $sanitizedDefaultLabel = sanitize_text_field($paramDefaultLabel);
        $defaultSelected = $paramSelectedManufacturerId == $validDefaultValue ? ' selected="selected"' : '';
        $manufacturerIds = $this->getAllIds();

        $manufacturersHTML = '';
        $manufacturersHTML .= '<option value="'.$validDefaultValue.'"'.$defaultSelected.'>'.$sanitizedDefaultLabel.'</option>';
        foreach($manufacturerIds AS $manufacturerId)
        {
            $objManufacturer = new Manufacturer($this->conf, $this->lang, $this->settings, $manufacturerId);
            $manufacturerDetails = $objManufacturer->getDetails();
            $printManufacturerTitle = $paramTranslated ? $manufacturerDetails['print_translated_manufacturer_title'] : $manufacturerDetails['print_manufacturer_title'];
            $selected = $manufacturerDetails['manufacturer_id'] == $paramSelectedManufacturerId ? ' selected="selected"' : '';

            $manufacturersHTML .= '<option value="'.$manufacturerDetails['manufacturer_id'].'"'.$selected.'>'.$printManufacturerTitle.'</option>';
        }

        return $manufacturersHTML;
    }

    /*******************************************************************************/
    /********************** METHODS FOR ADMIN ACCESS ONLY **************************/
    /*******************************************************************************/

    public function getAdminList()
    {
        $manufacturersHTML = '';
        $manufacturerIds = $this->getAllIds();
        foreach ($manufacturerIds AS $manufacturerId)
        {
            $objManufacturer = new Manufacturer($this->conf, $this->lang, $this->settings, $manufacturerId);
            $manufacturerDetails = $objManufacturer->getDetails();

            $printTranslatedManufacturerTitle = $manufacturerDetails['print_translated_manufacturer_title'];
            if($this->lang->canTranslateSQL())
            {
                $printTranslatedManufacturerTitle .= '<br /><span class="not-translated" title="'.$this->lang->getText('NRS_ADMIN_WITHOUT_TRANSLATION_TEXT').'">('.$manufacturerDetails['print_manufacturer_title'].')</span>';
            }

            $manufacturersHTML .= '<tr>';
            $manufacturersHTML .= '<td style="width: 1%">'.$manufacturerId.'</td>';
            $manufacturersHTML .= '<td>'.$printTranslatedManufacturerTitle.'</td>';
            $manufacturersHTML .= '<td align="right">';
            if(current_user_can('manage_'.$this->conf->getExtensionPrefix().'all_inventory'))
            {
                $manufacturersHTML .= '<a href="'.admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-manufacturer&amp;manufacturer_id='.$manufacturerId).'">'.$this->lang->getText('NRS_ADMIN_EDIT_TEXT').'</a> || ';
                $manufacturersHTML .= '<a href="javascript:;" onclick="javascript:delete'.$this->conf->getExtensionFolder().'Manufacturer(\''.$manufacturerId.'\')">'.$this->lang->getText('NRS_ADMIN_DELETE_TEXT').'</a>';
            } else
            {
                $manufacturersHTML .= '--';
            }
            $manufacturersHTML .= '</td>';
            $manufacturersHTML .= '</tr>';
        }

        return  $manufacturersHTML;
    }
}