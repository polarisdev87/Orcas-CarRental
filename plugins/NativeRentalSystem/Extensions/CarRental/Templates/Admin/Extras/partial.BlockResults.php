<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<form name="admin-block-results" action="<?php print($blockResultsFormAction); ?>" method="post" id="admin-block-results">
    <table cellpadding="4" cellspacing="2" border="0" style="width: 100%; font-family:Arial, Helvetica, sans-serif; font-size:12px; border:#999 solid 1px;">
        <input type="hidden" name="start_date" value="<?php print($startDate); ?>" />
        <input type="hidden" name="start_time" value="<?php print($startTime); ?>" />
        <input type="hidden" name="end_date" value="<?php print($endDate); ?>" />
        <input type="hidden" name="end_time" value="<?php print($endTime); ?>" />
        <tr>
            <td align="left" colspan="2" style="font-weight: bold">
                <strong><?php print($objLang->getText('NRS_PERIOD_TEXT')); ?></strong><br />
                <?php print($startDateTimeLabel.' - '.$endDateTimeLabel); ?>
            </td>
        </tr>
        <tr>
            <td align="left" colspan="2"><strong>Name/Description</strong><br />
                <input type="text" name="block_name" id="block_name"  style="width:100%"/>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <hr />
            </td>
        </tr>
        <tr>
            <th align="left">Extra Name</th>
            <th align="left">Units</th>
        </tr>
        <tr>
             <td colspan="2">
                 <hr />
             </td>
        </tr>
        <?php foreach($extras as $extra): ?>
            <tr>
            <td><?php print($extra['print_translated_extra_name_with_dependant_item'].' '.$extra['print_via_partner']); ?></td>
            <td>
                <input type="hidden" name="extra_ids[]" value="<?php print($extra['extra_id']); ?>" />
                <select name="extra_units[<?php print($extra['extra_id']); ?>]" id="extra_units_<?php print($extra['extra_id']); ?>" class="required">
                    <?php print($extra['quantity_dropdown_options']); ?>
                </select>
            </td>
            </tr>
        <?php endforeach; ?>
        <?php if($gotBlockResults): ?>
            <tr>
                <td colspan="2" style="text-align: center;"><input type="submit" value="Block selected extras" name="block" style="cursor:pointer;"/></td>
            </tr>
        <?php else: ?>
            <tr>
                <td colspan="2" style="text-align: center; color:red;"><strong>No extras found.</strong></td>
            </tr>
        <?php endif; ?>
   </table>
</form>