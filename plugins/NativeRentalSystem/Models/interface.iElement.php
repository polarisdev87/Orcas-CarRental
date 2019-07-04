<?php
/**
 * Element must-have interface - must have a single element Id
 * Interface purpose is describe all public methods used available in the class and enforce to use them
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Language\Language;

interface iElement {
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings, $paramElementId);
    public function getId();
    public function inDebug();
    public function getDebugMessages();
    public function getOkayMessages();
    public function getErrorMessages();
    public function getDetails($paramIncludeUnclassified = FALSE);
    /**
     * @return bool|false|int
     */
    public function save();
    public function registerForTranslation();
    /**
     * @return false|int
     */
    public function delete();
}