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
<div class="car-rental-wrapper car-rental-single-location">
    <div class="car-rental-list-single-location">
        <div class="location-images">
            <div class="location-big-image">
                <?php if($location['big_thumb_url']): ?>
                    <a class="location-main-image fancybox" rel="group" href="<?php print($location['image_url']); ?>" title="<?php print($location['print_translated_location_name']); ?>">
                        <img src="<?php print($location['big_thumb_url']); ?>" alt="<?php print($location['print_translated_location_name']); ?>" /><br />
                    </a>
                <?php else: ?>
                    &nbsp;
                <?php endif; ?>
            </div>
            <div class="location-small-images">
                <?php if($location['mini_thumb_url'] && ($location['mini_thumb_2_url'] || $location['mini_thumb_3_url'])): ?>
                    <div class="location-small-image">
                        <a class="location-image-1 fancybox" rel="group" href="<?php print($location['image_url']); ?>" title="<?php print($location['print_translated_location_name']); ?>">
                            <img src="<?php print($location['mini_thumb_url']); ?>" title="<?php print($location['big_thumb_url']); ?>" alt="<?php print($location['print_translated_location_name']); ?>" />
                        </a>
                    </div>
                <?php endif; ?>
                <?php if($location['mini_thumb_2_url']): ?>
                    <div class="location-small-image">
                        <a class="location-image-2 fancybox" rel="group" href="<?php print($location['image_2_url']); ?>" title="<?php print($location['print_translated_location_name']); ?>">
                            <img src="<?php print($location['mini_thumb_2_url']); ?>" title="<?php print($location['big_thumb_2_url']); ?>" alt="<?php print($location['print_translated_location_name']); ?>" />
                        </a>
                    </div>
                <?php endif; ?>
                <?php if($location['mini_thumb_3_url']): ?>
                    <div class="location-small-image">
                        <a class="location-image-3 fancybox" rel="group" href="<?php print($location['image_3_url']); ?>" title="<?php print($location['print_translated_location_name']); ?>">
                            <img src="<?php print($location['mini_thumb_3_url']); ?>" title="<?php print($location['big_thumb_3_url']); ?>" alt="<?php print($location['print_translated_location_name']); ?>" />
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="location-description">
            <div class="location-more">
                <?php if($location['show_full_address'] || $location['show_phone']): ?>
                    <div class="car-rental-section-title"><?php print($objLang->getText('NRS_CONTACT_DETAILS_TEXT')); ?></div><hr />
                <?php endif; ?>
                <?php if($location['show_full_address']): ?>
                    <div class="description-location">
                        <i class="fa fa-map-signs"></i>
                        <span class="highlight"><?php print($objLang->getText('NRS_ADDRESS_TEXT')); ?>:</span>
                        <?php print($location['print_full_address']); ?>
                    </div>
                <?php endif; ?>

                <?php if($location['show_phone']): ?>
                    <div class="description-location">
                        <i class="fa fa-phone"></i>
                        <span class="highlight"><?php print($objLang->getText('NRS_PHONE_TEXT')); ?>:</span>
                        <?php print($location['print_phone']); ?>
                    </div>
                <?php endif; ?>

                <div class="car-rental-section-title<?php print($location['show_full_address'] || $location['show_phone'] ? ' top-padded' : ''); ?>"><?php print($objLang->getText('NRS_BUSINESS_HOURS_FEES_TEXT')); ?></div><hr />
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
                </ul>
                <?php if($location['afterhours_pickup_allowed'] == 1 || $location['afterhours_return_allowed'] == 1): ?>
                    <div class="car-rental-section-title top-padded"><?php print($objLang->getText('NRS_AFTERHOURS_FEES_TEXT')); ?></div><hr />
                    <ul class="car-rental-fees-list">
                        <?php if($location['afterhours_pickup_allowed'] == 1): ?>
                            <li>
                                <i class="fa fa-money"></i>
                                <span class="highlight"><?php print($objLang->getText('NRS_PICKUP_TEXT')); ?>:</span>
                                <?php print($location['unit_long_without_fraction_print']['afterhours_pickup_fee_dynamic']); ?>
                            </li>
                        <?php endif; ?>
                        <?php if($location['afterhours_return_allowed'] == 1): ?>
                            <li>
                                <i class="fa fa-money"></i>
                                <span class="highlight"><?php print($objLang->getText('NRS_RETURN_TEXT')); ?>:</span>
                                <?php print($location['unit_long_without_fraction_print']['afterhours_return_fee_dynamic']); ?>
                            </li>
                        <?php endif; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <div class="location-more">
                <div class="car-rental-section-title"><?php print($objLang->getText('NRS_LOCATIONS_BUSINESS_HOURS_TEXT')); ?></div><hr />
                <ul class="car-rental-business-hours-list">
                    <li>
                        <i class="fa fa-clock-o"></i>
                        <span class="highlight"><?php print($objLang->getText('NRS_MONDAYS_TEXT')); ?>:</span>
                        <?php print($location['business_hours']['mon']); ?>
                    </li>
                    <li>
                        <i class="fa fa-clock-o"></i>
                        <span class="highlight"><?php print($objLang->getText('NRS_TUESDAYS_TEXT')); ?>:</span>
                        <?php print($location['business_hours']['mon']); ?>
                    </li>
                    <li>
                        <i class="fa fa-clock-o"></i>
                        <span class="highlight"><?php print($objLang->getText('NRS_WEDNESDAYS_TEXT')); ?>:</span>
                        <?php print($location['business_hours']['mon']); ?>
                    </li>
                    <li>
                        <i class="fa fa-clock-o"></i>
                        <span class="highlight"><?php print($objLang->getText('NRS_THURSDAYS_TEXT')); ?>:</span>
                        <?php print($location['business_hours']['thu']); ?>
                    </li>
                    <li>
                        <i class="fa fa-clock-o"></i>
                        <span class="highlight"><?php print($objLang->getText('NRS_FRIDAYS_TEXT')); ?>:</span>
                        <?php print($location['business_hours']['fri']); ?>
                    </li>
                    <li>
                        <i class="fa fa-clock-o"></i>
                        <span class="highlight"><?php print($objLang->getText('NRS_SATURDAYS_TEXT')); ?>:</span>
                        <?php print($location['business_hours']['sat']); ?>
                    </li>
                    <li>
                        <i class="fa fa-clock-o"></i>
                        <span class="highlight"><?php print($objLang->getText('NRS_SUNDAYS_TEXT')); ?>:</span>
                        <?php print($location['business_hours']['sun']); ?>
                    </li>
                </ul>

                <?php if($location['print_lunch_hours']): ?>
                    <div class="car-rental-section-title top-padded"><?php print($objLang->getText('NRS_LUNCH_TIME_TEXT')); ?></div><hr />
                    <ul class="car-rental-lunch-hours-list">
                        <li>
                            <i class="fa fa-clock-o"></i>
                            <span class="highlight"><?php print($objLang->getText('NRS_MON_TEXT').'-'.$objLang->getText('NRS_SUN_TEXT')); ?>:</span>
                            <?php print($location['print_lunch_hours']); ?>
                        </li>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
        <div class="clear">&nbsp;</div>
    </div>
</div>
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('.fancybox').fancybox();
    var mainImageHref = jQuery(".location-main-image");
    var mainImageFile = jQuery(".location-main-image img");
    var image1Href = jQuery(".location-image-1");
    var image1File = jQuery(".location-image-1 img");
    var image2Href = jQuery(".location-image-2");
    var image2File = jQuery(".location-image-2 img");
    var image3Href = jQuery(".location-image-3");
    var image3File = jQuery(".location-image-3 img");
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