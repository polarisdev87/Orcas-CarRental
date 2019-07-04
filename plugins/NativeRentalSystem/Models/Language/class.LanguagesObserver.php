<?php
/**
 * NRS Locations Observer (no setup for single location)

 * @note - this class is a root observer (with $settings) on purpose - for registration
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Language;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\EMail\EMail;
use NativeRentalSystem\Models\EMail\EMailsObserver;
use NativeRentalSystem\Models\Extra\Extra;
use NativeRentalSystem\Models\Extra\ExtrasObserver;
use NativeRentalSystem\Models\iRootObserver;
use NativeRentalSystem\Models\Benefit\Benefit;
use NativeRentalSystem\Models\Benefit\BenefitsObserver;
use NativeRentalSystem\Models\Item\BodyType;
use NativeRentalSystem\Models\Item\BodyTypesObserver;
use NativeRentalSystem\Models\Item\Feature;
use NativeRentalSystem\Models\Item\FeaturesObserver;
use NativeRentalSystem\Models\Item\FuelType;
use NativeRentalSystem\Models\Item\FuelTypesObserver;
use NativeRentalSystem\Models\Item\Item;
use NativeRentalSystem\Models\Item\ItemsObserver;
use NativeRentalSystem\Models\Item\Manufacturer;
use NativeRentalSystem\Models\Item\ManufacturersObserver;
use NativeRentalSystem\Models\Item\TransmissionType;
use NativeRentalSystem\Models\Item\TransmissionTypesObserver;
use NativeRentalSystem\Models\Location\Location;
use NativeRentalSystem\Models\Location\LocationsObserver;
use NativeRentalSystem\Models\Option\ExtraOption;
use NativeRentalSystem\Models\Option\ExtraOptionsObserver;
use NativeRentalSystem\Models\Option\ItemOption;
use NativeRentalSystem\Models\Option\ItemOptionsObserver;
use NativeRentalSystem\Models\PaymentMethod\PaymentMethod;
use NativeRentalSystem\Models\PaymentMethod\PaymentMethodsObserver;
use NativeRentalSystem\Models\Pricing\PriceGroup;
use NativeRentalSystem\Models\Pricing\PriceGroupsObserver;
use NativeRentalSystem\Models\Tax\Tax;
use NativeRentalSystem\Models\Tax\TaxesObserver;

class LanguagesObserver implements iRootObserver
{
    protected $conf 	                = NULL;
    protected $lang 		            = NULL;
    protected $settings 	            = array();
    protected $debugMode 	            = 0;

    /**
     * LocationsObserver constructor.
     * @param ExtensionConfiguration $paramConf
     * @param Language $paramLang
     */
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    /**
     * @note - we use array() here instead of all settings, just because we know that we
     * are not going to use that data for registration. It will be fine with default data there
     */
    public function registerAllForTranslation()
    {
        $objBenefitsObserver = new BenefitsObserver($this->conf, $this->lang, array());
        $objBodyTypesObserver = new BodyTypesObserver($this->conf, $this->lang, array());
        $objEMailsObserver = new EMailsObserver($this->conf, $this->lang, array());
        $objExtrasObserver = new ExtrasObserver($this->conf, $this->lang, array());
        $objFeaturesObserver = new FeaturesObserver($this->conf, $this->lang, array());
        $objFuelTypesObserver = new FuelTypesObserver($this->conf, $this->lang, array());
        $objItemsObserver = new ItemsObserver($this->conf, $this->lang, array());
        $objLocationsObserver = new LocationsObserver($this->conf, $this->lang, array());
        $objManufacturersObserver = new ManufacturersObserver($this->conf, $this->lang, array());
        $objItemOptionsObserver = new ItemOptionsObserver($this->conf, $this->lang, array());
        $objExtraOptionsObserver = new ExtraOptionsObserver($this->conf, $this->lang, array());
        $objPriceGroupsObserver = new PriceGroupsObserver($this->conf, $this->lang, array());
        $objPaymentMethodsObserver = new PaymentMethodsObserver($this->conf, $this->lang, array());
        $objTaxesObserver = new TaxesObserver($this->conf, $this->lang, array());
        $objTransmissionTypesObserver = new TransmissionTypesObserver($this->conf, $this->lang, array());

        $benefitIds = $objBenefitsObserver->getAllIds();
        foreach($benefitIds AS $benefitId)
        {
            $objBenefit = new Benefit($this->conf, $this->lang, array(), $benefitId);
            $objBenefit->registerForTranslation();
        }

        $bodyTypeIds = $objBodyTypesObserver->getAllIds();
        foreach($bodyTypeIds AS $bodyTypeId)
        {
            $objBodyType = new BodyType($this->conf, $this->lang, array(), $bodyTypeId);
            $objBodyType->registerForTranslation();
        }

        $emailIds = $objEMailsObserver->getAllIds();
        foreach($emailIds AS $emailId)
        {
            $objEmail = new EMail($this->conf, $this->lang, array(), $emailId);
            $objEmail->registerForTranslation();
        }

        $extraIds = $objExtrasObserver->getAllIds();
        foreach($extraIds AS $extraId)
        {
            $objExtra = new Extra($this->conf, $this->lang, array(), $extraId);
            $objExtra->registerForTranslation();
        }

        $featureIds = $objFeaturesObserver->getAllIds();
        foreach($featureIds AS $featureId)
        {
            $objFeature = new Feature($this->conf, $this->lang, array(), $featureId);
            $objFeature->registerForTranslation();
        }

        $fuelTypeIds = $objFuelTypesObserver->getAllIds();
        foreach($fuelTypeIds AS $fuelTypeId)
        {
            $objFuelType = new FuelType($this->conf, $this->lang, array(), $fuelTypeId);
            $objFuelType->registerForTranslation();
        }

        $itemIds = $objItemsObserver->getAllIds();
        foreach($itemIds AS $itemId)
        {
            $objItem = new Item($this->conf, $this->lang, array(), $itemId);
            $objItem->registerForTranslation();
        }

        $locationIds = $objLocationsObserver->getAllIds();
        foreach($locationIds AS $locationId)
        {
            $objLocation = new Location($this->conf, $this->lang, array(), $locationId);
            $objLocation->registerForTranslation();
        }

        $manufacturerIds = $objManufacturersObserver->getAllIds();
        foreach($manufacturerIds AS $manufacturerId)
        {
            $objManufacturer = new Manufacturer($this->conf, $this->lang, array(), $manufacturerId);
            $objManufacturer->registerForTranslation();
        }

        $optionIds = $objItemOptionsObserver->getAllIds();
        foreach($optionIds AS $optionId)
        {
            $objOption = new ItemOption($this->conf, $this->lang, array(), $optionId);
            $objOption->registerForTranslation();
        }

        $optionIds = $objExtraOptionsObserver->getAllIds();
        foreach($optionIds AS $optionId)
        {
            $objOption = new ExtraOption($this->conf, $this->lang, array(), $optionId);
            $objOption->registerForTranslation();
        }

        $priceGroupIds = $objPriceGroupsObserver->getAllIds();
        foreach($priceGroupIds AS $priceGroupId)
        {
            $objOption = new PriceGroup($this->conf, $this->lang, array(), $priceGroupId);
            $objOption->registerForTranslation();
        }

        $paymentMethodIds = $objPaymentMethodsObserver->getAllIds();
        foreach($paymentMethodIds AS $paymentMethodId)
        {
            $objPaymentMethod = new PaymentMethod($this->conf, $this->lang, array(), $paymentMethodId);
            $objPaymentMethod->registerForTranslation();
        }

        $taxIds = $objTaxesObserver->getAllIds();
        foreach($taxIds AS $taxId)
        {
            $objTax = new Tax($this->conf, $this->lang, array(), $taxId);
            $objTax->registerForTranslation();
        }

        $transmissionTypeIds = $objTransmissionTypesObserver->getAllIds();
        foreach($transmissionTypeIds AS $transmissionTypeId)
        {
            $objTransmissionType = new TransmissionType($this->conf, $this->lang, array(), $transmissionTypeId);
            $objTransmissionType->registerForTranslation();
        }
    }
}