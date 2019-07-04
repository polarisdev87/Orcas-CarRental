<?php
/**
 * Style class to handle visual view

 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Style;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\File\StaticFile;
use NativeRentalSystem\Models\Language\Language;

class Style
{
    protected $conf                 = NULL;
    protected $lang                 = NULL;
    protected $demo                 = NULL;
    protected $debugMode            = 0;
    protected $styleName            = "";
    protected $globalStyles         = array();
    protected $compatibilityStyles  = array();
    protected $systemStyles         = array();

    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, $paramSystemStyle)
    {
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->conf = $paramConf;
        $this->lang = $paramLang;

        // Set style name
        $this->styleName = sanitize_text_field($paramSystemStyle);
    }

    public function setGlobalStyles()
    {
        $cssFolderPath = $this->conf->getExtensionFrontCSSPath('', FALSE);
        $cssFolderURL = $this->conf->getExtensionFrontCSSURL('', FALSE);

        $this->globalStyles = array();
        $this->compatibilityStyles = array();
        $this->systemStyles = array();
        $cssFiles = StaticFile::getFolderFileList($cssFolderPath, "css");
        foreach($cssFiles AS $cssFile)
        {
            // Case-insensitive check
            if(stripos($cssFile, "global.") === 0)
            {
                $cssTemplateData = get_file_data($cssFolderPath.$cssFile, array('StyleName' => 'Style Name'));
                $this->globalStyles[] = array(
                    "style_name" => sanitize_text_field($cssTemplateData['StyleName']),
                    "file_path" => $cssFolderPath,
                    "file_name" => sanitize_text_field($cssFile),
                    "file_url" => $cssFolderURL.sanitize_text_field($cssFile),
                );
            } else if(stripos($cssFile, "compatibility.") === 0)
            {
                $cssTemplateData = get_file_data($cssFolderPath.$cssFile, array('ThemeName' => 'Theme Name'));
                $this->compatibilityStyles[] = array(
                    "theme_name" => sanitize_text_field($cssTemplateData['ThemeName']),
                    "file_path" => $cssFolderPath,
                    "file_name" => sanitize_text_field($cssFile),
                    "file_url" => $cssFolderURL.sanitize_text_field($cssFile),
                );
            }
        }

        if($this->debugMode >= 2)
        {
            echo "<br />CSS FILES:<br />".var_export($cssFiles, TRUE);
            echo "<br />COMPATIBILITY STYLES: ".nl2br(print_r($this->compatibilityStyles, TRUE));
            echo "<br /><br />GLOBAL STYLES: ".nl2br(print_r($this->globalStyles, TRUE));

        }
    }

    public function setLocalStyles()
    {
        $cssFolderPath = $this->conf->getExtensionFrontCSSPath('', FALSE);
        $cssFolderURL = $this->conf->getExtensionFrontCSSURL('', FALSE);

        $this->globalStyles = array();
        $this->compatibilityStyles = array();
        $this->systemStyles = array();
        $cssFiles = StaticFile::getFolderFileList($cssFolderPath, "css");
        foreach($cssFiles AS $cssFile)
        {
            // Case-insensitive check
            if(stripos($cssFile, "style.") === 0)
            {
                $cssTemplateData = get_file_data($cssFolderPath.$cssFile, array('StyleName' => 'Style Name'));
                $this->systemStyles[] = array(
                    "style_name" => sanitize_text_field($cssTemplateData['StyleName']),
                    "file_path" => $cssFolderPath,
                    "file_name" => sanitize_text_field($cssFile),
                    "file_url" => $cssFolderURL.sanitize_text_field($cssFile),
                );
            }
        }

        if($this->debugMode >= 2)
        {
            echo "<br />CSS FILES:<br />".var_export($cssFiles, TRUE);
            echo "<br /><br />SYSTEM STYLES: ".nl2br(print_r($this->systemStyles, TRUE));

        }
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    public function getParentThemeCompatibilityCSSURL()
    {
        // Get parent theme name
        $parentThemeName = "";
        $objParentTheme = wp_get_theme(get_template());
        $objCurrentTheme = wp_get_theme();
        if(!is_null($objParentTheme) && !is_null($objCurrentTheme))
        {
            $parentThemeName = $objParentTheme->get('Name') != $objCurrentTheme->get('Name') ? $objParentTheme->get('Name') : '';
        }

        // Get the stylesheet file and it's path
        $compatibilityFileURL = '';
        foreach($this->compatibilityStyles AS $theme)
        {
            if($theme['theme_name'] == $parentThemeName && $theme['file_name'] != '' && $parentThemeName != '')
            {
                $compatibilityFileURL = $theme['file_url'];
            }
        }

        if($this->debugMode)
        {
            echo "<br />PARENT THEME NAME: {$parentThemeName}";
            echo "<br />PARENT THEME COMPATIBILITY CSS FILE URL: ".$compatibilityFileURL;
        }

        return $compatibilityFileURL;
    }

    public function getCurrentThemeCompatibilityCSSURL()
    {
        // Get current theme name
        $currentThemeName = "";
        $objCurrentTheme = wp_get_theme();
        if(!is_null($objCurrentTheme))
        {
            $currentThemeName = $objCurrentTheme->get('Name');
        }

        // Get the stylesheet file and it's path
        $compatibilityFileURL = '';
        foreach($this->compatibilityStyles AS $theme)
        {
            if($theme['theme_name'] == $currentThemeName && $theme['file_name'] != '')
            {
                $compatibilityFileURL = $theme['file_url'];
            }
        }

        if($this->debugMode)
        {
            echo "<br />CURRENT THEME NAMES: {$currentThemeName}";
            echo "<br />CURRENT THEME COMPATIBILITY CSS FILE URL: ".$compatibilityFileURL;
        }

        return $compatibilityFileURL;
    }

    public function getGlobalCSSURL()
    {
        // Get the stylesheet file and it's path
        $selectedFileURL = '';
        $defaultFileURL = '';
        foreach($this->globalStyles AS $style)
        {
            if($defaultFileURL == '' && $style['file_name'] != '')
            {
                $defaultFileURL = $style['file_url'];
            }
            if($style['style_name'] == $this->styleName && $style['file_name'] != '')
            {
                $selectedFileURL = $style['file_url'];
            }
        }

        // If selected style not exist, then select the last available file
        $fileURL = $selectedFileURL != '' ? $selectedFileURL : $defaultFileURL;

        if($this->debugMode)
        {
            echo "<br />SELECTED GLOBAL STYLE FILE URL: {$selectedFileURL}";
            echo "<br />DEFAULT GLOBAL STYLE FILE URL: {$defaultFileURL}";
            echo "<br />GLOBAL STYLE FILE URL: {$fileURL}";
        }

        return $fileURL;
    }

    public function getSystemCSSURL()
    {
        // Get the stylesheet file and it's path
        $selectedFileURL = '';
        $defaultFileURL = '';
        foreach($this->systemStyles AS $style)
        {
            if($defaultFileURL == '' && $style['file_name'] != '')
            {
                $defaultFileURL = $style['file_url'];
            }
            if($style['style_name'] == $this->styleName && $style['file_name'] != '')
            {
                $selectedFileURL = $style['file_url'];
            }
        }

        // If selected style not exist, then select the last available file
        $fileURL = $selectedFileURL != '' ? $selectedFileURL : $defaultFileURL;

        if($this->debugMode)
        {
            echo "<br />SELECTED LOCAL SYSTEM STYLE FILE URL: {$selectedFileURL}";
            echo "<br />DEFAULT LOCAL SYSTEM STYLE FILE URL: {$defaultFileURL}";
            echo "<br />LOCAL SYSTEM STYLE FILE URL: {$fileURL}";
        }

        return $fileURL;
    }
}