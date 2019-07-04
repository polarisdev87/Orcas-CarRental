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

interface iConfiguration {
    public function getInternalWPDB();
    public function getBlogId();
    public function getRequiredPHPVersion();
    public function getCurrentPHPVersion();
    public function getRequiredWPVersion();
    public function getCurrentWPVersion();
    public function getVersion();
    public function isNetworkEnabled();
    public function getExtensionName();
    public function getExtensionFolder();
    public function getVariablePrefix();
    public function getExtensionPrefix();
    public function getURLPrefix();
    public function getBlogPrefix($paramBlogId = -1);
    public function getPrefix();
    public function getShortcode();
    public function getItemParameter();
    public function getItemPluralParameter();
    public function getManufacturerParameter();
    public function getManufacturerPluralParameter();
    public function getBodyTypeParameter();
    public function getTransmissionTypeParameter();
    public function getFuelTypeParameter();
    public function getTextDomain();
    public function getGallery();

    // PATH METHODS: START
    public function getPluginPathWithFilename();
    public function getPluginPath();
    public function getPluginBasename();
    public function getPluginDirname();
    public function getPluginExtensionsPath();
    public function getTestsPath();
    public function getGalleryPath();
    public function getGalleryPathWithoutEndSlash();
    public function getLibrariesPath();
    public function getLibrariesTestPath();
    public function getPluginLangRelPath();
    public function getGlobalLangPath();

    // URL METHODS: START
    public function getPluginURL();
    public function getGalleryURL();
    public function getPluginExtensionsURL();
    public function getTestsURL();
}