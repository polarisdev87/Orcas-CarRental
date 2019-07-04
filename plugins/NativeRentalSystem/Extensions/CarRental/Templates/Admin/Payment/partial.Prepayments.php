<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<h1>
	<span>Prepayment Plans</span>&nbsp;&nbsp;
	<input class="add-new" type="button" value="Add New Prepayment Plan" onClick="window.location.href='<?php print($addNewPrepaymentURL); ?>'" />
</h1>
<table class="display prepayment-datatable" border="0" style="width:100%">
	<thead>
	<tr>
		<th>Includes</th>
		<th>Reservation From</th>
		<th>Reservation To</th>
    	<th>Prepayment (%)</th>
		<th>Actions</th>
	</tr>
	</thead>
	<tbody><?php print($adminPrepaymentsList); ?></tbody>
</table>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('.prepayment-datatable').dataTable( {
		"responsive": true,
		"bJQueryUI": true,
		"iDisplayLength": 25,
		"bSortClasses": false,
		"aaSorting": [[0,'asc']],
		"aoColumns": [
			{ "width": "15%" },
			{ "width": "25%" },
			{ "width": "25%" },
			{ "width": "20%" },
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