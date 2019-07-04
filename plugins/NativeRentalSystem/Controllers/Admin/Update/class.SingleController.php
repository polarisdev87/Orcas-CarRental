<?php
/**
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Admin\Update;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\File\StaticFile;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Post\ItemType;
use NativeRentalSystem\Models\Post\LocationType;
use NativeRentalSystem\Models\Post\PageType;
use NativeRentalSystem\Models\Role\Assistant;
use NativeRentalSystem\Models\Role\Manager;
use NativeRentalSystem\Models\Role\Partner;
use NativeRentalSystem\Models\Update\DatabaseUpdate;

final class SingleController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    /**
     * For updating single site plugin from V4.3 to V5.0
     */
    private function processUpdateTo_5_0()
    {
        $updated = FALSE;

        // Create mandatory instances
        $objDatabaseUpdate = new DatabaseUpdate($this->conf, $this->lang, $this->conf->getBlogId());

        if($this->conf->isNetworkEnabled() === FALSE && $objDatabaseUpdate->canUpdate($this->conf->getVersion()) && $objDatabaseUpdate->getVersion() == 4.3)
        {
            // We register post types here only because we want to run 'flush_rewrite_rules()' bellow.
            $objPagePostType = new PageType($this->conf, $this->lang, $this->conf->getExtensionPrefix().'page');
            $objItemPostType = new ItemType($this->conf, $this->lang, $this->conf->getExtensionPrefix().'item');
            $objLocationPostType = new LocationType($this->conf, $this->lang, $this->conf->getExtensionPrefix().'location');

            // Alter the database early structure
            $structAltered = $objDatabaseUpdate->alter_4_3_DatabaseEarlyStructureTo_5_0();
            $dataUpdated = FALSE;
            $lateStructAltered = FALSE;

            // Process ONLY if the struct was updated - because what if it crashed in the middle of the process
            if($structAltered)
            {
                // Update the database data
                $dataUpdated = $objDatabaseUpdate->update_4_3_DatabaseDataTo_5_0();
            }
            // Process ONLY if the data was updated - because what if it crashed in the middle of the process
            if($dataUpdated)
            {
                // Alter the database late structure
                $lateStructAltered = $objDatabaseUpdate->alter_4_3_DatabaseLateStructureTo_5_0();
            }
            // Process ONLY if the late struct was altered - because what if it crashed in the middle of the process
            if($lateStructAltered)
            {
                // Update the database version to 5.0
                $updated = $objDatabaseUpdate->updateVersion('5.0');
            }

            // Add Roles
            $objPartnerRole = new Partner($this->conf, $this->lang, $this->conf->getExtensionPrefix().'partner');
            $objPartnerRole->remove(); // Remove roles if exist - Launch only on first time activation
            $objPartnerRole->add();

            $objAssistantRole = new Assistant($this->conf, $this->lang, $this->conf->getExtensionPrefix().'assistant');
            $objAssistantRole->remove(); // Remove roles if exist - Launch only on first time activation
            $objAssistantRole->add();

            $objManagerRole = new Manager($this->conf, $this->lang, $this->conf->getExtensionPrefix().'manager');
            $objManagerRole->remove(); // Remove roles if exist - Launch only on first time activation
            $objManagerRole->add();

            // Add all plugin capabilities to WordPress admin role
            $objWPAdminRole = get_role('administrator');
            $capabilitiesToAdd = $objManagerRole->getCapabilities();
            foreach($capabilitiesToAdd AS $capability => $grant)
            {
                $objWPAdminRole->add_cap($capability, $grant);
            }

            // Rename uploads folder - we can't hardcode here, so we have to be really careful about the names, or the update will break for future versions
            $oldGalleryFolderName = $this->conf->getURLPrefix().'gallery'; // Warning! We always need to maintain this data to be 'car-rental-gallery'
            $newGalleryFolderName = $this->conf->getGallery(); // Warning! We always need to maintain this data to be 'CarRentalGallery'
            $uploadsDir = wp_upload_dir();
            $oldGalleryPathWithoutEndSlash = str_replace('\\', DIRECTORY_SEPARATOR, $uploadsDir['basedir']).DIRECTORY_SEPARATOR.$oldGalleryFolderName;
            $newGalleryPathWithoutEndSlash = str_replace('\\', DIRECTORY_SEPARATOR, $uploadsDir['basedir']).DIRECTORY_SEPARATOR.$newGalleryFolderName;
            // We don't check here is that was successfully or not, as we still allow to process anyway
            StaticFile::renameFolder($oldGalleryPathWithoutEndSlash, $newGalleryPathWithoutEndSlash);

            // There were changes for links - so we need to flush the rewrite rules
            $objPagePostType->register($this->lang->getText('NRS_INSTALL_DEFAULT_PAGE_URL_SLUG_TEXT'), 95);
            $objItemPostType->register($this->lang->getText('NRS_INSTALL_DEFAULT_ITEM_URL_SLUG_TEXT'), 96);
            $objLocationPostType->register($this->lang->getText('NRS_INSTALL_DEFAULT_LOCATION_URL_SLUG_TEXT'), 97);
            flush_rewrite_rules();
        }

        $this->processDebugMessages($objDatabaseUpdate->getDebugMessages());
        $this->processOkayMessages($objDatabaseUpdate->getOkayMessages());
        $this->processErrorMessages($objDatabaseUpdate->getErrorMessages());

        return $updated;
    }

    private function processUpdate()
    {
        // List of all supported updates. Later the list bellow with processors will be extended to longer and longer
        $updated = $this->processUpdateTo_5_0();
        if($updated === FALSE)
        {
            // Failed
            wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'updater');
        } else
        {
            // Completed
            wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'single-manager&tab=single-status');
        }
        exit;
    }

    public function getContent()
    {
        if(isset($_POST['update'])) { $this->processUpdate(); }

        // Create mandatory instances
        $objDatabaseUpdate = new DatabaseUpdate($this->conf, $this->lang, $this->conf->getBlogId());

        // Set the view variables
        $this->view->updateTabFormAction = admin_url('admin.php?page='.$this->conf->getURLPrefix().'updater&noheader=true');
        $this->view->upgradeTabFormAction = admin_url('admin.php?page='.$this->conf->getURLPrefix().'updater&noheader=true');
        $this->view->majorUpgrade = $objDatabaseUpdate->isMajorUpgrade($this->conf->getVersion());
        $this->view->isNetworkEnabled = $this->conf->isNetworkEnabled();
        $this->view->canUpdate = $objDatabaseUpdate->canUpdate($this->conf->getVersion());
        $this->view->pluginUpToDate = $objDatabaseUpdate->isUpToDate($this->conf->getVersion());
        $this->view->version = number_format_i18n($this->conf->getVersion(), 1);
        $this->view->databaseVersion = number_format_i18n($objDatabaseUpdate->getVersion(), 1);

        // Get the template
        $retContent = $this->getTemplate('Update', 'SingleManager', 'Tabs');

        return $retContent;
    }
}
