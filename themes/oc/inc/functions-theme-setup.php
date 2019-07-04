<?php

use \app\helpers\AssetsHelper;

// init session
add_action('init', function () {
    if (!session_id()) {
        session_cache_limiter('');
        session_start();
    }
}, 1);

// Setup Theme
add_action('after_setup_theme', function () {
    /**
     * Set the content width in pixels, based on the theme's design and stylesheet.
     *
     * Priority 0 to make it available to lower priority callbacks.
     *
     * @global int $content_width
     */
    //$GLOBALS['content_width'] = apply_filters('orcascars_content_width', 640);

    // Let WordPress manage the document title.
    add_theme_support('title-tag');

    // Enable support for Post Thumbnails on posts and pages
    add_theme_support('post-thumbnails');
    //set_post_thumbnail_size(600, 400, true);

    add_image_size('half-thumb', 100, 75);
    add_image_size('our-cars-block-image', 505, 295, true);
    add_image_size('blog-post-loop-thumb', 961, 505, true);
    add_image_size('about-us-person', 170, 170, true);
    add_image_size('partners-logotype', 320, 150, true);

    // Switch default core markup for search form, comment form, and comments to output valid HTML5
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption']);

    // Add default posts and comments RSS feed links to head.
    add_theme_support('automatic-feed-links');

    // translate theme
    load_theme_textdomain('orcascars', get_template_directory() . '/languages');

    // Set up the WordPress core custom background feature.
    add_theme_support( 'custom-background', apply_filters( 'orcascars_custom_background_args', array(
        'default-color' => 'ffffff',
        'default-image' => '',
    ) ) );

    // Add theme support for selective refresh for widgets.
    add_theme_support( 'customize-selective-refresh-widgets' );

    // remove default body classes
    add_filter('body_class', '__return_empty_array', 5);

    // Menu locations
    register_nav_menus([
        'main_menu' => esc_html__('Primary', 'orcascars'),
        'footer_menu' => esc_html__('Footer', 'orcascars'),
    ]);

    // remove emoji dns-prefetch
    add_filter('emoji_svg_url', '__return_false');
});

// remove emoji
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

// Widgets
add_action('widgets_init', function () {
    register_sidebar( array(
        'name'          => esc_html__( 'Sidebar', 'orcascars' ),
        'id'            => 'default',
        'description'   => esc_html__( 'Add widgets here.', 'orcascars' ),
        'before_widget' => '<aside id="%1$s" class="sitebar__post widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h4>',
        'after_title'   => '</h4>',
    ) );
});

// Remove ACF Menu Item
//add_filter('acf/settings/show_admin', '__return_false');

// ACF: Add options page
if( function_exists('acf_add_options_page') ) {
    acf_add_options_page(array(
        'page_title' => 'Theme Global Settings',
        'menu_title' => 'Global settings',
    ));
}

// Menu items custom classes
function custom_menu_classes($classes, $item, $args) {
    if ($args->theme_location == 'main_menu') {
        if ($args->menu_class == 'menu-mobile__nav') {
            $classes[] = 'menu-mobile__item';
        } else {
            $classes[] = 'header__menu__item';
        }
    } elseif ($args->theme_location == 'footer_menu') {
        $classes[] = 'list__item';
    }
    return $classes;
}
add_filter('nav_menu_css_class', 'custom_menu_classes', 1, 3);

// add global JS vars
add_action('wp_head', function () {
    ?>
    <script>
        var THEME_URL = '<?php echo THEME_URI; ?>';
    </script>
    <?php
});

// --------------------- Styles and scripts

add_action('wp_enqueue_scripts', function () {

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }

    AssetsHelper::enqueue_css('bootstrap-css', 'stylesheets/vendors/bootstrap.min.css');
    AssetsHelper::enqueue_css('select2-css', 'stylesheets/vendors/select2.css');
    AssetsHelper::enqueue_css('reset-css', 'stylesheets/vendors/reset.css');
    AssetsHelper::enqueue_css('fontawesome-css', 'stylesheets/vendors/fontawesome.min.css');
    AssetsHelper::enqueue_css('stm-rental-css', 'stylesheets/vendors/stm-rental.css');
    AssetsHelper::enqueue_css('stm-icon-css', 'stylesheets/vendors/stm-icon.css');
    AssetsHelper::enqueue_css('stm-service-css', 'stylesheets/vendors/stm-service.css');
    AssetsHelper::enqueue_css('datetimepicker-css', 'stylesheets/vendors/datetimepicker.min.css');
    AssetsHelper::enqueue_css('main-css', 'stylesheets/css/main.css');
    AssetsHelper::enqueue_css('custom-css', 'stylesheets/css/custom.css');

    AssetsHelper::enqueue_js('bootstrap-js', 'libs/bootstrap.min.js', ['jquery']);
    AssetsHelper::enqueue_js('select2-js', 'libs/select2.js', ['jquery']);
    AssetsHelper::enqueue_js('datetimepicker-js', 'libs/datetimepicker.min.js', ['jquery']);
    AssetsHelper::enqueue_js('main-js', 'js/main.js', ['jquery']);
});