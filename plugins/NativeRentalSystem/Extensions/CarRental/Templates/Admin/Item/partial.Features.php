<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<h1>
	<span>Feature List</span>&nbsp;&nbsp;
	<input class="add-new" type="button" value="Add New Feature" onClick="window.location.href='<?php print($addNewFeatureURL); ?>'" />
</h1>
<table class="display features-datatable" border="0" style="width:100%">
	<thead>
	  	<tr>
			<th>Feature</th>
		  	<th>In Car List</th>
			<th>Actions</th>
	  	</tr>
	</thead>
	<tbody>
	 <?php print($adminFeaturesList); ?>
	</tbody>
</table>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('.features-datatable').dataTable( {
		"responsive": true,
		"bJQueryUI": true,
		"iDisplayLength": 25,
		"bSortClasses": false,
		"aaSorting": [[0,'asc']],
		"bAutoWidth": true,
		"aoColumns": [
			null,
			{ "sWidth": "15%" },
			{ "sWidth": "15%" }
		],
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