<?php
/**
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Front\Shortcodes;
use NativeRentalSystem\Controllers\Front\AbstractController;
use NativeRentalSystem\Models\Benefit\Benefit;
use NativeRentalSystem\Models\Benefit\BenefitsObserver;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Language\Language;

final class BenefitsController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, $paramArrLimitations = array())
    {
        parent::__construct($paramConf, $paramLang, $paramArrLimitations);
    }

    public function getContent($paramLayout = "Slider")
    {
        // Create mandatory instances
        $objBenefitsObserver = new BenefitsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());

        $gotResults = FALSE;
        $benefitIds = $objBenefitsObserver->getAllIds();
        $benefits = array();
        foreach($benefitIds AS $itemId)
        {
            $objBenefit = new Benefit($this->conf, $this->lang, $this->dbSettings->getSettings(), $itemId);
            $benefits[] = $objBenefit->getDetails();

            $gotResults = TRUE;
        }

        // Get the template
        $this->view->benefits = $benefits;
        $this->view->gotResults = $gotResults;
        $retContent = $this->getTemplate('', 'Benefits', $paramLayout);

        return $retContent;
    }
}