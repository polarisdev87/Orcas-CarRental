<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<h1>
    <span>Price Settings</span>
</h1>
<form name="price_calculation_settings_form" action="<?php print($priceSettingsTabFormAction); ?>" method="post" id="global_settings_form">
    <table cellpadding="5" cellspacing="2" border="0" width="100%">
        <tr>
            <td width="20%"><strong>Price Model:</strong></td>
            <td width="80%">
                <select name="conf_price_calculation_type">
                    <?php print($arrPriceSettings['select_price_calculation_type']); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong>Currency Code:</strong></td>
            <td><input type="text" name="conf_currency_code" id="conf_currency_code" value="<?php print($objSettings->getSetting('conf_currency_code')); ?>" class="required" style="width:70px;" /></td>
        </tr>

        <tr>
            <td><strong>Currency Symbol:</strong></td>
            <td>
                <input type="text" name="conf_currency_symbol" id="conf_currency_symbol" value="<?php print($objSettings->getSetting('conf_currency_symbol')); ?>" class="required" style="width:70px;" />
                <select name="conf_currency_symbol_location" id="conf_currency_symbol_location" class="on-the-right-narrow-input-box">
                    <?php print($arrPriceSettings['select_currency_symbol_location']); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong>Show Prices with <?php print($objLang->getText('NRS_TAX_TEXT')); ?>:</strong></td>
            <td>
                <select name="conf_show_price_with_taxes" id="conf_show_price_with_taxes">
                    <?php print($arrPriceSettings['select_show_price_with_taxes']); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong>Deposits:</strong></td>
            <td>
                <select name="conf_deposit_enabled" id="conf_deposit_enabled">
                    <?php print($arrPriceSettings['select_deposit_enabled']); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong>Prepayments:</strong></td>
            <td>
                <select name="conf_prepayment_enabled" id="conf_prepayment_enabled">
                    <?php print($arrPriceSettings['select_prepayment_enabled']); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <br />
                <input type="submit" value="Update price settings" name="update_price_settings" style="cursor:pointer;"/>
            </td>
        </tr>
    </table>
</form>
<p>Please keep in mind that:</p>
<ol>
    <li>
        If you want to use mixed price calculation, then you must define both prices - Daily and Hourly - in price manager for each car.
    </li>
    <li>Wondering, how different price calculation models work?<br />
        Let&#39;s say that you&#39;ve chosen those pickup &amp; return dates and times:<br />
            <strong>Pick-up Date &amp; Time:</strong> 21/08/<?php print(date("Y")+1); ?> 9:00 AM <br />
            <strong>Return Date &amp; Time:</strong> 23/08/<?php print(date("Y")+1); ?> 3:00 PM<br />
        <br />
        Then final item &amp; extra prices for that period of time will be calculated in this way:<br />
        If price model is <strong>daily</strong>, then price will be calculated for 3 days
        by using car&#39;s &amp; extra&#39;s daily price field data.<br />
        If price model is <strong>hourly</strong>, then price will be calculated for 54 hours
        by using car&#39;s &amp; extra&#39;s hourly price field data.<br />
        If price model is <strong>combined - daily &amp; hourly</strong>, then price will be calculated for 2 Day(s) and 6 Hour(s)
        by using car&#39;s &amp; extra&#39;s daily price field data for 2 days + 6 hours by using car&#39;s &amp; extra&#39;s hourly price field data.
    </li>
    <li>
        If discount is not in percentage, then discounts are set by fixed amounts, which are deducted from the car/extra price.
    </li>
</ol>
<script type="text/javascript">
    jQuery().ready(function() {
        jQuery("#price_calculation_settings_form").validate();

    });
</script>