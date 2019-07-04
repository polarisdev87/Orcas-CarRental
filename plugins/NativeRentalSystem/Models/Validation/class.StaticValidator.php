<?php
/**
 * NRS Modern data validator
 * Note 1: This model does not depend on any other class
 * Note 2: This model must be used in static context only
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Validation;

class StaticValidator
{
    public static function getValidPositiveInteger($paramValue, $defaultValue = 0)
    {
        $validValue = static::isPositiveInteger($paramValue) ? intval($paramValue) : $defaultValue;

        return $validValue;
    }

    public static function getValidInteger($paramValue, $defaultValue = 0)
    {
        $validValue = static::isInteger($paramValue) ? intval($paramValue) : $defaultValue;

        return $validValue;
    }


    /**
     * Returns a valid date or 0000-00-00 if date is not valid
     * @param string $paramDate - date to validate
     * @param string $paramFormat - 'Y-m-d', 'm/d/Y', 'd/m/Y'
     * @return string
     */
    public static function getValidISODate($paramDate, $paramFormat = 'Y-m-d')
    {
        $validISODate = "0000-00-00";
        if(static::isDate($paramDate, $paramFormat))
        {
            if($paramFormat == 'Y-m-d')
            {
                $dateParts = explode("-", $paramDate);
                $validISODate = $dateParts[0]."-".$dateParts[1]."-".$dateParts[2];
            } else if($paramFormat == 'd/m/Y')
            {
                $dateParts = explode("/", $paramDate);
                $validISODate = $dateParts[2]."-".$dateParts[1]."-".$dateParts[0];
            } else if($paramFormat == 'm/d/Y')
            {
                $dateParts = explode("/", $paramDate);
                $validISODate = $dateParts[2]."-".$dateParts[0]."-".$dateParts[1];
            }
        }

        return $validISODate;
    }

    /**
     * Returns a valid time or 00:00:00 if time is not valid
     * @param $paramTime - time to validate
     * @param $paramFormat - 'Y-m-d'
     * @return string - valid time
     */
    public static function getValidISOTime($paramTime, $paramFormat = 'H:i:s')
    {
        $validISOTime = "00:00:00";
        if(static::isTime($paramTime, $paramFormat))
        {
            if($paramFormat == 'H:i:s')
            {
                $timeParts = explode(":", $paramTime);
                $validISOTime = $timeParts[0].":".$timeParts[1].":".$timeParts[2];
            }
        }

        return $validISOTime;
    }

    public static function getUTCTimestampFromLocalISODateTime($paramDate, $paramTime)
    {
        $UTCTimestamp = 0;
        $validISODate = static::getValidISODate($paramDate, 'Y-m-d');
        $validISOTime = static::getValidISOTime($paramTime, 'H:i:s');
        if($validISODate != "0000-00-00")
        {
            $timezoneOffsetInSeconds = get_option('gmt_offset') * 3600;
            $UTCTimestamp = strtotime($validISODate." ".$validISOTime) - $timezoneOffsetInSeconds;
        }

        // DEBUG
        //echo "<br />UTC TIMESTAMP: {$UTCTimestamp}, LOCAL ISO DATE: {$validISODate}, LOCAL ISO TIME: {$validISOTime}";

        return $UTCTimestamp;
    }

    /*
     * Appears that this function is useless in the NRS and nowhere used
     * While my brains still think that I want to add timezone offset, appears that correct way is to subtract it
     * via getUTCTimestampFromLocalISODateTime($paramDate, $paramTime)
     */
    public static function getLocalTimestampFromUTC_ISODateTime($paramDate, $paramTime)
    {
        $localTimestamp = 0;
        $validISODate = static::getValidISODate($paramDate, 'Y-m-d');
        $validISOTime = static::getValidISOTime($paramTime, 'H:i:s');
        if($validISODate != "0000-00-00")
        {
            $timezoneOffsetInSeconds = get_option('gmt_offset') * 3600;
            $localTimestamp = strtotime($validISODate." ".$validISOTime) + $timezoneOffsetInSeconds;
        }
        return $localTimestamp;
    }

    public static function getLocalCurrentTimestamp()
    {
        $localCurrentTimestamp = time() + get_option('gmt_offset') * 3600;
        // DEBUG
        //echo "<br />LOCAL TIMESTAMP: {$localCurrentTimestamp}";

        return $localCurrentTimestamp;
    }

    public static function getTodayStartTimestamp()
    {
        $todayStartTimestamp = strtotime(date("Y-m-d")." 00:00:00");
        // DEBUG
        //echo "<br />TIME: ".time().", TODAY&#39;S START: {$todayStartTimestamp}";

        return $todayStartTimestamp;
    }

    public static function getTodayNoonTimestamp()
    {
        $todayNoonTimestamp = strtotime(date("Y-m-d")." 12:00:00");
        // DEBUG
        //echo "<br />TIME: ".time().", TODAY&#39;S START: {$todayStartTimestamp}";

        return $todayNoonTimestamp;
    }

    public static function getTodayEndTimestamp()
    {
        $todayEndTimestamp = strtotime(date("Y-m-d")." 23:59:59");
        // DEBUG
        //echo "<br />TIME: ".time().", TODAY&#39;S START: {$todayStartTimestamp}";

        return $todayEndTimestamp;
    }

    public static function getStartOfDayTimestampInWebsiteTimezone($paramDate)
    {
        return static::getUTCTimestampFromLocalISODateTime("{$paramDate}", "00:00:00");
    }

    public static function getLocalNoonOfDayTimestampInWebsiteTimezone($paramDate, $paramNoonTime = "12:00:00")
    {
        return static::getUTCTimestampFromLocalISODateTime("{$paramDate}", $paramNoonTime);
    }

    public static function getLocalEndOfDayTimestampInWebsiteTimezone($paramDate)
    {
        return static::getUTCTimestampFromLocalISODateTime("{$paramDate}", "23:59:59");
    }

    public static function getZeroIfDateIsPast($number, $paramYear, $paramMonth, $paramDay)
    {
        $validDate = static::getValidISODate("{$paramYear}-{$paramMonth}-{$paramDay}", "Y-m-d");
        $timestamp = strtotime("{$validDate} 00:00:00");

        return static::getTextIfTimestampIsPast($number, $timestamp, 0);
    }

    public static function getDashInsteadOfNumberIfDateIsPast($number, $paramYear, $paramMonth, $paramDay)
    {
        $validDate = static::getValidISODate("{$paramYear}-{$paramMonth}-{$paramDay}", "Y-m-d");
        $timestamp = strtotime("{$validDate} 00:00:00");

        return static::getTextIfTimestampIsPast($number, $timestamp, "-");
    }

    public static function getDashInsteadOfNumberIfTimestampIsPast($number, $paramTimestamp)
    {
        return static::getTextIfTimestampIsPast($number, $paramTimestamp, "-");
    }

    public static function getZeroIfTimestampIsPast($number, $paramTimestamp)
    {
        return static::getTextIfTimestampIsPast($number, $paramTimestamp, 0);
    }

    /**
     * Get char instead of number if date is past
     * @param $number
     * @param $paramTimestamp
     * @param $paramTextToReplace
     * @return int
     */
    private static function getTextIfTimestampIsPast($number, $paramTimestamp, $paramTextToReplace = "-")
    {
        $ret = intval($number);
        $validCharToReplace = sanitize_text_field($paramTextToReplace);
        $validTimestamp = static::getValidPositiveInteger($paramTimestamp, 0);
        if(time() > $validTimestamp)
        {
            // Return chosen char instead, if that is a past date
            $ret = $validCharToReplace;
        }

        return $ret;
    }

    public static function getTotalMonthsBetweenTwoISODates($paramISODateFrom, $paramISODateTo)
    {
        $validDateFrom = static::getValidISODate($paramISODateFrom, 'Y-m-d');
        $validDateTo = static::getValidISODate($paramISODateTo, 'Y-m-d');

        $monthsDifference = 0;
        if($validDateFrom != "0000-00-00" && $validDateTo != "0000-00-00")
        {
            $tsFrom = strtotime($validDateFrom." 00:00:00");
            $tsTo = strtotime($validDateTo." 00:00:00");

            $yearFrom = date('Y', $tsFrom);
            $yearTo = date('Y', $tsTo);

            $monthFrom = date('m', $tsFrom);
            $monthTo = date('m', $tsTo);

            $monthsDifference = (($yearTo - $yearFrom) * 12) + ($monthTo - $monthFrom);
        }

        return $monthsDifference;
    }

    public static function getTotalMonthsBetweenTwoTimestamps($paramTimestampFrom, $paramTimestampTo)
    {
        $validTimestampFrom = static::getValidPositiveInteger($paramTimestampFrom);
        $validTimestampTo = static::getValidPositiveInteger($paramTimestampTo);

        $monthsDifference = 0;
        if($validTimestampFrom > 0 && $validTimestampTo > 0)
        {
            $yearFrom = date('Y', $validTimestampFrom);
            $yearTo = date('Y', $validTimestampTo);

            $monthFrom = date('m', $validTimestampFrom);
            $monthTo = date('m', $validTimestampTo);

            $monthsDifference = (($yearTo - $yearFrom) * 12) + ($monthTo - $monthFrom);
        }

        return $monthsDifference;
    }

    /**
     * Security function to check if number is a positive integer
     * @param $input - value to check
     * @return bool
     */
    public static function isPositiveInteger($input)
    {
        if(!is_array($input))
        {
            return (ctype_digit(strval($input)));
        } else
        {
            return false;
        }
    }

    /**
     * Security function to check if number is a integer
     * Tests:
     * (string) -10 -->     -10
     *      -10     -->     -10
     *      10.2    -->     -1
     *      8F      -->     -1
     * @param $input - value to check
     * @return bool
     */
    public static function isInteger($input)
    {
        if(!is_array($input))
        {
            $stringInput = "{$input}";
            //echo "<br />INPUT: {$stringInput}, [0]: ".$stringInput[0];
            if($stringInput[0] == '-')
            {
                //echo ". Is negative.";
                return ctype_digit(substr($stringInput, 1));
            }
            return ctype_digit($stringInput);
        } else
        {
            return false;
        }
    }

    /**
     * Security function to check date
     * @param $paramDate - date to check
     * @param string $paramFormat - date format
     * @return bool
     */
    public static function isDate($paramDate, $paramFormat = "Y-m-d")
    {
        $ret = false;
        if($paramFormat == "Y-m-d" && !is_array($paramDate))
        {
            $dateParts = explode("-", $paramDate);
            if(sizeof($dateParts) == 3)
            {
                $year = static::getValidPositiveInteger($dateParts[0], "0000");
                $month = static::getValidPositiveInteger($dateParts[1], "00");
                $day = static::getValidPositiveInteger($dateParts[2], "00");
                $ret = checkdate($month, $day, $year);
            }
        } else if($paramFormat == 'd/m/Y' && !is_array($paramDate))
        {
            $dateParts = explode("/", $paramDate);
            if(sizeof($dateParts) == 3)
            {
                $year = static::getValidPositiveInteger($dateParts[2], "0000");
                $month = static::getValidPositiveInteger($dateParts[1], "00");
                $day = static::getValidPositiveInteger($dateParts[0], "00");
                $ret = checkdate($month, $day, $year);
            }
        } else if($paramFormat == 'm/d/Y')
        {
            $dateParts = explode("/", $paramDate);
            if(sizeof($dateParts) == 3)
            {
                $year = static::getValidPositiveInteger($dateParts[2], "0000");
                $month = static::getValidPositiveInteger($dateParts[0], "00");
                $day = static::getValidPositiveInteger($dateParts[1], "00");
                $ret = checkdate($month, $day, $year);
            }
        }
        return $ret;
    }

    /**
     * Security function to check time
     * @param $paramTime - value to check
     * @param string $format - time format
     * @return bool
     */
    public static function isTime($paramTime, $format = "H:i:s")
    {
        $ret = false;
        if($format == "H:i:s" && !is_array($paramTime))
        {
            $timeParts = explode(":", $paramTime);
            if(sizeof($timeParts) == 3)
            {
                $hour = isset($timeParts[0]) ? static::getValidPositiveInteger($timeParts[0]) : "00";
                $min = isset($timeParts[1]) ? static::getValidPositiveInteger($timeParts[1]) : "00";
                $sec = isset($timeParts[2]) ? static::getValidPositiveInteger($timeParts[2]) : "00";
                $ret = static::checkTime($hour, $min, $sec);
            }
        }
        return $ret;
    }

    public static function checkTime($paramHour, $paramMin, $paramSec)
    {
        if($paramHour < 0 || $paramHour > 23 || !is_numeric($paramHour))
        {
            return false;
        }
        if($paramMin < 0 || $paramMin > 59 || !is_numeric($paramMin))
        {
            return false;
        }
        if($paramSec < 0 || $paramSec > 59 || !is_numeric($paramSec))
        {
            return false;
        }
        return true;
    }

    /**
     * Returns period, or zero if TILL before FROM
     * @param int $paramFromTimestamp
     * @param int $paramTillTimestamp
     * @param bool $negativeReturnAllowed - do we allow negative number to be returned
     * @return int
     */
    public static function getPeriod($paramFromTimestamp, $paramTillTimestamp, $negativeReturnAllowed = FALSE)
    {
        $period = intval($paramTillTimestamp - $paramFromTimestamp);
        if($period < 0 && $negativeReturnAllowed == FALSE)
        {
            $period = 0;
        }
        return $period;
    }


    public static function getMySqlDate($date)
    {
        return date("Y-m-d", strtotime($date." 00:00:00"));
    }

    public static function isValidDateFormat($paramFormat)
    {
        $arrValidDateFormats = array("Y-m-d", "m/d/Y", "d/m/Y", "y-m-d", "m/d/y", "d/m/y", "D");
        return in_array($paramFormat, $arrValidDateFormats);
    }

    public static function isValidTimeFormat($paramFormat)
    {
        $arrValidDateFormats = array("H:i:s", "h:i:s", "H:i", "h:i");
        return in_array($paramFormat, $arrValidDateFormats);
    }

    /**
     * MUST BE GMT ADJUSTMENT - so that if user search for 2015-09-06 14:00, it would return back 2015-09-06 14:00
     * @param $paramTimestamp
     * @param $paramFormat - "Y-m-d", "m/d/Y", "d/m/Y", "D", "H:i:s", "H:i"
     * @return string
     */
    public static function getLocalDateByTimestamp($paramTimestamp, $paramFormat = "Y-m-d")
    {
        if(static::isValidDateFormat($paramFormat) || static::isValidTimeFormat($paramFormat))
        {
            $validFormat = sanitize_text_field($paramFormat);
        } else
        {
            $validFormat = "Y-m-d";
        }
        return date($validFormat, $paramTimestamp + get_option( 'gmt_offset' ) * 3600);
    }

    /**
     * This function uses lower number to return, days converted to hours
     * @param $paramSeconds
     * @return float
     */
    public static function getFloorMinutesFromSeconds($paramSeconds)
    {
        $intMinutesOnly = floor($paramSeconds / 60);  // 1

        return $intMinutesOnly;
    }

    /**
     * This function uses higher number to return, days converted to hours
     * @param $paramSeconds
     * @return float
     */
    public static function getCeilMinutesFromSeconds($paramSeconds)
    {
        $intMinutesOnly = ceil($paramSeconds / 60);  // 1

        return $intMinutesOnly;
    }

    /**
     * This function uses lower number to return, days not included
     * @param $paramSeconds
     * @return float
     */
    public static function getFloorHoursOnLastDayFromSeconds($paramSeconds)
    {
        $intDaysOnly = floor($paramSeconds / 86400);      // 1
        $additionalSeconds = $paramSeconds - $intDaysOnly*86400;
        $intHoursOnly = floor($additionalSeconds / 3600);

        return $intHoursOnly;
    }

    /**
     * This function uses higher number to return, days not included
     * @param $paramSeconds
     * @return float
     */
    public static function getCeilHoursOnLastDayFromSeconds($paramSeconds)
    {
        $intDaysOnly = floor($paramSeconds / 86400);      // 1
        $additionalSeconds = $paramSeconds - $intDaysOnly*86400;
        $intHoursOnly = ceil($additionalSeconds / 3600);

        return $intHoursOnly;
    }

    /**
     * This function uses lower number to return, days converted to hours
     * @param $paramSeconds
     * @return float
     */
    public static function getFloorHoursFromSeconds($paramSeconds)
    {
        $intHoursOnly = floor($paramSeconds / 3600);  // 1

        return $intHoursOnly;
    }

    /**
     * This function uses higher number to return, days converted to hours
     * @param $paramSeconds
     * @return float
     */
    public static function getCeilHoursFromSeconds($paramSeconds)
    {
        $intHoursOnly = ceil($paramSeconds / 3600);  // 1

        return $intHoursOnly;
    }

    /**
     * This function uses lower number to return
     * @param $paramSeconds
     * @return float
     */
    public static function getFloorDaysFromSeconds($paramSeconds)
    {
        $intDaysOnly = floor($paramSeconds / 86400);      // 1

        return $intDaysOnly;
    }

    /**
     * This function uses higher number to return
     * @param $paramSeconds
     * @return float
     */
    public static function getCeilDaysFromSeconds($paramSeconds)
    {
        $intDaysOnly = ceil($paramSeconds / 86400);      // 1

        return $intDaysOnly;
    }

    public static function getFloorDaysAndFloorHoursFromSeconds($paramSeconds)
    {
        $combined = array(
            "days" => static::getFloorDaysFromSeconds($paramSeconds),
            "hours" => static::getFloorHoursOnLastDayFromSeconds($paramSeconds),
        );

        return $combined;
    }

    public static function getFloorDaysAndCeilHoursFromSeconds($paramSeconds)
    {
        $combined = array(
            "days" => static::getFloorDaysFromSeconds($paramSeconds),
            "hours" => static::getCeilHoursOnLastDayFromSeconds($paramSeconds),
        );

        return $combined;
    }


    /**
     * @param $paramFromTimestamp
     * @param $paramTillTimestamp
     * @return array
     */
    public static function getDateRangeTimestampArray($paramFromTimestamp, $paramTillTimestamp)
    {
        $validFromTimestamp = static::getValidPositiveInteger($paramFromTimestamp);
        $validTillTimestamp = static::getValidPositiveInteger($paramTillTimestamp);

        // Always start from day 1 by adding the start date to timestamps stack
        $arrDateTimestamps = array($validFromTimestamp);

        // Continue from day 2
        $currentTimestamp = $validFromTimestamp + 86400;
        // We must use <, not <=, because if person ordered a car at 9:00:00 and drop's it at 9:00:00 next day, we count it as one day.
        while ($currentTimestamp < $validTillTimestamp)
        {
            $arrDateTimestamps[] = $currentTimestamp;
            $currentTimestamp += 86400; // add 1 day
        }
        return $arrDateTimestamps;
    }

    /**
     * @param $paramFromTimestamp
     * @param $paramTillTimestamp
     * @return array
     */
    public static function getHourRangeTimestampArray($paramFromTimestamp, $paramTillTimestamp)
    {
        $validFromTimestamp = static::getValidPositiveInteger($paramFromTimestamp);
        $validTillTimestamp = static::getValidPositiveInteger($paramTillTimestamp);

        // Always start from hour 1 by adding the start hour to timestamps stack
        $arrHourTimestamps = array($validFromTimestamp);

        // Continue from hour 2
        $currentTimestamp = $validFromTimestamp + 3600;
        // We must use <, not <=, because if person ordered a car at 9:00:00 and drop's it at 10:00:00, we count it as one hour.
        while ($currentTimestamp < $validTillTimestamp)
        {
            $arrHourTimestamps[] = $currentTimestamp;
            $currentTimestamp += 3600; // add 1 hours
        }
        return $arrHourTimestamps;
    }

    /**
     * @param $paramFromTimestamp
     * @param $paramTillTimestamp
     * @return array
     */
    public static function getDayRangeAndHourRangeTimestampArray($paramFromTimestamp, $paramTillTimestamp)
    {
        $validFromTimestamp = static::getValidPositiveInteger($paramFromTimestamp);
        $validTillTimestamp = static::getValidPositiveInteger($paramTillTimestamp);
        $validLastDayFromTimestamp = $validFromTimestamp + floor(($validTillTimestamp - $validFromTimestamp) / 86400) * 86400;
        $arrDateTimestamps = array();
        $arrHourTimestamps = array();


        // Start from day 2, because will take care hours on last day
        $currentDateTimestamp = $validFromTimestamp;
        // We must use <, not <=, because if person ordered a car at 9:00:00 and drop's it at 9:00:00 next day, we count it as one day.
        while ($currentDateTimestamp < $validLastDayFromTimestamp)
        {
            $arrDateTimestamps[] = $currentDateTimestamp;
            $currentDateTimestamp += 86400; // add 1 day
        }

        // Start from last days first hour
        $currentHourTimestamp = $validLastDayFromTimestamp;
        // We must use <, not <=, because if person ordered a car at 9:00:00 and drop's it at 9:00:00 next day, we count it as one day.
        while ($currentHourTimestamp < $validTillTimestamp)
        {
            $arrHourTimestamps[] = $currentHourTimestamp;
            $currentHourTimestamp += 3600; // add 1 hours
        }

        $combined = array(
            "days" => $arrDateTimestamps,
            "hours" => $arrHourTimestamps,
        );
        return $combined;
    }

    public static function getValidArray($paramValuesArray, $paramValidation, $paramDefaultValue)
    {
        $ret = array();
        if(is_array($paramValuesArray))
        {
            foreach($paramValuesArray AS $key => $value)
            {
                if(static::isPositiveInteger($key) && !is_array($value))
                {
                    // We will be strict and process only if array member is integer and nothing else
                    // Plus we will be strict once again, any only process ig $value is NOT an array!
                    $validKey = static::getValidPositiveInteger($key, 0);

                    $ret[$validKey] = static::getValidValue($value, $paramValidation, $paramDefaultValue);
                }
            }
        }

        return $ret;
    }

    public static function getValidValue($paramValue, $paramValidation, $paramDefaultValue)
    {
        $retValue = FALSE;
        if(!is_array($paramValue))
        {
            // Very cool array validation
            if($paramValidation == 'positive_integer')
            {
                // Only positive integers allowed
                $retValue = static::isPositiveInteger($paramValue) ? intval($paramValue) : static::getValidPositiveInteger($paramDefaultValue);
            } else if($paramValidation == 'intval')
            {
                // Both - positive and negative integers allowed
                $retValue = static::isInteger($paramValue) ? intval($paramValue) : intval($paramDefaultValue);
            } else if($paramValidation == 'Y-m-d')
            {
                $retValue = static::isDate($paramValue, 'Y-m-d') ? static::getValidISODate($paramValue, 'Y-m-d') : static::getValidISODate($paramDefaultValue, 'Y-m-d');
            } else if($paramValidation == "d/m/Y")
            {
                $retValue = static::isDate($paramValue, 'd/m/Y') ? static::getValidISODate($paramValue, 'd/m/Y') : static::getValidISODate($paramDefaultValue, 'd/m/Y');
            } else if($paramValidation == "m/d/Y")
            {
                $retValue = static::isDate($paramValue, 'm/d/Y') ? static::getValidISODate($paramValue, 'm/d/Y') : static::getValidISODate($paramDefaultValue, 'm/d/Y');
            } else if($paramValidation == "time_validation")
            {
                $retValue = static::isTime($paramValue, 'H:i:s') ? static::getValidISOTime($paramValue, 'H:i:s') : static::getValidISOTime($paramDefaultValue, 'H:i:s');
            } else if($paramValidation == "email_validation")
            {
                // We don't want to be strict, and allow that we can default email to blank if needed
                $retValue = is_email($paramValue) ? sanitize_email($paramValue) : sanitize_text_field($paramDefaultValue);
            } else if($paramValidation == "guest_text_validation")
            {
                // for names, and input text
                $retValue = sanitize_text_field($paramValue);
            } else if($paramValidation == "guest_multiline_text_validation")
            {
                $retValue = implode("\n", array_map('sanitize_text_field', explode("\n", $paramValue)));
            } else
            {
                $retValue = FALSE;
            }
        }

        return $retValue;
    }


    /**
     * Returns valid class settings. Used when creating new class instances
     * @param $paramSettings
     * @param $paramIndex
     * @param string $paramValidation
     * @param int $paramDefaultValue
     * @param array $paramAllowedValues
     * @return float|int|string
     */
    public static function getValidSetting($paramSettings, $paramIndex, $paramValidation = 'positive_integer', $paramDefaultValue = 0, $paramAllowedValues = array())
    {
        $value = isset($paramSettings[$paramIndex]) ? $paramSettings[$paramIndex] : '';
        // Data validation
        if(!is_array($value) && $paramValidation == 'positive_integer')
        {
            // Only positive integers allowed
            $validValue = static::isPositiveInteger($value) ? intval($value) : static::getValidPositiveInteger($paramDefaultValue);
        } elseif(!is_array($value) && $paramValidation == 'intval')
        {
            // Both - positive and negative integers allowed
            $validValue = static::isInteger($value) ? intval($value) : intval($paramDefaultValue);
        } elseif(!is_array($value) && $paramValidation == "floatval")
        {
            $validValue = floatval($value);
        } elseif(!is_array($value) && $paramValidation == "textval")
        {
            $validValue = sanitize_text_field($value);
        } elseif(!is_array($value) && $paramValidation == "email")
        {
            $validValue = sanitize_email($value);
        } elseif(!is_array($value) && $paramValidation == "date_format")
        {
            $tmpFormat = sanitize_text_field($value);
            $validValue = static::isValidDateFormat($tmpFormat) ? $tmpFormat : $paramDefaultValue;
        } elseif(!is_array($value) && $paramValidation == "time_format")
        {
            $tmpFormat = sanitize_text_field($value);
            $validValue = static::isValidTimeFormat($tmpFormat) ? $tmpFormat : $paramDefaultValue;
        } else
        {
            $validValue = $paramDefaultValue;
        }

        // Specific value validation (if needed)
        if(sizeof($paramAllowedValues) > 0)
        {
            $validValue = in_array($validValue, $paramAllowedValues) ? $validValue : $paramDefaultValue;
        }

        // Set the class member value
        return $validValue;
    }
}