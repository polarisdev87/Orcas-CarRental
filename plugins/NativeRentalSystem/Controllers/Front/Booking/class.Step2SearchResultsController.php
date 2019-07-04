<?php
/**
 * Booking step no. 2
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Front\Booking;
use NativeRentalSystem\Models\Booking\BookingsObserver;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Distance\Distance;
use NativeRentalSystem\Models\Distance\DistancesObserver;
use NativeRentalSystem\Models\Item\BodyType;
use NativeRentalSystem\Models\Item\BodyTypesObserver;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Booking\Booking;
use NativeRentalSystem\Models\Deposit\DepositsObserver;
use NativeRentalSystem\Models\Item\ItemsObserver;
use NativeRentalSystem\Models\Location\Location;
use NativeRentalSystem\Models\Pricing\DistanceFeeManager;
use NativeRentalSystem\Models\Tax\TaxManager;
use NativeRentalSystem\Models\Pricing\LocationFeeManager;
use NativeRentalSystem\Models\Search\FrontEndSearchManager;
use NativeRentalSystem\Models\Search\ItemSearchManager;
use NativeRentalSystem\Models\Search\ExtraSearchManager;
use NativeRentalSystem\Controllers\Front\AbstractController;

final class Step2SearchResultsController extends AbstractController
{
    protected $objSearch = NULL;

    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, $paramArrLimitations = array())
    {
        parent::__construct($paramConf, $paramLang, $paramArrLimitations);

        $this->objSearch = new FrontEndSearchManager($this->conf, $this->lang, $this->dbSettings->getSettings());

        // First - process request
        $this->processRequest();
    }

    private function processRequest()
    {
        //echo "INITIAL REQUEST VARS: ".nl2br(print_r($_REQUEST, TRUE));
        //echo "INITIAL SESSION VARS: ".nl2br(print_r($_SESSION, TRUE));

        $objBookingObservers = new BookingsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());

        // First - clear old expired bookings
        $objBookingObservers->clearExpired();

        // Second - validate the booking code
        $this->objSearch->setBookingCode($this->dbSettings->getSearchFieldStatus("booking_code", "REQUIRED"));
        if($this->objSearch->getBookingCode() != '')
        {
            $objBookingsObserver = new BookingsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
            $this->objSearch->setVariablesByBookingId($objBookingsObserver->getIdByCode($this->objSearch->getBookingCode()));
        }

        // Third - set object variables - allow to override by _POST, _GET or _SESSION
        $this->objSearch->setVariablesByRequestOrSessionParams();

        // Fourth - validate time input
        $this->objSearch->validateTimeInput($this->objSearch->getPickupTimestamp(), $this->objSearch->getReturnTimestamp());

        // Fifth - validate pick-up
        $this->objSearch->validatePickupInput($this->objSearch->getPickupLocationId(), $this->objSearch->getPickupTimestamp());

        // Sixth - validate return
        $this->objSearch->validateReturnInput($this->objSearch->getReturnLocationId(), $this->objSearch->getReturnTimestamp());

        if ($this->objSearch->searchEnabled() && $this->objSearch->isValidSearch())
        {
            // Data defined successfully, now remove session variables
            $this->objSearch->removeSessionVariables();

            // Set fresh session variables
            $this->objSearch->setMySessionVars();
        }

        //echo "UPDATED SESSION VARS: ".nl2br(print_r($_SESSION, TRUE));
    }

    public function getContent($paramLayout = 'List')
    {
        // Load local mandatory classes
        $objBookingsObserver = new BookingsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objBooking = new Booking($this->conf, $this->lang, $this->dbSettings->getSettings(), $objBookingsObserver->getIdByCode($this->objSearch->getBookingCode()));
        $objItemsObserver = new ItemsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objDepositsObserver = new DepositsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objDistancesObserver = new DistancesObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objTaxManager = new TaxManager($this->conf, $this->lang, $this->dbSettings->getSettings());
        $taxPercentage = $objTaxManager->getTaxPercentage($this->objSearch->getPickupLocationId(), $this->objSearch->getReturnLocationId());
        $objPickupLocation = new Location(
            $this->conf, $this->lang, $this->dbSettings->getSettings(), $this->objSearch->getPickupLocationId()
        );
        $locationCode = $objPickupLocation->getCode(); // We use pickup location code for availability checks
        $objReturnLocation = new Location(
            $this->conf, $this->lang, $this->dbSettings->getSettings(), $this->objSearch->getReturnLocationId()
        );
        $distanceId = $objDistancesObserver->getDistanceIdByTwoLocations($this->objSearch->getPickupLocationId(), $this->objSearch->getReturnLocationId());
        $objDistance = new Distance($this->conf, $this->lang, $this->dbSettings->getSettings(), $distanceId);
        $objPickupFeeManager = new LocationFeeManager(
            $this->conf, $this->lang, $this->dbSettings->getSettings(), $this->objSearch->getPickupLocationId(), $taxPercentage
        );
        $objReturnFeeManager = new LocationFeeManager(
            $this->conf, $this->lang, $this->dbSettings->getSettings(), $this->objSearch->getReturnLocationId(), $taxPercentage
        );
        $objDistanceFeeManager = new DistanceFeeManager(
            $this->conf, $this->lang, $this->dbSettings->getSettings(), $this->objSearch->getPickupLocationId(), $this->objSearch->getReturnLocationId(), $taxPercentage
        );
        $objAfterHoursPickupLocation = new Location(
            $this->conf, $this->lang, $this->dbSettings->getSettings(), $objPickupLocation->getAfterHoursPickupLocationId()
        );
        $objAfterHoursReturnLocation = new Location(
            $this->conf, $this->lang, $this->dbSettings->getSettings(), $objReturnLocation->getAfterHoursReturnLocationId()
        );

        $items = array();
        $extras = array();
        $itemTypesWithItems = array();
        $gotResults = FALSE;

        // Get all data
        $pickupInAfterHours = $objPickupLocation->isAfterHoursTime($this->objSearch->getLocalPickupDayOfWeek(), $this->objSearch->getLocalPickupTime());
        $returnInAfterHours = $objReturnLocation->isAfterHoursTime($this->objSearch->getLocalReturnDayOfWeek(), $this->objSearch->getLocalReturnTime());
        $pickupDetails = $objPickupLocation->getWeekdayDetails($this->objSearch->getLocalPickupDayOfWeek());
        $returnDetails = $objReturnLocation->getWeekdayDetails($this->objSearch->getLocalReturnDayOfWeek());
        $pickupFees = $objPickupFeeManager->getUnitDetails($objDistanceFeeManager->getUnitFee(), $pickupInAfterHours);
        $returnFees = $objReturnFeeManager->getUnitDetails($objDistanceFeeManager->getUnitFee(), $returnInAfterHours);

        $pickupOpenTime = isset($pickupDetails['open_time']) ? $pickupDetails['open_time'] : "";
        $pickupCloseTime = isset($pickupDetails['close_time']) ? $pickupDetails['close_time'] : "";
        $returnOpenTime = isset($returnDetails['open_time']) ? $returnDetails['open_time'] : "";
        $returnCloseTime = isset($returnDetails['close_time']) ? $returnDetails['close_time'] : "";
        $afterHoursPickupDetails = $objAfterHoursPickupLocation->getAfterHoursDetails($pickupOpenTime, $pickupCloseTime, $this->objSearch->getLocalPickupDayOfWeek());
        $afterHoursReturnDetails = $objAfterHoursReturnLocation->getAfterHoursDetails($returnOpenTime, $returnCloseTime, $this->objSearch->getLocalReturnDayOfWeek());

        $pickupIsWorkingInAfterHours = $objAfterHoursPickupLocation->isValidForAfterHoursPickup($this->objSearch->getLocalPickupDayOfWeek(), $pickupOpenTime, $pickupCloseTime);
        $returnIsWorkingInAfterHours = $objAfterHoursReturnLocation->isValidForAfterHoursReturn($this->objSearch->getLocalReturnDayOfWeek(), $returnOpenTime, $returnCloseTime);

        if($this->objSearch->isNewBooking() == FALSE)
        {
            $pageLabel = $this->lang->getText('NRS_BOOKING_CODE_TEXT')." ".$this->objSearch->getPrintBookingCode()." ".$this->lang->getText('NRS_BOOKING_EDIT_TEXT');
            if($this->objSearch->getCouponCode() != '')
            {
                $pageLabel .= '. '.$this->lang->getText('NRS_COUPON_TEXT').': '.$this->objSearch->getPrintCouponCode();
            }
        } else
        {
            $pageLabel = $this->lang->getText('NRS_BOOKING_DATA_TEXT');
            if($this->objSearch->getCouponCode() != '')
            {
                $pageLabel .= '. '.$this->lang->getText('NRS_COUPON_TEXT').': '.$this->objSearch->getPrintCouponCode();
            }
        }

        if($this->objSearch->isValidSearch())
        {
            $gotResults = FALSE;
            $objSearchManagerForItems = new ItemSearchManager(
                $this->conf, $this->lang, $this->dbSettings->getSettings(), $taxPercentage, $locationCode,
                $objBooking->getId(), $this->objSearch->getCouponCode()
            );
            $availableItemIds = $objSearchManagerForItems->getAvailableItemIds(
                $this->objSearch->getPickupLocationId(),
                $this->objSearch->getReturnLocationId(),
                $this->objSearch->getPartnerId(),
                $this->objSearch->getManufacturerId(),
                $this->objSearch->getBodyTypeId(),
                $this->objSearch->getTransmissionTypeId(),
                $this->objSearch->getFuelTypeId()
            );
            $items = $objSearchManagerForItems->getItemsWithPricesAndOptions(
                $availableItemIds, $this->objSearch->getItemUnits(), $this->objSearch->getItemOptions(),
                $this->objSearch->getPickupTimestamp(), $this->objSearch->getReturnTimestamp(), FALSE
            );
            $objSearchManagerForExtras = new ExtraSearchManager(
                $this->conf, $this->lang, $this->dbSettings->getSettings(), $taxPercentage, $locationCode,
                $objBooking->getId(), $availableItemIds
            );
            $availableExtraIds = $objSearchManagerForExtras->getAvailableExtraIds();
            $extras = $objSearchManagerForExtras->getExtrasWithPricesAndOptions(
                $availableExtraIds, $this->objSearch->getExtraUnits(), $this->objSearch->getExtraOptions(),
                $this->objSearch->getPickupTimestamp(), $this->objSearch->getReturnTimestamp(), FALSE
            );

            if($objItemsObserver->areItemsClassified())
            {
                $objBodyTypes = new BodyTypesObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
                $bodyTypeIds = $objBodyTypes->getAllIds(TRUE);
                $itemTypesWithItems = array();
                foreach($bodyTypeIds AS $bodyTypeId)
                {
                    $objBodyType = new BodyType($this->conf, $this->lang, $this->dbSettings->getSettings(), $bodyTypeId);
                    $type = $objBodyType->getDetails(TRUE);
                    $type['got_search_result'] = FALSE;
                    $availableItemIdsForCurrentBodyType = $objSearchManagerForItems->getAvailableItemIds(
                        $this->objSearch->getPickupLocationId(),
                        $this->objSearch->getReturnLocationId(),
                        $this->objSearch->getPartnerId(),
                        $this->objSearch->getManufacturerId(),
                        $type['body_type_id'],
                        $this->objSearch->getTransmissionTypeId(),
                        $this->objSearch->getFuelTypeId()
                    );
                    $type['items'] = $objSearchManagerForItems->getItemsWithPricesAndOptions(
                        $availableItemIdsForCurrentBodyType, $this->objSearch->getItemUnits(), $this->objSearch->getItemOptions(),
                        $this->objSearch->getPickupTimestamp(), $this->objSearch->getReturnTimestamp(), FALSE
                    );

                    if(sizeof($type['items']) > 0)
                    {
                        // Sort items by price
                        $sortableItems = $type['items'];
                        uasort($sortableItems, array('\NativeRentalSystem\Models\Formatting\StaticFormatter','priceCompare'));
                        $type['items'] = $sortableItems;

                        $type['got_search_result'] = TRUE;
                        $gotResults = TRUE;

                        // Add to stack
                        $itemTypesWithItems[] = $type;
                    }
                }
            } else
            {
                // Get all items from body type 0 and other body types (last '-1' parameters means, that we skip body type check)
                if(sizeof($items) > 0)
                {
                    // Sort items by price
                    uasort($items, array('\NativeRentalSystem\Models\Formatting\StaticFormatter', 'priceCompare'));

                    $gotResults = TRUE;
                }
            }

            if($gotResults)
            {
                $templateName = $objItemsObserver->areItemsClassified() ? 'Step2ClassifiedSearchResults' : 'Step2SearchResults';
            } else
            {
                $templateName = 'BookingFailureWithSearchAllButtons';
                $paramLayout = '';
            }
        } else
        {
            $templateName = 'BookingFailure'; // Failure template
            $paramLayout = '';
        }

        $showLocationSimpleFees = FALSE;
        if(is_array($pickupFees) && $pickupFees['unit'][$pickupInAfterHours ? 'afterhours_return_with_distance_fee_dynamic' : 'return_with_distance_fee_dynamic'] > 0.00)
        {
            $showLocationSimpleFees = TRUE;
        } else if(is_array($returnFees) && $returnFees['unit'][$returnInAfterHours ? 'afterhours_return_with_distance_fee_dynamic' : 'return_with_distance_fee_dynamic'] > 0.00)
        {
            $showLocationSimpleFees = TRUE;
        }
        $showWorkingHours = FALSE;
        if(is_array($pickupDetails) && $pickupDetails['afterhours_pickup_allowed'] == 0 && ($pickupDetails['open_time'] != "00:00:00" || $pickupDetails['close_time'] != "23:59:59"))
        {
            $showWorkingHours = TRUE;
        }
        $showWorkingHours = FALSE;
        if(is_array($returnDetails) && $returnDetails['afterhours_return_allowed'] == 0 && ($returnDetails['open_time'] != "00:00:00" || $returnDetails['close_time'] != "23:59:59"))
        {
            $showWorkingHours = TRUE;
        }

        // Set the view variables
        $this->fillSearchFieldsView(); // Fill search fields view
        $this->fillCustomerFieldsView(); // Fill customer fields view
        $this->view->extensionName = $this->conf->getExtensionName();
        $this->view->objSearch = $this->objSearch;
        $this->view->pageLabel = $pageLabel;
        $this->view->items = $items; // We use it for Enhanced Ecommerce and when item classification is off
        $this->view->extras = $extras; // We use it for Enhanced Ecommerce only
        $this->view->itemTypesWithItems = $itemTypesWithItems;
        $this->view->pickup = is_array($pickupDetails) && is_array($pickupFees) ? array_merge($pickupDetails, $pickupFees) : array();
        $this->view->return = is_array($returnDetails) && is_array($returnFees) ? array_merge($returnDetails, $returnFees) : array();
        $this->view->distance = $objDistance->getDetails();
        $this->view->complexPickup = $objPickupLocation->isComplexLocation();
        $this->view->complexReturn = $objReturnLocation->isComplexLocation();
        $this->view->showLocationSimpleFees = $showLocationSimpleFees;
        $this->view->showWorkingHours = $showWorkingHours;
        $this->view->showWorkingHours = $showWorkingHours;
        $this->view->pickupIsWorkingInAfterHours = $pickupIsWorkingInAfterHours;
        $this->view->returnIsWorkingInAfterHours = $returnIsWorkingInAfterHours;
        $this->view->pickupInAfterHours = $pickupInAfterHours;
        $this->view->returnInAfterHours = $returnInAfterHours;
        $this->view->afterHoursPickupDetails = $afterHoursPickupDetails;
        $this->view->afterHoursReturnDetails = $afterHoursReturnDetails;
        $this->view->gotResults = $gotResults;
        $this->view->depositsEnabled = $objDepositsObserver->areDepositsEnabled();
        $this->view->multiMode = $this->dbSettings->getSetting('conf_booking_model') == 2 ? TRUE : FALSE; // Can we select more than more different item
        $this->view->newBooking = $this->objSearch->isNewBooking();
        $this->view->searchErrors = implode("<br /><br />", $this->objSearch->getErrorMessages());

        // Get the template
        $retContent = $this->objSearch->searchEnabled() ? $this->getTemplate('Booking', $templateName, $paramLayout) : '';

        return $retContent;
    }
}