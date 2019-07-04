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
use NativeRentalSystem\Models\Item\BodyType;
use NativeRentalSystem\Models\Item\BodyTypesObserver;
use NativeRentalSystem\Models\Item\FeaturesObserver;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Item\Item;
use NativeRentalSystem\Models\Item\ItemsObserver;
use NativeRentalSystem\Models\Tax\TaxManager;
use NativeRentalSystem\Models\Pricing\ItemPriceManager;

final class ItemsController extends AbstractController
{
    private $objItemsObserver	        = NULL;

    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, $paramArrLimitations = array())
    {
        parent::__construct($paramConf, $paramLang, $paramArrLimitations);
        $this->objItemsObserver = new ItemsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
    }

    public function getContent($paramLayout = "List")
    {
        if($paramLayout != "Slider" && $this->objItemsObserver->areItemsClassified())
        {
            // For classified items, when they are not in slider (slider does not support items classification)
            return $this->getClassifiedItemsContent($paramLayout);
        } else
        {
            // For slider and when items are not classified
            return $this->getItemsContent($paramLayout);
        }
    }

    private function getClassifiedItemsContent($paramLayout = "List")
    {
        $gotResults = FALSE;
        $objTaxManager = new TaxManager($this->conf, $this->lang, $this->dbSettings->getSettings());
        $taxPercentage = $objTaxManager->getTaxPercentage($this->pickupLocationId, 0);
        $itemTypesWithItems = array();

        $objBodyTypes = new BodyTypesObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $bodyTypeIds = $objBodyTypes->getAllIds(TRUE);
        foreach($bodyTypeIds AS $bodyTypeId)
        {
            if($this->bodyTypeId == -1 || ($this->bodyTypeId >= 0 && $this->bodyTypeId == $bodyTypeId))
            {
                $objBodyType = new BodyType($this->conf, $this->lang, $this->dbSettings->getSettings(), $bodyTypeId);
                $type = $objBodyType->getDetails(TRUE);
                $itemIds = $this->objItemsObserver->getAvailableIdsByLayout(
                    $paramLayout, $this->partnerId, $this->manufacturerId,
                    $bodyTypeId, $this->transmissionTypeId,
                    $this->fuelTypeId, $this->itemId, $this->pickupLocationId, $this->returnLocationId
                );
                $type['items'] = array();
                $type['got_search_result'] = FALSE;
                foreach($itemIds AS $itemId)
                {
                    $objItem = new Item($this->conf, $this->lang, $this->dbSettings->getSettings(), $itemId);
                    $itemDetails = $objItem->getExtendedDetails();
                    $objPriceManager = new ItemPriceManager(
                        $this->conf, $this->lang, $this->dbSettings->getSettings(), $itemId, $itemDetails['price_group_id'], "", $taxPercentage
                    );

                    ///////////////////////////////////////////////////////////////////////////////
                    // FEATURES: START
                    $objFeaturesObserver = new FeaturesObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
                    $features = $objFeaturesObserver->getTranslatedSelectedFeaturesByItemId($itemId, FALSE);
                    $itemDetails['show_features'] = sizeof($features) > 0 ? TRUE : FALSE;
                    $itemDetails['features'] = $features;
                    // FEATURES: END
                    ///////////////////////////////////////////////////////////////////////////////

                    // Expand the $item array with new values
                    $type['items'][] = array_merge($itemDetails, $objPriceManager->getWeekCheapestDayMinimalPriceDetails());
                    $type['got_search_result'] = TRUE;
                    $gotResults = TRUE;
                }
                if(sizeof($itemIds) > 0)
                {
                    // Sort items by price
                    $sortableItems = $type['items'];
                    uasort($sortableItems, array('\NativeRentalSystem\Models\Formatting\StaticFormatter','priceCompare'));
                    $type['items'] = $sortableItems;

                    // Add to stack
                    $itemTypesWithItems[] = $type;
                }
            }
        }

        // Get the template
        $this->view->itemTypesWithItems = $itemTypesWithItems;
        $this->view->gotResults = $gotResults;
        $retContent = $this->getTemplate('', 'ClassifiedItems', $paramLayout);

        return $retContent;
    }

    private function getItemsContent($paramLayout = "List")
    {
        $gotResults = FALSE;
        $objTaxManager = new TaxManager($this->conf, $this->lang, $this->dbSettings->getSettings());
        $taxPercentage = $objTaxManager->getTaxPercentage($this->pickupLocationId, $this->returnLocationId);
        // Get all items from body type 0 and other body types (last 'false' means, that we skip body type check)
        $itemIds = $this->objItemsObserver->getAvailableIdsByLayout(
            $paramLayout, $this->partnerId, $this->manufacturerId, $this->bodyTypeId,
            $this->transmissionTypeId, $this->fuelTypeId, $this->itemId, $this->pickupLocationId, $this->returnLocationId
        );
        $items = array();
        foreach($itemIds AS $itemId)
        {
            $objItem = new Item($this->conf, $this->lang, $this->dbSettings->getSettings(), $itemId);
            $itemDetails = $objItem->getExtendedDetails();
            $objPriceManager = new ItemPriceManager(
                $this->conf, $this->lang, $this->dbSettings->getSettings(), $itemId, $itemDetails['price_group_id'], "", $taxPercentage
            );

            ///////////////////////////////////////////////////////////////////////////////
            // FEATURES: START
            $objFeaturesObserver = new FeaturesObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
            $features = $objFeaturesObserver->getTranslatedSelectedFeaturesByItemId($itemId, FALSE);
            $itemDetails['show_features'] = sizeof($features) > 0 ? TRUE : FALSE;
            $itemDetails['features'] = $features;
            // FEATURES: END
            ///////////////////////////////////////////////////////////////////////////////

            // Expand the $item array with new values
            $items[] = array_merge($itemDetails, $objPriceManager->getWeekCheapestDayMinimalPriceDetails());

            $gotResults = TRUE;
        }

        // Sort items by price
        uasort($items, array('\NativeRentalSystem\Models\Formatting\StaticFormatter', 'priceCompare'));
        // Get the template
        $this->view->items = $items;
        $this->view->gotResults = $gotResults;
        $retContent = $this->getTemplate('', 'Items', $paramLayout);

        return $retContent;
    }
}