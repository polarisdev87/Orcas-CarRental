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

class TransmissionType extends AbstractElement implements iElement
{
    protected $conf 	                = NULL;
    protected $lang 		            = NULL;
    protected $debugMode 	            = 0;
    protected $transmissionTypeId       = 0;

    /**
     * TransmissionType constructor.
     * @param ExtensionConfiguration $paramConf
     * @param Language $paramLang
     * @param array $paramSettings
     * @param int $paramTransmissionTypeId
     */
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings, $paramTransmissionTypeId)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;
        $this->transmissionTypeId = StaticValidator::getValidPositiveInteger($paramTransmissionTypeId, 0);
    }

    /**
     * @param $paramTransmissionTypeId
     * @return mixed
     */
    private function getDataFromDatabaseById($paramTransmissionTypeId)
    {
        $validTransmissionTypeId = StaticValidator::getValidPositiveInteger($paramTransmissionTypeId, 0);

        $retData = $this->conf->getInternalWPDB()->get_row("
            SELECT *
            FROM {$this->conf->getPrefix()}transmission_types
            WHERE transmission_type_id='{$validTransmissionTypeId}'
        ", ARRAY_A);

        return $retData;
    }

    public function getId()
    {
        return $this->transmissionTypeId;
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
        $ret = $this->getDataFromDatabaseById($this->transmissionTypeId);
        if(!is_null($ret))
        {
            // Make raw
            $ret['transmission_type_title'] = stripslashes($ret['transmission_type_title']);

            // Retrieve translation
            $ret['translated_transmission_type_title'] = $this->lang->getTranslated("tt{$ret['transmission_type_id']}_transmission_type_title", $ret['transmission_type_title']);

            // Prepare output for print
            $ret['print_transmission_type_title'] = esc_html($ret['transmission_type_title']);
            $ret['print_translated_transmission_type_title'] = esc_html($ret['translated_transmission_type_title']);

            // Prepare output for edit
            $ret['edit_transmission_type_title'] = esc_attr($ret['transmission_type_title']); // for input field
        }

        return $ret;
    }

    public function save()
    {
        $saved = FALSE;
        $ok = TRUE;
        $validTransmissionTypeId = StaticValidator::getValidPositiveInteger($this->transmissionTypeId, 0);
        $sanitizedTransmissionTypeTitle = isset($_POST['transmission_type_title']) ? sanitize_text_field($_POST['transmission_type_title']) : '';
        $validTransmissionTypeTitle = esc_sql($sanitizedTransmissionTypeTitle); // for sql query only

        $titleExistsQuery = "
            SELECT transmission_type_id
            FROM {$this->conf->getPrefix()}transmission_types
            WHERE transmission_type_title='{$validTransmissionTypeTitle}' AND transmission_type_id!='{$validTransmissionTypeId}'
            AND blog_id='{$this->conf->getBlogId()}'
        ";
        $titleExists = $this->conf->getInternalWPDB()->get_row($titleExistsQuery, ARRAY_A);

        if(!is_null($titleExists))
        {
            $ok = FALSE;
            $this->errorMessages[] = $this->lang->getText('NRS_TRANSMISSION_TYPE_TITLE_EXISTS_ERROR_TEXT');
        }

        if($validTransmissionTypeId > 0 && $ok)
        {
            $saved = $this->conf->getInternalWPDB()->query("
                UPDATE {$this->conf->getPrefix()}transmission_types SET
                transmission_type_title='{$validTransmissionTypeTitle}'
                WHERE transmission_type_id='{$validTransmissionTypeId}' AND blog_id='{$this->conf->getBlogId()}'
            ");

            if($saved === FALSE)
            {
                $this->errorMessages[] = $this->lang->getText('NRS_TRANSMISSION_TYPE_UPDATE_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('NRS_TRANSMISSION_TYPE_UPDATED_TEXT');
            }
        } else if($ok)
        {
            $saved = $this->conf->getInternalWPDB()->query("
                INSERT INTO {$this->conf->getPrefix()}transmission_types
                (transmission_type_title, blog_id)
                VALUES
                ('{$validTransmissionTypeTitle}', '{$this->conf->getBlogId()}')
            ");

            if($saved)
            {
                // Get newly inserted transmission type id
                $validInsertedNewTransmissionTypeId = $this->conf->getInternalWPDB()->insert_id;

                // Update class tax id with newly inserted transmission type id for future work
                $this->transmissionTypeId = $validInsertedNewTransmissionTypeId;
            }

            if($saved === FALSE || $saved === 0)
            {
                $this->errorMessages[] = $this->lang->getText('NRS_TRANSMISSION_TYPE_INSERT_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('NRS_TRANSMISSION_TYPE_INSERTED_TEXT');
            }
        }

        return $saved;
    }

    public function registerForTranslation()
    {
        $transmissionTypeDetails = $this->getDetails();
        if(!is_null($transmissionTypeDetails))
        {
            $this->lang->register("tt{$this->transmissionTypeId}_transmission_type_title", $transmissionTypeDetails['transmission_type_title']);
            $this->okayMessages[] = $this->lang->getText('NRS_TRANSMISSION_TYPE_REGISTERED_TEXT');
        }
    }

    /**
     * @return false|int
     */
    public function delete()
    {
        $validTransmissionTypeId = StaticValidator::getValidPositiveInteger($this->transmissionTypeId, 0);
        $deleted = $this->conf->getInternalWPDB()->query("
            DELETE FROM {$this->conf->getPrefix()}transmission_types
            WHERE transmission_type_id='{$validTransmissionTypeId}' AND blog_id='{$this->conf->getBlogId()}'
        ");

        if($deleted === FALSE || $deleted === 0)
        {
            $this->errorMessages[] = $this->lang->getText('NRS_TRANSMISSION_TYPE_DELETE_ERROR_TEXT');
        } else
        {
            $this->okayMessages[] = $this->lang->getText('NRS_TRANSMISSION_TYPE_DELETED_TEXT');
        }

        return $deleted;
    }
}