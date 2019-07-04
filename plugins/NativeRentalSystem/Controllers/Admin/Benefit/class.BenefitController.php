<?php
/**
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Admin\Benefit;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Item\BodyTypesObserver;
use NativeRentalSystem\Models\Item\FeaturesObserver;
use NativeRentalSystem\Models\Item\FuelTypesObserver;
use NativeRentalSystem\Models\Benefit\BenefitsObserver;
use NativeRentalSystem\Models\Item\ManufacturersObserver;
use NativeRentalSystem\Models\Item\TransmissionTypesObserver;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Deposit\DepositsObserver;
use NativeRentalSystem\Models\Item\ItemsObserver;
use NativeRentalSystem\Controllers\Admin\AbstractController;
use NativeRentalSystem\Models\Option\ItemOptionsObserver;

final class BenefitController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    public function getContent()
    {
        // Create mandatory instances
        $objBenefitsObserver = new BenefitsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());

        // Get the tab values
        $tabs = $this->getTabParams(array('benefits'), 'benefits');

        // 1. Set the view variables - Tab settings
        $this->view->itemBenefitsTabChecked = !empty($tabs['benefits']) ? ' checked="checked"' : '';

        // 2. Set the view variables - other
        $this->view->addNewBenefitURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-benefit&benefit_id=0');
        $this->view->adminBenefitsList = $objBenefitsObserver->getAdminList();

        // Get the template
        $retContent = $this->getTemplate('Benefit', 'BenefitManager', 'Tabs');

        return $retContent;
    }
}
