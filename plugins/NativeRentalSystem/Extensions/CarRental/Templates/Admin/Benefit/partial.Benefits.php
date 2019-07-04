<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<h1>
	<span>Benefits List</span>&nbsp;&nbsp;
	<input class="add-new" type="button" value="Add New Benefit" onClick="window.location.href='<?php print($addNewBenefitURL); ?>'" />
</h1>
<table class="display benefits-datatable" border="0" style="width:100%">
	<thead>
	  <tr>
        <th>ID</th>
        <th>Benefit</th>
        <th style="text-align: center">Order</th>
        <th>Actions</th>
	  </tr>
	</thead>
	<tbody>
	 <?php print($adminBenefitsList); ?>
	</tbody>
</table>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('.benefits-datatable').dataTable( {
		"responsive": true,
		"bJQueryUI": true,
		"iDisplayLength": 25,
		"bSortClasses": false,
		"aaSorting": [[2,'asc'],[1,'asc']],
        "aoColumns": [
            { "width": "5%" },
            { "width": "76%" },
            { "width": "4%" },
            { "width": "15%" }
        ],
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