<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
// Styles
wp_enqueue_style('car-rental-frontend');
?>
<div class="car-rental-wrapper car-rental-booking-cancelled">
    <h2><?php print($objLang->getText($cancelledSuccessfully ? 'NRS_THANK_YOU_TEXT' : 'NRS_BOOKING_FAILURE_TEXT')); ?></h2>
    <div class="booking-content">
    <?php
    if($cancelledSuccessfully):
        print($objLang->getText('NRS_BOOKING_CODE_TEXT')." ".$bookingCode." ".$objLang->getText('NRS_CANCELLED_SUCCESSFULLY_TEXT').".");
    else:
        print($objLang->getText('NRS_NOT_CANCELLED_TEXT').". ".$objLang->getText('NRS_BOOKING_CODE_TEXT')." ".$bookingCode." ".$objLang->getText('NRS_ERROR_BOOKING_DOES_NOT_EXIST_TEXT').".");
    endif;
    ?>
    </div>
    <div class="clear">&nbsp;</div>
</div>