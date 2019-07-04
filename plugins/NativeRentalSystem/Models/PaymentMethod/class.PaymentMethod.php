<?php
/**
 * NRS Control Root Class - we use it in initializer, so it cant be abstract
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\PaymentMethod;
use NativeRentalSystem\Models\AbstractElement;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\iElement;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;

class PaymentMethod extends AbstractElement implements iElement
{
    protected $conf 	            = NULL;
    protected $lang 		        = NULL;
    protected $debugMode 	        = 0;
    protected $paymentMethodId      = 0;

    /**
     * PaymentMethod constructor.
     * @param ExtensionConfiguration $paramConf
     * @param Language $paramLang
     * @param array $paramSettings
     * @param int $paramPaymentMethodId
     */
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings, $paramPaymentMethodId)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;

        $this->paymentMethodId = StaticValidator::getValidPositiveInteger($paramPaymentMethodId, 0);
    }

    private function getDataFromDatabaseById($paramPaymentMethodId)
    {
        $validPaymentMethodId = StaticValidator::getValidPositiveInteger($paramPaymentMethodId, 0);
        $paymentMethodData = $this->conf->getInternalWPDB()->get_row("
            SELECT *
            FROM {$this->conf->getPrefix()}payment_methods
            WHERE payment_method_id='{$validPaymentMethodId}'
        ", ARRAY_A);

        return $paymentMethodData;
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    public function getId()
    {
        return $this->paymentMethodId;
    }

    /**
     * Element-specific method
     * @return string
     */
    public function getCode()
    {
        $retCode = "";
        $paymentMethodData = $this->getDataFromDatabaseById($this->paymentMethodId);
        if(!is_null($paymentMethodData))
        {
            // Make raw
            $retCode = stripslashes($paymentMethodData['payment_method_code']);
        }
        return $retCode;
    }

    public function generateCode()
    {
        $nextInsertId = 1;
        $sqlQuery = "
            SHOW TABLE STATUS LIKE '{$this->conf->getPrefix()}payment_methods'
        ";
        $data = $this->conf->getInternalWPDB()->get_row($sqlQuery, ARRAY_A);
        if(!is_null($data))
        {
            $nextInsertId = $data['Auto_increment'];

        }

        $paymentMethodCode = 'PM_'.$nextInsertId;

        return $paymentMethodCode;
    }

    /**
     * Element-specific method
     */
    public function isValid()
    {
        $paymentMethodId = $this->getDataFromDatabaseById($this->paymentMethodId);
        if(!is_null($paymentMethodId))
        {
            return TRUE;
        } else
        {
            return FALSE;
        }
    }

    /**
     * Element-specific method
     * @return string
     */
    public function isOnlinePayment()
    {
        $isOnlinePayment = FALSE;
        $paymentMethodData = $this->getDataFromDatabaseById($this->paymentMethodId);
        if(!is_null($paymentMethodData))
        {
            $isOnlinePayment = $paymentMethodData['online_payment'] == 1 ? TRUE : FALSE;
        }

        return $isOnlinePayment;
    }

    public function inSandboxMode()
    {
        $retInSandboxMode = FALSE;

        $row = $this->getDataFromDatabaseById($this->paymentMethodId);

        if(!is_null($row))
        {
            $retInSandboxMode = $row['sandbox_mode'] == 1 ? TRUE : FALSE;
        }

        return $retInSandboxMode;
    }
    /**
     * Element specific method
     * @param bool $paramIncludeUnclassified - not used
     * @return mixed
     */
    public function getDetails($paramIncludeUnclassified = FALSE)
    {
        $ret = $this->getDataFromDatabaseById($this->paymentMethodId);
        if(!is_null($ret))
        {
            // Make raw
            $ret['payment_method_code'] = stripslashes($ret['payment_method_code']);
            $ret['class_name'] = stripslashes($ret['class_name']);
            $ret['payment_method_name'] = stripslashes($ret['payment_method_name']);
            $ret['payment_method_email'] = stripslashes($ret['payment_method_email']);
            $ret['payment_method_description'] = stripslashes($ret['payment_method_description']);
            $ret['public_key'] = stripslashes($ret['public_key']);
            $ret['private_key'] = stripslashes($ret['private_key']);

            // Process new fields
            if($ret['class_name'] != "")
            {
                $folderAndFileName = $ret['class_name'].DIRECTORY_SEPARATOR.$ret['class_name'].'.php';
            } else
            {
                $folderAndFileName = '';
            }
            $ret['folder_and_file_name'] = $folderAndFileName;
            $ret['translated_payment_method_name'] = $this->lang->getTranslated("pm{$ret['payment_method_id']}_payment_method_name", $ret['payment_method_name']);
            $ret['translated_payment_method_description'] = $this->lang->getTranslated("pm{$ret['payment_method_id']}_payment_method_description", $ret['payment_method_description']);

            // Prepare output for print
            $ret['print_payment_method_code'] = esc_html($ret['payment_method_code']);
            $ret['print_class_name'] = esc_html($ret['class_name']);
            $ret['print_folder_and_file_name'] = esc_html($ret['folder_and_file_name']);
            $ret['print_payment_method_name'] = esc_html($ret['payment_method_name']);
            $ret['print_translated_payment_method_name'] = esc_html($ret['translated_payment_method_name']);
            $ret['print_payment_method_email'] = esc_html($ret['payment_method_email']);
            $ret['payment_method_description_html'] = nl2br($ret['payment_method_description']); // nl2br and esc_html order here is important
            $ret['translated_payment_method_description_html'] = nl2br($ret['translated_payment_method_description']); // nl2br and esc_html order here is important
            $ret['print_public_key'] = esc_html($ret['public_key']);
            $ret['print_private_key'] = esc_html($ret['private_key']);

            // Prepare output for edit
            $ret['edit_payment_method_code'] = esc_attr($ret['payment_method_code']); // for input field
            $ret['edit_class_name'] = esc_attr($ret['class_name']); // for input field
            $ret['edit_payment_method_name'] = esc_attr($ret['payment_method_name']); // for input field
            $ret['edit_payment_method_email'] = esc_attr($ret['payment_method_email']); // for input field
            $ret['edit_payment_method_description'] = esc_textarea($ret['payment_method_description']); // for textarea field
            $ret['edit_public_key'] = esc_attr($ret['public_key']); // for input field
            $ret['edit_private_key'] = esc_attr($ret['private_key']); // for input field
        }

        return $ret;
    }

    /**
     * @return bool|false|int
     */
    public function save()
    {
        $ok = TRUE;
        $saved = FALSE;
        $validPaymentMethodId        = StaticValidator::getValidPositiveInteger($this->paymentMethodId, 0);

        // Do not use sanitize_key here, because we don't want to get it lowercase
        if($this->conf->isNetworkEnabled())
        {
            $sanitizedPaymentMethodCode = isset($_POST['payment_method_code']) ? sanitize_text_field($_POST['payment_method_code']) : '';
        } else
        {
            $sanitizedPaymentMethodCode = sanitize_text_field($validPaymentMethodId > 0 ? $this->getCode() : $this->generateCode());
        }
        $validPaymentMethodCode = esc_sql($sanitizedPaymentMethodCode); // For sql queries only
        $sanitizedClassName = isset($_POST['class_name']) ? sanitize_text_field($_POST['class_name']) : "";
        $validClassName = esc_sql($sanitizedClassName); // For sql queries only
        $sanitizedPaymentMethodName = isset($_POST['payment_method_name']) ? sanitize_text_field($_POST['payment_method_name']) : "";
        $validPaymentMethodName = esc_sql($sanitizedPaymentMethodName); // For sql queries only
        $sanitizedPaymentMethodEmail = isset($_POST['payment_method_email']) ? sanitize_email($_POST['payment_method_email']) : "";
        $validPaymentMethodEmail = esc_sql($sanitizedPaymentMethodEmail); // For sql queries only

        // NOTE: We can't use sanitize_text_field function for $paramPaymentMethodDescription,
        // because it has <br /> tags inside. So we must use 'wp_kses_post'.
        // Still, we sure that all data used for this field entered by trusted admin only, not from regular user
        $ksesedPaymentMethodDescription = isset($_POST['payment_method_description']) ? wp_kses_post($_POST['payment_method_description']) : "";
        $validPaymentMethodDescription = esc_sql($ksesedPaymentMethodDescription); // for sql query only
        $sanitizedPublicKey = isset($_POST['public_key']) ? sanitize_text_field($_POST['public_key']) : "";
        $validPublicKey = esc_sql($sanitizedPublicKey); // for sql query only
        $sanitizedPrivateKey = isset($_POST['private_key']) ? sanitize_text_field($_POST['private_key']) : "";
        $validPrivateKey = esc_sql($sanitizedPrivateKey); // for sql query only
        $validSandboxMode = isset($_POST['sandbox_mode']) ? 1 : 0;
        $validCheckCertificate = isset($_POST['check_certificate']) ? 1 : 0;
        $validSSLOnly = isset($_POST['ssl_only']) ? 1 : 0;
        $validOnlinePayment = isset($_POST['online_payment']) ? 1 : 0;
        $validPaymentMethodEnabled = isset($_POST['payment_method_enabled']) ? 1 : 0;
        $validExpirationTime = isset($_POST['expiration_time']) ? StaticValidator::getValidPositiveInteger($_POST['expiration_time'], 0) : 0;
        if(isset($_POST['payment_method_order']) && StaticValidator::isPositiveInteger($_POST['payment_method_order']))
        {
            $validPaymentMethodOrder = StaticValidator::getValidPositiveInteger($_POST['payment_method_order'], 1);
        } else
        {
            // SELECT MAX
            $sqlQuery = "
                SELECT MAX(payment_method_order) AS max_order
                FROM {$this->conf->getPrefix()}payment_methods
                WHERE 1
            ";
            $maxOrderResult = $this->conf->getInternalWPDB()->get_var($sqlQuery);
            $validPaymentMethodOrder = !is_null($maxOrderResult) ? intval($maxOrderResult)+1 : 1;
        }

        $codeExistsQuery = "
            SELECT payment_method_id
            FROM {$this->conf->getPrefix()}payment_methods
            WHERE payment_method_code='{$validPaymentMethodCode}'
            AND payment_method_id!='{$validPaymentMethodId}' AND blog_id='{$this->conf->getBlogId()}'
        ";
        $codeExists = $this->conf->getInternalWPDB()->get_row($codeExistsQuery, ARRAY_A);

        if(!is_null($codeExists))
        {
            $ok = FALSE;
            $this->errorMessages[] = $this->lang->getText('NRS_PAYMENT_METHOD_CODE_EXISTS_ERROR_TEXT');
        }
        if($validPaymentMethodName == "")
        {
            $ok = FALSE;
            $this->errorMessages[] = $this->lang->getText('NRS_PAYMENT_METHOD_INVALID_NAME_TEXT');
        }

        if($validPaymentMethodId > 0 && $ok)
        {
            $saved = $this->conf->getInternalWPDB()->query("
                UPDATE {$this->conf->getPrefix()}payment_methods SET
                payment_method_code='{$validPaymentMethodCode}', class_name='{$validClassName}', 
                payment_method_name='{$validPaymentMethodName}', payment_method_email='{$validPaymentMethodEmail}',
                payment_method_description='{$validPaymentMethodDescription}',
                public_key='{$validPublicKey}', private_key='{$validPrivateKey}',
                sandbox_mode='{$validSandboxMode}', check_certificate='{$validCheckCertificate}',
                ssl_only='{$validSSLOnly}', online_payment='{$validOnlinePayment}',
                payment_method_enabled='{$validPaymentMethodEnabled}',
                expiration_time='{$validExpirationTime}', payment_method_order='{$validPaymentMethodOrder}'
                WHERE payment_method_id='{$validPaymentMethodId}' AND blog_id='{$this->conf->getBlogId()}'
            ");

            if($saved === FALSE)
            {
                $this->errorMessages[] = $this->lang->getText('NRS_PAYMENT_METHOD_UPDATE_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('NRS_PAYMENT_METHOD_UPDATED_TEXT');
            }
        } else if($ok)
        {
            // Register new payment method
            $saved = $this->conf->getInternalWPDB()->query("
                INSERT INTO {$this->conf->getPrefix()}payment_methods (
                    payment_method_code, class_name, payment_method_name,
                    payment_method_email, payment_method_description, public_key, private_key, sandbox_mode,
                    check_certificate, ssl_only, online_payment,
                    payment_method_enabled, payment_method_order, expiration_time, blog_id
                ) VALUES (
                    '{$validPaymentMethodCode}', '{$validClassName}', '{$validPaymentMethodName}',
                    '', '', '', '', 0,
                    '{$validCheckCertificate}', '{$validSSLOnly}', '{$validOnlinePayment}',
                    0, {$validPaymentMethodOrder}, 0, '{$this->conf->getBlogId()}'
                );
            ");
            if($saved)
            {
                // Set object id for future use
                $this->paymentMethodId = $this->conf->getInternalWPDB()->insert_id;
            }

            if($saved === FALSE || $saved === 0)
            {
                $this->errorMessages[] = $this->lang->getText('NRS_PAYMENT_METHOD_INSERT_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('NRS_PAYMENT_METHOD_INSERTED_TEXT');
            }
        }

        return $saved;
    }


    public function registerForTranslation()
    {
        $paymentMethodDetails = $this->getDetails();
        if(!is_null($paymentMethodDetails))
        {
            $this->lang->register("pm{$this->paymentMethodId}_payment_method_name", $paymentMethodDetails['payment_method_name']);
            $this->lang->register("pm{$this->paymentMethodId}_payment_method_description", $paymentMethodDetails['payment_method_description']);
            $this->okayMessages[] = $this->lang->getText('NRS_PAYMENT_METHOD_REGISTERED_TEXT');
        }
    }

    public function delete()
    {
        $validPaymentMethodId = StaticValidator::getValidPositiveInteger($this->paymentMethodId, 0);
        $sqlQuery = "
            DELETE FROM {$this->conf->getPrefix()}payment_methods
            WHERE payment_method_id='{$validPaymentMethodId}' AND blog_id='{$this->conf->getBlogId()}'
        ";
        $deleted = $this->conf->getInternalWPDB()->query($sqlQuery);

        if($deleted === FALSE || $deleted === 0)
        {
            $this->errorMessages[] = $this->lang->getText('NRS_PAYMENT_METHOD_DELETE_ERROR_TEXT');
        } else
        {
            $this->okayMessages[] = $this->lang->getText('NRS_PAYMENT_METHOD_DELETED_TEXT');
        }

        return $deleted;
    }
}