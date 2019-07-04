<?php
/**
 * Deposit processor. Used in administration side only

 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Pricing;
use NativeRentalSystem\Models\AbstractElement;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\iElement;
use NativeRentalSystem\Models\iPartner;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;

class PriceGroup extends AbstractElement implements iElement, iPartner
{
    protected $conf 	    = NULL;
    protected $lang 		= NULL;
    protected $debugMode 	= 0;
    protected $priceGroupId = 0;
    protected $revealPartner = TRUE;

    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings, $paramPriceGroupId)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;

        // Set price group id
        $this->priceGroupId = StaticValidator::getValidPositiveInteger($paramPriceGroupId, 0);

        if(isset($paramSettings['conf_reveal_partner']))
        {
            // Set reveal partner
            $this->revealPartner = sanitize_text_field($paramSettings['conf_reveal_partner']);
        }
    }

    /**
     * For internal class use only
     * @param $paramPriceGroupId
     * @return mixed
     */
    private function getDataFromDatabaseById($paramPriceGroupId)
    {
        $validPriceGroupId = StaticValidator::getValidPositiveInteger($paramPriceGroupId, 0);
        $priceGroupData = $this->conf->getInternalWPDB()->get_row("
            SELECT *
            FROM {$this->conf->getPrefix()}price_groups
            WHERE price_group_id='{$validPriceGroupId}'
        ", ARRAY_A);

        return $priceGroupData;
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    public function getId()
    {
        return $this->priceGroupId;
    }

    /**
     * @return int
     */
    public function getPartnerId()
    {
        $retPartnerId = 0;
        $priceGroupData = $this->getDataFromDatabaseById($this->priceGroupId);
        if(!is_null($priceGroupData))
        {
            $retPartnerId = $priceGroupData['partner_id'];
        }
        return $retPartnerId;
    }

    /**
     * Checks if current user can edit the element
     * @return bool
     */
    public function canEdit()
    {
        $canEdit = FALSE;
        if($this->priceGroupId > 0)
        {
            $partnerId = $this->getPartnerId();
            if(current_user_can('manage_'.$this->conf->getExtensionPrefix().'all_items'))
            {
                $canEdit = TRUE;
            } else if($partnerId > 0 && $partnerId == get_current_user_id() && current_user_can('manage_'.$this->conf->getExtensionPrefix().'own_items'))
            {
                $canEdit = TRUE;
            }
        }

        return $canEdit;
    }

    /**
     * Checks if current user can view the element
     * @return bool
     */
    public function canView()
    {
        $canView = FALSE;
        if($this->priceGroupId > 0)
        {
            $partnerId = $this->getPartnerId();
            if(current_user_can('view_'.$this->conf->getExtensionPrefix().'all_items'))
            {
                $canView = TRUE;
            } else if($partnerId > 0 && $partnerId == get_current_user_id() && current_user_can('view_'.$this->conf->getExtensionPrefix().'own_items'))
            {
                $canView = TRUE;
            }
        }

        return $canView;
    }

    /**
     * @param bool $paramIncludeUnclassified - NOT USED
     * @return mixed
     */
    public function getDetails($paramIncludeUnclassified = FALSE)
    {
        return $this->getAllDetails(FALSE);
    }

    /**
     * Element specific function
     * @param bool $paramIncludeUnclassified - NOT USED
     * @return mixed
     */
    public function getDetailsWithPartner($paramIncludeUnclassified = FALSE)
    {
        return $this->getAllDetails(TRUE);
    }

    private function getAllDetails($paramWithPartner = FALSE)
    {
        $ret = $this->getDataFromDatabaseById($this->priceGroupId);
        if(!is_null($ret))
        {
            // Make raw
            $ret['price_group_name'] = stripslashes($ret['price_group_name']);

            // Retrieve translation
            $ret['translated_price_group_name'] = $this->lang->getTranslated("pg{$ret['price_group_id']}_price_group_name", $ret['price_group_name']);

            // Make output for print
            $ret['print_price_group_name'] = esc_html($ret['price_group_name']);
            $ret['print_translated_price_group_name'] = esc_html($ret['translated_price_group_name']);

            if($paramWithPartner == TRUE)
            {
                if($this->revealPartner && $ret['partner_id'] > 0)
                {
                    $printPartnerName = get_the_author_meta('display_name', $ret['partner_id']);
                    $printViaPartner = sprintf($this->lang->getText('NRS_VIA_PARTNER_TEXT'), $printPartnerName);
                    $partnerProfileURL = get_author_posts_url($ret['partner_id']);
                    $printPartnerLink = '<a href="'.$partnerProfileURL.'"><span class="partner-name">'.$printPartnerName.'</span></a>';
                    $printViaPartnerLink = sprintf($this->lang->getText('NRS_VIA_PARTNER_TEXT'), $printPartnerLink);
                    $ret['print_partner_name'] = $printPartnerName;
                    $ret['partner_profile_url'] = $partnerProfileURL;
                    $ret['print_partner_link'] = $printPartnerLink;
                    $ret['print_via_partner'] = '('.$printViaPartner.')';
                    $ret['print_via_partner_link'] = '('.$printViaPartnerLink.')';
                } else
                {
                    $ret['print_partner_name'] = '';
                    $ret['partner_profile_url'] = '';
                    $ret['print_partner_link'] = '';
                    $ret['print_via_partner'] = '';
                    $ret['print_via_partner_link'] = '';
                }
            }

            // Prepare output for edit
            $ret['edit_price_group_name'] = esc_attr($ret['price_group_name']); // for input field
        }

        return $ret;
    }

    /**
     * @return bool|false|int
     */
    public function save()
    {
        $saved = FALSE;
        $ok = TRUE;
        $isManager = current_user_can('manage_'.$this->conf->getExtensionPrefix().'all_items');
        $validPriceGroupId = StaticValidator::getValidPositiveInteger($this->priceGroupId, 0);
        $sanitizedPriceGroupName = isset($_POST['price_group_name']) ? sanitize_text_field($_POST['price_group_name']) : '';;
        $validPriceGroupName = esc_sql($sanitizedPriceGroupName); // for sql query only

        if($isManager)
        {
            // If that is a store manager - allow to define the partner
            $validPartnerId = isset($_POST['partner_id']) ? StaticValidator::getValidPositiveInteger($_POST['partner_id'], 0) : 0;
        } else
        {
            // Otherwise - use current user id
            $validPartnerId = intval(get_current_user_id());
        }

        if($validPriceGroupId > 0 && $ok)
        {
            $updateQuery = "
                UPDATE {$this->conf->getPrefix()}price_groups SET
                price_group_name='{$validPriceGroupName}', partner_id='{$validPartnerId}'
                WHERE price_group_id='{$validPriceGroupId}' AND blog_id='{$this->conf->getBlogId()}'
            ";

            $saved = $this->conf->getInternalWPDB()->query($updateQuery);
            if($saved === FALSE)
            {
                $this->errorMessages[] = $this->lang->getText('NRS_PRICE_GROUP_UPDATE_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('NRS_PRICE_GROUP_UPDATED_TEXT');
            }
        } else if($ok)
        {
            $insertQuery = "
                INSERT INTO {$this->conf->getPrefix()}price_groups
                (
                    price_group_name, partner_id, blog_id
                ) VALUES
                (
                    '{$validPriceGroupName}', '{$validPartnerId}', '{$this->conf->getBlogId()}'
                )
            ";
            $saved = $this->conf->getInternalWPDB()->query($insertQuery);

            if($saved)
            {
                // Get newly inserted price group id
                $validInsertedNewPriceGroupId = $this->conf->getInternalWPDB()->insert_id;

                // Update object id with newly inserted id for future work
                $this->priceGroupId = $validInsertedNewPriceGroupId;

                // Add default price plan
                $this->conf->getInternalWPDB()->query("
                    INSERT INTO {$this->conf->getPrefix()}price_plans
                    (
                        price_group_id, coupon_code, start_timestamp, end_timestamp,
                        daily_rate_mon, daily_rate_tue, daily_rate_wed, daily_rate_thu, daily_rate_fri, daily_rate_sat, daily_rate_sun,
                        hourly_rate_mon, hourly_rate_tue, hourly_rate_wed, hourly_rate_thu, hourly_rate_fri, hourly_rate_sat, hourly_rate_sun,
                        seasonal_price, blog_id
                    ) VALUES
                    (
                        '{$validInsertedNewPriceGroupId}', '', '0', '0',
                        '0.00','0.00','0.00','0.00','0.00','0.00','0.00',
                        '0.00','0.00','0.00','0.00','0.00','0.00','0.00',
                        '0', '{$this->conf->getBlogId()}'
                    )
                ");
            }

            if($saved === FALSE || $saved === 0)
            {
                $this->errorMessages[] = $this->lang->getText('NRS_PRICE_GROUP_INSERT_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('NRS_PRICE_GROUP_INSERTED_TEXT');
            }
        }

        return $saved;
    }

    public function registerForTranslation()
    {
        $priceGroupDetails = $this->getDetails();
        if(!is_null($priceGroupDetails))
        {
            $this->lang->register("pg{$this->priceGroupId}_price_group_name", $priceGroupDetails['price_group_name']);
            $this->okayMessages[] = $this->lang->getText('NRS_PRICE_GROUP_REGISTERED_TEXT');
        }
    }

    public function delete()
    {
        $validPriceGroupId = StaticValidator::getValidPositiveInteger($this->priceGroupId);

        // Allowed to delete
        $deleted = $this->conf->getInternalWPDB()->query("
            DELETE FROM {$this->conf->getPrefix()}price_groups
            WHERE price_group_id='{$validPriceGroupId}' AND blog_id='{$this->conf->getBlogId()}'
        ");

        if($deleted === FALSE || $deleted === 0)
        {
            $this->errorMessages[] = $this->lang->getText('NRS_PRICE_GROUP_DELETE_ERROR_TEXT');
        } else
        {
            $this->okayMessages[] = $this->lang->getText('NRS_PRICE_GROUP_DELETED_TEXT');
        }

        return $deleted;
    }
}