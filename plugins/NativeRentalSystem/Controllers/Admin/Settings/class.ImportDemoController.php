<?php
/**
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Controllers\Admin\Settings;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\Import\Demo;
use NativeRentalSystem\Models\Language\Language;
use NativeRentalSystem\Controllers\Admin\AbstractController;
use NativeRentalSystem\Models\Language\LanguagesObserver;
use NativeRentalSystem\Models\Post\ItemType;
use NativeRentalSystem\Models\Post\LocationType;
use NativeRentalSystem\Models\Post\PageType;

final class ImportDemoController extends AbstractController
{
    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    private function processImportDemo()
    {
        $paramDemoId = isset($_POST['demo_id']) ? $_POST['demo_id'] : 0;

        // Create mandatory instances
        $objDemo = new Demo($this->conf, $this->lang, $paramDemoId);
        // We register post types here only because we want to run 'flush_rewrite_rules()' bellow.
        $objPagePostType = new PageType($this->conf, $this->lang, $this->conf->getExtensionPrefix().'page');
        $objItemPostType = new ItemType($this->conf, $this->lang, $this->conf->getExtensionPrefix().'item');
        $objLocationPostType = new LocationType($this->conf, $this->lang, $this->conf->getExtensionPrefix().'location');

        // Delete all existing content and then insert new content
        $objDemo->deleteContent();
        $objDemo->replaceContent();

        // Register newly imported database data for translation
        $objLanguagesObserver = new LanguagesObserver($this->conf, $this->lang);
        if($this->lang->canTranslateSQL())
        {
            // If WPML is enabled
            $objLanguagesObserver->registerAllForTranslation();
        }

        // Load slugs
        $sqlPage = "
                        SELECT conf_value AS slug
                        FROM {$this->conf->getPrefix()}settings
                        WHERE conf_key='conf_page_url_slug' AND blog_id='{$this->conf->getBlogId()}'
                    ";
        $sqlItem = "
                        SELECT conf_value AS slug
                        FROM {$this->conf->getPrefix()}settings
                        WHERE conf_key='conf_item_url_slug' AND blog_id='{$this->conf->getBlogId()}'
                    ";
        $sqlLocation = "
                        SELECT conf_value AS slug
                        FROM {$this->conf->getPrefix()}settings
                        WHERE conf_key='conf_location_url_slug' AND blog_id='{$this->conf->getBlogId()}'
                    ";
        $tmpPageUrlSlug = $this->conf->getInternalWPDB()->get_var($sqlPage);
        $tmpItemUrlSlug = $this->conf->getInternalWPDB()->get_var($sqlItem);
        $tmpLocationUrlSlug = $this->conf->getInternalWPDB()->get_var($sqlLocation);

        $pageUrlSlug = !is_null($tmpPageUrlSlug) ? $tmpPageUrlSlug : $this->lang->getText('NRS_INSTALL_DEFAULT_PAGE_URL_SLUG_TEXT');
        $itemUrlSlug = !is_null($tmpPageUrlSlug) ? $tmpItemUrlSlug : $this->lang->getText('NRS_INSTALL_DEFAULT_ITEM_URL_SLUG_TEXT');
        $locationUrlSlug = !is_null($tmpPageUrlSlug) ? $tmpLocationUrlSlug : $this->lang->getText('NRS_INSTALL_DEFAULT_LOCATION_URL_SLUG_TEXT');

        // There were changes for links - so we need to flush the rewrite rules
        $objPagePostType->register($pageUrlSlug, 95);
        $objItemPostType->register($itemUrlSlug, 96);
        $objLocationPostType->register($locationUrlSlug, 97);
        flush_rewrite_rules();

        $this->processDebugMessages($objDemo->getDebugMessages());
        $this->processOkayMessages($objDemo->getOkayMessages());
        $this->processErrorMessages($objDemo->getErrorMessages());

        wp_safe_redirect('admin.php?page='.$this->conf->getURLPrefix().'single-manager&tab=import-demo');
        exit;
    }

    public function getContent()
    {
        // First - process actions
        if(isset($_POST['import_demo'])) { $this->processImportDemo(); }

        return '';
    }
}
