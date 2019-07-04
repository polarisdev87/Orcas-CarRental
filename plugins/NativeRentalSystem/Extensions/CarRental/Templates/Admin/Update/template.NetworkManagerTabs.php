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
<div class="car-rental-settings-admin car-rental-tabbed-admin car-rental-tabbed-admin-medium car-rental-tabbed-admin bg-cyan">
	<?php if ($errorMessage != ""): ?>
		<div class="admin-info-message admin-standard-width-message admin-error-message"><?php print($errorMessage); ?></div>
	<?php elseif ($okayMessage != ""): ?>
		<div class="admin-info-message admin-standard-width-message admin-okay-message"><?php print($okayMessage); ?></div>
	<?php endif; ?>
	<div class="body">
		<!-- tabs -->
		<div class="car-rental-admin-tabs car-rental-admin-tabs-pos-top-left car-rental-admin-tabs-anim-flip car-rental-admin-tabs-response-to-icons">
			<input type="radio" name="car-rental-admin-tabs" checked="checked" id="car-rental-admin-tab1" class="car-rental-admin-tab-content-1">
			<label for="car-rental-admin-tab1"><span><span><i class="fa fa-gear"></i>Network Upgrade / Network Update</span></span></label>

			<ul>
				<li class="car-rental-admin-tab-content-1">
					<div class="typography">
						<?php
						if($majorUpgrade):
							// Major plugin upgrade process
							include("partial.NetworkUpgrade.php");
						else:
							// Minor plugin update process
							include("partial.NetworkUpdate.php");
						endif;
						?>
					</div>
				</li>
			</ul>
		</div>
		<!--/ tabs -->
	</div>
</div>