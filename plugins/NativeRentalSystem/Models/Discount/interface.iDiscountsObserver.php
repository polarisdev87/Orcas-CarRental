<?php
/**
 * Discounts Observer must-have interface
 * Interface purpose is describe all public methods used available in the class and enforce to use them
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Discount;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Language\Language;

interface iDiscountsObserver {
    public function getAdminListForDiscountDuration();
    public function getAdminListForBookingInAdvance();
}