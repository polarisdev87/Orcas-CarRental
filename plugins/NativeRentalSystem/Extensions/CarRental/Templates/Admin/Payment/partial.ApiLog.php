<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<h1>
	<span>Payment callback log in last 30 days</span>
</h1>
<table class="display log-datatable" border="0" style="width:100%">
	<thead>
		<tr>
			<th width="15%" nowrap="nowrap">Date</th>
			<th width="15%" nowrap="nowrap">Email</th>
			<th width="10%" nowrap="nowrap">IP</th>
			<th width="15%" nowrap="nowrap">Browser</th>
			<th width="15%" nowrap="nowrap">OS</th>
			<th width="8%" nowrap="nowrap">Robot</th>
			<th width="22%" nowrap="nowrap">Details</th>
		</tr>
	</thead>
	<tbody class="payment-callback-list">
		<?php print($logList); ?>
	</tbody>
</table>
<script type="text/javascript">

jQuery(document).ready(function() {
	jQuery('.log-datatable').dataTable( {
		"responsive": true,
		"bJQueryUI": true,
		"iDisplayLength": 25,
		"bSortClasses": false,
		"aaSorting": [[0,'desc']],
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
