<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<h1 class="customer-search-title">
	All / Search
</h1>
<div class="customer-search-form">
	<form action="<?php print(admin_url('admin.php')); ?>" method="GET" class="form_customer_date">
        <input type="hidden" name="page" value="<?php print($objConf->getURLPrefix()); ?>customer-search-results" />
		<div class="col-date-field">
			<select name="booking_type">
				<option value="0">First Reservation</option>
				<option value="1">Last Reservation</option>
			</select> &nbsp;&nbsp;&nbsp;&nbsp;
			<?php print($objLang->getText('NRS_FROM_TEXT')); ?> &nbsp;
			<input type="text" name="from_date" class="from_customer_date required" />
    		<img src="<?php print($objConf->getExtensionAdminImagesURL('Month.png')); ?>" alt="Date Selector" class="customer_date_datepicker_from_image date-selector-image" />
			 &nbsp;&nbsp;&nbsp;&nbsp;
			<strong><?php print($objLang->getText('NRS_TO_TEXT')); ?></strong> &nbsp;&nbsp;&nbsp;&nbsp;
			<input type="text" name="to_date" class="to_customer_date required" />
			<img src="<?php print($objConf->getExtensionAdminImagesURL('Month.png')); ?>" alt="Date Selector" class="customer_date_datepicker_from_image date-selector-image" />
		</div>
		<div class="col-search">
			<input type="submit" value="Find" name="search_customer_date" />
			<input class="add-customer" type="button" value="Add"
			   onClick="window.location.href='admin.php?page=<?php print($objConf->getURLPrefix()); ?>add-customer&amp;customer_id=0'"
			/>
		</div>
	</form>
</div>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery(".form_customer_date").validate();

	jQuery(".from_customer_date").datepicker({
		maxDate: "+365D",
		numberOfMonths: 2,
        dateFormat: '<?php print($objSettings->getSetting('conf_datepicker_date_format')); ?>',
        firstDay: <?php print(get_option('start_of_week')); ?>
	});
	jQuery(".to_customer_date").datepicker({
		maxDate: "+365D",
		numberOfMonths: 2,
        dateFormat: '<?php print($objSettings->getSetting('conf_datepicker_date_format')); ?>',
        firstDay: <?php print(get_option('start_of_week')); ?>
	});
	jQuery(".customer_date_datepicker_from_image").click(function() {
		jQuery(".from_customer_date").datepicker("show");
	});
	jQuery(".customer_date_datepicker_to_image").click(function() {
		jQuery(".to_customer_date").datepicker("show");
	});
});
</script>