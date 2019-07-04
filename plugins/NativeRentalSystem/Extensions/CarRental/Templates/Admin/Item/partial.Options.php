<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<h1>
    <span>Car Options List</span>&nbsp;&nbsp;
    <input class="add-new" type="button" value="Add New Option" onClick="window.location.href='<?php print($addNewOptionURL); ?>'" />
</h1>
<table class="display options-datatable" border="0" style="width:100%">
    <thead>
    <tr>
        <th>#</th>
        <th>Car/Option Name</th>
        <th>Measurement Unit</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php print($adminOptionsList); ?>
    </tbody>
</table>
<script>
jQuery(document).ready(function() {
    jQuery('.options-datatable').dataTable( {
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
    } );
} );
</script>