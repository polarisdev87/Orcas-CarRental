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
<div id="container-inside" style="width:1000px;"> <span style="font-size:16px; font-weight:bold">Add/Edit Extra</span>
  <input type="button" value="Back to Extras List" onClick="window.location.href='<?php print($backToListURL); ?>'" style="background: #EFEFEF; float:right; cursor:pointer;"/>
  <hr style="margin-top:10px;"/>
  <form action="<?php print($formAction); ?>" method="post" id="form1">
    <table cellpadding="5" cellspacing="2" border="0">
      <input type="hidden" name="extra_id" value="<?php print($extraId); ?>"/>
        <tr>
            <td><strong>Extra Name:<span class="item-required">*</span></strong></td>
            <td>
                <input type="text" name="extra_name" value="<?php print($extraName); ?>" id="extra_name" class="required" style="width:200px;" />
            </td>
        </tr>
        <?php if($objConf->isNetworkEnabled()): ?>
            <tr>
                <td><strong>Stock Keeping Unit:<span class="item-required">*</span></strong></td>
                <td><input type="text" name="extra_sku" maxlength="50" value="<?php print($extraSKU); ?>" id="extra_sku" class="required" style="width:170px;" /><br />
                    &nbsp;&nbsp;&nbsp; <em>(Used for Google Enhanced Ecommerce tracking<br />
                        and when plugin is network-enabled in multisite mode)</em>
                </td>
            </tr>
        <?php endif; ?>
        <?php if($isManager): ?>
            <tr>
                <td><strong>Partner:</strong></td>
                <td>
                    <select name="partner_id" id="partner_id">
                        <?php print($partnersDropDownOptions); ?>
                    </select>
                </td>
            </tr>
        <?php endif; ?>
        <tr>
            <td><strong>Select a Car</strong>:</td>
            <td>
                <select name="item_id">
                    <?php print($itemDropDownOptions); ?>
                </select>
                &nbsp;&nbsp;&nbsp; <em>(optional, can be left blank. Use it to show this extra only to specific car)</em>
            </td>
        </tr>
        <tr>
            <td><strong>Total Units in Stock:<span class="item-required">*</span></strong></td>
            <td>
                <select name="units_in_stock" id="units_in_stock" class="required">
                    <?php print($unitsInStockDropDownOptions); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong>Max. Units per Reservation:<span class="item-required">*</span></strong></td>
            <td>
                <select name="max_units_per_booking" id="max_units_per_booking" class="required">
                    <?php print($maxUnitsPerBookingDropDownOptions); ?>
                </select>
                &nbsp;&nbsp;&nbsp; <em>(Can&#39;t be more than total extra units in stock)</em>
            </td>
        </tr>
        <tr>
            <td><strong>Price:<span class="item-required">*</span></strong></td>
            <td>
                <input type="text" name="price" value="<?php print($extraPrice); ?>" id="price" class="required number" style="width:70px;" />
                &nbsp;
                <?php print($objSettings->getSetting('conf_currency_code')); ?>
                &nbsp;&nbsp;&nbsp; <em>(Without <?php print($objLang->getText('NRS_TAX_TEXT')); ?>)</em>
            </td>
        </tr>
        <tr>
            <td><strong>Price Type:</strong></td>
            <td>
                <select name="price_type" id="price_type" class="required">
                    <?php print($priceTypeDropDownOptions); ?>
                </select>
            </td>
        </tr>
        <?php if($depositsEnabled): ?>
            <tr>
                <td><strong>Fixed Rental Deposit:<span class="item-required">*</span></strong></td>
                <td>
                    <input type="text" name="fixed_rental_deposit" value="<?php print($fixedExtraRentalDeposit); ?>" id="fixed_rental_deposit" class="required number" style="width:70px;" />
                    &nbsp;
                    <?php print($objSettings->getSetting('conf_currency_code')); ?>
                    &nbsp;&nbsp;&nbsp; <em>(<?php print($objLang->getText('NRS_TAX_TEXT')); ?> is not applicable for deposit - it is a refundable amount with no <?php print($objLang->getText('NRS_TAX_TEXT')); ?> applied to it)</em>
                </td>
            </tr>
        <?php else: ?>
            <input type="hidden" name="fixed_rental_deposit" value="<?php print($fixedExtraRentalDeposit); ?>" />
        <?php endif; ?>
        <tr>
            <td><strong>Units of Measurement:</strong></td>
            <td>
                <input type="text" name="options_measurement_unit" value="<?php print($optionsMeasurementUnit); ?>" id="options_measurement_unit" class="" style="width:200px;" />
                &nbsp;&nbsp;&nbsp; <em>(optional, can be left blank. Might be used if some extra options added)</em>
            </td>
        </tr>
        <tr>
            <td><strong>Options display mode:</strong><br /><em>(if added)</em></td>
            <td>
                <input type="radio" name="options_display_mode" value="1"<?php print($dropDownDisplayModeChecked); ?> /> <?php print($objLang->getText('NRS_ADMIN_DROPDOWN_TEXT')); ?>
                <input type="radio" name="options_display_mode" value="2"<?php print($sliderDisplayModeChecked); ?> /> <?php print($objLang->getText('NRS_ADMIN_SLIDER_TEXT')); ?><br />
                &nbsp;&nbsp;&nbsp; <em>(Slider can be shown only if all extra options have identical steps and are +/- numbers)</em>
            </td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" value="Save extra" name="save_extra" style="cursor:pointer;"/></td>
        </tr>
    </table>
  </form>
</div>
<script type="text/javascript">
jQuery().ready(function() {
    jQuery("#form1").validate();
 });
</script>