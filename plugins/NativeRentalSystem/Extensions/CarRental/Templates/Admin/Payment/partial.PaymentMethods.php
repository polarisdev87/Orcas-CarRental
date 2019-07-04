<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<h1>
	<span>Payment Methods</span>&nbsp;&nbsp;
    <input class="add-new" type="button" value="Add New Payment Method" onClick="window.location.href='<?php print($addNewPaymentMethodURL); ?>'" />
</h1>
<table class="display payment-method-datatable" border="0" style="width:100%">
	<thead>
	<tr>
		<th style="white-space: nowrap">ID / Code</th>
		<th>Name</th>
		<th>E-mail / Keys</th>
        <th title="Work in sandbox mode">Sandbox</th>
        <th title="Check certificate">Cert.</th>
        <th title="Work in https:// only">SSL</th>
        <th title="Is it an online payment">Online</th>
		<th>Status</th>
        <th style="text-align: center">Order</th>
		<th>Actions</th>
	</tr>
	</thead>
	<tbody><?php print($adminPaymentMethodsList); ?></tbody>
</table>
<p>Please keep in mind that:</p>
<ol>
    <li>
        If you set a payment method as online-payment, then if the reservation will be edited and saved, the old reservation will not (!) going to be updated.
        Instead of it will be cancelled and the new reservation will be saved.
        This is made because you need to be able easily track the payment difference which you will need to return.
    </li>
</ol>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('.payment-method-datatable').dataTable( {
		"responsive": true,
		"bJQueryUI": true,
		"iDisplayLength": 25,
		"bSortClasses": false,
		"aaSorting": [[8,'asc'],[1,'asc']],
        "aoColumns": [
            { "width": "7%" },
            { "width": "25%" },
            { "width": "40%" },
            { "width": "4%" },
            { "width": "4%" },
            { "width": "4%" },
            { "width": "4%" },
            { "width": "4%" },
            { "width": "4%" },
            { "width": "4%" }
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