<?php
/**
 * Ajax processor

 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
 /*
require_once ('Models/interface.iRootObserver.php');
require_once ('Models/Configuration/interface.iConfiguration.php');
require_once ('Models/Configuration/interface.iCoreConfiguration.php');
require_once ('Models/Configuration/class.CoreConfiguration.php');
require_once ('Models/Configuration/interface.iExtensionConfiguration.php');
require_once ('Models/Configuration/class.ExtensionConfiguration.php');
require_once ('Models/Language/interface.iLanguage.php');
require_once ('Models/Language/class.LanguagesObserver.php');
require_once ('Models/Language/class.Language.php');




require_once ('Models/Load/class.AutoLoad.php');
require_once ('Controllers/class.AbstractController.php');
require_once ('Controllers/class.MainController.php');

require_once ('Models/interface.iElement.php');
require_once ('Models/class.AbstractElement.php');
require_once ('Models/PaymentMethod/class.PaymentMethod.php');

require_once ('Models/Booking/class.Booking.php');
require_once ('Models/Booking/class.Invoice.php');*/



add_action( 'wp_ajax_nopriv_release_all_founds', 'release_all_founds' );
add_action( 'wp_ajax_release_all_founds', 'release_all_founds' );

function release_all_founds() {
	require_once('Libraries/NRSStripe/NRSStripeProcessor.php');

	
	
	$bookingID=$_POST['bookingID'];
	$bookingSecretKey=$_POST['bookingSecretKey'];
	$stripeChargeId=$_POST['stripeChargeId'];
	$globalLangPath="NativeRentalSystem/Extensions/CarRental/Languages";
	$extensionLangPath="NativeRentalSystem/Extensions/CarRental/Languages";


	$language= new \NativeRentalSystem\Models\Language\Language('car-rental-system',$globalLangPath,$extensionLangPath);
	global $objConfiguration;
	

	$extensionConfiguration= new \NativeRentalSystem\Models\Configuration\ExtensionConfiguration($objConfiguration);
	
	$objBooking = new \NativeRentalSystem\Models\Booking\Booking($extensionConfiguration,$language,array(),'aaaaaa');
	$stripeProcessor = new NRSStripeProcessor($extensionConfiguration,$language,array("conf_currency_code"=>"USD","company_name"=>"Oscar Island Car Rental"));
	
	
	$isSucces=$stripeProcessor->depositRefound($bookingSecretKey,$stripeChargeId);
	

	
	if($isSucces['succes']===1){
		$updateTransactionId=$objBooking->preAutorizeRefound($bookingID,NULL);
		$updateRetrieveFields=$objBooking->preAutorizeRetrieve($bookingID,'refound');
		die(json_encode(array('message' => 'success', 'code' => 1337)));
	}
	

	
}

add_action( 'wp_ajax_nopriv_make_deposit_charge', 'make_deposit_charge' );
add_action( 'wp_ajax_make_deposit_charge', 'make_deposit_charge' );


function make_deposit_charge() {
	require_once('Libraries/NRSStripe/NRSStripeProcessor.php');
	
	
	
	$bookingID=$_POST['bookingID'];
	$bookingSecretKey=$_POST['bookingSecretKey'];
	$stripeChargeId=$_POST['stripeChargeId'];
	$fields=$_POST['fields'];
	
	
	
	
	$summedRetrieveAmount=0;
	foreach($fields as $key=>$value){
		$summedRetrieveAmount+=(int)$value;
		if(empty($value)){
			die(json_encode(array('message' => 'ERROR', 'code' => 1337)));
		}
	}
	
	$fields['Incidentals Charge Total']=$_POST['customerCharged'];
	$fields['publicNotes']=$_POST['publicNotes'];
	$fieldsEncoded=json_encode($fields);
	
	$globalLangPath="NativeRentalSystem/Extensions/CarRental/Languages";
	$extensionLangPath="NativeRentalSystem/Extensions/CarRental/Languages";


	$language= new \NativeRentalSystem\Models\Language\Language('car-rental-system',$globalLangPath,$extensionLangPath);
	global $objConfiguration;
	

	$extensionConfiguration= new \NativeRentalSystem\Models\Configuration\ExtensionConfiguration($objConfiguration);
	
	$objBooking = new \NativeRentalSystem\Models\Booking\Booking($extensionConfiguration,$language,array(),'aaaaaa');
	
	$stripeProcessor = new NRSStripeProcessor($extensionConfiguration,$language,array("conf_currency_code"=>"USD","company_name"=>"Oscar Island Car Rental"));
	
	
	$isSucces=$stripeProcessor->depositRetrieve($bookingSecretKey,$stripeChargeId,$summedRetrieveAmount);
	

	
	if($isSucces['succes']===1){
		$updateTransactionId=$objBooking->preAutorizeRefound($bookingID,NULL);
		$updateRetrieveFields=$objBooking->preAutorizeRetrieve($bookingID,$fieldsEncoded);
		
	}
	
	
	
}