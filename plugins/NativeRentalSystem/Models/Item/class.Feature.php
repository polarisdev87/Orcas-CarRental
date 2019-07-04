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

class Feature extends AbstractElement implements iElement
{
    protected $conf 	                = NULL;
    protected $lang 		            = NULL;
    protected $debugMode 	            = 0;
    protected $featureId                = 0;

    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings, $paramFeatureId)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;
        $this->featureId = StaticValidator::getValidValue($paramFeatureId, 'positive_integer', 0);
    }

    /**
     * @param $paramFeatureId
     * @return mixed
     */
    private function getDataFromDatabaseById($paramFeatureId)
    {
        $validFeatureId = StaticValidator::getValidPositiveInteger($paramFeatureId, 0);

        $retData = $this->conf->getInternalWPDB()->get_row("
            SELECT *
            FROM {$this->conf->getPrefix()}features
            WHERE feature_id='{$validFeatureId}'
        ", ARRAY_A);

        return $retData;
    }

    public function getId()
    {
        return $this->featureId;
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    /**
     * @note Do not translate title here - it is used for editing
     * @param bool $paramIncludeUnclassified
     * @return mixed
     */
    public function getDetails($paramIncludeUnclassified = FALSE)
    {
        $ret = $this->getDataFromDatabaseById($this->featureId);
        if(!is_null($ret))
        {
            // Make raw
            $ret['feature_title'] = stripslashes($ret['feature_title']);

            // Retrieve translation
            $ret['translated_feature_title'] = $this->lang->getTranslated("fe{$ret['feature_id']}_feature_title", $ret['feature_title']);

            // Prepare output for print
            $ret['print_feature_title'] = esc_html($ret['feature_title']);
            $ret['print_translated_feature_title'] = esc_html($ret['translated_feature_title']);

            // Prepare output for edit
            $ret['edit_feature_title'] = esc_attr($ret['feature_title']); // for input field
        }

        return $ret;
    }

    /**
     * If add_to_all_items is checked - this function will also add it to all items for new item
     * @return bool|false|int
     */
    public function save()
    {
        $saved = FALSE;
        $ok = TRUE;
        $validFeatureId = StaticValidator::getValidPositiveInteger($this->featureId, 0);
        $sanitizedFeatureTitle = sanitize_text_field($_POST['feature_title']);
        $validFeatureTitle = esc_sql($sanitizedFeatureTitle); // for sql query only
        $validDisplayInItemList = (sanitize_text_field($_POST['display_in_item_list']) == "on" ? 1 : 0);
        if($validFeatureId == 0)
        {
            $addToAllItems = (sanitize_text_field($_POST['add_to_all_items']) == "on" ? TRUE : FALSE);
        } else
        {
            $addToAllItems = FALSE;
        }

        $titleExistsQuery = "
            SELECT feature_id
            FROM {$this->conf->getPrefix()}features
            WHERE feature_title='{$validFeatureTitle}' AND feature_id!='{$validFeatureId}' AND blog_id='{$this->conf->getBlogId()}'
        ";
        $titleExists = $this->conf->getInternalWPDB()->get_row($titleExistsQuery, ARRAY_A);

        if(!is_null($titleExists))
        {
            $ok = FALSE;
            $this->errorMessages[] = $this->lang->getText('NRS_FEATURE_TITLE_EXISTS_ERROR_TEXT');
        }

        if($validFeatureId > 0 && $ok)
        {
            $saved = $this->conf->getInternalWPDB()->query("
                UPDATE {$this->conf->getPrefix()}features SET
                feature_title='{$validFeatureTitle}', display_in_item_list='{$validDisplayInItemList}'
                WHERE feature_id='{$validFeatureId}' AND blog_id='{$this->conf->getBlogId()}'
            ");

            if($saved === FALSE)
            {
                $this->errorMessages[] = $this->lang->getText('NRS_FEATURE_UPDATE_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('NRS_FEATURE_UPDATED_TEXT');
            }
        } else if($ok)
        {
            $saved = $this->conf->getInternalWPDB()->query("
                INSERT INTO {$this->conf->getPrefix()}features
                (
                    feature_title, display_in_item_list, blog_id
                ) VALUES
                (
                    '{$validFeatureTitle}', '{$validDisplayInItemList}', '{$this->conf->getBlogId()}'
                )
            ");

            if($saved)
            {
                // Get newly inserted feature id
                $validInsertedNewFeatureId = $this->conf->getInternalWPDB()->insert_id;

                // Update class tax id with newly inserted feature id for future work
                $this->featureId = $validInsertedNewFeatureId;

                $items = $this->conf->getInternalWPDB()->get_results("
                    SELECT item_id
                    FROM {$this->conf->getPrefix()}items
                    WHERE blog_id='{$this->conf->getBlogId()}'
                ", ARRAY_A);

                if(!is_null($items) && $addToAllItems == TRUE)
                {
                    foreach($items as $item)
                    {
                        $this->conf->getInternalWPDB()->query("
                            INSERT INTO {$this->conf->getPrefix()}item_features
                            (
                                item_id, feature_id, blog_id
                            ) VALUES
                            (
                                '{$item['item_id']}', '{$validInsertedNewFeatureId}', '{$this->conf->getBlogId()}'
                            )
                        ");
                    }
                }
            }

            if($saved === FALSE || $saved === 0)
            {
                $this->errorMessages[] = $this->lang->getText('NRS_FEATURE_INSERT_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('NRS_FEATURE_INSERTED_TEXT');
            }
        }

        return $saved;
    }

    public function registerForTranslation()
    {
        $featureDetails = $this->getDetails();
        if(!is_null($featureDetails))
        {
            $this->lang->register("fe{$this->featureId}_feature_title", $featureDetails['feature_title']);
            $this->okayMessages[] = $this->lang->getText('NRS_FEATURE_REGISTERED_TEXT');
        }
    }

    public function delete()
    {
        $validFeatureId = StaticValidator::getValidPositiveInteger($this->featureId, 0);
        $deleted = $this->conf->getInternalWPDB()->query("
            DELETE FROM {$this->conf->getPrefix()}features
            WHERE feature_id='{$validFeatureId}' AND blog_id='{$this->conf->getBlogId()}'
        ");
        if($deleted)
        {
            // Delete corresponding item features
            $this->conf->getInternalWPDB()->query("
                DELETE FROM {$this->conf->getPrefix()}item_features
                WHERE feature_id='{$validFeatureId}' AND blog_id='{$this->conf->getBlogId()}'
            ");
        }

        if($deleted === FALSE || $deleted === 0)
        {
            $this->errorMessages[] = $this->lang->getText('NRS_FEATURE_DELETE_ERROR_TEXT');
        } else
        {
            $this->okayMessages[] = $this->lang->getText('NRS_FEATURE_DELETED_TEXT');
        }
    }
}