<?php
/**
 * Search must-have interface
 * Interface purpose is describe all public methods used available in the class and enforce to use them
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Block;

interface iBlockManager {
    // System methods
    public function inDebug();
    public function getOkayMessages();
    public function getErrorMessages();
    public function isValidBlock();

    // Main methods
    public function setVariablesByRequestOrSessionParams();

    // Time & Date methods for printing to the user screen (Applies formatting, localization and GMT zone adjustment
    public function getPrintStartDate();
    public function getPrintStartTime();
    public function getPrintEndDate();
    public function getPrintEndTime();
    public function getShortStartDate();
    public function getShortStartTime();
    public function getShortEndDate();
    public function getShortEndTime();
    public function getPrintBlockDuration();

    // Time & Date methods for system use
    public function getLocalStartDate();
    public function getLocalStartTime();
    public function getLocalEndDate();
    public function getLocalEndTime();
    public function getLocalStartDayOfWeek();
    public function getLocalEndDayOfWeek();
    public function getBlockPeriod();
    public function getStartTimestamp();
    public function getEndTimestamp();

    // Methods to retrieve ids, units and elements
    public function getIds();
    public function getUnits($paramElementId);
    public function getAvailable();
    public function getSelectedWithDetails($paramElementIds);
    public function getAvailableWithDetails($paramElementIds);
}