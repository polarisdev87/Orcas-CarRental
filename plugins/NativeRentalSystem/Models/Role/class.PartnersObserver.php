<?php
/**
 * NRS Initializer class to setup, update, install and load templates for plugin

 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Role;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\iObserver;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Validation\StaticValidator;

class PartnersObserver implements iObserver
{
	protected $conf             = NULL;
	protected $lang 		    = NULL;
    protected $settings 	    = array();
	protected $debugMode 	    = 0;

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
     * @param int $paramSelectedPartnerId
     * @param int $paramDefaultValue
     * @param string $paramDefaultLabel
     * @return string
     */
    public function getDropDownOptions($paramSelectedPartnerId = -1, $paramDefaultValue = -1, $paramDefaultLabel = "")
    {
        $partnerHTML = '';
        $validDefaultValue = StaticValidator::getValidInteger($paramDefaultValue, -1);
        $validDefaultLabel = esc_html(sanitize_text_field($paramDefaultLabel));
        $defaultSelected = $paramSelectedPartnerId == $validDefaultValue ? ' selected="selected"' : '';
        $partnerHTML .= '<option value="'.$validDefaultValue.'"'.$defaultSelected.'>'.$validDefaultLabel.'</option>';
        $arrPartners = get_users( array( 'role' => $this->conf->getExtensionPrefix().'partner' ) );
        // Array of WP_User objects.
        foreach($arrPartners AS $partner)
        {
            $validDBPartnerId = intval($partner->ID);
            $printPartnerDisplayName = esc_html($partner->display_name);
            if($validDBPartnerId == $paramSelectedPartnerId)
            {
                $partnerHTML .= '<option value="'.$validDBPartnerId.'" selected="selected">'.$printPartnerDisplayName.'</option>';
            } else
            {
                $partnerHTML .= '<option value="'.$validDBPartnerId.'">'.$printPartnerDisplayName.'</option>';
            }
        }
        return $partnerHTML;
    }
}