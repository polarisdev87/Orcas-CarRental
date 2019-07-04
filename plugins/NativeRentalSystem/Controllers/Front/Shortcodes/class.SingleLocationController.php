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
use NativeRentalSystem\Models\Pricing\LocationFeeManager;
use NativeRentalSystem\Models\Tax\TaxManager;

final class SingleLocationController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, $paramArrLimitations = array())
    {
        parent::__construct($paramConf, $paramLang, $paramArrLimitations);
    }

    public function getContent($paramLayout = "")
    {
        $objTaxManager = new TaxManager($this->conf, $this->lang, $this->dbSettings->getSettings());
        $taxPercentage = $objTaxManager->getTaxPercentage($this->locationId, $this->locationId);
        $objLocation = new Location($this->conf, $this->lang, $this->dbSettings->getSettings(), $this->locationId);
        $locationDetails = $objLocation->getDetails();
        if(!is_null($locationDetails))
        {
            $objFeeManager = new LocationFeeManager(
                $this->conf, $this->lang, $this->dbSettings->getSettings(), $this->locationId, $taxPercentage
            );
            $locationDetails = $objLocation->getDetails();
            // Extend location details
            $locationDetails['business_hours'] = $objLocation->getBusinessHours();

            // Expand the $location array with new values
            $location = array_merge($locationDetails, $objFeeManager->getUnitDetails());

            // Get the template
            $this->view->location = $location;

            $retContent = $this->getTemplate('', 'SingleLocation', $paramLayout);
        } else
        {
            $retContent = '';
        }
        return $retContent;
    }
}