<?php
/**
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Admin\Extras;
use NativeRentalSystem\Models\Block\Block;
use NativeRentalSystem\Models\Booking\ExtraBookingOption;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Formatting\StaticFormatter;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Models\Extra\Extra;
use NativeRentalSystem\Models\Block\ExtraBlockManager;
use NativeRentalSystem\Controllers\Admin\AbstractController;

final class AddEditBlockController extends AbstractController
{
    private $objBlockManager = NULL;

    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
        $this->objBlockManager = new ExtraBlockManager($this->conf, $this->lang, $this->dbSettings->getSettings());

        // First - process request
        $this->processRequest();
    }

    private function processRequest()
    {
        // Set class variables
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
        $arrExtras = $this->objBlockManager->getSelectedWithDetails($this->objBlockManager->getIds());

        // If there is items to block
        if(sizeof($arrExtras) > 0)
        {
            // Then create the block
            $blocked = $objBlock->save(
                $paramBlockName, "", $this->objBlockManager->getStartTimestamp(), $this->objBlockManager->getEndTimestamp()
            );
            if($blocked)
            {
                $blockId = $objBlock->getId();

                foreach($arrExtras AS $extra)
                {
                    $objExtra = new Extra($this->conf, $this->lang, $this->dbSettings->getSettings(), $extra['extra_id']);
                    if($objExtra->canEdit())
                    {
                        $objBookingOption = new ExtraBookingOption($this->conf, $this->lang, $this->dbSettings->getSettings(), $objExtra->getSKU(), $blockId);
                        $objBookingOption->save(0, $extra['selected_quantity']);

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

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'extras-manager&tab=blocks');
        exit;
    }

    private function processUnblock($paramBlockId, $paramExtraId)
    {
        // Create mandatory instances
        $objBlock = new Block($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramBlockId);
        $objExtra = new Extra($this->conf, $this->lang, $this->dbSettings->getSettings(), $paramExtraId);
        if($objExtra->canEdit())
        {
            $objBookingOption = new ExtraBookingOption($this->conf, $this->lang, $this->dbSettings->getSettings(), $objExtra->getSKU(), $paramBlockId);

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

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'extras-manager&tab=blocks');
        exit;
    }

    public function getContent()
    {
        // Process actions
        if(isset($_POST['block'], $_POST['block_name'])) { $this->processBlock($_POST['block_name']); }
        if(isset($_GET['unblock'], $_GET['extra_id'])) { $this->processUnblock($_GET['unblock'], $_GET['extra_id']); }

        // Set the view variables
        $this->view->backToListURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'extras-manager&tab=blocks');
        $this->view->blockFormAction = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-extra-block');
        $this->view->blockResultsFormAction = admin_url('admin.php?page='.$this->conf->getURLPrefix().'add-edit-extra-block&noheader=true');
        if(isset($_POST['search_to_block']))
        {
            // Block search results
            $extras = $this->objBlockManager->getAvailableWithDetails($this->objBlockManager->getAvailable());
            $this->view->gotBlockResults = sizeof($extras) > 0 ? TRUE : FALSE;
            $this->view->extras = $extras;
            $this->view->startDate = $this->objBlockManager->getShortStartDate();
            $this->view->startTime = $this->objBlockManager->getShortStartTime();
            $this->view->startTimeDropDownOptions = StaticFormatter::getTimeDropDownOptions($this->objBlockManager->getShortStartTime(), "00:00:00", "23:30:00", $this->lang->getText('NRS_MIDNIGHT_TEXT'), $this->lang->getText('NRS_NOON_TEXT'));
            $this->view->endDate = $this->objBlockManager->getShortEndDate();
            $this->view->endTime = $this->objBlockManager->getShortEndTime();
            $this->view->endTimeDropDownOptions = StaticFormatter::getTimeDropDownOptions($this->objBlockManager->getShortEndTime(), "00:00:00", "23:30:00", $this->lang->getText('NRS_MIDNIGHT_TEXT'), $this->lang->getText('NRS_NOON_TEXT'));
            $this->view->startDateTimeLabel = $this->objBlockManager->getPrintStartDate().' '.$this->objBlockManager->getPrintStartTime();
            $this->view->endDateTimeLabel = $this->objBlockManager->getPrintEndDate().' '.$this->objBlockManager->getPrintEndTime();
        } else
        {
            $this->view->gotBlockResults = FALSE;
            $this->view->extras = array();
            $this->view->startDate = $this->objBlockManager->getShortStartDate();
            $this->view->startTimeDropDownOptions = StaticFormatter::getTimeDropDownOptions('09:00:00', "00:00:00", "23:30:00", $this->lang->getText('NRS_MIDNIGHT_TEXT'), $this->lang->getText('NRS_NOON_TEXT'));
            $this->view->endDate = $this->objBlockManager->getShortEndDate();
            $this->view->endTimeDropDownOptions = StaticFormatter::getTimeDropDownOptions('09:00:00', "00:00:00", "23:30:00", $this->lang->getText('NRS_MIDNIGHT_TEXT'), $this->lang->getText('NRS_NOON_TEXT'));
        }

        // Get the template
        $retContent = $this->getTemplate('Extras', 'AddEditBlock', 'Form');

        return $retContent;
    }
}

