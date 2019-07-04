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
use NativeRentalSystem\Models\Item\Manufacturer;
use NativeRentalSystem\Models\Item\ManufacturersObserver;
use NativeRentalSystem\Models\Language\Language;

final class ManufacturersController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, $paramArrLimitations = array())
    {
        parent::__construct($paramConf, $paramLang, $paramArrLimitations);
    }

    public function getContent($paramLayout = "Slider")
    {
        // Create mandatory instances
        $objManufacturersObserver = new ManufacturersObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        
        $gotResults = FALSE;
        // Get all manufacturers from body type 0 and other body types (last 'false' means, that we skip body type check)
        $manufacturerIds = $objManufacturersObserver->getAllIds();
        $manufacturers = array();
        foreach($manufacturerIds AS $manufacturerId)
        {
            $objManufacturer = new Manufacturer($this->conf, $this->lang, $this->dbSettings->getSettings(), $manufacturerId);
            $manufacturers[] = $objManufacturer->getDetails();
            $gotResults = TRUE;
        }

        // Get the template
        $this->view->manufacturers = $manufacturers;
        $this->view->gotResults = $gotResults;
        $retContent = $this->getTemplate('', 'Manufacturers', $paramLayout);

        return $retContent;
    }
}