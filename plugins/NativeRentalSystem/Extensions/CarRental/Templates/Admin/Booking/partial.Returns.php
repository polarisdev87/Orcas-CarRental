<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<div class="clear">
	<table class="display <?php print('returns-datatable'); ?>" border="0" style="width:100%">
		<thead>
		<tr>
			<th width="1%">#</th>
			<th width="15%" style="white-space: nowrap">Reservation Code, Name &amp; Cars</th>
			<th style="white-space: nowrap">Pick-Up Date, Time &amp; Location</th>
			<th style="white-space: nowrap">Return Date, Time &amp; Location</th>
			<th width="18%" style="white-space: nowrap">Reservation Date &amp; Status</th>
			<th width="17%" style="white-space: nowrap">Amount</th>
			<th width="14%">Actions</th>
		</tr>
		</thead>
		<tbody>
		 <?php print($returnsHTML); ?>
		</tbody>
	</table>
</div>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('.<?php print('returns-datatable'); ?>').dataTable( {
		"responsive": true,
		"bJQueryUI": true,
		"bSortClasses": false,
		"iDisplayLength": 25,
		"aaSorting": [[0,'asc']],
		"bAutoWidth": true,
		"aoColumns": [
			{ "sWidth": "1%" },
			{ "sWidth": "15%" },
			{ "sWidth": "18%" },
			{ "sWidth": "18%" },
			{ "sWidth": "17%" },
			{ "sWidth": "17%" },
			{ "sWidth": "14%" }
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