<?php
/**
 * System styles observer

 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Style;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\File\StaticFile;
use NativeRentalSystem\Models\iObserver;
use NativeRentalSystem\Models\Language\Language;

class SystemStylesObserver implements iObserver
{
    protected $conf             = NULL;
    protected $lang             = NULL;
    protected $settings		    = array();
    protected $debugMode        = 0;

    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings)
    {
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->conf = $paramConf;
        $this->lang = $paramLang;
        // Set saved settings
        $this->settings = $paramSettings;
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    /**
     * Get supported styles in this plugin
     * @return array
     */
    public function getAll()
    {
        $cssFolderPath = $this->conf->getExtensionFrontCSSPath('', FALSE);
        $cssFiles = StaticFile::getFolderFileList($cssFolderPath, "css");

        $retSupportedStyles = array();
        foreach($cssFiles AS $cssFile)
        {
            // Case-insensitive check
            if(stripos($cssFile, "style.") === 0)
            {
                $cssTemplateData = get_file_data($cssFolderPath.$cssFile, array('StyleName' => 'Style Name'));
                $retSupportedStyles[] = array(
                    "style_name" => sanitize_text_field($cssTemplateData['StyleName']),
                    "file_name" => sanitize_text_field($cssFile),
                );
            }
        }

        return $retSupportedStyles;
    }

    public function getDropDownOptions($paramSelectedStyle)
    {
        $retHTML = '';
        $styles = $this->getAll();
        foreach($styles AS $style)
        {
            $selected = $style['style_name'] == $paramSelectedStyle ? ' selected="selected"' : '';
            $retHTML .= '<option value="'.$style['style_name'].'"'.$selected.'>'.$style['style_name'].'</option>'."\n";
        }

        return $retHTML;
    }
}