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
use NativeRentalSystem\Models\iObserver;
use NativeRentalSystem\Models\Language\Language;

class ExtraBlocksObserver implements iObserver
{
    protected $conf 	            = NULL;
    protected $lang 		        = NULL;
    protected $settings             = array();
    protected $debugMode 	        = 0;

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

    /**
     * @note - We don't use a BLOG_ID parameter here, as we want to return items from all blog in network!
     * @return array
     */
    public function getAllIds()
    {
        $blockSQL = "
            SELECT bb.booking_id
            FROM {$this->conf->getPrefix()}bookings bb
            WHERE is_block='1'
            ORDER BY block_name ASC
        ";

        // DEBUG
        //echo nl2br($blockSQL);

        $blockIds = $this->conf->getInternalWPDB()->get_col($blockSQL);

        return $blockIds;
    }


    /*******************************************************************************/
    /********************** METHODS FOR ADMIN ACCESS ONLY **************************/
    /*******************************************************************************/

    public function getAdminList()
    {
        $getHtml = "";

        $blockIds = $this->getAllIds();
        foreach ($blockIds AS $blockId)
        {
            $objBlock = new Block($this->conf, $this->lang, $this->settings, $blockId);
            $canEdit = $objBlock->canEdit();
            if($canEdit || current_user_can('view_'.$this->conf->getExtensionPrefix().'all_bookings'))
            {
                $blockDetails = $objBlock->getDetails();
                foreach($blockDetails['extras'] AS $blockedExtra)
                {
                    $objExtra = new Extra($this->conf, $this->lang, $this->settings, $blockedExtra['extra_id']);
                    if($objExtra->canView())
                    {
                        $extraDetails = $objExtra->getDetailsWithItemAndPartner();
                        if($blockedExtra['units_blocked'] == -1)
                        {
                            $printUnitsBlocked = $this->lang->getText('NRS_ALL_TEXT');
                        } else
                        {
                            $printUnitsBlocked = $blockedExtra['units_blocked'].'/'.$extraDetails['units_in_stock'];
                        }

                        $getHtml .= '<tr>';
                        $getHtml .= '<td align="left">'.$blockDetails['print_block_name'].'</td>';
                        $getHtml .= '<td>'.$extraDetails['extra_id'].' / '.$extraDetails['print_extra_sku'].'</td>';
                        $getHtml .= '<td>'.$extraDetails['print_translated_extra_name_with_dependant_item'].' '.$extraDetails['print_via_partner'].'</td>';
                        $getHtml .= '<td>'.$printUnitsBlocked.'</td>';
                        $getHtml .= '<td style="white-space: nowrap;">';
                        $getHtml .= $this->lang->getText('NRS_FROM_TEXT').': '.$blockDetails['print_start_date'].' '.$blockDetails['print_start_time'].'<br />';
                        $getHtml .= $this->lang->getText('NRS_TO_TEXT').': '.$blockDetails['print_end_date'].' '.$blockDetails['print_end_time'];
                        $getHtml .= '</td>';
                        $getHtml .= '<td align="right">';
                        if($objExtra->canEdit())
                        {
                            $getHtml .= '<a href="'.admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-extra-block&amp;noheader=true&amp;unblock='.$blockId.'&amp;extra_id='.$extraDetails['extra_id']).'">'.$this->lang->getText('NRS_ADMIN_UNBLOCK_TEXT').'</a>';
                        } else
                        {
                            $getHtml .= '--';
                        }
                        $getHtml .= '</td>';
                        $getHtml .= '</tr>';
                    }
                }
            }
        }

        return $getHtml;
    }
}