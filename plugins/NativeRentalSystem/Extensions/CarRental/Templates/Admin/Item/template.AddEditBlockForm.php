<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
// Scripts
wp_enqueue_script('jquery');
wp_enqueue_script('jquery-ui-core');
wp_enqueue_script('jquery-ui-tabs');
wp_enqueue_script('jquery-ui-datepicker', array('jquery','jquery-ui-core'));
wp_enqueue_script('jquery-validate');
wp_enqueue_script('car-rental-admin');

// Styles
wp_enqueue_style('datepicker');
wp_enqueue_style('jquery-validate');
wp_enqueue_style('car-rental-admin');
?>
<p>&nbsp;</p>
<div id="container-inside" style="width:1000px;">
    <span style="font-size:16px; font-weight:bold">Search for a Car Item To Block</span>
    <input type="button" value="Back To Car Block List" onClick="window.location.href='<?php print($backToListURL); ?>'" style="background: #EFEFEF; float:right; cursor:pointer;"/>
    <hr style="margin-top:14px;"/>
    <table cellpadding="4" width="100%">
        <tr>
            <td valign="top" style="width: 47%">
                <?php
                // Include admin car search form template
                include('partial.BlockForm.php');
                ?>
            </td>
            <td valign="top">
                <?php
                if(isset($_POST['search_for_block']))
                {
                    // Include admin car search results template
                    include('partial.BlockResults.php');
                }
                ?>
            </td>
        </tr>
    </table>
</div>