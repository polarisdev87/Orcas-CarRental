<?php
/**
 * Root interface for classes needed for setup, install etc., that can't
 * Interface purpose is describe all public methods used available in the class and enforce to use them
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Language\Language;

interface iRootObserver {
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang);
    public function inDebug();
}