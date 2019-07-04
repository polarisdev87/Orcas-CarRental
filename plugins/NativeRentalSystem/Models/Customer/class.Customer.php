<?php
/**
 * Customer manager

 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Customer;
use NativeRentalSystem\Models\AbstractElement;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\iElement;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;

class Customer extends AbstractElement implements iElement
{
    protected $conf                         = NULL;
    protected $lang 		                = NULL;
    protected $debugMode 	                = 0;
    protected $shortDateFormat              = "Y-m-d";
    protected $customerId                   = 0;
    protected $titleRequired                = FALSE;
    protected $firstNameRequired            = FALSE;
    protected $lastNameRequired             = FALSE;
    protected $birthdateRequired            = FALSE;
    protected $streetAddressRequired        = FALSE;
    protected $cityRequired                 = FALSE;
    protected $stateRequired                = FALSE;
    protected $zipCodeRequired              = FALSE;
    protected $countryRequired              = FALSE;
    protected $phoneRequired                = FALSE;
    protected $emailRequired                = FALSE;
    protected $commentsRequired             = FALSE;

    /**
     * Customer constructor.
     * @param ExtensionConfiguration &$paramConf
     * @param Language &$paramLang
     * @param array $paramSettings
     * @param int $paramCustomerId
     */
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings, $paramCustomerId)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;

        // Set customer id
        $this->customerId = StaticValidator::getValidPositiveInteger($paramCustomerId, 0);
        if(isset($paramSettings['conf_short_date_format']))
        {
            $this->shortDateFormat = sanitize_text_field($paramSettings['conf_short_date_format']);
        }

        // Set customer fields requirement status
        if(isset(
            $paramSettings['conf_customer_title_required'],
            $paramSettings['conf_customer_first_name_required'],
            $paramSettings['conf_customer_last_name_required'],
            $paramSettings['conf_customer_birthdate_required'],
            $paramSettings['conf_customer_street_address_required'],
            $paramSettings['conf_customer_city_required'],
            $paramSettings['conf_customer_state_required'],
            $paramSettings['conf_customer_zip_code_required'],
            $paramSettings['conf_customer_country_required'],
            $paramSettings['conf_customer_phone_required'],
            $paramSettings['conf_customer_email_required'],
            $paramSettings['conf_customer_comments_required']
        ))
        {
            $this->titleRequired = $paramSettings['conf_customer_title_required'] == 1 ? TRUE : FALSE;
            $this->firstNameRequired = $paramSettings['conf_customer_first_name_required'] ? TRUE : FALSE;
            $this->lastNameRequired = $paramSettings['conf_customer_last_name_required'] ? TRUE : FALSE;
            $this->birthdateRequired = $paramSettings['conf_customer_birthdate_required'] ? TRUE : FALSE;
            $this->streetAddressRequired = $paramSettings['conf_customer_street_address_required'] ? TRUE : FALSE;
            $this->cityRequired = $paramSettings['conf_customer_city_required'] ? TRUE : FALSE;
            $this->stateRequired = $paramSettings['conf_customer_state_required'] ? TRUE : FALSE;
            $this->zipCodeRequired = $paramSettings['conf_customer_zip_code_required'] ? TRUE : FALSE;
            $this->countryRequired = $paramSettings['conf_customer_country_required'] ? TRUE : FALSE;
            $this->phoneRequired = $paramSettings['conf_customer_phone_required'] ? TRUE : FALSE;
            $this->emailRequired = $paramSettings['conf_customer_email_required'] ? TRUE : FALSE;
            $this->commentsRequired = $paramSettings['conf_customer_comments_required'] ? TRUE : FALSE;
        }
    }

    /**
     * @param $paramCustomerId
     * @return mixed
     */
    private function getDataFromDatabaseById($paramCustomerId)
    {
        $validCustomerId = StaticValidator::getValidPositiveInteger($paramCustomerId, 0);

        $retData = $this->conf->getInternalWPDB()->get_row("
            SELECT *
            FROM {$this->conf->getPrefix()}customers
            WHERE customer_id='{$validCustomerId}'
        ", ARRAY_A);

        return $retData;
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    public function getId()
    {
        return $this->customerId;
    }

    public function getAge()
    {
        $customerData = $this->getDataFromDatabaseById($this->customerId);
        $birthDate = "0000-00-00"; // ISO DATE
        if(!is_null($customerData))
        {
            $birthDate = $customerData['birthdate'];
        }
        // Explode the date to get month, day and year
        $dateParts = explode("-", $birthDate);
        // Get age from date or birthdate
        $retAge = (date("md", date("U", mktime(0, 0, 0, $dateParts[2], $dateParts[0], $dateParts[1]))) > date("md")
            ? ((date("Y") - $dateParts[0]) - 1)
            : (date("Y") - $dateParts[0]));

        return $retAge;
    }

    /**
     * @param bool $paramIncludeUnclassified
     * @return mixed
     */
    public function getDetails($paramIncludeUnclassified = FALSE)
    {
        $ret = $this->getDataFromDatabaseById($this->customerId);
        $printBirthdate = '';
        $printRegistrationDate = '';
        $printLastVisitDate = '';
        if(!is_null($ret))
        {
            // Make raw
            $ret['title'] = stripslashes($ret['title']);
            $ret['first_name'] = stripslashes($ret['first_name']);
            $ret['last_name'] = stripslashes($ret['last_name']);
            $ret['street_address'] = stripslashes($ret['street_address']);
            $ret['city'] = stripslashes($ret['city']);
            $ret['state'] = stripslashes($ret['state']);
            $ret['zip_code'] = stripslashes($ret['zip_code']);
            $ret['country'] = stripslashes($ret['country']);
            $ret['phone'] = stripslashes($ret['phone']);
            $ret['email'] = stripslashes($ret['email']);
            $ret['comments'] = stripslashes($ret['comments']);

            if($ret['registration_timestamp'] > 0)
            {
                $ret['registration_date'] = date_i18n($this->shortDateFormat, $ret['registration_timestamp'] + get_option( 'gmt_offset' ) * 360, TRUE);
                $printRegistrationDate = date_i18n($this->shortDateFormat, $ret['registration_timestamp'] + get_option( 'gmt_offset' ) * 360, TRUE);
            } else
            {
                $ret['registration_date'] = '';
                $printRegistrationDate = $this->lang->getText('NRS_ADMIN_NEVER_TEXT');
            }

            if($ret['last_visit_timestamp'] > 0)
            {
                $ret['last_visit_date'] = date_i18n("Y-m-d", $ret['last_visit_timestamp'] + get_option( 'gmt_offset' ) * 3600, TRUE);
                $printLastVisitDate = date_i18n($this->shortDateFormat, $ret['last_visit_timestamp'] + get_option( 'gmt_offset' ) * 3600, TRUE);
            } else
            {
                $ret['last_visit_date'] = '';
                $printLastVisitDate = $this->lang->getText('NRS_ADMIN_NEVER_TEXT');
            }

            $titleValueLabel = array(
                "Mr." 	=> $this->lang->getText('NRS_MR_TEXT'),
                "Ms." 	=> $this->lang->getText('NRS_MS_TEXT'),
                "Mrs." 	=> $this->lang->getText('NRS_MRS_TEXT'),
                "Miss." => $this->lang->getText('NRS_MISS_TEXT'),
                "Dr." 	=> $this->lang->getText('NRS_DR_TEXT'),
                "Prof." => $this->lang->getText('NRS_PROF_TEXT'),
            );

            // Override if exist
            $ret['translated_title'] = "";
            if($ret['title'] != "" && isset($titleValueLabel[$ret['title']]))
            {
                $ret['translated_title'] = $titleValueLabel[$ret['title']];
            }
            $ret['full_name'] = $this->lang->getText('NRS_CUSTOMER_TEXT');
            if($ret['first_name'] != "" || $ret['last_name'] != "")
            {
                $ret['full_name'] = $ret['translated_title']." ".$ret['first_name']." ".$ret['last_name'];
            }

            // Get birthdate parts
            $customerBirthDateParts = explode("-", $ret['birthdate']);
            $ret['birth_year'] = $customerBirthDateParts[0];
            $ret['birth_month'] = $customerBirthDateParts[1];
            $ret['birth_day'] = $customerBirthDateParts[2];
            $printBirthdate = date_i18n($this->shortDateFormat, strtotime($ret['birthdate']." 00:00:00"), TRUE);
        } elseif($paramIncludeUnclassified == TRUE)
        {
            // Make blank data
            $ret['title'] = '';
            $ret['translated_title'] = '';
            $ret['first_name'] = '';
            $ret['last_name'] = '';
            $ret['full_name'] = $this->lang->getText('NRS_CUSTOMER_TEXT');
            $ret['birthdate'] = '0000-00-00';
            $printBirthdate = $this->lang->getText('NRS_NOT_SET_TEXT');
            $ret['birth_year'] = '0000';
            $ret['birth_month'] = '00';
            $ret['birth_day'] = '00';
            $ret['street_address'] = '';
            $ret['city'] = '';
            $ret['state'] = '';
            $ret['zip_code'] = '';
            $ret['country'] = '';
            $ret['phone'] = '';
            $ret['email'] = '';
            $ret['comments'] = '';
            $ret['ip'] = '';
            $ret['registration_timestamp'] = 0;
            $ret['last_visit_timestamp'] = 0;
            $ret['registration_date'] = '';
            $printRegistrationDate = $this->lang->getText('NRS_ADMIN_NEVER_TEXT');
            $ret['last_visit_date'] = '';
            $printLastVisitDate = $this->lang->getText('NRS_ADMIN_NEVER_TEXT');
            $ret['translated_title'] = "";
        }

        if(!is_null($ret) || $paramIncludeUnclassified === TRUE)
        {
            // Prepare output for print
            $ret['print_title'] = esc_html($ret['title']);
            $ret['print_translated_title'] = esc_html($ret['translated_title']);
            $ret['print_full_name'] = esc_html($ret['full_name']);
            $ret['print_street_address'] = esc_html($ret['street_address']);
            $ret['print_city'] = esc_html($ret['city']);
            $ret['print_state'] = esc_html($ret['state']);
            $ret['print_zip_code'] = esc_html($ret['zip_code']);
            $ret['print_country'] = esc_html($ret['country']);
            $ret['print_phone'] = esc_html($ret['phone']);
            $ret['print_email'] = esc_html($ret['email']);
            $ret['print_comments'] = nl2br(implode("\n", array_map('esc_html', explode("\n", $ret['comments']))));
            $ret['print_ip'] = esc_html($ret['ip']);
            $ret['print_birthdate'] = $printBirthdate;
            $ret['print_registration_date'] = $printRegistrationDate;
            $ret['print_last_visit_date'] = $printLastVisitDate;

            // Prepare output for edit
            $ret['edit_first_name'] = esc_attr($ret['first_name']); // for input field
            $ret['edit_last_name'] = esc_attr($ret['last_name']); // for input field
            $ret['edit_street_address'] = esc_attr($ret['street_address']); // for input field
            $ret['edit_city'] = esc_attr($ret['city']); // for input field
            $ret['edit_state'] = esc_attr($ret['state']); // for input field
            $ret['edit_zip_code'] = esc_attr($ret['zip_code']); // for input field
            $ret['edit_country'] = esc_attr($ret['country']); // for input field
            $ret['edit_phone'] = esc_attr($ret['phone']); // for input field
            $ret['edit_email'] = esc_attr($ret['email']); // for input field
            $ret['edit_comments'] = esc_textarea($ret['comments']); // for textarea field
            $ret['edit_ip'] = esc_attr($ret['ip']); // for textarea field
        }

        return $ret;
    }

    public function getTitleDropDownOptions($paramSelectedTitle = "", $paramDefaultValue = "", $paramDefaultLabel = "")
    {
        $printDefaultValue = esc_html(sanitize_text_field($paramDefaultValue));
        $printDefaultLabel = esc_html(sanitize_text_field($paramDefaultLabel));

        $titleValueLabel = array(
            "Mr." 	=> $this->lang->getText('NRS_MR_TEXT'),
            "Ms." 	=> $this->lang->getText('NRS_MS_TEXT'),
            "Mrs." 	=> $this->lang->getText('NRS_MRS_TEXT'),
            "Miss." => $this->lang->getText('NRS_MISS_TEXT'),
            "Dr." 	=> $this->lang->getText('NRS_DR_TEXT'),
            "Prof." => $this->lang->getText('NRS_PROF_TEXT'),
        );

        $defaultSelected = $paramSelectedTitle == $paramDefaultValue ? ' selected="selected"' : '';
        $retHTML = '<option value="'.$printDefaultValue.'"'.$defaultSelected.'>'.$printDefaultLabel.'</option>';
        foreach($titleValueLabel as $key => $value)
        {
            $selected = $paramSelectedTitle == $key ? ' selected="selected"': '';
            $retHTML .= '<option value="'.$key.'"'.$selected.'>'.$value.'</option>';
        }

        return $retHTML;
    }

    /**
     * @note - we do not update IP or last visit here, as we don't know if that is not called by admin.
     *         We do that with separate - updateLastVisit() - method.
     * @return bool|false|int
     */
    public function save()
    {
        $saved = FALSE;
        $ok = TRUE;
        $validCustomerId = StaticValidator::getValidPositiveInteger($this->customerId, 0);
        $customerDetails = $this->getDetails();
        if(!is_null($customerDetails))
        {
            // Set existing data to not to loose the data of disabled and not visible customer fields that were previously enabled
            $validTitle         = esc_sql(sanitize_text_field($customerDetails['title'])); // for sql query only
            $validFirstName     = esc_sql(sanitize_text_field($customerDetails['first_name'])); // for sql query only
            $validLastName      = esc_sql(sanitize_text_field($customerDetails['last_name'])); // for sql query only
            $validBirthdate     = esc_sql(sanitize_text_field($customerDetails['birthdate'])); // for sql query only
            $validStreetAddress = esc_sql(sanitize_text_field($customerDetails['street_address'])); // for sql query only
            $validCity          = esc_sql(sanitize_text_field($customerDetails['city'])); // for sql query only
            $validState         = esc_sql(sanitize_text_field($customerDetails['state'])); // for sql query only
            $validZIPCode       = esc_sql(sanitize_text_field($customerDetails['zip_code'])); // for sql query only
            $validCountry       = esc_sql(sanitize_text_field($customerDetails['country'])); // for sql query only
            $validPhone         = esc_sql(sanitize_text_field($customerDetails['phone'])); // for sql query only
            $validEmail         = esc_sql(sanitize_email($customerDetails['email'])); // for sql query only
            $validComments      = esc_sql(sanitize_text_field($customerDetails['comments'])); // for sql query only
        } else
        {
            $validTitle         = '';
            $validFirstName     = '';
            $validLastName      = '';
            $validBirthdate     = '0000-00-00';
            $validStreetAddress = '';
            $validCity          = '';
            $validState         = '';
            $validZIPCode       = '';
            $validCountry       = '';
            $validPhone         = '';
            $validEmail         = '';
            $validComments      = '';
        }

        $validTitle = isset($_POST['title']) ? esc_sql(sanitize_text_field($_POST['title'])) : $validTitle; // for sql query only
        $validFirstName = isset($_POST['first_name']) ? esc_sql(sanitize_text_field($_POST['first_name'])) : $validFirstName; // for sql query only
        $validLastName = isset($_POST['last_name']) ? esc_sql(sanitize_text_field($_POST['last_name'])) : $validLastName; // for sql query only
        if(isset($_POST['birth_year'], $_POST['birth_month'], $_POST['birth_day']))
        {
            $validBirthdate = intval($_POST['birth_year']).'-'.intval($_POST['birth_month']).'-'.intval($_POST['birth_day']);
        }
        $validStreetAddress = isset($_POST['street_address']) ? esc_sql(sanitize_text_field($_POST['street_address'])) : $validStreetAddress; // for sql query only
        $validCity = isset($_POST['city']) ? esc_sql(sanitize_text_field($_POST['city'])) : $validCity; // for sql query only
        $validState = isset($_POST['state']) ? esc_sql(sanitize_text_field($_POST['state'])) : $validState; // for sql query only
        $validZIPCode = isset($_POST['zip_code']) ? esc_sql(sanitize_text_field($_POST['zip_code'])) : $validZIPCode; // for sql query only
        $validCountry = isset($_POST['country']) ? esc_sql(sanitize_text_field($_POST['country'])) : $validCountry; // for sql query only
        $validPhone = isset($_POST['phone']) ? esc_sql(sanitize_text_field($_POST['phone'])) : $validPhone; // for sql query only
        $validEmail = isset($_POST['email']) ? esc_sql(sanitize_email($_POST['email'])) : $validEmail; // for sql query only
        $validComments = isset($_POST['comments']) ? esc_sql(implode("\n", array_map('sanitize_text_field', explode("\n", $_POST['comments'])))) : $validComments; // for sql query only

        if($this->titleRequired && $validTitle == '')
        {
            $ok = FALSE;
            $this->errorMessages[] = $this->lang->getText('NRS_CUSTOMER_TITLE_REQUIRED_ERROR_TEXT');
        }
        if($this->firstNameRequired && $validFirstName == '')
        {
            $ok = FALSE;
            $this->errorMessages[] = $this->lang->getText('NRS_CUSTOMER_FIRST_NAME_REQUIRED_ERROR_TEXT');
        }
        if($this->lastNameRequired && $validLastName == '')
        {
            $ok = FALSE;
            $this->errorMessages[] = $this->lang->getText('NRS_CUSTOMER_LAST_NAME_REQUIRED_ERROR_TEXT');
        }
        if($this->birthdateRequired && $validBirthdate == '')
        {
            $ok = FALSE;
            $this->errorMessages[] = $this->lang->getText('NRS_CUSTOMER_BIRTHDATE_REQUIRED_ERROR_TEXT');
        }
        if($this->streetAddressRequired && $validStreetAddress == '')
        {
            $ok = FALSE;
            $this->errorMessages[] = $this->lang->getText('NRS_CUSTOMER_STREET_ADDRESS_REQUIRED_ERROR_TEXT');
        }
        if($this->cityRequired && $validCity == '')
        {
            $ok = FALSE;
            $this->errorMessages[] = $this->lang->getText('NRS_CUSTOMER_CITY_REQUIRED_ERROR_TEXT');
        }
        if($this->stateRequired && $validState == '')
        {
            $ok = FALSE;
            $this->errorMessages[] = $this->lang->getText('NRS_CUSTOMER_STATE_REQUIRED_ERROR_TEXT');
        }
        if($this->zipCodeRequired && $validZIPCode == '')
        {
            $ok = FALSE;
            $this->errorMessages[] = $this->lang->getText('NRS_CUSTOMER_ZIP_CODE_REQUIRED_ERROR_TEXT');
        }
        if($this->countryRequired && $validCountry == '')
        {
            $ok = FALSE;
            $this->errorMessages[] = $this->lang->getText('NRS_CUSTOMER_COUNTRY_REQUIRED_ERROR_TEXT');
        }
        if($this->phoneRequired && $validPhone == '')
        {
            $ok = FALSE;
            $this->errorMessages[] = $this->lang->getText('NRS_CUSTOMER_PHONE_REQUIRED_ERROR_TEXT');
        }
        if($this->emailRequired && $validEmail == '')
        {
            $ok = FALSE;
            $this->errorMessages[] = $this->lang->getText('NRS_CUSTOMER_EMAIL_REQUIRED_ERROR_TEXT');
        }
        if($this->commentsRequired && $validComments == '')
        {
            $ok = FALSE;
            $this->errorMessages[] = $this->lang->getText('NRS_CUSTOMER_COMMENTS_REQUIRED_ERROR_TEXT');
        }

        if($validCustomerId > 0 && $ok)
        {
            $saved = $this->conf->getInternalWPDB()->query("
				UPDATE {$this->conf->getPrefix()}customers SET
				title = '{$validTitle}',
				first_name = '{$validFirstName}',
				last_name = '{$validLastName}',
				birthdate = '{$validBirthdate}',
				street_address = '{$validStreetAddress}',
				city = '{$validCity}',
				state = '{$validState}',
				zip_code = '{$validZIPCode}',
				country = '{$validCountry}',
				phone = '{$validPhone}',
				email = '{$validEmail}',
				comments = '{$validComments}'
				WHERE customer_id = '{$validCustomerId}' AND blog_id='{$this->conf->getBlogId()}'
			");

            if($saved === FALSE)
            {
                $this->errorMessages[] = $this->lang->getText('NRS_CUSTOMER_UPDATE_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('NRS_CUSTOMER_UPDATED_TEXT');
            }
        } else if($ok)
        {
            $saved = $this->conf->getInternalWPDB()->query("
				INSERT INTO {$this->conf->getPrefix()}customers
				(
					title, first_name, last_name,
					birthdate,
					street_address, city, state,
					zip_code, country, phone,
					email, comments, ip,
					existing_customer, registration_timestamp, last_visit_timestamp,
					blog_id
				) values
				(
					'{$validTitle}', '{$validFirstName}', '{$validLastName}',
					'{$validBirthdate}',
					'{$validStreetAddress}', '{$validCity}' , '{$validState}',
					'{$validZIPCode}', '{$validCountry}', '{$validPhone}',
					'{$validEmail}', '{$validComments}', '0.0.0.0',
					'0', '".time()."', '0',
					'{$this->conf->getBlogId()}'
				)
			");

            if($saved)
            {
                // Get newly inserted customer id
                $validInsertedNewCustomerId = $this->conf->getInternalWPDB()->insert_id;

                // Update the core customer id for future use
                $this->customerId = $validInsertedNewCustomerId;
            }

            if($saved === FALSE || $saved === 0)
            {
                $this->errorMessages[] = $this->lang->getText('NRS_CUSTOMER_INSERT_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('NRS_CUSTOMER_INSERTED_TEXT');
            }
        }

        return $saved;
    }

    public function updateLastVisit()
    {
        $validCustomerId = StaticValidator::getValidPositiveInteger($this->customerId, 0);
        $validIP = esc_sql(sanitize_text_field($_SERVER['REMOTE_ADDR'])); // for sql query only

        $updated = $this->conf->getInternalWPDB()->query("
            UPDATE {$this->conf->getPrefix()}customers SET
            last_visit_timestamp = '".time()."', ip = '{$validIP}'
            WHERE customer_id = '{$validCustomerId}' AND blog_id='{$this->conf->getBlogId()}'
        ");

        if($updated === FALSE || $updated === 0)
        {
            $this->errorMessages[] = $this->lang->getText('NRS_CUSTOMER_LAST_VISIT_UPDATE_ERROR_TEXT');
        } else
        {
            $this->okayMessages[] = $this->lang->getText('NRS_CUSTOMER_LAST_VISIT_UPDATED_TEXT');
        }

        return $updated;
    }

    /**
     * Not used for this element
     */
    public function registerForTranslation()
    {
        // not used
    }

    public function delete()
    {
        $validCustomerId = StaticValidator::getValidPositiveInteger($this->customerId, 0);
        $deleted = $this->conf->getInternalWPDB()->query("
            DELETE FROM {$this->conf->getPrefix()}customers
            WHERE customer_id='{$validCustomerId}' AND blog_id='{$this->conf->getBlogId()}'
        ");

        if($deleted === FALSE || $deleted === 0)
        {
            $this->errorMessages[] = $this->lang->getText('NRS_CUSTOMER_DELETE_ERROR_TEXT');
        } else
        {
            $this->okayMessages[] = $this->lang->getText('NRS_CUSTOMER_DELETED_TEXT');
        }

        return $deleted;
    }
}