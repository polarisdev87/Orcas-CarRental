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
use NativeRentalSystem\Models\Deposit\DepositsObserver;
use NativeRentalSystem\Models\Item\ItemsObserver;
use NativeRentalSystem\Models\Discount\PricePlanDiscountsObserver;
use NativeRentalSystem\Models\PriceTable\ItemsPriceTable;
use NativeRentalSystem\Models\Pricing\PriceGroupsObserver;
use NativeRentalSystem\Controllers\Admin\AbstractController;

final class ItemPriceController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    public function getContent()
    {
        // Create mandatory instances
        $objItemsObserver = new ItemsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objPriceTable = new ItemsPriceTable($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objPriceGroupsObserver = new PriceGroupsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objDepositsObserver = new DepositsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objDiscountsObserver = new PricePlanDiscountsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());

        $paramPriceGroupId = isset($_GET['price_group_id']) ? $_GET['price_group_id'] : 0;

        // Get the tab values
        $tabs = $this->getTabParams(array(
            'price-table', 'price-groups', 'price-plans', 'duration-discounts', 'discounts-in-advance'
        ), 'price-table');

        // 1. Set the view variables - Tab settings
        $this->view->priceTableTabChecked = !empty($tabs['price-table']) ? ' checked="checked"' : '';
        $this->view->priceGroupsTabChecked = !empty($tabs['price-groups']) ? ' checked="checked"' : '';
        $this->view->pricePlansTabChecked = !empty($tabs['price-plans']) ? ' checked="checked"' : '';
        $this->view->durationDiscountsTabChecked = !empty($tabs['duration-discounts']) ? ' checked="checked"' : '';
        $this->view->discountsInAdvanceTabChecked = !empty($tabs['discounts-in-advance']) ? ' checked="checked"' : '';

        // Set the view variables - price table tab
        $this->view->priceTable = $objPriceTable->getPriceTable();
        $this->view->itemsClassified = $objItemsObserver->areItemsClassified();
        $this->view->depositsEnabled = $objDepositsObserver->areDepositsEnabled();

        // Price groups tab
        $this->view->addNewPriceGroupURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-price-group&price_group_id=0');
        $this->view->adminPriceGroupsList = $objPriceGroupsObserver->getAdminList();

        // Price plans tab
        $this->view->addNewPricePlanPage = $this->conf->getURLPrefix().'add-edit-price-plan';
        $this->view->ajaxSecurityNonce = wp_create_nonce($this->conf->getURLPrefix().'admin-ajax-nonce');
        $this->view->extensionFolder = $this->conf->getExtensionFolder();
        if($objPriceGroupsObserver->canShowOnlyPartnerOwned())
        {
            $this->view->priceGroupDropDownOptions = $objPriceGroupsObserver->getTranslatedDropDownOptionsByPartnerId(
                get_current_user_id(), $paramPriceGroupId, "", $this->lang->getText('NRS_SELECT_DROPDOWN_TEXT')
            );
        } else
        {
            $this->view->priceGroupDropDownOptions = $objPriceGroupsObserver->getTranslatedDropDownOptions(
                $paramPriceGroupId, "", $this->lang->getText('NRS_SELECT_DROPDOWN_TEXT')
            );
        }

        // Duration discounts tab
        $this->view->addNewDurationDiscountURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-item-discount&discount_type=1&discount_id=0');
        $this->view->adminDurationDiscountGroups = $objDiscountsObserver->getAdminListForDiscountDuration();

        // Discounts in advance tab
        $this->view->addNewAdvanceDiscountURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-item-discount&discount_type=2&discount_id=0');
        $this->view->adminBookingInAdvanceDiscountGroups = $objDiscountsObserver->getAdminListForBookingInAdvance();

        // Get the template
        $retContent = $this->getTemplate('ItemPrice', 'ItemPriceManager', 'Tabs');

        return $retContent;
    }
}
