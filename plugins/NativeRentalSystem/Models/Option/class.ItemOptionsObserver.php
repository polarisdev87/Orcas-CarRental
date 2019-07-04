<?php
/**
 * NRS Item Options Observer

 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Option;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\iObserver;
use NativeRentalSystem\Models\Item\Item;
use NativeRentalSystem\Models\Item\ItemsObserver;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;

class ItemOptionsObserver implements iObserver
{
    private $conf 	            = NULL;
    private $lang 		        = NULL;
    private $debugMode 	        = 0;
    private $settings      = array();

    /**
     * ItemOptionsObserver constructor.
     * @param ExtensionConfiguration $paramConf
     * @param Language $paramLang
     * @param array $paramSettings
     */
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

    public function getAllIds($paramItemId = -1)
    {
        $validItemId = StaticValidator::getValidPositiveInteger($paramItemId, 0);
        $sqlWhere = $validItemId == -1 ? "item_id>'0'" : "item_id='{$validItemId}'";
        $optionIds = $this->conf->getInternalWPDB()->get_col("
            SELECT option_id
            FROM {$this->conf->getPrefix()}options
            WHERE {$sqlWhere} AND blog_id='{$this->conf->getBlogId()}'
            ORDER BY option_name ASC
        ");

        return $optionIds;
    }


    /*******************************************************************************/
    /********************** METHODS FOR ADMIN ACCESS ONLY *************************
    /*******************************************************************************/

    public function getAdminList()
    {
        $itemList = '';

        $objItemsObserver = new ItemsObserver($this->conf, $this->lang, $this->settings);
        $itemIds = $objItemsObserver->getAllIds($objItemsObserver->canShowOnlyPartnerOwned() ? get_current_user_id() : -1);

        $i = 0;
        foreach($itemIds AS $itemId)
        {
            $i++;
            $objItem = new Item($this->conf, $this->lang, $this->settings, $itemId);
            $optionsList = $this->getAdminOptionsListByItemId($itemId, sprintf('%02d', $i) . ".");
            $itemDetails = $objItem->getExtendedDetails();

            if($optionsList != "")
            {
                // HTML OUTPUT
                $itemList .= '<tr>';
                $itemList .= '<td>'.sprintf('%02d', $i).'</td>';
                $itemList .= '<td>'.$itemDetails['print_translated_manufacturer_title'].' '.$itemDetails['print_translated_model_name'].' '.$itemDetails['print_via_partner'].'</td>';
                $itemList .= '<td>ID: '.$itemId.', '.$itemDetails['print_translated_body_type_title'].', '.$itemDetails['print_translated_transmission_type_title'].'</td>';
                $itemList .= '<td>&nbsp;</td>';
                $itemList .= '</tr>';
                $itemList .= $optionsList;
            }
        }

        return  $itemList;
    }

    /**
     * @param $paramItemId
     * @param string $paramRowNumbersPrefix
     * @return string
     */
    private function getAdminOptionsListByItemId($paramItemId, $paramRowNumbersPrefix = "0.")
    {
        $optionList = '';
        $validRowNumberPrefix = esc_html(sanitize_text_field($paramRowNumbersPrefix));
        $optionIds = $this->getAllIds($paramItemId);

        $i = 0;
        foreach($optionIds AS $optionId)
        {
            $i++;
            $objOption = new ItemOption($this->conf, $this->lang, $this->settings, $optionId);
            $optionDetails = $objOption->getDetails();
            $objItem = new Item($this->conf, $this->lang, $this->settings, $optionDetails['item_id']);
            $itemDetails = $objItem->getDetails();
            if(!is_null($itemDetails))
            {
                $partnerId = $itemDetails['partner_id'];
                $printOptionsMeasurementUnit = $itemDetails['print_options_measurement_unit'];
            } else
            {
                $partnerId = 0;
                $printOptionsMeasurementUnit = "";
            }

            $printTranslatedOptionName = $optionDetails['print_translated_option_name'];
            if($this->lang->canTranslateSQL())
            {
                $printTranslatedOptionName .= '<br /><span class="not-translated" title="'.$this->lang->getText('NRS_ADMIN_WITHOUT_TRANSLATION_TEXT').'">('.$optionDetails['print_option_name'].')</span>';
            }

            // HTML OUTPUT
            $optionList .= '<tr>';
            $optionList .= '<td>'.$validRowNumberPrefix.sprintf('%02d', $i).'</td>';
            $optionList .= '<td><strong>'.$printTranslatedOptionName.'</strong></td>';
            $optionList .= '<td><strong>'.$printOptionsMeasurementUnit.'</strong></td>';
            $optionList .= '<td align="right">';
            if($objOption->canEdit($partnerId))
            {
                $optionList .= '<a href="'.admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-item-option&amp;option_id='.$optionId).'">'.$this->lang->getText('NRS_ADMIN_EDIT_TEXT').'</a> || ';
                $optionList .= '<a href="'.admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-item-option&amp;delete_option='.$optionId.'&amp;noheader=true').'">'.$this->lang->getText('NRS_ADMIN_DELETE_TEXT').'</a>';
            } else
            {
                $optionList .= '--';
            }
            $optionList .= '</td>';
            $optionList .= '</tr>';
        }

        return  $optionList;
    }
}