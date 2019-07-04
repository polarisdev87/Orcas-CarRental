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
<div class="car-rental-wrapper car-rental-manufacturers-grid car-rental-loading">
<?php if($gotResults): ?>
    <?php foreach($manufacturers AS $manufacturer): ?>
        <div class="car-rental-manufacturer-box">
            <?php if($manufacturer['thumb_url'] != ""): ?>
                <img src="<?php print($manufacturer['thumb_url']); ?>" title="<?php print($manufacturer['print_translated_manufacturer_title']); ?>" alt="<?php print($manufacturer['print_translated_manufacturer_title']); ?>">
            <?php else: ?>
                <div class="car-rental-manufacturer-title">
                    <?php print($manufacturer['print_translated_manufacturer_title']); ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
    <div class="clear">&nbsp;</div>
<?php else: ?>
    <div class="no-manufacturers-available"><?php print($objLang->getText('NRS_NO_MANUFACTURERS_AVAILABLE_TEXT')); ?></div>
<?php endif; ?>
</div>