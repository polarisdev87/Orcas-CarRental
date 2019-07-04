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
  <span style="font-size:16px; font-weight:bold"><?php print($pageTitle); ?></span>
  <input type="button" value="Back To Price Plan Discounts List" onClick="window.location.href='<?php print($backToListURL); ?>'" style="background: #EFEFEF; float:right; cursor:pointer;"/>
    <hr style="margin-top:10px;"/>
  <form action="<?php print($formAction); ?>" method="post" id="form1">
    <table cellpadding="5" cellspacing="2" border="0">
        <input type="hidden" name="discount_id" value="<?php print($discountId); ?>" />
        <input type="hidden" name="discount_type" value="<?php print($discountType); ?>" />
        <tr>
            <td width="20%"><strong>Select a Price Plan:</strong></td>
            <td width="80%">
                <select name="price_plan_id" style="width: 300px;" class="">
                    <?php print($pricePlanDropDownOptions); ?>
                </select> (optional, leave blank to apply same discount % to all price plans)
                <input type="hidden" name="extra_id" value="0" />
            </td>
        </tr>
        <tr>
          <td><strong><?php print($fromTitle); ?></strong></td>
          <td>
              <?php
              if (in_array($objSettings->getSetting('conf_price_calculation_type'), array("1", "3")))
              {
                  print('<input type="text" name="days_from" value="'.$durationFromDays.'" class="required digits" style="width:70px;" />');
                  print(' '.$objLang->getText('NRS_DAYS_TEXT'));
              }
              if (in_array($objSettings->getSetting('conf_price_calculation_type'), array("2", "3")))
              {
                  print('<input type="text" name="hours_from" value="'.$durationFromHours.'" class="required digits" style="width:70px;" />');
                  print(' '.$objLang->getText('NRS_HOURS_TEXT').' (for minutes - use fraction, i.e. for 1 hour 15 minutes enter 1.25)');
              }
              ?>
          </td>
        </tr>
        <tr>
          <td><strong><?php print($toTitle); ?></strong></td>
          <td>
              <?php
              if (in_array($objSettings->getSetting('conf_price_calculation_type'), array("1", "3")))
              {
                  print('<input type="text" name="days_till" value="'.$durationTillDays.'" class="required digits" style="width:70px;" />');
                  print(' '.$objLang->getText('NRS_DAYS_TEXT'));
                  if($objSettings->getSetting('conf_price_calculation_type') == 1) { print(', including full last day'); }
              }
              if (in_array($objSettings->getSetting('conf_price_calculation_type'), array("2", "3")))
              {
                  print('<input type="text" name="hours_till" value="'.$durationTillHours.'" class="required number" style="width:70px;" />');
                  print(' '.$objLang->getText('NRS_HOURS_TEXT').', including full last hour');
              }
              ?>
          </td>
        </tr>
        <tr>
          <td><strong>Price Discount:</strong><br /><em>(of total car price)</em></td>
          <td>
              <input type="text" name="discount_percentage" value="<?php print($discountPercentage); ?>" class="required number" style="width:70px;" />&nbsp;%
          </td>
        </tr>
        <tr>
            <td></td>
            <td>
                <input type="submit" value="Save discount plan" name="save_discount" style="cursor:pointer;"/>
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