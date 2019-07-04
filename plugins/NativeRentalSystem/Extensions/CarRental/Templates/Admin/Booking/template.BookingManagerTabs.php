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
wp_enqueue_style('datepicker');
wp_enqueue_style('jquery-ui');
wp_enqueue_style('jquery-datatables');
wp_enqueue_style('datatables-responsive');
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
            <input type="radio" name="car-rental-admin-tabs"<?php print($pickupsTabChecked); ?> id="car-rental-admin-tab1" class="car-rental-admin-tab-content-1">
            <label for="car-rental-admin-tab1"><span><span><i class="fa fa-car"></i>Pick-ups</span></span></label>

            <input type="radio" name="car-rental-admin-tabs"<?php print($returnsTabChecked); ?> id="car-rental-admin-tab2" class="car-rental-admin-tab-content-2">
            <label for="car-rental-admin-tab2"><span><span><i class="fa fa-globe"></i>Returns</span></span></label>

            <input type="radio" name="car-rental-admin-tabs"<?php print($bookingsTabChecked); ?> id="car-rental-admin-tab3" class="car-rental-admin-tab-content-3">
            <label for="car-rental-admin-tab3"><span><span><i class="fa fa-suitcase"></i>Reservations</span></span></label>

            <input type="radio" name="car-rental-admin-tabs"<?php print($customersTabChecked); ?> id="car-rental-admin-tab4" class="car-rental-admin-tab-content-4">
            <label for="car-rental-admin-tab4"><span><span><i class="fa fa-tachometer"></i>Customers</span></span></label>

            <input type="radio" name="car-rental-admin-tabs"<?php print($itemsAvailabilityTabChecked); ?> id="car-rental-admin-tab5" class="car-rental-admin-tab-content-5">
            <label for="car-rental-admin-tab5"><span><span><i class="fa fa-calendar"></i>Cars Calendar</span></span></label>

            <input type="radio" name="car-rental-admin-tabs"<?php print($extrasAvailabilityTabChecked); ?> id="car-rental-admin-tab6" class="car-rental-admin-tab-content-6">
            <label for="car-rental-admin-tab6"><span><span><i class="fa fa-calendar"></i>Extras Calendar</span></span></label>

            <input type="radio" name="car-rental-admin-tabs"<?php print($apiLogTabChecked); ?> id="car-rental-admin-tab7" class="car-rental-admin-tab-content-7">
            <label for="car-rental-admin-tab7"><span><span><i class="fa fa-eye"></i>API Log</span></span></label>

            <ul>
                <li class="car-rental-admin-tab-content-1">
                    <div class="typography">
                        <?php include('partial.SearchForm.PickupDate.php'); ?>
                        <?php include('partial.Pickups.php'); ?>
                    </div>
                </li>
                <li class="car-rental-admin-tab-content-2">
                    <div class="typography">
                        <?php include('partial.SearchForm.ReturnDate.php'); ?>
                        <?php include('partial.Returns.php'); ?>
                    </div>
                </li>
                <li class="car-rental-admin-tab-content-3">
                    <div class="typography">
                        <?php include('partial.SearchForm.BookingDate.php'); ?>
                        <?php include('partial.Bookings.php'); ?>
                    </div>
                </li>
                <li class="car-rental-admin-tab-content-4">
                    <div class="typography">
                        <?php include('partial.SearchForm.CustomerDate.php'); ?>
                        <?php include('partial.Customers.php'); ?>
                    </div>
                </li>
                <li class="car-rental-admin-tab-content-5">
                    <div class="typography">
                        <?php include('partial.SearchForm.ItemsAvailability.php'); ?>
                        <?php
                        if($classifiedItems):
                            include('partial.ClassifiedItemsAvailabilityCalendar.php');
                        else:
                            include('partial.ItemsAvailabilityCalendar.php');
                        endif;
                        ?>
                    </div>
                </li>
                <li class="car-rental-admin-tab-content-6">
                    <div class="typography">
                        <?php include('partial.SearchForm.ExtrasAvailability.php'); ?>
                        <?php include('partial.ExtrasAvailabilityCalendar.php'); ?>
                    </div>
                </li>
                <li class="car-rental-admin-tab-content-7">
                    <div class="typography">
                        <?php include('partial.ApiLog.php'); ?>
                    </div>
                </li>
            </ul>
        </div>
        <!--/ tabs -->
    </div>
</div>