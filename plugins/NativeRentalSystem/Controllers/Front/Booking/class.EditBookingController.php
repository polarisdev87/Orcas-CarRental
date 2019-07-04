<?php
/**
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Front\Booking;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Search\FrontEndSearchManager;
use NativeRentalSystem\Controllers\Front\AbstractController;

final class EditBookingController extends AbstractController
{
    private $objSearch          = NULL;

    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, $paramArrLimitations = array())
    {
        parent::__construct($paramConf, $paramLang, $paramArrLimitations);
    }

    public function getContent($paramLayout = 'Form')
    {
        // Create mandatory instances
        $this->objSearch = new FrontEndSearchManager($this->conf, $this->lang, $this->dbSettings->getSettings());

        // Set the view variables
        $this->view->objSearch = $this->objSearch;
        $this->view->actionPageURL = $this->actionPageId > 0 ? get_permalink($this->actionPageId) : '';

        // Get the template (edit booking page)
        $retContent = $this->getTemplate('Booking', 'EditBooking', $paramLayout);

        return $retContent;
    }
}