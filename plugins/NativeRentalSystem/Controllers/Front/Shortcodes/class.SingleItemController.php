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
use NativeRentalSystem\Models\Item\FeaturesObserver;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Deposit\DepositsObserver;
use NativeRentalSystem\Models\Item\Item;
use NativeRentalSystem\Models\Tax\TaxManager;
use NativeRentalSystem\Models\Pricing\ItemPriceManager;

final class SingleItemController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, $paramArrLimitations = array())
    {
        parent::__construct($paramConf, $paramLang, $paramArrLimitations);
    }

    public function getContent($paramLayout = "")
    {
        $objDepositsObserver = new DepositsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objTaxManager = new TaxManager($this->conf, $this->lang, $this->dbSettings->getSettings());
        $taxPercentage = $objTaxManager->getTaxPercentage($this->pickupLocationId, 0);
        $objItem = new Item($this->conf, $this->lang, $this->dbSettings->getSettings(), $this->itemId);
        $itemDetails = $objItem->getExtendedDetails();
        if(!is_null($itemDetails))
        {
            ///////////////////////////////////////////////////////////////////////////////
            // FEATURES: START
            $objFeaturesObserver = new FeaturesObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
            $features = $objFeaturesObserver->getTranslatedSelectedFeaturesByItemId($this->itemId, FALSE);
            $itemDetails['show_features'] = sizeof($features) > 0 ? TRUE : FALSE;
            $itemDetails['features'] = $features;
            // FEATURES: END
            ///////////////////////////////////////////////////////////////////////////////

            $objPriceManager = new ItemPriceManager(
                $this->conf, $this->lang, $this->dbSettings->getSettings(), $this->itemId, $itemDetails['price_group_id'], "", $taxPercentage
            );

            // Expand the $item array with new values
            $item = array_merge($itemDetails, $objPriceManager->getWeekCheapestDayMinimalPriceDetails());

            // Get the template
            $this->view->extensionName = $this->conf->getExtensionName();
            $this->view->depositsEnabled = $objDepositsObserver->areDepositsEnabled();
            $this->view->item = $item;

            $retContent = $this->getTemplate('', 'SingleItem', $paramLayout);
        } else
        {
            $retContent = '';
        }
        return $retContent;
    }
}