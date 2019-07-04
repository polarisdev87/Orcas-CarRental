<?php
/**
 * NRS Control Root Class - we use it in initializer, so it cant be abstract
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Settings;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\iRootObserver;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;

class SettingsObserver implements iRootObserver
{
    protected $conf 	                    = NULL;
    protected $lang 		                = NULL;
    protected $debugMode 	                = 0;
    protected $settings                     = array();

    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    /**
     * We want to keep this public, as we not always sure, if the settings are needed to be set
     */
    public function setSettings()
    {
        $sqlRows = $this->conf->getInternalWPDB()->get_results("
			SELECT conf_key, conf_value
			FROM {$this->conf->getPrefix()}settings
			WHERE blog_id='{$this->conf->getBlogId()}'
		", ARRAY_A);

        foreach ($sqlRows AS $currentRow)
        {
            if($currentRow['conf_key'])
            {
                // make edit ready
                $this->settings[sanitize_key($currentRow['conf_key'])] = stripslashes(trim($currentRow['conf_value']));
            }
        }

        if(isset($this->settings['conf_short_date_format']))
        {
            // Add datepicker format
            $datePickerFormat = "yy/mm/dd";
            if($this->settings['conf_short_date_format'] == "Y-m-d")
            {
                $datePickerFormat = "yy-mm-dd";
            } else if($this->settings['conf_short_date_format'] == "d/m/Y")
            {
                $datePickerFormat = "dd/mm/yy";
            } else if($this->settings['conf_short_date_format'] == "m/d/Y")
            {
                $datePickerFormat = "mm/dd/yy";
            }
            $this->settings['conf_datepicker_date_format'] = $datePickerFormat;
        }
    }

    /**
     * Returns with CONF_ prefix
     * @return array
     */
    public function getSettings()
    {
        return $this->settings;
    }

    public function getSetting($key)
    {
        $ret = "";
        $sanitizedKey = sanitize_key($key);
        if($sanitizedKey != "")
        {
            $ret = isset($this->settings[$sanitizedKey]) ? $this->settings[$sanitizedKey] : "";
        }

        return $ret;
    }

    public function getPrintSetting($key)
    {
        $ret = "";
        $sanitizedKey = sanitize_key($key);
        if($sanitizedKey != "")
        {
            $ret = isset($this->settings['conf_'.$sanitizedKey]) ? esc_html($this->settings['conf_'.$sanitizedKey]) : "";
        }

        return $ret;
    }

    public function getEditSetting($key)
    {
        $ret = "";
        $sanitizedKey = sanitize_key($key);
        if($sanitizedKey != "")
        {
            $ret = isset($this->settings['conf_'.$sanitizedKey]) ? esc_attr($this->settings['conf_'.$sanitizedKey]) : "";
        }

        return $ret;
    }

    /**
     * Visibility/requirement check for customers data fields
     * @param $fieldName
     * @param $type
     * @return bool
     */
    public function getCustomerFieldStatus($fieldName, $type)
    {
        return $this->getSettingsFieldStatus("conf_customer_", $fieldName, $type);
    }

    /**
     * Visibility/requirement check for search fields
     * @param $fieldName
     * @param $type
     * @return bool
     */
    public function getSearchFieldStatus($fieldName, $type)
    {
        return $this->getSettingsFieldStatus("conf_search_", $fieldName, $type);
    }

    // Pull up settings
    /**
     * @param $prefix - "search_" or "customer_"
     * @param $fieldName - i.e. "first_name"
     * @param $type - REQUIRED || VISIBLE
     * @return bool
     */
    private function getSettingsFieldStatus($prefix, $fieldName, $type)
    {
        $fieldVisible = FALSE;
        $fieldRequired = FALSE;

        $sanitizedPrefix = sanitize_text_field($prefix);
        $sanitizedFieldName = sanitize_text_field($fieldName);
        $fullFieldNameVisible = $sanitizedPrefix.$sanitizedFieldName."_visible";
        $fullFieldNameRequired = $sanitizedPrefix.$sanitizedFieldName."_required";

        if($this->getSetting($fullFieldNameVisible) == "1")
        {
            $fieldVisible = TRUE;
        }
        if($fieldVisible && $this->getSetting($fullFieldNameRequired) == "1")
        {
            $fieldRequired = TRUE;
        }

        if($this->debugMode)
        {
            echo "<br /><strong>[Method: getCustomerFieldStatus]:</strong> Visible Field Name: {$fullFieldNameVisible} ".var_export($fieldVisible, TRUE);
            echo ", Required Field Name: {$fullFieldNameRequired} ".var_export($fieldRequired, TRUE);
        }

        return $type == "REQUIRED" ? $fieldRequired : $fieldVisible;
    }

    /**************************************************************************************/
    /****************************** START OF EXTENDED METHODS *****************************/
    /**************************************************************************************/

    /**
     * @param $type - SHORT OR LONG
     * @return string
     */
    public function getPeriodWord($type = "SHORT")
    {
        $periodWord = "";

        if($this->getSetting('conf_price_calculation_type') == 1)
        {
            // Count by days only
            $periodWord = $type == "LONG" ? $this->lang->getText('NRS_PER_DAY_TEXT') : $this->lang->getText('NRS_PER_DAY_SHORT_TEXT');
        } else if($this->getSetting('conf_price_calculation_type') == 2)
        {
            // Count by hours only
            $periodWord = $type == "LONG" ? $this->lang->getText('NRS_PER_HOUR_TEXT') : $this->lang->getText('NRS_PER_HOUR_SHORT_TEXT');
        } else if($this->getSetting('conf_price_calculation_type') == 3)
        {
            // Combined count - days+hours
            $periodWord = $type == "LONG" ? $this->lang->getText('NRS_PER_DAY_TEXT') : $this->lang->getText('NRS_PER_DAY_SHORT_TEXT');
        }
        return $periodWord;
    }

    /**************************************************************************************/
    /**************************************************************************************/
    /**************************************************************************************/
    /**************************************************************************************/

    /**
     * Based on price calculation type from period it will return duration in days
     * @param $paramPeriod
     * @return int
     */
    public function getAdminDaysByPriceTypeFromPeriod($paramPeriod)
    {
        $validPeriod =  StaticValidator::getValidPositiveInteger($paramPeriod, 0);
        if($this->getSetting('conf_price_calculation_type') == "1")
        {
            // By days only
            $retDuration = StaticValidator::getFloorDaysFromSeconds($validPeriod);
        } else if($this->getSetting('conf_price_calculation_type') == "2")
        {
            // By hours only
            $retDuration = 0;
        } else
        {
            // Mixed - Days & Hours
            $retDuration =  StaticValidator::getFloorDaysFromSeconds($validPeriod);
        }

        return $retDuration;
    }

    /**
     * Based on price calculation type from period it will return duration in hours
     * @param $paramPeriod
     * @return int
     */
    public function getAdminHoursByPriceTypeFromPeriod($paramPeriod)
    {
        $validPeriod = StaticValidator::getValidPositiveInteger($paramPeriod, 0);
        if($this->getSetting('conf_price_calculation_type') == "1")
        {
            // By days only
            $retDuration = 0;
        } else if($this->getSetting('conf_price_calculation_type') == "2")
        {
            // By hours only
            $retDuration = StaticValidator::getFloorHoursFromSeconds($validPeriod);
        } else
        {
            // Mixed - Days & Hours
            $retDuration = StaticValidator::getFloorHoursOnLastDayFromSeconds($validPeriod);
        }

        return $retDuration;
    }

    /*****************************************************************************/
    /***************************** SETTINGS SECTION ******************************/
    /*****************************************************************************/

    /**
     * @param int $val
     * @param string $type - "YES/NO" (DEFAULT), "SHOW/HIDE", "ENABLED/DISABLED"
     * @return string
     */
    public function generateOption($val = 0, $type = "YES/NO")
    {
        $htmlOption = '';
        if($type == "SHOW/HIDE")
        {
            $arr = array(
                1 => $this->lang->getText('NRS_ADMIN_VISIBLE_TEXT'),
                0 => $this->lang->getText('NRS_ADMIN_HIDDEN_TEXT'),
            );
        } else if($type == "ENABLED/DISABLED")
        {
            $arr = array(
                1 => $this->lang->getText('NRS_ADMIN_ENABLED_TEXT'),
                0 => $this->lang->getText('NRS_ADMIN_DISABLED_TEXT'),
            );
        } else
        {
            $arr = array(
                1 => $this->lang->getText('NRS_ADMIN_YES_TEXT'),
                0 => $this->lang->getText('NRS_ADMIN_NO_TEXT'),
            );
        }

        foreach($arr as $key => $value)
        {
            if($val == $key)
            {
                $htmlOption .= '<option value="'.$key.'" selected="selected">'.$value.'</option>';
            } else
            {
                $htmlOption .= '<option value="'.$key.'">'.$value.'</option>';
            }
        }
        return $htmlOption;
    }

    public function getExpirationTimeDropDownOptions($selectedPeriod, $minPeriod, $maxPeriod)
    {
        // EXPIRATION TIMES
        $expirationPeriods = array(
            '0' => $this->lang->getText('NRS_ADMIN_NEVER_TEXT'),
            '120' => '2 '.$this->lang->getText('NRS_MINUTES_TEXT'),
            '300' => '5 '.$this->lang->getText('NRS_MINUTES_TEXT'),
            '600' => '10 '.$this->lang->getText('NRS_MINUTES2_TEXT'),
            '1200' => '20 '.$this->lang->getText('NRS_MINUTES2_TEXT'),
            '1800' => '30 '.$this->lang->getText('NRS_MINUTES2_TEXT'),
            '2700' => '45 '.$this->lang->getText('NRS_MINUTES_TEXT'),
            '3600' => '1 '.$this->lang->getText('NRS_HOUR_TEXT'),
            '7200' => '2 '.$this->lang->getText('NRS_HOUR_TEXT'),
            '10800' => '3 '.$this->lang->getText('NRS_HOURS_TEXT'),
            '14400' => '4 '.$this->lang->getText('NRS_HOURS_TEXT'),
            '18000' => '5 '.$this->lang->getText('NRS_HOURS_TEXT'),
            '21600' => '5 '.$this->lang->getText('NRS_HOURS_TEXT'),
            '43200' => '12 '.$this->lang->getText('NRS_HOURS2_TEXT'),
            '86400' => '1 '.$this->lang->getText('NRS_DAYS_TEXT'),
            '172800' => '2 '.$this->lang->getText('NRS_DAYS_TEXT'),
            '259200' => '3 '.$this->lang->getText('NRS_DAYS_TEXT'),
            '345600' => '4 '.$this->lang->getText('NRS_DAYS_TEXT'),
            '432000' => '5 '.$this->lang->getText('NRS_DAYS_TEXT'),
            '518400' => '6 '.$this->lang->getText('NRS_DAYS_TEXT'),
            '604800' => '7 '.$this->lang->getText('NRS_DAYS_TEXT'),
            '2592000' => '30 '.$this->lang->getText('NRS_DAYS2_TEXT'),
            '5184000' => '60 '.$this->lang->getText('NRS_DAYS2_TEXT'),
            '7776000' => '90 '.$this->lang->getText('NRS_DAYS2_TEXT'),
        );
        $html = '';

        foreach($expirationPeriods as $periodInSeconds => $periodTitle)
        {
            if(
                ($periodInSeconds >= $minPeriod || $periodInSeconds == 0)
                && $periodInSeconds <= $maxPeriod
            ){
                $selected = $periodInSeconds == $selectedPeriod ? ' selected="selected"' : '';
                $html .= '<option value="'.$periodInSeconds.'"'.$selected.'>'.$periodTitle.'</option>'."\n";
            }
        }

        return $html;
    }
}