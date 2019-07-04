<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
// Styles
wp_enqueue_style('car-rental-frontend');
?>
<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery('#car_rental_do_search').click(function()
        {
            var objBookingID = jQuery('#booking_code');
            var bookingCode = "";
            if(objBookingID.length)
            {
                bookingCode = objBookingID.val();
            }
            //alert('bookingID[len]:' + objBookingID.length + ', bookingID[val]:' + bookingID);

            if(bookingCode != "" && bookingCode != "<?php print($objLang->getText('NRS_I_HAVE_BOOKING_CODE_TEXT')); ?>")
            {
                return true;
            } else
            {
                alert('<?php print(esc_html($objLang->getText('NRS_EDIT_BOOKING_PLEASE_ENTER_BOOKING_NUMBER_TEXT'))); ?>');
                return false;
            }
        });
    });
</script>
<div class="car-rental-wrapper car-rental-search-step1">
    <form id="formElem" name="formElem" action="<?php print($actionPageURL); ?>" method="post">
        <div class="booking-item">
            <div class="booking-item-header">
                <div class="booking-item-title">
                    <?php print($objLang->getText('NRS_EDIT_TEXT')); ?><br />
                    <?php print($objLang->getText('NRS_BOOKING2_TEXT')); ?>
                </div>
                <img src="<?php print($objConf->getExtensionFrontImagesURL('booking-header'.$objLang->getRTLSuffixIfRTL().'.png')); ?>" alt="Reservation" />
            </div>
            <div class="booking-item-body">
                <input type="hidden" name="car_rental_came_from_step1" value="yes" />
                <div class="top-padded">
                    <input id="booking_code" value="<?php print($objLang->getText('NRS_I_HAVE_BOOKING_CODE_TEXT')); ?>" type="text" name="booking_code"
                           onfocus="if(this.value == '<?php print($objLang->getText('NRS_I_HAVE_BOOKING_CODE_TEXT')); ?>') {this.value=''}"
                           onblur="if(this.value == ''){this.value ='<?php print($objLang->getText('NRS_I_HAVE_BOOKING_CODE_TEXT')); ?>'}" />
                </div>
                <div class="top-padded-submit">
                    <input id="car_rental_edit_booking" type="submit" name="car_rental_edit_booking" value="<?php print($objLang->getText('NRS_EDIT_BOOKING_BUTTON_TEXT')); ?>" />
                </div>
            </div>
        </div>
    </form>
</div>