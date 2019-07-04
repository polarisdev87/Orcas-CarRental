<?php
/**

 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
*/
namespace NativeRentalSystem\Models\Block;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Extra\Extra;
use NativeRentalSystem\Models\Formatting\StaticFormatter;
use NativeRentalSystem\Models\iObserver;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;

class ExtraBlockManager extends AbstractBlockManager implements iObserver, iBlockManager
{
    protected $extraIds		   		= array();
    protected $extraUnits      		= array();

	public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings)
	{
		parent::__construct($paramConf, $paramLang, $paramSettings);
	}

	/**
	 * Step no. 1 - Show reservation box. (optional) + show car
	 * Step no. 2 - (optional) Select car, if no car provided
	 * Step no. 3 - Select car extras
	 * Step no. 4 - Show booking details
	 * Step no. 5 - Process booking
	 * Step no. 6 - PayPal payment
	 */
    public function setVariablesByRequestOrSessionParams()
	{
		// came from step1
		$inputStartDate = $this->getValidValueInput(array('POST', 'SESSION'), 'start_date', date($this->shortDateFormat), TRUE, $this->shortDateFormat);
		$inputStartTime = $this->getValidValueInput(array('POST', 'SESSION'), 'start_time', date("H:i:s"), TRUE, 'time_validation');
		// For blocking we allow only to select only from date and time options
		$inputEndDate = $this->getValidValueInput(array('POST', 'SESSION'), 'end_date', date($this->shortDateFormat), TRUE, $this->shortDateFormat);
		$inputEndTime = $this->getValidValueInput(array('POST', 'SESSION'), 'end_time', date("H:i:s"), TRUE, 'time_validation');
		$this->startTimestamp = StaticValidator::getUTCTimestampFromLocalISODateTime($inputStartDate, $inputStartTime);
		$this->endTimestamp = StaticValidator::getUTCTimestampFromLocalISODateTime($inputEndDate, $inputEndTime);

		if(isset($_POST['block']) || isset($_POST['extra_ids']))
		{
			// Came from step 2
			$this->extraIds = $this->getValidArrayInput(array('POST'), 'extra_ids', array(), TRUE, 'positive_integer');
		}
        // 17 - Extra Units
        if(isset($_POST['block']) || isset($_POST['extra_units']))
        {
            // intval allows us here for admins to block all (-1) extra units from backend
            $this->extraUnits = $this->getValidArrayInput(array('POST'), 'extra_units', array(), TRUE, 'intval');
        }

		/**********************************************************************/
		if($this->debugMode)
		{
		    echo "<br />";
			echo "\$inputStartDate: $inputStartDate<br />";
			echo "\$inputStartTime: $inputStartTime<br />";
			echo "\$inputEndDate: $inputEndDate<br />";
			echo "\$inputEndTime: $inputEndTime<br />";
			echo "\$this->startTimestamp: $this->startTimestamp<br />";
			echo "\$this->endTimestamp: $this->endTimestamp<br />";
			echo "\$this->extraIds: "; print_r($this->extraIds); echo "<br />";
            echo "\$this->extraUnits: "; print_r($this->extraUnits); echo "<br />";
			echo "POST: ".nl2br(print_r($_POST, TRUE));
		}
		/**********************************************************************/
	}


    public function setMySessionVars()
    {
        // filled data in step 1, pre-filled in step 2, 3
        $_SESSION['start_date']   	  	= $this->getShortStartDate();
        $_SESSION['start_time']   		= $this->getShortStartTime();
        $_SESSION['end_date']  	  	    = $this->getShortEndDate();
        $_SESSION['end_time']  		    = $this->getShortEndTime();

        // filled in step 2, pre-filled in step 3
        $_SESSION['extra_ids']   	  	= $this->extraIds;
        $_SESSION['extra_units']   		= $this->extraUnits;

        // DEBUG
        //echo "UPDATED SESSION VARS: ".nl2br(print_r($_SESSION, TRUE));
    }

    public function removeSessionVariables()
    {
        // filled data in step1
        if(isset($_SESSION['start_date']))
        {
            unset($_SESSION['start_date']);
        }
        if(isset($_SESSION['start_time']))
        {
            unset($_SESSION['start_time']);
        }
        if(isset($_SESSION['end_date']))
        {
            unset($_SESSION['end_date']);
        }
        if(isset($_SESSION['end_time']))
        {
            unset($_SESSION['end_time']);
        }

        // filled in step 2
        if(isset($_SESSION['extra_ids']))
        {
            unset($_SESSION['extra_ids']);
        }
        if(isset($_SESSION['extra_units']))
        {
            unset($_SESSION['extra_units']);
        }
    }

    public function getIds()
    {
        return $this->extraIds;
    }

    public function getUnits($paramExtraId)
    {
        return isset($this->extraUnits[$paramExtraId]) ? $this->extraUnits[$paramExtraId] : 0;
    }

    public function getAvailable()
    {
        $addQuery = "";

        $searchSQL = "
            SELECT extra_id
            FROM {$this->conf->getPrefix()}extras
            WHERE units_in_stock > 0
            {$addQuery} AND blog_id='{$this->conf->getBlogId()}'
			ORDER BY extra_name ASC
		";
        //echo "<br />".$searchSQL."<br />"; //die;

        $sqlRows = $this->conf->getInternalWPDB()->get_col($searchSQL);

        if($this->debugMode == 1)
        {
            echo "<br />TOTAL CANDIDATE EXTRAS FOUND: " . sizeof($sqlRows);
            echo "<br /><em>(Note: the candidate number is not final, it does not include blocked extras ";
            echo "or the situation when all extra units are booked)</em>";
        }

        return $sqlRows;
    }

    public function getSelectedWithDetails($paramExtraIds)
    {
        return $this->getWithDetails($paramExtraIds, TRUE);
    }

    public function getAvailableWithDetails($paramExtraIds)
    {
        return $this->getWithDetails($paramExtraIds, FALSE);
    }

    public function getWithDetails($paramExtraIds, $paramSelectedOnly = FALSE)
    {
        $retExtras = array();
        $extraIds = is_array($paramExtraIds) ? $paramExtraIds : array();

        // Multi-mode check is not applied for extra blocks
        foreach($extraIds AS $extraId)
        {
            // 1 - Process extra details
            $objExtra = new Extra($this->conf, $this->lang, $this->settings, $extraId);
            $extraDetails = $objExtra->getDetailsWithItemAndPartner();

            if($extraDetails['units_in_stock'] > 0 && $extraDetails['item_id'] == 0)
            {
                $selectedQuantity = 0;
                if(isset($this->extraUnits[$extraId]) && $extraDetails['units_in_stock'] > 0)
                {
                    if($this->extraUnits[$extraId] == -1)
                    {
                        // All units selected
                        $selectedQuantity = -1;
                    } else if($extraDetails['units_in_stock'] > $this->extraUnits[$extraId])
                    {
                        $selectedQuantity = $this->extraUnits[$extraId];
                    } else
                    {
                        $selectedQuantity = $extraDetails['units_in_stock'];
                    }
                }

                // For blocks we always need to display dependant item title and partner name (if exist)
                $printExtra = $extraDetails['print_extra_name_with_dependant_item'].' '.$extraDetails['print_via_partner'];
                $printTranslatedExtra = $extraDetails['print_translated_extra_name_with_dependant_item'].' '.$extraDetails['print_via_partner'];

                // 4 - Extend the $extra output with new details
                $extraDetails['selected'] = $selectedQuantity > 0 || $selectedQuantity == -1 ? TRUE : FALSE;
                $extraDetails['selected_quantity'] = $selectedQuantity;
                $extraDetails['quantity_dropdown_options'] = StaticFormatter::generateDropDownOptions(
                    0, $extraDetails['units_in_stock'], $selectedQuantity, -1, $this->lang->getText('NRS_ALL_TEXT'), FALSE, ""
                );
                $extraDetails['print_checked'] = $selectedQuantity > 0 || $selectedQuantity == -1 ? ' checked="checked"' : '';
                $extraDetails['print_selected'] = $selectedQuantity > 0 || $selectedQuantity == -1 ? 'selected' : '';
                $extraDetails['print_extra'] = $printExtra;
                $extraDetails['print_translated_extra'] = $printTranslatedExtra;

                if($selectedQuantity > 0 || $selectedQuantity == -1 || $paramSelectedOnly === FALSE)
                {
                    // Add to stack only if element is selected or if we return all elements
                    $retExtras[] = $extraDetails;
                }

                if($this->debugMode == 1)
                {
                    echo "<br /><br />Extra with ID={$extraId} is <span style='color:green;font-weight:bold;'>AVAILABLE</span> for booking ";
                    echo ", is not dependant on any item (Item ID=0) from the list of selected items: ";
                    echo "<br />and has {$selectedQuantity} units selected, with total {$extraDetails['units_in_stock']} units in stock";
                }
            } else
            {
                if($this->debugMode == 1)
                {
                    echo "<br /><br />Extra with ID={$extraId} is <span style='color:red;font-weight:bold;'>NOT AVAILABLE</span> for booking ";
                    echo "is dependant on item ID={$extraDetails['item_id']}, with {$extraDetails['units_in_stock']}";
                }
            }
        }

        // DEBUG
        //echo "<br />EXTRAS: ".nl2br(esc_textarea(print_r($retExtras, TRUE)));

        return $retExtras;
    }
}