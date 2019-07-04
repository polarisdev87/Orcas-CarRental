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

class FeaturesObserver implements iObserver
{
    protected $conf 	                = NULL;
    protected $lang 		            = NULL;
    protected $settings		            = array();
    protected $debugMode 	            = 0;

    /**
     * FeaturesObserver constructor.
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
            SELECT feature_id
            FROM {$this->conf->getPrefix()}features
            WHERE blog_id='{$this->conf->getBlogId()}'
            ORDER BY feature_title ASC
		";

        //DEBUG
        //echo nl2br($searchSQL)."<br /><br />";

        $searchResult = $this->conf->getInternalWPDB()->get_col($searchSQL);

        return $searchResult;
    }

    public function getFeatureCheckboxesByItemId($paramItemId)
    {
        $validItemId = StaticValidator::getValidPositiveInteger($paramItemId, 0);
        $i = 0;
        $validFeaturesIds = $this->getAllIds();

        $html = '<table width="100%"><tr>';
        foreach ($validFeaturesIds AS $validFeaturesId)
        {
            $objFeature = new Feature($this->conf, $this->lang, $this->settings, $validFeaturesId);
            $featureDetails = $objFeature->getDetails();
            $checked = '';
            if($validItemId > 0)
            {
                $itemFeatureData = $this->conf->getInternalWPDB()->get_row("
                    SELECT feature_id
                    FROM {$this->conf->getPrefix()}item_features
                    WHERE item_id='{$validItemId}' AND feature_id='{$validFeaturesId}' AND blog_id='{$this->conf->getBlogId()}'
                ", ARRAY_A);
                if(!is_null($itemFeatureData) && $itemFeatureData['feature_id'] == $validFeaturesId)
                {
                    $checked = 'checked="checked"';
                }
            }
            $html .= '<td><input type="checkbox" name="features[]" value="'.$validFeaturesId.'" '.$checked.' /><span> '.$featureDetails['print_feature_title'].'</span></td>';
            $i++;

            if($i == 2)
            {
                $html .= '</tr><tr>';
                $i = 0;
            }
        }
        $html .= '</tr></table>';

        return $html;
    }

    public function getTranslatedSelectedFeaturesByItemId($paramItemId, $showOnlyItemListFeatures = FALSE)
    {
        return $this->getSelectedFeaturesByItemId($paramItemId, $showOnlyItemListFeatures, 1);
    }

    /**
     * @param $paramItemId
     * @param bool $showOnlyItemListFeatures
     * @param bool $paramTranslated
     * @return string|array
     */
    private function getSelectedFeaturesByItemId($paramItemId, $showOnlyItemListFeatures = FALSE, $paramTranslated = FALSE)
    {
        $validItemId = StaticValidator::getValidPositiveInteger($paramItemId, 0);
        $sqlAddForItemListOnly = $showOnlyItemListFeatures ? " AND f.display_in_item_list='1'" : "";
        $sqlQuery = "
			SELECT f.feature_id
			FROM {$this->conf->getPrefix()}item_features AS itf, {$this->conf->getPrefix()}features AS f
			WHERE itf.item_id = '{$validItemId}' AND f.feature_id = itf.feature_id AND itf.blog_id='{$this->conf->getBlogId()}'
			{$sqlAddForItemListOnly}
		";
        $featureIds = $this->conf->getInternalWPDB()->get_col($sqlQuery);

        $features = array();
        foreach ($featureIds AS $featureId)
        {
            $objFeature = new Feature($this->conf, $this->lang, $this->settings, $featureId);
            $featureDetails = $objFeature->getDetails();
            $features[] = $featureDetails[$paramTranslated ? 'print_translated_feature_title' : 'print_feature_title'];
        }

        if($this->debugMode)
        {
            echo "<br />Item id: {$validItemId}, Feature Ids: ".print_r($featureIds, TRUE);
        }

        return $features;
    }



    /*******************************************************************************/
    /********************** METHODS FOR ADMIN ACCESS ONLY **************************/
    /*******************************************************************************/

    public function getAdminList()
    {
        $featuresHTML = '';
        $featureIds = $this->getAllIds();
        foreach ($featureIds AS $featureId)
        {
            $objFeature = new Feature($this->conf, $this->lang, $this->settings, $featureId);
            $featureDetails = $objFeature->getDetails();

            $printTranslatedFeatureTitle = $featureDetails['print_translated_feature_title'];
            if($this->lang->canTranslateSQL())
            {
                $printTranslatedFeatureTitle .= '<br /><span class="not-translated" title="'.$this->lang->getText('NRS_ADMIN_WITHOUT_TRANSLATION_TEXT').'">('.$featureDetails['print_feature_title'].')</span>';
            }

            $textInItemList = $this->lang->getText($featureDetails['display_in_item_list'] == 1 ? 'NRS_ADMIN_DISPLAYED_TEXT' : 'NRS_ADMIN_HIDDEN_TEXT');
            $featuresHTML .= '<tr>';
            $featuresHTML .= '<td>'.$printTranslatedFeatureTitle.'</td>';
            $featuresHTML .= '<td>'.$textInItemList.'</td>';
            $featuresHTML .= '<td align="right">';
            if(current_user_can('manage_'.$this->conf->getExtensionPrefix().'all_inventory'))
            {
                $featuresHTML .= '<a href="'.admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-feature&amp;feature_id='.$featureId).'">'.$this->lang->getText('NRS_ADMIN_EDIT_TEXT').'</a> || ';
                $featuresHTML .= '<a href="javascript:;" onclick="javascript:delete'.$this->conf->getExtensionFolder().'Feature(\''.$featureId.'\')">'.$this->lang->getText('NRS_ADMIN_DELETE_TEXT').'</a>';
            } else
            {
                $featuresHTML .= '--';
            }
            $featuresHTML .= '</td>';
            $featuresHTML .= '</tr>';
        }
        return  $featuresHTML;
    }
}