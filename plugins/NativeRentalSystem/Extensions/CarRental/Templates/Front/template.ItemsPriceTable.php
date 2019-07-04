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
<div class="car-rental-wrapper car-rental-price-table">
    <table class="price-table">
    <thead>
        <tr class="item-table-labels">
            <th colspan="2" class="item-label">
                <?php print($objLang->getText('NRS_ITEM_TEXT').' / '. $priceTable['print_dynamic_period_label']); ?>
            </th>
            <?php
            foreach($priceTable['print_periods'] AS $period):
                print('<th class="item-price-on-duration">');
                print('<span title="'.$objLang->getText('NRS_PERIOD_TEXT').'">'.$period['print_dynamic_period_label'].'</span>');
                print('</th>');
            endforeach;
            if($depositsEnabled):
                print('<th class="item-deposit">'.$objLang->getText('NRS_DEPOSIT_TEXT').'</th>');
            endif;
            ?>
        </tr>
    </thead>
    <tbody>
        <?php
        if($priceTable['body_types'][0]['got_search_result']):
            foreach($priceTable['body_types'][0]['items'] AS $item):
                include('partial.ItemsPriceTableOneType.php');
            endforeach;
        else:
            $colspan = $depositsEnabled ? $priceTable['total_periods']+3 : $priceTable['total_periods']+2;
            print('<tr class="car-rental-price-table-item">');
            print('<td class="no-items-in-category" colspan="'.$colspan.'">'.$objLang->getText('NRS_NO_ITEMS_AVAILABLE_TEXT').'</td>');
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