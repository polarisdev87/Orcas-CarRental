<?php
/**
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Admin\Update;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Views\PageView;

abstract class AbstractController
{
    protected $conf         = NULL;
    protected $lang 	    = NULL;
    protected $view 	    = NULL;
    protected $dbSettings	= NULL;

    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;

        // Message handler
        $printDebugMessage = "";
        $printErrorMessage = "";
        $printOkayMessage = "";
        if(isset($_SESSION['admin_debug_message']))
        {
            $unfilteredDebugMessages = explode("<br />", $_SESSION['admin_debug_message']);
            $arrDebugMessages = array();
            foreach($unfilteredDebugMessages AS $unfilteredDebugMessage)
            {
                // HTML is allowed here
                $arrDebugMessages[] = wp_kses_post($unfilteredDebugMessage);
            }
            $printDebugMessage = "<br />".implode("<br />", $arrDebugMessages);
            unset($_SESSION['admin_debug_message']);
        }
        if(isset($_SESSION['admin_error_message']))
        {
            $unfilteredErrorMessages = explode("<br />", $_SESSION['admin_error_message']);
            $arrErrorMessages = array();
            foreach($unfilteredErrorMessages AS $unfilteredErrorMessage)
            {
                $arrErrorMessages[] = esc_html(sanitize_text_field($unfilteredErrorMessage));
            }
            $printErrorMessage = implode("<br />", $arrErrorMessages);
            $printErrorMessage .= $this->wpDebugEnabledDisplay() ? $printDebugMessage : '';
            unset($_SESSION['admin_error_message']);
        } else if(isset($_SESSION['admin_okay_message']))
        {
            $unfilteredOkayMessages = explode("<br />", $_SESSION['admin_okay_message']);
            $arrOkayMessages = array();
            foreach($unfilteredOkayMessages AS $unfilteredOkayMessage)
            {
                $arrOkayMessages[] = esc_html(sanitize_text_field($unfilteredOkayMessage));
            }
            $printOkayMessage = implode("<br />", $arrOkayMessages);
            $printOkayMessage .= $this->wpDebugEnabledDisplay() ? $printDebugMessage : '';
            unset($_SESSION['admin_okay_message']);
        }

        // Initialize the page view and set it's conf and lang objects
        $this->view = new PageView();
        $this->view->objConf = $this->conf;
        $this->view->objLang = $this->lang;
        $this->view->objSettings = $this->dbSettings;
        $this->view->errorMessage = $printErrorMessage;
        $this->view->okayMessage = $printOkayMessage;
        $this->view->urlPrefix = $this->conf->getURLPrefix();
        $this->view->variablePrefix = $this->conf->getVariablePrefix();
    }

    protected function wpDebugEnabledDisplay()
    {
        $inDebug = defined('WP_DEBUG') && WP_DEBUG == TRUE && defined('WP_DEBUG_DISPLAY') && WP_DEBUG_DISPLAY == TRUE;

        return $inDebug;
    }

    protected function processDebugMessages(array $paramMessages)
    {
        $messagesToAdd = array();
        foreach($paramMessages AS $paramMessage)
        {
            // HTML is allowed here
            $messagesToAdd[] = wp_kses_post($paramMessage);
        }

        if($this->wpDebugEnabledDisplay() && sizeof($messagesToAdd) > 0)
        {
            if(isset($_SESSION['admin_debug_message']))
            {
                $_SESSION['admin_debug_message'] .= '<br />'.implode('<br />', $messagesToAdd);
            } else
            {
                $_SESSION['admin_debug_message'] = implode('<br />', $messagesToAdd);
            }
        }
    }

    protected function processOkayMessages(array $paramMessages)
    {
        $messagesToAdd = array();
        foreach($paramMessages AS $paramMessage)
        {
            $messagesToAdd[] = sanitize_text_field($paramMessage);
        }

        if(sizeof($messagesToAdd) > 0)
        {
            if(isset($_SESSION['admin_okay_message']))
            {
                $_SESSION['admin_okay_message'] .= '<br />'.implode('<br />', $messagesToAdd);
            } else
            {
                $_SESSION['admin_okay_message'] = implode('<br />', $messagesToAdd);
            }
        }
    }

    protected function processErrorMessages(array $paramMessages)
    {
        $messagesToAdd = array();
        foreach($paramMessages AS $paramMessage)
        {
            $messagesToAdd[] = sanitize_text_field($paramMessage);
        }

        if(sizeof($messagesToAdd) > 0)
        {
            if(isset($_SESSION['admin_error_message']))
            {
                $_SESSION['admin_error_message'] .= '<br />'.implode('<br />', $messagesToAdd);
            } else
            {
                $_SESSION['admin_error_message'] = implode('<br />', $messagesToAdd);
            }
        }
    }

    protected function getTabParams($paramArrTabsToCheck = array(), $paramSelectedTabByDefault = '')
    {
        $retTabs = array();
        $validSelectedTabByDefault = sanitize_key($paramSelectedTabByDefault);

        $oneTabAlreadyChecked = FALSE;
        if(is_array($paramArrTabsToCheck))
        {
            foreach($paramArrTabsToCheck AS $paramTab)
            {
                // Allow to check only one tab
                if($oneTabAlreadyChecked === FALSE && isset($_GET['tab']) && $_GET['tab'] == $paramTab)
                {
                    $retTabs[sanitize_key($paramTab)] = TRUE;
                    $oneTabAlreadyChecked = TRUE;
                } else
                {
                    $retTabs[sanitize_key($paramTab)] = FALSE;
                }
            }

            // If no tabs are marked as checked
            if($oneTabAlreadyChecked === FALSE && isset($retTabs[$validSelectedTabByDefault]))
            {
                $retTabs[$validSelectedTabByDefault] = TRUE;
            }
        }
        //echo nl2br(print_r($_REQUEST, TRUE));

        return $retTabs;
    }

    protected function getTemplate($paramTemplateFolder, $paramTemplateName, $paramLayout = '')
    {
        $validTemplateFolder = '';
        $validTemplateName = '';
        if(!is_array($paramTemplateFolder) && $paramTemplateFolder != '')
        {
            $validTemplateFolder = preg_replace('[^-_0-9a-zA-Z]', '', $paramTemplateFolder).DIRECTORY_SEPARATOR; // No sanitization, uppercase needed
        }
        if(!is_array($paramTemplateName) && $paramTemplateName != '')
        {
            $validTemplateName = preg_replace('[^-_0-9a-zA-Z]', '', $paramTemplateName); // No sanitization, uppercase needed
        }
        $validLayout = '';
        if(in_array(ucfirst($paramLayout), array('', 'Form', 'Widget', 'Slider', 'List', 'Grid', 'Table', 'Calendar', 'Tabs')))
        {
            $validLayout = ucfirst(sanitize_key($paramLayout));
        }
        $templateFile = 'template.'.$validTemplateName.$validLayout.'.php';
        $retTemplate = $this->view->render($this->conf->getExtensionAdminTemplatesPath($validTemplateFolder.$templateFile));

        return $retTemplate;
    }
}