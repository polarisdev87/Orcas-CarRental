<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
// Styles
wp_enqueue_style('car-rental-frontend');
?>
<table style="font-family:Verdana, Geneva, sans-serif; font-size: 12px; background-color:#eeeeee; width:840px; border:none;" cellpadding="5" cellspacing="1">
    <tbody>
    <tr>
        <td align="left" style="font-weight:bold; background-color:#eeeeee; padding-left:5px;" colspan="2"><?php print($objLang->getText('NRS_CUSTOMER_DETAILS_TEXT')); ?></td>
    </tr>
    <tr>
        <td align="left" style="width:160px; background-color:#ffffff; padding-left:5px;"><?php print($objLang->getText('NRS_BOOKING_CODE_TEXT')); ?></td>
        <td align="left" style="background-color:#ffffff; padding-left:5px;"><?php print($bookingCode); ?></td>
    </tr>
    <?php if($couponCodeVisible && $couponCode != ''): ?>
        <tr>
            <td align="left" style="background-color:#ffffff; padding-left:5px;"><?php print($objLang->getText('NRS_COUPON_CODE_TEXT')); ?></td>
            <td align="left" style="background-color:#ffffff; padding-left:5px;"><?php print($couponCode); ?></td>
        </tr>
    <?php endif; ?>
    <?php if($titleVisible || $firstNameVisible || $lastNameVisible): ?>
        <tr>
            <td align="left" style="background-color:#ffffff; padding-left:5px;"><?php print($objLang->getText('NRS_CUSTOMER_TEXT')); ?></td>
            <td align="left" style="background-color:#ffffff; padding-left:5px;"><?php print($customerDetails['print_full_name']); ?></td>
        </tr>
    <?php endif; ?>
    <?php if($birthdateVisible): ?>
        <tr>
            <td align="left" style="background-color:#ffffff; padding-left:5px;"><?php print($objLang->getText('NRS_DATE_OF_BIRTH_TEXT')); ?></td>
            <td align="left" style="background-color:#ffffff; padding-left:5px;"><?php print($customerDetails['birthdate']); ?></td>
        </tr>
    <?php endif; ?>
    <?php if($streetAddressVisible): ?>
        <tr>
            <td align="left" style="background-color:#ffffff; padding-left:5px;"><?php print($objLang->getText('NRS_STREET_ADDRESS_TEXT')); ?></td>
            <td align="left" style="background-color:#ffffff; padding-left:5px;"><?php print($customerDetails['street_address']); ?></td>
        </tr>
    <?php endif; ?>
    <?php if($cityVisible): ?>
        <tr>
            <td align="left" style="background-color:#ffffff; padding-left:5px;"><?php print($objLang->getText('NRS_CITY_TEXT')); ?></td>
            <td align="left" style="background-color:#ffffff; padding-left:5px;"><?php print($customerDetails['city']); ?></td>
        </tr>
    <?php endif; ?>
    <?php if($stateVisible): ?>
        <tr>
            <td align="left" style="background-color:#ffffff; padding-left:5px;"><?php print($objLang->getText('NRS_STATE_TEXT')); ?></td>
            <td align="left" style="background-color:#ffffff; padding-left:5px;"><?php print($customerDetails['state']); ?></td>
        </tr>
    <?php endif; ?>
    <?php if($zipCodeVisible): ?>
        <tr>
            <td align="left" style="background-color:#ffffff; padding-left:5px;"><?php print($objLang->getText('NRS_ZIP_CODE_TEXT')); ?></td>
            <td align="left" style="background-color:#ffffff; padding-left:5px;"><?php print($customerDetails['zip_code']); ?></td>
        </tr>
    <?php endif; ?>
    <?php if($countryVisible): ?>
        <tr>
            <td align="left" style="background-color:#ffffff; padding-left:5px;"><?php print($objLang->getText('NRS_COUNTRY_TEXT')); ?></td>
            <td align="left" style="background-color:#ffffff; padding-left:5px;"><?php print($customerDetails['country']); ?></td>
        </tr>
    <?php endif; ?>
    <?php if($phoneVisible): ?>
        <tr>
            <td align="left" style="background-color:#ffffff; padding-left:5px;"><?php print($objLang->getText('NRS_PHONE_TEXT')); ?></td>
            <td align="left" style="background-color:#ffffff; padding-left:5px;"><?php print($customerDetails['phone']); ?></td>
        </tr>
    <?php endif; ?>
    <?php if($emailVisible): ?>
        <tr>
            <td align="left" style="background-color:#ffffff; padding-left:5px;"><?php print($objLang->getText('NRS_EMAIL_TEXT')); ?></td>
            <td align="left" style="background-color:#ffffff; padding-left:5px;"><?php print($customerDetails['email']); ?></td>
        </tr>
    <?php endif; ?>
    <?php if($commentsVisible): ?>
        <tr>
            <td align="left" style="background-color:#ffffff; padding-left:5px;"><?php print($objLang->getText('NRS_ADDITIONAL_COMMENTS_TEXT')); ?></td>
            <td align="left" style="background-color:#ffffff; padding-left:5px;"><?php print($customerDetails['print_comments']); ?></td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>
<br />
<table style="font-family:Verdana, Geneva, sans-serif; font-size: 12px; background:#999999; width:840px; border:none;" cellpadding="5" cellspacing="1">
<tbody>
<tr>
    <td align="left" style="font-weight:bold; background-color:#eeeeee; padding-left:5px;" colspan="3"><?php print($objLang->getText('NRS_ITEM_RENTAL_DETAILS_TEXT')); ?></td>
</tr>
<?php include('partial.BookingSummary.php'); ?>
</tbody>
</table>

<!-- PAYMENT METHOD DETAILS -->
<?php if($showPaymentDetails): ?>
    <br />
    <table style="font-family:Verdana, Geneva, sans-serif; font-size: 12px; background:#999999; width:840px; border:none;" cellpadding="4" cellspacing="1">
        <tr>
            <td align="left" colspan="2" style="font-weight:bold; background-color:#eeeeee; padding-left:5px;"><?php print($objLang->getText('NRS_PAYMENT_DETAILS_TEXT')); ?></td>
        </tr>
        <tr>
            <td align="left" width="30%" style="font-weight:bold; background-color:#ffffff; padding-left:5px;"><?php print($objLang->getText('NRS_PAYMENT_OPTION_TEXT')); ?></td>
            <td align="left" style="background-color:#ffffff; padding-left:5px;"><?php print($paymentMethodName); ?></td>
        </tr>
    </table>
<?php endif; ?>