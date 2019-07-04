<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
// Scripts
wp_enqueue_script('jquery');
if($objSettings->getSetting('conf_load_slick_slider_from_plugin') == 1):
    wp_enqueue_script('slick-slider');
endif;

// Styles
if($objSettings->getSetting('conf_load_slick_slider_from_plugin') == 1):
    wp_enqueue_style('slick-slider');
    wp_enqueue_style('slick-theme');
endif;
wp_enqueue_style('car-rental-frontend');

?>
<div class="car-rental-wrapper car-rental-slider car-rental-loading">
<?php if($gotResults): ?>
    <div class="responsive-items-slider">
    <?php foreach($items AS $item): ?>
       <div>
            <?php
            print('<div class="car-rental-item-image">');
            if($item['item_page_url']):
                print('<a href="'.$item['item_page_url'].'" title="'.$objLang->getText('NRS_SHOW_ITEM_TEXT').'">');
            endif;
            if($item['thumb_url'] != ""):
                print('<img src="'.$item['thumb_url'].'" title="'.$item['print_translated_manufacturer_title'].' '.$item['print_translated_model_name'].'" alt="'.$item['print_translated_manufacturer_title'].' '.$item['print_translated_model_name'].'">');
            endif;
            if($item['item_page_url']):
                print('</a>');
            endif;
            print('</div>');
            print('<div class="car-rental-item-details">');
                print('<div class="'.($item['partner_profile_url'] ? 'car-rental-item-title-with-partner' : 'car-rental-item-title').'">');
                    if($item['item_page_url'])
                    {
                        print('<a href="'.$item['item_page_url'].'" title="'.$objLang->getText('NRS_SHOW_ITEM_TEXT').'">');
                    }
                    print($item['print_translated_manufacturer_title'].' '.$item['print_translated_model_name']);
                    if($item['item_page_url'])
                    {
                        print('</a>');
                    }
                print('</div>');
                if($item['partner_profile_url']):
                    print('<div class="car-rental-slider-partner-title">'.$item['print_via_partner_link'].'</div>');
                endif;
                if($item['price_group_id'] == 0):
                    print('<div class="car-rental-item-prefix">');
                        print($objLang->getText('NRS_GET_A_QUOTE_TEXT'));
                    print('</div>');
                else:
                    print('<div class="car-rental-item-prefix">');
                        print($objLang->getText('NRS_FROM_TEXT'));
                    print('</div>');
                    print('<div class="car-rental-item-price">');
                        print($item['unit_tiny_without_fraction_print']['discounted_total_dynamic']);
                    print('</div>');
                    print('<div class="car-rental-item-prefix">');
                        print($item['time_extension_long_print']);
                    print('</div>');
                endif;
            print('</div>');
            ?>
       </div>
    <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="no-items-available"><?php print($objLang->getText('NRS_NO_ITEMS_AVAILABLE_TEXT')); ?></div>
<?php endif; ?>
</div>
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('.responsive-items-slider').slick({
        dots: false,
        infinite: false,
        speed: 300,
        slidesToShow: 4,
        slidesToScroll: 4,
        prevArrow: '<button type="button" class="car-rental-slider-prev">Previous</button>',
        nextArrow: '<button type="button" class="car-rental-slider-next">Next</button>',
        responsive: [
            {
                breakpoint: 1280,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 420,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    infinite: true,
                    dots: true
                }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]
    });
});
</script>