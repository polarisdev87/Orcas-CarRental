<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<h1>
    <span>Car Discount Plans for Reservation Duration</span>&nbsp;&nbsp;
    <input class="add-new" type="button" value="Add New Discount Plan" onClick="window.location.href='<?php print($addNewDurationDiscountURL); ?>'" />
</h1>
<table class="display duration-datatable" border="0" style="width:100%">
    <thead>
    <tr>
        <th>#</th>
        <th>Reservation From - To / Price Group &amp; Plan</th>
        <th>Discount (%) Of Total Car Price</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody><?php print($adminDurationDiscountGroups); ?></tbody>
</table>
<p>
    Total car discount that will be applied in reservation process is a sum of two car discounts - reservation duration discount and reservation in advance discounts.<br />
    If both - reservation duration and reservation in advance discounts are used, the in price table you will see price details
    for specific reservation duration period as a range from-to, example:
</p>
<ul style="margin-top: 0; padding-top: 0;">
    <li><strong>Price text:</strong> 20-25 <?php print($objSettings->getSetting('conf_currency_code').' / '.$objSettings->getPeriodWord('LONG')); ?></li>
    <li><strong>Hover text:</strong> <?php print($objSettings->getPeriodWord('LONG')); ?> price from with 50-60% discount applied</li>
</ul>
<p style="margin-top: 5px;padding-top: 0;">
    Price &quot;from&quot; word is used there because in price table system takes the lowest <?php print($objSettings->getPeriodWord('LONG')); ?> price used for that car in week.
</p>
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('.duration-datatable').dataTable( {
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