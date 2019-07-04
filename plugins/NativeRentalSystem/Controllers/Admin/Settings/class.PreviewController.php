<?php
/**
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Admin\Settings;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\EMail\EMail;
use NativeRentalSystem\Models\Validation\StaticValidator;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Controllers\Admin\AbstractController;

final class PreviewController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    public function getContent()
    {
        // Tab - email settings
        $selectedEmailId = isset($_GET['email']) ? StaticValidator::getValidPositiveInteger($_GET['email'], 1) : 1;
        $objEMail = new EMail($this->conf, $this->lang, $this->dbSettings->getSettings(), $selectedEmailId);
        $localEmailDetails = $objEMail->getPreview(); // We know that it will will always return the fields

        // Set the view variables
        $this->view->emailPreviewTabChecked = ' checked="checked"';
        $this->view->backToListURL = admin_url('admin.php?page='.$this->conf->getURLPrefix().'single-manager&tab=email-settings');
        $this->view->emailSubject = $localEmailDetails['print_translated_email_subject']; // We know that it will always return the fields
        $this->view->emailBody = $localEmailDetails['print_translated_email_body']; // We know that it will always return the fields

        // Get the template
        $retContent = $this->getTemplate('Settings', 'PreviewManager', 'Tabs');

        return $retContent;
    }
}
