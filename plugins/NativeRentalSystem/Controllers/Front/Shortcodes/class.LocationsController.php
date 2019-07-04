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
use NativeRentalSystem\Models\Location\Location;
use NativeRentalSystem\Models\Location\LocationsObserver;
use NativeRentalSystem\Models\Pricing\LocationFeeManager;
use NativeRentalSystem\Models\Tax\TaxManager;

final class LocationsController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, $paramArrLimitations = array())
    {
        parent::__construct($paramConf, $paramLang, $paramArrLimitations);
    }

    public function getContent($paramLayout = "List")
    {
        // Create mandatory instances
        $objLocationsObserver = new LocationsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());

        $gotResults = FALSE;
        $objTaxManager = new TaxManager($this->conf, $this->lang, $this->dbSettings->getSettings());
        $locationIds = $objLocationsObserver->getItemPickupIds($this->itemId);
        $locations = array();
        foreach($locationIds AS $locationId)
        {
            $objLocation = new Location($this->conf, $this->lang, $this->dbSettings->getSettings(), $locationId);
            $taxPercentage = $objTaxManager->getTaxPercentage($locationId, $locationId);
            $objFeeManager = new LocationFeeManager(
                $this->conf, $this->lang, $this->dbSettings->getSettings(), $locationId, $taxPercentage
            );
            $locationDetails = $objLocation->getDetails();
            // Extend location details
            $locationDetails['business_hours'] = $objLocation->getBusinessHours();

            // Expand the $location array with new values
            $locations[] = array_merge($locationDetails, $objFeeManager->getUnitDetails());

            $gotResults = TRUE;
        }

        // Get the template
        $this->view->locations = $locations;
        $this->view->gotResults = $gotResults;
        $retContent = $this->getTemplate('', 'Locations', $paramLayout);

        return $retContent;
    }
}