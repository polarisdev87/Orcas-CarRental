<?php
/**
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Admin\Item;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Item\Item;
use NativeRentalSystem\Models\Item\TransmissionType;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Item\ItemsObserver;
use NativeRentalSystem\Controllers\Admin\AbstractController;

final class AddEditTransmissionTypeController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    private function processDelete($paramTransmissionTypeId)
    {
        $objTransmissionType = new TransmissionType($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramTransmissionTypeId);
        $deleted = $objTransmissionType->delete();

        if($deleted)
        {
            // Delete corresponding items
            $objItemsObserver = new ItemsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
            $itemIds = $objItemsObserver->getAllIds(-1, -1, -1, $paramTransmissionTypeId);
            foreach ($itemIds AS $itemId)
            {
                $objItem = new Item($this->conf, $this->lang, $this->dbSettings->getSettings(), $itemId);
                $objItem->delete();
            }
        }

        $this->processDebugMessages($objTransmissionType->getDebugMessages());
        $this->processOkayMessages($objTransmissionType->getOkayMessages());
        $this->processErrorMessages($objTransmissionType->getErrorMessages());

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'manager&tab=transmission-types');
        exit;
    }

    private function processSave($paramTransmissionTypeId)
    {
        $objTransmissionType = new TransmissionType($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramTransmissionTypeId);
        $saved = $objTransmissionType->save();
        if($saved && $this->lang->canTranslateSQL())
        {
            $objTransmissionType->registerForTranslation();
        }

        $this->processDebugMessages($objTransmissionType->getDebugMessages());
        $this->processOkayMessages($objTransmissionType->getOkayMessages());
        $this->processErrorMessages($objTransmissionType->getErrorMessages());

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'manager&tab=transmission-types');
        exit;
    }

    public function getContent()
    {
        // Process actions
        if(isset($_GET['delete_transmission_type'])) { $this->processDelete($_GET['delete_transmission_type']); }
        if(isset($_POST['save_transmission_type'], $_POST['transmission_type_id'])) { $this->processSave($_POST['transmission_type_id']); }

        $paramTransmissionTypeId = isset($_GET['transmission_type_id']) ? $_GET['transmission_type_id'] : 0;
        $objItemsObserver = new TransmissionType($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramTransmissionTypeId);
        $localDetails = $objItemsObserver->getDetails();

        // Set the view variables
        $this->view->backToListURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'manager&tab=transmissions');
        $this->view->formAction = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-transmission-type&noheader=true');
        if(!is_null($localDetails))
        {
            $this->view->transmissionTypeId = $localDetails['transmission_type_id'];
            $this->view->transmissionTypeTitle = $localDetails['edit_transmission_type_title'];
        } else
        {
            $this->view->transmissionTypeId = 0;
            $this->view->transmissionTypeTitle = '';
        }

        // Get the template
        $retContent = $this->getTemplate('Item', 'AddEditTransmissionType', 'Form');

        return $retContent;
    }
}
