<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
// Scripts
wp_enqueue_script('jquery');
wp_enqueue_script('jquery-ui-core');
wp_enqueue_script('jquery-ui-tabs');
wp_enqueue_script('jquery-validate');
wp_enqueue_script('car-rental-admin');

// Styles
wp_enqueue_style('jquery-validate');
wp_enqueue_style('car-rental-admin');
?>
<p>&nbsp;</p>
<div id="container-inside" style="width:1000px;" class="car-rental-add-location">
    <span style="font-size:16px; font-weight:bold">Location Add/Edit</span>
    <input type="button" value="Back To Location List" onClick="window.location.href='<?php print($backToListURL); ?>'" style="background: #EFEFEF; float:right; cursor:pointer;"/>
    <hr style="margin-top:10px;"/>
    <form action="<?php print($formAction); ?>" method="post" id="form1" enctype="multipart/form-data">
        <table cellpadding="5" cellspacing="2" border="0">
            <input type="hidden" name="location_id" value="<?php print($locationId); ?>"/>
            <tr>
                <td class="label"><strong>Location Name:<span class="item-required">*</span></strong></td>
                <td><input type="text" name="location_name" value="<?php print($locationName); ?>" id="location_name" class="required" style="width: 254px;" /></td>
            </tr>
            <?php if($objConf->isNetworkEnabled()): ?>
                 <tr>
                    <td class="label"><strong>Location Code:<span class="item-required">*</span></strong></td>
                    <td><input type="text" name="location_code" maxlength="50" value="<?php print($locationCode); ?>" id="location_code" class="required" style="width: 254px;" /><br />
                        <em>(Used when plugin is network-enabled in multisite mode)</em></td>
                </tr>
            <?php endif; ?>
            <?php if($locationPagesDropDown): ?>
                <tr>
                    <td><strong>Location Page:</strong></td>
                    <td><?php print($locationPagesDropDown); ?></td>
                </tr>
            <?php endif; ?>
            <tr>
                <td class="label"><strong>Street Address:</strong></td>
                <td><input type="text" name="street_address" value="<?php print($streetAddress); ?>" id="street_address" style="width: 302px;" /></td>
            </tr>
            <tr>
                <td class="label"><strong>City:</strong></td>
                <td><input type="text" name="city" value="<?php print($city); ?>" id="city" style="width: 302px;" /></td>
            </tr>
            <tr>
                <td class="label"><strong>State:</strong></td>
                <td><input type="text" name="state" value="<?php print($state); ?>" id="state" style="width: 302px;" /></td>
            </tr>
            <tr>
                <td class="label"><strong>Zip Code:</strong></td>
                <td><input type="text" name="zip_code" value="<?php print($zipCode); ?>" id="zip_code" style="width: 302px;" /></td>
            </tr>
            <tr>
                <td class="label"><strong>Country:</strong></td>
                <td><input type="text" name="country" value="<?php print($country); ?>" id="country" style="width: 302px;" /></td>
            </tr>
            <tr>
                <td class="label"><strong>Phone:</strong></td>
                <td><input type="text" name="phone" value="<?php print($phone); ?>" id="phone" style="width: 302px;" /></td>
            </tr>
            <tr>
                <td class="label"><strong>E-mail:</strong></td>
                <td><input type="text" name="email" value="<?php print($email); ?>" id="email" class="email" style="width: 302px;" /></td>
            </tr>
            <tr>
                <td class="label"><strong>Pick-up Fee:<span class="item-required">*</span></strong></td>
                <td>
                    <?php print($objSettings->getSetting('conf_currency_symbol')); ?>
                    <input type="text" name="pickup_fee" value="<?php print($pickupFee); ?>" id="pickup_fee" class="required number" size="4" />
                    <em>(excl. <?php print($objLang->getText('NRS_TAX_TEXT')); ?>)</em>
                </td>
            </tr>
            <tr>
                <td class="label"><strong>Return Fee:<span class="item-required">*</span></strong></td>
                <td>
                    <?php print($objSettings->getSetting('conf_currency_symbol')); ?>
                    <input type="text" name="return_fee" value="<?php print($returnFee); ?>" id="return_fee" class="required number" size="4" />
                    <em>(excl. <?php print($objLang->getText('NRS_TAX_TEXT')); ?>)</em>
                </td>
            </tr>
            <tr>
                <td class="label" colspan="2" align="center"><br /><strong><?php print($objLang->getText('NRS_LOCATIONS_BUSINESS_HOURS_TEXT')); ?></strong></td>
            </tr>
            <tr>
                <td align="right" style="padding-right: 10px; font-weight: bold;"><?php print($objLang->getText('NRS_MONDAYS_TEXT')); ?>:</td>
                <td>
                    <select name="open_time_mon" class="open-time-mon">
                        <?php print($openTimeMondaysDropDownOptions); ?>
                    </select> -
                    <select name="close_time_mon" class="close-time-mon">
                        <?php print($closeTimeMondaysDropDownOptions); ?>
                    </select> &nbsp;
                    <input type="checkbox" id="open_mondays" name="open_mondays" value="yes"<?php print($openMondays); ?>/> Open
                </td>
            </tr>
            <tr>
                <td align="right" style="padding-right: 10px; font-weight: bold;"><?php print($objLang->getText('NRS_TUESDAYS_TEXT')); ?>:</td>
                <td>
                    <select name="open_time_tue" class="open-time-tue">
                        <?php print($openTimeTuesdaysDropDownOptions); ?>
                    </select> -
                    <select name="close_time_tue" class="close-time-tue">
                        <?php print($closeTimeTuesdaysDropDownOptions); ?>
                    </select> &nbsp;
                    <input type="checkbox" id="open_tuesdays" name="open_tuesdays" value="yes"<?php print($openTuesdays); ?>/> Open
                </td>
            </tr>
            <tr>
                <td align="right" style="padding-right: 10px; font-weight: bold;"><?php print($objLang->getText('NRS_WEDNESDAYS_TEXT')); ?>:</td>
                <td>
                    <select name="open_time_wed" class="open-time-wed">
                        <?php print($openTimeWednesdaysDropDownOptions); ?>
                    </select> -
                    <select name="close_time_wed" class="close-time-wed">
                        <?php print($closeTimeWednesdaysDropDownOptions); ?>
                    </select> &nbsp;
                    <input type="checkbox" id="open_wednesdays" name="open_wednesdays" value="yes"<?php print($openWednesdays); ?>/> Open
                </td>
            </tr>
            <tr>
                <td align="right" style="padding-right: 10px; font-weight: bold;"><?php print($objLang->getText('NRS_THURSDAYS_TEXT')); ?>:</td>
                <td>
                    <select name="open_time_thu" class="open-time-thu">
                        <?php print($openTimeThursdaysDropDownOptions); ?>
                    </select> -
                    <select name="close_time_thu" class="close-time-thu">
                        <?php print($closeTimeThursdaysDropDownOptions); ?>
                    </select> &nbsp;
                    <input type="checkbox" id="open_thursdays" name="open_thursdays" value="yes"<?php print($openThursdays); ?>/> Open
                </td>
            </tr>
            <tr>
                <td align="right" style="padding-right: 10px; font-weight: bold;"><?php print($objLang->getText('NRS_FRIDAYS_TEXT')); ?>:</td>
                <td>
                    <select name="open_time_fri" class="open-time-fri">
                        <?php print($openTimeFridaysDropDownOptions); ?>
                    </select> -
                    <select name="close_time_fri" class="close-time-fri">
                        <?php print($closeTimeFridaysDropDownOptions); ?>
                    </select> &nbsp;
                    <input type="checkbox" id="open_fridays" name="open_fridays" value="yes"<?php print($openFridays); ?>/> Open
                </td>
            </tr>
            <tr>
                <td align="right" style="padding-right: 10px; font-weight: bold;"><?php print($objLang->getText('NRS_SATURDAYS_TEXT')); ?>:</td>
                <td>
                    <select name="open_time_sat" class="open-time-sat">
                        <?php print($openTimeSaturdaysDropDownOptions); ?>
                    </select> -
                    <select name="close_time_sat" class="close-time-sat">
                        <?php print($closeTimeSaturdaysDropDownOptions); ?>
                    </select> &nbsp;
                    <input type="checkbox" id="open_saturdays" name="open_saturdays" value="yes"<?php print($openSaturdays); ?>/> Open
                </td>
            </tr>
            <tr>
                <td align="right" style="padding-right: 10px; font-weight: bold;"><?php print($objLang->getText('NRS_SUNDAYS_TEXT')); ?>:</td>
                <td>
                    <select name="open_time_sun" class="open-time-sun">
                        <?php print($openTimeSundaysDropDownOptions); ?>
                    </select> -
                    <select name="close_time_sun" class="close-time-sun">
                        <?php print($closeTimeSundaysDropDownOptions); ?>
                    </select> &nbsp;
                    <input type="checkbox" id="open_sundays" name="open_sundays" value="yes"<?php print($openSundays); ?>/> Open
                </td>
            </tr>
            <tr>
                <td class="label" colspan="2" align="center"><strong><?php print($objLang->getText('NRS_LOCATIONS_LUNCH_TIME_TEXT')); ?></strong></td>
            </tr>
            <tr>
                <td align="right" style="padding-right: 10px; font-weight: bold;"><?php print($objLang->getText('NRS_MON_TEXT')); ?> - <?php print($objLang->getText('NRS_SUN_TEXT')); ?>:</td>
                <td>
                    <select name="lunch_start_time" class="lunch-start-time">
                        <?php print($lunchStartTimeDropDownOptions); ?>
                    </select> -
                    <select name="lunch_end_time" class="lunch-end-time">
                        <?php print($lunchEndTimeDropDownOptions); ?>
                    </select> &nbsp;
                    <input type="checkbox" id="lunch_enabled" name="lunch_enabled" value="yes"<?php print($lunchEnabled); ?>/> Enabled
                </td>
            </tr>
            <tr>
                <td colspan="2"><br /></td>
            </tr>
            <tr>
                <td><strong>Big Map:<br />(Image)</strong></td>
                <td><input type="file" name="location_image_1" id="location_image_1"/>
                    <?php
                    if($locationImage1 != "")
                    {
                        if($demoLocationImage1)
                        {
                            print('<span>&nbsp;&nbsp;&nbsp;&nbsp;<a rel="collection" href="'.$objConf->getExtensionDemoGalleryURL($locationImage1).'" target="_blank"><strong>View Demo Image</strong></a>');
                            print('&nbsp;&nbsp;&nbsp;&nbsp;<strong><span style="color: navy;">Delete Image</span></strong>');
                            print('<input type="checkbox" name="delete_location_image_1"/></span>');
                        } else
                        {
                            print('<span>&nbsp;&nbsp;&nbsp;&nbsp;<a rel="collection" href="'.$objConf->getGalleryURL().$locationImage1.'" target="_blank"><strong>View Image</strong></a>');
                            print('&nbsp;&nbsp;&nbsp;&nbsp;<strong><span style="color: navy;">Delete Image</span></strong>');
                            print('<input type="checkbox" name="delete_location_image_1"/></span>');
                        }
                    } else
                    {
                        print('&nbsp;&nbsp;&nbsp;&nbsp; <b>No Image</b>');
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td><strong>Outside - Street View:<br />(Image)</strong></td>
                <td><input type="file" name="location_image_2" id="location_image_2"/>
                    <?php
                    if($locationImage2 != "")
                    {
                        if($demoLocationImage2)
                        {
                            print('<span>&nbsp;&nbsp;&nbsp;&nbsp;<a rel="collection" href="'.$objConf->getExtensionDemoGalleryURL($locationImage2).'" target="_blank"><strong>View Demo Image</strong></a>');
                            print('&nbsp;&nbsp;&nbsp;&nbsp;<strong><span style="color: navy;">Delete Image</span></strong>');
                            print('<input type="checkbox" name="delete_location_image_2"/></span>');
                        } else
                        {
                            print('<span>&nbsp;&nbsp;&nbsp;&nbsp;<a rel="collection" href="'.$objConf->getGalleryURL().$locationImage2.'" target="_blank"><strong>View Image</strong></a>');
                            print('&nbsp;&nbsp;&nbsp;&nbsp;<strong><span style="color: navy;">Delete Image</span></strong>');
                            print('<input type="checkbox" name="delete_location_image_2"/></span>');
                        }
                    } else
                    {
                        print('&nbsp;&nbsp;&nbsp;&nbsp; <b>No Image</b>');
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td><strong>Inside - Office:<br />(Image)</strong></td>
                <td><input type="file" name="location_image_3" id="location_image_3"/>
                    <?php
                    if($locationImage3 != "")
                    {
                        if($demoLocationImage3)
                        {
                            print('<span>&nbsp;&nbsp;&nbsp;&nbsp;<a rel="collection" href="'.$objConf->getExtensionDemoGalleryURL($locationImage3).'" target="_blank"><strong>View Demo Image</strong></a>');
                            print('&nbsp;&nbsp;&nbsp;&nbsp;<strong><span style="color: navy;">Delete Image</span></strong>');
                            print('<input type="checkbox" name="delete_location_image_3"/></span>');
                        } else
                        {
                            print('<span>&nbsp;&nbsp;&nbsp;&nbsp;<a rel="collection" href="'.$objConf->getGalleryURL().$locationImage3.'" target="_blank"><strong>View Image</strong></a>');
                            print('&nbsp;&nbsp;&nbsp;&nbsp;<strong><span style="color: navy;">Delete Image</span></strong>');
                            print('<input type="checkbox" name="delete_location_image_3"/></span>');
                        }
                    } else
                    {
                        print('&nbsp;&nbsp;&nbsp;&nbsp; <strong>No Image</strong>');
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td><strong>Small List Map:<br />(Image)</strong></td>
                <td><input type="file" name="location_image_4" id="location_image_4"/>
                    <?php
                    if($locationImage4 != "")
                    {
                        if($demoLocationImage4)
                        {
                            print('<span>&nbsp;&nbsp;&nbsp;&nbsp;<a rel="collection" href="'.$objConf->getExtensionDemoGalleryURL($locationImage4).'" target="_blank"><strong>View Demo Image</strong></a>');
                            print('&nbsp;&nbsp;&nbsp;&nbsp;<strong><span style="color: navy;">Delete Image</span></strong>');
                            print('<input type="checkbox" name="delete_location_image_4"/></span>');
                        } else
                        {
                            print('<span>&nbsp;&nbsp;&nbsp;&nbsp;<a rel="collection" href="'.$objConf->getGalleryURL().$locationImage4.'" target="_blank"><strong>View Image</strong></a>');
                            print('&nbsp;&nbsp;&nbsp;&nbsp;<strong><span style="color: navy;">Delete Image</span></strong>');
                            print('<input type="checkbox" name="delete_location_image_4"/></span>');
                        }
                    } else
                    {
                        print('&nbsp;&nbsp;&nbsp;&nbsp; <strong>No Image</strong>');
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td><strong>After Hours<br />Pick-up:</strong></td>
                <td>
                    &nbsp;<input type="checkbox" id="afterhours_pickup_allowed" name="afterhours_pickup_allowed"<?php print($afterHoursPickupAllowedChecked); ?>/> Allowed
                </td>
            </tr>
            <tr>
                <td class="label"><strong>After Hours<br />Pick-up Location:</strong></td>
                <td>
                    <select name="afterhours_pickup_location_id" id="afterhours_pickup_location_id">
                        <?php print($afterHoursPickupDropDownOptions); ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="label"><strong>After Hours<br />Pick-up Fee:</strong></td>
                <td>
                    <?php print($objSettings->getSetting('conf_currency_symbol')); ?>
                    <input type="text" name="afterhours_pickup_fee" value="<?php print($afterHoursPickupFee); ?>" id="afterhours_pickup_fee" class="number" size="4" />
                    <em>(<?php print($locationId > 0 ? 'excl. '.$objLang->getText('NRS_TAX_TEXT') : 'optional, excl. '.$objLang->getText('NRS_TAX_TEXT')); ?>)</em>
                </td>
            </tr>
            <tr>
                <td><strong>After Hours<br />Return:</strong></td>
                <td>
                    &nbsp;<input type="checkbox" id="afterhours_return_allowed" name="afterhours_return_allowed"<?php print($afterHoursReturnAllowedChecked); ?>/> Allowed
                </td>
            </tr>
            <tr>
                <td class="label"><strong>After Hours<br />Return Location:</strong></td>
                <td>
                    <select name="afterhours_return_location_id" id="afterhours_return_location_id">
                        <?php print($afterHoursReturnDropDownOptions); ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="label"><strong>After Hours<br />Return Fee:</strong></td>
                <td>
                    <?php print($objSettings->getSetting('conf_currency_symbol')); ?>
                    <input type="text" name="afterhours_return_fee" value="<?php print($afterHoursReturnFee); ?>" id="afterhours_return_fee" class="number" size="4" />
                    <em>(<?php print($locationId > 0 ? 'excl. '.$objLang->getText('NRS_TAX_TEXT') : 'optional, excl. '.$objLang->getText('NRS_TAX_TEXT')); ?>)</em>
                </td>
            </tr>
            <tr>
                <td><strong>Location Order:</strong></td>
                <td>
                    <input type="text" name="location_order" value="<?php print($locationOrder); ?>" id="location_order" class="" style="width:40px;" />
                    <em><?php print($locationId > 0 ? '' : '(optional, leave blank to add to the end)'); ?></em>
                </td>
            </tr>
            <tr>
                <td class="label"></td>
                <td><input type="submit" value="Save Location" name="save_location" style="background:#e5f9bb; cursor:pointer;"/></td>
            </tr>
        </table>
    </form>
</div>
<script type="text/javascript">
jQuery().ready(function() {
		jQuery("#form1").validate();
});
</script>