<?php
/**
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Admin\ItemPrice;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Discount\PricePlanDiscount;
use NativeRentalSystem\Models\Pricing\PriceGroup;
use NativeRentalSystem\Models\Pricing\PricePlan;
use NativeRentalSystem\Models\Pricing\PricePlansObserver;
use NativeRentalSystem\Controllers\Admin\AbstractController;

final class AddEditDiscountController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    private function processDelete($paramDiscountId, $paramDiscountType)
    {
        $objDiscount = new PricePlanDiscount($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramDiscountId);
        $objPricePlan = new PricePlan($this->conf, $this->lang, $this->dbSettings->getSettings(), $objDiscount->getPricePlanId());
        $priceGroupId = $objPricePlan->getPriceGroupId();
        $objPriceGroup = new PriceGroup($this->conf, $this->lang, $this->dbSettings->getSettings(), $priceGroupId);
        if($objDiscount->canEdit($objPriceGroup->getPartnerId()))
        {
            $objDiscount->delete();

            $this->processDebugMessages($objDiscount->getDebugMessages());
            $this->processOkayMessages($objDiscount->getOkayMessages());
            $this->processErrorMessages($objDiscount->getErrorMessages());
        }

        $discountTabToReturn = 'duration-discounts';
        if($paramDiscountType == 1)
        {
            $discountTabToReturn = 'duration-discounts';
        } else if($paramDiscountType == 2)
        {
            $discountTabToReturn = 'discounts-in-advance';
        }

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'prices&tab='.$discountTabToReturn);
        exit;
    }

    private function processSave($paramDiscountId, $paramDiscountType)
    {
        $objDiscount = new PricePlanDiscount($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramDiscountId);
        $objPricePlan = new PricePlan($this->conf, $this->lang, $this->dbSettings->getSettings(), $objDiscount->getPricePlanId());
        $priceGroupId = $objPricePlan->getPriceGroupId();
        $objPriceGroup = new PriceGroup($this->conf, $this->lang, $this->dbSettings->getSettings(), $priceGroupId);
        if($paramDiscountId == 0 || $objDiscount->canEdit($objPriceGroup->getPartnerId()))
        {
            $objDiscount->save();

            $this->processDebugMessages($objDiscount->getDebugMessages());
            $this->processOkayMessages($objDiscount->getOkayMessages());
            $this->processErrorMessages($objDiscount->getErrorMessages());
        }

        $discountTabToReturn = 'duration-discounts';
        if($paramDiscountType == 1)
        {
            $discountTabToReturn = 'duration-discounts';
        } else if($paramDiscountType == 2)
        {
            $discountTabToReturn = 'discounts-in-advance';
        }

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'prices&tab='.$discountTabToReturn);
        exit;
    }

    public function getContent()
    {
        // Create mandatory instances
        $objPricePlansObserver = new PricePlansObserver($this->conf, $this->lang, $this->dbSettings->getSettings());

        // Process actions
        if(isset($_GET['delete_discount'], $_GET['discount_type'])) { $this->processDelete($_GET['delete_discount'], $_GET['discount_type']); }
        if(isset($_POST['save_discount'], $_POST['discount_id'], $_POST['discount_type'])) { $this->processSave($_POST['discount_id'], $_POST['discount_type']); }

        $paramDiscountType = isset($_GET['discount_type']) ? $_GET['discount_type'] : 1;
        $paramDiscountId = isset($_GET['discount_id']) ? $_GET['discount_id'] : 0;
        $objDiscount = new PricePlanDiscount($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramDiscountId);
        $objPricePlan = new PricePlan($this->conf, $this->lang, $this->dbSettings->getSettings(), $objDiscount->getPricePlanId());
        $objPriceGroup = new PriceGroup($this->conf, $this->lang, $this->dbSettings->getSettings(), $objPricePlan->getPriceGroupId());
        $localDetails = $objDiscount->getDetails();

        // Set the view variables
        $localDiscountTabToReturn = $paramDiscountType == 2 ? 'discounts-in-advance' : 'duration-discounts';
        $this->view->backToListURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'prices&tab='.$localDiscountTabToReturn);
        $this->view->formAction = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-item-discount&noheader=true');
        if(!is_null($localDetails) && $objDiscount->canEdit($objPriceGroup->getPartnerId()))
        {
            $this->view->discountId = $localDetails['discount_id'];
            if($objPricePlansObserver->canShowOnlyPartnerOwned())
            {
                $this->view->pricePlanDropDownOptions = $objPricePlansObserver->getTranslatedDropDownOptionsByPartnerId(
                    get_current_user_id(), $localDetails['price_plan_id'], "", $this->lang->getText('NRS_SELECT_DROPDOWN_TEXT')
                );
            } else
            {
                $this->view->pricePlanDropDownOptions = $objPricePlansObserver->getTranslatedDropDownOptions(
                    $localDetails['price_plan_id'], "", $this->lang->getText('NRS_SELECT_DROPDOWN_TEXT')
                );
            }
            $this->view->discountType = $localDetails['discount_type'];
            $this->view->durationFromDays = $this->dbSettings->getAdminDaysByPriceTypeFromPeriod($localDetails['period_from']);
            $this->view->durationFromHours = $this->dbSettings->getAdminHoursByPriceTypeFromPeriod($localDetails['period_from']);
            $this->view->durationTillDays = $this->dbSettings->getAdminDaysByPriceTypeFromPeriod($localDetails['period_till']);
            $this->view->durationTillHours = $this->dbSettings->getAdminHoursByPriceTypeFromPeriod($localDetails['period_till']);
            $this->view->discountPercentage = $localDetails['discount_percentage'];
        } else
        {
            $this->view->discountId = 0;
            if($objPricePlansObserver->canShowOnlyPartnerOwned())
            {
                $this->view->pricePlanDropDownOptions = $objPricePlansObserver->getTranslatedDropDownOptionsByPartnerId(
                    get_current_user_id(), 0, "", $this->lang->getText('NRS_SELECT_DROPDOWN_TEXT'));
            } else
            {
                $this->view->pricePlanDropDownOptions = $objPricePlansObserver->getTranslatedDropDownOptions(
                    0, "", $this->lang->getText('NRS_SELECT_DROPDOWN_TEXT')
                );
            }
            $this->view->discountType = in_array($paramDiscountType, array(1, 2)) ? intval($paramDiscountType) : 1;
            $this->view->durationFromDays = '';
            $this->view->durationFromHours = '';
            $this->view->durationTillDays = '';
            $this->view->durationTillHours = '';
            $this->view->discountPercentage = '';
        }
        if($paramDiscountType == 2)
        {
            $this->view->discountTabToReturn = 'discounts-in-advance';
            $this->view->pageTitle = $this->lang->getText('NRS_ADMIN_DISCOUNT_ITEM_BOOKING_IN_ADVANCE_TEXT');
            $this->view->fromTitle = $this->lang->getText('NRS_ADMIN_DISCOUNT_DURATION_BEFORE_TEXT');
            $this->view->toTitle = $this->lang->getText('NRS_ADMIN_DISCOUNT_DURATION_UNTIL_TEXT');
        } else
        {
            $this->view->discountTabToReturn = 'duration-discounts';
            $this->view->pageTitle = $this->lang->getText('NRS_ADMIN_DISCOUNT_ITEM_BOOKING_DURATION_TEXT');
            $this->view->fromTitle = $this->lang->getText('NRS_ADMIN_DISCOUNT_DURATION_FROM_TEXT');
            $this->view->toTitle = $this->lang->getText('NRS_ADMIN_DISCOUNT_DURATION_TILL_TEXT');
        }

        // Get the template
        $retContent = $this->getTemplate('ItemPrice', 'AddEditDiscount', 'Form');

        return $retContent;
    }
}
