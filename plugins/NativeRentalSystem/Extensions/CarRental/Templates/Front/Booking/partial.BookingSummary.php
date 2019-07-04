<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<?php if(($pickupLocationVisible || $returnLocationVisible) && ($objSearch->getPickupLocationId() > 0 || $objSearch->getReturnLocationId() > 0)): ?>
    <?php if($pickupLocationVisible && $returnLocationVisible && $objSearch->getPickupLocationId() > 0 && $objSearch->getReturnLocationId() > 0): ?>
        <tr style="background-color:#343434; color: white;" class="location-headers">
            <td align="left" class="col1" style="padding-left:5px;"><strong><?php print($objLang->getText('NRS_PICKUP_LOCATION_TEXT')); ?></strong></td>
            <td align="left" class="col2" style="padding-left:5px;" colspan="2"><strong><?php print($objLang->getText('NRS_RETURN_LOCATION_TEXT')); ?></strong></td>
        </tr>
    <?php elseif($pickupLocationVisible && $objSearch->getPickupLocationId() > 0): ?>
        <tr style="background-color:#343434; color: white;" class="location-headers">
            <td align="left" class="col1" style="padding-left:5px;" colspan="3"><strong><?php print($objLang->getText('NRS_PICKUP_LOCATION_TEXT')); ?></strong></td>
        </tr>
    <?php elseif($returnLocationVisible && $objSearch->getReturnLocationId() > 0): ?>
        <tr style="background-color:#343434; color: white;" class="location-headers">
            <td align="left" class="col1" style="padding-left:5px;" colspan="3"><strong><?php print($objLang->getText('NRS_RETURN_LOCATION_TEXT')); ?></strong></td>
        </tr>
    <?php endif; ?>

    <tr style="background-color:#FFFFFF" class="location-details">
        <?php if($pickupLocationVisible): ?>
            <td align="left" class="col1" style="padding-left:5px;" colspan="<?php print($pickupMainColspan); ?>">
                <?php print($pickupLocations); ?>
            </td>
        <?php endif; ?>
        <?php if($returnLocationVisible): ?>
            <td align="left" class="col2" style="padding-left:5px;" colspan="<?php print($returnMainColspan); ?>">
                <?php print($returnLocations); ?>
            </td>
        <?php endif; ?>
    </tr>
<?php endif; ?>


<?php if($pickupDateVisible || $returnDateVisible): ?>
    <tr style="background-color:#343434; color: white" class="duration-headers">
        <?php if($pickupDateVisible): ?>
            <td align="left" class="col1" style="padding-left:5px;" colspan="<?php print($pickupColspan); ?>"><strong><?php print($objLang->getText('NRS_PICKUP_DATE_AND_TIME_TEXT')); ?></strong></td>
        <?php endif; ?>
        <?php if($returnDateVisible): ?>
            <td align="left" class="col2" style="padding-left:5px;" colspan="<?php print($returnColspan); ?>"><strong><?php print($objLang->getText('NRS_RETURN_DATE_AND_TIME_TEXT')); ?></strong></td>
            <td align="right" class="col3" style="padding-right:5px;"><strong><?php print($objLang->getText('NRS_PERIOD_TEXT')); ?></strong></td>
        <?php endif; ?>
    </tr>

    <tr style="background-color:#FFFFFF" class="duration-details">
        <?php if($pickupDateVisible): ?>
            <td align="left" class="col1" style="padding-left:5px;" colspan="<?php print($pickupColspan); ?>">
                <?php print($objSearch->getPrintPickupDate().' &nbsp;&nbsp; '.$objSearch->getPrintPickupTime()); ?>
            </td>
        <?php endif; ?>
        <?php if($returnDateVisible): ?>
            <td align="left" class="col2" style="padding-left:5px;" colspan="<?php print($returnColspan); ?>">
                <?php print($objSearch->getPrintReturnDate().' &nbsp;&nbsp; '.$objSearch->getPrintReturnTime()); ?>
            </td>
            <td align="right" class="col3" style="padding-right:5px;">
                <?php print($objSearch->getPrintBookingDuration()); ?>
            </td>
        <?php endif; ?>
    </tr>
<?php endif; ?>

<!-- ITEMS -->
<?php if(sizeof($priceSummary['items']) > 0): ?>
    <tr class="item-headers" style="background-color:#343434; color: white;">
        <td align="left" class="col1" style="padding-left:5px;"><strong><?php print($objLang->getText('NRS_SELECTED_ITEMS_TEXT')); ?></strong></td>
        <td align="left" class="col2" style="padding-left:5px;"><strong><?php print($objLang->getText('NRS_PRICE_TEXT')); ?></strong></td>
        <td align="right" class="col3" style="padding-right:5px;"><strong><?php print($objLang->getText('NRS_TOTAL_TEXT')); ?></strong></td>
    </tr>
<?php endif; ?>
<?php foreach ($priceSummary['items'] AS $item): ?>
    <tr style="background-color:#FFFFFF" class="items">
        <td align="left" class="col1" style="padding-left:5px;">
            <?php print($item['print_translated_item_with_option']); ?>
        </td>
        <td align="left" class="col2" style="padding-left:5px;">
            <span title="<?php
            if($item['tax_percentage'] > 0):
                print($item['unit_print']['discounted_total'].' '.$objLang->getText('NRS_WITHOUT_TAX_TEXT').' x ');
                print($item['multiplier'].' '.$objLang->getText('NRS_ITEM_QUANTITY_SUFFIX_TEXT').' + ');
                print($item['unit_print']['discounted_tax_amount'].' '.$objLang->getText('NRS_TAX_TEXT').' = ');
            endif;
            print($item['unit_print']['discounted_total_with_tax'].' x '.$item['multiplier']);
            ?>" style="cursor:pointer">
                <?php print($item['unit_print']['discounted_total']); ?>
                <?php print($item['multiplier'] > 1 ? ' x '.$item['multiplier'] : ''); ?>
            </span>
        </td>
        <td align="right" class="col3" style="padding-right:5px;">
            <span title="<?php
            if($item['tax_percentage'] > 0):
                print($item['multiplied_print']['discounted_total'].' '.$objLang->getText('NRS_WITHOUT_TAX_TEXT').' + ');
                print($item['multiplied_print']['discounted_tax_amount'].' '.$objLang->getText('NRS_TAX_TEXT').' = ');
                print($item['multiplied_print']['discounted_total_with_tax']);
            endif;
            ?>" style="cursor:pointer">
                <?php print($item['multiplied_print']['discounted_total']); ?>
            </span>
        </td>
    </tr>
<?php endforeach; ?>

<!-- PICKUP FEES -->
<?php if($showLocationFees && ($pickupLocationVisible || $returnLocationVisible) && ($objSearch->getPickupLocationId() > 0 || $objSearch->getReturnLocationId() > 0)): ?>
    <tr style="background-color:#343434; color: white" class="office-fee-headers">
        <td align="left" class="col1" style="padding-left:5px;" colspan="3"><strong><?php print($objLang->getText('NRS_LOCATION_FEES_TEXT')); ?></strong></td>
    </tr>
<?php endif; ?>
<?php if($showLocationFees && $pickupLocationVisible && $objSearch->getPickupLocationId() > 0): ?>
    <tr style="background-color:#FFFFFF" class="office-fees">
        <td align="left" class="col1" style="padding-left:5px;"><?php print($objLang->getText('NRS_PICKUP_FEE_TEXT')); ?>
            <?php if($priceSummary['pickup_in_afterhours']) { print(" ".$objLang->getText('NRS_NIGHTLY_PICKUP_RATE_APPLIED_TEXT')); } ?>
        </td>
        <td align="left" class="col2" style="padding-left:5px;">
            <span title="<?php print($priceSummary['pickup']['print_current_pickup_fee_details']); ?>" style="cursor:pointer">
                <?php print($priceSummary['pickup']['unit_print']['current_pickup_fee']); ?>
                <?php print($priceSummary['pickup']['multiplier'] > 1 ? ' x '.$priceSummary['pickup']['multiplier'] : ''); ?>
            </span>
        </td>
        <td align="right" class="col3" style="padding-right:5px;">
            <span title="<?php print($priceSummary['pickup']['print_multiplied_current_pickup_fee_details']); ?>" style="cursor:pointer">
                <?php print($priceSummary['pickup']['multiplied_print']['current_pickup_fee']); ?>
            </span>
        </td>
    </tr>
<?php endif; ?>



<!-- RETURN FEES -->
<?php if($showLocationFees && $returnLocationVisible && $objSearch->getReturnLocationId() > 0): ?>
    <tr style="background-color:#FFFFFF" class="office-fees">
        <td align="left" class="col1" style="padding-left:5px;"><?php print($objLang->getText('NRS_RETURN_FEE_TEXT')); ?>
          <?php if($priceSummary['return_in_afterhours']) { print(" ".$objLang->getText('NRS_NIGHTLY_RETURN_RATE_APPLIED_TEXT')); } ?>
        </td>
        <td align="left" class="col2" style="padding-left:5px;">
            <span title="<?php print($priceSummary['return']['print_current_return_with_distance_fee_details']); ?>" style="cursor:pointer">
                <?php print($priceSummary['return']['unit_print']['current_return_with_distance_fee']); ?>
                <?php print($priceSummary['return']['multiplier'] > 1 ? ' x '.$priceSummary['return']['multiplier'] : ''); ?>
            </span>
        </td>
        <td align="right" class="col3" style="padding-right:5px;">
            <span title="<?php print($priceSummary['return']['print_multiplied_current_return_with_distance_fee_details']); ?>" style="cursor:pointer">
                <?php print($priceSummary['return']['multiplied_print']['current_return_with_distance_fee']); ?>
            </span>
        </td>
    </tr>
<?php endif; ?>

<!-- EXTRAS -->
<?php if(sizeof($priceSummary['extras']) > 0): ?>
    <tr class="extra-headers" style="background-color:#343434; color: white;">
        <td align="left" class="col1" colspan="3"><strong><?php print($objLang->getText('NRS_RENTAL_OPTIONS_TEXT')); ?></strong></td>
    </tr>
<?php endif; ?>
<?php foreach($priceSummary['extras'] AS $extra): ?>
    <tr style="background-color:#FFFFFF" class="extras">
        <td align="left" class="col1" style="padding-left:5px;"><?php print($extra['print_translated_extra_with_option']); ?></td>
        <td align="left" class="col2" style="padding-left:5px;">
            <span title="<?php
            if($extra['tax_percentage'] > 0):
                print($extra['unit_print']['discounted_total'].' '.$objLang->getText('NRS_WITHOUT_TAX_TEXT').' x ');
                print($extra['multiplier'].' '.$objLang->getText('NRS_EXTRA_QUANTITY_SUFFIX_TEXT').' + ');
                print($extra['unit_print']['discounted_tax_amount'].' '.$objLang->getText('NRS_TAX_TEXT').' = ');
            endif;
            print($extra['unit_print']['discounted_total_with_tax'].' x '.$extra['multiplier']);
            ?>" style="cursor:pointer">
                <?php print($extra['unit_print']['discounted_total']); ?>
                <?php print($extra['multiplier'] > 1 ? ' x '.$extra['multiplier'] : ''); ?>
            </span>
        </td>
        <td align="right" class="col3" style="padding-right:5px;">
            <span title="<?php
            if($extra['tax_percentage'] > 0):
                print($extra['multiplied_print']['discounted_total'].' '.$objLang->getText('NRS_WITHOUT_TAX_TEXT').' + ');
                print($extra['multiplied_print']['discounted_tax_amount'].' '.$objLang->getText('NRS_TAX_TEXT').' = ');
                print($extra['multiplied_print']['discounted_total_with_tax']);
            endif;
            ?>" style="cursor:pointer">
                <?php print($extra['multiplied_print']['discounted_total']); ?>
            </span>
        </td>
    </tr>
<?php endforeach; ?>

<!-- TOTAL -->
<?php $counter = 0; ?>
<tr style="background-color:#343434; color: white;" class="total-headers">
    <td align="left" class="col1" colspan="3" style="padding-left:5px;"><strong><?php print($objLang->getText('NRS_TOTAL_TEXT')); ?></strong></td>
</tr>
<?php if($priceSummary['overall']['gross_total'] < $priceSummary['overall']['grand_total']): ?>
    <?php $counter++; ?>
    <tr style="<?php print($counter % 2 == 0 ? 'background-color:#f2f2f2' : 'background-color:#FFFFFF'); ?>">
        <td align="right" class="col1" style="padding-right:5px;" colspan="2">
            <?php print($objLang->getText('NRS_GROSS_TOTAL_TEXT')); ?>:
        </td>
        <td align="right" class="col3" style="padding-right:5px;">
            <?php print($priceSummary['overall_print']['gross_total']); ?>
        </td>
    </tr>
<?php endif; ?>
<?php $counter++; ?>
<?php foreach($priceSummary['taxes'] AS $tax): if($tax['tax_amount'] > 0.00): ?>
    <tr style="<?php print($counter % 2 == 0 ? 'background-color:#f2f2f2' : 'background-color:#FFFFFF'); ?>">
        <td align="right" class="col1" style="padding-right:5px;" colspan="2">
            <?php print($tax['print_translated_tax_name'].' ('.$tax['print_tax_percentage'].')'); ?>:
        </td>
        <td align="right" class="col3" style="padding-right:5px;">
            <?php print($tax['print_tax_amount']); ?>
        </td>
    </tr>
<?php endif; endforeach; ?>
<?php $counter++; ?>
<tr style="<?php print($counter % 2 == 0 ? 'background-color:#f2f2f2' : 'background-color:#FFFFFF'); ?>">
    <td align="right" class="col1" style="padding-right:5px;" colspan="2">
        <strong><?php print($objLang->getText('NRS_GRAND_TOTAL_TEXT')); ?>:</strong>
    </td>
    <td align="right" class="col3" style="padding-right:5px;">
        <strong><?php print($priceSummary['overall_print']['grand_total']); ?></strong>
    </td>
</tr>
<?php if($depositsEnabled && $priceSummary['overall']['fixed_deposit_amount'] > 0): ?>
    <?php $counter++; ?>
    <tr style="<?php print($counter % 2 == 0 ? 'background-color:#f2f2f2' : 'background-color:#FFFFFF'); ?>">
        <td align="right" class="col1" style="padding-right:5px;" colspan="2">
            <?php print($objLang->getText('NRS_DEPOSIT_TEXT')); ?>:
        </td>
        <td align="right" class="col3" style="padding-right:5px;">
            <span title="<?php
                print($objLang->getText('NRS_ITEMS_TEXT').' '.$objLang->getText('NRS_DEPOSIT_TEXT').' ');
                print('('.$priceSummary['overall_print']['fixed_item_deposit_amount'].') + ');
                print($objLang->getText('NRS_EXTRAS_TEXT').' '.$objLang->getText('NRS_DEPOSIT_TEXT').' ');
                print('('.$priceSummary['overall_print']['fixed_extra_deposit_amount'].') = ');
                print($priceSummary['overall_print']['fixed_deposit_amount']);
            ?>" style="cursor:pointer">
                <?php print($priceSummary['overall_print']['fixed_deposit_amount']); ?>
            </span>
        </td>
    </tr>
<?php endif;

 ?>
<?php if($prepaymentsEnabled && ($priceSummary['overall']['grand_total'] != $priceSummary['overall']['total_pay_now'])): ?>
    <?php $counter++; ?>
    <tr style="<?php print($counter % 2 == 0 ? 'background-color:#f2f2f2' : 'background-color:#FFFFFF'); ?>">
        <td align="right" class="col1" style="padding-right:5px;" colspan="2">
            <strong><?php print($payNowText); ?>:</strong>
        </td>
        <td align="right" class="col3" style="padding-right:5px;">
            <span title="<?php print($priceSummary['overall_print']['total_pay_now']); ?>" style="cursor:pointer">
                <strong><?php print($priceSummary['overall_print']['total_pay_now']); ?></strong>
            </span>
        </td>
    </tr>
    <?php $counter++; ?>
    <tr style="<?php print($counter % 2 == 0 ? 'background-color:#f2f2f2' : 'background-color:#FFFFFF'); ?>">
        <td align="right" class="col1" style="padding-right:5px;" colspan="2">
            <?php print($objLang->getText('NRS_PAY_LATER_OR_ON_RETURN_TEXT')); ?>:
        </td>
        <td align="right" class="col3" style="padding-right:5px;">
            <span title="<?php
                print($objLang->getText('NRS_GRAND_TOTAL_TEXT').' ('.$priceSummary['overall_print']['grand_total'].') - ');
                print($objLang->getText('NRS_PAY_NOW_TEXT').' ('.$priceSummary['overall_print']['total_pay_now'].') = ');
                print($priceSummary['overall_print']['total_pay_later']);
            ?>" style="cursor:pointer">
                <?php print($priceSummary['overall_print']['total_pay_later']); ?>
            </span>
        </td>
    </tr>
<?php endif; ?>