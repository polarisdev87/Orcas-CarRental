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
        <tr class="classified-item-table-labels">
            <th colspan="2" class="classified-item-label">
                <?php print($objLang->getText('NRS_ITEM_TEXT').' / '. $priceTable['print_dynamic_period_label']); ?>:
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
        foreach($priceTable['body_types'] AS $itemType):
            if($itemType['got_search_result']):
                $colspan = $depositsEnabled ? $priceTable['total_periods']+3 : $priceTable['total_periods']+2;
                print('<tr class="item-type-label">');
                print('<td class="item-type-name" colspan="'.$colspan.'">'.$itemType['body_type_title'].'</td>');
                print('</tr>');
                foreach($itemType['items'] AS $item):
                    include('partial.ItemsPriceTableOneType.php');
                endforeach;
            endif;
        endforeach;
        if($priceTable['got_search_result'] === FALSE):
            $colspan = $depositsEnabled ? $priceTable['total_periods']+3 : $priceTable['total_periods']+2;
            print('<tr class="item-type-label">');
            print('<td class="item-type-name" colspan="'.$colspan.'">'.$objLang->getText('NRS_UNCLASSIFIED_ITEM_TYPE_TEXT').'</td>');
            print('</tr>');
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