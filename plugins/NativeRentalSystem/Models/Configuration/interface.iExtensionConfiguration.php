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

interface iExtensionConfiguration {
    public function __construct(CoreConfiguration &$paramCoreConf);

    //PATH METHODS: START
    public function getExtensionPath($paramRelativePathAndFile = '', $paramReturnWithFileName = TRUE);
    public function getExtensionFrontAssetsPath($paramRelativePathAndFile = '', $paramReturnWithFileName = TRUE);
    public function getExtensionAdminAssetsPath($paramRelativePathAndFile = '', $paramReturnWithFileName = TRUE);
    public function getExtensionFrontCSSPath($paramRelativePathAndFile = '', $paramReturnWithFileName = TRUE);
    public function getExtensionAdminCSSPath($paramRelativePathAndFile = '', $paramReturnWithFileName = TRUE);
    public function getExtensionFrontFontsPath($paramRelativePathAndFile = '', $paramReturnWithFileName = TRUE);
    public function getExtensionAdminFontsPath($paramRelativePathAndFile = '', $paramReturnWithFileName = TRUE);
    public function getExtensionFrontImagesPath($paramRelativePathAndFile = '', $paramReturnWithFileName = TRUE);
    public function getExtensionAdminImagesPath($paramRelativePathAndFile = '', $paramReturnWithFileName = TRUE);
    public function getExtensionFrontJSPath($paramRelativePathAndFile = '', $paramReturnWithFileName = TRUE);
    public function getExtensionAdminJSPath($paramRelativePathAndFile = '', $paramReturnWithFileName = TRUE);
    public function getExtensionDemoGalleryPath($paramRelativePathAndFile = '', $paramReturnWithFileName = TRUE);
    public function getExtensionSQLsPath($paramRelativePathAndFile = '', $paramReturnWithFileName = TRUE);
    public function getExtensionLangPath($paramRelativePathAndFile = '', $paramReturnWithFileName = TRUE);
    public function getExtensionFrontTemplatesPath($paramRelativePathAndFile = '', $paramReturnWithFileName = TRUE);
    public function getExtensionAdminTemplatesPath($paramRelativePathAndFile = '', $paramReturnWithFileName = TRUE);

    // URL METHODS: START
    public function getExtensionURL($paramRelativeURLAndFile = '', $paramReturnWithFileName = TRUE);
    public function getExtensionFrontAssetsURL($paramRelativeURLAndFile = '', $paramReturnWithFileName = TRUE);
    public function getExtensionAdminAssetsURL($paramRelativeURLAndFile = '', $paramReturnWithFileName = TRUE);
    public function getExtensionFrontCSSURL($paramRelativeURLAndFile = '', $paramReturnWithFileName = TRUE);
    public function getExtensionAdminCSSURL($paramRelativeURLAndFile = '', $paramReturnWithFileName = TRUE);
    public function getExtensionFrontFontsURL($paramRelativeURLAndFile = '', $paramReturnWithFileName = TRUE);
    public function getExtensionAdminFontsURL($paramRelativeURLAndFile = '', $paramReturnWithFileName = TRUE);
    public function getExtensionFrontImagesURL($paramRelativeURLAndFile = '', $paramReturnWithFileName = TRUE);
    public function getExtensionAdminImagesURL($paramRelativeURLAndFile = '', $paramReturnWithFileName = TRUE);
    public function getExtensionFrontJSURL($paramRelativeURLAndFile = '', $paramReturnWithFileName = TRUE);
    public function getExtensionAdminJSURL($paramRelativeURLAndFile = '', $paramReturnWithFileName = TRUE);
    public function getExtensionDemoGalleryURL($paramRelativeURLAndFile = '', $paramReturnWithFileName = TRUE);
    public function getExtensionSQLsURL($paramRelativeURLAndFile = '', $paramReturnWithFileName = TRUE);
    public function getExtensionLangURL($paramRelativeURLAndFile = '', $paramReturnWithFileName = TRUE);
    public function getExtensionFrontTemplatesURL($paramRelativeURLAndFile = '', $paramReturnWithFileName = TRUE);
    public function getExtensionAdminTemplatesURL($paramRelativeURLAndFile = '', $paramReturnWithFileName = TRUE);
}