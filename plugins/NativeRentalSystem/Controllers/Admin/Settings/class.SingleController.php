<?php
/**
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Admin\Settings;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\EMail\EMailsObserver;
use NativeRentalSystem\Models\Style\SystemStylesObserver;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Import\DemosObserver;
use NativeRentalSystem\Controllers\Admin\AbstractController;

final class SingleController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    public function getGlobalSettings()
    {
        $globalSettingSelectDropDowns = array();

        $sdfSelected1 = $this->dbSettings->getSetting('conf_short_date_format') == "Y-m-d" ? ' selected="selected"' : '';
        $sdfSelected2 = $this->dbSettings->getSetting('conf_short_date_format') == "d/m/Y" ? ' selected="selected"' : '';
        $sdfSelected3 = $this->dbSettings->getSetting('conf_short_date_format') == "m/d/Y" ? ' selected="selected"' : '';
        $selectShortDateFormat  = '<option value="Y-m-d"'.$sdfSelected1.'>YYYY-MM-DD. '.$this->lang->getText('NRS_TODAY_TEXT').' - '.date_i18n('Y-m-d').'</option>'."\n";
        $selectShortDateFormat .= '<option value="d/m/Y"'.$sdfSelected2.'>DD/MM/YYYY. '.$this->lang->getText('NRS_TODAY_TEXT').' - '.date_i18n('d/m/Y').'</option>'."\n";
        $selectShortDateFormat .= '<option value="m/d/Y"'.$sdfSelected3.'>MM/DD/YYYY. '.$this->lang->getText('NRS_TODAY_TEXT').' - '.date_i18n('m/d/Y').'</option>'."\n";

        $globalSettingSelectDropDowns['select_short_date_format'] = $selectShortDateFormat;

        // MINIMUM PERIOD BETWEEN TWO BOOKINGS (BOOKED ITEM BLOCK TIME)
        $itemBlockList = array(
            '1' => '0 '.$this->lang->getText('NRS_MINUTES2_TEXT'),
            '899' => '15 '.$this->lang->getText('NRS_MINUTES_TEXT'),
            '1799' => '30 '.$this->lang->getText('NRS_MINUTES2_TEXT'),
            '2599' => '45 '.$this->lang->getText('NRS_MINUTES_TEXT'),
            '3599' => '1 '.$this->lang->getText('NRS_HOUR_TEXT'),
            '7199' => '2 '.$this->lang->getText('NRS_HOURS_TEXT'),
            '10799' => '3 '.$this->lang->getText('NRS_HOURS_TEXT'),
            '14399' => '4 '.$this->lang->getText('NRS_HOURS_TEXT'),
            '17999' => '5 '.$this->lang->getText('NRS_HOURS_TEXT'),
            '21599' => '6 '.$this->lang->getText('NRS_HOURS_TEXT'),
            '25199' => '7 '.$this->lang->getText('NRS_HOURS_TEXT'),
            '28799' => '8 '.$this->lang->getText('NRS_HOURS_TEXT'),
            '32399' => '9 '.$this->lang->getText('NRS_HOURS_TEXT'),
            '35999' => '10 '.$this->lang->getText('NRS_HOURS2_TEXT'),
            '39599' => '11 '.$this->lang->getText('NRS_HOURS2_TEXT'),
            '43199' => '12 '.$this->lang->getText('NRS_HOURS2_TEXT'),
            '46799' => '13 '.$this->lang->getText('NRS_HOURS2_TEXT'),
            '50399' => '14 '.$this->lang->getText('NRS_HOURS2_TEXT'),
            '53999' => '15 '.$this->lang->getText('NRS_HOURS2_TEXT'),
            '57599' => '16 '.$this->lang->getText('NRS_HOURS2_TEXT'),
            '61199' => '17 '.$this->lang->getText('NRS_HOURS2_TEXT'),
            '64799' => '18 '.$this->lang->getText('NRS_HOURS2_TEXT'),
            '68399' => '19 '.$this->lang->getText('NRS_HOURS2_TEXT'),
            '71999' => '20 '.$this->lang->getText('NRS_HOURS2_TEXT'),
            '75599' => '21 '.$this->lang->getText('NRS_HOURS_TEXT'),
            '79199' => '22 '.$this->lang->getText('NRS_HOURS_TEXT'),
            '82799' => '23 '.$this->lang->getText('NRS_HOURS_TEXT'),
            '86399' => '24 '.$this->lang->getText('NRS_HOURS_TEXT'),
        );

        $select_list = "";
        foreach($itemBlockList as $key => $value)
        {
            if($key == $this->dbSettings->getSetting('conf_minimum_block_period_between_bookings'))
            {
                $select_list .= '<option value="'.$key.'" selected="selected">'.$value.'</option>'."\n";
            } else
            {
                $select_list .= '<option value="'.$key.'">'.$value.'</option>'."\n";
            }
        }
        $globalSettingSelectDropDowns['select_minimum_block_period_between_bookings'] = $select_list;


        // MIN PERIOD BEFORE BOOKING START
        $selectMinimumPeriodBeforeBooking = "";
        for($minutes = 0; $minutes < 60; $minutes = $minutes+15)
        {
            $inSeconds = $minutes*60;
            if($inSeconds == $this->dbSettings->getSetting('conf_minimum_period_until_pickup'))
            {
                $selectMinimumPeriodBeforeBooking .= '<option value="'.$inSeconds.'" selected="selected">'.$minutes.' '.$this->lang->getText('NRS_MINUTES_TEXT').'</option>';
            } else
            {
                $selectMinimumPeriodBeforeBooking .= '<option value="'.$inSeconds.'">'.$minutes.' '.$this->lang->getText('NRS_MINUTES_TEXT').'</option>';
            }
        }
        for($hours = 1; $hours <= 23; $hours++)
        {
            $inSeconds = $hours*3600;
            if($inSeconds == $this->dbSettings->getSetting('conf_minimum_period_until_pickup'))
            {
                $selectMinimumPeriodBeforeBooking .= '<option value="'.$inSeconds.'" selected="selected">'.$hours.' '.$this->lang->getText('NRS_HOURS_TEXT').'</option>';
            } else
            {
                $selectMinimumPeriodBeforeBooking .= '<option value="'.$inSeconds.'">'.$hours.' '.$this->lang->getText('NRS_HOURS_TEXT').'</option>';
            }
        }
        for($days = 1; $days <= 30; $days++)
        {
            $inSeconds = $days*86400;
            if($inSeconds == $this->dbSettings->getSetting('conf_minimum_period_until_pickup'))
            {
                $selectMinimumPeriodBeforeBooking .= '<option value="'.$inSeconds.'" selected="selected">'.$days.' '.$this->lang->getText('NRS_DAYS_TEXT').'</option>';
            } else
            {
                $selectMinimumPeriodBeforeBooking .= '<option value="'.$inSeconds.'">'.$days.' '.$this->lang->getText('NRS_DAYS_TEXT').'</option>';
            }
        }
        $globalSettingSelectDropDowns['select_minimum_period_until_pickup'] = $selectMinimumPeriodBeforeBooking;


        if($this->dbSettings->getSetting('conf_send_emails') == 1)
        {
            $select_send_emails  = '<option value="1" selected="selected">'.$this->lang->getText('NRS_ADMIN_YES_TEXT').'</option>' . "\n";
            $select_send_emails .= '<option value="0">'.$this->lang->getText('NRS_ADMIN_NO_TEXT').'</option>' . "\n";
        } else
        {
            $select_send_emails  = '<option value="1">'.$this->lang->getText('NRS_ADMIN_YES_TEXT').'</option>' . "\n";
            $select_send_emails .= '<option value="0" selected="selected">'.$this->lang->getText('NRS_ADMIN_NO_TEXT').'</option>' . "\n";
        }
        $globalSettingSelectDropDowns['select_send_emails'] = $select_send_emails;

        if($this->dbSettings->getSetting('conf_company_notification_emails') == 1)
        {
            $select_company_notification_emails  = '<option value="1" selected="selected">'.$this->lang->getText('NRS_ADMIN_ENABLED_TEXT').'</option>' . "\n";
            $select_company_notification_emails .= '<option value="0">'.$this->lang->getText('NRS_ADMIN_DISABLED_TEXT').'</option>' . "\n";
        } else
        {
            $select_company_notification_emails  = '<option value="1">'.$this->lang->getText('NRS_ADMIN_ENABLED_TEXT').'</option>' . "\n";
            $select_company_notification_emails .= '<option value="0" selected="selected">'.$this->lang->getText('NRS_ADMIN_DISABLED_TEXT').'</option>' . "\n";
        }
        $globalSettingSelectDropDowns['select_company_notification_emails'] = $select_company_notification_emails;


        if($this->dbSettings->getSetting('conf_universal_analytics_events_tracking') == 1)
        {
            $universal_analytics_events_tracking  = '<option value="1" selected="selected">'.$this->lang->getText('NRS_ADMIN_ENABLED_TEXT').'</option>' . "\n";
            $universal_analytics_events_tracking .= '<option value="0">'.$this->lang->getText('NRS_ADMIN_DISABLED_TEXT').'</option>' . "\n";
        } else
        {
            $universal_analytics_events_tracking  = '<option value="1">'.$this->lang->getText('NRS_ADMIN_ENABLED_TEXT').'</option>' . "\n";
            $universal_analytics_events_tracking .= '<option value="0" selected="selected">'.$this->lang->getText('NRS_ADMIN_DISABLED_TEXT').'</option>' . "\n";
        }
        $globalSettingSelectDropDowns['select_universal_analytics_events_tracking'] = $universal_analytics_events_tracking;

        if($this->dbSettings->getSetting('conf_universal_analytics_enhanced_ecommerce') == 1)
        {
            $select_universal_analytics_enhanced_ecommerce  = '<option value="1" selected="selected">'.$this->lang->getText('NRS_ADMIN_ENABLED_TEXT').'</option>' . "\n";
            $select_universal_analytics_enhanced_ecommerce .= '<option value="0">'.$this->lang->getText('NRS_ADMIN_DISABLED_TEXT').'</option>' . "\n";
        } else
        {
            $select_universal_analytics_enhanced_ecommerce  = '<option value="1">'.$this->lang->getText('NRS_ADMIN_ENABLED_TEXT').'</option>' . "\n";
            $select_universal_analytics_enhanced_ecommerce .= '<option value="0" selected="selected">'.$this->lang->getText('NRS_ADMIN_DISABLED_TEXT').'</option>' . "\n";
        }
        $globalSettingSelectDropDowns['select_universal_analytics_enhanced_ecommerce'] = $select_universal_analytics_enhanced_ecommerce;

        if($this->dbSettings->getSetting('conf_recaptcha_enabled') == 1)
        {
            $select_recaptcha_enabled  = '<option value="1" selected="selected">'.$this->lang->getText('NRS_ADMIN_ENABLED_TEXT').'</option>' . "\n";
            $select_recaptcha_enabled .= '<option value="0">'.$this->lang->getText('NRS_ADMIN_DISABLED_TEXT').'</option>' . "\n";
        } else
        {
            $select_recaptcha_enabled  = '<option value="1">'.$this->lang->getText('NRS_ADMIN_ENABLED_TEXT').'</option>' . "\n";
            $select_recaptcha_enabled .= '<option value="0" selected="selected">'.$this->lang->getText('NRS_ADMIN_DISABLED_TEXT').'</option>' . "\n";
        }
        $globalSettingSelectDropDowns['select_recaptcha_enabled'] = $select_recaptcha_enabled;

        $selectAPIMaxRequestsPerPeriod = '';
        $selected = $this->dbSettings->getSetting('conf_api_max_requests_per_period') == -1 ? ' selected="selected"' : '';
        $selectAPIMaxRequestsPerPeriod .= '<option value="-1"'.$selected.'>'.$this->lang->getText('NRS_UNLIMITED_TEXT').'</option>';
        for($requests = 10; $requests <= 200; $requests = $requests + 10)
        {
            $selected = $requests == $this->dbSettings->getSetting('conf_api_max_requests_per_period') ? ' selected="selected"' : '';
            $selectAPIMaxRequestsPerPeriod .= '<option value="'.$requests.'"'.$selected.'>'.$requests.' ';
            $selectAPIMaxRequestsPerPeriod .= mb_strtolower($this->lang->getText('NRS_ADMIN_REQUESTS_TEXT'), 'UTF-8').' / ';
            $selectAPIMaxRequestsPerPeriod .= $this->lang->getText('NRS_PER_HOUR_TEXT').' / ';
            $selectAPIMaxRequestsPerPeriod .= $this->lang->getText('NRS_ADMIN_IP_TEXT').'</option>';
        }
        $globalSettingSelectDropDowns['select_api_max_requests_per_period'] = $selectAPIMaxRequestsPerPeriod;

        $selectAPIMaxFailedRequestsPerPeriod = '';
        $selected = $this->dbSettings->getSetting('conf_api_max_failed_requests_per_period') == -1 ? ' selected="selected"' : '';
        $selectAPIMaxFailedRequestsPerPeriod .= '<option value="-1"'.$selected.'>'.$this->lang->getText('NRS_UNLIMITED_TEXT').'</option>';
        for($failedRequests = 1; $failedRequests <= 20; $failedRequests++)
        {
            $selected = $failedRequests == $this->dbSettings->getSetting('conf_api_max_failed_requests_per_period') ? ' selected="selected"' : '';
            $selectAPIMaxFailedRequestsPerPeriod .= '<option value="'.$failedRequests.'"'.$selected.'>'.$failedRequests.' ';
            $selectAPIMaxFailedRequestsPerPeriod .= mb_strtolower($this->lang->getText($failedRequests == 1 ? 'NRS_ADMIN_REQUEST_TEXT' : 'NRS_ADMIN_REQUESTS_TEXT'), 'UTF-8').' / ';
            $selectAPIMaxFailedRequestsPerPeriod .= $this->lang->getText('NRS_PER_HOUR_TEXT').' / ';
            $selectAPIMaxFailedRequestsPerPeriod .=  $this->lang->getText('NRS_ADMIN_IP_TEXT').'</option>';
        }
        $globalSettingSelectDropDowns['select_api_max_failed_requests_per_period'] = $selectAPIMaxFailedRequestsPerPeriod;

        $cancelledPageArgs = array(
            'depth' => 1,
            'child_of' => 0,
            'selected' => $this->dbSettings->getSetting('conf_cancelled_payment_page_id'),
            'echo' => 0,
            'name' => 'conf_cancelled_payment_page_id',
            'id' => 'conf_cancelled_payment_page_id', // string
            'show_option_none' => $this->lang->getText('NRS_ADMIN_CHOOSE_PAGE_TEXT'), // string
            'sort_order' => 'ASC',
            'sort_column' => 'post_title',
            'post_type' => $this->conf->getExtensionPrefix().'page',
        );
        $confirmedPageArgs = array(
            'depth' => 1,
            'child_of' => 0,
            'selected' => $this->dbSettings->getSetting('conf_confirmation_page_id'),
            'echo' => 0,
            'name' => 'conf_confirmation_page_id',
            'id' => 'conf_confirmation_page_id', // string
            'show_option_none' => $this->lang->getText('NRS_ADMIN_CHOOSE_PAGE_TEXT'), // string
            'sort_order' => 'ASC',
            'sort_column' => 'post_title',
            'post_type' => $this->conf->getExtensionPrefix().'page',
        );
        $termsAndConditionsPageArgs = array(
            'depth' => 1,
            'child_of' => 0,
            'selected' => $this->dbSettings->getSetting('conf_terms_and_conditions_page_id'),
            'echo' => 0,
            'name' => 'conf_terms_and_conditions_page_id',
            'id' => 'conf_terms_and_conditions_page_id', // string
            'show_option_none' => $this->lang->getText('NRS_ADMIN_CHOOSE_PAGE_TEXT'), // string
            'sort_order' => 'ASC',
            'sort_column' => 'post_title',
            'post_type' => $this->conf->getExtensionPrefix().'page',
        );
        $globalSettingSelectDropDowns['select_cancelled_payment_page_id'] = wp_dropdown_pages($cancelledPageArgs);
        $globalSettingSelectDropDowns['select_confirmation_page_id'] = wp_dropdown_pages($confirmedPageArgs);
        $globalSettingSelectDropDowns['select_terms_and_conditions_page_id'] = wp_dropdown_pages($termsAndConditionsPageArgs);

        // MIN BOOKING PERIOD
        $select_minimum_booking_period = "";
        for($minutes = 0; $minutes < 60; $minutes = $minutes+15)
        {
            $inSeconds = $minutes*60;
            if($this->dbSettings->getSetting('conf_minimum_booking_period') == $inSeconds)
            {
                $select_minimum_booking_period .= '<option value="'.$inSeconds.'" selected="selected">'.$minutes.' '.$this->lang->getText('NRS_MINUTES_TEXT').'</option>';
            } else
            {
                $select_minimum_booking_period .= '<option value="'.$inSeconds.'">'.$minutes.' '.$this->lang->getText('NRS_MINUTES_TEXT').'</option>';
            }
        }
        for($hours = 1; $hours <= 23; $hours++)
        {
            $inSeconds = $hours*3600;
            if($this->dbSettings->getSetting('conf_minimum_booking_period') == $inSeconds)
            {
                $select_minimum_booking_period .= '<option value="'.$inSeconds.'" selected="selected">'.$hours.' '.$this->lang->getText('NRS_HOURS_TEXT').'</option>'."\n";
            } else
            {
                $select_minimum_booking_period .= '<option value="'.$inSeconds.'">'.$hours.' '.$this->lang->getText('NRS_HOURS_TEXT').'</option>'."\n";
            }
        }
        for($days = 1; $days <= 90; $days++)
        {
            $inSeconds = $days*86400;
            if($this->dbSettings->getSetting('conf_minimum_booking_period') == $inSeconds)
            {
                $select_minimum_booking_period .= '<option value="'.$inSeconds.'" selected="selected">'.$days.' '.$this->lang->getText('NRS_DAYS_TEXT').'</option>'."\n";
            } else
            {
                $select_minimum_booking_period .= '<option value="'.$inSeconds.'">'.$days.' '.$this->lang->getText('NRS_DAYS_TEXT').'</option>'."\n";
            }
        }
        $globalSettingSelectDropDowns['select_minimum_booking_period'] = $select_minimum_booking_period;


        // MAX BOOKING PERIOD
        $select_maximum_booking_period = "";
        for($minutes = 0; $minutes < 60; $minutes = $minutes+15)
        {
            $inSeconds = $minutes*60;
            if($this->dbSettings->getSetting('conf_maximum_booking_period') == $inSeconds)
            {
                $select_maximum_booking_period .= '<option value="'.$inSeconds.'" selected="selected">'.$minutes.' '.$this->lang->getText('NRS_MINUTES_TEXT').'</option>';
            } else
            {
                $select_maximum_booking_period .= '<option value="'.$inSeconds.'">'.$minutes.' '.$this->lang->getText('NRS_MINUTES_TEXT').'</option>';
            }
        }
        for($hours = 1; $hours <= 23; $hours++)
        {
            $inSeconds = $hours*3600;
            if($this->dbSettings->getSetting('conf_maximum_booking_period') == $inSeconds)
            {
                $select_maximum_booking_period .= '<option value="'.$inSeconds.'" selected="selected">'.$hours.' '.$this->lang->getText('NRS_HOURS_TEXT').'</option>'."\n";
            } else
            {
                $select_maximum_booking_period .= '<option value="'.$inSeconds.'">'.$hours.' '.$this->lang->getText('NRS_HOURS_TEXT').'</option>'."\n";
            }
        }
        for($days = 1; $days <= 365; $days++)
        {
            $inSeconds = $days*86400;
            if($this->dbSettings->getSetting('conf_maximum_booking_period') == $inSeconds)
            {
                $select_maximum_booking_period .= '<option value="'.$inSeconds.'" selected="selected">'.$days.' '.$this->lang->getText('NRS_DAYS_TEXT').'</option>'."\n";
            } else
            {
                $select_maximum_booking_period .= '<option value="'.$inSeconds.'">'.$days.' '.$this->lang->getText('NRS_DAYS_TEXT').'</option>'."\n";
            }
        }
        $globalSettingSelectDropDowns['select_maximum_booking_period'] = $select_maximum_booking_period;


        // NOON TIME
        $select_noon_time = '';
        for($hour = 10; $hour < 17; $hour++)
        {
            for($min = 0; $min < 60; $min = $min+30)
            {
                $currentHour = sprintf("%02d", $hour);
                $currentMin = sprintf("%02d", $min);

                $currentTime = $currentHour.':'.$currentMin.':00';
                $selected = ($currentTime == $this->dbSettings->getSetting('conf_noon_time') ? ' selected="selected"' : '');

                $UTCUnixTime = strtotime(date("Y-m-d")." ".$currentTime);
                $printCurrentTime = date_i18n(get_option('time_format'), $UTCUnixTime, TRUE);

                $select_noon_time .= '<option value="'.$currentTime.'"'.$selected.'>'.$printCurrentTime.'</option>';
            }
        }
        $globalSettingSelectDropDowns['select_noon_time'] = $select_noon_time;


        if($this->dbSettings->getSetting('conf_reveal_partner') == 0)
        {
            $selectRevealPartner  = '<option value="0" selected="selected">'.$this->lang->getText('NRS_ADMIN_NO_TEXT').'</option>'."\n";
            $selectRevealPartner .= '<option value="1">'.$this->lang->getText('NRS_ADMIN_YES_TEXT').'</option>'."\n";
        } else
        {
            $selectRevealPartner  = '<option value="0">'.$this->lang->getText('NRS_ADMIN_NO_TEXT').'</option>'."\n";
            $selectRevealPartner .= '<option value="1" selected="selected">'.$this->lang->getText('NRS_ADMIN_YES_TEXT').'</option>'."\n";
        }
        $globalSettingSelectDropDowns['select_reveal_partner'] = $selectRevealPartner;


        if($this->dbSettings->getSetting('conf_classify_items') == 0)
        {
            $selectClassifyItems  = '<option value="0" selected="selected">'.$this->lang->getText('NRS_ADMIN_NO_TEXT').'</option>'."\n";
            $selectClassifyItems .= '<option value="1">'.$this->lang->getText('NRS_ADMIN_YES_TEXT').'</option>'."\n";
        } else
        {
            $selectClassifyItems  = '<option value="0">'.$this->lang->getText('NRS_ADMIN_NO_TEXT').'</option>'."\n";
            $selectClassifyItems .= '<option value="1" selected="selected">'.$this->lang->getText('NRS_ADMIN_YES_TEXT').'</option>'."\n";
        }
        $globalSettingSelectDropDowns['select_classify_items'] = $selectClassifyItems;


        if($this->dbSettings->getSetting('conf_booking_model') == 1)
        {
            $selectBookingModel   = '<option value="1" selected="selected">'.$this->lang->getText('NRS_ADMIN_DISABLED_TEXT').'</option>'."\n";
            $selectBookingModel  .= '<option value="2">'.$this->lang->getText('NRS_ADMIN_ENABLED_TEXT').'</option>'."\n";
        } else
        {
            $selectBookingModel  = '<option value="1">'.$this->lang->getText('NRS_ADMIN_DISABLED_TEXT').'</option>'."\n";
            $selectBookingModel .= '<option value="2" selected="selected">'.$this->lang->getText('NRS_ADMIN_ENABLED_TEXT').'</option>'."\n";
        }
        $globalSettingSelectDropDowns['select_booking_model'] = $selectBookingModel;


        if($this->dbSettings->getSetting('conf_search_enabled') == 1)
        {
            $selectSearchEnabled  = '<option value="0">'.$this->lang->getText('NRS_ADMIN_DISABLED_TEXT').'</option>'."\n";
            $selectSearchEnabled .= '<option value="1" selected="selected">'.$this->lang->getText('NRS_ADMIN_ENABLED_TEXT').'</option>'."\n";
        } else
        {
            $selectSearchEnabled  = '<option value="0" selected="selected">'.$this->lang->getText('NRS_ADMIN_DISABLED_TEXT').'</option>'."\n";
            $selectSearchEnabled .= '<option value="1">'.$this->lang->getText('NRS_ADMIN_ENABLED_TEXT').'</option>'."\n";
        }
        $globalSettingSelectDropDowns['select_search_enabled'] = $selectSearchEnabled;


        if($this->dbSettings->getSetting('conf_load_datepicker_from_plugin') == 1)
        {
            $selectLoadDatePickerFromPlugin  = '<option value="0">'.$this->lang->getText('NRS_ADMIN_LOAD_FROM_OTHER_PLACE_TEXT').'</option>'."\n";
            $selectLoadDatePickerFromPlugin .= '<option value="1" selected="selected">'.$this->lang->getText('NRS_ADMIN_LOAD_FROM_PLUGIN_TEXT').'</option>'."\n";
        } else
        {
            $selectLoadDatePickerFromPlugin  = '<option value="0" selected="selected">'.$this->lang->getText('NRS_ADMIN_LOAD_FROM_OTHER_PLACE_TEXT').'</option>'."\n";
            $selectLoadDatePickerFromPlugin .= '<option value="1">'.$this->lang->getText('NRS_ADMIN_LOAD_FROM_PLUGIN_TEXT').'</option>'."\n";
        }
        $globalSettingSelectDropDowns['select_load_datepicker_from_plugin'] = $selectLoadDatePickerFromPlugin;


        if($this->dbSettings->getSetting('conf_load_fancybox_from_plugin') == 1)
        {
            $selectLoadFancyBoxFromPlugin  = '<option value="0">'.$this->lang->getText('NRS_ADMIN_LOAD_FROM_OTHER_PLACE_TEXT').'</option>'."\n";
            $selectLoadFancyBoxFromPlugin .= '<option value="1" selected="selected">'.$this->lang->getText('NRS_ADMIN_LOAD_FROM_PLUGIN_TEXT').'</option>'."\n";
        } else
        {
            $selectLoadFancyBoxFromPlugin  = '<option value="0" selected="selected">'.$this->lang->getText('NRS_ADMIN_LOAD_FROM_OTHER_PLACE_TEXT').'</option>'."\n";
            $selectLoadFancyBoxFromPlugin .= '<option value="1">'.$this->lang->getText('NRS_ADMIN_LOAD_FROM_PLUGIN_TEXT').'</option>'."\n";
        }
        $globalSettingSelectDropDowns['select_load_fancybox_from_plugin'] = $selectLoadFancyBoxFromPlugin;


        if($this->dbSettings->getSetting('conf_load_font_awesome_from_plugin') == 1)
        {
            $selectLoadFontAwesomeFromPlugin  = '<option value="0">'.$this->lang->getText('NRS_ADMIN_LOAD_FROM_OTHER_PLACE_TEXT').'</option>'."\n";
            $selectLoadFontAwesomeFromPlugin .= '<option value="1" selected="selected">'.$this->lang->getText('NRS_ADMIN_LOAD_FROM_PLUGIN_TEXT').'</option>'."\n";
        } else
        {
            $selectLoadFontAwesomeFromPlugin  = '<option value="0" selected="selected">'.$this->lang->getText('NRS_ADMIN_LOAD_FROM_OTHER_PLACE_TEXT').'</option>'."\n";
            $selectLoadFontAwesomeFromPlugin .= '<option value="1">'.$this->lang->getText('NRS_ADMIN_LOAD_FROM_PLUGIN_TEXT').'</option>'."\n";
        }
        $globalSettingSelectDropDowns['select_load_font_awesome_from_plugin'] = $selectLoadFontAwesomeFromPlugin;


        if($this->dbSettings->getSetting('conf_load_slick_slider_from_plugin') == 1)
        {
            $selectLoadSlickSliderFromPlugin  = '<option value="0">'.$this->lang->getText('NRS_ADMIN_LOAD_FROM_OTHER_PLACE_TEXT').'</option>'."\n";
            $selectLoadSlickSliderFromPlugin .= '<option value="1" selected="selected">'.$this->lang->getText('NRS_ADMIN_LOAD_FROM_PLUGIN_TEXT').'</option>'."\n";
        } else
        {
            $selectLoadSlickSliderFromPlugin  = '<option value="0" selected="selected">'.$this->lang->getText('NRS_ADMIN_LOAD_FROM_OTHER_PLACE_TEXT').'</option>'."\n";
            $selectLoadSlickSliderFromPlugin .= '<option value="1">'.$this->lang->getText('NRS_ADMIN_LOAD_FROM_PLUGIN_TEXT').'</option>'."\n";
        }
        $globalSettingSelectDropDowns['select_load_slick_slider_from_plugin'] = $selectLoadSlickSliderFromPlugin;


        return $globalSettingSelectDropDowns;
    }

    public function getPriceSettings()
    {
        $priceSettingSelectDropDowns = array();

        // Price calculation type
        $price_array = array(
            1 => $this->lang->getText('NRS_ADMIN_DAILY_TEXT'),
            2 => $this->lang->getText('NRS_ADMIN_HOURLY_TEXT'),
            3 => $this->lang->getText('NRS_ADMIN_COMBINED_TEXT'),
        );
        $select_price_calculation_type = "";
        foreach($price_array as $key => $value)
        {
            if($key == $this->dbSettings->getSetting('conf_price_calculation_type'))
            {
                $select_price_calculation_type .= '<option value="'.$key.'" selected="selected">'.$value.'</option>';
            } else
            {
                $select_price_calculation_type .= '<option value="'.$key.'" >'.$value.'</option>';
            }
        }
        $priceSettingSelectDropDowns['select_price_calculation_type'] = $select_price_calculation_type;



        // Currency symbol is on the left or on the right side to the price
        if($this->dbSettings->getSetting('conf_currency_symbol_location') == 0)
        {
            $select_currency_symbol_location  = '<option value="0" selected="selected">'.$this->lang->getText('NRS_ADMIN_ON_THE_LEFT_TEXT').'</option>' . "\n";
            $select_currency_symbol_location .= '<option value="1">'.$this->lang->getText('NRS_ADMIN_ON_THE_RIGHT_TEXT').'</option>' . "\n";
        } else
        {
            $select_currency_symbol_location  = '<option value="0">'.$this->lang->getText('NRS_ADMIN_ON_THE_LEFT_TEXT').'</option>' . "\n";
            $select_currency_symbol_location .= '<option value="1" selected="selected">'.$this->lang->getText('NRS_ADMIN_ON_THE_RIGHT_TEXT').'</option>' . "\n";
        }
        $priceSettingSelectDropDowns['select_currency_symbol_location'] = $select_currency_symbol_location;


        // Show prices with VAT
        if($this->dbSettings->getSetting('conf_show_price_with_taxes') == 1)
        {
            $select_show_price_with_taxes  = '<option value="1" selected="selected">'.$this->lang->getText('NRS_ADMIN_YES_TEXT').'</option>' . "\n";
            $select_show_price_with_taxes .= '<option value="0">'.$this->lang->getText('NRS_ADMIN_NO_TEXT').'</option>' . "\n";
        } else
        {
            $select_show_price_with_taxes  = '<option value="1">'.$this->lang->getText('NRS_ADMIN_YES_TEXT').'</option>' . "\n";
            $select_show_price_with_taxes .= '<option value="0" selected="selected">'.$this->lang->getText('NRS_ADMIN_NO_TEXT').'</option>' . "\n";
        }
        $priceSettingSelectDropDowns['select_show_price_with_taxes'] = $select_show_price_with_taxes;


        if($this->dbSettings->getSetting('conf_deposit_enabled') == 0)
        {
            $selectDepositEnabled  = '<option value="0" selected="selected">'.$this->lang->getText('NRS_ADMIN_DISABLED_TEXT').'</option>'."\n";
            $selectDepositEnabled .= '<option value="1">'.$this->lang->getText('NRS_ADMIN_ENABLED_TEXT').'</option>'."\n";
        } else
        {
            $selectDepositEnabled  = '<option value="0">'.$this->lang->getText('NRS_ADMIN_DISABLED_TEXT').'</option>'."\n";
            $selectDepositEnabled .= '<option value="1" selected="selected">'.$this->lang->getText('NRS_ADMIN_ENABLED_TEXT').'</option>'."\n";
        }
        $priceSettingSelectDropDowns['select_deposit_enabled'] = $selectDepositEnabled;


        if($this->dbSettings->getSetting('conf_prepayment_enabled') == 0)
        {
            $selectPrepaymentEnabled  = '<option value="0" selected="selected">'.$this->lang->getText('NRS_ADMIN_DISABLED_TEXT').'</option>'."\n";
            $selectPrepaymentEnabled .= '<option value="1">'.$this->lang->getText('NRS_ADMIN_ENABLED_TEXT').'</option>'."\n";
        } else
        {
            $selectPrepaymentEnabled  = '<option value="0">'.$this->lang->getText('NRS_ADMIN_DISABLED_TEXT').'</option>'."\n";
            $selectPrepaymentEnabled .= '<option value="1" selected="selected">'.$this->lang->getText('NRS_ADMIN_ENABLED_TEXT').'</option>'."\n";
        }
        $priceSettingSelectDropDowns['select_prepayment_enabled'] = $selectPrepaymentEnabled;


        return $priceSettingSelectDropDowns;
    }


    public function getContent()
    {
        // Local variables
        $pickupLocationVisible = $this->dbSettings->getSearchFieldStatus("pickup_location", "VISIBLE");
        $returnLocationVisible = $this->dbSettings->getSearchFieldStatus("return_location", "VISIBLE");

        // Tab - global settings
        $objStylesObserver = new SystemStylesObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $this->view->globalSettingsTabFormAction = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-global-settings&noheader=true');
        $this->view->extensionName = $this->conf->getExtensionName();
        $this->view->itemParameter = $this->conf->getItemParameter();
        $this->view->itemPluralParameter = $this->conf->getItemPluralParameter();
        $this->view->systemStylesDropdownOptions = $objStylesObserver->getDropDownOptions($this->dbSettings->getSetting('conf_system_style'));
        $this->view->arrGlobalSettings = $this->getGlobalSettings();
        $this->view->showLocationBBCodes = $pickupLocationVisible || $returnLocationVisible;
        $this->view->siteUrl = get_site_url();


        // Tab - customer settings
        $this->view->customerSettingsTabFormAction = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-customer-settings&noheader=true');
        $this->view->titleVisibleChecked = $this->dbSettings->getCustomerFieldStatus("title", "VISIBLE") ? ' checked="checked"' : '';
        $this->view->firstNameVisibleChecked = $this->dbSettings->getCustomerFieldStatus("first_name", "VISIBLE") ? ' checked="checked"' : '';
        $this->view->lastNameVisibleChecked = $this->dbSettings->getCustomerFieldStatus("last_name", "VISIBLE") ? ' checked="checked"' : '';
        $this->view->birthdateVisibleChecked = $this->dbSettings->getCustomerFieldStatus("birthdate", "VISIBLE") ? ' checked="checked"' : '';
        $this->view->streetAddressVisibleChecked = $this->dbSettings->getCustomerFieldStatus("street_address", "VISIBLE") ? ' checked="checked"' : '';
        $this->view->cityVisibleChecked = $this->dbSettings->getCustomerFieldStatus("city", "VISIBLE") ? ' checked="checked"' : '';
        $this->view->stateVisibleChecked = $this->dbSettings->getCustomerFieldStatus("state", "VISIBLE") ? ' checked="checked"' : '';
        $this->view->zipCodeVisibleChecked = $this->dbSettings->getCustomerFieldStatus("zip_code", "VISIBLE") ? ' checked="checked"' : '';
        $this->view->countryVisibleChecked = $this->dbSettings->getCustomerFieldStatus("country", "VISIBLE") ? ' checked="checked"' : '';
        $this->view->phoneVisibleChecked = $this->dbSettings->getCustomerFieldStatus("phone", "VISIBLE") ? ' checked="checked"' : '';
        $this->view->emailVisibleChecked = $this->dbSettings->getCustomerFieldStatus("email", "VISIBLE") ? ' checked="checked"' : '';
        $this->view->commentsVisibleChecked = $this->dbSettings->getCustomerFieldStatus("comments", "VISIBLE") ? ' checked="checked"' : '';

        $this->view->titleRequiredChecked = $this->dbSettings->getCustomerFieldStatus("title", "REQUIRED") ? ' checked="checked"' : '';
        $this->view->firstNameRequiredChecked = $this->dbSettings->getCustomerFieldStatus("first_name", "REQUIRED") ? ' checked="checked"' : '';
        $this->view->lastNameRequiredChecked = $this->dbSettings->getCustomerFieldStatus("last_name", "REQUIRED") ? ' checked="checked"' : '';
        $this->view->birthdateRequiredChecked = $this->dbSettings->getCustomerFieldStatus("birthdate", "REQUIRED") ? ' checked="checked"' : '';
        $this->view->streetAddressRequiredChecked = $this->dbSettings->getCustomerFieldStatus("street_address", "REQUIRED") ? ' checked="checked"' : '';
        $this->view->cityRequiredChecked = $this->dbSettings->getCustomerFieldStatus("city", "REQUIRED") ? ' checked="checked"' : '';
        $this->view->stateRequiredChecked = $this->dbSettings->getCustomerFieldStatus("state", "REQUIRED") ? ' checked="checked"' : '';
        $this->view->zipCodeRequiredChecked = $this->dbSettings->getCustomerFieldStatus("zip_code", "REQUIRED") ? ' checked="checked"' : '';
        $this->view->countryRequiredChecked = $this->dbSettings->getCustomerFieldStatus("country", "REQUIRED") ? ' checked="checked"' : '';
        $this->view->phoneRequiredChecked = $this->dbSettings->getCustomerFieldStatus("phone", "REQUIRED") ? ' checked="checked"' : '';
        $this->view->emailRequiredChecked = $this->dbSettings->getCustomerFieldStatus("email", "REQUIRED") ? ' checked="checked"' : '';
        $this->view->commentsRequiredChecked = $this->dbSettings->getCustomerFieldStatus("comments", "REQUIRED") ? ' checked="checked"' : '';


        // Tab - search settings
        $this->view->searchSettingsTabFormAction = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-search-settings&noheader=true');
        $this->view->pickupLocationVisibleChecked = $this->dbSettings->getSearchFieldStatus("pickup_location", "VISIBLE") ? ' checked="checked"' : '';
        $this->view->pickupDateVisibleChecked = $this->dbSettings->getSearchFieldStatus("pickup_date", "VISIBLE") ? ' checked="checked"' : '';
        $this->view->returnLocationVisibleChecked = $this->dbSettings->getSearchFieldStatus("return_location", "VISIBLE") ? ' checked="checked"' : '';
        $this->view->returnDateVisibleChecked = $this->dbSettings->getSearchFieldStatus("return_date", "VISIBLE") ? ' checked="checked"' : '';
        $this->view->partnerVisibleChecked = $this->dbSettings->getSearchFieldStatus("partner", "VISIBLE") ? ' checked="checked"' : '';
        $this->view->manufacturerVisibleChecked = $this->dbSettings->getSearchFieldStatus("manufacturer", "VISIBLE") ? ' checked="checked"' : '';
        $this->view->bodyTypeVisibleChecked = $this->dbSettings->getSearchFieldStatus("body_type", "VISIBLE") ? ' checked="checked"' : '';
        $this->view->transmissionTypeVisibleChecked = $this->dbSettings->getSearchFieldStatus("transmission_type", "VISIBLE") ? ' checked="checked"' : '';
        $this->view->fuelTypeVisibleChecked = $this->dbSettings->getSearchFieldStatus("fuel_type", "VISIBLE") ? ' checked="checked"' : '';
        $this->view->existingBookingCodeVisibleChecked = $this->dbSettings->getSearchFieldStatus("booking_code", "VISIBLE") ? ' checked="checked"' : '';
        $this->view->couponCodeVisibleChecked = $this->dbSettings->getSearchFieldStatus("coupon_code", "VISIBLE") ? ' checked="checked"' : '';

        $this->view->pickupLocationRequiredChecked = $this->dbSettings->getSearchFieldStatus("pickup_location", "REQUIRED") ? ' checked="checked"' : '';
        $this->view->pickupDateRequiredChecked = $this->dbSettings->getSearchFieldStatus("pickup_date", "REQUIRED") ? ' checked="checked"' : '';
        $this->view->returnLocationRequiredChecked = $this->dbSettings->getSearchFieldStatus("return_location", "REQUIRED") ? ' checked="checked"' : '';
        $this->view->returnDateRequiredChecked = $this->dbSettings->getSearchFieldStatus("return_date", "REQUIRED") ? ' checked="checked"' : '';
        $this->view->partnerRequiredChecked = $this->dbSettings->getSearchFieldStatus("partner", "REQUIRED") ? ' checked="checked"' : '';
        $this->view->manufacturerRequiredChecked = $this->dbSettings->getSearchFieldStatus("manufacturer", "REQUIRED") ? ' checked="checked"' : '';
        $this->view->bodyTypeRequiredChecked = $this->dbSettings->getSearchFieldStatus("body_type", "REQUIRED") ? ' checked="checked"' : '';
        $this->view->transmissionTypeRequiredChecked = $this->dbSettings->getSearchFieldStatus("transmission_type", "REQUIRED") ? ' checked="checked"' : '';
        $this->view->fuelTypeRequiredChecked = $this->dbSettings->getSearchFieldStatus("fuel_type", "REQUIRED") ? ' checked="checked"' : '';
        $this->view->existingBookingCodeRequiredChecked = $this->dbSettings->getSearchFieldStatus("booking_code", "REQUIRED") ? ' checked="checked"' : '';
        $this->view->couponCodeRequiredChecked = $this->dbSettings->getSearchFieldStatus("coupon_code", "REQUIRED") ? ' checked="checked"' : '';


        // Tab - price settings
        $this->view->priceSettingsTabFormAction = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-price-settings&noheader=true');
        $this->view->arrPriceSettings = $this->getPriceSettings();


        // Tab - email settings
        $localSelectedEmailId = isset($_GET['email']) ? StaticValidator::getValidPositiveInteger($_GET['email'], 0) : 0;
        $objEMailsObserver = new EMailsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $this->view->emailSettingsTabFormAction = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-email&noheader=true');
        $this->view->ajaxSecurityNonce = wp_create_nonce($this->conf->getURLPrefix().'admin-ajax-nonce');
        $this->view->extensionFolder = $this->conf->getExtensionFolder();
        $this->view->urlPrefix = $this->conf->getURLPrefix();
        $this->view->emailList = $objEMailsObserver->getAdminList($localSelectedEmailId);


        // Tab - import demo
        $objDemosObserver = new DemosObserver($this->conf, $this->lang);
        $this->view->importDemoTabFormAction = admin_url('admin.php?page='.$this->conf->getURLPrefix().'import-demo&noheader=true');
        $this->view->demosDropDownOptions = $objDemosObserver->getDemosDropDownOptions(0, 0, $this->lang->getText('NRS_ADMIN_SELECT_DEMO_TEXT'));


        // Tab - system status
        $this->view->version = number_format_i18n($this->conf->getVersion(), 1);

        // Get the tab values
        $tabs = $this->getTabParams(array(
            'global-settings', 'customer-settings', 'search-settings', 'price-settings', 'email-settings', 'import-demo', 'single-status'
        ), 'global-settings');

        // 1. Set the view variables - Tab settings
        $this->view->globalSettingsTabChecked = !empty($tabs['global-settings']) ? ' checked="checked"' : '';
        $this->view->customerSettingsTabChecked = !empty($tabs['customer-settings']) ? ' checked="checked"' : '';
        $this->view->searchSettingsTabChecked = !empty($tabs['search-settings']) ? ' checked="checked"' : '';
        $this->view->priceSettingsTabChecked = !empty($tabs['price-settings']) ? ' checked="checked"' : '';
        $this->view->emailSettingsTabChecked = !empty($tabs['email-settings']) ? ' checked="checked"' : '';
        $this->view->importDemoTabChecked = !empty($tabs['import-demo']) ? ' checked="checked"' : '';
        $this->view->singleStatusTabChecked = !empty($tabs['single-status']) ? ' checked="checked"' : '';

        // Get the template
        $retContent = $this->getTemplate('Settings', 'SingleManager', 'Tabs');

        return $retContent;
    }
}
