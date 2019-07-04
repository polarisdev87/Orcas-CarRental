<?php
/**
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Admin\Settings;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Post\ItemType;
use NativeRentalSystem\Models\Post\LocationType;
use NativeRentalSystem\Models\Post\PageType;
use NativeRentalSystem\Models\Settings\Setting;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Controllers\Admin\AbstractController;

final class AddEditGlobalSettingsController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    private function processSave()
    {
        $objSetting = new Setting($this->conf, $this->lang, 'conf_company_name');
        $objSetting->saveText();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_company_street_address');
        $objSetting->saveText();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_company_city');
        $objSetting->saveText();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_company_state');
        $objSetting->saveText();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_company_country');
        $objSetting->saveText();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_company_zip_code');
        $objSetting->saveText();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_company_phone');
        $objSetting->saveText();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_company_email');
        $objSetting->saveEmail();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_send_emails');
        $objSetting->saveNumber(0, array(0, 1));

        $objSetting = new Setting($this->conf, $this->lang, 'conf_company_notification_emails');
        $objSetting->saveNumber(0, array(0, 1));

        $objSetting = new Setting($this->conf, $this->lang, 'conf_universal_analytics_events_tracking');
        $objSetting->saveNumber(0, array(0, 1));

        $objSetting = new Setting($this->conf, $this->lang, 'conf_universal_analytics_enhanced_ecommerce');
        $objSetting->saveNumber(0, array(0, 1));

        $objSetting = new Setting($this->conf, $this->lang, 'conf_recaptcha_site_key');
        $objSetting->saveText();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_recaptcha_secret_key');
        $objSetting->saveText();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_recaptcha_enabled');
        $objSetting->saveNumber(0, array(0, 1));

        $objSetting = new Setting($this->conf, $this->lang, 'conf_api_max_requests_per_period');
        $objSetting->saveNumber(0);

        $objSetting = new Setting($this->conf, $this->lang, 'conf_api_max_failed_requests_per_period');
        $objSetting->saveNumber(0);

        $objSetting = new Setting($this->conf, $this->lang, 'conf_cancelled_payment_page_id');
        $objSetting->saveNumber(0);

        $objSetting = new Setting($this->conf, $this->lang, 'conf_confirmation_page_id');
        $objSetting->saveNumber(0);

        $objSetting = new Setting($this->conf, $this->lang, 'conf_terms_and_conditions_page_id');
        $objSetting->saveNumber(0);

        $objSetting = new Setting($this->conf, $this->lang, 'conf_system_style');
        $objSetting->saveText();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_short_date_format');
        $objSetting->saveText();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_distance_measurement_unit');
        $objSetting->saveText();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_minimum_booking_period');
        $objSetting->saveNumber(0);

        $objSetting = new Setting($this->conf, $this->lang, 'conf_maximum_booking_period');
        $objSetting->saveNumber(0);

        $objSetting = new Setting($this->conf, $this->lang, 'conf_search_enabled');
        $objSetting->saveNumber(0, array(0, 1));

        $objSetting = new Setting($this->conf, $this->lang, 'conf_minimum_block_period_between_bookings');
        $objSetting->saveNumber(0);

        $objSetting = new Setting($this->conf, $this->lang, 'conf_minimum_period_until_pickup');
        $objSetting->saveNumber(0);

        $objSetting = new Setting($this->conf, $this->lang, 'conf_noon_time');
        $objSetting->saveTime();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_page_url_slug');
        $objSetting->saveKey();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_item_url_slug');
        $objSetting->saveKey();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_location_url_slug');
        $objSetting->saveKey();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_reveal_partner');
        $objSetting->saveNumber(1, array(0, 1));

        $objSetting = new Setting($this->conf, $this->lang, 'conf_booking_model');
        $objSetting->saveNumber(1, array(1, 2));

        $objSetting = new Setting($this->conf, $this->lang, 'conf_classify_items');
        $objSetting->saveNumber(0, array(0, 1));

        $objSetting = new Setting($this->conf, $this->lang, 'conf_load_datepicker_from_plugin');
        $objSetting->saveNumber(0, array(0, 1));

        $objSetting = new Setting($this->conf, $this->lang, 'conf_load_fancybox_from_plugin');
        $objSetting->saveNumber(0, array(0, 1));

        $objSetting = new Setting($this->conf, $this->lang, 'conf_load_font_awesome_from_plugin');
        $objSetting->saveNumber(0, array(0, 1));

        $objSetting = new Setting($this->conf, $this->lang, 'conf_load_slick_slider_from_plugin');
        $objSetting->saveNumber(0, array(0, 1));

        $this->processOkayMessages(array($this->lang->getText('NRS_ADMIN_SETTINGS_OKAY_GLOBAL_SETTINGS_UPDATED_TEXT')));

        // Note: Initialize line bellow for every extension!
        if(isset($_POST['conf_page_url_slug']))
        {
            $objPostType = new PageType($this->conf, $this->lang, $this->conf->getExtensionPrefix().'page');
            $objPostType->register($_POST['conf_page_url_slug'], 95);
        }
        if(isset($_POST['conf_item_url_slug']))
        {
            $objPostType = new ItemType($this->conf, $this->lang, $this->conf->getExtensionPrefix().'item');
            $objPostType->register($_POST['conf_item_url_slug'], 96);
        }
        if(isset($_POST['conf_location_url_slug']))
        {
            $objPostType = new LocationType($this->conf, $this->lang, $this->conf->getExtensionPrefix().'location');
            $objPostType->register($_POST['conf_location_url_slug'], 97);
        }

        // We need this due the fact that we might have changed a path for items, locations or pages in global settings
        flush_rewrite_rules();

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'single-manager&tab=global-settings');
        exit;
    }

    public function getContent()
    {
        // First - process actions
        if(isset($_POST['update_global_settings'])) { $this->processSave(); }

        return '';
    }
}
