<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
// Scripts
wp_enqueue_script('jquery');
wp_enqueue_script('jquery-validate');
wp_enqueue_script( 'car-rental-frontend' );

// Styles
if($objSettings->getSetting('conf_load_font_awesome_from_plugin') == 1):
    wp_enqueue_style('font-awesome');
endif;
wp_enqueue_style('car-rental-frontend');
if($newBooking == TRUE && $cameFromSingleStep1 && $objSettings->getSetting('conf_universal_analytics_enhanced_ecommerce') == 1):
    include('partial.Step3.EnhancedEcommerce.php');
endif;
?>
<div class="car-rental-wrapper car-rental-options">
    <?php
    if($complexPickup || $complexReturn)
    {
        include('partial.LocationsSummaryComplex.php');
    } else
    {
        include('partial.LocationsSummarySimple.php');
    }
    ?>
    <div class="clear">&nbsp;</div>
    <form name="form1" id="form1" method="post" action="">
    <h2 class="search-label"><?php print($objLang->getText('NRS_SELECTED_ITEMS_TEXT')); ?></h2>

    <div class="content list-headers">
        <div class="col1 item-details">
            <?php print($objLang->getText('NRS_ITEM_TEXT')); ?>
        </div>
        <div class="col2 item-options">
            &nbsp;
        </div>
        <div class="col3 item-price">
            <?php print($objLang->getText('NRS_TOTAL_TEXT')); ?>
        </div>
        <div class="col4 item-deposit">
            <?php if($depositsEnabled): ?>
                <?php print($objLang->getText('NRS_DEPOSIT_TEXT')); ?>
            <?php else: ?>
                &nbsp;
            <?php endif; ?>
        </div>
        <div class="col5 item-quantity">
            &nbsp;
        </div>
    </div>
    <?php foreach ($items AS $item): ?>
        <div class="selected-item">
            <div class="col1 item-details">
                <?php print($item['print_translated_body_type_title'] ? $item['print_translated_body_type_title']."," : ""); ?>
                <?php print($item['print_translated_manufacturer_title'].' '.$item['print_translated_model_name'].' '.$item['print_via_partner']); ?>
            </div>
            <div class="col2 item-options">
                <?php if($item['options_html'] != ""): ?>
                    <?php print($item['options_html']); ?>
                <?php else: ?>
                    &nbsp;
                <?php endif; ?>
            </div>
            <div class="col3 item-price">
                <span class="mobile-only"><?php print($objLang->getText('NRS_TOTAL_TEXT')); ?>:</span>
                <span title="<?php
                if($item['tax_percentage'] > 0):
                    print($item['unit_print']['discounted_total'].' '.$objLang->getText('NRS_WITHOUT_TAX_TEXT').' + ');
                    print( $item['unit_print']['discounted_tax_amount'].' '.$objLang->getText('NRS_TAX_TEXT').' = ');
                    print($item['unit_print']['discounted_total_with_tax']);
                endif;
                ?>" style="cursor:pointer">
                    <?php print($item['unit_long_print']['discounted_total_dynamic']); ?>
                </span>
            </div>
            <div class="col4 item-deposit">
                <?php if($depositsEnabled): ?>
                    <span class="mobile-only"><?php print($objLang->getText('NRS_DEPOSIT_TEXT')); ?>:</span>
                    <?php print($item['unit_long_print']['fixed_deposit_amount']); ?>
                <?php else: ?>
                    &nbsp;
                <?php endif; ?>
            </div>
            <div class="col5 item-quantity">
                <?php if($item['max_allowed_units'] > 1): ?>
                    <span class="mobile-only"><?php print($objLang->getText('NRS_QUANTITY_TEXT')); ?>:</span>
                        <select name="item_units[<?php print($item['item_id']); ?>]" id="item_units_<?php print($item['item_id']); ?>" class="required">
                            <?php print($item['quantity_dropdown_options']); ?>
                        </select>
                <?php else: ?>
                    <input type="hidden" name="item_units[<?php print($item['item_id']); ?>]" value="1" />
                    &nbsp;
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="clear">&nbsp;</div>
    <h2 class="search-label top-padded"><?php print($objLang->getText('NRS_RENTAL_OPTIONS_TEXT')); ?></h2>
    <div class="content list-headers">
        <div class="col1 extra-name">
            <?php print($objLang->getText('NRS_EXTRA_TEXT')); ?>
        </div>
        <div class="col2 extra-options">
            &nbsp;
        </div>
        <div class="col3 extra-price">
            <?php print($objLang->getText('NRS_TOTAL_TEXT')); ?>
        </div>
        <div class="col4 extra-deposit">
            <?php if($depositsEnabled): ?>
                <?php print($objLang->getText('NRS_DEPOSIT_TEXT')); ?>
            <?php else: ?>
                &nbsp;
            <?php endif; ?>
        </div>
        <div class="col5 extra-select">
            &nbsp;
        </div>
    </div>


    <?php foreach ($extras AS $extra): ?>
        <div class="extra">
            <div class="col1 extra-name">
                <?php print($extra['print_translated_extra']); ?>
            </div>
            <div class="col2 extra-options">
                <?php if($extra['options_html'] != ""): ?>
                    <?php print($extra['options_html']); ?>
                <?php else: ?>
                    &nbsp;
                <?php endif; ?>
            </div>
            <div class="col3 extra-price">
                <span class="mobile-only"><?php print($objLang->getText('NRS_TOTAL_TEXT')); ?>:</span>
                <span title="<?php
                if($extra['tax_percentage'] > 0):
                    print($extra['unit_print']['discounted_total'].' '.$objLang->getText('NRS_WITHOUT_TAX_TEXT').' + ');
                    print($extra['unit_print']['discounted_tax_amount'].' '.$objLang->getText('NRS_TAX_TEXT').' = '.$extra['unit_print']['discounted_total_with_tax']);
                endif;
                ?>" style="cursor:pointer">
                    <?php print($extra['unit_long_print']['discounted_total_dynamic']); ?>
                </span>
            </div>
            <div class="col4 extra-deposit">
                <?php if($depositsEnabled): ?>
                    <span class="mobile-only"><?php print($objLang->getText('NRS_DEPOSIT_TEXT')); ?>:</span>
                    <?php print($extra['unit_long_print']['fixed_deposit_amount']); ?>
                <?php else: ?>
                    &nbsp;
                <?php endif; ?>
            </div>
            <div class="col5 extra-select">
                <span class="mobile-only"><?php print($objLang->getText('NRS_QUANTITY_TEXT')); ?>:</span>
                <input type="hidden" name="extra_ids[]" value="<?php print($extra['extra_id']); ?>" />
                    <select name="extra_units[<?php print($extra['extra_id']); ?>]" id="extra_units_<?php print($extra['extra_id']); ?>" class="required">
                        <?php print($extra['quantity_dropdown_options']); ?>
                    </select>
            </div>
        </div>
    <?php endforeach; ?>
    <?php if($gotAnyExtras == FALSE):  ?>
        <div class="no-items">
            <?php print($objLang->getText('NRS_NO_EXTRAS_AVAILABLE_CLICK_CONTINUE_TEXT')); ?>
        </div>
    <?php endif; ?>
    <div class="buttons">
        <?php if($newBooking == FALSE): ?>
            <input type="submit" name="car_rental_cancel_booking" value="<?php print($objLang->getText('NRS_CANCEL_BOOKING_TEXT')); ?>" />
            <input type="submit" name="car_rental_do_search0" value="<?php print($objLang->getText('NRS_CHANGE_BOOKING_DATE_TIME_AND_LOCATION_TEXT')); ?>" />
            <input type="submit" name="car_rental_do_search" value="<?php print($objLang->getText('NRS_CHANGE_BOOKED_ITEMS_TEXT')); ?>" />
            <button id="car_rental_do_search" name="car_rental_do_search3" type="submit"><?php print($objLang->getText('NRS_CONTINUE_TEXT')); ?></button>
        <?php else: ?>
            <?php
            if($objSettings->getSetting('conf_universal_analytics_events_tracking') == 1):
                // Note: Do not translate events to track well inter-language events
                $onClick = "ga('send', 'event', 'Car Rental', 'Click', '3. Continue to summary');";
                print('<button id="car_rental_do_search" name="car_rental_do_search3" type="submit" onClick="'.esc_js($onClick).'">'.$objLang->getText('NRS_CONTINUE_TEXT').'</button>');
            else:
                print('<button id="car_rental_do_search" name="car_rental_do_search3" type="submit">'.$objLang->getText('NRS_CONTINUE_TEXT').'</button>');
            endif;
            ?>
        <?php endif; ?>
        <input type="hidden" name="car_rental_came_from_step3" value="yes" />
    </div>
    </form>
    <div class="clear">&nbsp;</div>
</div>