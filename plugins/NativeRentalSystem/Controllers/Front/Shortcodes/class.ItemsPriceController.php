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
use NativeRentalSystem\Models\Deposit\DepositsObserver;
use NativeRentalSystem\Models\Item\ItemsObserver;
use NativeRentalSystem\Models\PriceTable\ItemsPriceTable;

final class ItemsPriceController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, $paramArrLimitations = array())
    {
        parent::__construct($paramConf, $paramLang, $paramArrLimitations);
    }

    public function getContent($paramLayout = "Table")
    {
        // Create mandatory instances
        $objItemsObserver = new ItemsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objDepositsObserver = new DepositsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objPriceTable = new ItemsPriceTable($this->conf, $this->lang, $this->dbSettings->getSettings());

        // Get the template
        $this->view->depositsEnabled = $objDepositsObserver->areDepositsEnabled();
        $this->view->priceTable = $objPriceTable->getPriceTable(
            $this->itemId, $this->extraId, $this->pickupLocationId, $this->returnLocationId, $this->partnerId, $this->manufacturerId,
            $this->bodyTypeId, $this->transmissionTypeId, $this->fuelTypeId
        );

        // Get the template
        $templateName = $objItemsObserver->areItemsClassified() ? 'ClassifiedItemsPrice' : 'ItemsPrice';
        $retContent = $this->getTemplate('', $templateName, $paramLayout);

        return $retContent;
    }
}