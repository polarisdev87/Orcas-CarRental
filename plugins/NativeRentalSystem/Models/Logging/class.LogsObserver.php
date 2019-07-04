<?php
/**
 * NRS API logger

 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Logging;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\iObserver;
use NativeRentalSystem\Models\Language\Language;

class LogsObserver implements iObserver
{
    // For security reasons we do not allow to change this value from admin
    const LOG_EXPIRATION_PERIOD = 2592000; // 30 Days

    protected $conf 	                = NULL;
    protected $lang 		            = NULL;
    protected $settings		            = array();
    protected $debugMode 	            = 0;

    /**
     * LogsObserver constructor.
     * @param ExtensionConfiguration $paramConf
     * @param Language $paramLang
     * @param array $paramSettings
     */
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;
        // Set saved settings
        $this->settings = $paramSettings;
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    /**
     * @note - we don't use blog_id here, because we want to see logs here for all sites
     * @param $paramLogType
     * @return array
     */
    public function getAllIds($paramLogType)
    {
        // Show logs not older than 30 days
        $validTimestampLimit = time() - self::LOG_EXPIRATION_PERIOD;
        $validLogType = $paramLogType == "customer-lookup" ? "customer-lookup" : "payment-callback";

        $searchSQL = "
            SELECT log_id
            FROM {$this->conf->getPrefix()}logs
            WHERE log_type='{$validLogType}' AND log_timestamp >= '{$validTimestampLimit}'
            ORDER BY log_timestamp DESC
		";

        //DEBUG
        //echo nl2br($searchSQL)."<br /><br />";

        $searchResult = $this->conf->getInternalWPDB()->get_col($searchSQL);

        return $searchResult;
    }

    /**
     * @note - we don't use blog_id here, because we want to delete here for all sites
     */
    public function deleteExpiredCustomerLookupLogs()
    {
        // Delete logs older than 30 days
        $deleteOlderThan = time() - self::LOG_EXPIRATION_PERIOD;

        $deleteSQL = "
            DELETE FROM {$this->conf->getPrefix()}logs
            WHERE log_timestamp < '{$deleteOlderThan}' AND log_type='customer-lookup'
        ";

        // Debug
        //echo esc_html($deleteSQL); die();
        $this->conf->getInternalWPDB()->query($deleteSQL);
    }


    /*******************************************************************************/
    /********************** METHODS FOR ADMIN ACCESS ONLY **************************/
    /*******************************************************************************/

    public function getAdminListForCustomers()
    {
        return $this->getAdminList("customer-lookup");
    }

    public function getAdminListForPayments()
    {
        return $this->getAdminList("payment-callback");
    }

    private function getAdminList($paramType)
    {
        $html = '';

        $logIds = $this->getAllIds($paramType);
        foreach ($logIds AS $logId)
        {
            $objLog = new Log($this->conf, $this->lang, $this->settings, $logId);
            $logDetails = $objLog->getDetails();

            $html .= '<tr>';
            $html .= '<td>'.$logDetails['print_log_date'].' '.$logDetails['print_log_time'].'</td>';
            $html .= '<td nowrap="nowrap">'.$logDetails['print_email'].'</td>';
            if($logDetails['log_type'] == "customer-lookup")
            {
                $html .= '<td>'.$logDetails['print_year'].$logDetails['print_year_required'].'</td>';
            }
            $html .= '<td><span title="'.$logDetails['print_host'].'">'.$logDetails['print_ip'].'<br />('.$logDetails['print_real_ip'].')</span></td>';
            $html .= '<td>'.$logDetails['print_browser'].'</td>';
            $html .= '<td>'.$logDetails['print_os'].'</td>';
            $html .= '<td>'.$logDetails['print_is_robot'].'</td>';
            $html .= '<td style="text-align: right">';
            if($logDetails['log_type'] == "customer-lookup")
            {
                // Customer lookup log
                $html .= '<span title="'.$this->lang->getText('NRS_ADMIN_TOTAL_REQUESTS_LEFT_TEXT').': '.$logDetails['total_requests_left'];
                $html .= ', '.$this->lang->getText('NRS_ADMIN_FAILED_REQUESTS_LEFT_TEXT').': '.$logDetails['failed_requests_left'].', '.$this->lang->getText('NRS_ADMIN_EMAIL_ATTEMPTS_LEFT_TEXT').': '.$logDetails['email_attempts_left'].'">';
                $html .= '<span style="color: '.$logDetails['print_status_color'].';">'.$logDetails['print_status'].'</span></span>';
            } else
            {
                // Payment callback log
                $html .= '<span title="'.$logDetails['print_debug_log'].'">'.$logDetails['print_error_message'].'</span>';
            }
            $html .= '</td>';
            $html .= '</tr>';
        }
        return $html;
    }
}