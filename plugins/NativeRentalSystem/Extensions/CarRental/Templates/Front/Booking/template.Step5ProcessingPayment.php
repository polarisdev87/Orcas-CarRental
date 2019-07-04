<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
// Styles
wp_enqueue_style('car-rental-frontend');
if($newBooking == TRUE && $objSettings->getSetting('conf_universal_analytics_enhanced_ecommerce') == 1):
    include('partial.Step5.EnhancedEcommerce.php');
endif;
?>
<div class="car-rental-wrapper car-rental-booking-confirmed">
    <?php print($processingPageContent); ?>
    <div class="clear">&nbsp;</div>
</div>


