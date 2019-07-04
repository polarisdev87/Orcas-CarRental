<?php
/**
 * NRS Extra Units Manager

 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Unit;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Language\Language;

class ExtraUnitManager extends AbstractUnitManager implements iUnitsManager
{
    public function __construct(
        ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings,
        $paramElementSKU, $paramTimestampFrom, $paramTimestampTo
    ) {
        parent::__construct($paramConf, $paramLang, $paramSettings, $paramElementSKU, $paramTimestampFrom, $paramTimestampTo);
    }

    /**
     * SQL optimized method
     * Supports units
     * @param string $paramLocationCode (DEFAULT = "", when it is applied to any location)
     * @param int $paramIgnoreFromBookingId (DEFAULT = 0, when it will not exclude any booking from calculation any booking)
     * @return array
     */
    public function getTotalUnits($paramLocationCode = "", $paramIgnoreFromBookingId = 0)
    {
        $unitsInStock = $this->getTotalUnitsInStock();
        $unitsBooked = $this->getTotalUnitsBookedByType("EXTRA", $paramLocationCode, $paramIgnoreFromBookingId);
        if($unitsBooked == -1)
        {
            // All item units are blocked by site administrator, no units available
            $unitsAvailable = 0;
        } else
        {
            $unitsAvailable = ($unitsInStock > $unitsBooked) ? ($unitsInStock - $unitsBooked) : 0;
        }

        $arrUnits = array(
            "units_in_stock" => $unitsInStock,
            "units_booked" => $unitsBooked,
            "units_available" => $unitsAvailable,
        );

        return $arrUnits;
    }

    public function getTotalUnitsAvailable($paramLocationCode = "", $paramIgnoreFromBookingId = 0)
    {
        $unitsInStock = $this->getTotalUnitsInStock();
        $unitsBooked = $this->getTotalUnitsBookedByType("EXTRA", "", $paramIgnoreFromBookingId);
        if($unitsBooked == -1)
        {
            // All item units are blocked by site administrator, no units available
            $unitsAvailable = 0;
        } else
        {
            $unitsAvailable = ($unitsInStock > $unitsBooked) ? ($unitsInStock - $unitsBooked) : 0;
        }

        return $unitsAvailable;
    }

    /**
     * This function appears to be same for both classes
     * @param string $paramLocationCode (DEFAULT = "")
     * @param int $paramIgnoreFromBookingId (DEFAULT = 0)
     * @return int
     */
    public function getMaxAllowedUnitsForBooking($paramLocationCode = "", $paramIgnoreFromBookingId = 0)
    {
        $maxUnitsPerBooking = $this->getMaxUnitsPerBooking();
        $totalUnitsInStock = $this->getTotalUnitsInStock();
        $bookedItems = $this->getTotalUnitsBookedByType("EXTRA", $paramLocationCode, $paramIgnoreFromBookingId);

        // Hope that auto type casting works well here from string to int
        $totalAvailableUnits = $totalUnitsInStock - $bookedItems;

        if($maxUnitsPerBooking > $totalAvailableUnits)
        {
            $maxAllowedUnitsForBooking = $totalAvailableUnits;
        } else
        {
            $maxAllowedUnitsForBooking = $maxUnitsPerBooking;
        }

        return $maxAllowedUnitsForBooking;
    }


    /**
     * NOTE: Use with CAUSE! If you need both - priciest and cheapest days of the week,
     * please use other - getTotalUnits() method
     * @return int
     */
    private function getTotalUnitsInStock()
    {
        $validExtraSKU = esc_sql(sanitize_text_field($this->elementSKU)); // for sql queries only
        $searchSQL = "
            SELECT units_in_stock
            FROM {$this->conf->getPrefix()}extras
            WHERE extra_sku='{$validExtraSKU}'
		";

        //echo "<br />".$searchSQL."<br />"; //die;

        $dbTotalUnitsInStock = $this->conf->getInternalWPDB()->get_var($searchSQL);

        $totalUnitsInStock = !is_null($dbTotalUnitsInStock) ? intval($dbTotalUnitsInStock) : 0;

        return $totalUnitsInStock;
    }

    private function getMaxUnitsPerBooking()
    {
        $validExtraSKU = esc_sql(sanitize_text_field($this->elementSKU)); // for sql queries only
        $searchSQL = "
            SELECT max_units_per_booking
            FROM {$this->conf->getPrefix()}extras
            WHERE extra_sku='{$validExtraSKU}'
		";

        //echo "<br />".$searchSQL."<br />"; //die;

        $dbMaxUnitsPerBooking = $this->conf->getInternalWPDB()->get_var($searchSQL);

        $maxUnitsPerBooking = !is_null($dbMaxUnitsPerBooking) ? intval($dbMaxUnitsPerBooking) : 0;

        return $maxUnitsPerBooking;
    }
}