<?php
/**
 * NRS Main controller
 * Final class cannot be inherited anymore. We use them when creating new instances
 * @description This file is the main entry point to the plugin that will handle all requests from WordPress
 * and add actions, filters, etc. as necessary. So we simply declare the class and add a constructor.
 * @note 1: This class must not depend on any static model
 * @note 2: All Controllers and Models should have full path in the class
 * @note 3: Fatal errors on this file cannot be translated
 * @note 4: Do not use static:: in this class, as it is maximum backwards compatible class for version check,
 *   and should work on Php 5.2, or even 5.0. All other classes can support Php 5.3+ or so.
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers;
use NativeRentalSystem\Models\Configuration\CoreConfiguration;
use NativeRentalSystem\Models\Load\AutoLoad;

abstract class AbstractController
{
    // Because loading of language text is not allowed in the abstract controller level, we use constants to simulate language text behavior, just the text is English
    const NRS_ERROR_CLONING_IS_FORBIDDEN_TEXT = 'Error in __clone() method: Cloning instances of the class in the Native Rental System is forbidden.';
    const NRS_ERROR_UNSERIALIZING_IS_FORBIDDEN_TEXT = 'Error in __wakeup() method: Unserializing instances of the class in the Native Rental System is forbidden.';
    const NRS_ERROR_PLEASE_UPGRADE_PHP_TEXT = 'Sorry, %s requires PHP %s or higher. Your current PHP version is %s. Please upgrade your server Php version.';
    const NRS_ERROR_PLEASE_UPGRADE_WORDPRESS_TEXT = 'Sorry, %s requires WordPress %s or higher. Your current WordPress version is %s. Please upgrade your WordPress setup.';
    const NRS_ERROR_EXTENSION_NOT_EXIST_PLUGIN_CHILD_THEME_TEXT = 'Sorry, but %s extension does not exist neither in %s plugin directory, nor in %s child theme folder, nor in it&#39;s parent %s theme&#39;s folder.';
    const NRS_ERROR_EXTENSION_NOT_EXIST_PLUGIN_THEME_TEXT = 'Sorry, but %s extension does not exist neither in %s plugin directory, nor in %s theme folder.';
    const NRS_ERROR_IN_METHOD_TEXT = 'Error in %s method: ';

    // Configuration object reference
    protected $coreConf   = NULL;
    protected $canProcess = FALSE;
    private static $dependenciesLoaded = FALSE;

    private $errors = array();

    public function __construct(CoreConfiguration &$paramCoreConf)
    {
        $this->coreConf = $paramCoreConf;

        //
        // 1. Check plug-in requirements - if not passed, then exit
        //
        if (!$this->checkRequirements())
        {
            $this->canProcess = FALSE;
            return;
        } else
        {
            $this->canProcess = TRUE;
        }

        //
        // 2. Load dependencies. Autoloader. This must be in constructor to know the file paths.
        // Note: Singleton pattern used.
        //
        if(self::$dependenciesLoaded === FALSE)
        {
            $this->loadDependencies();
            self::$dependenciesLoaded = TRUE;
        }
    }

    /**
     * Checks that the WordPress setup meets the plugin requirements
     * @return boolean
     */
    private function checkRequirements()
    {
        // Check Php version
        if(version_compare($this->coreConf->getCurrentPHPVersion(), $this->coreConf->getRequiredPHPVersion(), '>=') === FALSE)
        {
            // WordPress version does not meet plugin requirements
            add_action('admin_notices', array( &$this, 'displayPhpVersionRequirementNotice'));

            return FALSE;
        }

        // Check WordPress version
        if(version_compare($this->coreConf->getCurrentWPVersion(), $this->coreConf->getRequiredWPVersion(), '>=') === FALSE)
        {
            // WordPress version does not meet plugin requirements
            add_action('admin_notices', array( &$this, 'displayWPVersionRequirementNotice'));

            return FALSE;
        }

        return TRUE;
    }

    /**
     * Load only those classes of which instances are created
     */
    private function loadDependencies()
    {
        $objAutoload = new AutoLoad($this->coreConf);
        spl_autoload_register(array(&$objAutoload, 'includeClassFile'));
        spl_autoload_register(array(&$objAutoload, 'includeReCaptcha'));
    }

    protected function wpDebugEnabledDisplay()
    {
        $inDebug = defined('WP_DEBUG') && WP_DEBUG == TRUE && defined('WP_DEBUG_DISPLAY') && WP_DEBUG_DISPLAY == TRUE;

        return $inDebug;
    }

    /**
     * Get Language text from constant with defined check
     * @param $paramName
     * @return string
     */
    protected function getLangText($paramName)
    {
        if(defined('self::'.$paramName))
        {
            return constant('self::'.$paramName);
        } else
        {
            return "";
        }
    }

    /**
     * Throw error on object clone.
     *
     * Cloning instances of the class is forbidden.
     *
     * @since 1.0
     * @return void
     */
    public function __clone()
    {
        add_action('admin_notices', array( &$this, 'displayCloningIsForbiddenNotice'));
    }

    /**
     * Disable unserializing of the class
     *
     * Unserializing instances of the class is forbidden.
     *
     * @since 1.0
     * @return void
     */
    public function __wakeup()
    {
        add_action('admin_notices', array( &$this, 'displayUnserializingIsForbiddenNotice'));
    }

    /**
     * Display Php version requirement notice.
     *
     * @access static
     */
    public function displayPhpVersionRequirementNotice()
    {
        echo '<div id="message" class="error"><p><strong>';
        echo sprintf(
            $this->getLangText('NRS_ERROR_PLEASE_UPGRADE_PHP_TEXT'),
            $this->getLangText('NRS_ERROR_EXTENSION_NAME_TEXT'), $this->coreConf->getRequiredPHPVersion(), $this->coreConf->getCurrentPHPVersion()
        );
        echo '</strong></p></div>';
    }

    /**
     * Display WordPress version requirement notice.
     *
     * @access static
     */
    public function displayWPVersionRequirementNotice()
    {
        echo '<div id="message" class="error"><p><strong>';
        echo sprintf(
            $this->getLangText('NRS_ERROR_PLEASE_UPGRADE_WORDPRESS_TEXT'),
            $this->getLangText('NRS_ERROR_EXTENSION_NAME_TEXT'), $this->coreConf->getRequiredWPVersion(), $this->coreConf->getCurrentWPVersion()
        );
        echo '</strong></p></div>';
    }

    /**
     * Display WordPress version requirement notice.
     *
     * @access static
     */
    public function displayCloningIsForbiddenNotice()
    {
        echo '<div id="message" class="error"><p><strong>';
        echo $this->getLangText('NRS_ERROR_CLONING_IS_FORBIDDEN_TEXT');
        echo '</strong></p></div>';
    }

    /**
     * Display WordPress version requirement notice.
     *
     * @access static
     */
    public function displayUnserializingIsForbiddenNotice()
    {
        echo '<div id="message" class="error"><p><strong>';
        echo $this->getLangText('NRS_ERROR_UNSERIALIZING_IS_FORBIDDEN_TEXT');
        echo '</strong></p></div>';
    }

    /**
     * @param array $paramErrorMessages
     * @param array $paramDebugMessages
     * @throws \Exception
     */
    protected function throwExceptionOnFailure(array $paramErrorMessages, array $paramDebugMessages)
    {
        $errorMessagesToAdd = array();
        $debugMessagesToAdd = array();
        foreach($paramErrorMessages AS $paramErrorMessage)
        {
            $errorMessagesToAdd[] = sanitize_text_field($paramErrorMessage);
        }
        foreach($paramDebugMessages AS $paramDebugMessage)
        {
            // HTML is allowed here
            $debugMessagesToAdd[] = wp_kses_post($paramDebugMessage);
        }

        if(sizeof($errorMessagesToAdd) > 0)
        {
            $throwMessage = implode('<br />', $errorMessagesToAdd);
            if($this->wpDebugEnabledDisplay() && sizeof($debugMessagesToAdd) > 0)
            {
                $throwMessage .= '<br />'.implode('<br />', $debugMessagesToAdd);
            }

            throw new \Exception($throwMessage);
        }
    }

    protected function processError($paramName, $paramErrorMessage)
    {
        if($this->wpDebugEnabledDisplay())
        {
            $validName = esc_html($paramName);
            $validErrorMessage = esc_html($paramErrorMessage);
            // Load errors only in local or global debug mode
            $this->errors[] = sprintf($this->getLangText('NRS_ERROR_IN_METHOD_TEXT'), $validName).$validErrorMessage;

            // Doesn't always work (maybe due to fact, that 'admin_notices' has to be registered not later than X point in code)
            //add_action('admin_notices', array( &$this, 'displayErrors'));

            // Works
            $validErrorMessage = '<div id="message" class="error"><p>'.$validErrorMessage.'</p></div>';
            _doing_it_wrong($validName, $validErrorMessage, $this->coreConf->getVersion());
        }
    }

    public function displayErrors()
    {
        if(sizeof($this->errors) > 0)
        {
            // Print all errors in the stack
            echo '<div id="message" class="error"><p>';
            echo implode('</p><p>', $this->errors);
            echo '</p></div>';

            // Then, clean the errors stack
            $this->errors = array();
        }
    }
}