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
<div id="container-inside" style="width:1000px;">
  <span style="font-size:16px; font-weight:bold">Add/Edit Payment Method</span>
  <input type="button" value="Back to payment method list" onClick="window.location.href='<?php print($backToListURL); ?>'" style="background: #EFEFEF; float:right; cursor:pointer;"/>
    <hr style="margin-top:10px;"/>
  <form action="<?php print($formAction); ?>" method="post" id="form1">
    <table cellpadding="5" cellspacing="2" border="0">
        <input type="hidden" name="payment_method_id" value="<?php print($paymentMethodId); ?>"/>
        <tr>
            <td width="20%"><strong>Payment Method Name:<span class="item-required">*<span></strong></td>
            <td width="80%">
                <input type="text" name="payment_method_name" value="<?php print($paymentMethodName); ?>" id="payment_method_name" class="required" style="width:450px;" />
            </td>
        </tr>
        <?php if($objConf->isNetworkEnabled()): ?>
            <tr>
                <td><strong>Payment Method Code:<span class="item-required">*<span></strong></td>
                <td>
                    <input type="text" name="payment_method_code" maxlength="50" value="<?php print($paymentMethodCode); ?>" id="payment_method_code" class="required" style="width:60px;" />
                </td>
            </tr>
        <?php endif; ?>
        <tr>
            <td><strong>Class Name:</strong></td>
            <td>
                <select name="class_name" class="" style="width:450px;">
                    <?php print($paymentMethodClassesDropDownOptions); ?>
                </select><br />
                (Optional, leave blank for payment methods without class integration)
            </td>
        </tr>
        <tr>
            <td><strong>Payment Method E-mail:</strong></td>
            <td>
                <input type="text" name="payment_method_email" id="payment_method_email" value="<?php print($paymentMethodEmail); ?>" class="email" style="width:450px;" />
            </td>
        </tr>
        <tr>
            <td><strong>Description:</strong></td>
            <td>
                <textarea name="payment_method_description" id="payment_method_description" rows="3" cols="50"><?php print($paymentMethodDescription); ?></textarea><br />
                (I.e. &quot;Credit Card Required&quot;, &quot;Cash Payment Only&quot;)
            </td>
        </tr>
        <tr>
            <td><strong>Public Key:</strong></td>
            <td>
                <input type="text" name="public_key" value="<?php print($publicKey); ?>" id="public_key" class="" style="width:450px;" />
            </td>
        </tr>
        <tr>
            <td><strong>Secret Key:</strong></td>
            <td>
                <input type="text" name="private_key" value="<?php print($privateKey); ?>" id="private_key" class="" style="width:450px;" />
            </td>
        </tr>
        <tr>
            <td><strong>Expiration Time:</strong></td>
            <td>
                <select name="expiration_time" id="expiration_time" style="width:450px;">
                    <?php print($expirationTimeDropDown); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong>Work in Sandbox Mode:</strong></td>
            <td>
                <input type="checkbox" name="sandbox_mode" value="yes" id="sandbox_mode"<?php print($sandboxModeChecked); ?> />
            </td>
        </tr>
        <tr>
            <td><strong>Check Certificate:</strong></td>
            <td>
                <input type="checkbox" name="check_certificate" value="yes" id="check_certificate"<?php print($checkCertificateChecked); ?> />
            </td>
        </tr>
        <tr>
            <td><strong>SSL Only (https://):</strong></td>
            <td>
                <input type="checkbox" name="ssl_only" value="yes" id="ssl_only"<?php print($sslOnlyChecked); ?> />
            </td>
        </tr>
        <tr>
            <td><strong>Is Online Payment:</strong></td>
            <td>
                <input type="checkbox" name="online_payment" value="yes" id="online_payment"<?php print($onlinePaymentChecked); ?> />
            </td>
        </tr>
        <tr>
            <td><strong>Enabled:</strong></td>
            <td>
                <input type="checkbox" name="payment_method_enabled" value="yes" id="payment_method_enabled"<?php print($paymentMethodEnabledChecked); ?> />
            </td>
        </tr>
        <tr>
            <td><strong>Order:</strong></td>
            <td>
                <input type="text" name="payment_method_order" value="<?php print($paymentMethodOrder); ?>" id="payment_method_order" class="" style="width:40px;" />
                <em><?php print($paymentMethodId > 0 ? '' : '(optional, leave blank to add to the end)'); ?></em>
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type="submit" value="Save payment method" name="save_payment_method" style="cursor:pointer;"/>
            </td>
        </tr>
    </table>
  </form>
</div>
<script type="text/javascript">
jQuery().ready(function() {
    jQuery("#form1").validate();
});
</script>