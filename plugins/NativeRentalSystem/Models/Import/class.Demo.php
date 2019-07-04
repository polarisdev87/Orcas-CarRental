<?php
/**
 * Demo import manager

 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Import;
use NativeRentalSystem\Models\AbstractElement;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\File\StaticFile;
use NativeRentalSystem\Models\iPrimitive;
use NativeRentalSystem\Models\Language\Language;

class Demo extends AbstractElement implements iPrimitive
{
    protected $conf             = NULL;
    protected $lang             = NULL;
    protected $demo             = NULL;
    protected $debugMode        = 0;
    protected $demoId           = 0;

    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, $paramDemoId)
    {
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->conf = $paramConf;
        $this->lang = $paramLang;

        $this->demoId = intval($paramDemoId);
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    public function getId()
    {
        return $this->demoId;
    }

    private function getTables()
    {
        $arrTables = array(
            "benefits",
            "body_types",
            "bookings", // Only Truncate
            "booking_options", // Only Truncate
            "closed_dates", // Only Truncate
            "customers",
            "deposits",
            "discounts",
            "distances",
            "emails",
            "extras",
            "features",
            "fuel_types",
            "invoices",
            "items",
            "item_features",
            "item_locations",
            "locations",
            "logs",
            "manufacturers",
            "options",
            "payment_methods",
            "prepayments",
            "price_groups",
            "price_plans",
            "settings",
            "taxes",
            "transmission_types",
        );

        return $arrTables;
    }

    /**
     * Replace all content
     * @note - Replace mode helps us here to avoid conflicts with already existing regular WordPress posts
     * @return bool
     */
    public function replaceContent()
    {
        // Insert SQL
        $inserted = TRUE;
        // If importable demo file is provided and it's file is readable
        $demoSQLFileNameWithPath = $this->getDemoFileNameWithPath();

        // Get WordPress auto increment - used in the demo import file
        $sqlQuery = "SELECT ID FROM `{$this->conf->getBlogPrefix()}posts` WHERE 1 ORDER BY ID DESC LIMIT 1";
        $wpAIResult = $this->conf->getInternalWPDB()->get_var($sqlQuery);
        $wpAI = !is_null($wpAIResult) ? $wpAIResult : 0;

        // Get extension auto increment - used in the demo import file
        $extAI = $this->conf->getBlogId() > 1 ? $this->conf->getBlogId() * 100 : 0;
        if($demoSQLFileNameWithPath != '' && is_readable($demoSQLFileNameWithPath))
        {
            // Clean the values
            $arrReplaceSQL = array();
            $arrExtensionReplaceSQL = array();

            // Fill the values
            require ($demoSQLFileNameWithPath);

            // Replace data in WP tables
            foreach($arrReplaceSQL AS $sqlTable => $sqlData)
            {
                $sqlQuery = "
                    REPLACE INTO `{$this->conf->getBlogPrefix()}{$sqlTable}` {$sqlData}
                ";
                $ok = $this->conf->getInternalWPDB()->query($sqlQuery);
                if($ok === FALSE)
                {
                    if($this->debugMode)
                    {
                        $debugMessage = "FAILED TO REPLACE IN WP TABLE: ".nl2br(esc_html($sqlQuery));
                        $this->debugMessages[] = $debugMessage;
                        //echo $debugMessage; // This class is used with redirect, do not output here
                    }
                    $inserted = FALSE;
                }
            }

            // Parse blog id and plugin version BB codes and replace data in NRS tables
            foreach($arrExtensionReplaceSQL AS $sqlTable => $sqlData)
            {
                $pluginVersion = number_format(floatval($this->conf->getVersion()), 1,'.','');
                $sqlData = str_replace(array('[BLOG_ID]', '[PLUGIN_VERSION]'), array($this->conf->getBlogId(), $pluginVersion), $sqlData);

                $sqlQuery = "
                    REPLACE INTO `{$this->conf->getPrefix()}{$sqlTable}` {$sqlData}
                ";
                $ok = $this->conf->getInternalWPDB()->query($sqlQuery);
                if($ok === FALSE)
                {
                    if($this->debugMode)
                    {
                        $debugMessage = "FAILED TO REPLACE IN NRS EXTENSION TABLE: ".nl2br(esc_html($sqlQuery));
                        $this->debugMessages[] = $debugMessage;
                        //echo $debugMessage; // This class is used with redirect, do not ouput here
                    }
                    $inserted = FALSE;
                }
            }
        }

        if($inserted === FALSE)
        {
            $this->errorMessages[] = $this->lang->getText('NRS_DEMO_INSERT_ERROR_TEXT');
        } else
        {
            $this->okayMessages[] = $this->lang->getText('NRS_DEMO_INSERTED_TEXT');
        }

        return $inserted;
    }

    /**
     * @return bool
     */
    public function deleteContent()
    {
        // Clear all tables
        $deleted = TRUE;
        foreach($this->getTables() AS $table)
        {
            $ok = $this->conf->getInternalWPDB()->query(
                "DELETE FROM {$this->conf->getPrefix()}".$table." WHERE blog_id='{$this->conf->getBlogId()}'"
            );
            if($ok === FALSE)
            {
                $deleted = FALSE;
            }
        }

        // Delete all NRS page posts
        $pagePosts = get_posts(array('posts_per_page' => -1, 'post_type' => $this->conf->getExtensionPrefix().'page'));
        foreach ($pagePosts AS $pagePost)
        {
            $ok = wp_delete_post( $pagePost->ID, TRUE);
            if($ok === FALSE)
            {
                $deleted = FALSE;
            }
        }

        // Delete all NRS item posts
        $itemPosts = get_posts(array('posts_per_page' => -1, 'post_type' => $this->conf->getExtensionPrefix().'item'));
        foreach ($itemPosts AS $itemPost)
        {
            $ok = wp_delete_post($itemPost->ID, TRUE);
            if($ok === FALSE)
            {
                $deleted = FALSE;
            }
        }

        // Delete all NRS location posts
        $locationPosts = get_posts(array('posts_per_page' => -1, 'post_type' => $this->conf->getExtensionPrefix().'location'));
        foreach ($locationPosts AS $locationPost)
        {
            $ok = wp_delete_post($locationPost->ID, TRUE);
            if($ok === FALSE)
            {
                $deleted = FALSE;
            }
        }

        return $deleted;
    }

    /**
     * @return string
     */
    private function getDemoFileNameWithPath()
    {
        $retDemoFileNameWithPath = '';

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

        foreach ($phpFiles AS $phpFile)
        {
            $break = FALSE;
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
                $enabledDemo = intval($phpDemoData['Enabled']);

                if($validDemoId == $this->demoId)
                {
                    $break = TRUE;
                    if($enabledDemo)
                    {
                        $validFilePath = sanitize_text_field($phpFile['file_path']);
                        $validFileName = sanitize_file_name($phpFile['file_name']);
                        $retDemoFileNameWithPath = $validFilePath.$validFileName;
                    }
                }
            }

            // DEBUG
            if($this->debugMode == 2)
            {
                $debugMessage = "<br /><br />\$phpDemoData: " . nl2br(print_r($phpDemoData, TRUE));
                $debugMessage .= "<br /><br />File: {$phpFile['file_name']}";
                $debugMessage .= "<br />\$firstPhpDemoPos: {$firstPhpDemoPos} === 0";
                $debugMessage .= "<br />\$lastPhpPos: {$lastPhpPos} === \$requiredPhpPos: {$requiredPhpPos}";
                $this->debugMessages[] = $debugMessage;
                // echo $debugMessage; // This class is used with redirect, do not ouput here
            }

            if($break)
            {
                break;
            }
        }

        return $retDemoFileNameWithPath;
    }
}