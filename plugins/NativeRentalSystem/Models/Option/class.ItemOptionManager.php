<?php
/**
 * NRS Item Option Manager (with setup for single item)

 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Option;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;

class ItemOptionManager implements iOptionManager
{
    private $conf 	    = NULL;
    private $lang 		= NULL;
    private $settings   = array();
    private $debugMode 	= 0;
    private $itemId 	= 0;

    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings, $paramItemId)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;
        $this->settings = $paramSettings;

        $this->itemId = StaticValidator::getValidPositiveInteger($paramItemId, 0);
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    public function getFirstIds()
    {
        $ret = 0;
        $validItemId = StaticValidator::getValidPositiveInteger($this->itemId, 0);
        $optionIds = $this->conf->getInternalWPDB()->get_col("
            SELECT option_id
            FROM {$this->conf->getPrefix()}options
            WHERE item_id='{$validItemId}' AND blog_id='{$this->conf->getBlogId()}'
            ORDER BY option_name ASC
            LIMIT 1
        ");
        if(sizeof($optionIds) > 0)
        {
            $ret = $optionIds[0];
        }

        return $ret;
    }

    public function getAllIds()
    {
        $validItemId = StaticValidator::getValidPositiveInteger($this->itemId, 0);
        $optionIds = $this->conf->getInternalWPDB()->get_col("
            SELECT option_id
            FROM {$this->conf->getPrefix()}options
            WHERE item_id='{$validItemId}' AND blog_id='{$this->conf->getBlogId()}'
            ORDER BY option_name ASC
        ");

        return $optionIds;
    }

    private function getOptions()
    {
        $retOptions = array();

        $optionIds = $this->getAllIds();
        foreach($optionIds AS $optionId)
        {
            $objOption = new ItemOption($this->conf, $this->lang, $this->settings, $optionId);
            $retOptions[] = $objOption->getDetails();
        }

        return $retOptions;
    }

    /**
     * @return int
     */
    public function getTotalOptions()
    {
        $validItemId = StaticValidator::getValidPositiveInteger($this->itemId, 0);
        $totalOptions = $this->conf->getInternalWPDB()->get_var("
			SELECT COUNT(option_id) AS total_options
			FROM {$this->conf->getPrefix()}options
			WHERE item_id='{$validItemId}' AND blog_id='{$this->conf->getBlogId()}'
		");

        return !is_null($totalOptions) ? intval($totalOptions) : 0;
    }

    public function getTranslatedDropDown($paramSelectedOptionId = 0, $paramOptionsMeasurementUnit = "")
    {
        return $this->getDropDown($paramSelectedOptionId, $paramOptionsMeasurementUnit, TRUE);
    }

    public function getDropDown($paramSelectedOptionId = 0, $paramOptionsMeasurementUnit = "", $paramTranslated = FALSE)
    {
        $options = $this->getOptions();
        $validOptionsMeasurementUnit = $paramOptionsMeasurementUnit != '' ? ' '.sanitize_text_field($paramOptionsMeasurementUnit) : '';
        $printOptionsMeasurementUnit = esc_html($validOptionsMeasurementUnit);

        $ret = '';
        $ret .= '<select name="item_options['.$this->itemId.']">';
        foreach($options AS $option)
        {
            $printOptionName = $paramTranslated ? $option['print_translated_option_name'] : $option['print_option_name'];
            $selected = $option['option_id'] == $paramSelectedOptionId ? ' selected="selected"': '';
            $ret .= '<option value="'.$option['option_id'].'"'.$selected.'>'.$printOptionName.$printOptionsMeasurementUnit.'</option>';
        }
        $ret .= '</select>';

        return $ret;
    }


    /**
     * @note - SLIDERS CANNOT BE TRANSLATED AT ALL - THEIR VALUES ARE NUMBERS
     * @param int $paramSelectedOptionId
     * @param string $paramOptionsMeasurementUnit
     * @return string
     */
    public function getSlider($paramSelectedOptionId = 0, $paramOptionsMeasurementUnit = "")
    {
        $options = $this->getOptions();
        $validSelectedOptionId = StaticValidator::getValidPositiveInteger($paramSelectedOptionId, 0);
        $sanitizedOptionsMeasurementUnit = $paramOptionsMeasurementUnit != '' ? ' '.sanitize_text_field($paramOptionsMeasurementUnit) : '';
        $printOptionsMeasurementUnit = esc_html($sanitizedOptionsMeasurementUnit);
        $newOptions = array();
        foreach($options AS $option)
        {
            // In slider there is numbers only - no translation needed
            $newOptions[$option['option_id']] = $option['option_name'];
        }
        // asort - sort array and maintain index association
        asort($newOptions, SORT_NUMERIC);

        reset($newOptions);
        $firstArrayKey = !is_null(key($newOptions)) ? key($newOptions) : 0;

        $allValuesAreAllowedNumbers = TRUE;
        $selectedValue = "";
        $i = 1;
        $prev = 0;
        $step = 1;
        foreach($newOptions AS $id=>$value)
        {
            if(is_numeric($value))
            {
                // find the step
                if($i == 2)
                {
                    $step = abs($value - $prev);
                } else if($i > 2)
                {
                    //i == 2+
                    if(abs($value - $prev) != $step)
                    {
                        // Range are not balanced
                        $allValuesAreAllowedNumbers = FALSE;
                    }
                }
                $prev = $value;
                $i++;
                if($validSelectedOptionId == $id)
                {
                    $selectedValue = $value;
                }
            } else
            {
                $allValuesAreAllowedNumbers = FALSE;
            }
        }
        if($step == 0)
        {
            $allValuesAreAllowedNumbers = FALSE;
        }

        $ret = '';
        if($allValuesAreAllowedNumbers == FALSE)
        {
            // If slider cannot be displayed - do not show slider at all and default to selected option
            $ret .= $this->lang->getText('NRS_ERROR_SLIDER_CANT_BE_DISPLAYED_TEXT');
            $ret .= '<input type="hidden" id="item_option_'.$this->itemId.'" name="item_options['.$this->itemId.']" value="'.$validSelectedOptionId.'" />';
        } else
        {
            $minArrayNumber = min($newOptions);
            $maxArrayNumber = max($newOptions);
            $selectedValue = $selectedValue == "" ? $minArrayNumber : $selectedValue;

            $ret .= '<label for="item_fader_'.$this->itemId.'"></label>';
            $ret .= '<input id="item_fader_'.$this->itemId.'" type="range" min="'.$minArrayNumber.'" max="'.$maxArrayNumber.'" value="'.$selectedValue.'" step="'.$step.'" list="item_volsettings_'.$this->itemId.'"
            oninput="update'.$this->conf->getExtensionFolder().'Output('.$firstArrayKey.', value,\'"item_volume_'.$this->itemId.'\', \'"item_option_'.$this->itemId.'\', \'item_volsettings'.$this->itemId.'\')">';
            $ret .= '<datalist id="item_volsettings'.$this->itemId.'">';
            foreach($newOptions AS $id=>$value)
            {
                // This is an number, no translation or print needed
                $ret .= '<option value="'.$id.'">'.$value.'</option>';
            }
            $ret .= '</datalist>';
            $ret .= '<output for="item_fader_'.$this->itemId.'" id="item_volume_'.$this->itemId.'">'.$selectedValue.'</output>'.$printOptionsMeasurementUnit;
            $ret .= '<input type="hidden" id="item_option_'.$this->itemId.'" name="item_options['.$this->itemId.']" value="'.$validSelectedOptionId.'" />';
        }

        return $ret;
    }
}