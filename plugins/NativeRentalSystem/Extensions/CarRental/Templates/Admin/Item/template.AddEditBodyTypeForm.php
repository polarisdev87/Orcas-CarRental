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
    <span style="font-size:16px; font-weight:bold">Add/Edit Car Body Type</span>
    <input type="button" value="Back To Car Body Types List" onClick="window.location.href='<?php print($backToListURL); ?>'" style="background: #EFEFEF; float:right; cursor:pointer;"/>
    <hr style="margin-top:10px;"/>
    <form action="<?php print($formAction); ?>" method="post" id="form1">
        <table cellpadding="5" cellspacing="2" border="0">
            <input type="hidden" name="body_type_id" value="<?php print($bodyTypeId); ?>"/>
            <tr>
            <td><strong>Type Title:</strong></td>
                <td>
                    <input type="text" name="body_type_title" value="<?php print($bodyTypeTitle); ?>" id="body_type_title" class="required" style="width:200px;" />
                </td>
            </tr>
            <tr>
                <td><strong>Type Order:</strong></td>
                <td>
                    <input type="text" name="body_type_order" value="<?php print($bodyTypeOrder); ?>" id="body_type_order" class="" style="width:40px;" />
                    <em><?php print($bodyTypeId > 0 ? '' : '(optional, leave blank to add to the end)'); ?></em>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" value="Save body type" name="save_body_type" style="cursor:pointer;"/></td>
            </tr>
        </table>
    </form>
</div>
<script type="text/javascript">
jQuery().ready(function() {
		jQuery("#form1").validate();
});
</script>