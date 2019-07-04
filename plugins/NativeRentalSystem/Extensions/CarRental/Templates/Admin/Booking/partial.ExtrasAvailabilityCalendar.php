<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<div class="car-rental-calendar">
    <div class="calendar-label">
        <?php
        if($extrasCalendar['30_days']):
            print($objLang->getText('NRS_EXTRAS_AVAILABILITY_IN_NEXT_30_DAYS_TEXT'));
        else:
            print($objLang->getText('NRS_EXTRAS_AVAILABILITY_FOR_TEXT').' '.$extrasCalendar['print_month_name'].', '.$extrasCalendar['print_year']);
        endif;
        ?>
    </div>
    <hr style="margin-top:14px;"/>
    <table class="availability-table" cellspacing="0" cellpadding="0">
        <thead>
        <tr class="item-table-labels">
            <th class="month-label">
                <?php
                if($extrasCalendar['2_months']):
                    print($objLang->getText('NRS_EXTRAS_TEXT').' / '.$extrasCalendar['print_month_names'].' '.$objLang->getText('NRS_MONTH_DAYS_TEXT'));
                else:
                    print($objLang->getText('NRS_EXTRAS_TEXT').' / '.$extrasCalendar['print_month_name'].' '.$objLang->getText('NRS_MONTH_DAY_TEXT'));
                endif;
                ?>
            </th>
            <?php
            foreach($extrasCalendar['print_days'] AS $oneDay):
                print('<th class="one-day">'.$oneDay.'</th>');
            endforeach;
            ?>
        </tr>
        </thead>
        <tbody>
        <?php foreach($extrasCalendar['extras'] AS $extra): ?>
            <tr class="car-rental-calendar-item">
                <td class="item-description">
                    <span class="item-name"><?php print($extra['extra_name_with_dependant_item']); ?></span>
                    <br /><hr />
                    <?php print($objLang->getText('NRS_EXTRA_ID_TEXT').': '.$extra['extra_id']); ?>
                    <?php if($extra['partner_profile_url']): ?>
                        <br /><?php print($objLang->getText('NRS_PARTNER_TEXT').': '.$extra['print_partner_link']); ?>
                    <?php endif; ?>
                </td>
                <?php
                foreach($extra['day_list'] AS $day):
                    print('<td class="quantity-left-in-day '.$day['print_quantity_class'].'">');
                    print('<div class="quantity-hover"
                        title="'.$objLang->getText('NRS_EXTRAS_AVAILABILITY_FOR_TEXT').' '.$objLang->getText('NRS_ALL_DAY_TEXT').'
                        '.$objLang->getText('NRS_ON_TEXT').' '.$day['print_month_name'].' '.$day['print_day'].',
                        '.$objLang->getText('NRS_TOTAL_EXTRAS_TEXT').' '.$day['units_in_stock'].'">'.$day['print_units_available'].'</div>');
                    print('<div class="partial-quantity-hover"
                        title="'.$objLang->getText('NRS_EXTRAS_PARTIAL_AVAILABILITY_FOR_TEXT').'
                        '.sprintf($objLang->getText('NRS_PARTIAL_DAY_TEXT'), $noonTime).'">'.$day['print_partial_units_available'].'</div>');
                    print('</td>');
                endforeach;
                ?>
            </tr>
        <?php endforeach; ?>
        <?php if($extrasCalendar['got_search_result'] === FALSE): ?>
            <tr class="car-rental-price-table-item">
                <td class="no-items-in-category" colspan="<?php print($extrasCalendar['total_days']+1); ?>"><?php print($objLang->getText('NRS_NO_EXTRAS_AVAILABLE_TEXT')); ?></td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>