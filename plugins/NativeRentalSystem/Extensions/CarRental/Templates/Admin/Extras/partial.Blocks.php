<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<h1>
	<span>Blocked Extras List</span>&nbsp;&nbsp;
	<input class="add-new" type="button" value="Add New Block" onClick="window.location.href='<?php print($addNewBlockURL); ?>'" />
</h1>
<table class="display blocks-datatable" border="0" style="width:100%">
	<thead>
		<tr>
			<th>Label</th>
			<th>Extra ID / SKU</th>
			<th>Blocked Extra</th>
			<th>Units</th>
			<th>Date Range</th>
			<th>Actions</th>
		</tr>
	</thead>
	<?php print($adminBlockedList); ?>
</table>
<script>
jQuery(document).ready(function() {
	jQuery('.blocks-datatable').dataTable( {
		"responsive": true,
		"bJQueryUI": true,
		"iDisplayLength": 25,
		"bSortClasses": false,
		"aaSorting": [[0,'asc']],
		"aoColumns": [
			{ "width": "15%" },
			{ "width": "10%" },
			{ "width": "25%" },
			{ "width": "5%" },
			{ "width": "35%" },
			{ "width": "10%" }
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