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
<div id="container-inside" style="width:1000px;">
  <span style="font-size:16px; font-weight:bold">Add/Edit Tax</span>
  <input type="button" value="Back to tax list" onClick="window.location.href='<?php print($backToListURL); ?>'" style="background: #EFEFEF; float:right; cursor:pointer;"/>
    <hr style="margin-top:10px;"/>
  <form action="<?php print($formAction); ?>" method="post" id="form1">
    <table cellpadding="5" cellspacing="2" border="0">
        <input type="hidden" name="tax_id" value="<?php print($taxId); ?>"/>
        <tr>
            <td width="20%"><strong>Tax Name:<span class="item-required">*<span></strong></td>
            <td width="80%">
                <input type="text" name="tax_name" value="<?php print($taxName); ?>" id="tax_name" class="required" style="width:450px;" />
            </td>
        </tr>
        <tr>
            <td><strong>Select Location</strong>:</td>
            <td>
                <select name="location_id" class="">
                    <?php print($locationsDropDownOptions); ?>
                </select> (optional, leave blank to apply same tax % to all locations)
            </td>
        </tr>
        <tr>
            <td><strong>Location Type:</strong><br /></td>
            <td>
                <input type="radio" name="location_type" value="1"<?php print($pickupTypeChecked); ?> /> <?php print($objLang->getText('NRS_PICKUP_TEXT')); ?>
                <input type="radio" name="location_type" value="2"<?php print($returnTypeChecked); ?> /> <?php print($objLang->getText('NRS_RETURN_TEXT')); ?>
            </td>
        </tr>

        <tr>
            <td><strong>Tax Percentage:</strong></td>
            <td>
                <input type="text" name="tax_percentage" value="<?php print($taxPercentage); ?>" id="tax_percentage" class="required number" style="width:60px;" />&nbsp;%
            </td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" value="Save tax" name="save_tax" style="cursor:pointer;"/></td>
        </tr>
    </table>
  </form>
</div>
<script type="text/javascript">
jQuery().ready(function() {
    jQuery("#form1").validate();
});
</script>