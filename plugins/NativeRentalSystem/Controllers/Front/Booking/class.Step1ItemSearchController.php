<?php
/**
 * Booking step no. 1
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Front\Booking;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Formatting\StaticFormatter;
use NativeRentalSystem\Models\Item\BodyTypesObserver;
use NativeRentalSystem\Models\Item\FuelTypesObserver;
use NativeRentalSystem\Models\Item\ManufacturersObserver;
use NativeRentalSystem\Models\Item\TransmissionTypesObserver;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Booking\BookingsObserver;
use NativeRentalSystem\Models\Location\ClosedDatesObserver;
use NativeRentalSystem\Models\Location\Location;
use NativeRentalSystem\Models\Location\LocationsObserver;
use NativeRentalSystem\Models\Role\PartnersObserver;
use NativeRentalSystem\Controllers\Front\AbstractController;
use NativeRentalSystem\Models\Search\FrontEndSearchManager;

final class Step1ItemSearchController extends AbstractController
{
    private $objSearch	                = NULL;

    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, $paramArrLimitations = array())
    {
        parent::__construct($paramConf, $paramLang, $paramArrLimitations);

        $this->objSearch = new FrontEndSearchManager($this->conf, $this->lang, $this->dbSettings->getSettings());
        // No prepare request needed here - it is a new booking
    }

    public function getNewContent($paramLayout = 'Form')
    {
        return $this->getContent($paramLayout, "NEW");
    }

    public function getEditContent($paramLayout = 'Form')
    {
        // Second - validate the booking code
        $this->objSearch->setBookingCode($this->dbSettings->getSearchFieldStatus("booking_code", "REQUIRED"));
        if($this->objSearch->getBookingCode() != '')
        {
            $objBookingsObserver = new BookingsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
            $this->objSearch->setVariablesByBookingId($objBookingsObserver->getIdByCode($this->objSearch->getBookingCode()));
        }

        // Third - set object variables - allow to override by _POST, _GET or _SESSION
        $this->objSearch->setVariablesByRequestOrSessionParams();

        if ($this->objSearch->searchEnabled() && $this->objSearch->isValidSearch())
        {
            // Data defined successfully, now remove session variables
            $this->objSearch->removeSessionVariables();

            // Set fresh session variables
            $this->objSearch->setMySessionVars();
        }

        return $this->getContent($paramLayout, "EDIT");
    }

    private function getContent($paramLayout = 'Form', $paramMode = "NEW")
    {
        // Load local mandatory classes
        $objPartnersObserver = new PartnersObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objManufacturersObserver = new ManufacturersObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objBodyTypesObserver = new BodyTypesObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objTransmissionTypesObserver = new TransmissionTypesObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objFuelTypesObserver = new FuelTypesObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objLocationObserver = new LocationsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objClosedDatesObserver = new ClosedDatesObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objBookingsObserver = new BookingsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        if($this->locationId > 0)
        {
            // If there is a pickup coming from shortcode parameters
            $pickupLocationId = $this->locationId;
            $returnLocationId = $this->locationId;
        } else
        {
            if($this->pickupLocationId > 0)
            {
                // If there is a pickup coming from shortcode parameters
                $pickupLocationId = $this->pickupLocationId;
            } else
            {
                // If there is only one pickup location for this item or for all items (-1)
                $objLocationObserver = new LocationsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
                $locationIds = $objLocationObserver->getItemPickupIds($this->itemId);
                $pickupLocationId = sizeof($locationIds) == 1 ? $locationIds[0] : -1;
            }
            if($this->returnLocationId > 0)
            {
                // If there is a pickup coming from shortcode parameters
                $returnLocationId = $this->pickupLocationId;
            } else
            {
                // If there is only one pickup location for this item or for all items (-1)
                $objLocationObserver = new LocationsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
                $locationIds = $objLocationObserver->getItemReturnIds($this->itemId);
                $returnLocationId = sizeof($locationIds) == 1 ? $locationIds[0] : -1;
            }
        }
        $objPickupLocation = new Location($this->conf, $this->lang, $this->dbSettings->getSettings(), $pickupLocationId);
        $objReturnLocation = new Location($this->conf, $this->lang, $this->dbSettings->getSettings(), $returnLocationId);

        // Search fields visibility settings
        $pickupLocationVisible = $this->dbSettings->getSearchFieldStatus("pickup_location", "VISIBLE");
        $pickupDateVisible = $this->dbSettings->getSearchFieldStatus("pickup_date", "VISIBLE");
        $returnLocationVisible = $this->dbSettings->getSearchFieldStatus("return_location", "VISIBLE");
        $returnDateVisible = $this->dbSettings->getSearchFieldStatus("return_date", "VISIBLE");
        $partnerVisible = $this->dbSettings->getSearchFieldStatus("partner", "VISIBLE");
        $manufacturerVisible = $this->dbSettings->getSearchFieldStatus("manufacturer", "VISIBLE");
        $bodyTypeVisible = $this->dbSettings->getSearchFieldStatus("body_type", "VISIBLE");
        $transmissionTypeVisible = $this->dbSettings->getSearchFieldStatus("transmission_type", "VISIBLE");
        $fuelTypeVisible = $this->dbSettings->getSearchFieldStatus("fuel_type", "VISIBLE");
        if($paramMode == "NEW")
        {
            $existingBookingCodeVisible = $this->dbSettings->getSearchFieldStatus("booking_code", "VISIBLE");
        } else
        {
            $existingBookingCodeVisible = FALSE;
        }
        $couponCodeVisible = $this->dbSettings->getSearchFieldStatus("booking_code", "VISIBLE");

        // Check if display blocks
        $displaySearchBlock1 = FALSE;
        $displaySearchBlock2 = FALSE;
        $displaySearchBlock3 = FALSE;
        if($paramMode == "NEW")
        {
            $cancelButtonBlock = 0; // Not used
            $searchButtonBlock = 3;
            if($partnerVisible == FALSE && $manufacturerVisible == FALSE && $bodyTypeVisible == FALSE && $transmissionTypeVisible == FALSE && $fuelTypeVisible == FALSE)
            {
                if($returnLocationVisible || $returnDateVisible)
                {
                    $searchButtonBlock = 2;
                } else
                {
                    $searchButtonBlock = 1;
                }
            }

            if($searchButtonBlock == 1 || $pickupLocationVisible || $pickupDateVisible || $existingBookingCodeVisible || $couponCodeVisible)
            {
                $displaySearchBlock1 = TRUE;
            }
            if($searchButtonBlock == 2 || $returnLocationVisible || $returnDateVisible)
            {
                $displaySearchBlock2 = TRUE;
            }
            if($searchButtonBlock == 3 || $partnerVisible || $manufacturerVisible || $bodyTypeVisible || $transmissionTypeVisible || $fuelTypeVisible)
            {
                $displaySearchBlock3 = TRUE;
            }
        } else
        {
            // Edit
            $cancelButtonBlock = 2;
            $searchButtonBlock = 3;
            if($partnerVisible == FALSE && $manufacturerVisible == FALSE && $bodyTypeVisible == FALSE && $transmissionTypeVisible == FALSE && $fuelTypeVisible == FALSE)
            {
                if($returnLocationVisible || $returnDateVisible)
                {
                    $searchButtonBlock = 2;
                } else
                {
                    $searchButtonBlock = 1;
                }
            }

            if($searchButtonBlock == 1 || ($searchButtonBlock == 2 && ($pickupLocationVisible || $pickupDateVisible)))
            {
                $cancelButtonBlock = 1;
            } else if($searchButtonBlock == 3)
            {
                if($returnLocationVisible || $returnDateVisible)
                {
                    $cancelButtonBlock = 2;
                } else if($pickupLocationVisible || $pickupDateVisible)
                {
                    $cancelButtonBlock = 1;
                } else
                {
                    $cancelButtonBlock = 3;
                }
            }

            if($cancelButtonBlock == 1 || $searchButtonBlock == 1 || $pickupLocationVisible || $pickupDateVisible)
            {
                $displaySearchBlock1 = TRUE;
            }
            if($cancelButtonBlock == 2 || $searchButtonBlock == 2 || $returnLocationVisible || $returnDateVisible)
            {
                $displaySearchBlock2 = TRUE;
            }
            if($cancelButtonBlock == 3 || $searchButtonBlock == 3 || $partnerVisible || $manufacturerVisible || $bodyTypeVisible || $transmissionTypeVisible || $fuelTypeVisible)
            {
                $displaySearchBlock3 = TRUE;
            }
        }

        // Get pickup FROM-TO times
        if($pickupLocationId > 0)
        {
            $earliestPickupTime = $objPickupLocation->getWeekEarliestPickupTime();
            $latestPickupTime = $objPickupLocation->getWeekLatestPickupTime();
            $afterHoursPickupLocationId = $objPickupLocation->getAfterHoursPickupLocationId();
            if($afterHoursPickupLocationId > 0)
            {
                $objAfterHoursPickupLocation = new Location($this->conf, $this->lang, $this->dbSettings->getSettings(), $afterHoursPickupLocationId);
                $earliestAfterHoursPickupTime = $objAfterHoursPickupLocation->getWeekEarliestPickupTime();
                $latestAfterHoursPickupTime = $objAfterHoursPickupLocation->getWeekLatestPickupTime();
                if(strtotime(date("Y-m-d")." ".$earliestAfterHoursPickupTime) < strtotime(date("Y-m-d")." ".$earliestPickupTime))
                {
                    $earliestPickupTime = $earliestAfterHoursPickupTime;
                }
                if(strtotime(date("Y-m-d")." ".$latestAfterHoursPickupTime) > strtotime(date("Y-m-d")." ".$latestPickupTime))
                {
                    $latestPickupTime = $latestAfterHoursPickupTime;
                }
            }
        } else
        {
            $earliestPickupTime = "00:00:00";
            $latestPickupTime = "23:30:00";
        }

        // Get return FROM-TO times
        if($returnLocationId > 0)
        {
            $earliestReturnTime = $objReturnLocation->getWeekEarliestReturnTime();
            $latestReturnTime = $objReturnLocation->getWeekLatestReturnTime();
            $afterHoursReturnLocationId = $objReturnLocation->getAfterHoursReturnLocationId();
            if($afterHoursReturnLocationId > 0)
            {
                $objAfterHoursReturnLocation = new Location($this->conf, $this->lang, $this->dbSettings->getSettings(), $afterHoursReturnLocationId);
                $earliestAfterHoursReturnTime = $objAfterHoursReturnLocation->getWeekEarliestReturnTime();
                $latestAfterHoursReturnTime = $objAfterHoursReturnLocation->getWeekLatestReturnTime();
                if(strtotime(date("Y-m-d")." ".$earliestAfterHoursReturnTime) < strtotime(date("Y-m-d")." ".$earliestReturnTime))
                {
                    $earliestReturnTime = $earliestAfterHoursReturnTime;
                }
                if(strtotime(date("Y-m-d")." ".$latestAfterHoursReturnTime) > strtotime(date("Y-m-d")." ".$latestReturnTime))
                {
                    $latestReturnTime = $latestAfterHoursReturnTime;
                }
            }
        } else
        {
            $earliestReturnTime = "00:00:00";
            $latestReturnTime = "23:30:00";
        }

        // Set the view variables
        $this->fillSearchFieldsView(); // Fill search fields view
        $this->fillCustomerFieldsView(); // Fill customer fields view
        $this->view->extensionName = $this->conf->getExtensionName();
        $this->view->itemParameter = $this->conf->getItemParameter();
        $this->view->itemPluralParameter = $this->conf->getItemPluralParameter();
        $this->view->actionPageURL = $this->actionPageId > 0 ? get_permalink($this->actionPageId) : '';
        $this->view->minDate = intval(($this->dbSettings->getSetting('conf_minimum_period_until_pickup') - StaticFormatter::WORLD_TIMEZONES_MAX_DIFFERENCE_IN_SECONDS) / 86400);
        $this->view->newBooking = $this->objSearch->isNewBooking();

        if($paramMode == "NEW")
        {
            // New
            $this->view->selectedPickupDate = $this->lang->getText('NRS_SELECT_BOOKING_DATE_TEXT');
            $this->view->pickupTimeDropDownOptions = StaticFormatter::getTimeDropDownOptions('12:00:00', $earliestPickupTime, $latestPickupTime, $this->lang->getText('NRS_MIDNIGHT_TEXT'), $this->lang->getText('NRS_NOON_TEXT'), array("23:59:59"));
            $this->view->selectedReturnDate = $this->lang->getText('NRS_SELECT_BOOKING_DATE_TEXT');
            $this->view->returnTimeDropDownOptions = StaticFormatter::getTimeDropDownOptions('12:00:00', $earliestReturnTime, $latestReturnTime, $this->lang->getText('NRS_MIDNIGHT_TEXT'), $this->lang->getText('NRS_NOON_TEXT'), array("23:59:59"));

            $this->view->itemId = $this->itemId;
            $this->view->couponCode = $this->lang->getText('NRS_I_HAVE_COUPON_CODE_TEXT');
        } else
        {
            // Edit
            $this->view->selectedPickupDate = $this->objSearch->getShortPickupDate();
            $this->view->pickupTimeDropDownOptions = StaticFormatter::getTimeDropDownOptions($this->objSearch->getISOPickupTime(), $earliestPickupTime, $latestPickupTime, $this->lang->getText('NRS_MIDNIGHT_TEXT'), $this->lang->getText('NRS_NOON_TEXT'), array("23:59:59"));
            $this->view->selectedReturnDate = $this->objSearch->getShortReturnDate();
            $this->view->returnTimeDropDownOptions = StaticFormatter::getTimeDropDownOptions($this->objSearch->getISOReturnTime(), $earliestReturnTime, $latestReturnTime, $this->lang->getText('NRS_MIDNIGHT_TEXT'), $this->lang->getText('NRS_NOON_TEXT'), array("23:59:59"));

            $this->view->itemId = 0;
            $this->view->couponCode = $this->objSearch->getEditCouponCode();
        }


        $this->view->cancelButtonBlock = $cancelButtonBlock;
        $this->view->searchButtonBlock = $searchButtonBlock;

        $this->view->displaySearchBlock1 = $displaySearchBlock1;
        $this->view->displaySearchBlock2 = $displaySearchBlock2;
        $this->view->displaySearchBlock3 = $displaySearchBlock3;

        $this->view->closedDates = $objClosedDatesObserver->getClosedDates($objPickupLocation->getCode(), TRUE);
        $this->view->pickupLocationId = $pickupLocationId;
        $this->view->returnLocationId = $returnLocationId;
        $this->view->pickupLocationName = $objPickupLocation->getPrintTranslatedLocationName();
        $this->view->returnLocationName = $objReturnLocation->getPrintTranslatedLocationName();
        // Use data from objSearch bellow, because only that data can be selected in dropdown, otherwise it will not be used at all if it was set from shortcode
        $this->view->pickupDropDownOptions = $objLocationObserver->getTranslatedPickupDropDownOptions($this->itemId, $this->objSearch->getPickupLocationId(), 0, $this->lang->getText('NRS_PICKUP_CITY_AND_LOCATION_TEXT'), -1);
        $this->view->returnDropDownOptions = $objLocationObserver->getTranslatedReturnDropDownOptions($this->itemId, $this->objSearch->getReturnLocationId(), 0, $this->lang->getText('NRS_RETURN_CITY_AND_LOCATION_TEXT'), -1);
        $this->view->bookingPeriodsDropDownOptions = $objBookingsObserver->getPeriodDropDownOptions($this->lang->getText('NRS_SELECT_BOOKING_PERIOD_TEXT'), $this->objSearch->getBookingPeriod(), "");
        $this->view->partnersDropDownOptions = $objPartnersObserver->getDropDownOptions($this->objSearch->getPartnerId(), -1, $this->lang->getText('NRS_PARTNER_TEXT').':');
        $this->view->manufacturersDropDownOptions = $objManufacturersObserver->getTranslatedDropDownOptions($this->objSearch->getManufacturerId(), -1, $this->lang->getText('NRS_MANUFACTURER_TEXT').':');
        $this->view->bodyTypesDropDownOptions = $objBodyTypesObserver->getTranslatedDropDownOptions($this->objSearch->getBodyTypeId(), -1, $this->lang->getText('NRS_BODY_TYPE_TEXT').':');
        $this->view->transmissionTypesDropDownOptions = $objTransmissionTypesObserver->getTranslatedDropDownOptions($this->objSearch->getTransmissionTypeId(), -1, $this->lang->getText('NRS_TRANSMISSION_TYPE_TEXT').':');
        $this->view->fuelTypesDropDownOptions = $objFuelTypesObserver->getTranslatedDropDownOptions($this->objSearch->getFuelTypeId(), -1, $this->lang->getText('NRS_FUEL_TYPE_TEXT').':');

        // Select which template to show - 'individual car search template' or 'all cars search template'
        $templateName = 'Step1ItemsSearch';
        if($this->itemId > 0 && $paramMode == "NEW")
        {
            $templateName = 'Step1SingleItemSearch';
        } else if($this->locationId > 0 && $paramMode == "NEW")
        {
            $templateName = 'Step1SingleLocationSearch';
        }

        // Get the template
        $retContent = $this->objSearch->searchEnabled() ? $this->getTemplate('Booking', $templateName, $paramLayout) : '';

        return $retContent;
    }
}
