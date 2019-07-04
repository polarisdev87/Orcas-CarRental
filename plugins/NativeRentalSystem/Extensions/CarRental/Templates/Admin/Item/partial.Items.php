<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<h1>
	<span>Car List</span>&nbsp;&nbsp;
	<input class="add-new" type="button" value="Add New Car" onClick="window.location.href='<?php print($addNewItemURL); ?>'" />
</h1>
<table class="display datatable" border="0" style="width:100%">
	<thead>
	<tr>
		<th>ID</th>
		<th>SKU</th>
		<th>Type</th>
		<th>Transmission</th>
		<th>Car</th>
		<th>Qty.</th>
		<th>Fuel</th>
		<th>Price Group</th>
        <?php if($depositsEnabled): ?>
            <th>Deposit</th>
        <?php endif; ?>
		<th title="Minimum age">Age</th>
		<th>Status</th>
		<th>In Slider</th>
		<th>Actions</th>
	</tr>
	</thead>
	<tbody>
	<?php print($adminItemList); ?>
	</tbody>
</table>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('.datatable').dataTable( {
		"responsive": true,
		"bJQueryUI": true,
		"iDisplayLength": 25,
		"bSortClasses": false,
		"aaSorting": [[9,'asc'],[4,'asc'],[2,'asc']],
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