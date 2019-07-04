<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<h1>
    <span>Extras List</span>&nbsp;&nbsp;
    <input class="add-new" type="button" value="Add New Extra" onClick="window.location.href='<?php print($addNewExtraURL); ?>'" />
</h1>
<table class="display extras-datatable" border="0" style="width:100%">
    <thead>
    <tr>
        <th>ID</th>
        <th>SKU</th>
        <th>Extra Name</th>
        <th>Quantity in Stock</th>
        <th>Unit Price (<?php print($objLang->getText('NRS_WITHOUT_TAX_TEXT')); ?>)</th>
        <?php if($depositsEnabled): ?>
            <th>Fixed Deposit</th>
        <?php endif; ?>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php print($adminExtrasList); ?>
    </tbody>
</table>
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('.extras-datatable').dataTable( {
        "responsive": true,
        "bJQueryUI": true,
        "iDisplayLength": 25,
        "bSortClasses": false,
        "aaSorting": [[2,'asc']],
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