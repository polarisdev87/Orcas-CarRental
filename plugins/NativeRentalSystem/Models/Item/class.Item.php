<?php
/**
 * Item Processor

 * @package NRS
 * @uses NRSDepositManager, NRSDiscountManager, NRSPrepaymentManager
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Item;
use NativeRentalSystem\Models\AbstractElement;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\File\StaticFile;
use NativeRentalSystem\Models\iElement;
use NativeRentalSystem\Models\iPartner;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;

class Item extends AbstractElement implements iElement, iPartner
{
    private $conf 	                = NULL;
    private $lang 		            = NULL;
    private $settings	            = array();
    private $debugMode 	            = 0;
    private $distanceMeasurementUnit= "";
    private $revealPartner          = TRUE;
    private $itemId                 = 0;
    private $bigThumbWidth	        = 360;
    private $bigThumbHeight		    = 225;
    private $thumbWidth	            = 240;
    private $thumbHeight		    = 150;
    private $miniThumbWidth	        = 100;
    private $miniThumbHeight		= 63;

    /**
     * Item constructor.
     * @param ExtensionConfiguration &$paramConf
     * @param Language &$paramLang
     * @param array $paramSettings
     * @param int $paramItemId
     */
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings, $paramItemId)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;
        // Set saved settings
        $this->settings = $paramSettings;

        if(isset(
            $paramSettings['conf_item_big_thumb_w'], $paramSettings['conf_item_big_thumb_h'],
            $paramSettings['conf_item_thumb_w'], $paramSettings['conf_item_thumb_h'],
            $paramSettings['conf_item_mini_thumb_w'], $paramSettings['conf_item_mini_thumb_h']
        ))
        {
            // Set image dimensions
            $this->bigThumbWidth = StaticValidator::getValidPositiveInteger($paramSettings['conf_item_big_thumb_w'], 0);
            $this->bigThumbHeight = StaticValidator::getValidPositiveInteger($paramSettings['conf_item_big_thumb_h'], 0);
            $this->thumbWidth = StaticValidator::getValidPositiveInteger($paramSettings['conf_item_thumb_w'], 0);
            $this->thumbHeight = StaticValidator::getValidPositiveInteger($paramSettings['conf_item_thumb_h'], 0);
            $this->miniThumbWidth = StaticValidator::getValidPositiveInteger($paramSettings['conf_item_mini_thumb_w'], 0);
            $this->miniThumbHeight = StaticValidator::getValidPositiveInteger($paramSettings['conf_item_mini_thumb_h'], 0);
        }

        if(isset($paramSettings['conf_distance_measurement_unit']))
        {
            // Set distance measurement unit
            $this->distanceMeasurementUnit = sanitize_text_field($paramSettings['conf_distance_measurement_unit']);
        }

        if(isset($paramSettings['conf_reveal_partner']))
        {
            // Set reveal partner
            $this->revealPartner = $paramSettings['conf_reveal_partner'] == 1 ? TRUE : FALSE;
        }

        $this->itemId = StaticValidator::getValidValue($paramItemId, 'positive_integer', 0);
    }

    /**
     * @param $paramItemId
     * @return mixed
     */
    private function getDataFromDatabaseById($paramItemId)
    {
        $validItemId = StaticValidator::getValidPositiveInteger($paramItemId, 0);
        $row = $this->conf->getInternalWPDB()->get_row("
            SELECT *
            FROM {$this->conf->getPrefix()}items
            WHERE item_id='{$validItemId}'
        ", ARRAY_A);

        return $row;
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    public function getId()
    {
        return $this->itemId;
    }

    /**
     * Element-specific method
     * @return string
     */
    public function getSKU()
    {
        $retSKU = "";
        $ret = $this->getDataFromDatabaseById($this->itemId);
        if(!is_null($ret))
        {
            // Make raw
            $retSKU = stripslashes($ret['item_sku']);
        }
        return $retSKU;
    }

    /**
     * Element-specific method
     * @return string
     */
    public function getPrintSKU()
    {
        return esc_html($this->getSKU());
    }

    /**
     * Element-specific method
     * @return string
     */
    public function getEditSKU()
    {
        return esc_attr($this->getSKU());
    }

    /**
     * Element-specific method
     * @return int
     */
    public function getPartnerId()
    {
        $retPartnerId = 0;
        $itemData = $this->getDataFromDatabaseById($this->itemId);
        if(!is_null($itemData))
        {
            $retPartnerId = $itemData['partner_id'];
        }
        return $retPartnerId;
    }

    /**
     * Element-specific method
     * @return int
     */
    public function getMinDriverAge()
    {
        $retMinDriverAge = 0;
        $itemData = $this->getDataFromDatabaseById($this->itemId);
        if(!is_null($itemData))
        {
            $retMinDriverAge = $itemData['min_driver_age'];
        }
        return $retMinDriverAge;
    }

    /**
     * Element-specific method
     * @param $paramAgeToCheck
     * @return bool
     */
    public function canDriveByAge($paramAgeToCheck)
    {
        $validAgeToCheck = StaticValidator::getValidPositiveInteger($paramAgeToCheck, 0);
        $minDriverAge = 0;
        $itemData = $this->getDataFromDatabaseById($this->itemId);
        if(!is_null($itemData))
        {
            $minDriverAge = $itemData['min_driver_age'];
        }

        $canDrive = $validAgeToCheck >= $minDriverAge ? TRUE : FALSE;

        if($this->debugMode)
        {
            echo "<br />Minimum age for selected car (ID=".$this->itemId."): ". $minDriverAge;
            echo "<br />Customer&#39;s age: ". $validAgeToCheck;
            echo "<br />Can customer drive this car: ". var_export($canDrive, TRUE);
        }

        return $canDrive;
    }

    /**
     * Checks if current user can edit the element
     * @return bool
     */
    public function canEdit()
    {
        $canEdit = FALSE;
        if($this->itemId > 0)
        {
            $partnerId = $this->getPartnerId();
            if(current_user_can('manage_'.$this->conf->getExtensionPrefix().'all_items'))
            {
                $canEdit = TRUE;
            } else if($partnerId > 0 && $partnerId == get_current_user_id() && current_user_can('manage_'.$this->conf->getExtensionPrefix().'own_items'))
            {
                $canEdit = TRUE;
            }
        }

        return $canEdit;
    }

    /**
     * Checks if current user can view the element
     * @return bool
     */
    public function canView()
    {
        $canView = FALSE;
        if($this->itemId > 0)
        {
            $partnerId = $this->getPartnerId();
            if(current_user_can('view_'.$this->conf->getExtensionPrefix().'all_items'))
            {
                $canView = TRUE;
            } else if($partnerId > 0 && $partnerId == get_current_user_id() && current_user_can('view_'.$this->conf->getExtensionPrefix().'own_items'))
            {
                $canView = TRUE;
            }
        }

        return $canView;
    }

    /**
     * @param bool $paramIncludeUnclassified - not used
     * @return mixed
     */
    public function getDetails($paramIncludeUnclassified = FALSE)
    {
        return $this->getAllDetails(FALSE);
    }

    /**
     * Element specific function
     * @return mixed
     */
    public function getExtendedDetails()
    {
        return $this->getAllDetails(TRUE);
    }

    /**
     * @param bool $paramExtendedDetails
     * @return mixed
     */
    private function getAllDetails($paramExtendedDetails = FALSE)
    {
        $ret = $this->getDataFromDatabaseById($this->itemId);

        if(!is_null($ret))
        {
            // Make raw
            $ret['item_sku'] = stripslashes($ret['item_sku']);
            $ret['model_name'] = stripslashes($ret['model_name']);
            $ret['item_image_1'] = stripslashes($ret['item_image_1']);
            $ret['item_image_2'] = stripslashes($ret['item_image_2']);
            $ret['item_image_3'] = stripslashes($ret['item_image_3']);
            $ret['mileage'] = stripslashes($ret['mileage']);
            $ret['fuel_consumption'] = stripslashes($ret['fuel_consumption']);
            $ret['engine_capacity'] = stripslashes($ret['engine_capacity']);
            $ret['options_measurement_unit'] = stripslashes($ret['options_measurement_unit']);

            // Add translation
            $ret['translated_model_name'] = $this->lang->getTranslated("it{$ret['item_id']}_model_name", $ret['model_name']);

            // Extend $item with additional details
            $image1_Folder = $ret['demo_item_image_1'] == 1 ? $this->conf->getExtensionDemoGalleryURL('', FALSE) : $this->conf->getGalleryURL();
            $image2_Folder = $ret['demo_item_image_2'] == 1 ? $this->conf->getExtensionDemoGalleryURL('', FALSE) : $this->conf->getGalleryURL();
            $image3_Folder = $ret['demo_item_image_3'] == 1 ? $this->conf->getExtensionDemoGalleryURL('', FALSE) : $this->conf->getGalleryURL();

            $itemPageURL = get_permalink($ret['item_page_id']);
            $ret['item_page_url'] = $ret['item_page_id'] != 0 && $itemPageURL != '' ? $itemPageURL : "";

            $ret['mini_thumb_url'] = $ret['item_image_1'] != "" ? $image1_Folder."mini_thumb_".$ret['item_image_1'] : "";
            $ret['thumb_url'] = $ret['item_image_1'] != "" ? $image1_Folder."thumb_".$ret['item_image_1'] : "";
            $ret['big_thumb_url'] = $ret['item_image_1'] != "" ? $image1_Folder."big_thumb_".$ret['item_image_1'] : "";
            $ret['image_url'] = $ret['item_image_1'] != "" ? $image1_Folder.$ret['item_image_1'] : "";

            $ret['mini_thumb_2_url'] = $ret['item_image_2'] != "" ? $image2_Folder."mini_thumb_".$ret['item_image_2'] : "";
            $ret['thumb_2_url'] = $ret['item_image_2'] != "" ? $image2_Folder."thumb_".$ret['item_image_2'] : "";
            $ret['big_thumb_2_url'] = $ret['item_image_2'] != "" ? $image2_Folder."big_thumb_".$ret['item_image_2'] : "";
            $ret['image_2_url'] = $ret['item_image_2'] != "" ? $image2_Folder.$ret['item_image_2'] : "";

            $ret['mini_thumb_3_url'] = $ret['item_image_3'] != "" ? $image3_Folder."mini_thumb_".$ret['item_image_3'] : "";
            $ret['thumb_3_url'] = $ret['item_image_3'] != "" ? $image3_Folder."thumb_".$ret['item_image_3'] : "";
            $ret['big_thumb_3_url'] = $ret['item_image_3'] != "" ? $image3_Folder."big_thumb_".$ret['item_image_3'] : "";
            $ret['image_3_url'] = $ret['item_image_3'] != "" ? $image3_Folder.$ret['item_image_3'] : "";

            // Make output ready for print
            $ret['print_item_sku'] = esc_html($ret['item_sku']);
            $ret['print_model_name'] = esc_html($ret['model_name']);
            $ret['print_translated_model_name'] = esc_html($ret['translated_model_name']);
            $ret['print_mileage'] = esc_html($ret['mileage']);
            $ret['print_fuel_consumption'] = esc_html($ret['fuel_consumption']);
            $ret['print_engine_capacity'] = esc_html($ret['engine_capacity']);
            $ret['print_mileage'] = esc_html($ret['mileage'] == "" ? $this->lang->getText('NRS_UNLIMITED_TEXT') : $ret['mileage'].' '.$this->distanceMeasurementUnit);
            $ret['print_min_driver_age'] = $ret['min_driver_age'] > 0 ? $ret['min_driver_age'] : $this->lang->getText('NRS_NA_TEXT');
            $ret['print_options_measurement_unit'] = esc_html($ret['options_measurement_unit']);

            // Prepare output for edit
            $ret['edit_item_sku'] = esc_attr($ret['item_sku']); // for input field
            $ret['edit_model_name'] = esc_attr($ret['model_name']); // for input field
            $ret['edit_mileage'] = esc_attr($ret['mileage']); // for input field
            $ret['edit_fuel_consumption'] = esc_attr($ret['fuel_consumption']); // for input field
            $ret['edit_engine_capacity'] = esc_attr($ret['engine_capacity']); // for input field
            $ret['edit_mileage'] = esc_attr($ret['mileage']); // for input field
            $ret['edit_options_measurement_unit'] = esc_attr($ret['options_measurement_unit']); // for input field

            // Show of hide fields
            $ret['show_model_name'] = TRUE; // Always true - this field is mandatory
            $ret['show_fuel_consumption'] = $ret['fuel_consumption'] != "" ? TRUE : FALSE;
            $ret['show_max_passengers'] = $ret['max_passengers'] > 0 ? TRUE : FALSE;

            $ret['show_engine_capacity'] = $ret['engine_capacity'] != "" ? TRUE : FALSE;
            $ret['show_max_luggage'] = $ret['max_luggage'] > 0 ? TRUE : FALSE;
            $ret['show_item_doors'] = $ret['item_doors'] > 0 ? TRUE : FALSE;
            $ret['show_min_driver_age'] = $ret['min_driver_age'] > 0 ? TRUE : FALSE;

            $ret['show_mileage'] = $ret['mileage'] > 0 || $ret['mileage'] == "" ? TRUE : FALSE;

            if($paramExtendedDetails == TRUE)
            {
                if($this->revealPartner && $ret['partner_id'] > 0)
                {
                    $printPartnerName = get_the_author_meta('display_name', $ret['partner_id']);
                    $printViaPartner = sprintf($this->lang->getText('NRS_VIA_PARTNER_TEXT'), $printPartnerName);
                    $partnerProfileURL = get_author_posts_url($ret['partner_id']);
                    $printPartnerLink = '<a href="'.$partnerProfileURL.'"><span class="partner-name">'.$printPartnerName.'</span></a>';
                    $printViaPartnerLink = sprintf($this->lang->getText('NRS_VIA_PARTNER_TEXT'), $printPartnerLink);
                    $ret['print_partner_name'] = $printPartnerName;
                    $ret['partner_profile_url'] = $partnerProfileURL;
                    $ret['print_partner_link'] = $printPartnerLink;
                    $ret['print_via_partner'] = '('.$printViaPartner.')';
                    $ret['print_via_partner_link'] = '('.$printViaPartnerLink.')';
                } else
                {
                    $ret['print_partner_name'] = '';
                    $ret['partner_profile_url'] = '';
                    $ret['print_partner_link'] = '';
                    $ret['print_via_partner'] = '';
                    $ret['print_via_partner_link'] = '';
                }

                ///////////////////////////////////////////////////////////////////////////////
                // MANUFACTURER: START
                $objManufacturer = new Manufacturer($this->conf, $this->lang, $this->settings, $ret['manufacturer_id']);
                $manufacturerDetails = $objManufacturer->getDetails();
                if(!is_null($manufacturerDetails))
                {
                    $ret['manufacturer_title'] = $manufacturerDetails['manufacturer_title'];
                    $ret['translated_manufacturer_title'] = $manufacturerDetails['translated_manufacturer_title'];
                    $ret['print_manufacturer_title'] = $manufacturerDetails['print_manufacturer_title'];
                    $ret['print_translated_manufacturer_title'] = $manufacturerDetails['print_translated_manufacturer_title'];
                } else
                {
                    $ret['manufacturer_title'] = '';
                    $ret['translated_manufacturer_title'] = '';
                    $ret['print_manufacturer_title'] = '';
                    $ret['print_translated_manufacturer_title'] = '';
                }

                // MANUFACTURER: END
                ///////////////////////////////////////////////////////////////////////////////

                ///////////////////////////////////////////////////////////////////////////////
                // BODY TYPE: START
                $objBodyType = new BodyType($this->conf, $this->lang, $this->settings, $ret['body_type_id']);
                $bodyTypeDetails = $objBodyType->getDetails();
                if(!is_null($bodyTypeDetails))
                {
                    $ret['body_type_title'] = $bodyTypeDetails['body_type_title'];
                    $ret['translated_body_type_title'] = $bodyTypeDetails['translated_body_type_title'];
                    $ret['print_body_type_title'] = $bodyTypeDetails['print_body_type_title'];
                    $ret['print_translated_body_type_title'] = $bodyTypeDetails['print_translated_body_type_title'];
                } else
                {
                    $ret['body_type_title'] = '';
                    $ret['translated_body_type_title'] = '';
                    $ret['print_body_type_title'] = '';
                    $ret['print_translated_body_type_title'] = '';
                }
                // BODY TYPE: END
                ///////////////////////////////////////////////////////////////////////////////

                ///////////////////////////////////////////////////////////////////////////////
                // FUEL TYPE: START
                $objFuelType = new FuelType($this->conf, $this->lang, $this->settings, $ret['fuel_type_id']);
                $fuelTypeDetails = $objFuelType->getDetails();
                if(!is_null($fuelTypeDetails))
                {
                    $ret['fuel_type_title'] = $fuelTypeDetails['fuel_type_title'];
                    $ret['translated_fuel_type_title'] = $fuelTypeDetails['translated_fuel_type_title'];
                    $ret['print_fuel_type_title'] = $fuelTypeDetails['print_fuel_type_title'];
                    $ret['print_translated_fuel_type_title'] = $fuelTypeDetails['print_translated_fuel_type_title'];
                } else
                {
                    $ret['fuel_type_title'] = '';
                    $ret['translated_fuel_type_title'] = '';
                    $ret['print_fuel_type_title'] = '';
                    $ret['print_translated_fuel_type_title'] = '';
                }
                // FUEL TYPE: END
                ///////////////////////////////////////////////////////////////////////////////

                ///////////////////////////////////////////////////////////////////////////////
                // TRANSMISSION TYPE: START
                $objFuelType = new TransmissionType($this->conf, $this->lang, $this->settings, $ret['transmission_type_id']);
                $transmissionTypeDetails = $objFuelType->getDetails();
                if(!is_null($transmissionTypeDetails))
                {
                    $ret['transmission_type_title'] = $transmissionTypeDetails['transmission_type_title'];
                    $ret['translated_transmission_type_title'] = $transmissionTypeDetails['translated_transmission_type_title'];
                    $ret['print_transmission_type_title'] = $transmissionTypeDetails['print_transmission_type_title'];
                    $ret['print_translated_transmission_type_title'] = $transmissionTypeDetails['print_translated_transmission_type_title'];
                } else
                {
                    $ret['transmission_type_title'] = '';
                    $ret['translated_transmission_type_title'] = '';
                    $ret['print_transmission_type_title'] = '';
                    $ret['print_translated_transmission_type_title'] = '';
                }
                // TRANSMISSION TYPE: END
                ///////////////////////////////////////////////////////////////////////////////

                // Show of hide fields
                $ret['show_manufacturer'] = $ret['manufacturer_id'] > 0 && $ret['manufacturer_title'] != "" ? TRUE : FALSE;
                $ret['show_body_type'] = $ret['body_type_id'] > 0 && $ret['body_type_title'] != "" ? TRUE : FALSE;
                $ret['show_transmission_type'] =$ret['transmission_type_id'] > 0 && $ret['transmission_type_title'] != "" ? TRUE : FALSE;
                $ret['show_fuel_type'] = $ret['fuel_type_id'] > 0 && $ret['fuel_type_title'] != "" ? TRUE : FALSE;
            }
        }

        return $ret;
    }

    public function generateSKU()
    {
        if($this->itemId > 0)
        {
            $itemSKU = $this->getSKU();
        } else
        {
            $nextInsertId = 1;
            $sqlQuery = "
                SHOW TABLE STATUS LIKE '{$this->conf->getPrefix()}items'
            ";
            $data = $this->conf->getInternalWPDB()->get_row($sqlQuery, ARRAY_A);
            if(!is_null($data))
            {
                $nextInsertId = $data['Auto_increment'];

            }

            $itemSKU = 'IT_'.$nextInsertId;
        }
        return $itemSKU;
    }

    /**
     * @return bool|false|int
     */
    public function save()
    {
        $saved = FALSE;
        $ok = TRUE;
        $isManager = current_user_can('manage_'.$this->conf->getExtensionPrefix().'all_items');

        // Input data
        $validItemId = StaticValidator::getValidPositiveInteger($this->itemId, 0);

        // Do not use sanitize_key here, because we don't want to get it lowercase
        if($this->conf->isNetworkEnabled())
        {
            $sanitizedItemSKU = isset($_POST['item_sku']) ? sanitize_text_field($_POST['item_sku']) : '';
        } else
        {
            $sanitizedItemSKU = sanitize_text_field($validItemId > 0 ? $this->getSKU() : $this->generateSKU());
        }
        $validItemSKU = esc_sql($sanitizedItemSKU); // for sql query only

        // If item data exist, otherwise - create a new page if that is a new item creation
        $validItemPageId = isset($_POST['item_page_id']) ? StaticValidator::getValidPositiveInteger($_POST['item_page_id'], 0) : 0;

        if($isManager)
        {
            // If that is a store manager - allow to define the partner
            $validPartnerId = isset($_POST['partner_id']) ? StaticValidator::getValidPositiveInteger($_POST['partner_id'], 0) : 0;
        } else
        {
            // Otherwise - use current user id
            $validPartnerId = intval(get_current_user_id());
        }
        $validBodyTypeId = isset($_POST['body_type_id']) ? StaticValidator::getValidPositiveInteger($_POST['body_type_id'], 0) : 0;
        $validFuelTypeId = isset($_POST['fuel_type_id']) ? StaticValidator::getValidPositiveInteger($_POST['fuel_type_id'], 0) : 0;
        $validManufacturerId = isset($_POST['manufacturer_id']) ? StaticValidator::getValidPositiveInteger($_POST['manufacturer_id'], 0) : 0;
        $sanitizedModelName = isset($_POST['model_name']) ? sanitize_text_field($_POST['model_name']) : '';
        $validModelName = esc_sql($sanitizedModelName); // for sql query only
        $validTransmissionTypeId = isset($_POST['transmission_type_id']) ? StaticValidator::getValidPositiveInteger($_POST['transmission_type_id'], 0) : 0;
        $sanitizedMeasurementUnit = isset($_POST['options_measurement_unit']) ? sanitize_text_field($_POST['options_measurement_unit']) : '';
        $validMeasurementUnit = esc_sql($sanitizedMeasurementUnit); // for sql query only
        $sanitizedFuelConsumption = isset($_POST['fuel_consumption']) ? sanitize_text_field($_POST['fuel_consumption']) : '';
        $validFuelConsumption = esc_sql($sanitizedFuelConsumption); // for sql query only
        $sanitizedEngineCapacity = isset($_POST['engine_capacity']) ? sanitize_text_field($_POST['engine_capacity']) : '';
        $validEngineCapacity = esc_sql($sanitizedEngineCapacity); // for sql query only
        $validMaxPassengers = isset($_POST['max_passengers']) ? StaticValidator::getValidPositiveInteger($_POST['max_passengers'], 5) : 5;
        $validMaxLuggage = isset($_POST['max_luggage']) ? StaticValidator::getValidPositiveInteger($_POST['max_luggage'], 2) : 2;
        $validItemDoors = isset($_POST['item_doors']) ? StaticValidator::getValidPositiveInteger($_POST['item_doors'], 5) : 5;
        $validMinDriverAge = isset($_POST['min_driver_age']) ? StaticValidator::getValidPositiveInteger($_POST['min_driver_age'], 18) : 18;
        $sanitizedItemMileage = isset($_POST['item_mileage']) ? sanitize_text_field($_POST['item_mileage']) : '';
        $validItemMileage = esc_sql($sanitizedItemMileage); // for sql query only
        $validPriceGroupId = isset($_POST['price_group_id']) ? StaticValidator::getValidPositiveInteger($_POST['price_group_id'], 0) : 0;
        $validFixedRentalDeposit = isset($_POST['fixed_rental_deposit']) ? floatval($_POST['fixed_rental_deposit']) : 0.00;
        $validUnitsInStock = isset($_POST['units_in_stock']) ? StaticValidator::getValidPositiveInteger($_POST['units_in_stock'], 1) : 1;
        $validMaxItemUnitsPerBooking = isset($_POST['max_units_per_booking']) ? StaticValidator::getValidPositiveInteger($_POST['max_units_per_booking'], 1) : 1;
        $validEnabled = isset($_POST['enabled']) ? 1 : 0;
        $validDisplayInSlider = isset($_POST['display_in_slider']) ? 1 : 0;
        $validDisplayInItemList = isset($_POST['display_in_item_list'])? 1 : 0;
        $validDisplayInPriceTable = isset($_POST['display_in_price_table']) ? 1 : 0;
        $validDisplayInCalendar = isset($_POST['display_in_calendar']) ? 1 : 0;
        $validOptionsDisplayMode = isset($_POST['options_display_mode']) && $_POST['options_display_mode'] == 1 ? 1 : 2;

        if($validFixedRentalDeposit < 0)
        {
            $validFixedRentalDeposit = 0.00;
        }
        if($validUnitsInStock < 1)
        {
            $validUnitsInStock = 1;
        }
        if($validMaxItemUnitsPerBooking < 1)
        {
            $validMaxItemUnitsPerBooking = 1;
        }

        $arr_POST_FeatureIds = isset($_POST['features']) ? $_POST['features'] : array();
        $arr_POST_PickupLocationIds = isset($_POST['pickup_location_ids']) ? $_POST['pickup_location_ids'] : array();
        $arr_POST_ReturnLocationIds = isset($_POST['return_location_ids']) ? $_POST['return_location_ids'] : array();

        // Verifications
        $skuExistsQuery = "
            SELECT item_id
            FROM {$this->conf->getPrefix()}items
            WHERE item_sku='{$validItemSKU}'
            AND item_id!='{$validItemId}' AND blog_id='{$this->conf->getBlogId()}'
        ";
        $skuExists = $this->conf->getInternalWPDB()->get_row($skuExistsQuery, ARRAY_A);
        if(!is_null($skuExists))
        {
            $ok = FALSE;
            $this->errorMessages[] = $this->lang->getText('NRS_ITEM_SKU_EXISTS_ERROR_TEXT');
        }
        if($validMaxItemUnitsPerBooking > $validUnitsInStock)
        {
            $ok = FALSE;
            $this->errorMessages[] = $this->lang->getText('NRS_ITEM_MORE_UNITS_PER_BOOKING_THAN_IN_STOCK_ERROR_TEXT');
        }

        if($validItemId > 0 && $ok)
        {
            $updateQuery = "
                  UPDATE {$this->conf->getPrefix()}items SET
                  item_sku='{$validItemSKU}',
                  item_page_id='{$validItemPageId}',
                  partner_id='{$validPartnerId}', body_type_id='{$validBodyTypeId}',
                  fuel_type_id='{$validFuelTypeId}', transmission_type_id='{$validTransmissionTypeId}',
                  manufacturer_id ='{$validManufacturerId}', model_name='{$validModelName}',
                  options_measurement_unit='{$validMeasurementUnit}',
                  mileage='{$validItemMileage}',
                  fuel_consumption='{$validFuelConsumption}', engine_capacity='{$validEngineCapacity}',
                  max_passengers='{$validMaxPassengers}', max_luggage='{$validMaxLuggage}',
                  item_doors='{$validItemDoors}',
                  min_driver_age='{$validMinDriverAge}',
                  price_group_id='{$validPriceGroupId}', fixed_rental_deposit='{$validFixedRentalDeposit}',
                  units_in_stock='{$validUnitsInStock}', max_units_per_booking='{$validMaxItemUnitsPerBooking}',
                  enabled='{$validEnabled}',
                  display_in_slider='{$validDisplayInSlider}',
                  display_in_item_list='{$validDisplayInItemList}',
                  display_in_price_table='{$validDisplayInPriceTable}',
                  display_in_calendar='{$validDisplayInCalendar}',
                  options_display_mode='{$validOptionsDisplayMode}'
                  WHERE item_id='{$validItemId}' AND blog_id='{$this->conf->getBlogId()}'
            ";

            //die(nl2br($updateQuery));
            $saved = $this->conf->getInternalWPDB()->query($updateQuery);

            // Only if there is error in query we will skip that, if no changes were made (and 0 was returned) we will still process
            if($saved !== FALSE)
            {
                $itemEditData = $this->conf->getInternalWPDB()->get_row("
                    SELECT *
                    FROM {$this->conf->getPrefix()}items
                    WHERE item_id='{$validItemId}' AND blog_id='{$this->conf->getBlogId()}'
                ", ARRAY_A);

                // Upload images
                for($validImageCounter = 1; $validImageCounter <= 3; $validImageCounter++)
                {
                    if(
                        isset($_POST['delete_item_image_'.$validImageCounter]) && $itemEditData['item_image_'.$validImageCounter] != "" &&
                        $itemEditData['demo_item_image_'.$validImageCounter] == 0
                    ) {
                        // Unlink files only if it's not a demo image
                        unlink($this->conf->getGalleryPath().$itemEditData['item_image_'.$validImageCounter]);
                        unlink($this->conf->getGalleryPath()."thumb_".$itemEditData['item_image_'.$validImageCounter]);
                        unlink($this->conf->getGalleryPath()."big_thumb_".$itemEditData['item_image_'.$validImageCounter]);
                        unlink($this->conf->getGalleryPath()."mini_thumb_".$itemEditData['item_image_'.$validImageCounter]);
                    }

                    $validUploadedImageFileName = '';
                    if($_FILES['item_image_'.$validImageCounter]['tmp_name'] != '')
                    {
                        $uploadedImageFileName = StaticFile::uploadImageFile($_FILES['item_image_'.$validImageCounter], $this->conf->getGalleryPathWithoutEndSlash(), "");
                        StaticFile::makeThumbnail($this->conf->getGalleryPath(), $uploadedImageFileName, $this->bigThumbWidth, $this->bigThumbHeight, "big_thumb_");
                        StaticFile::makeThumbnail($this->conf->getGalleryPath(), $uploadedImageFileName, $this->thumbWidth, $this->thumbHeight, "thumb_");
                        StaticFile::makeThumbnail($this->conf->getGalleryPath(), $uploadedImageFileName, $this->miniThumbWidth, $this->miniThumbHeight, "mini_thumb_");
                        $validUploadedImageFileName = esc_sql(sanitize_file_name($uploadedImageFileName)); // for sql query only
                    }

                    if($validUploadedImageFileName != '' || isset($_POST['delete_item_image_'.$validImageCounter]))
                    {
                        // Update the sql
                        $this->conf->getInternalWPDB()->query("
                            UPDATE {$this->conf->getPrefix()}items SET
                            item_image_{$validImageCounter}='{$validUploadedImageFileName}', demo_item_image_{$validImageCounter}='0'
                            WHERE item_id='{$validItemId}' AND blog_id='{$this->conf->getBlogId()}'
                        ");
                    }
                }

                $this->conf->getInternalWPDB()->query("
                    DELETE FROM {$this->conf->getPrefix()}item_features
                    WHERE item_id='{$validItemId}' AND blog_id='{$this->conf->getBlogId()}'
                ");

                foreach($arr_POST_FeatureIds AS $POST_FeatureId)
                {
                    $validFeatureId = StaticValidator::getValidPositiveInteger($POST_FeatureId, 0);
                    $this->conf->getInternalWPDB()->query("
                        INSERT INTO {$this->conf->getPrefix()}item_features
                        (item_id, feature_id, blog_id)
                        VALUES
                        ('{$validItemId}', '{$validFeatureId}', '{$this->conf->getBlogId()}')
                    ");
                }

                // Delete current car locations
                $this->conf->getInternalWPDB()->query("
                    DELETE FROM {$this->conf->getPrefix()}item_locations
                    WHERE item_id='{$validItemId}' AND blog_id='{$this->conf->getBlogId()}'
                ");

                // Insert new car pickup locations
                foreach($arr_POST_PickupLocationIds AS $POST_pickupLocationId)
                {
                    $validPickupLocationId = StaticValidator::getValidPositiveInteger($POST_pickupLocationId, 0);
                    $this->conf->getInternalWPDB()->query("
                        INSERT INTO {$this->conf->getPrefix()}item_locations
                        (item_id, location_id, location_type, blog_id)
                        VALUES
                        ('{$validItemId}', '{$validPickupLocationId}', 1, '{$this->conf->getBlogId()}')
                    ");
                }

                // Insert new car return locations
                foreach($arr_POST_ReturnLocationIds AS $POST_returnLocationId)
                {
                    $validReturnLocationId = StaticValidator::getValidPositiveInteger($POST_returnLocationId, 0);
                    $this->conf->getInternalWPDB()->query("
                        INSERT INTO {$this->conf->getPrefix()}item_locations
                        (item_id, location_id, location_type, blog_id)
                        VALUES
                        ('{$validItemId}', '{$validReturnLocationId}', 2, '{$this->conf->getBlogId()}')
                    ");
                }
            }

            if($saved === FALSE)
            {
                $this->errorMessages[] = $this->lang->getText('NRS_ITEM_UPDATE_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('NRS_ITEM_UPDATED_TEXT');
            }

        } else if($ok)
        {
            // Add new car, if there is no errors

            /* *************************** WP POSTS PART: START ***************************  */
            $manufacturerRow = $this->conf->getInternalWPDB()->get_row("
                SELECT manufacturer_title
                FROM {$this->conf->getPrefix()}manufacturers
                WHERE manufacturer_id='{$validManufacturerId}'
            ", ARRAY_A);
            $manufacturerTitle = '';
            if(!is_null($manufacturerRow))
            {
                $manufacturerTitle = $manufacturerRow['manufacturer_title'];
            }

            // Create post object
            $wpItemPage = array(
                'post_title'    => $manufacturerTitle.' '.$sanitizedModelName,
                'post_content'  => '',
                'post_status'   => 'publish',
                'post_type'     => $this->conf->getExtensionPrefix().'item',
                /*'post_author'   => 1,*/ /*auto assign current user*/
                /*'post_category' => array(8,39)*/ /*no categories needed here*/
            );
            // Insert corresponding post as post type 'post'
            $validNewWPItemPageId = wp_insert_post( $wpItemPage, FALSE );
            /* *************************** WP POSTS PART: END ***************************  */

            $insertQuery = "
                INSERT INTO {$this->conf->getPrefix()}items
                (
                    item_sku, item_page_id,
                    partner_id, body_type_id, fuel_type_id, transmission_type_id,
                    manufacturer_id, model_name,
                    options_measurement_unit,
                    mileage, fuel_consumption, engine_capacity,
                    max_passengers, max_luggage, item_doors,
                    min_driver_age,
                    price_group_id, fixed_rental_deposit,
                    units_in_stock, max_units_per_booking,
                    enabled,
                    display_in_slider, display_in_item_list, display_in_price_table, display_in_calendar,
                    options_display_mode,
                    blog_id
                ) VALUES
                (
                    '{$validItemSKU}', '{$validNewWPItemPageId}',
                    '{$validPartnerId}', '{$validBodyTypeId}', '{$validFuelTypeId}', '{$validTransmissionTypeId}',
                    '{$validManufacturerId}', '{$validModelName}',
                    '{$validMeasurementUnit}',
                    '{$validItemMileage}', '{$validFuelConsumption}', '{$validEngineCapacity}',
                    '{$validMaxPassengers}', '{$validMaxLuggage}', '{$validItemDoors}',
                    '{$validMinDriverAge}',
                    '{$validPriceGroupId}', '{$validFixedRentalDeposit}',
                    '{$validUnitsInStock}', '{$validMaxItemUnitsPerBooking}',
                    '{$validEnabled}',
                    '{$validDisplayInSlider}', '{$validDisplayInItemList}', '{$validDisplayInPriceTable}', '{$validDisplayInCalendar}',
                    '{$validOptionsDisplayMode}',
                    '{$this->conf->getBlogId()}'
                )
            ";

            $saved = $this->conf->getInternalWPDB()->query($insertQuery);

            // We will process only if there one line was added to sql
            if($saved)
            {
                // Get newly inserted item id
                $validInsertedNewItemId = $this->conf->getInternalWPDB()->insert_id;

                // Update the core element id for future use
                $this->itemId = $validInsertedNewItemId;

                for($validImageCounter = 1; $validImageCounter <= 3; $validImageCounter++)
                {
                    $validUploadedImageFileName = '';
                    if($_FILES['item_image_'.$validImageCounter]['tmp_name'] != '')
                    {
                        $uploadedImageFileName = StaticFile::uploadImageFile($_FILES['item_image_'.$validImageCounter], $this->conf->getGalleryPathWithoutEndSlash(), "");
                        StaticFile::makeThumbnail($this->conf->getGalleryPath(), $uploadedImageFileName, $this->bigThumbWidth, $this->bigThumbHeight, "big_thumb_");
                        StaticFile::makeThumbnail($this->conf->getGalleryPath(), $uploadedImageFileName, $this->thumbWidth, $this->thumbHeight, "thumb_");
                        StaticFile::makeThumbnail($this->conf->getGalleryPath(), $uploadedImageFileName, $this->miniThumbWidth, $this->miniThumbHeight, "mini_thumb_");
                        $validUploadedImageFileName = esc_sql(sanitize_file_name($uploadedImageFileName)); // for sql query only
                    }

                    if($validUploadedImageFileName != '')
                    {
                        // Update the sql
                        $this->conf->getInternalWPDB()->query("
                            UPDATE {$this->conf->getPrefix()}items SET
                            item_image_{$validImageCounter}='{$validUploadedImageFileName}', demo_item_image_{$validImageCounter}='0'
                            WHERE item_id='{$validInsertedNewItemId}' AND blog_id='{$this->conf->getBlogId()}'
                        ");
                    }
                }

                /* *************************** WP POSTS PART: START ***************************  */
                // Create post object
                $wpItemPage = array(
                    'ID'            => $validNewWPItemPageId,
                    // content now will be updated and escaped securely
                    'post_content'  => wp_filter_kses(
'['.$this->conf->getShortcode().' display="'.$this->conf->getItemParameter().'" '.$this->conf->getItemParameter().'="'.$validInsertedNewItemId.'"]
['.$this->conf->getShortcode().' display="search" '.$this->conf->getItemParameter().'="'.$validInsertedNewItemId.'" steps="form,list,list,table,table"]'
                    ),
                );

                // Update corresponding post as post type 'post'
                wp_update_post($wpItemPage);
                /* *************************** WP POSTS PART: END ***************************  */


                foreach($arr_POST_FeatureIds AS $POST_FeatureId)
                {
                    $validFeatureId = StaticValidator::getValidPositiveInteger($POST_FeatureId);
                    $this->conf->getInternalWPDB()->query("
                          INSERT INTO {$this->conf->getPrefix()}item_features
                          (
                            item_id, feature_id, blog_id
                          ) VALUES
                          (
                            '{$validInsertedNewItemId}', '{$validFeatureId}', '{$this->conf->getBlogId()}'
                          )
                    ");
                }

                foreach($arr_POST_PickupLocationIds AS $POST_pickupLocationId)
                {
                    $validPickupLocationId = StaticValidator::getValidPositiveInteger($POST_pickupLocationId);
                    $this->conf->getInternalWPDB()->query("
                    INSERT INTO {$this->conf->getPrefix()}item_locations
                    (
                        item_id, location_id, location_type, blog_id
                    ) VALUES
                    (
                        '{$validInsertedNewItemId}', '{$validPickupLocationId}', '1', '{$this->conf->getBlogId()}'
                    )
                    ");
                }

                foreach($arr_POST_ReturnLocationIds AS $POST_returnLocationId)
                {
                    $validReturnLocationId = StaticValidator::getValidPositiveInteger($POST_returnLocationId);
                    $this->conf->getInternalWPDB()->query("
                        INSERT INTO {$this->conf->getPrefix()}item_locations
                        (
                            item_id, location_id, location_type, blog_id
                        ) VALUES
                        (
                            '{$validInsertedNewItemId}', '{$validReturnLocationId}', '2', '{$this->conf->getBlogId()}'
                        )
                    ");
                }
            }

            if($saved === FALSE || $saved === 0)
            {
                $this->errorMessages[] = $this->lang->getText('NRS_ITEM_INSERT_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('NRS_ITEM_INSERTED_TEXT');
            }
        }

        return $saved;
    }

    public function registerForTranslation()
    {
        $itemDetails = $this->getDetails();
        if(!is_null($itemDetails))
        {
            $this->lang->register("it{$this->itemId}_model_name", $itemDetails['model_name']);
            $this->okayMessages[] = $this->lang->getText('NRS_ITEM_REGISTERED_TEXT');
        }
    }

    /**
     * @note - due to tree repeatedness we don't want to remove discounts and options deletion from here
     * @return false|int
     */
    public function delete()
    {
        $deleted = FALSE;
        $itemDetails = $this->getDetails();
        if(!is_null($itemDetails))
        {
            // Delete corresponding item
            $deleted = $this->conf->getInternalWPDB()->query("
                DELETE FROM {$this->conf->getPrefix()}items WHERE item_id='{$itemDetails['item_id']}' AND blog_id='{$this->conf->getBlogId()}'
            ");

            if($deleted)
            {
                // NOTE: WE DON'T WANT TO DELETE BOOKINGS / BOOKING OPTIONS HERE, BECAUSE WE NEED TO TRACK THAT AND THERE CAN BE MORE THAN 1 ITEM PER BOOKING ID

                // Delete corresponding item features
                $this->conf->getInternalWPDB()->query("
                    DELETE FROM {$this->conf->getPrefix()}item_features WHERE item_id='{$itemDetails['item_id']}' AND blog_id='{$this->conf->getBlogId()}'
                ");

                // Delete corresponding item locations
                $this->conf->getInternalWPDB()->query("
                    DELETE FROM {$this->conf->getPrefix()}item_locations WHERE item_id='{$itemDetails['item_id']}' AND blog_id='{$this->conf->getBlogId()}'
                ");

                // Delete corresponding item options
                $this->conf->getInternalWPDB()->query("
                    DELETE FROM {$this->conf->getPrefix()}options WHERE item_id='{$itemDetails['item_id']}' AND blog_id='{$this->conf->getBlogId()}'
                ");

                // Unlink images
                if($itemDetails['demo_item_image_1'] == 0 && $itemDetails['item_image_1'] != "")
                {
                    unlink($this->conf->getGalleryPath().$itemDetails['item_image_1']);
                    unlink($this->conf->getGalleryPath()."thumb_".$itemDetails['item_image_1']);
                    unlink($this->conf->getGalleryPath()."big_thumb_".$itemDetails['item_image_1']);
                    unlink($this->conf->getGalleryPath()."mini_thumb_".$itemDetails['item_image_1']);
                }

                if($itemDetails['demo_item_image_2'] == 0 && $itemDetails['item_image_2'] != "")
                {
                    unlink($this->conf->getGalleryPath().$itemDetails['item_image_2']);
                    unlink($this->conf->getGalleryPath()."thumb_".$itemDetails['item_image_2']);
                    unlink($this->conf->getGalleryPath()."big_thumb_".$itemDetails['item_image_2']);
                    unlink($this->conf->getGalleryPath()."mini_thumb_".$itemDetails['item_image_2']);
                }

                if($itemDetails['demo_item_image_3'] == 0 && $itemDetails['item_image_3'] != "")
                {
                    unlink($this->conf->getGalleryPath().$itemDetails['item_image_3']);
                    unlink($this->conf->getGalleryPath()."thumb_".$itemDetails['item_image_3']);
                    unlink($this->conf->getGalleryPath()."big_thumb_".$itemDetails['item_image_3']);
                    unlink($this->conf->getGalleryPath()."mini_thumb_".$itemDetails['item_image_3']);
                }

                // Delete page
                wp_delete_post($itemDetails['item_page_id'], TRUE);
            }
        }

        if($deleted === FALSE || $deleted === 0)
        {
            $this->errorMessages[] = $this->lang->getText('NRS_ITEM_DELETE_ERROR_TEXT');
        } else
        {
            $this->okayMessages[] = $this->lang->getText('NRS_ITEM_DELETED_TEXT');
        }

        return $deleted;
    }
}