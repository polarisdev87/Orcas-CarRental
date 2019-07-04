<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
// Styles
wp_enqueue_style('car-rental-frontend');
?>
<div class="car-rental-wrapper extra-rental-price-table">
    <table class="price-table">
    <thead>
        <tr class="extra-table-labels">
            <th class="extra-label">
                <?php print($objLang->getText('NRS_RENTAL_OPTION_TEXT').' / '. $priceTable['print_dynamic_period_label']); ?>
            </th>
            <?php
            foreach($priceTable['print_periods'] AS $period):
                print('<th class="extra-price-on-duration">');
                print('<span title="'.$objLang->getText('NRS_PERIOD_TEXT').'">'.$period['print_dynamic_period_label'].'</span>');
                print('</th>');
            endforeach;
            if($depositsEnabled):
                print('<th class="extra-deposit">'.$objLang->getText('NRS_DEPOSIT_TEXT').'</th>');
            endif;
            ?>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach($priceTable['extras'] AS $extra):
        ?>
            <tr class="car-rental-price-table-extra">
                <td class="extra-description">
                    <span class="extra-name"><?php print($extra['print_translated_extra_name_with_dependant_item']); ?></span>
                    <?php if($extra['partner_profile_url']): ?>
                        <br /><?php print($objLang->getText('NRS_PARTNER_TEXT').': '.$extra['print_partner_link']); ?>
                    <?php endif; ?>
                </td>
                <?php
                foreach($extra['period_list'] AS $period):
                    print('<td class="extra-price-on-duration">');
                        print('<span title="'.$period['print_price_description'].'">'.$period['print_price'].'</span>');
                    print('</td>');
                endforeach;
                ?>
                <?php if($depositsEnabled): ?>
                    <td class="extra-deposit">
                        <strong><?php print($extra['unit_long_without_fraction_print']['fixed_deposit_amount']); ?></strong>
                    </td>
                <?php endif; ?>
            </tr>
        <?php
        endforeach;
        if($priceTable['got_search_result'] === FALSE):
            $colspan = $depositsEnabled ? $priceTable['total_periods']+2 : $priceTable['total_periods']+1;
            print('<tr class="car-rental-price-table-extra">');
            print('<td class="no-extras-available" colspan="'.$colspan.'">'.$objLang->getText('NRS_NO_EXTRAS_AVAILABLE_TEXT').'</td>');
            print('</tr>');
        endif;
        ?>
    </tbody>
    </table>
</div>