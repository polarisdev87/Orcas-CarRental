<?php
/**
 * NRS Benefits Observer

 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Benefit;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\iObserver;
use NativeRentalSystem\Models\Language\Language;

class BenefitsObserver implements iObserver
{
    protected $conf 	                = NULL;
    protected $lang 		            = NULL;
    protected $settings		            = array();
    protected $debugMode 	            = 0;

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

    public function getAllIds()
    {
        $searchSQL = "
            SELECT benefit_id
            FROM {$this->conf->getPrefix()}benefits
            WHERE blog_id='{$this->conf->getBlogId()}'
            ORDER BY benefit_order ASC, benefit_title ASC
		";

        //DEBUG
        //echo nl2br($searchSQL)."<br /><br />";

        $searchResult = $this->conf->getInternalWPDB()->get_col($searchSQL);

        return $searchResult;
    }

    /*******************************************************************************/
    /********************** METHODS FOR ADMIN ACCESS ONLY **************************/
    /*******************************************************************************/

    public function getAdminList()
    {
        $benefitsHTML = '';
        $benefitIds = $this->getAllIds();
        foreach ($benefitIds AS $benefitId)
        {
            $objBenefit = new Benefit($this->conf, $this->lang, $this->settings, $benefitId);
            $benefitDetails = $objBenefit->getDetails();

            $printTranslatedBenefitTitle = $benefitDetails['print_translated_benefit_title'];
            if($this->lang->canTranslateSQL())
            {
                $printTranslatedBenefitTitle .= '<br /><span class="not-translated" title="'.$this->lang->getText('NRS_ADMIN_WITHOUT_TRANSLATION_TEXT').'">('.$benefitDetails['print_benefit_title'].')</span>';
            }

            $benefitsHTML .= '<tr>';
            $benefitsHTML .= '<td>'.$benefitId.'</td>';
            $benefitsHTML .= '<td>'.$printTranslatedBenefitTitle.'</td>';
            $benefitsHTML .= '<td style="text-align: center">'.$benefitDetails['benefit_order'].'</td>';
            $benefitsHTML .= '<td align="right">';
            if(current_user_can('manage_'.$this->conf->getExtensionPrefix().'all_inventory'))
            {
                $benefitsHTML .= '<a href="'.admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-benefit&amp;benefit_id='.$benefitId).'">'.$this->lang->getText('NRS_ADMIN_EDIT_TEXT').'</a> || ';
                $benefitsHTML .= '<a href="javascript:;" onclick="javascript:delete'.$this->conf->getExtensionFolder().'Benefit(\''.$benefitId.'\')">'.$this->lang->getText('NRS_ADMIN_DELETE_TEXT').'</a>';
            } else
            {
                $benefitsHTML .= '--';
            }
            $benefitsHTML .= '</td>';
            $benefitsHTML .= '</tr>';
        }

        return  $benefitsHTML;
    }
}