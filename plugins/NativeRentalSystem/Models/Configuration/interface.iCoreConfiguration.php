<?php
/**
 * NRS Configuration class dependant on template
 * Note 1: This is a root class and do not depend on any other plugin classes

 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Configuration;

interface iCoreConfiguration {
    public function __construct(
        \wpdb &$paramWPDB, $paramBlogId, $paramRequiredPHPVersion, $paramCurrentPHPVersion, $paramRequiredWPVersion, $paramCurrentWPVersion,
        $paramVersion, $paramExtensionName, $paramExtensionFolder, $paramVariablePrefix, $paramExtensionPrefix, $paramURLPrefix, $paramShortcode,
        $paramItemParameter, $paramItemPluralParameter, $paramManufacturerParameter, $paramManufacturerPluralParameter,
        $paramBodyTypeParameter, $paramTransmissionTypeParameter, $paramFuelTypeParameter,
        $paramTextDomain, $paramGallery, $paramPluginPathWithFilename
    );
}