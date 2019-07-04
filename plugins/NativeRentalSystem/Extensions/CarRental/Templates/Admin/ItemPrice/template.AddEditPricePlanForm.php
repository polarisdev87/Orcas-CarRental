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
    <span style="font-size:16px; font-weight:bold">Add/Edit Car Price Plan</span>
    <input type="button" value="Back to Price Plan List" onClick="window.location.href='<?php print($backToListURL); ?>'" style="background: #EFEFEF; cursor:pointer; float:right; "/>
    <hr style="margin-top:10px;" />
    <strong>Note:</strong> All prices have to be entered without <?php print($objLang->getText('NRS_TAX_TEXT')); ?>.<br />
    <hr />
    <form action="<?php print($formAction); ?>" method="post" class="price-plan-form">
    <table width="100%" border="0" cellspacing="2" cellpadding="2">
        <input type="hidden" name="price_plan_id" value="<?php print($pricePlanId); ?>" />
        <input type="hidden" name="price_group_id" value="<?php print($priceGroupId); ?>" />
        <tr>
            <td width="10%"><strong>Price Group:</strong></td>
            <td width="90%">
                <?php print($priceGroupName); ?>
            </td>
        </tr>
        <tr>
            <td><strong>Coupon Code:</strong></td>
            <td><input type="text" name="coupon_code" maxlength="50" value="<?php print($couponCode); ?>" id="coupon_code" class="" style="width:250px;" /></td>
        </tr>
        <tr>
            <td width="95px"><strong>Start Date:</strong></td>
            <td>
                <input name="start_date" type="text" size="10" value="<?php print($startDate); ?>" class="start-date" />
                <img class="start-date-datepicker" src="<?php print($objConf->getExtensionAdminImagesURL('Month.png')); ?>" height="18px" width="18px" style="cursor: pointer;"/></a>
                (optional, active from <?php print($startTime); ?>)
            </td>
        </tr>
        <tr>
            <td width="95px"><strong>End Date:</strong></td>
            <td>
                <input name="end_date" type="text" size="10" value="<?php print($endDate); ?>" class="end-date"/>
                <img class="end-date-datepicker" src="<?php print($objConf->getExtensionAdminImagesURL('Month.png')); ?>" height="18px" width="18px" style="cursor: pointer;" /></a>
                (optional, active till <?php print($endTime); ?>)
            </td>
        </tr>
        <tr>
            <td colspan="2">&nbsp;</td>
        </tr>
        <tr>
        <td colspan="2">
        <table cellpadding="3" cellspacing="0" border="0" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;width:700px;">
            <tr>
                <td style="width: 72px; padding-left:5px; font-weight: bold;"><?php print($objLang->getText('NRS_ADMIN_PRICE_TYPE_TEXT')); ?></td>
                <?php foreach($weekDays AS $weekDay => $dayName): ?>
                    <td style="width: 65px; padding-left:17px; font-weight: bold;"><?php print($dayName); ?></td>
                <?php endforeach; ?>
            </tr>
            <tr>
                <td colspan="8"><hr /></td>
            </tr>
            <?php
            if ($displayDailyRates):
                print('<tr>');
                    print('<td>');
                    print($objLang->getText('NRS_PRICE_TEXT').' / '.$objLang->getText('NRS_PER_DAY_TEXT'));
                    print('</td>');
            endif;
                foreach($dailyRates AS $weekDay => $dailyRate):
                    if ($displayDailyRates):
                        print('<td>'.$leftCurrencySymbol);
                        print('<input type="text" name="daily_rate_'.$weekDay.'" value="'.$dailyRate.'" size="4" class="required number" />');
                        print($rightCurrencySymbol.'</td>');
                    else:
                        print('<input type="hidden" name="daily_rate_'.$weekDay.'" value="'.$dailyRate.'" />');
                    endif;
                endforeach;
            if ($displayDailyRates):
                print('</tr>');
            endif;

            if ($displayHourlyRates):
                print('<tr>');
                    print('<td>');
                    print($objLang->getText('NRS_PRICE_TEXT').' / '.$objLang->getText('NRS_PER_HOUR_TEXT'));
                    print('</td>');
            endif;
                foreach($hourlyRates AS $weekDay => $hourlyRate):
                    if ($displayHourlyRates):
                        print('<td>'.$leftCurrencySymbol);
                        print('<input type="text" name="hourly_rate_'.$weekDay.'" value="'.$hourlyRate.'" size="4" class="required number" />');
                        print($rightCurrencySymbol.'</td>');
                    else:
                        print('<input type="hidden" name="hourly_rate_'.$weekDay.'" value="'.$hourlyRate.'" />');
                    endif;
                endforeach;
            if ($displayHourlyRates):
                print('</tr>');
            endif;
            ?>
        </table>
        </td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
                <input type="submit" value="Save price plan" name="save_price_plan" style="cursor:pointer;" />
            <td>
        </tr>
    </table>
    </form>
</div>
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery(".start-date").datepicker({
        minDate: "-365D",
        maxDate: "+1095D",
        numberOfMonths: 2,
        dateFormat: '<?php print($objSettings->getSetting('conf_datepicker_date_format')); ?>',
        firstDay: <?php print(get_option('start_of_week')); ?>,
        onSelect: function(selected) {
            var date = jQuery(this).datepicker('getDate');
            if(date)
            {
                date.setDate(date.getDate() + 1);
            }
            jQuery(".end-date").datepicker("option","minDate", date);
        }
    });

    jQuery(".end-date").datepicker({
        minDate: "-365D",
        maxDate:"+1095D",
        numberOfMonths: 2,
        dateFormat: '<?php print($objSettings->getSetting('conf_datepicker_date_format')); ?>',
        firstDay: <?php print(get_option('start_of_week')); ?>,
        onSelect: function(selected) {
            jQuery(".start-date").datepicker("option","maxDate", selected)
        }
    });
    jQuery(".start-date-datepicker").click(function() {
        jQuery(".start-date").datepicker("show");
    });
    jQuery(".end-date-datepicker").click(function() {
        jQuery(".end-date").datepicker("show");
    });

    // Validator
    jQuery('.price-plan-form').validate();
 });
</script> 