<?php
/**
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;
use Stripe\Stripe;
use Stripe\Charge AS StripeCharge;
use Stripe\Customer AS StripeCustomer;
use Stripe\Refund AS StripeRefund;
use Stripe\Error\Card AS StripeCardError;

class NRSStripeProcessor
{
    protected $conf 	                = NULL;
    protected $lang 		            = NULL;
    protected $debugMode 	            = 0;

    protected $currencyCode		        = 'USD';
    protected $companyName              = "";
	
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings)
    {
        // Set class settings
        $this->conf = $paramConf;
		
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;

        $this->currencyCode = StaticValidator::getValidSetting($paramSettings, 'conf_currency_code', "textval", "USD");
        $this->companyName = StaticValidator::getValidSetting($paramSettings, 'company_name', "textval", "");

        // Initialize stripe
        require_once('init.php');
    }
	
	
	/**
	 * Save stripe card
     * Based on https://stripe.com/docs/saving-cards
     * @param $paramPrivateKey
     * @param $paramBookingCode
     * @param string $paramTotalPayNow
     * @return bool
     */

    
	 
	public function saveStripeCard($paramPrivateKey, $costumerEmail){
		Stripe::setAppInfo($this->conf->getExtensionName(), $this->conf->getVersion(), "https://profiles.wordpress.org/KestutisIT");
		Stripe::setApiKey(sanitize_text_field($paramPrivateKey));
		$success = array( 'succes'=>1, 'cardID'=>'DEMO_TRANSACTION_ID');
        $sanitizedCostumerEmail = sanitize_text_field($costumerEmail);
        
		$token = isset($_POST['stripeToken']) ? sanitize_text_field($_POST['stripeToken']) : "";
		
		
		 try
        {
			$saveCard = StripeCustomer::create(array(                
                "source" => $token,
                "email" => $sanitizedCostumerEmail,
            ));
			
			$success['cardID'] = $saveCard->id;
		
		} catch(StripeCardError $e)
        {
            // The card has been declined
            $success['succes'] = 0;
        }
		
		return $success;
	}
	
	public function depositRefound($paramPrivateKey, $paramStripeChargeId){
		Stripe::setAppInfo($this->conf->getExtensionName(), $this->conf->getVersion(), "https://profiles.wordpress.org/KestutisIT");
		Stripe::setApiKey(sanitize_text_field($paramPrivateKey));
		$success = array( 'succes'=>1, 'refoundID'=>'DEMO_TRANSACTION_ID');
        $sanitizedStripeChargeId = sanitize_text_field($paramStripeChargeId);
		
        
		$token = isset($_POST['stripeToken']) ? sanitize_text_field($_POST['stripeToken']) : "";
		
		
		 try
        {

			

			$refund = StripeRefund::create([
				'charge' => $sanitizedStripeChargeId,
			]);
			$success['refoundID'] = $refund->id;
		
		} catch(StripeCardError $e)
        {
            // The card has been declined
            $success['succes'] = 0;
        }
		
		return $success;
	}
	
	public function depositRetrieve($paramPrivateKey, $paramStripeChargeId,$paramAmount){
		Stripe::setAppInfo($this->conf->getExtensionName(), $this->conf->getVersion(), "https://profiles.wordpress.org/KestutisIT");
		Stripe::setApiKey(sanitize_text_field($paramPrivateKey));
		$success = array( 'succes'=>1, 'refoundID'=>'DEMO_TRANSACTION_ID');
        $sanitizedStripeChargeId = sanitize_text_field($paramStripeChargeId);
		$sanitizedAmount = sanitize_text_field($paramAmount)*100;
		
        
		
		


			
			$refund = \Stripe\Charge::retrieve($sanitizedStripeChargeId);
		
			
			$refund->capture(array(
				"amount" => $sanitizedAmount
			));
			
			$success['refoundID'] = $refund->id;

		
		return $success;
	}
	
	public function depositPreAutorize($paramPrivateKey, $paramcustomerId,$paramDepositAmount){
		Stripe::setAppInfo($this->conf->getExtensionName(), $this->conf->getVersion(), "https://profiles.wordpress.org/KestutisIT");
		Stripe::setApiKey(sanitize_text_field($paramPrivateKey));
		$success = array( 'succes'=>1, 'depositPreAutorizeID'=>'DEMO_TRANSACTION_ID');
        $sanitizedCostumerId = sanitize_text_field($paramcustomerId);
		$sanitizedDepositAmount = sanitize_text_field($paramDepositAmount)*100;
        
		$token = isset($_POST['stripeToken']) ? sanitize_text_field($_POST['stripeToken']) : "";
		
		
		 try
        {

			
			$charge = \Stripe\Charge::create([
				'amount' => $sanitizedDepositAmount, // $200.00 this time
				'currency' => $this->currencyCode,
				'customer' => $sanitizedCostumerId, // Previously stored, then retrieved
				'description' => 'Security Deposit Hold',
				'capture' => false, // To authorize a payment without capturing it
			]);
			$success['depositPreAutorizeID'] = $charge->id;
		
		} catch(StripeCardError $e)
        {
            // The card has been declined
            $success['succes'] = 0;
        }
		
		return $success;
	}
	
	/**
     * Based on https://stripe.com/docs/charges
     * @param $paramPrivateKey
     * @param $paramBookingCode
     * @param string $paramTotalPayNow
     * @return bool
     */
	 
	
    public function process($paramPrivateKey, $paramBookingCode, $paramTotalPayNow = '0.00',$paramCustomerCard,$paramCustomerName,$paramCutomerEmail)
    {
        $success = array( 'succes'=>1, 'chargeID'=>'DEMO_TRANSACTION_ID');
        $sanitizedBookingCode = sanitize_text_field($paramBookingCode);
		$sanitizedCustomerName = sanitize_text_field($paramCustomerName);
		$sanitizedCutomerEmail = sanitize_text_field($paramCutomerEmail);
		$sanitizedCustomerCard = sanitize_text_field($paramCustomerCard);
        $validTotalPayNowInCents = intval($paramTotalPayNow * 100);

        // Are you writing a plugin that integrates Stripe and embeds our library?
        // Then please use the setAppInfo function to identify your plugin. For example:
        Stripe::setAppInfo($this->conf->getExtensionName(), $this->conf->getVersion(), "https://profiles.wordpress.org/KestutisIT");

        // Set your secret key: remember to change this to your live secret key in production
        // See your keys here: https://dashboard.stripe.com/account/apikeys
        Stripe::setApiKey(sanitize_text_field($paramPrivateKey));

        // Get the credit card details submitted by the form
        $token = isset($_POST['stripeToken']) ? sanitize_text_field($_POST['stripeToken']) : "";
		
		
		
		
        // Create a charge: this will charge the user's card
        try
        {
           
			
			// Charge the Customer instead of the card:
			$charge = StripeCharge::create([
			'amount' => $validTotalPayNowInCents, // 120.00
			'currency' => $this->currencyCode,
			'customer' => $sanitizedCustomerCard,
			'description'=>'Orcas Island Car Rentals reservation: '.$sanitizedBookingCode,
			'statement_descriptor' => 'OrcasCars.com',
			'metadata' => [
				'reservation_id' => $sanitizedBookingCode,
				'customer_name'=> $sanitizedCustomerName,
				'customer_email'=> $sanitizedCutomerEmail,
			],
			]);

            /** Return is object, with demo response.
             * Example is from here: https://stripe.com/docs/api#metadata
             * When call is Stripe charges v1:
             * $ curl https://api.stripe.com/v1/charges \
            -u sk_test_BQokikJOvBiI2HlWgH4olfQ2: \
            -d amount=2000 \
            -d currency=usd \
            -d source=tok_189faZ2eZvKYlo2CqFhJ2iLh \
            -d metadata[order_id]=6735
             * Then example response is the following:
            Example Response{
            "id": "ch_19F41L2eZvKYlo2CyzjX9Q8r",
            "object": "charge",
            "amount": 100,
            "amount_refunded": 0,
            "application": null,
            "application_fee": null,
            "balance_transaction": "txn_18tjj22eZvKYlo2CeFxM3FxI",
            "captured": true,
            "created": 1478966783,
            "currency": "usd",
            "customer": null,
            "description": null,
            "destination": null,
            "dispute": null,
            "failure_code": null,
            "failure_message": null,
            "fraud_details": {
            },
            "invoice": null,
            "livemode": false,
            "metadata": {
            "order_id": "6735"
            },
            "order": null,
            "outcome": {
            "network_status": "approved_by_network",
            "reason": null,
            "risk_level": "normal",
            "seller_message": "Payment complete.",
            "type": "authorized"
            },
            "paid": true,
            "receipt_email": null,
            "receipt_number": null,
            "refunded": false,
            "refunds": {
            "object": "list",
            "data": [
            ],
            "has_more": false,
            "total_count": 0,
            "url": "/v1/charges/ch_19F41L2eZvKYlo2CyzjX9Q8r/refunds"
            },
            "review": null,
            "shipping": null,
            "source": {
            "id": "card_19F41K2eZvKYlo2CNnNawij1",
            "object": "card",
            "address_city": null,
            "address_country": null,
            "address_line1": null,
            "address_line1_check": null,
            "address_line2": null,
            "address_state": null,
            "address_zip": null,
            "address_zip_check": null,
            "brand": "Visa",
            "country": "US",
            "customer": null,
            "cvc_check": "pass",
            "dynamic_last4": null,
            "exp_month": 10,
            "exp_year": 2017,
            "funding": "credit",
            "last4": "4242",
            "metadata": {
            },
            "name": null,
            "tokenization_method": null
            },
            "source_transfer": null,
            "statement_descriptor": null,
            "status": "succeeded"
            }
             */
			 $success['chargeID'] = $charge->id;
			 
        } catch(StripeCardError $e)
        {
            // The card has been declined
            $success['succes'] = 0;
        }

        return $success;
    }
	

}
