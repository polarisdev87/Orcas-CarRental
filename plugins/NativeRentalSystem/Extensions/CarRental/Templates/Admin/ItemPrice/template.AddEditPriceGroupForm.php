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
  <span style="font-size:16px; font-weight:bold">Add/Edit Price Group</span>
  <input type="button" value="Back to price group list" onClick="window.location.href='<?php print($backToListURL); ?>'" style="background: #EFEFEF; float:right; cursor:pointer;"/>
    <hr style="margin-top:10px;"/>
  <form action="<?php print($formAction); ?>" method="post" id="form1">
    <table cellpadding="5" cellspacing="2" border="0">
        <input type="hidden" name="price_group_id" value="<?php print($priceGroupId); ?>"/>
        <tr>
            <td><strong>Price Group Name:</strong></td>
            <td>
                <input type="text" name="price_group_name" value="<?php print($priceGroupName); ?>" id="price_group_name" class="required" style="width:250px;" />
            </td>
        </tr>
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
            <td></td>
            <td><input type="submit" value="Save price group" name="save_price_group" style="cursor:pointer;"/></td>
        </tr>
    </table>
  </form>
</div>
<script type="text/javascript">
jQuery().ready(function() {
    jQuery("#form1").validate();
});
</script>