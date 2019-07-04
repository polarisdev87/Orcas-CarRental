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
use NativeRentalSystem\Models\Item\Item;
use NativeRentalSystem\Models\Item\ItemsObserver;
use NativeRentalSystem\Models\Item\Manufacturer;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Controllers\Admin\AbstractController;

final class AddEditManufacturerController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    private function processDelete($paramManufacturerId)
    {
        $objManufacturer = new Manufacturer($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramManufacturerId);
        $deleted = $objManufacturer->delete();

        if($deleted)
        {
            // Delete corresponding items
            $objItemsObserver = new ItemsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
            $itemIds = $objItemsObserver->getAllIds(-1, $paramManufacturerId);
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

        $this->processDebugMessages($objManufacturer->getDebugMessages());
        $this->processOkayMessages($objManufacturer->getOkayMessages());
        $this->processErrorMessages($objManufacturer->getErrorMessages());

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'manager&tab=manufacturers');
        exit;
    }

    private function processSave($paramManufacturerId)
    {
        $objManufacturer = new Manufacturer($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramManufacturerId);
        $saved = $objManufacturer->save();
        if($saved && $this->lang->canTranslateSQL())
        {
            $objManufacturer->registerForTranslation();
        }

        $this->processDebugMessages($objManufacturer->getDebugMessages());
        $this->processOkayMessages($objManufacturer->getOkayMessages());
        $this->processErrorMessages($objManufacturer->getErrorMessages());

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'manager&tab=manufacturers');
        exit;
    }

    public function getContent()
    {
        // Process actions
        if(isset($_GET['delete_manufacturer'])) { $this->processDelete($_GET['delete_manufacturer']); }
        if(isset($_POST['save_manufacturer'], $_POST['manufacturer_id'])) { $this->processSave($_POST['manufacturer_id']); }

        $paramManufacturerId = isset($_GET['manufacturer_id']) ? $_GET['manufacturer_id'] : 0;
        $objManufacturer = new Manufacturer($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramManufacturerId);
        $localDetails = $objManufacturer->getDetails();

        // Set the view variables
        $this->view->backToListURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'manager&tab=manufacturers');
        $this->view->formAction = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-manufacturer&noheader=true');
        if(!is_null($localDetails))
        {
            $this->view->manufacturerId = $localDetails['manufacturer_id'];
            $this->view->manufacturerTitle = $localDetails['edit_manufacturer_title'];
            $this->view->manufacturerLogo = $localDetails['manufacturer_logo'];
            $this->view->demoManufacturerLogo = $localDetails['demo_manufacturer_logo'];
        } else
        {
            $this->view->manufacturerId = 0;
            $this->view->manufacturerTitle = '';
            $this->view->manufacturerLogo = '';
            $this->view->demoManufacturerLogo = 0;
        }

        // Get the template
        $retContent = $this->getTemplate('Item', 'AddEditManufacturer', 'Form');

        return $retContent;
    }
}
