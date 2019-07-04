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

class ExtensionConfiguration implements iConfiguration, iExtensionConfiguration
{
    private $coreConf                     = NULL;
    private $debugMode                    = 0;
    private $arrPathsCache                = array();
    private $arrURLsCache                 = array();

    public function __construct(CoreConfiguration &$paramCoreConf)
    {
        // Set class settings
        $this->coreConf = $paramCoreConf;
    }

    /*** PATH METHODS: START ***/
    /**
     * @note - file_exist and is_readable are server time and resources consuming, so we cache the paths
     * @param string $paramRelativePathAndFile
     * @param bool $paramReturnWithFileName
     * @return string
     */
    public function getExtensionPath($paramRelativePathAndFile = '', $paramReturnWithFileName = TRUE)
    {
        $ret = DIRECTORY_SEPARATOR;
        $validRelativePathAndFile = sanitize_text_field($paramRelativePathAndFile);

        // If the file with path is not yet cached
        if(!isset($this->arrPathsCache[$validRelativePathAndFile]))
        {
            $currentThemeExtensionPath = get_stylesheet_directory().DIRECTORY_SEPARATOR.'Extensions'.DIRECTORY_SEPARATOR.$this->coreConf->getExtensionFolder().DIRECTORY_SEPARATOR;
            $parentThemeExtensionPath = get_template_directory().DIRECTORY_SEPARATOR.'Extensions'.DIRECTORY_SEPARATOR.$this->coreConf->getExtensionFolder().DIRECTORY_SEPARATOR;
            $pluginExtensionPath = $this->coreConf->getPluginExtensionsPath().$this->coreConf->getExtensionFolder().DIRECTORY_SEPARATOR;

            if(is_readable($currentThemeExtensionPath.$validRelativePathAndFile))
            {
                // First - check for extension folder in current theme's folder
                $ret = $currentThemeExtensionPath;
            } else if($currentThemeExtensionPath != $parentThemeExtensionPath && is_readable($parentThemeExtensionPath.$validRelativePathAndFile))
            {
                // Second - check for extension folder in parent theme's folder
                $ret = $parentThemeExtensionPath;
            } else if(is_readable($pluginExtensionPath.$validRelativePathAndFile))
            {
                // Third - check for extension folder in local plugin folder
                $ret = $pluginExtensionPath;
            }

            // Save path to cache for future use
            $this->arrPathsCache[$validRelativePathAndFile] = $ret;

            if($this->debugMode == 2)
            {
                echo "<br /><br /><strong>Checking getExtensionPath(&#39;".$validRelativePathAndFile."&#39;) dirs:</strong>";
                echo "<br />Current theme extension dir: ".$currentThemeExtensionPath.$validRelativePathAndFile;
                echo "<br />Parent theme extension dir: ".$parentThemeExtensionPath.$validRelativePathAndFile;
                echo "<br />Plugin extension dir: ".$pluginExtensionPath.$validRelativePathAndFile;
                echo "<br />Return: ".$ret;
            }
        } else
        {
            // Return path from cache
            $ret = $this->arrPathsCache[$validRelativePathAndFile];

            if($this->debugMode == 2)
            {
                echo "<br /><br /><strong>Checking getExtensionPath(&#39;".$validRelativePathAndFile."&#39;) dirs:</strong>";
                echo "<br />Returned from cache: ".$ret;
            }
        }

        return $ret.($paramReturnWithFileName === TRUE ? $validRelativePathAndFile : '');
    }

    public function getExtensionFrontAssetsPath($paramRelativePathAndFile = '', $paramReturnWithFileName = TRUE)
    {
        $extensionFrontAssetsPath = $this->getExtensionPath('Assets'.DIRECTORY_SEPARATOR.'Front'.DIRECTORY_SEPARATOR.$paramRelativePathAndFile, FALSE)
            .'Assets'.DIRECTORY_SEPARATOR.'Front'.DIRECTORY_SEPARATOR;

        return $extensionFrontAssetsPath.($paramReturnWithFileName === TRUE ? $paramRelativePathAndFile : '');
    }

    public function getExtensionAdminAssetsPath($paramRelativePathAndFile = '', $paramReturnWithFileName = TRUE)
    {
        $extensionAdminAssetsPath = $this->getExtensionPath('Assets'.DIRECTORY_SEPARATOR.'Admin'.DIRECTORY_SEPARATOR.$paramRelativePathAndFile, FALSE)
            .'Assets'.DIRECTORY_SEPARATOR.'Admin'.DIRECTORY_SEPARATOR;

        return $extensionAdminAssetsPath.($paramReturnWithFileName === TRUE ? $paramRelativePathAndFile : '');
    }

    public function getExtensionFrontCSSPath($paramRelativePathAndFile = '', $paramReturnWithFileName = TRUE)
    {
        $extensionFrontCSSPath = $this->getExtensionPath('Assets'.DIRECTORY_SEPARATOR.'Front'.DIRECTORY_SEPARATOR.'CSS'.DIRECTORY_SEPARATOR.$paramRelativePathAndFile, FALSE)
            .'Assets'.DIRECTORY_SEPARATOR.'Front'.DIRECTORY_SEPARATOR.'CSS'.DIRECTORY_SEPARATOR;

        return $extensionFrontCSSPath.($paramReturnWithFileName === TRUE ? $paramRelativePathAndFile : '');
    }

    public function getExtensionAdminCSSPath($paramRelativePathAndFile = '', $paramReturnWithFileName = TRUE)
    {
        $extensionAdminCSSPath = $this->getExtensionPath('Assets'.DIRECTORY_SEPARATOR.'Admin'.DIRECTORY_SEPARATOR.'CSS'.DIRECTORY_SEPARATOR.$paramRelativePathAndFile, FALSE)
            .'Assets'.DIRECTORY_SEPARATOR.'Admin'.DIRECTORY_SEPARATOR.'CSS'.DIRECTORY_SEPARATOR;

        return $extensionAdminCSSPath.($paramReturnWithFileName === TRUE ? $paramRelativePathAndFile : '');
    }

    public function getExtensionFrontFontsPath($paramRelativePathAndFile = '', $paramReturnWithFileName = TRUE)
    {
        $extensionFrontFontsPath = $this->getExtensionPath('Assets'.DIRECTORY_SEPARATOR.'Front'.DIRECTORY_SEPARATOR.'Fonts'.DIRECTORY_SEPARATOR.$paramRelativePathAndFile, FALSE)
            .'Assets'.DIRECTORY_SEPARATOR.'Front'.DIRECTORY_SEPARATOR.'Fonts'.DIRECTORY_SEPARATOR;

        return $extensionFrontFontsPath.($paramReturnWithFileName === TRUE ? $paramRelativePathAndFile : '');
    }

    public function getExtensionAdminFontsPath($paramRelativePathAndFile = '', $paramReturnWithFileName = TRUE)
    {
        $extensionAdminFontsPath = $this->getExtensionPath('Assets'.DIRECTORY_SEPARATOR.'Admin'.DIRECTORY_SEPARATOR.'Fonts'.DIRECTORY_SEPARATOR.$paramRelativePathAndFile, FALSE)
            .'Assets'.DIRECTORY_SEPARATOR.'Admin'.DIRECTORY_SEPARATOR.'Fonts'.DIRECTORY_SEPARATOR;

        return $extensionAdminFontsPath.($paramReturnWithFileName === TRUE ? $paramRelativePathAndFile : '');
    }

    public function getExtensionFrontImagesPath($paramRelativePathAndFile = '', $paramReturnWithFileName = TRUE)
    {
        $extensionFrontImagesPath = $this->getExtensionPath('Assets'.DIRECTORY_SEPARATOR.'Front'.DIRECTORY_SEPARATOR.'Images'.DIRECTORY_SEPARATOR.$paramRelativePathAndFile, FALSE)
            .'Assets'.DIRECTORY_SEPARATOR.'Front'.DIRECTORY_SEPARATOR.'Images'.DIRECTORY_SEPARATOR;

        return $extensionFrontImagesPath.($paramReturnWithFileName === TRUE ? $paramRelativePathAndFile : '');
    }

    public function getExtensionAdminImagesPath($paramRelativePathAndFile = '', $paramReturnWithFileName = TRUE)
    {
        $extensionAdminImagesPath = $this->getExtensionPath('Assets'.DIRECTORY_SEPARATOR.'Admin'.DIRECTORY_SEPARATOR.'Images'.DIRECTORY_SEPARATOR.$paramRelativePathAndFile, FALSE)
            .'Assets'.DIRECTORY_SEPARATOR.'Admin'.DIRECTORY_SEPARATOR.'Images'.DIRECTORY_SEPARATOR;

        return $extensionAdminImagesPath.($paramReturnWithFileName === TRUE ? $paramRelativePathAndFile : '');
    }

    public function getExtensionFrontJSPath($paramRelativePathAndFile = '', $paramReturnWithFileName = TRUE)
    {
        $extensionFrontJSPath = $this->getExtensionPath('Assets'.DIRECTORY_SEPARATOR.'Front'.DIRECTORY_SEPARATOR.'JS'.DIRECTORY_SEPARATOR.$paramRelativePathAndFile, FALSE)
            .'Assets'.DIRECTORY_SEPARATOR.'Front'.DIRECTORY_SEPARATOR.'JS'.DIRECTORY_SEPARATOR;

        return $extensionFrontJSPath.($paramReturnWithFileName === TRUE ? $paramRelativePathAndFile : '');
    }

    public function getExtensionAdminJSPath($paramRelativePathAndFile = '', $paramReturnWithFileName = TRUE)
    {
        $extensionAdminJSPath = $this->getExtensionPath('Assets'.DIRECTORY_SEPARATOR.'Admin'.DIRECTORY_SEPARATOR.'JS'.DIRECTORY_SEPARATOR.$paramRelativePathAndFile, FALSE)
            .'Assets'.DIRECTORY_SEPARATOR.'Admin'.DIRECTORY_SEPARATOR.'JS'.DIRECTORY_SEPARATOR;

        return $extensionAdminJSPath.($paramReturnWithFileName === TRUE ? $paramRelativePathAndFile : '');
    }

    public function getExtensionDemoGalleryPath($paramRelativePathAndFile = '', $paramReturnWithFileName = TRUE)
    {
        $extensionDemoGalleryPath = $this->getExtensionPath('DemoGallery'.DIRECTORY_SEPARATOR.$paramRelativePathAndFile, FALSE)
            .'DemoGallery'.DIRECTORY_SEPARATOR;

        return $extensionDemoGalleryPath.($paramReturnWithFileName === TRUE ? $paramRelativePathAndFile : '');
    }

    public function getExtensionSQLsPath($paramRelativePathAndFile = '', $paramReturnWithFileName = TRUE)
    {
        $extensionDemosPath = $this->getExtensionPath('SQLs'.DIRECTORY_SEPARATOR.$paramRelativePathAndFile, FALSE)
            .'SQLs'.DIRECTORY_SEPARATOR;

        return $extensionDemosPath.($paramReturnWithFileName === TRUE ? $paramRelativePathAndFile : '');
    }

    public function getExtensionLangPath($paramRelativePathAndFile = '', $paramReturnWithFileName = TRUE)
    {
        $extensionLangPath = $this->getExtensionPath('Languages'.DIRECTORY_SEPARATOR.$paramRelativePathAndFile, FALSE)
            .'Languages'.DIRECTORY_SEPARATOR;

        return $extensionLangPath.($paramReturnWithFileName === TRUE ? $paramRelativePathAndFile : '');
    }

    public function getExtensionFrontTemplatesPath($paramRelativePathAndFile = '', $paramReturnWithFileName = TRUE)
    {
        $extensionFrontTemplatesPath = $this->getExtensionPath('Templates'.DIRECTORY_SEPARATOR.'Front'.DIRECTORY_SEPARATOR.$paramRelativePathAndFile, FALSE)
            .'Templates'.DIRECTORY_SEPARATOR.'Front'.DIRECTORY_SEPARATOR;

        return $extensionFrontTemplatesPath.($paramReturnWithFileName === TRUE ? $paramRelativePathAndFile : '');
    }

    public function getExtensionAdminTemplatesPath($paramRelativePathAndFile = '', $paramReturnWithFileName = TRUE)
    {
        $extensionAdminTemplatesPath = $this->getExtensionPath('Templates'.DIRECTORY_SEPARATOR.'Admin'.DIRECTORY_SEPARATOR.$paramRelativePathAndFile, FALSE)
            .'Templates'.DIRECTORY_SEPARATOR.'Admin'.DIRECTORY_SEPARATOR;

        return $extensionAdminTemplatesPath.($paramReturnWithFileName === TRUE ? $paramRelativePathAndFile : '');
    }

    /*** URL METHODS: START ***/
    /**
     * @note - file_exist and is_readable are server time and resources consuming, so we cache the paths
     * @param string $paramRelativeURLAndFile
     * @param bool $paramReturnWithFileName
     * @return string
     */
    public function getExtensionURL($paramRelativeURLAndFile = '', $paramReturnWithFileName = TRUE)
    {
        $ret = '/';
        $validRelativeURLAndFile = sanitize_text_field($paramRelativeURLAndFile);

        // If the file with path is not yet cached
        if(!isset($this->arrURLsCache[$validRelativeURLAndFile]))
        {
            $currentThemeExtensionPath = get_stylesheet_directory().DIRECTORY_SEPARATOR.'Extensions'.DIRECTORY_SEPARATOR.$this->coreConf->getExtensionFolder().DIRECTORY_SEPARATOR;
            $parentThemeExtensionPath = get_template_directory().DIRECTORY_SEPARATOR.'Extensions'.DIRECTORY_SEPARATOR.$this->coreConf->getExtensionFolder().DIRECTORY_SEPARATOR;
            $pluginExtensionPath = $this->coreConf->getPluginExtensionsPath().$this->coreConf->getExtensionFolder().DIRECTORY_SEPARATOR;

            $currentThemeExtensionURL = get_stylesheet_directory_uri().'/Extensions/'.$this->coreConf->getExtensionFolder().'/';
            $parentThemeExtensionURL = get_template_directory().'/Extensions/'.$this->coreConf->getExtensionFolder().'/';
            $pluginExtensionURL = $this->coreConf->getPluginExtensionsURL().$this->coreConf->getExtensionFolder().'/';

            if(file_exists($currentThemeExtensionPath.$validRelativeURLAndFile))
            {
                // First - check for extension folder in current theme's folder
                $ret = $currentThemeExtensionURL;
            } else if($parentThemeExtensionPath != $currentThemeExtensionPath && file_exists($parentThemeExtensionPath.$validRelativeURLAndFile))
            {
                // Second - check for extension folder in parent theme's folder
                $ret = $parentThemeExtensionURL;
            } else if(file_exists($pluginExtensionPath.$validRelativeURLAndFile))
            {
                // Third - check for extension folder in local plugin folder
                $ret = $pluginExtensionURL;
            }

            // Save URL to cache for future use
            $this->arrURLsCache[$validRelativeURLAndFile] = $ret;

            if($this->debugMode == 1)
            {
                echo "<br /><br /><strong>Checking getExtensionURL(&#39;".$validRelativeURLAndFile."&#39;) dirs:</strong>";
                echo "<br />Current theme extension dir: ".$currentThemeExtensionPath.$validRelativeURLAndFile;
                echo "<br />Parent theme extension dir: ".$parentThemeExtensionPath.$validRelativeURLAndFile;
                echo "<br />Plugin extension dir: ".$pluginExtensionPath.$validRelativeURLAndFile;
                echo "<br />Return: ".$ret;
            }
        } else
        {
            // Return URL from cache
            $ret = $this->arrURLsCache[$validRelativeURLAndFile];

            if($this->debugMode == 1)
            {
                echo "<br /><br /><strong>Checking getExtensionURL(&#39;".$validRelativeURLAndFile."&#39;) dirs:</strong>";
                echo "<br />Returned from cache: ".$ret;
            }
        }

        return $ret.($paramReturnWithFileName === TRUE ? $validRelativeURLAndFile : '');
    }

    public function getExtensionFrontAssetsURL($paramRelativeURLAndFile = '', $paramReturnWithFileName = TRUE)
    {
        $extensionFrontAssetsURL = $this->getExtensionURL('Assets/Front/'.$paramRelativeURLAndFile, FALSE).'Assets/Front/';

        return $extensionFrontAssetsURL.($paramReturnWithFileName === TRUE ? $paramRelativeURLAndFile : '');
    }

    public function getExtensionAdminAssetsURL($paramRelativeURLAndFile = '', $paramReturnWithFileName = TRUE)
    {
        $extensionAdminAssetsURL = $this->getExtensionURL('Assets/Admin/'.$paramRelativeURLAndFile, FALSE).'Assets/Admin/';

        return $extensionAdminAssetsURL.($paramReturnWithFileName === TRUE ? $paramRelativeURLAndFile : '');
    }

    public function getExtensionFrontCSSURL($paramRelativeURLAndFile = '', $paramReturnWithFileName = TRUE)
    {
        $extensionFrontCSSURL = $this->getExtensionURL('Assets/Front/CSS/'.$paramRelativeURLAndFile, FALSE).'Assets/Front/CSS/';

        return $extensionFrontCSSURL.($paramReturnWithFileName === TRUE ? $paramRelativeURLAndFile : '');
    }

    public function getExtensionAdminCSSURL($paramRelativeURLAndFile = '', $paramReturnWithFileName = TRUE)
    {
        $extensionAdminCSSURL = $this->getExtensionURL('Assets/Admin/CSS/'.$paramRelativeURLAndFile, FALSE).'Assets/Admin/CSS/';

        return $extensionAdminCSSURL.($paramReturnWithFileName === TRUE ? $paramRelativeURLAndFile : '');
    }

    public function getExtensionFrontFontsURL($paramRelativeURLAndFile = '', $paramReturnWithFileName = TRUE)
    {
        $extensionFrontFontsURL = $this->getExtensionURL('Assets/Front/Fonts/'.$paramRelativeURLAndFile, FALSE).'Assets/Front/Fonts/';

        return $extensionFrontFontsURL.($paramReturnWithFileName === TRUE ? $paramRelativeURLAndFile : '');
    }

    public function getExtensionAdminFontsURL($paramRelativeURLAndFile = '', $paramReturnWithFileName = TRUE)
    {
        $extensionAdminFontsURL = $this->getExtensionURL('Assets/Admin/Fonts/'.$paramRelativeURLAndFile, FALSE).'Assets/Admin/Fonts/';

        return $extensionAdminFontsURL.($paramReturnWithFileName === TRUE ? $paramRelativeURLAndFile : '');
    }

    public function getExtensionFrontImagesURL($paramRelativeURLAndFile = '', $paramReturnWithFileName = TRUE)
    {
        $extensionFrontImagesURL = $this->getExtensionURL('Assets/Front/Images/'.$paramRelativeURLAndFile, FALSE).'Assets/Front/Images/';

        return $extensionFrontImagesURL.($paramReturnWithFileName === TRUE ? $paramRelativeURLAndFile : '');
    }

    public function getExtensionAdminImagesURL($paramRelativeURLAndFile = '', $paramReturnWithFileName = TRUE)
    {
        $extensionAdminImagesURL = $this->getExtensionURL('Assets/Admin/Images/'.$paramRelativeURLAndFile, FALSE).'Assets/Admin/Images/';

        return $extensionAdminImagesURL.($paramReturnWithFileName === TRUE ? $paramRelativeURLAndFile : '');
    }

    public function getExtensionFrontJSURL($paramRelativeURLAndFile = '', $paramReturnWithFileName = TRUE)
    {
        $extensionFrontJSURL = $this->getExtensionURL('Assets/Front/JS/'.$paramRelativeURLAndFile, FALSE).'Assets/Front/JS/';

        return $extensionFrontJSURL.($paramReturnWithFileName === TRUE ? $paramRelativeURLAndFile : '');
    }

    public function getExtensionAdminJSURL($paramRelativeURLAndFile = '', $paramReturnWithFileName = TRUE)
    {
        $extensionAdminJSURL = $this->getExtensionURL('Assets/Admin/JS/'.$paramRelativeURLAndFile, FALSE).'Assets/Admin/JS/';

        return $extensionAdminJSURL.($paramReturnWithFileName === TRUE ? $paramRelativeURLAndFile : '');
    }

    public function getExtensionDemoGalleryURL($paramRelativeURLAndFile = '', $paramReturnWithFileName = TRUE)
    {
        $extensionDemoGalleryURL = $this->getExtensionURL('DemoGallery/'.$paramRelativeURLAndFile, FALSE).'DemoGallery/';

        return $extensionDemoGalleryURL.($paramReturnWithFileName === TRUE ? $paramRelativeURLAndFile : '');
    }

    public function getExtensionSQLsURL($paramRelativeURLAndFile = '', $paramReturnWithFileName = TRUE)
    {
        $extensionDemosURL = $this->getExtensionURL('SQLs/'.$paramRelativeURLAndFile, FALSE).'SQLs/';

        return $extensionDemosURL.($paramReturnWithFileName === TRUE ? $paramRelativeURLAndFile : '');
    }

    public function getExtensionLangURL($paramRelativeURLAndFile = '', $paramReturnWithFileName = TRUE)
    {
        $extensionLangURL = $this->getExtensionURL('Languages/'.$paramRelativeURLAndFile, FALSE).'Languages/';

        return $extensionLangURL.($paramReturnWithFileName === TRUE ? $paramRelativeURLAndFile : '');
    }

    public function getExtensionFrontTemplatesURL($paramRelativeURLAndFile = '', $paramReturnWithFileName = TRUE)
    {
        $extensionFrontTemplatesURL = $this->getExtensionURL('Templates/Front/'.$paramRelativeURLAndFile, FALSE).'Templates/Front/';

        return $extensionFrontTemplatesURL.($paramReturnWithFileName === TRUE ? $paramRelativeURLAndFile : '');
    }

    public function getExtensionAdminTemplatesURL($paramRelativeURLAndFile = '', $paramReturnWithFileName = TRUE)
    {
        $extensionAdminTemplatesURL = $this->getExtensionURL('Templates/Admin/'.$paramRelativeURLAndFile, FALSE).'Templates/Admin/';

        return $extensionAdminTemplatesURL.($paramReturnWithFileName === TRUE ? $paramRelativeURLAndFile : '');
    }


    /** (REPLICATION) CORE METHODS: START */
    public function getInternalWPDB()
    {
        return $this->coreConf->getInternalWPDB();
    }

    public function getBlogId()
    {
        return $this->coreConf->getBlogId();
    }

    public function getRequiredPHPVersion()
    {
        return $this->coreConf->getRequiredPHPVersion();
    }

    public function getCurrentPHPVersion()
    {
        return $this->coreConf->getCurrentPHPVersion();
    }

    public function getRequiredWPVersion()
    {
        return $this->coreConf->getRequiredWPVersion();
    }

    public function getCurrentWPVersion()
    {
        return $this->coreConf->getCurrentWPVersion();
    }

    public function getVersion()
    {
        return $this->coreConf->getVersion();
    }

    public function isNetworkEnabled()
    {
        return $this->coreConf->isNetworkEnabled();
    }

    public function getExtensionName()
    {
        return $this->coreConf->getExtensionName();
    }

    public function getExtensionFolder()
    {
        return $this->coreConf->getExtensionFolder();
    }

    public function getVariablePrefix()
    {
        return $this->coreConf->getVariablePrefix();
    }

    public function getExtensionPrefix()
    {
        return $this->coreConf->getExtensionPrefix();
    }

    public function getURLPrefix()
    {
        return $this->coreConf->getURLPrefix();
    }

    public function getBlogPrefix($paramBlogId = -1)
    {
       return $this->coreConf->getBlogPrefix($paramBlogId);
    }

    public function getPrefix()
    {
        return $this->coreConf->getPrefix();
    }

    public function getShortcode()
    {
        return $this->coreConf->getShortcode();
    }

    public function getItemParameter()
    {
        return $this->coreConf->getItemParameter();
    }

    public function getItemPluralParameter()
    {
        return $this->coreConf->getItemPluralParameter();
    }

    public function getManufacturerParameter()
    {
        return $this->coreConf->getManufacturerParameter();
    }

    public function getManufacturerPluralParameter()
    {
        return $this->coreConf->getManufacturerPluralParameter();
    }

    public function getBodyTypeParameter()
    {
        return $this->coreConf->getBodyTypeParameter();
    }

    public function getTransmissionTypeParameter()
    {
        return $this->coreConf->getTransmissionTypeParameter();
    }

    public function getFuelTypeParameter()
    {
        return $this->coreConf->getFuelTypeParameter();
    }

    public function getTextDomain()
    {
        return $this->coreConf->getTextDomain();
    }

    public function getGallery()
    {
        return $this->coreConf->getGallery();
    }


    /*** (REPLICATION) PATH METHODS: START ***/
    public function getPluginPathWithFilename()
    {
        return $this->coreConf->getPluginPathWithFilename();
    }

    public function getPluginPath()
    {
        return $this->coreConf->getPluginPath();
    }

    public function getPluginBasename()
    {
        return $this->coreConf->getPluginBasename();
    }

    public function getPluginDirname()
    {
        return $this->coreConf->getPluginDirname();
    }

    public function getPluginExtensionsPath()
    {
        return $this->coreConf->getPluginExtensionsPath();
    }

    public function getTestsPath()
    {
        return $this->coreConf->getTestsPath();
    }

    public function getGalleryPath()
    {
        return $this->coreConf->getGalleryPath();
    }

    public function getGalleryPathWithoutEndSlash()
    {
        return $this->coreConf->getGalleryPathWithoutEndSlash();
    }

    public function getLibrariesPath()
    {
        return $this->coreConf->getLibrariesPath();
    }

    public function getLibrariesTestPath()
    {
        return $this->coreConf->getLibrariesTestPath();
    }

    public function getPluginLangRelPath()
    {
        return $this->coreConf->getPluginLangRelPath();
    }

    public function getGlobalLangPath()
    {
        return $this->coreConf->getGlobalLangPath();
    }


    /*** (REPLICATION) URL METHODS: START ***/
    public function getPluginURL()
    {
        return $this->coreConf->getPluginURL();
    }

    public function getGalleryURL()
    {
        return $this->coreConf->getGalleryURL();
    }

    public function getPluginExtensionsURL()
    {
        return $this->coreConf->getPluginExtensionsURL();
    }

    public function getTestsURL()
    {
        return $this->coreConf->getTestsURL();
    }
}