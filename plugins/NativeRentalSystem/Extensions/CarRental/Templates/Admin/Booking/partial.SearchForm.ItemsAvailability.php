<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<h1 class="search-title">
	30 Days / Search
</h1>
<div class="calendar-search-form">
	<form action="<?php print(admin_url('admin.php')); ?>" method="GET" class="form_item_calendar">
        <input type="hidden" name="page" value="<?php print($objConf->getURLPrefix()); ?>item-calendar-search-results" />
		<div class="col-date-field">
			<?php print($objLang->getText('NRS_FROM_TEXT')); ?> &nbsp;
			<input type="text" name="from_date" class="from_item_calendar required" />
			<img src="<?php print($objConf->getExtensionAdminImagesURL('Month.png')); ?>" alt="Date Selector" class="item_calendar_datepicker_from_image date-selector-image" />
			 &nbsp;&nbsp;&nbsp;&nbsp;
			<strong><?php print($objLang->getText('NRS_TO_TEXT')); ?></strong> &nbsp;
			<input type="text" name="to_date" class="to_item_calendar required"  />
			<img src="<?php print($objConf->getExtensionAdminImagesURL('Month.png')); ?>" alt="Date Selector" class="item_calendar_datepicker_to_image date-selector-image" />
		</div>
		<div class="col-search">
			<input type="submit" value="Show Calendar" name="search_item_calendar" />
		</div>
	</form>
</div>
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery.extend(jQuery.validator.messages, {
        required: "<?php print($objLang->getText('NRS_REQUIRED_TEXT')); ?>"
    });
	jQuery(".form_item_calendar").validate();

	jQuery(".from_item_calendar").datepicker({
		maxDate: "+365D",
		numberOfMonths: 2,
        dateFormat: '<?php print($objSettings->getSetting('conf_datepicker_date_format')); ?>',
        firstDay: <?php print(get_option('start_of_week')); ?>
	});
	jQuery(".to_item_calendar").datepicker({
		maxDate: "+365D",
		numberOfMonths: 2,
        dateFormat: '<?php print($objSettings->getSetting('conf_datepicker_date_format')); ?>',
        firstDay: <?php print(get_option('start_of_week')); ?>
	});
	jQuery(".item_calendar_datepicker_from_image").click(function() {
		jQuery(".from_item_calendar").datepicker("show");
	});
	jQuery(".item_calendar_datepicker_to_image").click(function() {
		jQuery(".to_item_calendar").datepicker("show");
	});
});
</script>