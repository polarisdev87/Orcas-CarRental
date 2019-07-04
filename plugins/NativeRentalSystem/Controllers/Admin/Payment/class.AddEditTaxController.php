<?php
/**
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Admin\Payment;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Location\LocationsObserver;
use NativeRentalSystem\Models\Tax\Tax;
use NativeRentalSystem\Controllers\Admin\AbstractController;

final class AddEditTaxController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    private function processDelete($paramTaxId)
    {
        $objTax = new Tax($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramTaxId);
        $objTax->delete();

        $this->processDebugMessages($objTax->getDebugMessages());
        $this->processOkayMessages($objTax->getOkayMessages());
        $this->processErrorMessages($objTax->getErrorMessages());

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'payment-manager&tab=taxes');
        exit;
    }

    private function processSave($paramTaxId)
    {
        $objTax = new Tax($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramTaxId);
        $saved = $objTax->save();
        if($saved && $this->lang->canTranslateSQL())
        {
            $objTax->registerForTranslation();
        }

        $this->processDebugMessages($objTax->getDebugMessages());
        $this->processOkayMessages($objTax->getOkayMessages());
        $this->processErrorMessages($objTax->getErrorMessages());

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'payment-manager&tab=taxes');
        exit;
    }

    public function getContent()
    {
        // Create mandatory instances
        $objLocationsObserver = new LocationsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());

        if(isset($_GET['delete_tax'])) { $this->processDelete($_GET['delete_tax']); }
        if(isset($_POST['save_tax'], $_POST['tax_id'])) { $this->processSave($_POST['tax_id']); }

        $paramTaxId = isset($_GET['tax_id']) ? $_GET['tax_id'] : 0;
        $objTax = new Tax($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramTaxId);
        $localDetails = $objTax->getDetails();

        // Set the view variables
        $this->view->backToListURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'payment-manager&tab=taxes');
        $this->view->formAction = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-tax&noheader=true');
        if(!is_null($localDetails))
        {
            $this->view->taxId = $localDetails['tax_id'];
            $this->view->taxName = $localDetails['edit_tax_name'];
            $this->view->locationsDropDownOptions = $objLocationsObserver->getTranslatedLocationsDropDownOptions(
                "BOTH", 0, $localDetails['location_id'], 0, $this->lang->getText('NRS_ADMIN_SELECT_LOCATION_TEXT')
            );
            $this->view->pickupTypeChecked = $localDetails['location_type'] == 1 ? ' checked="checked"' : '';
            $this->view->returnTypeChecked = $localDetails['location_type'] == 2 ? ' checked="checked"' : '';
            $this->view->taxPercentage = $localDetails['tax_percentage'];
        } else
        {
            $this->view->taxId = 0;
            $this->view->taxName = '';
            $this->view->locationsDropDownOptions = $objLocationsObserver->getTranslatedLocationsDropDownOptions(
                "BOTH", 0, 0, 0, $this->lang->getText('NRS_ADMIN_SELECT_LOCATION_TEXT')
            );
            $this->view->pickupTypeChecked = ' checked="checked"';
            $this->view->returnTypeChecked = '';
            $this->view->taxPercentage = $localDetails['tax_percentage'];
        }

        // Get the template
        $retContent = $this->getTemplate('Payment', 'AddEditTax', 'Form');

        return $retContent;
    }
}
