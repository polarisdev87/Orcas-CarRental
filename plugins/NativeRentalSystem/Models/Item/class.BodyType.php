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

class BodyType extends AbstractElement implements iElement
{
    protected $conf 	                = NULL;
    protected $lang 		            = NULL;
    protected $debugMode 	            = 0;
    protected $bodyTypeId               = 0;

    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings, $paramBodyTypeId)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;
        $this->bodyTypeId = StaticValidator::getValidValue($paramBodyTypeId, 'positive_integer', 0);
    }

    /**
     * @param $paramBodyTypeId
     * @return mixed
     */
    private function getDataFromDatabaseById($paramBodyTypeId)
    {
        $validBodyTypeId = StaticValidator::getValidPositiveInteger($paramBodyTypeId, 0);

        $retData = $this->conf->getInternalWPDB()->get_row("
            SELECT *
            FROM {$this->conf->getPrefix()}body_types
            WHERE body_type_id='{$validBodyTypeId}'
        ", ARRAY_A);

        return $retData;
    }

    public function getId()
    {
        return $this->bodyTypeId;
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
        $ret = $this->getDataFromDatabaseById($this->bodyTypeId);
        if(!is_null($ret))
        {
            // Make raw
            $ret['body_type_title'] = stripslashes($ret['body_type_title']);

            // Retrieve translation
            $ret['translated_body_type_title'] = $this->lang->getTranslated("bt{$ret['body_type_id']}_body_type_title", $ret['body_type_title']);

            // Prepare output for print
            $ret['print_body_type_title'] = esc_html($ret['body_type_title']);
            $ret['print_translated_body_type_title'] = esc_html($ret['translated_body_type_title']);

            // Prepare output for edit
            $ret['edit_body_type_title'] = esc_attr($ret['body_type_title']); // for input field
        }

        // Add unclassified type result
        if($paramIncludeUnclassified && $this->bodyTypeId == 0)
        {
            $ret = array(
                "body_type_id" => "0",
                "body_type_title" => $this->lang->getText('NRS_UNCLASSIFIED_ITEM_TYPE_TEXT'),
                "translated_body_type_title" => $this->lang->getText('NRS_UNCLASSIFIED_ITEM_TYPE_TEXT'),
                "print_body_type_title" => esc_html($this->lang->getText('NRS_UNCLASSIFIED_ITEM_TYPE_TEXT')),
                "print_translated_body_type_title" => esc_html($this->lang->getText('NRS_UNCLASSIFIED_ITEM_TYPE_TEXT')),
                "edit_body_type_title" => "",
            );
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
        $validBodyTypeId = StaticValidator::getValidPositiveInteger($this->bodyTypeId, 0);
        $sanitizedBodyTypeTitle = sanitize_text_field($_POST['body_type_title']);
        $validBodyTypeTitle = esc_sql($sanitizedBodyTypeTitle); // for sql query only
        if(isset($_POST['body_type_order']) && StaticValidator::isPositiveInteger($_POST['body_type_order']))
        {
            $validBodyTypeOrder = StaticValidator::getValidPositiveInteger($_POST['body_type_order'], 1);
        } else
        {
            // SELECT MAX
            $sqlQuery = "
                SELECT MAX(body_type_order) AS max_order
                FROM {$this->conf->getPrefix()}body_types
                WHERE 1
            ";
            $maxOrderResult = $this->conf->getInternalWPDB()->get_var($sqlQuery);
            $validBodyTypeOrder = !is_null($maxOrderResult) ? intval($maxOrderResult)+1 : 1;
        }

        $titleExistsQuery = "
            SELECT body_type_id
            FROM {$this->conf->getPrefix()}body_types
            WHERE body_type_title='{$validBodyTypeTitle}' AND body_type_id!='{$validBodyTypeId}' AND blog_id='{$this->conf->getBlogId()}'
        ";
        $titleExists = $this->conf->getInternalWPDB()->get_row($titleExistsQuery, ARRAY_A);

        if(!is_null($titleExists))
        {
            $ok = FALSE;
            $this->errorMessages[] = $this->lang->getText('NRS_BODY_TYPE_TITLE_EXISTS_ERROR_TEXT');
        }

        if($validBodyTypeId > 0 && $ok)
        {
            $saved = $this->conf->getInternalWPDB()->query("
                  UPDATE {$this->conf->getPrefix()}body_types SET
                  body_type_title='{$validBodyTypeTitle}', body_type_order='{$validBodyTypeOrder}'
                  WHERE body_type_id='{$validBodyTypeId}' AND blog_id='{$this->conf->getBlogId()}'
            ");

            if($saved === FALSE)
            {
                $this->errorMessages[] = $this->lang->getText('NRS_BODY_TYPE_UPDATE_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('NRS_BODY_TYPE_UPDATED_TEXT');
            }

        } else if($ok)
        {
            $saved = $this->conf->getInternalWPDB()->query("
                INSERT INTO {$this->conf->getPrefix()}body_types
                (body_type_title, body_type_order, blog_id)
                VALUES
                ('{$validBodyTypeTitle}', '{$validBodyTypeOrder}', '{$this->conf->getBlogId()}')
            ");

            if($saved)
            {
                // Get newly inserted body type id
                $validInsertedNewBodyTypeId = $this->conf->getInternalWPDB()->insert_id;

                // Update the core body type id for future use
                $this->bodyTypeId = $validInsertedNewBodyTypeId;
            }

            if($saved === FALSE || $saved === 0)
            {
                $this->errorMessages[] = $this->lang->getText('NRS_BODY_TYPE_INSERT_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('NRS_BODY_TYPE_INSERTED_TEXT');
            }
        }

        return $saved;
    }

    public function registerForTranslation()
    {
        $bodyTypeDetails = $this->getDetails();
        if(!is_null($bodyTypeDetails))
        {
            $this->lang->register("bt{$this->bodyTypeId}_body_type_title", $bodyTypeDetails['body_type_title']);
            $this->okayMessages[] = $this->lang->getText('NRS_BODY_TYPE_REGISTERED_TEXT');
        }
    }

    /**
     * @return false|int
     */
    public function delete()
    {
        $validBodyTypeId = StaticValidator::getValidPositiveInteger($this->bodyTypeId, 0);
        $deleted = $this->conf->getInternalWPDB()->query("
            DELETE FROM {$this->conf->getPrefix()}body_types
            WHERE body_type_id='{$validBodyTypeId}' AND blog_id='{$this->conf->getBlogId()}'
        ");

        if($deleted === FALSE || $deleted === 0)
        {
            $this->errorMessages[] = $this->lang->getText('NRS_BODY_TYPE_DELETE_ERROR_TEXT');
        } else
        {
            $this->okayMessages[] = $this->lang->getText('NRS_BODY_TYPE_DELETED_TEXT');
        }

        return $deleted;
    }
}