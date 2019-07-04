<?php
/**

 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
*/
namespace NativeRentalSystem\Models\EMail;
use NativeRentalSystem\Models\Booking\Booking;
use NativeRentalSystem\Models\Booking\Invoice;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\iObserver;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Location\Location;
use NativeRentalSystem\Models\Location\LocationsObserver;
use NativeRentalSystem\Models\Validation\StaticValidator;

class EMailsObserver implements iObserver
{
    protected $savedDebugMessages           = array();
    protected $savedOkayMessages            = array();
    protected $savedErrorMessages           = array();
    protected $conf 	                    = NULL;
    protected $lang 		                = NULL;
    protected $debugMode 	                = 0;
    protected $settings                     = array();
    protected $companyEmail 	            = "";
    protected $sendCompanyNotificationEmails= FALSE;

	public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings)
	{
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;
        // Set saved settings
        $this->settings = $paramSettings;

        $this->companyEmail = StaticValidator::getValidSetting($paramSettings, 'conf_company_email', "email", "");
        if(isset($paramSettings['conf_company_notification_emails']))
        {
            $this->sendCompanyNotificationEmails = $paramSettings['conf_company_notification_emails'] == 1 ? TRUE : FALSE;
        }
	}

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    /**
     * Class-specific method
     * @return array
     */
    public function getSavedDebugMessages()
    {
        return $this->savedDebugMessages;
    }

    /**
     * Class-specific method
     * @return array
     */
    public function getSavedOkayMessages()
    {
        return $this->savedOkayMessages;
    }

    /**
     * Class-specific method
     * @return array
     */
    public function getSavedErrorMessages()
    {
        return $this->savedErrorMessages;
    }

    public function getIdByType($paramEmailType)
    {
        $retEmailId = 0;
        $validEmailType = StaticValidator::getValidPositiveInteger($paramEmailType, 0); // For sql query only

        $emailData = $this->conf->getInternalWPDB()->get_row("
                SELECT email_id
                FROM {$this->conf->getPrefix()}emails
                WHERE email_type='{$validEmailType}' AND blog_id='{$this->conf->getBlogId()}'
            ", ARRAY_A);
        if(!is_null($emailData))
        {
            $retEmailId = $emailData['email_id'];
        }

        return $retEmailId;
    }

    public function getAllIds()
    {
        $locationIds = $this->conf->getInternalWPDB()->get_col("
            SELECT email_id
            FROM {$this->conf->getPrefix()}emails
            WHERE blog_id='{$this->conf->getBlogId()}'
            ORDER BY email_type ASC
        ");

        return $locationIds;
    }


    /* --------------------------------------------------------------------------------- */
    /* Emails sending                                                                    */
    /* --------------------------------------------------------------------------------- */

    public function sendBookingDetailsEmail($paramBookingId, $paramSendNotificationToAdmin = TRUE, $paramEmailWidth = 840)
    {
        // DETAILS
        return $this->sendBookingEmail(1, 4, $paramBookingId, $paramSendNotificationToAdmin, $paramEmailWidth);
    }

    public function sendBookingConfirmationEmail($paramBookingId, $paramSendNotificationToAdmin = TRUE, $paramEmailWidth = 840)
    {
        // CONFIRMED
        return $this->sendBookingEmail(2, 5, $paramBookingId, $paramSendNotificationToAdmin, $paramEmailWidth);
    }

    public function sendBookingCancellationEmail($paramBookingId, $paramSendNotificationToAdmin = TRUE, $paramEmailWidth = 840)
    {
        // CANCELLED
        return $this->sendBookingEmail(3, 6, $paramBookingId, $paramSendNotificationToAdmin, $paramEmailWidth);
    }

    /**
     * @param $paramEmailType
     * @param $paramNotifyEmailType
     * @param $paramBookingId
     * @param bool $paramSendNotificationToAdmin
     * @param int $paramEmailWidth
     * @return bool
     */
    public function sendBookingEmail($paramEmailType, $paramNotifyEmailType, $paramBookingId, $paramSendNotificationToAdmin = TRUE, $paramEmailWidth = 840)
    {
        // CANCELLED
        $sent = FALSE;

        $objLocationsObserver = new LocationsObserver($this->conf, $this->lang, $this->settings);
        $objEMail = new EMail($this->conf, $this->lang, $this->settings, $this->getIdByType($paramEmailType));
        $objNotifyEMail = new EMail($this->conf, $this->lang, $this->settings, $this->getIdByType($paramNotifyEmailType));
        $objBooking = new Booking($this->conf, $this->lang, $this->settings, $paramBookingId);
        $objInvoice = new Invoice($this->conf, $this->lang, $this->settings, $paramBookingId);
        $bookingDetails = $objBooking->getDetails();
        $invoiceDetails = $objInvoice->getDetails();
        if(!is_null($bookingDetails) && !is_null($invoiceDetails))
        {
            $objLocation = new Location($this->conf, $this->lang, $this->settings, $objLocationsObserver->getIdByCode($bookingDetails['pickup_location_code']));
            $locationDetails = $objLocation->getDetails();
            $locationName = '';
            $locationPhone = '';
            $locationEmail = '';
            if(!is_null($locationDetails))
            {
                $locationName = $locationDetails['translated_location_name'];
                $locationPhone = $locationDetails['phone'];
                $locationEmail = $locationDetails['email'];

            }
            $arrData = array(
                "booking_code" => $bookingDetails['booking_code'],
                "customer_id" => $bookingDetails['customer_id'],
                "customer_name" => $invoiceDetails['customer_name'],
                "customer_email" => $invoiceDetails['customer_email'],
                "invoice_html" => $invoiceDetails['invoice'],
                "location_name" => $locationName,
                "location_phone" => $locationPhone,
                "location_email" => $locationEmail
            );

            $sent = $objEMail->sendTranslatedBookingEmail($invoiceDetails['customer_email'], $arrData, $paramEmailWidth);
            if($sent == TRUE && $paramSendNotificationToAdmin == TRUE)
            {
                // Send an admin email to location email address
                $sent = $objNotifyEMail->sendTranslatedBookingEmail($locationEmail, $arrData, $paramEmailWidth);
                if($this->sendCompanyNotificationEmails == TRUE)
                {
                    // Send an admin email to company headquarters
                    $sent = $objNotifyEMail->sendTranslatedBookingEmail($this->companyEmail, $arrData, $paramEmailWidth);
                }
            }

            $this->savedOkayMessages = array_merge($objEMail->getErrorMessages(), $objNotifyEMail->getErrorMessages());
            $this->savedErrorMessages = array_merge($objEMail->getErrorMessages(), $objNotifyEMail->getErrorMessages());
        }

        return $sent;
    }


    /*******************************************************************************/
    /********************** METHODS FOR ADMIN ACCESS ONLY **************************/
    /*******************************************************************************/

    /**
     * @param int $selectedEmailId
     * @return string
     */
	public function getAdminList($selectedEmailId = 0)
	{
		$selected = $selectedEmailId == 0 ? ' selected="selected"' : '';
		$emailList = '<option value="0"'.$selected.'>'.$this->lang->getText('NRS_ADMIN_SELECT_EMAIL_TYPE_TEXT').'</option>';
		$emailIds = $this->getAllIds();
		foreach ($emailIds AS $emailId)
		{
		    $objEMail = new EMail($this->conf, $this->lang, $this->settings, $emailId);
		    $emailDetails = $objEMail->getDetails();
			$selected = $selectedEmailId == $emailId ? ' selected="selected"' : '';
			$emailList .= '<option value="'.$emailId.'"'.$selected.'>'.$emailDetails['email_type'].'. '.$emailDetails['print_translated_email_subject'].'</option>';
		}

		return $emailList;
	}
}