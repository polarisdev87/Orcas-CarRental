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
use NativeRentalSystem\Models\Distance\Distance;
use NativeRentalSystem\Models\Location\LocationsObserver;
use NativeRentalSystem\Controllers\Admin\AbstractController;

final class AddEditDistanceController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    private function processDelete($paramDistanceId)
    {
        $objDistance = new Distance($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramDistanceId);
        $objDistance->delete();

        $this->processDebugMessages($objDistance->getDebugMessages());
        $this->processOkayMessages($objDistance->getOkayMessages());
        $this->processErrorMessages($objDistance->getErrorMessages());

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'location-manager&tab=distances');
        exit;
    }

    private function processSave($paramDistanceId)
    {
        $objDistance = new Distance($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramDistanceId);
        $objDistance->save();

        $this->processDebugMessages($objDistance->getDebugMessages());
        $this->processOkayMessages($objDistance->getOkayMessages());
        $this->processErrorMessages($objDistance->getErrorMessages());

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'location-manager&tab=distances');
        exit;
    }


    public function getContent()
    {
        // Create mandatory instances
        $objLocationsObserver = new LocationsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());

        if(isset($_GET['delete_distance'])) { $this->processDelete($_GET['delete_distance']); }
        if(isset($_POST['save_distance']) && isset($_POST['distance_id'])) { $this->processSave($_POST['distance_id']); }

        $paramDistanceId = isset($_GET['distance_id']) ? $_GET['distance_id'] : 0;
        $objDistance = new Distance($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramDistanceId);
        $localDetails = $objDistance->getDetails();

        // Set the view variables
        $this->view->backToListURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'location-manager&tab=distances');
        $this->view->formAction = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-distance&noheader=true');
        if(!is_null($localDetails))
        {
            $this->view->distanceId = $localDetails['distance_id'];
            $this->view->showDistance = $localDetails['show_distance'] == 1 ? ' checked="checked"' : '';
            $this->view->distance = $localDetails['distance'];
            $this->view->distanceFee = $localDetails['distance_fee'];
            $this->view->pickupLocationsDropDownOptions = $objLocationsObserver->getTranslatedLocationsDropDownOptions(
                "BOTH", 0, $localDetails['pickup_location_id'], 0, $this->lang->getText('NRS_ADMIN_SELECT_LOCATION_TEXT')
            );
            $this->view->returnLocationsDropDownOptions = $objLocationsObserver->getTranslatedLocationsDropDownOptions(
                "BOTH", 0, $localDetails['return_location_id'], 0, $this->lang->getText('NRS_ADMIN_SELECT_LOCATION_TEXT')
            );
        } else
        {
            $this->view->distanceId = 0;
            $this->view->showDistance = ' checked="checked"';
            $this->view->distance = '';
            $this->view->distanceFee = '';
            $this->view->pickupLocationsDropDownOptions = $objLocationsObserver->getTranslatedLocationsDropDownOptions(
                "BOTH", 0, 0, 0, $this->lang->getText('NRS_ADMIN_SELECT_LOCATION_TEXT')
            );
            $this->view->returnLocationsDropDownOptions = $objLocationsObserver->getTranslatedLocationsDropDownOptions(
                "BOTH", 0, 0, 0, $this->lang->getText('NRS_ADMIN_SELECT_LOCATION_TEXT')
            );
        }

        // Get the template
        $retContent = $this->getTemplate('Location', 'AddEditDistance', 'Form');

        return $retContent;
    }
}
