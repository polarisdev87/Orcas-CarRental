<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<div class="clear">
	<table class="display customer-datatable" border="0" style="width:100%">
		<thead>
			<tr>
				<th width="2%" style="white-space: nowrap">ID</th>
				<th width="13%" style="white-space: nowrap">Customer Name</th>
				<th width="8%" style="white-space: nowrap">Date of Birth</th>
				<th width="20%" style="white-space: nowrap">Street Address</th>
				<th width="10%" style="white-space: nowrap">Phone Number</th>
				<th width="16%" style="white-space: nowrap">E-mail</th>
				<th width="8%" style="white-space: nowrap">Registered</th>
				<th width="8%" style="white-space: nowrap">Visited</th>
				<th width="15%" style="white-space: nowrap">Actions</th>
			</tr>
		</thead>
		<tbody class="customer-list">
			<?php print($customersHTML); ?>
		</tbody>
	</table>
</div>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('.customer-datatable').dataTable( {
		"responsive": true,
		"bJQueryUI": true,
		"iDisplayLength": 25,
		"bSortClasses": false,
		"aaSorting": [[1,'asc']],
		"bAutoWidth": true,
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
