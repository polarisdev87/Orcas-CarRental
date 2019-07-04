<?php
/**
 * Location Manager

 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Location;
use NativeRentalSystem\Models\AbstractElement;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\File\StaticFile;
use NativeRentalSystem\Models\iElement;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;

class Location extends AbstractElement implements iElement
{
    protected $conf 	                = NULL;
    protected $lang 		            = NULL;
    protected $debugMode 	            = 0;

    protected $locationId               = 0;
    protected $bigThumbWidth	        = 360;
    protected $bigThumbHeight		    = 225;
    protected $thumbWidth	            = 240;
    protected $thumbHeight		        = 240;
    protected $miniThumbWidth	        = 100;
    protected $miniThumbHeight		    = 63;

    /**
     * @param ExtensionConfiguration &$paramConf
     * @param Language &$paramLang
     * @param array $paramSettings
     * @param int $paramLocationId
     */
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings, $paramLocationId)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;

        if(isset(
            $paramSettings['conf_location_big_thumb_w'], $paramSettings['conf_location_big_thumb_h'],
            $paramSettings['conf_location_thumb_w'], $paramSettings['conf_location_thumb_h'],
            $paramSettings['conf_location_mini_thumb_w'], $paramSettings['conf_location_mini_thumb_h']
        ))
        {
            // Set image dimensions
            $this->bigThumbWidth = StaticValidator::getValidPositiveInteger($paramSettings['conf_location_big_thumb_w'], 0);
            $this->bigThumbHeight = StaticValidator::getValidPositiveInteger($paramSettings['conf_location_big_thumb_h'], 0);
            $this->thumbWidth = StaticValidator::getValidPositiveInteger($paramSettings['conf_location_thumb_w'], 0);
            $this->thumbHeight = StaticValidator::getValidPositiveInteger($paramSettings['conf_location_thumb_h'], 0);
            $this->miniThumbWidth = StaticValidator::getValidPositiveInteger($paramSettings['conf_location_mini_thumb_w'], 0);
            $this->miniThumbHeight = StaticValidator::getValidPositiveInteger($paramSettings['conf_location_mini_thumb_h'], 0);
        }

        $this->locationId = StaticValidator::getValidPositiveInteger($paramLocationId);
    }

    /**
     * Get location data from MySQL database
     * @note - MUST BE PRIVATE. FOR INTERNAL USE ONLY
     * @param int $paramLocationId - primary it's this class unique id, with some exceptions when we call for afterhours id
     * @param string $dayOfWeek
     * @return mixed
     */
    private function getDataFromDatabaseById($paramLocationId, $dayOfWeek = "mon")
    {
        $SQLOpenTimeField = $this->getOpenTimeFieldByDayOfWeek($dayOfWeek);
        $SQLCloseTimeField = $this->getCloseTimeFieldByDayOfWeek($dayOfWeek);
        $SQLOpenStatusField = $this->getOpenStatusFieldByDayOfWeek($dayOfWeek);

        // For all items reservation
        $validLocationId = StaticValidator::getValidPositiveInteger($paramLocationId);
        $sqlQuery = "
			SELECT
				location_id, location_code, location_page_id, location_name,
				location_image_1, location_image_2, location_image_3, location_image_4,
				demo_location_image_1, demo_location_image_2, demo_location_image_3, demo_location_image_4,
				street_address, city, state, zip_code, country, phone, email,
				pickup_fee, return_fee,
				{$SQLOpenTimeField} AS open_time,
				{$SQLCloseTimeField} AS close_time,
				{$SQLOpenStatusField} AS open_today,
                      open_mondays,
                      open_tuesdays,
                      open_wednesdays,
                      open_thursdays,
                      open_fridays,
                      open_saturdays,
                      open_sundays,
                      open_time_mon,
                      open_time_tue,
                      open_time_wed,
                      open_time_thu,
                      open_time_fri,
                      open_time_sat,
                      open_time_sun,
                      close_time_mon,
                      close_time_tue,
                      close_time_wed,
                      close_time_thu,
                      close_time_fri,
                      close_time_sat,
                      close_time_sun,
                      lunch_enabled,
                      lunch_start_time,
                      lunch_end_time,
				afterhours_pickup_allowed, afterhours_pickup_location_id, afterhours_pickup_fee,
				afterhours_return_allowed, afterhours_return_location_id, afterhours_return_fee,
				location_order
			FROM {$this->conf->getPrefix()}locations
			WHERE location_id='{$validLocationId}'
		";
        $locationData = $this->conf->getInternalWPDB()->get_row($sqlQuery, ARRAY_A);

        // Debug
        //echo nl2br($sqlQuery);

        return $locationData;
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    public function getId()
    {
        return $this->locationId;
    }

    /**
     * Class-specific method
     */
    public function getNextInsertId()
    {
        $sqlQuery = "SELECT `auto_increment` FROM INFORMATION_SCHEMA.TABLES WHERE table_name = '{$this->conf->getPrefix()}locations'";
        $nextInsertId = $this->conf->getInternalWPDB()->get_var($sqlQuery);
        if(is_null($nextInsertId))
        {
            $nextInsertId = 0;
        }

        return $nextInsertId;
    }

    /**
     * Element-specific method
     * @return string
     */
    public function getCode()
    {
        $retCode = "";
        $locationData = $this->getDataFromDatabaseById($this->locationId);
        if(!is_null($locationData))
        {
            // Make raw
            $retCode = stripslashes($locationData['location_code']);
        }
        return $retCode;
    }

    /**
     * Element-specific method
     * @return string
     */
    public function getPrintCode()
    {
        return esc_html($this->getCode());
    }

    /**
     * Element-specific method
     * @return string
     */
    public function getEditCode()
    {
        return esc_attr($this->getCode());
    }

    /**
     * Element-specific method
     * @return string
     */
    public function generateCode()
    {
        $nextInsertId = 1;
        $sqlQuery = "
            SHOW TABLE STATUS LIKE '{$this->conf->getPrefix()}locations'
        ";
        $data = $this->conf->getInternalWPDB()->get_row($sqlQuery, ARRAY_A);
        if(!is_null($data))
        {
            $nextInsertId = $data['Auto_increment'];

        }

        $locationCode = 'LO_'.$nextInsertId;

        return $locationCode;
    }

    /**
     * Element-specific method
     * @return string
     */
    public function getAfterHoursPickupLocationId()
    {
        $retLocationId = 0;
        $locationData = $this->getDataFromDatabaseById($this->locationId);
        if(!is_null($locationData))
        {
            $retLocationId = $locationData['afterhours_pickup_location_id'];
        }
        return $retLocationId;
    }

    /**
     * Element-specific method
     * @return string
     */
    public function getAfterHoursReturnLocationId()
    {
        $retLocationId = 0;
        $locationData = $this->getDataFromDatabaseById($this->locationId);
        if(!is_null($locationData))
        {
            $retLocationId = $locationData['afterhours_return_location_id'];
        }
        return $retLocationId;
    }

    /**
     * Get open time field by day of week
     * @param string $paramDayOfWeek - 'all_week', 'mon', 'tue', wed', 'fri', 'sat', 'sun'
     * @return string
     */
    public function getOpenTimeFieldByDayOfWeek($paramDayOfWeek = "mon")
    {
        $SQLOpenTimeFields = array(
            "all_week" => "LEAST(open_time_mon, open_time_tue, open_time_wed, open_time_thu, open_time_fri, open_time_sat, open_time_sun)",
            "mon" => "open_time_mon",
            "tue" => "open_time_tue",
            "wed" => "open_time_wed",
            "thu" => "open_time_thu",
            "fri" => "open_time_fri",
            "sat" => "open_time_sat",
            "sun" => "open_time_sun",
        );

        // Main purpose of the action bellow is to lowercase the key
        $validDayOfWeek = sanitize_key($paramDayOfWeek);
        $SQLOpenTimeField = isset($SQLOpenTimeFields[$validDayOfWeek]) ? $SQLOpenTimeFields[$validDayOfWeek] : $SQLOpenTimeFields['mon'];

        return $SQLOpenTimeField;
    }

    /**
     * Get close time field by day of week
     * @param string $paramDayOfWeek - 'all_week', 'mon', 'tue', wed', 'fri', 'sat', 'sun'
     * @return string
     */
    public function getCloseTimeFieldByDayOfWeek($paramDayOfWeek = "mon")
    {
        $SQLCloseTimeFields = array(
            "all_week" => "GREATEST(close_time_mon, close_time_tue, close_time_wed, close_time_thu, close_time_fri, close_time_sat, close_time_sun)",
            "mon" => "close_time_mon",
            "tue" => "close_time_tue",
            "wed" => "close_time_wed",
            "thu" => "close_time_thu",
            "fri" => "close_time_fri",
            "sat" => "close_time_sat",
            "sun" => "close_time_sun",
        );

        // Main purpose of the action bellow is to lowercase the key
        $validDayOfWeek = sanitize_key($paramDayOfWeek);
        $SQLCloseTimeField = isset($SQLCloseTimeFields[$validDayOfWeek]) ? $SQLCloseTimeFields[$validDayOfWeek] : $SQLCloseTimeFields['mon'];

        return $SQLCloseTimeField;
    }

    /**
     * Get open status field by day of week
     * @param string $paramDayOfWeek - 'all_week', 'mon', 'tue', wed', 'fri', 'sat', 'sun'
     * @return string
     */
    public function getOpenStatusFieldByDayOfWeek($paramDayOfWeek = "mon")
    {
        $SQLOpenStatusFields = array(
            "all_week" => "LEAST(open_mondays, open_tuesdays, open_wednesdays, open_thursdays, open_fridays, open_saturdays, open_sundays)",
            "mon" => "open_mondays",
            "tue" => "open_tuesdays",
            "wed" => "open_wednesdays",
            "thu" => "open_thursdays",
            "fri" => "open_fridays",
            "sat" => "open_saturdays",
            "sun" => "open_sundays",
        );

        // Main purpose of the action bellow is to lowercase the key
        $validDayOfWeek = sanitize_key($paramDayOfWeek);
        $SQLOpenStatusField = isset($SQLOpenStatusFields[$validDayOfWeek]) ? $SQLOpenStatusFields[$validDayOfWeek] : $SQLOpenStatusFields['mon'];

        return $SQLOpenStatusField;
    }

    public function getDetails($paramIncludeUnclassified = FALSE)
    {
        return $this->getAllDetails("mon", "SELF", "SELF", $paramIncludeUnclassified);
    }

    public function getWeekdayDetails($paramDayOfWeek = "mon", $paramIncludeUnclassified = FALSE)
    {
        return $this->getAllDetails($paramDayOfWeek, "SELF", "SELF", $paramIncludeUnclassified);
    }

    public function getAfterHoursDetails($paramEarlyTime, $paramLateTime, $paramDayOfWeek = "mon", $paramIncludeUnclassified = FALSE)
    {
        return $this->getAllDetails($paramDayOfWeek, $paramEarlyTime, $paramLateTime, $paramIncludeUnclassified);
    }

    /**
     * Location data, plus extra field 'print_open_hours' (front end only)
     * @note 'print_open_hours' in front_end only, generated on the fly
     * @param string $paramDayOfWeek - 'mon', 'tue', wed', 'fri', 'sat', 'sun'
     * @param string $paramEarlyTime - "SELF" (for current location) or "HH:ii:ss"
     * @param string $paramLateTime - "SELF" (for current location) or "HH:ii:ss"
     * @param bool $paramIncludeUnclassified
     * @return mixed
     */
    private function getAllDetails($paramDayOfWeek = "mon", $paramEarlyTime = "SELF", $paramLateTime = "SELF", $paramIncludeUnclassified = FALSE)
    {
        $ret = $this->getDataFromDatabaseById($this->locationId, $paramDayOfWeek);
        if(!is_null($ret))
        {
            // Make raw
            $ret['location_code']    = stripslashes($ret['location_code']);
            $ret['location_name']    = stripslashes($ret['location_name']);
            $ret['location_image_1'] = stripslashes($ret['location_image_1']);
            $ret['location_image_2'] = stripslashes($ret['location_image_2']);
            $ret['location_image_3'] = stripslashes($ret['location_image_3']);
            $ret['location_image_4'] = stripslashes($ret['location_image_4']);
            $ret['street_address']   = stripslashes($ret['street_address']);
            $ret['city']             = stripslashes($ret['city']);
            $ret['state']            = stripslashes($ret['state']);
            $ret['zip_code']         = stripslashes($ret['zip_code']);
            $ret['country']          = stripslashes($ret['country']);
            $ret['phone']            = stripslashes($ret['phone']);
            $ret['email']            = stripslashes($ret['email']);

            // Process translate
            $ret['translated_location_name'] = $this->lang->getTranslated("lo{$ret['location_id']}_location_name", $ret['location_name']);
        } else if($paramIncludeUnclassified === TRUE)
        {
            // Create default for unclassified
            $ret = array();
            $ret['location_id'] = '0';
            $ret['location_page_id'] = '0';
			$ret['demo_location_image_1'] = '0';
            $ret['demo_location_image_2'] = '0';
            $ret['demo_location_image_3'] = '0';
            $ret['demo_location_image_4'] = '0';
			$ret['pickup_fee'] = '0.00';
            $ret['return_fee'] = '0.00';
            $ret['open_time'] = '00:00:00';
            $ret['close_time'] = '23:59:59';
            $ret['open_today'] = '0';
            $ret['open_mondays'] = '0';
            $ret['open_tuesdays'] = '0';
            $ret['open_wednesdays'] = '0';
            $ret['open_thursdays'] = '0';
            $ret['open_fridays'] = '0';
            $ret['open_saturdays'] = '0';
            $ret['open_sundays'] = '0';
            $ret['open_time_mon'] = '00:00:00';
            $ret['open_time_tue'] = '00:00:00';
            $ret['open_time_wed'] = '00:00:00';
            $ret['open_time_thu'] = '00:00:00';
            $ret['open_time_fri'] = '00:00:00';
            $ret['open_time_sat'] = '00:00:00';
            $ret['open_time_sun'] = '00:00:00';
            $ret['close_time_mon'] = '23:59:59';
            $ret['close_time_tue'] = '23:59:59';
            $ret['close_time_wed'] = '23:59:59';
            $ret['close_time_thu'] = '23:59:59';
            $ret['close_time_fri'] = '23:59:59';
            $ret['close_time_sat'] = '23:59:59';
            $ret['close_time_sun'] = '23:59:59';
            $ret['lunch_enabled'] = '0';
            $ret['lunch_start_time'] = '12:00:00';
            $ret['lunch_end_time'] = '13:00:00';
            $ret['afterhours_pickup_allowed'] = '0';
            $ret['afterhours_pickup_location_id'] = '0';
            $ret['afterhours_pickup_fee'] = '0.00';
            $ret['afterhours_return_allowed'] = '0';
            $ret['afterhours_return_location_id'] = '0';
            $ret['afterhours_return_fee'] = '0.00';
            $ret['location_order'] = '0';

            // Make raw
            $ret['location_code']    = '';
            $ret['location_name']    = '';
            $ret['location_image_1'] = '';
            $ret['location_image_2'] = '';
            $ret['location_image_3'] = '';
            $ret['location_image_4'] = '';
            $ret['street_address']   = '';
            $ret['city']             = '';
            $ret['state']            = '';
            $ret['zip_code']         = '';
            $ret['country']          = '';
            $ret['phone']            = '';
            $ret['email']            = '';

            // Process translate
            $ret['translated_location_name'] = '';
        }

        if(!is_null($ret) || $paramIncludeUnclassified === TRUE)
        {
            // Process print
            $arrPrintFullAddress = array();
            if($ret['street_address'] != '')
            {
                $arrPrintFullAddress[] = esc_html($ret['street_address']);
            }
            if($ret['city'] != '')
            {
                $arrPrintFullAddress[] = esc_html($ret['city']);
            }
            if($ret['state'] != '' && $ret['zip_code'] != '')
            {
                $arrPrintFullAddress[] = esc_html($ret['state'].' '.$ret['zip_code']);
            } else if($ret['state'] != '')
            {
                $arrPrintFullAddress[] = esc_html($ret['state']);
            } else if($ret['zip_code'] != '')
            {
                $arrPrintFullAddress[] = esc_html($ret['zip_code']);
            }
            if($ret['country'] != '')
            {
                $arrPrintFullAddress[] = esc_html($ret['country']);
            }
            $printFullAddress = implode(', ', $arrPrintFullAddress);
            $ret['two_lines_address'] = strlen($printFullAddress) > 40 ? TRUE : FALSE;

            $arrPrintContacts = array();
            if($ret['phone'] != '')
            {
                $arrPrintContacts[] = esc_html($ret['phone']);
            }
            if($ret['email'] != '')
            {
                $arrPrintContacts[] = esc_html($ret['email']);
            }
            $printContacts = implode('<br />', $arrPrintContacts);

            // Extend $location with additional details
            $image1_Folder = $ret['demo_location_image_1'] == 1 ? $this->conf->getExtensionDemoGalleryURL('', FALSE) : $this->conf->getGalleryURL();
            $image2_Folder = $ret['demo_location_image_2'] == 1 ? $this->conf->getExtensionDemoGalleryURL('', FALSE) : $this->conf->getGalleryURL();
            $image3_Folder = $ret['demo_location_image_3'] == 1 ? $this->conf->getExtensionDemoGalleryURL('', FALSE) : $this->conf->getGalleryURL();
            $image4_Folder = $ret['demo_location_image_4'] == 1 ? $this->conf->getExtensionDemoGalleryURL('', FALSE) : $this->conf->getGalleryURL();

            $locationPageURL = get_permalink($ret['location_page_id']);
            $ret['location_page_url'] = $ret['location_page_id'] != 0 && $locationPageURL != '' ? $locationPageURL : "";

            $ret['mini_thumb_url'] = $ret['location_image_1'] != "" ? $image1_Folder."mini_thumb_".$ret['location_image_1'] : "";
            $ret['thumb_url'] = $ret['location_image_1'] != "" ? $image1_Folder."thumb_".$ret['location_image_1'] : "";
            $ret['big_thumb_url'] = $ret['location_image_1'] != "" ? $image1_Folder."big_thumb_".$ret['location_image_1'] : "";
            $ret['image_url'] = $ret['location_image_1'] != "" ? $image1_Folder.$ret['location_image_1'] : "";

            $ret['mini_thumb_2_url'] = $ret['location_image_2'] != "" ? $image2_Folder."mini_thumb_".$ret['location_image_2'] : "";
            $ret['thumb_2_url'] = $ret['location_image_2'] != "" ? $image2_Folder."thumb_".$ret['location_image_2'] : "";
            $ret['big_thumb_2_url'] = $ret['location_image_2'] != "" ? $image2_Folder."big_thumb_".$ret['location_image_2'] : "";
            $ret['image_2_url'] = $ret['location_image_2'] != "" ? $image2_Folder.$ret['location_image_2'] : "";

            $ret['mini_thumb_3_url'] = $ret['location_image_3'] != "" ? $image3_Folder."mini_thumb_".$ret['location_image_3'] : "";
            $ret['thumb_3_url'] = $ret['location_image_3'] != "" ? $image3_Folder."thumb_".$ret['location_image_3'] : "";
            $ret['big_thumb_3_url'] = $ret['location_image_3'] != "" ? $image3_Folder."big_thumb_".$ret['location_image_3'] : "";
            $ret['image_3_url'] = $ret['location_image_3'] != "" ? $image3_Folder.$ret['location_image_3'] : "";

            $ret['mini_thumb_4_url'] = $ret['location_image_4'] != "" ? $image4_Folder."mini_thumb_".$ret['location_image_4'] : "";
            $ret['thumb_4_url'] = $ret['location_image_4'] != "" ? $image4_Folder."thumb_".$ret['location_image_4'] : "";
            $ret['big_thumb_4_url'] = $ret['location_image_4'] != "" ? $image4_Folder."big_thumb_".$ret['location_image_4'] : "";
            $ret['image_4_url'] = $ret['location_image_4'] != "" ? $image4_Folder.$ret['location_image_4'] : "";

            // Prepare output for print
            $ret['print_location_code']                  = esc_html($ret['location_code']);
            $ret['print_location_name']                  = esc_html($ret['location_name']);
            $ret['print_translated_location_name']       = esc_html($ret['translated_location_name']);
            $ret['print_street_address']                 = esc_html($ret['street_address']);
            $ret['print_city']                           = esc_html($ret['city']);
            $ret['print_state']                          = esc_html($ret['state']);
            $ret['print_zip_code']                       = esc_html($ret['zip_code']);
            $ret['print_country']                        = esc_html($ret['country']);
            $ret['print_full_address']                   = $printFullAddress;
            $ret['print_phone']                          = esc_html($ret['phone']);
            $ret['print_email']                          = esc_html($ret['email']);
            $ret['print_contacts']                       = $printContacts;

            // Location working hours
            if($ret['open_today'] == 1 && $ret['afterhours_pickup_location_id'] == 0)
            {
                // In after-hours this location is open all days
                $ret['start_time'] = "00:00:00";
                $ret['end_time'] = "23:59:59";
                $ret['print_start_time'] = date_i18n(get_option('time_format'), strtotime(date("Y-m-d")." 00:00:00"), TRUE);
                $ret['print_end_time'] = date_i18n(get_option('time_format'), strtotime(date("Y-m-d")." 23:59:59"), TRUE);
                if($paramEarlyTime == "SELF" && $paramLateTime == "SELF")
                {
                    // Use current location hours
                    $ret['works_early'] = $ret['open_time'] != "00:00:00" ? TRUE : FALSE;
                    $ret['works_late'] = $ret['close_time'] != "23:59:59" ? TRUE : FALSE;
                    $ret['print_open_hours'] = date_i18n(get_option('time_format'), strtotime(date("Y-m-d")." ".$ret['open_time']), TRUE);
                    $ret['print_open_hours'] .= ' - ';
                    $ret['print_open_hours'] .= date_i18n(get_option('time_format'), strtotime(date("Y-m-d")." ".$ret['close_time']), TRUE);
                } else
                {
                    $ret['works_early'] = strtotime(date("Y-m-d")." ".$ret['open_time']) < strtotime(date("Y-m-d")." ".$paramEarlyTime);
                    $ret['works_late'] = strtotime(date("Y-m-d")." ".$ret['close_time']) > strtotime(date("Y-m-d")." ".$paramLateTime);
                    $ret['print_open_hours'] = $this->lang->getText('NRS_ALL_DAY_TEXT');
                }
            } else if($ret['open_today'] == 1 && strtotime(date("Y-m-d")." ".$ret['open_time']) < strtotime(date("Y-m-d")." ".$ret['close_time']))
            {
                // IF ID > 0, then allow to return only during after hours location working hours
                $ret['works_early'] = strtotime(date("Y-m-d")." ".$ret['open_time']) < strtotime(date("Y-m-d")." ".$paramEarlyTime);
                $ret['works_late'] = strtotime(date("Y-m-d")." ".$ret['close_time']) > strtotime(date("Y-m-d")." ".$paramLateTime);
                $ret['start_time'] = $ret['open_time'];
                $ret['end_time'] = $ret['close_time'];
                $ret['print_start_time'] = date_i18n(get_option('time_format'), strtotime(date("Y-m-d")." ".$ret['open_time']), TRUE);
                $ret['print_end_time'] = date_i18n(get_option('time_format'), strtotime(date("Y-m-d")." ".$ret['close_time']), TRUE);
                $ret['print_open_hours'] .= date_i18n(get_option('time_format'), strtotime(date("Y-m-d")." ".$ret['open_time']), TRUE);
                $ret['print_open_hours'] .= ' - ';
                $ret['print_open_hours'] .= date_i18n(get_option('time_format'), strtotime(date("Y-m-d")." ".$ret['close_time']), TRUE);
            } else
            {
                // Closed today
                $ret['works_early'] = FALSE;
                $ret['works_late'] = FALSE;
                $ret['start_time'] = "";
                $ret['end_time'] = "";
                $ret['print_start_time'] = "";
                $ret['print_end_time'] = "";
                $ret['print_open_hours'] = $this->lang->getText('NRS_CLOSED_TEXT');
            }


            if($ret['lunch_enabled'] == 1 && strtotime(date("Y-m-d")." ".$ret['lunch_start_time']) < strtotime(date("Y-m-d")." ".$ret['lunch_end_time']))
            {
                $ret['print_lunch_hours']  = date_i18n(get_option('time_format'), strtotime(date("Y-m-d")." ".$ret['lunch_start_time']), TRUE);
                $ret['print_lunch_hours'] .= ' - ';
                $ret['print_lunch_hours'] .= date_i18n(get_option('time_format'), strtotime(date("Y-m-d")." ".$ret['lunch_end_time']), TRUE);
            } else
            {
                // No lunch - no lunch hours
                $ret['print_lunch_hours'] = "";
            }

            // Hours before location opening
            if($ret['works_early'])
            {
                // Exist
                $ret['early_start_time'] = $ret['start_time'];
                $ret['early_end_time'] = $ret['open_time'];
                $ret['print_early_hours'] = date_i18n(get_option('time_format'), strtotime(date("Y-m-d")." ".$ret['start_time']), TRUE);
                $ret['print_early_hours'] .= ' - ';
                $ret['print_early_hours'] .= date_i18n(get_option('time_format'), strtotime(date("Y-m-d")." ".$ret['open_time']), TRUE);
            } else
            {
                $ret['early_start_time'] = "";
                $ret['early_end_time'] = "";
                $ret['print_early_hours'] = "";
            }

            // Hours after location closing
            if($ret['works_late'])
            {
                // Exist
                $ret['late_start_time'] = $ret['close_time'];
                $ret['late_end_time'] = $ret['end_time'];
                $ret['print_late_hours'] = date_i18n(get_option('time_format'), strtotime(date("Y-m-d")." ".$ret['close_time']), TRUE);
                $ret['print_late_hours'] .= ' - ';
                $ret['print_late_hours'] .= date_i18n(get_option('time_format'), strtotime(date("Y-m-d")." ".$ret['end_time']), TRUE);
            } else
            {
                $ret['late_start_time'] = "";
                $ret['late_end_time'] = "";
                $ret['print_late_hours'] = "";
            }

            // Show
            $ret['show_full_address'] = $ret['print_full_address'] != "" ? TRUE : FALSE; // we must use print_ prefix here to shorten the code
            $ret['show_phone'] = $ret['phone'] != "" ? TRUE : FALSE;

            // Prepare output for edit
            $ret['edit_location_code']    = esc_attr($ret['location_code']); // for input field
            $ret['edit_location_name']    = esc_attr($ret['location_name']); // for input field
            $ret['edit_street_address']   = esc_attr($ret['street_address']); // for input field
            $ret['edit_city']             = esc_attr($ret['city']); // for input field
            $ret['edit_state']            = esc_attr($ret['state']); // for input field
            $ret['edit_zip_code']         = esc_attr($ret['zip_code']); // for input field
            $ret['edit_country']          = esc_attr($ret['country']); // for input field
            $ret['edit_phone']            = esc_attr($ret['phone']); // for input field
            $ret['edit_email']            = esc_attr($ret['email']); // for input field
        }

        return $ret;
    }

    /**
     * @return bool|false|int
     */
    public function save()
    {
        $saved = FALSE;
        $ok = TRUE;
        $validLocationId        = StaticValidator::getValidPositiveInteger($this->locationId, 0);

        // Do not use sanitize_key here, because we don't want to get it lowercase
        if($this->conf->isNetworkEnabled())
        {
            $sanitizedLocationCode = isset($_POST['location_code']) ? sanitize_text_field($_POST['location_code']) : '';
        } else
        {
            $sanitizedLocationCode = sanitize_text_field($validLocationId > 0 ? $this->getCode() : $this->generateCode());
        }
        $validLocationCode      = esc_sql($sanitizedLocationCode); // for sql queries only

        // If location data exist, otherwise - create a new page if that is a new item creation
        $validLocationPageId    = isset($_POST['location_page_id']) ? StaticValidator::getValidPositiveInteger($_POST['location_page_id'], 0) : 0;

        $sanitizedLocationName  = isset($_POST['location_name']) ? sanitize_text_field($_POST['location_name']) : '';
        $validLocationName      = esc_sql($sanitizedLocationName); // for sql queries only
        $sanitizedStreetAddress = isset($_POST['street_address']) ? sanitize_text_field($_POST['street_address']) : '';
        $validStreetAddress     = esc_sql($sanitizedStreetAddress); // for sql queries only
        $sanitizedCity          = isset($_POST['city']) ? sanitize_text_field($_POST['city']) : '';
        $validCity              = esc_sql($sanitizedCity); // for sql queries only
        $sanitizedState         = isset($_POST['state']) ? sanitize_text_field($_POST['state']) : '';
        $validState             = esc_sql($sanitizedState); // for sql queries only
        $sanitizedZipCode       = isset($_POST['zip_code']) ? sanitize_text_field($_POST['zip_code']) : '';
        $validZipCode           = esc_sql($sanitizedZipCode); // for sql queries only
        $sanitizedCountry       = isset($_POST['country']) ? sanitize_text_field($_POST['country']) : '';
        $validCountry           = esc_sql($sanitizedCountry); // for sql queries only
        $sanitizedPhone         = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
        $validPhone             = esc_sql($sanitizedPhone); // for sql queries only
        $sanitizedEmail         = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        $validEmail             = esc_sql($sanitizedEmail); // for sql queries only

        $validPickupFee         = isset($_POST['pickup_fee']) ? floatval($_POST['pickup_fee']) : '';
        $validReturnFee         = isset($_POST['return_fee']) ? floatval($_POST['return_fee']) : '';

        $validOpenMondays       = isset($_POST['open_mondays']) ? 1 : 0;
        $validOpenTuesdays      = isset($_POST['open_tuesdays']) ? 1 : 0;
        $validOpenWednesdays    = isset($_POST['open_wednesdays']) ? 1 : 0;
        $validOpenThursdays     = isset($_POST['open_thursdays']) ? 1 : 0;
        $validOpenFridays       = isset($_POST['open_fridays']) ? 1 : 0;
        $validOpenSaturdays     = isset($_POST['open_saturdays']) ? 1 : 0;
        $validOpenSundays       = isset($_POST['open_sundays']) ? 1 : 0;

        $validOpenTimeMon       = isset($_POST['open_time_mon']) ? StaticValidator::getValidISOTime($_POST['open_time_mon'], 'H:i:s') : '08:00:00';
        $validOpenTimeTue       = isset($_POST['open_time_tue']) ? StaticValidator::getValidISOTime($_POST['open_time_tue'], 'H:i:s') : '08:00:00';
        $validOpenTimeWed       = isset($_POST['open_time_wed']) ? StaticValidator::getValidISOTime($_POST['open_time_wed'], 'H:i:s') : '08:00:00';
        $validOpenTimeThu       = isset($_POST['open_time_thu']) ? StaticValidator::getValidISOTime($_POST['open_time_thu'], 'H:i:s') : '08:00:00';
        $validOpenTimeFri       = isset($_POST['open_time_fri']) ? StaticValidator::getValidISOTime($_POST['open_time_fri'], 'H:i:s') : '08:00:00';
        $validOpenTimeSat       = isset($_POST['open_time_sat']) ? StaticValidator::getValidISOTime($_POST['open_time_sat'], 'H:i:s') : '08:00:00';
        $validOpenTimeSun       = isset($_POST['open_time_sun']) ? StaticValidator::getValidISOTime($_POST['open_time_sun'], 'H:i:s') : '08:00:00';

        $validCloseTimeMon      = isset($_POST['close_time_mon']) ? StaticValidator::getValidISOTime($_POST['close_time_mon'], 'H:i:s') : '19:00:00';
        $validCloseTimeTue      = isset($_POST['close_time_tue']) ? StaticValidator::getValidISOTime($_POST['close_time_tue'], 'H:i:s') : '19:00:00';
        $validCloseTimeWed      = isset($_POST['close_time_wed']) ? StaticValidator::getValidISOTime($_POST['close_time_wed'], 'H:i:s') : '19:00:00';
        $validCloseTimeThu      = isset($_POST['close_time_thu']) ? StaticValidator::getValidISOTime($_POST['close_time_thu'], 'H:i:s') : '19:00:00';
        $validCloseTimeFri      = isset($_POST['close_time_fri']) ? StaticValidator::getValidISOTime($_POST['close_time_fri'], 'H:i:s') : '19:00:00';
        $validCloseTimeSat      = isset($_POST['close_time_sat']) ? StaticValidator::getValidISOTime($_POST['close_time_sat'], 'H:i:s') : '19:00:00';
        $validCloseTimeSun      = isset($_POST['close_time_sun']) ? StaticValidator::getValidISOTime($_POST['close_time_sun'], 'H:i:s') : '19:00:00';

        $validLunchEnabled      = isset($_POST['lunch_enabled']) ? 1 : 0;
        $validLunchStartTime    = isset($_POST['lunch_start_time']) ? StaticValidator::getValidISOTime($_POST['lunch_start_time'], 'H:i:s') : '12:00:00';
        $validLunchEndTime      = isset($_POST['lunch_end_time']) ? StaticValidator::getValidISOTime($_POST['lunch_end_time'], 'H:i:s') : '13:00:00';

        $validAfterHoursPickupAllowed           = isset($_POST['afterhours_pickup_allowed']) ? 1 : 0;
        $validAfterHoursPickupLocationId        = isset($_POST['afterhours_pickup_location_id']) ? StaticValidator::getValidPositiveInteger($_POST['afterhours_pickup_location_id'], 0) : 0;
        $validAfterHoursPickupFee               = isset($_POST['afterhours_return_fee']) ? floatval($_POST['afterhours_pickup_fee']) : 0.00;

        $validAfterHoursReturnAllowed           = isset($_POST['afterhours_return_allowed']) ? 1 : 0;
        $validAfterHoursReturnLocationId        = isset($_POST['afterhours_return_location_id']) ? StaticValidator::getValidPositiveInteger($_POST['afterhours_return_location_id'], 0) : 0;
        $validAfterHoursReturnFee               = isset($_POST['afterhours_return_fee']) ? floatval($_POST['afterhours_return_fee']) : 0.00;

        if(isset($_POST['location_order']) && StaticValidator::isPositiveInteger($_POST['location_order']))
        {
            $validLocationOrder = StaticValidator::getValidPositiveInteger($_POST['location_order'], 1);
        } else
        {
            // SELECT MAX
            $sqlQuery = "
                SELECT MAX(location_order) AS max_order
                FROM {$this->conf->getPrefix()}locations
                WHERE 1
            ";
            $maxOrderResult = $this->conf->getInternalWPDB()->get_var($sqlQuery);
            $validLocationOrder = !is_null($maxOrderResult) ? intval($maxOrderResult)+1 : 1;
        }

        $codeExistsQuery = "
            SELECT location_id
            FROM {$this->conf->getPrefix()}locations
            WHERE location_code='{$validLocationCode}'
            AND location_id!='{$validLocationId}' AND blog_id='{$this->conf->getBlogId()}'
        ";
        $titleExistsQuery = "
            SELECT location_id
            FROM {$this->conf->getPrefix()}locations
            WHERE location_name='{$validLocationName}'
            AND location_id!='{$validLocationId}' AND blog_id='{$this->conf->getBlogId()}'
        ";
        $codeExists = $this->conf->getInternalWPDB()->get_row($codeExistsQuery, ARRAY_A);
        $titleExists = $this->conf->getInternalWPDB()->get_row($titleExistsQuery, ARRAY_A);

        if(!is_null($codeExists))
        {
            $ok = FALSE;
            $this->errorMessages[] = $this->lang->getText('NRS_LOCATION_CODE_EXISTS_ERROR_TEXT');
        }
        if(!is_null($titleExists))
        {
            $ok = FALSE;
            $this->errorMessages[] = $this->lang->getText('NRS_LOCATION_NAME_EXISTS_ERROR_TEXT');
        }

        if($validLocationId > 0 && $ok)
        {
            // Update location
            $updateQuery = "
                UPDATE {$this->conf->getPrefix()}locations SET
                location_page_id='{$validLocationPageId}',
                location_name='{$validLocationName}',
                street_address='{$validStreetAddress}',
                city='{$validCity}',
                state='{$validState}',
                zip_code='{$validZipCode}',
                country='{$validCountry}',
                phone='{$validPhone}',
                email='{$validEmail}',
                pickup_fee='{$validPickupFee}',
                return_fee='{$validReturnFee}',
                open_mondays='{$validOpenMondays}',
                open_tuesdays='{$validOpenTuesdays}',
                open_wednesdays='{$validOpenWednesdays}',
                open_thursdays='{$validOpenThursdays}',
                open_fridays='{$validOpenFridays}',
                open_saturdays='{$validOpenSaturdays}',
                open_sundays='{$validOpenSundays}',
                open_time_mon='{$validOpenTimeMon}',
                open_time_tue='{$validOpenTimeTue}',
                open_time_wed='{$validOpenTimeWed}',
                open_time_thu='{$validOpenTimeThu}',
                open_time_fri='{$validOpenTimeFri}',
                open_time_sat='{$validOpenTimeSat}',
                open_time_sun='{$validOpenTimeSun}',
                close_time_mon='{$validCloseTimeMon}',
                close_time_tue='{$validCloseTimeTue}',
                close_time_wed='{$validCloseTimeWed}',
                close_time_thu='{$validCloseTimeThu}',
                close_time_fri='{$validCloseTimeFri}',
                close_time_sat='{$validCloseTimeSat}',
                close_time_sun='{$validCloseTimeSun}',
                lunch_enabled='{$validLunchEnabled}',
                lunch_start_time='{$validLunchStartTime}',
                lunch_end_time='{$validLunchEndTime}',
                afterhours_pickup_allowed='{$validAfterHoursPickupAllowed}',
                afterhours_pickup_location_id='{$validAfterHoursPickupLocationId}', afterhours_pickup_fee='{$validAfterHoursPickupFee}',
                afterhours_return_allowed='{$validAfterHoursReturnAllowed}',
                afterhours_return_location_id='{$validAfterHoursReturnLocationId}', afterhours_return_fee='{$validAfterHoursReturnFee}',
                location_order='{$validLocationOrder}'
                WHERE location_id='{$validLocationId}' AND blog_id='{$this->conf->getBlogId()}'
            ";

            // DEBUG
            //die(nl2br($updateQuery));

            $saved = $this->conf->getInternalWPDB()->query($updateQuery);

            // Only if there is error in query we will skip that, if no changes were made (and 0 was returned) we will still process
            if($saved !== FALSE)
            {
                $locationEditData = $this->conf->getInternalWPDB()->get_row("
                    SELECT *
                    FROM {$this->conf->getPrefix()}locations
                    WHERE location_id='{$validLocationId}' AND blog_id='{$this->conf->getBlogId()}'
                ", ARRAY_A);

                // Upload images
                for($validImageCounter = 1; $validImageCounter <= 4; $validImageCounter++)
                {
                    if(
                        isset($_POST['delete_location_image_'.$validImageCounter]) && $locationEditData['location_image_'.$validImageCounter] != "" &&
                        $locationEditData['demo_location_image_'.$validImageCounter] == 0
                    ) {
                        // Unlink files only if it's not a demo image
                        unlink($this->conf->getGalleryPath().$locationEditData['location_image_'.$validImageCounter]);
                        unlink($this->conf->getGalleryPath()."thumb_".$locationEditData['location_image_'.$validImageCounter]);
                        unlink($this->conf->getGalleryPath()."big_thumb_".$locationEditData['location_image_'.$validImageCounter]);
                        unlink($this->conf->getGalleryPath()."mini_thumb_".$locationEditData['location_image_'.$validImageCounter]);
                    }

                    $validUploadedImageFileName = '';
                    if($_FILES['location_image_'.$validImageCounter]['tmp_name'] != '')
                    {
                        $uploadedImageFileName = StaticFile::uploadImageFile($_FILES['location_image_'.$validImageCounter], $this->conf->getGalleryPathWithoutEndSlash(), "location_");
                        StaticFile::makeThumbnail($this->conf->getGalleryPath(), $uploadedImageFileName, $this->bigThumbWidth, $this->bigThumbHeight, "big_thumb_");
                        StaticFile::makeThumbnail($this->conf->getGalleryPath(), $uploadedImageFileName, $this->thumbWidth, $this->thumbHeight, "thumb_");
                        StaticFile::makeThumbnail($this->conf->getGalleryPath(), $uploadedImageFileName, $this->miniThumbWidth, $this->miniThumbHeight, "mini_thumb_");
                        $validUploadedImageFileName = esc_sql(sanitize_file_name($uploadedImageFileName)); // for sql query only
                    }

                    if($validUploadedImageFileName != '' || isset($_POST['delete_location_image_'.$validImageCounter]))
                    {
                        // Update the sql
                        $this->conf->getInternalWPDB()->query("
                            UPDATE {$this->conf->getPrefix()}locations SET
                            location_image_{$validImageCounter}='{$validUploadedImageFileName}', demo_location_image_{$validImageCounter}='0'
                            WHERE location_id='{$validLocationId}' AND blog_id='{$this->conf->getBlogId()}'
                        ");
                    }
                }
            }

            if($saved === FALSE)
            {
                $this->errorMessages[] = $this->lang->getText('NRS_LOCATION_UPDATE_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('NRS_LOCATION_UPDATED_TEXT');
            }
        } else if($ok)
        {
            // Add new location

            /* *************************** WP POSTS PART: START ***************************  */
            // Create post object
            $newLocationPage = array(
                'post_title'    => $sanitizedLocationName,
                'post_content'  => '',
                'post_status'   => 'publish',
                'post_type'     => $this->conf->getExtensionPrefix().'location',
            );
            // Insert corresponding location post
            $validNewLocationPageId = wp_insert_post( $newLocationPage, FALSE );
            /* *************************** WP POSTS PART: END ***************************  */

            $insertQuery = "
                INSERT INTO {$this->conf->getPrefix()}locations
                (
                    location_code,
                    location_page_id,
                    location_name,
                    street_address,
                    city,
                    state,
                    zip_code,
                    country,
                    phone,
                    email,
                    pickup_fee,
                    return_fee,
                    open_mondays,
                    open_tuesdays,
                    open_wednesdays,
                    open_thursdays,
                    open_fridays,
                    open_saturdays,
                    open_sundays,
                    open_time_mon,
                    open_time_tue,
                    open_time_wed,
                    open_time_thu,
                    open_time_fri,
                    open_time_sat,
                    open_time_sun,
                    close_time_mon,
                    close_time_tue,
                    close_time_wed,
                    close_time_thu,
                    close_time_fri,
                    close_time_sat,
                    close_time_sun,
                    lunch_enabled,
                    lunch_start_time,
                    lunch_end_time,
                    afterhours_pickup_allowed,
                    afterhours_pickup_location_id,
                    afterhours_pickup_fee,
                    afterhours_return_allowed,
                    afterhours_return_location_id,
                    afterhours_return_fee,
                    location_order,
                    blog_id
                ) VALUES
                (
                    '{$validLocationCode}',
                    '{$validNewLocationPageId}',
                    '{$validLocationName}',
                    '{$validStreetAddress}',
                    '{$validCity}',
                    '{$validState}',
                    '{$validZipCode}',
                    '{$validCountry}',
                    '{$validPhone}',
                    '{$validEmail}',
                    '{$validPickupFee}',
                    '{$validReturnFee}',
                    '{$validOpenMondays}',
                    '{$validOpenTuesdays}',
                    '{$validOpenWednesdays}',
                    '{$validOpenThursdays}',
                    '{$validOpenFridays}',
                    '{$validOpenSaturdays}',
                    '{$validOpenSundays}',
                    '{$validOpenTimeMon}',
                    '{$validOpenTimeTue}',
                    '{$validOpenTimeWed}',
                    '{$validOpenTimeThu}',
                    '{$validOpenTimeFri}',
                    '{$validOpenTimeSat}',
                    '{$validOpenTimeSun}',
                    '{$validCloseTimeMon}',
                    '{$validCloseTimeTue}',
                    '{$validCloseTimeWed}',
                    '{$validCloseTimeThu}',
                    '{$validCloseTimeFri}',
                    '{$validCloseTimeSat}',
                    '{$validCloseTimeSun}',
                    '{$validLunchEnabled}',
                    '{$validLunchStartTime}',
                    '{$validLunchEndTime}',
                    '{$validAfterHoursPickupAllowed}',
                    '{$validAfterHoursPickupLocationId}',
                    '{$validAfterHoursPickupFee}',
                    '{$validAfterHoursReturnAllowed}',
                    '{$validAfterHoursReturnLocationId}',
                    '{$validAfterHoursReturnFee}',
                    '{$validLocationOrder}',
                    '{$this->conf->getBlogId()}'
                )
            ";

            $saved = $this->conf->getInternalWPDB()->query($insertQuery);

            // We will process only if there one line was added to sql
            if($saved)
            {
                // Get newly inserted location id
                $validInsertedNewLocationId = $this->conf->getInternalWPDB()->insert_id;

                // Update the core location id for future use
                $this->locationId = $validInsertedNewLocationId;

                for($validImageCounter = 1; $validImageCounter <= 3; $validImageCounter++)
                {
                    $validUploadedImageFileName = '';
                    if($_FILES['location_image_'.$validImageCounter]['tmp_name'] != '')
                    {
                        $uploadedImageFileName = StaticFile::uploadImageFile($_FILES['location_image_'.$validImageCounter], $this->conf->getGalleryPathWithoutEndSlash(), "location_");
                        StaticFile::makeThumbnail($this->conf->getGalleryPath(), $uploadedImageFileName, $this->bigThumbWidth, $this->bigThumbHeight, "big_thumb_");
                        StaticFile::makeThumbnail($this->conf->getGalleryPath(), $uploadedImageFileName, $this->thumbWidth, $this->thumbHeight, "thumb_");
                        StaticFile::makeThumbnail($this->conf->getGalleryPath(), $uploadedImageFileName, $this->miniThumbWidth, $this->miniThumbHeight, "mini_thumb_");
                        $validUploadedImageFileName = esc_sql(sanitize_file_name($uploadedImageFileName)); // for sql query only
                    }

                    if($validUploadedImageFileName != '')
                    {
                        // Update the sql
                        $this->conf->getInternalWPDB()->query("
                            UPDATE {$this->conf->getPrefix()}locations SET
                            location_image_{$validImageCounter}='{$validUploadedImageFileName}', demo_location_image_{$validImageCounter}='0'
                            WHERE location_id='{$validInsertedNewLocationId}' AND blog_id='{$this->conf->getBlogId()}'
                        ");
                    }
                }

                /* *************************** WP POSTS PART: START ***************************  */
                // Create post object
                $wpLocationPage = array(
                    'ID'            => $validNewLocationPageId,
                    // content now will be updated and escaped securely
                    'post_content'  => wp_filter_kses(
'['.$this->conf->getShortcode().' display="location" location="'.$validInsertedNewLocationId.'"]
['.$this->conf->getShortcode().' display="search" location="'.$validInsertedNewLocationId.'" steps="form,list,list,table,table"]'
                    ),
                );

                // Update corresponding post as post type 'post'
                wp_update_post($wpLocationPage);
                /* *************************** WP POSTS PART: END ***************************  */
            }

            if($saved === FALSE || $saved === 0)
            {
                $this->errorMessages[] = $this->lang->getText('NRS_LOCATION_INSERT_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('NRS_LOCATION_INSERTED_TEXT');
            }
        }

        if($this->debugMode)
        {
            // Show debug log
            die();
        }

        return $saved;
    }

    public function registerForTranslation()
    {
        $locationDetails = $this->getDetails();
        if(!is_null($locationDetails))
        {
            $this->lang->register("lo{$this->locationId}_location_name", $locationDetails['location_name']);
            $this->okayMessages[] = $this->lang->getText('NRS_LOCATION_REGISTERED_TEXT');
        }
    }

    public function delete()
    {
        $deleted = FALSE;
        $locationDetails = $this->getDetails();
        if(!is_null($locationDetails))
        {
            if($locationDetails['demo_location_image_1'] == 0 && $locationDetails['location_image_1'] != "")
            {
                unlink($this->conf->getGalleryPath().$locationDetails['location_image_1']);
                unlink($this->conf->getGalleryPath()."thumb_".$locationDetails['location_image_1']);
                unlink($this->conf->getGalleryPath()."big_thumb_".$locationDetails['location_image_1']);
                unlink($this->conf->getGalleryPath()."mini_thumb_".$locationDetails['location_image_1']);
            }

            if($locationDetails['demo_location_image_2'] == 0 && $locationDetails['location_image_2'] != "")
            {
                unlink($this->conf->getGalleryPath().$locationDetails['location_image_2']);
                unlink($this->conf->getGalleryPath()."thumb_".$locationDetails['location_image_2']);
                unlink($this->conf->getGalleryPath()."big_thumb_".$locationDetails['location_image_2']);
                unlink($this->conf->getGalleryPath()."mini_thumb_".$locationDetails['location_image_2']);
            }

            if($locationDetails['demo_location_image_3'] == 0 && $locationDetails['location_image_3'] != "")
            {
                unlink($this->conf->getGalleryPath().$locationDetails['location_image_3']);
                unlink($this->conf->getGalleryPath()."thumb_".$locationDetails['location_image_3']);
                unlink($this->conf->getGalleryPath()."big_thumb_".$locationDetails['location_image_3']);
                unlink($this->conf->getGalleryPath()."mini_thumb_".$locationDetails['location_image_3']);
            }

            if($locationDetails['demo_location_image_4'] == 0 && $locationDetails['location_image_4'] != "")
            {
                unlink($this->conf->getGalleryPath().$locationDetails['location_image_4']);
                unlink($this->conf->getGalleryPath()."thumb_".$locationDetails['location_image_4']);
                unlink($this->conf->getGalleryPath()."big_thumb_".$locationDetails['location_image_4']);
                unlink($this->conf->getGalleryPath()."mini_thumb_".$locationDetails['location_image_4']);
            }

            // Delete page
            wp_delete_post($locationDetails['location_page_id'], TRUE);

            $deleted = $this->conf->getInternalWPDB()->query("
                DELETE FROM {$this->conf->getPrefix()}locations
                WHERE location_id='{$locationDetails['location_id']}' AND blog_id='{$this->conf->getBlogId()}'
            ");
            // Delete all assigned item locations for this location id
            $this->conf->getInternalWPDB()->query("
                DELETE FROM {$this->conf->getPrefix()}item_locations
                WHERE location_id='{$locationDetails['location_id']}' AND blog_id='{$this->conf->getBlogId()}'
            ");
            // Delete all distances with this location id
            $this->conf->getInternalWPDB()->query("
                DELETE FROM {$this->conf->getPrefix()}distances
                WHERE (pickup_location_id='{$locationDetails['location_id']}' OR return_location_id='{$locationDetails['location_id']}')
                AND blog_id='{$this->conf->getBlogId()}'
            ");
        }

        if($deleted === FALSE || $deleted === 0)
        {
            $this->errorMessages[] = $this->lang->getText('NRS_LOCATION_DELETE_ERROR_TEXT');
        } else
        {
            $this->okayMessages[] = $this->lang->getText('NRS_LOCATION_DELETED_TEXT');
        }

        return $deleted;
    }


    /*******************************************************************************/
    /************************* ELEMENT SPECIFIC FUNCTIONS **************************/
    /*******************************************************************************/

    /**
     * Returns true if current location is closed today, or if current time is before it's opening or after it's closing
     * @param $paramDayOfWeek
     * @param $paramTimeToCheck
     * @return bool
     */
    public function isAfterHoursTime($paramDayOfWeek, $paramTimeToCheck)
    {
        $validTimeToCheck = StaticValidator::getValidISOTime($paramTimeToCheck, 'H:i:s');
        $afterHoursTime = FALSE;

        $locationData = $this->getDataFromDatabaseById($this->locationId, $paramDayOfWeek);
        if(!is_null($locationData))
        {
            if($locationData['open_today'] == 0 || $validTimeToCheck < $locationData['open_time'] || $validTimeToCheck > $locationData['close_time'])
            {
                $afterHoursTime = TRUE;
            }
        }

        return $afterHoursTime;
    }

    /**
     * Check if location is not blocked by admin for specified date
     * @note - we use location_code here and we don't use blog_id to support network date blocks
     * @param $paramISODate
     * @return bool
     */
    public function isOpenAtDate($paramISODate)
    {
        $validDate = StaticValidator::getValidISODate($paramISODate, 'Y-m-d');
        $validLocationCode = esc_sql(sanitize_text_field($this->getCode())); // for sql queries only

        $sql = "SELECT closed_date
            FROM {$this->conf->getPrefix()}closed_dates
            WHERE closed_date='{$validDate}' AND (location_code='' OR location_code='{$validLocationCode}')
        ";
        //echo nl2br($sql);
        $blockedDate = $this->conf->getInternalWPDB()->get_row($sql, ARRAY_A);
        if(!is_null($blockedDate))
        {
            // Location is closed at this date - is blocked for selected date
            return FALSE;
        } else
        {
            // Location is open at this date - is not blocked for selected date
            return TRUE;
        }
    }

    /**
     * @param $paramTime
     * @param string $paramDayOfWeek - 'mon', 'tue', wed', 'fri', 'sat', 'sun'
     * @return bool
     */
    public function isOpenAtTime($paramTime, $paramDayOfWeek = "mon")
    {
        $isOpen = FALSE;
        $locationDetails = $this->getWeekdayDetails($paramDayOfWeek);

        if(!is_null($locationDetails) && $locationDetails['open_today'] == 1
            && strtotime(date("Y-m-d")." ".$paramTime) >= strtotime(date("Y-m-d")." ".$locationDetails['open_time'])
            && strtotime(date("Y-m-d")." ".$paramTime) <= strtotime(date("Y-m-d")." ".$locationDetails['close_time'])
        ) {
            $isOpen = TRUE;
        }

        if($this->debugMode)
        {
            echo "<br /><strong>[CHECK]</strong> Is location (id=".$this->locationId.") open at ".esc_html(sanitize_text_field($paramTime)).": ".var_export($isOpen, TRUE);
        }

        return $isOpen;
    }

    /**
     * Result is TRUE/FALSE to describe is location in complex or not.
     * @return bool
     */
    public function isComplexLocation()
    {
        $isComplexLocation = FALSE;
        $locationDetails = $this->getDetails();

        if(!is_null($locationDetails))
        {
            // Complex check
            if($locationDetails['open_time'] != "00:00:00" || $locationDetails['close_time'] != "23:59:59")
            {
                // Does not work 24/7
                if($locationDetails['afterhours_pickup_allowed'] == 1 && $locationDetails['afterhours_pickup_location_id'] > 0)
                {
                    // Afterhours pickup is not is the same location, so location is complex
                    $isComplexLocation = TRUE;
                    /***DEBUG***/ if($this->debugMode) { echo "<br />[LOCATION ID={$locationDetails['location_id']}] IS COMPLEX - afterhours pickup is not in the same location"; }
                } else if($locationDetails['afterhours_return_allowed'] == 1 && $locationDetails['afterhours_return_location_id'] > 0)
                {
                    // Afterhours return is not is the same location, so location is complex
                    $isComplexLocation = TRUE;
                    /***DEBUG***/ if($this->debugMode) { echo "<br />[LOCATION ID={$locationDetails['location_id']}] IS COMPLEX - afterhours return is not in the same location"; }
                }
            }
        }

        return $isComplexLocation;
    }

    /**
     * @param string $paramDayOfWeek - 'mon', 'tue', wed', 'fri', 'sat', 'sun'
     * @param string $paramEarlyTime
     * @param string $paramLateTime
     * @return bool
     */
    public function isValidForAfterHoursPickup($paramDayOfWeek, $paramEarlyTime, $paramLateTime)
    {
        $isValidForAfterHoursPickup = FALSE;
        $validEarlyTime = StaticValidator::getValidISOTime($paramEarlyTime, 'H:i:s');
        $validLateTime = StaticValidator::getValidISOTime($paramLateTime, 'H:i:s');

        // We will only give results if after hours pick-up/return location exist,
        // and it's open or close hour is earlier/later than checked open/close hour or it's 24/7
        $locationDetails = $this->getWeekdayDetails($paramDayOfWeek);

        if(!is_null($locationDetails) && $locationDetails['afterhours_pickup_allowed'] == 1 && $locationDetails['afterhours_pickup_location_id'] == 0)
        {
            // Works 24/7 in same location
            $isValidForAfterHoursPickup = TRUE;
        } else if(!is_null($locationDetails) && $locationDetails['open_today'] == 1)
        {
            if(strtotime(date("Y-m-d")." ".$locationDetails['open_time']) < strtotime(date("Y-m-d")." ".$validEarlyTime) ||
                strtotime(date("Y-m-d")." ".$locationDetails['close_time']) > strtotime(date("Y-m-d")." ".$validLateTime)
            ) {
                $isValidForAfterHoursPickup = TRUE;
            }
        }

        return $isValidForAfterHoursPickup;
    }

    /**
     * @param string $paramDayOfWeek - 'mon', 'tue', wed', 'fri', 'sat', 'sun'
     * @param string $paramEarlyTime
     * @param string $paramLateTime
     * @return bool
     */
    public function isValidForAfterHoursReturn($paramDayOfWeek, $paramEarlyTime, $paramLateTime)
    {
        $isValidForAfterHoursReturn = FALSE;
        $validEarlyTime = StaticValidator::getValidISOTime($paramEarlyTime, 'H:i:s');
        $validLateTime = StaticValidator::getValidISOTime($paramLateTime, 'H:i:s');

        // We will only give results if after hours pick-up/return location exist,
        // and it's open or close hour is earlier/later than checked open/close hour or it's 24/7
        $locationDetails = $this->getWeekdayDetails($paramDayOfWeek);
        if(!is_null($locationDetails) && $locationDetails['afterhours_return_allowed'] == 1 && $locationDetails['afterhours_return_location_id'] == 0)
        {
            // Works 24/7 in same location
            $isValidForAfterHoursReturn = TRUE;
        } else if(!is_null($locationDetails) && $locationDetails['open_today'] == 1)
        {
            if(strtotime(date("Y-m-d")." ".$locationDetails['open_time']) < strtotime(date("Y-m-d")." ".$validEarlyTime) ||
                strtotime(date("Y-m-d")." ".$locationDetails['close_time']) > strtotime(date("Y-m-d")." ".$validLateTime))
            {
                $isValidForAfterHoursReturn = TRUE;
            }
        }

        return $isValidForAfterHoursReturn;
    }

    public function getWeekEarliestPickupTime()
    {
        $retEarliestTime = "00:00:00";
        $locationDetails = $this->getWeekdayDetails("all_week");

        if(!is_null($locationDetails) && $locationDetails['afterhours_pickup_allowed'] == 1 && $locationDetails['afterhours_pickup_location_id'] == 0)
        {
            // Works 24/7 in same location
            $retEarliestTime = "00:00:00";
        } else if(!is_null($locationDetails))
        {
            $retEarliestTime = $locationDetails['open_time'];
        }

        if($this->debugMode)
        {
            echo "<br />Earliest pickup time: {$retEarliestTime}";
        }

        return $retEarliestTime;
    }

    public function getWeekLatestPickupTime()
    {
        $retLatestTime = "23:59:59";
        $locationDetails = $this->getWeekdayDetails("all_week");
        if(!is_null($locationDetails) && $locationDetails['afterhours_pickup_allowed'] == 1 && $locationDetails['afterhours_pickup_location_id'] == 0)
        {
            // Works 24/7 in same location
            $retLatestTime = "23:59:59";
        } else if(!is_null($locationDetails))
        {
            $retLatestTime = $locationDetails['close_time'];
        }

        if($this->debugMode)
        {
            echo "<br />Latest pickup time: {$retLatestTime}";
        }

        return $retLatestTime;
    }

    public function getWeekEarliestReturnTime()
    {
        $retEarliestTime = "00:00:00";
        $locationDetails = $this->getWeekdayDetails("all_week");
        if(!is_null($locationDetails) && $locationDetails['afterhours_return_allowed'] == 1 && $locationDetails['afterhours_return_location_id'] == 0)
        {
            // Works 24/7 in same location
            $retEarliestTime = "00:00:00";
        } else if(!is_null($locationDetails))
        {
            $retEarliestTime = $locationDetails['open_time'];
        }

        if($this->debugMode)
        {
            echo "<br />Earliest return time: {$retEarliestTime}";
        }

        return $retEarliestTime;
    }

    public function getWeekLatestReturnTime()
    {
        $retLatestTime = "23:59:59";
        $locationDetails = $this->getWeekdayDetails("all_week");
        if(!is_null($locationDetails) && $locationDetails['afterhours_return_allowed'] == 1 && $locationDetails['afterhours_return_location_id'] == 0)
        {
            // Works 24/7 in same location
            $retLatestTime = "23:59:59";
        } else if(!is_null($locationDetails))
        {
            $retLatestTime = $locationDetails['close_time'];
        }

        if($this->debugMode)
        {
            echo "<br />Latest return time: {$retLatestTime}";
        }

        return $retLatestTime;
    }

    public function getPrintTranslatedLocationName()
    {
        return $this->getPrintLocationName(TRUE);
    }

    public function getPrintLocationName($paramTranslated = FALSE)
    {
        $printLocationName = "";
        $locationDetails = $this->getDetails();
        if(!is_null($locationDetails))
        {
            $printLocationName = $paramTranslated ? $locationDetails['print_translated_location_name'] : $locationDetails['print_location_name'];
        }

        return $printLocationName;
    }

    public function getPrintTranslatedLocationNameWithFullAddress()
    {
        return $this->getPrintLocationNameWithFullAddress(TRUE);
    }

    public function getPrintLocationNameWithFullAddress($paramTranslated = FALSE)
    {
        $printLocationNameWithFullAddress = "";
        $locationDetails = $this->getDetails();
        if(!is_null($locationDetails))
        {
            $printLocationNameWithFullAddress = $paramTranslated ? $locationDetails['print_translated_location_name'] : $locationDetails['print_location_name'];
            $printLocationNameWithFullAddress .= $locationDetails['show_full_address'] ? ' - '.$locationDetails['print_full_address'] : '';
        }

        return $printLocationNameWithFullAddress;
    }

    public function getPrintBusinessHoursWithShortDayName()
    {
        $weeklyBusinessHours = $this->getBusinessHours();

        $retPrintBusinessHoursWithShortDayName = "";
        $retPrintBusinessHoursWithShortDayName .= $this->lang->getText('NRS_MON_TEXT').": ".$weeklyBusinessHours['mon']."<br />";
        $retPrintBusinessHoursWithShortDayName .= $this->lang->getText('NRS_TUE_TEXT').": ".$weeklyBusinessHours['mon']."<br />";
        $retPrintBusinessHoursWithShortDayName .= $this->lang->getText('NRS_WED_TEXT').": ".$weeklyBusinessHours['mon']."<br />";
        $retPrintBusinessHoursWithShortDayName .= $this->lang->getText('NRS_THU_TEXT').": ".$weeklyBusinessHours['thu']."<br />";
        $retPrintBusinessHoursWithShortDayName .= $this->lang->getText('NRS_FRI_TEXT').": ".$weeklyBusinessHours['fri']."<br />";
        $retPrintBusinessHoursWithShortDayName .= $this->lang->getText('NRS_SAT_TEXT').": ".$weeklyBusinessHours['sat']."<br />";
        $retPrintBusinessHoursWithShortDayName .= $this->lang->getText('NRS_SUN_TEXT').": ".$weeklyBusinessHours['sun'];

        return $retPrintBusinessHoursWithShortDayName;
    }

    public function getPrintBusinessHoursWithDayName()
    {
        $weeklyBusinessHours = $this->getBusinessHours();

        $retPrintBusinessHoursWithDayName = "";
        $retPrintBusinessHoursWithDayName .= $this->lang->getText('NRS_MONDAYS_TEXT').": ".$weeklyBusinessHours['mon']."<br />";
        $retPrintBusinessHoursWithDayName .= $this->lang->getText('NRS_TUESDAYS_TEXT').": ".$weeklyBusinessHours['mon']."<br />";
        $retPrintBusinessHoursWithDayName .= $this->lang->getText('NRS_WEDNESDAYS_TEXT').": ".$weeklyBusinessHours['mon']."<br />";
        $retPrintBusinessHoursWithDayName .= $this->lang->getText('NRS_THURSDAYS_TEXT').": ".$weeklyBusinessHours['thu']."<br />";
        $retPrintBusinessHoursWithDayName .= $this->lang->getText('NRS_FRIDAYS_TEXT').": ".$weeklyBusinessHours['fri']."<br />";
        $retPrintBusinessHoursWithDayName .= $this->lang->getText('NRS_SATURDAYS_TEXT').": ".$weeklyBusinessHours['sat']."<br />";
        $retPrintBusinessHoursWithDayName .= $this->lang->getText('NRS_SUNDAYS_TEXT').": ".$weeklyBusinessHours['sun'];

        return $retPrintBusinessHoursWithDayName;
    }

    public function getPrintShortLunchHours()
    {
        $lunchHours = $this->getLunchHours();
        $retPrintLunchHours = $lunchHours != '' ? $this->lang->getText('NRS_LUNCH_TEXT').': '.$lunchHours : '';

        return $retPrintLunchHours;
    }

    public function getPrintLunchHours()
    {
        $lunchHours = $this->getLunchHours();
        $retPrintLunchHours = $lunchHours != '' ? $this->lang->getText('NRS_LUNCH_TIME_TEXT').': '.$lunchHours : '';

        return $retPrintLunchHours;
    }

    public function getPrintBusinessHoursForWeekday($paramDayOfWeek)
    {
        $weeklyBusinessHours = $this->getBusinessHours();
        $validDayOfWeek = sanitize_key($paramDayOfWeek);
        $printBusinessHours = isset($weeklyBusinessHours[$validDayOfWeek]) ? $weeklyBusinessHours[$validDayOfWeek] : '';

        return $printBusinessHours;
    }

    public function getBusinessHours()
    {
        $locationDetails = $this->getDetails();

        // Set defaults
        $retOpenHours = array(
            "mon" => "",
            "tue" => "",
            "wed" => "",
            "thu" => "",
            "fri" => "",
            "sat" => "",
            "sun" => "",
        );
        if(!is_null($locationDetails))
        {
            if($locationDetails['open_mondays'] == "1" && strtotime(date("Y-m-d")." ".$locationDetails['open_time_mon']) < strtotime(date("Y-m-d")." ".$locationDetails['close_time_mon']))
            {
                $retOpenHours['mon']  = date_i18n(get_option('time_format'), strtotime(date("Y-m-d")." ".$locationDetails['open_time_mon']), TRUE);
                $retOpenHours['mon'] .= ' - ';
                $retOpenHours['mon'] .= date_i18n(get_option('time_format'), strtotime(date("Y-m-d")." ".$locationDetails['close_time_mon']), TRUE);
            } else
            {
                $retOpenHours['mon'] = $this->lang->getText('NRS_CLOSED_TEXT');
            }

            if($locationDetails['open_tuesdays'] == "1" && strtotime(date("Y-m-d")." ".$locationDetails['open_time_tue']) < strtotime(date("Y-m-d")." ".$locationDetails['close_time_tue']))
            {
                $retOpenHours['tue']  = date_i18n(get_option('time_format'), strtotime(date("Y-m-d")." ".$locationDetails['open_time_tue']), TRUE);
                $retOpenHours['tue'] .= ' - ';
                $retOpenHours['tue'] .= date_i18n(get_option('time_format'), strtotime(date("Y-m-d")." ".$locationDetails['close_time_tue']), TRUE);
            } else
            {
                $retOpenHours['tue'] = $this->lang->getText('NRS_CLOSED_TEXT');
            }

            if($locationDetails['open_wednesdays'] == "1" && strtotime(date("Y-m-d")." ".$locationDetails['open_time_wed']) < strtotime(date("Y-m-d")." ".$locationDetails['close_time_wed']))
            {
                $retOpenHours['wed']  = date_i18n(get_option('time_format'), strtotime(date("Y-m-d")." ".$locationDetails['open_time_wed']), TRUE);
                $retOpenHours['wed'] .= ' - ';
                $retOpenHours['wed'] .= date_i18n(get_option('time_format'), strtotime(date("Y-m-d")." ".$locationDetails['close_time_wed']), TRUE);
            } else
            {
                $retOpenHours['wed'] = $this->lang->getText('NRS_CLOSED_TEXT');
            }

            if($locationDetails['open_thursdays'] == "1" && strtotime(date("Y-m-d")." ".$locationDetails['open_time_thu']) < strtotime(date("Y-m-d")." ".$locationDetails['close_time_thu']))
            {
                $retOpenHours['thu']  = date_i18n(get_option('time_format'), strtotime(date("Y-m-d")." ".$locationDetails['open_time_thu']), TRUE);
                $retOpenHours['thu'] .= ' - ';
                $retOpenHours['thu'] .= date_i18n(get_option('time_format'), strtotime(date("Y-m-d")." ".$locationDetails['close_time_thu']), TRUE);
            } else
            {
                $retOpenHours['thu'] = $this->lang->getText('NRS_CLOSED_TEXT');
            }

            if($locationDetails['open_fridays'] == "1" && strtotime(date("Y-m-d")." ".$locationDetails['open_time_fri']) < strtotime(date("Y-m-d")." ".$locationDetails['close_time_fri']))
            {
                $retOpenHours['fri']  = date_i18n(get_option('time_format'), strtotime(date("Y-m-d")." ".$locationDetails['open_time_fri']), TRUE);
                $retOpenHours['fri'] .= ' - ';
                $retOpenHours['fri'] .= date_i18n(get_option('time_format'), strtotime(date("Y-m-d")." ".$locationDetails['close_time_fri']), TRUE);
            } else
            {
                $retOpenHours['fri'] = $this->lang->getText('NRS_CLOSED_TEXT');
            }

            if($locationDetails['open_saturdays'] == "1" && strtotime(date("Y-m-d")." ".$locationDetails['open_time_sat']) < strtotime(date("Y-m-d")." ".$locationDetails['close_time_sat']))
            {
                $retOpenHours['sat']  = date_i18n(get_option('time_format'), strtotime(date("Y-m-d")." ".$locationDetails['open_time_sat']), TRUE);
                $retOpenHours['sat'] .= ' - ';
                $retOpenHours['sat'] .= date_i18n(get_option('time_format'), strtotime(date("Y-m-d")." ".$locationDetails['close_time_sat']), TRUE);
            } else
            {
                $retOpenHours['sat'] = $this->lang->getText('NRS_CLOSED_TEXT');
            }

            if($locationDetails['open_sundays'] == "1" && strtotime(date("Y-m-d")." ".$locationDetails['open_time_sun']) < strtotime(date("Y-m-d")." ".$locationDetails['close_time_sun']))
            {
                $retOpenHours['sun']  = date_i18n(get_option('time_format'), strtotime(date("Y-m-d")." ".$locationDetails['open_time_sun']), TRUE);
                $retOpenHours['sun'] .= ' - ';
                $retOpenHours['sun'] .= date_i18n(get_option('time_format'), strtotime(date("Y-m-d")." ".$locationDetails['close_time_sun']), TRUE);
            } else
            {
                $retOpenHours['sun'] = $this->lang->getText('NRS_CLOSED_TEXT');
            }
        }

        return $retOpenHours;
    }

    public function getLunchHours()
    {
        $locationDetails = $this->getDetails();

        // Set defaults
        $retLunchHours = "";
        if(
            !is_null($locationDetails) && ($locationDetails['lunch_enabled'] == "1" &&
            strtotime(date("Y-m-d")." ".$locationDetails['lunch_start_time']) < strtotime($locationDetails['lunch_end_time'])
        )) {
            $retLunchHours  = date_i18n(get_option('time_format'), strtotime(date("Y-m-d")." ".$locationDetails['lunch_start_time']), TRUE);
            $retLunchHours .= ' - ';
            $retLunchHours .= date_i18n(get_option('time_format'), strtotime(date("Y-m-d")." ".$locationDetails['lunch_end_time']), TRUE);
        }

        return $retLunchHours;
    }
}