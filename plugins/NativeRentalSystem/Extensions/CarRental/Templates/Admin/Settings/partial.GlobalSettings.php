<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<h1>
    <span>Global Settings</span>
</h1>
<form name="global_settings_form" action="<?php print($globalSettingsTabFormAction); ?>" method="post" id="global_settings_form">
    <table cellpadding="5" cellspacing="2" border="0" width="100%">
        <tr>
            <td width="20%"><strong>Company Name:</strong></td>
            <td width="80%"><input type="text" class="required" value="<?php print($objSettings->getEditSetting('company_name')); ?>" name="conf_company_name" id="conf_company_name" /></td>
        </tr>
        <tr>
            <td><strong>Company Street Address:</strong></td>
            <td><input type="text" class="" value="<?php print($objSettings->getEditSetting('company_street_address')); ?>" name="conf_company_street_address" id="conf_company_street_address" /></td>
        </tr>
        <tr>
            <td><strong>Company City:</strong></td>
            <td><input type="text" class="" value="<?php print($objSettings->getEditSetting('company_city')); ?>" name="conf_company_city" id="conf_company_city" /></td>
        </tr>
        <tr>
            <td><strong>Company State:</strong></td>
            <td><input type="text" class="" value="<?php print($objSettings->getEditSetting('company_state')); ?>" name="conf_company_state" id="conf_company_state" /></td>
        </tr>
        <tr>
            <td><strong>Company Country:</strong></td>
            <td><input type="text" class="" value="<?php print($objSettings->getEditSetting('company_country')); ?>" name="conf_company_country" id="conf_company_country" /></td>
        </tr>
        <tr>
            <td><strong>Company Zip Code:</strong></td>
            <td><input type="text" class="" value="<?php print($objSettings->getEditSetting('company_zip_code')); ?>" name="conf_company_zip_code" id="conf_company_zip_code" /></td>
        </tr>
        <tr>
            <td><strong>Company Phone:</strong></td>
            <td><input type="text" class="" value="<?php print($objSettings->getEditSetting('company_phone')); ?>" name="conf_company_phone" id="conf_company_phone" /></td>
        </tr>
        <tr>
            <td><strong>Company Email:</strong></td>
            <td><input type="text" class="email" value="<?php print($objSettings->getEditSetting('company_email')); ?>" name="conf_company_email" id="conf_company_email" /></td>
        </tr>
        <tr>
            <td colspan="2"><hr />
            </td>
        </tr>
        <tr>
            <td><strong>Send Emails:</strong></td>
            <td>
                <select name="conf_send_emails" id="conf_send_emails">
                    <?php print($arrGlobalSettings['select_send_emails']); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong>Company Notification Emails:</strong></td>
            <td>
                <select name="conf_company_notification_emails" id="conf_company_notification_emails">
                    <?php print($arrGlobalSettings['select_company_notification_emails']); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong>UA Event Tracking:</strong></td>
            <td>
                <select name="conf_universal_analytics_events_tracking" id="conf_universal_analytics_events_tracking">
                    <?php print($arrGlobalSettings['select_universal_analytics_events_tracking']); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong>UA Enhanced Ecommerce:</strong></td>
            <td>
                <select name="conf_universal_analytics_enhanced_ecommerce" id="conf_universal_analytics_enhanced_ecommerce">
                    <?php print($arrGlobalSettings['select_universal_analytics_enhanced_ecommerce']); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong>ReCaptcha Site Key:</strong></td>
            <td><input type="text" value="<?php print($objSettings->getEditSetting('recaptcha_site_key')); ?>" name="conf_recaptcha_site_key" id="conf_recaptcha_site_key" /></td>
        </tr>
        <tr>
            <td><strong>ReCaptcha Secret Key:</strong></td>
            <td><input type="text" value="<?php print($objSettings->getEditSetting('recaptcha_secret_key')); ?>" name="conf_recaptcha_secret_key" id="conf_recaptcha_secret_key" /></td>
        </tr>
        <tr>
            <td><strong>ReCaptcha Validation:</strong></td>
            <td>
                <select name="conf_recaptcha_enabled" id="conf_recaptcha_enabled">
                    <?php print($arrGlobalSettings['select_recaptcha_enabled']); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong>API requests limit:</strong></td>
            <td>
                <select name="conf_api_max_requests_per_period" id="conf_api_max_requests_per_period">
                    <?php print($arrGlobalSettings['select_api_max_requests_per_period']); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong>Failed API requests limit:</strong></td>
            <td>
                <select name="conf_api_max_failed_requests_per_period" id="conf_api_max_failed_requests_per_period">
                    <?php print($arrGlobalSettings['select_api_max_failed_requests_per_period']); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong>Cancelled Payment Page:</strong></td>
            <td valign="middle"><?php print($arrGlobalSettings['select_cancelled_payment_page_id']); ?></td>
        </tr>
        <tr>
            <td><strong>Confirmation Page:</strong></td>
            <td valign="middle"><?php print($arrGlobalSettings['select_confirmation_page_id']); ?></td>
        </tr>
        <tr>
            <td><strong>Terms &amp; Conditions Page:</strong></td>
            <td valign="middle"><?php print($arrGlobalSettings['select_terms_and_conditions_page_id']); ?></td>
        </tr>
        <tr>
            <td><strong>Front-end Style:</strong></td>
            <td>
                <select name="conf_system_style" id="conf_system_style">
                    <?php print($systemStylesDropdownOptions); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong>Short Date Format:</strong></td>
            <td>
                <select name="conf_short_date_format" id="conf_short_date_format">
                    <?php print($arrGlobalSettings['select_short_date_format']); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong>Distance Measurement Unit:</strong></td>
            <td><input type="text" name="conf_distance_measurement_unit" id="conf_distance_measurement_unit" value="<?php print($objSettings->getEditSetting('distance_measurement_unit')); ?>" class="required" style="width:70px;" /></td>
        </tr>
        <tr>
            <td><strong>Reservation Period (Min):</strong></td>
            <td>
                <select name="conf_minimum_booking_period" id="conf_minimum_booking_period">
                    <?php print($arrGlobalSettings['select_minimum_booking_period']); ?>
                </select>
        </tr>
        <tr>
            <td><strong>Reservation Period (Max):</strong></td>
            <td>
                <select name="conf_maximum_booking_period" id="conf_maximum_booking_period">
                    <?php print($arrGlobalSettings['select_maximum_booking_period']); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong>Car Reservation Interval (Min):</strong></td>
            <td>
                <select name="conf_minimum_block_period_between_bookings" id="conf_minimum_block_period_between_bookings">
                    <?php print($arrGlobalSettings['select_minimum_block_period_between_bookings']); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong>Period Until Pick-up (Min):</strong></td>
            <td>
                <select name="conf_minimum_period_until_pickup" id="conf_minimum_period_until_pickup">
                    <?php print($arrGlobalSettings['select_minimum_period_until_pickup']); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong>Noon time (Avail. Calendar):</strong></td>
            <td>
                <select name="conf_noon_time" id="conf_noon_time">
                    <?php print($arrGlobalSettings['select_noon_time']); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong>Page Slug:</strong></td>
            <td><strong>DOMAIN/</strong><input type="text" name="conf_page_url_slug" id="conf_page_url_slug" value="<?php print($objSettings->getEditSetting('page_url_slug')); ?>" class="required" style="width:165px;" /><strong>/CONFIRMED/</strong></td>
        </tr>
        <tr>
            <td><strong>Car Slug:</strong></td>
            <td><strong>DOMAIN/</strong><input type="text" name="conf_item_url_slug" id="conf_item_url_slug" value="<?php print($objSettings->getEditSetting('item_url_slug')); ?>" class="required" style="width:165px;" /><strong>/GREAT-CAR/</strong></td>
        </tr>
        <tr>
            <td><strong>Location Slug:</strong></td>
            <td><strong>DOMAIN/</strong><input type="text" name="conf_location_url_slug" id="conf_location_url_slug" value="<?php print($objSettings->getEditSetting('location_url_slug')); ?>" class="required" style="width:165px;" /><strong>/GREAT-AIRPORT/</strong></td>
        </tr>
        <tr>
            <td><strong>Reveal Partners:</strong></td>
            <td>
                <select name="conf_reveal_partner" id="conf_reveal_partner">
                    <?php print($arrGlobalSettings['select_reveal_partner']); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong>Classify Cars:</strong></td>
            <td>
                <select name="conf_classify_items" id="conf_classify_items">
                    <?php print($arrGlobalSettings['select_classify_items']); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong>Select Multiple Cars:</strong></td>
            <td>
                <select name="conf_booking_model" id="conf_booking_model">
                    <?php print($arrGlobalSettings['select_booking_model']); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong>Search:</strong></td>
            <td>
                <select name="conf_search_enabled" id="conf_search_enabled">
                    <?php print($arrGlobalSettings['select_search_enabled']); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong>DatePicker Assets:</strong></td>
            <td>
                <select name="conf_load_datepicker_from_plugin" id="conf_load_datepicker_from_plugin">
                    <?php print($arrGlobalSettings['select_load_datepicker_from_plugin']); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong>fancyBox Assets:</strong></td>
            <td>
                <select name="conf_load_fancybox_from_plugin" id="conf_load_fancybox_from_plugin">
                    <?php print($arrGlobalSettings['select_load_fancybox_from_plugin']); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong>Font Awesome Assets:</strong></td>
            <td>
                <select name="conf_load_font_awesome_from_plugin" id="conf_load_font_awesome_from_plugin">
                    <?php print($arrGlobalSettings['select_load_font_awesome_from_plugin']); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong>Slick Slider Assets:</strong></td>
            <td>
                <select name="conf_load_slick_slider_from_plugin" id="conf_load_slick_slider_from_plugin">
                    <?php print($arrGlobalSettings['select_load_slick_slider_from_plugin']); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <br />
                <input type="submit" value="Update global settings" name="update_global_settings" style="cursor:pointer;"/>
            </td>
        </tr>
    </table>
</form>
<p>Please keep in mind that:</p>
<ol>
    <li>If you want to enable Universal Analytics event tracking or/and Enhanced Ecommerce,
        make sure you have standard Universal Analytics tracking code added to your site header
        or just after opening of &lt;body&gt; tag. Most themes has the header scripts part.<br />
        Default universal analytics tracking code looks like this:<br />
        <div style="font-family: 'Courier New', Courier, mono; font-size: 10px;color:#196601;line-height: 1.4em;">
            <pre>
&lt;script type=&quot;text/javascript&quot;&gt;
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-<strong>YOUR-TRACKING-CODE</strong>', 'auto');
    ga('send', 'pageview');
&lt;/script&gt;</pre>
        </div>
        Keep in mind that default universal analytics tracking code SHOULD NOT (!) have the following line:<br />
        <span style="font-family: 'Courier New', Courier, mono; font-size: 10px;color:#196601;">ga('require', 'ec');</span><br />
        The line above will be added automatically whenever Enhanced Analytics will be called.<br />
        Universal Analytics Event tracking will fire these onClick actions for new reservation:<br />
        <div style="font-family: 'Courier New', Courier, mono; font-size: 10px; color:#196601;line-height: 1.4em;">
            ga('send', 'event', 'Car Rental', 'Click', '1. Search for all cars');<br />
            ga('send', 'event', 'Car Rental', 'Click', '1. Search for single car');<br />
            ga('send', 'event', 'Car Rental', 'Click', '2. Continue to extras');<br />
            ga('send', 'event', 'Car Rental', 'Click', '3. Continue to summary');<br />
            ga('send', 'event', 'Car Rental', 'Click', '4. Confirm reservation');
        </div>
    </li>
    <li>With Enhanced Ecommerce we can track only those cars, which has &quot;Stock Keeping Unit&quot; (SKU) set.</li>
    <li>With Enhanced Ecommerce we can track only those extras, which has &quot;Stock Keeping Unit&quot; (SKU) set.</li>
    <li>If enabled, ReCaptcha validation box is displayed on last step of reservation - in reservation summary, after customer details.</li>
    <li>To use ReCaptcha validation method, you must enter valid site and secret keys. If you don&#39;t have them - you can generate them at
        <a href = "https://www.google.com/recaptcha/admin">Google ReCaptcha Admin</a>.</li>
    <li>If you have set 50 api requests limit, this means that customer, who will lookup customer details for more than 50 times in an hour
        from same ip address will be withheld from fetching any customer details in reservation summary step for 1 hours period
        on his IP address.</li>
    <li>If you have set 3 failed api requests limit, this means that customer, who will fail 3 times in 1 hour to find his customer details
        from same ip address will be withheld from fetching any customer details in reservation summary step for 1 hours period
        on his IP address (or for that email address).</li>
    <li>Car reservation interval - is the shortest time period, required to clean or process the car after return,
        until it will get back online as available for next booking.</li>
    <li>Noon time is used in cars and extras availability calendar for small grey numbers as partial car/extra availability
        from NOON till MIDNIGHT.</li>
    <li>Revealing the partners means, that if enabled, all customers will see, on which partner there is a car created.
        This applies only when partners are used</li>
    <li>Loading assets from the other place, means that scripts/style/fonts/images will be loaded from the current or parent theme (if defined there),
        or from other plugin (if defined there).</li>
</ol>
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery("#global_settings_form").validate();
});
</script>