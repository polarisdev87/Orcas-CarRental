<?php
/**
 * Demo import manager

 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Import;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\File\StaticFile;
use NativeRentalSystem\Models\iRootObserver;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;

class DemosObserver implements iRootObserver
{
    protected $conf             = NULL;
    protected $lang             = NULL;
    protected $demo             = NULL;
    protected $importableDemos  = array(); // This value may be overridden or extended by theme
    protected $debugMode        = 0;

    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->conf = $paramConf;
        $this->lang = $paramLang;
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    /**
     * Get importable demos in this plugin
     * @return array
     */
    private function getAllDemos()
    {
        $extensionDemosPath = $this->conf->getExtensionSQLsPath('', FALSE);
        $phpFiles = array();
        if(is_dir($extensionDemosPath))
        {
            // Get PHP folder file list
            $tmpPhpFiles = StaticFile::getFolderFileList($extensionDemosPath, "php");
            $tmpFiles = array();
            foreach ($tmpPhpFiles AS $tmpPhpFile)
            {
                if(!in_array($tmpPhpFile, $tmpFiles))
                {
                    $tmpFiles[] = $tmpPhpFile;
                    $phpFiles[] = array(
                        "file_path" => $extensionDemosPath,
                        "file_name" => $tmpPhpFile,
                    );
                }
            }
        }

        $retDemos = array();
        foreach ($phpFiles AS $phpFile)
        {
            // Case-insensitive check - Find the position of the last occurrence of a case-insensitive substring in a string
            $firstPhpDemoPos = stripos($phpFile['file_name'], "demo.");
            $lastPhpPos = strripos($phpFile['file_name'], ".php");
            $requiredPhpPos = strlen($phpFile['file_name']) - strlen(".php");
            $phpDemoData = array();
            if($firstPhpDemoPos === 0 && $lastPhpPos === $requiredPhpPos)
            {
                $phpDemoData = get_file_data($phpFile['file_path'].$phpFile['file_name'], array('DemoUID' => 'Demo UID', 'DemoName' => 'Demo Name', 'Enabled' => 'Enabled'));

                // Format data
                $validDemoId = intval($phpDemoData['DemoUID']);
                $validDemoName = sanitize_text_field($phpDemoData['DemoName']);
                $enabledDemo = intval($phpDemoData['Enabled']);
                $validFilePath = sanitize_text_field($phpFile['file_path']);
                $validFileName = sanitize_file_name($phpFile['file_name']);
                $validFileNameWithPath = $validFilePath . $validFileName;

                $retDemos[] = array(
                    "demo_id" => $validDemoId,
                    "demo_name" => $validDemoName,
                    "enabled" => $enabledDemo,
                    "file_path" => $validFilePath,
                    "file_name" => $validFileName,
                    "file_name_with_path" => $validFileNameWithPath,
                );
            }

            // DEBUG
            if($this->debugMode == 2)
            {
                echo "<br /><br />\$phpDemoData: " . nl2br(print_r($phpDemoData, TRUE));
                echo "<br /><br />File: {$phpFile['file_name']}";
                echo "<br />\$firstPhpDemoPos: {$firstPhpDemoPos} === 0";
                echo "<br />\$lastPhpPos: {$lastPhpPos} === \$requiredPhpPos: {$requiredPhpPos}";
            }
        }

        // DEBUG
        if($this->debugMode == 1)
        {
            echo "<br />Php demo files: ".nl2br(print_r($phpFiles, TRUE));
            echo "<br />Demos: ".nl2br(print_r($retDemos, TRUE));
        }

        return $retDemos;
    }

    public function getDemosDropDownOptions($paramSelectedDemoId = 0, $paramDefaultValue = 0, $paramDefaultLabel = "")
    {
        $validDefaultValue = StaticValidator::getValidPositiveInteger($paramDefaultValue, 0);
        $sanitizedDefaultLabel = sanitize_text_field($paramDefaultLabel);
        $defaultSelected = $paramSelectedDemoId == $validDefaultValue ? ' selected="selected"' : '';

        $demosHTML = '';
        $demosHTML .= '<option value="'.$validDefaultValue.'"'.$defaultSelected.'>'.$sanitizedDefaultLabel.'</option>';
        $allDemos = $this->getAllDemos();
        foreach ($allDemos AS $demo)
        {
            $selected = ($demo['demo_id'] == $paramSelectedDemoId ? ' selected="selected"' : '');
            if($demo['enabled'] == 1)
            {
                $demosHTML .= '<option value="'.$demo['demo_id'].'"'.$selected.'>'.$demo['demo_name'].'</option>';
            }
        }

        return $demosHTML;
    }
}