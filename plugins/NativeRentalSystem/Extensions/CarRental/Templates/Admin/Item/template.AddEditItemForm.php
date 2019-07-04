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
<div id="container-inside">
 <p style="font-size:16px; font-weight:bold">&nbsp;</p>
 <p style="font-size:16px; font-weight:bold">Car Add/Edit</p>

<input type="button" value="Back to Car List" onClick="window.location.href='<?php print($backToListURL); ?>'" style="background: #EFEFEF; float:right; cursor:pointer;"/><p><br />
<hr style=" width:100%; margin-top:10px;"/>
  <form action="<?php print($formAction); ?>" method="post" id="form1" enctype="multipart/form-data">
   <table cellpadding="5" cellspacing="2" border="0">
    <input type="hidden" name="item_id" value="<?php print($itemId); ?>"/>
       <tr>
           <td><strong>Model Name:<span class="item-required">*</span></strong></td>
           <td><input type="text" name="model_name" value="<?php print($modelName); ?>" id="model_name" class="required" style="width:170px;" /></td>
       </tr>
       <?php if($objConf->isNetworkEnabled()): ?>
           <tr>
               <td><strong>Stock Keeping Unit:<span class="item-required">*</span></strong></td>
               <td><input type="text" name="item_sku" maxlength="50" value="<?php print($itemSKU); ?>" id="item_sku" class="required" style="width:170px;" /><br />
                   &nbsp;&nbsp;&nbsp; <em>(Used for Google Enhanced Ecommerce tracking<br />
                       and when plugin is network-enabled in multisite mode)</em>
               </td>
           </tr>
       <?php endif; ?>
       <?php if($isManager): ?>
           <tr>
               <td><strong>Partner:</strong></td>
               <td>
                   <select name="partner_id" id="partner_id">
                        <?php print($partnersDropDownOptions); ?>
                   </select>
               </td>
           </tr>
       <?php endif; ?>
        <tr>
           <td><strong>Manufacturer:</strong></td>
           <td>
               <select name="manufacturer_id" id="manufacturer_id">
                   <?php print($manufacturersDropDownOptions); ?>
               </select>
           </td>
        </tr>
        <?php if($itemPagesDropDown): ?>
           <tr>
               <td><strong>Car Page:</strong></td>
               <td><?php print($itemPagesDropDown); ?></td>
           </tr>
        <?php endif; ?>
        <tr>
          <td><strong>Body Type:</strong></td>
          <td>
              <select name="body_type_id" id="body_type_id">
                  <?php print($bodyTypesDropDownOptions); ?>
              </select>
          </td>
        </tr>
        <tr>
          <td><strong>Transmission Type:</strong></td>
          <td>
              <select name="transmission_type_id" id="transmission_type_id">
                  <?php print($transmissionTypesDropDownOptions); ?>
              </select>
          </td>
        </tr>
        <tr>
           <td><strong>Fuel Type:</strong></td>
           <td>
               <select name="fuel_type_id" id="fuel_type_id">
                   <?php print($fuelTypesDropDownOptions); ?>
               </select>
           </td>
        </tr>
       <tr>
           <td><strong>Fuel Consumption:</strong></td>
           <td>
               <input type="text" name="fuel_consumption" value="<?php print($fuelConsumption); ?>" id="fuel_consumption" style="width:170px;" />
               &nbsp;&nbsp;&nbsp; <em>(Leave blank hide the field from displaying)</em>
           </td>
       </tr>
       <tr>
           <td><strong>Engine Capacity:</strong></td>
           <td>
               <input type="text" name="engine_capacity" value="<?php print($engineCapacity); ?>" id="engine_capacity" style="width:170px;" />
               &nbsp;&nbsp;&nbsp; <em>(Leave blank hide the field from displaying)</em>
           </td>
       </tr>
       <tr>
           <td><strong>Daily Mileage Limit:</strong></td>
           <td>
               <input type="text" name="item_mileage" value="<?php print($mileage); ?>" id="item_mileage" class="number" style="width:50px;" />
               &nbsp;<strong><?php print($objSettings->getSetting('conf_distance_measurement_unit')); ?></strong>
               &nbsp;&nbsp;&nbsp; <em>(Leave blank for Unlimited, or enter 0 to hide the field from displaying)</em>
           </td>
       </tr>
       <tr>
          <td><strong>Total Units in Garage:<span class="item-required">*</span></strong></td>
          <td>
              <select name="units_in_stock" id="units_in_stock" class="required">
                  <?php print($unitsInStockDropDownOptions); ?>
              </select>
          </td>
       </tr>
       <tr>
           <td><strong>Max. Units per Reservation:<span class="item-required">*</span></strong></td>
           <td>
               <select name="max_units_per_booking" id="max_units_per_booking" class="required">
                   <?php print($maxUnitsPerBookingDropDownOptions); ?>
               </select>
               &nbsp;&nbsp;&nbsp; <em>(Can&#39;t be more than total car units in garage)</em>
           </td>
        </tr>
        <tr>
            <td valign="top">
                <strong>Pick-up Locations:<span class="item-required">*</span></strong><br />
                <em>(hold SHIFT or CTRL button<br />to select multiple locations)</em>
            </td>
            <td>
                <select multiple="multiple" name="pickup_location_ids[]" style="width:400px" class="required">
                    <?php print($pickupSelectOptions); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td valign="top">
                <strong>Return Locations:<span class="item-required">*</span></strong><br />
                <em>(hold SHIFT or CTRL button<br />to select multiple locations)</em>
            </td>
            <td>
                <select multiple="multiple" name="return_location_ids[]" style="width:400px" class="required">
                    <?php print($returnSelectOptions); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong>Passenger seats (incl. driver):</strong></td>
            <td>
                <select name="max_passengers" id="max_passengers" class="">
                    <?php print($itemPassengersDropDownOptions); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong>Maximum Luggage:</strong></td>
            <td>
                <select name="max_luggage" id="max_luggage" class="">
                    <?php print($itemLuggageDropDownOptions); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong>Total Doors:</strong></td>
            <td>
                <select name="item_doors" id="item_doors" class="">
                    <?php print($itemDoorsDropDownOptions); ?>
                </select>
            </td>
        </tr>
        <tr>
           <td><strong>Minimum Driver Age:</strong></td>
           <td>
               <select name="min_driver_age" id="min_driver_age" class="">
                    <?php print($driversAgeDropDownOptions); ?>
               </select>
           </td>
        </tr>
        <tr>
            <td><strong>Car Main Image:</strong></td>
            <td><input type="file" name="item_image_1" id="item_image_1"/>
            <?php
            if($itemImage1 != "")
            {
                if($demoItemImage1)
                {
                    print('<span>&nbsp;&nbsp;&nbsp;&nbsp;<a rel="collection" href="'.$objConf->getExtensionDemoGalleryURL($itemImage1).'" target="_blank"><strong>View Demo Image</strong></a>');
                    print('&nbsp;&nbsp;&nbsp;&nbsp;<strong><span style="color: navy;">Delete Image</span></strong>');
                    print('<input type="checkbox" name="delete_item_image_1"/></span>');
                } else
                {
                    print('<span>&nbsp;&nbsp;&nbsp;&nbsp;<a rel="collection" href="'.$objConf->getGalleryURL().$itemImage1.'" target="_blank"><strong>View Image</strong></a>');
                    print('&nbsp;&nbsp;&nbsp;&nbsp;<strong><span style="color: navy;">Delete Image</span></strong>');
                    print('<input type="checkbox" name="delete_item_image_1"/></span>');
                }
            } else
            {
                print('&nbsp;&nbsp;&nbsp;&nbsp; <b>No Image</b>');
            }
            ?>
            </td>
        </tr>
       <tr>
           <td><strong>Car Interior Image:</strong></td>
           <td><input type="file" name="item_image_2" id="item_image_2"/>
               <?php
               if($itemImage2 != "")
               {
                   if($demoItemImage2)
                   {
                       print('<span>&nbsp;&nbsp;&nbsp;&nbsp;<a rel="collection" href="'.$objConf->getExtensionDemoGalleryURL($itemImage2).'" target="_blank"><strong>View Demo Image</strong></a>');
                       print('&nbsp;&nbsp;&nbsp;&nbsp;<strong><span style="color: navy;">Delete Image</span></strong>');
                       print('<input type="checkbox" name="delete_item_image_2"/></span>');
                   } else
                   {
                       print('<span>&nbsp;&nbsp;&nbsp;&nbsp;<a rel="collection" href="'.$objConf->getGalleryURL().$itemImage2.'" target="_blank"><strong>View Image</strong></a>');
                       print('&nbsp;&nbsp;&nbsp;&nbsp;<strong><span style="color: navy;">Delete Image</span></strong>');
                       print('<input type="checkbox" name="delete_item_image_2"/></span>');
                   }
               } else
               {
                   print('&nbsp;&nbsp;&nbsp;&nbsp; <b>No Image</b>');
               }
               ?>
           </td>
       </tr>
       <tr>
           <td><strong>Car Boot Image:</strong></td>
           <td><input type="file" name="item_image_3" id="item_image_3"/>
               <?php
               if($itemImage3 != "")
               {
                   if($demoItemImage3)
                   {
                       print('<span>&nbsp;&nbsp;&nbsp;&nbsp;<a rel="collection" href="'.$objConf->getExtensionDemoGalleryURL($itemImage3).'" target="_blank"><strong>View Demo Image</strong></a>');
                       print('&nbsp;&nbsp;&nbsp;&nbsp;<strong><span style="color: navy;">Delete Image</span></strong>');
                       print('<input type="checkbox" name="delete_item_image_3"/></span>');
                   } else
                   {
                       print('<span>&nbsp;&nbsp;&nbsp;&nbsp;<a rel="collection" href="'.$objConf->getGalleryURL().$itemImage3.'" target="_blank"><strong>View Image</strong></a>');
                       print('&nbsp;&nbsp;&nbsp;&nbsp;<strong><span style="color: navy;">Delete Image</span></strong>');
                       print('<input type="checkbox" name="delete_item_image_3"/></span>');
                   }
               } else
               {
                   print('&nbsp;&nbsp;&nbsp;&nbsp; <strong>No Image</strong>');
               }
               ?>
           </td>
       </tr>
       <tr>
            <td valign="top"><strong>Car Features:</strong></td>
            <td><?php print($itemFeatures); ?></td>
       </tr>
       <tr>
           <td><strong>Price Group:</strong></td>
           <td>
               <select name="price_group_id" class="">
                   <?php print($priceGroupDropDownOptions); ?>
               </select>
               <em>(Optional, leave blank to show &#39;<?php print($objLang->getText('NRS_GET_A_QUOTE_TEXT')); ?>&#39; instead of price)</em>
           </td>
       </tr>
       <?php if($depositsEnabled): ?>
           <tr>
               <td><strong>Fixed Rental Deposit:<span class="item-required">*</span></strong></td>
               <td>
                   <input type="text" name="fixed_rental_deposit" value="<?php print($fixedItemRentalDeposit); ?>" id="fixed_rental_deposit" class="required number" style="width:70px;" />
                   &nbsp;
                   <?php print($objSettings->getSetting('conf_currency_code')); ?>
                   &nbsp;&nbsp;&nbsp; <em>(<?php print($objLang->getText('NRS_TAX_TEXT')); ?> is not applicable for deposit - it is a refundable amount with no <?php print($objLang->getText('NRS_TAX_TEXT')); ?> applied to it)</em>
               </td>
           </tr>
       <?php else: ?>
           <input type="hidden" name="fixed_rental_deposit" value="<?php print($fixedExtraRentalDeposit); ?>" />
       <?php endif; ?>
       <tr>
          <td><strong>Display in:</strong></td>
          <td>
              <table width="100%">
                  <tr>
                      <td><input type="checkbox" id="display_in_slider" name="display_in_slider"<?php print($itemDisplayInSliderChecked); ?>/> Car Slider</td>
                      <td><input type="checkbox" id="display_in_item_list" name="display_in_item_list"<?php print($itemDisplayInItemListChecked); ?>/> Car List</td>
                  </tr>
                  <tr>
                      <td><input type="checkbox" id="display_in_price_table" name="display_in_price_table"<?php print($itemDisplayInPriceTableChecked); ?>/> Car Price Table</td>
                      <td><input type="checkbox" id="display_in_calendar" name="display_in_calendar"<?php print($itemDisplayInCalendarChecked); ?>/> Car Calendar</td>
                  </tr>
              </table>
          </td>
       </tr>
       <tr>
           <td><strong>Units of Measurement:</strong></td>
           <td>
               <input type="text" name="options_measurement_unit" value="<?php print($optionsMeasurementUnit); ?>" id="options_measurement_unit" class="" style="width:200px;" />
               &nbsp;&nbsp;&nbsp; <em>(optional, can be left blank. Might be used if some car options added)</em>
           </td>
       </tr>
       <tr>
           <td><strong>Options display mode:</strong><br /><em>(if added)</em></td>
           <td>
               <input type="radio" name="options_display_mode" value="1"<?php print($dropDownDisplayModeChecked); ?> /> <?php print($objLang->getText('NRS_ADMIN_DROPDOWN_TEXT')); ?>
               <input type="radio" name="options_display_mode" value="2"<?php print($sliderDisplayModeChecked); ?> /> <?php print($objLang->getText('NRS_ADMIN_SLIDER_TEXT')); ?><br />
               &nbsp;&nbsp;&nbsp; <em>(Slider can be shown only if all item options have identical steps and are +/- numbers)</em>
           </td>
       </tr>
       <tr>
           <td><strong>Available:</strong></td>
           <td>
               &nbsp;<input type="checkbox" id="enabled" name="enabled" value="yes"<?php print($itemAvailableChecked); ?>/> Available for reservation
           </td>
        </tr>
        <tr>
              <td></td>
              <td><input type="submit" value="Save car" name="save_item" style="cursor: pointer;"/></td>
        </tr>
    </table>
  </form>
</div>
<script type="text/javascript">
jQuery().ready(function() {
    jQuery("#form1").validate();
});
</script>      