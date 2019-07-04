<?php
/**
 * NRS Control Root Class - we use it in initializer, so it cant be abstract
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\PaymentMethod;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\File\StaticFile;
use NativeRentalSystem\Models\iObserver;
use NativeRentalSystem\Models\Language\Language;

class PaymentMethodsObserver implements iObserver
{
    protected $conf 	                = NULL;
    protected $lang 		            = NULL;
    protected $debugMode 	            = 0;
    protected $settings 	            = array();

    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;
        $this->settings = $paramSettings;
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    public function getIdByCode($paramPaymentMethodCode)
    {
        $retId = 0;
        $validPaymentMethodCode = esc_sql(sanitize_text_field($paramPaymentMethodCode)); // For sql query only

        $paymentMethodData = $this->conf->getInternalWPDB()->get_row("
                SELECT payment_method_id
                FROM {$this->conf->getPrefix()}payment_methods
                WHERE payment_method_code='{$validPaymentMethodCode}' AND blog_id='{$this->conf->getBlogId()}'
            ", ARRAY_A);
        if(!is_null($paymentMethodData))
        {
            $retId = $paymentMethodData['payment_method_id'];
        }

        return $retId;
    }

    public function getAllIds()
    {
        return $this->getIds(FALSE);
    }

    public function getEnabledIds()
    {
        return $this->getIds(TRUE);
    }

    private function getIds($paramOnlyEnabled = FALSE)
    {
        $paymentMethodIds = array();
        $sqlAdd = $paramOnlyEnabled ? "AND payment_method_enabled='1'" : "";
        $sqlRows = $this->conf->getInternalWPDB()->get_results("
            SELECT payment_method_id
            FROM {$this->conf->getPrefix()}payment_methods
            WHERE blog_id='{$this->conf->getBlogId()}' {$sqlAdd}
            ORDER BY payment_method_order ASC, payment_method_name ASC
        ", ARRAY_A);

        foreach($sqlRows AS $currentRow)
        {
            $paymentMethodIds[] = $currentRow['payment_method_id'];
        }

        return $paymentMethodIds;
    }

    public function getTotalEnabledOnline()
    {
        $enabledMethods = $this->conf->getInternalWPDB()->get_results("
            SELECT payment_method_id
            FROM {$this->conf->getPrefix()}payment_methods
            WHERE online_payment='1' AND payment_method_enabled='1' AND blog_id='{$this->conf->getBlogId()}'
        ", ARRAY_A);

        return sizeof($enabledMethods);
    }

    public function getTotalEnabledLocally()
    {
        $enabledMethods = $this->conf->getInternalWPDB()->get_results("
            SELECT payment_method_id
            FROM {$this->conf->getPrefix()}payment_methods
            WHERE online_payment='0' AND payment_method_enabled='1' AND blog_id='{$this->conf->getBlogId()}'
        ", ARRAY_A);

        return sizeof($enabledMethods);
    }

    public function getTotalEnabled()
    {
        $enabledMethods = $this->conf->getInternalWPDB()->get_results("
            SELECT payment_method_id
            FROM {$this->conf->getPrefix()}payment_methods
            WHERE payment_method_enabled='1' AND blog_id='{$this->conf->getBlogId()}'
        ", ARRAY_A);

        return sizeof($enabledMethods);
    }

    public function getPaymentMethods($paramSelectedId = 0, $paramTotalPayNow = '0.00')
    {
        $arrPaymentMethods = array();

        $arrPaymentMethodIds = $this->getEnabledIds();
        // Set default to first item to be selected, if nothing is selected
        if(isset($arrPaymentMethodIds[0]))
        {
            $paramSelectedId = $paramSelectedId == 0 ? $arrPaymentMethodIds[0] : $paramSelectedId;
        }

        foreach($arrPaymentMethodIds AS $paymentMethodId)
        {
            $objPaymentMethod = new PaymentMethod($this->conf, $this->lang, $this->settings, $paymentMethodId);
            $paymentMethodDetails = $objPaymentMethod->getDetails();
            $paymentMethodDescriptionHTML = $paymentMethodDetails['translated_payment_method_description_html'];

            $paymentFolderName = $paymentMethodDetails['class_name'];
            $paymentClassName = $paymentMethodDetails['class_name'];
            $paymentFolderPathWithFileName = $this->conf->getLibrariesPath().$paymentFolderName.DIRECTORY_SEPARATOR.$paymentClassName.'.php';
            if($paymentClassName != "" && is_readable($paymentFolderPathWithFileName))
            {
                require_once $paymentFolderPathWithFileName;
                // This is ok that the classes are not found
                if(class_exists($paymentClassName))
                {
                    $objPayment = new $paymentClassName($this->conf, $this->lang, $this->settings, $paymentMethodId);
                    if(method_exists($objPayment, 'getDescriptionHTML'))
                    {
                        $paymentMethodDescriptionHTML = $objPayment->getDescriptionHTML(
                            $paymentMethodDetails['translated_payment_method_description'], $paramTotalPayNow
                        );
                    }
                }
            }

            $selected = $paramSelectedId == $paymentMethodId ? TRUE : FALSE;
            $paymentMethodDetails['selected'] = $selected;
            $paymentMethodDetails['print_checked'] = $selected ? ' checked="checked"' : '';
            $paymentMethodDetails['print_selected'] = $selected ? ' selected="selected"' : '';
            $paymentMethodDetails['payment_method_description_html'] = $paymentMethodDescriptionHTML;

            $arrPaymentMethods[] = $paymentMethodDetails;
        }

        return $arrPaymentMethods;
    }


    /**
     * Get supported styles in this plugin
     * @param $paramPaymentMethodsFolderPath
     * @param array $paramPaymentMethodFolders
     * @return array
     */
    public static function getSupportedPaymentMethodsFromFolderList($paramPaymentMethodsFolderPath = "", $paramPaymentMethodFolders = array())
    {
        $retSupportedPaymentMethods = array();
        $validPaymentMethodsFolderPath = sanitize_text_field($paramPaymentMethodsFolderPath);
        foreach($paramPaymentMethodFolders AS $paramFolderName)
        {
            $validFolderName = sanitize_text_field($paramFolderName);
            $paymentMethodFilePath = $validPaymentMethodsFolderPath.$validFolderName.DIRECTORY_SEPARATOR;
            $paymentMethodFileName = $validFolderName.'.php';
            $paymentMethodClassName = $validFolderName;
            $fullPathToPaymentMethodClassFile = $paymentMethodFilePath.$paymentMethodFileName;
            if(is_readable($paymentMethodFilePath.$paymentMethodFileName))
            {
                include_once ($fullPathToPaymentMethodClassFile);
                if(class_exists($paymentMethodClassName))
                {
                    $retSupportedPaymentMethods[] = array(
                        "file_path" => $paymentMethodFilePath,
                        "folder_name" => $validFolderName,
                        "file_name" => $paymentMethodFileName,
                        "class_name" => $paymentMethodClassName,
                    );
                }
            }
        }

        return $retSupportedPaymentMethods;
    }

    /**
     * @param string $paramSelectedPaymentMethodClassName
     * @param string $paramDefaultValue
     * @param string $paramDefaultLabel
     * @return string
     */
    public function getClassesDropDownOptions($paramSelectedPaymentMethodClassName = "", $paramDefaultValue = "", $paramDefaultLabel = "")
    {
        $printDefaultValue = esc_html(sanitize_key($paramDefaultValue));
        $printDefaultLabel = esc_html(sanitize_text_field($paramDefaultLabel));
        $defaultSelected = $paramSelectedPaymentMethodClassName == $paramDefaultValue ? ' selected="selected"' : '';
        $paymentMethodFolderHTML = '<option value="'.$printDefaultValue.'"'.$defaultSelected.'>'.$printDefaultLabel.'</option>';

        // Get Payment Methods folder list
        $paymentMethodFolders = StaticFile::getFolderList($this->conf->getLibrariesPath());
        // Get supported Payment Method folders
        $supportedPaymentMethodFolders = $this::getSupportedPaymentMethodsFromFolderList($this->conf->getLibrariesPath(), $paymentMethodFolders);

        foreach($supportedPaymentMethodFolders AS $folder)
        {
            $selected = $folder['class_name'] == $paramSelectedPaymentMethodClassName ? ' selected="selected"' : '';
            $name = $folder['class_name'].' ('.DIRECTORY_SEPARATOR.'Resources'.DIRECTORY_SEPARATOR.$folder['folder_name'].DIRECTORY_SEPARATOR.$folder['file_name'].')';
            $paymentMethodFolderHTML .= '<option value="'.$folder['class_name'].'"'.$selected.'>'.$name.'</option>'."\n";
        }

        return $paymentMethodFolderHTML;
    }


    /*******************************************************************************/
    /********************** METHODS FOR ADMIN ACCESS ONLY **************************/
    /*******************************************************************************/

    public function getAdminList()
    {
        $paymentMethodsHTML = '';
        $arrPaymentMethodIds = $this->getAllIds();

        foreach ($arrPaymentMethodIds AS $paymentMethodId)
        {
            $objPaymentMethod = new PaymentMethod($this->conf, $this->lang, $this->settings, $paymentMethodId);
            $paymentMethodDetails = $objPaymentMethod->getDetails();
            $printTranslatedPaymentMethodName = $paymentMethodDetails['print_translated_payment_method_name'];
            if($this->lang->canTranslateSQL())
            {
                $printTranslatedPaymentMethodName .= '<br /><span class="not-translated" title="'.$this->lang->getText('NRS_ADMIN_WITHOUT_TRANSLATION_TEXT').'">('.$paymentMethodDetails['print_payment_method_name'].')</span>';
            }
            if($paymentMethodDetails['folder_and_file_name'] != "")
            {
                $printTranslatedPaymentMethodName .= '<br /><span style="font-size:10px;cursor: pointer" title="'.esc_sql($this->conf->getLibrariesPath()).$paymentMethodDetails['print_folder_and_file_name'].'">'.$paymentMethodDetails['print_folder_and_file_name'].'</span>';
            }
            $printSandboxMode = $this->lang->getText($paymentMethodDetails['sandbox_mode'] == 1 ? 'NRS_ADMIN_YES_TEXT' : 'NRS_ADMIN_NO_TEXT');
            $printCheckCertificate = $this->lang->getText($paymentMethodDetails['check_certificate'] == 1 ? 'NRS_ADMIN_CHECK_TEXT' : 'NRS_ADMIN_SKIP_TEXT');
            $printSSLOnly = $this->lang->getText($paymentMethodDetails['ssl_only'] == 1 ? 'NRS_ADMIN_YES_TEXT' : 'NRS_ADMIN_NO_TEXT');
            $printOnlinePayment = $this->lang->getText($paymentMethodDetails['online_payment'] == 1 ? 'NRS_ADMIN_YES_TEXT' : 'NRS_ADMIN_NO_TEXT');
            $printPaymentMethodEnabled = $this->lang->getText($paymentMethodDetails['payment_method_enabled'] == 1 ? 'NRS_ADMIN_ENABLED_TEXT' : 'NRS_ADMIN_DISABLED_TEXT');

            if(current_user_can('manage_'.$this->conf->getExtensionPrefix().'all_inventory'))
            {
                $printEmail = $paymentMethodDetails['payment_method_email'] != '' ? '<strong>'.$this->lang->getText('NRS_ADMIN_EMAIL_TEXT').':</strong> '.$paymentMethodDetails['print_payment_method_email'] : '';
                $printPublicKey = $paymentMethodDetails['public_key'] != '' ? '<strong>'.$this->lang->getText('NRS_ADMIN_PUBLIC_TEXT').':</strong> '.$paymentMethodDetails['print_public_key'] : '';
                $printPublicKey .= ($printEmail && $printPublicKey) ? '<br />'.$printPublicKey : $printPublicKey;
                $printPrivateKey = $paymentMethodDetails['public_key'] != '' ? '<strong>'.$this->lang->getText('NRS_ADMIN_PRIVATE_TEXT').':</strong> '.$paymentMethodDetails['print_private_key'] : '';
                $printPrivateKey = ($printPublicKey && $printPrivateKey) ? '<br />'.$printPrivateKey : $printPrivateKey;
            } else
            {
                $printEmail = $this->lang->getText('NRS_ADMIN_HIDDEN_TEXT');
                $printPublicKey = '';
                $printPrivateKey = '';
            }

            $paymentMethodsHTML .= '<tr>';
            $paymentMethodsHTML .= '<td>'.$paymentMethodId.'<br />'.$paymentMethodDetails['print_payment_method_code'].'</td>';
            $paymentMethodsHTML .= '<td>'.$printTranslatedPaymentMethodName.'</td>';
            $paymentMethodsHTML .= '<td>'.$printEmail.$printPublicKey.$printPrivateKey.'</td>';
            $paymentMethodsHTML .= '<td>'.$printSandboxMode.'</td>';
            $paymentMethodsHTML .= '<td>'.$printCheckCertificate.'</td>';
            $paymentMethodsHTML .= '<td>'.$printSSLOnly.'</td>';
            $paymentMethodsHTML .= '<td>'.$printOnlinePayment.'</td>';
            $paymentMethodsHTML .= '<td>'.$printPaymentMethodEnabled.'</td>';
            $paymentMethodsHTML .= '<td style="text-align: center">'.$paymentMethodDetails['payment_method_order'].'</td>';
            $paymentMethodsHTML .= '<td align="right" style="white-space: nowrap">';
            $paymentMethodsHTML .= '<a href="'.admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-payment-method&amp;payment_method_id='.$paymentMethodId).'">'.$this->lang->getText('NRS_ADMIN_EDIT_TEXT').'</a> || ';
            $paymentMethodsHTML .= '<a href="'.admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-payment-method&amp;noheader=true&amp;delete_payment_method='.$paymentMethodId).'">'.$this->lang->getText('NRS_ADMIN_DELETE_TEXT').'</a>';
            $paymentMethodsHTML .= '</td>';
            $paymentMethodsHTML .= '</tr>';
        }

        return  $paymentMethodsHTML;
    }
}