<?php
/**
 * Option Manager must-have interface (with element id)
 * Interface purpose is describe all public methods used available in the class and enforce to use them
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Option;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Language\Language;

interface iOptionManager {
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings, $paramItemOrExtraId);
    public function getTotalOptions();
    public function getTranslatedDropDown($paramSelectedOptionId = 0, $paramOptionsMeasurementUnit = "");
    public function getSlider($paramSelectedOptionId = 0, $paramOptionsMeasurementUnit = "");
}