<?php
/**
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Admin\Item;
use NativeRentalSystem\Models\Booking\BookingsObserver;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Extra\ExtrasObserver;
use NativeRentalSystem\Models\Formatting\StaticFormatter;
use NativeRentalSystem\Models\Item\BodyTypesObserver;
use NativeRentalSystem\Models\Item\FeaturesObserver;
use NativeRentalSystem\Models\Item\FuelTypesObserver;
use NativeRentalSystem\Models\Item\ManufacturersObserver;
use NativeRentalSystem\Models\Item\TransmissionTypesObserver;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Item\Item;
use NativeRentalSystem\Models\Deposit\DepositsObserver;
use NativeRentalSystem\Models\Item\ItemsObserver;
use NativeRentalSystem\Models\Location\LocationsObserver;
use NativeRentalSystem\Models\Pricing\PriceGroupsObserver;
use NativeRentalSystem\Controllers\Admin\AbstractController;
use NativeRentalSystem\Models\Role\PartnersObserver;

final class AddEditItemController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    private function processDelete($paramItemId)
    {
        $objItem = new Item($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramItemId);
        if($objItem->canEdit())
        {
            $deleted = $objItem->delete();

            if($deleted)
            {
                // Delete corresponding extras and all data related to them
                $objExtrasObserver = new ExtrasObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
                $objExtrasObserver->explicitDeleteByItemId($paramItemId);
            }

            $this->processDebugMessages($objItem->getDebugMessages());
            $this->processOkayMessages($objItem->getOkayMessages());
            $this->processErrorMessages($objItem->getErrorMessages());
        }

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'manager&tab=items');
        exit;
    }

    private function processSave($paramItemId)
    {
        $objBookingsObserver = new BookingsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objItem = new Item($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramItemId);
        if($paramItemId == 0 || $objItem->canEdit())
        {
            $oldSKU = $objItem->getSKU();
            $saved = $objItem->save();
            $newSKU = $objItem->getSKU();
            if($paramItemId > 0 && $saved && $oldSKU != '' && $newSKU != $oldSKU)
            {
                $objBookingsObserver->changeExtraSKU($oldSKU, $newSKU);
            }
            if($saved && $this->lang->canTranslateSQL())
            {
                $objItem->registerForTranslation();
            }

            $this->processDebugMessages($objItem->getDebugMessages());
            $this->processOkayMessages($objItem->getOkayMessages());
            $this->processErrorMessages($objItem->getErrorMessages());
        }

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'manager&tab=items');
        exit;
    }

    public function getContent()
    {
        // Create mandatory instances
        $objItemsObserver = new ItemsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objLocationsObserver = new LocationsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objPartnersObserver = new PartnersObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objManufacturersObserver = new ManufacturersObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objBodyTypesObserver = new BodyTypesObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objFuelTypesObserver = new FuelTypesObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objTransmissionTypesObserver = new TransmissionTypesObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objPriceGroupsObserver = new PriceGroupsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objDepositsObserver = new DepositsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objFeaturesObserver = new FeaturesObserver($this->conf, $this->lang, $this->dbSettings->getSettings());

        if(isset($_GET['delete_item'])) { $this->processDelete($_GET['delete_item']); }
        if(isset($_POST['save_item'], $_POST['item_id'])) { $this->processSave($_POST['item_id']); }

        $paramItemId = isset($_GET['item_id']) ? $_GET['item_id'] : 0;
        $objItem = new Item($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramItemId);
        $localDetails = $objItem->getDetails();

        // Set the view variables
        $this->view->backToListURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'manager&tab=items');
        $this->view->formAction = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-item&noheader=true');
        if (!is_null($localDetails) && $objItem->canEdit())
        {
            $this->view->itemId = $localDetails['item_id'];
            $this->view->itemSKU = $localDetails['edit_item_sku'];
            $this->view->modelName = $localDetails['edit_model_name'];
            $this->view->optionsMeasurementUnit = $localDetails['edit_options_measurement_unit'];
            $this->view->itemPagesDropDown = $objItemsObserver->getPagesDropDown($localDetails['item_page_id'], "item_page_id", "item_page_id");
            $this->view->partnersDropDownOptions = $objPartnersObserver->getDropDownOptions($localDetails['partner_id'], 0, $this->lang->getText('NRS_SELECT_DROPDOWN_TEXT'));
            $this->view->manufacturersDropDownOptions = $objManufacturersObserver->getTranslatedDropDownOptions($localDetails['manufacturer_id'], 0, $this->lang->getText('NRS_SELECT_DROPDOWN_TEXT'));
            $this->view->bodyTypesDropDownOptions = $objBodyTypesObserver->getTranslatedDropDownOptions($localDetails['body_type_id'], 0, $this->lang->getText('NRS_SELECT_DROPDOWN_TEXT'));
            $this->view->fuelTypesDropDownOptions = $objFuelTypesObserver->getTranslatedDropDownOptions($localDetails['fuel_type_id'], 0, $this->lang->getText('NRS_SELECT_DROPDOWN_TEXT'));
            $this->view->transmissionTypesDropDownOptions = $objTransmissionTypesObserver->getTranslatedDropDownOptions($localDetails['transmission_type_id'], 0, $this->lang->getText('NRS_SELECT_DROPDOWN_TEXT'));
            $this->view->fuelConsumption = $localDetails['edit_fuel_consumption'];
            $this->view->engineCapacity = $localDetails['edit_engine_capacity'];
            $this->view->mileage = $localDetails['edit_mileage'];
            $this->view->itemFeatures = $objFeaturesObserver->getFeatureCheckboxesByItemId($localDetails['item_id']);
            $this->view->unitsInStockDropDownOptions = StaticFormatter::getNumberDropDownOptions(1, 100, $localDetails['units_in_stock'], "0");
            $this->view->maxUnitsPerBookingDropDownOptions = StaticFormatter::getNumberDropDownOptions(1, 100, $localDetails['max_units_per_booking'], "0");
            $this->view->pickupSelectOptions = $objLocationsObserver->getTranslatedPickupSelectOptions($localDetails['item_id']);
            $this->view->returnSelectOptions = $objLocationsObserver->getTranslatedReturnSelectOptions($localDetails['item_id']);
            $this->view->itemPassengersDropDownOptions = StaticFormatter::getNumberDropDownOptions(0, 100, $localDetails['max_passengers'], $this->lang->getText('NRS_DONT_DISPLAY_TEXT'));
            $this->view->itemLuggageDropDownOptions = StaticFormatter::getNumberDropDownOptions(0, 100, $localDetails['max_luggage'], $this->lang->getText('NRS_DONT_DISPLAY_TEXT'));
            $this->view->itemDoorsDropDownOptions = StaticFormatter::getNumberDropDownOptions(0, 10, $localDetails['item_doors'], $this->lang->getText('NRS_DONT_DISPLAY_TEXT'));
            $this->view->driversAgeDropDownOptions = StaticFormatter::getNumberDropDownOptions(0, 30, $localDetails['min_driver_age'], $this->lang->getText('NRS_DONT_DISPLAY_TEXT'));
            $this->view->dropDownDisplayModeChecked = $localDetails['options_display_mode'] == 1 ? ' checked="checked"' : '';
            $this->view->sliderDisplayModeChecked = $localDetails['options_display_mode'] == 2 ? ' checked="checked"' : '';
            $this->view->itemImage1 = $localDetails['item_image_1'];
            $this->view->itemImage2 = $localDetails['item_image_2'];
            $this->view->itemImage3 = $localDetails['item_image_3'];
            $this->view->demoItemImage1 = $localDetails['demo_item_image_1'];
            $this->view->demoItemImage2 = $localDetails['demo_item_image_2'];
            $this->view->demoItemImage3 = $localDetails['demo_item_image_3'];
            if($objPriceGroupsObserver->canShowOnlyPartnerOwned())
            {
                $this->view->priceGroupDropDownOptions = $objPriceGroupsObserver->getTranslatedDropDownOptionsByPartnerId(
                    get_current_user_id(), $localDetails['price_group_id'], "", $this->lang->getText('NRS_SELECT_DROPDOWN_TEXT')
                );
            } else
            {
                $this->view->priceGroupDropDownOptions = $objPriceGroupsObserver->getTranslatedDropDownOptions(
                    $localDetails['price_group_id'], "", $this->lang->getText('NRS_SELECT_DROPDOWN_TEXT')
                );
            }
            $this->view->fixedItemRentalDeposit = $localDetails['fixed_rental_deposit'];
            $this->view->itemDisplayInSliderChecked = $localDetails['display_in_slider'] == 1 ? ' checked="checked"' : '';
            $this->view->itemDisplayInItemListChecked = $localDetails['display_in_item_list'] == 1 ? ' checked="checked"' : '';
            $this->view->itemDisplayInPriceTableChecked = $localDetails['display_in_price_table'] == 1 ? ' checked="checked"' : '';
            $this->view->itemDisplayInCalendarChecked = $localDetails['display_in_calendar'] == 1 ? ' checked="checked"' : '';
            $this->view->itemAvailableChecked = $localDetails['enabled'] == 1 ? ' checked="checked"' : '';
        } else
        {
            $this->view->itemId = 0;
            $this->view->itemSKU = $objItem->generateSKU();
            $this->view->modelName = "";
            $this->view->optionsMeasurementUnit = "";
            $this->view->itemPagesDropDown = '';
            $this->view->partnersDropDownOptions = $objPartnersObserver->getDropDownOptions(0, 0, $this->lang->getText('NRS_SELECT_DROPDOWN_TEXT'));
            $this->view->manufacturersDropDownOptions = $objManufacturersObserver->getTranslatedDropDownOptions(0, 0, $this->lang->getText('NRS_SELECT_DROPDOWN_TEXT'));
            $this->view->bodyTypesDropDownOptions = $objBodyTypesObserver->getTranslatedDropDownOptions(0, 0, $this->lang->getText('NRS_SELECT_DROPDOWN_TEXT'));
            $this->view->fuelTypesDropDownOptions = $objFuelTypesObserver->getTranslatedDropDownOptions(0, 0, $this->lang->getText('NRS_SELECT_DROPDOWN_TEXT'));
            $this->view->transmissionTypesDropDownOptions = $objTransmissionTypesObserver->getTranslatedDropDownOptions(0, 0, $this->lang->getText('NRS_SELECT_DROPDOWN_TEXT'));
            $this->view->fuelConsumption = '';
            $this->view->engineCapacity = '';
            $this->view->mileage = '';
            $this->view->itemFeatures = $objFeaturesObserver->getFeatureCheckboxesByItemId(0);
            $this->view->unitsInStockDropDownOptions = StaticFormatter::getNumberDropDownOptions(1, 100, 1, "0");
            $this->view->maxUnitsPerBookingDropDownOptions = StaticFormatter::getNumberDropDownOptions(1, 100, 1, "0");
            $this->view->pickupSelectOptions = $objLocationsObserver->getTranslatedPickupSelectOptions(0);
            $this->view->returnSelectOptions = $objLocationsObserver->getTranslatedReturnSelectOptions(0);
            $this->view->itemPassengersDropDownOptions = StaticFormatter::getNumberDropDownOptions(0, 100, 0, $this->lang->getText('NRS_DONT_DISPLAY_TEXT'));
            $this->view->itemLuggageDropDownOptions = StaticFormatter::getNumberDropDownOptions(0, 100, 0, $this->lang->getText('NRS_DONT_DISPLAY_TEXT'));
            $this->view->itemDoorsDropDownOptions = StaticFormatter::getNumberDropDownOptions(0, 10, 0, $this->lang->getText('NRS_DONT_DISPLAY_TEXT'));
            $this->view->driversAgeDropDownOptions = StaticFormatter::getNumberDropDownOptions(0, 30, 0, $this->lang->getText('NRS_DONT_DISPLAY_TEXT'));
            $this->view->dropDownDisplayModeChecked = ' checked="checked"';
            $this->view->sliderDisplayModeChecked = '';
            $this->view->itemImage1 = '';
            $this->view->itemImage2 = '';
            $this->view->itemImage3 = '';
            $this->view->demoItemImage1 = 0;
            $this->view->demoItemImage2 = 0;
            $this->view->demoItemImage3 = 0;
            if($objPriceGroupsObserver->canShowOnlyPartnerOwned())
            {
                $this->view->priceGroupDropDownOptions = $objPriceGroupsObserver->getTranslatedDropDownOptionsByPartnerId(get_current_user_id(), 0, "", $this->lang->getText('NRS_SELECT_DROPDOWN_TEXT'));
            } else
            {
                $this->view->priceGroupDropDownOptions = $objPriceGroupsObserver->getTranslatedDropDownOptions(0, "", $this->lang->getText('NRS_SELECT_DROPDOWN_TEXT'));
            }
            $this->view->fixedItemRentalDeposit = "";
            $this->view->itemDisplayInSliderChecked = ' checked="checked"';
            $this->view->itemDisplayInItemListChecked = ' checked="checked"';
            $this->view->itemDisplayInPriceTableChecked = ' checked="checked"';
            $this->view->itemDisplayInCalendarChecked = ' checked="checked"';
            $this->view->itemAvailableChecked = ' checked="checked"'; // Default is checked
        }
        $this->view->isManager = current_user_can('manage_'.$this->conf->getExtensionPrefix().'all_items');
        $this->view->depositsEnabled = $objDepositsObserver->areDepositsEnabled();

        // Get the template
        $retContent = $this->getTemplate('Item', 'AddEditItem', 'Form');

        return $retContent;
    }
}
