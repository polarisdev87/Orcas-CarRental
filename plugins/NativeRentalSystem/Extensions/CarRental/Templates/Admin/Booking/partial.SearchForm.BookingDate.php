<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<h1 class="search-title">
    Today &amp; Later / Search
</h1>
<div class="booking-search-form">
	<form action="<?php print(admin_url('admin.php')); ?>" method="GET" class="form_booking_date">
        <input type="hidden" name="page" value="<?php print($objConf->getURLPrefix()); ?>booking-search-results" />
        <input type="hidden" name="backto_tab" value="bookings" />
		<div class="col-date-field">
			<?php print($objLang->getText('NRS_FROM_TEXT')); ?> &nbsp;
			<input type="text" name="from_date" class="from_booking_date" />
			<img src="<?php print($objConf->getExtensionAdminImagesURL('Month.png')); ?>" alt="Date Selector" class="booking_date_datepicker_from_image date-selector-image" />
			 &nbsp;&nbsp;&nbsp;&nbsp;
			<strong><?php print($objLang->getText('NRS_TO_TEXT')); ?></strong> &nbsp;
			<input type="text" name="to_date" class="to_booking_date" />
			<img src="<?php print($objConf->getExtensionAdminImagesURL('Month.png')); ?>" alt="Date Selector" class="booking_date_datepicker_to_image date-selector-image" />
		</div>
		<div class="col-search">
			<input type="submit" value="Find" name="search_booking_date" />
		</div>
	</form>
</div>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery(".from_booking_date").datepicker({
		maxDate: "+365D",
		numberOfMonths: 2,
        dateFormat: '<?php print($objSettings->getSetting('conf_datepicker_date_format')); ?>',
        firstDay: <?php print(get_option('start_of_week')); ?>
	});
	jQuery(".to_booking_date").datepicker({
		maxDate: "+365D",
		numberOfMonths: 2,
        dateFormat: '<?php print($objSettings->getSetting('conf_datepicker_date_format')); ?>',
        firstDay: <?php print(get_option('start_of_week')); ?>
	});
	jQuery(".booking_date_datepicker_from_image").click(function() {
		jQuery(".from_booking_date").datepicker("show");
	});
	jQuery(".booking_date_datepicker_to_image").click(function() {
		jQuery(".to_booking_date").datepicker("show");
	});
});
</script>