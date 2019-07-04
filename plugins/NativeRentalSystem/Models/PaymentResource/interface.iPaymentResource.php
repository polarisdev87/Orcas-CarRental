<?php
/**
 * Payment must-have interface - must have a single element Id
 * Interface purpose is describe all public methods used available in the class and enforce to use them
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\PaymentResource;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Language\Language;

interface iPaymentResource {
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings, $paramPaymentMethodId);
    public function inDebug();
    public function getDescriptionHTML($paramCurrentDescription, $paramTotalPayNow = '0.00');
    public function setProcessingPage($paramBookingCode, $paramTotalPayNow = '0.00');
    public function getProcessingPageContent();
    public function processAPI();
}