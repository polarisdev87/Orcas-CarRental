<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<h1>
	<span>Taxes</span>&nbsp;&nbsp;
	<input class="add-new" type="button" value="Add New Tax" onClick="window.location.href='<?php print($addNewTaxURL); ?>'"/>
</h1>
<table class="display taxes-datatable" border="0" style="width:100%">
	<thead>
	<tr>
        <th>Tax Name</th>
		<th>Location Name</th>
		<th>Location Type</th>
    	<th>Tax (%)</th>
		<th>Actions</th>
	</tr>
	</thead>
	<tbody><?php print($adminTaxesList); ?></tbody>
</table>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('.taxes-datatable').dataTable( {
		"responsive": true,
		"bJQueryUI": true,
		"iDisplayLength": 25,
		"bSortClasses": false,
		"aaSorting": [[0,'asc']],
		"aoColumns": [
			{ "width": "30%" },
			{ "width": "30%" },
			{ "width": "20%" },
			{ "width": "10%" },
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