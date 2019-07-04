<?php
/**
 * Calendar must-have interface
 * Interface purpose is describe all public methods used available in the class and enforce to use them
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Calendar;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Language\Language;

interface iCalendar {
    // Base methods - same for both
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, $paramSettings = array());
    public function inDebug();
    // Item & Extra specific methods - manufacturer, body, transmission and fuel params not used for extras; extra id not used for items
    public function get30DaysCalendar(
        $paramItemId = -1, $paramExtraId = -1, $paramPickupLocationId = -1, $paramReturnLocationId = -1, $paramPartnerId = -1,
        $paramManufacturerId = -1, $paramBodyTypeId = -1, $paramTransmissionTypeId = -1, $paramFuelTypeId = -1,
        $paramYear = "current", $paramMonth = "current", $paramDay = "current"
    );
    public function getMonthlyCalendar(
        $paramItemId = -1, $paramExtraId = -1, $paramPickupLocationId = -1, $paramReturnLocationId = -1, $paramPartnerId = -1,
        $paramManufacturerId = -1, $paramBodyTypeId = -1, $paramTransmissionTypeId = -1, $paramFuelTypeId = -1,
        $paramYear = "current", $paramMonth = "current"
    );
}