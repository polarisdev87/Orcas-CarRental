<?php
/**
 * Search must-have interface
 * Interface purpose is describe all public methods used available in the class and enforce to use them
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Search;

interface iSearchManager {
    // System methods
    public function inDebug();
    public function getOkayMessages();
    public function getErrorMessages();
    public function isValidSearch();

    // Main methods
    public function setVariablesByRequestOrSessionParams();

    // Time & Date methods for printing to the user screen (Applies formatting, localization and GMT zone adjustment
    public function getPrintPickupDate();
    public function getPrintPickupTime();
    public function getPrintReturnDate();
    public function getPrintReturnTime();
    public function getShortPickupDate();
    public function getISOPickupTime();
    public function getShortReturnDate();
    public function getISOReturnTime();
    public function getPrintBookingDuration();

    // Time & Date methods for system use
    public function getLocalPickupDate();
    public function getLocalPickupTime();
    public function getLocalReturnDate();
    public function getLocalReturnTime();
    public function getLocalPickupDayOfWeek();
    public function getLocalReturnDayOfWeek();
    public function getBookingPeriod();
    public function getPickupTimestamp();
    public function getReturnTimestamp();

    // Methods to retrieve booking location, item, customer and booking details
    public function isNewBooking();
    public function getBookingCode();
    public function getPrintBookingCode();
    public function getEditBookingCode();
    public function getCouponCode();
    public function getPrintCouponCode();
    public function getEditCouponCode();
    public function getPickupLocationId();
    public function getReturnLocationId();
    public function getBodyTypeId();
    public function getTransmissionTypeId();
    public function getFuelTypeId();
    public function getSearchInputDataArray();
    public function getItemIds();
    public function getItemUnits();
    public function getItemOptions();
    public function getExtraIds();
    public function getExtraUnits();
    public function getExtraOptions();
    public function getItemQuantity($paramItemId);
    public function getItemOption($paramItemId);
    public function getExtraQuantity($paramExtraId);
    public function getExtraOption($paramExtraId);
}