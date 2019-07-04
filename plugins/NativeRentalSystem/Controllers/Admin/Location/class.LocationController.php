<?php
/**
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Admin\Location;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Location\ClosedDatesObserver;
use NativeRentalSystem\Models\Location\Location;
use NativeRentalSystem\Models\Location\LocationsObserver;
use NativeRentalSystem\Models\Distance\DistancesObserver;
use NativeRentalSystem\Controllers\Admin\AbstractController;

final class LocationController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    public function getContent()
    {
        // Create mandatory instances
        $objLocationsObserver = new LocationsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objDistancesObserver = new DistancesObserver($this->conf, $this->lang, $this->dbSettings->getSettings());
        $objClosedDatesObserver = new ClosedDatesObserver($this->conf, $this->lang, $this->dbSettings->getSettings());

        $closedDatesArrays = array();
        // First - add closed dates for all locations
        $closedDatesArrays[] = array(
            'location_id' => '0',
            'closed_dates' => $objClosedDatesObserver->getClosedDates("", FALSE),
        );
        foreach($objLocationsObserver->getAllIds("ANY", -1) AS $locationId)
        {
            $objLocation = new Location($this->conf, $this->lang, $this->dbSettings->getSettings(), $locationId);
            $closedDatesArrays[] = array(
                'location_id' => $locationId,
                'closed_dates' => $objClosedDatesObserver->getClosedDates($objLocation->getCode(), FALSE),
            );
        }

        // Get the tab values
        $tabs = $this->getTabParams(array('locations', 'distances', 'closed-dates'), 'locations');

        // 1. Set the view variables - Tab settings
        $this->view->locationsTabChecked = !empty($tabs['locations']) ? ' checked="checked"' : '';
        $this->view->distancesTabChecked = !empty($tabs['distances']) ? ' checked="checked"' : '';
        $this->view->closedDaysTabChecked = !empty($tabs['closed-dates']) ? ' checked="checked"' : '';

        // 2. Set the view variables - locations
        $this->view->addNewLocationURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-location&location_id=0');
        $this->view->adminLocationsList = $objLocationsObserver->getAdminList();

        // 3. Set the view variables - distances
        $this->view->addNewDistanceURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-distance&distance_id=0');
        $this->view->adminDistancesList = $objDistancesObserver->getAdminList();

        // 4. Set the view variables - closed dates
        $this->view->ajaxSecurityNonce = wp_create_nonce($this->conf->getURLPrefix().'admin-ajax-nonce');
        $this->view->extensionFolder = $this->conf->getExtensionFolder();
        $this->view->locationDropDownOptions = $objLocationsObserver->getTranslatedLocationsDropDownOptions(
            "BOTH", 0, -1, -1, $this->lang->getText('NRS_ADMIN_ALL_LOCATIONS_TEXT')
        );
        $this->view->closedDatesArrays = $closedDatesArrays;

        // Get the template
        $retContent = $this->getTemplate('Location', 'LocationManager', 'Tabs');

        return $retContent;
    }
}
