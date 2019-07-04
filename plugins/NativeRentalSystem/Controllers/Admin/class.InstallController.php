<?php
/**
 * NRS Install controller to handle all install/network install and uninstall procedures
 * Final class cannot be inherited anymore. We use them when creating new instances
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Admin;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Install\Install;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Language\LanguagesObserver;
use NativeRentalSystem\Models\Post\ItemType;
use NativeRentalSystem\Models\Post\LocationType;
use NativeRentalSystem\Models\Post\PageType;
use NativeRentalSystem\Models\Role\Assistant;
use NativeRentalSystem\Models\Role\Manager;
use NativeRentalSystem\Models\Role\Partner;

final class InstallController
{
    private $conf 	                = NULL;
    private $lang 		            = NULL;
    private $blogId 	            = 0;

    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, $paramBlogId)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;

        $this->blogId = intval($paramBlogId);
    }

    /**
     * Creates the tables
     */
    public function install()
    {
        // Create mandatory instances
        $objSingleSiteInstall = new Install($this->conf, $this->lang, $this->blogId);

        // @Note - even if this is multisite, tables will be created only once for the main site only (with blog_id = '0' or '1')
        if ($objSingleSiteInstall->isInstalled() === FALSE)
        {
            // First - drop all tables if exists any to have a clean install as expected
            $objSingleSiteInstall->dropTables();
            // Then - create all tables
            $objSingleSiteInstall->createTables();
        }
    }

    /**
     * Only inserts all content, roles, etc. Does not create tables
     */
    public function add()
    {
        // Create mandatory instances
        $objSingleSiteInstall = new Install($this->conf, $this->lang, $this->blogId);

        if($objSingleSiteInstall->checkDataExists() === FALSE)
        {
            // We register post types here only because we want to run 'flush_rewrite_rules()' bellow.
            // First, we "add" the custom post type via the above written function.
            // Note: "add" is written with quotes, as CPTs don't get added to the DB,
            // They are only referenced in the post_type column with a post entry,
            // when you add a post of this CPT.
            // Note 2: Registering of these post types has to be inside the sql query, because if there is a slug in db, we should use init section instead
            $objPostType = new PageType($this->conf, $this->lang, $this->conf->getExtensionPrefix().'page');
            $objPostType->register($this->lang->getText('NRS_INSTALL_DEFAULT_PAGE_URL_SLUG_TEXT'), 95);

            $objPostType = new ItemType($this->conf, $this->lang, $this->conf->getExtensionPrefix().'item');
            $objPostType->register($this->lang->getText('NRS_INSTALL_DEFAULT_ITEM_URL_SLUG_TEXT'), 96);

            $objPostType = new LocationType($this->conf, $this->lang, $this->conf->getExtensionPrefix().'location');
            $objPostType->register($this->lang->getText('NRS_INSTALL_DEFAULT_LOCATION_URL_SLUG_TEXT'), 97);

            // Delete any old content if exists
            $objSingleSiteInstall->deleteContent();

            // Then insert all content
            $objSingleSiteInstall->insertContent($this->conf->getExtensionSQLsPath('install.InsertSQL.php', TRUE));
            $this->processDebug($objSingleSiteInstall->getDebugMessages());
            $this->throwExceptionOnFailure($objSingleSiteInstall->getErrorMessages());

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

            // ATTENTION: This is *only* done during plugin activation hook!
            // You should *NEVER EVER* do this on every page load!!
            // And this function is important, because otherwise it was really not working (new url)
            // @note - It flushes rules only for current site:
            //      https://iandunn.name/2015/04/23/flushing-rewrite-rules-on-all-sites-in-a-multisite-network/
            flush_rewrite_rules();
        }

        // Check if the database is up to date
        if($objSingleSiteInstall->isDatabaseVersionUpToDate())
        {
            // Then insert all content
            $objSingleSiteInstall->resetContent($this->conf->getExtensionSQLsPath('reset.ReplaceSQL.php', TRUE));
            $this->processDebug($objSingleSiteInstall->getDebugMessages());
            $this->throwExceptionOnFailure($objSingleSiteInstall->getErrorMessages());

            // Even if the data existed before, having this code out of IF scope, means that we allow
            // to re-register language text to WMPL and elsewhere (this will help us to add not-added texts if some is missing)
            $objLanguagesObserver = new LanguagesObserver($this->conf, $this->lang);
            if($this->lang->canTranslateSQL())
            {
                // If WPML is enabled
                $objLanguagesObserver->registerAllForTranslation();
            }
        }
    }

    /**
     * Only deletes the content, and the roles. Does not delete the tables
     */
    public function delete()
    {
        // Remove Roles
        $objPartnerRole = new Partner($this->conf, $this->lang, $this->conf->getExtensionPrefix().'partner');
        $objPartnerRole->remove(); // Remove roles if exist - Launch only on first time activation

        $objAssistantRole = new Assistant($this->conf, $this->lang, $this->conf->getExtensionPrefix().'assistant');
        $objAssistantRole->remove(); // Remove roles if exist - Launch only on first time activation

        $objManagerRole = new Manager($this->conf, $this->lang, $this->conf->getExtensionPrefix().'manager');
        $objManagerRole->remove(); // Remove roles if exist - Launch only on first time activation

        // Remove all plugin capabilities from WordPress admin role
        $objWPAdminRole = get_role('administrator');
        $capabilitiesToRemove = $objManagerRole->getCapabilities();
        foreach($capabilitiesToRemove AS $capability => $grant)
        {
            $objWPAdminRole->remove_cap($capability);
        }

        // Create mandatory instances
        $objSingleSiteInstall = new Install($this->conf, $this->lang, $this->blogId);
        $objSingleSiteInstall->deleteContent();

        flush_rewrite_rules();
    }

    /**
     * Deletes roles and drops tables
     */
    public function uninstall()
    {
        // Remove Roles
        $objRole = new Partner($this->conf, $this->lang, $this->conf->getExtensionPrefix().'partner');
        $objRole->remove(); // Remove roles if exist - Launch only on first time activation

        $objRole = new Assistant($this->conf, $this->lang, $this->conf->getExtensionPrefix().'assistant');
        $objRole->remove(); // Remove roles if exist - Launch only on first time activation

        $objRole = new Manager($this->conf, $this->lang, $this->conf->getExtensionPrefix().'manager');
        $objRole->remove(); // Remove roles if exist - Launch only on first time activation

        $objSingleSiteInstall = new Install($this->conf, $this->lang, $this->blogId);
        $objSingleSiteInstall->dropTables();
    }

    protected function wpDebugEnabledDisplay()
    {
        $inDebug = defined('WP_DEBUG') && WP_DEBUG == TRUE && defined('WP_DEBUG_DISPLAY') && WP_DEBUG_DISPLAY == TRUE;

        return $inDebug;
    }

    /**
     * @param array $paramErrorMessages
     * @throws \Exception
     */
    protected function throwExceptionOnFailure(array $paramErrorMessages)
    {
        $errorMessagesToAdd = array();
        foreach($paramErrorMessages AS $paramErrorMessage)
        {
            $errorMessagesToAdd[] = sanitize_text_field($paramErrorMessage);
        }

        if(sizeof($errorMessagesToAdd) > 0)
        {
            $throwMessage = implode('<br />', $errorMessagesToAdd);
            throw new \Exception($throwMessage);
        }
    }

    /**
     * @param array $paramDebugMessages
     * @throws \Exception
     */
    protected function processDebug(array $paramDebugMessages)
    {
        $debugMessagesToAdd = array();
        foreach($paramDebugMessages AS $paramDebugMessage)
        {
            // HTML is allowed here
            $debugMessagesToAdd[] = wp_kses_post($paramDebugMessage);
        }

        if($this->wpDebugEnabledDisplay() && sizeof($debugMessagesToAdd) > 0)
        {
            echo '<br />'.implode('<br />', $debugMessagesToAdd);
        }
    }
}