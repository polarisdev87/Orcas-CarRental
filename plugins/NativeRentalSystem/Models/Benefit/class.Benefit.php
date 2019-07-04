<?php
/**
 * NRS Benefit

 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Benefit;
use NativeRentalSystem\Models\AbstractElement;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\File\StaticFile;
use NativeRentalSystem\Models\iElement;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;

class Benefit extends AbstractElement implements iElement
{
    protected $conf 	                = NULL;
    protected $lang 		            = NULL;
    protected $debugMode 	            = 0;
    protected $benefitId                = 0;
    protected $thumbWidth	            = 71;
    protected $thumbHeight		        = 81;

    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings, $paramBenefitId)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;
        $this->benefitId = StaticValidator::getValidValue($paramBenefitId, 'positive_integer', 0);

        if(isset($paramSettings['conf_benefit_thumb_w'], $paramSettings['conf_benefit_thumb_h']))
        {
            // Set image dimensions
            $this->thumbWidth = StaticValidator::getValidPositiveInteger($paramSettings['conf_benefit_thumb_w'], 0);
            $this->thumbHeight = StaticValidator::getValidPositiveInteger($paramSettings['conf_benefit_thumb_h'], 0);
        }
    }

    /**
     * @param $paramBenefitId
     * @return mixed
     */
    private function getDataFromDatabaseById($paramBenefitId)
    {
        $validBenefitId = StaticValidator::getValidPositiveInteger($paramBenefitId, 0);

        $retData = $this->conf->getInternalWPDB()->get_row("
            SELECT *
            FROM {$this->conf->getPrefix()}benefits
            WHERE benefit_id='{$validBenefitId}'
        ", ARRAY_A);

        return $retData;
    }

    public function getId()
    {
        return $this->benefitId;
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
        $ret = $this->getDataFromDatabaseById($this->benefitId);
        if(!is_null($ret))
        {
            // Make raw
            $ret['benefit_title'] = stripslashes($ret['benefit_title']);
            $ret['benefit_image'] = stripslashes($ret['benefit_image']);

            // Retrieve translation
            $ret['translated_benefit_title'] = $this->lang->getTranslated("be{$ret['benefit_id']}_benefit_title", $ret['benefit_title']);

            // Extend with additional rows
            $imageFolder = $ret['demo_benefit_image'] == 1 ? $this->conf->getExtensionDemoGalleryURL('', FALSE) : $this->conf->getGalleryURL();
            $ret['thumb_url'] = $ret['benefit_image'] != "" ? $imageFolder."thumb_".$ret['benefit_image'] : "";
            $ret['image_url'] = $ret['benefit_image'] != "" ? $imageFolder.$ret['benefit_image'] : "";

            // Prepare output for print
            $ret['print_benefit_title'] = esc_html($ret['benefit_title']);
            $ret['print_translated_benefit_title'] = esc_html($ret['translated_benefit_title']);

            // Prepare output for edit
            $ret['edit_benefit_title'] = esc_attr($ret['benefit_title']); // for input field
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
        $validBenefitId = StaticValidator::getValidPositiveInteger($this->benefitId, 0);
        $sanitizedBenefitTitle = isset($_POST['benefit_title']) ? sanitize_text_field($_POST['benefit_title']) : '';
        $validBenefitTitle = esc_sql($sanitizedBenefitTitle); // for sql query only
        if(isset($_POST['benefit_order']) && StaticValidator::isPositiveInteger($_POST['benefit_order']))
        {
            $validBenefitOrder = StaticValidator::getValidPositiveInteger($_POST['benefit_order'], 1);
        } else
        {
            // SELECT MAX
            $sqlQuery = "
                SELECT MAX(benefit_order) AS max_order
                FROM {$this->conf->getPrefix()}benefits
                WHERE 1
            ";
            $maxOrderResult = $this->conf->getInternalWPDB()->get_var($sqlQuery);
            $validBenefitOrder = !is_null($maxOrderResult) ? intval($maxOrderResult)+1 : 1;
        }

        $titleExistsQuery = "
            SELECT benefit_id
            FROM {$this->conf->getPrefix()}benefits
            WHERE benefit_title='{$validBenefitTitle}' AND benefit_id!='{$validBenefitId}'
            AND blog_id='{$this->conf->getBlogId()}'
        ";
        $titleExists = $this->conf->getInternalWPDB()->get_row($titleExistsQuery, ARRAY_A);

        if(!is_null($titleExists))
        {
            $ok = FALSE;
            $this->errorMessages[] = $this->lang->getText('NRS_BENEFIT_TITLE_EXISTS_ERROR_TEXT');
        }

        if($validBenefitId > 0 && $ok)
        {
            $saved = $this->conf->getInternalWPDB()->query("
                UPDATE {$this->conf->getPrefix()}benefits SET
                benefit_title='{$validBenefitTitle}', benefit_order='{$validBenefitOrder}'
                WHERE benefit_id='{$validBenefitId}' AND blog_id='{$this->conf->getBlogId()}'
            ");

            // Only if there is error in query we will skip that, if no changes were made (and 0 was returned) we will still process
            if($saved !== FALSE)
            {
                $benefitEditData = $this->conf->getInternalWPDB()->get_row("
                    SELECT *
                    FROM {$this->conf->getPrefix()}benefits
                    WHERE benefit_id='{$validBenefitId}' AND blog_id='{$this->conf->getBlogId()}'
                ", ARRAY_A);

                // Upload image
                if(
                    isset($_POST['delete_benefit_image']) && $benefitEditData['benefit_image'] != "" &&
                    $benefitEditData['demo_benefit_image'] == 0
                ) {
                    // Unlink files only if it's not a demo image
                    unlink($this->conf->getGalleryPath().$benefitEditData['benefit_image']);
                    unlink($this->conf->getGalleryPath()."thumb_".$benefitEditData['benefit_image']);
                }

                $validUploadedImageFileName = '';
                if($_FILES['benefit_image']['tmp_name'] != '')
                {
                    $uploadedImageFileName = StaticFile::uploadImageFile($_FILES['benefit_image'], $this->conf->getGalleryPathWithoutEndSlash(), "location_");
                    StaticFile::makeThumbnail($this->conf->getGalleryPath(), $uploadedImageFileName, $this->thumbWidth, $this->thumbHeight, "thumb_");
                    $validUploadedImageFileName = esc_sql(sanitize_file_name($uploadedImageFileName)); // for sql query only
                }

                if($validUploadedImageFileName != '' || isset($_POST['delete_manufacturer_logo']))
                {
                    // Update the sql
                    $this->conf->getInternalWPDB()->query("
                        UPDATE {$this->conf->getPrefix()}benefits SET
                        benefit_image='{$validUploadedImageFileName}', demo_benefit_image='0'
                        WHERE benefit_id='{$validBenefitId}' AND blog_id='{$this->conf->getBlogId()}'
                    ");
                }
            }

            if($saved === FALSE)
            {
                $this->errorMessages[] = $this->lang->getText('NRS_BENEFIT_UPDATE_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('NRS_BENEFIT_UPDATED_TEXT');
            }
        } else if($ok)
        {
            $saved = $this->conf->getInternalWPDB()->query("
                INSERT INTO {$this->conf->getPrefix()}benefits
                (benefit_title, benefit_order, blog_id)
                VALUES
                ('{$validBenefitTitle}', '{$validBenefitOrder}', '{$this->conf->getBlogId()}')
            ");

            // We will process only if there one line was added to sql
            if($saved)
            {
                // Get newly inserted benefit id
                $validInsertedNewBenefitId = $this->conf->getInternalWPDB()->insert_id;

                // Update the core benefit id for future use
                $this->benefitId = $validInsertedNewBenefitId;

                $validUploadedImageFileName = '';
                if($_FILES['benefit_image']['tmp_name'] != '')
                {
                    $uploadedImageFileName = StaticFile::uploadImageFile($_FILES['benefit_image'], $this->conf->getGalleryPathWithoutEndSlash(), "location_");
                    StaticFile::makeThumbnail($this->conf->getGalleryPath(), $uploadedImageFileName, $this->thumbWidth, $this->thumbHeight, "thumb_");
                    $validUploadedImageFileName = esc_sql(sanitize_file_name($uploadedImageFileName)); // for sql query only
                }

                if($validUploadedImageFileName != '')
                {
                    // Update the sql
                    $this->conf->getInternalWPDB()->query("
                        UPDATE {$this->conf->getPrefix()}benefits SET
                        benefit_image='{$validUploadedImageFileName}', demo_benefit_image='0'
                        WHERE benefit_id='{$validInsertedNewBenefitId}' AND blog_id='{$this->conf->getBlogId()}'
                    ");
                }
            }

            if($saved === FALSE || $saved === 0)
            {
                $this->errorMessages[] = $this->lang->getText('NRS_BENEFIT_INSERT_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('NRS_BENEFIT_INSERTED_TEXT');
            }
        }

        return $saved;
    }

    public function registerForTranslation()
    {
        $benefitDetails = $this->getDetails();
        if(!is_null($benefitDetails))
        {
            $this->lang->register("be{$this->benefitId}_benefit_title", $benefitDetails['benefit_title']);
            $this->okayMessages[] = $this->lang->getText('NRS_BENEFIT_REGISTERED_TEXT');
        }
    }

    /**
     * @return false|int
     */
    public function delete()
    {
        $deleted = FALSE;
        $benefitDetails = $this->getDetails();
        if(!is_null($benefitDetails))
        {
            $deleted = $this->conf->getInternalWPDB()->query("
                DELETE FROM {$this->conf->getPrefix()}benefits
                WHERE benefit_id='{$benefitDetails['benefit_id']}' AND blog_id='{$this->conf->getBlogId()}'
            ");

            if($deleted)
            {
                // Unlink image file
                if($benefitDetails['demo_benefit_image'] == 0 && $benefitDetails['benefit_image'] != "")
                {
                    unlink($this->conf->getGalleryPath().$benefitDetails['benefit_image']);
                    unlink($this->conf->getGalleryPath()."thumb_".$benefitDetails['benefit_image']);
                }
            }
        }

        if($deleted === FALSE || $deleted === 0)
        {
            $this->errorMessages[] = $this->lang->getText('NRS_BENEFIT_DELETE_ERROR_TEXT');
        } else
        {
            $this->okayMessages[] = $this->lang->getText('NRS_BENEFIT_DELETED_TEXT');
        }

        return $deleted;
    }
}