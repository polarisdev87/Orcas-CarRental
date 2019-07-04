<?php
/**
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Admin\Extras;
use NativeRentalSystem\Models\Block\ExtraBlocksObserver;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Deposit\DepositsObserver;
use NativeRentalSystem\Models\Discount\ExtraDiscountsObserver;
use NativeRentalSystem\Models\Extra\ExtrasObserver;
use NativeRentalSystem\Models\Option\ExtraOptionsObserver;
use NativeRentalSystem\Models\PriceTable\ExtrasPriceTable;
use NativeRentalSystem\Controllers\Admin\AbstractController;

final class ExtrasController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    public function getContent()
    {
        // Create mandatory instances
        $objExtrasObserver = new ExtrasObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objOptionsObserver = new ExtraOptionsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objBlocksObserver = new ExtraBlocksObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objPriceTable = new ExtrasPriceTable($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objDepositsObserver = new DepositsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objDiscountsObserver = new ExtraDiscountsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());

        // Get the tab values
        $tabs = $this->getTabParams(array(
            'price-table', 'extras', 'options', 'duration-discounts', 'discounts-in-advance', 'blocks'
        ), 'price-table');

        // 1. Set the view variables - Tab settings
        $this->view->priceTableTabChecked = !empty($tabs['price-table']) ? ' checked="checked"' : '';
        $this->view->extrasTabChecked = !empty($tabs['extras']) ? ' checked="checked"' : '';
        $this->view->optionsTabChecked = !empty($tabs['options']) ? ' checked="checked"' : '';
        $this->view->durationDiscountsTabChecked = !empty($tabs['duration-discounts']) ? ' checked="checked"' : '';
        $this->view->discountsInAdvanceTabChecked = !empty($tabs['discounts-in-advance']) ? ' checked="checked"' : '';
        $this->view->blocksTabChecked = !empty($tabs['blocks']) ? ' checked="checked"' : '';

        // 2. Set the view variables - other
        $this->view->depositsEnabled = $objDepositsObserver->areDepositsEnabled();

        // Extras price table tab
        $this->view->priceTable = $objPriceTable->getPriceTable();

        // Extras tab
        $this->view->addNewExtraURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-extra&extra_id=0');
        $this->view->adminExtrasList = $objExtrasObserver->getAdminList();

        // Extra options tab
        $this->view->addNewOptionURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-extra-option&extra_id=0');
        $this->view->adminOptionsList = $objOptionsObserver->getAdminList();

        // Duration discounts tab
        $this->view->addNewDurationDiscountURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-extra-discount&discount_type=3&discount_id=0');
        $this->view->adminDurationDiscountsGroups = $objDiscountsObserver->getAdminListForDiscountDuration();

        // Discounts in advance tab
        $this->view->addNewAdvanceDiscountURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-extra-discount&discount_type=4&discount_id=0');
        $this->view->adminBookingInAdvanceDiscountsGroups = $objDiscountsObserver->getAdminListForBookingInAdvance();

        // Blocked extras tab
        $this->view->addNewBlockURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-extra-block');
        $this->view->adminBlockedList = $objBlocksObserver->getAdminList();

        // Get the template
        $retContent = $this->getTemplate('Extras', 'ExtrasManager', 'Tabs');

        return $retContent;
    }
}
