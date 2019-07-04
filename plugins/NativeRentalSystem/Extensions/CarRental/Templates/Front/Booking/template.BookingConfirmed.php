<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
// Styles
wp_enqueue_style('car-rental-frontend');
if($newBooking == TRUE && $objSettings->getSetting('conf_universal_analytics_enhanced_ecommerce') == 1):
    include('partial.Step5.EnhancedEcommerce.php');
endif;
?>
<div class="car-rental-wrapper car-rental-booking-confirmed">
    <h2><?php print($objLang->getText('NRS_THANK_YOU_TEXT')); ?></h2>
    <div class="booking-content">
        <?php
        print($objLang->getText('NRS_YOUR_BOOKING_CONFIRMED_TEXT').' '.$bookingCode.'.');
        if($objSettings->getSetting('conf_send_emails')):
            print(' '.$objLang->getText('NRS_INVOICE_SENT_TO_YOUR_EMAIL_ADDRESS_TEXT').'.');
        endif;
        if($emailSentSuccessfully == FALSE):
            print('<br /><br />'.$objLang->getText('NRS_ERROR_SYSTEM_IS_UNABLE_TO_SEND_EMAIL_TEXT'));
        endif;
        ?>
    </div>
</div>
<div class="clear">&nbsp;</div>


