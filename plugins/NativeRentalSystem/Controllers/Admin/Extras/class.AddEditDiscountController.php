<?php
/**
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Admin\Extras;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Discount\ExtraDiscount;
use NativeRentalSystem\Models\Extra\Extra;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Extra\ExtrasObserver;
use NativeRentalSystem\Controllers\Admin\AbstractController;

final class AddEditDiscountController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    private function processDelete($paramDiscountId, $paramDiscountType)
    {
        $objDiscount = new ExtraDiscount($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramDiscountId);
        $extraId = $objDiscount->getExtraId();
        $objExtra = new Extra($this->conf, $this->lang, $this->dbSettings->getSettings(), $extraId);
        if($objExtra->canEdit())
        {
            $objDiscount->delete();
        }

        $this->processDebugMessages($objDiscount->getDebugMessages());
        $this->processOkayMessages($objDiscount->getOkayMessages());
        $this->processErrorMessages($objDiscount->getErrorMessages());

        $discountTabToReturn = 'duration-discounts';
        if($paramDiscountType == 3)
        {
            $discountTabToReturn = 'duration-discounts';
        } else if($paramDiscountType == 4)
        {
            $discountTabToReturn = 'discounts-in-advance';
        }

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'extras-manager&tab='.$discountTabToReturn);
        exit;
    }

    private function processSave($paramDiscountId, $paramDiscountType)
    {
        $objDiscount = new ExtraDiscount($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramDiscountId);
        $extraId = $objDiscount->getExtraId();
        $objExtra = new Extra($this->conf, $this->lang, $this->dbSettings->getSettings(), $extraId);
        if($extraId == 0 || $objDiscount->canEdit($objExtra->getPartnerId()))
        {
            $objDiscount->save();

            $this->processDebugMessages($objDiscount->getDebugMessages());
            $this->processOkayMessages($objDiscount->getOkayMessages());
            $this->processErrorMessages($objDiscount->getErrorMessages());
        }

        $discountTabToReturn = 'duration-discounts';
        if($paramDiscountType == 3)
        {
            $discountTabToReturn = 'duration-discounts';
        } else if($paramDiscountType == 4)
        {
            $discountTabToReturn = 'discounts-in-advance';
        }

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'extras-manager&tab='.$discountTabToReturn);
        exit;
    }

    public function getContent()
    {
        // Create mandatory instances
        $objExtrasObserver = new ExtrasObserver($this->conf, $this->lang, $this->dbSettings->getSettings());

        // Process actions
        if(isset($_GET['delete_discount'], $_GET['discount_type'])) { $this->processDelete($_GET['delete_discount'], $_GET['discount_type']); }
        if(isset($_POST['save_discount'], $_POST['discount_id'], $_POST['discount_type'])) { $this->processSave($_POST['discount_id'], $_POST['discount_type']); }

        $paramDiscountId = isset($_GET['discount_id']) ? $_GET['discount_id'] : 0;
        $paramDiscountType = isset($_GET['discount_type']) ? $_GET['discount_type'] : 3;
        $objDiscount = new ExtraDiscount($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramDiscountId);

        $objExtra = new Extra($this->conf, $this->lang, $this->dbSettings->getSettings(), $objDiscount->getExtraId());
        $localDetails = $objDiscount->getDetails();

        // Set the view variables
        $localDiscountTabToReturn = $paramDiscountType == 4 ? 'discounts-in-advance' : 'duration-discounts';
        $this->view->backToListURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'discount-manager&tab='.$localDiscountTabToReturn);
        $this->view->formAction = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-extra-discount&noheader=true');
        if(!is_null($localDetails) && $objDiscount->canEdit($objExtra->getPartnerId()))
        {
            $this->view->discountId = $localDetails['discount_id'];
            if($objExtrasObserver->canShowOnlyPartnerOwned())
            {
                $this->view->extrasDropDownOptions = $objExtrasObserver->getTranslatedExtrasDropDownOptionsByPartnerId(
                    get_current_user_id(), $localDetails['extra_id'], "", $this->lang->getText('NRS_SELECT_DROPDOWN_TEXT')
                );
            } else
            {
                $this->view->extrasDropDownOptions = $objExtrasObserver->getTranslatedExtrasDropDownOptions(
                    $localDetails['extra_id'], "", $this->lang->getText('NRS_SELECT_DROPDOWN_TEXT')
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
            if($objExtrasObserver->canShowOnlyPartnerOwned())
            {
                $this->view->extrasDropDownOptions = $objExtrasObserver->getTranslatedExtrasDropDownOptionsByPartnerId(
                    get_current_user_id(), 0, "", $this->lang->getText('NRS_SELECT_DROPDOWN_TEXT')
                );
            } else
            {
                $this->view->extrasDropDownOptions = $objExtrasObserver->getTranslatedExtrasDropDownOptions(
                    0, "", $this->lang->getText('NRS_SELECT_DROPDOWN_TEXT')
                );
            }
            $this->view->discountType = in_array($paramDiscountType, array(3, 4)) ? intval($paramDiscountType) : 3;
            $this->view->durationFromDays = '';
            $this->view->durationFromHours = '';
            $this->view->durationTillDays = '';
            $this->view->durationTillHours = '';
            $this->view->discountPercentage = '';
        }
        if($paramDiscountType == 4)
        {
            $this->view->discountTabToReturn = 'discounts-in-advance';
            $this->view->pageTitle = $this->lang->getText('NRS_ADMIN_DISCOUNT_EXTRA_BOOKING_IN_ADVANCE_TEXT');
            $this->view->fromTitle = $this->lang->getText('NRS_ADMIN_DISCOUNT_DURATION_BEFORE_TEXT');
            $this->view->toTitle = $this->lang->getText('NRS_ADMIN_DISCOUNT_DURATION_UNTIL_TEXT');
        } else
        {
            $this->view->discountTabToReturn = 'duration-discounts';
            $this->view->pageTitle = $this->lang->getText('NRS_ADMIN_DISCOUNT_EXTRA_BOOKING_DURATION_TEXT');
            $this->view->fromTitle = $this->lang->getText('NRS_ADMIN_DISCOUNT_DURATION_FROM_TEXT');
            $this->view->toTitle = $this->lang->getText('NRS_ADMIN_DISCOUNT_DURATION_TILL_TEXT');
        }

        // Get the template
        $retContent = $this->getTemplate('Extras', 'AddEditDiscount', 'Form');

        return $retContent;
    }
}
