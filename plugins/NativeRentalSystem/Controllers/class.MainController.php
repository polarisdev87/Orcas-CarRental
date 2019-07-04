<?php
/**
 * NRS Main controller
 * Final class cannot be inherited anymore. We use them when creating new instances
 * @description This file is the main entry point to the plugin that will handle all requests from WordPress
 * and add actions, filters, etc. as necessary. So we simply declare the class and add a constructor.
 * @note 1: In this class we use full qualifiers (without 'use', except for CoreConfiguration, which is already included).
 *          We do this, to ensure, that nobody will try to use any of these classes before the autoloader is called.
 * @note 2: This class must not depend on any static model
 * @note 3: All Controllers and Models should have full path in the class
 * @note 4: Fatal errors on this file cannot be translated
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers;
// This class file was statically included, so that's why we can use here the keyword 'use'.
// The rest class files are loaded dynamically, and SHOULD NOT be listed bellow with keyword 'use' for code quality reasons.
use NativeRentalSystem\Models\Configuration\CoreConfiguration;

final class MainController extends AbstractController
{
    // Configuration object reference
    private $conf       = NULL;
    private $lang       = NULL;

    public function __construct(CoreConfiguration &$paramCoreConf)
    {
        parent::__construct($paramCoreConf);
    }

    /**
     * Note: Do not add try {} catch {} for this block, as this method includes WordPress hooks.
     *   For those hooks handling we have individual methods in this class bellow, where the try {} catch {} is used.
     */
    public function run()
    {
        if($this->canProcess)
        {
            //
            // 3. API Hooks
            //

            add_action('parse_request', array(&$this, 'frontEndAPICallback'), 0);

            //
            // 4. Activation Hooks
            //
            // Check whether the current request is for an administrative interface page, and check if we not doing admin ajax
            // More at: https://codex.wordpress.org/AJAX_in_Plugins
            if(is_admin() || is_network_admin())
            {
                // Note! Initialize the two lines bellow for every extension!
                // ATTENTION: This is *only* done during plugin activation hook!
                register_activation_hook($this->coreConf->getPluginPathWithFilename(), array(&$this, 'networkActivate'));
                register_deactivation_hook($this->coreConf->getPluginPathWithFilename(), array(&$this, 'networkDeactivate'));

                // Add network admin menu items
                add_action('network_admin_menu', array(&$this, 'loadNetworkAdmin'));
                // Remove admin footer text
                add_filter('admin_footer_text', array(&$this, 'removeAdminFooterText'));
                // Remove network admin footer text
                add_filter('network_admin_menu', array(&$this, 'removeAdminFooterVersion'));
            }
            if(is_admin())
            {
                // Note! Initialize the two lines bellow for every extension!
                // ATTENTION: This is *only* done during plugin activation hook!
                // register_activation_hook($this->coreConf->getPluginPathWithFilename(), array(&$this, 'activate'));
                // register_deactivation_hook($this->coreConf->getPluginPathWithFilename(), array(&$this, 'deactivate'));

                // Add network / regular admin menu items
                add_action('admin_menu', array(&$this, 'loadAdmin'));
                // Remove admin footer text
                add_filter('admin_footer_text', array(&$this, 'removeAdminFooterText'));
                // Remove admin footer text
                add_filter('admin_menu', array(&$this, 'removeAdminFooterVersion'));

                // Admin AJAX must be inside is_admin() if case to prevent security risks
                // of non-admins running admin ajax queries and getting results
                add_action('wp_ajax_'.$this->coreConf->getExtensionPrefix().'admin_api', array(&$this, 'adminAPICallback'));
            }

            //
            // 5. New blog creation hook
            // 'wpmu_new_blog' is an action triggered whenever a new blog is created within a multisite network
            //
            add_action( 'wpmu_new_blog', array(&$this, 'newBlogAdded'), 10, 6);

            //
            // 6. Blog deletion hook (fired every time when new blog is deleted in multisite)
            // More: https://developer.wordpress.org/reference/hooks/delete_blog/
            // More: http://wordpress.stackexchange.com/questions/82961/perform-action-on-wpmu-blog-deletion
            // More: https://codex.wordpress.org/Plugin_API/Action_Reference/delete_blog
            // More: http://wordpress.stackexchange.com/questions/130462/is-there-a-hook-or-a-function-for-multisite-blog-deactivate-or-delete
            // Should be replaced by 'wpmu_delete_blog' (since WP 4.8 https://wpseek.com/function/wpmu_delete_blog/ )
            // https://developer.wordpress.org/reference/hooks/delete_blog/
            // Fires before a site is deleted.
            //
            add_action('delete_blog', array(&$this, 'newBlogDeleted'), 10, 6);


            //
            // 7. Shortcode hook
            //
            add_shortcode($this->coreConf->getShortcode(), array(&$this, 'parseShortcode'));

            //
            // 8. Run on init - internationalization, custom post type and visitor session registration
            //

            add_action('init', array(&$this, 'runOnInit'), 0);
        }
    }

    /**
     * Activate (enable+install or enable only) plugin for across the whole network
     * @note - 'get_sites' function requires WordPress 4.6 or newer!
     */
    public function networkActivate()
    {
        if($this->canProcess && is_multisite() && function_exists('get_sites') && class_exists('WP_Site_Query'))
        {
            try
            {
                // Load the extension configuration, only if it is not yet loaded
                $conf = $this->conf();
                // Save original locale
                $orgLang = $this->lang;

                $sites = get_sites();
                foreach ($sites AS $site)
                {
                    $blogId = $site->blog_id;
                    switch_to_blog($blogId);

                    // AS get_locale() is not allowed to process in install process, we have to use a workaround here and do a direct call to WP table:
                    // Get next invoice id (booking_id is ok here)
                    $sqlQuery = "SELECT option_value FROM `{$conf->getBlogPrefix($blogId)}options` WHERE option_name='WPLANG'";
                    $blogLocaleResult = $conf->getInternalWPDB()->get_var($sqlQuery);
                    $blogLocale = !is_null($blogLocaleResult) && $blogLocaleResult != '' ? $blogLocaleResult : 'en_US';

                    $lang = new \NativeRentalSystem\Models\Language\Language(
                        $conf->getTextDomain(), $conf->getGlobalLangPath(), $conf->getExtensionLangPath('', FALSE), $blogLocale
                    );

                    // DEBUG
                    //echo "<br />BLOG ID:".$blogId.", LOCALE IS: ".get_locale()." (and do not change in the install), we used (WPLANG): ".$blogLocale;

                    $objInstaller = new \NativeRentalSystem\Controllers\Admin\InstallController($conf, $lang, $blogId);
                    $objInstaller->install();
                    $objInstaller->add();
                }
                // Switch back to current blog id. Restore current blog won't work here, as it would just restore to previous blog of the long loop
                switch_to_blog($conf->getBlogId());
                // Restore original locale
                $this->lang = $orgLang;
            }
            catch (\Exception $e)
            {
               $this->processError(__FUNCTION__, $e->getMessage());
            }
        } else if($this->canProcess && is_multisite() === FALSE)
        {
            // A workaround until WP will get fixed
            $this->activate();
        }
    }

    /**
     * Deactivate plugin for across the whole network
     * @note - 'get_sites' function requires WordPress 4.6 or newer!
     */
    public function networkDeactivate()
    {
        if($this->canProcess && is_multisite() && function_exists('get_sites') && class_exists('WP_Site_Query'))
        {
            try
            {
                // Load the extension configuration, only if it is not yet loaded
                $conf = $this->conf();

                $sites = get_sites();
                foreach ($sites AS $site)
                {
                    $blogId = $site->blog_id;
                    switch_to_blog($blogId);
                    flush_rewrite_rules();
                }

                // Switch back to current blog id. Restore current blog won't work here, as it would just restore to previous blog of the long loop
                switch_to_blog($conf->getBlogId());
            }
            catch (\Exception $e)
            {
                $this->processError(__FUNCTION__, $e->getMessage());
            }
        } else if($this->canProcess && is_multisite() === FALSE)
        {
            // A workaround until WP will get fixed
            $this->deactivate();
        }
    }

    public function activate()
    {
        if($this->canProcess)
        {
            try
            {
                // Load the extension configuration, only if it is not yet loaded
                $conf = $this->conf();

                // Note: Don't move $lang to parameter bellow, or WordPress will generate an installation warning
                $lang = $this->i18n();
                // Install plugin for single site
                $objInstaller = new \NativeRentalSystem\Controllers\Admin\InstallController($conf, $lang, $conf->getBlogId());
                $objInstaller->install();
                $objInstaller->add();
            }
            catch (\Exception $e)
            {
                $this->processError(__FUNCTION__, $e->getMessage());
            }
        }
    }

    public function deactivate()
    {
        if($this->canProcess)
        {
            try
            {
                flush_rewrite_rules();
            }
            catch (\Exception $e)
            {
                $this->processError(__FUNCTION__, $e->getMessage());
            }
        }
    }

    /**
     * newBlogAdded is an action triggered whenever a new blog is created within a multisite network
     * @mote1 - https://codex.wordpress.org/Plugin_API/Action_Reference/wpmu_new_blog
     * @note2 - https://developer.wordpress.org/reference/hooks/wpmu_new_blog/
     * @param int $paramNewBlogId -  Blog ID
     * @param int $paramUserId -  User ID
     * @param string $paramDomain - Site domain
     * @param string $paramPath - Site domain
     * @param int $paramSiteId - Site ID. Only relevant on multi-network installs
     * @param array $paramMeta -  Meta data. Used to set initial site options.
     */
    public function newBlogAdded($paramNewBlogId, $paramUserId, $paramDomain, $paramPath, $paramSiteId, $paramMeta)
    {
        if($this->canProcess)
        {
            try
            {
                // Load the extension configuration, only if it is not yet loaded
                $conf = $this->conf();

                if($conf->isNetworkEnabled())
                {
                    $oldBlogId = $conf->getInternalWPDB()->blogid;
                    switch_to_blog($paramNewBlogId);

                    $lang = new \NativeRentalSystem\Models\Language\Language(
                        $conf->getTextDomain(), $conf->getGlobalLangPath(), $conf->getExtensionLangPath('', FALSE), get_locale()
                    );

                    $objInstaller = new \NativeRentalSystem\Controllers\Admin\InstallController($conf, $lang, $paramNewBlogId);
                    $objInstaller->add();

                    switch_to_blog($oldBlogId);
                }

            }
            catch (\Exception $e)
            {
                $this->processError(__FUNCTION__, $e->getMessage());
            }
        }
    }

    /**
     * @param int $paramBlogIdToDelete Blog ID to delete
     * @param bool $paramDropBlogTables True if blog's table should be dropped. Default is false.
     */
    public function blogDeleted($paramBlogIdToDelete, $paramDropBlogTables)
    {
        if($this->canProcess)
        {
            try
            {
                // Load the extension configuration, only if it is not yet loaded
                $conf = $this->conf();

                if($conf->isNetworkEnabled())
                {
                    $oldBlogId = $conf->getInternalWPDB()->blogid;
                    switch_to_blog($paramBlogIdToDelete);

                    $lang = new \NativeRentalSystem\Models\Language\Language(
                        $conf->getTextDomain(), $conf->getGlobalLangPath(), $conf->getExtensionLangPath('', FALSE), get_locale()
                    );

                    // Install plugin for across the whole network
                    $objInstaller = new \NativeRentalSystem\Controllers\Admin\InstallController($conf, $lang, $paramBlogIdToDelete);
                    $objInstaller->delete();

                    switch_to_blog($oldBlogId);
                }
            }
            catch (\Exception $e)
            {
                $this->processError(__FUNCTION__, $e->getMessage());
            }
        }
    }

    public function uninstall()
    {
        if($this->canProcess)
        {
            try
            {
                // Load the extension configuration, only if it is not yet loaded
                $conf = $this->conf();
                // Load the language file, only if it is not yet loaded
                $lang = $this->i18n();

                if($conf->isNetworkEnabled())
                {
                    $sites = get_sites();
                    foreach ($sites AS $site)
                    {
                        $blogId = $site->blog_id;
                        switch_to_blog($blogId);

                        // Delete all content and uninstall for specific blog id
                        $objInstaller = new \NativeRentalSystem\Controllers\Admin\InstallController($conf, $lang, $blogId);
                        $objInstaller->delete();
                        $objInstaller->uninstall();
                    }
                    // Switch back to current blog id. Restore current blog won't work here, as it would just restore to previous blog of the long loop
                    switch_to_blog($conf->getBlogId());
                } else
                {
                    // Delete all content and uninstall
                    $objInstaller = new \NativeRentalSystem\Controllers\Admin\InstallController($conf, $lang, $conf->getBlogId());
                    $objInstaller->delete();
                    $objInstaller->uninstall();
                }
            }
            catch (\Exception $e)
            {
                $this->processError(__FUNCTION__, $e->getMessage());
            }
        }
    }

    /**
     * This method handles request then generates response using WP_Ajax_Response or standard JSON
     */
    public function adminAPICallback()
    {
        if($this->canProcess)
        {
            try
            {
                // Load the extension configuration, only if it is not yet loaded
                $conf = $this->conf();

                // Load the language file, only if it is not yet loaded
                $lang = $this->i18n();

                // We use $_REQUEST here to support both - jQuery.get and jQuery.post AJAX
                $paramExtensionFolder = isset($_REQUEST['extension']) ? $_REQUEST['extension'] : '';
                $paramAction = isset($_REQUEST['extension_action']) ? $_REQUEST['extension_action'] : "";

                if($paramExtensionFolder == $conf->getExtensionFolder())
                {
                    // Process only if this is the handler for desired extension
                    // This IF case allows us to have more than one plugin enable, and return data based by the extension
                    $objAdminAPIController = new \NativeRentalSystem\Controllers\Admin\APIController($conf, $lang);
                    $objAdminAPIController->handleAPIRequest($paramAction);

                    // @Note: Notice the use of  wp_die(), instead of die() or exit().
                    // Most of the time you should be using wp_die() in your Ajax callback function.
                    // This provides better integration with WordPress and makes it easier to test your code.
                    // Don't forget to stop execution afterward:
                    // This is required to terminate immediately and return a proper response
                    wp_die();
                }
            }
            catch (\Exception $e)
            {
                $this->processError(__FUNCTION__, $e->getMessage());
            }
        }
    }

    /**
     * Sniff Requests
     * This is where we hijack all API requests
     * If $_GET['__api'] is set, we kill WP and serve our data
     * die if API request
     */
    public function frontEndAPICallback()
    {
        try
        {
            // Load the extension configuration, only if it is not yet loaded
            $conf = $this->conf();
            if($this->canProcess && isset($_REQUEST['__'.$conf->getExtensionPrefix().'api']) && $_REQUEST['__'.$conf->getExtensionPrefix().'api'] == 1)
            {
                // Load the language file, only if it is not yet loaded
                $lang = $this->i18n();

                // We use $_REQUEST here to support both - jQuery.get and jQuery.post AJAX
                $paramExtensionFolder = isset($_REQUEST['extension']) ? $_REQUEST['extension'] : '';
                $paramAction = isset($_REQUEST['extension_action']) ? $_REQUEST['extension_action'] : "";

                if($paramExtensionFolder == $conf->getExtensionFolder())
                {
                    $objAPIController = new \NativeRentalSystem\Controllers\Front\APIController($conf, $lang);
                    $objAPIController->handleAPIRequest($paramAction);

                    // For front-end we dont use wp_die();
                    die();
                }
            }
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
    }

    public function loadNetworkAdmin()
    {
        if($this->canProcess)
        {
            try
            {
                // Load the extension configuration, only if it is not yet loaded
                $conf = $this->conf();
                // Load the language file, only if it is not yet loaded
                $lang = $this->i18n();

                // Create mandatory instance
                $objLoader = new \NativeRentalSystem\Controllers\Admin\LoadController($conf, $lang);

                // First - register network admin scripts
                $objLoader->registerScripts();
                // Second - register network admin styles
                $objLoader->registerStyles();
                // Finally load the network admin menu and register all admin pages
                $objLoader->addPluginNetworkAdminMenu(97);
            }
            catch (\Exception $e)
            {
                $this->processError(__FUNCTION__, $e->getMessage());
            }
        }
    }

    public function loadAdmin()
    {
        if($this->canProcess)
        {
            try
            {
                // Load the extension configuration, only if it is not yet loaded
                $conf = $this->conf();
                // Load the language file, only if it is not yet loaded
                $lang = $this->i18n();

                // Set the theme and child theme to config

                // Create mandatory instance
                $objLoader = new \NativeRentalSystem\Controllers\Admin\LoadController($conf, $lang);

                // First - register network admin scripts
                $objLoader->registerScripts();
                // Second - register network admin styles
                $objLoader->registerStyles();
                // Finally load the network admin menu and register all admin pages
                $objLoader->addPluginAdminMenu(98);
            }
            catch (\Exception $e)
            {
                $this->processError(__FUNCTION__, $e->getMessage());
            }
        }
    }

    /**
     * Remove admin footer text - 'Thank you for creating with WordPress'
     * @note - this mostly helps our invoice print to look much more clean
     */
    public function removeAdminFooterText()
    {
        echo '';
    }

    /**
     * Remove admin footer WordPress version
     */
    public function removeAdminFooterVersion()
    {
        remove_filter( 'update_footer', 'core_update_footer' );
    }

    /**
     * Parses NRS shortcode and returns the content
     * @param $attributes
     * @return string
     */
    public function parseShortcode($attributes)
    {
        $retContent = '';
        if($this->canProcess)
        {
            try
            {
                // Load the extension configuration, only if it is not yet loaded
                $conf = $this->conf();
                // Load the language file, only if it is not yet loaded
                $lang = $this->i18n();

                // Create mandatory instances
                $objInstall = new \NativeRentalSystem\Models\Install\Install($this->conf, $this->lang, $conf->getBlogId());

                // Process only if the plugin is installed and there is data for this blog
                if ($objInstall->isInstalled() && $objInstall->checkDataExists())
                {
                    $objLoader = new \NativeRentalSystem\Controllers\Front\LoadController($conf, $lang);

                    // First - register front-end scripts
                    $objLoader->registerScripts();
                    // Second - register front-end styles
                    $objLoader->registerStyles();
                    // Finally - parse the shortcode
                    $retContent = $objLoader->parseShortcode($attributes);
                }
                $this->throwExceptionOnFailure($objInstall->getErrorMessages(), $objInstall->getDebugMessages());
            }
            catch (\Exception $e)
            {
                $this->processError(__FUNCTION__, $e->getMessage());
            }
        }

        return $retContent;
    }

    /**
     * Starts the plug-in main functionality
     */
    public function runOnInit()
    {
        if($this->canProcess)
        {
            try
            {
                // Load the extension configuration, only if it is not yet loaded
                $conf = $this->conf();
                // Load the language file, only if it is not yet loaded
                $lang = $this->i18n();

                // Create mandatory instances
                $objInstall = new \NativeRentalSystem\Models\Install\Install($this->conf, $this->lang, $conf->getBlogId());

                // Process only if the plugin is installed and there is data for this blog
                if ($objInstall->checkBlogIdColumnExists())
                {
                    // Load slugs
                    $sqlPage = "
                        SELECT conf_value AS slug
                        FROM {$conf->getPrefix()}settings
                        WHERE conf_key='conf_page_url_slug' AND blog_id='{$conf->getBlogId()}'
                    ";
                    $sqlItem = "
                        SELECT conf_value AS slug
                        FROM {$conf->getPrefix()}settings
                        WHERE conf_key='conf_item_url_slug' AND blog_id='{$conf->getBlogId()}'
                    ";
                    $sqlLocation = "
                        SELECT conf_value AS slug
                        FROM {$conf->getPrefix()}settings
                        WHERE conf_key='conf_location_url_slug' AND blog_id='{$conf->getBlogId()}'
                    ";
                    $tmpPageUrlSlug = $conf->getInternalWPDB()->get_var($sqlPage);
                    $tmpItemUrlSlug = $conf->getInternalWPDB()->get_var($sqlItem);
                    $tmpLocationUrlSlug = $conf->getInternalWPDB()->get_var($sqlLocation);

                    $pageUrlSlug = !is_null($tmpPageUrlSlug) ? $tmpPageUrlSlug : $lang->getText('NRS_INSTALL_DEFAULT_PAGE_URL_SLUG_TEXT');
                    $itemUrlSlug = !is_null($tmpPageUrlSlug) ? $tmpItemUrlSlug : $lang->getText('NRS_INSTALL_DEFAULT_ITEM_URL_SLUG_TEXT');
                    $locationUrlSlug = !is_null($tmpPageUrlSlug) ? $tmpLocationUrlSlug : $lang->getText('NRS_INSTALL_DEFAULT_LOCATION_URL_SLUG_TEXT');

                    // Hook into the 'init' action so that the function
                    // Containing our post type registration is not necessarily executed.
                    // Note: Initialize line bellow for every extension!
                    $objPostType = new \NativeRentalSystem\Models\Post\PageType($conf, $lang, $conf->getExtensionPrefix().'page');
                    $objPostType->register($pageUrlSlug, 95);

                    $objPostType = new \NativeRentalSystem\Models\Post\ItemType($conf, $lang, $conf->getExtensionPrefix().'item');
                    $objPostType->register($itemUrlSlug, 96);

                    $objPostType = new \NativeRentalSystem\Models\Post\LocationType($conf, $lang, $conf->getExtensionPrefix().'location');
                    $objPostType->register($locationUrlSlug, 97);

                    // Enqueue global mandatory styles
                    $objLoader = new \NativeRentalSystem\Controllers\Front\LoadController($conf, $lang);
                    $objLoader->enqueueMandatoryFrontEndStyles();
                }

                // Set session cookie before any headers will be sent. Start the session, because:
                // 1. Booking search uses session to save booking process
                // 2. NRS admin has ok/error messages saved in sessions
                if(!session_id())
                {
                    session_start();
                }
            }
            catch (\Exception $e)
            {
                $this->processError(__FUNCTION__, $e->getMessage());
            }
        }
    }

    /**
     * Extension configuration.
     * Load the extension configuration, only if it is not yet loaded
     *
     * @access public
     * @return \NativeRentalSystem\Models\Configuration\ExtensionConfiguration
     */
    private function conf()
    {
        // Singleton pattern - load the extension configuration, only if it is not yet loaded
        if(is_null($this->conf))
        {
            $this->conf = new \NativeRentalSystem\Models\Configuration\ExtensionConfiguration($this->coreConf);
        }

        return $this->conf;
    }

    /**
     * Internationalization.
     * Load the language file, only if it is not yet loaded
     *
     * @access public
     * @return \NativeRentalSystem\Models\Language\Language
     */
    private function i18n()
    {
        // Singleton pattern - load the language file, only if it is not yet loaded
        if(is_null($this->lang))
        {
            // Load the extension configuration, only if it is not yet loaded
            $conf = $this->conf();

            // Traditional WordPress plugin locale filter
            // Note 1: We don't want to include the rows bellow to language model class, as they are a part of controller
            // Note 2: Keep in mind that, if the translation do not exist, plugin will load a default english translation file
            $locale = apply_filters('plugin_locale', get_locale(), $this->coreConf->getTextDomain());

            // Load textdomain
            // Loads MO file into the list of domains.
            // Note 1: If the domain already exists, the inclusion will fail. If the MO file is not readable, the inclusion will fail.
            // Note 2: On success, the MO file will be placed in the $l10n global by $domain and will be an gettext_reader object.

            // See 1: http://geertdedeckere.be/article/loading-wordpress-language-files-the-right-way
            // See 2: https://ulrich.pogson.ch/load-theme-plugin-translations
            // wp-content/languages/extension-name/lt_LT.mo
         	load_textdomain($this->coreConf->getTextDomain(), $this->coreConf->getGlobalLangPath().$locale.'.mo');
         	// wp-content/plugins/plugin-name/extensions/extension-name/languages/lt_LT.mo
            load_plugin_textdomain($this->coreConf->getTextDomain(), FALSE, $this->coreConf->getPluginLangRelPath());

            $this->lang = new \NativeRentalSystem\Models\Language\Language(
                $this->coreConf->getTextDomain(), $this->coreConf->getGlobalLangPath(), $conf->getExtensionLangPath('', FALSE), $locale
            );
        }

        return $this->lang;
    }
}