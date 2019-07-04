<?php
/**
 * NRS Extras Observer (no setup for single item)

 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Extra;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Discount\ExtraDiscount;
use NativeRentalSystem\Models\Discount\ExtraDiscountsObserver;
use NativeRentalSystem\Models\iObserver;
use NativeRentalSystem\Models\Option\ExtraOption;
use NativeRentalSystem\Models\Option\ExtraOptionsObserver;
use NativeRentalSystem\Models\Tax\TaxManager;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Pricing\ExtraPriceManager;

class ExtrasObserver implements iObserver
{
    protected $conf 	                = NULL;
    protected $lang 		            = NULL;
    protected $settings		            = array();
    protected $debugMode 	            = 0;
    protected $depositsEnabled          = FALSE;

    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;
        // Set saved settings
        $this->settings = $paramSettings;

        if(isset($paramSettings['conf_deposit_enabled']))
        {
            // Set deposit status
            $this->depositsEnabled = StaticValidator::getValidPositiveInteger($paramSettings['conf_deposit_enabled'], 1) == 1 ? TRUE : FALSE;
        }
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    public function getIdBySKU($paramExtraSKU)
    {
        $retExtraId = 0;
        $validExtraSKU = esc_sql(sanitize_text_field($paramExtraSKU)); // For sql query only

        $extraData = $this->conf->getInternalWPDB()->get_row("
                SELECT extra_id
                FROM {$this->conf->getPrefix()}extras
                WHERE extra_sku='{$validExtraSKU}' AND blog_id='{$this->conf->getBlogId()}'
            ", ARRAY_A);
        if(!is_null($extraData))
        {
            $retExtraId = $extraData['extra_id'];
        }

        return $retExtraId;
    }

    /**
     * Get an extra which has amount > 0 of units in stock
     * @param int $paramPartnerId
     * @param int $paramExtraId
     * @param int $paramItemId
     * @return mixed
     */
    public function getAvailableIds($paramPartnerId = -1, $paramExtraId = -1, $paramItemId = -1)
    {
        return $this->getIds("AVAILABLE", $paramPartnerId, $paramExtraId, $paramItemId);
    }

    public function getAllIds($paramPartnerId = -1, $paramExtraId = -1, $paramItemId = -1)
    {
        return $this->getIds("ALL", $paramPartnerId, $paramExtraId, $paramItemId);
    }

    /**
     * @param string $displayMode - "ALL" or "AVAILABLE"
     * @param int $paramPartnerId
     * @param int $paramExtraId
     * @param int $paramItemId
     * @return array
     */
    private function getIds($displayMode = "ALL", $paramPartnerId = -1, $paramExtraId = -1, $paramItemId = -1)
    {
        $validPartnerId = StaticValidator::getValidInteger($paramPartnerId, -1);
        $validExtraId = StaticValidator::getValidInteger($paramExtraId, -1);
        $validItemId = StaticValidator::getValidInteger($paramItemId, -1);

        $sqlAdd = "";
        if($displayMode == "AVAILABLE")
        {
            $sqlAdd .= " AND units_in_stock > 0";
        }

        // Partner field
        if($validPartnerId >= 0)
        {
            $sqlAdd .= " AND partner_id='{$validPartnerId}'";
        }

        // Extra field
        if($validExtraId > 0)
        {
            $sqlAdd .= " AND extra_id='{$validExtraId}'";
        }

        // Item field
        if($validItemId >= 0)
        {
            $sqlAdd .= " AND item_id='{$validItemId}'";
        }

        $searchSQL = "
            SELECT extra_id
            FROM {$this->conf->getPrefix()}extras
            WHERE blog_id='{$this->conf->getBlogId()}' {$sqlAdd}
            ORDER BY extra_name ASC
		";

        //DEBUG
        //echo nl2br($searchSQL)."<br /><br />";

        $searchResult = $this->conf->getInternalWPDB()->get_col($searchSQL);

        return $searchResult;
    }

    /**
     * Delete corresponding extras by item id
     * @param $paramItemId
     * @return bool
     */
    public function explicitDeleteByItemId($paramItemId)
    {
        $retAllDeleted = TRUE;
        // Delete corresponding extras by item id
        $extraIds = $this->getAllIds(-1, -1, $paramItemId);
        foreach ($extraIds AS $extraId)
        {
            $objExtra = new Extra($this->conf, $this->lang, $this->settings, $extraId);
            $deleted = $objExtra->delete();
            if($deleted === FALSE || $deleted === 0)
            {
                $retAllDeleted = FALSE;
            } else
            {
                // Delete corresponding discounts
                $objDiscountsObserver = new ExtraDiscountsObserver($this->conf, $this->lang, $this->settings);
                $discountIds = $objDiscountsObserver->getAllIds("", $extraId);
                foreach ($discountIds AS $discountId)
                {
                    $objDiscount = new ExtraDiscount($this->conf, $this->lang, $this->settings, $discountId);
                    $objDiscount->delete();
                }

                // Delete corresponding extra options
                $objOptionsObserver = new ExtraOptionsObserver($this->conf, $this->lang, $this->settings);
                $optionIds = $objOptionsObserver->getAllIds($extraId);
                foreach ($optionIds AS $optionId)
                {
                    $objOption = new ExtraOption($this->conf, $this->lang, $this->settings, $optionId);
                    $objOption->delete();
                }
            }
        }

        return $retAllDeleted;
    }

    public function canShowOnlyPartnerOwned()
    {
        $canEditOwnExtras = current_user_can('manage_'.$this->conf->getExtensionPrefix().'own_extras');
        $canEditAllExtras = current_user_can('manage_'.$this->conf->getExtensionPrefix().'all_extras');
        $onlyPartnerOwned = $canEditOwnExtras == TRUE && $canEditAllExtras == FALSE;

        return $onlyPartnerOwned;
    }

    public function getTranslatedExtrasDropDownOptionsByPartnerId($paramPartnerId = -1, $paramSelectedExtraId = 0, $paramDefaultValue = "", $paramDefaultLabel = "", $paramShowExtraId = TRUE)
    {
        return $this->getExtrasDropDownOptions($paramSelectedExtraId, $paramDefaultValue, $paramDefaultLabel, $paramShowExtraId, TRUE, $paramPartnerId);
    }

    public function getExtrasDropDownOptionsByPartnerId($paramPartnerId = -1, $paramSelectedExtraId = 0, $paramDefaultValue = "", $paramDefaultLabel = "", $paramShowExtraId = TRUE)
    {
        return $this->getExtrasDropDownOptions($paramSelectedExtraId, $paramDefaultValue, $paramDefaultLabel, $paramShowExtraId, FALSE, $paramPartnerId);
    }

    public function getTranslatedExtrasDropDownOptions($paramSelectedExtraId = 0, $paramDefaultValue = "", $paramDefaultLabel = "", $paramShowExtraId = TRUE)
    {
        return $this->getExtrasDropDownOptions($paramSelectedExtraId, $paramDefaultValue, $paramDefaultLabel, $paramShowExtraId, TRUE, -1);
    }

    /**
     * @param int $paramSelectedExtraId
     * @param string $paramDefaultValue
     * @param string $paramDefaultLabel
     * @param bool $paramShowExtraId
     * @param bool $paramTranslated
     * @param int $paramPartnerId
     * @return string
     */
    public function getExtrasDropDownOptions($paramSelectedExtraId = 0, $paramDefaultValue = "", $paramDefaultLabel = "", $paramShowExtraId = TRUE, $paramTranslated = FALSE, $paramPartnerId = -1)
    {
        $printDefaultValue = esc_html(sanitize_text_field($paramDefaultValue));
        $printDefaultLabel = esc_html(sanitize_text_field($paramDefaultLabel));
        $extraHTML = '';
        if($paramDefaultValue != "" || $paramDefaultLabel != "")
        {
            $defaultSelected = $paramSelectedExtraId == $paramDefaultValue ? ' selected="selected"' : '';
            $extraHTML .= '<option value="'.$printDefaultValue.'"'.$defaultSelected.'>'.$printDefaultLabel.'</option>';
        }

        $extraIds = $this->getAllIds($paramPartnerId);
        foreach ($extraIds AS $extraId)
        {
            // Process extra details
            $objExtra = new Extra($this->conf, $this->lang, $this->settings, $extraId);
            $extraDetails = $objExtra->getDetailsWithItemAndPartner();

            if($paramTranslated)
            {
                $printTitle = $extraDetails['print_translated_extra_name_with_dependant_item'];
            } else
            {
                $printTitle = $extraDetails['print_extra_name_with_dependant_item'];
            }

            if($paramShowExtraId)
            {
                $printTitle .= " (ID=".$extraDetails['extra_id'].")";
            }
            if($paramSelectedExtraId == $extraDetails['extra_id'])
            {
                $extraHTML .= '<option value='.$extraDetails['extra_id'].' selected="selected">'.$printTitle.'</option>';
            } else
            {
                $extraHTML .= '<option value='.$extraDetails['extra_id'].'>'.$printTitle.'</option>';
            }
        }

        return $extraHTML;
    }


    /*******************************************************************************/
    /********************** METHODS FOR ADMIN ACCESS ONLY **************************/
    /*******************************************************************************/

    public function getAdminList()
    {
        $extraList = '';

        $objTaxManager = new TaxManager($this->conf, $this->lang, $this->settings);
        $taxPercentage = $objTaxManager->getTaxPercentage(0, 0);

        $extraIds = $this->getAllIds($this->canShowOnlyPartnerOwned() ? get_current_user_id() : -1);
        foreach($extraIds AS $extraId)
        {
            $objExtra = new Extra($this->conf, $this->lang, $this->settings, $extraId);
            $canEdit = $objExtra->canEdit();
            if($canEdit || current_user_can('view_'.$this->conf->getExtensionPrefix().'all_extras'))
            {
                $objPriceManager = new ExtraPriceManager($this->conf, $this->lang, $this->settings, $extraId, $taxPercentage);
                $extraDetails = $objExtra->getDetailsWithItemAndPartner();
                $extraPriceDetails = $objPriceManager->getMinimalPriceDetails();
                $extra = array_merge($extraDetails, $extraPriceDetails);

                $printTranslatedExtraName = $extraDetails['print_translated_extra_name_with_dependant_item'].' '.$extraDetails['print_via_partner'];
                if($this->lang->canTranslateSQL())
                {
                    $printTranslatedExtraName .= '<br /><span class="not-translated" title="'.$this->lang->getText('NRS_ADMIN_WITHOUT_TRANSLATION_TEXT').'">('.$extraDetails['print_extra_name'].')</span>';
                }

                $printUnitsRange  = '<span style="cursor:pointer;" title="'.$this->lang->getText('NRS_ADMIN_MAX_EXTRA_UNITS_PER_BOOKING_TEXT').'">'.$extra['max_units_per_booking'].'</span> / ';
                $printUnitsRange .= '<span style="cursor:pointer;font-weight:bold" title="'.$this->lang->getText('NRS_ADMIN_TOTAL_EXTRA_UNITS_IN_STOCK_TEXT').'">'.$extra['units_in_stock'].'</span> ';
                $extraList .= '<tr>';
                $extraList .= '<td>'.$extra['extra_id'].'</td>';
                $extraList .= '<td>'.$extra['print_extra_sku'].'</td>';
                $extraList .= '<td>'.$printTranslatedExtraName.'</td>';
                $extraList .= '<td>'.$printUnitsRange.'</td>';
                $extraList .= '<td>'.$extra['unit_print']['subtotal_price']." / ".$extra['time_extension_long_print'].'</td>';
                if($this->depositsEnabled)
                {
                    $extraList .= '<td>'.$extra['unit_print']['fixed_deposit_amount'].'</td>';
                }
                $extraList .= '<td align="right">';
                if($canEdit)
                {
                    $extraList .= '<a href="'.admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-extra&amp;extra_id='.$extraId).'">'.$this->lang->getText('NRS_ADMIN_EDIT_TEXT').'</a> || ';
                    $extraList .= '<a href="javascript:;" onclick="javascript:delete'.$this->conf->getExtensionFolder().'Extra(\''.$extraId.'\')">'.$this->lang->getText('NRS_ADMIN_DELETE_TEXT').'</a>';
                } else
                {
                    $extraList .= '--';
                }
                $extraList .= '</td>';
                $extraList .= '</tr>';
            }
        }

        return  $extraList;
    }
}