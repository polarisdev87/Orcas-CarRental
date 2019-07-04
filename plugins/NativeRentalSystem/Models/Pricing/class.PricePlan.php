<?php
/**
 * Configuration core class implementation for Admin part of the website

 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Pricing;
use NativeRentalSystem\Models\AbstractElement;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\iElement;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;

class PricePlan extends AbstractElement implements iElement
{
    protected $conf 	                = NULL;
    protected $lang 		            = NULL;
    protected $debugMode 	            = 0;
    protected $pricePlanId              = 0;
    protected $shortDateFormat          = "Y-m-d";

    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings, $paramPricePlanId)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;

        // Set price plan id
        $this->pricePlanId = StaticValidator::getValidPositiveInteger($paramPricePlanId, 0);
        if(isset($paramSettings['conf_short_date_format']))
        {
            $this->shortDateFormat = sanitize_text_field($paramSettings['conf_short_date_format']);
        }
    }

    /**
     * For internal class use only
     * @param $paramPricePlanId
     * @return mixed
     */
    private function getDataFromDatabaseById($paramPricePlanId)
    {
        $validPricePlanId = StaticValidator::getValidPositiveInteger($paramPricePlanId, 0);
        $pricePlanData = $this->conf->getInternalWPDB()->get_row("
            SELECT *
            FROM {$this->conf->getPrefix()}price_plans
            WHERE price_plan_id='{$validPricePlanId}'
        ", ARRAY_A);


        return $pricePlanData;
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    public function getId()
    {
        return $this->pricePlanId;
    }

    /**
     * Element-specific function
     * @return int
     */
    public function getPriceGroupId()
    {
        $retPriceGroupId = 0;
        $pricePlanData = $this->getDataFromDatabaseById($this->pricePlanId);
        if(!is_null($pricePlanData))
        {
            $retPriceGroupId = $pricePlanData['price_group_id'];
        }
        return $retPriceGroupId;
    }

    /**
     * Element-specific function
     * @return int
     */
    public function getCouponCode()
    {
        $retCouponCode = "";
        $pricePlanData = $this->getDataFromDatabaseById($this->pricePlanId);
        if(!is_null($pricePlanData))
        {
            $retCouponCode = $pricePlanData['coupon_code'];
        }
        return $retCouponCode;
    }

    /**
     * Checks if current user can edit the element
     * @param $paramPartnerId - partner id is mandatory here, as it comes from other plugin
     * @return bool
     */
    public function canEdit($paramPartnerId)
    {
        $canEdit = FALSE;
        if($this->pricePlanId > 0)
        {
            if(current_user_can('manage_'.$this->conf->getExtensionPrefix().'all_items'))
            {
                $canEdit = TRUE;
            } else if($paramPartnerId > 0 && $paramPartnerId == get_current_user_id() && current_user_can('manage_'.$this->conf->getExtensionPrefix().'own_items'))
            {
                $canEdit = TRUE;
            }
        }

        return $canEdit;
    }

    /**
     * Element-specific function
     * @return bool
     */
    public function isSeasonal()
    {
        $retIsSeasonal = FALSE;
        $pricePlanData = $this->getDataFromDatabaseById($this->pricePlanId);
        if(!is_null($pricePlanData))
        {
            $retIsSeasonal = $pricePlanData['seasonal_price'] == 1 ? TRUE : FALSE;
        }
        return $retIsSeasonal;
    }

    /**
     * @param bool $paramIncludeUnclassified - NOT USED
     * @return mixed
     */
    public function getDetails($paramIncludeUnclassified = FALSE)
    {
        $ret = $this->getDataFromDatabaseById($this->pricePlanId);

        if(!is_null($ret))
        {
            // Make raw
            $ret['coupon_code'] = stripslashes($ret['coupon_code']);

            if($ret['start_timestamp'] > 0)
            {
                $ret['start_date'] = date_i18n($this->shortDateFormat, $ret['start_timestamp'] + get_option('gmt_offset') * 3600, TRUE);
                $ret['start_time'] = date_i18n('H:i', $ret['start_timestamp'] + get_option('gmt_offset') * 3600, TRUE);
                $printStartDate = date_i18n(get_option('date_format'), $ret['start_timestamp'] + get_option('gmt_offset') * 3600, TRUE);
                $printStartTime = date_i18n(get_option('time_format'), $ret['start_timestamp'] + get_option('gmt_offset') * 3600, TRUE);
            } else
            {
                $ret['start_date'] = '';
                $ret['start_time'] = '';
                $printStartDate = $this->lang->getText('NRS_ALL_YEAR_TEXT');
                $printStartTime = $this->lang->getText('NRS_ALL_DAY_TEXT');
            }

            if($ret['end_timestamp'] > 0)
            {
                $ret['end_date'] = date_i18n($this->shortDateFormat, $ret['end_timestamp'] + get_option('gmt_offset') * 3600, TRUE);
                $ret['end_time'] = date_i18n('H:i', $ret['end_timestamp'] + get_option('gmt_offset') * 3600, TRUE);
                $printEndDate = date_i18n(get_option('date_format'), $ret['end_timestamp'] + get_option('gmt_offset') * 3600, TRUE);
                $printEndTime = date_i18n(get_option('time_format'), $ret['end_timestamp'] + get_option('gmt_offset') * 3600, TRUE);
            } else
            {
                $ret['end_date'] = '';
                $ret['end_time'] = '';
                $printEndDate = $this->lang->getText('NRS_ALL_YEAR_TEXT');
                $printEndTime = $this->lang->getText('NRS_ALL_DAY_TEXT');
            }

            if($ret['seasonal_price'] == 0)
            {
                $printLabel = $this->lang->getText('NRS_ADMIN_REGULAR_PRICE_TEXT');
            } else
            {
                $printLabel = $this->lang->getText('NRS_PERIOD_TEXT').': ';
                $printLabel .= date_i18n(get_option('date_format').' '.get_option('time_format'), $ret['start_timestamp'] + get_option('gmt_offset') * 3600, TRUE);
                $printLabel .= ' - '.date_i18n(get_option('date_format').' '.get_option('time_format'), $ret['end_timestamp'] + get_option('gmt_offset') * 3600, TRUE);
            }
            if($ret['coupon_code'] != '')
            {
                $printLabel .= ' ('.$this->lang->getText('NRS_COUPON_TEXT').': '.esc_html($ret['coupon_code']).')';
            }

            // Prepare output for print
            $ret['print_coupon_code'] = esc_html($ret['coupon_code']);
            $ret['print_label'] = $printLabel;
            $ret['print_start_date'] = $printStartDate;
            $ret['print_start_time'] = $printStartTime;
            $ret['print_end_date'] = $printEndDate;
            $ret['print_end_time'] = $printEndTime;

            // Prepare output for edit
            $ret['edit_coupon_code'] = esc_attr($ret['coupon_code']); // for input field
        }

        return $ret;
    }

    public function save()
    {
        $saved = FALSE;
        $ok = TRUE;
        $validPricePlanId = StaticValidator::getValidPositiveInteger($this->pricePlanId);

        $validPriceGroupId = isset($_POST['price_group_id']) ? StaticValidator::getValidPositiveInteger($_POST['price_group_id'], 0) : 0;
        $sanitizedCouponCode = isset($_POST['coupon_code']) ? sanitize_text_field($_POST['coupon_code']) : '';
        $validCouponCode = esc_sql($sanitizedCouponCode); // for sql queries only
        $validStartTimestamp = 0;
        if(isset($_POST['start_date']) && $_POST['start_date'] != "")
        {
            $validISOStartDate = StaticValidator::getValidISODate($_POST['start_date'], $this->shortDateFormat);
            $validStartTimestamp = StaticValidator::getUTCTimestampFromLocalISODateTime($validISOStartDate, '00:00:00');
        }
        $validEndTimestamp = 0;
        if($validStartTimestamp > 0 && isset($_POST['end_date']) && $_POST['end_date'] != "")
        {
            $validISOEndDate = StaticValidator::getValidISODate($_POST['end_date'], $this->shortDateFormat);
            $validEndTimestamp = StaticValidator::getUTCTimestampFromLocalISODateTime($validISOEndDate, '23:59:59');
        }

        $validDailyRateMon = isset($_POST['daily_rate_mon']) ? floatval($_POST['daily_rate_mon']) : 0.00;
        $validDailyRateTue = isset($_POST['daily_rate_tue']) ? floatval($_POST['daily_rate_tue']) : 0.00;
        $validDailyRateWed = isset($_POST['daily_rate_wed']) ? floatval($_POST['daily_rate_wed']) : 0.00;
        $validDailyRateThu = isset($_POST['daily_rate_thu']) ? floatval($_POST['daily_rate_thu']) : 0.00;
        $validDailyRateFri = isset($_POST['daily_rate_fri']) ? floatval($_POST['daily_rate_fri']) : 0.00;
        $validDailyRateSat = isset($_POST['daily_rate_sat']) ? floatval($_POST['daily_rate_sat']) : 0.00;
        $validDailyRateSun = isset($_POST['daily_rate_sun']) ? floatval($_POST['daily_rate_sun']) : 0.00;
        $validHourlyRateMon = isset($_POST['hourly_rate_mon']) ? floatval($_POST['hourly_rate_mon']) : 0.00;
        $validHourlyRateTue = isset($_POST['hourly_rate_tue']) ? floatval($_POST['hourly_rate_tue']) : 0.00;
        $validHourlyRateWed = isset($_POST['hourly_rate_wed']) ? floatval($_POST['hourly_rate_wed']) : 0.00;
        $validHourlyRateThu = isset($_POST['hourly_rate_thu']) ? floatval($_POST['hourly_rate_thu']) : 0.00;
        $validHourlyRateFri = isset($_POST['hourly_rate_fri']) ? floatval($_POST['hourly_rate_fri']) : 0.00;
        $validHourlyRateSat = isset($_POST['hourly_rate_sat']) ? floatval($_POST['hourly_rate_sat']) : 0.00;
        $validHourlyRateSun = isset($_POST['hourly_rate_sun']) ? floatval($_POST['hourly_rate_sun']) : 0.00;
        $seasonalPrice = $validStartTimestamp > 0 && $validEndTimestamp > 0 ? 1 : 0;

        $SQLExistConflictingDates = "
            SELECT *
            FROM {$this->conf->getPrefix()}price_plans
            WHERE
            (
                ('{$validStartTimestamp}' BETWEEN start_timestamp AND end_timestamp)
                OR ('{$validEndTimestamp}' BETWEEN start_timestamp AND end_timestamp)
                OR (start_timestamp BETWEEN '{$validStartTimestamp}' AND '{$validEndTimestamp}')
                OR (end_timestamp BETWEEN '{$validStartTimestamp}' AND '{$validEndTimestamp}')            
            ) AND price_group_id='{$validPriceGroupId}' AND coupon_code='{$validCouponCode}'
            AND blog_id='{$this->conf->getBlogId()}' AND price_plan_id!='{$validPricePlanId}'
        ";

        // DEBUG
        //die("<br />Query: ".nl2br($SQLExistConflictingDates));
        $existConflictingDates = $this->conf->getInternalWPDB()->get_row($SQLExistConflictingDates, ARRAY_A);

        if(($validStartTimestamp > 0 || $validEndTimestamp > 0) && $validStartTimestamp >= $validEndTimestamp)
        {
            $ok = FALSE;
            $this->errorMessages[] = $this->lang->getText('NRS_PRICE_PLAN_LATER_DATE_ERROR_TEXT');
        }
        if($validPriceGroupId == 0)
        {
            $ok = FALSE;
            $this->errorMessages[] = $this->lang->getText('NRS_PRICE_PLAN_INVALID_PRICE_GROUP_ERROR_TEXT');
        }
        if(!is_null($existConflictingDates))
        {
            $ok = FALSE;
            $this->errorMessages[] = $this->lang->getText('NRS_PRICE_PLAN_EXISTS_FOR_DATE_RANGE_ERROR_TEXT');
        }

        if($validPricePlanId > 0 && $ok)
        {
            $query = "UPDATE `{$this->conf->getPrefix()}price_plans` SET
                price_group_id='{$validPriceGroupId}',
                coupon_code='{$validCouponCode}',
                start_timestamp='{$validStartTimestamp}',
                end_timestamp='{$validEndTimestamp}',
                daily_rate_mon='{$validDailyRateMon}',
                daily_rate_tue='{$validDailyRateTue}',
                daily_rate_wed='{$validDailyRateWed}',
                daily_rate_thu='{$validDailyRateThu}',
                daily_rate_fri='{$validDailyRateFri}',
                daily_rate_sat='{$validDailyRateSat}',
                daily_rate_sun='{$validDailyRateSun}',
                hourly_rate_mon='{$validHourlyRateMon}',
                hourly_rate_tue='{$validHourlyRateTue}',
                hourly_rate_wed='{$validHourlyRateWed}',
                hourly_rate_thu='{$validHourlyRateThu}',
                hourly_rate_fri='{$validHourlyRateFri}',
                hourly_rate_sat='{$validHourlyRateSat}',
                hourly_rate_sun='{$validHourlyRateSun}',
                seasonal_price='{$seasonalPrice}'
                WHERE price_plan_id='{$validPricePlanId}' AND blog_id='{$this->conf->getBlogId()}'
            ";

            // DEBUG
            //echo nl2br($query)."<br /><br />";
            $saved = $this->conf->getInternalWPDB()->query($query);
            if($saved === FALSE)
            {
                $this->errorMessages[] = $this->lang->getText('NRS_PRICE_PLAN_UPDATE_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('NRS_PRICE_PLAN_UPDATED_TEXT');
            }
        } else if($ok)
        {
            $saved = $this->conf->getInternalWPDB()->query("
                INSERT INTO `{$this->conf->getPrefix()}price_plans`
                (
                    price_group_id,
                    coupon_code,
                    start_timestamp,
                    end_timestamp,
                    daily_rate_mon,
                    daily_rate_tue,
                    daily_rate_wed,
                    daily_rate_thu,
                    daily_rate_fri,
                    daily_rate_sat,
                    daily_rate_sun,
                    hourly_rate_mon,
                    hourly_rate_tue,
                    hourly_rate_wed,
                    hourly_rate_thu,
                    hourly_rate_fri,
                    hourly_rate_sat,
                    hourly_rate_sun,
                    seasonal_price,
                    blog_id
                ) VALUES
                (
                    '{$validPriceGroupId}',
                    '{$validCouponCode}',
                    '{$validStartTimestamp}',
                    '{$validEndTimestamp}',
                    '{$validDailyRateMon}',
                    '{$validDailyRateTue}',
                    '{$validDailyRateWed}',
                    '{$validDailyRateThu}',
                    '{$validDailyRateFri}',
                    '{$validDailyRateSat}',
                    '{$validDailyRateSun}',
                    '{$validHourlyRateMon}',
                    '{$validHourlyRateTue}',
                    '{$validHourlyRateWed}',
                    '{$validHourlyRateThu}',
                    '{$validHourlyRateFri}',
                    '{$validHourlyRateSat}',
                    '{$validHourlyRateSun}',
                    '{$seasonalPrice}',
                    '{$this->conf->getBlogId()}'
                );
            ");
            if($saved)
            {
                // Update object id with newly inserted id for future work
                $this->pricePlanId = $this->conf->getInternalWPDB()->insert_id;
            }

            if($saved === FALSE || $saved === 0)
            {
                $this->errorMessages[] = $this->lang->getText('NRS_PRICE_PLAN_INSERT_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('NRS_PRICE_PLAN_INSERTED_TEXT');
            }
        }

        return $saved;
    }

    /**
     * Not used for this element
     */
    public function registerForTranslation()
    {
        // not used
    }

    /**
     * Seasonal price plan delete method. All data securely validated
     */
    public function delete()
    {
        $validPricePlanId = StaticValidator::getValidPositiveInteger($this->pricePlanId, 0);

        $deleted = $this->conf->getInternalWPDB()->query("
            DELETE FROM `{$this->conf->getPrefix()}price_plans`
            WHERE price_plan_id='{$validPricePlanId}' AND blog_id='{$this->conf->getBlogId()}'
        ");

        if($deleted === FALSE || $deleted === 0)
        {
            $this->errorMessages[] = $this->lang->getText('NRS_PRICE_PLAN_DELETE_ERROR_TEXT');
        } else
        {
            $this->okayMessages[] = $this->lang->getText('NRS_PRICE_PLAN_DELETED_TEXT');
        }

        return $deleted;
    }

    /**
     * Element-specific method
     * @return array
     */
    public function getWeekdays()
    {
        if(get_option('start_of_week') == 1)
        {
            $weeksDays = array(
                'mon' => $this->lang->getText('NRS_MON_TEXT'),
                'tue' => $this->lang->getText('NRS_TUE_TEXT'),
                'wed' => $this->lang->getText('NRS_WED_TEXT'),
                'thu' => $this->lang->getText('NRS_THU_TEXT'),
                'fri' => $this->lang->getText('NRS_FRI_TEXT'),
                'sat' => $this->lang->getText('NRS_SAT_TEXT'),
                'sun' => $this->lang->getText('NRS_SUN_TEXT'),
            );
        } else
        {
            $weeksDays = array(
                'sun' => $this->lang->getText('NRS_SUN_TEXT'),
                'mon' => $this->lang->getText('NRS_MON_TEXT'),
                'tue' => $this->lang->getText('NRS_TUE_TEXT'),
                'wed' => $this->lang->getText('NRS_WED_TEXT'),
                'thu' => $this->lang->getText('NRS_THU_TEXT'),
                'fri' => $this->lang->getText('NRS_FRI_TEXT'),
                'sat' => $this->lang->getText('NRS_SAT_TEXT'),
            );
        }

        return $weeksDays;
    }
}