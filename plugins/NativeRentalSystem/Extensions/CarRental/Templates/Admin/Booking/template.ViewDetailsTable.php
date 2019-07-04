<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
// Scripts
wp_enqueue_script('jquery');
wp_enqueue_script('jquery-ui-core');
wp_enqueue_script('jquery-ui-tabs');
wp_enqueue_script('jquery-ui-datepicker', array('jquery','jquery-ui-core'));
wp_enqueue_script('jquery-validate');
wp_enqueue_script('car-rental-admin');

// Styles
wp_enqueue_style('datepicker');
wp_enqueue_style('jquery-validate');
wp_enqueue_style('car-rental-admin');
?>
<p>&nbsp;</p>
<div id="container-inside">
    <span style="font-size:16px; font-weight:bold"><?php print($objLang->getText('NRS_ADMIN_VIEW_DETAILS_TEXT').' - '.$objLang->getText('NRS_BOOKING_CODE_TEXT')); ?>:
    <?php print($bookingCode.($couponCode ? ' ('.$objLang->getText('NRS_COUPON_TEXT').': '.$couponCode.')' : '')); ?>
    </span>
    <input type="submit" value="<?php print($objLang->getText('NRS_ADMIN_BACK_TO_CUSTOMER_BOOKING_LIST_TEXT')); ?>" onClick="window.location.href='<?php print($backToListURL); ?>'" style="cursor: pointer; float: right" />
    <hr style="margin-top:10px;" />
    <table style="font-family:Verdana, Geneva, sans-serif; font-size: 12px; background:#999999; width:840px; border:none;" cellpadding="4" cellspacing="1">
        <tbody>
        <tr>
          <td align="left" style="font-weight:bold; font-variant:small-caps; background:#eeeeee;" colspan="2">
              <strong><?php print($objLang->getText('NRS_ADMIN_CUSTOMER_DETAILS_FROM_DB_TEXT')); ?></strong>
          </td>
        </tr>
        <?php if($titleVisible || $firstNameVisible || $lastNameVisible): ?>
            <tr>
                <td align="left" style="background:#ffffff;" width="160px"><?php print($objLang->getText('NRS_CUSTOMER_TEXT')); ?></td>
                <td align="left" style="background:#ffffff;"><?php print($fullName); ?></td>
            </tr>
        <?php endif; ?>
        <?php if($birthdateVisible): ?>
            <tr>
                <td align="left" style="background:#ffffff;"><?php print($objLang->getText('NRS_DATE_OF_BIRTH_TEXT')); ?></td>
                <td align="left" style="background:#ffffff;"><?php print($birthdate); ?></td>
            </tr>
        <?php endif; ?>
        <?php if($streetAddressVisible): ?>
            <tr>
                <td align="left" style="background:#ffffff;"><?php print($objLang->getText('NRS_STREET_ADDRESS_TEXT')); ?></td>
                <td align="left" style="background:#ffffff;"><?php print($streetAddress); ?></td>
            </tr>
        <?php endif; ?>
        <?php if($cityVisible): ?>
            <tr>
                <td align="left" style="background:#ffffff;"><?php print($objLang->getText('NRS_CITY_TEXT')); ?></td>
                <td align="left" style="background:#ffffff;"><?php print($city); ?></td>
            </tr>
        <?php endif; ?>
        <?php if($stateVisible): ?>
            <tr>
                <td align="left" style="background:#ffffff;"><?php print($objLang->getText('NRS_STATE_TEXT')); ?></td>
                <td align="left" style="background:#ffffff;"><?php print($state); ?></td>
            </tr>
        <?php endif; ?>
        <?php if($zipCodeVisible): ?>
            <tr>
                <td align="left" style="background:#ffffff;"><?php print($objLang->getText('NRS_ZIP_CODE_TEXT')); ?></td>
                <td align="left" style="background:#ffffff;"><?php print($zipCode); ?></td>
            </tr>
        <?php endif; ?>
        <?php if($countryVisible): ?>
            <tr>
                <td align="left" style="background:#ffffff;"><?php print($objLang->getText('NRS_COUNTRY_TEXT')); ?></td>
                <td align="left" style="background:#ffffff;"><?php print($country); ?></td>
            </tr>
        <?php endif; ?>
        <?php if($phoneVisible): ?>
            <tr>
                <td align="left" style="background:#ffffff;"><?php print($objLang->getText('NRS_PHONE_TEXT')); ?></td>
                <td align="left" style="background:#ffffff;"><?php print($phone); ?></td>
            </tr>
        <?php endif; ?>
        <?php if($emailVisible): ?>
            <tr>
                <td align="left" style="background:#ffffff;"><?php print($objLang->getText('NRS_EMAIL_TEXT')); ?></td>
                <td align="left" style="background:#ffffff;"><?php print($email); ?></td>
            </tr>
        <?php endif; ?>
        <?php if($commentsVisible): ?>
            <tr>
                <td align="left" style="background:#ffffff;"><?php print($objLang->getText('NRS_ADDITIONAL_COMMENTS_TEXT')); ?></td>
                <td align="left" style="background:#ffffff;"><?php print($comments); ?></td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
    <?php print($invoiceHTML); ?>
    <br />
    <table style="font-family:Verdana, Geneva, sans-serif; font-size: 12px; background:#999999; width:840px; border:none;" cellpadding="4" cellspacing="1">
        <tr>
            <td align="left" style="font-weight:bold; font-variant:small-caps; background:#eeeeee;" colspan="2"><strong><?php print($objLang->getText('NRS_ADMIN_BOOKING_STATUS_TEXT')); ?></strong></td>
        </tr>
        <tr>
            <td style="text-align:left;font-weight:bold;background:#ffffff;">
                <span style="color:<?php print($paymentStatusColor); ?>;"><?php print($paymentStatus); ?></span>,
                <span style="color:<?php print($bookingStatusColor); ?>;"><?php print($bookingStatus); ?></span>
            </td>
        </tr>
    </table>
</div>