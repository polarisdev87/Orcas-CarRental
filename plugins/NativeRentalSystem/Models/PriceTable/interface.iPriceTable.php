<?php
/**
 * Price Table must-have interface
 * Interface purpose is describe all public methods used available in the class and enforce to use them
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\PriceTable;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Language\Language;

interface iPriceTable {
    // Base methods - same for both
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings);
    public function inDebug();
    // Item & Extra specific methods - params not used for extras / for items
    public function getPriceTable(
        $paramItemId = -1, $paramExtraId = -1, $paramPickupLocationId = -1, $paramReturnLocationId = -1, $paramPartnerId = -1,
        $paramManufacturerId = -1, $paramBodyTypeId = -1, $paramTransmissionTypeId = -1, $paramFuelTypeId = -1
    );
}