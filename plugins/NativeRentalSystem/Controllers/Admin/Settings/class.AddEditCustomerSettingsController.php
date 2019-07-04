<?php
/**
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Admin\Settings;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Settings\Setting;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Controllers\Admin\AbstractController;

final class AddEditCustomerSettingsController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    private function processSave()
    {
        $objSetting = new Setting($this->conf, $this->lang, 'conf_customer_title_visible');
        $objSetting->saveCheckbox();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_customer_title_required');
        $objSetting->saveCheckbox();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_customer_first_name_visible');
        $objSetting->saveCheckbox();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_customer_first_name_required');
        $objSetting->saveCheckbox();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_customer_last_name_visible');
        $objSetting->saveCheckbox();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_customer_last_name_required');
        $objSetting->saveCheckbox();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_customer_birthdate_visible');
        $objSetting->saveCheckbox();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_customer_birthdate_required');
        $objSetting->saveCheckbox();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_customer_street_address_visible');
        $objSetting->saveCheckbox();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_customer_street_address_required');
        $objSetting->saveCheckbox();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_customer_city_visible');
        $objSetting->saveCheckbox();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_customer_city_required');
        $objSetting->saveCheckbox();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_customer_state_visible');
        $objSetting->saveCheckbox();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_customer_state_required');
        $objSetting->saveCheckbox();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_customer_zip_code_visible');
        $objSetting->saveCheckbox();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_customer_zip_code_required');
        $objSetting->saveCheckbox();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_customer_country_visible');
        $objSetting->saveCheckbox();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_customer_country_required');
        $objSetting->saveCheckbox();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_customer_phone_visible');
        $objSetting->saveCheckbox();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_customer_phone_required');
        $objSetting->saveCheckbox();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_customer_email_visible');
        $objSetting->saveCheckbox();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_customer_email_required');
        $objSetting->saveCheckbox();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_customer_comments_visible');
        $objSetting->saveCheckbox();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_customer_comments_required');
        $objSetting->saveCheckbox();

        $this->processOkayMessages(array($this->lang->getText('NRS_ADMIN_SETTINGS_OKAY_CUSTOMER_SETTINGS_UPDATED_TEXT')));

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'single-manager&tab=customer-settings');
        exit;
    }

    public function getContent()
    {
        // First - process actions
        if(isset($_POST['update_customer_settings'])) { $this->processSave(); }

        return '';
    }
}
