<?php
/**
 * NRS Prepayments Observer (no setup for single item)
 * Abstract class cannot be inherited anymore. We use them when creating new instances
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Prepayment;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;

class PrepaymentsObserver
{
    protected $conf 	                = NULL;
    protected $lang 		            = NULL;
    protected $settings		            = array();
    protected $debugMode 	            = 0;
	protected $prepaymentsEnabled  		= FALSE;
	// Price calculation type: 1 - daily, 2 - hourly, 3 - mixed (daily+hourly)
	protected $priceCalculationType 	= 1;

    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;
        // Set saved settings
        $this->settings = $paramSettings;

        $this->priceCalculationType = StaticValidator::getValidSetting($paramSettings, 'conf_price_calculation_type', 'positive_integer', 1, array(1, 2, 3));
        if(isset($paramSettings['conf_prepayment_enabled']))
        {
            // Set prepayment status
            $this->prepaymentsEnabled = $paramSettings['conf_prepayment_enabled'] == 1 ? TRUE : FALSE;
        }
	}

	public function arePrepaymentsEnabled()
	{
		return $this->prepaymentsEnabled;
	}


    /*******************************************************************************/
    /********************** METHODS FOR ADMIN ACCESS ONLY **************************/
    /*******************************************************************************/

    public function getAdminList()
    {
        $taxList = '';

        $sqlQuery = "
          SELECT prepayment_id, period_from, period_till,
          item_prices_included, item_deposits_included, extra_prices_included, extra_deposits_included,
          pickup_fees_included, distance_fees_included, return_fees_included,
          prepayment_percentage
          FROM {$this->conf->getPrefix()}prepayments
          WHERE blog_id='{$this->conf->getBlogId()}'
          GROUP BY period_from, period_till
          ORDER BY period_from ASC, period_till ASC
        ";
        $rows = $this->conf->getInternalWPDB()->get_results($sqlQuery, ARRAY_A);

        $i = 0;
        foreach($rows AS $row)
        {
            $i++;
            $printDurationFrom = $this->lang->getPrintFloorDurationByPeriod($this->priceCalculationType, $row['period_from']);
            $printDurationTill = $this->lang->getPrintFloorDurationByPeriod($this->priceCalculationType, $row['period_till']);

            $arrIncluded = array();
            if($row['item_prices_included'] == 1)
            {
                $arrIncluded[] = $this->lang->getText('NRS_ADMIN_ITEM_PRICES_TEXT');
            }
            if($row['item_deposits_included'] == 1)
            {
                $arrIncluded[] = $this->lang->getText('NRS_ADMIN_ITEM_DEPOSITS_TEXT');
            }
            if($row['extra_prices_included'] == 1)
            {
                $arrIncluded[] = $this->lang->getText('NRS_ADMIN_EXTRA_PRICES_TEXT');
            }
            if($row['extra_deposits_included'] == 1)
            {
                $arrIncluded[] = $this->lang->getText('NRS_ADMIN_EXTRA_DEPOSITS_TEXT');
            }
            if($row['pickup_fees_included'] == 1)
            {
                $arrIncluded[] = $this->lang->getText('NRS_ADMIN_PICKUP_FEES_TEXT');
            }
            if($row['distance_fees_included'] == 1)
            {
                $arrIncluded[] = $this->lang->getText('NRS_ADMIN_DISTANCE_FEES_TEXT');
            }
            if($row['return_fees_included'] == 1)
            {
                $arrIncluded[] = $this->lang->getText('NRS_ADMIN_RETURN_FEES_TEXT');
            }

            $printIncluded = implode(",<br />", $arrIncluded);

            // HTML OUTPUT
            $taxList .= '<tr>';
            $taxList .= '<td>'.$printIncluded.'</td>';
            $taxList .= '<td><strong>'.$printDurationFrom.'</strong></td>';
            $taxList .= '<td><strong>'.$printDurationTill.'</strong></td>';
            $taxList .= '<td>'.$row['prepayment_percentage'].' %</td>';
            // Default prepayment applied for all items
            $taxList .= '<td align="right"><a href="'.admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-prepayment&amp;prepayment_id='.$row['prepayment_id']).'">'.$this->lang->getText('NRS_ADMIN_EDIT_TEXT').'</a> || ';
            $taxList .= '<a href="'.admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-prepayment&amp;noheader=true&amp;delete_prepayment='.$row['prepayment_id']).'">'.$this->lang->getText('NRS_ADMIN_DELETE_TEXT').'</a></td>';

            $taxList .= '</tr>';
        }

        return  $taxList;
    }
}