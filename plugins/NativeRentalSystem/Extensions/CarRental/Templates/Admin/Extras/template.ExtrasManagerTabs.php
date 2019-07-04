<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
// Scripts
wp_enqueue_script('jquery');
wp_enqueue_script('jquery-ui-core');
wp_enqueue_script('jquery-ui-tabs');
wp_enqueue_script('jquery-datatables');
wp_enqueue_script('datatables-responsive');
wp_enqueue_script('jquery-ui-datepicker', array('jquery','jquery-ui-core'));
wp_enqueue_script('jquery-validate');
wp_enqueue_script('car-rental-admin');

// Load Nice Admin Tabs CSS
wp_enqueue_style('font-awesome');
wp_enqueue_style('car-rental-admin-tabs');

// Styles
wp_enqueue_style('jquery-datatables');
wp_enqueue_style('datatables-responsive');
wp_enqueue_style('datepicker');
wp_enqueue_style('jquery-ui');
wp_enqueue_style('jquery-validate');
wp_enqueue_style('car-rental-admin');
?>
<div class="car-rental-list-admin car-rental-tabbed-admin car-rental-tabbed-admin-wide bg-cyan">
	<?php if ($errorMessage != ""): ?>
		<div class="admin-info-message admin-wide-message admin-error-message"><?php print($errorMessage); ?></div>
	<?php elseif ($okayMessage != ""): ?>
		<div class="admin-info-message admin-wide-message admin-okay-message"><?php print($okayMessage); ?></div>
	<?php endif; ?>
	<div class="body">
		<!-- tabs -->
		<div class="car-rental-admin-tabs car-rental-admin-tabs-pos-top-left car-rental-admin-tabs-anim-flip car-rental-admin-tabs-response-to-icons">
			<input type="radio" name="car-rental-admin-tabs"<?php print($priceTableTabChecked); ?> id="car-rental-admin-tab1" class="car-rental-admin-tab-content-1">
			<label for="car-rental-admin-tab1"><span><span><i class="fa fa-bolt"></i>Overview</span></span></label>

			<input type="radio" name="car-rental-admin-tabs"<?php print($extrasTabChecked); ?> id="car-rental-admin-tab2" class="car-rental-admin-tab-content-2">
			<label for="car-rental-admin-tab2"><span><span><i class="fa fa-plus-square-o"></i>Extras</span></span></label>

            <input type="radio" name="car-rental-admin-tabs"<?php print($optionsTabChecked); ?> id="car-rental-admin-tab3" class="car-rental-admin-tab-content-3">
			<label for="car-rental-admin-tab3"><span><span><i class="fa fa-globe"></i>Options</span></span></label>

            <input type="radio" name="car-rental-admin-tabs"<?php print($durationDiscountsTabChecked); ?> id="car-rental-admin-tab4" class="car-rental-admin-tab-content-4">
            <label for="car-rental-admin-tab4"><span><span><i class="fa fa-clock-o"></i>Duration Discounts</span></span></label>

            <input type="radio" name="car-rental-admin-tabs"<?php print($discountsInAdvanceTabChecked); ?> id="car-rental-admin-tab5" class="car-rental-admin-tab-content-5">
            <label for="car-rental-admin-tab5"><span><span><i class="fa fa-bolt"></i>Discounts in Advance</span></span></label>

            <input type="radio" name="car-rental-admin-tabs"<?php print($blocksTabChecked); ?> id="car-rental-admin-tab6" class="car-rental-admin-tab-content-6">
			<label for="car-rental-admin-tab6"><span><span><i class="fa fa-suitcase"></i>Blocks</span></span></label>

			<ul>
				<li class="car-rental-admin-tab-content-1">
					<div class="typography">
						<?php include('partial.ExtrasPriceTable.php'); ?>
					</div>
				</li>
				<li class="car-rental-admin-tab-content-2">
					<div class="typography">
						<?php include('partial.Extras.php'); ?>
					</div>
				</li>
				<li class="car-rental-admin-tab-content-3">
					<div class="typography">
						<?php include('partial.Options.php'); ?>
					</div>
				</li>
                <li class="car-rental-admin-tab-content-4">
                    <div class="typography">
                        <?php include('partial.DurationDiscounts.php'); ?>
                    </div>
                </li>
                <li class="car-rental-admin-tab-content-5">
                    <div class="typography">
                        <?php include('partial.BookingInAdvanceDiscounts.php'); ?>
                    </div>
                </li>
				<li class="car-rental-admin-tab-content-6">
					<div class="typography">
						<?php include('partial.Blocks.php'); ?>
					</div>
				</li>
			</ul>
		</div>
		<!--/ tabs -->
	</div>
</div>
