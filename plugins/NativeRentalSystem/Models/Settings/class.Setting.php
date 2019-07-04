<?php
/**
 * NRS Setting class. It is on purpose don't have the $settings parameter
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Settings;
use NativeRentalSystem\Models\AbstractElement;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Validation\StaticValidator;

class Setting extends AbstractElement
{
    protected $conf 	    = NULL;
    protected $lang 		= NULL;
    protected $debugMode 	= 0;
    protected $key          = '';

    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, $paramKey)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;

        // Set the key
        $this->key = sanitize_key($paramKey);
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    public function save($paramValue)
    {
        $validKey = esc_sql(sanitize_text_field($this->key)); // for sql queries only
        $validValue = esc_sql(sanitize_text_field($paramValue)); // for sql queries only
        $this->conf->getInternalWPDB()->query("
            UPDATE {$this->conf->getPrefix()}settings
            SET conf_value='{$validValue}'
            WHERE conf_key='{$validKey}' AND blog_id='{$this->conf->getBlogId()}'
        ");
    }

    public function saveCheckbox()
    {
        $validKey = esc_sql(sanitize_text_field($this->key)); // for sql queries only
        $validValue = isset($_POST[$this->key]) ? 1 : 0;
        $this->conf->getInternalWPDB()->query("
            UPDATE {$this->conf->getPrefix()}settings
            SET conf_value='{$validValue}'
            WHERE conf_key='{$validKey}' AND blog_id='{$this->conf->getBlogId()}'
        ");
    }

    public function saveTime()
    {
        $validKey = esc_sql(sanitize_text_field($this->key)); // for sql queries only
        $validValue = isset($_POST[$this->key]) ? StaticValidator::getValidISOTime($_POST[$this->key], 'H:i:s') : '00:00:00';

        $sqlQuery = "
            UPDATE {$this->conf->getPrefix()}settings
            SET conf_value='{$validValue}'
            WHERE conf_key='{$validKey}' AND blog_id='{$this->conf->getBlogId()}'
        ";

        // DEBUG
        //echo nl2br($sqlQuery);

        $updated = $this->conf->getInternalWPDB()->query($sqlQuery);

        return $updated;
    }

    public function saveNumber($defaultValue = 0, $allowedValues = array())
    {
        $validKey = esc_sql(sanitize_text_field($this->key)); // for sql queries only
        $validValue = StaticValidator::getValidPositiveInteger(isset($_POST[$this->key]) ? $_POST[$this->key] : $defaultValue, $defaultValue);
        if(sizeof($allowedValues) > 0)
        {
            $validValue = in_array($validValue, $allowedValues) ? $validValue : $defaultValue;
        }

        $sqlQuery = "
            UPDATE {$this->conf->getPrefix()}settings
            SET conf_value='{$validValue}'
            WHERE conf_key='{$validKey}' AND blog_id='{$this->conf->getBlogId()}'
        ";

        // DEBUG
        //echo nl2br($sqlQuery);

        $updated = $this->conf->getInternalWPDB()->query($sqlQuery);

        return $updated;
    }

    public function saveKey()
    {
        $validKey = esc_sql(sanitize_text_field($this->key)); // for sql queries only
        $validValue = isset($_POST[$this->key]) ? esc_sql(sanitize_key($_POST[$this->key])) : '';

        $sqlQuery = "
            UPDATE {$this->conf->getPrefix()}settings
            SET conf_value='{$validValue}'
            WHERE conf_key='{$validKey}' AND blog_id='{$this->conf->getBlogId()}'
        ";

        // DEBUG
        //echo nl2br($sqlQuery);

        $updated = $this->conf->getInternalWPDB()->query($sqlQuery);

        return $updated;
    }

    public function saveText($paramTransformToUFT8Code = FALSE)
    {
        $validKey = esc_sql(sanitize_text_field($this->key)); // for sql queries only
        if($paramTransformToUFT8Code)
        {
            $validValue = isset($_POST[$this->key]) ? htmlentities(sanitize_text_field($_POST[$this->key]), ENT_COMPAT, 'utf-8') : '';
        } else
        {
            $validValue = isset($_POST[$this->key]) ? esc_sql(sanitize_text_field($_POST[$this->key])) : '';
        }

        $sqlQuery = "
            UPDATE {$this->conf->getPrefix()}settings
            SET conf_value='{$validValue}'
            WHERE conf_key='{$validKey}' AND blog_id='{$this->conf->getBlogId()}'
        ";

        // DEBUG
        //echo nl2br($sqlQuery);

        $updated = $this->conf->getInternalWPDB()->query($sqlQuery);

        return $updated;
    }


    public function saveEmail()
    {
        $validKey = esc_sql(sanitize_text_field($this->key)); // for sql queries only
        $validValue = isset($_POST[$this->key]) ? esc_sql(sanitize_email($_POST[$this->key])) : '';

        $sqlQuery = "
            UPDATE {$this->conf->getPrefix()}settings
            SET conf_value='{$validValue}'
            WHERE conf_key='{$validKey}' AND blog_id='{$this->conf->getBlogId()}'
        ";

        // DEBUG
        //echo nl2br($sqlQuery);

        $updated = $this->conf->getInternalWPDB()->query($sqlQuery);

        return $updated;
    }
}