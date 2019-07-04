<?php
/**
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Admin\ItemPrice;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Discount\PricePlanDiscount;
use NativeRentalSystem\Models\Discount\PricePlanDiscountsObserver;
use NativeRentalSystem\Models\Pricing\PriceGroup;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Pricing\PricePlan;
use NativeRentalSystem\Controllers\Admin\AbstractController;

final class AddEditPricePlanController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    private function processDelete($paramPricePlanId)
    {
        $objPricePlan = new PricePlan($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramPricePlanId);
        $priceGroupId = $objPricePlan->getPriceGroupId();
        $objPriceGroup = new PriceGroup($this->conf, $this->lang, $this->dbSettings->getSettings(), $priceGroupId);
        // Allow to delete only seasonal prices
        if($objPricePlan->canEdit($objPriceGroup->getPartnerId()) && $objPricePlan->isSeasonal() === TRUE)
        {
            $deleted = $objPricePlan->delete();

            if($deleted)
            {
                // Delete corresponding discounts
                $objDiscountsObserver = new PricePlanDiscountsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
                $discountIds = $objDiscountsObserver->getAllIds("", $paramPricePlanId);
                foreach ($discountIds AS $discountId)
                {
                    $objDiscount = new PricePlanDiscount($this->conf, $this->lang, $this->dbSettings->getSettings(), $discountId);
                    $objDiscount->delete();
                }
            }

            $this->processDebugMessages($objPricePlan->getDebugMessages());
            $this->processOkayMessages($objPricePlan->getOkayMessages());
            $this->processErrorMessages($objPricePlan->getErrorMessages());
        }

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'prices&price_group_id='.$priceGroupId.'&tab=price-plans');
        exit;
    }

    private function processSave($paramPriceGroupId, $paramPricePlanId)
    {
        $objDiscountsObserver = new PricePlanDiscountsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objPricePlan = new PricePlan($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramPricePlanId);
        $objPriceGroup = new PriceGroup($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramPriceGroupId);
        if(($paramPricePlanId == 0 && $objPriceGroup->canEdit()) || $objPricePlan->canEdit($objPriceGroup->getPartnerId()))
        {
            $oldCouponCode = $objPricePlan->getCouponCode();
            $saved = $objPricePlan->save();
            $newCouponCode = $objPricePlan->getCouponCode();
            if($paramPricePlanId > 0 && $saved && $newCouponCode != $oldCouponCode)
            {
                $hasCouponCode = $newCouponCode != '' ? TRUE : FALSE;
                $objDiscountsObserver->changeCouponStatus($paramPricePlanId, $hasCouponCode);
            }

            $this->processDebugMessages($objPricePlan->getDebugMessages());
            $this->processOkayMessages($objPricePlan->getOkayMessages());
            $this->processErrorMessages($objPricePlan->getErrorMessages());
        }

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'prices&tab=price-plans&price_group_id='.intval($paramPriceGroupId));
        exit;
    }

    public function getContent()
    {
        // Process actions
        if(isset($_GET['delete_price_plan'])) { $this->processDelete($_GET['delete_price_plan']); }
        if(isset($_POST['save_price_plan'], $_POST['price_group_id'], $_POST['price_plan_id'])) { $this->processSave($_POST['price_group_id'], $_POST['price_plan_id']); }

        $paramPriceGroupId = isset($_GET['price_group_id']) ? $_GET['price_group_id'] : 0;
        $paramPricePlanId = isset($_GET['price_plan_id']) ? $_GET['price_plan_id'] : 0;

        // Create mandatory instances
        $objPricePlan = new PricePlan($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramPricePlanId);
        $validPriceGroupId = $paramPricePlanId > 0 ? $objPricePlan->getPriceGroupId() : StaticValidator::getValidPositiveInteger($paramPriceGroupId, 0);
        $objPriceGroup = new PriceGroup($this->conf, $this->lang, $this->dbSettings->getSettings(), $validPriceGroupId);
        $localPriceGroupDetails = $objPriceGroup->getDetailsWithPartner();
        if(is_null($localPriceGroupDetails))
        {
            // Price group do not exist
            // Note - we don't use here wp_safe_redirect, because headers already sent, so we have to use a redirect Javascript code in content
            $redirectToPage = admin_url('admin.php?page='.$this->conf->getURLPrefix().'prices&tab=price-plans');
            echo '<script type="text/javascript">window.location="'.$redirectToPage.'"</script>';
            exit;
        } else
        {
            // Price group exists
            $localDetails = $objPricePlan->getDetails();
            $dailyRates = array();
            $hourlyRates = array();

            // Set the view variables
            $this->view->backToListURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'prices&tab=price-plans&price_group_id='.$validPriceGroupId);
            $this->view->formAction = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-price-plan&noheader=true');
            $this->view->priceGroupName = $localPriceGroupDetails['print_translated_price_group_name'].' '.$localPriceGroupDetails['print_via_partner'].' (ID='.$localPriceGroupDetails['price_group_id'].')';
            if(!is_null($localDetails) && $objPricePlan->canEdit($objPriceGroup->getPartnerId()))
            {
                $this->view->pricePlanId = $localDetails['price_plan_id'];
                $this->view->priceGroupId = $localDetails['price_group_id'];
                $this->view->couponCode = $localDetails['edit_coupon_code'];
                $this->view->startDate = $localDetails['start_date'];
                $this->view->endDate = $localDetails['end_date'];
                $this->view->startTime = $localDetails['print_start_time'];
                $this->view->endTime = $localDetails['print_end_time'];
                foreach($objPricePlan->getWeekdays() AS $weekDay => $dayName)
                {
                    $dailyRates[$weekDay] = $localDetails['daily_rate_'.$weekDay];
                    $hourlyRates[$weekDay] = $localDetails['hourly_rate_'.$weekDay];
                }
            } else
            {
                $this->view->pricePlanId = 0;
                $this->view->priceGroupId = $validPriceGroupId;
                $this->view->couponCode = '';
                $this->view->startDate = $localDetails['start_date'];
                $this->view->endDate = $localDetails['end_date'];
                $this->view->startTime = date_i18n(get_option('time_format'), strtotime(date("Y-m-d")." 00:00:00"), TRUE);
                $this->view->endTime = date_i18n(get_option('time_format'), strtotime(date("Y-m-d")." 23:59:59"), TRUE);
                foreach($objPricePlan->getWeekdays() AS $weekDay => $dayName)
                {
                    $dailyRates[$weekDay] = '';
                    $hourlyRates[$weekDay] = '';
                }
            }
            $this->view->weekDays = $objPricePlan->getWeekdays();
            $this->view->displayDailyRates = in_array($this->dbSettings->getSetting('conf_price_calculation_type'), array(1, 3));
            $this->view->displayHourlyRates = in_array($this->dbSettings->getSetting('conf_price_calculation_type'), array(2, 3));
            $this->view->leftCurrencySymbol = $this->dbSettings->getSetting('conf_currency_symbol_location') == 0 ? esc_html($this->dbSettings->getSetting('conf_currency_symbol')).' ' : '';
            $this->view->rightCurrencySymbol = $this->dbSettings->getSetting('conf_currency_symbol_location') == 1 ? esc_html($this->dbSettings->getSetting('conf_currency_symbol')).' ' : '';
            $this->view->dailyRates = $dailyRates;
            $this->view->hourlyRates = $hourlyRates;

            // Get the template
            $retContent = $this->getTemplate('ItemPrice', 'AddEditPricePlan', 'Form');

            return $retContent;
        }
    }
}
