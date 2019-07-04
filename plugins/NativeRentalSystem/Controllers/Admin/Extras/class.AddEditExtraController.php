<?php
/**
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Admin\Extras;
use NativeRentalSystem\Models\Booking\BookingsObserver;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Discount\ExtraDiscount;
use NativeRentalSystem\Models\Discount\ExtraDiscountsObserver;
use NativeRentalSystem\Models\Formatting\StaticFormatter;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Deposit\DepositsObserver;
use NativeRentalSystem\Models\Extra\Extra;
use NativeRentalSystem\Models\Item\ItemsObserver;
use NativeRentalSystem\Controllers\Admin\AbstractController;
use NativeRentalSystem\Models\Option\ExtraOption;
use NativeRentalSystem\Models\Option\ExtraOptionsObserver;
use NativeRentalSystem\Models\Role\PartnersObserver;

final class AddEditExtraController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    private function processDelete($paramExtraId)
    {
        $objExtra = new Extra($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramExtraId);
        if($objExtra->canEdit())
        {
            $deleted = $objExtra->delete();

            if($deleted)
            {
                // Delete corresponding discounts
                $objDiscountsObserver = new ExtraDiscountsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
                $discountIds = $objDiscountsObserver->getAllIds("", $paramExtraId);
                foreach ($discountIds AS $discountId)
                {
                    $objDiscount = new ExtraDiscount($this->conf, $this->lang, $this->dbSettings->getSettings(), $discountId);
                    $objDiscount->delete();
                }

                // Delete corresponding extra options
                $objOptionsObserver = new ExtraOptionsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
                $optionIds = $objOptionsObserver->getAllIds($paramExtraId);
                foreach ($optionIds AS $optionId)
                {
                    $objOption = new ExtraOption($this->conf, $this->lang, $this->dbSettings->getSettings(), $optionId);
                    $objOption->delete();
                }
            }

            $this->processDebugMessages($objExtra->getDebugMessages());
            $this->processOkayMessages($objExtra->getOkayMessages());
            $this->processErrorMessages($objExtra->getErrorMessages());
        }

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'extras-manager&tab=extras');
        exit;
    }

    private function processSave($paramExtraId)
    {
        $objBookingsObserver = new BookingsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objExtra = new Extra($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramExtraId);
        if($paramExtraId == 0 || $objExtra->canEdit())
        {
            $oldSKU = $objExtra->getSKU();
            $saved = $objExtra->save();
            $newSKU = $objExtra->getSKU();
            if($paramExtraId > 0 && $saved && $oldSKU != '' && $newSKU != $oldSKU)
            {
                $objBookingsObserver->changeExtraSKU($oldSKU, $newSKU);
            }
            if($saved && $this->lang->canTranslateSQL())
            {
                $objExtra->registerForTranslation();
            }

            $this->processDebugMessages($objExtra->getDebugMessages());
            $this->processOkayMessages($objExtra->getOkayMessages());
            $this->processErrorMessages($objExtra->getErrorMessages());
        }

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'extras-manager&tab=extras');
        exit;
    }

    public function getContent()
    {
        // Create mandatory instances
        $objItemsObserver = new ItemsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objDepositsObserver = new DepositsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objPartnersObserver = new PartnersObserver($this->conf, $this->lang, $this->dbSettings->getSettings());

        if(isset($_GET['delete_extra'])) { $this->processDelete($_GET['delete_extra']); }
        if(isset($_POST['save_extra']) && isset($_POST['extra_id'])) { $this->processSave($_POST['extra_id']); }

        $paramExtraId = isset($_GET['extra_id']) ? $_GET['extra_id'] : 0;
        $objExtra = new Extra($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramExtraId);
        $localDetails = $objExtra->getDetailsWithItemAndPartner();

        // Set the view variables
        $this->view->backToListURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'extras-manager&tab=extras');
        $this->view->formAction = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-extra&noheader=true');
        if (!is_null($localDetails) && $objExtra->canEdit())
        {
            $this->view->extraId = $localDetails['extra_id'];
            $this->view->extraSKU = $localDetails['edit_extra_sku'];
            $this->view->extraName = $localDetails['edit_extra_name'];
            $this->view->partnersDropDownOptions = $objPartnersObserver->getDropDownOptions($localDetails['partner_id'], 0, $this->lang->getText('NRS_SELECT_DROPDOWN_TEXT'));
            if($objItemsObserver->canShowOnlyPartnerOwned())
            {
                $this->view->itemDropDownOptions = $objItemsObserver->getTranslatedDropDownOptionsByPartnerId(
                    get_current_user_id(), $localDetails['item_id'], 0, $this->lang->getText('NRS_SELECT_DROPDOWN_TEXT')
                );
            } else
            {
                $this->view->itemDropDownOptions = $objItemsObserver->getTranslatedDropDownOptions(
                    $localDetails['item_id'], 0, $this->lang->getText('NRS_SELECT_DROPDOWN_TEXT')
                );
            }
            $this->view->optionsMeasurementUnit = $localDetails['edit_options_measurement_unit'];
            $this->view->unitsInStockDropDownOptions = StaticFormatter::getNumberDropDownOptions(1, 100, $localDetails['units_in_stock'], "0");
            $this->view->maxUnitsPerBookingDropDownOptions = StaticFormatter::getNumberDropDownOptions(1, 100, $localDetails['max_units_per_booking'], "0");
            $this->view->extraPrice = $localDetails['price'];
            $this->view->priceTypeDropDownOptions = $objExtra->getPriceTypesDropDownOptions($localDetails['price_type']);
            $this->view->fixedExtraRentalDeposit = $localDetails['fixed_rental_deposit'];
            $this->view->dropDownDisplayModeChecked = $localDetails['options_display_mode'] == 1 ? ' checked="checked"' : '';
            $this->view->sliderDisplayModeChecked = $localDetails['options_display_mode'] == 2 ? ' checked="checked"' : '';
        } else
        {
            $this->view->extraId = 0;
            $this->view->extraSKU = $objExtra->generateSKU();
            $objExtra = new Extra($this->conf, $this->lang, $this->dbSettings->getSettings(), 0);
            $this->view->extraName = "";
            $this->view->partnersDropDownOptions = $objPartnersObserver->getDropDownOptions(0, 0, $this->lang->getText('NRS_SELECT_DROPDOWN_TEXT'));
            if($objItemsObserver->canShowOnlyPartnerOwned())
            {
                $this->view->itemDropDownOptions = $objItemsObserver->getTranslatedDropDownOptionsByPartnerId(
                    get_current_user_id(), 0, 0, $this->lang->getText('NRS_SELECT_DROPDOWN_TEXT')
                );
            } else
            {
                $this->view->itemDropDownOptions = $objItemsObserver->getTranslatedDropDownOptions(
                    0, 0, $this->lang->getText('NRS_SELECT_DROPDOWN_TEXT')
                );
            }
            $this->view->optionsMeasurementUnit = "";
            $this->view->unitsInStockDropDownOptions = StaticFormatter::getNumberDropDownOptions(1, 100, 50, "0");
            $this->view->maxUnitsPerBookingDropDownOptions = StaticFormatter::getNumberDropDownOptions(1, 100, 2, "0");
            $this->view->extraPrice = "";
            $this->view->priceTypeDropDownOptions = $objExtra->getPriceTypesDropDownOptions($this->dbSettings->getSetting('conf_price_calculation_type'));
            $this->view->fixedExtraRentalDeposit = "";
            $this->view->dropDownDisplayModeChecked = ' checked="checked"';
            $this->view->sliderDisplayModeChecked = '';
        }
        $this->view->isManager = current_user_can('manage_'.$this->conf->getExtensionPrefix().'all_extras');
        $this->view->depositsEnabled = $objDepositsObserver->areDepositsEnabled();

        // Get the template
        $retContent = $this->getTemplate('Extras', 'AddEditExtra', 'Form');

        return $retContent;
    }
}
