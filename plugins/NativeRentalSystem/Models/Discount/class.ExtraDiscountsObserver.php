<?php
/**
 * NRS Extra Discounts Observer (no setup for single item)

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
use NativeRentalSystem\Models\Extra\Extra;

class ExtraDiscountsObserver implements iObserver, iDiscountsObserver
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
     * @param int $paramExtraId
     * @return array
     */
    public function getAllIds($paramDiscountType = "", $paramExtraId = -1)
    {
        $validExtraId = StaticValidator::getValidInteger($paramExtraId, -1);

        $sqlAdd = "";
        if($paramDiscountType != "")
        {
            $validSQLDiscountType = $paramDiscountType == "BOOKING_DURATION" ? 1 : 2;
            $sqlAdd .= " AND discount_type='{$validSQLDiscountType}'";
        }
        if($validExtraId > 0)
        {
            $sqlAdd .= " AND extra_id='{$validExtraId}'";
        }
        $validSqlOrder = $paramDiscountType == "BOOKING_IN_ADVANCE" ? "DESC" : "ASC";

        $sqlQuery = "
            SELECT discount_id
            FROM {$this->conf->getPrefix()}discounts
            WHERE blog_id='{$this->conf->getBlogId()}' AND price_plan_id='0'{$sqlAdd}
            ORDER BY period_from {$validSqlOrder}, period_till {$validSqlOrder}
        ";

        $ids = $this->conf->getInternalWPDB()->get_col($sqlQuery);

        return $ids;
    }

    /**
     * @param string $paramDiscountType - "", "BOOKING_DURATION" or "BOOKING_IN_ADVANCE"
     * @param int $paramExtraId
     * @param int $paramPartnerId
     * @return array
     */
    public function getGroupedIds($paramDiscountType = "", $paramExtraId = -1, $paramPartnerId = -1)
    {
        $validExtraId = StaticValidator::getValidInteger($paramExtraId, -1);
        $validPartnerId = StaticValidator::getValidInteger($paramPartnerId, -1);

        $sqlAdd = "";
        if($paramDiscountType != "")
        {
            $validSQLDiscountType = $paramDiscountType == "BOOKING_DURATION" ? 3 : 4;
            $sqlAdd .= " AND di.discount_type='{$validSQLDiscountType}'";
        }
        if($validExtraId > 0)
        {
            $sqlAdd .= " AND di.extra_id='{$validExtraId}'";
        }
        $validSqlOrder = $paramDiscountType == "BOOKING_IN_ADVANCE" ? "DESC" : "ASC";

        if($validPartnerId >= 0)
        {
            $sqlQuery = "
                SELECT discount_id
                FROM {$this->conf->getPrefix()}discounts di
                JOIN {$this->conf->getPrefix()}extras ex ON ex.extra_id=di.extra_id
                WHERE di.blog_id='{$this->conf->getBlogId()}' AND ex.partner_id='{$validPartnerId}'
                AND di.price_plan_id='0'{$sqlAdd}
                GROUP BY period_from, period_till
                ORDER BY period_from {$validSqlOrder}, period_till {$validSqlOrder}
            ";
        } else
        {
            $sqlQuery = "
                SELECT discount_id
                FROM {$this->conf->getPrefix()}discounts di
                WHERE di.blog_id='{$this->conf->getBlogId()}' AND di.price_plan_id='0'{$sqlAdd}
                GROUP BY period_from, period_till
                ORDER BY period_from {$validSqlOrder}, period_till {$validSqlOrder}
            ";
        }

        // Debug
        //echo "<br />".nl2br($sqlQuery);

        $ids = $this->conf->getInternalWPDB()->get_col($sqlQuery);

        return $ids;
    }

    public function canShowOnlyPartnerOwned()
    {
        $canEditOwnExtras = current_user_can('manage_'.$this->conf->getExtensionPrefix().'own_extras');
        $canEditAllExtras = current_user_can('manage_'.$this->conf->getExtensionPrefix().'all_extras');
        $onlyPartnerOwned = $canEditOwnExtras == TRUE && $canEditAllExtras == FALSE;

        return $onlyPartnerOwned;
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

        $discountIds = $this->getGroupedIds($paramDiscountType, -1, ($this->canShowOnlyPartnerOwned() ? get_current_user_id() : -1));
        $i = 0;
        foreach($discountIds AS $discountId)
        {
            $i++;
            $objDiscount = new ExtraDiscount($this->conf, $this->lang, $this->settings, $discountId);
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
     */
    private function getAdminDiscountListByPeriod($paramDiscountType = "BOOKING_DURATION", $paramPeriodFrom, $paramPeriodTill, $rowNumbersPrefix = "0.")
    {
        $discountList = '';
        $validPeriodFrom = StaticValidator::getValidPositiveInteger($paramPeriodFrom, 0);
        $validPeriodTill = StaticValidator::getValidPositiveInteger($paramPeriodTill, 0);
        $validDiscountType = $paramDiscountType == "BOOKING_IN_ADVANCE" ? 4 : 3;

        $discountIds = $this->conf->getInternalWPDB()->get_col("
            SELECT di.discount_id
            FROM {$this->conf->getPrefix()}discounts di
            LEFT JOIN {$this->conf->getPrefix()}extras ext ON ext.extra_id=di.extra_id
            WHERE di.period_from='{$validPeriodFrom}' AND di.period_till='{$validPeriodTill}' AND di.blog_id='{$this->conf->getBlogId()}'
            AND di.discount_type='{$validDiscountType}'
            ORDER BY ext.extra_name ASC
        ");

        $i = 0;
        foreach($discountIds AS $discountId)
        {
            $i++;
            $objDiscount = new ExtraDiscount($this->conf, $this->lang, $this->settings, $discountId);
            $discountDetails = $objDiscount->getDetails();
            $objExtra = new Extra($this->conf, $this->lang, $this->settings, $discountDetails['extra_id']);
            $extraDetails = $objExtra->getDetailsWithItemAndPartner();
            $printExtraName = "";
            $partnerId = 0;
            if(!is_null($extraDetails))
            {
                $partnerId = $extraDetails['partner_id'];
                $printExtraName = $extraDetails['print_translated_extra_name_with_dependant_item'].' '.$extraDetails['print_via_partner'];
            }

            $printDurationFrom = $this->lang->getPrintFloorDurationByPeriod($this->priceCalculationType, $validPeriodFrom);
            $printDurationTill = $this->lang->getPrintFloorDurationByPeriod($this->priceCalculationType, $validPeriodTill);

            // HTML OUTPUT
            $discountList .= '<tr>';
            $discountList .= '<td>'.$rowNumbersPrefix.sprintf('%02d', $i).'</td>';
            if($discountDetails['extra_id'] == 0)
            {
                $discountList .= '<td>---</td>';
            } else
            {
                $discountList .= '<td>'.$printExtraName.'</td>';
            }
            $discountList .= '<td>'.$discountDetails['discount_percentage'].' %';
            $discountList .= '&nbsp;<strong>('.$printDurationFrom.' - '.$printDurationTill.')</strong>';
            $discountList .= '</td>';
            $discountList .= '<td align="right">';
            if($objDiscount->canEdit($partnerId))
            {
                $discountList .= '<a href="'.admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-extra-discount&amp;discount_type='.$validDiscountType.'&amp;discount_id='.$discountId).'">'.$this->lang->getText('NRS_ADMIN_EDIT_TEXT').'</a> || ';
                $discountList .= '<a href="'.admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-extra-discount&amp;discount_type='.$validDiscountType.'&amp;delete_discount='.$discountId.'&amp;noheader=true').'">'.$this->lang->getText('NRS_ADMIN_DELETE_TEXT').'</a>';
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