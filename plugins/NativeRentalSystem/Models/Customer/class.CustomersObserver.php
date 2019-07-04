<?php
/**
 * NRS Customers Observer (no setup for single customer)
 * Abstract class cannot be inherited anymore. We use them when creating new instances
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Customer;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\iObserver;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;

class CustomersObserver implements iObserver
{
    protected $conf 	                = NULL;
    protected $lang 		            = NULL;
    protected $settings		            = array();
    protected $debugMode 	            = 0;

    /**
     * CustomersObserver constructor.
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
     * Sets the customer id for class. Used only from class constructor
     * @note - we don't use blog_id here, because customers has to be cross-site
     * @param string $paramCustomerEmail
     * @return int
     */
    public function getIdByEmail($paramCustomerEmail)
    {
        $validCustomerEmail = esc_sql(sanitize_email($paramCustomerEmail)); // for sql queries only
        $retCustomerId = 0;

        $customerData = $this->conf->getInternalWPDB()->get_row("
            SELECT customer_id
            FROM {$this->conf->getPrefix()}customers
            WHERE email='{$validCustomerEmail}'
        ", ARRAY_A);
        if(!is_null($customerData))
        {
            $retCustomerId = $customerData['customer_id'];
        }

        return $retCustomerId;
    }

    /**
     * @note - We don't use blog_id here, because we want to see customers from all sites
     * @param string $paramBookingType - "first", "last"
     * @param int $paramTimestampFrom
     * @param int $paramTimestampTo
     * @return array
     */
    public function getAllIds($paramBookingType = "", $paramTimestampFrom = 0, $paramTimestampTo = 0)
    {
        $validTimestampFrom = StaticValidator::getValidPositiveInteger($paramTimestampFrom, 0);
        $validTimestampTo = StaticValidator::getValidPositiveInteger($paramTimestampTo, 0);
        if($paramBookingType == "last")
        {
            $sqlCondition = "WHERE last_visit_timestamp BETWEEN $validTimestampFrom AND $validTimestampTo";
            $sqlOrderBy = "ORDER BY last_visit_timestamp ASC";
        } else if($paramBookingType == "first")
        {
            $sqlCondition = "WHERE registration_timestamp BETWEEN $validTimestampFrom AND $validTimestampTo";
            $sqlOrderBy = "ORDER BY registration_timestamp ASC";
        } else
        {
            // Do nothing, get all
            $sqlCondition = "";
            $sqlOrderBy = "ORDER BY first_name ASC, last_name ASC";
        }

        $searchSQL = "
            SELECT customer_id
            FROM {$this->conf->getPrefix()}customers
            {$sqlCondition}
            {$sqlOrderBy}
		";

        //DEBUG
        //echo nl2br($searchSQL)."<br /><br />";

        $searchResult = $this->conf->getInternalWPDB()->get_col($searchSQL);

        return $searchResult;
    }


    /*******************************************************************************/
    /********************** METHODS FOR ADMIN ACCESS ONLY **************************/
    /*******************************************************************************/

    /**
     * @param int $paramTimestampFrom
     * @param int $paramTimestampTo
     * @param string $paramBackToURLPart
     * @return string
     */
    public function getAdminListByFirstBooking($paramTimestampFrom = 0, $paramTimestampTo = 0, $paramBackToURLPart = "")
    {
        return $this->getAdminListByBookingType($paramTimestampFrom, $paramTimestampTo, $paramBackToURLPart, 'FIRST');
    }

    public function getAdminListByLastBooking($paramTimestampFrom = 0, $paramTimestampTo = 0, $paramBackToURLPart = "")
    {
        return $this->getAdminListByBookingType($paramTimestampFrom, $paramTimestampTo, $paramBackToURLPart, 'LAST');
    }

    public function getAdminList($paramBackToURLPart = "")
    {
        return $this->getAdminListByBookingType(-1, -1, $paramBackToURLPart, '');
    }

    /**
     * @param int $paramTimestampFrom - -1 means 'skip'
     * @param int $paramTimestampTo - -1 means 'skip'
     * @param string $paramBackToURLPart
     * @param string $paramBookingType - '', 'first', 'last'
     * @return string
     */
    private function getAdminListByBookingType($paramTimestampFrom = -1, $paramTimestampTo = -1, $paramBackToURLPart = "", $paramBookingType = "")
    {
        $sanitizedBackToURLPart = sanitize_text_field($paramBackToURLPart); // TEST: do not escape it, as it is for url redirect
        //$validBackToURLPart = esc_attr($sanitizedBackToURLPart); // escaped, as it is attribute for JS

        $html = '';
        $customerIds = $this->getAllIds($paramBookingType, $paramTimestampFrom, $paramTimestampTo);
        foreach ($customerIds AS $customerId)
        {
            $objCustomer = new Customer($this->conf, $this->lang, $this->settings, $customerId);
            $customerDetails = $objCustomer->getDetails();
			

            if(is_multisite() && $customerDetails['blog_id'] != $this->conf->getBlogId())
            {
                switch_to_blog($customerDetails['blog_id']);
            }

            $html .= '<tr>';
            $html .= '<td>'.$customerId.'</td>';
            $html .= '<td>'.$customerDetails['print_full_name'].'<br /><a target="_blank" href="https://dashboard.stripe.com/customers/'.$customerDetails["cotumer_stripe_id"].'">'.$customerDetails["cotumer_stripe_id"].'</a></td>';
            $html .= '<td style="white-space: nowrap">'.$customerDetails['print_birthdate'].'</td>';
            $html .= '<td>'.$customerDetails['print_street_address'].", ".$customerDetails['print_city'].", ".$customerDetails['print_country']." - ".$customerDetails['print_zip_code'].'</td>';
            $html .= '<td>'.$customerDetails['print_phone'].'</td>';
            $html .= '<td>'.$customerDetails['print_email'].'</td>';
            $html .= '<td style="white-space: nowrap">'.$customerDetails['print_registration_date'].'</td>';
            $html .= '<td style="white-space: nowrap">'.$customerDetails['print_last_visit_date'].'</td>';
            $html .= '<td align="right" nowrap="nowrap">';
            if(current_user_can('view_'.$this->conf->getExtensionPrefix().'all_bookings'))
            {
                if($customerDetails['existing_customer'] == 1)
                {
                    $html .= '<a href="'.admin_url('admin.php?page='.$this->conf->getURLPrefix().'booking-search-results&amp;customer_id='.$customerId).'">'.$this->lang->getText('NRS_ADMIN_VIEW_BOOKINGS_TEXT').'</a><br />';
                } else
                {
                    $html .= '<a href="'.admin_url('admin.php?page='.$this->conf->getURLPrefix().'booking-search-results&amp;customer_id='.$customerId).'">'.$this->lang->getText('NRS_ADMIN_VIEW_UNPAID_BOOKINGS_TEXT').'</a><br />';
                }
            }
            if(current_user_can('manage_'.$this->conf->getExtensionPrefix().'all_customers'))
            {
                $html .= '<a href="'.admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-customer&amp;customer_id='.$customerId).'">'.$this->lang->getText('NRS_ADMIN_EDIT_TEXT').'</a> || ';
                $html .= '<a href="javascript:;" onclick="javascript:delete'.$this->conf->getExtensionFolder().'Customer(\''.$customerId.'\', \''.$sanitizedBackToURLPart.'\')">'.$this->lang->getText('NRS_ADMIN_DELETE_TEXT').'</a>';
            }
            if(current_user_can('view_'.$this->conf->getExtensionPrefix().'all_bookings') === FALSE && current_user_can('manage_'.$this->conf->getExtensionPrefix().'all_customers') === FALSE)
            {
                $html .= '--';
            }
            $html .= '</td>';
            $html .= '</tr>';

            if(is_multisite())
            {
                // Switch back to current blog id. Restore current blog won't work here, as it would just restore to previous blog of the long loop
                switch_to_blog($this->conf->getBlogId());
            }
        }

        return $html;
    }
}