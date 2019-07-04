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
  <span style="font-size:16px; font-weight:bold">Add/Edit Car Option</span>
  <input type="button" value="Back to Car Options List" onClick="window.location.href='<?php print($backToListURL); ?>'" style="background: #EFEFEF; float:right; cursor:pointer;"/>
    <hr style="margin-top:10px;"/>
  <form action="<?php print($formAction); ?>" method="post" id="form1">
    <table cellpadding="5" cellspacing="2" border="0">
        <input type="hidden" name="option_id" value="<?php print($optionId); ?>"/>
        <tr>
            <td width="20%"><strong>Select a Car:<span class="item-required">*</span></strong></td>
            <td width="80%">
                <select name="item_id" class="required">
                    <?php print($itemDropDownOptions); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong>Option Name:<span class="item-required">*</span></strong></td>
            <td>
                <input type="text" name="option_name" value="<?php print($optionName); ?>" id="option_name" class="required" style="width:150px;" />
            </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type="submit" value="Save car option" name="save_option" style="cursor:pointer;"/>
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