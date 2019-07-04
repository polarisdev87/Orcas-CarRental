<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
// Scripts
wp_enqueue_script('jquery');
wp_enqueue_script('jquery-validate');
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
if($objSettings->getSetting('conf_universal_analytics_enhanced_ecommerce') == 1):
    include('partial.Step2.EnhancedEcommerce.php');
endif;
?>
<div class="car-rental-wrapper car-rental-search-result">
    <?php
    if($complexPickup || $complexReturn)
    {
        include('partial.LocationsSummaryComplex.php');
    } else
    {
        include('partial.LocationsSummarySimple.php');
    }
    ?>
   <div id="search-results-title"><h2><?php print($objLang->getText('NRS_SEARCH_RESULTS_TEXT')); ?></h2></div>
    <div class="content list-headers">
        <div class="col1 item-data">
            <?php print($objLang->getText('NRS_BODY_TYPE_TEXT')); ?>
        </div>
        <div class="col3 item-price">
            <?php print($objLang->getText('NRS_TOTAL_TEXT')); ?>
        </div>
        <?php if($depositsEnabled): ?>
            <div class="col4 item-deposit">
                <?php print($objLang->getText('NRS_DEPOSIT_TEXT')); ?>
            </div>
        <?php endif; ?>
        <div class="col5 item-select">
            &nbsp;
        </div>
    </div>

    <?php if($multiMode): ?>
        <form action="" name="form1" id="form1" method="post">
    <?php endif; ?>
        <?php
        foreach($itemTypesWithItems AS $typeWithItems)
        {
            print('<div class="item-type-label">'.$typeWithItems['body_type_title'].'</div>');
            foreach($typeWithItems['items'] as $item)
            {
                include('partial.Step2.SingleItem.php');
            }
        }
        ?>
        <?php if($multiMode): ?>
            <div class="buttons car-rental-search-result-button">
                <label class="error" generated="true" for="item_ids[]" style="display:none;"><?php print($objLang->getText('NRS_ERROR_PLEASE_SELECT_AT_LEAST_ONE_ITEM_TEXT')); ?>.</label><br />
                <input type="hidden" name="car_rental_came_from_step2" value="yes" />
                <?php if($newBooking == FALSE): ?>
                    <input type="submit" name="car_rental_cancel_booking" value="<?php print($objLang->getText('NRS_CANCEL_BOOKING_TEXT')); ?>" />
                    <input type="submit" name="car_rental_do_search0" value="<?php print($objLang->getText('NRS_CHANGE_BOOKING_DATE_TIME_AND_LOCATION_TEXT')); ?>" />
                    <button id="car_rental_do_search" name="car_rental_do_search2" type="submit"><?php print($objLang->getText('NRS_CONTINUE_TEXT')); ?></button>
                <?php else: ?>
                    <?php
                    if($objSettings->getSetting('conf_universal_analytics_events_tracking') == 1):
                        // Note: Do not translate events to track well inter-language events
                        $onClick = "ga('send', 'event', 'Car Rental', 'Click', '2. Continue to extras');";
                        print('<button id="car_rental_do_search" name="car_rental_do_search2" type="submit" onClick="'.esc_js($onClick).'">'.$objLang->getText('NRS_CONTINUE_TEXT').'</button>');
                    else:
                        print('<button id="car_rental_do_search" name="car_rental_do_search2" type="submit">'.$objLang->getText('NRS_CONTINUE_TEXT').'</button>');
                    endif;
                    ?>
                <?php endif; ?>
            </div>
        <?php elseif($newBooking == FALSE): ?>
            <form action="" name="form1" id="form1" method="post">
                <div class="buttons car-rental-search-result-button">
                    <input type="submit" name="car_rental_cancel_booking" value="<?php print($objLang->getText('NRS_CANCEL_BOOKING_TEXT')); ?>" />
                    <input type="submit" name="car_rental_do_search0" value="<?php print($objLang->getText('NRS_CHANGE_BOOKING_DATE_TIME_AND_LOCATION_TEXT')); ?>" />
                </div>
            </form>
        <?php endif; ?>

    <?php if($multiMode): ?>
        </form>
    <?php endif; ?>
    <div class="clear">&nbsp;</div>
</div>
<?php if($multiMode): ?>
    <script type="text/javascript">
        jQuery().ready(function() {
            jQuery('#form1').validate();
            jQuery('.fancybox').fancybox();
        });
    </script>
<?php else: ?>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery('.fancybox').fancybox();
        });
    </script>
<?php endif; ?>