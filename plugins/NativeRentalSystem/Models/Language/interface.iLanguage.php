<?php
/**
 * Language must-have interface
 * Interface purpose is describe all public methods used available in the class and enforce to use them
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Language;

interface iLanguage {
    public function __construct($paramTextDomain, $paramGlobalLangPath, $paramExtensionLangPath, $paramLocale = "en_US");
    public function getText($paramKey);
    public function isRTL();
    public function getRTLSuffixIfRTL();
    public function getItemQuantityText($quantity = 1);
    public function getExtraQuantityText($quantity = 1);
    public function getUnitsTextByQuantity($units, $singularText, $pluralText, $pluralText2);
    public function getOrderExtensionByPosition($position, $textST, $textND, $textRD, $textTH);
    public function canTranslateSQL();
    public function register($paramKey, $paramValue);
    public function getTranslated($paramKey, $paramNonTranslatedValue);
}