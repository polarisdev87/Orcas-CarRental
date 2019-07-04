<?php
/**
 * Price Plan Discount calculator

 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Discount;
use NativeRentalSystem\Models\AbstractElement;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Formatting\StaticFormatter;
use NativeRentalSystem\Models\iElement;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;

class PricePlanDiscount extends AbstractElement implements iElement
{
    protected $conf 	                = NULL;
    protected $lang 		            = NULL;
    protected $debugMode 	            = 0;
    protected $discountId               = 0;
    // Price calculation type: 1 - daily, 2 - hourly, 3 - mixed (daily+hourly)
    protected $priceCalculationType     = 1;

    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings, $paramDiscountId)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;

        // Set discount id
        $this->discountId = StaticValidator::getValidPositiveInteger($paramDiscountId, 0);

        // Set price calculation type
        $this->priceCalculationType = StaticValidator::getValidSetting($paramSettings, 'conf_price_calculation_type', 'positive_integer', 1, array(1, 2, 3));
    }

    /**
     * For internal class use only
     * @param $paramDiscountId
     * @return mixed
     */
    protected function getDataFromDatabaseById($paramDiscountId)
    {
        $validDiscountId = StaticValidator::getValidPositiveInteger($paramDiscountId, 0);
        $discountData = $this->conf->getInternalWPDB()->get_row("
            SELECT *
            FROM {$this->conf->getPrefix()}discounts
            WHERE discount_id='{$validDiscountId}'
        ", ARRAY_A);

        return $discountData;
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    public function getId()
    {
        return $this->discountId;
    }

    public function getIsCouponDiscount()
    {
        $retIsCouponDiscount = FALSE;
        $discountData = $this->getDataFromDatabaseById($this->discountId);
        if(!is_null($discountData))
        {
            $retIsCouponDiscount = $discountData['coupon_discount'] == 1 ? TRUE : FALSE;
        }

        return $retIsCouponDiscount;
    }

    /**
     * Element-specific function
     * @return int
     */
    public function getPricePlanId()
    {
        $retPricePlanId = 0;
        $discountData = $this->getDataFromDatabaseById($this->discountId);
        if(!is_null($discountData))
        {
            $retPricePlanId = $discountData['price_plan_id'];
        }

        return $retPricePlanId;
    }

    /**
     * Checks if current user can edit the element
     * @param $paramPartnerId - partner id is mandatory here, as it comes from other plugin
     * @return bool
     */
    public function canEdit($paramPartnerId)
    {
        $canEdit = FALSE;
        if($this->discountId > 0)
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

    public function getDetails($paramIncludeUnclassified = FALSE)
    {
        $discountDetails = $this->getDataFromDatabaseById($this->discountId);
        if(!is_null($discountDetails))
        {
            if($this->priceCalculationType == "1")
            {
                // Days only
                $durationFrom = StaticValidator::getFloorDaysFromSeconds($discountDetails['period_from']);
                $durationTill = StaticValidator::getFloorDaysFromSeconds($discountDetails['period_till']);
                $durationTimeExtension = $this->lang->getText('NRS_DAYS_TEXT');
            } else if($this->priceCalculationType == "2")
            {
                // Hours only
                $durationFrom = StaticValidator::getFloorHoursFromSeconds($discountDetails['period_from']);
                $durationTill = StaticValidator::getFloorHoursFromSeconds($discountDetails['period_till']);
                $durationTimeExtension = $this->lang->getText('NRS_HOURS_TEXT');
            } else
            {
                // Mixed
                $durationFrom = StaticValidator::getFloorDaysFromSeconds($discountDetails['period_from']);
                $durationTill = StaticValidator::getFloorDaysFromSeconds($discountDetails['period_till']);
                $durationTimeExtension = $this->lang->getText('NRS_DAYS_TEXT');
            }
            $discountDetails['dynamic_duration_from'] = $durationFrom;
            $discountDetails['dynamic_duration_till'] = $durationTill;
            $discountDetails['dynamic_duration_extension'] = $durationTimeExtension;
            $discountDetails['print_dynamic_period_label'] = $durationFrom.'-'.$durationTill.' '.$durationTimeExtension;
        } else if($paramIncludeUnclassified == TRUE)
        {
            $discountDetails = array(
                "period_from" => 0,
                "period_till" => $this->priceCalculationType == 2 ? 3600 : 86400, /* Hour vs Day */
                "dynamic_duration_from" => 0,
                "dynamic_duration_till" => 1,
                "dynamic_duration_extension" => $this->priceCalculationType == 2 ? $this->lang->getText('NRS_HOURS_TEXT') : $this->lang->getText('NRS_DAYS_TEXT'),
                "print_dynamic_period_label" => $this->priceCalculationType == 2 ? '1 '.$this->lang->getText('NRS_HOUR_TEXT') : '1 '.$this->lang->getText('NRS_DAY_TEXT'),
            );
        }

        return $discountDetails;
    }

    public function save()
    {
        $saved = FALSE;
        $ok = TRUE;
        $paramDaysFrom = isset($_POST['days_from']) ? $_POST['days_from'] : 0;
        $paramHoursFrom = isset($_POST['hours_from']) ? $_POST['hours_from'] : 0;
        $paramDaysTill = isset($_POST['days_till']) ? $_POST['days_till'] : 0;
        $paramHoursTill = isset($_POST['hours_till']) ? $_POST['hours_till'] : 0;

        $validDiscountId = StaticValidator::getValidPositiveInteger($this->discountId, 0);
        $validPricePlanId = isset($_POST['price_plan_id']) ? StaticValidator::getValidPositiveInteger($_POST['price_plan_id'], 0) : 0;
        $validDiscountType = isset($_POST['discount_type']) && $_POST['discount_type'] == 2 ? 2 : 1;
        $validPeriodFrom = StaticFormatter::getPeriodFromByPriceType(
            $this->priceCalculationType, $paramDaysFrom, $paramHoursFrom
        );
        $validPeriodTill = StaticFormatter::getPeriodTillByPriceType(
            $this->priceCalculationType, $paramDaysTill, $paramHoursTill
        );
        $validDiscountPercentage = isset($_POST['discount_percentage']) ? floatval($_POST['discount_percentage']) : 0.000;

        // Do not allow to have discounts more than 100%
        if($validDiscountPercentage > 100)
        {
            $validDiscountPercentage = 100;
        }

        $validCouponDiscount = 0;
        if($validPricePlanId > 0)
        {
            $couponCheckQuery = "
                SELECT price_plan_id
                FROM {$this->conf->getPrefix()}price_plans
                WHERE price_plan_id='{$validPricePlanId}' AND coupon_code!='' AND blog_id='{$this->conf->getBlogId()}'
            ";
            $couponCheck = $this->conf->getInternalWPDB()->get_row($couponCheckQuery, ARRAY_A);

            if(!is_null($couponCheck))
            {
                $validCouponDiscount = 1;
            }
        }

        //If expr is greater than or equal to min and expr is less than or equal to max, BETWEEN returns 1, otherwise it returns 0
        $periodCheckQuery = "
            SELECT discount_id
            FROM {$this->conf->getPrefix()}discounts
            WHERE discount_id!='{$validDiscountId}' AND discount_type='{$validDiscountType}' AND price_plan_id='{$validPricePlanId}' AND (
                '{$validPeriodFrom}' BETWEEN period_from AND period_till
                OR '{$validPeriodTill}' BETWEEN period_from AND period_till
            ) AND blog_id='{$this->conf->getBlogId()}'
        ";
        $periodCheck = $this->conf->getInternalWPDB()->get_row($periodCheckQuery, ARRAY_A);

        if(!is_null($periodCheck))
        {
            $ok = FALSE;
            $this->errorMessages[] = $this->lang->getText('NRS_PRICE_PLAN_DISCOUNT_DAYS_INTERSECTION_ERROR_TEXT');
        }

        if($validDiscountId > 0 && $ok)
        {
            $updateSQL = "
                UPDATE {$this->conf->getPrefix()}discounts SET
                coupon_discount='{$validCouponDiscount}', price_plan_id='{$validPricePlanId}', period_from='{$validPeriodFrom}', period_till='{$validPeriodTill}',
                discount_percentage='{$validDiscountPercentage}'
                WHERE discount_id='{$validDiscountId}' AND blog_id='{$this->conf->getBlogId()}'
            ";
            $saved = $this->conf->getInternalWPDB()->query($updateSQL);

            if($saved === FALSE)
            {
                $this->errorMessages[] = $this->lang->getText('NRS_PRICE_PLAN_DISCOUNT_UPDATE_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('NRS_PRICE_PLAN_DISCOUNT_UPDATED_TEXT');
            }
        } else if($ok)
        {
            $insertSQL = "
                INSERT INTO {$this->conf->getPrefix()}discounts
                (
                    discount_type, coupon_discount, price_plan_id, extra_id,
                    period_from, period_till, discount_percentage,
                    blog_id
                )
                VALUES
                (
                    '{$validDiscountType}', '{$validCouponDiscount}', '{$validPricePlanId}', '0',
                    '{$validPeriodFrom}', '{$validPeriodTill}', '{$validDiscountPercentage}',
                    '{$this->conf->getBlogId()}'
                )
            ";
            $saved = $this->conf->getInternalWPDB()->query($insertSQL);
            if($saved)
            {
                // Update object id with newly inserted id for future work
                $this->discountId = $this->conf->getInternalWPDB()->insert_id;
            }

            if($saved === FALSE || $saved === 0)
            {
                $this->errorMessages[] = $this->lang->getText('NRS_PRICE_PLAN_DISCOUNT_INSERT_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('NRS_PRICE_PLAN_DISCOUNT_INSERTED_TEXT');
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
     * @return false|int
     */
    public function delete()
    {
        $validDiscountId = StaticValidator::getValidPositiveInteger($this->discountId);

        $deleted = $this->conf->getInternalWPDB()->query("
            DELETE FROM {$this->conf->getPrefix()}discounts
            WHERE discount_id='{$validDiscountId}' AND blog_id='{$this->conf->getBlogId()}'
        ");

        if($deleted === FALSE || $deleted === 0)
        {
            $this->errorMessages[] = $this->lang->getText('NRS_PRICE_PLAN_DISCOUNT_DELETE_ERROR_TEXT');
        } else
        {
            $this->okayMessages[] = $this->lang->getText('NRS_PRICE_PLAN_DISCOUNT_DELETED_TEXT');
        }

        return $deleted;
    }
}