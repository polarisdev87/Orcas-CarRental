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
    <span style="font-size:16px; font-weight:bold">Search for Extra Item to Block</span>
    <input type="button" value="Back To Extras Block List" onClick="window.location.href='<?php print($backToListURL); ?>'" style="background: #EFEFEF; float:right; cursor:pointer;"/>
    <hr style="margin-top:14px;"/>
    <table cellpadding="4" width="100%">
        <tr>
            <td valign="top" style="width: 50%">
                <?php
                // Load extra block search form admin template
                include('partial.BlockForm.php');
                ?>
            </td>
            <td valign="top">
                <?php
                if(isset($_POST['search_to_block']))
                {
                    // Include admin extra search results template
                    include('partial.BlockResults.php');
                }
                ?>
            </td>
        </tr>
    </table>
</div>