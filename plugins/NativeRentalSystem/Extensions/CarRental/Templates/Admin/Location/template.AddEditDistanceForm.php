<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
// Scripts
wp_enqueue_script('jquery');
wp_enqueue_script('jquery-ui-core');
wp_enqueue_script('jquery-ui-tabs');
wp_enqueue_script('jquery-validate');
wp_enqueue_script('car-rental-admin');

// Styles
wp_enqueue_style('jquery-validate');
wp_enqueue_style('car-rental-admin');
?>
<p>&nbsp;</p>
<div id="container-inside" style="width:1000px;" class="car-rental-add-distance">
    <span style="font-size:16px; font-weight:bold">Distance Add/Edit</span>
    <input type="button" value="Back To Distance List" onClick="window.location.href='<?php print($backToListURL); ?>'" style="background: #EFEFEF; float:right; cursor:pointer;"/>
    <hr style="margin-top:10px;"/>
    <form action="<?php print($formAction); ?>" method="post" id="form1">
        <table cellpadding="5" cellspacing="2" border="0">
            <input type="hidden" name="distance_id" value="<?php print($distanceId); ?>"/>
            <tr>
                <td class="label"><strong>Pick-up Location:</strong></td>
                <td>
                    <select name="pickup_location_id" id="pickup_location_id">
                        <?php print($pickupLocationsDropDownOptions); ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="label"><strong>Return Location:</strong></td>
                <td>
                    <select name="return_location_id" id="return_location_id">
                        <?php print($returnLocationsDropDownOptions); ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td><strong>Distance:</strong></td>
                <td>
                    <input type="text" name="distance" value="<?php print($distance); ?>" id="distance" class="required number" style="width:50px;" />
                    &nbsp;<strong><?php print($objSettings->getSetting('conf_distance_measurement_unit')); ?></strong> &nbsp;
                    <input type="checkbox" id="show_distance" name="show_distance" value="yes"<?php print($showDistance); ?>/> Show
                </td>
            </tr>
            <tr>
                <td class="label"><strong>Distance Fee:</strong></td>
                <td>
                    <?php print($objSettings->getSetting('conf_currency_symbol')); ?>
                    <input type="text" name="distance_fee" value="<?php print($distanceFee); ?>" id="distance_fee" class="required number" size="4" />
                    (excl. <?php print($objLang->getText('NRS_TAX_TEXT')); ?>)
                </td>
            </tr>
            <tr>
                <td class="label"></td>
                <td><input type="submit" value="Save Distance" name="save_distance" style="background:#e5f9bb; cursor:pointer;"/></td>
            </tr>
        </table>
    </form>
</div>
<script type="text/javascript">
jQuery().ready(function() {
		jQuery("#form1").validate();
});
</script>