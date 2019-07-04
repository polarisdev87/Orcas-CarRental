<?php
/**
 * @package NRS
 * @note - this has to be loaded with &noheader _GET param
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Admin\Booking;
use NativeRentalSystem\Controllers\Admin\AbstractController;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Booking\Invoice;

final class PrintInvoiceController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    public function getContent()
    {
        $paramBookingId = isset($_GET['booking_id']) ? $_GET['booking_id'] : 0;
        $objInvoice = new Invoice($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramBookingId);
        $localDetails = $objInvoice->getDetails();

        // Set the view variables
        if(!is_null($localDetails))
        {
            $this->view->invoiceHTML = $localDetails['invoice'];
        } else
        {
            $this->view->invoiceHTML = '';
        }

        // Get the template
        $retContent = $this->getTemplate('Booking', 'PrintInvoice', 'Table');

        return $retContent;
    }
}
