<?php
/**
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Admin\Extras;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Extra\Extra;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Extra\ExtrasObserver;
use NativeRentalSystem\Models\Option\ExtraOption;
use NativeRentalSystem\Controllers\Admin\AbstractController;

final class AddEditOptionController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    private function processDelete($paramOptionId)
    {
        $objOption = new ExtraOption($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramOptionId);
        $objOption->delete();

        $this->processDebugMessages($objOption->getDebugMessages());
        $this->processOkayMessages($objOption->getOkayMessages());
        $this->processErrorMessages($objOption->getErrorMessages());

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'extras-manager&tab=options');
        exit;
    }

    private function processSave($paramOptionId)
    {
        $objOption = new ExtraOption($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramOptionId);
        $saved = $objOption->save();
        if($saved && $this->lang->canTranslateSQL())
        {
            $objOption->registerForTranslation();
        }

        $this->processDebugMessages($objOption->getDebugMessages());
        $this->processOkayMessages($objOption->getOkayMessages());
        $this->processErrorMessages($objOption->getErrorMessages());

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'extras-manager&tab=options');
        exit;
    }

    public function getContent()
    {
        // Create mandatory instances
        $objExtrasObserver = new ExtrasObserver($this->conf, $this->lang, $this->dbSettings->getSettings());

        if(isset($_GET['delete_option'])) { $this->processDelete($_GET['delete_option']); }
        if(isset($_POST['save_option'], $_POST['option_id'])) { $this->processSave($_POST['option_id']); }

        $paramOptionId = isset($_GET['option_id']) ? $_GET['option_id'] : 0;
        $objOption = new ExtraOption($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramOptionId);
        $objExtra = new Extra($this->conf, $this->lang, $this->dbSettings->getSettings(), $objOption->getExtraId());
        $localDetails = $objOption->getDetails();

        // Set the view variables
        $this->view->backToListURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'extras-manager&tab=options');
        $this->view->formAction = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-extra-option&noheader=true');
        if(!is_null($localDetails) && $objOption->canEdit($objExtra->getPartnerId()))
        {
            $this->view->optionId = $localDetails['option_id'];
            $this->view->optionName = $localDetails['edit_option_name'];
            if($objExtrasObserver->canShowOnlyPartnerOwned())
            {
                $this->view->extrasDropDownOptions = $objExtrasObserver->getTranslatedExtrasDropDownOptionsByPartnerId(
                    get_current_user_id(), $localDetails['extra_id'], "", ""
                );
            } else
            {
                $this->view->extrasDropDownOptions = $objExtrasObserver->getTranslatedExtrasDropDownOptions(
                    $localDetails['extra_id'], "", ""
                );
            }
        } else
        {
            $this->view->optionId = 0;
            $this->view->optionName = '';
            if($objExtrasObserver->canShowOnlyPartnerOwned())
            {
                $this->view->extrasDropDownOptions = $objExtrasObserver->getTranslatedExtrasDropDownOptionsByPartnerId(
                    get_current_user_id(), 0, "", ""
                );
            } else
            {
                $this->view->extrasDropDownOptions = $objExtrasObserver->getTranslatedExtrasDropDownOptions(
                    0, "", ""
                );
            }
        }

        // Get the template
        $retContent = $this->getTemplate('Extras', 'AddEditOption', 'Form');

        return $retContent;
    }
}
