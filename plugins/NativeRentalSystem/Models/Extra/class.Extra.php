<?php
/**
 * Extra Processor

 * @package NRS
 * @uses NRSDepositManager, NRSDiscountManager, NRSPrepaymentManager
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Extra;
use NativeRentalSystem\Models\AbstractElement;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\iElement;
use NativeRentalSystem\Models\iPartner;
use NativeRentalSystem\Models\Item\Item;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;

class Extra extends AbstractElement implements iElement, iPartner
{
    private $conf 	                = NULL;
    private $lang 		            = NULL;
    private $settings	            = array();
    private $debugMode 	            = 0;
    private $extraId                = 0;
    private $revealPartner          = TRUE;

    /**
     * Extra constructor.
     * @param ExtensionConfiguration &$paramConf
     * @param Language &$paramLang
     * @param array $paramSettings
     * @param int $paramExtraId
     */
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, array $paramSettings, $paramExtraId)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;
        // Set settings
        $this->settings = $paramSettings;

        if(isset($paramSettings['conf_reveal_partner']))
        {
            // Set reveal partner
            $this->revealPartner = $paramSettings['conf_reveal_partner'] == 1 ? TRUE : FALSE;
        }

        $this->extraId = StaticValidator::getValidValue($paramExtraId, 'positive_integer', 0);
    }

    /**
     * @param $paramExtraId
     * @return mixed
     */
    private function getDataFromDatabaseById($paramExtraId)
    {
        $validExtraId = StaticValidator::getValidPositiveInteger($paramExtraId, 0);
        $row = $this->conf->getInternalWPDB()->get_row("
            SELECT *
            FROM {$this->conf->getPrefix()}extras
            WHERE extra_id='{$validExtraId}'
        ", ARRAY_A);

        return $row;
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    public function getId()
    {
        return $this->extraId;
    }

    /**
     * Element-specific method
     * @return string
     */
    public function getSKU()
    {
        $retSKU = "";
        $ret = $this->getDataFromDatabaseById($this->extraId);
        if(!is_null($ret))
        {
            // Make raw
            $retSKU = stripslashes($ret['extra_sku']);
        }
        return $retSKU;
    }

    /**
     * Element-specific method
     * @return string
     */
    public function getPrintSKU()
    {
        return esc_html($this->getSKU());
    }

    /**
     * Element-specific method
     * @return string
     */
    public function getEditSKU()
    {
        return esc_attr($this->getSKU());
    }

    /**
     * Element-specific function
     * @return int
     */
    public function getPartnerId()
    {
        $retPartnerId = 0;
        $extraData = $this->getDataFromDatabaseById($this->extraId);
        if(!is_null($extraData))
        {
            $retPartnerId = $extraData['partner_id'];
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
        if($this->extraId > 0)
        {
            $partnerId = $this->getPartnerId();
            if(current_user_can('manage_'.$this->conf->getExtensionPrefix().'all_extras'))
            {
                $canEdit = TRUE;
            } else if($partnerId > 0 && $partnerId == get_current_user_id() && current_user_can('manage_'.$this->conf->getExtensionPrefix().'own_extras'))
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
        if($this->extraId > 0)
        {
            $partnerId = $this->getPartnerId();
            if(current_user_can('view_'.$this->conf->getExtensionPrefix().'all_extras'))
            {
                $canView = TRUE;
            } else if($partnerId > 0 && $partnerId == get_current_user_id() && current_user_can('view_'.$this->conf->getExtensionPrefix().'own_extras'))
            {
                $canView = TRUE;
            }
        }

        return $canView;
    }

    /**
     * Element specific method
     * @param bool $paramIncludeUnclassified - not used
     * @return mixed
     */
    public function getDetailsWithItemAndPartner($paramIncludeUnclassified = FALSE)
    {
        return $this->getAllDetails(TRUE);
    }

    public function getDetails($paramIncludeUnclassified = FALSE)
    {
        return $this->getAllDetails(FALSE);
    }

    /**
     * @param bool $paramIncludeItemAndPartner
     * @return mixed
     */
    private function getAllDetails($paramIncludeItemAndPartner = FALSE)
    {
        // For extras basic and full details are the same
        $ret = $this->getDataFromDatabaseById($this->extraId);

        if(!is_null($ret))
        {
            // Make raw
            $ret['extra_sku'] = stripslashes($ret['extra_sku']);
            $ret['extra_name'] = stripslashes($ret['extra_name']);
            $ret['options_measurement_unit'] = stripslashes($ret['options_measurement_unit']);

            // Retrieve translation
            $ret['translated_extra_name'] = $this->lang->getTranslated("ex{$ret['extra_id']}_extra_name", $ret['extra_name']);

            // Prepare output for print
            $ret['print_extra_sku'] = esc_html($ret['extra_sku']);
            $ret['print_extra_name'] = esc_html($ret['extra_name']);
            $ret['print_translated_extra_name'] = esc_html($ret['translated_extra_name']);
            $ret['print_options_measurement_unit'] = esc_html($ret['options_measurement_unit']);

            // Prepare output for edit
            $ret['edit_extra_sku'] = esc_attr($ret['extra_sku']); // for input field
            $ret['edit_extra_name'] = esc_attr($ret['extra_name']); // for input field
            $ret['edit_options_measurement_unit'] = esc_attr($ret['options_measurement_unit']); // for input field

            if($paramIncludeItemAndPartner == TRUE)
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
                ////////////////////////////////////////////////////////////////////////////////////
                // ITEM: START
                if($ret['item_id'] > 0)
                {
                    // Process dependant item basic details
                    $objDependantItem = new Item($this->conf, $this->lang, $this->settings, $ret['item_id']);
                    $dependantItemDetails = $objDependantItem->getExtendedDetails();

                    $dependantItemTitle = $dependantItemDetails['body_type_title'] ? $dependantItemDetails['body_type_title'].", " : "";
                    $dependantItemTitle .= $dependantItemDetails['manufacturer_title'].' '.$dependantItemDetails['model_name'];
                    $ret['extra_name_with_dependant_item'] = $ret['extra_name'].' '.sprintf($this->lang->getText('NRS_FOR_DEPENDANT_ITEM_TEXT'), $dependantItemTitle);

                    $translatedDependantItemTitle = $dependantItemDetails['translated_body_type_title'] ? $dependantItemDetails['translated_body_type_title'].", " : "";
                    $translatedDependantItemTitle .= $dependantItemDetails['translated_manufacturer_title'].' '.$dependantItemDetails['translated_model_name'];
                    $ret['translated_extra_name_with_dependant_item'] = $ret['translated_extra_name'].' '.sprintf($this->lang->getText('NRS_FOR_DEPENDANT_ITEM_TEXT'), $translatedDependantItemTitle);
                } else
                {
                    $ret['extra_name_with_dependant_item'] = $ret['extra_name'];
                    $ret['translated_extra_name_with_dependant_item'] = $ret['translated_extra_name'];
                }

                // Prepare output for print
                $ret['print_extra_name_with_dependant_item'] = esc_html($ret['extra_name_with_dependant_item']);
                $ret['print_translated_extra_name_with_dependant_item'] = esc_html($ret['translated_extra_name_with_dependant_item']);
                // ITEM: END
                ////////////////////////////////////////////////////////////////////////////////////
            }
        }

        return $ret;
    }

    public function generateSKU()
    {
        if($this->extraId > 0)
        {
            $extraSKU = $this->getSKU();
        } else
        {
            $nextInsertId = 1;
            $sqlQuery = "
                SHOW TABLE STATUS LIKE '{$this->conf->getPrefix()}extras'
            ";
            $data = $this->conf->getInternalWPDB()->get_row($sqlQuery, ARRAY_A);
            if(!is_null($data))
            {
                $nextInsertId = $data['Auto_increment'];

            }

            $extraSKU = 'EX_'.$nextInsertId;
        }

        return $extraSKU;
    }

    /**
     * @return bool|false|int
     */
    public function save()
    {
        $saved = FALSE;
        $ok = TRUE;
        $isManager = current_user_can('manage_'.$this->conf->getExtensionPrefix().'all_extras');

        $validExtraId = StaticValidator::getValidPositiveInteger($this->extraId, 0);
        // Do not use sanitize_key here, because we don't want to get it lowercase
        if($this->conf->isNetworkEnabled())
        {
            $sanitizedExtraSKU = isset($_POST['extra_sku']) ? sanitize_text_field($_POST['extra_sku']) : '';
        } else
        {
            $sanitizedExtraSKU = sanitize_text_field($validExtraId > 0 ? $this->getSKU() : $this->generateSKU());
        }
        $validExtraSKU = esc_sql($sanitizedExtraSKU); // for sql query only
        if($isManager)
        {
            // If that is a store manager - allow to define the partner
            $validPartnerId = isset($_POST['partner_id']) ? StaticValidator::getValidPositiveInteger($_POST['partner_id']) : 0;
        } else
        {
            // Otherwise - use current user id
            $validPartnerId = intval(get_current_user_id());
        }
        $validItemId = StaticValidator::getValidPositiveInteger($_POST['item_id'], 0);
        $sanitizedExtraName = sanitize_text_field($_POST['extra_name']);
        $validExtraName = esc_sql($sanitizedExtraName); // for sql query only
        $sanitizedMeasurementUnit = sanitize_text_field($_POST['options_measurement_unit']);
        $validMeasurementUnit = esc_sql($sanitizedMeasurementUnit); // for sql query only
        $validExtraUnitsInStock = StaticValidator::getValidPositiveInteger($_POST['units_in_stock'], 50);
        $validMaximumExtraUnitsPerBooking = StaticValidator::getValidPositiveInteger($_POST['max_units_per_booking'], 2);
        $validExtraPrice = floatval($_POST['price']);
        $validExtraPriceType = intval($_POST['price_type']);
        $validFixedRentalDeposit = floatval($_POST['fixed_rental_deposit']); // Allow negative deposits to drop item price
        $validOptionsDisplayMode = intval($_POST['options_display_mode']);

        // Validations
        $skuExistsQuery = "
            SELECT extra_id
            FROM {$this->conf->getPrefix()}extras
            WHERE extra_sku='{$validExtraSKU}'
            AND extra_id!='{$validExtraId}' AND blog_id='{$this->conf->getBlogId()}'
        ";
        $skuExists = $this->conf->getInternalWPDB()->get_row($skuExistsQuery, ARRAY_A);
        if(!is_null($skuExists))
        {
            $ok = FALSE;
            $this->errorMessages[] = $this->lang->getText('NRS_EXTRA_SKU_EXISTS_ERROR_TEXT');
        }
        if($validMaximumExtraUnitsPerBooking > $validExtraUnitsInStock)
        {
            $ok = FALSE;
            $this->errorMessages[] = $this->lang->getText('NRS_EXTRA_MORE_UNITS_PER_BOOKING_THAN_IN_STOCK_ERROR_TEXT');
        }

        if($validItemId > 0)
        {
            $itemExists = $this->conf->getInternalWPDB()->get_row("
                    SELECT item_id, partner_id
                    FROM {$this->conf->getPrefix()}items
                    WHERE item_id='{$validItemId}' AND blog_id='{$this->conf->getBlogId()}'
                ", ARRAY_A);
            if(is_null($itemExists))
            {
                $ok = FALSE;
                $this->errorMessages[] = $this->lang->getText('NRS_EXTRA_ITEM_DOES_NOT_EXIST_ERROR_TEXT');
            } else
            {
                $canAssignChosenItem = ($itemExists['partner_id'] == get_current_user_id() || $isManager) ? TRUE : FALSE;
                if($canAssignChosenItem == FALSE)
                {
                    $ok = FALSE;
                    $this->errorMessages[] = $this->lang->getText('NRS_EXTRA_ITEM_ASSIGN_ERROR_TEXT');
                }
            }
        } else
        {
            // Only store managers can add extras without selected item
            if($isManager == FALSE)
            {
                $ok = FALSE;
                $this->errorMessages[] = $this->lang->getText('NRS_EXTRA_ITEM_SELECT_ERROR_TEXT');
            }
        }

        if($validExtraId > 0 && $ok)
        {
            $updateQuery = "
                UPDATE {$this->conf->getPrefix()}extras SET
                extra_sku='{$validExtraSKU}',
                partner_id='{$validPartnerId}',
                item_id='{$validItemId}',
                extra_name='{$validExtraName}',
                price='{$validExtraPrice}', price_type='{$validExtraPriceType}',
                fixed_rental_deposit='{$validFixedRentalDeposit}',
                units_in_stock='{$validExtraUnitsInStock}',
                max_units_per_booking='{$validMaximumExtraUnitsPerBooking}',
                options_display_mode='{$validOptionsDisplayMode}', options_measurement_unit='{$validMeasurementUnit}'
                WHERE extra_id='{$validExtraId}' AND blog_id='{$this->conf->getBlogId()}'
            ";

            $saved = $this->conf->getInternalWPDB()->query($updateQuery);

            if($saved === FALSE)
            {
                $this->errorMessages[] = $this->lang->getText('NRS_EXTRA_UPDATE_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('NRS_EXTRA_UPDATED_TEXT');
            }
        } else if($ok)
        {
            $insertQuery = "
                INSERT INTO {$this->conf->getPrefix()}extras
                (
                    extra_sku, partner_id, item_id, extra_name, price,
                    price_type, fixed_rental_deposit,
                    units_in_stock, max_units_per_booking,
                    options_display_mode, options_measurement_unit, blog_id
                ) VALUES
                (
                    '{$validExtraSKU}', '{$validPartnerId}', '{$validItemId}', '{$validExtraName}', '{$validExtraPrice}',
                    '{$validExtraPriceType}', '{$validFixedRentalDeposit}',
                    '{$validExtraUnitsInStock}', '{$validMaximumExtraUnitsPerBooking}',
                    '{$validOptionsDisplayMode}', '{$validMeasurementUnit}', '{$this->conf->getBlogId()}'
                )
            ";
            $saved = $this->conf->getInternalWPDB()->query($insertQuery);

            if($saved)
            {
                // Update object id for future use
                $this->extraId = $this->conf->getInternalWPDB()->insert_id;;
            }

            if($saved === FALSE || $saved === 0)
            {
                $this->errorMessages[] = $this->lang->getText('NRS_EXTRA_INSERT_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('NRS_EXTRA_INSERTED_TEXT');
            }
        }

        return $saved;
    }

    public function registerForTranslation()
    {
        $extraDetails = $this->getDetails();
        if(!is_null($extraDetails))
        {
            $this->lang->register("ex{$this->extraId}_extra_name", $extraDetails['extra_name']);
            $this->okayMessages[] = $this->lang->getText('NRS_EXTRA_REGISTERED_TEXT');
        }
    }

    /**
     * @return false|int
     */
    public function delete()
    {
        $validExtraId = StaticValidator::getValidPositiveInteger($this->extraId, 0);
        $deleted = $this->conf->getInternalWPDB()->query("
            DELETE FROM {$this->conf->getPrefix()}extras
            WHERE extra_id='{$validExtraId}' AND blog_id='{$this->conf->getBlogId()}'
        ");

        if($deleted === FALSE || $deleted === 0)
        {
            $this->errorMessages[] = $this->lang->getText('NRS_EXTRA_DELETE_ERROR_TEXT');
        } else
        {
            $this->okayMessages[] = $this->lang->getText('NRS_EXTRA_DELETED_TEXT');
        }

        return $deleted;
    }

    /*******************************************************************************/
    /************************* ELEMENT SPECIFIC FUNCTIONS **************************/
    /*******************************************************************************/

    /**
     * @param int $paramSelectedPriceTypeId
     * @return string
     */
    public function getPriceTypesDropDownOptions($paramSelectedPriceTypeId = 0)
    {
        $extraPriceTypeHTML = '<option value="0"'.($paramSelectedPriceTypeId == 0 ? ' selected="selected"' : '').'>'.$this->lang->getText('NRS_ADMIN_PER_BOOKING_TEXT').'</option>';
        $extraPriceTypeHTML .= '<option value="1"'.($paramSelectedPriceTypeId == 1 ? ' selected="selected"' : '').'>'.$this->lang->getText('NRS_ADMIN_DAILY_TEXT').'</option>';
        $extraPriceTypeHTML .= '<option value="2"'.(in_array($paramSelectedPriceTypeId, array(2,3)) ? ' selected="selected"' : '').'>'.$this->lang->getText('NRS_ADMIN_HOURLY_TEXT').'</option>';

        return $extraPriceTypeHTML;
    }
}