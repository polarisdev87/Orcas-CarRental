<?php
/**
 * NRS Initializer class to load admin section
 * Final class cannot be inherited anymore. We use them when creating new instances
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Admin;
use NativeRentalSystem\Controllers\Admin\Benefit\AddEditBenefitController;
use NativeRentalSystem\Controllers\Admin\Benefit\BenefitController;
use NativeRentalSystem\Controllers\Admin\Booking\AddEditBookingController;
use NativeRentalSystem\Controllers\Admin\Booking\AddEditCustomerController;
use NativeRentalSystem\Controllers\Admin\Booking\BookingController;
use NativeRentalSystem\Controllers\Admin\Booking\ViewDetailsController;
use NativeRentalSystem\Controllers\Admin\Booking\BookingSearchResultsController;
use NativeRentalSystem\Controllers\Admin\Booking\CustomerSearchResultsController;
use NativeRentalSystem\Controllers\Admin\Booking\ExtrasAvailabilitySearchResultsController;
use NativeRentalSystem\Controllers\Admin\Booking\ItemsAvailabilitySearchResultsController;
use NativeRentalSystem\Controllers\Admin\Booking\PrintInvoiceController;
use NativeRentalSystem\Controllers\Admin\Extras\AddEditBlockController AS Extra_AddEditBlockController;
use NativeRentalSystem\Controllers\Admin\Extras\AddEditDiscountController AS Extra_AddEditDiscountController;
use NativeRentalSystem\Controllers\Admin\Extras\AddEditExtraController;
use NativeRentalSystem\Controllers\Admin\Extras\AddEditOptionController AS Extra_AddEditOptionController;
use NativeRentalSystem\Controllers\Admin\Extras\ExtrasController;
use NativeRentalSystem\Controllers\Admin\Item\AddEditBlockController AS Item_AddEditBlockController;
use NativeRentalSystem\Controllers\Admin\Item\AddEditBodyTypeController;
use NativeRentalSystem\Controllers\Admin\Item\AddEditFeatureController;
use NativeRentalSystem\Controllers\Admin\Item\AddEditFuelTypeController;
use NativeRentalSystem\Controllers\Admin\Item\AddEditItemController;
use NativeRentalSystem\Controllers\Admin\Item\AddEditManufacturerController;
use NativeRentalSystem\Controllers\Admin\Item\AddEditOptionController AS Item_AddEditOptionController;
use NativeRentalSystem\Controllers\Admin\Item\AddEditTransmissionTypeController;
use NativeRentalSystem\Controllers\Admin\Item\ItemController;
use NativeRentalSystem\Controllers\Admin\ItemPrice\AddEditDiscountController AS Item_AddEditDiscountController;
use NativeRentalSystem\Controllers\Admin\ItemPrice\AddEditPriceGroupController;
use NativeRentalSystem\Controllers\Admin\ItemPrice\AddEditPricePlanController;
use NativeRentalSystem\Controllers\Admin\ItemPrice\ItemPriceController;
use NativeRentalSystem\Controllers\Admin\Location\AddEditDistanceController;
use NativeRentalSystem\Controllers\Admin\Location\AddEditLocationController;
use NativeRentalSystem\Controllers\Admin\Location\LocationController;
use NativeRentalSystem\Controllers\Admin\Payment\AddEditPaymentMethodController;
use NativeRentalSystem\Controllers\Admin\Payment\AddEditPrepaymentController;
use NativeRentalSystem\Controllers\Admin\Payment\AddEditTaxController;
use NativeRentalSystem\Controllers\Admin\Payment\PaymentController;
use NativeRentalSystem\Controllers\Admin\Settings\AddEditCustomerSettingsController;
use NativeRentalSystem\Controllers\Admin\Settings\AddEditEmailController;
use NativeRentalSystem\Controllers\Admin\Settings\AddEditGlobalSettingsController;
use NativeRentalSystem\Controllers\Admin\Settings\AddEditPriceSettingsController;
use NativeRentalSystem\Controllers\Admin\Settings\AddEditSearchSettingsController;
use NativeRentalSystem\Controllers\Admin\Settings\ImportDemoController;
use NativeRentalSystem\Controllers\Admin\Settings\PreviewController;
use NativeRentalSystem\Controllers\Admin\Settings\NetworkController;
use NativeRentalSystem\Controllers\Admin\Settings\SingleController;
use NativeRentalSystem\Controllers\Admin\Update\NetworkController AS Update_NetworkController;
use NativeRentalSystem\Controllers\Admin\Update\SingleController AS Update_SingleController;
use NativeRentalSystem\Controllers\Admin\UserManual\UserManualController;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Install\Install;
use NativeRentalSystem\Models\Language\Language;

final class LoadController
{
    protected static $scriptsLoaded = FALSE;
    protected static $stylesLoaded  = FALSE;
    private $conf 	                = NULL;
    private $lang 		            = NULL;
    private $debugMode 	            = 0;
    private $errors                 = array();

    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    public function isDatabaseVersionUpToDate()
    {
        $sql = "
				SELECT conf_value AS plugin_version
				FROM {$this->conf->getPrefix()}settings
				WHERE conf_key='conf_plugin_version' AND blog_id='{$this->conf->getBlogId()}'
			";
        $pluginVersionFieldValue = $this->conf->getInternalWPDB()->get_var($sql);

        $pluginDatabaseVersion = 3.2;
        if(!is_null($pluginVersionFieldValue))
        {
            $pluginDatabaseVersion = floatval($pluginVersionFieldValue);
        }
        $codeVersion = $this->conf->getVersion();

        // DEBUG
        //echo "DB VERSION: {$pluginDatabaseVersion}<br />";
        //echo "CODE VERSION: {$codeVersion}<br />";

        return $pluginDatabaseVersion >= $codeVersion ? TRUE : FALSE;
    }

    /**
     * Set discount status - called in class constructor
     */
    private function arePrepaymentsEnabled()
    {
        $sql = "
				SELECT conf_value AS prepayment_enabled
				FROM {$this->conf->getPrefix()}settings
				WHERE conf_key='conf_prepayment_enabled' AND blog_id='{$this->conf->getBlogId()}'
			";
        $prepaymentEnabled = $this->conf->getInternalWPDB()->get_var($sql);

        if(!is_null($prepaymentEnabled) && intval($prepaymentEnabled) == 1)
        {
            $retStatus = TRUE;
        } else
        {
            $retStatus = FALSE;
        }

        return $retStatus;
    }

    public function registerScripts()
    {
        // Note: 'jquery-ui-datepicker' is registered in WordPress core
        //wp_enqueue_script('jquery-ui-datepicker'); - not sure why it was enqueue here

        // Register scripts for further use - in file_exists we must use PATH, and in register_script we must use URL
        if(is_readable($this->conf->getExtensionLangPath('DatePicker/'.get_locale().'.js')))
        {
            wp_register_script(
                'jquery-datepicker-locale', $this->conf->getExtensionLangURL('DatePicker/'.get_locale().'.js'),
                array('jquery', 'jquery-ui-datepicker')
            );
        } else
        {
            wp_register_script(
                'jquery-datepicker-locale', $this->conf->getExtensionLangURL('DatePicker/en_US.js'),
                array('jquery', 'jquery-ui-datepicker')
            );
        }

        wp_register_script('multidatespicker', $this->conf->getExtensionAdminJSURL('jquery-ui.multidatespicker.js'));

        // 2. Datatables with Responsive support
        wp_register_script('jquery-datatables', $this->conf->getExtensionAdminJSURL('jquery.dataTables.min.js'));
        wp_register_script('datatables-responsive', $this->conf->getExtensionAdminJSURL('dataTables.responsive.min.js'));

        // 3. jQuery validate
        wp_register_script('jquery-validate', $this->conf->getExtensionAdminJSURL('jquery.validate.js'));

        // 4. NRS Admin script
        wp_register_script($this->conf->getURLPrefix().'admin', $this->conf->getExtensionAdminJSURL('Admin.js'), array(), '1.0', TRUE);

        $javascriptLocale = array(
            'NRS_ADMIN_AJAX_DELETE_FEATURE_CONFIRM_TEXT' => $this->lang->getText('NRS_ADMIN_AJAX_DELETE_FEATURE_CONFIRM_TEXT'),
            'NRS_ADMIN_AJAX_DELETE_BENEFIT_CONFIRM_TEXT' => $this->lang->getText('NRS_ADMIN_AJAX_DELETE_BENEFIT_CONFIRM_TEXT'),
            'NRS_ADMIN_AJAX_DELETE_CUSTOMER_CONFIRM_TEXT' => $this->lang->getText('NRS_ADMIN_AJAX_DELETE_CUSTOMER_CONFIRM_TEXT'),
            'NRS_ADMIN_AJAX_DELETE_TRANSMISSION_TYPE_TEXT' => $this->lang->getText('NRS_ADMIN_AJAX_DELETE_TRANSMISSION_TYPE_TEXT'),
            'NRS_ADMIN_AJAX_DELETE_MANUFACTURER_CONFIRM_TEXT' => $this->lang->getText('NRS_ADMIN_AJAX_DELETE_MANUFACTURER_CONFIRM_TEXT'),
            'NRS_ADMIN_AJAX_DELETE_ITEM_CONFIRM_TEXT' => $this->lang->getText('NRS_ADMIN_AJAX_DELETE_ITEM_CONFIRM_TEXT'),
            'NRS_ADMIN_AJAX_DELETE_FUEL_TYPE_CONFIRM_TEXT' => $this->lang->getText('NRS_ADMIN_AJAX_DELETE_FUEL_TYPE_CONFIRM_TEXT'),
            'NRS_ADMIN_AJAX_DELETE_BODY_TYPE_CONFIRM_TEXT' => $this->lang->getText('NRS_ADMIN_AJAX_DELETE_BODY_TYPE_CONFIRM_TEXT'),
            'NRS_ADMIN_AJAX_DELETE_EXTRA_CONFIRM_TEXT' => $this->lang->getText('NRS_ADMIN_AJAX_DELETE_EXTRA_CONFIRM_TEXT'),
            'NRS_ADMIN_AJAX_DELETE_LOCATION_CONFIRM_TEXT' => $this->lang->getText('NRS_ADMIN_AJAX_DELETE_LOCATION_CONFIRM_TEXT'),
            'NRS_ADMIN_AJAX_DELETE_DISTANCE_CONFIRM_TEXT' => $this->lang->getText('NRS_ADMIN_AJAX_DELETE_DISTANCE_CONFIRM_TEXT'),
            'NRS_ADMIN_AJAX_DELETE_PRICE_GROUP_CONFIRM_TEXT' => $this->lang->getText('NRS_ADMIN_AJAX_DELETE_PRICE_GROUP_CONFIRM_TEXT'),
            'NRS_ADMIN_AJAX_PRICE_PLANS_NOT_FOUND_TEXT' => $this->lang->getText('NRS_ADMIN_AJAX_PRICE_PLANS_NOT_FOUND_TEXT'),
            'NRS_ADMIN_AJAX_PRICE_PLANS_PLEASE_SELECT_TEXT' => $this->lang->getText('NRS_ADMIN_AJAX_PRICE_PLANS_PLEASE_SELECT_TEXT'),
            'NRS_ADMIN_AJAX_CLOSED_ON_SELECTED_DATES_TEXT' => $this->lang->getText('NRS_ADMIN_AJAX_CLOSED_ON_SELECTED_DATES_TEXT'),
            'NRS_ADMIN_AJAX_PRINT_INVOICE_POPUP_TITLE_TEXT' => $this->lang->getText('NRS_ADMIN_AJAX_PRINT_INVOICE_POPUP_TITLE_TEXT'),
            'NRS_ADMIN_AJAX_CANCEL_BOOKING_CONFIRM_TEXT' => $this->lang->getText('NRS_ADMIN_AJAX_CANCEL_BOOKING_CONFIRM_TEXT'),
            'NRS_ADMIN_AJAX_DELETE_BOOKING_CONFIRM_TEXT' => $this->lang->getText('NRS_ADMIN_AJAX_DELETE_BOOKING_CONFIRM_TEXT'),
            'NRS_ADMIN_AJAX_MARK_PAID_BOOKING_TEXT' => $this->lang->getText('NRS_ADMIN_AJAX_MARK_PAID_BOOKING_TEXT'),
            'NRS_ADMIN_AJAX_MARK_COMPLETED_EARLY_CONFIRM_TEXT' => $this->lang->getText('NRS_ADMIN_AJAX_MARK_COMPLETED_EARLY_CONFIRM_TEXT'),
            'NRS_ADMIN_AJAX_REFUND_BOOKING_CONFIRM_TEXT' => $this->lang->getText('NRS_ADMIN_AJAX_REFUND_BOOKING_CONFIRM_TEXT'),
        );

        // Script localizations, fires only when wp_enqueue_script is used
        // Read more: https://codex.wordpress.org/AJAX_in_Plugins
        // Read more 2: https://pippinsplugins.com/use-wp_localize_script-it-is-awesome/
        wp_localize_script($this->conf->getURLPrefix().'admin', $this->conf->getVariablePrefix().'Locale', $javascriptLocale);
    }

    public function registerStyles()
    {
        // 1. Admin tabs styles
        wp_register_style('font-awesome', $this->conf->getExtensionAdminCSSURL('FontAwesome.css'));
        wp_register_style($this->conf->getURLPrefix().'admin-tabs', $this->conf->getExtensionAdminCSSURL('AdminTabs.css'));

        // 2. Datatables with Responsive support
        wp_register_style('jquery-datatables', $this->conf->getExtensionAdminCSSURL('jquery.dataTables.min.css'));
        wp_register_style('datatables-responsive', $this->conf->getExtensionAdminCSSURL('responsive.dataTables.min.css'));

        // 3. Datepicker
        wp_register_style('datepicker', $this->conf->getExtensionAdminCSSURL('DatePicker.css'));
        wp_register_style('jquery-ui', $this->conf->getExtensionAdminCSSURL('jqueryui.css'));

        // 4. jQueryValidate
        wp_register_style('jquery-validate', $this->conf->getExtensionAdminCSSURL('jquery.validate.css'));

        // 5. Plugin design style
        wp_enqueue_style($this->conf->getURLPrefix().'admin', $this->conf->getExtensionAdminCSSURL('style.Admin.css'));
    }


    /****************************************************************************************/
    /************************** NETWORK-ENABLED SITE METHODS ********************************/
    /****************************************************************************************/

    /**
     * @param int $paramMenuPosition
     */
    public function addPluginNetworkAdminMenu($paramMenuPosition = 97)
    {
        $validMenuPosition = intval($paramMenuPosition);
        $iconURL = $this->conf->getExtensionAdminImagesURL('Plugin.png');
        $extensionPrefix = $this->conf->getExtensionPrefix();
        $URLPrefix = $this->conf->getURLPrefix();

        $objInstall = new Install($this->conf, $this->lang, $this->conf->getBlogId());
        if($objInstall->isDatabaseVersionUpToDate())
        {
            // For those, who have 'manage_{$extensionPrefix}all_inventory' rights
            add_menu_page(
                $this->lang->getText('NRS_ADMIN_MENU_EXTENSION_PAGE_TITLE_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_EXTENSION_LABEL_TEXT'),
                "manage_{$extensionPrefix}all_inventory", "{$URLPrefix}menu", array(&$this, "printNetworkManager"), $iconURL, $validMenuPosition
            );

            // For admins only
            add_submenu_page(
                "{$URLPrefix}menu", $this->lang->getText('NRS_ADMIN_MENU_NETWORK_MANAGER_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_NETWORK_MANAGER_TEXT'),
                "manage_{$extensionPrefix}all_inventory","{$URLPrefix}network-manager", array(&$this, "printNetworkManager")
            );
            remove_submenu_page("{$URLPrefix}menu", "{$URLPrefix}menu");
        } else
        {
            // For admins only - update_plugins are official WordPress role for updates
            add_menu_page(
                $this->lang->getText('NRS_ADMIN_MENU_EXTENSION_PAGE_TITLE_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_EXTENSION_LABEL_TEXT'),
                "update_plugins", "{$URLPrefix}menu", array(&$this, "printNetworkUpdater"), $iconURL, $validMenuPosition
            );
            add_submenu_page(
                "{$URLPrefix}menu", $this->lang->getText('NRS_ADMIN_MENU_NETWORK_UPDATE_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_NETWORK_UPDATE_TEXT'),
                "update_plugins", "{$URLPrefix}network-updater", array(&$this, "printNetworkUpdater")
            );
            remove_submenu_page("{$URLPrefix}menu", "{$URLPrefix}menu");
        }
    }

    // Network Manager
    public function printNetworkManager()
    {
        try
        {
            $objNetworkController = new NetworkController($this->conf, $this->lang);
            print($objNetworkController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
    }


    // Network Updater (available only if there is newer plugin code uploaded than set in db version)
    public function printNetworkUpdater()
    {
        try
        {
            $objNetworkUpdateController = new Update_NetworkController($this->conf, $this->lang);
            print($objNetworkUpdateController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
    }

    /****************************************************************************************/
    /************************** LOCALLY-ENABLED SITE METHODS ********************************/
    /****************************************************************************************/

    /**
     * @param int $paramMenuPosition
     */
	public function addPluginAdminMenu($paramMenuPosition = 97)
	{
        $validMenuPosition = intval($paramMenuPosition);
		$iconURL = $this->conf->getExtensionAdminImagesURL('Plugin.png');
		$extensionPrefix = $this->conf->getExtensionPrefix();
        $URLPrefix = $this->conf->getURLPrefix();

        $objInstall = new Install($this->conf, $this->lang, $this->conf->getBlogId());
		if($objInstall->isDatabaseVersionUpToDate())
		{
            // For those, who have 'view_{$extensionPrefix}bookings'
            add_menu_page(
                $this->lang->getText('NRS_ADMIN_MENU_EXTENSION_PAGE_TITLE_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_EXTENSION_LABEL_TEXT'),
                "view_{$extensionPrefix}partner_bookings", "{$URLPrefix}menu", array(&$this, "printItemManager"), $iconURL, $validMenuPosition
            );

            // For those, who have 'view_{$extensionPrefix}_inventory' or 'manage_{$extensionPrefix}all_inventory' rights
            add_submenu_page(
                "{$URLPrefix}menu", $this->lang->getText('NRS_ADMIN_MENU_BENEFIT_MANAGER_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_BENEFIT_MANAGER_TEXT'),
                "view_{$extensionPrefix}all_benefits", "{$URLPrefix}benefit-manager", array(&$this, "printBenefitManager")
            );
                add_submenu_page(
                    "{$URLPrefix}manager", $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_BENEFIT_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_BENEFIT_TEXT'),
                    "manage_{$extensionPrefix}all_benefits", "{$URLPrefix}add-edit-benefit", array(&$this, "printBenefitAddEdit")
                );

            // For those, who have 'view_{$extensionPrefix}own_items', 'manage_{$extensionPrefix}all_inventory' or 'manage_{$extensionPrefix}own_items' rights
            add_submenu_page(
                "{$URLPrefix}menu", $this->lang->getText('NRS_ADMIN_MENU_ITEM_MANAGER_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_ITEM_MANAGER_TEXT'),
                "view_{$extensionPrefix}own_items", "{$URLPrefix}manager", array(&$this, "printItemManager")
            );
				add_submenu_page(
				    "{$URLPrefix}manager", $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_ITEM_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_ITEM_TEXT'),
                    "manage_{$extensionPrefix}own_items", "{$URLPrefix}add-edit-item", array(&$this, "printItemAddEdit")
                );
				add_submenu_page(
				    "{$URLPrefix}manager", $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_MANUFACTURER_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_MANUFACTURER_TEXT'),
                    "manage_{$extensionPrefix}all_inventory", "{$URLPrefix}add-edit-manufacturer", array(&$this, "printManufacturerAddEdit")
                );
				add_submenu_page(
				    "{$URLPrefix}manager", $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_BODY_TYPE_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_BODY_TYPE_TEXT'),
                    "manage_{$extensionPrefix}all_inventory", "{$URLPrefix}add-edit-body-type", array(&$this, "printBodyTypeAddEdit")
                );
				add_submenu_page(
				    "{$URLPrefix}manager", $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_FUEL_TYPE_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_FUEL_TYPE_TEXT'),
                    "manage_{$extensionPrefix}all_inventory", "{$URLPrefix}add-edit-fuel-type", array(&$this, "printFuelTypeAddEdit")
                );
				add_submenu_page(
				    "{$URLPrefix}manager", $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_TRANSMISSION_TYPE_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_TRANSMISSION_TYPE_TEXT'),
                    "manage_{$extensionPrefix}all_inventory", "{$URLPrefix}add-edit-transmission-type", array(&$this, "printTransmissionTypeAddEdit")
                );
				add_submenu_page(
				    "{$URLPrefix}manager", $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_FEATURE_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_FEATURE_TEXT'),
                    "manage_{$extensionPrefix}all_inventory", "{$URLPrefix}add-edit-feature", array(&$this, "printFeatureAddEdit")
                );
				add_submenu_page(
				    "{$URLPrefix}manager", $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_ITEM_OPTION_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_ITEM_OPTION_TEXT'),
                    "manage_{$extensionPrefix}own_items", "{$URLPrefix}add-edit-item-option", array(&$this, "printItemOptionAddEdit")
                );
				add_submenu_page(
				    "{$URLPrefix}manager", $this->lang->getText('NRS_ADMIN_MENU_BLOCK_ITEM_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_BLOCK_ITEM_TEXT'),
                    "manage_{$extensionPrefix}own_items", "{$URLPrefix}add-edit-item-block", array(&$this, "printBlockItem")
                );

            // For those, who have 'view_{$extensionPrefix}own_items' or 'manage_{$extensionPrefix}own_items' rights
            add_submenu_page(
                "{$URLPrefix}menu", $this->lang->getText('NRS_ADMIN_MENU_ITEM_PRICES_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_ITEM_PRICES_TEXT'),
                "view_{$extensionPrefix}own_items", "{$URLPrefix}prices", array(&$this, "printItemPrices")
            );
                add_submenu_page(
                    "{$URLPrefix}prices", $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_PRICE_GROUP_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_PRICE_GROUP_TEXT'),
                    "manage_{$extensionPrefix}own_items", "{$URLPrefix}add-edit-price-group", array(&$this, "printPriceGroupAddEdit")
                );
				add_submenu_page(
				    "{$URLPrefix}prices", $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_PRICE_PLAN_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_PRICE_PLAN_TEXT'),
                    "manage_{$extensionPrefix}own_items", "{$URLPrefix}add-edit-price-plan", array(&$this, "printPricePlanAddEdit")
                );
                add_submenu_page(
                    "{$URLPrefix}prices", $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_ITEM_DISCOUNT_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_ITEM_DISCOUNT_TEXT'),
                    "manage_{$extensionPrefix}own_items", "{$URLPrefix}add-edit-item-discount", array(&$this, "printItemDiscountAddEdit")
                );

            // For those, who have 'view_{$extensionPrefix}own_extras' or 'manage_{$extensionPrefix}own_extras' rights
			add_submenu_page(
			    "{$URLPrefix}menu", $this->lang->getText('NRS_ADMIN_MENU_EXTRAS_MANAGER_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_EXTRAS_MANAGER_TEXT'),
                "view_{$extensionPrefix}own_extras", "{$URLPrefix}extras-manager", array(&$this, "printExtrasManager")
            );
				add_submenu_page(
				    "{$URLPrefix}extras-manager", $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_EXTRA_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_EXTRA_TEXT'),
                    "manage_{$extensionPrefix}own_extras", "{$URLPrefix}add-edit-extra", array(&$this, "printExtraAddEdit")
                );
				add_submenu_page(
				    "{$URLPrefix}extras-manager", $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_EXTRA_OPTION_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_EXTRA_OPTION_TEXT'),
                    "manage_{$extensionPrefix}own_extras", "{$URLPrefix}add-edit-extra-option", array(&$this, "printExtraOptionAddEdit")
                );
                add_submenu_page(
                    "{$URLPrefix}extras-manager", $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_EXTRA_DISCOUNT_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_EXTRA_DISCOUNT_TEXT'),
                    "manage_{$extensionPrefix}own_extras", "{$URLPrefix}add-edit-extra-discount", array(&$this, "printExtraDiscountAddEdit")
                );
                add_submenu_page(
                    "{$URLPrefix}extras-manager", $this->lang->getText('NRS_ADMIN_MENU_BLOCK_EXTRA_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_BLOCK_EXTRA_TEXT'),
                    "manage_{$extensionPrefix}own_extras", "{$URLPrefix}add-edit-extra-block", array(&$this, "printBlockExtra")
                );

            // For those, who have 'view_{$extensionPrefix}all_locations' and 'manage_{$extensionPrefix}all_locations' rights
			add_submenu_page(
			    "{$URLPrefix}menu", $this->lang->getText('NRS_ADMIN_MENU_LOCATION_MANAGER_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_LOCATION_MANAGER_TEXT'),
                "view_{$extensionPrefix}all_locations", "{$URLPrefix}location-manager", array(&$this, "printLocationManager")
            );
				add_submenu_page(
				    "{$URLPrefix}location-manager", $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_LOCATION_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_LOCATION_TEXT'),
                    "manage_{$extensionPrefix}all_locations", "{$URLPrefix}add-edit-location", array(&$this, "printLocationAddEdit")
                );
				add_submenu_page(
				    "{$URLPrefix}location-manager", $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_DISTANCE_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_DISTANCE_TEXT'),
                    "manage_{$extensionPrefix}all_locations", "{$URLPrefix}add-edit-distance", array(&$this, "printDistanceAddEdit")
                );

            // For those, who have 'view_{$extensionPrefix}partner_bookings' or 'manage_{$extensionPrefix}customers' rights
			add_submenu_page(
			    "{$URLPrefix}menu", $this->lang->getText('NRS_ADMIN_MENU_BOOKING_MANAGER_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_BOOKING_MANAGER_TEXT'),
                "view_{$extensionPrefix}partner_bookings", "{$URLPrefix}booking-manager", array(&$this, "printBookingManager")
            );
				add_submenu_page(
				    "{$URLPrefix}booking-manager", $this->lang->getText('NRS_ADMIN_MENU_BOOKING_SEARCH_RESULTS_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_BOOKING_SEARCH_RESULTS_TEXT'),
                    "view_{$extensionPrefix}partner_bookings", "{$URLPrefix}booking-search-results", array(&$this, "printBookingSearchResults")
                );
				add_submenu_page(
				    "{$URLPrefix}booking-manager", $this->lang->getText('NRS_ADMIN_MENU_ITEM_CALENDAR_SEARCH_RESULTS_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_ITEM_CALENDAR_SEARCH_RESULTS_TEXT'),
                    "view_{$extensionPrefix}partner_bookings", "{$URLPrefix}item-calendar-search-results", array(&$this, "printItemsAvailabilitySearchResults")
                );
				add_submenu_page(
				    "{$URLPrefix}booking-manager", $this->lang->getText('NRS_ADMIN_MENU_EXTRAS_CALENDAR_SEARCH_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_EXTRAS_CALENDAR_SEARCH_TEXT'),
                    "view_{$extensionPrefix}partner_bookings", "{$URLPrefix}extras-calendar-search-results", array(&$this, "printExtrasAvailabilitySearchResults")
                );
				add_submenu_page(
				    "{$URLPrefix}booking-manager", $this->lang->getText('NRS_ADMIN_MENU_CUSTOMER_SEARCH_RESULTS_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_CUSTOMER_SEARCH_RESULTS_TEXT'),
                    "view_{$extensionPrefix}partner_bookings", "{$URLPrefix}customer-search-results", array(&$this, "printCustomerSearchResults")
                );
				add_submenu_page(
				    "{$URLPrefix}booking-manager", $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_CUSTOMER_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_CUSTOMER_TEXT'),
                    "manage_{$extensionPrefix}all_customers", "{$URLPrefix}add-edit-customer", array(&$this, "printCustomerAddEdit")
                );
				add_submenu_page(
				    "{$URLPrefix}booking-manager", $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_BOOKING_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_BOOKING_TEXT'),
                    "manage_{$extensionPrefix}partner_bookings", "{$URLPrefix}add-edit-booking", array(&$this, "printBookingAddEdit")
                );
				add_submenu_page(
				    "{$URLPrefix}booking-manager", $this->lang->getText('NRS_ADMIN_MENU_VIEW_DETAILS_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_VIEW_DETAILS_TEXT'),
                    "view_{$extensionPrefix}partner_bookings","{$URLPrefix}view-details", array(&$this, "printViewDetails")
                );
				add_submenu_page(
				    "{$URLPrefix}booking-manager", $this->lang->getText('NRS_ADMIN_MENU_PRINT_INVOICE_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_PRINT_INVOICE_TEXT'),
                    "view_{$extensionPrefix}partner_bookings","{$URLPrefix}print-invoice", array(&$this, "printInvoice")
                );

            // For those, who have 'view_{$extensionPrefix}all_settings' or 'manage_{$extensionPrefix}all_settings' rights
            add_submenu_page(
                "{$URLPrefix}menu", $this->lang->getText('NRS_ADMIN_MENU_PAYMENTS_AND_TAXES_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_PAYMENTS_AND_TAXES_TEXT'),
                "view_{$extensionPrefix}all_settings", "{$URLPrefix}payment-manager", array(&$this, "printPaymentManager")
            );
                if($this->arePrepaymentsEnabled())
                {
                    add_submenu_page(
                        "{$URLPrefix}payment-manager", $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_PAYMENT_METHOD_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_PAYMENT_METHOD_TEXT'),
                        "manage_{$extensionPrefix}all_settings", "{$URLPrefix}add-edit-payment-method", array(&$this, "printPaymentMethodAddEdit")
                    );
                    add_submenu_page(
                        "{$URLPrefix}payment-manager", $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_PREPAYMENT_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_PREPAYMENT_TEXT'),
                        "manage_{$extensionPrefix}all_settings", "{$URLPrefix}add-edit-prepayment", array(&$this, "printPrepaymentAddEdit")
                    );
                }
                add_submenu_page(
                    "{$URLPrefix}payment-manager", $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_TAX_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_TAX_TEXT'),
                    "manage_{$extensionPrefix}all_settings", "{$URLPrefix}add-edit-tax", array(&$this, "printTaxAddEdit")
                );

            // For those, who have 'view_{$extensionPrefix}all_settings' or 'manage_{$extensionPrefix}all_settings' rights
			add_submenu_page(
			    "{$URLPrefix}menu", $this->lang->getText('NRS_ADMIN_MENU_SINGLE_MANAGER_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_SINGLE_MANAGER_TEXT'),
                "view_{$extensionPrefix}all_settings","{$URLPrefix}single-manager", array(&$this, "printSingleManager")
            );
                add_submenu_page(
                    "{$URLPrefix}settings", $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_GLOBAL_SETTINGS_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_CONTENT_PREVIEW_TEXT'),
                    "manage_{$extensionPrefix}all_settings","{$URLPrefix}add-edit-global-settings", array(&$this, "printGlobalSettingsAddEdit")
                );
                add_submenu_page(
                    "{$URLPrefix}settings", $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_CUSTOMER_SETTINGS_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_CONTENT_PREVIEW_TEXT'),
                    "manage_{$extensionPrefix}all_settings","{$URLPrefix}add-edit-customer-settings", array(&$this, "printCustomerSettingsAddEdit")
                );
                add_submenu_page(
                    "{$URLPrefix}settings", $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_SEARCH_SETTINGS_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_CONTENT_PREVIEW_TEXT'),
                    "manage_{$extensionPrefix}all_settings","{$URLPrefix}add-edit-search-settings", array(&$this, "printSearchSettingsAddEdit")
                );
                add_submenu_page(
                    "{$URLPrefix}settings", $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_PRICE_SETTINGS_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_CONTENT_PREVIEW_TEXT'),
                    "manage_{$extensionPrefix}all_settings","{$URLPrefix}add-edit-price-settings", array(&$this, "printPriceSettingsAddEdit")
                );
                add_submenu_page(
                    "{$URLPrefix}settings", $this->lang->getText('NRS_ADMIN_MENU_ADD_EDIT_EMAIL_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_CONTENT_PREVIEW_TEXT'),
                    "manage_{$extensionPrefix}all_settings","{$URLPrefix}add-edit-email", array(&$this, "printEmailAddEdit")
                );
                add_submenu_page(
                    "{$URLPrefix}settings", $this->lang->getText('NRS_ADMIN_MENU_IMPORT_DEMO_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_CONTENT_PREVIEW_TEXT'),
                    "manage_{$extensionPrefix}all_settings","{$URLPrefix}import-demo", array(&$this, "printImportDemo")
                );
				add_submenu_page(
				    "{$URLPrefix}settings", $this->lang->getText('NRS_ADMIN_MENU_CONTENT_PREVIEW_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_CONTENT_PREVIEW_TEXT'),
                    "manage_{$extensionPrefix}all_settings","{$URLPrefix}preview", array(&$this, "printPreview")
                );

            // For those, who have 'edit_pages' rights
            // We allow to see shortcodes for those who have rights to edit pages (including item description pages)
			add_submenu_page(
			    "{$URLPrefix}menu", $this->lang->getText('NRS_ADMIN_MENU_INSTRUCTIONS_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_INSTRUCTIONS_TEXT'),
                "edit_pages","{$URLPrefix}user-manual", array(&$this, "printUserManual")
            );
            remove_submenu_page("{$URLPrefix}menu", "{$URLPrefix}menu");
		} else
		{
            // For admins only - update_plugins are official WordPress role for updates
			add_menu_page(
			    $this->lang->getText('NRS_ADMIN_MENU_EXTENSION_PAGE_TITLE_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_EXTENSION_LABEL_TEXT'),
                "update_plugins", "{$URLPrefix}menu", array(&$this, "printUpdater"), $iconURL, $validMenuPosition
            );
				add_submenu_page(
				    "{$URLPrefix}menu", $this->lang->getText('NRS_ADMIN_MENU_SYSTEM_UPDATE_TEXT'), $this->lang->getText('NRS_ADMIN_MENU_SYSTEM_UPDATE_TEXT'),
                    "update_plugins", "{$URLPrefix}updater", array(&$this, "printUpdater")
                );
			remove_submenu_page("{$URLPrefix}menu", "{$URLPrefix}menu");
		}
	}


    // Benefit Manager
    public function printBenefitManager()
    {
        try
        {
            $objBenefitController = new BenefitController($this->conf, $this->lang);
            print($objBenefitController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
    }


	// Item Manager
	public function printItemManager()
	{
        try
        {
            $objItemController = new ItemController($this->conf, $this->lang);
            print($objItemController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
	}

	public function printItemAddEdit()
	{
        try
        {
            $objAddEditController = new AddEditItemController($this->conf, $this->lang);
            print($objAddEditController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
	}

    public function printBenefitAddEdit()
    {
        try
        {
            $objAddEditController = new AddEditBenefitController($this->conf, $this->lang);
            print($objAddEditController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
    }

	public function printManufacturerAddEdit()
	{
        try
        {
            $objAddEditController = new AddEditManufacturerController($this->conf, $this->lang);
            print($objAddEditController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
	}

	public function printBodyTypeAddEdit()
	{
        try
        {
            $objAddEditController = new AddEditBodyTypeController($this->conf, $this->lang);
            print($objAddEditController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
	}

	public function printFuelTypeAddEdit()
	{
	    try
        {
            $objAddEditController = new AddEditFuelTypeController($this->conf, $this->lang);
            print($objAddEditController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
	}

	public function printTransmissionTypeAddEdit()
	{
        try
        {
            $objAddEditController = new AddEditTransmissionTypeController($this->conf, $this->lang);
            print($objAddEditController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
	}

	public function printFeatureAddEdit()
	{
        try
        {
            $objAddEditController = new AddEditFeatureController($this->conf, $this->lang);
            print($objAddEditController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
	}

	public function printItemOptionAddEdit()
	{
        try
        {
            $objAddEditController = new Item_AddEditOptionController($this->conf, $this->lang);
            print($objAddEditController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
	}

	public function printBlockItem()
	{
        try
        {
            $objBlockController = new Item_AddEditBlockController($this->conf, $this->lang);
            print($objBlockController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
	}


	// Item Price Manager
	public function printItemPrices()
	{
        try
        {
            $objPriceController = new ItemPriceController($this->conf, $this->lang);
            print($objPriceController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
	}

    public function printPriceGroupAddEdit()
    {
        try
        {
            $objAddEditController = new AddEditPriceGroupController($this->conf, $this->lang);
            print($objAddEditController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
    }

	public function printPricePlanAddEdit()
	{
        try
        {
            $objAddEditController = new AddEditPricePlanController($this->conf, $this->lang);
            print($objAddEditController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
	}

    public function printItemDiscountAddEdit()
    {
        try
        {
            $objAddEditController = new Item_AddEditDiscountController($this->conf, $this->lang);
            print($objAddEditController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
    }


	// Extras Manager
	public function printExtrasManager()
	{
        try
        {
            $objExtrasController = new ExtrasController($this->conf, $this->lang);
            print($objExtrasController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
	}

	public function printExtraAddEdit()
	{
        try
        {
            $objAddEditController = new AddEditExtraController($this->conf, $this->lang);
            print($objAddEditController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
	}

	public function printExtraOptionAddEdit()
	{
        try
        {
            $objAddEditController = new Extra_AddEditOptionController($this->conf, $this->lang);
            print($objAddEditController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
	}

    public function printExtraDiscountAddEdit()
    {
        try
        {
            $objAddEditController = new Extra_AddEditDiscountController($this->conf, $this->lang);
            print($objAddEditController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
    }

	public function printBlockExtra()
	{
        try
        {
            $objBlockController = new Extra_AddEditBlockController($this->conf, $this->lang);
            print($objBlockController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
	}


	// Location Manager
	public function printLocationManager()
	{
        try
        {
            $objLocationController = new LocationController($this->conf, $this->lang);
            print($objLocationController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
	}

	public function printLocationAddEdit()
	{
        try
        {
            $objAddEditController = new AddEditLocationController($this->conf, $this->lang);
            print($objAddEditController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
	}

    public function printDistanceAddEdit()
    {
        try
        {
            $objAddEditController = new AddEditDistanceController($this->conf, $this->lang);
            print($objAddEditController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
    }


	// Item & Extras Discount Manager
	public function printPaymentManager()
	{
        try
        {
            $objDiscountController = new PaymentController($this->conf, $this->lang);
            print($objDiscountController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
	}

    public function printPaymentMethodAddEdit()
    {
        try
        {
            $objAddEditController = new AddEditPaymentMethodController($this->conf, $this->lang);
            print($objAddEditController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
    }

    public function printPrepaymentAddEdit()
    {
        try
        {
            $objAddEditController = new AddEditPrepaymentController($this->conf, $this->lang);
            print($objAddEditController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
    }

    public function printTaxAddEdit()
    {
        try
        {
            $objAddEditController = new AddEditTaxController($this->conf, $this->lang);
            print($objAddEditController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
    }


	// Booking Manager
	public function printBookingManager()
	{
        try
        {
            $objBookingController = new BookingController($this->conf, $this->lang);
            print($objBookingController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
	}

	public function printBookingSearchResults()
	{
        try
        {
            $objSearchController = new BookingSearchResultsController($this->conf, $this->lang);
            print($objSearchController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
	}

	public function printItemsAvailabilitySearchResults()
	{
        try
        {
            $objAvailabilityController = new ItemsAvailabilitySearchResultsController($this->conf, $this->lang);
            print($objAvailabilityController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
	}

	public function printExtrasAvailabilitySearchResults()
	{
        try
        {
            $objAvailabilityController = new ExtrasAvailabilitySearchResultsController($this->conf, $this->lang);
            print($objAvailabilityController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
	}

	public function printCustomerSearchResults()
	{
        try
        {
            $objSearchController = new CustomerSearchResultsController($this->conf, $this->lang);
            print($objSearchController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
	}

	public function printCustomerAddEdit()
	{
        try
        {
            $objAddEditController = new AddEditCustomerController($this->conf, $this->lang);
            print($objAddEditController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
	}

    public function printBookingAddEdit()
    {
        try
        {
            $objAddEditController = new AddEditBookingController($this->conf, $this->lang);
            print($objAddEditController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
    }

	public function printViewDetails()
	{
        try
        {
            $objViewDetailsController = new ViewDetailsController($this->conf, $this->lang);
            print($objViewDetailsController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
	}

    public function printInvoice()
    {
        try
        {
            $objInvoiceController = new PrintInvoiceController($this->conf, $this->lang);
            print($objInvoiceController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
    }


	// Single Manager
	public function printSingleManager()
	{
        try
        {
            $objSettingsController = new SingleController($this->conf, $this->lang);
            print($objSettingsController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
	}

    public function printGlobalSettingsAddEdit()
    {
        try
        {
            $objAddEditController = new AddEditGlobalSettingsController($this->conf, $this->lang);
            print($objAddEditController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
    }

    public function printCustomerSettingsAddEdit()
    {
        try
        {
            $objAddEditController = new AddEditCustomerSettingsController($this->conf, $this->lang);
            print($objAddEditController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
    }

    public function printSearchSettingsAddEdit()
    {
        try
        {
            $objAddEditController = new AddEditSearchSettingsController($this->conf, $this->lang);
            print($objAddEditController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
    }

    public function printPriceSettingsAddEdit()
    {
        try
        {
            $objAddEditController = new AddEditPriceSettingsController($this->conf, $this->lang);
            print($objAddEditController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
    }

    public function printEmailAddEdit()
    {
        try
        {
            $objAddEditController = new AddEditEmailController($this->conf, $this->lang);
            print($objAddEditController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
    }

    public function printImportDemo()
    {
        try
        {
            $objImportDemoController = new ImportDemoController($this->conf, $this->lang);
            print($objImportDemoController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
    }

	public function printPreview()
	{
        try
        {
            $objPreviewController = new PreviewController($this->conf, $this->lang);
            print($objPreviewController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
	}

	// User Manual
	public function printUserManual()
	{
        try
        {
            $objUserManualController = new UserManualController($this->conf, $this->lang);
            print($objUserManualController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
	}


	// Updater (available only if there is newer plugin code uploaded than set in db version)
	public function printUpdater()
	{
        try
        {
            $objUpdateController = new Update_SingleController($this->conf, $this->lang);
            print($objUpdateController->getContent());
        }
        catch (\Exception $e)
        {
            $this->processError(__FUNCTION__, $e->getMessage());
        }
	}


	/******************************************************************************************/
	/* Other methods                                                                          */
	/******************************************************************************************/
    /**
     * @param $paramName
     * @param $paramErrorMessage
     */
    private function processError($paramName, $paramErrorMessage)
    {
        if($this->debugMode == 1 || WP_DEBUG)
        {
            $sanitizedName = sanitize_text_field($paramName);
            $sanitizedErrorMessage = sanitize_text_field($paramErrorMessage);
            // Load errors only in local or global debug mode
            $this->errors[] = sprintf($this->lang->getText('NRS_ERROR_IN_METHOD_TEXT'), $sanitizedName).$sanitizedErrorMessage;

            // Doesn't work here (maybe due to fact, that 'admin_notices' has to be registered not later than X point in code)
            //add_action('admin_notices', array( &$this, 'displayErrors'));

            // Works
            $sanitizedErrorMessage = '<div id="message" class="error"><p>'.$sanitizedErrorMessage.'</p></div>';
            _doing_it_wrong($sanitizedName, $sanitizedErrorMessage, $this->conf->getVersion());
        }
    }

    public function displayErrors()
    {
        if(sizeof($this->errors) > 0)
        {
            // Print all errors in the stack
            echo '<div id="message" class="error"><p>';
            echo implode('</p><p>', $this->errors);
            echo '</p></div>';

            // Then, clean the errors stack
            $this->errors = array();
        }
    }
}