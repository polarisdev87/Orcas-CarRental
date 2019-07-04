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

class TransmissionTypesObserver implements iObserver
{
    protected $conf 	                = NULL;
    protected $lang 		            = NULL;
    protected $settings 		        = array();
    protected $debugMode 	            = 0;

    /**
     * TransmissionTypesObserver constructor.
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
            SELECT transmission_type_id
            FROM {$this->conf->getPrefix()}transmission_types
            WHERE blog_id='{$this->conf->getBlogId()}'
            ORDER BY transmission_type_title ASC
		";

        //DEBUG
        //echo nl2br($searchSQL)."<br /><br />";

        $searchResult = $this->conf->getInternalWPDB()->get_col($searchSQL);

        return $searchResult;
    }

    public function getTranslatedDropDownOptions($paramSelectedTransmissionTypeId = -1, $paramDefaultValue = -1, $paramDefaultLabel = "")
    {
        return $this->getDropDownOptions($paramSelectedTransmissionTypeId, $paramDefaultValue, $paramDefaultLabel, TRUE);
    }

    /**
     * Get Car transmission
     * @param int $paramSelectedTransmissionTypeId - for edit mode
     * @param int $paramDefaultValue
     * @param string $paramDefaultLabel
     * @param bool $paramTranslated
     * @return string transmissions drop-down html
     */
    public function getDropDownOptions($paramSelectedTransmissionTypeId = -1, $paramDefaultValue = -1, $paramDefaultLabel = "", $paramTranslated = FALSE)
    {
        $validDefaultValue = StaticValidator::getValidInteger($paramDefaultValue, -1);
        $sanitizedDefaultLabel = sanitize_text_field($paramDefaultLabel);
        $defaultSelected = $paramSelectedTransmissionTypeId == $validDefaultValue ? ' selected="selected"' : '';
        $transmissionTypeIds = $this->getAllIds();

        $transmissionTypesHTML = '';
        $transmissionTypesHTML .= '<option value="'.$validDefaultValue.'"'.$defaultSelected.'>'.$sanitizedDefaultLabel.'</option>';
        foreach($transmissionTypeIds AS $transmissionTypeId)
        {
            $objTransmissionType = new TransmissionType($this->conf, $this->lang, $this->settings, $transmissionTypeId);
            $transmissionTypeDetails = $objTransmissionType->getDetails();
            $transmissionTypeTitle = $paramTranslated ? $transmissionTypeDetails['translated_transmission_type_title'] : $transmissionTypeDetails['transmission_type_title'];
            $selected = $transmissionTypeDetails['transmission_type_id'] == $paramSelectedTransmissionTypeId ? ' selected="selected"' : '';

            $transmissionTypesHTML .= '<option value="'.$transmissionTypeDetails['transmission_type_id'].'"'.$selected.'>'.$transmissionTypeTitle.'</option>';
        }

        return $transmissionTypesHTML;
    }

    /*******************************************************************************/
    /********************** METHODS FOR ADMIN ACCESS ONLY **************************/
    /*******************************************************************************/

    public function getAdminList()
    {
        $transmissionTypesHtml = '';
        $transmissionTypeIds = $this->getAllIds();
        foreach ($transmissionTypeIds AS $transmissionTypeId)
        {
            $objTransmissionType = new TransmissionType($this->conf, $this->lang, $this->settings, $transmissionTypeId);
            $transmissionTypeDetails = $objTransmissionType->getDetails();

            $printTranslatedTransmissionTypeTitle = $transmissionTypeDetails['print_translated_transmission_type_title'];
            if($this->lang->canTranslateSQL())
            {
                $printTranslatedTransmissionTypeTitle .= '<br /><span class="not-translated" title="'.$this->lang->getText('NRS_ADMIN_WITHOUT_TRANSLATION_TEXT').'">('.$transmissionTypeDetails['print_transmission_type_title'].')</span>';
            }

            $transmissionTypesHtml .= '<tr>';
            $transmissionTypesHtml .= '<td style="width: 1%">'.$transmissionTypeId.'</td>';
            $transmissionTypesHtml .= '<td>'.$printTranslatedTransmissionTypeTitle.'</td>';
            $transmissionTypesHtml .= '<td align="right">';
            if(current_user_can('manage_'.$this->conf->getExtensionPrefix().'all_inventory'))
            {
                $transmissionTypesHtml .= '<a href="'.admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-transmission-type&amp;transmission_type_id='.$transmissionTypeId).'">'.$this->lang->getText('NRS_ADMIN_EDIT_TEXT').'</a> || ';
                $transmissionTypesHtml .= '<a href="javascript:;" onclick="javascript:delete'.$this->conf->getExtensionFolder().'TransmissionType(\''.$transmissionTypeId.'\')">'.$this->lang->getText('NRS_ADMIN_DELETE_TEXT').'</a>';
            } else
            {
                $transmissionTypesHtml .= '--';
            }
            $transmissionTypesHtml .= '</td>';
            $transmissionTypesHtml .= '</tr>';
        }

        return $transmissionTypesHtml;
    }
}