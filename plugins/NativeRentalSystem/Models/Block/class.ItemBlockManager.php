<?php
/**

 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
*/
namespace NativeRentalSystem\Models\Block;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Formatting\StaticFormatter;
use NativeRentalSystem\Models\iObserver;
use NativeRentalSystem\Models\Item\Item;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;

class ItemBlockManager extends AbstractBlockManager implements iObserver, iBlockManager
{
    protected $locationId   	    = 0;
    protected $itemIds            	= array();
    protected $itemUnits        	= array();

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
		$this->locationId = $this->getValidValueInput(array('POST', 'SESSION'), 'location_id', 0, FALSE, 'positive_integer');
		$inputStartDate = $this->getValidValueInput(array('POST', 'SESSION'), 'start_date', date($this->shortDateFormat), TRUE, $this->shortDateFormat);
		$inputStartTime = $this->getValidValueInput(array('POST', 'SESSION'), 'start_time', date("H:i:s"), TRUE, 'time_validation');
		// For blocking we allow only to select only from date and time options
		$inputEndDate = $this->getValidValueInput(array('POST', 'SESSION'), 'end_date', date($this->shortDateFormat), TRUE, $this->shortDateFormat);
		$inputEndTime = $this->getValidValueInput(array('POST', 'SESSION'), 'end_time', date("H:i:s"), TRUE, 'time_validation');
		$this->startTimestamp = StaticValidator::getUTCTimestampFromLocalISODateTime($inputStartDate, $inputStartTime);
		$this->endTimestamp = StaticValidator::getUTCTimestampFromLocalISODateTime($inputEndDate, $inputEndTime);

		if(isset($_POST['block']) || isset($_POST['item_ids']))
		{
			// Came from step 2
			$this->itemIds = $this->getValidArrayInput(array('POST'), 'item_ids', array(), TRUE, 'positive_integer');
		}
        if(isset($_POST['block']) || isset($_POST['item_units']))
        {
            // came back to step2 from step3
            // intval allows us here for admins to block all (-1) item units from backend
            $this->itemUnits = $this->getValidArrayInput(array('POST'), 'item_units', array(), TRUE, 'intval');
        }

		/**********************************************************************/
		if($this->debugMode)
		{
            echo "<br />";
			echo "\$this->locationId: $this->locationId<br />";
			echo "\$inputStartDate: $inputStartDate<br />";
			echo "\$inputStartTime: $inputStartTime<br />";
			echo "\$inputEndDate: $inputEndDate<br />";
			echo "\$inputEndTime: $inputEndTime<br />";
			echo "\$this->startTimestamp: $this->startTimestamp<br />";
			echo "\$this->endTimestamp: $this->endTimestamp<br />";
			echo "\$this->itemIds: "; print_r($this->itemIds); echo "<br />";
			echo "\$this->itemUnits: "; print_r($this->itemUnits); echo "<br />";
			echo "POST: ".nl2br(print_r($_POST, TRUE));
		}
		/**********************************************************************/
	}

    public function setMySessionVars()
    {
        // filled data in step 1, pre-filled in step 2, 3
        $_SESSION['location_id']	    = $this->locationId;
        $_SESSION['start_date']   	  	= $this->getShortStartDate();
        $_SESSION['start_time']   		= $this->getShortStartTime();
        $_SESSION['end_date']  	  	    = $this->getShortEndDate();
        $_SESSION['end_time']  		    = $this->getShortEndTime();

        // filled in step 2, pre-filled in step 3
        $_SESSION['item_ids']   		= $this->itemIds;
        $_SESSION['item_units']   	  	= $this->itemUnits;

        // DEBUG
        //echo "UPDATED SESSION VARS: ".nl2br(print_r($_SESSION, TRUE));
    }

    public function removeSessionVariables()
    {
        // filled data in step1
        if(isset($_SESSION['location_id']))
        {
            unset($_SESSION['location_id']);
        }
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
        if(isset($_SESSION['item_ids']))
        {
            unset($_SESSION['item_ids']);
        }
        if(isset($_SESSION['item_units']))
        {
            unset($_SESSION['item_units']);
        }

    }

    public function getLocationId()
    {
        return $this->locationId;
    }

    public function getIds()
    {
        return $this->itemIds;
    }

    public function getUnits($paramItemId)
    {
        return isset($this->itemUnits[$paramItemId]) ? $this->itemUnits[$paramItemId] : 0;
    }

    public function getAvailable()
    {
        $addQuery = '';

        $validLocationId = StaticValidator::getValidPositiveInteger($this->locationId, 0);

        if($validLocationId > 0)
        {
            // For items in specific pickup location
            $addQuery .= "
				AND it.item_id IN
				(
					SELECT item_id
					FROM {$this->conf->getPrefix()}item_locations
					WHERE location_id='{$validLocationId}' AND location_type='1'
				)";
        }

        $blockSQL = "
			SELECT it.item_id
			FROM {$this->conf->getPrefix()}items it
			LEFT JOIN {$this->conf->getPrefix()}manufacturers mf ON it.manufacturer_id=mf.manufacturer_id
			WHERE it.units_in_stock > 0 AND it.enabled = '1'
			{$addQuery} AND it.blog_id='{$this->conf->getBlogId()}'
			ORDER BY manufacturer_title ASC, model_name ASC
			";

        //echo "<br />".$blockSQL."<br />"; //die;
        $sqlRows = $this->conf->getInternalWPDB()->get_col($blockSQL);

        if($this->debugMode == 1)
        {
            echo "<br />TOTAL CANDIDATE ITEMS FOUND: " . sizeof($sqlRows);
            echo "<br /><em>(Note: the candidate number is not final, it does not include blocked items ";
            echo "(for all or specific locations) or the situation when all item units are booked)</em>";
        }

        return $sqlRows;
    }

    public function getSelectedWithDetails($paramItemIds)
    {
        return $this->getWithDetails($paramItemIds, TRUE);
    }

    public function getAvailableWithDetails($paramItemIds)
    {
        return $this->getWithDetails($paramItemIds, FALSE);
    }

    public function getWithDetails($paramItemIds, $paramSelectedOnly = FALSE)
    {
        $retItems = array();
        $itemIds = is_array($paramItemIds) ? $paramItemIds : array();

        // Multi-mode check is not applied for item blocks
        foreach($itemIds AS $itemId)
        {
            // 1 - Process full item details
            $objItem = new Item($this->conf, $this->lang, $this->settings, $itemId);
            $itemDetails = $objItem->getExtendedDetails();

            // If there is more items in stock than booked, and more items in stock than min quantity for booking
            if($itemDetails['units_in_stock'] > 0)
            {
                $selectedQuantity = 0;
                if(isset($this->itemUnits[$itemId]) && $itemDetails['units_in_stock'] > 0)
                {
                    if($this->itemUnits[$itemId] == -1)
                    {
                        // All units selected
                        $selectedQuantity = -1;
                    } else if($itemDetails['units_in_stock'] > $this->itemUnits[$itemId])
                    {
                        $selectedQuantity = $this->itemUnits[$itemId];
                    } else
                    {
                        $selectedQuantity = $itemDetails['units_in_stock'];
                    }
                }

                // 4 - Extend the $item output with new details
                $itemDetails['selected'] = $selectedQuantity > 0 || $selectedQuantity == -1 ? true : false;
                $itemDetails['selected_quantity'] = $selectedQuantity;
                $itemDetails['quantity_dropdown_options'] = StaticFormatter::generateDropDownOptions(
                    0, $itemDetails['units_in_stock'], $selectedQuantity, -1, $this->lang->getText('NRS_ALL_TEXT'), FALSE, ""
                );
                $itemDetails['print_checked'] = $selectedQuantity > 0 || $selectedQuantity == -1 ? ' checked="checked"' : '';
                $itemDetails['print_selected'] = $selectedQuantity > 0 || $selectedQuantity == -1 ? 'selected' : '';

                if($selectedQuantity > 0 || $selectedQuantity == -1 || $paramSelectedOnly === FALSE)
                {
                    // Add to stack only if element is selected or if we return all elements
                    $retItems[] = $itemDetails;
                }

                if($this->debugMode == 1)
                {
                    echo "<br /><br />Item with ID={$itemId} is <span style='color:green;font-weight:bold;'>AVAILABLE</span> for booking ";
                    echo "has {$selectedQuantity} units selected, with total {$itemDetails['units_in_stock']} units in stock";
                }
            } else
            {
                if($this->debugMode == 1)
                {
                    echo "<br /><br />Item with ID={$itemId} is <span style='color:red;font-weight:bold;'>NOT AVAILABLE</span> for booking ";
                    echo "and has {$itemDetails['units_in_stock']} units in stock";
                }
            }
        }

        // DEBUG
        //echo "<br />ITEMS: ".nl2br(esc_textarea(print_r($retItems, TRUE)));

        return $retItems;
    }


}