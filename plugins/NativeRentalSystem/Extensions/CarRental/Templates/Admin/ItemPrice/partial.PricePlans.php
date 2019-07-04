<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<h1>
	<span>Car Price Plans</span>&nbsp;&nbsp;
</h1>
<p class="big-text padded">Please select a price group:</p>
<form action="<?php print(admin_url('admin.php')); ?>" method="GET" class="big-labels form-new-price-plan">
    <input type="hidden" name="page" value="<?php print($addNewPricePlanPage); ?>" />
    <select name="price_group_id" class="price-groups-dropdown">
        <?php print($priceGroupDropDownOptions); ?>
    </select>
    <input type="hidden" name="price_plan_id" value="0" />
    <input type="submit" value="Add New Price Plan" name="add_new_price_plan" class="add-new-price-plan" disabled="disabled" />
</form>
<hr />
<table class="price-plans-table" cellpadding="5" cellspacing="0">
	<tbody class="price-group-html">
	<tr>
		<td colspan="9"><?php print($objLang->getText('NRS_ADMIN_AJAX_PRICE_PLANS_PLEASE_SELECT_TEXT')); ?></td>
	</tr>
	</tbody>
</table>

<script type="text/javascript">
jQuery(document).ready(function()
{
	var objPriceGroupsDropDown = jQuery('.price-groups-dropdown');

    objPriceGroupsDropDown.on('change', function()
    {
        //alert('test');
        var objAddNew = jQuery('input[type="submit"].add-new-price-plan');
        if(this.value > 0)
        {
            objAddNew.removeAttr('disabled');
        } else
        {
            objAddNew.attr('disabled', true);
        }
        getCarRentalPricePlans(
            '<?php print($ajaxSecurityNonce); ?>',
            this.value,
            '<?php print($objConf->getExtensionAdminImagesURL('AjaxLoader.gif')); ?>'
        );
    });
    // If it's already chosen
    if(objPriceGroupsDropDown.val() > 0)
    {
        //alert('test val:' + jQuery('.price-groups-dropdown').val());
        jQuery('input[type="submit"].add-new-price-plan').removeAttr('disabled');
        getCarRentalPricePlans(
            '<?php print($ajaxSecurityNonce); ?>',
            objPriceGroupsDropDown.val(),
            '<?php print($objConf->getExtensionAdminImagesURL('AjaxLoader.gif')); ?>'
        );
    }
});
</script>