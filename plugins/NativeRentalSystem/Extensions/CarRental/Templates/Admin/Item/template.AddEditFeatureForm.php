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
  <span style="font-size:16px; font-weight:bold">Feature Add/Edit</span>
  <input type="button" value="Back To Features List" onClick="window.location.href='<?php print($backToListURL); ?>'" style="background: #EFEFEF; float:right; cursor:pointer;"/>
  <hr style="margin-top:10px;"/>
  <form action="<?php print($formAction); ?>" method="post" id="form1">
      <table cellpadding="5" cellspacing="2" border="0">
          <input type="hidden" name="feature_id" value="<?php print($featureId); ?>"/>
          <tr>
              <td><strong>Features Title:</strong></td>
              <td><input type="text" name="feature_title" value="<?php print($featureTitle); ?>" id="feature_title" class="required" style="width:200px;" /></td>
          </tr>
          <tr>
              <td><strong>Display in Car List:</strong></td>
              <td><input type="checkbox" id="display_in_item_list" name="display_in_item_list"<?php print($displayInItemListChecked); ?>/></td>
          </tr>
          <?php if($featureId == 0): ?>
              <tr>
                  <td><strong>Add to All Cars:</strong></td>
                  <td><input type="checkbox" id="add_to_all_items" name="add_to_all_items"<?php print($addToAllItemsChecked); ?>/></td>
              </tr>
          <?php endif; ?>
          <tr>
                <td>&nbsp;</td>
                <td><input type="submit" value="Save Car Feature" name="save_feature" style="cursor:pointer;"/></td>
          </tr>
        </table>
    </form>
</div>
<script type="text/javascript">
	jQuery().ready(function() {
		jQuery("#form1").validate();
     });
</script>