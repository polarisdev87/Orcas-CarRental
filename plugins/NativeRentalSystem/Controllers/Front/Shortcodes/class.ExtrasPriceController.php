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
use NativeRentalSystem\Models\PriceTable\ExtrasPriceTable;

final class ExtrasPriceController extends AbstractController
{

    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, $paramArrLimitations = array())
    {
        parent::__construct($paramConf, $paramLang, $paramArrLimitations);
    }

    public function getContent($paramLayout = "Table")
    {
        // Create mandatory object instances
        $objDepositsObserver = new DepositsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objPriceTable = new ExtrasPriceTable($this->conf, $this->lang, $this->dbSettings->getSettings());

        // Get the template
        $this->view->depositsEnabled = $objDepositsObserver->areDepositsEnabled();
        $this->view->priceTable = $objPriceTable->getPriceTable(
            $this->itemId, $this->extraId, $this->pickupLocationId, $this->returnLocationId, $this->partnerId, $this->manufacturerId,
            $this->bodyTypeId, $this->transmissionTypeId, $this->fuelTypeId
        );
        $retContent = $this->getTemplate('', 'ExtrasPrice', $paramLayout);

        return $retContent;
    }
}