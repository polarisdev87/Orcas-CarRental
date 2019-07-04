<?php
/**
 * NRS Initializer class to load front-end
 * Final class cannot be inherited anymore. We use them when creating new instances
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Front;
use NativeRentalSystem\Controllers\Front\Shortcodes\BenefitsController;
use NativeRentalSystem\Controllers\Front\Shortcodes\LocationsController;
use NativeRentalSystem\Controllers\Front\Shortcodes\ManufacturersController;
use NativeRentalSystem\Controllers\Front\Shortcodes\SingleLocationController;
use NativeRentalSystem\Controllers\Front\Shortcodes\ExtrasAvailabilityController;
use NativeRentalSystem\Controllers\Front\Shortcodes\ExtrasPriceController;
use NativeRentalSystem\Controllers\Front\Shortcodes\ItemsAvailabilityController;
use NativeRentalSystem\Controllers\Front\Shortcodes\ItemsController;
use NativeRentalSystem\Controllers\Front\Shortcodes\ItemsPriceController;
use NativeRentalSystem\Controllers\Front\Shortcodes\SearchController;
use NativeRentalSystem\Controllers\Front\Shortcodes\SingleItemController;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Style\Style;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;

final class LoadController
{
    protected static $mandatoryStylesEnqueued   = FALSE;
    protected static $scriptsLoaded             = FALSE;
    protected static $stylesLoaded              = FALSE;
    private $conf 	                            = NULL;
    private $lang 		                        = NULL;
    private $debugMode 	                        = 0;

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

	public function registerScripts()
	{
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

        // wp_deregister_script( 'jquery.mousewheel' ); // we may leave it
        wp_register_script(
            'jquery.mousewheel', $this->conf->getExtensionFrontJSURL('jquery.mousewheel.js'),
            array('jquery')
        );

        // wp_deregister_script( 'jquery.validate' ); // we may leave it
        wp_register_script(
            'jquery-validate', $this->conf->getExtensionFrontJSURL('jquery.validate.js'),
            array('jquery')
        );

        wp_register_script(
            'slick-slider', $this->conf->getExtensionFrontJSURL('slick.min.js'),
            array('jquery')
        );

        wp_register_script(
            'fancybox', $this->conf->getExtensionFrontJSURL('jquery.fancybox.pack.js'),
            array('jquery')
        );

        $placeScriptInFooter = TRUE;
        wp_register_script(
            $this->conf->getURLPrefix().'frontend', $this->conf->getExtensionFrontJSURL('FrontEnd.js'),
            array('jquery'), '1.0', $placeScriptInFooter
        );
	}

    public function enqueueMandatoryFrontEndStyles()
    {
        $styleSql = "SELECT conf_value AS conf_system_style
            FROM {$this->conf->getPrefix()}settings
            WHERE conf_key='conf_system_style' AND blog_id='{$this->conf->getBlogId()}'
        ";
        $styleSetting = $this->conf->getInternalWPDB()->get_var($styleSql);
        $styleName = !is_null($styleSetting) ? $styleSetting : '';

        $objStyle = new Style($this->conf, $this->lang, $styleName);
        // Set global and compatibility styles
        $objStyle->setGlobalStyles();
        $parentThemeCompatibilityCSSFileURL = $objStyle->getParentThemeCompatibilityCSSURL();
        $currentThemeCompatibilityCSSFileURL = $objStyle->getCurrentThemeCompatibilityCSSURL();
        $globalCSSFileURL = $objStyle->getGlobalCSSURL();

        if($this->lang->isRTL())
        {
            // Add .rtl body class, then we will able to set different styles for rtl version
            add_filter( 'body_class', function( $classes ) {
                return array_merge( $classes, array( 'rtl' ) );
            } );
        }

        // Register compatibility styles for further use
        if($parentThemeCompatibilityCSSFileURL != '')
        {
            wp_register_style($this->conf->getURLPrefix().'parent-theme-frontend-compatibility', $parentThemeCompatibilityCSSFileURL);
        }
        if($currentThemeCompatibilityCSSFileURL != '')
        {
            wp_register_style($this->conf->getURLPrefix().'current-theme-frontend-compatibility', $currentThemeCompatibilityCSSFileURL);
        }

        // Register plugin global style for further use
        if($globalCSSFileURL != '')
        {
            wp_register_style($this->conf->getURLPrefix().'frontend-global', $globalCSSFileURL);
        }

        // As these styles are mandatory, enqueue them here
        wp_enqueue_style($this->conf->getURLPrefix().'parent-theme-frontend-compatibility');
        wp_enqueue_style($this->conf->getURLPrefix().'current-theme-frontend-compatibility');
        wp_enqueue_style($this->conf->getURLPrefix().'frontend-global');

    }

    public function registerStyles()
	{
        $styleSql = "SELECT conf_value AS conf_system_style
            FROM {$this->conf->getPrefix()}settings
            WHERE conf_key='conf_system_style' AND blog_id='{$this->conf->getBlogId()}'
        ";
        $styleSetting = $this->conf->getInternalWPDB()->get_var($styleSql);
        $styleName = !is_null($styleSetting) ? $styleSetting : '';

        $objStyle = new Style($this->conf, $this->lang, $styleName);
        // Set local system styles
        $objStyle->setLocalStyles();
        $systemStyleCSSFileURL = $objStyle->getSystemCSSURL();

        // Register styles for further use (register even it the file is '' - WordPress will process that as needed)
        wp_register_style('font-awesome', $this->conf->getExtensionFrontCSSURL('FontAwesome.css'));
        wp_register_style('fancybox', $this->conf->getExtensionFrontCSSURL('jquery.fancybox.css'));
        wp_register_style('datepicker', $this->conf->getExtensionFrontCSSURL('DatePicker.css'));
        wp_register_style('slick-slider', $this->conf->getExtensionFrontCSSURL('slick.css'));
        wp_register_style('slick-theme', $this->conf->getExtensionFrontCSSURL('slick-theme.css'));

        // Register plugin design style for further use
        if($systemStyleCSSFileURL != '')
        {
            wp_register_style($this->conf->getURLPrefix().'frontend', $systemStyleCSSFileURL);
        }
	}

    public function parseShortcode($attributes)
    {
        //print_r($attributes);
        $itemParameter = $this->conf->getItemParameter();
        $itemPluralParameter = $this->conf->getItemPluralParameter();
        $validItemId = isset($attributes[$itemParameter]) ? StaticValidator::getValidInteger($attributes[$itemParameter], -1) : -1;
        $validExtraId = isset($attributes['extra']) ? StaticValidator::getValidInteger($attributes['extra'], -1) : -1;
        $validLocationId = isset($attributes['location']) ? StaticValidator::getValidInteger($attributes['location'], -1) : -1;
        $validPickupLocationId = isset($attributes['pickup_location']) ? StaticValidator::getValidInteger($attributes['pickup_location'], -1) : -1;
        $validReturnLocationId = isset($attributes['return_location']) ? StaticValidator::getValidInteger($attributes['return_location'], -1) : -1;
        $validPartnerId = isset($attributes['partner']) ? StaticValidator::getValidInteger($attributes['partner'], -1) : -1;
        $validManufacturerId = isset($attributes['manufacturer']) ? StaticValidator::getValidInteger($attributes['manufacturer'], -1) : -1;
        $validBodyTypeId = isset($attributes['body_type']) ? StaticValidator::getValidInteger($attributes['body_type'], -1) : -1;
        $validTransmissionTypeId = isset($attributes['transmission_type']) ? StaticValidator::getValidInteger($attributes['transmission_type'], -1) : -1;
        $validFuelTypeId = isset($attributes['fuel_type']) ? StaticValidator::getValidInteger($attributes['fuel_type'], -1) : -1;
        $validActionPageId = isset($attributes['action_page']) ? StaticValidator::getValidPositiveInteger($attributes['action_page'], 0) : 0;
        $sanitizedDisplay = isset($attributes['display']) ? sanitize_key($attributes['display']) : "search";
        $sanitizedLayout = isset($attributes['layout']) ? ucfirst(sanitize_key($attributes['layout'])) : "";
        $sanitizedStepsLayoutArray = array();
        if(isset($attributes['steps']))
        {
            $paramStepsLayout = explode(",", $attributes['steps']);
            foreach($paramStepsLayout AS $paramLayout)
            {
                $sanitizedStepsLayoutArray[] = ucfirst(sanitize_key($paramLayout));
            }
        }

        $arrLimitations = array(
            "item_id" => $validItemId,
            "extra_id" => $validExtraId,
            "location_id" => $validLocationId,
            "pickup_location_id" => $validPickupLocationId,
            "return_location_id" => $validReturnLocationId,
            "partner_id" => $validPartnerId,
            "manufacturer_id" => $validManufacturerId,
            "body_type_id" => $validBodyTypeId,
            "transmission_type_id" => $validTransmissionTypeId,
            "fuel_type_id" => $validFuelTypeId,
            "action_page_id" => $validActionPageId,
        );

        // Render the page HTML to output buffer cache
        switch($sanitizedDisplay)
        {
            case "search":
                // Create instance and render search
                $objSearchController = new SearchController($this->conf, $this->lang);
                $retContent = $objSearchController->getNewContent($sanitizedStepsLayoutArray, $arrLimitations);
                break;

            case "edit":
                // Create instance and render search edit
                $objSearchController = new SearchController($this->conf, $this->lang);
                $retContent = $objSearchController->getEditContent($sanitizedStepsLayoutArray, $arrLimitations);
                break;

            case $itemParameter:
                // Create instance and render single item page (i.e. 'car')
                $objItemController = new SingleItemController($this->conf, $this->lang, $arrLimitations);
                $retContent = $objItemController->getContent($sanitizedLayout);
                break;

            case "location":
                // Create instance and render single location page. Use location_id here
                $objLocationController = new SingleLocationController($this->conf, $this->lang, $arrLimitations);
                $retContent = $objLocationController->getContent($sanitizedLayout);
                break;

            case $itemPluralParameter:
                // Create instance and render item list or items slider (i.e. 'cars')
                $objItemsController = new ItemsController($this->conf, $this->lang, $arrLimitations);
                $retContent = $objItemsController->getContent($sanitizedLayout);
                break;

            case "locations":
                // Create instance and render location list
                $objLocationsController = new LocationsController($this->conf, $this->lang, $arrLimitations);
                $retContent = $objLocationsController->getContent($sanitizedLayout);
                break;

            case "manufacturers":
                // Create instance and render manufacturer slider
                $obManufacturersController = new ManufacturersController($this->conf, $this->lang, $arrLimitations);
                $retContent = $obManufacturersController->getContent($sanitizedLayout);
                break;

            case "benefits":
                // Create instance and render benefits slider
                $objBenefitsController = new BenefitsController($this->conf, $this->lang, $arrLimitations);
                $retContent = $objBenefitsController->getContent($sanitizedLayout);
                break;

            case "prices":
                // Create instance and render items price table
                $objPricesController = new ItemsPriceController($this->conf, $this->lang, $arrLimitations);
                $retContent = $objPricesController->getContent($sanitizedLayout);
                break;

            case "extra_prices":
                // Create instance and render extras price table
                $objPricesController = new ExtrasPriceController($this->conf, $this->lang, $arrLimitations);
                $retContent = $objPricesController->getContent($sanitizedLayout);
                break;

            case "availability":
                // Create instance and render items calendar
                $objAvailabilityController = new ItemsAvailabilityController($this->conf, $this->lang, $arrLimitations);
                $retContent = $objAvailabilityController->getContent($sanitizedLayout);
                break;

            case "extras_availability":
                // Create instance and render extras calendar
                $objAvailabilityController = new ExtrasAvailabilityController($this->conf, $this->lang, $arrLimitations);
                $retContent = $objAvailabilityController->getContent($sanitizedLayout);
                break;

            default:
                // Do nothing
                $retContent = '';
        }

        // Return page content to shortcode
        return $retContent;
    }
}