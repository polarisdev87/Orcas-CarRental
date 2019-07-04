<?php
/**
 * Demo import manager

 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Update;
use NativeRentalSystem\Models\AbstractElement;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\iPrimitive;
use NativeRentalSystem\Models\Language\Language;

class DatabaseUpdate extends AbstractElement implements iPrimitive
{
    protected $conf 	        = NULL;
    protected $lang 		    = NULL;
    protected $debugMode 	    = 0; // 0 - off, 1 - standard, 2 - deep debug
    protected $blogId           = 0;

    // NOTE: The 3.2 version number here is ok, because it defines the case of older plugin versions,
    // when plugin version data was not saved to db
    protected $version          = 3.2;
    protected $internalCounter  = 0;

    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, $paramBlogId)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;

        $this->blogId = intval($paramBlogId);
        // Reset internal counter and use it class-wide to count all queries processed (but maybe not executed)
        $this->internalCounter = 0;

        // Set database version
        $this->setVersion();
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    public function getId()
    {
        return $this->blogId;
    }

    /**
     * @note - This function maintains backwards compatibility to NRS V4.3 and older
     */
    private function setVersion()
    {
        // NOTE: The default 3.2 version number here is ok, because it defines the case of older plugin versions,
        // when plugin version data was not saved to db
        $databaseVersion = 3.2;
        $doOldFormatCheck = FALSE;

        $sqlQuery = "SHOW COLUMNS FROM `{$this->conf->getPrefix()}settings` LIKE 'blog_id'";
        $blogIdColumnResult = $this->conf->getInternalWPDB()->get_var($sqlQuery);

        // As version is not yet set, we use blog column to check
        if(!is_null($blogIdColumnResult))
        {
            // We are testing NRS 5.0 or later database version
            $validBlogId = intval($this->blogId);
            $sqlQuery = "
				SELECT conf_value AS plugin_version
				FROM {$this->conf->getPrefix()}settings
				WHERE conf_key='conf_plugin_version' AND blog_id='{$validBlogId}'
			";
            $databaseVersionResult = $this->conf->getInternalWPDB()->get_var($sqlQuery);
            if(!is_null($databaseVersionResult))
            {
                $databaseVersion = floatval($databaseVersionResult);
            } else
            {
                $doOldFormatCheck = TRUE;
            }
        } else
        {
            $doOldFormatCheck = TRUE;
        }
        if($doOldFormatCheck)
        {
            // We are testing NRS 4.3 or earlier database version when blog_id column did not yet existed
            $sqlQuery = "
				SELECT conf_value AS plugin_version
				FROM {$this->conf->getPrefix()}settings
				WHERE conf_key='conf_plugin_version'
			";
            $databaseVersionResult = $this->conf->getInternalWPDB()->get_var($sqlQuery);
            if(!is_null($databaseVersionResult))
            {
                // Avoid of V5 instance without updating getting passed as V4
                if(floatval($databaseVersionResult) < 5.0)
                {
                    $databaseVersion = floatval($databaseVersionResult);
                }
            }
        }

        $this->version = $databaseVersion;

        if($this->debugMode)
        {
            $debugMessage = "DB VERSION: {$databaseVersion}";
            $this->debugMessages[] = $debugMessage;
            // Do not echo here, as this class is used in redirect
            //echo "<br />".$debugMessage;
        }

        return $databaseVersion;
    }

    public function getVersion()
    {
        return $this->version;
    }

    /**
     * This method for internal use only
     * @note - This function maintains backwards compatibility to NRS V4.3 and older
     * @param $paramNewValue
     * @return int
     */
    private function setCounter($paramNewValue)
    {
        $updated = FALSE;
        $validValue = $paramNewValue > 0 ? intval($paramNewValue) : 0;
        if($this->version >= 5.0)
        {
            // We are testing NRS 5.0 or later database version
            $validBlogId = intval($this->blogId);
            $sqlQuery = "
				UPDATE {$this->conf->getPrefix()}settings SET conf_value='{$validValue}'
				WHERE conf_key='conf_updated' AND blog_id='{$validBlogId}'
			";
            $ok = $this->conf->getInternalWPDB()->get_var($sqlQuery);
            if($ok !== FALSE)
            {
                $updated = TRUE;
            }
        } else
        {
            // We are testing NRS 4.3 or earlier database version when blog_id column did not yet existed
            $sqlQuery = "
				UPDATE {$this->conf->getPrefix()}settings SET conf_value='{$validValue}'
				WHERE conf_key='conf_updated'
			";
            $ok = $this->conf->getInternalWPDB()->get_var($sqlQuery);
            if($ok !== FALSE)
            {
                $updated = TRUE;
            }
        }

        if($this->debugMode == 2)
        {
            if($updated === FALSE)
            {
                $debugMessage = '<span style="font-weight:bold;color: red;">FAILED</span> TO SET DB UPDATE COUNTER TO: '.$validValue;
            } else
            {
                $debugMessage = 'DB UPDATE COUNTER SET TO: '.$validValue;
            }
            $this->debugMessages[] = $debugMessage;
            // Do not echo here, as this class is used in redirect
            //echo "<br />".$debugMessage;
        }

        return $updated;
    }

    /**
     * This method for internal use only
     * @note - This function maintains backwards compatibility to NRS V4.3 and older
     */
    private function getCounter()
    {
        // If that is not the newest version, then for sure the database update counter is 0
        $retUpdateCounter = 0;
        if($this->version >= 5.0)
        {
            // We are testing NRS 5.0 or later database version
            $validBlogId = intval($this->blogId);
            $sqlQuery = "
				SELECT conf_value AS data_updated
				FROM {$this->conf->getPrefix()}settings
				WHERE conf_key='conf_updated' AND blog_id='{$validBlogId}'
			";
            $dbUpdateCounterValue = $this->conf->getInternalWPDB()->get_var($sqlQuery);
            if(!is_null($dbUpdateCounterValue) && $dbUpdateCounterValue > 0)
            {
                $retUpdateCounter = intval($dbUpdateCounterValue);
            }
        } else
        {
            // We are testing NRS 4.3 or earlier database version when blog_id column did not yet existed
            $sqlQuery = "
				SELECT conf_value AS plugin_version
				FROM {$this->conf->getPrefix()}settings
				WHERE conf_key='conf_updated'
			";
            $dbUpdateCounterValue = $this->conf->getInternalWPDB()->get_var($sqlQuery);
            if(!is_null($dbUpdateCounterValue) && $dbUpdateCounterValue > 0)
            {
                $retUpdateCounter = intval($dbUpdateCounterValue);
            }
        }

        if($this->debugMode)
        {
            $debugMessage = "GOT CURRENT DB UPDATE COUNTER: {$retUpdateCounter}";
            $this->debugMessages[] = $debugMessage;
            // Do not echo here, as this class is used in redirect
            //echo "<br />".$debugMessage;
        }

        return $retUpdateCounter;
    }

    /**
     * Check if this is a root update, i.e. from V4 to V5 etc., not V5 to V5.1
     * @param $paramCodeVersion
     * @return bool
     */
    public function isMajorUpgrade($paramCodeVersion)
    {
        $majorUpgrade = FALSE;
        if(intval($this->version) < intval($paramCodeVersion))
        {
            $majorUpgrade = TRUE;
        }
        return $majorUpgrade;
    }

    public function isUpToDate($paramCodeVersion)
    {
        return $this->version == $paramCodeVersion ? TRUE : FALSE;
    }

    public function canUpdate($paramCodeVersion)
    {
        $canUpdate = FALSE;
        if($this->version >= 4.3 && floatval($this->version) < floatval($paramCodeVersion))
        {
            $canUpdate = TRUE;
        }
        return $canUpdate;
    }

    /**
     * SQL for early database altering from V4.3 to V5.0 and newer
     * @return bool
     */
    public function alter_4_3_DatabaseEarlyStructureTo_5_0()
    {
        $arrSQL = array();

        // Get DB tables charset and collate
        $dbTableCharsetCollate = $this->conf->getInternalWPDB()->get_charset_collate();

        // 1. Drop outdated indexes first
        $arrSQL[] = "ALTER TABLE `{$this->conf->getPrefix()}bookings` DROP INDEX `dropoff_timestamp`;";
        $arrSQL[] = "ALTER TABLE `{$this->conf->getPrefix()}locations` DROP INDEX `afterhours_dropoff_location_id`;";
        $arrSQL[] = "ALTER TABLE `{$this->conf->getPrefix()}payment_methods` DROP INDEX `payment_method_code`;";
        $arrSQL[] = "ALTER TABLE `{$this->conf->getPrefix()}settings` DROP INDEX `conf_key`;";


        // 2. Create new tables
        $arrSQL[] = "DROP TABLE IF EXISTS `{$this->conf->getPrefix()}benefits`;";
        $arrSQL[] = "CREATE TABLE IF NOT EXISTS `{$this->conf->getPrefix()}benefits` (
              `benefit_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
              `benefit_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
              `benefit_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
              `demo_benefit_image` tinyint(1) unsigned NOT NULL DEFAULT '0',
              `benefit_order` INT( 11 ) UNSIGNED NOT NULL DEFAULT '1',
              `blog_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
              PRIMARY KEY (`benefit_id`),
              KEY `benefit_order` (`benefit_order`),
              KEY `blog_id` (`blog_id`)
            ) ENGINE=InnoDB {$dbTableCharsetCollate};";

        $arrSQL[] = "DROP TABLE IF EXISTS `{$this->conf->getPrefix()}distances`;";
        $arrSQL[] = "CREATE TABLE IF NOT EXISTS `{$this->conf->getPrefix()}distances` (
              `distance_id` INT( 11 ) unsigned NOT NULL AUTO_INCREMENT,
              `pickup_location_id` INT( 11 ) unsigned NOT NULL DEFAULT '0',
              `return_location_id` INT( 11 ) unsigned NOT NULL DEFAULT '0',
              `show_distance` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '1',
              `distance` DECIMAL( 10, 1 ) unsigned NOT NULL DEFAULT '0.0',
              `distance_fee` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0.00',
              `blog_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
              PRIMARY KEY ( `distance_id` ),
              KEY `pickup_location_id` ( `pickup_location_id` ),
              KEY `return_location_id` ( `return_location_id` ),
              KEY `blog_id` ( `blog_id` )
            ) ENGINE=InnoDB {$dbTableCharsetCollate};";

        $arrSQL[] = "DROP TABLE IF EXISTS `{$this->conf->getPrefix()}price_groups`;";
        $arrSQL[] = "CREATE TABLE IF NOT EXISTS `{$this->conf->getPrefix()}price_groups` (
              `price_group_id` INT( 11 ) UNSIGNED UNSIGNED NOT NULL AUTO_INCREMENT,
              `partner_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
              `price_group_name` VARCHAR( 255 ) NOT NULL,
              `blog_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
              PRIMARY KEY ( `price_group_id` ),
              KEY `partner_id` ( `partner_id` ),
              KEY `blog_id` ( `blog_id` )
            ) ENGINE=InnoDB {$dbTableCharsetCollate};";

        $arrSQL[] = "DROP TABLE IF EXISTS `{$this->conf->getPrefix()}taxes`;";
        $arrSQL[] = "CREATE TABLE IF NOT EXISTS `{$this->conf->getPrefix()}taxes` (
              `tax_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT,
              `tax_name` VARCHAR( 100 ) NOT NULL,
              `location_id` INT( 11 ) unsigned NOT NULL DEFAULT '0',
              `location_type` TINYINT( 1 ) unsigned NOT NULL DEFAULT '1',
              `tax_percentage` DECIMAL( 10, 2 ) unsigned NOT NULL DEFAULT '0.00',
              `blog_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
              PRIMARY KEY ( `tax_id` ),
              KEY `location` ( `location_id`, `location_type` ),
              KEY `blog_id` ( `blog_id` )
            ) ENGINE=InnoDB {$dbTableCharsetCollate};";


        // 3. Rename existing tables
        $arrSQL[] = "DROP TABLE IF EXISTS `{$this->conf->getPrefix()}emails`;";
        $arrSQL[] = "RENAME TABLE `{$this->conf->getPrefix()}email_contents` TO `{$this->conf->getPrefix()}emails`;";
        $arrSQL[] = "DROP TABLE IF EXISTS `{$this->conf->getPrefix()}logs`;";
        $arrSQL[] = "RENAME TABLE `{$this->conf->getPrefix()}api_log` TO `{$this->conf->getPrefix()}logs`;";


        // 4. Modify existing tables
        $arrSQL[] = "ALTER TABLE `{$this->conf->getPrefix()}body_types`
            ADD `body_type_order` INT( 11 ) UNSIGNED NOT NULL DEFAULT '1' AFTER `body_type_title`,
            ADD `blog_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
            ADD INDEX ( `body_type_order` ),
            ADD INDEX ( `blog_id` );";

        $arrSQL[] = "ALTER TABLE `{$this->conf->getPrefix()}bookings`
            CHANGE `body_type_id` `body_type_id` INT( 11 ) NOT NULL DEFAULT '-1',
            CHANGE `transmission_type_id` `transmission_type_id` INT( 11 ) NOT NULL DEFAULT '-1',
            CHANGE `fuel_type_id` `fuel_type_id` INT( 11 ) NOT NULL DEFAULT '-1',
            CHANGE `dropoff_timestamp` `return_timestamp` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
            CHANGE `dropoff_location_id` `return_location_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
            CHANGE `payment_success` `payment_successful` TINYINT( 1 ) NOT NULL DEFAULT '0',
            CHANGE `payment_txnid` `payment_transaction_id` VARCHAR( 100 ) NULL DEFAULT NULL,
            CHANGE `paypal_email` `payer_email` VARCHAR( 255 ) NULL DEFAULT NULL,
            ADD `coupon_code` VARCHAR( 50 ) NOT NULL AFTER `booking_code`,
            ADD `pickup_location_code` VARCHAR( 50 ) NOT NULL AFTER `pickup_location_id` ,
            ADD `return_location_code` VARCHAR( 50 ) NOT NULL AFTER `pickup_location_code` ,
            ADD `partner_id` INT( 11 ) NOT NULL DEFAULT '-1' AFTER `return_location_code`,
            ADD `manufacturer_id` INT( 11 ) NOT NULL DEFAULT '-1' AFTER `partner_id`,
            ADD `is_completed_early` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `is_cancelled`,
            ADD `blog_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
            ADD INDEX ( `coupon_code` ),
            ADD INDEX ( `return_timestamp` ),
            ADD INDEX ( `pickup_location_code` ),
            ADD INDEX ( `return_location_code` ),
            ADD INDEX ( `is_completed_early` ),
            ADD INDEX ( `blog_id` );";

        $arrSQL[] = "ALTER TABLE `{$this->conf->getPrefix()}booking_options`
            ADD `item_sku` VARCHAR( 50 ) NOT NULL AFTER `item_id`,
            ADD `extra_sku` VARCHAR( 50 ) NOT NULL AFTER `extra_id`,
            ADD `blog_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
            ADD INDEX ( `item_sku` ),
            ADD INDEX ( `extra_sku` ),
            ADD INDEX ( `blog_id` );";

        $arrSQL[] = "ALTER TABLE `{$this->conf->getPrefix()}closed_dates`
            ADD `location_code` VARCHAR( 50 ) NOT NULL ,
            ADD `blog_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
            ADD INDEX ( `location_code` ),
            ADD INDEX ( `blog_id` );";

        // Customers table may have thousands of rows, so we split this query to three separate queries
        $arrSQL[] = "ALTER TABLE `{$this->conf->getPrefix()}customers`
            ADD `blog_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
            ADD INDEX ( `blog_id` );";
        $arrSQL[] = "ALTER TABLE `{$this->conf->getPrefix()}customers`
            CHANGE `zipcode` `zip_code` VARCHAR( 64 ) NOT NULL;";
        $arrSQL[] = "ALTER TABLE `{$this->conf->getPrefix()}customers`
            CHANGE `additional_comments` `comments` TEXT NOT NULL;";

        $arrSQL[] = "ALTER TABLE `{$this->conf->getPrefix()}discounts`
            CHANGE `discount_type` `discount_type` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '1',
            ADD `coupon_discount` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `discount_type`,
            ADD `price_plan_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `coupon_discount`,
            ADD `blog_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
            ADD INDEX ( `coupon_discount` ),
            ADD INDEX ( `price_plan_id` ),
            ADD INDEX ( `blog_id` );";

        $arrSQL[] = "ALTER TABLE `{$this->conf->getPrefix()}emails`
            ADD `email_type` TINYINT( 2 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `email_id` ,
            ADD `blog_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
            ADD INDEX ( `email_type` ),
            ADD INDEX ( `blog_id` );";

        $arrSQL[] = "ALTER TABLE `{$this->conf->getPrefix()}extras`
            CHANGE `fixed_rental_deposit` `fixed_rental_deposit` DECIMAL( 10, 2 ) NOT NULL DEFAULT '0.00',
            CHANGE `measurement_unit` `options_measurement_unit` VARCHAR( 25 ) NOT NULL,
            ADD `extra_sku` VARCHAR( 50 ) NOT NULL AFTER `extra_id` ,
            ADD `partner_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `extra_sku` ,
            ADD `item_id` INT( 11 ) unsigned NOT NULL DEFAULT '0' AFTER `partner_id` ,
            ADD `blog_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
            ADD INDEX ( `extra_sku` ),
            ADD INDEX ( `partner_id` ),
            ADD INDEX ( `item_id` ),
            ADD INDEX ( `blog_id` );";

        $arrSQL[] = "ALTER TABLE `{$this->conf->getPrefix()}features`
            ADD `blog_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
            ADD INDEX ( `blog_id` );";

        $arrSQL[] = "ALTER TABLE `{$this->conf->getPrefix()}fuel_types`
            ADD `blog_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
            ADD INDEX ( `blog_id` );";

        $arrSQL[] = "ALTER TABLE `{$this->conf->getPrefix()}items`
            CHANGE `item_description_page_id` `item_page_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
            CHANGE `demo_image_1` `demo_item_image_1` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0',
            CHANGE `demo_image_2` `demo_item_image_2` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0',
            CHANGE `demo_image_3` `demo_item_image_3` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0',
            CHANGE `measurement_unit` `options_measurement_unit` VARCHAR( 25 ) NOT NULL,
            ADD `item_sku` VARCHAR( 50 ) NOT NULL AFTER `item_id` ,
            ADD `partner_id` INT( 11 ) unsigned NOT NULL DEFAULT '0' AFTER `item_page_id`,
            ADD `price_group_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `min_driver_age` ,
            ADD `fixed_rental_deposit` DECIMAL( 10, 2 ) UNSIGNED NOT NULL DEFAULT '0.00' AFTER `price_group_id`,
            ADD `blog_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
            ADD INDEX ( `item_sku` ),
            ADD INDEX ( `partner_id` ),
            ADD INDEX ( `price_group_id` ),
            ADD INDEX ( `blog_id` );";

        $arrSQL[] = "ALTER TABLE `{$this->conf->getPrefix()}item_features`
            ADD `blog_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
            ADD INDEX ( `blog_id` );";

        $arrSQL[] = "ALTER TABLE `{$this->conf->getPrefix()}item_locations`
            ADD `blog_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
            ADD INDEX ( `blog_id` );";

        $arrSQL[] = "ALTER TABLE `{$this->conf->getPrefix()}invoices`
            CHANGE `grand_total` `grand_total` VARCHAR( 15 ) NOT NULL DEFAULT '$ 0.00',
            CHANGE `fixed_deposit_amount` `fixed_deposit_amount` VARCHAR( 15 ) NOT NULL DEFAULT '$ 0.00',
            CHANGE `total_pay_now` `total_pay_now` VARCHAR( 15 ) NOT NULL DEFAULT '$ 0.00',
            CHANGE `dropoff_location` `return_location` VARCHAR( 255 ) NOT NULL,
            ADD `total_pay_later` VARCHAR( 15 ) NOT NULL DEFAULT '$ 0.00' AFTER `total_pay_now`,
            ADD `blog_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
            ADD INDEX ( `blog_id` );";

        $arrSQL[] = "ALTER TABLE `{$this->conf->getPrefix()}locations`
            CHANGE `location_title` `location_name` VARCHAR( 255 ) NOT NULL,
            CHANGE `location_address` `street_address` VARCHAR( 255 ) NOT NULL,
            CHANGE `dropoff_fee` `return_fee` DECIMAL( 10, 2 ) UNSIGNED NOT NULL DEFAULT '0.00',
            CHANGE `afterhours_dropoff_location_id` `afterhours_return_location_id` INT( 11 ) NOT NULL DEFAULT '0',
            CHANGE `afterhours_dropoff_fee` `afterhours_return_fee` DECIMAL( 10, 2 ) UNSIGNED NOT NULL DEFAULT '0.00',
            ADD `location_code` VARCHAR( 50 ) NOT NULL AFTER `location_id` ,
            ADD `location_page_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `location_code`,
            ADD `location_image_1` VARCHAR( 255 ) NOT NULL AFTER `location_name` ,
            ADD `location_image_2` VARCHAR( 255 ) NOT NULL AFTER `location_image_1` ,
            ADD `location_image_3` VARCHAR( 255 ) NOT NULL AFTER `location_image_2` ,
            ADD `location_image_4` VARCHAR( 255 ) NOT NULL AFTER `location_image_3` ,
            ADD `demo_location_image_1` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `location_image_4` ,
            ADD `demo_location_image_2` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `demo_location_image_1`,
            ADD `demo_location_image_3` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `demo_location_image_2`,
            ADD `demo_location_image_4` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `demo_location_image_3`,
            ADD `city` VARCHAR( 64 ) NOT NULL AFTER `street_address` ,
            ADD `state` VARCHAR( 128 ) NOT NULL AFTER `city` ,
            ADD `zip_code` VARCHAR( 64 ) NOT NULL AFTER `state` ,
            ADD `country` VARCHAR( 64 ) NOT NULL AFTER `zip_code` ,
            ADD `phone` VARCHAR( 64 ) NOT NULL AFTER `country` ,
            ADD `email` VARCHAR( 128 ) NOT NULL AFTER `phone`,
            ADD `lunch_enabled` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `close_time_sun` ,
            ADD `lunch_start_time` TIME NOT NULL DEFAULT '12:00:00' AFTER `lunch_enabled` ,
            ADD `lunch_end_time` TIME NOT NULL DEFAULT '13:00:00' AFTER `lunch_start_time`,
            ADD `afterhours_pickup_allowed` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `lunch_end_time`,
            ADD `afterhours_return_allowed` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `afterhours_pickup_fee`,
            ADD `location_order` INT( 11 ) UNSIGNED NOT NULL DEFAULT '1' AFTER `afterhours_return_fee` ,
            ADD `blog_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
            ADD INDEX ( `location_code` ),
            ADD INDEX ( `location_page_id` ),
            ADD INDEX ( `afterhours_pickup_allowed` ),
            ADD INDEX ( `afterhours_return_allowed` ),
            ADD INDEX ( `afterhours_return_location_id` ),
            ADD INDEX ( `location_order` ),
            ADD INDEX ( `blog_id` );";

        $arrSQL[] = "ALTER TABLE `{$this->conf->getPrefix()}logs`
            ADD `log_type` ENUM( 'customer-lookup', 'payment-callback' ) NOT NULL DEFAULT 'customer-lookup' AFTER `log_id` ,
            ADD `error_message` TEXT NOT NULL AFTER `year_required` ,
            ADD `debug_log` TEXT NOT NULL AFTER `error_message`,
            ADD `blog_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
            ADD INDEX ( `log_type` ),
            ADD INDEX ( `blog_id` );";

        $arrSQL[] = "ALTER TABLE `{$this->conf->getPrefix()}manufacturers`
            ADD `manufacturer_logo` VARCHAR( 255 ) NOT NULL ,
            ADD `demo_manufacturer_logo` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0',
            ADD `blog_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
            ADD INDEX ( `blog_id` );";

        $arrSQL[] = "ALTER TABLE `{$this->conf->getPrefix()}options`
            ADD `blog_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
            ADD INDEX ( `blog_id` );";

        $arrSQL[] = "ALTER TABLE `{$this->conf->getPrefix()}payment_methods`
            CHANGE `payment_method_code` `payment_method_code` VARCHAR( 50 ) NOT NULL,
            CHANGE `method_name` `payment_method_name` VARCHAR( 255 ) NOT NULL ,
            CHANGE `method_details` `payment_method_description` VARCHAR( 255 ) NOT NULL ,
            CHANGE `method_enabled` `payment_method_enabled` TINYINT( 1 ) unsigned NOT NULL DEFAULT '0',
            CHANGE `method_order` `payment_method_order` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
            CHANGE `unpaid_booking_expiration_time` `expiration_time` INT( 11 ) NOT NULL DEFAULT '0',
            ADD `payment_method_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST,
            ADD `class_name` VARCHAR( 128 ) NOT NULL AFTER `payment_method_code`,
            ADD `payment_method_email` VARCHAR( 128 ) NOT NULL AFTER `payment_method_name`,
            ADD `public_key` VARCHAR( 255 ) NOT NULL AFTER `payment_method_description`,
            ADD `private_key` VARCHAR( 255 ) NOT NULL AFTER `public_key`,
            ADD `sandbox_mode` TINYINT UNSIGNED NOT NULL DEFAULT '0' AFTER `private_key`,
            ADD `check_certificate` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `sandbox_mode`,
            ADD `ssl_only` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `check_certificate`,
            ADD `online_payment` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '1' AFTER `ssl_only`,
            ADD `blog_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
            ADD INDEX ( `payment_method_code` ),
            ADD INDEX ( `class_name` ),
            ADD INDEX ( `online_payment` ),
            ADD INDEX ( `blog_id` );";

        $arrSQL[] = "ALTER TABLE `{$this->conf->getPrefix()}prepayments`
            ADD `item_prices_included` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '1' AFTER `period_till`,
            ADD `item_deposits_included` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `item_prices_included`,
            ADD `extra_prices_included` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '1' AFTER `item_deposits_included`,
            ADD `extra_deposits_included` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `extra_prices_included`,
            ADD `pickup_fees_included` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '1' AFTER `extra_deposits_included`,
            ADD `distance_fees_included` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '1' AFTER `pickup_fees_included`,
            ADD `return_fees_included` TINYINT( 1 ) UNSIGNED NOT NULL DEFAULT '1' AFTER `distance_fees_included`,
            ADD `blog_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
            ADD INDEX ( `blog_id` );";

        // So some why on MariaDB (MySQL-type clone) servers the single price-plan query crashes,
        // so we separate index adding and new index columns creating and other columns add/change
        // with primary key to separate queries. And appears that makes it work
        $arrSQL[] = "ALTER TABLE `{$this->conf->getPrefix()}price_plans`
            CHANGE `plan_id` `price_plan_id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT,
            CHANGE `mon` `daily_rate_mon` DECIMAL( 10, 2 ) UNSIGNED NOT NULL DEFAULT '0.00',
            CHANGE `tue` `daily_rate_tue` DECIMAL( 10, 2 ) UNSIGNED NOT NULL DEFAULT '0.00',
            CHANGE `wed` `daily_rate_wed` DECIMAL( 10, 2 ) UNSIGNED NOT NULL DEFAULT '0.00',
            CHANGE `thu` `daily_rate_thu` DECIMAL( 10, 2 ) UNSIGNED NOT NULL DEFAULT '0.00',
            CHANGE `fri` `daily_rate_fri` DECIMAL( 10, 2 ) UNSIGNED NOT NULL DEFAULT '0.00',
            CHANGE `sat` `daily_rate_sat` DECIMAL( 10, 2 ) UNSIGNED NOT NULL DEFAULT '0.00',
            CHANGE `sun` `daily_rate_sun` DECIMAL( 10, 2 ) UNSIGNED NOT NULL DEFAULT '0.00',
            ADD `hourly_rate_mon` DECIMAL( 10, 2 ) UNSIGNED NOT NULL DEFAULT '0.00' AFTER `daily_rate_sun`,
            ADD `hourly_rate_tue` DECIMAL( 10, 2 ) UNSIGNED NOT NULL DEFAULT '0.00' AFTER `hourly_rate_mon`,
            ADD `hourly_rate_wed` DECIMAL( 10, 2 ) UNSIGNED NOT NULL DEFAULT '0.00' AFTER `hourly_rate_tue`,
            ADD `hourly_rate_thu` DECIMAL( 10, 2 ) UNSIGNED NOT NULL DEFAULT '0.00' AFTER `hourly_rate_wed`,
            ADD `hourly_rate_fri` DECIMAL( 10, 2 ) UNSIGNED NOT NULL DEFAULT '0.00' AFTER `hourly_rate_thu`,
            ADD `hourly_rate_sat` DECIMAL( 10, 2 ) UNSIGNED NOT NULL DEFAULT '0.00' AFTER `hourly_rate_fri`,
            ADD `hourly_rate_sun` DECIMAL( 10, 2 ) UNSIGNED NOT NULL DEFAULT '0.00' AFTER `hourly_rate_sat`;";
        $arrSQL[] = "ALTER TABLE `{$this->conf->getPrefix()}price_plans`
            ADD `price_group_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0' AFTER `price_plan_id`,
            ADD `coupon_code` VARCHAR( 50 ) NOT NULL AFTER `price_group_id` ,
            ADD `blog_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
            ADD INDEX ( `price_group_id` ),
            ADD INDEX ( `coupon_code` ),
            ADD INDEX ( `blog_id` );";

        // The UNIQUE KEY bellow is ok, because we know, that there were no network-enabled support before NRS 5.0
        $arrSQL[] = "ALTER TABLE `{$this->conf->getPrefix()}settings`
            ADD `blog_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
            ADD UNIQUE (`conf_key` ,`blog_id`)";

        $arrSQL[] = "ALTER TABLE `{$this->conf->getPrefix()}transmission_types`
            ADD `blog_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
            ADD INDEX ( `blog_id` );";

        $altered = $this->executeQueries($arrSQL);
        if($altered === FALSE)
        {
            $this->errorMessages[] = sprintf($this->lang->getText('NRS_DATABASE_UPDATE_EARLY_STRUCTURE_ALTER_ERROR_TEXT'), $this->blogId);
        } else
        {
            $this->okayMessages[] = sprintf($this->lang->getText('NRS_DATABASE_UPDATE_EARLY_STRUCTURE_ALTERED_TEXT'), $this->blogId);
        }

        return $altered;
    }

    /**
     * The very first action for NRS tables - is to set blog id to current blog for all tables
     * @note - We don't need to care about blog_id here, as we know there was no network-enable support in NRS 4.3
     * @return bool
     */
    private function updateBlogIdsFor_5_0()
    {
        $validBlogId = intval($this->blogId);
        $arrSQL = array();
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}benefits` SET blog_id='{$validBlogId}' WHERE 1";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}body_types` SET blog_id='{$validBlogId}' WHERE 1";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}bookings` SET blog_id='{$validBlogId}' WHERE 1";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}booking_options` SET blog_id='{$validBlogId}' WHERE 1";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}closed_dates` SET blog_id='{$validBlogId}' WHERE 1";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}customers` SET blog_id='{$validBlogId}' WHERE 1";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}discounts` SET blog_id='{$validBlogId}' WHERE 1";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}distances` SET blog_id='{$validBlogId}' WHERE 1";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}emails` SET blog_id='{$validBlogId}' WHERE 1";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}extras` SET blog_id='{$validBlogId}' WHERE 1";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}features` SET blog_id='{$validBlogId}' WHERE 1";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}fuel_types` SET blog_id='{$validBlogId}' WHERE 1";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}invoices` SET blog_id='{$validBlogId}' WHERE 1";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}items` SET blog_id='{$validBlogId}' WHERE 1";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}item_features` SET blog_id='{$validBlogId}' WHERE 1";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}item_locations` SET blog_id='{$validBlogId}' WHERE 1";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}locations` SET blog_id='{$validBlogId}' WHERE 1";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}logs` SET blog_id='{$validBlogId}' WHERE 1";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}manufacturers` SET blog_id='{$validBlogId}' WHERE 1";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}options` SET blog_id='{$validBlogId}' WHERE 1";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}payment_methods` SET blog_id='{$validBlogId}' WHERE 1";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}prepayments` SET blog_id='{$validBlogId}' WHERE 1";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}price_groups` SET blog_id='{$validBlogId}' WHERE 1";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}price_plans` SET blog_id='{$validBlogId}' WHERE 1";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}settings` SET blog_id='{$validBlogId}' WHERE 1";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}taxes` SET blog_id='{$validBlogId}' WHERE 1";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}transmission_types` SET blog_id='{$validBlogId}' WHERE 1";

        // Update it here (this is a must to continue)
        $blogIdsUpdated = $this->executeQueries($arrSQL);

        return $blogIdsUpdated;
    }

    /**
     * SQL for updating from V4.3 to V5.0 and newer
     * @return bool
     */
    public function update_4_3_DatabaseDataTo_5_0()
    {
        $arrSQL = array();
        $validBlogId = intval($this->blogId);
        $validPaymentMethodPayPalDescription = $this->lang->getText('NRS_INSTALL_DEFAULT_PAYMENT_METHOD_PAYPAL_DESCRIPTION_TEXT');
        $validPaymentMethodStripeName = $this->lang->getText('NRS_INSTALL_DEFAULT_PAYMENT_METHOD_STRIPE_TEXT');
        $validSettingItemURLSlug = esc_sql(sanitize_text_field($this->lang->getText('NRS_INSTALL_DEFAULT_ITEM_URL_SLUG_TEXT')));
        $validSettingLocationURLSlug = esc_sql(sanitize_text_field($this->lang->getText('NRS_INSTALL_DEFAULT_LOCATION_URL_SLUG_TEXT')));
        $validSettingPageURLSlug = esc_sql(sanitize_text_field($this->lang->getText('NRS_INSTALL_DEFAULT_PAGE_URL_SLUG_TEXT')));
        $validTaxName = esc_sql(sanitize_text_field($this->lang->getText('NRS_TAX_TEXT')));

        //////////////////////////////////////////////////////////////////////////////////////////////////////
        // 5. First - update blog ids
        //////////////////////////////////////////////////////////////////////////////////////////////////////
        $blogIdsUpdated = $this->updateBlogIdsFor_5_0();

        //////////////////////////////////////////////////////////////////////////////////////////////////////
        // 6. Non-NRS table changes - Create search page
        //////////////////////////////////////////////////////////////////////////////////////////////////////
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

        // Insert corresponding page post
        $validNewSearchPageId = wp_insert_post($arrSearchPage, FALSE);

        //////////////////////////////////////////////////////////////////////////////////////////////////////
        // 7. Get current currency symbol
        //////////////////////////////////////////////////////////////////////////////////////////////////////
        $sql = "SELECT conf_value AS currency_symbol
            FROM {$this->conf->getPrefix()}settings
            WHERE conf_key='conf_currency_symbol' AND blog_id='{$validBlogId}'";
        $currencySymbolResult = $this->conf->getInternalWPDB()->get_var($sql);
        $validCurrentCurrencySymbol = '';
        if(!is_null($currencySymbolResult))
        {
            $validCurrentCurrencySymbol = htmlentities(sanitize_text_field(stripslashes($currencySymbolResult)), ENT_COMPAT, 'utf-8');
        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////
        // 8. Update existing tables data or add new
        //////////////////////////////////////////////////////////////////////////////////////////////////////
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}bookings`
            SET payment_method_code='paypal'
            WHERE payment_method_code='pp' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}bookings`
            SET payment_method_code='pay-at-pickup'
            WHERE payment_method_code='poa' AND blog_id='{$validBlogId}'";

        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}bookings`
            SET pickup_location_code = CONCAT('LO_', pickup_location_id)
            WHERE pickup_location_id > 0 AND blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}bookings`
            SET return_location_code = CONCAT('LO_', return_location_id)
            WHERE return_location_id > 0 AND blog_id='{$validBlogId}'";

        // Set body type, transmission type & fuel type id's to '-1' (all) where is was before equal to '0'
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}bookings`
            SET body_type_id='-1'
            WHERE body_type_id='0' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}bookings`
            SET transmission_type_id='-1'
            WHERE transmission_type_id='0' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}bookings`
            SET fuel_type_id='-1'
            WHERE fuel_type_id='0' AND blog_id='{$validBlogId}'";

        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}booking_options`
            SET item_sku = CONCAT('IT_', item_id)
            WHERE item_id > 0 AND blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}booking_options`
            SET extra_sku = CONCAT('EX_', extra_id)
            WHERE extra_id > 0 AND blog_id='{$validBlogId}'";

        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}discounts` d
            JOIN `{$this->conf->getPrefix()}price_plans` pp ON pp.item_id=d.item_id AND pp.price_type='1'
            SET d.price_plan_id = pp.price_plan_id
            WHERE d.item_id > 0 AND d.blog_id='{$validBlogId}'";

        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}emails`
            SET email_type=email_id
            WHERE blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}emails`
            SET email_subject = REPLACE (email_subject, '[PORTAL_URL]', '[SITE_URL]'),
            email_body = REPLACE (email_body, '[PORTAL_URL]', '[SITE_URL]')
            WHERE blog_id='{$validBlogId}'";

        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}emails`
            SET email_body = REPLACE (email_body, '[PORTAL_NAME]', '[COMPANY_NAME]')
            WHERE blog_id='{$validBlogId}'";

        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}emails`
            SET email_body = REPLACE (email_body, '[PORTAL_PHONE]', '[COMPANY_PHONE]')
            WHERE blog_id='{$validBlogId}'";

        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}emails`
            SET email_body = REPLACE (email_body, '[PORTAL_EMAIL]', '[COMPANY_EMAIL]')
            WHERE blog_id='{$validBlogId}'";

        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}extras`
            SET extra_sku = CONCAT('EX_', extra_id)
            WHERE blog_id='{$validBlogId}'";

        // First - adopt the new field properly
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}invoices`
            SET total_pay_later = CAST(grand_total AS DECIMAL(10, 2))- CAST(total_pay_now AS DECIMAL(10, 2))
            WHERE blog_id='{$validBlogId}'";

        // Add the prefix for invoice numbers (as it got transformed to string now)
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}invoices` SET
            grand_total = CONCAT('{$validCurrentCurrencySymbol} ', grand_total),
            fixed_deposit_amount = CONCAT('{$validCurrentCurrencySymbol} ', fixed_deposit_amount),
            total_pay_now = CONCAT('{$validCurrentCurrencySymbol} ', total_pay_now),
            total_pay_later = CONCAT('{$validCurrentCurrencySymbol} ', total_pay_later)
            WHERE blog_id='{$validBlogId}'";

        $arrSQL[] = "UPDATE {$this->conf->getPrefix()}items it 
            INNER JOIN {$this->conf->getPrefix()}deposits dep ON it.item_id = dep.item_id  
            SET it.fixed_rental_deposit = dep.fixed_rental_deposit
            WHERE it.blog_id='{$validBlogId}'";

        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}items`
            SET price_group_id = item_id, item_sku = CONCAT('IT_', item_id)
            WHERE blog_id='{$validBlogId}'";

        // Update items demo images - individual item images
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}items`
            SET item_image_1='car_peugeot-207.jpg'
            WHERE item_id='1' AND demo_item_image_1='1' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}items`
            SET item_image_1='car_suzuki-alto.jpg'
            WHERE item_id='2' AND demo_item_image_1='1' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}items`
            SET item_image_1='car_opel-vivaro.jpg'
            WHERE item_id='3' AND demo_item_image_1='1' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}items`
            SET item_image_1='car_peugeot-boxer.jpg'
            WHERE item_id='4' AND demo_item_image_1='1' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}items`
            SET item_image_1='car_audi-a6.jpg'
            WHERE item_id='5' AND demo_item_image_1='1' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}items`
            SET item_image_1='car_citroen-c5.jpg'
            WHERE item_id='6' AND demo_item_image_1='1' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}items`
            SET item_image_1='car_opel-astra-sport-tourer.jpg'
            WHERE item_id='8' AND demo_item_image_1='1' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}items`
            SET item_image_1='car_opel-insignia.jpg'
            WHERE item_id='9' AND demo_item_image_1='1' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}items`
            SET item_image_1='car_mazda-6.jpg'
            WHERE item_id='10' AND demo_item_image_1='1' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}items`
            SET item_image_1='car_mercedes-ml350.jpg'
            WHERE item_id='12' AND demo_item_image_1='1' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}items`
            SET item_image_1='car_nissan-qashqai.jpg'
            WHERE item_id='13' AND demo_item_image_1='1' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}items`
            SET item_image_1='car_ford-fiesta.jpg'
            WHERE item_id='14' AND demo_item_image_1='1' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}items`
            SET item_image_1='car_nissan-qashqai+2.jpg'
            WHERE item_id='15' AND demo_item_image_1='1' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}items`
            SET item_image_1='car_kia-ceed.jpg'
            WHERE item_id='16' AND demo_item_image_1='1' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}items`
            SET item_image_1='car_vw-touareg.jpg'
            WHERE item_id='17' AND demo_item_image_1='1' AND blog_id='{$validBlogId}'";
        // Update items demo images - standard item images
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}items`
            SET item_image_2='car_interior.jpg'
            WHERE demo_item_image_2='1' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}items`
            SET item_image_3='car_boot.jpg'
            WHERE demo_item_image_3='1' AND blog_id='{$validBlogId}'";

        // NO NEED SQL UPDATE FOR LOCATION IN CLOSED DATES AS IT NEW FEATURE
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}locations`
            SET location_code=CONCAT('LO_', location_id)
            WHERE blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}locations`
            SET afterhours_pickup_location_id='0', afterhours_pickup_allowed='1'
            WHERE afterhours_pickup_location_id='-1' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}locations`
            SET afterhours_return_location_id='0', afterhours_return_allowed='1'
            WHERE afterhours_return_location_id='-1' AND blog_id='{$validBlogId}'";

        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}payment_methods`
            SET expiration_time='0' WHERE blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}payment_methods`
            SET payment_method_order='5', payment_method_code='pay-at-pickup', online_payment='0'
            WHERE payment_method_code='poa' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}payment_methods`
            SET payment_method_order='4', online_payment='0'
            WHERE payment_method_code='phone' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}payment_methods`
            SET payment_method_order= '3', online_payment='0'
            WHERE payment_method_code='bank' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}payment_methods`
            SET payment_method_email=payment_method_description, payment_method_description='{$validPaymentMethodPayPalDescription}',
            online_payment= '1', class_name='NRSPayPal', payment_method_code='paypal'
            WHERE payment_method_code='pp' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "INSERT INTO `{$this->conf->getPrefix()}payment_methods` (
              `payment_method_code`, `class_name`, `payment_method_name`, `payment_method_description`,`sandbox_mode`, `check_certificate`,
              `ssl_only`, `online_payment`, `payment_method_enabled` ,`payment_method_order`, `expiration_time`, `blog_id`
            ) VALUES (
              'stripe', 'NRSStripe', '{$validPaymentMethodStripeName}', '', '0', '0',
              '1', '1', '0', '2', '0', '{$validBlogId}'
            )";

        $arrSQL[] = "INSERT INTO `{$this->conf->getPrefix()}price_groups` (price_group_id, price_group_name, blog_id)
            SELECT it.item_id, CONCAT(IFNULL(ma.manufacturer_title,''), ' ', it.model_name), it.blog_id
            FROM `{$this->conf->getPrefix()}items` it
            LEFT JOIN `{$this->conf->getPrefix()}manufacturers` ma ON it.manufacturer_id=ma.manufacturer_id
            WHERE it.blog_id='{$validBlogId}'";

        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}price_plans` pp
            SET pp.price_group_id = pp.item_id
            WHERE pp.blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}price_plans` pp
            JOIN `{$this->conf->getPrefix()}price_plans` pp2 ON pp2.price_group_id = pp.price_group_id AND pp2.price_type='2'
            SET pp.hourly_rate_mon = pp2.daily_rate_mon,
            pp.hourly_rate_tue = pp2.daily_rate_tue,
            pp.hourly_rate_wed = pp2.daily_rate_wed,
            pp.hourly_rate_thu = pp2.daily_rate_thu,
            pp.hourly_rate_fri = pp2.daily_rate_fri,
            pp.hourly_rate_sat = pp2.daily_rate_sat,
            pp.hourly_rate_sun = pp2.daily_rate_sun
            WHERE pp.price_type='1' AND pp.blog_id='{$validBlogId}'";

        $arrSQL[] = "INSERT INTO `{$this->conf->getPrefix()}settings` (`conf_key`, `conf_value`, `blog_id`) VALUES
            ('conf_search_coupon_code_required', '0', '{$validBlogId}'),
            ('conf_search_coupon_code_visible', '1', '{$validBlogId}'),
            ('conf_search_manufacturer_required', '0', '{$validBlogId}'),
            ('conf_search_manufacturer_visible', '0', '{$validBlogId}'), 
            ('conf_search_partner_visible', '0', '{$validBlogId}'),
            ('conf_search_partner_required', '0', '{$validBlogId}'),
            ('conf_company_notification_emails', '1', '{$validBlogId}'),
            ('conf_currency_symbol_location', '0', '{$validBlogId}'),
            ('conf_universal_analytics_events_tracking', '1', '{$validBlogId}'),
            ('conf_universal_analytics_enhanced_ecommerce', '1', '{$validBlogId}'),
            ('conf_load_datepicker_from_plugin', '1', '{$validBlogId}'),
            ('conf_load_fancybox_from_plugin', '1', '{$validBlogId}'),
            ('conf_load_font_awesome_from_plugin', '0', '{$validBlogId}'),
            ('conf_load_slick_slider_from_plugin', '1', '{$validBlogId}'),
            ('conf_item_url_slug', '{$validSettingItemURLSlug}', '{$validBlogId}'),
            ('conf_location_url_slug', '{$validSettingLocationURLSlug}', '{$validBlogId}'),
            ('conf_page_url_slug', '{$validSettingPageURLSlug}', '{$validBlogId}'),
            ('conf_reveal_partner', '1', '{$validBlogId}'),
            ('conf_benefit_thumb_w', '71', '{$validBlogId}'),
            ('conf_benefit_thumb_h', '81', '{$validBlogId}'),
            ('conf_item_big_thumb_w', '360', '{$validBlogId}'),
            ('conf_item_big_thumb_h', '225', '{$validBlogId}'),
            ('conf_item_thumb_w', '240', '{$validBlogId}'),
            ('conf_item_thumb_h', '150', '{$validBlogId}'),
            ('conf_item_mini_thumb_w', '100', '{$validBlogId}'),
            ('conf_item_mini_thumb_h', '63', '{$validBlogId}'),
            ('conf_location_big_thumb_w', '360', '{$validBlogId}'),
            ('conf_location_big_thumb_h', '225', '{$validBlogId}'),
            ('conf_location_thumb_w', '179', '{$validBlogId}'),
            ('conf_location_thumb_h', '179', '{$validBlogId}'),
            ('conf_location_mini_thumb_w', '100', '{$validBlogId}'),
            ('conf_location_mini_thumb_h', '63', '{$validBlogId}'),
            ('conf_manufacturer_thumb_w', '205', '{$validBlogId}'),
            ('conf_manufacturer_thumb_h', '205', '{$validBlogId}')";

        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}settings`
            SET conf_key='conf_customer_zip_code_required'
            WHERE conf_key='conf_customer_zipcode_required' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}settings`
            SET conf_key='conf_customer_zip_code_visible'
            WHERE conf_key='conf_customer_zipcode_visible' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}settings`
            SET conf_key='conf_portal_zip_code'
            WHERE conf_key='conf_portal_zipcode' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}settings`
            SET conf_key='conf_terms_and_conditions_page_id'
            WHERE conf_key='conf_tos_page_id' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}settings`
            SET conf_key='conf_company_name'
            WHERE conf_key='conf_portal_name' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}settings`
            SET conf_key='conf_company_city'
            WHERE conf_key='conf_portal_city' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}settings`
            SET conf_key='conf_company_country'
            WHERE conf_key='conf_portal_country' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}settings`
            SET conf_key='conf_company_email'
            WHERE conf_key='conf_portal_email' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}settings`
            SET conf_key='conf_company_phone'
            WHERE conf_key='conf_portal_phone' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}settings`
            SET conf_key='conf_company_state'
            WHERE conf_key='conf_portal_state' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}settings`
            SET conf_key='conf_company_street_address'
            WHERE conf_key='conf_portal_street_address' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}settings`
            SET conf_key='conf_company_zip_code'
            WHERE conf_key='conf_portal_zip_code' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}settings`
            SET conf_key='conf_show_price_with_taxes'
            WHERE conf_key='conf_show_prices_with_vat' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}settings`
            SET conf_key='conf_search_return_date_required'
            WHERE conf_key='conf_search_dropoff_date_required' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}settings`
            SET conf_key='conf_search_return_date_visible'
            WHERE conf_key='conf_search_dropoff_date_visible' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}settings`
            SET conf_key='conf_search_return_location_required'
            WHERE conf_key='conf_search_dropoff_location_required' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}settings`
            SET conf_key='conf_search_return_location_visible'
            WHERE conf_key='conf_search_dropoff_location_visible' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}settings`
            SET conf_key='conf_distance_measurement_unit'
            WHERE conf_key='conf_measurement_unit' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}settings`
            SET conf_key='conf_customer_comments_required'
            WHERE conf_key='conf_customer_additional_comments_required' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}settings`
            SET conf_key='conf_customer_comments_visible'
            WHERE conf_key='conf_customer_additional_comments_visible' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "UPDATE `{$this->conf->getPrefix()}settings`
            SET conf_value='Crimson Red'
            WHERE conf_key='conf_system_style' AND blog_id='{$validBlogId}'";

        $arrSQL[] = "INSERT INTO `{$this->conf->getPrefix()}taxes` (tax_name, location_id, location_type, tax_percentage, blog_id)
            SELECT '{$validTaxName}', '0', '1', s.conf_value, '{$validBlogId}'
            FROM `{$this->conf->getPrefix()}settings` s
            WHERE s.conf_key='conf_vat_percentage'";

        //////////////////////////////////////////////////////////////////////////////////////////////////////
        // 9. Update non-plugin tables
        //////////////////////////////////////////////////////////////////////////////////////////////////////
        $arrSQL[] = "UPDATE `{$this->conf->getBlogPrefix($validBlogId)}posts`
            SET post_type='car_rental_item'
            WHERE post_type='car';";
        $arrSQL[] = "UPDATE `{$this->conf->getBlogPrefix($validBlogId)}posts` p
            JOIN `{$this->conf->getPrefix()}settings` s ON s.conf_value=p.ID AND s.conf_key='conf_terms_and_conditions_page_id'
            SET p.post_type='car_rental_page'
            WHERE p.post_type='car_rental_item'";

        $arrSQL[] = "UPDATE `{$this->conf->getBlogPrefix($validBlogId)}posts` p
            JOIN `{$this->conf->getPrefix()}settings` s ON s.conf_value=p.ID AND s.conf_key='conf_confirmation_page_id'
            SET p.post_type='car_rental_page'
            WHERE p.post_type='car_rental_item'";

        $arrSQL[] = "UPDATE `{$this->conf->getBlogPrefix($validBlogId)}posts` p
            JOIN `{$this->conf->getPrefix()}settings` s ON s.conf_value=p.ID AND s.conf_key='conf_cancelled_payment_page_id'
            SET p.post_type='car_rental_page'
            WHERE p.post_type='car_rental_item'";

        //////////////////////////////////////////////////////////////////////////////////////////////////////
        // 10. Update Shortcodes
        //////////////////////////////////////////////////////////////////////////////////////////////////////
        $arrSQL[] = "UPDATE `{$this->conf->getBlogPrefix($validBlogId)}posts`
            SET post_content=REPLACE (post_content, '[car_rental_system display=\"item\" item=\"', '[car_rental_system display=\"car\" car=\"')
            WHERE post_type='car_rental_item'";
        $arrSQL[] = "UPDATE `{$this->conf->getBlogPrefix($validBlogId)}posts`
            SET post_content=REPLACE (post_content, '[car_rental_system display=\"search\" item=\"', '[car_rental_system display=\"search\" action_page=\"".$validNewSearchPageId."\" steps=\"form,list,list,table,table\" car=\"')
            WHERE post_type='car_rental_item'";
        $arrSQL[] = "UPDATE `{$this->conf->getBlogPrefix($validBlogId)}posts`
            SET post_content=REPLACE (post_content, '[car_rental_system display=\"search\"', '[car_rental_system display=\"search\" action_page=\"".$validNewSearchPageId."\" steps=\"form,list,list,table,table\"')
            WHERE post_type='page'";
        $arrSQL[] = "UPDATE `{$this->conf->getBlogPrefix($validBlogId)}posts`
            SET post_content=REPLACE (post_content, '[car_rental_system display=\"list\"]', '[car_rental_system display=\"cars\" layout=\"list\"]')
            WHERE post_type='page'";
        $arrSQL[] = "UPDATE `{$this->conf->getBlogPrefix($validBlogId)}posts`
            SET post_content=REPLACE (post_content, '[car_rental_system display=\"price_table\"]', '[car_rental_system display=\"prices\" layout=\"table\"]')
            WHERE post_type='page'";
        $arrSQL[] = "UPDATE `{$this->conf->getBlogPrefix($validBlogId)}posts`
            SET post_content=REPLACE (post_content, '[car_rental_system display=\"extras_price_table\"]', '[car_rental_system display=\"extra_prices\" layout=\"table\"]')
            WHERE post_type='page'";
        $arrSQL[] = "UPDATE `{$this->conf->getBlogPrefix($validBlogId)}posts`
            SET post_content=REPLACE (post_content, '[car_rental_system display=\"calendar\"]', '[car_rental_system display=\"availability\" layout=\"calendar\"]')
            WHERE post_type='page'";
        $arrSQL[] = "UPDATE `{$this->conf->getBlogPrefix($validBlogId)}posts`
            SET post_content=REPLACE (post_content, '[car_rental_system display=\"extras_calendar\"]', '[car_rental_system display=\"extras_availability\" layout=\"\calendar\"]')
            WHERE post_type='page'";
        $arrSQL[] = "UPDATE `{$this->conf->getBlogPrefix($validBlogId)}posts`
            SET post_content=REPLACE (post_content, '[car_rental_system display=\"slider\"]', '[car_rental_system display=\"cars\" layout=\"slider\"]')
            WHERE post_type='page'";
        $arrSQL[] = "UPDATE `{$this->conf->getBlogPrefix($validBlogId)}posts`
            SET post_content=REPLACE (post_content, '[car_rental_system display=\"edit\"]', '[car_rental_system display=\"edit\" steps=\"form,list,list,table,table\"]')
            WHERE post_type='page'";

        //////////////////////////////////////////////////////////////////////////////////////////////////////
        // 11. Create location pages with action_page linked to search page
        //////////////////////////////////////////////////////////////////////////////////////////////////////
        $sqlQuery = "
                SELECT location_id, location_name
                FROM {$this->conf->getPrefix()}locations
                WHERE blog_id='{$this->conf->getBlogId()}'
                ORDER BY location_id ASC
            ";
        $locationRows = $this->conf->getInternalWPDB()->get_results($sqlQuery, ARRAY_A);

        foreach($locationRows AS $locationRow)
        {
            $validLocationId = intval($locationRow['location_id']);
            $locationName = stripslashes($locationRow['location_name']);
            // Create location page post object
            $arrLocationPage = array(
                'post_title'    => $locationName,
                'post_content'  => wp_filter_kses(
                    '['.$this->conf->getShortcode().' display="location" location="'.$validLocationId.'"]
['.$this->conf->getShortcode().' display="search" location="'.$validLocationId.'" action_page="'.$validNewSearchPageId.'" steps="form,list,list,table,table"]'
                ),
                'post_status'   => 'publish',
                'post_type'     => $this->conf->getExtensionPrefix().'location',
                /*'post_author'   => 1,*/ /*auto assign current user*/
                /*'post_category' => array(8,39)*/ /*no categories needed here*/
            );

            // Insert corresponding location post
            $validNewLocationPageId = wp_insert_post($arrLocationPage, FALSE);

            $arrSQL[] = "UPDATE {$this->conf->getPrefix()}locations
                SET location_page_id='{$validNewLocationPageId}'
                WHERE location_id='{$validLocationId}' AND blog_id='{$this->conf->getBlogId()}'
            ";
        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////
        // 12. Delete data that is not used anymore
        //////////////////////////////////////////////////////////////////////////////////////////////////////
        $arrSQL[] = "DELETE FROM `{$this->conf->getPrefix()}prepayments` WHERE item_id>'0' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "DELETE FROM `{$this->conf->getPrefix()}price_plans` WHERE price_type='2' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "DELETE FROM `{$this->conf->getPrefix()}settings` WHERE conf_key='conf_paypal_sandbox_mode' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "DELETE FROM `{$this->conf->getPrefix()}settings` WHERE conf_key='conf_vat_percentage' AND blog_id='{$validBlogId}'";
        $arrSQL[] = "DELETE FROM `{$this->conf->getPrefix()}settings` WHERE conf_key='conf_discount_enabled' AND blog_id='{$validBlogId}'";

        $updated = FALSE;
        if($blogIdsUpdated)
        {
            $updated = $this->executeQueries($arrSQL);
        }
        if($updated === FALSE)
        {
            $this->errorMessages[] = sprintf($this->lang->getText('NRS_DATABASE_UPDATE_DATA_UPDATE_ERROR_TEXT'), $this->blogId);
        } else
        {
            $this->okayMessages[] = sprintf($this->lang->getText('NRS_DATABASE_UPDATE_DATA_UPDATED_TEXT'), $this->blogId);
        }

        return $updated;
    }

    /**
     * SQL for late database altering from V4.3 to V5.0 and newer
     * @return bool
     */
    public function alter_4_3_DatabaseLateStructureTo_5_0()
    {
        $arrSQL = array();

        // This structure update can be done ONLY in late structure update, this is because after SQL data update,
        // in which we replaced all -1's with 0's, and set afterhours_pickup/return_allowed to 1,
        // after update afterhours pick-up/return location id is ZERO or POSITIVE INTEGERS ONLY (UNSIGNED)
        $arrSQL[] = "ALTER TABLE `{$this->conf->getPrefix()}locations`
            CHANGE `afterhours_pickup_location_id` `afterhours_pickup_location_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
            CHANGE `afterhours_return_location_id` `afterhours_return_location_id` INT( 11 ) UNSIGNED NOT NULL DEFAULT '0';";

        // All reordering done - now we can drop unnecessary table columns and unnecessary tables
        $arrSQL[] = "ALTER TABLE `{$this->conf->getPrefix()}bookings` DROP `pickup_location_id`, DROP `return_location_id`;";
        $arrSQL[] = "ALTER TABLE `{$this->conf->getPrefix()}booking_options` DROP `item_id`, DROP `extra_id`;";
        $arrSQL[] = "DROP TABLE `{$this->conf->getPrefix()}deposits`;";
        $arrSQL[] = "ALTER TABLE `{$this->conf->getPrefix()}discounts` DROP `item_id`;";
        $arrSQL[] = "ALTER TABLE `{$this->conf->getPrefix()}extras` DROP `prepayment_percentage`, DROP `min_units_per_booking`;";
        $arrSQL[] = "ALTER TABLE `{$this->conf->getPrefix()}price_plans` DROP `price_type`, DROP `item_id`;";
        $arrSQL[] = "ALTER TABLE `{$this->conf->getPrefix()}prepayments` DROP `item_id`;";

        $altered = $this->executeQueries($arrSQL);
        if($altered === FALSE)
        {
            $this->errorMessages[] = sprintf($this->lang->getText('NRS_DATABASE_UPDATE_LATE_STRUCTURE_ALTER_ERROR_TEXT'), $this->blogId);
        } else
        {
            $this->okayMessages[] = sprintf($this->lang->getText('NRS_DATABASE_UPDATE_LATE_STRUCTURE_ALTERED_TEXT'), $this->blogId);
        }

        return $altered;
    }

    /**
     * Insert/Update/Alter data to database
     * @param array $paramArrTrustedSQLs
     * @return bool
     */
    private function executeQueries(array $paramArrTrustedSQLs)
    {
        $currentCounter = $this->getCounter();

        $executed = TRUE;
        foreach($paramArrTrustedSQLs AS $sqlQuery)
        {
            // Increase internal queries counter
            $this->internalCounter = $this->internalCounter + 1;
            if($currentCounter > $this->internalCounter)
            {
                // Do nothing Just SKIP this query
            } else
            {
                // Try to execute current query
                $ok = $this->conf->getInternalWPDB()->query($sqlQuery);
                if($ok === FALSE)
                {
                    $executed = FALSE;
                    $startIdentifier = '`'.$this->conf->getPrefix();
                    $endIdentifier = '`';
                    $startCharPosOfTableName = strpos($sqlQuery, $startIdentifier) + strlen($startIdentifier);
                    $tableLength = strpos($sqlQuery, $endIdentifier, $startCharPosOfTableName) - $startCharPosOfTableName;
                    $tableName = '';
                    if($startCharPosOfTableName > 0 && $tableLength > 0)
                    {
                        $tableName = substr($sqlQuery, $startCharPosOfTableName, $tableLength);
                    }
                    $this->errorMessages[] = sprintf($this->lang->getText('NRS_DATABASE_UPDATE_QUERY_FAILED_FOR_TABLE_ERROR_TEXT'), $this->blogId, $tableName, $this->internalCounter);
                    if($this->debugMode)
                    {
                        $debugMessage = "FAILED AT QUERY:<br />".nl2br($sqlQuery);
                        $this->debugMessages[] = $debugMessage;
                        // Do not echo here, as this class is used in redirect
                        //echo "<br />".$debugMessage;
                    }

                    // Stop executing any more queries
                    break;
                } else
                {
                    // Increase currently executed queries counter
                    $this->setCounter($this->internalCounter);
                }
            }
        }

        return $executed;
    }

    /**
     * @note - We should not use blog_id here, as we want that this would get updated in all sites at once
     * @param $paramNewVersion
     */
    public function updateVersion($paramNewVersion)
    {
        $updated = FALSE;
        $validBlogId = intval($this->blogId);
        $validNewVersion = number_format(floatval($paramNewVersion), 1,'.','');
        // Update plugin version till newest
        $versionUpdated = $this->conf->getInternalWPDB()->query("
            UPDATE `{$this->conf->getPrefix()}settings`
            SET `conf_value`='{$validNewVersion}'
            WHERE `conf_key`='conf_plugin_version' AND blog_id='{$validBlogId}'
        ");
        // Reset counter back to 0 to say that the new update can start from the first update class query. That will be used in future updates
        $counterReset = $this->conf->getInternalWPDB()->query("
            UPDATE `{$this->conf->getPrefix()}settings`
            SET `conf_value`='0'
            WHERE `conf_key`='conf_updated' AND blog_id='{$validBlogId}'
        ");
        if($versionUpdated !== FALSE && $counterReset !== FALSE)
        {
            $updated = TRUE;
        }

        if($updated === FALSE)
        {
            $this->errorMessages[] = sprintf($this->lang->getText('NRS_DATABASE_UPDATE_VERSION_UPDATE_ERROR_TEXT'), $this->blogId);
        } else
        {
            $this->okayMessages[] = sprintf($this->lang->getText('NRS_DATABASE_UPDATE_VERSION_UPDATED_TEXT'), $this->blogId, $validNewVersion);
        }
    }
}