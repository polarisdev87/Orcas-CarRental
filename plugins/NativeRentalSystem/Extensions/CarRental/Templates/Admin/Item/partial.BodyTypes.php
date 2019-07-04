<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<h1>
	<span>Body Type List</span>&nbsp;&nbsp;
	<input class="add-new" type="button" value="Add New Body Type" onClick="window.location.href='<?php print($addNewBodyTypeURL); ?>'" />
</h1>
<table class="display body-types-datatable" border="0" style="width:100%">
	<thead>
	<tr>
		<th style="width: 1%">ID</th>
		<th>Body Type</th>
        <th style="text-align: center; width: 4%">Order</th>
		<th style="width: 15%">Actions</th>
	</tr>
	</thead>
	<tbody>
	<?php print($adminBodyTypesList); ?>
	</tbody>
</table>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('.body-types-datatable').dataTable( {
		"responsive": true,
		"bJQueryUI": true,
		"iDisplayLength": 25,
		"bSortClasses": false,
		"aaSorting": [[2,'asc'],[1,'asc']],
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