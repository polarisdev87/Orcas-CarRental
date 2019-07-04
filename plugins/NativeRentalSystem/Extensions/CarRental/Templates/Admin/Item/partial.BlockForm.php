<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<script type="text/javascript">
jQuery(document).ready(function(){
    jQuery('.start-date').datepicker({
        minDate: 0,
        maxDate: "+365D",
        numberOfMonths: 2,
        dateFormat: '<?php print($objSettings->getSetting('conf_datepicker_date_format')); ?>',
        firstDay: <?php print(get_option('start_of_week')); ?>,
        onSelect: function(selected) {
    	var date = jQuery(this).datepicker('getDate');
            if(date)
            {
                date.setDate(date.getDate());
            }
            jQuery('.end-date').datepicker("option","minDate", date);
        }
    });
 
    jQuery('.end-date').datepicker({
        minDate: 0,
        maxDate:"+365D",
        numberOfMonths: 2,
        dateFormat: '<?php print($objSettings->getSetting('conf_datepicker_date_format')); ?>',
        firstDay: <?php print(get_option('start_of_week')); ?>,
        onSelect: function(selected) {
           jQuery('.start-date').datepicker("option","maxDate", selected)
        }
    });  
 jQuery('.start-date-datepicker').click(function() {
    jQuery('.start-date').datepicker('show');
  });
 jQuery('.end-date-datepicker').click(function() {
   jQuery(".end-date").datepicker('show');
  });
});
</script>
<form action="<?php print($blockFormAction); ?>" method="post" class="block-form">
    <table cellpadding="0" cellspacing="7" border="0"  align="left" style="width: 100%;text-align:left;">
        <tr>
            <td>
                <strong>Location:<span class="item-required">*</span></strong>
            </td>
            <td>
                <select name="location_id" class="location" style="width: 350px;">
                    <?php print($locationsDropDownOptions); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td><strong>Start Date:<span class="item-required">*</span></strong></td>
            <td>
                <input type="text" name="start_date" style="width:140px" value="<?php print($startDate); ?>" class="start-date" readonly="readonly" />
                <img src="<?php print($objConf->getExtensionAdminImagesURL('Month.png')); ?>" class="start-date-datepicker" style="width: 16px; height: 16px; cursor:pointer; margin-bottom:-4px; border: 0" />
                <select name="start_time" class="start-time">
                    <?php print($startTimeDropDownOptions); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td>
                <strong>End Date:<span class="item-required">*</span></strong>
            </td>
            <td>
                <input type="text" name="end_date" value="<?php print($endDate); ?>" class="end-date" style="width:140px" readonly="readonly"/>
                <img src="<?php print($objConf->getExtensionAdminImagesURL('Month.png')); ?>" class="end-date-datepicker" style="width: 16px; height: 16px; cursor:pointer; margin-bottom:-4px; border: 0" />
                <select name="end_time" class="end-time">
                    <?php print($endTimeDropDownOptions); ?>
                </select>
            </td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" name="search_for_block" value="Search for cars to block" style="cursor:pointer; min-width: 259px;"/></td>
        </tr>
    </table>
</form>
<script type="text/javascript">
jQuery().ready(function() {
    jQuery('.block-form').validate();
});
</script> 