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

final class AddEditPriceSettingsController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    private function processSave()
    {
        $objSetting = new Setting($this->conf, $this->lang, 'conf_price_calculation_type');
        $objSetting->saveNumber(1, array(1, 2, 3));

        $objSetting = new Setting($this->conf, $this->lang, 'conf_currency_symbol');
        $objSetting->saveText(TRUE);

        $objSetting = new Setting($this->conf, $this->lang, 'conf_currency_symbol_location');
        $objSetting->saveNumber(0, array(0, 1));

        $objSetting = new Setting($this->conf, $this->lang, 'conf_currency_code');
        $objSetting->saveText();

        $objSetting = new Setting($this->conf, $this->lang, 'conf_show_price_with_taxes');
        $objSetting->saveNumber(0, array(0, 1));

        $objSetting = new Setting($this->conf, $this->lang, 'conf_deposit_enabled');
        $objSetting->saveNumber(0, array(0, 1));

        $objSetting = new Setting($this->conf, $this->lang, 'conf_prepayment_enabled');
        $objSetting->saveNumber(0, array(0, 1));

        $this->processOkayMessages(array($this->lang->getText('NRS_ADMIN_SETTINGS_OKAY_PRICE_SETTINGS_UPDATED_TEXT')));

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'single-manager&tab=price-settings');
        exit;
    }

    public function getContent()
    {
        // First - process actions
        if(isset($_POST['update_price_settings'])){ $this->processSave();}

        return '';
    }
}
