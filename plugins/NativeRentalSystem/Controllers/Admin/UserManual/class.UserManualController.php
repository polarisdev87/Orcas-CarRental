<?php
/**
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Admin\UserManual;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Controllers\Admin\AbstractController;

final class UserManualController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    public function getContent()
    {
        // Get the tab values
        $tabs = $this->getTabParams(array(
            'instructions', 'shortcode-parameters'
        ), 'instructions');

        // 1. Set the view variables - Tab settings
        $this->view->instructionsTabChecked = !empty($tabs['instructions']) ? ' checked="checked"' : '';
        $this->view->shortcodeParametersTabChecked = !empty($tabs['shortcode-parameters']) ? ' checked="checked"' : '';

        // Get the template
        $retContent = $this->getTemplate('UserManual', 'UserManual', 'Tabs');

        return $retContent;
    }
}
