<?php
/**
 * NRS AutoLoader to load classes for NRS plugin
 * Final class cannot be inherited anymore. We use them when creating new instances
 * @note: Do not use static:: in this class, as it is maximum backwards compatible class for version check,
 *   and should work on Php 5.2, or even 5.0. All other classes can support Php 5.3+ or so.
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Load;
use NativeRentalSystem\Models\Configuration\CoreConfiguration;

class AutoLoad
{
    // Because loading of language text is not allowed in the abstract controller level, we use constants to simulate language text behavior, just the text is English
    const NRS_ERROR_UNABLE_TO_LOAD_CLASS_TEXT = 'Unable to load &#39;%s&#39; class/interface nor from plugin root folder, nor from it&#39;s tests folder with &#39;%s&#39; path provided.';
    private $coreConf = NULL;
    private $debugMode = 0;

    public function __construct(CoreConfiguration &$paramCoreConf)
    {
        $this->coreConf = $paramCoreConf;
    }

    /**
     * Load the model, view or controller from plugin folder (normal or test)
     * @param $paramClassOrInterface
     * @return bool
     * @throws \Exception
     */
    public function includeClassFile($paramClassOrInterface)
    {
        if (substr($paramClassOrInterface, 0, 19) !== 'NativeRentalSystem\\')
        {
            /* If the class does not lie under the "NativeRentalSystem" namespace,
             * then we can exit immediately.
             */
            return FALSE;
        }

        // Otherwise - process further
        $pluginsFolderPath = str_replace('\\', DIRECTORY_SEPARATOR, WP_PLUGIN_DIR).DIRECTORY_SEPARATOR;
        $relativeFolderAndFilePath = $this->getFilePathAndNameFromNamespaceAndClass($paramClassOrInterface);

        // DEBUG
        if($this->debugMode == 1)
        {
            echo '<br /><br />'.$paramClassOrInterface.' class/interface is called. It&#39;s relative path is '.$relativeFolderAndFilePath;
            echo '<br />Plugin folder path: '.$pluginsFolderPath.$relativeFolderAndFilePath;
            echo '<br />Tests folder path: '.$this->coreConf->getTestsPath().$relativeFolderAndFilePath;
        }

        /**
         * REGULAR AND TESTS PATHS
         * Note - order is important to properly support plugin's MVC classes overriding!
         * First, check under plugin directory, only then check test folders.
         * It is important that we look here first, so that we don't waste time searching for
         * test classes in the common case.
         */
        if(is_readable($pluginsFolderPath.$relativeFolderAndFilePath))
        {
            // Check for main folder in local plugin folder
            // It's a regular class / interface
            require_once ($pluginsFolderPath.$relativeFolderAndFilePath);
            return TRUE;
        } else if(is_readable($this->coreConf->getTestsPath().$relativeFolderAndFilePath))
        {
            // Check for main folder in local plugin folder
            // It's a test class / interface
            require_once ($this->coreConf->getTestsPath().$relativeFolderAndFilePath);
            return TRUE;
        } else
        {
            // File do not exist or is not readable
            $validClassOrInterface = sanitize_text_field($paramClassOrInterface);
            throw new \Exception(sprintf($this->getLangText('NRS_ERROR_UNABLE_TO_LOAD_CLASS_TEXT'), $validClassOrInterface, $relativeFolderAndFilePath));
        }
    }

    /**
     * This method is pretty much a copy of default Google's ReCaptcha autoloader, just path is dynamic
     * @param $class
     */
    public function includeReCaptcha($class)
    {
        if (substr($class, 0, 10) !== 'ReCaptcha\\')
        {
            /* If the class does not lie under the "ReCaptcha" namespace,
             * then we can exit immediately.
             */
            return;
        }

        /* All of the classes have names like "ReCaptcha\Foo", so we need
         * to replace the backslashes with frontslashes if we want the
         * name to map directly to a location in the filesystem.
         */
        $class = str_replace('\\', '/', $class);

        /* First, check under the current directory. It is important that
         * we look here first, so that we don't waste time searching for
         * test classes in the common case.
         */
        $path = $this->coreConf->getLibrariesPath().$class.'.php';
        if (is_readable($path))
        {
            require_once $path;
        }

        /* If we didn't find what we're looking for already, maybe it's
         * a test class?
         */
        $path = $this->coreConf->getLibrariesTestPath().$class.'.php';
        if (is_readable($path))
        {
            require_once $path;
        }
    }

    /**
     * Get Language text from constant with defined check
     * @param $paramName
     * @return string
     */
    private function getLangText($paramName)
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
     * Example:
     *   Org class name: NativeRentalSystem\Models\Settings\SettingsObserver
     *   Class name: SettingsObserver
     *   File name: NativeRentalSystem\Models\Database\class.SettingsObserver.php
     * @param $paramClassOrInterface - a namespace
     * @return string
     */
    private function getFilePathAndNameFromNamespaceAndClass($paramClassOrInterface)
    {
        $validClassOrInterface = sanitize_text_field($paramClassOrInterface);

        $className = ltrim($validClassOrInterface, '\\');
        $filePath  = "";
        $lastNamespacePosition = strripos($className, '\\');
        // If namespace is used
        if ($lastNamespacePosition !== FALSE)
        {
            // Then separate namespace and class name
            $namespace = substr($className, 0, $lastNamespacePosition);
            $className = substr($className, $lastNamespacePosition + 1);
            $filePath  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }

        // Check if this is an interface or a class and set specific prefix to it
        if(isset($className[0]) && $className[0] == "i")
        {
            // This is an interface (i.e. iNRSModel) - add "interface." prefix to the filename
            $fileName = 'interface.'.$className.'.php';
        } else
        {
            // This is a class (i.e. NRSModel) - add "class." prefix to the filename
            $fileName = 'class.'.$className.'.php';
        }

        // DEBUG
        if($this->debugMode == 2)
        {
            echo "<br /><br />Org class name: {$validClassOrInterface}";
            echo "<br />Class name: {$className}<br />";
            echo "File path and name: {$filePath}{$fileName}";
        }

        return $filePath.$fileName;
    }
}