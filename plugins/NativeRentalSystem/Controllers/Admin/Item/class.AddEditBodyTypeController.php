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
use NativeRentalSystem\Models\Item\BodyType;
use NativeRentalSystem\Models\Item\Item;
use NativeRentalSystem\Models\Item\ItemsObserver;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Controllers\Admin\AbstractController;

final class AddEditBodyTypeController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    private function processDelete($paramBodyTypeId)
    {
        $objBodyType = new BodyType($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramBodyTypeId);
        $deleted = $objBodyType->delete();

        if($deleted)
        {
            // Delete corresponding items
            $objItemsObserver = new ItemsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
            $itemIds = $objItemsObserver->getAllIds(-1, -1, $paramBodyTypeId);
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

        $this->processDebugMessages($objBodyType->getDebugMessages());
        $this->processOkayMessages($objBodyType->getOkayMessages());
        $this->processErrorMessages($objBodyType->getErrorMessages());

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'manager&tab=body-types');
        exit;
    }

    private function processSave($paramBodyTypeId)
    {
        $objBodyType = new BodyType($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramBodyTypeId);
        $saved = $objBodyType->save();
        if($saved && $this->lang->canTranslateSQL())
        {
            $objBodyType->registerForTranslation();
        }

        $this->processDebugMessages($objBodyType->getDebugMessages());
        $this->processOkayMessages($objBodyType->getOkayMessages());
        $this->processErrorMessages($objBodyType->getErrorMessages());

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'manager&tab=body-types');
        exit;
    }

    public function getContent()
    {
        // Process actions
        if(isset($_GET['delete_body_type'])) { $this->processDelete($_GET['delete_body_type']); }
        if(isset($_POST['save_body_type'], $_POST['body_type_id'])) { $this->processSave($_POST['body_type_id']); }

        $paramBodyTypeId = isset($_GET['body_type_id']) ? $_GET['body_type_id'] : 0;
        $objBodyType = new BodyType($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramBodyTypeId);
        $localDetails = $objBodyType->getDetails();

        // Set the view variables
        $this->view->backToListURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'manager&tab=body-types');
        $this->view->formAction = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-body-type&noheader=true');
        if(!is_null($localDetails))
        {
            $this->view->bodyTypeId = $localDetails['body_type_id'];
            $this->view->bodyTypeTitle = $localDetails['edit_body_type_title'];
            $this->view->bodyTypeOrder = $localDetails['body_type_order'];
        } else
        {
            $this->view->bodyTypeId = 0;
            $this->view->bodyTypeTitle = '';
            $this->view->bodyTypeOrder = '';
        }

        // Get the template
        $retContent = $this->getTemplate('Item', 'AddEditBodyType', 'Form');

        return $retContent;
    }
}
