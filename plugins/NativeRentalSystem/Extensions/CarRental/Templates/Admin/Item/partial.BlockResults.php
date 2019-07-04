<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<form name="admin-block-results" action="<?php print($blockResultsFormAction); ?>" method="post" id="admin-block-results">
    <table cellpadding="4" cellspacing="2" border="0" style="width: 100%; font-family:Arial, Helvetica, sans-serif; font-size:12px; border:#999 solid 1px;">
    <input type="hidden" name="location_id" value="<?php print($locationId); ?>" />
    <input type="hidden" name="start_date" value="<?php print($startDate); ?>" />
    <input type="hidden" name="start_time" value="<?php print($startTime); ?>" />
    <input type="hidden" name="end_date" value="<?php print($endDate); ?>" />
    <input type="hidden" name="end_time" value="<?php print($endTime); ?>" />
    <tr>
        <td align="left" colspan="6">
            <strong><?php print($objLang->getText('NRS_PERIOD_TEXT')); ?></strong><br />
            <?php print($startDateTimeLabel.' - '.$endDateTimeLabel); ?>
        </td>
    </tr>
    <tr>
        <td align="left" colspan="6"><strong>Name/Description</strong><br />
          <input type="text" name="block_name" id="block_name" style="width:100%"/>
        </td>
    </tr>
    <tr>
        <td colspan="6">
            <hr />
        </td>
    </tr>
    <tr>
        <th align="left" style="width: 200px">Car Model</th>
        <th align="left">Class</th>
        <th align="left">Transmission</th>
        <th align="left">Fuel</th>
        <th align="left">Units</th>
    </tr>
    <tr>
         <td colspan="5">
             <hr />
         </td>
    </tr>
    <?php foreach($items as $item): ?>
        <tr>
            <td><?php print($item['manufacturer_title'].' '.$item['model_name'].' '.$item['print_via_partner']); ?></td>
            <td><?php print($item['body_type_title']); ?></td>
            <td><?php print($item['transmission_type_title']); ?></td>
            <td><?php print($item['fuel_type_title']); ?></td>
            <td align="right">
                <input type="hidden" name="item_ids[]" value="<?php print($item['item_id']); ?>" />
                <select name="item_units[<?php print($item['item_id']); ?>]" id="item_units_<?php print($item['item_id']); ?>" class="required">
                    <?php print($item['quantity_dropdown_options']); ?>
                </select>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php if($gotBlockResults): ?>
        <tr>
            <td>&nbsp;</td>
            <td colspan="5"><input type="submit" value="Block selected cars" name="block" style="cursor:pointer;"/></td>
        </tr>
    <?php else: ?>
        <tr>
            <td colspan="5" align="center" style="color:red;"><strong>No cars found.</strong></td>
        </tr>
    <?php endif; ?>
    </table>
</form>