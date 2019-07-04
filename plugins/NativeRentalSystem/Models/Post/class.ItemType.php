<?php
/**
 * NRS Item post type

 * @note - It does not have settings param in constructor on purpose!
 * @package NRS
 * @author Kestutis Matuliauskas
 * @copyright Kestutis Matuliauskas
 * See Licensing/README_License.txt for copyright notices and details.
 */
namespace NativeRentalSystem\Models\Post;
use NativeRentalSystem\Models\AbstractElement;
use NativeRentalSystem\Models\Configuration\ExtensionConfiguration;
use NativeRentalSystem\Models\iPrimitive;
use NativeRentalSystem\Models\Language\Language;

class ItemType extends AbstractElement implements iPrimitive
{
    private $conf 	                = NULL;
    private $lang 		            = NULL;
    private $debugMode 	            = 0;
    private $postType               = '';

    public function __construct(ExtensionConfiguration &$paramConf, Language &$paramLang, $paramPostType)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitation will kill the system speed
        $this->lang = $paramLang;
        $this->postType = sanitize_key($paramPostType);
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    public function getId()
    {
        return $this->postType;
    }

    /**
     * Creating a function to create our item post type
     * @param $paramSlug
     * @param int $paramMenuPosition
     */
    public function register($paramSlug, $paramMenuPosition)
    {
        $sanitizedSlug = sanitize_key($paramSlug);
        $validMenuPosition = intval($paramMenuPosition);
        $iconURL = $this->conf->getExtensionAdminImagesURL('Plugin.png');

        // Set UI labels for Custom Post Type
        $labels = array(
            'name'                => $this->lang->getText('NRS_ADMIN_ITEM_POST_TYPE_LABEL_NAME_TEXT'),
            'singular_name'       => $this->lang->getText('NRS_ADMIN_ITEM_POST_TYPE_LABEL_SINGULAR_NAME_TEXT'),
            'menu_name'           => $this->lang->getText('NRS_ADMIN_ITEM_POST_TYPE_LABEL_MENU_NAME_TEXT'),
            'parent_item_colon'   => $this->lang->getText('NRS_ADMIN_ITEM_POST_TYPE_LABEL_PARENT_ITEM_COLON_TEXT'),
            'all_items'           => $this->lang->getText('NRS_ADMIN_ITEM_POST_TYPE_LABEL_ALL_ITEMS_TEXT'),
            'view_item'           => $this->lang->getText('NRS_ADMIN_ITEM_POST_TYPE_LABEL_VIEW_ITEM_TEXT'),
            'add_new_item'        => $this->lang->getText('NRS_ADMIN_ITEM_POST_TYPE_LABEL_ADD_NEW_ITEM_TEXT'),
            'add_new'             => $this->lang->getText('NRS_ADMIN_ITEM_POST_TYPE_LABEL_ADD_NEW_TEXT'),
            'edit_item'           => $this->lang->getText('NRS_ADMIN_ITEM_POST_TYPE_LABEL_EDIT_ITEM_TEXT'),
            'update_item'         => $this->lang->getText('NRS_ADMIN_ITEM_POST_TYPE_LABEL_UPDATE_ITEM_TEXT'),
            'search_items'        => $this->lang->getText('NRS_ADMIN_ITEM_POST_TYPE_LABEL_SEARCH_ITEMS_TEXT'),
            'not_found'           => $this->lang->getText('NRS_ADMIN_ITEM_POST_TYPE_LABEL_NOT_FOUND_TEXT'),
            'not_found_in_trash'  => $this->lang->getText('NRS_ADMIN_ITEM_POST_TYPE_LABEL_NOT_FOUND_IN_TRASH_TEXT'),
        );

        // Set other options for Custom Post Type
        $args = array(
            'description'         => $this->lang->getText('NRS_ADMIN_ITEM_POST_TYPE_DESCRIPTION_TEXT'),
            'labels'              => $labels,
            // Features this CPT supports in Post Editor
            /*'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),*/
            'supports'            => array( 'title', 'editor', 'author', 'thumbnail', ),
            // You can associate this CPT with a taxonomy or custom taxonomy.
            /*'taxonomies'          => array( $extension.'s' ),*/
            /* A hierarchical CPT is like Pages and can have
            * Parent and child items. A non-hierarchical CPT
            * is like Posts. MUST BE hierarchical=TRUE, to have drop-downs
            */
            'hierarchical'        => TRUE,
            'public'              => TRUE,
            'show_ui'             => TRUE,
            'show_in_menu'        => TRUE,
            'show_in_nav_menus'   => TRUE,
            'show_in_admin_bar'   => TRUE,
            'menu_position'       => $validMenuPosition,
            'menu_icon'			  => $iconURL,
            'can_export'          => TRUE,
            'has_archive'         => FALSE,
            'rewrite'			  => array(
                'slug' => $sanitizedSlug, // loaded from language file
                'with_front' => FALSE,
                'pages' => FALSE,
            ),
            'exclude_from_search' => FALSE,
            'publicly_queryable'  => TRUE,
            /* To manage specific rights to edit only items*/
            //'capability_type'     => $extension,// cant be car, because we need to
            'capability_type'     => 'page', // We allow to edit description pages for those who have rights to edit pages
            /** Note:
             * If we will set map_meta_cap = TRUE, we will have an error:
             *   Undefined property: stdClass::$delete_posts at wp-admin/includes/class-wp-posts-list-table.php:209
             * The following code is run:
             *   if( current_user_can( $post_type_obj->cap->delete_posts ) ) {
             * The problem is that the capability, 'delete_posts', is only applied to a post type (via get_post_type_capabilities())
             * if the 'map_meta_cap' argument is set to true when you're registering the post type (via register_post_type()).
             * So, despite that we want to have here map_meta_cap = FALSE, we have to set it to TRUE until WordPress will fix the bug here:
             * https://core.trac.wordpress.org/ticket/30991
             */
            'map_meta_cap'        => TRUE, // Set to false, if users are not allowed to edit/delete existing posts

            /*'capabilities' => array(
                'read'
            ),*/
            /*'capabilities' => array(
                'create_posts' => false, // Removes support for the "Add New" function - DOESN'T WORK
                'delete_post' => false,
                'publish_posts' => false,
            ),*/
        );

        // Registering your Custom Post Type
        register_post_type( $this->postType, $args );
        // flush_rewrite_rules();
    }
}