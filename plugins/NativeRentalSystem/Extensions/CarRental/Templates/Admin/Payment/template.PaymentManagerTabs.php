<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
// Scripts
wp_enqueue_script('jquery');
wp_enqueue_script('jquery-ui-core');
wp_enqueue_script('jquery-ui-tabs');
wp_enqueue_script('jquery-datatables');
wp_enqueue_script('datatables-responsive');
wp_enqueue_script('car-rental-admin');

// Load Nice Admin Tabs CSS
wp_enqueue_style('font-awesome');
wp_enqueue_style('car-rental-admin-tabs');

// Styles
wp_enqueue_style('jquery-datatables');
wp_enqueue_style('datatables-responsive');
wp_enqueue_style('jquery-ui');
wp_enqueue_style('car-rental-admin');
?>
<div class="car-rental-payments-admin car-rental-tabbed-admin car-rental-tabbed-admin-wide bg-cyan">
	<?php if ($errorMessage != ""): ?>
		<div class="admin-info-message admin-wide-message admin-error-message"><?php print($errorMessage); ?></div>
	<?php elseif ($okayMessage != ""): ?>
		<div class="admin-info-message admin-wide-message admin-okay-message"><?php print($okayMessage); ?></div>
	<?php endif; ?>
	<div class="body">
	<!-- tabs -->
	<div class="car-rental-admin-tabs car-rental-admin-tabs-pos-top-left car-rental-admin-tabs-anim-flip car-rental-admin-tabs-response-to-icons">
        <?php if($prepaymentsEnabled): ?>
            <input type="radio" name="car-rental-admin-tabs"<?php print($prepaymentsTabChecked); ?> id="car-rental-admin-tab1" class="car-rental-admin-tab-content-1">
            <label for="car-rental-admin-tab1"><span><span><i class="fa fa-money"></i>Prepayments</span></span></label>
        <?php endif; ?>

        <input type="radio" name="car-rental-admin-tabs"<?php print($taxesTabChecked); ?> id="car-rental-admin-tab2" class="car-rental-admin-tab-content-2">
        <label for="car-rental-admin-tab2"><span><span><i class="fa fa-money"></i>Taxes</span></span></label>

        <?php if($prepaymentsEnabled): ?>
            <input type="radio" name="car-rental-admin-tabs"<?php print($paymentMethodsTabChecked); ?> id="car-rental-admin-tab3" class="car-rental-admin-tab-content-3">
            <label for="car-rental-admin-tab3"><span><span><i class="fa fa-gear"></i>Payment Methods</span></span></label>

            <input type="radio" name="car-rental-admin-tabs"<?php print($apiLogTabChecked); ?> id="car-rental-admin-tab4" class="car-rental-admin-tab-content-4">
            <label for="car-rental-admin-tab4"><span><span><i class="fa fa-eye"></i>API Log</span></span></label>
        <?php endif; ?>

		<ul>
            <?php if($prepaymentsEnabled): ?>
                <li class="car-rental-admin-tab-content-1">
                    <div class="typography">
                        <?php include('partial.Prepayments.php'); ?>
                    </div>
                </li>
            <?php endif; ?>
            <li class="car-rental-admin-tab-content-2">
                <div class="typography">
                    <?php include('partial.Taxes.php'); ?>
                </div>
            </li>
            <?php if($prepaymentsEnabled): ?>
                <li class="car-rental-admin-tab-content-3">
                    <div class="typography">
                        <?php include('partial.PaymentMethods.php'); ?>
                    </div>
                </li>
                <li class="car-rental-admin-tab-content-4">
                    <div class="typography">
                        <?php include('partial.ApiLog.php'); ?>
                    </div>
                </li>
            <?php endif; ?>
		</ul>
	</div>
	<!--/ tabs -->
	</div>
</div>