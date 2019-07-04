<?php
/**
 * Deposit processor

 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
define('WP_USE_THEMES', false);
$path =dirname(dirname(dirname(dirname(__FILE__))));

include_once $path . '/wp-blog-header.php';

require_once ('Models/interface.iRootObserver.php');
require_once ('Models/Configuration/interface.iConfiguration.php');
require_once ('Models/Configuration/interface.iCoreConfiguration.php');
require_once ('Models/Configuration/class.CoreConfiguration.php');
require_once ('Models/Configuration/interface.iExtensionConfiguration.php');
require_once ('Models/Configuration/class.ExtensionConfiguration.php');
require_once ('Models/Language/interface.iLanguage.php');
require_once ('Models/Language/class.LanguagesObserver.php');
require_once ('Models/Language/class.Language.php');
/*
require_once ('Models/PaymentResource/class.iPaymentResource.php');
*/
require_once ('Libraries/NRSStripe/NRSStripeProcessor.php');


require_once ('Models/Load/class.AutoLoad.php');
require_once ('Controllers/class.AbstractController.php');
require_once ('Controllers/class.MainController.php');

require_once ('Models/interface.iElement.php');
require_once ('Models/class.AbstractElement.php');
require_once ('Models/PaymentMethod/class.PaymentMethod.php');

require_once ('Models/Booking/class.Booking.php');
require_once ('Models/Booking/class.Invoice.php');


global $wpdb;
$objConfiguration = new \NativeRentalSystem\models\Configuration\CoreConfiguration(
    $wpdb, /* DATABASE OBJECT REFERENCE */
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
 
 /*get all the bookings in the future*/
 
 
 

$timeAtTheMoment=time();
$timeAtTheMomentPlusDay=$timeAtTheMoment+24*3600;


			$query="
				SELECT *
				FROM {$wpdb->prefix}car_rental_bookings
				WHERE pickup_timestamp>'{$timeAtTheMoment}' AND pickup_timestamp<'{$timeAtTheMomentPlusDay}' AND is_block='0'";
				

$results = $wpdb->get_results($query);
			



$globalLangPath="NativeRentalSystem/Extensions/CarRental/Languages";
$extensionLangPath="NativeRentalSystem/Extensions/CarRental/Languages";


$language= new \NativeRentalSystem\Models\Language\Language('car-rental-system',$globalLangPath,$extensionLangPath);


$extensionConfiguration= new \NativeRentalSystem\Models\Configuration\ExtensionConfiguration($objConfiguration);


$objBooking = new \NativeRentalSystem\Models\Booking\Booking($extensionConfiguration,$language,array(),'aaaaaa');



$objPaymentMethod = new \NativeRentalSystem\Models\PaymentMethod\PaymentMethod($extensionConfiguration,$language,array(), 2);
$paymentMethodDetails = $objPaymentMethod->getDetails();
$stripeProcessor = new NRSStripeProcessor($extensionConfiguration,$language,array("conf_currency_code"=>"USD","company_name"=>"Oscar Island Car Rental"));


foreach($results as $result){
	if($result->deposit_charge_id==NULL&&$result->deposit_retrieve_fields==NULL){
		$costumer=$objBooking->getPaymentUser($result->customer_id);
		$objInvoice = new \NativeRentalSystem\Models\Booking\Invoice($extensionConfiguration,$language,array(),$result->booking_id);
		$invoiceDetails = $objInvoice->getDetails();
		
		$price=$invoiceDetails['print_fixed_deposit_amount'];
		$price=str_ireplace('$','',$price);
		$price=str_ireplace(' ','',$price);
		$price=(int)$price;
		
		$isSucces=$stripeProcessor->depositPreAutorize($paymentMethodDetails['private_key'],$costumer['cotumer_stripe_id'],$price);
		
		if($isSucces['succes']===1){
			$updateTransactionId=$objBooking->preAutorize($result->booking_id,$isSucces['depositPreAutorizeID']);
		}

	}
}

