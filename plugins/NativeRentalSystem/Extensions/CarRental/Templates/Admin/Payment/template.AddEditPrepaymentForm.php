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
  <span style="font-size:16px; font-weight:bold">Add/Edit Prepayment</span>
  <input type="button" value="Back to prepayments list" onClick="window.location.href='<?php print($backToListURL); ?>'" style="background: #EFEFEF; float:right; cursor:pointer;"/>
    <hr style="margin-top:10px;"/>
  <form action="<?php print($formAction); ?>" method="post" id="form1">
    <table cellpadding="5" cellspacing="2" border="0">
        <input type="hidden" name="prepayment_id" value="<?php print($prepaymentId); ?>"/>
        <tr>
            <td width="20%"><strong>Duration From:</strong></td>
            <td width="80%">
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
            <td><strong>Duration Till:</strong></td>
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
            <td><strong>Include:</strong></td>
            <td>
                <table width="100%">
                    <tr>
                        <td><input type="checkbox" id="item_prices_included" name="item_prices_included"<?php print($itemPricesIncludedChecked); ?>/> <?php print($objLang->getText('NRS_ADMIN_ITEM_PRICES_TEXT')); ?></td>
                        <td><input type="checkbox" id="item_deposits_included" name="item_deposits_included"<?php print($itemDepositsIncludedChecked); ?>/> <?php print($objLang->getText('NRS_ADMIN_ITEM_DEPOSITS_TEXT')); ?></td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" id="extra_prices_included" name="extra_prices_included"<?php print($extraPricesIncludedChecked); ?>/> <?php print($objLang->getText('NRS_ADMIN_EXTRA_PRICES_TEXT')); ?></td>
                        <td><input type="checkbox" id="extra_deposits_included" name="extra_deposits_included"<?php print($extraDepositsIncludedChecked); ?>/> <?php print($objLang->getText('NRS_ADMIN_EXTRA_DEPOSITS_TEXT')); ?></td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" id="pickup_fees_included" name="pickup_fees_included"<?php print($pickupFeesIncludedChecked); ?>/> <?php print($objLang->getText('NRS_ADMIN_PICKUP_FEES_TEXT')); ?></td>
                        <td><input type="checkbox" id="distance_fees_included" name="distance_fees_included"<?php print($distanceFeesIncludedChecked); ?>/> <?php print($objLang->getText('NRS_ADMIN_DISTANCE_FEES_TEXT')); ?></td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" id="return_fees_included" name="return_fees_included"<?php print($returnFeesIncludedChecked); ?>/> <?php print($objLang->getText('NRS_ADMIN_RETURN_FEES_TEXT')); ?></td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td><strong>Prepayment:</strong></td>
            <td>
                <input type="text" name="prepayment_percentage" value="<?php print($prepaymentPercentage); ?>" id="prepayment_percentage" class="required number" style="width:60px;" />&nbsp;%
            </td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" value="Save prepayment" name="save_prepayment" style="cursor:pointer;"/></td>
        </tr>
    </table>
  </form>
</div>
<script type="text/javascript">
jQuery().ready(function() {
    jQuery("#form1").validate();
});
</script>