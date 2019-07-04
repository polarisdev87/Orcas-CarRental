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
use NativeRentalSystem\Models\File\StaticFile;
use NativeRentalSystem\Models\iElement;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;

class Manufacturer extends AbstractElement implements iElement
{
    protected $conf 	                = NULL;
    protected $lang 		            = NULL;
    protected $debugMode 	            = 0;
    protected $manufacturerId           = 0;
    protected $thumbWidth	            = 205;
    protected $thumbHeight		        = 205;

    /**
     * Manufacturer constructor.
     * @param ExtensionConfiguration $paramConf
     * @param Language $paramLang
     * @param array $paramSettings
     * @param int $paramManufacturerId
     */
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings, $paramManufacturerId)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;
        $this->manufacturerId = StaticValidator::getValidValue($paramManufacturerId, 'positive_integer', 0);

        if(isset($paramSettings['conf_manufacturer_thumb_w'], $paramSettings['conf_manufacturer_thumb_h']))
        {
            // Set image dimensions
            $this->thumbWidth = StaticValidator::getValidPositiveInteger($paramSettings['conf_manufacturer_thumb_w'], 0);
            $this->thumbHeight = StaticValidator::getValidPositiveInteger($paramSettings['conf_manufacturer_thumb_h'], 0);
        }
    }

    /**
     * @param $paramManufacturerId
     * @return mixed
     */
    private function getDataFromDatabaseById($paramManufacturerId)
    {
        $validManufacturerId = StaticValidator::getValidPositiveInteger($paramManufacturerId, 0);

        $retData = $this->conf->getInternalWPDB()->get_row("
            SELECT *
            FROM {$this->conf->getPrefix()}manufacturers
            WHERE manufacturer_id='{$validManufacturerId}'
        ", ARRAY_A);

        return $retData;
    }

    public function getId()
    {
        return $this->manufacturerId;
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
        $ret = $this->getDataFromDatabaseById($this->manufacturerId);
        if(!is_null($ret))
        {
            // Make raw
            $ret['manufacturer_title'] = stripslashes($ret['manufacturer_title']);
            $ret['manufacturer_logo'] = stripslashes($ret['manufacturer_logo']);

            $logoFolder = $ret['demo_manufacturer_logo'] == 1 ? $this->conf->getExtensionDemoGalleryURL('', FALSE) : $this->conf->getGalleryURL();

            // Retrieve translation
            $ret['translated_manufacturer_title'] = $this->lang->getTranslated("ma{$ret['manufacturer_id']}_manufacturer_title", $ret['manufacturer_title']);

            // Extend with additional rows
            $ret['thumb_url'] = $ret['manufacturer_logo'] != "" ? $logoFolder."thumb_".$ret['manufacturer_logo'] : "";
            $ret['logo_url'] = $ret['manufacturer_logo'] != "" ? $logoFolder.$ret['manufacturer_logo'] : "";

            // Prepare output for print
            $ret['print_manufacturer_title'] = esc_html($ret['manufacturer_title']);
            $ret['print_translated_manufacturer_title'] = esc_html($ret['translated_manufacturer_title']);

            // Prepare output for edit
            $ret['edit_manufacturer_title'] = esc_attr($ret['manufacturer_title']); // for input field
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
        $validManufacturerId = StaticValidator::getValidPositiveInteger($this->manufacturerId, 0);
        $sanitizedManufacturerTitle = sanitize_text_field($_POST['manufacturer_title']);
        $validManufacturerTitle = esc_sql($sanitizedManufacturerTitle); // for sql query only

        $titleExistsQuery = "
            SELECT manufacturer_id
            FROM {$this->conf->getPrefix()}manufacturers
            WHERE manufacturer_title='{$validManufacturerTitle}' AND manufacturer_id!='{$validManufacturerId}'
            AND blog_id='{$this->conf->getBlogId()}'
        ";
        $titleExists = $this->conf->getInternalWPDB()->get_row($titleExistsQuery, ARRAY_A);

        if(!is_null($titleExists))
        {
            $ok = FALSE;
            $this->errorMessages[] = $this->lang->getText('NRS_MANUFACTURER_TITLE_EXISTS_ERROR_TEXT');
        }

        if($validManufacturerId > 0 && $ok)
        {
            $saved = $this->conf->getInternalWPDB()->query("
                UPDATE {$this->conf->getPrefix()}manufacturers SET
                manufacturer_title='{$validManufacturerTitle}'
                WHERE manufacturer_id='{$validManufacturerId}' AND blog_id='{$this->conf->getBlogId()}'
            ");

            // Only if there is error in query we will skip that, if no changes were made (and 0 was returned) we will still process
            if($saved !== FALSE)
            {
                $manufacturerEditData = $this->conf->getInternalWPDB()->get_row("
                    SELECT *
                    FROM {$this->conf->getPrefix()}manufacturers
                    WHERE manufacturer_id='{$validManufacturerId}' AND blog_id='{$this->conf->getBlogId()}'
                ", ARRAY_A);

                // Upload logo
                if(
                    isset($_POST['delete_manufacturer_logo']) && $manufacturerEditData['manufacturer_logo'] != "" &&
                    $manufacturerEditData['demo_manufacturer_logo'] == 0
                ) {
                    // Unlink files only if it's not a demo image
                    unlink($this->conf->getGalleryPath().$manufacturerEditData['manufacturer_logo']);
                    unlink($this->conf->getGalleryPath()."thumb_".$manufacturerEditData['manufacturer_logo']);
                }

                $validUploadedLogoFileName = '';
                if($_FILES['manufacturer_logo']['tmp_name'] != '')
                {
                    $uploadedLogoFileName = StaticFile::uploadImageFile($_FILES['manufacturer_logo'], $this->conf->getGalleryPathWithoutEndSlash(), "manufacturer_");
                    StaticFile::makeThumbnail($this->conf->getGalleryPath(), $uploadedLogoFileName, $this->thumbWidth, $this->thumbHeight, "thumb_");
                    $validUploadedLogoFileName = esc_sql(sanitize_file_name($uploadedLogoFileName)); // for sql query only
                }

                if($validUploadedLogoFileName != '' || isset($_POST['delete_manufacturer_logo']))
                {
                    // Update the sql
                    $this->conf->getInternalWPDB()->query("
                        UPDATE {$this->conf->getPrefix()}manufacturers SET
                        manufacturer_logo='{$validUploadedLogoFileName}', demo_manufacturer_logo='0'
                        WHERE manufacturer_id='{$validManufacturerId}' AND blog_id='{$this->conf->getBlogId()}'
                    ");
                }
            }

            if($saved === FALSE)
            {
                $this->errorMessages[] = $this->lang->getText('NRS_MANUFACTURER_UPDATE_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('NRS_MANUFACTURER_UPDATED_TEXT');
            }
        } else if($ok)
        {
            $saved = $this->conf->getInternalWPDB()->query("
                INSERT INTO {$this->conf->getPrefix()}manufacturers
                (manufacturer_title, blog_id)
                VALUES
                ('{$validManufacturerTitle}', '{$this->conf->getBlogId()}')
            ");

            // We will process only if there one line was added to sql
            if($saved)
            {
                // Get newly inserted manufacturer id
                $validInsertedNewManufacturerId = $this->conf->getInternalWPDB()->insert_id;

                // Update the core element id for future use
                $this->manufacturerId = $validInsertedNewManufacturerId;

                $validUploadedLogoFileName = '';
                if($_FILES['manufacturer_logo']['tmp_name'] != '')
                {
                    $uploadedLogoFileName = StaticFile::uploadImageFile($_FILES['manufacturer_logo'], $this->conf->getGalleryPathWithoutEndSlash(), "manufacturer_");
                    StaticFile::makeThumbnail($this->conf->getGalleryPath(), $uploadedLogoFileName, $this->thumbWidth, $this->thumbHeight, "thumb_");
                    $validUploadedLogoFileName = esc_sql(sanitize_file_name($uploadedLogoFileName)); // for sql query only
                }

                if($validUploadedLogoFileName != '')
                {
                    // Update the sql
                    $this->conf->getInternalWPDB()->query("
                        UPDATE {$this->conf->getPrefix()}manufacturers SET
                        manufacturer_logo='{$validUploadedLogoFileName}', demo_manufacturer_logo='0'
                        WHERE manufacturer_id='{$validInsertedNewManufacturerId}' AND blog_id='{$this->conf->getBlogId()}'
                    ");
                }
            }

            if($saved === FALSE || $saved === 0)
            {
                $this->errorMessages[] = $this->lang->getText('NRS_MANUFACTURER_INSERT_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('NRS_MANUFACTURER_INSERTED_TEXT');
            }
        }

        return $saved;
    }

    public function registerForTranslation()
    {
        $manufacturerDetails = $this->getDetails();
        if(!is_null($manufacturerDetails))
        {
            $this->lang->register("ma{$this->manufacturerId}_manufacturer_title", $manufacturerDetails['manufacturer_title']);
            $this->okayMessages[] = $this->lang->getText('NRS_MANUFACTURER_REGISTERED_TEXT');
        }
    }

    /**
     * @return false|int
     */
    public function delete()
    {
        $deleted = FALSE;
        $manufacturerDetails = $this->getDetails();
        if(!is_null($manufacturerDetails))
        {
            $deleted = $this->conf->getInternalWPDB()->query("
                DELETE FROM {$this->conf->getPrefix()}manufacturers
                WHERE manufacturer_id='{$manufacturerDetails['manufacturer_id']}' AND blog_id='{$this->conf->getBlogId()}'
            ");

            if($deleted)
            {
                // Unlink logo file
                if($manufacturerDetails['demo_manufacturer_logo'] == 0 && $manufacturerDetails['manufacturer_logo'] != "")
                {
                    unlink($this->conf->getGalleryPath().$manufacturerDetails['manufacturer_logo']);
                    unlink($this->conf->getGalleryPath()."thumb_".$manufacturerDetails['manufacturer_logo']);
                }
            }
        }

        if($deleted === FALSE || $deleted === 0)
        {
            $this->errorMessages[] = $this->lang->getText('NRS_MANUFACTURER_DELETE_ERROR_TEXT');
        } else
        {
            $this->okayMessages[] = $this->lang->getText('NRS_MANUFACTURER_DELETED_TEXT');
        }

        return $deleted;
    }
}