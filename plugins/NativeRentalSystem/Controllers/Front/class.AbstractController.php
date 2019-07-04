<?php
/**
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Front;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Settings\SettingsObserver;
use NativeRentalSystem\Views\PageView;

abstract class AbstractController
{
    protected $conf         = NULL;
    protected $lang 	    = NULL;
    protected $view 	    = NULL;
    protected $dbSettings	= NULL;

    // Limitations
    protected $arrLimitations = array();
    protected $itemId   = -1;
    protected $extraId  = -1;
    protected $locationId = -1;
    protected $pickupLocationId = -1;
    protected $returnLocationId = -1;
    protected $partnerId = -1;
    protected $manufacturerId = -1;
    protected $bodyTypeId = -1;
    protected $transmissionTypeId = -1;
    protected $fuelTypeId = -1;
    protected $actionPageId = 0;

    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, $paramArrLimitations = array())
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;
        // Set database settings
        $this->dbSettings = new SettingsObserver($this->conf, $this->lang);
        $this->dbSettings->setSettings();

        // Set limitations
        $this->itemId = isset($paramArrLimitations['item_id']) ? StaticValidator::getValidInteger($paramArrLimitations['item_id'], -1) : -1;
        $this->extraId = isset($paramArrLimitations['extra_id']) ? StaticValidator::getValidInteger($paramArrLimitations['extra_id'], -1) : -1;
        $this->locationId = isset($paramArrLimitations['location_id']) ? StaticValidator::getValidInteger($paramArrLimitations['location_id'], -1) : -1;
        $this->pickupLocationId = isset($paramArrLimitations['pickup_location_id']) ? StaticValidator::getValidInteger($paramArrLimitations['pickup_location_id'], -1) : -1;
        $this->returnLocationId = isset($paramArrLimitations['return_location_id']) ? StaticValidator::getValidInteger($paramArrLimitations['return_location_id'], -1) : -1;
        $this->partnerId = isset($paramArrLimitations['partner_id']) ? StaticValidator::getValidInteger($paramArrLimitations['partner_id'], -1) : -1;
        $this->manufacturerId = isset($paramArrLimitations['manufacturer_id']) ? StaticValidator::getValidInteger($paramArrLimitations['manufacturer_id'], -1) : -1;
        $this->bodyTypeId = isset($paramArrLimitations['body_type_id']) ? StaticValidator::getValidInteger($paramArrLimitations['body_type_id'], -1) : -1;
        $this->transmissionTypeId = isset($paramArrLimitations['transmission_type_id']) ? StaticValidator::getValidInteger($paramArrLimitations['transmission_type_id'], -1) : -1;
        $this->fuelTypeId = isset($paramArrLimitations['fuel_type_id']) ? StaticValidator::getValidInteger($paramArrLimitations['fuel_type_id'], -1) : -1;
        $this->actionPageId = isset($paramArrLimitations['action_page_id']) ? StaticValidator::getValidPositiveInteger($paramArrLimitations['action_page_id'], 0) : 0;

        // Initialize the page view and set it's conf and lang objects
        $this->view = new PageView();
        $this->view->objConf = $this->conf;
        $this->view->objLang = $this->lang;
        $this->view->objSettings = $this->dbSettings;
        $this->view->urlPrefix = $this->conf->getURLPrefix();
        $this->view->variablePrefix = $this->conf->getVariablePrefix();

        // Process limitations to view as well
        $this->view->pickupLocationId = $this->pickupLocationId;
        $this->view->returnLocationId = $this->returnLocationId;
        $this->view->partnerId = $this->partnerId;
        $this->view->manufacturerId = $this->manufacturerId;
        $this->view->bodyTypeId = $this->bodyTypeId;
        $this->view->transmissionTypeId = $this->transmissionTypeId;
        $this->view->fuelTypeId = $this->fuelTypeId;
    }

    protected function wpDebugEnabledDisplay()
    {
        $inDebug = defined('WP_DEBUG') && WP_DEBUG == TRUE && defined('WP_DEBUG_DISPLAY') && WP_DEBUG_DISPLAY == TRUE;

        return $inDebug;
    }

    protected function fillSearchFieldsView()
    {
        // Search fields visibility settings
        $this->view->pickupLocationVisible = $this->dbSettings->getSearchFieldStatus("pickup_location", "VISIBLE");
        $this->view->pickupDateVisible = $this->dbSettings->getSearchFieldStatus("pickup_date", "VISIBLE");
        $this->view->returnLocationVisible = $this->dbSettings->getSearchFieldStatus("return_location", "VISIBLE");
        $this->view->returnDateVisible = $this->dbSettings->getSearchFieldStatus("return_date", "VISIBLE");
        $this->view->partnerVisible = $this->dbSettings->getSearchFieldStatus("partner", "VISIBLE");
        $this->view->manufacturerVisible = $this->dbSettings->getSearchFieldStatus("manufacturer", "VISIBLE");
        $this->view->bodyTypeVisible = $this->dbSettings->getSearchFieldStatus("body_type", "VISIBLE");
        $this->view->transmissionTypeVisible = $this->dbSettings->getSearchFieldStatus("transmission_type", "VISIBLE");
        $this->view->fuelTypeVisible = $this->dbSettings->getSearchFieldStatus("fuel_type", "VISIBLE");
        $this->view->existingBookingCodeVisible = $this->dbSettings->getSearchFieldStatus("booking_code", "VISIBLE");
        $this->view->couponCodeVisible = $this->dbSettings->getSearchFieldStatus("coupon_code", "VISIBLE");

        // Search fields requirement settings
        $this->view->pickupLocationRequired = $this->dbSettings->getSearchFieldStatus("pickup_location", "REQUIRED");
        $this->view->pickupDateRequired = $this->dbSettings->getSearchFieldStatus("pickup_date", "REQUIRED");
        $this->view->returnLocationRequired = $this->dbSettings->getSearchFieldStatus("return_location", "REQUIRED");
        $this->view->returnDateRequired = $this->dbSettings->getSearchFieldStatus("return_date", "REQUIRED");
        $this->view->partnerRequired = $this->dbSettings->getSearchFieldStatus("partner", "REQUIRED");
        $this->view->manufacturerRequired = $this->dbSettings->getSearchFieldStatus("manufacturer", "REQUIRED");
        $this->view->bodyTypeRequired = $this->dbSettings->getSearchFieldStatus("body_type", "VISIBLE");
        $this->view->transmissionTypeRequired = $this->dbSettings->getSearchFieldStatus("transmission_type", "REQUIRED");
        $this->view->fuelTypeRequired = $this->dbSettings->getSearchFieldStatus("fuel_type", "REQUIRED");
        $this->view->existingBookingCodeRequired = $this->dbSettings->getSearchFieldStatus("booking_code", "REQUIRED");
        $this->view->couponCodeRequired = $this->dbSettings->getSearchFieldStatus("coupon_code", "REQUIRED");
    }

    public function fillCustomerFieldsView()
    {
        // Customer fields visibility settings
        $this->view->titleVisible = $this->dbSettings->getCustomerFieldStatus("title", "VISIBLE");
        $this->view->firstNameVisible = $this->dbSettings->getCustomerFieldStatus("first_name", "VISIBLE");
        $this->view->lastNameVisible = $this->dbSettings->getCustomerFieldStatus("last_name", "VISIBLE");
        $this->view->birthdateVisible = $this->dbSettings->getCustomerFieldStatus("birthdate", "VISIBLE");
        $this->view->streetAddressVisible = $this->dbSettings->getCustomerFieldStatus("street_address", "VISIBLE");
        $this->view->cityVisible = $this->dbSettings->getCustomerFieldStatus("city", "VISIBLE");
        $this->view->stateVisible = $this->dbSettings->getCustomerFieldStatus("state", "VISIBLE");
        $this->view->zipCodeVisible = $this->dbSettings->getCustomerFieldStatus("zip_code", "VISIBLE");
        $this->view->countryVisible = $this->dbSettings->getCustomerFieldStatus("country", "VISIBLE");
        $this->view->phoneVisible = $this->dbSettings->getCustomerFieldStatus("phone", "VISIBLE");
        $this->view->emailVisible = $this->dbSettings->getCustomerFieldStatus("email", "VISIBLE");
        $this->view->commentsVisible = $this->dbSettings->getCustomerFieldStatus("comments", "VISIBLE");

        // If it is not visible, then if will not be required (function will always return false of 'required+not visible')
        $this->view->boolBirthdateRequired = $this->dbSettings->getCustomerFieldStatus("birthdate", "REQUIRED") ? TRUE : FALSE;
        $this->view->boolEmailRequired = $this->dbSettings->getCustomerFieldStatus("email", "REQUIRED") ? TRUE : FALSE;

        $this->view->titleRequired = $this->dbSettings->getCustomerFieldStatus("title", "REQUIRED") ? ' required' : '';
        $this->view->firstNameRequired = $this->dbSettings->getCustomerFieldStatus("first_name", "REQUIRED") ? ' required' : '';
        $this->view->lastNameRequired = $this->dbSettings->getCustomerFieldStatus("last_name", "REQUIRED") ? ' required' : '';
        $this->view->birthdateRequired = $this->dbSettings->getCustomerFieldStatus("birthdate", "REQUIRED") ? ' required' : '';
        $this->view->streetAddressRequired = $this->dbSettings->getCustomerFieldStatus("street_address", "REQUIRED") ? ' required' : '';
        $this->view->cityRequired = $this->dbSettings->getCustomerFieldStatus("city", "REQUIRED") ? ' required' : '';
        $this->view->stateRequired = $this->dbSettings->getCustomerFieldStatus("state", "REQUIRED") ? ' required' : '';
        $this->view->zipCodeRequired = $this->dbSettings->getCustomerFieldStatus("zip_code", "REQUIRED") ? ' required' : '';
        $this->view->countryRequired = $this->dbSettings->getCustomerFieldStatus("country", "REQUIRED") ? ' required' : '';
        $this->view->phoneRequired = $this->dbSettings->getCustomerFieldStatus("phone", "REQUIRED") ? ' required' : '';
        $this->view->emailRequired = $this->dbSettings->getCustomerFieldStatus("email", "REQUIRED") ? ' required' : '';
        $this->view->commentsRequired = $this->dbSettings->getCustomerFieldStatus("comments", "REQUIRED") ? ' required' : '';
    }

    protected function getTemplate($paramTemplateFolder, $paramTemplateName, $paramLayout = '')
    {
        $validTemplateFolder = '';
        $validTemplateName = '';
        if(!is_array($paramTemplateFolder) && $paramTemplateFolder != '')
        {
            $validTemplateFolder = preg_replace('[^-_0-9a-zA-Z]', '', $paramTemplateFolder).DIRECTORY_SEPARATOR; // No sanitization, uppercase needed
        }
        if(!is_array($paramTemplateName) && $paramTemplateName != '')
        {
            $validTemplateName = preg_replace('[^-_0-9a-zA-Z]', '', $paramTemplateName); // No sanitization, uppercase needed
        }
        $validLayout = '';
        if(in_array(ucfirst($paramLayout), array('', 'Form', 'Widget', 'Slider', 'List', 'Grid', 'Table', 'Calendar', 'Tabs')))
        {
            $validLayout = ucfirst(sanitize_key($paramLayout));
        }
        $templateFile = 'template.'.$validTemplateName.$validLayout.'.php';
        $retTemplate = $this->view->render($this->conf->getExtensionFrontTemplatesPath($validTemplateFolder.$templateFile));

        return $retTemplate;
    }
}