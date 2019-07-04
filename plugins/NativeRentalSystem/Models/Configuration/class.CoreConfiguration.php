<?php
/**
 * NRS Configuration class dependant on template
 * Note 1: This is a root class and do not depend on any other plugin classes
 * Note 2: Final class cannot be inherited anymore. We use them when creating new instances
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Configuration;

class CoreConfiguration implements iConfiguration, iCoreConfiguration
{
    private $internalWPDB                 = NULL;
    private $blogId                       = 1;

    private $requiredPHPVersion           = '5.3.0';
    private $currentPHPVersion            = '5.3.0';
    private $requiredWPVersion            = 4.0;
    private $currentWPVersion             = 4.0;
    private $version                      = 0.0;
    private $extensionName                = "";
    private $extensionFolder              = "";
    private $variablePrefix               = "";
    private $extensionPrefix              = "";
    private $urlPrefix                    = "";
    private $blogPrefix                   = "";
    private $prefix                       = "";
    private $networkEnabled               = FALSE;
    private $shortcode                    = "";
    private $itemParameter                = "";
    private $itemPluralParameter          = "";
    private $manufacturerParameter        = "";
    private $manufacturerPluralParameter  = "";
    private $bodyTypeParameter            = "";
    private $transmissionTypeParameter    = "";
    private $fuelTypeParameter            = "";
    private $textDomain                   = "";
    private $gallery                      = "";

    private $pluginPathWithFilename       = "";
    private $pluginPath                   = "";
    private $pluginBasename               = "";
    private $pluginDirname                = "";
    private $pluginExtensionsPath         = "";
    private $testsPath                    = "";
    private $galleryPath                  = "";
    private $galleryPathWithoutEndSlash   = "";
    private $librariesPath                = "";
    private $librariesTestPath            = "";
    private $pluginLangRelPath            = "";
    private $globalLangPath               = "";

    private $pluginURL                    = "";
    private $galleryURL                   = "";
    private $pluginExtensionsURL          = "";
    private $testsURL                     = "";

    public function __construct(
        \wpdb &$paramWPDB, $paramBlogId, $paramRequiredPHPVersion, $paramCurrentPHPVersion, $paramRequiredWPVersion, $paramCurrentWPVersion,
        $paramVersion, $paramExtensionName, $paramExtensionFolder, $paramVariablePrefix, $paramExtensionPrefix, $paramURLPrefix, $paramShortcode,
        $paramItemParameter, $paramItemPluralParameter, $paramManufacturerParameter, $paramManufacturerPluralParameter,
        $paramBodyTypeParameter, $paramTransmissionTypeParameter, $paramFuelTypeParameter,
        $paramTextDomain, $paramGallery, $paramPluginPathWithFilename
    ) {
        // Makes sure the plugin is defined before trying to use it, because by default it is available only for admin section
        if( ! function_exists( 'is_plugin_active_for_network' ) )
        {
            require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
        }

        $this->internalWPDB = $paramWPDB;
        $this->blogId = absint($paramBlogId);

        $this->requiredPHPVersion           = !is_array($paramRequiredPHPVersion) ? preg_replace('[^0-9\.,]', '', $paramRequiredPHPVersion) : '5.3.0';
        $this->currentPHPVersion            = !is_array($paramCurrentPHPVersion) ? preg_replace('[^0-9\.,]', '', $paramCurrentPHPVersion) : '5.3.0';
        $this->requiredWPVersion            = !is_array($paramRequiredWPVersion) ? preg_replace('[^0-9\.,]', '', $paramRequiredWPVersion) : 0.0;
        $this->currentWPVersion             = !is_array($paramCurrentWPVersion) ? preg_replace('[^0-9\.,]', '', $paramCurrentWPVersion) : 0.0;
        $this->version                      = !is_array($paramVersion) ? preg_replace('[^0-9\.,]', '', $paramVersion) : 0.0;
        // We must use plugin_basename here, despite that we used full path for activation hook, because in database the plugin is still saved UNIX like:
        // network_db_prefix_options:
        //      Row: active_plugins
        //      Value (in JSON): <..>;i:0;s:38:"NativeRentalSystem/CarRentalSystem.php";<..>
        $this->networkEnabled               = is_plugin_active_for_network(plugin_basename($paramPluginPathWithFilename));
        $this->extensionName                = sanitize_text_field($paramExtensionName); // We want to allow uppercase and spaces
        $this->extensionFolder              = !is_array($paramExtensionFolder) ? preg_replace('[^-_0-9a-zA-Z]', '', $paramExtensionFolder) : 'Default'; // No sanitization, uppercase needed
        $this->variablePrefix               = !is_array($paramVariablePrefix) ? preg_replace('[^-_0-9a-zA-Z]', '', $paramVariablePrefix) : 'Default'; // No sanitization, uppercase needed
        $this->extensionPrefix              = sanitize_key($paramExtensionPrefix);
        $this->urlPrefix                    = sanitize_key($paramURLPrefix);

        // We need this for multisite data for regular WordPress tables, i.e. 'posts'.
        $this->blogPrefix                   = $this->internalWPDB->get_blog_prefix($paramBlogId);
        // We don't use unique blog prefix here, as we want to all multisite to work, this means that all sites data should be under same blog id
        // So use internalWPDB->prefix here instead, as it automatically figures out for every site
        // NOTE: Appears that WordPress internalWPDB->prefix cannot figure out himself, so need to do that on our own
        if($this->networkEnabled)
        {
            // Plugin is network-enabled, so we use same blog id for all sites
            $this->prefix                   = $this->internalWPDB->get_blog_prefix(BLOG_ID_CURRENT_SITE).$this->extensionPrefix;
        } else
        {
            // Plugin is locally-enabled, so we use same blog id of current site
            $this->prefix                   = $this->internalWPDB->prefix.$this->extensionPrefix;
        }
        $this->shortcode                    = sanitize_key($paramShortcode);
        $this->itemParameter                = sanitize_key($paramItemParameter);
        $this->itemPluralParameter          = sanitize_key($paramItemPluralParameter);
        $this->manufacturerParameter        = sanitize_key($paramManufacturerParameter);
        $this->manufacturerPluralParameter  = sanitize_key($paramManufacturerPluralParameter);
        $this->bodyTypeParameter            = sanitize_key($paramBodyTypeParameter);
        $this->transmissionTypeParameter    = sanitize_key($paramTransmissionTypeParameter);
        $this->fuelTypeParameter            = sanitize_key($paramFuelTypeParameter);
        $this->textDomain                   = sanitize_key($paramTextDomain);
        $this->gallery                      = !is_array($paramGallery) ? preg_replace('[^-_0-9a-zA-Z]', '', $paramGallery) : ''; // No sanitization, uppercase needed

        // Global Settings
        // Note 1: It's ok to use 'sanitize_text_field' function here,
        //       because this function does not escape or remove the '/' char in path.
        // Note 2: We use __FILE__ to make sure that we are not dependant on plugin folder name
        // Note 3: WordPress constants overview - http://wpengineer.com/2382/wordpress-constants-overview/
        // Demo examples (__FILE__ = $this->pluginFolderAndFile):
        // 1. __FILE__ => /GitHub/NativeRental/wp-content/plugins/NativeRentalSystem/CarRentalSystem.php
        // 2. plugin_dir_path(__FILE__) => /GitHub/NativeRental/wp-content/plugins/NativeRentalSystem/ (with trailing slash at the end)
        // 3. plugin_basename(__FILE__) => NativeRentalSystem/CarRentalSystem.php (used for active plugins list in WP database)
        // 4. dirname(plugin_basename((__FILE__)) => native-rental-system
        // 5. extensionLangRelPath used for load_textdomain, i.e. native-rental-system/extensions/car/languages
        $this->pluginPathWithFilename = sanitize_text_field($paramPluginPathWithFilename); // Leave directory separator UNIX like here, used in WP hooks
        $this->pluginPath = str_replace('\\', DIRECTORY_SEPARATOR, plugin_dir_path($this->pluginPathWithFilename));
        $this->pluginBasename = plugin_basename($this->pluginPathWithFilename); // Leave directory separator UNIX like here, used in WP database
        $this->pluginDirname = dirname(plugin_basename($this->pluginPathWithFilename));
        $this->pluginExtensionsPath = $this->pluginPath.'Extensions'.DIRECTORY_SEPARATOR;
        $this->testsPath = $this->pluginPath.'Tests'.DIRECTORY_SEPARATOR;
        $uploadsDir = wp_upload_dir();
        $this->galleryPath = str_replace('\\', DIRECTORY_SEPARATOR, $uploadsDir['basedir']).DIRECTORY_SEPARATOR.$this->gallery.DIRECTORY_SEPARATOR;
        $this->galleryPathWithoutEndSlash = str_replace('\\', DIRECTORY_SEPARATOR, $uploadsDir['basedir']).DIRECTORY_SEPARATOR.$this->gallery;
        $this->librariesPath = $this->pluginPath.'Libraries'.DIRECTORY_SEPARATOR;
        $this->librariesTestPath = $this->testsPath.'NativeRentalSystem'.DIRECTORY_SEPARATOR.'Libraries'.DIRECTORY_SEPARATOR;
        $this->pluginLangRelPath = $this->pluginDirname.'/Extensions/'.$this->extensionFolder.'/Languages';
        $this->globalLangPath = WP_LANG_DIR.DIRECTORY_SEPARATOR.$this->extensionFolder.DIRECTORY_SEPARATOR;

        // esc_url replaces ' and & chars with &#39; and &amp; - but because we know that exact path,
        // we know it does not contains them, so we don't need to have two versions esc_url and esc_url_raw
        // Demo examples (__FILE__ = $this->pluginFolderAndFile):
        // 1. plugin_dir_url(__FILE__) => http://nativerental.com/wp-content/plugins/native-rental-system/
        $this->pluginURL = esc_url(plugin_dir_url($this->pluginPathWithFilename));
        $this->galleryURL = $uploadsDir['baseurl'].'/'.$this->gallery.'/';
        $this->pluginExtensionsURL = $this->pluginURL.'Extensions/';
        $this->testsURL = $this->pluginURL.'Tests/';
    }
    public function getInternalWPDB()
    {
        return $this->internalWPDB;
    }

    public function getBlogId()
    {
        return $this->blogId;
    }

    public function getRequiredPHPVersion()
    {
        return $this->requiredPHPVersion;
    }

    public function getCurrentPHPVersion()
    {
        return $this->currentPHPVersion;
    }

    public function getRequiredWPVersion()
    {
        return $this->requiredWPVersion;
    }

    public function getCurrentWPVersion()
    {
        return $this->currentWPVersion;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function isNetworkEnabled()
    {
        return $this->networkEnabled;
    }

    public function getExtensionName()
    {
        return $this->extensionName;
    }
    
    public function getExtensionFolder()
    {
        return $this->extensionFolder;
    }

    public function getVariablePrefix()
    {
        return $this->variablePrefix;
    }

    public function getExtensionPrefix()
    {
        return $this->extensionPrefix;
    }

    public function getURLPrefix()
    {
        return $this->urlPrefix;
    }

    /**
     * @note - Differently to plugin full prefix, the blog prefix may be different for sites, as pages can be inserted in different _posts tables
     * @param int $paramBlogId
     * @return string
     */
    public function getBlogPrefix($paramBlogId = -1)
    {
        if($paramBlogId == -1)
        {
            // Skip blog id overriding
            return $this->blogPrefix;
        } else
        {
            return $this->internalWPDB->get_blog_prefix($paramBlogId);

        }
    }

    /**
     * @note - we never use blog_id param here, as the prefix for the site is always the same - despite even if it is multisite and plugin is network enabled
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    public function getShortcode()
    {
        return $this->shortcode;
    }

    public function getItemParameter()
    {
        return $this->itemParameter;
    }

    public function getItemPluralParameter()
    {
        return $this->itemPluralParameter;
    }

    public function getManufacturerParameter()
    {
        return $this->manufacturerParameter;
    }

    public function getManufacturerPluralParameter()
    {
        return $this->manufacturerPluralParameter;
    }

    public function getBodyTypeParameter()
    {
        return $this->bodyTypeParameter;
    }

    public function getTransmissionTypeParameter()
    {
        return $this->transmissionTypeParameter;
    }

    public function getFuelTypeParameter()
    {
        return $this->fuelTypeParameter;
    }

    public function getTextDomain()
    {
        return $this->textDomain;
    }

    public function getGallery()
    {
        return $this->gallery;
    }




    /*** PATH METHODS: START ***/
    public function getPluginPathWithFilename()
    {
        return $this->pluginPathWithFilename;
    }

    public function getPluginPath()
    {
        return $this->pluginPath;
    }

    public function getPluginBasename()
    {
        return $this->pluginBasename;
    }

    public function getPluginDirname()
    {
        return $this->pluginDirname;
    }

    public function getPluginExtensionsPath()
    {
        return $this->pluginExtensionsPath;
    }

    public function getTestsPath()
    {
        return $this->testsPath;
    }

    public function getGalleryPath()
    {
        return $this->galleryPath;
    }

    public function getGalleryPathWithoutEndSlash()
    {
        return $this->galleryPathWithoutEndSlash;
    }

    public function getLibrariesPath()
    {
        return $this->librariesPath;
    }

    public function getLibrariesTestPath()
    {
        return $this->librariesTestPath;
    }

    /**
     * extensionLangRelPath used for load_textdomain, i.e. NativeRentalSystem/Extensions/CarRental/Languages
     * @note - Do not use DIRECTORY_SEPARATOR for this file, as it used for WP-TEXT-DOMAIN definition and always should be the same

     * @return string
     */
    public function getPluginLangRelPath()
    {
        return $this->pluginLangRelPath;
    }

    public function getGlobalLangPath()
    {
        return $this->globalLangPath;
    }


    /*** URL METHODS: START ***/
    /**
     * Demo examples (__FILE__ = $this->pluginFolderAndFile):
     * plugin_dir_url(__FILE__) => http://nativerental.com/wp-content/plugins/native-rental-system/
     * @return string
     */
    public function getPluginURL()
    {
        return $this->pluginURL;
    }

    public function getGalleryURL()
    {
        return $this->galleryURL;
    }

    public function getPluginExtensionsURL()
    {
        return $this->pluginExtensionsURL;
    }

    public function getTestsURL()
    {
        return $this->testsURL;
    }
}