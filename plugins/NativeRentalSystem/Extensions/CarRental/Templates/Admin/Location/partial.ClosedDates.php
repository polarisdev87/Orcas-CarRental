<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<h1>
	<span>Closed Dates</span>&nbsp;&nbsp;
</h1>
<p class="big-text padded">Click on the dates in calendar, if you want to have your specific or all locations closed:</p>
<div class="big-labels">
    <select name="location_id" class="location">
        <?php print($locationDropDownOptions); ?>
    </select>
    <input type="button" class="save-closed-dates" value="Save closed dates" />
</div>
<?php foreach($closedDatesArrays AS $closedDates): ?>
    <div class="closed-dates-calendar closed-dates-<?php print($closedDates['location_id']); ?> box" style="display:none;"></div>
    <input type="hidden" name="selected_dates_<?php print($closedDates['location_id']); ?>" value="<?php print($closedDates['closed_dates']); ?>" class="selected-dates-<?php print($closedDates['location_id']); ?>" />
<?php endforeach; ?>
<input type="hidden" name="selected_dates" class="selected-dates" />
<script type="text/javascript">
jQuery(document).ready(function()
{
    // Show selected location calendar and hide previously selected calendar
    showCarRentalCalendar(0);
    var prevId = 0;
    jQuery('.location').on('change', function()
    {
        jQuery('.closed-dates-' + prevId).hide();
        showCarRentalCalendar(this.value);
        prevId = this.value;
    });

    jQuery('.save-closed-dates').click(function()
    {
        var selectedLocationId = jQuery('.location').val();
        var selectedDates = jQuery('.selected-dates-' + selectedLocationId).val();
        //console.log('Selected Dates:'); console.log(selectedDates);
        closeCarRentalOnSelectedDays('<?php print($ajaxSecurityNonce); ?>', selectedLocationId, selectedDates);
    });
});
</script>
