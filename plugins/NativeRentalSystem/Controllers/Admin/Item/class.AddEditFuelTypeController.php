<?php
/**
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Admin\Item;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Extra\ExtrasObserver;
use NativeRentalSystem\Models\Item\FuelType;
use NativeRentalSystem\Models\Item\Item;
use NativeRentalSystem\Models\Item\ItemsObserver;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Controllers\Admin\AbstractController;

final class AddEditFuelTypeController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    private function processDelete($paramFuelTypeId)
    {
        $objFuelType = new FuelType($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramFuelTypeId);
        $deleted = $objFuelType->delete();

        if($deleted)
        {
            // Delete corresponding items
            $objItemsObserver = new ItemsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
            $itemIds = $objItemsObserver->getAllIds(-1, -1, -1, -1, $paramFuelTypeId);
            foreach ($itemIds AS $itemId)
            {
                $objItem = new Item($this->conf, $this->lang, $this->dbSettings->getSettings(), $itemId);
                $deleted2 = $objItem->delete();

                if($deleted2)
                {
                    // Delete corresponding extras and all data related to them
                    $objExtrasObserver = new ExtrasObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
                    $objExtrasObserver->explicitDeleteByItemId($itemId);
                }
            }
        }

        $this->processDebugMessages($objFuelType->getDebugMessages());
        $this->processOkayMessages($objFuelType->getOkayMessages());
        $this->processErrorMessages($objFuelType->getErrorMessages());

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'manager&tab=fuel-types');
        exit;
    }

    private function processSave($paramFuelTypeId)
    {
        $objFuelType = new FuelType($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramFuelTypeId);
        $saved = $objFuelType->save();
        if($saved && $this->lang->canTranslateSQL())
        {
            $objFuelType->registerForTranslation();
        }

        $this->processDebugMessages($objFuelType->getDebugMessages());
        $this->processOkayMessages($objFuelType->getOkayMessages());
        $this->processErrorMessages($objFuelType->getErrorMessages());

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'manager&tab=fuel-types');
        exit;
    }

    public function getContent()
    {
        // Process actions
        if(isset($_GET['delete_fuel_type'])) { $this->processDelete($_GET['delete_fuel_type']); }
        if(isset($_POST['save_fuel_type'], $_POST['fuel_type_id'])) { $this->processSave($_POST['fuel_type_id']); }

        $paramFuelTypeId = isset($_GET['fuel_type_id']) ? $_GET['fuel_type_id'] : 0;
        $objFuelType = new FuelType($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramFuelTypeId);
        $localDetails = $objFuelType->getDetails();

        // Set the view variables
        $this->view->backToListURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'manager&tab=fuel-types');
        $this->view->formAction = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-fuel-type&noheader=true');
        if(!is_null($localDetails))
        {
            $this->view->fuelTypeId = $localDetails['fuel_type_id'];
            $this->view->fuelTypeTitle = $localDetails['edit_fuel_type_title'];
        } else
        {
            $this->view->fuelTypeId = 0;
            $this->view->fuelTypeTitle = '';
        }

        // Get the template
        $retContent = $this->getTemplate('Item', 'AddEditFuelType', 'Form');

        return $retContent;
    }
}
