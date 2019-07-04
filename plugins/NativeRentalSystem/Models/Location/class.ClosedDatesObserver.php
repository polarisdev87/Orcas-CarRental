<?php
/**
 * NRS Locations Observer (no setup for single location)
 * Abstract class cannot be inherited anymore. We use them when creating new instances
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Location;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Validation\StaticValidator;

class ClosedDatesObserver
{
    protected $conf 	                = NULL;
    protected $lang 		            = NULL;
    protected $settings		            = array();
    protected $debugMode 	            = 0;

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

    public function getClosedDates($paramLocationCode = "", $paramAddQuotes = FALSE)
    {
        $sanitizedLocationCode = sanitize_text_field($paramLocationCode);
        $validLocationCode = esc_sql($sanitizedLocationCode); // for sql queries only

        $sqlQuery = "SELECT closed_date
                     FROM {$this->conf->getPrefix()}closed_dates
                     WHERE location_code='{$validLocationCode}'";

        // Get dates
        $arrDates = $this->conf->getInternalWPDB()->get_col($sqlQuery);
        $closedDates = array();
        foreach ($arrDates AS $date)
        {
            if($date != "0000-00-00")
            {
                $closedDates[] .= $paramAddQuotes ? "'{$date}'" : "{$date}";
            }
        }
        $dateRange = implode(",", $closedDates);

        return $dateRange;
    }

    public function saveClosedDates($paramLocationCode, $paramSelectedDates)
    {
        $validLocationCode = esc_sql(sanitize_text_field($paramLocationCode)); // for sql queries only

        // Delete old dates in all sites for this location code
        $sqlQuery = "DELETE FROM {$this->conf->getPrefix()}closed_dates WHERE location_code='{$validLocationCode}'";
        $this->conf->getInternalWPDB()->query($sqlQuery);

        $arrSelectedDates = explode(',', $paramSelectedDates);
        $alreadyClosedArray = array();
        foreach($arrSelectedDates AS $key => $nonVerifiedDate)
        {
            // Security verification
            $validDate = StaticValidator::getValidISODate($nonVerifiedDate, 'Y-m-d');
            if(!in_array($validDate, $alreadyClosedArray) && $validDate != "0000-00-00")
            {
                $this->conf->getInternalWPDB()->query("
                INSERT INTO {$this->conf->getPrefix()}closed_dates (
                    closed_date, location_code, blog_id
                ) VALUES (
                    '{$validDate}', '{$validLocationCode}', '{$this->conf->getBlogId()}'
                )");
                $alreadyClosedArray[] = $validDate;
            }
        }
    }
}