<?php
/**
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Front\Shortcodes;
use NativeRentalSystem\Controllers\Front\AbstractController;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Calendar\ExtrasCalendar;

final class ExtrasAvailabilityController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, $paramArrLimitations = array())
    {
        parent::__construct($paramConf, $paramLang, $paramArrLimitations);
    }
    
    public function getContent($paramLayout = "Calendar")
    {
        // Create mandatory instances
        $objCalendar = new ExtrasCalendar($this->conf, $this->lang, $this->dbSettings->getSettings());

        // Extra Calendar table: Start
        $extrasCalendar = $objCalendar->get30DaysCalendar(
            $this->itemId, $this->extraId, $this->pickupLocationId, $this->returnLocationId, $this->partnerId,
            $this->manufacturerId, $this->bodyTypeId, $this->transmissionTypeId, $this->fuelTypeId,
            "current", "current", "current"
        );
        // Extra Calendar table: End

        // Get the template
        $this->view->noonTime = date_i18n(get_option('time_format'), strtotime(date("Y-m-d")." ".$this->dbSettings->getSetting('conf_noon_time')), TRUE);
        $this->view->extrasCalendar = $extrasCalendar;
        $retContent = $this->getTemplate('', 'ExtrasAvailability', $paramLayout);

        return $retContent;
    }    
}
