<?php
/**
 * Units must-have interface
 * Interface purpose is describe all public methods used available in the class and enforce to use them
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Unit;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Language\Language;

interface iUnitsManager {
    public function __construct(
        ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings,
        $paramElementSKU, $paramTimestampFrom, $paramTimestampTo
    );
    public function inDebug();
    public function getTotalUnits($paramLocationCode = "", $paramIgnoreFromBookingId = 0); // SQL optimized method
    public function getTotalUnitsAvailable($paramLocationCode = "", $paramIgnoreFromBookingId = 0);
    public function getMaxAllowedUnitsForBooking($paramLocationCode = "", $paramIgnoreFromBookingId = 0);
}