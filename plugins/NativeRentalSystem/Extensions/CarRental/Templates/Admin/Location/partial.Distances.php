<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<h1>
	<span>Distance List</span>&nbsp;&nbsp;
	<input class="add-new" type="button" value="Add New Distance" onClick="window.location.href='<?php print($addNewDistanceURL); ?>'" />
</h1>
<table class="display distance-datatable" border="0" style="width:100%">
	<thead>
	<tr>
		<th style="width:1%">#</th>
		<th>Pick-up Location</th>
		<th>Return Location</th>
        <th>Distance</th>
		<th>Fee (<?php print($objLang->getText('NRS_WITHOUT_TAX_TEXT')); ?>)</th>
        <th>Fee (<?php print($objLang->getText('NRS_WITH_TAX_TEXT')); ?>)</th>
		<th>Actions</th>
	</tr>
	</thead>
	<tbody>
	<?php print($adminDistancesList); ?>
	</tbody>
</table>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('.distance-datatable').dataTable( {
		"responsive": true,
		"bJQueryUI": true,
        "iDisplayLength": 25,
		"bSortClasses": false,
		"aaSorting": [[0,'asc']],
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