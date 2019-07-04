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
   <span style="font-size:16px; font-weight:bold">Add/Edit Car Manufacturer</span>
   <input type="button" value="Back To Manufacturer List" onClick="window.location.href='<?php print($backToListURL); ?>'" style="background: #EFEFEF; float:right; cursor:pointer;"/>
   <hr style="margin-top:10px;"/>
   <form action="<?php print($formAction); ?>" method="post" id="form1" enctype="multipart/form-data">
        <table cellpadding="5" cellspacing="2" border="0">
            <input type="hidden" name="manufacturer_id" value="<?php print($manufacturerId); ?>"/>
            <tr>
              <td><strong>Manufacturer Title:</strong></td>
              <td><input type="text" name="manufacturer_title" value="<?php print($manufacturerTitle); ?>" id="manufacturer_title" class="required" style="width:200px;" /></td>
            </tr>
            <tr>
                <td><strong>Manufacturer Logo:</strong></td>
                <td><input type="file" name="manufacturer_logo" id="manufacturer_logo"/>
                    <?php
                    if($manufacturerLogo != "")
                    {
                        if($demoManufacturerLogo)
                        {
                            print('<span>&nbsp;&nbsp;&nbsp;&nbsp;<a rel="collection" href="'.$objConf->getExtensionDemoGalleryURL($manufacturerLogo).'" target="_blank"><strong>View Demo Image</strong></a>');
                            print('&nbsp;&nbsp;&nbsp;&nbsp;<strong><span style="color: navy;">Delete Image</span></strong>');
                            print('<input type="checkbox" name="delete_manufacturer_logo"/></span>');
                        } else
                        {
                            print('<span>&nbsp;&nbsp;&nbsp;&nbsp;<a rel="collection" href="'.$objConf->getGalleryURL().$manufacturerLogo.'" target="_blank"><strong>View Image</strong></a>');
                            print('&nbsp;&nbsp;&nbsp;&nbsp;<strong><span style="color: navy;">Delete Image</span></strong>');
                            print('<input type="checkbox" name="delete_manufacturer_logo"/></span>');
                        }
                    } else
                    {
                        print('&nbsp;&nbsp;&nbsp;&nbsp; <b>No Image</b>');
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" value="Save manufacturer" name="save_manufacturer" style="cursor:pointer;"/></td>
            </tr>
        </table>
    </form>
</div>
<script type="text/javascript">
jQuery().ready(function() {
    jQuery("#form1").validate();
 });
</script>

