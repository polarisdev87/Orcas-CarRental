<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
// Scripts
wp_enqueue_script('jquery');
wp_enqueue_script('jquery-ui-core');
wp_enqueue_script('jquery-ui-tabs');
wp_enqueue_script('jquery-ui-datepicker', array('jquery','jquery-ui-core'));
wp_enqueue_script('jquery-validate');
wp_enqueue_script('car-rental-admin');

// Load Nice Admin Tabs CSS
wp_enqueue_style('font-awesome');
wp_enqueue_style('car-rental-admin-tabs');

// Styles
wp_enqueue_style('datepicker');
wp_enqueue_style('jquery-validate');
wp_enqueue_style('car-rental-admin');
?>
<div class="car-rental-settings-admin car-rental-tabbed-admin car-rental-tabbed-admin-medium bg-cyan">
	<?php if ($errorMessage != ""): ?>
		<div class="admin-info-message admin-standard-width-message admin-error-message"><?php print($errorMessage); ?></div>
	<?php elseif ($okayMessage != ""): ?>
		<div class="admin-info-message admin-standard-width-message admin-okay-message"><?php print($okayMessage); ?></div>
	<?php endif; ?>
	<div class="body">
		<!-- tabs -->
		<div class="car-rental-admin-tabs car-rental-admin-tabs-pos-top-left car-rental-admin-tabs-anim-flip car-rental-admin-tabs-response-to-icons">
			<input type="radio" name="car-rental-admin-tabs"<?php print($globalSettingsTabChecked); ?> id="car-rental-admin-tab1" class="car-rental-admin-tab-content-1">
			<label for="car-rental-admin-tab1"><span><span><i class="fa fa-gear"></i>Global</span></span></label>

			<input type="radio" name="car-rental-admin-tabs"<?php print($customerSettingsTabChecked); ?> id="car-rental-admin-tab2" class="car-rental-admin-tab-content-2">
			<label for="car-rental-admin-tab2"><span><span><i class="fa fa-gear"></i>Customer</span></span></label>

			<input type="radio" name="car-rental-admin-tabs"<?php print($searchSettingsTabChecked); ?> id="car-rental-admin-tab3" class="car-rental-admin-tab-content-3">
			<label for="car-rental-admin-tab3"><span><span><i class="fa fa-gear"></i>Search</span></span></label>

			<input type="radio" name="car-rental-admin-tabs"<?php print($priceSettingsTabChecked); ?> id="car-rental-admin-tab4" class="car-rental-admin-tab-content-4">
			<label for="car-rental-admin-tab4"><span><span><i class="fa fa-gear"></i>Price</span></span></label>

			<input type="radio" name="car-rental-admin-tabs"<?php print($emailSettingsTabChecked); ?> id="car-rental-admin-tab5" class="car-rental-admin-tab-content-5">
			<label for="car-rental-admin-tab5"><span><span><i class="fa fa-gear"></i>Email</span></span></label>

			<input type="radio" name="car-rental-admin-tabs"<?php print($importDemoTabChecked); ?> id="car-rental-admin-tab6" class="car-rental-admin-tab-content-6">
			<label for="car-rental-admin-tab6"><span><span><i class="fa fa-gear"></i>Demo</span></span></label>

			<input type="radio" name="car-rental-admin-tabs"<?php print($singleStatusTabChecked); ?> id="car-rental-admin-tab7" class="car-rental-admin-tab-content-7">
			<label for="car-rental-admin-tab7"><span><span><i class="fa fa-gear"></i>Status</span></span></label>

			<ul>
				<li class="car-rental-admin-tab-content-1">
					<div class="typography">
						<?php include('partial.GlobalSettings.php'); ?>
					</div>
				</li>
				<li class="car-rental-admin-tab-content-2">
					<div class="typography">
						<?php include('partial.CustomerSettings.php'); ?>
					</div>
				</li>
				<li class="car-rental-admin-tab-content-3">
					<div class="typography">
						<?php include('partial.SearchSettings.php'); ?>
					</div>
				</li>
				<li class="car-rental-admin-tab-content-4">
					<div class="typography">
						<?php include('partial.PriceSettings.php'); ?>
					</div>
				</li>
				<li class="car-rental-admin-tab-content-5">
					<div class="typography">
						<?php include('partial.EmailSettings.php'); ?>
					</div>
				</li>
				<li class="car-rental-admin-tab-content-6">
					<div class="typography">
						<?php include('partial.ImportDemo.php'); ?>
					</div>
				</li>
				<li class="car-rental-admin-tab-content-7">
					<div class="typography">
						<?php include('partial.SingleStatus.php'); ?>
					</div>
				</li>
			</ul>
		</div>
		<!--/ tabs -->
	</div>
</div>