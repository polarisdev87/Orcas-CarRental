<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<h1>
    <span>Extra Discount Plans for Reservation in Advance</span>&nbsp;&nbsp;
    <input class="add-new" type="button" value="Add New Discount Plan" onClick="window.location.href='<?php print($addNewAdvanceDiscountURL); ?>'" />
</h1>
<table class="display advance-datatable" border="0" style="width:100%">
    <thead>
    <tr>
        <th>#</th>
        <th>Reservation Before - Until/ Extra Name</th>
        <th>Discount (%) Of Total Extra Price</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody><?php print($adminBookingInAdvanceDiscountsGroups); ?></tbody>
</table>
<p>
    Total extra discount that will be applied in reservation process is a sum of two extra discounts - reservation duration discount and reservation in advance discounts.<br />
    If both - reservation duration and reservation in advance discounts are used, the in price table you will see price details
    for specific reservation duration period as a range from-to, example:
</p>
<ul style="margin-top: 0; padding-top: 0;">
    <li><strong>Price text:</strong> 20-25 <?php print($objSettings->getSetting('conf_currency_code').' / '.$objSettings->getPeriodWord('LONG')); ?></li>
    <li><strong>Hover text:</strong> <?php print($objSettings->getPeriodWord('LONG')); ?> price from with 50-60% discount applied</li>
</ul>
<p style="margin-top: 5px;padding-top: 0;">
    Price &quot;from&quot; word is used there because in price table system takes the lowest <?php print($objSettings->getPeriodWord('LONG')); ?> price used for that extra in week.
</p>
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('.advance-datatable').dataTable( {
        "responsive": true,
        "bJQueryUI": true,
        "iDisplayLength": 100,
        "bSortClasses": false,
        "aaSorting": [[0,'asc']],
        "aoColumns": [
            { "width": "10%" },
            { "width": "50%" },
            { "width": "25%" },
            { "width": "15%" }
        ],
        "bAutoWidth": false,
        "bInfo": true,
        "sScrollY": "100%",
        "sScrollX": "100%",
        "bScrollCollapse": true,
        "sPaginationType": "full_numbers",
        "bRetrieve": true,
        "oLanguage": {
            "sSearch": "Search:",
            "sInfo": "Showing _START_ to _END_ of _TOTAL_ entries",
            "sInfoEmpty": "Showing 0 to 0 of 0 entries",
            "sZeroRecords": "No matching records found",
            "sInfoFiltered": "(filtered from _MAX_ total entries)",
            "sEmptyTable": "No data available in table",
            "sLengthMenu": "Show _MENU_ entries",
            "oPaginate": {
                "sFirst":    "First",
                "sPrevious": "Previous",
                "sNext":     "Next",
                "sLast":     "Last"
            }
        }
    } );
} );
</script>