<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<h1>
    <span>Search Settings</span>
</h1>
<form name="search_settings_form" action="<?php print($searchSettingsTabFormAction); ?>" method="post" id="search_settings_form">
    <table class="big-text" cellpadding="5" cellspacing="2" border="0" width="100%" style="line-height: 2">
        <thead>
        <tr>
            <th align="left" style="width: 350px">Search Field</th>
            <th align="center" style="width: 100px">Visible</th>
            <th align="center" style="width: 100px">Required</th>
            <th>Actions</th>
        </tr>
        <tr>
            <th colspan="4"><hr /></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <?php print($objLang->getText('NRS_PICKUP_LOCATION_TEXT')); ?>
            </td>
            <td align="center">
                <input type="checkbox" name="conf_search_pickup_location_visible" value="yes"<?php print($pickupLocationVisibleChecked); ?> />
            </td>
            <td align="center">
                <input type="checkbox" name="conf_search_pickup_location_required" value="yes"<?php print($pickupLocationRequiredChecked); ?> />
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <?php print($objLang->getText('NRS_PICKUP_DATE_TEXT')); ?>
            </td>
            <td align="center">
                <input type="checkbox" name="conf_search_pickup_date_visible" value="yes"<?php print($pickupDateVisibleChecked); ?> />
            </td>
            <td align="center">
                <input type="checkbox" name="conf_search_pickup_date_required" value="yes"<?php print($pickupDateRequiredChecked); ?> />
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <?php print($objLang->getText('NRS_RETURN_LOCATION_TEXT')); ?>
            </td>
            <td align="center">
                <input type="checkbox" name="conf_search_return_location_visible" value="yes"<?php print($returnLocationVisibleChecked); ?> />
            </td>
            <td align="center">
                <input type="checkbox" name="conf_search_return_location_required" value="yes"<?php print($returnLocationRequiredChecked); ?> />
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <?php print($objLang->getText('NRS_RETURN_DATE_TEXT')); ?>
            </td>
            <td align="center">
                <input type="checkbox" name="conf_search_return_date_visible" value="yes"<?php print($returnDateVisibleChecked); ?> />
            </td>
            <td align="center">
                <input type="checkbox" name="conf_search_return_date_required" value="yes"<?php print($returnDateRequiredChecked); ?> />
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <?php print($objLang->getText('NRS_PARTNER_TEXT')); ?>
            </td>
            <td align="center">
                <input type="checkbox" name="conf_search_partner_visible" value="yes"<?php print($partnerVisibleChecked); ?> />
            </td>
            <td align="center">
                <input type="checkbox" name="conf_search_partner_required" value="yes"<?php print($partnerRequiredChecked); ?> />
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <?php print($objLang->getText('NRS_MANUFACTURER_TEXT')); ?>
            </td>
            <td align="center">
                <input type="checkbox" name="conf_search_manufacturer_visible" value="yes"<?php print($manufacturerVisibleChecked); ?> />
            </td>
            <td align="center">
                <input type="checkbox" name="conf_search_manufacturer_required" value="yes"<?php print($manufacturerRequiredChecked); ?> />
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <?php print($objLang->getText('NRS_BODY_TYPE_TEXT')); ?> <span class="not-important">(Body Type)</span>
            </td>
            <td align="center">
                <input type="checkbox" name="conf_search_body_type_visible" value="yes"<?php print($bodyTypeVisibleChecked); ?> />
            </td>
            <td align="center">
                <input type="checkbox" name="conf_search_body_type_required" value="yes"<?php print($bodyTypeRequiredChecked); ?> />
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <?php print($objLang->getText('NRS_TRANSMISSION_TYPE_TEXT')); ?> <span class="not-important">(Transmission Type)</span>
            </td>
            <td align="center">
                <input type="checkbox" name="conf_search_transmission_type_visible" value="yes"<?php print($transmissionTypeVisibleChecked); ?> />
            </td>
            <td align="center">
                <input type="checkbox" name="conf_search_transmission_type_required" value="yes"<?php print($transmissionTypeRequiredChecked); ?> />
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <?php print($objLang->getText('NRS_FUEL_TYPE_TEXT')); ?> <span class="not-important">(Fuel Type)</span>
            </td>
            <td align="center">
                <input type="checkbox" name="conf_search_fuel_type_visible" value="yes"<?php print($fuelTypeVisibleChecked); ?> />
            </td>
            <td align="center">
                <input type="checkbox" name="conf_search_fuel_type_required" value="yes"<?php print($fuelTypeRequiredChecked); ?> />
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <?php print($objLang->getText('NRS_BOOKING_CODE_TEXT')); ?>
            </td>
            <td align="center">
                <input type="checkbox" name="conf_search_booking_code_visible" value="yes"<?php print($existingBookingCodeVisibleChecked); ?> />
            </td>
            <td align="center">
                <input type="checkbox" name="conf_search_booking_code_required" value="yes"<?php print($existingBookingCodeRequiredChecked); ?> />
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <?php print($objLang->getText('NRS_COUPON_CODE_TEXT')); ?>
            </td>
            <td align="center">
                <input type="checkbox" name="conf_search_coupon_code_visible" value="yes"<?php print($couponCodeVisibleChecked); ?> />
            </td>
            <td align="center">
                <input type="checkbox" name="conf_search_coupon_code_required" value="yes"<?php print($couponCodeRequiredChecked); ?> />
            </td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4" align="center">
                <br />
                <input type="submit" value="Update search settings" name="update_search_settings" style="cursor:pointer;"/>
            </td>
        </tr>
    </table>
</form>
<p>Please keep in mind that:</p>
<ol>
    <li>Existing reservation code visibility/required setting is only applied to main search template, and is not applied for &quot;Edit Reservation&quot; template.</li>
    <li>If you don&#39;t want to allow edit reservations at all - then just do not install reservation editing shortcode.</li>
</ol>
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery("#search_settings_form").validate();
});
</script>