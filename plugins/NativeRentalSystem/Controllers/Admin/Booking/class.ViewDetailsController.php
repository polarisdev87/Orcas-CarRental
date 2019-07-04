<?php
/**
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Admin\Booking;
use NativeRentalSystem\Controllers\Admin\AbstractController;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Booking\Booking;
use NativeRentalSystem\Models\Booking\Invoice;
use NativeRentalSystem\Models\Customer\Customer;

final class ViewDetailsController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    public function getContent()
    {
        $paramBookingId = isset($_GET['booking_id']) ? $_GET['booking_id'] : 0;
        $objBooking = new Booking($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramBookingId);
        $objInvoice = new Invoice($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramBookingId);
        $objCustomer = new Customer($this->conf, $this->lang, $this->dbSettings->getSettings(), $objBooking->getCustomerId());
        $localDetails = $objBooking->getDetails();
        $localInvoiceDetails = $objInvoice->getDetails();
        $localCustomerDetails = $objCustomer->getDetails();
		$localInvoiceDetailsTemp=$localInvoiceDetails['invoice'];
		if($localDetails['deposit_retrieve_fields']===NULL&&$localDetails['deposit_charge_id']===NULL){
			
		}else if($localDetails['deposit_charge_id']==NULL&&$localDetails['deposit_retrieve_fields']=='refound'){
			
			
			$htmlFields='';
			
					$htmlFields.='<tr style="background-color:#FFFFFF">';
					$htmlFields.='<td align="right"  style="color:green; padding-right:5px" colspan="2">Deposit Released';
					$htmlFields.='</td><td align="right"  style="color:green;  padding-right:5px">';
					$htmlFields.=$localInvoiceDetails['print_fixed_deposit_amount'];
				
				
				
				$htmlFields.='</td>	</tr>';
			
			$myScript="
				<script>
					jQuery( document ).ready(function() {
						jQuery('#wpbody-content table:nth-of-type(3) tr:last-child').remove();
						jQuery('#wpbody-content table:nth-of-type(3)').append('.$htmlFields.');
						
					});
				</script>
			";
			
			
			$localInvoiceDetailsTemp.=$myScript;
		}else if($localDetails['deposit_charge_id']==NULL&&$localDetails['deposit_retrieve_fields']!=NULL){
			$stripeFields=json_decode($localDetails['deposit_retrieve_fields']);
			$htmlFields='';
			$htmlFields='';
			
			foreach($stripeFields as $key=>$value){
				
				if($key=='publicNotes'){
					$htmlFields.='<tr  colspan="3" style="display:block; position:absolute; right:0; bottom:-3px; width:840px; padding-top:18px; background-color:#f1f1f1;">';
					$htmlFields.='<td colspan="3" align="left"  style="display:block; border:1px solid #000; font-weight:bold; font-family:Verdana, Geneva, sans-serif;font-size: 12px;background:#eeeeee; padding-right:5px" colspan="2">Incidentals Charge Message:';
					$htmlFields.='</td><td align="left" colspan="3" class="" style="border:1px solid #000; border-top:0; min-height: 16px; display:block;padding-right:5px; background-color:#fff;">';
					$htmlFields.=$value;
				}else{
					$htmlFields.='<tr style="background-color:#FFFFFF">';
					$htmlFields.='<td align="right"  style="color:red; padding-right:5px" colspan="2">'.$key;
					$htmlFields.='</td><td align="right"  style="color:red;  padding-right:5px">';
					$htmlFields.=$value.'$';
				}
				
				
				$htmlFields.='</td>	</tr>';
			}
			
			
			
			
			
			$myScript="
				<script>
					jQuery( document ).ready(function() {
						jQuery('#wpbody-content table:nth-of-type(3) tr:last-child').remove();
						jQuery('#wpbody-content table:nth-of-type(3)').append('.$htmlFields.');
						var height=jQuery('#wpbody-content table:nth-of-type(3) tr:last-child').height();
						jQuery('#wpbody-content table:nth-of-type(3)').css('padding-bottom', height+14);
						
						jQuery('#wpbody-content table:nth-of-type(3)').css('position', 'relative');
					});
				</script>
			";
		
			
			$localInvoiceDetailsTemp.=$myScript;
		}
        if($objBooking->canEdit() == FALSE)
        {
            // Current user is not allowed to edit current booking
            // Note - we don't use here wp_safe_redirect, because headers already sent, so we have to use a redirect Javascript code in content
            $redirectToPage = admin_url('admin.php?page='.$this->conf->getURLPrefix().'booking-manager&tab=bookings');
            echo '<script type="text/javascript">window.location="'.$redirectToPage.'"</script>';
            exit;
        }

        // Set the view variables - Customer fields visibility settings
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

        // Set the view variables - other
        if(!is_null($localDetails) && !is_null($localInvoiceDetails))
        {
            $this->view->bookingCode = $localDetails['booking_code'];
            $this->view->couponCode = $localDetails['coupon_code'];
            $this->view->paymentStatus = $localDetails['print_payment_status'];
            $this->view->paymentStatusColor = $localDetails['booking_status_color'];
            $this->view->bookingStatus = $localDetails['print_booking_status'];
            $this->view->bookingStatusColor = $localDetails['payment_status_color'];
			
			
			
            $this->view->invoiceHTML = $localInvoiceDetailsTemp;
        } else
        {
            $this->view->bookingCode = '';
            $this->view->couponCode = '';
            $this->view->paymentStatus = '';
            $this->view->paymentStatusColor = 'black';
            $this->view->bookingStatus = '';
            $this->view->bookingStatusColor = 'black';
            $this->view->invoiceHTML = '';
        }

        if(!is_null($localCustomerDetails))
        {
            $this->view->backToListURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'booking-search-results&customer_id='.$localCustomerDetails['customer_id']);
            $this->view->customerId = $localCustomerDetails['customer_id'];
            $this->view->fullName = $localCustomerDetails['print_full_name'];
            $this->view->birthdate = $localCustomerDetails['birthdate'];
            $this->view->streetAddress = $localCustomerDetails['print_street_address'];
            $this->view->city = $localCustomerDetails['print_city'];
            $this->view->state = $localCustomerDetails['print_state'];
            $this->view->zipCode = $localCustomerDetails['print_zip_code'];
            $this->view->country = $localCustomerDetails['print_country'];
            $this->view->phone = $localCustomerDetails['print_phone'];
            $this->view->email = $localCustomerDetails['print_email'];
            $this->view->comments = $localCustomerDetails['print_comments'];
        } else
        {
            $this->view->backToListURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'booking-search-results&customer_id=0');
            $this->view->customerId = 0;
            $this->view->fullName = '';
            $this->view->birthdate = '';
            $this->view->streetAddress = '';
            $this->view->city = '';
            $this->view->state = '';
            $this->view->zipCode = '';
            $this->view->country = '';
            $this->view->phone = '';
            $this->view->email = '';
            $this->view->comments = '';
        }

        // Get the template
        $retContent = $this->getTemplate('Booking', 'ViewDetails', 'Table');

        return $retContent;
    }
}
