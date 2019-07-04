<?php
/**
 * NRS Prepayments Observer (no setup for single item)
 * Abstract class cannot be inherited anymore. We use them when creating new instances
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Tax;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\iObserver;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Location\Location;
use NativeRentalSystem\Models\Validation\StaticValidator;

class TaxesObserver implements iObserver
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

    /**
     * @param int $paramPickupLocationId
     * @param int $paramReturnLocationId
     * @param int $paramPrice - used to pre-calculate the taxes from gross amount
     * @return mixed
     */
    public function getTaxesForPrice($paramPickupLocationId = 0, $paramReturnLocationId = 0, $paramPrice = 0)
    {
        $taxIds = $this->getAllIds($paramPickupLocationId, $paramReturnLocationId);

        if($this->debugMode)
        {
            echo "<br />Getting all taxes for price: ".floatval($paramPrice);
        }

        $arrTaxes = array();
        foreach($taxIds AS $taxId)
        {
            $objTax = new Tax($this->conf, $this->lang, $this->settings, $taxId);
            $arrTaxes[] = $objTax->getDetailsWithAmountForPrice($paramPrice);
        }

        return $arrTaxes;
    }

    /*******************************************************************************/
    /********************** METHODS FOR ADMIN ACCESS ONLY **************************/
    /*******************************************************************************/

    public function getAdminList()
    {
        $taxList = '';

        $taxIds = $this->getAllIds(-1, -1);

        foreach ($taxIds AS $taxId)
        {
            $objTax = new Tax($this->conf, $this->lang, $this->settings, $taxId);
            $taxDetails = $objTax->getDetails();

            if($taxDetails['location_id'] > 0)
            {
                $objLocation = new Location($this->conf, $this->lang, $this->settings, $taxDetails['location_id']);
                $printTranslatedLocationName = $objLocation->getPrintTranslatedLocationName();
            } else
            {
                $printTranslatedLocationName = $this->lang->getText('NRS_ADMIN_ALL_LOCATIONS_TEXT');
            }
            $printLocationType = $this->lang->getText($taxDetails['location_type'] == 1 ? 'NRS_PICKUP_TEXT' : 'NRS_RETURN_TEXT');

            $printTranslatedTaxName = $taxDetails['print_translated_tax_name'];
            if($this->lang->canTranslateSQL())
            {
                $printTranslatedTaxName .= '<br /><span class="not-translated" title="'.$this->lang->getText('NRS_ADMIN_WITHOUT_TRANSLATION_TEXT').'">('.$taxDetails['print_tax_name'].')</span>';
            }

            // HTML OUTPUT
            $taxList .= '<tr>';
            $taxList .= '<td>'.$printTranslatedTaxName.'</td>';
            $taxList .= '<td>'.$printTranslatedLocationName.'</td>';
            $taxList .= '<td>'.$printLocationType.'</td>';
            $taxList .= '<td>'.$taxDetails['tax_percentage'].' %</td>';
            $taxList .= '<td align="right"><a href="'.admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-tax&amp;tax_id='.$taxDetails['tax_id']).'">'.$this->lang->getText('NRS_ADMIN_EDIT_TEXT').'</a> || ';
            $taxList .= '<a href="'.admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-tax&amp;noheader=true&amp;delete_tax='.$taxDetails['tax_id']).'">'.$this->lang->getText('NRS_ADMIN_DELETE_TEXT').'</a></td>';
            $taxList .= '</tr>';
        }

        return $taxList;
    }
}