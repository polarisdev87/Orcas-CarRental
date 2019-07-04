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
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Pricing\PriceGroup;
use NativeRentalSystem\Controllers\Admin\AbstractController;
use NativeRentalSystem\Models\Pricing\PricePlan;
use NativeRentalSystem\Models\Pricing\PricePlansObserver;
use NativeRentalSystem\Models\Role\PartnersObserver;

final class AddEditPriceGroupController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    private function processDelete($paramPriceGroupId)
    {
        $objPriceGroup = new PriceGroup($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramPriceGroupId);
        if($objPriceGroup->canEdit())
        {
            $deleted = $objPriceGroup->delete();

            if($deleted)
            {
                // Delete corresponding items
                $objPricePlansObserver = new PricePlansObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
                $pricePlanIds = $objPricePlansObserver->getAllIds($paramPriceGroupId);
                foreach ($pricePlanIds AS $pricePlanId)
                {
                    $objPricePlan = new PricePlan($this->conf, $this->lang, $this->dbSettings->getSettings(), $pricePlanId);
                    $deleted2 = $objPricePlan->delete();

                    if($deleted2)
                    {
                        // Delete corresponding discounts
                        $objDiscountsObserver = new PricePlanDiscountsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
                        $discountIds = $objDiscountsObserver->getAllIds("", $pricePlanId);
                        foreach ($discountIds AS $discountId)
                        {
                            $objDiscount = new PricePlanDiscount($this->conf, $this->lang, $this->dbSettings->getSettings(), $discountId);
                            $objDiscount->delete();
                        }
                    }
                }
            }

            $this->processDebugMessages($objPriceGroup->getDebugMessages());
            $this->processOkayMessages($objPriceGroup->getOkayMessages());
            $this->processErrorMessages($objPriceGroup->getErrorMessages());
        }

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'prices&tab=price-groups');
        exit;
    }

    private function processSave($paramPriceGroupId)
    {
        $objPriceGroup = new PriceGroup($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramPriceGroupId);
        if($paramPriceGroupId == 0 || $objPriceGroup->canEdit())
        {
            $saved = $objPriceGroup->save();
            if($saved && $this->lang->canTranslateSQL())
            {
                $objPriceGroup->registerForTranslation();
            }

            $this->processDebugMessages($objPriceGroup->getDebugMessages());
            $this->processOkayMessages($objPriceGroup->getOkayMessages());
            $this->processErrorMessages($objPriceGroup->getErrorMessages());
        }
        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'prices&tab=price-groups');
        exit;
    }

    public function getContent()
    {
        // Create mandatory instances
        $objPartnersObserver = new PartnersObserver($this->conf, $this->lang, $this->dbSettings->getSettings());

        // Process actions
        if(isset($_GET['delete_price_group'])) { $this->processDelete($_GET['delete_price_group']); }
        if(isset($_POST['save_price_group'], $_POST['price_group_id'])) { $this->processSave($_POST['price_group_id']); }

        $paramPriceGroupId = isset($_GET['price_group_id']) ? $_GET['price_group_id'] : 0;
        $objPriceGroup = new PriceGroup($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramPriceGroupId);
        $localDetails = $objPriceGroup->getDetails();

        // Set the view variables
        $this->view->backToListURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'prices&tab=price-groups');
        $this->view->formAction = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-price-group&noheader=true');
        if(!is_null($localDetails) && $objPriceGroup->canEdit())
        {
            // Override default value
            $this->view->priceGroupId = $objPriceGroup->getId();
            $this->view->priceGroupName = $localDetails['edit_price_group_name'];
            $this->view->partnersDropDownOptions = $objPartnersObserver->getDropDownOptions($localDetails['partner_id'], 0, $this->lang->getText('NRS_SELECT_DROPDOWN_TEXT'));
        } else
        {
            $this->view->priceGroupId = 0;
            $this->view->priceGroupName = '';
            $this->view->partnersDropDownOptions = $objPartnersObserver->getDropDownOptions(0, 0, $this->lang->getText('NRS_SELECT_DROPDOWN_TEXT'));
        }
        $this->view->isManager = current_user_can('manage_'.$this->conf->getExtensionPrefix().'all_items');

        // Get the template
        $retContent = $this->getTemplate('ItemPrice', 'AddEditPriceGroup', 'Form');

        return $retContent;
    }
}
