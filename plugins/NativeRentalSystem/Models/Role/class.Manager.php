<?php
/**
 * Manager Role

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

class Manager extends AbstractElement implements iPrimitive
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
            'read'                                                          => true, // true allows this capability
            'view_'.$this->conf->getExtensionPrefix().'all_benefits'        => true, // use false to explicitly deny
            'manage_'.$this->conf->getExtensionPrefix().'all_benefits'      => true,
            'view_'.$this->conf->getExtensionPrefix().'all_inventory'       => true,
            'manage_'.$this->conf->getExtensionPrefix().'all_inventory'     => true,
            'view_'.$this->conf->getExtensionPrefix().'all_items'           => true,
            'manage_'.$this->conf->getExtensionPrefix().'all_items'         => true, // this allows only to add/edit/delete/block the item and it's options, not it's body types etc.
            'view_'.$this->conf->getExtensionPrefix().'own_items'           => true,
            'manage_'.$this->conf->getExtensionPrefix().'own_items'         => true,
            'view_'.$this->conf->getExtensionPrefix().'all_extras'          => true,
            'manage_'.$this->conf->getExtensionPrefix().'all_extras'        => true,
            'view_'.$this->conf->getExtensionPrefix().'own_extras'          => true,
            'manage_'.$this->conf->getExtensionPrefix().'own_extras'        => true,
            'view_'.$this->conf->getExtensionPrefix().'all_locations'       => true,
            'manage_'.$this->conf->getExtensionPrefix().'all_locations'     => true,
            'view_'.$this->conf->getExtensionPrefix().'all_bookings'        => true,
            'manage_'.$this->conf->getExtensionPrefix().'all_bookings'      => true,
            'view_'.$this->conf->getExtensionPrefix().'partner_bookings'    => true,
            'manage_'.$this->conf->getExtensionPrefix().'partner_bookings'  => true,
            'view_'.$this->conf->getExtensionPrefix().'all_customers'       => true,
            'manage_'.$this->conf->getExtensionPrefix().'all_customers'     => true,
            'view_'.$this->conf->getExtensionPrefix().'all_settings'        => true,
            'manage_'.$this->conf->getExtensionPrefix().'all_settings'      => true,
        );

        return $roleCapabilities;
    }

    public function add()
    {
        $roleResult = add_role($this->role, $this->lang->getText('NRS_MANAGER_ROLE_NAME_TEXT'), $this->getCapabilities());

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
        // Remove role if exist
        // Note: When a role is removed, the users who have this role lose all rights on the site.
        remove_role($this->role);

        // Get the admin role
        $role = get_role('administrator');

        // Remove all NRS capabilities from admin role
        $role->remove_cap('edit_others_posts');
    }
}