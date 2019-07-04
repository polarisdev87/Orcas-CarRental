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
<div class="car-rental-wrapper car-rental-benefits-slider car-rental-loading">
<?php if($gotResults): ?>
    <div class="responsive-benefits-slider">
    <?php foreach($benefits AS $benefit): ?>
        <div>
            <div class="car-rental-benefit-image">
                <?php if($benefit['thumb_url'] != ""): ?>
                    <img src="<?php print($benefit['thumb_url']); ?>" title="<?php print($benefit['print_translated_benefit_title']); ?>" alt="<?php print($benefit['print_translated_benefit_title']); ?>">
                <?php endif; ?>
            </div>
            <div class="car-rental-benefit-title">
                <?php print($benefit['print_translated_benefit_title']); ?>
            </div>
        </div>
    <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="no-benefits-available"><?php print($objLang->getText('NRS_NO_BENEFITS_AVAILABLE_TEXT')); ?></div>
<?php endif; ?>
</div>
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('.responsive-benefits-slider').slick({
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
                    slidesToScroll: 4
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
                breakpoint: 420,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2,
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