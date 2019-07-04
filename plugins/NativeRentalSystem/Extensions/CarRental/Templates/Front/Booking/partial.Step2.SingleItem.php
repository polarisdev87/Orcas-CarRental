<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<div class="content car-rental-list-item">
    <div class="col1 item-image">
        <?php if($item['thumb_url'] != ""): ?>
            <a class="fancybox" href="<?php print($item['image_url']); ?>" title="<?php print($item['print_translated_manufacturer_title'].' '.$item['print_translated_model_name'].' '.$item['print_via_partner']); ?>">
                <img src="<?php print($item['thumb_url']); ?>" alt="<?php print($item['print_translated_manufacturer_title'].' '.$item['print_translated_model_name'].' '.$item['print_via_partner']); ?>" />
            </a>
        <?php else: ?>
            &nbsp;
        <?php endif; ?>
    </div>
    <div class="col2 item-details">
        <?php
        if($item['item_page_url'])
        {
            // Because this is a search process, we should open the link in new tab
            print('<a href="'.$item['item_page_url'].'" target="_blank" title="'.$objLang->getText('NRS_SHOW_ITEM_PAGE_TEXT').'">');
            print('<span class="item-name">'.$item['print_translated_manufacturer_title'].' '.$item['print_translated_model_name'].'</span>');
            print('</a>');
        } else
        {
            print('<span class="item-name">'.$item['print_translated_manufacturer_title'].' '.$item['print_translated_model_name'].'</span>');
        }
        ?>

        <?php if($item['partner_profile_url']): ?>
            <div class="description-item">
                <i class="fa fa-user"></i> <span class="highlight"><?php print($objLang->getText('NRS_PARTNER_TEXT')); ?>:</span> <?php print($item['print_partner_link']); ?>
            </div>
        <?php endif; ?>

        <?php if($item['show_body_type']): ?>
            <div class="description-item">
                <i class="fa fa-car"></i> <span class="highlight"><?php print($objLang->getText('NRS_BODY_TYPE_TEXT')); ?>:</span> <?php print($item['body_type_title']); ?>
            </div>
        <?php endif; ?>

        <?php if($item['show_transmission_type']): ?>
            <div class="description-item">
                <i class="fa fa-cogs"></i> <span class="highlight"><?php print($objLang->getText('NRS_TRANSMISSION_TYPE_TEXT')); ?>:</span> <?php print($item['transmission_type_title']); ?>
            </div>
        <?php endif; ?>

        <?php if($item['show_fuel_consumption']): ?>
            <div class="description-item">
                <i class="fa fa-bar-chart"></i> <span class="highlight"><?php print($objLang->getText('NRS_ITEM_FUEL_CONSUMPTION_TEXT')); ?>:</span> <?php print($item['fuel_consumption']); ?>
            </div>
        <?php endif; ?>

        <?php if($item['show_max_passengers']): ?>
            <div class="description-item">
                <i class="fa fa-users"></i> <span class="highlight"><?php print($objLang->getText('NRS_ITEM_PASSENGERS_TEXT')); ?>:</span> <?php print($item['max_passengers']); ?>
            </div>
        <?php endif; ?>

        <?php if($item['show_fuel_type']): ?>
            <div class="description-item">
                <i class="fa fa-tachometer"></i> <span class="highlight"><?php print($objLang->getText('NRS_FUEL_TYPE_TEXT')); ?>:</span> <?php print($item['fuel_type_title']); ?>
            </div>
        <?php endif; ?>

        <?php if ($item['show_features']): ?>
            <ul class="car-rental-item-features-list">
                <?php foreach($item['features'] AS $feature): ?>
                    <li><?php print($feature); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
    <div class="col3 item-price">
        <span class="mobile-only"><?php print($objLang->getText('NRS_TOTAL_TEXT')); ?>:</span>
        <span title="<?php
        if($item['tax_percentage'] > 0):
            print($item['unit_print']['discounted_total'].' '.$objLang->getText('NRS_WITHOUT_TAX_TEXT').' + ');
            print($item['unit_print']['discounted_tax_amount'].' '.$objLang->getText('NRS_TAX_TEXT').' = ');
            print($item['unit_print']['discounted_total_with_tax']);
        endif;
        ?>" style="cursor:pointer">
            <?php print($item['unit_long_print']['discounted_total_dynamic']); ?>
        </span><br />
        <span  class="price-per-period" style="cursor:pointer">
            <?php print($item['']); ?>
        </span>
    </div>
    <?php if($depositsEnabled): ?>
        <div class="col4 item-deposit">
            <span class="mobile-only"><?php print($objLang->getText('NRS_DEPOSIT_TEXT')); ?>:</span>
            <?php print($item['unit_long_print']['fixed_deposit_amount']); ?>
        </div>
    <?php endif; ?>
    <div class="col5 item-select">
        <?php if($multiMode): ?>
            <label class="checkbox">
                <input type="checkbox" name="item_ids[]" value="<?php print($item['item_id']); ?>"<?php print($item['print_checked']); ?> class="required" />
                <span><?php print($objLang->getText('NRS_CHOOSE_TEXT')); ?></span>
            </label>
        <?php else: ?>
            <form action="" name="form1" id="form_item<?php print($item['item_id']); ?>" method="post">
                <input type="hidden" name="car_rental_came_from_step2" value="yes" />
                <input type="hidden" name="item_ids[]" value="<?php print($item['item_id']); ?>" />
                <?php if($newBooking == FALSE): ?>
                    <button id="car_rental_do_search_item<?php print($item['item_id']); ?>" name="car_rental_do_search2" type="submit" class="<?php print($item['print_selected']); ?>"><?php print($objLang->getText('NRS_CHOOSE_TEXT')); ?></button>
                <?php else: ?>
                    <?php
                    if($objSettings->getSetting('conf_universal_analytics_events_tracking') == 1):
                        // Note: Do not translate events to track well inter-language events
                        $onClick = "ga('send', 'event', 'Car Rental', 'Click', '2. Continue to extras');";
                        print('<button id="car_rental_do_search_item'.$item['item_id'].'" name="car_rental_do_search2" type="submit" class="'.$item['print_selected'].'" onClick="'.esc_js($onClick).'">'.$objLang->getText('NRS_CHOOSE_TEXT').'</button>');
                    else:
                        print('<button id="car_rental_do_search_item'.$item['item_id'].'" name="car_rental_do_search2" type="submit" class="'.$item['print_selected'].'">'.$objLang->getText('NRS_CHOOSE_TEXT').'</button>');
                    endif;
                    ?>
                <?php endif; ?>
            </form>
        <?php endif; ?>
    </div>
</div>