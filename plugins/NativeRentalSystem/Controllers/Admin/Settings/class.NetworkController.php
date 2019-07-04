<?php
/**
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Admin\Settings;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Controllers\Admin\AbstractController;

final class NetworkController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    public function getContent()
    {
        // Set the view variables
        $this->view->networkStatusTabChecked = ' checked="checked"';
        $this->view->version = number_format_i18n($this->conf->getVersion(), 1);

        // Get the template
        $retContent = $this->getTemplate('Settings', 'NetworkManager', 'Tabs');

        return $retContent;
    }
}
