<?php
/**
 * NRS Price Plan Discounts Observer (no setup for single price plan id)

 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Discount;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\iObserver;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Pricing\PriceGroup;
use NativeRentalSystem\Models\Pricing\PricePlan;

class PricePlanDiscountsObserver implements iObserver, iDiscountsObserver
{
    protected $conf 	                = NULL;
    protected $lang 		            = NULL;
    protected $debugMode 	            = 0;
    protected $settings 	            = array();
    // Price calculation type: 1 - daily, 2 - hourly, 3 - mixed (daily+hourly)
    protected $priceCalculationType 	= 1;
    protected $currencySymbol		    = '$';
    protected $currencyCode		        = 'USD';
    protected $currencySymbolLocation	= 0;

    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;
        // Set saved settings
        $this->settings = $paramSettings;

        $this->priceCalculationType = StaticValidator::getValidSetting($paramSettings, 'conf_price_calculation_type', 'positive_integer', 1, array(1, 2, 3));
        $this->currencySymbol = StaticValidator::getValidSetting($paramSettings, 'conf_currency_symbol', "textval", "$");
        $this->currencyCode = StaticValidator::getValidSetting($paramSettings, 'conf_currency_code', "textval", "USD");
        $this->currencySymbolLocation = StaticValidator::getValidSetting($paramSettings, 'conf_currency_symbol_location', 'positive_integer', 0, array(0, 1));
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    /**
     * @param string $paramDiscountType - "", "BOOKING_DURATION" or "BOOKING_IN_ADVANCE"
     * @param int $paramPricePlanId
     * @return array
     */
    public function getAllIds($paramDiscountType = "", $paramPricePlanId = -1)
    {
        $validPricePlanId = StaticValidator::getValidInteger($paramPricePlanId, -1);

        $sqlAdd = "";
        if($paramDiscountType != "")
        {
            $validSQLDiscountType = $paramDiscountType == "BOOKING_DURATION" ? 1 : 2;
            $sqlAdd .= " AND discount_type='{$validSQLDiscountType}'";
        }
        if($validPricePlanId > 0)
        {
            $sqlAdd .= " AND price_plan_id='{$validPricePlanId}'";
        }
        $validSqlOrder = $paramDiscountType == "BOOKING_IN_ADVANCE" ? "DESC" : "ASC";

        $sqlQuery = "
            SELECT discount_id
            FROM {$this->conf->getPrefix()}discounts
            WHERE blog_id='{$this->conf->getBlogId()}' AND extra_id='0'{$sqlAdd}
            ORDER BY period_from {$validSqlOrder}, period_till {$validSqlOrder}
        ";

        $ids = $this->conf->getInternalWPDB()->get_col($sqlQuery);

        return $ids;
    }

    /**
     * @param string $paramDiscountType - "", "BOOKING_DURATION" or "BOOKING_IN_ADVANCE"
     * @param bool $paramExcludeCouponPrices
     * @param int $paramPricePlanId
     * @param int $paramPartnerId
     * @return array
     */
    public function getGroupedIds($paramDiscountType = "", $paramExcludeCouponPrices = TRUE, $paramPricePlanId = -1, $paramPartnerId = -1)
    {
        $validPricePlanId = StaticValidator::getValidInteger($paramPricePlanId, -1);
        $validPartnerId = StaticValidator::getValidInteger($paramPartnerId, -1);

        $sqlAdd = "";
        if($paramDiscountType != "")
        {
            $validSQLDiscountType = $paramDiscountType == "BOOKING_DURATION" ? 1 : 2;
            $sqlAdd .= " AND di.discount_type='{$validSQLDiscountType}'";
        }
        if($paramExcludeCouponPrices)
        {
            $sqlAdd .= " AND di.coupon_discount='0'";
        }
        if($validPricePlanId > 0)
        {
            $sqlAdd .= " AND di.price_plan_id='{$validPricePlanId}'";
        }

        $validSqlOrder = $paramDiscountType == "BOOKING_IN_ADVANCE" ? "DESC" : "ASC";

        if($validPartnerId >= 0)
        {
            $sqlQuery = "
                SELECT di.discount_id
                FROM {$this->conf->getPrefix()}discounts di
                JOIN {$this->conf->getPrefix()}price_plans pp ON pp.price_plan_id=di.price_plan_id
                JOIN {$this->conf->getPrefix()}price_groups pg ON pg.price_group_id=pp.price_group_id
                WHERE di.blog_id='{$this->conf->getBlogId()}' AND di.extra_id='0'
                AND pg.partner_id='{$validPartnerId}'{$sqlAdd}
                GROUP BY period_from, period_till
                ORDER BY period_from {$validSqlOrder}, period_till {$validSqlOrder}
            ";
        } else
        {
            $sqlQuery = "
                SELECT di.discount_id
                FROM {$this->conf->getPrefix()}discounts di
                WHERE di.blog_id='{$this->conf->getBlogId()}' AND di.extra_id='0'{$sqlAdd}
                GROUP BY period_from, period_till
                ORDER BY period_from {$validSqlOrder}, period_till {$validSqlOrder}
            ";
        }

        // Debug
        // echo "<br />".nl2br($sqlQuery);

        $ids = $this->conf->getInternalWPDB()->get_col($sqlQuery);

        return $ids;
    }

    public function canShowOnlyPartnerOwned()
    {
        $canEditOwnItems = current_user_can('manage_'.$this->conf->getExtensionPrefix().'own_items');
        $canEditAllItems = current_user_can('manage_'.$this->conf->getExtensionPrefix().'all_items');
        $onlyPartnerOwned = $canEditOwnItems == TRUE && $canEditAllItems == FALSE;

        return $onlyPartnerOwned;
    }


    /**
     * Update discount coupon status in discounts table based on price_plan_id for specific blog_id
     * @param int $paramPricePlanId
     * @param bool $paramHasCouponCode
     */
    public function changeCouponStatus($paramPricePlanId, $paramHasCouponCode)
    {
        $validPricePlanId = StaticValidator::getValidPositiveInteger($paramPricePlanId, 0);
        $validIsCouponDiscount = $paramHasCouponCode == TRUE ? 1 : 0;

        if($validPricePlanId > 0)
        {
            $updateQuery = "
                UPDATE {$this->conf->getPrefix()}discounts SET coupon_discount='{$validIsCouponDiscount}'
                WHERE price_plan_id='{$validPricePlanId}' AND blog_id='{$this->conf->getBlogId()}'
            ";

            $this->conf->getInternalWPDB()->query($updateQuery);
        }
    }


    /*******************************************************************************/
    /********************** METHODS FOR ADMIN ACCESS ONLY **************************/
    /*******************************************************************************/

    public function getAdminListForDiscountDuration()
    {
        return $this->getAdminList("BOOKING_DURATION");
    }

    public function getAdminListForBookingInAdvance()
    {
        return $this->getAdminList("BOOKING_IN_ADVANCE");
    }

    /**
     * DIFFERENT DURATION DISCOUNTS FOR DIFFERENT ITEMS
     * DEPTH: 1
     * @param string $paramDiscountType - "BOOKING_DURATION" or "BOOKING_IN_ADVANCE"
     * @return string
     * @internal param int $paramPartnerId
     */
    private function getAdminList($paramDiscountType = "BOOKING_DURATION")
    {
        $discountList = '';

        // Include discounts for price plans with coupon codes
        $discountIds = $this->getGroupedIds($paramDiscountType, FALSE, -1, ($this->canShowOnlyPartnerOwned() ? get_current_user_id() : -1));
        $i = 0;
        foreach($discountIds AS $discountId)
        {
            $i++;
            $objDiscount = new PricePlanDiscount($this->conf, $this->lang, $this->settings, $discountId);
            $discountDetails = $objDiscount->getDetails();
            $printDurationFrom = $this->lang->getPrintFloorDurationByPeriod($this->priceCalculationType, $discountDetails['period_from']);
            $printDurationTill = $this->lang->getPrintFloorDurationByPeriod($this->priceCalculationType, $discountDetails['period_till']);

            // HTML OUTPUT
            $discountList .= '<tr>';
            $discountList .= '<td>'.sprintf('%02d', $i).'</td>';
            $discountList .= '<td><strong>'.$printDurationFrom.' - '.$printDurationTill.'</strong></td>';
            $discountList .= '<td>&nbsp;</td>';
            $discountList .= '<td>&nbsp;</td>';
            $discountList .= '</tr>';
            $discountList .= $this->getAdminDiscountListByPeriod(
                $paramDiscountType, $discountDetails['period_from'], $discountDetails['period_till'], sprintf('%02d', $i) . "."
            );
        }

        return  $discountList;
    }

    /**
     * DIFFERENT DISCOUNTS FOR DIFFERENT ITEMS IN SPECIFIC PERIOD FROM-TO
     * DEPTH: 2
     * @param string $paramDiscountType - "BOOKING_DURATION" or "BOOKING_IN_ADVANCE"
     * @param int $paramPeriodFrom
     * @param int $paramPeriodTill
     * @param string $rowNumbersPrefix
     * @return string
     * @internal param bool $showRowNumbers
     */
    private function getAdminDiscountListByPeriod($paramDiscountType, $paramPeriodFrom, $paramPeriodTill, $rowNumbersPrefix = "0.")
    {
        $discountList = '';
        $validPeriodFrom = StaticValidator::getValidPositiveInteger($paramPeriodFrom, 0);
        $validPeriodTill = StaticValidator::getValidPositiveInteger($paramPeriodTill, 0);
        $validDiscountType = $paramDiscountType == "BOOKING_IN_ADVANCE" ? 2 : 1;

        $discountIds = $this->conf->getInternalWPDB()->get_col("
            SELECT di.discount_id
            FROM {$this->conf->getPrefix()}discounts di
            LEFT JOIN {$this->conf->getPrefix()}price_plans pp ON pp.price_plan_id=di.price_plan_id
            LEFT JOIN {$this->conf->getPrefix()}price_groups pg ON pg.price_group_id=pp.price_group_id
            WHERE di.period_from='{$validPeriodFrom}' AND di.period_till='{$validPeriodTill}'
            AND di.discount_type='{$validDiscountType}' AND di.blog_id='{$this->conf->getBlogId()}'
            ORDER BY pg.price_group_name ASC, pp.coupon_code ASC, pp.seasonal_price ASC, pp.start_timestamp ASC, pp.end_timestamp ASC
        ");

        $i = 0;
        foreach($discountIds AS $discountId)
        {
            $i++;
            $objDiscount = new PricePlanDiscount($this->conf, $this->lang, $this->settings, $discountId);
            $discountDetails = $objDiscount->getDetails();
            $objPricePlan = new PricePlan($this->conf, $this->lang, $this->settings, $discountDetails['price_plan_id']);
            $pricePlanDetails = $objPricePlan->getDetails();
            $printFullPriceLabel = "";
            $partnerId = 0;
            if(!is_null($pricePlanDetails))
            {
                $printFullPriceLabel = $pricePlanDetails['print_label'];
                $objPriceGroup = new PriceGroup($this->conf, $this->lang, $this->settings, $pricePlanDetails['price_group_id']);
                $priceGroupDetails = $objPriceGroup->getDetailsWithPartner();
                if(!is_null($priceGroupDetails))
                {
                    $partnerId = $priceGroupDetails['partner_id'];
                    $printTranslatedPriceGroupName = $priceGroupDetails['print_translated_price_group_name'].' '.$priceGroupDetails['print_via_partner'];
                    $printFullPriceLabel = $printTranslatedPriceGroupName.' - '.$printFullPriceLabel;
                }
            }

            $printDurationFrom = $this->lang->getPrintFloorDurationByPeriod($this->priceCalculationType, $validPeriodFrom);
            $printDurationTill = $this->lang->getPrintFloorDurationByPeriod($this->priceCalculationType, $validPeriodTill);

            // HTML OUTPUT
            $discountList .= '<tr>';
            $discountList .= '<td>'.$rowNumbersPrefix.sprintf('%02d', $i).'</td>';
            if($discountDetails['price_plan_id'] == 0)
            {
                $discountList .= '<td>---</td>';
            } else
            {
                $discountList .= '<td>'.$printFullPriceLabel.'</td>';
            }
            $discountList .= '<td>'.$discountDetails['discount_percentage'].' %';
            $discountList .= '&nbsp;<strong>('.$printDurationFrom.' - '.$printDurationTill.')</strong>';
            $discountList .= '</td>';
            $discountList .= '<td align="right">';
            if($objDiscount->canEdit($partnerId))
            {
                $discountList .= '<a href="'.admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-item-discount&amp;discount_type='.$validDiscountType.'&amp;discount_id='.$discountId).'">'.$this->lang->getText('NRS_ADMIN_EDIT_TEXT').'</a> || ';
                $discountList .= '<a href="'.admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-item-discount&amp;discount_type='.$validDiscountType.'&amp;delete_discount='.$discountId.'&amp;noheader=true').'">'.$this->lang->getText('NRS_ADMIN_DELETE_TEXT').'</a>';
            } else
            {
                $discountList = '--';
            }
            $discountList .= '</td>';
            $discountList .= '</tr>';
        }

        return  $discountList;
    }
}