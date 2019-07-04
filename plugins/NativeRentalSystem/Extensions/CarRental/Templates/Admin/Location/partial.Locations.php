<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<h1>
	<span>Location List</span>&nbsp;&nbsp;
	<input class="add-new" type="button" value="Add New Location" onClick="window.location.href='<?php print($addNewLocationURL); ?>'" />
</h1>
<table class="display location-datatable" border="0" style="width:100%">
	<thead>
	<tr>
		<th style="white-space: nowrap; width: 5%">Id / Code</th>
		<th>Location / Contacts</th>
		<th style="width:110px;">Regular Fees</th>
		<th>Business Hours</th>
		<th>After Hours Locations</th>
		<th style="width:110px;">After Hours Fees</th>
		<th style="width: 4%; text-align: center">Order</th>
		<th>Actions</th>
	</tr>
	</thead>
	<tbody>
	<?php print($adminLocationsList); ?>
	</tbody>
</table>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('.location-datatable').dataTable( {
		"responsive": true,
		"bJQueryUI": true,
        "iDisplayLength": 25,
		"bSortClasses": false,
		"aaSorting": [[6,'asc'],[1,'asc']],
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
	});
});
</script>