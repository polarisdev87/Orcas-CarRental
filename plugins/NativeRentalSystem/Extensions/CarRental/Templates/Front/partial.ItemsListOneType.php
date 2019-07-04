<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<div class="car-rental-list-item">
    <div class="item-image">
         <?php if($item['thumb_url'] != ""): ?>
            <a class="fancybox" href="<?php print($item['image_url']); ?>" title="<?php print($item['print_translated_manufacturer_title'].' '.$item['print_translated_model_name'].' '.$item['print_via_partner']); ?>">
                <img src="<?php print($item['thumb_url']); ?>" alt="<?php print($item['print_translated_manufacturer_title'].' '.$item['print_translated_model_name'].' '.$item['print_via_partner']); ?>" />
            </a>
        <?php else: ?>
            &nbsp;
        <?php endif; ?>
    </div>
    <div class="item-description">
        <?php
        if($item['item_page_url']):
            print('<a href="'.$item['item_page_url'].'" title="'.$objLang->getText('NRS_SHOW_ITEM_PAGE_TEXT').'">');
            print('<span class="item-name">'.$item['print_translated_manufacturer_title'].' '.$item['print_translated_model_name'].'</span>');
            print('</a>');
        else:
            print('<span class="item-name">'.$item['print_translated_manufacturer_title'].' '.$item['print_translated_model_name'].'</span>');
        endif;
        ?>
        <br /><hr />
        <?php if($item['partner_profile_url']): ?>
            <div class="description-item">
                <i class="fa fa-user"></i>
                <span class="highlight"><?php print($objLang->getText('NRS_PARTNER_TEXT')); ?>:</span> <?php print($item['print_partner_link']); ?>
            </div>
        <?php endif; ?>

        <?php if($item['show_fuel_type']): ?>
            <div class="description-item">
                <i class="fa fa-tachometer"></i>
                <span class="highlight"> <?php print($objLang->getText('NRS_FUEL_TYPE_TEXT')); ?>:</span> <?php print($item['print_translated_fuel_type_title']); ?>
            </div>
        <?php endif; ?>

        <?php if($item['show_body_type']): ?>
            <div class="description-item">
                <i class="fa fa-car"></i>
                <span class="highlight"><?php print($objLang->getText('NRS_BODY_TYPE_TEXT')); ?>:</span> <?php print($item['print_translated_body_type_title']); ?>
            </div>
        <?php endif; ?>

        <?php if($item['show_transmission_type']): ?>
            <div class="description-item">
                <i class="fa fa-cogs"></i>
                <span class="highlight"><?php print($objLang->getText('NRS_TRANSMISSION_TYPE_TEXT')); ?>:</span> <?php print($item['print_translated_transmission_type_title']); ?>
            </div>
        <?php endif; ?>

        <?php if($item['show_fuel_consumption']): ?>
            <div class="description-item">
                <i class="fa fa-bar-chart"></i>
                <span class="highlight"><?php print($objLang->getText('NRS_ITEM_FUEL_CONSUMPTION_TEXT')); ?>:</span> <?php print($item['print_fuel_consumption']); ?>
            </div>
        <?php endif; ?>

        <?php if($item['show_max_passengers']): ?>
            <div class="description-item">
                <i class="fa fa-users"></i>
                <span class="highlight"><?php print($objLang->getText('NRS_ITEM_PASSENGERS_TEXT')); ?>:</span> <?php print($item['max_passengers']); ?>
            </div>
        <?php endif; ?>

        <div class="description-item">
            <i class="fa fa-credit-card"></i>
            <span class="highlight"><?php print($objLang->getText('NRS_ITEM_PRICE_FROM_TEXT')); ?>:</span>
                <span title="<?php print($item['unit_long_print']['discounted_total_dynamic']); ?>">
                    <?php print($item['unit_long_without_fraction_print']['discounted_total_dynamic']); ?>
                </span> / <?php print($item['time_extension_long_print']); ?>
        </div>
    </div>
    <div class="item-more">
        <?php if($item['show_features']): ?>
            <div class="item-features-title"><?php print($objLang->getText('NRS_ITEM_FEATURES_TEXT')); ?></div><hr />
            <ul class="car-rental-item-features-list">
                <?php foreach($item['features'] AS $feature): ?>
                    <li><?php print($feature); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <div class="car-rental-buttons">
            <?php if($item['item_page_url']): ?>
                <div class="car-rental-single-button">
                    <?php print('<a href="'.$item['item_page_url'].'" title="'.$objLang->getText('NRS_BOOK_ITEM_TEXT').'">'.$objLang->getText('NRS_BOOK_ITEM_TEXT').'</a>'); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
