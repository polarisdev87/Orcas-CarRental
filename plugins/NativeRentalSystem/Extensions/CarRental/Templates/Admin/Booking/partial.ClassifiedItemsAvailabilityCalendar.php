<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<div class="car-rental-calendar">
    <div class="calendar-label">
        <?php
        if($itemsCalendar['30_days']):
            print($objLang->getText('NRS_ITEMS_AVAILABILITY_IN_NEXT_30_DAYS_TEXT'));
        else:
            print($objLang->getText('NRS_ITEMS_AVAILABILITY_FOR_TEXT').' '.$itemsCalendar['print_month_name'].', '.$itemsCalendar['print_year']);
        endif;
        ?>
    </div>
    <table class="availability-table" cellpadding="0" cellspacing="0">
        <thead>
        <tr class="classified-item-table-labels">
            <th class="classified-month-label">
                <?php
                if($itemsCalendar['2_months']):
                    print($objLang->getText('NRS_ITEM_TEXT').' / '.$itemsCalendar['print_month_names'].' '.$objLang->getText('NRS_MONTH_DAYS_TEXT').':');
                else:
                    print($objLang->getText('NRS_ITEM_TEXT').' / '.$itemsCalendar['print_month_name'].' '.$objLang->getText('NRS_MONTH_DAY_TEXT').':');
                endif;
                ?>
            </th>
            <?php
            foreach($itemsCalendar['print_days'] AS $dayName):
                print('<th class="one-day">'.$dayName.'</th>');
            endforeach;
            ?>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach($itemsCalendar['body_types'] AS $itemType):
            if($itemType['got_search_result']):
                print('<tr class="item-type-label">');
                print('<td class="item-type-name" colspan="'.($itemsCalendar['total_days']+1).'">'.$itemType['body_type_title'].'</td>');
                print('</tr>');
                foreach($itemType['items'] AS $item):
                    include('partial.ItemsAvailabilityCalendarOneType.php');
                endforeach;
            endif;
        endforeach;

        if($itemsCalendar['got_search_result'] === FALSE):
            print('<tr class="item-type-label">');
            print('<td class="item-type-name" colspan="'.($itemsCalendar['total_days']+1).'">'.$objLang->getText('NRS_UNCLASSIFIED_ITEM_TYPE_TEXT').'</td>');
            print('</tr>');
            print('<tr class="car-rental-price-table-item">');
            print('<td class="no-items-in-category" colspan="'.($itemsCalendar['total_days']+1).'">'.$objLang->getText('NRS_NO_ITEMS_AVAILABLE_TEXT').'</td>');
            print('</tr>');
        endif;
        ?>
        </tbody>
    </table>
</div>