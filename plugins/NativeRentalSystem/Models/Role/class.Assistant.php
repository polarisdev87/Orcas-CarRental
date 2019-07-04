<?php
/**
 * Assistant role

 * @note - It does not have settings param in constructor on purpose!
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Role;
use NativeRentalSystem\Models\AbstractElement;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\iPrimitive;
use NativeRentalSystem\Models\Language\Language;

class Assistant extends AbstractElement implements iPrimitive
{
	protected $conf         = NULL;
	protected $lang 		= NULL;
	protected $debugMode 	= 0;
	protected $role 	    = '';

	public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, $paramRole)
	{
		// Set class settings
		$this->conf = $paramConf;
		// Already sanitized before in it's constructor. Too much sanitation will kill the system speed
		$this->lang = $paramLang;

        $this->role = sanitize_key($paramRole);
	}

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    public function getId()
    {
        return $this->role;
    }

    public function getCapabilities()
    {
        $roleCapabilities = array(
            'read'                                                          => true,  // true allows this capability
            'view_'.$this->conf->getExtensionPrefix().'all_benefits'        => true,
            'manage_'.$this->conf->getExtensionPrefix().'all_benefits'      => false, // use false to explicitly deny
            'view_'.$this->conf->getExtensionPrefix().'all_inventory'       => true,
            'manage_'.$this->conf->getExtensionPrefix().'all_inventory'     => false,
            'view_'.$this->conf->getExtensionPrefix().'all_items'           => true,
            'manage_'.$this->conf->getExtensionPrefix().'all_items'         => false,
            'view_'.$this->conf->getExtensionPrefix().'own_items'           => true,
            'manage_'.$this->conf->getExtensionPrefix().'own_items'         => false,
            'view_'.$this->conf->getExtensionPrefix().'all_extras'          => true,
            'manage_'.$this->conf->getExtensionPrefix().'all_extras'        => false,
            'view_'.$this->conf->getExtensionPrefix().'own_extras'          => true,
            'manage_'.$this->conf->getExtensionPrefix().'own_extras'        => false,
            'view_'.$this->conf->getExtensionPrefix().'all_locations'       => true,
            'manage_'.$this->conf->getExtensionPrefix().'all_locations'     => false,
            'view_'.$this->conf->getExtensionPrefix().'all_bookings'        => true,
            'manage_'.$this->conf->getExtensionPrefix().'all_bookings'      => true,
            'view_'.$this->conf->getExtensionPrefix().'partner_bookings'    => true,
            'manage_'.$this->conf->getExtensionPrefix().'partner_bookings'  => true,
            'view_'.$this->conf->getExtensionPrefix().'all_customers'       => true,
            'manage_'.$this->conf->getExtensionPrefix().'all_customers'     => true,
            'view_'.$this->conf->getExtensionPrefix().'all_settings'        => false,
            'manage_'.$this->conf->getExtensionPrefix().'all_settings'      => false,
        );

        return $roleCapabilities;
    }

    public function add()
    {
        $roleResult = add_role($this->role, $this->lang->getText('NRS_ASSISTANT_ROLE_NAME_TEXT'), $this->getCapabilities());

        if($roleResult !== null)
        {
            // New role added!
            $newRoleAdded = TRUE;
        } else
        {
            // The selected role already exists
            $newRoleAdded = FALSE;
        }

        return $newRoleAdded;
    }

    public function remove()
    {
        // Remove roles if exist
        // Note: When a role is removed, the users who have this role lose all rights on the site.
        remove_role($this->role);
    }
}