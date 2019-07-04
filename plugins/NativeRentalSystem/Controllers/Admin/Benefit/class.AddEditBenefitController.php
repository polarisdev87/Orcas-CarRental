<?php
/**
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Admin\Benefit;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Benefit\Benefit;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Controllers\Admin\AbstractController;

final class AddEditBenefitController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    private function processDelete($paramBenefitId)
    {
        $objBenefit = new Benefit($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramBenefitId);
        $objBenefit->delete();

        $this->processDebugMessages($objBenefit->getDebugMessages());
        $this->processOkayMessages($objBenefit->getOkayMessages());
        $this->processErrorMessages($objBenefit->getErrorMessages());

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'benefit-manager&tab=benefits');
        exit;
    }

    private function processSave($paramBenefitId)
    {
        $objBenefit = new Benefit($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramBenefitId);
        $saved = $objBenefit->save();
        if($saved && $this->lang->canTranslateSQL())
        {
            $objBenefit->registerForTranslation();
        }

        $this->processDebugMessages($objBenefit->getDebugMessages());
        $this->processOkayMessages($objBenefit->getOkayMessages());
        $this->processErrorMessages($objBenefit->getErrorMessages());

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'benefit-manager&tab=benefits');
        exit;
    }

    public function getContent()
    {
        // Process actions
        if(isset($_GET['delete_benefit'])) { $this->processDelete($_GET['delete_benefit']); }
        if(isset($_POST['save_benefit'], $_POST['benefit_id'])) { $this->processSave($_POST['benefit_id']); }

        $paramBenefitId = isset($_GET['benefit_id']) ? $_GET['benefit_id'] : 0;
        $objBenefit = new Benefit($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramBenefitId);
        $localDetails = $objBenefit->getDetails();

        // Set the view variables
        $this->view->backToListURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'benefit-manager&tab=benefits');
        $this->view->formAction = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-benefit&noheader=true');
        if(!is_null($localDetails))
        {
            $this->view->benefitId = $localDetails['benefit_id'];
            $this->view->benefitTitle = $localDetails['edit_benefit_title'];
            $this->view->benefitImage = $localDetails['benefit_image'];
            $this->view->demoBenefitImage = $localDetails['demo_benefit_image'];
            $this->view->benefitOrder = $localDetails['benefit_order'];
        } else
        {
            $this->view->benefitId = 0;
            $this->view->benefitTitle = '';
            $this->view->benefitImage = '';
            $this->view->demoBenefitImage = 0;
            $this->view->benefitOrder = '';
        }

        // Get the template
        $retContent = $this->getTemplate('Benefit', 'AddEditBenefit', 'Form');

        return $retContent;
    }
}
