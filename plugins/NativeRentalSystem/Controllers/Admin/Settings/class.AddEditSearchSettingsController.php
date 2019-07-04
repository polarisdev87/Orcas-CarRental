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

final class AddEditSearchSettingsController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    private function processSave()
    {
        $objSetting = new Setting($this->conf, $this->lang, 'conf_search_pickup_location_visible');
        $objSetting->saveCheckbox();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_search_pickup_location_required');
        $objSetting->saveCheckbox();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_search_pickup_date_visible');
        $objSetting->saveCheckbox();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_search_pickup_date_required');
        $objSetting->saveCheckbox();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_search_return_location_visible');
        $objSetting->saveCheckbox();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_search_return_location_required');
        $objSetting->saveCheckbox();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_search_return_date_visible');
        $objSetting->saveCheckbox();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_search_return_date_required');
        $objSetting->saveCheckbox();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_search_partner_visible');
        $objSetting->saveCheckbox();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_search_partner_required');
        $objSetting->saveCheckbox();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_search_manufacturer_visible');
        $objSetting->saveCheckbox();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_search_manufacturer_required');
        $objSetting->saveCheckbox();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_search_body_type_visible');
        $objSetting->saveCheckbox();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_search_body_type_required');
        $objSetting->saveCheckbox();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_search_transmission_type_visible');
        $objSetting->saveCheckbox();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_search_transmission_type_required');
        $objSetting->saveCheckbox();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_search_fuel_type_visible');
        $objSetting->saveCheckbox();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_search_fuel_type_required');
        $objSetting->saveCheckbox();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_search_booking_code_visible');
        $objSetting->saveCheckbox();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_search_booking_code_required');
        $objSetting->saveCheckbox();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_search_coupon_code_visible');
        $objSetting->saveCheckbox();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_search_coupon_code_required');
        $objSetting->saveCheckbox();

        $this->processOkayMessages(array($this->lang->getText('NRS_ADMIN_SETTINGS_OKAY_SEARCH_SETTINGS_UPDATED_TEXT')));

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'single-manager&tab=search-settings');
        exit;
    }

    public function getContent()
    {
        // First - process actions
        if(isset($_POST['update_search_settings']))  { $this->processSave(); }

        return '';
    }
}
