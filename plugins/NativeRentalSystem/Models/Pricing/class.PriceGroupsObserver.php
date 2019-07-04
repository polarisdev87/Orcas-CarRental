<?php
/**
 * NRS Item Deposits Observer (no setup for single item)

 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Pricing;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\iObserver;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;

class PriceGroupsObserver implements iObserver
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

    public function getAllIds($paramPartnerId = -1)
    {
        $validPartnerId = StaticValidator::getValidInteger($paramPartnerId, -1);

        $sqlAdd = "";
        if($validPartnerId >= 0)
        {
            $sqlAdd = "AND partner_id='{$validPartnerId}'";
        }

        $sqlQuery = "
            SELECT price_group_id
            FROM {$this->conf->getPrefix()}price_groups
            WHERE blog_id='{$this->conf->getBlogId()}' {$sqlAdd}
            ORDER BY price_group_name ASC
        ";

        $ids = $this->conf->getInternalWPDB()->get_col($sqlQuery);

        return $ids;
    }

    public function canShowOnlyPartnerOwned()
    {
        $canEditOwnItems = current_user_can('manage_'.$this->conf->getExtensionPrefix().'own_items');
        $canEditAllItems = current_user_can('manage_'.$this->conf->getExtensionPrefix().'all_items');
        $onlyPartnerOwned = $canEditOwnItems == TRUE && $canEditAllItems == FALSE;

        return $onlyPartnerOwned;
    }

    public function getTranslatedDropDownOptionsByPartnerId($paramPartnerId, $paramSelectedPriceGroupId = 0, $paramDefaultValue = "", $paramDefaultLabel = "", $paramShowPriceGroupId = TRUE)
    {
        return $this->getDropDownOptions($paramSelectedPriceGroupId, $paramDefaultValue, $paramDefaultLabel, $paramShowPriceGroupId, TRUE, $paramPartnerId);
    }

    public function getDropDownOptionsByPartnerId($paramPartnerId, $paramSelectedPriceGroupId = 0, $paramDefaultValue = "", $paramDefaultLabel = "", $paramShowPriceGroupId = TRUE)
    {
        return $this->getDropDownOptions($paramSelectedPriceGroupId, $paramDefaultValue, $paramDefaultLabel, $paramShowPriceGroupId, FALSE, $paramPartnerId);
    }

    public function getTranslatedDropDownOptions($paramSelectedPriceGroupId = 0, $paramDefaultValue = "", $paramDefaultLabel = "", $paramShowPriceGroupId = TRUE)
    {
        return $this->getDropDownOptions($paramSelectedPriceGroupId, $paramDefaultValue, $paramDefaultLabel, $paramShowPriceGroupId, TRUE, -1);
    }

    /**
     * @param int $paramSelectedPriceGroupId
     * @param int $paramDefaultValue
     * @param string $paramDefaultLabel
     * @param bool $paramShowPriceGroupId
     * @param bool $paramTranslated
     * @param int $paramPartnerId
     * @return string
     */
    public function getDropDownOptions($paramSelectedPriceGroupId = 0, $paramDefaultValue = 0, $paramDefaultLabel = "", $paramShowPriceGroupId = TRUE, $paramTranslated = FALSE, $paramPartnerId = -1)
    {
        $validDefaultValue = StaticValidator::getValidPositiveInteger($paramDefaultValue, 0);
        $sanitizedDefaultLabel = sanitize_text_field($paramDefaultLabel);
        $defaultSelected = $paramSelectedPriceGroupId == $validDefaultValue ? ' selected="selected"' : '';
        $priceGroupHTML = '<option value="'.$validDefaultValue.'"'.$defaultSelected.'>'.$sanitizedDefaultLabel.'</option>';

        $priceGroupIds = $this->getAllIds($paramPartnerId);
        foreach ($priceGroupIds AS $priceGroupId)
        {
            // Process full item details
            $objPriceGroup = new PriceGroup($this->conf, $this->lang, $this->settings, $priceGroupId);
            $priceGroupDetails = $objPriceGroup->getDetailsWithPartner();

            $printTitle = $priceGroupDetails[$paramTranslated ? 'print_translated_price_group_name' : 'print_price_group_name'];
            $printTitle .= ' '.$priceGroupDetails['print_via_partner'];
            if($paramShowPriceGroupId)
            {
                $printTitle .= " (ID=".$priceGroupDetails['price_group_id'].")";
            }
            $selected = $paramSelectedPriceGroupId == $priceGroupDetails['price_group_id'] ? ' selected="selected"' : '';

            $priceGroupHTML .= '<option value="'.$priceGroupDetails['price_group_id'].'"'.$selected.'>'.$printTitle.'</option>';
        }

        return $priceGroupHTML;
    }


    /*******************************************************************************/
    /********************** METHODS FOR ADMIN ACCESS ONLY **************************/
    /*******************************************************************************/

	public function getAdminList()
	{
        $getHtml = '';

        $priceGroupIds = $this->getAllIds($this->canShowOnlyPartnerOwned() ? get_current_user_id() : -1);
		foreach($priceGroupIds AS $priceGroupId)
		{
            $objPriceGroup = new PriceGroup($this->conf, $this->lang, $this->settings, $priceGroupId);
            $priceGroupDetails = $objPriceGroup->getDetailsWithPartner();
            $printTranslatedPriceGroupName = $priceGroupDetails['print_translated_price_group_name'].' '.$priceGroupDetails['print_via_partner'];
            if($this->lang->canTranslateSQL())
            {
                $printTranslatedPriceGroupName .= '<br /><span class="not-translated" title="'.$this->lang->getText('NRS_ADMIN_WITHOUT_TRANSLATION_TEXT').'">('.$priceGroupDetails['print_price_group_name'].')</span>';
            }

			// HTML OUTPUT
			$getHtml .= '<tr>';
			$getHtml .= '<td>'.$priceGroupId.'</td>';
			$getHtml .= '<td>'.$printTranslatedPriceGroupName.'</td>';
            $getHtml .= '<td align="right">';
            if($objPriceGroup->canEdit())
            {
                $getHtml .= '<a href="'.admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-price-group&amp;price_group_id='.$priceGroupId).'">'.$this->lang->getText('NRS_ADMIN_EDIT_TEXT').'</a> || ';
                $getHtml .= '<a href="javascript:;" onclick="javascript:delete'.$this->conf->getExtensionFolder().'PriceGroup(\''.$priceGroupId.'\')">'.$this->lang->getText('NRS_ADMIN_DELETE_TEXT').'</a>';
            } else
            {
                $getHtml .= '--';
            }
            $getHtml .= '</td>';
            $getHtml .= '</tr>';

		}
		return  $getHtml;
	}
}