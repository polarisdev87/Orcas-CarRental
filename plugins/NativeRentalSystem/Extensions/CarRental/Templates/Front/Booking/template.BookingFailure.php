<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
// Styles
wp_enqueue_style('car-rental-frontend');
?>
<div class="car-rental-wrapper car-rental-booking-failure">
    <div class="booking-failure-title"><?php print($objLang->getText('NRS_BOOKING_FAILURE_TEXT')); ?></div>
    <div class="booking-failure-content">
        <?php print($searchErrors); ?>
        <div class="buttons">
            <button type="submit" class="back-button" onclick="window.location.href='index.php'"><?php print($objLang->getText('NRS_BACK_TEXT')); ?></button>
        </div>
    </div>
    <div class="clear">&nbsp;</div>
</div>