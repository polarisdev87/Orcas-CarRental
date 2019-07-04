<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
// Scripts
wp_enqueue_script('jquery');
wp_enqueue_script('jquery.mousewheel'); // Optional for fancyBox
if($objSettings->getSetting('conf_load_fancybox_from_plugin') == 1):
    wp_enqueue_script('fancybox');
endif;

// Styles
if($objSettings->getSetting('conf_load_fancybox_from_plugin') == 1):
    wp_enqueue_style('fancybox');
endif;
if($objSettings->getSetting('conf_load_font_awesome_from_plugin') == 1):
    wp_enqueue_style('font-awesome');
endif;
wp_enqueue_style('car-rental-frontend');
?>
<div class="car-rental-wrapper car-rental-single-item" >
    <?php if($item['enabled'] == 1): ?>
        <?php
        if($item['item_sku'] != "" && $objSettings->getSetting('conf_universal_analytics_enhanced_ecommerce') == 1):
            include('partial.SingleItem.EnhancedEcommerce.php');
        endif;
        ?>
        <div class="car-rental-list-single-item">
            <div class="item-images">
                <div class="item-big-image">
                    <?php if($item['big_thumb_url']): ?>
                        <a class="item-main-image fancybox" rel="group" href="<?php print($item['image_url']); ?>" title="<?php print($item['print_translated_manufacturer_title'].' '.$item['print_translated_model_name'].' '.$item['print_via_partner']); ?>">
                            <img src="<?php print($item['big_thumb_url']); ?>" alt="<?php print($item['print_translated_manufacturer_title'].' '.$item['print_translated_model_name'].' '.$item['print_via_partner']); ?>" /><br />
                        </a>
                    <?php else: ?>
                        &nbsp;
                    <?php endif; ?>
                </div>
                <div class="item-small-images">
                    <?php if($item['mini_thumb_url'] && ($item['mini_thumb_2_url'] || $item['mini_thumb_3_url'])): ?>
                        <div class="item-small-image">
                            <a class="item-image-1 fancybox" rel="group" href="<?php print($item['image_url']); ?>" title="<?php print($item['print_translated_manufacturer_title'].' '.$item['print_translated_model_name'].' '.$item['print_via_partner']); ?>">
                                <img src="<?php print($item['mini_thumb_url']); ?>" title="<?php print($item['big_thumb_url']); ?>" alt="<?php print($item['print_translated_manufacturer_title'].' '.$item['print_translated_model_name'].' '.$item['print_via_partner']); ?>" />
                            </a>
                        </div>
                    <?php endif; ?>
                    <?php if($item['mini_thumb_2_url']): ?>
                        <div class="item-small-image">
                            <a class="item-image-2 fancybox" rel="group" href="<?php print($item['image_2_url']); ?>" title="<?php print($item['print_translated_manufacturer_title'].' '.$item['print_translated_model_name'].' '.$item['print_via_partner']); ?>">
                                <img src="<?php print($item['mini_thumb_2_url']); ?>" title="<?php print($item['big_thumb_2_url']); ?>" alt="<?php print($item['print_translated_manufacturer_title'].' '.$item['print_translated_model_name'].' '.$item['print_via_partner']); ?>" />
                            </a>
                        </div>
                    <?php endif; ?>
                    <?php if($item['mini_thumb_3_url']): ?>
                        <div class="item-small-image">
                            <a class="item-image-3 fancybox" rel="group" href="<?php print($item['image_3_url']); ?>" title="<?php print($item['print_translated_manufacturer_title'].' '.$item['print_translated_model_name'].' '.$item['print_via_partner']); ?>">
                                <img src="<?php print($item['mini_thumb_3_url']); ?>" title="<?php print($item['big_thumb_3_url']); ?>" alt="<?php print($item['print_translated_manufacturer_title'].' '.$item['print_translated_model_name'].' '.$item['print_via_partner']); ?>" />
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="item-description">
                <?php if($item['partner_profile_url']): ?>
                    <div class="description-item">
                        <i class="fa fa-user"></i>
                        <span class="highlight"><?php print($objLang->getText('NRS_PARTNER_TEXT')); ?>:</span> <?php print($item['print_partner_link']); ?>
                    </div>
                <?php endif; ?>

                <?php if($item['show_body_type']): ?>
                    <div class="description-item">
                        <i class="fa fa-car"></i>
                        <span class="highlight"><?php print($objLang->getText('NRS_BODY_TYPE_TEXT')); ?>:</span> <?php print($item['print_translated_body_type_title']); ?>
                    </div>
                <?php endif; ?>

                <?php if($item['show_fuel_type']): ?>
                    <div class="description-item">
                        <i class="fa fa-tachometer"></i>
                        <span class="highlight"><?php print($objLang->getText('NRS_FUEL_TYPE_TEXT')); ?>:</span> <?php print($item['print_translated_fuel_type_title']); ?>
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

                <?php if($item['show_engine_capacity']): ?>
                    <div class="description-item">
                        <i class="fa fa-tachometer"></i>
                        <span class="highlight"><?php print($objLang->getText('NRS_ITEM_ENGINE_CAPACITY_TEXT')); ?>:</span> <?php print($item['print_engine_capacity']); ?>
                    </div>
                <?php endif; ?>

                <?php if($item['show_max_luggage']): ?>
                    <div class="description-item">
                        <i class="fa fa-briefcase"></i>
                        <span class="highlight"><?php print($objLang->getText('NRS_ITEM_LUGGAGE_TEXT')); ?>:</span> <?php print($item['max_luggage']); ?>
                    </div>
                <?php endif; ?>

                <?php if($item['show_item_doors']): ?>
                    <div class="description-item">
                        <i class="fa fa-car"></i>
                        <span class="highlight"><?php print($objLang->getText('NRS_ITEM_DOORS_TEXT')); ?>:</span> <?php print($item['item_doors']); ?>
                    </div>
                <?php endif; ?>

                <?php if($item['show_min_driver_age']): ?>
                    <div class="description-item">
                        <i class="fa fa-user"></i>
                        <span class="highlight"><?php print($objLang->getText('NRS_ITEM_DRIVER_AGE_TEXT')); ?>:</span> <?php print($item['min_driver_age']); ?>
                    </div>
                <?php endif; ?>

                <div class="description-item">
                    <i class="fa fa-credit-card"></i>
                    <span class="highlight"><?php print($objLang->getText('NRS_ITEM_PRICE_FROM_TEXT')); ?>:</span>
                    <span title="<?php print($item['unit_long_print']['discounted_total_dynamic']); ?>">
                        <?php print($item['unit_long_without_fraction_print']['discounted_total_dynamic']); ?>
                    </span> / <?php print($item['time_extension_long_print']); ?>
                </div>

                <?php if($depositsEnabled): ?>
                    <div class="description-item">
                        <i class="fa fa-credit-card"></i>
                        <span class="highlight"><?php print($objLang->getText('NRS_DEPOSIT_TEXT')); ?>:</span>
                        <span title="<?php print($item['unit_long_print']['fixed_deposit_amount']); ?>">
                            <?php print($item['unit_long_without_fraction_print']['fixed_deposit_amount']); ?>
                        </span>
                    </div>
                <?php endif; ?>

                <?php if($item['show_mileage']): ?>
                    <div class="description-item">
                        <i class="fa fa-tachometer"></i>
                        <span class="highlight"><?php print($objLang->getText('NRS_MILEAGE_TEXT')); ?>:</span> <?php print($item['print_mileage']); ?>
                    </div>
                <?php endif; ?>

                <?php if($item['show_features']): ?>
                    <div class="item-features">
                        <span class="item-features-label"><?php print($objLang->getText('NRS_ADDITIONAL_INFORMATION_TEXT')); ?></span><br />
                        <ul class="car-rental-item-features-list">
                            <?php foreach($item['features'] AS $feature): ?>
                                <li><?php print($feature); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
            <div class="clear">&nbsp;</div>
        </div>
    <?php else: ?>
        <div class="no-items-available"><?php print($objLang->getText('NRS_ERROR_ITEM_NOT_AVAILABLE_TEXT')); ?></div>
    <?php endif; ?>
</div>
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('.fancybox').fancybox();
    var mainImageHref = jQuery(".item-main-image");
    var mainImageFile = jQuery(".item-main-image img");
    var image1Href = jQuery(".item-image-1");
    var image1File = jQuery(".item-image-1 img");
    var image2Href = jQuery(".item-image-2");
    var image2File = jQuery(".item-image-2 img");
    var image3Href = jQuery(".item-image-3");
    var image3File = jQuery(".item-image-3 img");
    if(image1Href.length)
    {
        image1File.hover(
            function() {
                mainImageHref.attr("href", image1Href.attr("href"));
                mainImageFile.attr("src", image1File.attr("title"));
            }, function() {
                // Do nothing
            }
        );
    }
    if(image2Href.length)
    {
        image2File.hover(
            function() {
                mainImageHref.attr("href", image2Href.attr("href"));
                mainImageFile.attr("src", image2File.attr("title"));
            }, function() {
                // Do nothing
            }
        );

    }
    if(image3Href.length)
    {
        image3File.hover(
            function() {
                mainImageHref.attr("href", image3Href.attr("href"));
                mainImageFile.attr("src", image3File.attr("title"));
            }, function() {
                // Do nothing
            }
        );

    }
});
</script>