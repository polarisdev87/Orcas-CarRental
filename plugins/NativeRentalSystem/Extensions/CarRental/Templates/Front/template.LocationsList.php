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
<div class="car-rental-wrapper car-rental-locations-list">
    <?php if($gotResults): ?>
        <?php foreach($locations as $location): ?>
            <div class="car-rental-location">
<div class="location-description">
    <?php
    if($location['location_page_url'])
    {
        print('<a href="'.$location['location_page_url'].'" title="'.$objLang->getText('NRS_SHOW_ITEM_PAGE_TEXT').'">');
        print('<span class="location-name">'.$location['print_translated_location_name'].'</span>');
        print('</a>');
    } else
    {
        print('<span class="location-name">'.$location['print_translated_location_name'].'</span>');
    }
    ?>
    <br /><hr />

    <?php if($location['show_full_address'] != ''): ?>
        <div class="description-location">
            <i class="fa fa-map-signs"></i>
            <span class="highlight"><?php print($objLang->getText('NRS_ADDRESS_TEXT')); ?>:</span>
            <?php print($location['print_full_address']); ?>
        </div>
    <?php endif; ?>

    <?php if($location['show_phone']): ?>
        <div class="description-location">
            <i class="fa fa-phone"></i>
            <span class="highlight">&nbsp;<?php print($objLang->getText('NRS_PHONE_TEXT')); ?>:</span>
            <?php print($location['print_phone']); ?>
        </div>
    <?php endif; ?>

    <div class="car-rental-buttons">
        <?php
        if($location['location_page_url'])
        {
            // TO-DO: TRANSLATE IS MANDATORY HERE FOR 'Show car description' and 'Details
            print('<div class="car-rental-single-button"><a href="'.$location['location_page_url'].'" title="'.$objLang->getText('NRS_VIEW_LOCATION_TEXT').'">');
            print($objLang->getText('NRS_VIEW_LOCATION_TEXT'));
            print('</div></a>');
        }
        ?>
    </div>
</div>
<div class="location-business-hours">
    <div class="car-rental-section-title"><?php print($objLang->getText('NRS_LOCATIONS_BUSINESS_HOURS_TEXT')); ?></div><hr />
    <ul class="car-rental-business-hours-list">
        <li>
            <i class="fa fa-clock-o"></i>
            <span class="highlight"><?php print($objLang->getText('NRS_MON_TEXT')); ?>:</span>
            <?php print($location['business_hours']['mon']); ?>
        </li>
        <li>
            <i class="fa fa-clock-o"></i>
            <span class="highlight"><?php print($objLang->getText('NRS_TUE_TEXT')); ?>:</span>
            <?php print($location['business_hours']['mon']); ?>
        </li>
        <li>
            <i class="fa fa-clock-o"></i>
            <span class="highlight"><?php print($objLang->getText('NRS_WED_TEXT')); ?>:</span>
            <?php print($location['business_hours']['mon']); ?>
        </li>
        <li>
            <i class="fa fa-clock-o"></i>
            <span class="highlight"><?php print($objLang->getText('NRS_THU_TEXT')); ?>:</span>
            <?php print($location['business_hours']['thu']); ?>
        </li>
        <li>
            <i class="fa fa-clock-o"></i>
            <span class="highlight"><?php print($objLang->getText('NRS_FRI_TEXT')); ?>:</span>
            <?php print($location['business_hours']['fri']); ?>
        </li>
        <li>
            <i class="fa fa-clock-o"></i>
            <span class="highlight"><?php print($objLang->getText('NRS_SAT_TEXT')); ?>:</span>
            <?php print($location['business_hours']['sat']); ?>
        </li>
        <li>
            <i class="fa fa-clock-o"></i>
            <span class="highlight"><?php print($objLang->getText('NRS_SUN_TEXT')); ?>:</span>
            <?php print($location['business_hours']['sun']); ?>
        </li>
    </ul>
</div>
<div class="location-more">
    <?php if($location['print_lunch_hours']): ?>
        <div class="car-rental-section-title"><?php print($objLang->getText('NRS_LUNCH_TIME_TEXT')); ?></div><hr />
        <ul class="car-rental-lunch-hours-list">
            <li>
                <i class="fa fa-clock-o"></i>
                <span class="highlight"><?php print($objLang->getText('NRS_MON_TEXT').'-'.$objLang->getText('NRS_SUN_TEXT')); ?>:</span>
                <?php print($location['print_lunch_hours']); ?>
            </li>
        </ul>
    <?php endif; ?>
    <div class="car-rental-section-title"><?php print($objLang->getText('NRS_LOCATION_FEES_TEXT')); ?></div><hr />
    <ul class="car-rental-fees-list">
        <li>
            <i class="fa fa-money"></i>
            <span class="highlight"><?php print($objLang->getText('NRS_PICKUP_TEXT')); ?>:</span>
            <?php print($location['unit_long_without_fraction_print']['pickup_fee_dynamic']); ?>
        </li>
        <li>
            <i class="fa fa-money"></i>
            <span class="highlight"><?php print($objLang->getText('NRS_RETURN_TEXT')); ?>:</span>
            <?php print($location['unit_long_without_fraction_print']['return_fee_dynamic']); ?>
        </li>
        <?php if($location['afterhours_pickup_allowed'] == 1): ?>
            <li>
                <i class="fa fa-money"></i>
                <span class="highlight"><?php print($objLang->getText('NRS_EARLY_PICKUP_TEXT')); ?>:</span>
                <?php print($location['unit_long_without_fraction_print']['afterhours_pickup_fee_dynamic']); ?>
            </li>
        <?php endif; ?>
        <?php if($location['afterhours_return_allowed'] == 1): ?>
            <li>
                <i class="fa fa-money"></i>
                <span class="highlight"><?php print($objLang->getText('NRS_LATE_RETURN_TEXT')); ?>:</span>
                <?php print($location['unit_long_without_fraction_print']['afterhours_return_fee_dynamic']); ?>
            </li>
        <?php endif; ?>
    </ul>
</div>
<div class="location-image">
    <?php if($location['thumb_4_url'] != ""): ?>
        <a class="fancybox" href="<?php print($location['image_4_url']); ?>" title="<?php print($location['print_translated_location_name']); ?>">
            <img src="<?php print($location['thumb_4_url']); ?>" alt="<?php print($location['print_translated_location_name']); ?>" />
        </a>
    <?php else: ?>
        &nbsp;
    <?php endif; ?>
</div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="no-locations-available"><?php print($objLang->getText('NRS_NO_LOCATIONS_AVAILABLE_TEXT')); ?></div>
    <?php endif; ?>
</div>
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('.fancybox').fancybox();
});
</script>