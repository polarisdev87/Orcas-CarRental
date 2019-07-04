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

final class AddEditEmailController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    private function processSave()
    {
        $selectedEmailId = isset($_POST['email_id']) ? StaticValidator::getValidPositiveInteger($_POST['email_id'], 0) : 0;
        $objEMail = new EMail($this->conf, $this->lang, $this->dbSettings->getSettings(), $selectedEmailId);
        if($objEMail->canEdit())
        {
            $saved = $objEMail->save();
            if($saved && $this->lang->canTranslateSQL())
            {
                $objEMail->registerForTranslation();
            }

            $this->processDebugMessages($objEMail->getDebugMessages());
            $this->processOkayMessages($objEMail->getOkayMessages());
            $this->processErrorMessages($objEMail->getErrorMessages());
        }

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'single-manager&tab=email-settings&email='.$selectedEmailId);
        exit;
    }

    public function getContent()
    {
        // First - process actions
        if(isset($_POST['update_email'])) { $this->processSave(); }

        return '';
    }
}
