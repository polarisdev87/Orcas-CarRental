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
 <span style="font-size:16px; font-weight:bold">Add/Edit Transmission Type</span>

<input type="button" value="Back To Car Type List" onClick="window.location.href='<?php print($backToListURL); ?>'" style="background: #EFEFEF; float:right; cursor:pointer;"/><hr style="margin-top:10px;"/>
   <form action="<?php print($formAction); ?>" method="post" id="form1">
  <table cellpadding="5" cellspacing="2" border="0">
  <input type="hidden" name="transmission_type_id" value="<?php print($transmissionTypeId); ?>"/>
    <tr>
      <td><strong>Transmission type:</strong></td>
      <td>
          <input type="text" name="transmission_type_title" value="<?php print($transmissionTypeTitle); ?>" id="transmission_type_title" class="required" style="width:200px;" />
      </td>
    </tr>
    <tr>
        <td></td>
        <td><input type="submit" value="Save transmission" name="save_transmission_type" style="cursor:pointer;"/></td>
    </tr>
    </table>
    </form>
</div>

<script type="text/javascript">
jQuery().ready(function() {
	jQuery("#form1").validate();
});
</script>