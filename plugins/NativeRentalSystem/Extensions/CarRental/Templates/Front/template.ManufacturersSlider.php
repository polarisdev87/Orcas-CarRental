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
<div class="car-rental-wrapper car-rental-manufacturers-slider car-rental-loading">
<?php if($gotResults): ?>
    <div class="responsive-manufacturers-slider">
    <?php foreach($manufacturers AS $manufacturer): ?>
        <div>
            <?php if($manufacturer['thumb_url'] != ""): ?>
                <div class="car-rental-manufacturer-logo">
                    <img src="<?php print($manufacturer['thumb_url']); ?>" title="<?php print($manufacturer['print_translated_manufacturer_title']); ?>" alt="<?php print($manufacturer['print_translated_manufacturer_title']); ?>">
                </div>
            <?php else: ?>
                <div class="car-rental-manufacturer-title">
                    <?php print($manufacturer['print_translated_manufacturer_title']); ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="no-manufacturers-available"><?php print($objLang->getText('NRS_NO_MANUFACTURERS_AVAILABLE_TEXT')); ?></div>
<?php endif; ?>
</div>
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('.responsive-manufacturers-slider').slick({
            dots: false,
            infinite: false,
            speed: 300,
            slidesToShow: 5,
            slidesToScroll: 5,
            prevArrow: '<button type="button" class="car-rental-slider-prev">Previous</button>',
            nextArrow: '<button type="button" class="car-rental-slider-next">Next</button>',
            responsive: [
                {
                    breakpoint: 1280,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 4,
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3
                    }
                },
                {
                    breakpoint: 640,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 360,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        infinite: true,
                        dots: false
                    }
                }
                // You can unslick at a given breakpoint now by adding:
                // settings: "unslick"
                // instead of a settings object
            ]
        });
    });
</script>