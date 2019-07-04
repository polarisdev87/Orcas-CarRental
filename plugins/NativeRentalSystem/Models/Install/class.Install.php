<?php
/**
 * NRS Plugin

 * @note - It does not have settings param in constructor on purpose!
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Install;
use NativeRentalSystem\Models\AbstractElement;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\iPrimitive;
use NativeRentalSystem\Models\Language\Language;

class Install extends AbstractElement implements iPrimitive
{
    protected $conf             = NULL;
    protected $lang 		    = NULL;
    protected $debugMode 	    = 0;
    protected $blogId           = 0;

    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, $paramBlogId)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;
        $this->blogId = intval($paramBlogId);
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    public function getId()
    {
        return $this->blogId;
    }

    private function getTables()
    {
        $arrTables = array(
            "benefits",
            "body_types",
            "bookings",
            "booking_options",
            "closed_dates",
            "customers",
            "discounts",
            "distances",
            "emails",
            "extras",
            "features",
            "fuel_types",
            "invoices",
            "items",
            "item_features",
            "item_locations",
            "locations",
            "logs",
            "manufacturers",
            "options",
            "payment_methods",
            "prepayments",
            "price_groups",
            "price_plans",
            "settings",
            "taxes",
            "transmission_types",
        );

        return $arrTables;
    }

    private function getTableCreateSQLs()
    {
        $arrSQL = array();
        // Get DB tables charset and collate
        $dbTableCharsetCollate = $this->conf->getInternalWPDB()->get_charset_collate();

        $arrSQL[] = "CREATE TABLE `{$this->conf->getPrefix()}benefits` (
          `benefit_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `benefit_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
          `benefit_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
          `demo_benefit_image` tinyint(1) unsigned NOT NULL DEFAULT '0',
          `benefit_order` int(11) unsigned NOT NULL DEFAULT '1',
          `blog_id` int(11) unsigned NOT NULL DEFAULT '0',
          PRIMARY KEY (`benefit_id`),
          KEY `benefit_order` (`benefit_order`),
          KEY `blog_id` (`blog_id`)
        ) ENGINE=InnoDB {$dbTableCharsetCollate};";

        $arrSQL[] = "CREATE TABLE `{$this->conf->getPrefix()}body_types` (
          `body_type_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `body_type_title` varchar(255) NOT NULL,
          `body_type_order` int(11) unsigned NOT NULL DEFAULT '1',
          `blog_id` int(11) unsigned NOT NULL DEFAULT '0',
          PRIMARY KEY (`body_type_id`),
          KEY `body_type_order` (`body_type_order`),
          KEY `blog_id` (`blog_id`)
        ) ENGINE=InnoDB {$dbTableCharsetCollate};";

        // The NULL value, in databases and programming, is the equivalent of saying that the field has no value (or it is unknown).
        // NOTE: 'DEFAULT NULL' allows for us to have unique columns with NULL value
        $arrSQL[] = "CREATE TABLE `{$this->conf->getPrefix()}bookings` (
          `booking_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `booking_code` varchar(25) DEFAULT NULL,
          `coupon_code` varchar(50) NOT NULL,
          `booking_timestamp` int(11) unsigned NOT NULL DEFAULT '0',
          `last_edit_timestamp` int(11) unsigned NOT NULL DEFAULT '0',
          `pickup_timestamp` int(11) unsigned NOT NULL DEFAULT '0',
          `return_timestamp` int(11) unsigned NOT NULL DEFAULT '0',
          `pickup_location_code` varchar(50) NOT NULL,
          `return_location_code` varchar(50) NOT NULL,
          `partner_id` int(11) NOT NULL DEFAULT '-1',
          `manufacturer_id` int(11) NOT NULL DEFAULT '-1',
          `body_type_id` int(11) NOT NULL DEFAULT '-1',
          `transmission_type_id` int(11) NOT NULL DEFAULT '-1',
          `fuel_type_id` int(11) NOT NULL DEFAULT '-1',
          `customer_id` int(11) unsigned NOT NULL DEFAULT '0',
          `payment_method_code` varchar(25) DEFAULT NULL,
          `payment_successful` tinyint(1) NOT NULL DEFAULT '0',
          `payment_transaction_id` varchar(100) DEFAULT NULL,
          `payer_email` varchar(255) DEFAULT NULL,
          `is_block` tinyint(1) unsigned NOT NULL DEFAULT '0',
          `is_cancelled` tinyint(1) unsigned NOT NULL DEFAULT '0',
          `is_completed_early` tinyint(1) unsigned NOT NULL DEFAULT '0',
          `block_name` varchar(255) DEFAULT NULL,
          `blog_id` int(11) unsigned NOT NULL DEFAULT '0',
		  `deposit_charge_id` varchar(255) DEFAULT NULL,
		  `deposit_retrieve_fields` TEXT NULL,
          PRIMARY KEY (`booking_id`),
          UNIQUE KEY `booking_code` (`booking_code`),
          KEY `booking_timestamp` (`booking_timestamp`),
          KEY `last_edit_timestamp` (`last_edit_timestamp`),
          KEY `pickup_timestamp` (`pickup_timestamp`),
          KEY `customer_id` (`customer_id`),
          KEY `is_cancelled` (`is_cancelled`),
          KEY `coupon_code` (`coupon_code`),
          KEY `return_timestamp` (`return_timestamp`),
          KEY `pickup_location_code` (`pickup_location_code`),
          KEY `return_location_code` (`return_location_code`),
          KEY `is_completed_early` (`is_completed_early`),
          KEY `blog_id` (`blog_id`)
        ) ENGINE=InnoDB {$dbTableCharsetCollate};";

        // Make sure we allow units booked to be SIGNED and UNSIGNED, because of possible value -1, which stands for ALL
        $arrSQL[] = "CREATE TABLE `{$this->conf->getPrefix()}booking_options` (
          `booking_id` int(11) unsigned NOT NULL DEFAULT '0',
          `item_sku` varchar(50) NOT NULL,
          `extra_sku` varchar(50) NOT NULL,
          `option_id` int(11) unsigned NOT NULL DEFAULT '0',
          `units_booked` int(11) NOT NULL DEFAULT '1',
          `blog_id` int(11) unsigned NOT NULL DEFAULT '0',
          KEY `booking_id` (`booking_id`),
          KEY `option_id` (`option_id`),
          KEY `item_sku` (`item_sku`),
          KEY `extra_sku` (`extra_sku`),
          KEY `blog_id` (`blog_id`)
        ) ENGINE=InnoDB {$dbTableCharsetCollate};";

        $arrSQL[] = "CREATE TABLE `{$this->conf->getPrefix()}closed_dates` (
          `closed_date` date NOT NULL DEFAULT '0000-00-00',
          `location_code` varchar(50) NOT NULL,
          `blog_id` int(11) unsigned NOT NULL DEFAULT '0',
          KEY `closed_date` (`closed_date`),
          KEY `location_code` (`location_code`),
          KEY `blog_id` (`blog_id`)
        ) ENGINE=InnoDB {$dbTableCharsetCollate};";

        $arrSQL[] = "CREATE TABLE `{$this->conf->getPrefix()}customers` (
          `customer_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `title` varchar(16) NOT NULL,
          `first_name` varchar(64) NOT NULL,
          `last_name` varchar(64) NOT NULL,
          `birthdate` date NOT NULL DEFAULT '0000-00-00',
          `street_address` varchar(255) NOT NULL,
          `city` varchar(64) NOT NULL,
          `state` varchar(128) NOT NULL,
          `zip_code` varchar(64) NOT NULL,
          `country` varchar(64) NOT NULL,
          `phone` varchar(64) NOT NULL,
          `email` varchar(128) NOT NULL,
          `comments` text NOT NULL,
          `ip` varchar(32) NOT NULL,
          `existing_customer` tinyint(1) unsigned NOT NULL DEFAULT '0',
          `registration_timestamp` int(11) unsigned NOT NULL DEFAULT '0',
          `last_visit_timestamp` int(11) unsigned NOT NULL DEFAULT '0',
          `blog_id` int(11) unsigned NOT NULL DEFAULT '0',
		  `cotumer_stripe_id` varchar(255) NOT NULL,
          PRIMARY KEY (`customer_id`),
          KEY `email` (`email`),
          KEY `registration_timestamp` (`registration_timestamp`),
          KEY `last_visit_timestamp` (`last_visit_timestamp`),
          KEY `blog_id` (`blog_id`)
        ) ENGINE=InnoDB {$dbTableCharsetCollate};";

        $arrSQL[] = "CREATE TABLE `{$this->conf->getPrefix()}discounts` (
          `discount_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `discount_type` tinyint(11) unsigned NOT NULL DEFAULT '1',
          `coupon_discount` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
          `price_plan_id` int(11) unsigned NOT NULL DEFAULT '0',
          `extra_id` int(11) unsigned NOT NULL DEFAULT '0',
          `period_from` int(11) unsigned NOT NULL DEFAULT '0',
          `period_till` int(11) unsigned NOT NULL DEFAULT '0',
          `discount_percentage` decimal(10,3) unsigned NOT NULL DEFAULT '0.000',
          `blog_id` int(11) unsigned NOT NULL DEFAULT '0',
          PRIMARY KEY (`discount_id`),
          KEY `discount_type` (`discount_type`),
          KEY `coupon_discount` (`coupon_discount`),
          KEY `price_plan_id` (`price_plan_id`),
          KEY `extra_id` (`extra_id`),
          KEY `period` (`period_from`,`period_till`),
          KEY `discount_percentage` (`discount_percentage`),
          KEY `blog_id` (`blog_id`)
        ) ENGINE=InnoDB {$dbTableCharsetCollate};";

        $arrSQL[] = "CREATE TABLE `{$this->conf->getPrefix()}distances` (
          `distance_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `pickup_location_id` int(11) unsigned NOT NULL DEFAULT '0',
          `return_location_id` int(11) unsigned NOT NULL DEFAULT '0',
          `show_distance` tinyint(1) unsigned NOT NULL DEFAULT '1',
          `distance` decimal(10,1) unsigned NOT NULL DEFAULT '0.0',
          `distance_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
          `blog_id` int(11) unsigned NOT NULL DEFAULT '0',
          PRIMARY KEY (`distance_id`),
          KEY `pickup_location_id` (`pickup_location_id`),
          KEY `return_location_id` (`return_location_id`),
          KEY `blog_id` (`blog_id`)
        ) ENGINE=InnoDB {$dbTableCharsetCollate};";

        $arrSQL[] = "CREATE TABLE `{$this->conf->getPrefix()}emails` (
          `email_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `email_type` tinyint(2) unsigned NOT NULL DEFAULT '0',
          `email_subject` varchar(255) NOT NULL,
          `email_body` longtext NOT NULL,
          `blog_id` int(11) unsigned NOT NULL DEFAULT '0',
          PRIMARY KEY (`email_id`),
          KEY `email_type` (`email_type`),
          KEY `blog_id` (`blog_id`)
        ) ENGINE=InnoDB {$dbTableCharsetCollate};";

        $arrSQL[] = "CREATE TABLE `{$this->conf->getPrefix()}extras` (
          `extra_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `extra_sku` varchar(50) NOT NULL,
          `partner_id` int(11) unsigned NOT NULL DEFAULT '0',
          `item_id` int(11) unsigned NOT NULL DEFAULT '0',
          `extra_name` varchar(255) NOT NULL,
          `price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
          `price_type` tinyint(1) unsigned NOT NULL DEFAULT '1',
          `fixed_rental_deposit` decimal(10,2) NOT NULL DEFAULT '0.00',
          `units_in_stock` int(11) unsigned NOT NULL DEFAULT '1',
          `max_units_per_booking` int(11) unsigned NOT NULL DEFAULT '1',
          `options_display_mode` tinyint(1) unsigned NOT NULL DEFAULT '1',
          `options_measurement_unit` varchar(25) NOT NULL,
          `blog_id` int(11) unsigned NOT NULL DEFAULT '0',
          PRIMARY KEY (`extra_id`),
          KEY `extra_sku` (`extra_sku`),
          KEY `partner_id` (`partner_id`),
          KEY `item_id` (`item_id`),
          KEY `blog_id` (`blog_id`)
        ) ENGINE=InnoDB {$dbTableCharsetCollate};";

        $arrSQL[] = "CREATE TABLE `{$this->conf->getPrefix()}features` (
          `feature_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `feature_title` varchar(255) NOT NULL,
          `display_in_item_list` tinyint(1) unsigned NOT NULL DEFAULT '0',
          `blog_id` int(11) unsigned NOT NULL DEFAULT '0',
          PRIMARY KEY (`feature_id`),
          KEY `display_in_item_list` (`display_in_item_list`),
          KEY `blog_id` (`blog_id`)
        ) ENGINE=InnoDB {$dbTableCharsetCollate};";

        $arrSQL[] = "CREATE TABLE `{$this->conf->getPrefix()}fuel_types` (
          `fuel_type_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `fuel_type_title` varchar(255) NOT NULL,
          `blog_id` int(11) unsigned NOT NULL DEFAULT '0',
          PRIMARY KEY (`fuel_type_id`),
          KEY `blog_id` (`blog_id`)
        ) ENGINE=InnoDB {$dbTableCharsetCollate};";

        $arrSQL[] = "CREATE TABLE `{$this->conf->getPrefix()}invoices` (
          `booking_id` int(11) unsigned NOT NULL,
          `customer_name` varchar(255) NOT NULL,
          `customer_email` varchar(255) NOT NULL,
          `grand_total` varchar(15) NOT NULL DEFAULT '$ 0.00',
          `fixed_deposit_amount` varchar(15) NOT NULL DEFAULT '$ 0.00',
          `total_pay_now` varchar(15) NOT NULL DEFAULT '$ 0.00',
          `total_pay_later` varchar(15) NOT NULL DEFAULT '$ 0.00',
          `pickup_location` varchar(255) NOT NULL,
          `return_location` varchar(255) NOT NULL,
          `invoice` longtext NOT NULL,
          `blog_id` int(11) unsigned NOT NULL DEFAULT '0',
          PRIMARY KEY (`booking_id`),
          KEY `blog_id` (`blog_id`)
        ) ENGINE=InnoDB {$dbTableCharsetCollate};";

        $arrSQL[] = "CREATE TABLE `{$this->conf->getPrefix()}items` (
          `item_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `item_sku` varchar(50) NOT NULL,
          `item_page_id` int(11) unsigned NOT NULL DEFAULT '0',
          `partner_id` int(11) unsigned NOT NULL DEFAULT '0',
          `manufacturer_id` int(11) unsigned NOT NULL DEFAULT '0',
          `body_type_id` int(11) unsigned NOT NULL DEFAULT '0',
          `transmission_type_id` int(11) unsigned NOT NULL DEFAULT '0',
          `fuel_type_id` int(11) unsigned NOT NULL DEFAULT '0',
          `model_name` varchar(255) NOT NULL,
          `item_image_1` varchar(255) NOT NULL,
          `item_image_2` varchar(255) NOT NULL,
          `item_image_3` varchar(255) NOT NULL,
          `demo_item_image_1` tinyint(1) unsigned NOT NULL DEFAULT '0',
          `demo_item_image_2` tinyint(1) unsigned NOT NULL DEFAULT '0',
          `demo_item_image_3` tinyint(1) unsigned NOT NULL DEFAULT '0',
          `mileage` varchar(50) NOT NULL,
          `fuel_consumption` varchar(255) NOT NULL,
          `engine_capacity` varchar(255) NOT NULL,
          `max_passengers` int(11) unsigned NOT NULL DEFAULT '5',
          `max_luggage` int(11) unsigned NOT NULL DEFAULT '2',
          `item_doors` int(11) unsigned NOT NULL DEFAULT '5',
          `min_driver_age` int(11) unsigned NOT NULL DEFAULT '18',
          `price_group_id` int(11) unsigned NOT NULL DEFAULT '0',
          `fixed_rental_deposit` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
          `units_in_stock` int(11) unsigned NOT NULL DEFAULT '1',
          `max_units_per_booking` int(11) unsigned NOT NULL DEFAULT '1',
          `enabled` tinyint(1) unsigned NOT NULL DEFAULT '1',
          `display_in_slider` tinyint(1) unsigned NOT NULL DEFAULT '1',
          `display_in_item_list` tinyint(1) unsigned NOT NULL DEFAULT '1',
          `display_in_price_table` tinyint(1) unsigned NOT NULL DEFAULT '1',
          `display_in_calendar` tinyint(1) unsigned NOT NULL DEFAULT '1',
          `options_display_mode` tinyint(1) unsigned NOT NULL DEFAULT '1',
          `options_measurement_unit` varchar(25) NOT NULL,
          `blog_id` int(11) unsigned NOT NULL DEFAULT '0',
          PRIMARY KEY (`item_id`),
          KEY `display_in_slider` (`display_in_slider`),
          KEY `display_in_price_table` (`display_in_price_table`),
          KEY `item_description_page_id` (`item_page_id`),
          KEY `body_type_id` (`body_type_id`),
          KEY `fuel_type_id` (`fuel_type_id`),
          KEY `transmission_type_id` (`transmission_type_id`),
          KEY `units_in_stock` (`units_in_stock`),
          KEY `max_units_per_booking` (`max_units_per_booking`),
          KEY `enabled` (`enabled`),
          KEY `display_in_item_list` (`display_in_item_list`),
          KEY `display_in_calendar` (`display_in_calendar`),
          KEY `manufacturer_id` (`manufacturer_id`),
          KEY `item_sku` (`item_sku`),
          KEY `partner_id` (`partner_id`),
          KEY `price_group_id` (`price_group_id`),
          KEY `blog_id` (`blog_id`)
        ) ENGINE=InnoDB {$dbTableCharsetCollate};";

        $arrSQL[] = "CREATE TABLE `{$this->conf->getPrefix()}item_features` (
          `item_id` int(11) unsigned NOT NULL DEFAULT '0',
          `feature_id` int(11) unsigned NOT NULL DEFAULT '0',
          `blog_id` int(11) unsigned NOT NULL DEFAULT '0',
          KEY `item_id` (`item_id`),
          KEY `feature_id` (`feature_id`),
          KEY `blog_id` (`blog_id`)
        ) ENGINE=InnoDB {$dbTableCharsetCollate};";

        // Note for indexes
        // As per review we either search for:
        // a) ITEM_ID + (LOCATION_ID & LOCATION_TYPE)
        // b) (LOCATION_ID & LOCATION_TYPE) - that's why we need joined index for 2 columns
        $arrSQL[] = "CREATE TABLE `{$this->conf->getPrefix()}item_locations` (
          `item_id` int(11) unsigned NOT NULL DEFAULT '0',
          `location_id` int(11) unsigned NOT NULL DEFAULT '0',
          `location_type` tinyint(1) unsigned NOT NULL DEFAULT '1',
          `blog_id` int(11) unsigned NOT NULL DEFAULT '0',
          KEY `item_id` (`item_id`),
          KEY `location` (`location_id`,`location_type`),
          KEY `blog_id` (`blog_id`)
        ) ENGINE=InnoDB {$dbTableCharsetCollate};";

        $arrSQL[] = "CREATE TABLE `{$this->conf->getPrefix()}locations` (
          `location_id` int(11) NOT NULL AUTO_INCREMENT,
          `location_code` varchar(50) NOT NULL,
          `location_page_id` int(11) unsigned NOT NULL DEFAULT '0',
          `location_name` varchar(255) NOT NULL,
          `location_image_1` varchar(255) NOT NULL,
          `location_image_2` varchar(255) NOT NULL,
          `location_image_3` varchar(255) NOT NULL,
          `location_image_4` varchar(255) NOT NULL,
          `demo_location_image_1` tinyint(1) unsigned NOT NULL DEFAULT '0',
          `demo_location_image_2` tinyint(1) unsigned NOT NULL DEFAULT '0',
          `demo_location_image_3` tinyint(1) unsigned NOT NULL DEFAULT '0',
          `demo_location_image_4` tinyint(1) unsigned NOT NULL DEFAULT '0',
          `street_address` varchar(255) NOT NULL,
          `city` varchar(64) NOT NULL,
          `state` varchar(128) NOT NULL,
          `zip_code` varchar(64) NOT NULL,
          `country` varchar(64) NOT NULL,
          `phone` varchar(64) NOT NULL,
          `email` varchar(128) NOT NULL,
          `pickup_fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
          `return_fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
          `open_mondays` tinyint(1) unsigned NOT NULL DEFAULT '1',
          `open_tuesdays` tinyint(1) unsigned NOT NULL DEFAULT '1',
          `open_wednesdays` tinyint(1) unsigned NOT NULL DEFAULT '1',
          `open_thursdays` tinyint(1) unsigned NOT NULL DEFAULT '1',
          `open_fridays` tinyint(1) unsigned NOT NULL DEFAULT '1',
          `open_saturdays` tinyint(1) unsigned NOT NULL DEFAULT '1',
          `open_sundays` tinyint(1) unsigned NOT NULL DEFAULT '1',
          `open_time_mon` time NOT NULL DEFAULT '08:00:00',
          `open_time_tue` time NOT NULL DEFAULT '08:00:00',
          `open_time_wed` time NOT NULL DEFAULT '08:00:00',
          `open_time_thu` time NOT NULL DEFAULT '08:00:00',
          `open_time_fri` time NOT NULL DEFAULT '08:00:00',
          `open_time_sat` time NOT NULL DEFAULT '08:00:00',
          `open_time_sun` time NOT NULL DEFAULT '08:00:00',
          `close_time_mon` time NOT NULL DEFAULT '19:00:00',
          `close_time_tue` time NOT NULL DEFAULT '19:00:00',
          `close_time_wed` time NOT NULL DEFAULT '19:00:00',
          `close_time_thu` time NOT NULL DEFAULT '19:00:00',
          `close_time_fri` time NOT NULL DEFAULT '19:00:00',
          `close_time_sat` time NOT NULL DEFAULT '19:00:00',
          `close_time_sun` time NOT NULL DEFAULT '19:00:00',
          `lunch_enabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
          `lunch_start_time` time NOT NULL DEFAULT '12:00:00',
          `lunch_end_time` time NOT NULL DEFAULT '13:00:00',
          `afterhours_pickup_allowed` tinyint(1) unsigned NOT NULL DEFAULT '0',
          `afterhours_pickup_location_id` int(11) unsigned NOT NULL DEFAULT '0',
          `afterhours_pickup_fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
          `afterhours_return_allowed` tinyint(1) unsigned NOT NULL DEFAULT '0',
          `afterhours_return_location_id` int(11) unsigned NOT NULL DEFAULT '0',
          `afterhours_return_fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
          `location_order` int(11) unsigned NOT NULL DEFAULT '1',
          `blog_id` int(11) unsigned NOT NULL DEFAULT '0',
          PRIMARY KEY (`location_id`),
          KEY `afterhours_pickup_location_id` (`afterhours_pickup_location_id`),
          KEY `location_code` (`location_code`),
          KEY `location_page_id` (`location_page_id`),
          KEY `afterhours_pickup_allowed` (`afterhours_pickup_allowed`),
          KEY `afterhours_return_allowed` (`afterhours_return_allowed`),
          KEY `afterhours_return_location_id` (`afterhours_return_location_id`),
          KEY `location_order` (`location_order`),
          KEY `blog_id` (`blog_id`)
        ) ENGINE=InnoDB {$dbTableCharsetCollate};";

        $arrSQL[] = "CREATE TABLE `{$this->conf->getPrefix()}logs` (
          `log_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `log_type` enum('customer-lookup','payment-callback') NOT NULL DEFAULT 'customer-lookup',
          `email` varchar(128) NOT NULL,
          `year` smallint(4) unsigned NOT NULL DEFAULT '0',
          `year_required` tinyint(1) unsigned NOT NULL DEFAULT '0',
          `error_message` text NOT NULL,
          `debug_log` text NOT NULL,
          `ip` varchar(32) NOT NULL DEFAULT '0.0.0.0',
          `real_ip` varchar(32) NOT NULL DEFAULT '0.0.0.0',
          `host` varchar(255) NOT NULL,
          `agent` varchar(255) NOT NULL,
          `browser` varchar(50) NOT NULL,
          `os` varchar(50) NOT NULL,
          `total_requests_left` int(11) NOT NULL DEFAULT '1',
          `failed_requests_left` int(11) NOT NULL DEFAULT '1',
          `email_attempts_left` int(11) NOT NULL DEFAULT '1',
          `is_robot` tinyint(1) unsigned NOT NULL,
          `status` tinyint(1) unsigned NOT NULL DEFAULT '2',
          `log_timestamp` int(11) unsigned NOT NULL DEFAULT '0',
          `blog_id` int(11) unsigned NOT NULL DEFAULT '0',
          PRIMARY KEY (`log_id`),
          KEY `email` (`email`),
          KEY `year` (`year`),
          KEY `year_required` (`year_required`),
          KEY `ip` (`ip`),
          KEY `real_ip` (`real_ip`),
          KEY `is_robot` (`is_robot`),
          KEY `status` (`status`),
          KEY `log_timestamp` (`log_timestamp`),
          KEY `log_type` (`log_type`),
          KEY `blog_id` (`blog_id`)
        ) ENGINE=InnoDB {$dbTableCharsetCollate};";

        $arrSQL[] = "CREATE TABLE `{$this->conf->getPrefix()}manufacturers` (
          `manufacturer_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `manufacturer_title` varchar(255) NOT NULL,
          `manufacturer_logo` varchar(255) NOT NULL,
          `demo_manufacturer_logo` tinyint(1) unsigned NOT NULL DEFAULT '0',
          `blog_id` int(11) unsigned NOT NULL DEFAULT '0',
          PRIMARY KEY (`manufacturer_id`),
          KEY `blog_id` (`blog_id`)
        ) ENGINE=InnoDB {$dbTableCharsetCollate};";

        $arrSQL[] = "CREATE TABLE `{$this->conf->getPrefix()}options` (
          `option_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `item_id` int(11) unsigned NOT NULL DEFAULT '0',
          `extra_id` int(11) unsigned NOT NULL DEFAULT '0',
          `option_name` varchar(255) NOT NULL,
          `blog_id` int(11) unsigned NOT NULL DEFAULT '0',
          PRIMARY KEY (`option_id`),
          KEY `item_id` (`item_id`),
          KEY `extra_id` (`extra_id`),
          KEY `blog_id` (`blog_id`)
        ) ENGINE=InnoDB {$dbTableCharsetCollate};";

        // We should allow expiration_time to be SIGNED, for potential '-1' value for 'never expires' situation
        $arrSQL[] = "CREATE TABLE `{$this->conf->getPrefix()}payment_methods` (
          `payment_method_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `payment_method_code` varchar(50) NOT NULL,
          `class_name` varchar(128) NOT NULL,
          `payment_method_name` varchar(255) NOT NULL,
          `payment_method_email` varchar(128) NOT NULL,
          `payment_method_description` varchar(255) NOT NULL,
          `public_key` varchar(255) NOT NULL,
          `private_key` varchar(255) NOT NULL,
          `sandbox_mode` tinyint(3) unsigned NOT NULL DEFAULT '0',
          `check_certificate` tinyint(1) unsigned NOT NULL DEFAULT '0',
          `ssl_only` tinyint(1) unsigned NOT NULL DEFAULT '0',
          `online_payment` tinyint(1) unsigned NOT NULL DEFAULT '1',
          `payment_method_enabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
          `payment_method_order` int(11) unsigned NOT NULL DEFAULT '0',
          `expiration_time` int(11) NOT NULL DEFAULT '0',
          `blog_id` int(11) unsigned NOT NULL DEFAULT '0',
          PRIMARY KEY (`payment_method_id`),
          KEY `unpaid_booking_expiration_time` (`expiration_time`),
          KEY `method_enabled` (`payment_method_enabled`),
          KEY `method_order` (`payment_method_order`),
          KEY `payment_method_code` (`payment_method_code`),
          KEY `class_name` (`class_name`),
          KEY `online_payment` (`online_payment`),
          KEY `blog_id` (`blog_id`)
        ) ENGINE=InnoDB {$dbTableCharsetCollate};";

        $arrSQL[] = "CREATE TABLE `{$this->conf->getPrefix()}prepayments` (
          `prepayment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `period_from` int(11) unsigned NOT NULL DEFAULT '0',
          `period_till` int(11) unsigned NOT NULL DEFAULT '0',
          `item_prices_included` tinyint(1) unsigned NOT NULL DEFAULT '1',
          `item_deposits_included` tinyint(1) unsigned NOT NULL DEFAULT '0',
          `extra_prices_included` tinyint(1) unsigned NOT NULL DEFAULT '1',
          `extra_deposits_included` tinyint(1) unsigned NOT NULL DEFAULT '0',
          `pickup_fees_included` tinyint(1) unsigned NOT NULL DEFAULT '1',
          `distance_fees_included` tinyint(1) unsigned NOT NULL DEFAULT '1',
          `return_fees_included` tinyint(1) unsigned NOT NULL DEFAULT '1',
          `prepayment_percentage` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
          `blog_id` int(11) unsigned NOT NULL DEFAULT '0',
          PRIMARY KEY (`prepayment_id`),
          KEY `period` (`period_from`,`period_till`),
          KEY `blog_id` (`blog_id`)
        ) ENGINE=InnoDB {$dbTableCharsetCollate};";

        $arrSQL[] = "CREATE TABLE `{$this->conf->getPrefix()}price_groups` (
          `price_group_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `partner_id` int(11) unsigned NOT NULL DEFAULT '0',
          `price_group_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
          `blog_id` int(11) unsigned NOT NULL DEFAULT '0',
          PRIMARY KEY (`price_group_id`),
          KEY `partner_id` (`partner_id`),
          KEY `blog_id` (`blog_id`)
        ) ENGINE=InnoDB {$dbTableCharsetCollate};";

        $arrSQL[] = "CREATE TABLE `{$this->conf->getPrefix()}price_plans` (
          `price_plan_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `price_group_id` int(11) unsigned NOT NULL DEFAULT '0',
          `coupon_code` varchar(50) NOT NULL,
          `start_timestamp` int(11) unsigned NOT NULL DEFAULT '0',
          `end_timestamp` int(11) unsigned NOT NULL DEFAULT '0',
          `daily_rate_mon` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
          `daily_rate_tue` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
          `daily_rate_wed` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
          `daily_rate_thu` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
          `daily_rate_fri` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
          `daily_rate_sat` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
          `daily_rate_sun` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
          `hourly_rate_mon` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
          `hourly_rate_tue` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
          `hourly_rate_wed` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
          `hourly_rate_thu` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
          `hourly_rate_fri` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
          `hourly_rate_sat` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
          `hourly_rate_sun` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
          `seasonal_price` tinyint(1) unsigned NOT NULL DEFAULT '0',
          `blog_id` int(11) unsigned NOT NULL DEFAULT '0',
          PRIMARY KEY (`price_plan_id`),
          KEY `seasonal_price` (`seasonal_price`),
          KEY `period` (`start_timestamp`,`end_timestamp`),
          KEY `price_group_id` (`price_group_id`),
          KEY `coupon_code` (`coupon_code`),
          KEY `blog_id` (`blog_id`)
        ) ENGINE=InnoDB {$dbTableCharsetCollate};";

        $arrSQL[] = "CREATE TABLE `{$this->conf->getPrefix()}settings` (
          `conf_key` varchar(100) NOT NULL,
          `conf_value` varchar(255) NOT NULL,
          `blog_id` int(11) unsigned NOT NULL DEFAULT '0',
          UNIQUE KEY `conf_key` (`conf_key`,`blog_id`)
        ) ENGINE=InnoDB {$dbTableCharsetCollate};";

        $arrSQL[] = "CREATE TABLE `{$this->conf->getPrefix()}taxes` (
          `tax_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `tax_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
          `location_id` int(11) unsigned NOT NULL DEFAULT '0',
          `location_type` tinyint(1) unsigned NOT NULL DEFAULT '1',
          `tax_percentage` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
          `blog_id` int(11) unsigned NOT NULL DEFAULT '0',
          PRIMARY KEY (`tax_id`),
          KEY `location` (`location_id`,`location_type`),
          KEY `blog_id` (`blog_id`)
        ) ENGINE=InnoDB {$dbTableCharsetCollate};";

        $arrSQL[] = "CREATE TABLE `{$this->conf->getPrefix()}transmission_types` (
          `transmission_type_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `transmission_type_title` varchar(255) NOT NULL,
          `blog_id` int(11) unsigned NOT NULL DEFAULT '0',
          PRIMARY KEY (`transmission_type_id`),
          KEY `blog_id` (`blog_id`)
        ) ENGINE=InnoDB {$dbTableCharsetCollate};";

        return $arrSQL;
    }


    /**
     * @note make this call a cache independent
     * @return bool
     */
    public function isInstalled()
    {
        $retIsInstalled = FALSE;
        $tableToCheck = $this->conf->getPrefix().'settings';
        $sqlQuery = "SHOW TABLES LIKE '{$tableToCheck}'";
        $installedResult = $this->conf->getInternalWPDB()->get_var($sqlQuery);

        if(!is_null($installedResult) && $installedResult === $tableToCheck)
        {
            $retIsInstalled = TRUE;
        }

        // DEBUG
        if($this->debugMode)
        {
            $debugMessage = "Debug: isInstalled(): ".($retIsInstalled ? "Yes" : "No")."<br />SQL: {$sqlQuery}<br />Table to check: {$tableToCheck}";
            $this->debugMessages[] = $debugMessage;
            // Do not echo here, as this class is used for ajax
            // echo "<br />".$debugMessage;
        }

        return $retIsInstalled;
    }

    /**
     * @note - This function maintains backwards compatibility to NRS V4.3 and older
     * @return bool
     */
    public function checkBlogIdColumnExists()
    {
        $sqlQuery = "SHOW COLUMNS FROM `{$this->conf->getPrefix()}settings` LIKE 'blog_id'";
        $blogIdColumnResult = $this->conf->getInternalWPDB()->get_var($sqlQuery);
        $blogIdColumnExists = !is_null($blogIdColumnResult) ? TRUE : FALSE;

        return $blogIdColumnExists;
    }

    /**
     * @note - This function maintains backwards compatibility to NRS V4.3 and older
     * @return bool
     */
    public function checkDataExists()
    {
        if($this->checkBlogIdColumnExists())
        {
            // We are testing NRS 5.0 or later database version
            $validBlogId = intval($this->blogId);
            // Note: SELECT 1 is not supported by WordPress, Php, or get_var, so it has to be an exact field name
            $sqlQuery = "SELECT conf_key FROM {$this->conf->getPrefix()}settings WHERE blog_id='{$validBlogId}'";
            $hasSettings = $this->conf->getInternalWPDB()->get_var($sqlQuery, 0, 0);
            // NRS plugins is installed or not for this blog_id
            $retExists = !is_null($hasSettings) ? TRUE : FALSE;
        } else
        {
            // We are testing NRS 4.3 or earlier database version when blog_id column did not yet existed
            // Note: SELECT 1 is not supported by WordPress, Php, or get_var, so it has to be an exact field name
            $sqlQuery = "SELECT conf_key FROM {$this->conf->getPrefix()}settings WHERE 1";
            $hasSettings = $this->conf->getInternalWPDB()->get_var($sqlQuery, 0, 0);
            // Old plugin version is installed or not for this blog_id
            $retExists = !is_null($hasSettings) ? TRUE : FALSE;
        }

        // DEBUG
        if($this->debugMode)
        {
            $debugMessage = "Debug: checkDataExists(): ".($retExists ? "Yes" : "No")."<br />SQL: {$sqlQuery}<br />";
            $this->debugMessages[] = $debugMessage;
            // Do not echo here, as this class is used for ajax
            // echo "<br />".$debugMessage;
        }

        return $retExists;
    }


    /**
     * Is the NRS database version is newer or same as code version. If no - launch updater
     * @note make sure the blog id here is ok for network
     * @return bool
     */
    public function isDatabaseVersionUpToDate()
    {
        // NOTE: The default 3.2 version number here is ok, because it defines the case of older plugin versions,
        // when plugin version data was not saved to db
        $databaseVersion = 3.2;

        if($this->checkBlogIdColumnExists())
        {
            // We are testing NRS 5.0 or later database version
            $validBlogId = intval($this->blogId);
            $sql = "
                    SELECT conf_value AS plugin_version
                    FROM {$this->conf->getPrefix()}settings
                    WHERE conf_key='conf_plugin_version' AND blog_id='{$validBlogId}'
                ";
            $databaseVersionResult = $this->conf->getInternalWPDB()->get_var($sql);
            if(!is_null($databaseVersionResult))
            {
                $databaseVersion = floatval($databaseVersionResult);
            }
        } else
        {
            // We are testing NRS 4.3 or earlier database version when blog_id column did not yet existed
            $sql = "
				SELECT conf_value AS plugin_version
				FROM {$this->conf->getPrefix()}settings
				WHERE conf_key='conf_plugin_version'
			";
            $databaseVersionResult = $this->conf->getInternalWPDB()->get_var($sql);
            if(!is_null($databaseVersionResult))
            {
                $databaseVersion = floatval($databaseVersionResult);
            }
        }
        $codeVersion = $this->conf->getVersion();

        // DEBUG
        //echo "DB VERSION: {$databaseVersion}<br />";
        //echo "CODE VERSION: {$codeVersion}<br />";

        return $databaseVersion >= $codeVersion ? TRUE : FALSE;
    }

    public function createTables()
    {
        $created = TRUE;
        foreach($this->getTableCreateSQLs() AS $sqlQuery)
        {
            $ok = $this->conf->getInternalWPDB()->query($sqlQuery);
            if($ok === FALSE)
            {
                $created = FALSE;

                $startIdentifier = '`'.$this->conf->getPrefix();
                $endIdentifier = '`';
                $startCharPosOfTableName = strpos($sqlQuery, $startIdentifier) + strlen($startIdentifier);
                $tableLength = strpos($sqlQuery, $endIdentifier, $startCharPosOfTableName) - $startCharPosOfTableName;
                $tableName = '';
                if($startCharPosOfTableName > 0 && $tableLength > 0)
                {
                    $tableName = substr($sqlQuery, $startCharPosOfTableName, $tableLength);
                }
                $this->errorMessages[] = sprintf($this->lang->getText('NRS_INSTALL_QUERY_FAILED_FOR_EXTENSION_TABLE_CREATE_ERROR_TEXT'), $this->blogId, $tableName);
                if($this->debugMode)
                {
                    $debugMessage = "FAILED AT CREATE EXTENSION TABLE QUERY:<br />".nl2br($sqlQuery);
                    $this->debugMessages[] = $debugMessage;
                    // Do not echo here, as this class is used in redirect
                    //echo "<br />".$debugMessage;
                }
            }
        }

        return $created;
    }

    public function dropTables()
    {
        $dropped = TRUE;
        $arrAllTables = $this->getTables();
        if(sizeof($arrAllTables))
        {
            $tableNames = implode(', ',$arrAllTables);
            $tablesSql = '`'.$this->conf->getPrefix().implode('`, `'.$this->conf->getPrefix(),$arrAllTables).'`';
            $sqlQuery = "DROP TABLE IF EXISTS {$tablesSql};";
            $ok = $this->conf->getInternalWPDB()->query($sqlQuery);
            if($ok === FALSE)
            {
                $dropped = FALSE;
                $this->errorMessages[] = sprintf($this->lang->getText('NRS_INSTALL_QUERY_FAILED_FOR_EXTENSION_TABLE_DROP_ERROR_TEXT'), $this->blogId, $tableNames);
                if($this->debugMode)
                {
                    $debugMessage = "FAILED AT DROP EXTENSION TABLES QUERY:<br />".nl2br($sqlQuery);
                    $this->debugMessages[] = $debugMessage;
                    // Do not echo here, as this class is used in redirect
                    //echo "<br />".$debugMessage;
                }
            }
        }

        return $dropped;
    }

    /**
     * Insert all content
     * @param $paramSQLFileNameWithPath
     * @return bool
     */
    public function insertContent($paramSQLFileNameWithPath)
    {
        $validBlogId = intval($this->blogId);
        // Language file already loaded, so we can use translated text

        // Create terms of use page post object
        $arrCancellationPage = array(
            'post_title'    => $this->lang->getText('NRS_INSTALL_DEFAULT_CANCELLED_PAYMENT_PAGE_TITLE_TEXT'),
            'post_content'  => $this->lang->getText('NRS_INSTALL_DEFAULT_CANCELLED_PAYMENT_PAGE_CONTENT_TEXT'),
            'post_status'   => 'publish',
            'post_type'     => $this->conf->getExtensionPrefix().'page',
            /*'post_author'   => 1,*/ /*auto assign current user*/
            /*'post_category' => array(8,39)*/ /*no categories needed here*/
        );

        // Create terms of use page post object
        $arrConfirmationPage = array(
            'post_title'    => $this->lang->getText('NRS_INSTALL_DEFAULT_CONFIRMATION_PAGE_TITLE_TEXT'),
            'post_content'  => $this->lang->getText('NRS_INSTALL_DEFAULT_CONFIRMATION_PAGE_CONTENT_TEXT'),
            'post_status'   => 'publish',
            'post_type'     => $this->conf->getExtensionPrefix().'page',
            /*'post_author'   => 1,*/ /*auto assign current user*/
            /*'post_category' => array(8,39)*/ /*no categories needed here*/
        );

        // Create terms of use page post object
        $arrTermsAndConditionPage = array(
            'post_title'    => $this->lang->getText('NRS_INSTALL_DEFAULT_TERMS_AND_CONDITIONS_PAGE_TITLE_TEXT'),
            'post_content'  => $this->lang->getText('NRS_INSTALL_DEFAULT_TERMS_AND_CONDITIONS_PAGE_CONTENT_TEXT'),
            'post_status'   => 'publish',
            'post_type'     => $this->conf->getExtensionPrefix().'page',
            /*'post_author'   => 1,*/ /*auto assign current user*/
            /*'post_category' => array(8,39)*/ /*no categories needed here*/
        );

        // Insert corresponding page posts
        $newCancelledPaymentPageId = wp_insert_post($arrCancellationPage, FALSE);
        $newConfirmationPageId = wp_insert_post($arrConfirmationPage, FALSE);
        $newTermsAndConditionsPageId = wp_insert_post($arrTermsAndConditionPage, FALSE);


        // Create company location page post object
        // Note: do not set it's content now, as it we will get new location id later
        $arrCompanyLocationPage = array(
            'post_title'    => $this->lang->getText('NRS_INSTALL_DEFAULT_COMPANY_NAME_TEXT'),
            'post_content'  => '',
            'post_status'   => 'publish',
            'post_type'     => $this->conf->getExtensionPrefix().'location',
            /*'post_author'   => 1,*/ /*auto assign current user*/
            /*'post_category' => array(8,39)*/ /*no categories needed here*/
        );

        // Insert corresponding location posts
        $newCompanyLocationPageId = wp_insert_post($arrCompanyLocationPage, FALSE);

        // Get next invoice id (booking_id is ok here)
        $sqlQuery = "SELECT booking_id FROM `{$this->conf->getPrefix()}invoices` ORDER BY booking_id DESC LIMIT 1";
        $invoiceIdResult = $this->conf->getInternalWPDB()->get_var($sqlQuery);
        $nextInvoiceId = !is_null($invoiceIdResult) ? $invoiceIdResult+1 : 0;

        // Insert SQL
        $inserted = TRUE;
        // If importable demo file is provided and it's file is readable
        if($paramSQLFileNameWithPath != '' && is_readable($paramSQLFileNameWithPath))
        {
            // Clean the values
            $arrInsertSQL = array();
            $arrExtensionInsertSQL = array();

            // Fill the values
            require ($paramSQLFileNameWithPath);

            // Insert data to WP tables
            foreach($arrInsertSQL AS $sqlTable => $sqlData)
            {
                $sqlQuery = "INSERT INTO `{$this->conf->getBlogPrefix($this->blogId)}{$sqlTable}` {$sqlData}";
                $ok = $this->conf->getInternalWPDB()->query($sqlQuery);
                if($ok === FALSE)
                {
                    $inserted = FALSE;
                    $this->errorMessages[] = sprintf($this->lang->getText('NRS_INSTALL_QUERY_FAILED_FOR_WP_TABLE_INSERT_ERROR_TEXT'), $this->blogId, $sqlTable);
                    if($this->debugMode)
                    {
                        $debugMessage = "INSERT FAILED TO WP TABLE FOR QUERY: ".nl2br($sqlQuery);
                        $this->debugMessages[] = $debugMessage;
                        // Do not echo here, as it is used for ajax
                        //echo "<br />".$debugMessage;
                    }
                }
            }

            // Parse shortcodes and make SQL queries
            foreach($arrExtensionInsertSQL AS $sqlTable => $sqlData)
            {
                $sqlData = $this->parseBBCodes(
                    $sqlData, $newCancelledPaymentPageId, $newConfirmationPageId, $newTermsAndConditionsPageId, $newCompanyLocationPageId, $nextInvoiceId
                );

                // Note: we don't use blog_id param for getPrefix, as it is always the same
                $sqlQuery = "INSERT INTO `{$this->conf->getPrefix()}{$sqlTable}` {$sqlData}";
                $ok = $this->conf->getInternalWPDB()->query($sqlQuery);
                if($ok === FALSE)
                {
                    $inserted = FALSE;
                    $this->errorMessages[] = sprintf($this->lang->getText('NRS_INSTALL_QUERY_FAILED_FOR_EXTENSION_TABLE_INSERT_ERROR_TEXT'), $this->blogId, $sqlTable);
                    if($this->debugMode)
                    {
                        $debugMessage = "INSERT FAILED TO EXTENSION TABLE FOR QUERY: ".nl2br($sqlQuery);
                        $this->debugMessages[] = $debugMessage;
                        // Do not echo here, as it is used for ajax
                        //echo "<br />".$debugMessage;
                    }
                }
            }
        }

        /* *************************** WP POSTS PART: START *************************** */
        $sqlQuery = "SELECT location_id FROM `{$this->conf->getPrefix()}locations` WHERE blog_id='{$validBlogId}' ORDER BY location_id DESC LIMIT 1";
        $locationIdResult = $this->conf->getInternalWPDB()->get_var($sqlQuery);
        $newLocationId = !is_null($locationIdResult) ? $locationIdResult : 0;
        // Create search page post object
        $arrSearchPage = array(
            'post_title'    => '',
            'post_name'     => $this->lang->getText('NRS_INSTALL_DEFAULT_SEARCH_PAGE_URL_SLUG_TEXT'),
            'post_content'  => wp_filter_kses('['.$this->conf->getShortcode().' display="search" steps="form,list,list,table,table"]'),
            'post_status'   => 'publish',
            'post_type'     => $this->conf->getExtensionPrefix().'page',
            /*'post_author'   => 1,*/ /*auto assign current user*/
            /*'post_category' => array(8,39)*/ /*no categories needed here*/
        );
        $newSearchPageId = wp_insert_post($arrSearchPage, FALSE);

        // Create post object
        $wpLocationPage = array(
            'ID'            => $newLocationId,
            // content now will be updated and escaped securely
            'post_content'  => wp_filter_kses(
                '['.$this->conf->getShortcode().' display="location" location="'.$newLocationId.'"]
['.$this->conf->getShortcode().' display="search" location="'.$newLocationId.'" action_page="'.$newSearchPageId.'" steps="form,list,list,table,table"]'
            ),
        );

        // Update corresponding post as post type 'EXTENSION_PREFIX_location'
        wp_update_post($wpLocationPage);
        /* *************************** WP POSTS PART: END ***************************  */

        if($inserted === FALSE)
        {
            $this->errorMessages[] = sprintf($this->lang->getText('NRS_INSTALL_INSERT_ERROR_TEXT'), $this->blogId);
        } else
        {
            $this->okayMessages[] = sprintf($this->lang->getText('NRS_INSTALL_INSERTED_TEXT'), $this->blogId);
        }

        return $inserted;
    }

    /**
     * Replace special content
     * @note1 - fires every time when plugin is enabled, or enabled->disabled->enabled, etc.
     * @note2 - used mostly to set image dimensions right
     * @param $paramSQLFileNameWithPath
     * @return bool
     */
    public function resetContent($paramSQLFileNameWithPath)
    {
        // Replace SQL
        $replaced = TRUE;
        // If importable demo file is provided and it's file is readable
        if($paramSQLFileNameWithPath != '' && is_readable($paramSQLFileNameWithPath))
        {
            // Clean the values
            $arrReplaceSQL = array();
            $arrExtensionReplaceSQL = array();

            // Fill the values
            require ($paramSQLFileNameWithPath);

            // Replace data to WP tables
            foreach($arrReplaceSQL AS $sqlTable => $sqlData)
            {
                // Note - MySQL 'REPLACE INTO' works like MySQL 'INSERT INTO', except that if there is a row
                // with the same key you are trying to insert, it will be deleted on replace instead of giving you an error.
                $sqlQuery = "REPLACE INTO `{$this->conf->getBlogPrefix($this->blogId)}{$sqlTable}` {$sqlData}";
                $ok = $this->conf->getInternalWPDB()->query($sqlQuery);
                if($ok === FALSE)
                {
                    $replaced = FALSE;
                    $this->errorMessages[] = sprintf($this->lang->getText('NRS_INSTALL_QUERY_FAILED_FOR_WP_TABLE_REPLACE_ERROR_TEXT'), $this->blogId, $sqlTable);
                    if($this->debugMode)
                    {
                        $debugMessage = "REPLACE FAILED TO WP TABLE FOR QUERY: ".nl2br($sqlQuery);
                        $this->debugMessages[] = $debugMessage;
                        // Do not echo here, as it is used for ajax
                        //echo "<br />".$debugMessage;
                    }
                }
            }

            // Parse shortcodes and make SQL queries
            foreach($arrExtensionReplaceSQL AS $sqlTable => $sqlData)
            {
                $sqlData = str_replace('[BLOG_ID]', intval($this->blogId), $sqlData);
                // Note: we don't use blog_id param for getPrefix, as it is always the same
                $sqlQuery = "REPLACE INTO `{$this->conf->getPrefix()}{$sqlTable}` {$sqlData}";
                $ok = $this->conf->getInternalWPDB()->query($sqlQuery);

                if($ok === FALSE)
                {
                    $replaced = FALSE;
                    $this->errorMessages[] = sprintf($this->lang->getText('NRS_INSTALL_QUERY_FAILED_FOR_EXTENSION_TABLE_REPLACE_ERROR_TEXT'), $this->blogId, $sqlTable);
                    if($this->debugMode)
                    {
                        $debugMessage = "REPLACE FAILED TO EXTENSION TABLE FOR QUERY: ".nl2br($sqlQuery);
                        $this->debugMessages[] = $debugMessage;
                        // Do not echo here, as it is used for ajax
                        //echo "<br />".$debugMessage;
                    }
                }
            }
        }

        if($replaced === FALSE)
        {
            $this->errorMessages[] = sprintf($this->lang->getText('NRS_INSTALL_REPLACE_ERROR_TEXT'), $this->blogId);
        } else
        {
            $this->okayMessages[] = sprintf($this->lang->getText('NRS_INSTALL_REPLACED_TEXT'), $this->blogId);
        }

        return $replaced;
    }

    /**
     * @return bool
     */
    public function deleteContent()
    {
        $validBlogId = intval($this->blogId);

        // Clear all tables for specific blog id
        $deleted = TRUE;
        foreach($this->getTables() AS $sqlTable)
        {
            $sqlQuery = "DELETE FROM {$this->conf->getPrefix()}".$sqlTable." WHERE blog_id='{$validBlogId}'";
            $ok = $this->conf->getInternalWPDB()->query($sqlQuery);
            if($ok === FALSE)
            {
                $deleted = FALSE;
                $this->errorMessages[] = sprintf($this->lang->getText('NRS_INSTALL_QUERY_FAILED_FOR_EXTENSION_TABLE_DELETE_ERROR_TEXT'), $this->blogId, $sqlTable);
                if($this->debugMode)
                {
                    $debugMessage = "DELETE FAILED TO EXTENSION TABLE FOR QUERY: ".nl2br($sqlQuery);
                    $this->debugMessages[] = $debugMessage;
                    // Do not echo here, as it is used for ajax
                    //echo "<br />".$debugMessage;
                }
            }
        }

        // Delete all NRS page posts
        $pagePosts = get_posts(array('posts_per_page' => -1, 'post_type' => $this->conf->getExtensionPrefix().'page'));
        foreach ($pagePosts AS $pagePost)
        {
            $ok = wp_delete_post( $pagePost->ID, TRUE);
            if($ok === FALSE)
            {
                $deleted = FALSE;
                // We don't want to have a fatal crash here, only a debug message, so we comment-out the error message
                //$this->errorMessages[] = sprintf($this->lang->getText('NRS_INSTALL_QUERY_FAILED_FOR_WP_TABLE_DELETE_ERROR_TEXT'), $this->blogId, $tableName);
                if($this->debugMode)
                {
                    $debugMessage = "DELETE FAILED TO WP POSTS TABLE [post_type=".$this->conf->getExtensionPrefix()."page]";
                    $this->debugMessages[] = $debugMessage;
                    // Do not echo here, as it is used for ajax
                    //echo "<br />".$debugMessage;
                }
            }
        }

        // Delete all NRS item posts
        $itemPosts = get_posts(array('posts_per_page' => -1, 'post_type' => $this->conf->getExtensionPrefix().'item'));
        foreach ($itemPosts AS $itemPost)
        {
            $ok = wp_delete_post($itemPost->ID, TRUE);
            if($ok === FALSE)
            {
                $deleted = FALSE;
                // We don't want to have a fatal crash here, only a debug message, so we comment-out the error message
                //$tableName = 'posts[post_type=&#39;'.$this->conf->getExtensionPrefix().'item&#39;]';
                //$this->errorMessages[] = sprintf($this->lang->getText('NRS_INSTALL_QUERY_FAILED_FOR_WP_TABLE_DELETE_ERROR_TEXT'), $this->blogId, $tableName);
                if($this->debugMode)
                {
                    $debugMessage = "DELETE FAILED TO WP POSTS TABLE [post_type=".$this->conf->getExtensionPrefix()."item]";
                    $this->debugMessages[] = $debugMessage;
                    // Do not echo here, as it is used for ajax
                    //echo "<br />".$debugMessage;
                }
            }
        }

        // Delete all NRS location posts
        $locationPosts = get_posts(array('posts_per_page' => -1, 'post_type' => $this->conf->getExtensionPrefix().'location'));
        foreach ($locationPosts AS $locationPost)
        {
            $ok = wp_delete_post($locationPost->ID, TRUE);
            if($ok === FALSE)
            {
                $deleted = FALSE;
                // We don't want to have a fatal crash here, only a debug message, so we comment-out the error message
                //$tableName = 'posts[post_type=&#39;'.$this->conf->getExtensionPrefix().'location&#39;]';
                //$this->errorMessages[] = sprintf($this->lang->getText('NRS_INSTALL_QUERY_FAILED_FOR_WP_TABLE_DELETE_ERROR_TEXT'), $this->blogId, $tableName);
                if($this->debugMode)
                {
                    $debugMessage = "DELETE FAILED TO WP POSTS TABLE [post_type=".$this->conf->getExtensionPrefix()."location]";
                    $this->debugMessages[] = $debugMessage;
                    // Do not echo here, as it is used for ajax
                    //echo "<br />".$debugMessage;
                }
            }
        }

        return $deleted;
    }

    /**
     * No parametrization here
     * @param string $trustedText
     * @param int (optional) $paramCancelledPaymentPageId
     * @param int (optional) $paramConfirmationPageId
     * @param int (optional) $paramTermsOfUsePageId
     * @param int (optional) $paramCompanyLocationPageId
     * @param int (optional) $paramNextInvoiceId
     * @return mixed
     */
    private function parseBBCodes(
        $trustedText, $paramCancelledPaymentPageId = 0, $paramConfirmationPageId = 0, $paramTermsOfUsePageId = 0,
        $paramCompanyLocationPageId = 0, $paramNextInvoiceId = 0
    ) {
        $validBlogId = intval($this->blogId);
        $pluginVersion = number_format(floatval($this->conf->getVersion()), 1,'.','');
        $validCancelledPaymentPageId = intval($paramCancelledPaymentPageId);
        $validConfirmationPageId = intval($paramConfirmationPageId);
        $validTermsOfUsePageId = intval($paramTermsOfUsePageId);
        $validCompanyLocationPageId = intval($paramCompanyLocationPageId);
        $validNextInvoiceId = intval($paramNextInvoiceId);

        $arrFrom = array(
            '[BLOG_ID]',
            '[PLUGIN_VERSION]',
            '[CANCELLED_PAYMENT_PAGE_ID]', '[CONFIRMATION_PAGE_ID]', '[TERMS_AND_CONDITIONS_PAGE_ID]',
            '[COMPANY_LOCATION_PAGE_ID]',
            '[NEXT_INVOICE_ID]',
            '[NRS_INSTALL_DEFAULT_COMPANY_NAME_TEXT]',
            '[NRS_INSTALL_DEFAULT_ITEM_URL_SLUG_TEXT]',
            '[NRS_INSTALL_DEFAULT_LOCATION_URL_SLUG_TEXT]',
            '[NRS_INSTALL_DEFAULT_PAGE_URL_SLUG_TEXT]',

            '[NRS_INSTALL_DEFAULT_COMPANY_STREET_ADDRESS_TEXT]',
            '[NRS_INSTALL_DEFAULT_COMPANY_CITY_TEXT]',
            '[NRS_INSTALL_DEFAULT_COMPANY_STATE_TEXT]',
            '[NRS_INSTALL_DEFAULT_COMPANY_ZIP_CODE_TEXT]',
            '[NRS_INSTALL_DEFAULT_COMPANY_COUNTRY_TEXT]',
            '[NRS_INSTALL_DEFAULT_COMPANY_PHONE_TEXT]',
            '[NRS_INSTALL_DEFAULT_COMPANY_EMAIL_TEXT]',

            '[NRS_INSTALL_DEFAULT_PAYMENT_METHOD_PAYPAL_TEXT]',
            '[NRS_INSTALL_DEFAULT_PAYMENT_METHOD_PAYPAL_DETAILS_TEXT]',
            '[NRS_INSTALL_DEFAULT_PAYMENT_METHOD_STRIPE_TEXT]',
            '[NRS_INSTALL_DEFAULT_PAYMENT_METHOD_BANK_TEXT]',
            '[NRS_INSTALL_DEFAULT_PAYMENT_METHOD_BANK_DETAILS_TEXT]',
            '[NRS_INSTALL_DEFAULT_PAYMENT_METHOD_PAY_OVER_THE_PHONE_TEXT]',
            '[NRS_INSTALL_DEFAULT_PAYMENT_METHOD_PAY_ON_ARRIVAL_TEXT]',
            '[NRS_INSTALL_DEFAULT_PAYMENT_METHOD_PAY_ON_ARRIVAL_DETAILS_TEXT]',

            '[NRS_INSTALL_DEFAULT_DEAR_TEXT]',
            '[NRS_INSTALL_DEFAULT_REGARDS_TEXT]',
            '[NRS_INSTALL_DEFAULT_EMAIL_TITLE_BOOKING_DETAILS_TEXT]',
            '[NRS_INSTALL_DEFAULT_EMAIL_TITLE_BOOKING_CONFIRMED_TEXT]',
            '[NRS_INSTALL_DEFAULT_EMAIL_TITLE_BOOKING_CANCELLED_TEXT]',
            '[NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_TITLE_BOOKING_DETAILS_TEXT]',
            '[NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_TITLE_BOOKING_CONFIRMED_TEXT]',
            '[NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_TITLE_BOOKING_CANCELLED_TEXT]',

            '[NRS_INSTALL_DEFAULT_EMAIL_BODY_BOOKING_RECEIVED_TEXT]',
            '[NRS_INSTALL_DEFAULT_EMAIL_BODY_BOOKING_DETAILS_TEXT]',
            '[NRS_INSTALL_DEFAULT_EMAIL_BODY_PAYMENT_RECEIVED_TEXT]',
            '[NRS_INSTALL_DEFAULT_EMAIL_BODY_BOOKING_CANCELLED_TEXT]',
            '[NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_BODY_BOOKING_RECEIVED_TEXT]',
            '[NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_BODY_BOOKING_DETAILS_TEXT]',
            '[NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_BODY_BOOKING_PAID_TEXT]',
            '[NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_BODY_BOOKING_CANCELLED_TEXT]',
            '[NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_BODY_CANCELLED_BOOKING_DETAILS_TEXT]',

            '[NRS_TAX_TEXT]',
        );
        $arrTo = array(
            $validBlogId,
            $pluginVersion,
            $validCancelledPaymentPageId, $validConfirmationPageId, $validTermsOfUsePageId,
            $validCompanyLocationPageId,
            $validNextInvoiceId,
            $this->lang->getText('NRS_INSTALL_DEFAULT_COMPANY_NAME_TEXT'),
            $this->lang->getText('NRS_INSTALL_DEFAULT_ITEM_URL_SLUG_TEXT'),
            $this->lang->getText('NRS_INSTALL_DEFAULT_LOCATION_URL_SLUG_TEXT'),
            $this->lang->getText('NRS_INSTALL_DEFAULT_PAGE_URL_SLUG_TEXT'),

            $this->lang->getText('NRS_INSTALL_DEFAULT_COMPANY_STREET_ADDRESS_TEXT'),
            $this->lang->getText('NRS_INSTALL_DEFAULT_COMPANY_CITY_TEXT'),
            $this->lang->getText('NRS_INSTALL_DEFAULT_COMPANY_STATE_TEXT'),
            $this->lang->getText('NRS_INSTALL_DEFAULT_COMPANY_ZIP_CODE_TEXT'),
            $this->lang->getText('NRS_INSTALL_DEFAULT_COMPANY_COUNTRY_TEXT'),
            $this->lang->getText('NRS_INSTALL_DEFAULT_COMPANY_PHONE_TEXT'),
            $this->lang->getText('NRS_INSTALL_DEFAULT_COMPANY_EMAIL_TEXT'),

            $this->lang->getText('NRS_INSTALL_DEFAULT_PAYMENT_METHOD_PAYPAL_TEXT'),
            $this->lang->getText('NRS_INSTALL_DEFAULT_PAYMENT_METHOD_PAYPAL_DESCRIPTION_TEXT'),
            $this->lang->getText('NRS_INSTALL_DEFAULT_PAYMENT_METHOD_STRIPE_TEXT'),
            $this->lang->getText('NRS_INSTALL_DEFAULT_PAYMENT_METHOD_BANK_TEXT'),
            $this->lang->getText('NRS_INSTALL_DEFAULT_PAYMENT_METHOD_BANK_DETAILS_TEXT'),
            $this->lang->getText('NRS_INSTALL_DEFAULT_PAYMENT_METHOD_PAY_OVER_THE_PHONE_TEXT'),
            $this->lang->getText('NRS_INSTALL_DEFAULT_PAYMENT_METHOD_PAY_ON_ARRIVAL_TEXT'),
            $this->lang->getText('NRS_INSTALL_DEFAULT_PAYMENT_METHOD_PAY_ON_ARRIVAL_DETAILS_TEXT'),

            $this->lang->getText('NRS_INSTALL_DEFAULT_DEAR_TEXT'),
            $this->lang->getText('NRS_INSTALL_DEFAULT_REGARDS_TEXT'),
            $this->lang->getText('NRS_INSTALL_DEFAULT_EMAIL_TITLE_BOOKING_DETAILS_TEXT'),
            $this->lang->getText('NRS_INSTALL_DEFAULT_EMAIL_TITLE_BOOKING_CONFIRMED_TEXT'),
            $this->lang->getText('NRS_INSTALL_DEFAULT_EMAIL_TITLE_BOOKING_CANCELLED_TEXT'),
            $this->lang->getText('NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_TITLE_BOOKING_DETAILS_TEXT'),
            $this->lang->getText('NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_TITLE_BOOKING_CONFIRMED_TEXT'),
            $this->lang->getText('NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_TITLE_BOOKING_CANCELLED_TEXT'),

            $this->lang->getText('NRS_INSTALL_DEFAULT_EMAIL_BODY_BOOKING_RECEIVED_TEXT'),
            $this->lang->getText('NRS_INSTALL_DEFAULT_EMAIL_BODY_BOOKING_DETAILS_TEXT'),
            $this->lang->getText('NRS_INSTALL_DEFAULT_EMAIL_BODY_PAYMENT_RECEIVED_TEXT'),
            $this->lang->getText('NRS_INSTALL_DEFAULT_EMAIL_BODY_BOOKING_CANCELLED_TEXT'),
            $this->lang->getText('NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_BODY_BOOKING_RECEIVED_TEXT'),
            $this->lang->getText('NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_BODY_BOOKING_DETAILS_TEXT'),
            $this->lang->getText('NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_BODY_BOOKING_PAID_TEXT'),
            $this->lang->getText('NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_BODY_BOOKING_CANCELLED_TEXT'),
            $this->lang->getText('NRS_INSTALL_DEFAULT_NOTIFICATION_EMAIL_BODY_CANCELLED_BOOKING_DETAILS_TEXT'),

            $this->lang->getText('NRS_TAX_TEXT'),
        );
        $updatedText = str_replace($arrFrom, $arrTo, $trustedText);

        return $updatedText;
    }
}