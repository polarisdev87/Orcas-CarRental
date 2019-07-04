<?php
/**
 * Location Manager

 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Distance;
use NativeRentalSystem\Models\AbstractElement;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\iElement;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;

class Distance extends AbstractElement implements iElement
{
    protected $conf 	                = NULL;
    protected $lang 		            = NULL;
    protected $debugMode 	            = 0;

    protected $distanceId               = 0;
    protected $distanceMeasurementUnit  = "";

    /**
     * Distance constructor.
     * @param ExtensionConfiguration &$paramConf
     * @param Language &$paramLang
     * @param array $paramSettings
     * @param int $paramDistanceId
     */
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings, $paramDistanceId)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;

        // Set distance measurement unit
        $this->distanceMeasurementUnit = StaticValidator::getValidSetting($paramSettings, 'conf_distance_measurement_unit', "textval", "");

        // Set distance id
        $this->distanceId = StaticValidator::getValidPositiveInteger($paramDistanceId);
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    public function getId()
    {
        return $this->distanceId;
    }

    /**
     * Get distance data from MySQL database
     * @note - MUST BE PRIVATE. FOR INTERNAL USE ONLY
     * @param int $paramDistanceId - primary it's this class unique id
     * @return mixed
     */
    private function getDataFromDatabaseById($paramDistanceId)
    {
        // For all items reservation
        $validDistanceId = StaticValidator::getValidPositiveInteger($paramDistanceId);
        $sqlQuery = "
			SELECT
				distance_id, pickup_location_id, return_location_id, show_distance, distance, distance_fee
			FROM {$this->conf->getPrefix()}distances
			WHERE distance_id='{$validDistanceId}'
		";
        $distanceData = $this->conf->getInternalWPDB()->get_row($sqlQuery, ARRAY_A);

        // Debug
        //echo nl2br($sqlQuery);

        return $distanceData;
    }

    public function getPickupLocationId()
    {
        $locationId = 0;
        $distanceData = $this->getDataFromDatabaseById($this->distanceId);
        if(!is_null($distanceData))
        {
            $locationId = $distanceData['pickup_location_id'];
        }

        return $locationId;
    }

    public function getReturnLocationId()
    {
        $locationId = 0;
        $distanceData = $this->getDataFromDatabaseById($this->distanceId);
        if(!is_null($distanceData))
        {
            $locationId = $distanceData['return_location_id'];
        }

        return $locationId;
    }

    public function getDetails($paramIncludeUnclassified = FALSE)
    {
        $ret = $this->getDataFromDatabaseById($this->distanceId);
        if(!is_null($ret))
        {
            // Prices output stack
            $ret['print_distance'] = $ret['show_distance'] ? $ret['distance'].' '.$this->distanceMeasurementUnit : '';
        }

        return $ret;
    }

    public function save()
    {
        $ok = TRUE;
        $validDistanceId = StaticValidator::getValidPositiveInteger($this->distanceId, 0);
        $validPickupLocationId = isset($_POST['pickup_location_id']) ? StaticValidator::getValidPositiveInteger($_POST['pickup_location_id'], 0) : 0;
        $validReturnLocationId = isset($_POST['pickup_location_id']) ? StaticValidator::getValidPositiveInteger($_POST['return_location_id'], 0) : 0;
        $validShowDistance = isset($_POST['show_distance']) ? 1 : 0;
        $validDistance = isset($_POST['distance']) ? floatval($_POST['distance']) : 0.00;
        $validDistanceFee = isset($_POST['distance_fee']) ? floatval($_POST['distance_fee']) : 0.00;

        $distanceExistsQuery = "
            SELECT distance_id
            FROM {$this->conf->getPrefix()}distances
            WHERE pickup_location_id='{$validPickupLocationId}' AND return_location_id='{$validReturnLocationId}'
            AND distance_id!='{$validDistanceId}' AND blog_id='{$this->conf->getBlogId()}'
        ";
        $distanceExists = $this->conf->getInternalWPDB()->get_row($distanceExistsQuery, ARRAY_A);

        if($validPickupLocationId <= 0)
        {
            $ok = FALSE;
            $this->errorMessages[] = $this->lang->getText('NRS_DISTANCE_PICKUP_NOT_SELECTED_ERROR_TEXT');
        }
        if($validReturnLocationId <= 0)
        {
            $ok = FALSE;
            $this->errorMessages[] = $this->lang->getText('NRS_DISTANCE_RETURN_NOT_SELECTED_ERROR_TEXT');
        }
        if($validPickupLocationId == $validReturnLocationId)
        {
            $ok = FALSE;
            $this->errorMessages[] = $this->lang->getText('NRS_DISTANCE_SAME_PICKUP_AND_RETURN_ERROR_TEXT');
        }
        if(!is_null($distanceExists))
        {
            $ok = FALSE;
            $this->errorMessages[] = $this->lang->getText('NRS_DISTANCE_EXISTS_ERROR_TEXT');
        }

        if($validDistanceId > 0 && $ok)
        {
            $updateSQL = "
                UPDATE {$this->conf->getPrefix()}distances SET
                pickup_location_id='{$validPickupLocationId}',
                return_location_id='{$validReturnLocationId}',
                show_distance='{$validShowDistance}',
                distance='{$validDistance}',
                distance_fee='{$validDistanceFee}'
                WHERE distance_id='{$validDistanceId}' AND blog_id='{$this->conf->getBlogId()}'
            ";
            $saved = $this->conf->getInternalWPDB()->query($updateSQL);

            if($saved === FALSE)
            {
                $this->errorMessages[] = $this->lang->getText('NRS_DISTANCE_UPDATE_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('NRS_DISTANCE_UPDATED_TEXT');
            }
        } else if($ok)
        {
            $insertSQL = "
                INSERT INTO {$this->conf->getPrefix()}distances
                (
                    distance_id,
                    pickup_location_id,
                    return_location_id,
                    show_distance,
                    distance,
                    distance_fee,
                    blog_id
                ) VALUES
                (
                    '{$validDistanceId}',
                    '{$validPickupLocationId}',
                    '{$validReturnLocationId}',
                    '{$validShowDistance}',
                    '{$validDistance}',
                    '{$validDistanceFee}',
                    '{$this->conf->getBlogId()}'
                )
            ";
            $saved = $this->conf->getInternalWPDB()->query($insertSQL);
            if($saved)
            {
                // Update class discount id with newly inserted discount it for future work
                $this->distanceId = $this->conf->getInternalWPDB()->insert_id;
            }

            if($saved === FALSE || $saved === 0)
            {
                $this->errorMessages[] = $this->lang->getText('NRS_DISTANCE_INSERT_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('NRS_DISTANCE_INSERTED_TEXT');
            }
        }
    }

    /**
     * Not used for this element
     */
    public function registerForTranslation()
    {
        // not used
    }

    public function delete()
    {
        $validDistanceId = StaticValidator::getValidPositiveInteger($this->distanceId);
        $deleted = $this->conf->getInternalWPDB()->query("
            DELETE FROM {$this->conf->getPrefix()}distances
            WHERE distance_id='{$validDistanceId}' AND blog_id='{$this->conf->getBlogId()}'
        ");

        if($deleted === FALSE || $deleted === 0)
        {
            $this->errorMessages[] = $this->lang->getText('NRS_DISTANCE_DELETE_ERROR_TEXT');
        } else
        {
            $this->okayMessages[] = $this->lang->getText('NRS_DISTANCE_DELETED_TEXT');
        }

        return $deleted;
    }
}