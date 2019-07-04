<?php
/**
 * Deposit manager must-have interface - must have a single element ID
 * Interface purpose is describe all public methods used available in the class and enforce to use them
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Deposit;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Language\Language;

interface iDepositManager {
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, $paramSettings, $paramItemOrExtraId);
    public function inDebug();
    public function getAmount();
    public function getDetails(); // Deposit and formatted prices
}