<?php
/**
 * Language Manager

 * @note: This class is made to work without any other external plugin classes,
 * and it should remain that for independent include support.
 * @note 2: We can use static:: here, because version check already happened before
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Language;

class Language implements iLanguage
{
    // This is the error text before the language file will be loaded
    const NRS_ERROR_UNABLE_TO_LOAD_LANGUAGE_FILE_TEXT = 'Unable to load %s language file from none of it&#39;s 2 paths.';
    private $lang = array();
    private $textDomain = 'unknown';
    private $WMPLEnabled = FALSE;
    /*
     * We can keep this ON until we don't have 1000's of entries in item list, options list, location list or extras list
     * It does not applies to bookings table, nor customers table, so in most of scenarios it will be ok.
     * @note - depends on $WMPLEnabled
     */
    private $translateDatabase = TRUE;

    public function __construct($paramTextDomain, $paramGlobalLangPath, $paramExtensionLangPath, $paramLocale = "en_US")
    {
	
        $this->setLocale($paramGlobalLangPath, $paramExtensionLangPath, $paramLocale);
        $this->setTranslate($paramTextDomain);
    }

    // Load locale file
    private function setLocale($paramGlobalLangPath, $paramExtensionLangPath, $paramLocale = "en_US")
    {
        $lang = array();
        $validGlobalLangPath = sanitize_text_field($paramGlobalLangPath);
		
        $validExtensionLangPath = sanitize_text_field($paramExtensionLangPath);
		
        //$validLocale = !is_array($paramLocale) ? preg_replace('[^-_0-9a-zA-Z]', '', $paramLocale) : 'en_US';
		
		$validLocale = sanitize_text_field($paramLocale);
		
	

        // If the lt_LT.php file do not exist neither in wp-content/languages/<EXTENSION>/,
        // nor in wp-content/plugins/NativeRentalSystem/Extensions/<EXTENSION>/Languages folders
        if(
            is_readable($validGlobalLangPath.$validLocale.'.php') === FALSE &&
            is_readable($validExtensionLangPath.$validLocale.'.php') === FALSE
        )
        {
            // then set language default to en_US (with en_US.php as a corresponding file)
            $validLocale = "en_US";
        }

        if(is_readable($validGlobalLangPath.$validLocale.'.php'))
        {
            // Include the language file
            require($validGlobalLangPath.$validLocale.'.php');
        } else if(is_readable($validExtensionLangPath.$validLocale.'.php'))
        {
            // Include the language file
            require($validExtensionLangPath.$validLocale.'.php');
        } else
        {
            // Language file is not readable - do not include the language file
            //throw new \Exception(sprintf(static::NRS_ERROR_UNABLE_TO_LOAD_LANGUAGE_FILE_TEXT, $validLocale));
        }
		
		
        // NOTE: This might be a system slowing-down process
        if(sizeof($lang) > 0)
        {
            foreach($lang AS $key => $value)
            {
                $this->addText($key, $value);
            }
        }
        //die("NRS LANG:".$validLocale.", PARAM LOCALE:". $paramLocale.", WP LOCALE:". get_locale());
    }

    private function setTranslate($paramTextDomain)
    {
        $this->textDomain = sanitize_key($paramTextDomain);
        // For the front-end is_plugin_active(..) function is not included automatically
        if(!is_admin() && !is_network_admin())
        {
            include_once(ABSPATH.'wp-admin/includes/plugin.php');
        }
        // WMPL - Determine if WMPL string translation module is enabled
        $this->WMPLEnabled = is_plugin_active('wpml-string-translation/plugin.php');
    }

    /**
     * Add new text row
     * @param $paramKey
     * @param $paramValue
     */
    private function addText($paramKey, $paramValue)
    {
        // Sanitize key
        $sanitizedKey = strtoupper(sanitize_key($paramKey));
        if(strlen($sanitizedKey) > 0)
        {
            // Sanitize value
            $sanitizedValue = sanitize_text_field($paramValue);
    
            // Assign the language internally
            $this->lang[$sanitizedKey] = $sanitizedValue;
        }
    }

    public function getText($paramKey)
    {
        // Sanitize key
        $sanitizedKey = strtoupper(sanitize_key($paramKey));
        $retValue = "";
        if(strlen($sanitizedKey) > 0)
        {
            if(isset($this->lang[$sanitizedKey]))
            {
                $retValue = $this->lang[$sanitizedKey];
            }
        }

        return $retValue;
    }

    /**
     * Is current language is right to left?
     * @return bool
     */
    public function isRTL()
    {
        return ((isset($this->lang['rtl']) && $this->lang['rtl'] == TRUE) ? TRUE : FALSE);
    }

    public function getRTLSuffixIfRTL()
    {
        return $this->isRTL() ? "-rtl" : "";
    }

    public function getItemQuantityText($quantity = 1)
    {
        $text = $this->getUnitsTextByQuantity($quantity, $this->getText('NRS_DAY_TEXT'), $this->getText('NRS_DAYS_TEXT'), $this->getText('NRS_DAYS2_TEXT'));
        $ret = $quantity. " ".$text;

        return $ret;
    }
    public function getExtraQuantityText($quantity = 1)
    {
        $text = $this->getUnitsTextByQuantity($quantity, $this->getText('NRS_DAY_TEXT'), $this->getText('NRS_DAYS_TEXT'), $this->getText('NRS_DAYS2_TEXT'));
        $ret = $quantity. " ".$text;

        return $ret;
    }

    /**
     * Used for items and extras in booking summary
     * Localize the amount text by quantity
     * @param $units
     * @param $singularText
     * @param $pluralText
     * @param $pluralText2
     * @return mixed
     */
    public function getUnitsTextByQuantity($units, $singularText, $pluralText, $pluralText2)
    {
        // Set default - plural text
        $unitsText = $pluralText;

        if($units == 1)
        {
            // Change to singular if it's 1
            $unitsText = $singularText;
        } else if($units == 0 || ($units % 10 == 0) || ($units >= 11 && $units <= 19))
        {
            // Change to plural 2, if it's 0, divides by 10 without fraction (10,20,30,40,..), or is between 11 to 19
            $unitsText = $pluralText2;
        }

        return $unitsText;
    }

    /**
     * Used for items and extras in booking summary
     * Localize the amount text by quantity
     * @param $position
     * @param $textST
     * @param $textND
     * @param $textRD
     * @param $textTH
     * @return string
     */
    public function getOrderExtensionByPosition($position, $textST, $textND, $textRD, $textTH)
    {
        // Set default - th
        $orderExtension = $textTH;

        if($position == 1 || ($position % 10 == 1 && $position >= 20))
        {
            //-st. Change to text 1, if it's 1, divides by 10 with fraction = 1 and is more than 20 (21,31,41,..)
            $orderExtension = $textST;
        } else if($position == 2 || ($position % 10 == 2 && $position >= 20))
        {
            //-nd. Change to text 1, if it's 2, divides by 10 with fraction = 2 and is more than 20 (22,32,42,..)
            $orderExtension = $textND;
        } else if($position == 3 || ($position % 10 == 3 && $position >= 20))
        {
            //-rd. Change to text 1, if it's 3, divides by 10 with fraction = 3 and is more than 20 (23,33,43,..)
            $orderExtension = $textRD;
        } else if($position == 0 || ($position % 10 == 0) || ($position >= 11 && $position <= 19))
        {
            //-th. Change to text 0, if it's 0, divides by 10 without fraction (10,20,30,40,..), or is between 11 to 19
            $orderExtension = $textTH;
        }

        return $orderExtension;
    }


    /**
     * Localize the time text by period
     * @param $period
     * @param $singularText
     * @param $pluralText
     * @param $pluralText2
     * @return mixed
     */
    public function getTimeTextByPeriod($period, $singularText, $pluralText, $pluralText2)
    {
        // Set default - plural text
        $timeText = $pluralText;

        if($period == 1)
        {
            // Change to singular if it's 1
            $timeText = $singularText;
        } else if($period == 0 || ($period % 10 == 0) || ($period >= 11 && $period <= 19))
        {
            // Change to plural 2, if it's 0, divides by 10 without fraction (10,20,30,40,..), or is between 11 to 19
            $timeText = $pluralText2;
        }

        return $timeText;
    }

    public function getPrintFloorDurationByPeriod($paramPriceCalculationType, $paramPeriodInSeconds)
    {
        return $this->getPrintDurationByPeriod("FLOOR", $paramPriceCalculationType, $paramPeriodInSeconds);
    }

    public function getPrintCeilDurationByPeriod($paramPriceCalculationType, $paramPeriodInSeconds)
    {
        return $this->getPrintDurationByPeriod("CEIL", $paramPriceCalculationType, $paramPeriodInSeconds);
    }

    /**
     * @note THIS FUNCTION IS PRIMARY DEFINED AS PUBLIC IN NRS CORE
     * @param $paramRoundingType - "FLOOR", "CEIL"
     * @param int $paramPriceCalculationType - 1 (daily), 2 (hourly) or 3 (mixed - daily & hourly)
     * @param int $paramPeriodInSeconds
     * @return string
     */
    private function getPrintDurationByPeriod($paramRoundingType = "CEIL", $paramPriceCalculationType, $paramPeriodInSeconds)
    {
        $textShowDuration = "";
        if($paramRoundingType == "FLOOR")
        {
            // FLOOR ROUNDING
            $durationInDaysOnly = floor($paramPeriodInSeconds / 86400);
            $durationInHoursOnly = floor($paramPeriodInSeconds / 3600);
            $secondsOnLastDay = $paramPeriodInSeconds-floor($paramPeriodInSeconds / 86400)*86400;
            $durationInDaysAndHours = array(
                "days" => floor($paramPeriodInSeconds / 86400),
                "hours" => floor($secondsOnLastDay / 3600),
            );
        } else
        {
            // CEIL ROUNDING
            $durationInDaysOnly = ceil($paramPeriodInSeconds / 86400);
            $durationInHoursOnly = ceil($paramPeriodInSeconds / 3600);
            $secondsOnLastDay = $paramPeriodInSeconds-floor($paramPeriodInSeconds / 86400)*86400;
            $durationInDaysAndHours = array(
                "days" => floor($paramPeriodInSeconds / 86400),
                "hours" => ceil($secondsOnLastDay / 3600),
            );
        }

        if($paramPriceCalculationType == 1)
        {
            // Count by days only
            $daysText = $this->getTimeTextByPeriod($durationInDaysOnly, $this->getText('NRS_DAY_TEXT'), $this->getText('NRS_DAYS_TEXT'), $this->getText('NRS_DAYS2_TEXT'));
            $textShowDuration = $durationInDaysOnly.' '.$daysText;
        } else if($paramPriceCalculationType == 2)
        {
            // Count by hours only
            $hoursText = $this->getTimeTextByPeriod($durationInHoursOnly, $this->getText('NRS_HOUR_TEXT'), $this->getText('NRS_HOURS_TEXT'), $this->getText('NRS_HOURS2_TEXT'));
            $textShowDuration = $durationInHoursOnly.' '.$hoursText;
        } else if($paramPriceCalculationType == 3)
        {
            // Combined count - days+hours
            $daysText = $this->getTimeTextByPeriod($durationInDaysAndHours['days'], $this->getText('NRS_DAY_TEXT'), $this->getText('NRS_DAYS_TEXT'), $this->getText('NRS_DAYS2_TEXT'));
            $hoursText = $this->getTimeTextByPeriod($durationInDaysAndHours['hours'], $this->getText('NRS_HOUR_TEXT'), $this->getText('NRS_HOURS_TEXT'), $this->getText('NRS_HOURS2_TEXT'));

            if($durationInDaysAndHours['days'] > 0 && $durationInDaysAndHours['hours'] > 0)
            {
                $textShowDuration = $durationInDaysAndHours['days'].' '.$daysText.' '.$durationInDaysAndHours['hours'].' '.$hoursText;
            } else if($durationInDaysAndHours['days'] > 0 && $durationInDaysAndHours['hours'] == 0)
            {
                $textShowDuration = $durationInDaysAndHours['days'].' '.$daysText;
            } else
            {
                $textShowDuration = $durationInDaysAndHours['hours'].' '.$hoursText;
            }
        }
        return $textShowDuration;
    }

    /*************** TRANSLATE PART *****************/
    public function canTranslateSQL()
    {
        if($this->WMPLEnabled === TRUE && $this->translateDatabase === TRUE)
        {
            return TRUE;
        } else
        {
            return FALSE;
        }
    }

    /**
     * Add new text row for translation
     * Used mostly on pre-loaders of all data to register all DB texts
     * @param $paramKey
     * @param $paramValue
     */
    public function register($paramKey, $paramValue)
    {
        // Sanitize key
        $sanitizedKey = strtolower(sanitize_key($paramKey));

        if(strlen($sanitizedKey) > 0)
        {
            // Sanitize value
            $sanitizedValue = sanitize_text_field($paramValue);

            // WPML - Register string for translation with WMPL
            do_action('wpml_register_single_string', $this->textDomain, $sanitizedKey, $sanitizedValue);
        }
    }

    /**
     * @note - we should not do any value sanitization here, as it may break like breaks etc.
     *         All that is done elsewhere
     * @param $paramKey
     * @param $paramNonTranslatedValue
     * @return string
     */
    public function getTranslated($paramKey, $paramNonTranslatedValue)
    {
        $retValue = $paramNonTranslatedValue;

        // Process only if we allow translations
        if($this->canTranslateSQL())
        {
            // Sanitize key
            $sanitizedKey = strtolower(sanitize_key($paramKey));
            if(strlen($sanitizedKey) > 0)
            {
                // WPML - translate single string with WMPL
                $retValue = apply_filters('wpml_translate_single_string', $paramNonTranslatedValue, $this->textDomain, $sanitizedKey);
            }
        }

        return $retValue;
    }
}