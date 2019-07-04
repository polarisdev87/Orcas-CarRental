<?php
/**
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Admin\Item;
use NativeRentalSystem\Models\Block\ItemBlocksObserver;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Item\BodyTypesObserver;
use NativeRentalSystem\Models\Item\FeaturesObserver;
use NativeRentalSystem\Models\Item\FuelTypesObserver;
use NativeRentalSystem\Models\Item\ManufacturersObserver;
use NativeRentalSystem\Models\Item\TransmissionTypesObserver;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Deposit\DepositsObserver;
use NativeRentalSystem\Models\Item\ItemsObserver;
use NativeRentalSystem\Controllers\Admin\AbstractController;
use NativeRentalSystem\Models\Option\ItemOptionsObserver;

final class ItemController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    public function getContent()
    {
        // Create mandatory instances
        $objItemsObserver = new ItemsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objOptionsObserver = new ItemOptionsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objBlocksObserver = new ItemBlocksObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objDepositsObserver = new DepositsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objManufacturersObserver = new ManufacturersObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objBodyTypesObserver = new BodyTypesObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objTransmissionTypesObserver = new TransmissionTypesObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objFuelTypesObserver = new FuelTypesObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objFeaturesObserver = new FeaturesObserver($this->conf, $this->lang, $this->dbSettings->getSettings());

        // Get the tab values
        $tabs = $this->getTabParams(array(
            'items', 'manufacturers', 'body-types', 'transmission-types', 'fuel-types', 'features', 'options', 'blocks'
        ), 'items');

        // 1. Set the view variables - Tab settings
        $this->view->itemListTabChecked = !empty($tabs['items']) ? ' checked="checked"' : '';
        $this->view->itemManufacturersTabChecked = !empty($tabs['manufacturers']) ? ' checked="checked"' : '';
        $this->view->itemBodyTypesTabChecked = !empty($tabs['body-types']) ? ' checked="checked"' : '';
        $this->view->itemTransmissionTypesTabChecked = !empty($tabs['transmission-types']) ? ' checked="checked"' : '';
        $this->view->itemFuelTypesTabChecked = !empty($tabs['fuel-types']) ? ' checked="checked"' : '';
        $this->view->itemFeaturesTabChecked = !empty($tabs['features']) ? ' checked="checked"' : '';
        $this->view->itemOptionsTabChecked = !empty($tabs['options']) ? ' checked="checked"' : '';
        $this->view->itemBlocksTabChecked = !empty($tabs['blocks']) ? ' checked="checked"' : '';

        // Set the view variables - Items tab
        $this->view->addNewItemURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-item&item_id=0');
        $this->view->depositsEnabled = $objDepositsObserver->areDepositsEnabled();
        $this->view->adminItemList = $objItemsObserver->getAdminList();

        // Set the view variables - Manufacturers tab
        $this->view->addNewManufacturerURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-manufacturer&manufacturer_id=0');
        $this->view->adminManufacturersList = $objManufacturersObserver->getAdminList();

        // Set the view variables - Body types tab
        $this->view->addNewBodyTypeURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-body-type&body_type_id=0');
        $this->view->adminBodyTypesList = $objBodyTypesObserver->getAdminList();

        // Set the view variables - Transmission types tab
        $this->view->addNewTransmissionTypeURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-transmission-type&transmission_type_id=0');
        $this->view->adminTransmissionTypesList = $objTransmissionTypesObserver->getAdminList();

        // Set the view variables - Fuel types tab
        $this->view->addNewFuelTypeURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-fuel-type&fuel_type_id=0');
        $this->view->adminFuelTypesList = $objFuelTypesObserver->getAdminList();

        // Set the view variables - Features tab
        $this->view->addNewFeatureURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-feature&feature_id=0');
        $this->view->adminFeaturesList = $objFeaturesObserver->getAdminList();

        // Set the view variables - Item options tab
        $this->view->addNewOptionURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-item-option&item_id=0');
        $this->view->adminOptionsList = $objOptionsObserver->getAdminList();

        // Set the view variables - Item blocks tab
        $this->view->addNewBlockURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-item-block');
        $this->view->adminBlockedList = $objBlocksObserver->getAdminList();

        // Get the template
        $retContent = $this->getTemplate('Item', 'ItemManager', 'Tabs');

        return $retContent;
    }
}
