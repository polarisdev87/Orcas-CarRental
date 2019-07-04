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
use NativeRentalSystem\Models\Prepayment\Prepayment;
use NativeRentalSystem\Controllers\Admin\AbstractController;

final class AddEditPrepaymentController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    private function processDelete($paramPrepaymentId)
    {
        $objPrepayment = new Prepayment($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramPrepaymentId);
        $objPrepayment->delete();

        $this->processDebugMessages($objPrepayment->getDebugMessages());
        $this->processOkayMessages($objPrepayment->getOkayMessages());
        $this->processErrorMessages($objPrepayment->getErrorMessages());

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'payment-manager&tab=prepayments');
        exit;
    }

    private function processSave($paramPrepaymentId)
    {
        $objPrepayment = new Prepayment($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramPrepaymentId);
        $objPrepayment->save();

        $this->processDebugMessages($objPrepayment->getDebugMessages());
        $this->processOkayMessages($objPrepayment->getOkayMessages());
        $this->processErrorMessages($objPrepayment->getErrorMessages());

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'payment-manager&tab=prepayments');
        exit;
    }

    public function getContent()
    {
        if(isset($_GET['delete_prepayment'])) { $this->processDelete($_GET['delete_prepayment']); }
        if(isset($_POST['save_prepayment'], $_POST['prepayment_id'])) { $this->processSave($_POST['prepayment_id']); }

        $paramPrepaymentId = isset($_GET['prepayment_id']) ? $_GET['prepayment_id'] : 0;
        $objPrepayment = new Prepayment($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramPrepaymentId);
        $localPrepaymentData = $objPrepayment->getDetails();

        // Set the view variables
        $this->view->backToListURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'prices&tab=prepayments');
        $this->view->formAction = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-prepayment&noheader=true');
        if(!is_null($localPrepaymentData))
        {
            $this->view->prepaymentId = $localPrepaymentData['prepayment_id'];
            $this->view->durationFromDays = $this->dbSettings->getAdminDaysByPriceTypeFromPeriod($localPrepaymentData['period_from']);
            $this->view->durationFromHours = $this->dbSettings->getAdminHoursByPriceTypeFromPeriod($localPrepaymentData['period_from']);
            $this->view->durationTillDays = $this->dbSettings->getAdminDaysByPriceTypeFromPeriod($localPrepaymentData['period_till']);
            $this->view->durationTillHours = $this->dbSettings->getAdminHoursByPriceTypeFromPeriod($localPrepaymentData['period_till']);
            $this->view->itemPricesIncludedChecked = $localPrepaymentData['item_prices_included'] == 1 ? ' checked="checked"' : '';
            $this->view->itemDepositsIncludedChecked = $localPrepaymentData['item_deposits_included'] == 1 ? ' checked="checked"' : '';
            $this->view->extraPricesIncludedChecked = $localPrepaymentData['extra_prices_included'] == 1 ? ' checked="checked"' : '';
            $this->view->extraDepositsIncludedChecked = $localPrepaymentData['extra_deposits_included'] == 1 ? ' checked="checked"' : '';
            $this->view->pickupFeesIncludedChecked = $localPrepaymentData['pickup_fees_included'] == 1 ? ' checked="checked"' : '';
            $this->view->distanceFeesIncludedChecked = $localPrepaymentData['distance_fees_included'] == 1 ? ' checked="checked"' : '';
            $this->view->returnFeesIncludedChecked = $localPrepaymentData['return_fees_included'] == 1 ? ' checked="checked"' : '';
            $this->view->prepaymentPercentage = $localPrepaymentData['prepayment_percentage'];
        } else
        {
            $this->view->prepaymentId = 0;
            $this->view->durationFromDays = "";
            $this->view->durationFromHours = "";
            $this->view->durationTillDays = "";
            $this->view->durationTillHours = "";
            $this->view->itemPricesIncludedChecked = ' checked="checked"';
            $this->view->itemDepositsIncludedChecked = '';
            $this->view->extraPricesIncludedChecked = ' checked="checked"';
            $this->view->extraDepositsIncludedChecked = '';
            $this->view->pickupFeesIncludedChecked = ' checked="checked"';
            $this->view->distanceFeesIncludedChecked = ' checked="checked"';
            $this->view->returnFeesIncludedChecked = ' checked="checked"';
            $this->view->prepaymentPercentage = "";
        }

        // Get the template
        $retContent = $this->getTemplate('Payment', 'AddEditPrepayment', 'Form');

        return $retContent;
    }
}
