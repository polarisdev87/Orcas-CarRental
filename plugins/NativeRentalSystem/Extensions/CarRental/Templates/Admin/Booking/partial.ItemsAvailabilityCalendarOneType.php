<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<tr class="car-rental-calendar-item">
    <td class="item-description">
        <span class="item-name">
            <?php print($item['print_translated_manufacturer_title'].' '.$item['print_translated_model_name']); ?>
        </span>
        <br /><hr />
        <?php print($objLang->getText('NRS_ITEM_ID_TEXT').' '.$item['item_id']); ?>, <?php print($objLang->getText('NRS_TRANSMISSION_TYPE_TEXT')); ?>: <?php print($item['print_translated_transmission_type_title']); ?>
        <?php if($item['partner_profile_url']): ?>
            <br /><?php print($objLang->getText('NRS_PARTNER_TEXT').': '.$item['print_partner_link']); ?>
        <?php endif; ?>
    </td>
    <?php
    foreach($item['day_list'] as $day)
    {
        print('<td class="quantity-left-in-day '.$day['print_quantity_class'].'">');
        print('<div class="quantity-hover"
            title="'.$objLang->getText('NRS_ITEMS_AVAILABILITY_FOR_TEXT').' '.$objLang->getText('NRS_ALL_DAY_TEXT').'
            '.$objLang->getText('NRS_ON_TEXT').' '.$day['print_month_name'].' '.$day['print_day'].',
            '.$objLang->getText('NRS_TOTAL_ITEMS_TEXT').' '.$day['units_in_stock'].'">'.$day['print_units_available'].'</div>');
        print('<div class="partial-quantity-hover"
            title="'.$objLang->getText('NRS_ITEMS_PARTIAL_AVAILABILITY_FOR_TEXT').'
            '.sprintf($objLang->getText('NRS_PARTIAL_DAY_TEXT'), $noonTime).'">'.$day['print_partial_units_available'].'</div>');
        print('</td>');
    }
    ?>
</tr>