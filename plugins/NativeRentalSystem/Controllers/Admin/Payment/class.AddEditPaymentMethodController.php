<?php
/**
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Admin\Payment;
use NativeRentalSystem\Models\Booking\BookingsObserver;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\PaymentMethod\PaymentMethod;
use NativeRentalSystem\Controllers\Admin\AbstractController;
use NativeRentalSystem\Models\PaymentMethod\PaymentMethodsObserver;

final class AddEditPaymentMethodController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    private function processDelete($paramPaymentMethodId)
    {
        $objPaymentMethod = new PaymentMethod($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramPaymentMethodId);
        $objPaymentMethod->delete();

        $this->processDebugMessages($objPaymentMethod->getDebugMessages());
        $this->processOkayMessages($objPaymentMethod->getOkayMessages());
        $this->processErrorMessages($objPaymentMethod->getErrorMessages());

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'payment-manager&tab=payment-methods');
        exit;
    }

    private function processSave($paramPaymentMethodId)
    {
        $objBookingsObserver = new BookingsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objPaymentMethod = new PaymentMethod($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramPaymentMethodId);
        $oldCode = $objPaymentMethod->getCode();
        $saved = $objPaymentMethod->save();
        $newCode = $objPaymentMethod->getCode();
        if($paramPaymentMethodId > 0 && $saved && $oldCode != '' && $newCode != $oldCode)
        {
            $objBookingsObserver->changePaymentMethodCode($oldCode, $newCode);
        }
        if($saved && $this->lang->canTranslateSQL())
        {
            $objPaymentMethod->registerForTranslation();
        }

        $this->processDebugMessages($objPaymentMethod->getDebugMessages());
        $this->processOkayMessages($objPaymentMethod->getOkayMessages());
        $this->processErrorMessages($objPaymentMethod->getErrorMessages());

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'payment-manager&tab=payment-methods');
        exit;
    }

    public function getContent()
    {
        // Create mandatory instances
        $objPaymentMethodsObserver = new PaymentMethodsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());

        // Process action only if prepayments are enabled
        if($this->dbSettings->getSetting('conf_prepayment_enabled') == 1)
        {
            if(isset($_POST['save_payment_method'], $_POST['payment_method_id'])) { $this->processSave($_POST['payment_method_id']); }
            if(isset($_GET['delete_payment_method'])) { $this->processDelete($_GET['delete_payment_method']); }
        }

        $paramPaymentMethodId = isset($_GET['payment_method_id']) ? $_GET['payment_method_id'] : "";
        $objPaymentMethod = new PaymentMethod($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramPaymentMethodId);
        $localDetails = $objPaymentMethod->getDetails();

        // Set the view variables
        $this->view->backToListURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'payment-manager&tab=payment-methods');
        $this->view->formAction = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-payment-method&noheader=true');
        if(!is_null($localDetails))
        {
            $this->view->paymentMethodId = $localDetails['payment_method_id'];
            $this->view->paymentMethodCode = $localDetails['edit_payment_method_code'];
            $this->view->paymentMethodClassesDropDownOptions = $objPaymentMethodsObserver->getClassesDropDownOptions(
                $localDetails['class_name'], "", ""
            );
            $this->view->paymentMethodName = $localDetails['edit_payment_method_name'];
            $this->view->paymentMethodEmail = $localDetails['edit_payment_method_email'];
            $this->view->paymentMethodDescription = $localDetails['edit_payment_method_description'];
            $this->view->publicKey = $localDetails['edit_public_key'];
            $this->view->privateKey = $localDetails['edit_private_key'];
            $this->view->sandboxModeChecked = $localDetails['sandbox_mode'] == 1 ? ' checked="checked"' : '';
            $this->view->checkCertificateChecked = $localDetails['check_certificate'] == 1 ? ' checked="checked"' : '';
            $this->view->sslOnlyChecked = $localDetails['ssl_only'] == 1 ? ' checked="checked"' : '';
            $this->view->onlinePaymentChecked = $localDetails['online_payment'] == 1 ? ' checked="checked"' : '';
            $this->view->paymentMethodEnabledChecked = $localDetails['payment_method_enabled'] == 1 ? ' checked="checked"' : '';
            $this->view->expirationTimeDropDown = $this->dbSettings->getExpirationTimeDropDownOptions(
                $localDetails['expiration_time'], 0, 7776000
            );
            $this->view->paymentMethodOrder = $localDetails['payment_method_order'];
        } else
        {
            $this->view->paymentMethodId = 0;
            $this->view->paymentMethodCode = $objPaymentMethod->generateCode();
            $this->view->paymentMethodClassesDropDownOptions = $objPaymentMethodsObserver->getClassesDropDownOptions("", "", "");
            $this->view->paymentMethodName = '';
            $this->view->paymentMethodEmail = '';
            $this->view->paymentMethodDescription = '';
            $this->view->publicKey = '';
            $this->view->privateKey = '';
            $this->view->sandboxModeChecked = '';
            $this->view->checkCertificateChecked = '';
            $this->view->sslOnlyChecked = '';
            $this->view->onlinePaymentChecked = '';
            $this->view->paymentMethodEnabledChecked = '';
            $this->view->expirationTimeDropDown = $this->dbSettings->getExpirationTimeDropDownOptions(0, 0, 7776000);
            $this->view->paymentMethodOrder = '';
        }

        // Get the template
        $retContent = $this->getTemplate('Payment', 'AddEditPaymentMethod', 'Form');

        return $retContent;
    }
}
