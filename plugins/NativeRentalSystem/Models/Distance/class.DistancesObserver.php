<?php
/**
 * NRS Locations Observer (no setup for single location)
 * Abstract class cannot be inherited anymore. We use them when creating new instances
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Distance;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\iObserver;
use NativeRentalSystem\Models\Location\Location;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Pricing\DistanceFeeManager;
use NativeRentalSystem\Models\Tax\TaxManager;
use NativeRentalSystem\Models\Validation\StaticValidator;

class DistancesObserver implements iObserver
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
        // Set saved settings
        $this->settings = $paramSettings;
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    public function getAllIds()
    {
        $distanceIds = $this->conf->getInternalWPDB()->get_col("
            SELECT distance_id
            FROM {$this->conf->getPrefix()}distances d
            JOIN {$this->conf->getPrefix()}locations ploc ON ploc.location_id=d.pickup_location_id
            JOIN {$this->conf->getPrefix()}locations rloc ON rloc.location_id=d.return_location_id
            WHERE d.blog_id='{$this->conf->getBlogId()}'
            ORDER BY ploc.location_order ASC, rloc.location_order ASC
        ");

        return $distanceIds;
    }

    public function getDistanceIdByTwoLocations($paramPickupLocationId, $paramReturnLocationId)
    {
        $retDistanceId = 0;

        $validPickupLocationId = StaticValidator::getValidPositiveInteger($paramPickupLocationId, 0);
        $validReturnLocationId = StaticValidator::getValidPositiveInteger($paramReturnLocationId, 0);

        $sql = "
            SELECT distance_id
            FROM {$this->conf->getPrefix()}distances
            WHERE pickup_location_id='{$validPickupLocationId}' AND return_location_id='{$validReturnLocationId}'
        ";

        $distanceId = $this->conf->getInternalWPDB()->get_var($sql);

        if(!is_null($distanceId))
        {
            $retDistanceId = StaticValidator::getValidPositiveInteger($distanceId, 0);
        }

        return $retDistanceId;
    }

    /**
     * @return string
     */
    public function getAdminList()
    {
        $distancesHTML = '';
        $distanceIds = $this->getAllIds();

        $i = 0;
        foreach ($distanceIds AS $distanceId)
        {
            $i++;
            $objDistance = new Distance($this->conf, $this->lang, $this->settings, $distanceId);
            $pickupLocationId = $objDistance->getPickupLocationId();
            $returnLocationId = $objDistance->getReturnLocationId();
            $objTaxManager = new TaxManager($this->conf, $this->lang, $this->settings);
            $taxPercentage = $objTaxManager->getTaxPercentage($pickupLocationId, $returnLocationId);
            $objPickupLocation = new Location($this->conf, $this->lang, $this->settings, $pickupLocationId);
            $objReturnLocation = new Location($this->conf, $this->lang, $this->settings, $returnLocationId);
            $objDistanceFeeManager = new DistanceFeeManager($this->conf, $this->lang, $this->settings, $pickupLocationId, $returnLocationId, $taxPercentage);

            $pickupDetails = $objPickupLocation->getDetails();
            $returnDetails = $objReturnLocation->getDetails();
            $distanceDetails = $objDistance->getDetails();
            $distanceFees = $objDistanceFeeManager->getUnitDetails();

            $distancesHTML .= '<tr>';
            $distancesHTML .= '<td>'.$i.'</td>';
            $distancesHTML .= '<td>'.$pickupDetails['print_translated_location_name'].'</td>';
            $distancesHTML .= '<td>'.$returnDetails['print_translated_location_name'].'</td>';
            $distancesHTML .= '<td style="white-space: nowrap">'.$distanceDetails['print_distance'].'</td>';
            $distancesHTML .= '<td>'.$distanceFees['unit_print']['distance_fee'].'</td>';
            $distancesHTML .= '<td>'.$distanceFees['unit_print']['distance_fee_with_tax'].'</td>';
            $distancesHTML .= '<td align="right" style="white-space: nowrap">';
            if(current_user_can('manage_'.$this->conf->getExtensionPrefix().'all_locations'))
            {
                $distancesHTML .= '<a href="'.admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-distance&amp;distance_id='.$distanceId).'">'.$this->lang->getText('NRS_ADMIN_EDIT_TEXT').'</a> || ';
                $distancesHTML .= '<a href="javascript:;" onclick="javascript:delete'.$this->conf->getExtensionFolder().'Distance(\''.$distanceId.'\')">'.$this->lang->getText('NRS_ADMIN_DELETE_TEXT').'</a>';
            } else
            {
                $distancesHTML .= '--';
            }
            $distancesHTML .= '</td>';
            $distancesHTML .= '</tr>';
        }

        return  $distancesHTML;
    }
}