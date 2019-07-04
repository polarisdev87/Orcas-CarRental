<?php
/**
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Admin\Payment;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Logging\LogsObserver;
use NativeRentalSystem\Models\PaymentMethod\PaymentMethodsObserver;
use NativeRentalSystem\Models\Prepayment\PrepaymentsObserver;
use NativeRentalSystem\Models\Tax\TaxesObserver;
use NativeRentalSystem\Controllers\Admin\AbstractController;

final class PaymentController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    public function getContent()
    {
        // Create mandatory instances
        $objPrepaymentsObserver = new PrepaymentsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objTaxesObserver = new TaxesObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objPaymentMethodsObserver = new PaymentMethodsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objLogsObserver = new LogsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());

        // Get the tab values
        $defaultTab = $objPrepaymentsObserver->arePrepaymentsEnabled() ? 'prepayments' : 'taxes';
        $tabs = $this->getTabParams(
            array('prepayments', 'taxes', 'payment-methods', 'api-log'),
            $defaultTab
        );

        // 1. Set the view variables - Tab settings
        $this->view->prepaymentsTabChecked = !empty($tabs['prepayments']) ? ' checked="checked"' : '';
        $this->view->taxesTabChecked = !empty($tabs['taxes']) ? ' checked="checked"' : '';
        $this->view->paymentMethodsTabChecked = !empty($tabs['payment-methods']) ? ' checked="checked"' : '';
        $this->view->apiLogTabChecked = !empty($tabs['api-log']) ? ' checked="checked"' : '';

        // Set the view variables - Prepayments tab
        $this->view->addNewPrepaymentURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-prepayment&prepayment_id=0');
        $this->view->prepaymentsEnabled = $objPrepaymentsObserver->arePrepaymentsEnabled();
        $this->view->adminPrepaymentsList = $objPrepaymentsObserver->getAdminList();

        // Set the view variables - Taxes tab
        $this->view->addNewTaxURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-tax&tax_id=0');
        $this->view->adminTaxesList = $objTaxesObserver->getAdminList();

        // Set the view variables - Payment methods tab
        $this->view->addNewPaymentMethodURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-payment-method&payment_method_id=0');
        $this->view->adminPaymentMethodsList = $objPaymentMethodsObserver->getAdminList();

        // Set the view variables - Payments log tab
        $this->view->logList = $objLogsObserver->getAdminListForPayments();

        // Get the template
        $retContent = $this->getTemplate('Payment', 'PaymentManager', 'Tabs');

        return $retContent;
    }
}
