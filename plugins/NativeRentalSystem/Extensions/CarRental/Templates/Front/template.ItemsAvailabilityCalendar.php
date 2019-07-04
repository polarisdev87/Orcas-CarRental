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
wp_enqueue_style('car-rental-frontend');
?>
<div class="car-rental-wrapper car-rental-calendar">
    <span style="font-size:16px; font-weight:bold">
        <?php print($objLang->getText('NRS_ITEMS_AVAILABILITY_IN_NEXT_30_DAYS_TEXT')); ?>
    </span>
    <hr style="margin-top:14px;"/>
    <table class="availability-table">
        <thead>
        <tr class="item-table-labels">
            <th colspan="2" class="month-label">
                <?php
                if($itemsCalendar['2_months']):
                    print($objLang->getText('NRS_ITEM_TEXT').' / '.$itemsCalendar['print_month_names'].' '.$objLang->getText('NRS_MONTH_DAYS_TEXT'));
                else:
                    print($objLang->getText('NRS_ITEM_TEXT').' / '.$itemsCalendar['print_month_name'].' '.$objLang->getText('NRS_MONTH_DAY_TEXT'));
                endif;
                ?>
            </th>
            <?php
            foreach($itemsCalendar['print_days'] AS $oneDay):
                print('<th class="one-day">'.$oneDay.'</th>');
            endforeach;
            ?>
        </tr>
        </thead>
        <tbody>
        <?php
        if($itemsCalendar['body_types'][0]['got_search_result']):
            foreach($itemsCalendar['body_types'][0]['items'] AS $item):
                include('partial.ItemsAvailabilityCalendarOneType.php');
            endforeach;
        else:
            print('<tr class="car-rental-price-table-item">');
            print('<td class="no-items-in-category" colspan="'.($itemsCalendar['total_days']+2).'">'.$objLang->getText('NRS_NO_ITEMS_AVAILABLE_TEXT').'</td>');
            print('</tr>');
        endif;
        ?>
        </tbody>
    </table>
</div>
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('.fancybox').fancybox();
});
</script>