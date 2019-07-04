<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
// Scripts
wp_enqueue_script('jquery');
wp_enqueue_script('jquery-ui-core');
wp_enqueue_script('jquery-ui-tabs');
wp_enqueue_script('jquery-datatables');
wp_enqueue_script('datatables-responsive');
wp_enqueue_script('jquery-ui-datepicker', array('jquery','jquery-ui-core'));
wp_enqueue_script('multidatespicker');
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
<div class="car-rental-locations-admin car-rental-tabbed-admin car-rental-tabbed-admin-wide car-rental-tabbed-admin bg-cyan">
	<?php if ($errorMessage != ""): ?>
		<div class="admin-info-message admin-wide-message admin-error-message"><?php print($errorMessage); ?></div>
	<?php elseif ($okayMessage != ""): ?>
		<div class="admin-info-message admin-wide-message admin-okay-message"><?php print($okayMessage); ?></div>
	<?php endif; ?>
	<div class="body">
		<!-- tabs -->
		<div class="car-rental-admin-tabs car-rental-admin-tabs-pos-top-left car-rental-admin-tabs-anim-flip car-rental-admin-tabs-response-to-icons">
			<input type="radio" name="car-rental-admin-tabs"<?php print($locationsTabChecked); ?> id="car-rental-admin-tab1" class="car-rental-admin-tab-content-1">
			<label for="car-rental-admin-tab1"><span><span><i class="fa fa-bolt"></i>Locations</span></span></label>

			<input type="radio" name="car-rental-admin-tabs"<?php print($distancesTabChecked); ?> id="car-rental-admin-tab2" class="car-rental-admin-tab-content-2">
			<label for="car-rental-admin-tab2"><span><span><i class="fa fa-bolt"></i>Distances</span></span></label>

			<input type="radio" name="car-rental-admin-tabs"<?php print($closedDaysTabChecked); ?> id="car-rental-admin-tab3" class="car-rental-admin-tab-content-3">
			<label for="car-rental-admin-tab3"><span><span><i class="fa fa-car"></i>Closed Days</span></span></label>

			<ul>
				<li class="car-rental-admin-tab-content-1">
					<div class="typography">
						<?php include('partial.Locations.php'); ?>
					</div>
				</li>
                <li class="car-rental-admin-tab-content-2">
                    <div class="typography">
                        <?php include('partial.Distances.php'); ?>
                    </div>
                </li>
				<li class="car-rental-admin-tab-content-3">
					<div class="typography">
						<?php include('partial.ClosedDates.php'); ?>
					</div>
				</li>
			</ul>
		</div>
		<!--/ tabs -->
	</div>
</div>