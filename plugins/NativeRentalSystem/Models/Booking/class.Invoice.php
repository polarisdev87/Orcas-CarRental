<?php
/**
 * Invoice processor

 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Booking;
use NativeRentalSystem\Models\AbstractElement;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;

class Invoice extends AbstractElement
{
    protected $conf 	    = NULL;
    protected $lang 		= NULL;
    protected $debugMode 	= 0;
    protected $bookingId    = 0;

    /**
     * Invoice constructor.
     * @param ExtensionConfiguration $paramConf
     * @param Language $paramLang
     * @param array $paramSettings
     * @param $paramBookingId = unique element identifier, mandatory, for managing invoices
     */
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings, $paramBookingId)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;

        // Set booking id
        $this->bookingId = StaticValidator::getValidPositiveInteger($paramBookingId, 0);
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    public function getBookingId()
    {
        return $this->bookingId;
    }

    private function getDataFromDatabaseById($paramBookingId)
    {
        $validBookingId =  StaticValidator::getValidPositiveInteger($paramBookingId, 0);
        $invoiceData = $this->conf->getInternalWPDB()->get_row("
            SELECT *
            FROM {$this->conf->getPrefix()}invoices
            WHERE booking_id='{$validBookingId}'
        ", ARRAY_A);

        return $invoiceData;
    }
	
	 private function getDepositAmountFromDatabaseById($paramBookingId)
    {
        $validBookingId =  StaticValidator::getValidPositiveInteger($paramBookingId, 0);
        $invoiceData = $this->conf->getInternalWPDB()->get_row("
            SELECT fixed_deposit_amount
            FROM {$this->conf->getPrefix()}invoices
            WHERE booking_id='{$validBookingId}'
        ", ARRAY_A);

        return $invoiceData;
    }

    public function getDetails()
    {
        $ret = $this->getDataFromDatabaseById($this->bookingId);

        if(!is_null($ret))
        {
            // Make raw
            $ret['invoice'] = stripslashes($ret['invoice']);
            $ret['customer_name'] = stripslashes($ret['customer_name']);
            $ret['customer_email'] = stripslashes($ret['customer_email']);
            $ret['grand_total'] = stripslashes($ret['grand_total']);
            $ret['fixed_deposit_amount'] = stripslashes($ret['fixed_deposit_amount']);
            $ret['total_pay_now'] = stripslashes($ret['total_pay_now']);
            $ret['total_pay_later'] = stripslashes($ret['total_pay_later']);
            $ret['pickup_location'] = stripslashes($ret['pickup_location']);
            $ret['return_location'] = stripslashes($ret['return_location']);

            // No translations for invoice table. It has to be like that - what was generated, that was ok

            // Prepare output for print
            $ret['print_customer_name'] = esc_html($ret['customer_name']);
            $ret['print_customer_email'] = esc_html($ret['customer_email']);
            $ret['print_grand_total'] = esc_html($ret['grand_total']);
            $ret['print_fixed_deposit_amount'] = esc_html($ret['fixed_deposit_amount']);
            $ret['print_total_pay_now'] = esc_html($ret['total_pay_now']);
            $ret['print_total_pay_later'] = esc_html($ret['total_pay_later']);

            // Prepare output for edit
            $ret['edit_customer_name'] = esc_attr($ret['customer_name']); // for input field
            $ret['edit_customer_email'] = esc_attr($ret['customer_email']); // for input field
            $ret['edit_grand_total'] = esc_attr($ret['grand_total']); // for input field
            $ret['edit_fixed_deposit_amount'] = esc_attr($ret['fixed_deposit_amount']); // for input field
            $ret['edit_total_pay_now'] = esc_attr($ret['total_pay_now']); // for input field
            $ret['edit_total_pay_later'] = esc_attr($ret['total_pay_later']); // for input field
            $ret['edit_pickup_location'] = esc_textarea($ret['pickup_location']); // for textarea field
            $ret['edit_return_location'] = esc_textarea($ret['return_location']); // for textarea field
        }

        return $ret;
    }

    /**
     * Insert invoice HTML to database
     * @param string $paramFullName
     * @param string $paramCustomerEmail
     * @param string $paramPickupLocationHTML
     * @param string $paramReturnLocationHTML
     * @param string $paramPrintGrandTotal
     * @param string $paramPrintFixedDepositAmount
     * @param string $paramPrintTotalPayNow
     * @param string $paramPrintTotalPayLater
     * @param string $paramInvoiceHTML - invoice HTML
     * @return false|int
     */
    public function save(
        $paramFullName, $paramCustomerEmail,
        $paramPrintGrandTotal, $paramPrintFixedDepositAmount, $paramPrintTotalPayNow, $paramPrintTotalPayLater,
        $paramPickupLocationHTML, $paramReturnLocationHTML, $paramInvoiceHTML
    ) {
        $validBookingId = intval($this->bookingId);
        $sanitizedName = sanitize_text_field($paramFullName);
        $validName = esc_sql($sanitizedName); // for sql query only
        $sanitizedCustomerEmail = sanitize_email($paramCustomerEmail);
        $validCustomerEmail = esc_sql($sanitizedCustomerEmail); // for sql query only
        $sanitizedGrandTotal = sanitize_text_field($paramPrintGrandTotal);
        $validGrandTotal = esc_sql($sanitizedGrandTotal); // for sql query only
        $sanitizedFixedDepositAmount = sanitize_text_field($paramPrintFixedDepositAmount);
        $validFixedDepositAmount = esc_sql($sanitizedFixedDepositAmount); // for sql query only
        $sanitizedTotalPayNow = sanitize_text_field($paramPrintTotalPayNow);
        $validTotalPayNow = esc_sql($sanitizedTotalPayNow); // for sql query only
        $sanitizedTotalPayLater = sanitize_text_field($paramPrintTotalPayLater);
        $validTotalPayLater = esc_sql($sanitizedTotalPayLater); // for sql query only

        // NOTE: We can't use sanitize_text_field function for $pickupLocation and $returnLocation,
        // because it has <br /> tags inside. So we must use 'wp_kses_post'.
        // Still, we sure that all data used for this field is generated from internal db content, not from user input
        $ksesedPickupLocation = wp_kses_post($paramPickupLocationHTML);
        $validPickupLocation = esc_sql($ksesedPickupLocation); // for sql query only
        $ksesedReturnLocation = wp_kses_post($paramReturnLocationHTML);
        $validReturnLocation = esc_sql($ksesedReturnLocation); // for sql query only
        $ksesedInvoiceHTML = wp_kses_post($paramInvoiceHTML);
        $validInvoiceHTML = esc_sql($ksesedInvoiceHTML); // for sql query only

        $invoiceExist = $this->conf->getInternalWPDB()->get_row("
            SELECT booking_id
            FROM {$this->conf->getPrefix()}invoices
            WHERE booking_id='{$validBookingId}' AND blog_id='{$this->conf->getBlogId()}'
        ");

        if(!is_null($invoiceExist))
        {
            /* update the invoice data in {$this->conf->getPrefix()}invoice table */
            $updateSQL = "
				UPDATE {$this->conf->getPrefix()}invoices SET
				customer_name='{$validName}', customer_email='{$validCustomerEmail}',
				grand_total='{$validGrandTotal}', fixed_deposit_amount='{$validFixedDepositAmount}',
				total_pay_now='{$validTotalPayNow}', total_pay_later='{$validTotalPayLater}',
				pickup_location='{$validPickupLocation}', return_location='{$validReturnLocation}',
				invoice='{$validInvoiceHTML}'
				WHERE booking_id='{$validBookingId}' AND blog_id='{$this->conf->getBlogId()}'
			";
			$saved = $this->conf->getInternalWPDB()->query($updateSQL);

            if($saved === FALSE)
            {
                $this->errorMessages[] = $this->lang->getText('NRS_INVOICE_UPDATE_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('NRS_INVOICE_UPDATED_TEXT');
            }
        } else
        {
            /* insert the invoice data in {$this->conf->getPrefix()}invoice table */
            $insertSQL = "
				INSERT INTO {$this->conf->getPrefix()}invoices
				(
					booking_id, customer_name, customer_email,
					grand_total, fixed_deposit_amount,
					total_pay_now, total_pay_later,
					pickup_location, return_location,
					invoice, blog_id
				) VALUES
				(
					'{$validBookingId}', '{$validName}', '{$validCustomerEmail}',
					'{$validGrandTotal}', '{$validFixedDepositAmount}',
					'{$validTotalPayNow}', '{$validTotalPayLater}',
					'{$validPickupLocation}', '{$validReturnLocation}',
					'{$validInvoiceHTML}', '{$this->conf->getBlogId()}'
				)
			";
            // Debug
            //echo esc_html($insertSQL); die();
            $saved = $this->conf->getInternalWPDB()->query($insertSQL);

            if($saved === FALSE || $saved === 0)
            {
                $this->errorMessages[] = $this->lang->getText('NRS_INVOICE_INSERT_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('NRS_INVOICE_INSERTED_TEXT');
            }
        }

        return $saved;
    }

    /**
     * Append invoice HTML in Database
     * @param string $paramHTMLToAppendInvoice - HTML to append
     * @return false|int
     */
    public function append($paramHTMLToAppendInvoice)
    {
        $appended = FALSE;
        $validBookingId = intval($this->bookingId);
        $ksesedHTMLtoAppendInvoice = wp_kses_post($paramHTMLToAppendInvoice);
        $validHTMLtoAppendInvoice = esc_sql($ksesedHTMLtoAppendInvoice); // for sql query only

        $sqlExist = "
            SELECT booking_id
            FROM {$this->conf->getPrefix()}invoices
            WHERE booking_id='{$validBookingId}' AND blog_id='{$this->conf->getBlogId()}'
        ";

        // DEBUG
        // echo nl2br($sqlExist);

        $invoiceExist = $this->conf->getInternalWPDB()->get_row($sqlExist, ARRAY_A);

        if(!is_null($invoiceExist))
        {
            /* update the invoice data in {$this->conf->getPrefix()}invoice table */
            $appendQuery = "
				UPDATE {$this->conf->getPrefix()}invoices SET
				invoice = CONCAT(invoice, '{$validHTMLtoAppendInvoice}')
				WHERE booking_id='{$validBookingId}' AND blog_id='{$this->conf->getBlogId()}'
			";
            $appended = $this->conf->getInternalWPDB()->query($appendQuery);
        }

        if($appended === FALSE || $appended === 0)
        {
            $this->errorMessages[] = $this->lang->getText('NRS_INVOICE_APPEND_ERROR_TEXT');
        } else
        {
            $this->okayMessages[] = $this->lang->getText('NRS_INVOICE_APPENDED_TEXT');
        }

        return $appended;
    }

    public function delete()
    {
        $deleted = FALSE;

        $validBookingId = intval($this->bookingId);
        if($validBookingId > 0)
        {
            $deleted = $this->conf->getInternalWPDB()->query("
                DELETE FROM {$this->conf->getPrefix()}invoices
                WHERE booking_id='{$validBookingId}' AND blog_id='{$this->conf->getBlogId()}'
            ");
        }

        if($deleted === FALSE || $deleted === 0)
        {
            $this->errorMessages[] = $this->lang->getText('NRS_INVOICE_DELETE_ERROR_TEXT');
        } else
        {
            $this->okayMessages[] = $this->lang->getText('NRS_INVOICE_DELETED_TEXT');
        }

        return $deleted;
    }
}