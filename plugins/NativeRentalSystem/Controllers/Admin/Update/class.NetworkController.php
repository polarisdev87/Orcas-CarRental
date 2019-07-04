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

final class NetworkController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    /**
     * For updating across multisite the network-enabled plugin from V4.3 to V5.0
     * @note - Works only with WordPress 4.6+
     */
    private function processNetworkUpdateTo_5_0()
    {
        // Create mandatory instances
        $objNetworkDatabaseUpdate = new DatabaseUpdate($this->conf, $this->lang, $this->conf->getBlogId());
        $allSitesVersionUpdated = TRUE;

        if($this->conf->isNetworkEnabled() && $objNetworkDatabaseUpdate->getVersion() == 4.3 &&
            function_exists('get_sites') && class_exists('WP_Site_Query')
        ) {
            // Alter the database early structure for all sites (because they use same database tables)
            $networkEarlyStructUpdated = $objNetworkDatabaseUpdate->alter_4_3_DatabaseEarlyStructureTo_5_0();

            // NOTE: Network site is one of the sites. So it will update network site id as well.
            $sites = get_sites();
            foreach ($sites AS $site)
            {
                $blogId = $site->blog_id;
                switch_to_blog($blogId);

                $lang = new Language(
                    $this->conf->getTextDomain(), $this->conf->getGlobalLangPath(), $this->conf->getExtensionLangPath('', FALSE), get_locale()
                );

                // Update the database data
                $objSingleDatabaseUpdate = new DatabaseUpdate($this->conf, $lang, $blogId);

                // Process ONLY if network struct is already updated and current site database was not yet updated
                if($networkEarlyStructUpdated && $objSingleDatabaseUpdate->getVersion() == 4.3)
                {
                    // We register post types here only because we want to run 'flush_rewrite_rules()' bellow.
                    $objPagePostType = new PageType($this->conf, $this->lang, $this->conf->getExtensionPrefix().'page');
                    $objItemPostType = new ItemType($this->conf, $this->lang, $this->conf->getExtensionPrefix().'item');
                    $objLocationPostType = new LocationType($this->conf, $this->lang, $this->conf->getExtensionPrefix().'location');

                    $dataUpdated = $objSingleDatabaseUpdate->update_4_3_DatabaseDataTo_5_0();
                    if($dataUpdated)
                    {
                        // Update the current site database version to 5.0
                        $versionUpdated = $objNetworkDatabaseUpdate->updateVersion('5.0');
                        if($versionUpdated == FALSE)
                        {
                            $allSitesVersionUpdated = FALSE;
                        }
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

                $this->processDebugMessages($objSingleDatabaseUpdate->getDebugMessages());
                $this->processOkayMessages($objSingleDatabaseUpdate->getOkayMessages());
                $this->processErrorMessages($objSingleDatabaseUpdate->getErrorMessages());
            }
            // Switch back to current network blog id. Restore current blog won't work here, as it would just restore to previous blog of the long loop
            switch_to_blog($this->conf->getBlogId());

            // Process ONLY if the data was updated in ALL sites - because what if it crashed in the middle of the process
            if($allSitesVersionUpdated)
            {
                // Alter the database late structure - we not going to pay attention if the crash will happen in here,
                // because the database is already valid with just extra data, which we may just skip
                $objNetworkDatabaseUpdate->alter_4_3_DatabaseLateStructureTo_5_0();
            }
        }

        $this->processDebugMessages($objNetworkDatabaseUpdate->getDebugMessages());
        $this->processOkayMessages($objNetworkDatabaseUpdate->getOkayMessages());
        $this->processErrorMessages($objNetworkDatabaseUpdate->getErrorMessages());

        return $allSitesVersionUpdated;
    }

    private function processNetworkUpdate()
    {
        // List of all supported updates. Later the list bellow with processors will be extended to longer and longer
        $allSitesVersionUpdated = $this->processNetworkUpdateTo_5_0();
        if($allSitesVersionUpdated === FALSE)
        {
            // Failed
            wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'network-updater');
        } else
        {
            // Completed
            wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'network-manager&tab=network-status');
        }
        exit;
    }

    public function getContent()
    {
        if(isset($_POST['network_update'])) { $this->processNetworkUpdate(); }

        // Create mandatory instances
        $objDatabaseUpdate = new DatabaseUpdate($this->conf, $this->lang, $this->conf->getBlogId());

        // Set the view variables
        $this->view->networkUpdateTabFormAction = admin_url('admin.php?page='.$this->conf->getURLPrefix().'network-updater&noheader=true');
        $this->view->networkUpgradeTabFormAction = admin_url('admin.php?page='.$this->conf->getURLPrefix().'network-updater&noheader=true');
        $this->view->majorUpgrade = $objDatabaseUpdate->isMajorUpgrade($this->conf->getVersion());
        $this->view->canUpdate = $objDatabaseUpdate->canUpdate($this->conf->getVersion());
        $this->view->pluginUpToDate = $objDatabaseUpdate->isUpToDate($this->conf->getVersion());
        $this->view->version = number_format_i18n($this->conf->getVersion(), 1);
        $this->view->databaseVersion = number_format_i18n($objDatabaseUpdate->getVersion(), 1);

        // Get the template
        $retContent = $this->getTemplate('Update', 'NetworkManager', 'Tabs');

        return $retContent;
    }
}
