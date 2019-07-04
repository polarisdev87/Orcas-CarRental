<?php
/**
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Admin\Item;
use NativeRentalSystem\Models\Block\Block;
use NativeRentalSystem\Models\Booking\ItemBookingOption;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Formatting\StaticFormatter;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Item\Item;
use NativeRentalSystem\Models\Location\Location;
use NativeRentalSystem\Models\Location\LocationsObserver;
use NativeRentalSystem\Models\Block\ItemBlockManager;
use NativeRentalSystem\Controllers\Admin\AbstractController;

final class AddEditBlockController extends AbstractController
{
    private $objBlockManager = NULL;

    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
        $this->objBlockManager = new ItemBlockManager($this->conf, $this->lang, $this->dbSettings->getSettings());

        // First - process request
        $this->processRequest();
    }

    private function processRequest()
    {
        // Second - set the object variables
        $this->objBlockManager->setVariablesByRequestOrSessionParams();

        // Second: Validate Time input
        $this->objBlockManager->validateTimeInput($this->objBlockManager->getStartTimestamp(), $this->objBlockManager->getEndTimestamp());

        // Data defined successfully, now remove session variables
        $this->objBlockManager->removeSessionVariables();

        // Set fresh session variables
        $this->objBlockManager->setMySessionVars();
    }

    private function processBlock($paramBlockName)
    {
        $objBlock = new Block($this->conf, $this->lang, $this->dbSettings->getSettings(), 0);
        $arrItems = $this->objBlockManager->getSelectedWithDetails($this->objBlockManager->getIds());

        // If there is items to block
        if(sizeof($arrItems) > 0)
        {
            // Then create the block
            $objLocation = new Location($this->conf, $this->lang, $this->dbSettings->getSettings(), $this->objBlockManager->getLocationId());
            $locationCode = $objLocation->getCode();

            $blocked = $objBlock->save(
                $paramBlockName, $locationCode, $this->objBlockManager->getStartTimestamp(), $this->objBlockManager->getEndTimestamp()
            );
            if($blocked)
            {
                $blockId = $objBlock->getId();

                foreach($arrItems AS $item)
                {
                    $objItem = new Item($this->conf, $this->lang, $this->dbSettings->getSettings(), $item['item_id']);
                    if($objItem->canEdit())
                    {
                        $objBookingOption = new ItemBookingOption($this->conf, $this->lang, $this->dbSettings->getSettings(), $objItem->getSKU(), $blockId);
                        $objBookingOption->save(0, $item['selected_quantity']);

                        $this->processDebugMessages($objBookingOption->getDebugMessages());
                        $this->processOkayMessages($objBookingOption->getOkayMessages());
                        $this->processErrorMessages($objBookingOption->getErrorMessages());
                    }
                }
            }
        }

        $this->processDebugMessages($objBlock->getDebugMessages());
        $this->processOkayMessages($objBlock->getOkayMessages());
        $this->processErrorMessages($objBlock->getErrorMessages());

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'manager&tab=blocks');
        exit;
    }

    private function processUnblock($paramBlockId, $paramItemId)
    {
        // Create mandatory instances
        $objBlock = new Block($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramBlockId);
        $objItem = new Item($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramItemId);
        if($objItem->canEdit())
        {
            $objBookingOption = new ItemBookingOption($this->conf, $this->lang, $this->dbSettings->getSettings(), $objItem->getSKU(), $paramBlockId);

            // Delete booking option
            $objBookingOption->delete();

            // If no related elements found to this block
            if($objBlock->isEmpty())
            {
                // Then also remove the block itself as well
                $objBlock->delete();
            }

            $this->processDebugMessages($objBookingOption->getDebugMessages());
            $this->processOkayMessages($objBookingOption->getOkayMessages());
            $this->processErrorMessages($objBookingOption->getErrorMessages());
        }

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'manager&tab=blocks');
        exit;
    }

    public function getContent()
    {
        // Create mandatory instances
        $objLocationsObserver = new LocationsObserver($this->conf, $this->lang, $this->dbSettings->getSettings());

        // Process actions
        if(isset($_POST['block'], $_POST['block_name'])) { $this->processBlock($_POST['block_name']); }
        if(isset($_GET['unblock'], $_GET['item_id'])) { $this->processUnblock($_GET['unblock'], $_GET['item_id']); }

        // Set the view variables
        $this->view->backToListURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'manager&tab=blocks');
        $this->view->blockFormAction = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-item-block');
        $this->view->blockResultsFormAction = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-item-block&noheader=true');
        if(isset($_POST['search_for_block']))
        {
            // Block search results
            $items = $this->objBlockManager->getAvailableWithDetails($this->objBlockManager->getAvailable());
            $this->view->gotBlockResults = sizeof($items) > 0 ? TRUE : FALSE;
            $this->view->items = $items;
            $this->view->locationId = $this->objBlockManager->getLocationId();
            $this->view->locationsDropDownOptions = $objLocationsObserver->getTranslatedLocationsDropDownOptions("BOTH", 0, $this->objBlockManager->getLocationId(), 0, $this->lang->getText('NRS_ADMIN_SELECT_LOCATION_TEXT'));
            $this->view->startDate = $this->objBlockManager->getShortStartDate();
            $this->view->startTime = $this->objBlockManager->getShortStartTime();
            $this->view->startTimeDropDownOptions = StaticFormatter::getTimeDropDownOptions($this->objBlockManager->getShortStartTime(), "00:00:00", "23:30:00", $this->lang->getText('NRS_MIDNIGHT_TEXT'), $this->lang->getText('NRS_NOON_TEXT'));
            $this->view->endDate = $this->objBlockManager->getShortEndDate();
            $this->view->endTimeDropDownOptions = $this->objBlockManager->getShortEndTime();
            $this->view->endTimeDropDownOptions = StaticFormatter::getTimeDropDownOptions($this->objBlockManager->getShortEndTime(), "00:00:00", "23:30:00", $this->lang->getText('NRS_MIDNIGHT_TEXT'), $this->lang->getText('NRS_NOON_TEXT'));
            $this->view->startDateTimeLabel = $this->objBlockManager->getPrintStartDate().' '.$this->objBlockManager->getPrintStartTime();
            $this->view->endDateTimeLabel = $this->objBlockManager->getPrintEndDate().' '.$this->objBlockManager->getPrintEndTime();
        } else
        {
            $this->view->gotBlockResults = FALSE;
            $this->view->items = array();
            $this->view->locationsDropDownOptions = $objLocationsObserver->getTranslatedLocationsDropDownOptions("BOTH", 0, 0, 0, $this->lang->getText('NRS_ADMIN_SELECT_LOCATION_TEXT'));
            $this->view->startDate = $this->objBlockManager->getShortStartDate();
            $this->view->startTimeDropDownOptions = StaticFormatter::getTimeDropDownOptions('09:00:00', "00:00:00", "23:30:00", $this->lang->getText('NRS_MIDNIGHT_TEXT'), $this->lang->getText('NRS_NOON_TEXT'));
            $this->view->endDate = $this->objBlockManager->getShortEndDate();
            $this->view->endTimeDropDownOptions = StaticFormatter::getTimeDropDownOptions('09:00:00', "00:00:00", "23:30:00", $this->lang->getText('NRS_MIDNIGHT_TEXT'), $this->lang->getText('NRS_NOON_TEXT'));
        }

        // Get the template
        $retContent = $this->getTemplate('Item', 'AddEditBlock', 'Form');

        return $retContent;
    }
}
