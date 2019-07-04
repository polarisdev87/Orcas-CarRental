<?php
/**
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Front\Booking;
use NativeRentalSystem\Models\Booking\BookingsObserver;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Booking\Booking;
use NativeRentalSystem\Models\EMail\EMailsObserver;
use NativeRentalSystem\Models\Search\FrontEndSearchManager;
use NativeRentalSystem\Controllers\Front\AbstractController;

final class CancelBookingController extends AbstractController
{
    private $objSearch = NULL;

    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, $paramArrLimitations = array())
    {
        parent::__construct($paramConf, $paramLang, $paramArrLimitations);
        $this->objSearch = new FrontEndSearchManager($this->conf, $this->lang, $this->dbSettings->getSettings());

        // First - process request
        $this->processRequest();
    }

    private function processRequest()
    {
        //echo "INITIAL REQUEST VARS: ".nl2br(print_r($_REQUEST, TRUE));
        //echo "INITIAL SESSION VARS: ".nl2br(print_r($_SESSION, TRUE));

        $objBookingObservers = new BookingsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());

        // First - clear old expired bookings
        $objBookingObservers->clearExpired();

        // Second - validate the booking code
        $this->objSearch->setBookingCode($this->dbSettings->getSearchFieldStatus("booking_code", "REQUIRED"));
        if($this->objSearch->getBookingCode() != '')
        {
            $objBookingsObserver = new BookingsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
            $this->objSearch->setVariablesByBookingId($objBookingsObserver->getIdByCode($this->objSearch->getBookingCode()));
        }

        // Finally, destroy the session
        session_destroy();

        //echo "UPDATED SESSION VARS: ".nl2br(print_r($_SESSION, TRUE));
    }

    public function getContent()
    {
        // Create mandatory instances
        $objEMailsObserver = new EMailsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objBookingsObserver = new BookingsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objBooking = new Booking($this->conf, $this->lang, $this->dbSettings->getSettings(), $objBookingsObserver->getIdByCode($this->objSearch->getBookingCode()));

        // CANCEL RESERVATION
        $cancelledSuccessfully = $objBooking->cancel();
        $objEMailsObserver->sendBookingCancellationEmail($objBooking->getId(), TRUE);

        // Set the view variables
        $this->view->cancelledSuccessfully = $cancelledSuccessfully;
        $this->view->objSearch = $this->objSearch;
        $this->view->bookingCode = $this->objSearch->getPrintBookingCode();

        // Get the template
        $retContent = $this->objSearch->searchEnabled() ? $this->getTemplate('Booking', 'BookingCancelled', '') : '';

        return $retContent;
    }
}