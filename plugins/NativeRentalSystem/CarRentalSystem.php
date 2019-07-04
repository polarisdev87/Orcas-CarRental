<?php
/**
 * Plugin Name: Car Rental System
 * Plugin URI: http://nativerental.com/
 * Description: Native WordPress plugin for renting cars online
 * Version: 5.0
 * Author: Kęstutis Matuliauskas
 * Author URI: https://profiles.wordpress.org/KestutisIT
 * Text Domain: car-rental-system
 */
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );

// Require mandatory models
require_once ('Models/Configuration/interface.iConfiguration.php');
require_once ('Models/Configuration/interface.iCoreConfiguration.php');
require_once ('Models/Configuration/class.CoreConfiguration.php');

// Require autoloader and main NRS controller
require_once ('Models/Load/class.AutoLoad.php');
require_once ('Controllers/class.AbstractController.php');
require_once ('Controllers/class.MainController.php');


// Create an instance of NRS configuration model
$objConfiguration = new \NativeRentalSystem\models\Configuration\CoreConfiguration(
    $GLOBALS['wpdb'], /* DATABASE OBJECT REFERENCE */
    get_current_blog_id(), /* BLOG ID FOR MULTISITE */
    '5.3.0', /* REQUIRED PHP VERSION */
    phpversion(), /* CURRENT PHP VERSION */
    4.6, /* REQUIRED WORDPRESS VERSION */
    $GLOBALS['wp_version'], /* CURRENT WORDPRESS VERSION */
    5.0, /* NRS PLUGIN VERSION */
    'Car Rental', /* EXTENSION NAME */
    'CarRental', /* EXTENSION FOLDER */
    'carRental', /* EXTENSION VARIABLE PREFIX FOLDER */
    'car_rental_', /* EXTENSION PREFIX*/
    'car-rental-', /* EXTENSION URL PREFIX */
    'car_rental_system', /* EXTENSION SHORTCODE */
    'car', /* EXTENSION ITEM PARAMETER */
    'cars', /* EXTENSION ITEM PLURAL PARAMETER */
    'manufacturer', /* EXTENSION MANUFACTURER PARAMETER */
    'manufacturers', /* EXTENSION MANUFACTURER PLURAL PARAMETER */
    'body_type', /* EXTENSION BODY TYPE PARAMETER */
    'transmission_type', /* EXTENSION TRANSMISSION TYPE PARAMETER */
    'fuel_type', /* EXTENSION FUEL TYPE PARAMETER */
    'car-rental-system', /* EXTENSION TEXT DOMAIN */
    'CarRentalGallery', /* EXTENSION UPLOADS FOLDER */
    __FILE__
);

// Create an instance of NRS main controller
// Note: We do not use singleton pattern here, because we want to allow load different plugin configurations
$GLOBALS['obj_crs'] = new \NativeRentalSystem\Controllers\MainController($objConfiguration);

// Run the plugin
$GLOBALS['obj_crs']->run();

// Register plugin uninstall hook
// Note: this has to be separated from all dynamic classes and objects, because uninstall hook can be called in static context only!
register_uninstall_hook(__FILE__, 'uninstall__CarRentalSystem');

/**
 * Drop all tables if exists in static context
 * @global $wpdb
 */
function uninstall__CarRentalSystem()
{
    $GLOBALS['obj_crs']->uninstall();
}

require_once ('ajaxDeposit.php');