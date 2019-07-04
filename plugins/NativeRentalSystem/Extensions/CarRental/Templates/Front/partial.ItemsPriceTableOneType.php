<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );

print('<tr class="car-rental-price-table-item">');
    print('<td class="item-image">');
        if($item['mini_thumb_url'] != ""):
            print('<a class="fancybox" href="'.$item['image_url'].'" title="'.$item['print_translated_manufacturer_title'].' '.$item['print_translated_model_name'].' '.$item['print_via_partner'].'">');
                print('<img src="'.$item['mini_thumb_url'].'" alt="'.$item['print_translated_manufacturer_title'].' '.$item['print_translated_model_name'].' '.$item['print_via_partner'].'" />');
            print('</a>');
        else:
            print('&nbsp;');
        endif;
    print('</td>');
    print('<td class="item-description">');
        if($item['item_page_url']):
            print('<a href="'.$item['item_page_url'].'" title="'.$objLang->getText('NRS_SHOW_ITEM_PAGE_TEXT').'">');
            print('<span class="item-name">'.$item['print_translated_manufacturer_title'].' '.$item['print_translated_model_name'].'</span>');
            print('</a>');
        else:
            print('<span class="item-name">'.$item['print_translated_manufacturer_title'].' '.$item['print_translated_model_name'].'</span>');
        endif;
        print('<br /><hr />');
        if($item['show_transmission_type']):
            print($objLang->getText('NRS_TRANSMISSION_TYPE_TEXT').': '.$item['print_translated_transmission_type_title']);
        endif;
        if($item['partner_profile_url']):
            print('<br />'.$objLang->getText('NRS_PARTNER_TEXT').': '.$item['print_partner_link']);
        endif;
    print('</td>');
    foreach($item['period_list'] as $period)
    {
        print('<td class="item-price-on-duration">');
        print('<span title="'.$period['print_price_description'].'">'.$period['print_price'].'</span>');
        print('</td>');
    }
    if($depositsEnabled):
        print('<td class="item-deposit">');
        print('<strong>'.$item['unit_long_without_fraction_print']['fixed_deposit_amount'].'</strong>');
        print('</td>');
    endif;
print('</tr>');