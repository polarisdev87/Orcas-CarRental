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

class BodyTypesObserver implements iObserver
{
    protected $conf 	                = NULL;
    protected $lang 		            = NULL;
    protected $settings		            = array();
    protected $debugMode 	            = 0;

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

    public function getAllIds($includeUnclassified = FALSE)
    {
        $searchSQL = "
            SELECT body_type_id
            FROM {$this->conf->getPrefix()}body_types
            WHERE blog_id='{$this->conf->getBlogId()}'
            ORDER BY body_type_order ASC, body_type_title ASC
		";

        //DEBUG
        //echo nl2br($searchSQL)."<br /><br />";

        $searchResult = $this->conf->getInternalWPDB()->get_col($searchSQL);

        // Add unclassified types results
        if($includeUnclassified)
        {
            $searchResult[] = 0;
        }

        return $searchResult;
    }

    public function getTranslatedDropDownOptions($paramSelectedBodyTypeId = -1, $paramDefaultValue = -1, $paramDefaultLabel = "")
    {
        return $this->getDropDownOptions($paramSelectedBodyTypeId, $paramDefaultValue, $paramDefaultLabel, TRUE);
    }

    /**
     * Get car body type - sedan, compact, jeep etc.
     * @param int $paramSelectedBodyTypeId - for edit mode
     * @param int $paramDefaultValue
     * @param string $paramDefaultLabel
     * @param bool $paramTranslated
     * @return string type drop-down html
     */
    public function getDropDownOptions($paramSelectedBodyTypeId = -1, $paramDefaultValue = -1, $paramDefaultLabel = "", $paramTranslated = FALSE)
    {

        $validDefaultValue = StaticValidator::getValidInteger($paramDefaultValue, -1);
        $sanitizedDefaultLabel = sanitize_text_field($paramDefaultLabel);
        $defaultSelected = $paramSelectedBodyTypeId == $validDefaultValue ? ' selected="selected"' : '';

        $bodyTypesHTML = '';
        $bodyTypesHTML .= '<option value="'.$validDefaultValue.'"'.$defaultSelected.'>'.$sanitizedDefaultLabel.'</option>';
        $bodyTypeIds = $this->getAllIds();
        foreach($bodyTypeIds AS $bodyTypeId)
        {
            $objBodyType = new BodyType($this->conf, $this->lang, $this->settings, $bodyTypeId);
            $bodyTypeDetails = $objBodyType->getDetails();
            $bodyTypeTitle = $paramTranslated ? $bodyTypeDetails['translated_body_type_title'] : $bodyTypeDetails['body_type_title'];
            $selected = $bodyTypeDetails['body_type_id'] == $paramSelectedBodyTypeId ? ' selected="selected"' : '';

            $bodyTypesHTML .= '<option value="'.$bodyTypeDetails['body_type_id'].'"'.$selected.'>'.$bodyTypeTitle.'</option>';
        }

        return $bodyTypesHTML;
    }

    /*******************************************************************************/
    /********************** METHODS FOR ADMIN ACCESS ONLY **************************/
    /*******************************************************************************/

    public function getAdminList()
    {
        $bodyTypesHTML = '';
        $bodyTypeIds = $this->getAllIds();
        foreach ($bodyTypeIds AS $bodyTypeId)
        {
            $objBodyType = new BodyType($this->conf, $this->lang, $this->settings, $bodyTypeId);
            $bodyTypeDetails = $objBodyType->getDetails();

            $printTranslatedBodyTypeTitle = $bodyTypeDetails['print_translated_body_type_title'];
            if($this->lang->canTranslateSQL())
            {
                $printTranslatedBodyTypeTitle .= '<br /><span class="not-translated" title="'.$this->lang->getText('NRS_ADMIN_WITHOUT_TRANSLATION_TEXT').'">('.$bodyTypeDetails['print_body_type_title'].')</span>';
            }

            $bodyTypesHTML .= '<tr>';
            $bodyTypesHTML .= '<td>'.$bodyTypeId.'</td>';
            $bodyTypesHTML .= '<td>'.$printTranslatedBodyTypeTitle.'</td>';
            $bodyTypesHTML .= '<td style="text-align: center">'.$bodyTypeDetails['body_type_order'].'</td>';
            $bodyTypesHTML .= '<td align="right">';
            if(current_user_can('manage_'.$this->conf->getExtensionPrefix().'all_inventory'))
            {
                $bodyTypesHTML .= '<a href="'.admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-body-type&amp;body_type_id='.$bodyTypeId).'">'.$this->lang->getText('NRS_ADMIN_EDIT_TEXT').'</a> || ';
                $bodyTypesHTML .= '<a href="javascript:;" onclick="javascript:delete'.$this->conf->getExtensionFolder().'BodyType(\''.$bodyTypeId.'\')">'.$this->lang->getText('NRS_ADMIN_DELETE_TEXT').'</a>';
            } else
            {
                $bodyTypesHTML .= '--';
            }
            $bodyTypesHTML .= '</td>';
            $bodyTypesHTML .= '</tr>';
        }

        return $bodyTypesHTML;
    }
}