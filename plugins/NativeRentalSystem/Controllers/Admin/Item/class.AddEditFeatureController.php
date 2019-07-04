<?php
/**
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Admin\Item;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Item\Feature;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Controllers\Admin\AbstractController;

final class AddEditFeatureController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    private function processDelete($paramFeatureId)
    {
        $objFeature = new Feature($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramFeatureId);
        $objFeature->delete();

        $this->processDebugMessages($objFeature->getDebugMessages());
        $this->processOkayMessages($objFeature->getOkayMessages());
        $this->processErrorMessages($objFeature->getErrorMessages());

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'manager&tab=features');
        exit;
    }

    private function processSave($paramFeatureId)
    {
        $objFeature = new Feature($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramFeatureId);
        $saved = $objFeature->save();
        if($saved && $this->lang->canTranslateSQL())
        {
            $objFeature->registerForTranslation();
        }

        $this->processDebugMessages($objFeature->getDebugMessages());
        $this->processOkayMessages($objFeature->getOkayMessages());
        $this->processErrorMessages($objFeature->getErrorMessages());

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'manager&tab=features');
        exit;
    }

    public function getContent()
    {
        // Process actions
        if(isset($_GET['delete_feature'])) { $this->processDelete($_GET['delete_feature']); }
        if(isset($_POST['save_feature'], $_POST['feature_id'])) { $this->processSave($_POST['feature_id']); }

        $paramFeatureId = isset($_GET['feature_id']) ? $_GET['feature_id'] : 0;
        $objFeature = new Feature($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramFeatureId);
        $localDetails = $objFeature->getDetails();

        // Set the view variables
        $this->view->backToListURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'manager&tab=features');
        $this->view->formAction = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-feature&noheader=true');
        if(!is_null($localDetails))
        {
            $this->view->featureId = $localDetails['feature_id'];
            $this->view->featureTitle = $localDetails['edit_feature_title'];
            $this->view->displayInItemListChecked = $localDetails['display_in_item_list'] == 1 ? ' checked="checked"' : '';
            $this->view->addToAllItemsChecked = '';
        } else
        {
            $this->view->featureId = 0;
            $this->view->featureTitle = '';
            $this->view->displayInItemListChecked = '';
            $this->view->addToAllItemsChecked = '';
        }

        // Get the template
        $retContent = $this->getTemplate('Item', 'AddEditFeature', 'Form');

        return $retContent;
    }
}
