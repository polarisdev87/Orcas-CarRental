<?php
/**
 * NRS Prepayments Observer (no setup for single item)
 * Abstract class cannot be inherited anymore. We use them when creating new instances
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Search;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Validation\StaticValidator;

class TaxSearchManager
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

    public function getAllIds($paramPickupLocationId = -1, $paramReturnLocationId = -1)
    {
        $validPickupLocationId = StaticValidator::getValidInteger($paramPickupLocationId, -1); // -1 (all) is supported here
        $validReturnLocationId = StaticValidator::getValidInteger($paramReturnLocationId, -1); // -1 (all) is supported here
        $sqlAdd = '';
        if($validPickupLocationId >= 0 || $paramReturnLocationId >= 0)
        {
            $sqlAdd .= " AND (
                ((location_id='0' OR location_id='{$validPickupLocationId}') AND location_type='1') OR
                ((location_id='0' OR location_id='{$validReturnLocationId}') AND location_type='2') 
            )";
        }

        $locationIds = $this->conf->getInternalWPDB()->get_col("
            SELECT tax_id
            FROM {$this->conf->getPrefix()}taxes
            WHERE blog_id='{$this->conf->getBlogId()}'{$sqlAdd}
            ORDER BY tax_name ASC
        ");

        return $locationIds;
    }
}