<?php
/**
 * Prepayment processor. Used in administration side only

 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Prepayment;
use NativeRentalSystem\Models\AbstractElement;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\iElement;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;

class Prepayment extends AbstractElement implements iElement
{
    protected $conf 	    = NULL;
    protected $lang 		= NULL;
    protected $debugMode 	= 0;
    protected $prepaymentId = 0;
    /**
     * @var int - price calculation: 1 - daily, 2 - hourly, 3 - mixed (daily+hourly)
     */
    protected $priceCalculationType     = 1;

    /**
     * Prepayment constructor.
     * @param ExtensionConfiguration &$paramConf
     * @param Language &$paramLang
     * @param array $paramSettings
     * @param int $paramPrepaymentId
     */
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings, $paramPrepaymentId)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;

        // Set prepayment id
        $this->prepaymentId = StaticValidator::getValidPositiveInteger($paramPrepaymentId, 0);

        if(isset($paramSettings['conf_price_calculation_type']))
        {
            // Set price calculation type
            $this->priceCalculationType = StaticValidator::getValidPositiveInteger($paramSettings['conf_price_calculation_type'], 1);
        }
    }

    /**
     * For internal class use only
     * @param $paramPrepaymentId
     * @return mixed
     */
    private function getDataFromDatabaseById($paramPrepaymentId)
    {
        $validPrepaymentId = StaticValidator::getValidPositiveInteger($paramPrepaymentId, 0);
        $prepaymentData = $this->conf->getInternalWPDB()->get_row("
            SELECT *
            FROM {$this->conf->getPrefix()}prepayments
            WHERE prepayment_id='{$validPrepaymentId}'
        ", ARRAY_A);

        return $prepaymentData;
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    public function getId()
    {
        return $this->prepaymentId;
    }

    public function getDetails($paramIncludeUnclassified = FALSE)
    {
        $retData = $this->getDataFromDatabaseById($this->prepaymentId);

        // No need for edit ready preparation here - as this class corresponding database table does not have string fields

        return $retData;
    }

    /**
     * @note - We use 'blog_id' here because we don't want to allow NRS Managers from one multisite site to edit items in other multisite site
     */
    public function save()
    {
        $saved = FALSE;
        $ok = TRUE;
        $paramDaysFrom = isset($_POST['days_from']) ? $_POST['days_from'] : 0;
        $paramHoursFrom = isset($_POST['hours_from']) ? $_POST['hours_from'] : 0;
        $paramDaysTill = isset($_POST['days_till']) ? $_POST['days_till'] : 0;
        $paramHoursTill = isset($_POST['hours_till']) ? $_POST['hours_till'] : 0;

        $validPrepaymentId = StaticValidator::getValidPositiveInteger($this->prepaymentId, 0);
        $validPeriodFrom = $this->getPeriodByPriceType($paramDaysFrom, $paramHoursFrom, "FROM");
        $validPeriodTill = $this->getPeriodByPriceType($paramDaysTill, $paramHoursTill, "TILL");
        $validItemPricesIncluded = isset($_POST['item_prices_included']) ? 1 : 0;
        $validItemDepositsIncluded = isset($_POST['item_deposits_included']) ? 1 : 0;
        $validExtraPricesIncluded = isset($_POST['extra_prices_included']) ? 1 : 0;
        $validExtraDepositsIncluded = isset($_POST['extra_deposits_included']) ? 1 : 0;
        $validPickupFeesIncluded = isset($_POST['pickup_fees_included']) ? 1 : 0;
        $validDistanceFeesIncluded = isset($_POST['distance_fees_included']) ? 1 : 0;
        $validReturnFeesIncluded = isset($_POST['return_fees_included']) ? 1 : 0;
        if(isset($_POST['prepayment_percentage']) && $_POST['prepayment_percentage'] > 0)
        {
            // Allow only positive prepayment percentage
            $validPrepaymentPercentage = floatval($_POST['prepayment_percentage']);
        } else
        {
            $validPrepaymentPercentage = 0.00;
        }

        // Do not allow to have prepayments more than 100%
        if($validPrepaymentPercentage > 100)
        {
            $validPrepaymentPercentage = 100;
        }

        // If expr is greater than or equal to min and expr is less than or equal to max, BETWEEN returns 1, otherwise it returns 0
        $minDaysValueCheck = $this->conf->getInternalWPDB()->get_results("
            SELECT prepayment_id
            FROM {$this->conf->getPrefix()}prepayments
            WHERE prepayment_id!='{$validPrepaymentId}' AND (
              '{$validPeriodFrom}' BETWEEN period_from AND period_till
              OR '{$validPeriodTill}' BETWEEN period_from AND period_till
            ) AND blog_id='{$this->conf->getBlogId()}'
        ", ARRAY_A);

        if(sizeof($minDaysValueCheck) > 0)
        {
            $ok = FALSE;
            $this->errorMessages[] = $this->lang->getText('NRS_PREPAYMENT_DAYS_INTERSECTION_ERROR_TEXT');
        }

        if($validPrepaymentId > 0 && $ok)
        {
            $saved = $this->conf->getInternalWPDB()->query("
                UPDATE {$this->conf->getPrefix()}prepayments SET
                period_from='{$validPeriodFrom}', period_till='{$validPeriodTill}',
                item_prices_included='{$validItemPricesIncluded}',
                item_deposits_included='{$validItemDepositsIncluded}',
                extra_prices_included='{$validExtraPricesIncluded}',
                extra_deposits_included='{$validExtraDepositsIncluded}',
                pickup_fees_included='{$validPickupFeesIncluded}',
                distance_fees_included='{$validDistanceFeesIncluded}',
                return_fees_included='{$validReturnFeesIncluded}',
                prepayment_percentage='{$validPrepaymentPercentage}'
                WHERE prepayment_id='{$validPrepaymentId}' AND blog_id='{$this->conf->getBlogId()}'
            ");

            if($saved === FALSE)
            {
                $this->errorMessages[] = $this->lang->getText('NRS_PREPAYMENT_UPDATE_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('NRS_PREPAYMENT_UPDATED_TEXT');
            }
        } else if($ok)
        {
            $saved = $this->conf->getInternalWPDB()->query("
                INSERT INTO {$this->conf->getPrefix()}prepayments
                (
                    period_from, period_till,
                    item_prices_included,
                    item_deposits_included,
                    extra_prices_included,
                    extra_deposits_included,
                    pickup_fees_included,
                    distance_fees_included,
                    return_fees_included,
                    prepayment_percentage,
                    blog_id
                ) VALUES
                (
                    '{$validPeriodFrom}', '{$validPeriodTill}',
                    '{$validItemPricesIncluded}',
                    '{$validItemDepositsIncluded}',
                    '{$validExtraPricesIncluded}',
                    '{$validExtraDepositsIncluded}',
                    '{$validPickupFeesIncluded}',
                    '{$validDistanceFeesIncluded}',
                    '{$validReturnFeesIncluded}',
                    '{$validPrepaymentPercentage}',
                    '{$this->conf->getBlogId()}'
                )
            ");
            if($saved)
            {
                // Update object id with newly inserted id for future work
                $this->prepaymentId = $this->conf->getInternalWPDB()->insert_id;
            }

            if($saved === FALSE || $saved === 0)
            {
                $this->errorMessages[] = $this->lang->getText('NRS_PREPAYMENT_INSERT_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('NRS_PREPAYMENT_INSERTED_TEXT');
            }
        }

        return $saved;
    }

    /**
     * Not used for this element
     */
    public function registerForTranslation()
    {
        // not used
    }

    /**
     * @note - We use 'blog_id' here because we don't want to allow NRS Managers from one multisite site to delete items in other multisite site
     */
    public function delete()
    {
        $validPrepaymentId = StaticValidator::getValidPositiveInteger($this->prepaymentId);
        $deleted = $this->conf->getInternalWPDB()->query("
            DELETE FROM {$this->conf->getPrefix()}prepayments
            WHERE prepayment_id='{$validPrepaymentId}' AND blog_id='{$this->conf->getBlogId()}'
        ");

        if($deleted === FALSE || $deleted === 0)
        {
            $this->errorMessages[] = $this->lang->getText('NRS_PREPAYMENT_DELETE_ERROR_TEXT');
        } else
        {
            $this->okayMessages[] = $this->lang->getText('NRS_PREPAYMENT_DELETED_TEXT');
        }


        return $deleted;
    }


    /* ------------------------------------------------------------------------------------ */
    /* Element-specific methods                                                             */
    /* ------------------------------------------------------------------------------------ */
    /**
     * Based on price calculation type from duration days / hours it will return period (in seconds)
     * @param $paramDays
     * @param $paramHours
     * @param string $paramType
     * @return int
     */
    protected function getPeriodByPriceType($paramDays, $paramHours, $paramType)
    {
        $validDays =  StaticValidator::getValidPositiveInteger($paramDays, 0);
        $validHours = StaticValidator::getValidPositiveInteger($paramHours, 0);
        if($this->priceCalculationType == 1)
        {
            // Days only
            $retPeriod = $paramType == "FROM" ? $validDays*86400 : $validDays*86400+86400-1;
        } else if($this->priceCalculationType == 2)
        {
            // Hours only
            $retPeriod = $paramType == "FROM" ? $validHours*3600 : $validHours*3600+3600-1;
        } else
        {
            // Mixed - Days & Hours
            $retPeriod = $paramType == "FROM" ? $validDays*86400 + $validHours*3600 : ($validDays*86400+86400-1) + ($validHours*3600+3600-1);
        }

        return $retPeriod;
    }
}