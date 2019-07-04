<?php

session_start();
$nextAviableDate='No cars available. Please change reservation period or modify your search criteria. ';

if(isset($_SESSION['next_aviable_date']) && !empty($_SESSION['next_aviable_date'])) {
   $nextAviableDate=$_SESSION['next_aviable_date'];
}
// remove all session variables
session_unset(); 

// destroy the session 
session_destroy(); 

defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
// Styles
wp_enqueue_style('car-rental-frontend');
?>
<div class="car-rental-wrapper car-rental-booking-failure">
    <div class="booking-failure-title"><?php print($objLang->getText('NRS_BOOKING_FAILURE_TEXT')); ?></div>
    <div class="booking-failure-content">
        <?php print($searchErrors); ?>
        <?php print($nextAviableDate); ?>
        <div class="buttons">
            <form name="inputform" action="" method="post">
                <input type="hidden" name="car_rental_came_from_step1" value="yes" />
                <input type="hidden" name="booking_code" value="" />
                <input type="hidden" name="coupon_code" value="<?php print($objSearch->getEditCouponCode()); ?>" />
                <input type="hidden" name="body_type_id" value="-1" />
                <input type="hidden" name="transmission_type_id" value="-1" />
                <input type="hidden" name="fuel_type_id" value="-1" />
                <input type="hidden" name="pickup_location_id" value="<?php print($objSearch->getPickupLocationId()); ?>" />
                <input type="hidden" name="pickup_date" value="<?php print($objSearch->getShortPickupDate()); ?>" />
                <input type="hidden" name="pickup_time" value="<?php print($objSearch->getISOPickupTime()); ?>" />
                <input type="hidden" name="return_location_id" value="<?php print($objSearch->getReturnLocationId()); ?>" />
                <input type="hidden" name="return_date" value="<?php print($objSearch->getShortReturnDate()); ?>" />
                <input type="hidden" name="return_time" value="<?php print($objSearch->getISOReturnTime()); ?>" />
                <input type="submit" name="go_back" value="<?php print($objLang->getText('NRS_BACK_TEXT')); ?>" class="back-button" onclick="window.location.href='index.php'" />
                <input type="submit" name="car_rental_do_search" value="<?php print($objLang->getText('NRS_BOOKING_FAILURE_SEARCH_ALL_ITEMS_TEXT')); ?>" class="back-button">
            </form>
        </div>
    </div>
    <div class="clear">&nbsp;</div>
</div>