<?php
/**
 * NRS Modern data formatter
 * Note 1: This model does not depend on any other class
 * Note 2: This model must be used in static context only
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Formatting;

class StaticFormatter
{
    const WORLD_TIMEZONES_MAX_DIFFERENCE_IN_SECONDS = 43200;

    /**
     * We do not apply validators here because of flexible data and speed
     * @param $array
     * @param $multiplier - how many times to multiply
     * @return array
     */
    public static function getMultipliedNumberArray($array, $multiplier)
    {
        $retArray = array();
        foreach($array AS $key => $number)
        {
            $retArray[$key] = $number * $multiplier;
        }

        return $retArray;
    }

    /**
     * We do not apply validators here because of flexible data and speed
     * @param array $array1
     * @param array $array2
     * @return array
     */
    public static function getSumOfTwoArrays($array1, $array2)
    {
        $sumArray = array();
        foreach($array1 AS $key => $number)
        {
            $sumArray[$key] = isset($array2[$key]) ? $array2[$key] + $number : $number;
        }

        return $sumArray;
    }

    /**
     * We do not apply validators here because of flexible data and speed
     * @param $array
     * @param $formatType
     * @param $currencySymbol
     * @param $currencyCode
     * @param int $currencySymbolLocation
     * @return array
     */
    public static function getFormattedPriceArray($array, $formatType, $currencySymbol, $currencyCode, $currencySymbolLocation = 0)
    {
        $retArray = array();
        foreach($array AS $key => $price)
        {
            $retArray[$key] = static::getFormattedPrice($price, $formatType, $currencySymbol, $currencyCode, $currencySymbolLocation);
        }

        return $retArray;
    }

    /**
     * We do not apply validators here because of flexible data and speed
     * @param $array
     * @param $formatType
     * @return array
     */
    public static function getFormattedPercentageArray($array, $formatType)
    {
        $retArray = array();
        foreach($array AS $key => $percentage)
        {
            $retArray[$key] = static::getFormattedPercentage($percentage, $formatType);
        }

        return $retArray;
    }

    /**
     * We do not apply validators here because of flexible data and speed
     * @param float $price
     * @param string $formatType - tiny, tiny_without_fraction, regular, regular_without_fraction, long, long_without_fraction
     * @param string $currencySymbol
     * @param string $currencyCode
     * @param int $currencySymbolLocation
     * @return string
     */
    public static function getFormattedPrice($price, $formatType, $currencySymbol, $currencyCode, $currencySymbolLocation = 0)
    {
        switch($formatType)
        {
            case "tiny":
                if($currencySymbolLocation)
                {
                    $formattedNumber = number_format_i18n($price, 2).$currencySymbol;
                } else
                {
                    $formattedNumber = $currencySymbol.number_format_i18n($price, 2);
                }
                break;
            case "tiny_without_fraction":
                if($currencySymbolLocation)
                {
                    $formattedNumber = number_format_i18n($price, 0).$currencySymbol;
                } else
                {
                    $formattedNumber = $currencySymbol.number_format_i18n($price, 0);
                }
                break;
            case "regular":
                if($currencySymbolLocation)
                {
                    $formattedNumber = number_format_i18n($price, 2).' '.$currencySymbol;
                } else
                {
                    $formattedNumber = $currencySymbol.' '.number_format_i18n($price, 2);
                }
                break;
            case "regular_without_fraction":
                if($currencySymbolLocation)
                {
                    $formattedNumber = number_format_i18n($price, 0).' '.$currencySymbol;
                } else
                {
                    $formattedNumber = $currencySymbol.' '.number_format_i18n($price, 0);
                }
                break;
            case "long":
                $formattedNumber = number_format_i18n($price, 2)." ".$currencyCode;
                break;
            case "long_without_fraction":
                $formattedNumber = number_format_i18n($price, 0)." ".$currencyCode;
                break;
            default:
                $formattedNumber = $price;
        }

        return $formattedNumber;
    }

    /**
     * We do not apply validators here because of flexible data and speed
     * @param $percentage
     * @param $formatType
     * @return string
     */
    public static function getFormattedPercentage($percentage, $formatType)
    {
        switch($formatType)
        {
            case "tiny":
                $formattedPercentage = number_format_i18n($percentage, 2)."%";
                break;
            case "tiny_without_fraction":
                $formattedPercentage = number_format_i18n($percentage, 0)."%";
                break;
            case "regular":
                $formattedPercentage = number_format_i18n($percentage, 2)." %";
                break;
            case "regular_without_fraction":
                $formattedPercentage = number_format_i18n($percentage, 0)." %";
                break;
            default:
                $formattedPercentage = $percentage;
        }

        return $formattedPercentage;
    }

    /**
     * Based on price calculation type from duration days / hours it will return period (in seconds)
     * @param int $paramPriceCalculationType
     * @param int $paramDays
     * @param int $paramHours
     * @return int
     */
    public static function getPeriodFromByPriceType($paramPriceCalculationType, $paramDays, $paramHours)
    {
        $validDays =  intval($paramDays);
        $validHours = intval($paramHours);
        if($paramPriceCalculationType == 1)
        {
            // Days only
            $retPeriod = $validDays * 86400;
        } else if($paramPriceCalculationType == 2)
        {
            // Hours only
            $retPeriod = $validHours * 3600;
        } else
        {
            // Mixed - Days & Hours
            $retPeriod = $validDays * 86400 + $validHours * 3600;
        }

        return $retPeriod;
    }

    /**
     * Based on price calculation type from duration days / hours it will return period (in seconds)
     * @param int $paramPriceCalculationType
     * @param int $paramDays
     * @param int $paramHours
     * @return int
     */
    public static function getPeriodTillByPriceType($paramPriceCalculationType, $paramDays, $paramHours)
    {
        $validDays =  intval($paramDays);
        $validHours = intval($paramHours);
        if($paramPriceCalculationType == 1)
        {
            // Days only
            $retPeriod = $validDays * 86400 + 86400 - 1;
        } else if($paramPriceCalculationType == 2)
        {
            // Hours only
            $retPeriod = $validHours * 3600 + 3600 - 1;
        } else
        {
            // Mixed - Days & Hours
            $retPeriod = $validDays * 86400 + $validHours * 3600 + 3600 - 1;
        }

        return $retPeriod;
    }

    public static function getPerPeriodPricesArray($paramPricesArray, $paramPeriod, $paramPriceCalculationType)
    {
        $validPeriod = intval($paramPeriod);
        $days = ceil($validPeriod / 86400);
        $hours = ceil($validPeriod / 3600);

        $retArray = array();
        foreach($paramPricesArray AS $key => $price)
        {
            $pricePerPeriod = 0.00;
            if($paramPriceCalculationType == 1)
            {
                // Price Calculation Type = DAYS
                $pricePerPeriod = $days > 0 ? round($price / $days, 2) : 0.00;
            } else if($paramPriceCalculationType == 2)
            {
                // Price Calculation Type = HOURS
                $pricePerPeriod = $hours > 0 ? round($price / $hours, 2) : 0.00;
            } else if($paramPriceCalculationType == 3)
            {
                // Price Calculation Type = MIXED (RESULT IN DAYS)
                $pricePerPeriod = $days > 0 ? round($price / $days, 2) : 0.00;
            }
            $retArray[$key] = $pricePerPeriod;
        }

        return $retArray;
    }

    /**
     * Proper amount formatting for print
     * @param float $paramAmount
     * @param string $paramCurrencySymbol
     * @param bool $paramCurrencySymbolLocation
     * @return string
     */
    public static function getPrintAmount($paramAmount, $paramCurrencySymbol, $paramCurrencySymbolLocation)
    {
        $validAmount = floatval($paramAmount);
        $sanitizedCurrencySymbol = sanitize_text_field($paramCurrencySymbol);

        if($paramCurrencySymbolLocation == 1)
        {
            $printAmount = $validAmount.' '.$sanitizedCurrencySymbol;
        } else
        {
            $printAmount = $sanitizedCurrencySymbol.' '.$validAmount;
        }

        return $printAmount;
    }

    public static function getPrintMessage(array $paramMessages)
    {
        $messagesToAdd = array();
        foreach($paramMessages AS $paramMessage)
        {
            $messagesToAdd[] = sanitize_text_field($paramMessage);
        }

        $printMessage = implode('<br />', $messagesToAdd);

        return $printMessage;
    }

    public static function getAllDaysOfTheMonthArray($year="current", $month="current")
    {
        $ret = array();
        if($year =="current" && $month == "current")
        {
            $startDate = date("Y-m-01");
        } else
        {
            $startDate = date("{$year}-{$month}-01"); // Give in your own start date
        }
        $endDate = date("Y-m-t", strtotime($startDate." 00:00:00"));

        $numberOfDays = (strtotime($endDate." 00:00:00") - strtotime($startDate." 00:00:00"))/86400+1 ; // Add the last day also

        /*DEBUG*/ //echo "START: $startDate, END: $endDate, DIFF: $numberOfDays<br />";

        for ($i = 0; $i < $numberOfDays; $i++) {
            $date = strtotime(date("Y-m-d", strtotime($startDate." 00:00:00")) . " +$i day");
            $ret[] = date('d', $date);
        }

        return $ret;
    }

    public static function getNext30DaysArray($year="current", $month="current", $day="current")
    {
        $ret = array();
        if($year =="current" && $month == "current" && $day == "current")
        {
            $startDate = date("Y-m-d");
        } else
        {
            $startDate = date("{$year}-{$month}-{$day}"); // Give in your own start date
        }

        /*DEBUG*/ //echo "START: $startDate<br />";

        for ($i = 0; $i < 30; $i++) {
            $date = strtotime(date("Y-m-d", strtotime($startDate)) . " +$i day");
            $ret[] = date('d', $date);
        }

        return $ret;
    }

    public static function generateDropDownOptions($from, $to, $selectedValue = "", $defaultValue = "", $defaultText = "", $prefixed = FALSE, $suffix = "")
    {
        $ret = "";
        $suffix = $suffix != '' ? ' '.$suffix : '';

        if($defaultText != "")
        {
            $selected = $selectedValue == $defaultValue ? ' selected="selected"' : "";
            $ret .= '<option value="'.$defaultValue.'"'.$selected.'>'.$defaultText.'</option>';
        }

        for($i = $from; $i <= $to; $i++)
        {
            $prefixedValue = $prefixed ? sprintf('%0'.strlen($to).'d', $i) : $i;
            $selected = $prefixedValue == $selectedValue ? ' selected="selected"' : "";
            $ret .= '<option value="'.$prefixedValue.'"'.$selected.'>'.$i.$suffix.'</option>';
        }
        $ret .= "</select>";

        return $ret;
    }

    public static function priceCompare($a, $b)
    {
        if($a['unit']['discounted_total'] == $b['unit']['discounted_total'])
        {
            return 0;
        }

        return ($a['unit']['discounted_total'] < $b['unit']['discounted_total']) ? -1 : 1;
    }


    /**
     * @param string $paramSelectedTime
     * @param string $paramISOTimeFrom
     * @param string $paramISOTimeTo
     * @param string $paramMidnightText
     * @param string $paramNoonText
     * @param array $paramExcludedTimes - times to exclude, i.e. (09:00:00)
     * @return string
     */
    public static function getTimeDropDownOptions($paramSelectedTime = "09:00:00", $paramISOTimeFrom = "00:00:00", $paramISOTimeTo = "23:30:00", $paramMidnightText = "00:00", $paramNoonText = "12:00", $paramExcludedTimes = array())
    {
        $UTCUnixTimeFrom = strtotime(date("Y-m-d")." ".$paramISOTimeFrom);
        $UTCUnixTimeTo = strtotime(date("Y-m-d")." ".$paramISOTimeTo);
        $timeDropDownOptions = '';
        for($hour = 0; $hour < 24; $hour++)
        {
            for($min = 0; $min < 60; $min = $min+30)
            {
                $currentHour = sprintf("%02d", $hour);
                $currentMin = sprintf("%02d", $min);

                $currentTime = $currentHour.':'.$currentMin.':00';
                $selected = ($currentTime == $paramSelectedTime ? ' selected="selected"' : '');

                $UTCUnixCurrentTime = strtotime(date("Y-m-d")." ".$currentTime);
                if($currentTime == "00:00:00")
                {
                    $printCurrentTime = esc_html(sanitize_text_field($paramMidnightText));
                } else if($currentTime == "12:00:00")
                {
                    $printCurrentTime = esc_html(sanitize_text_field($paramNoonText));
                } else
                {
                    $printCurrentTime = date_i18n(get_option('time_format'), $UTCUnixCurrentTime, TRUE);
                }

                // Show time only it is is not in exclude list
                if($UTCUnixCurrentTime >= $UTCUnixTimeFrom && $UTCUnixCurrentTime <= $UTCUnixTimeTo && !in_array($currentTime, $paramExcludedTimes))
                {
                    $timeDropDownOptions .= '<option value="'.$currentTime.'"'.$selected.'>'.$printCurrentTime.'</option>';
                }
            }
        }

        // Special 23:59:59
        if($paramISOTimeTo == "23:59:59" && !in_array("23:59:59", $paramExcludedTimes))
        {
            $selected = ($paramSelectedTime == "23:59:59" ? ' selected="selected"' : '');

            $UTCUnixCurrentTime = strtotime(date("Y-m-d")." 23:59:59");
            $printCurrentTime = date_i18n(get_option('time_format'), $UTCUnixCurrentTime, TRUE);

            $timeDropDownOptions .= '<option value="23:59:59"'.$selected.'>'.$printCurrentTime.'</option>';
        }

        // DEBUG
        //echo "<br />getTimeDropDownOptions(): FROM-TO TIME: {$paramISOTimeFrom} - {$paramISOTimeTo}, SELECTED: {$paramSelectedTime}";

        return $timeDropDownOptions;
    }

    /**
     * Get drop-down for any admin option
     * @param $paramName
     * @param int $paramValueFrom
     * @param int $paramValueTill
     * @param int $paramSelectedValue
     * @param bool $paramRequired
     * @param string $paramZeroText
     * @return string
     */
    public static function getAdminNumberDropDown($paramName, $paramValueFrom = 0, $paramValueTill = 100, $paramSelectedValue = 0, $paramRequired = FALSE, $paramZeroText = "")
    {
        $required = $paramRequired == FALSE ? "" : "required";

        $numberHTML = '<select name="'.$paramName.'" id="'.$paramName.'" class="'.$required.'">';
        for ($i = $paramValueFrom; $i <= $paramValueTill; $i++)
        {
            $selected = $i == $paramSelectedValue ? ' selected="selected"' : '';
            $value = $i;
            $text = $i == 0 ? $paramZeroText : $i;
            $numberHTML .= '<option value="'.$value.'"'.$selected.'>'.$text.'</option>';
        }
        $numberHTML .= '</select>';

        return $numberHTML;
    }

    /**
     * Get drop-down options for any admin option
     * @param int $paramValueFrom
     * @param int $paramValueTill
     * @param int $paramSelectedValue
     * @param string $paramZeroText
     * @return string
     */
    public static function getNumberDropDownOptions($paramValueFrom = 0, $paramValueTill = 100, $paramSelectedValue = 0, $paramZeroText = "")
    {
        $numberHTML = '';
        for ($i = $paramValueFrom; $i <= $paramValueTill; $i++)
        {
            $selected = $i == $paramSelectedValue ? ' selected="selected"' : '';
            $value = $i;
            $text = $i == 0 ? $paramZeroText : $i;
            $numberHTML .= '<option value="'.$value.'"'.$selected.'>'.$text.'</option>';
        }

        return $numberHTML;
    }
}