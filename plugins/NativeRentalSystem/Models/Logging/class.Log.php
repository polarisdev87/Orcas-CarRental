<?php
/**
 * NRS API logger

 * @note - Log types:
 *      1. customer-lookup - customer lookup log,
 *      2. payment-callback - payment api log
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Logging;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Security\StaticSecurity;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;

class Log
{
    // For security reasons we do not allow to change this value from admin
    const REQUEST_EXPIRATION_PERIOD         = 3600; // 1 hour

    protected $conf 	                    = NULL;
    protected $lang 		                = NULL;
    protected $debugMode 	                = 0;
    protected $shortDateFormat              = "Y-m-d";

    protected $maxRequestsPerPeriod         = 50;
    protected $maxFailedRequestsPerPeriod   = 3;
    protected $logId                        = 0;

    /**
     * Log constructor.
     * @param ExtensionConfiguration $paramConf
     * @param Language $paramLang
     * @param array $paramSettings
     * @param int $paramLogId
     */
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings, $paramLogId)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;

        // Set log id
        $this->logId = StaticValidator::getValidPositiveInteger($paramLogId, 0);

        $this->maxRequestsPerPeriod = StaticValidator::getValidSetting($paramSettings, 'conf_api_max_requests_per_period', 'positive_integer', 50);
        $this->maxFailedRequestsPerPeriod = StaticValidator::getValidSetting($paramSettings, 'conf_api_max_failed_requests_per_period', 'positive_integer', 3);
        if(isset($paramSettings['conf_short_date_format']))
        {
            $this->shortDateFormat = sanitize_text_field($paramSettings['conf_short_date_format']);
        }
    }

    /**
     * @param $paramLogId
     * @return mixed
     */
    private function getDataFromDatabaseById($paramLogId)
    {
        $validLogId = StaticValidator::getValidPositiveInteger($paramLogId, 0);

        $retData = $this->conf->getInternalWPDB()->get_row("
            SELECT *
            FROM {$this->conf->getPrefix()}logs
            WHERE log_id='{$validLogId}'
        ", ARRAY_A);

        return $retData;
    }

    public function getId()
    {
        return $this->logId;
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    /**
     * @param bool $paramIncludeUnclassified - not used
     * @return mixed
     */
    public function getDetails($paramIncludeUnclassified = FALSE)
    {
        $ret = $this->getDataFromDatabaseById($this->logId);
        if(!is_null($ret))
        {
            // Make raw
            $ret['log_type'] = stripslashes($ret['log_type']);
            $ret['email'] = stripslashes($ret['email']);
            $ret['error_message'] = stripslashes($ret['error_message']);
            $ret['debug_log'] = stripslashes($ret['debug_log']);
            $ret['host'] = stripslashes($ret['host']);
            $ret['ip'] = stripslashes($ret['ip']);
            $ret['real_ip'] = stripslashes($ret['real_ip']);
            $ret['browser'] = stripslashes($ret['browser']);
            $ret['os'] = stripslashes($ret['os']);

            if($ret['log_timestamp'] > 0)
            {
                $ret['log_date'] = date_i18n($this->shortDateFormat, $ret['log_timestamp'] + get_option('gmt_offset') * 3600, TRUE);
                $ret['log_time'] = date_i18n('H:i', $ret['log_timestamp'] + get_option('gmt_offset') * 3600, TRUE);
                $printLogDate = date_i18n(get_option('date_format'), $ret['log_timestamp'] + get_option('gmt_offset') * 3600, TRUE);
                $printLogTime = date_i18n(get_option('time_format'), $ret['log_timestamp'] + get_option('gmt_offset') * 3600, TRUE);
            } else
            {
                $ret['start_date'] = '';
                $ret['start_time'] = '';
                $printLogDate = '';
                $printLogTime = '';
            }

            // Prepare output for print
            $ret['print_log_type']       = esc_html($ret['log_type']);
            $ret['print_email']          = esc_html($ret['email']);
            $ret['print_error_message']  = nl2br(implode("\n", array_map('esc_html', explode("\n", $ret['error_message']))));
            $ret['print_debug_log']      = nl2br(implode("\n", array_map('esc_html', explode("\n", $ret['debug_log']))));
            $ret['print_year']           = $ret['year'] == 0 ? '0000' : $ret['year'];
            $ret['print_year_required']  = $ret['year_required'] ? '('.$this->lang->getText('NRS_REQUIRED_TEXT').')' : '';
            $ret['print_host']           = esc_html($ret['host']);
            $ret['print_ip']             = esc_html($ret['ip']);
            $ret['print_real_ip']        = esc_html($ret['real_ip']);
            $ret['print_browser']        = esc_html($ret['browser']);
            $ret['print_os']             = esc_html($ret['os']);
            $ret['print_log_date']       = $printLogDate;
            $ret['print_log_time']       = $printLogTime;

            $ret['print_is_robot'] = $this->lang->getText($ret['is_robot'] == 1 ? 'NRS_ADMIN_YES_TEXT' : 'NRS_ADMIN_NO_TEXT');
            $ret['print_status'] = "";
            $ret['print_status_color'] = "black";
            if($ret['status'] == 1)
            {
                $ret['print_status'] = $this->lang->getText('NRS_ADMIN_FAILED_TEXT');
                $ret['print_status_color'] = "black";
            } else if($ret['status'] == 2)
            {
                $ret['print_status'] = $this->lang->getText('NRS_ADMIN_ALLOWED_TEXT');
                $ret['print_status_color'] = "green";
            } else if($ret['status'] == 0)
            {
                $ret['print_status'] = $this->lang->getText('NRS_ADMIN_BLOCKED_TEXT');
                $ret['print_status_color'] = "red";
            }

            // Prepare output for edit
            $ret['edit_log_type']        = esc_attr($ret['log_type']); // for input field
            $ret['edit_email']           = esc_attr($ret['email']); // for input field
            $ret['edit_error_message']   = esc_textarea($ret['error_message']); // for textarea field
            $ret['edit_debug_log']       = esc_textarea($ret['debug_log']); // for textarea field
            $ret['edit_host']            = esc_attr($ret['host']); // for input field
            $ret['edit_ip']              = esc_attr($ret['ip']); // for input field
            $ret['edit_real_ip']         = esc_attr($ret['real_ip']); // for input field
            $ret['edit_browser']         = esc_attr($ret['browser']); // for input field
            $ret['edit_os']              = esc_attr($ret['os']); // for input field
        }

        return $ret;
    }

    public function save($paramLogType, $paramEmail, $paramYear = "0000", $paramYearRequired = FALSE, $paramFailed = FALSE, $errorMessage = '', $debugLog = '')
    {
        $validLogType = $paramLogType == "payment-callback" ? "payment-callback" : "customer-lookup";
        $validEmail = esc_sql(sanitize_email($paramEmail)); // for sql queries only
        $validYear = intval($paramYear);
        $validYearRequired = $paramYearRequired ? 1 : 0;
        $errorMessage = esc_sql(sanitize_text_field($errorMessage)); // for sql queries only
        $debugLog = esc_sql(sanitize_text_field($debugLog)); // for sql queries only

        $validIP = esc_sql(sanitize_text_field($_SERVER['REMOTE_ADDR'])); // for sql queries only
        $validRealIP = esc_sql(sanitize_text_field(StaticSecurity::getRealIP())); // for sql queries only
        $validHost = esc_sql(sanitize_text_field(gethostbyaddr($_SERVER['REMOTE_ADDR']))); // for sql queries only
        $agent = StaticSecurity::getAgent();
        $validAgent = esc_sql(sanitize_text_field($agent)); // for sql queries only
        $validBrowser = esc_sql(sanitize_text_field(StaticSecurity::getBrowser($agent))); // for sql queries only
        $validOS = esc_sql(sanitize_text_field(StaticSecurity::getOS($agent))); // for sql queries only
        $validIsRobot = StaticSecurity::isRobot($agent) ? 1 : 0;

        $validTotalRequestsLeft = intval($this->getTotalRequestsLeft());
        $validFailedRequestsLeft = intval($this->getFailedRequestsLeft());
        $validEmailAttemptsLeft = intval($this->getFailedEmailAttemptsLeft($paramEmail));

        $validStatus = 0; // 0 - BLOCKED, 1 - FAILED, 2 - PASSED
        if($validFailedRequestsLeft > 0 && $validTotalRequestsLeft > 0 && $validEmailAttemptsLeft > 0)
        {
            $validStatus = $paramFailed ? 1 : 2;
        }

        /* insert the invoice data in {$this->conf->getPrefix()}invoice table */
        $insertSQL = "
            INSERT INTO {$this->conf->getPrefix()}logs
            (
                log_type, email, year, year_required, error_message, debug_log,
                ip, real_ip, host,
                agent, browser, os,
                total_requests_left, failed_requests_left, email_attempts_left,
                is_robot, status, log_timestamp, blog_id
            ) VALUES
            (
                '{$validLogType}', '{$validEmail}', '{$validYear}', '{$validYearRequired}', '{$errorMessage}', '{$debugLog}',
                '{$validIP}', '{$validRealIP}', '{$validHost}',
                '{$validAgent}', '{$validBrowser}', '{$validOS}',
                '{$validTotalRequestsLeft}', '{$validFailedRequestsLeft}', '{$validEmailAttemptsLeft}',
                '{$validIsRobot}', '{$validStatus}', '".time()."', '{$this->conf->getBlogId()}'
            )
        ";

        // Debug
        //echo esc_html($insertSQL); die();
        $this->conf->getInternalWPDB()->query($insertSQL);
    }

    /* --------------------------------------------------------------------------------- */
    /* More methods                                                                      */
    /* --------------------------------------------------------------------------------- */

    /**
     * @note - we don't use blog_id here, because we want to count here for all sites
     * @return int
     */
    public function getTotalRequestsLeft()
    {
        $totalRequestsLeft = -1;
        if($this->maxRequestsPerPeriod > -1)
        {
            $validIP = esc_sql(sanitize_text_field($_SERVER['REMOTE_ADDR']));
            $validRealIP = esc_sql(sanitize_text_field(StaticSecurity::getRealIP()));
            $validTimestampLimit = time() - self::REQUEST_EXPIRATION_PERIOD;

            $totalRequests = $this->conf->getInternalWPDB()->get_var("
                SELECT COUNT(*) AS total_requests
                FROM {$this->conf->getPrefix()}logs
                WHERE log_timestamp >= '{$validTimestampLimit}' AND log_type='customer-lookup'
                AND (ip='{$validIP}' OR ip='{$validRealIP}' OR real_ip='{$validIP}')
            ");

            $totalRequestsLeft = !is_null($totalRequests) ? max($this->maxRequestsPerPeriod - $totalRequests, 0) : $this->maxRequestsPerPeriod;
        }

        return $totalRequestsLeft;
    }

    /**
     * @note - we don't use blog_id here, because we want to count here for all sites
     * @return int
     */
    public function getFailedRequestsLeft()
    {
        $failedRequestsLeft = -1;
        if($this->maxFailedRequestsPerPeriod > -1)
        {
            $validIP = esc_sql(sanitize_text_field($_SERVER['REMOTE_ADDR']));
            $validRealIP = esc_sql(sanitize_text_field(StaticSecurity::getRealIP()));
            $validTimestampLimit = time() - self::REQUEST_EXPIRATION_PERIOD;

            $failedRequests = $this->conf->getInternalWPDB()->get_var("
                SELECT COUNT(*) AS total_requests
                FROM {$this->conf->getPrefix()}logs
                WHERE status = '1' AND log_timestamp >= '{$validTimestampLimit}' AND log_type='customer-lookup'
                AND (ip='{$validIP}' OR ip='{$validRealIP}' OR real_ip='{$validRealIP}')
            ");

            $failedRequestsLeft = !is_null($failedRequests) ? max($this->maxFailedRequestsPerPeriod - $failedRequests, 0) : $this->maxFailedRequestsPerPeriod;
        }

        return $failedRequestsLeft;
    }

    /**
     * @note - we don't use blog_id here, because we want to count here for all sites
     * @param $paramEmail
     * @return int
     */
    public function getFailedEmailAttemptsLeft($paramEmail)
    {
        $failedAttemptsLeft = -1;
        if($this->maxFailedRequestsPerPeriod > -1)
        {
            $validEmail = esc_sql(sanitize_email($paramEmail));
            $validTimestampLimit = time() - self::REQUEST_EXPIRATION_PERIOD;

            $failedAttempts = $this->conf->getInternalWPDB()->get_var("
                SELECT COUNT(*) AS total_attemps
                FROM {$this->conf->getPrefix()}logs
                WHERE status = '1' AND log_timestamp >= '{$validTimestampLimit}' AND log_type='customer-lookup'
                AND email='{$validEmail}'
            ");

            $failedAttemptsLeft = !is_null($failedAttempts) ? max($this->maxFailedRequestsPerPeriod - $failedAttempts, 0) : $this->maxFailedRequestsPerPeriod;
        }

        return $failedAttemptsLeft;
    }
}