<?php
/**
 * NRS Items Observer (no setup for single item)

 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Item;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Deposit\ItemDepositManager;
use NativeRentalSystem\Models\iObserver;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Pricing\PriceGroup;

class ItemsObserver implements iObserver
{
    protected $conf 	                = NULL;
    protected $lang 		            = NULL;
    protected $settings                 = array();
    protected $debugMode 	            = 0;
    protected $depositsEnabled          = FALSE;
    protected $classifyItems            = FALSE;

    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;
        $this->settings = $paramSettings;

        if(isset($paramSettings['conf_deposit_enabled']))
        {
            // Set deposit status
            $this->depositsEnabled = StaticValidator::getValidPositiveInteger($paramSettings['conf_deposit_enabled'], 1) == 1 ? TRUE : FALSE;
        }

        if(isset($paramSettings['conf_classify_items']))
        {
            // Set classified status
            $this->classifyItems = $paramSettings['conf_classify_items'] == 1 ? TRUE : FALSE;
        }
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    public function getIdBySKU($paramItemSKU)
    {
        $retItemId = 0;
        $validItemSKU = esc_sql(sanitize_text_field($paramItemSKU)); // For sql query only

        $itemData = $this->conf->getInternalWPDB()->get_row("
                SELECT item_id
                FROM {$this->conf->getPrefix()}items
                WHERE item_sku='{$validItemSKU}' AND blog_id='{$this->conf->getBlogId()}'
            ", ARRAY_A);
        if(!is_null($itemData))
        {
            $retItemId = $itemData['item_id'];
        }

        return $retItemId;
    }

    public function getAvailableIdsByLayout(
        $paramLayout, $paramPartnerId = -1, $paramManufacturerId = -1, $paramBodyTypeId = -1, $paramTransmissionTypeId = -1,
        $paramFuelTypeId = -1, $paramItemId = -1, $paramPickupLocationId = -1, $paramReturnLocationId = -1
    )
    {
        switch($paramLayout)
        {
            case "form":
                $displayMode = "AVAILABLE";
                break;

            case "slider":
                $displayMode = "IN_SLIDER";
                break;

            case "list":
                $displayMode = "IN_ITEM_LIST";
                break;

            case "grid":
                $displayMode = "IN_ITEM_LIST";
                break;

            case "table":
                $displayMode = "IN_PRICE_TABLE";
                break;

            case "calendar":
                $displayMode = "IN_AVAILABILITY_CALENDAR";
                break;

            default:
                $displayMode = "AVAILABLE";
        }

        return $this->getIds(
            $displayMode, $paramPartnerId, $paramManufacturerId, $paramBodyTypeId, $paramTransmissionTypeId,
            $paramFuelTypeId, $paramItemId, $paramPickupLocationId, $paramReturnLocationId
        );
    }

    public function getAvailableIdsForSlider(
        $paramPartnerId = -1, $paramManufacturerId = -1, $paramBodyTypeId = -1, $paramTransmissionTypeId = -1,
        $paramFuelTypeId = -1, $paramItemId = -1, $paramPickupLocationId = -1, $paramReturnLocationId = -1
    ) {
        return $this->getIds(
            "IN_SLIDER", $paramPartnerId, $paramManufacturerId, $paramBodyTypeId, $paramTransmissionTypeId,
            $paramFuelTypeId, $paramItemId, $paramPickupLocationId, $paramReturnLocationId
        );
    }

    public function getAvailableIdsForListOrGrid(
        $paramPartnerId = -1, $paramManufacturerId = -1, $paramBodyTypeId = -1, $paramTransmissionTypeId = -1,
        $paramFuelTypeId = -1, $paramItemId = -1, $paramPickupLocationId = -1, $paramReturnLocationId = -1
    ) {
        return $this->getIds(
            "IN_ITEM_LIST", $paramPartnerId, $paramManufacturerId, $paramBodyTypeId, $paramTransmissionTypeId,
            $paramFuelTypeId, $paramItemId, $paramPickupLocationId, $paramReturnLocationId
        );
    }

    public function getAvailableIdsForPriceTable(
        $paramPartnerId = -1, $paramManufacturerId = -1, $paramBodyTypeId = -1, $paramTransmissionTypeId = -1,
        $paramFuelTypeId = -1, $paramItemId = -1, $paramPickupLocationId = -1, $paramReturnLocationId = -1
    ) {
        return $this->getIds(
            "IN_PRICE_TABLE", $paramPartnerId, $paramManufacturerId, $paramBodyTypeId, $paramTransmissionTypeId,
            $paramFuelTypeId, $paramItemId, $paramPickupLocationId, $paramReturnLocationId
        );
    }

    public function getAvailableIdsForCalendar(
        $paramPartnerId = -1, $paramManufacturerId = -1, $paramBodyTypeId = -1, $paramTransmissionTypeId = -1,
        $paramFuelTypeId = -1, $paramItemId = -1, $paramPickupLocationId = -1, $paramReturnLocationId = -1
    ) {
        return $this->getIds(
            "IN_AVAILABILITY_CALENDAR", $paramPartnerId, $paramManufacturerId, $paramBodyTypeId, $paramTransmissionTypeId,
            $paramFuelTypeId, $paramItemId, $paramPickupLocationId, $paramReturnLocationId
        );
    }

    /**
     * Get an item which has amount > 0 of units and status != hidden
     * @param int $paramPartnerId
     * @param int $paramManufacturerId
     * @param int $paramBodyTypeId
     * @param int $paramTransmissionTypeId
     * @param int $paramFuelTypeId
     * @param int $paramItemId
     * @param int $paramPickupLocationId
     * @param int $paramReturnLocationId
     * @return array
     */
    public function getAvailableIds(
        $paramPartnerId = -1, $paramManufacturerId = -1, $paramBodyTypeId = -1, $paramTransmissionTypeId = -1,
        $paramFuelTypeId = -1, $paramItemId = -1, $paramPickupLocationId = -1, $paramReturnLocationId = -1
    ) {
        return $this->getIds(
            "AVAILABLE", $paramPartnerId, $paramManufacturerId, $paramBodyTypeId, $paramTransmissionTypeId,
            $paramFuelTypeId, $paramItemId, $paramPickupLocationId, $paramReturnLocationId
        );
    }

    public function getAllIds(
        $paramPartnerId = -1, $paramManufacturerId = -1, $paramBodyTypeId = -1, $paramTransmissionTypeId = -1,
        $paramFuelTypeId = -1, $paramItemId = -1, $paramPickupLocationId = -1, $paramReturnLocationId = -1
    ) {
        return $this->getIds(
            "ALL", $paramPartnerId, $paramManufacturerId, $paramBodyTypeId, $paramTransmissionTypeId,
            $paramFuelTypeId, $paramItemId, $paramPickupLocationId, $paramReturnLocationId
        );
    }

    /**
     * @param string $paramDisplayMode - one of display modes: "ALL", "AVAILABLE", "IN_SLIDER", "IN_ITEM_LIST", "IN_PRICE_TABLE", "IN_AVAILABILITY_CALENDAR"
     * @param int $paramPartnerId
     * @param int $paramManufacturerId
     * @param int $paramBodyTypeId
     * @param int $paramTransmissionTypeId
     * @param int $paramFuelTypeId
     * @param int $paramItemId
     * @param int $paramPickupLocationId
     * @param int $paramReturnLocationId
     * @return array
     */
    private function getIds(
        $paramDisplayMode = "ALL", $paramPartnerId = -1, $paramManufacturerId = -1, $paramBodyTypeId = -1, $paramTransmissionTypeId = -1,
        $paramFuelTypeId = -1, $paramItemId = -1, $paramPickupLocationId = -1, $paramReturnLocationId = -1
    ) {
        $validPartnerId = StaticValidator::getValidInteger($paramPartnerId, -1); // -1 means 'skip'
        $validManufacturerId = StaticValidator::getValidInteger($paramManufacturerId, -1); // -1 means 'skip'
        $validBodyTypeId = StaticValidator::getValidInteger($paramBodyTypeId, -1); // -1 means 'skip'
        $validTransmissionTypeId = StaticValidator::getValidInteger($paramTransmissionTypeId, -1); // -1 means 'skip'
        $validFuelTypeId = StaticValidator::getValidInteger($paramFuelTypeId, -1); // -1 means 'skip'
        $validItemId = StaticValidator::getValidInteger($paramItemId, -1); // -1 means 'skip'
        $validPickupLocationId = StaticValidator::getValidInteger($paramPickupLocationId, -1); // -1 means 'skip'
        $validReturnLocationId = StaticValidator::getValidInteger($paramReturnLocationId, -1); // -1 means 'skip'

        switch($paramDisplayMode)
        {
            case "IN_SLIDER":
                $sqlAdd = "AND it.units_in_stock > 0 AND enabled = '1' AND it.display_in_slider='1'";
                break;

            case "IN_ITEM_LIST":
                $sqlAdd = "AND it.units_in_stock > 0 AND enabled = '1' AND it.display_in_item_list='1'";
                break;

            case "IN_PRICE_TABLE":
                $sqlAdd = "AND it.units_in_stock > 0 AND enabled = '1' AND it.display_in_price_table='1'";
                break;

            case "IN_AVAILABILITY_CALENDAR":
                $sqlAdd = "AND it.units_in_stock > 0 AND enabled = '1' AND it.display_in_calendar='1'";
                break;

            case "AVAILABLE":
                $sqlAdd = "AND it.units_in_stock > 0 AND enabled = '1'";
                break;

            default:
                $sqlAdd = "";
        }

        // Partner field
        if($validPartnerId >= 0)
        {
            $sqlAdd .= " AND it.partner_id='{$validPartnerId}'";
        }

        // Manufacturer field
        if($validManufacturerId >= 0)
        {
            $sqlAdd .= " AND it.manufacturer_id='{$validManufacturerId}'";
        }

        // Body type field
        if($validBodyTypeId >= 0)
        {
            $sqlAdd .= " AND it.body_type_id='{$validBodyTypeId}'";
        }

        // Transmission type field
        if($validTransmissionTypeId >= 0)
        {
            $sqlAdd .= " AND it.transmission_type_id='{$validTransmissionTypeId}'";
        }

        // Fuel type field
        if($validFuelTypeId >= 0)
        {
            $sqlAdd .= " AND it.fuel_type_id='{$validFuelTypeId}'";
        }

        // Item field
        if($validItemId > 0)
        {
            $sqlAdd .= " AND it.item_id='{$validItemId}'";
        }

        if($validPickupLocationId > 0)
        {
            $sqlAdd .= "
				AND it.item_id IN
				(
					SELECT item_id
					FROM {$this->conf->getPrefix()}item_locations
					WHERE location_id='{$validPickupLocationId}' AND location_type='1'
				)";
        }

        if($validReturnLocationId > 0)
        {
            $sqlAdd .= "AND it.item_id IN
			(
				SELECT item_id
				FROM {$this->conf->getPrefix()}item_locations
				WHERE location_id='{$validReturnLocationId}' AND location_type='2'
			)";
        }

        $searchSQL = "
            SELECT it.item_id
            FROM {$this->conf->getPrefix()}items it
            LEFT JOIN {$this->conf->getPrefix()}manufacturers mf ON it.manufacturer_id=mf.manufacturer_id
            WHERE it.blog_id='{$this->conf->getBlogId()}' {$sqlAdd}
            ORDER BY manufacturer_title ASC, model_name ASC
		";

        //DEBUG
        //echo nl2br($searchSQL)."<br /><br />";

        $searchResult = $this->conf->getInternalWPDB()->get_col($searchSQL);

        return $searchResult;
    }

    /**
     * Do items are classified?
     * @return bool
     */
    public function areItemsClassified()
    {
        return $this->classifyItems;
    }

    public function canShowOnlyPartnerOwned()
    {
        $canEditOwnItems = current_user_can('manage_'.$this->conf->getExtensionPrefix().'own_items');
        $canEditAllItems = current_user_can('manage_'.$this->conf->getExtensionPrefix().'all_items');
        $onlyPartnerOwned = $canEditOwnItems == TRUE && $canEditAllItems == FALSE;

        return $onlyPartnerOwned;
    }

    public function getTranslatedDropDownOptionsByPartnerId($paramPartnerId = -1, $paramSelectedItemId = 0, $paramDefaultValue = "", $paramDefaultLabel = "", $paramShowItemId = TRUE)
    {
        return $this->getDropDownOptions($paramSelectedItemId, $paramDefaultValue, $paramDefaultLabel, $paramShowItemId, TRUE, $paramPartnerId);
    }

    public function getDropDownOptionsByPartnerId($paramPartnerId = -1, $paramSelectedItemId = 0, $paramDefaultValue = "", $paramDefaultLabel = "", $paramShowItemId = TRUE)
    {
        return $this->getDropDownOptions($paramSelectedItemId, $paramDefaultValue, $paramDefaultLabel, $paramShowItemId, FALSE, $paramPartnerId);
    }

    public function getTranslatedDropDownOptions($paramSelectedItemId = 0, $paramDefaultValue = "", $paramDefaultLabel = "", $paramShowItemId = TRUE)
    {
        return $this->getDropDownOptions($paramSelectedItemId, $paramDefaultValue, $paramDefaultLabel, $paramShowItemId, TRUE, -1);
    }

    /**
     * @param int $paramSelectedItemId
     * @param string $paramDefaultValue
     * @param string $paramDefaultLabel
     * @param bool $paramShowItemId
     * @param bool $paramTranslated
     * @param int $paramPartnerId
     * @return string
     */
    public function getDropDownOptions($paramSelectedItemId = 0, $paramDefaultValue = "", $paramDefaultLabel = "", $paramShowItemId = TRUE, $paramTranslated = FALSE, $paramPartnerId = -1)
    {
        $printDefaultValue = esc_html(sanitize_text_field($paramDefaultValue));
        $printDefaultLabel = esc_html(sanitize_text_field($paramDefaultLabel));
        $itemHTML = '';
        if($paramDefaultValue != "" || $paramDefaultLabel != "")
        {
            $defaultSelected = $paramSelectedItemId == $paramDefaultValue ? ' selected="selected"' : '';
            $itemHTML .= '<option value="'.$printDefaultValue.'"'.$defaultSelected.'>'.$printDefaultLabel.'</option>';
        }

        $itemIds = $this->getIds("ALL", $paramPartnerId);
        foreach ($itemIds AS $itemId)
        {
            // Process full item details
            $objItem = new Item($this->conf, $this->lang, $this->settings, $itemId);
            $itemDetails = $objItem->getExtendedDetails();

            if($paramTranslated)
            {
                $printTitle = $itemDetails['print_translated_manufacturer_title'].' '.$itemDetails['print_translated_model_name'].' '.$itemDetails['print_via_partner'];
            } else
            {
                $printTitle = $itemDetails['print_manufacturer_title'].' '.$itemDetails['print_model_name'].' '.$itemDetails['print_via_partner'];
            }
            if($paramShowItemId)
            {
                $printTitle .= " (ID=".$itemDetails['item_id'].")";
            }
            $selected = $paramSelectedItemId == $itemDetails['item_id'] ? ' selected="selected"' : '';

            $itemHTML .= '<option value="'.$itemDetails['item_id'].'"'.$selected.'>'.$printTitle.'</option>';
        }

        return $itemHTML;
    }

    /**
     * @param int $paramSelectPageId
     * @param string $name
     * @param null $id
     * @return string
     */
    public function getPagesDropDown($paramSelectPageId = 0, $name = "item_page_id", $id = null)
    {
        $pageArgs = array(
            'depth' => 1,
            'child_of' => 0,
            'selected' => $paramSelectPageId,
            'echo' => 0,
            'name' => $name,
            'id' => $id, // string
            'show_option_none' => $this->lang->getText('NRS_ADMIN_CHOOSE_PAGE_TEXT'), // string
            'sort_order' => 'ASC',
            'sort_column' => 'post_title',
            'post_type' => $this->conf->getExtensionPrefix().'item',
        );
        $dropDownHtml = wp_dropdown_pages($pageArgs);

        // DEBUG
        //echo "RESULT: $dropDownHtml";

        return $dropDownHtml;
    }

    /*******************************************************************************/
    /********************** METHODS FOR ADMIN ACCESS ONLY **************************/
    /*******************************************************************************/

    public function getAdminList()
    {
        $itemList = '';

        $itemIds = $this->getIds("ALL", ($this->canShowOnlyPartnerOwned() ? get_current_user_id() : -1));
        foreach($itemIds AS $itemId)
        {
            $objItem = new Item($this->conf, $this->lang, $this->settings, $itemId);
            $itemDetails = $objItem->getExtendedDetails();
            $objDepositManager                  = new ItemDepositManager($this->conf, $this->lang, $this->settings, $itemId);
            $itemDepositDetails 		        = $objDepositManager->getDetails();
            $item = array_merge($itemDetails, $itemDepositDetails);
            $objPriceGroup = new PriceGroup($this->conf, $this->lang, $this->settings, $item['price_group_id']);
            $priceGroupDetails = $objPriceGroup->getDetailsWithPartner();
            $enabled = $this->lang->getText($item['enabled'] == 1 ? 'NRS_ADMIN_AVAILABLE_TEXT' : 'NRS_ADMIN_HIDDEN_TEXT');
            $displayInSlider = $this->lang->getText($item['display_in_slider'] == 1 ? 'NRS_ADMIN_DISPLAYED_TEXT' : 'NRS_ADMIN_HIDDEN_TEXT');

            if(!is_null($priceGroupDetails))
            {
                $printTranslatedPriceGroupName = $priceGroupDetails['print_translated_price_group_name'].' '.$priceGroupDetails['print_via_partner'];
            } else
            {
                $printTranslatedPriceGroupName = '<span style="color: darkred;">'.$this->lang->getText('NRS_NOT_SET_TEXT').'</span>';
            }

            if($item['item_page_id'] != 0 && $item['item_page_url'] != '')
            {

                $itemPageTitle = get_the_title($item['item_page_id']);
                $linkTitle = sprintf($this->lang->getText('NRS_ADMIN_VIEW_PAGE_IN_NEW_WINDOW_TEXT'), $itemPageTitle);
                $printTranslatedItemManufacturerAndModelWithLink = '<a href="'.$item['item_page_url'].'" target="_blank" title="'.$linkTitle.'">';
                $printTranslatedItemManufacturerAndModelWithLink .= $item['print_translated_manufacturer_title'].' '.$item['print_translated_model_name'].' '.$item['print_via_partner'];
                $printTranslatedItemManufacturerAndModelWithLink .= '</a>';
            } else
            {
                $printTranslatedItemManufacturerAndModelWithLink = $item['print_translated_manufacturer_title'].' '.$item['print_translated_model_name'].' '.$item['print_via_partner'];
            }

            if($this->lang->canTranslateSQL())
            {
                $printTranslatedItemManufacturerAndModelWithLink .= '<br /><span class="not-translated" title="'.$this->lang->getText('NRS_ADMIN_WITHOUT_TRANSLATION_TEXT').'">('.$item['print_model_name'].')</span>';
            }

            $itemList .= '<tr>';
            $itemList .= '<td>'.$itemId.'</td>';
            $itemList .= '<td>'.$item['print_item_sku'].'</td>';
            $itemList .= '<td>'.$item['print_translated_body_type_title'].'</td>';
            $itemList .= '<td>'.$item['print_translated_transmission_type_title'].'</td>';
            $itemList .= '<td>'.$printTranslatedItemManufacturerAndModelWithLink.'</td>';
            $itemList .= '<td style="white-space: nowrap">';
            $itemList .= '<span style="cursor:pointer;" title="'.$this->lang->getText('NRS_ADMIN_MAX_ITEM_UNITS_PER_BOOKING_TEXT').'">'.$item['max_units_per_booking'].'</span> / ';
            $itemList .= '<span style="cursor:pointer;font-weight:bold" title="'.$this->lang->getText('NRS_ADMIN_TOTAL_ITEM_UNITS_IN_STOCK_TEXT').'">'.$item['units_in_stock'].'</span> ';
            $itemList .= '</td>';
            $itemList .= '<td>'.$item['print_translated_fuel_type_title'].'</td>';
            $itemList .= '<td>'.$printTranslatedPriceGroupName.'</td>';
            if($this->depositsEnabled)
            {
                $itemList .= '<td>'.$item['unit_print']['fixed_deposit_amount'].'</td>';
            }
            $itemList .= '<td>'.$item['print_min_driver_age'].'</td>';
            $itemList .= '<td>'.$enabled.'</td>';
            $itemList .= '<td>'.$displayInSlider.'</td>';
            $itemList .= '<td align="right">';
            if($objItem->canEdit())
            {
                $itemList .= '<a href="'.admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-item&amp;item_id='.$itemId).'">'.$this->lang->getText('NRS_ADMIN_EDIT_TEXT').'</a> || ';
                $itemList .= '<a href="javascript:;" onclick="javascript:delete'.$this->conf->getExtensionFolder().'Item(\''.$itemId.'\')">'.$this->lang->getText('NRS_ADMIN_DELETE_TEXT').'</a>';
            } else
            {
                $itemList .= '--';
            }
            $itemList .= '</td>';
            $itemList .= '</tr>';
        }

        return  $itemList;
    }
}