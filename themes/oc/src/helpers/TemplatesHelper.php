<?php

namespace app\helpers;

class TemplatesHelper
{
    /**
     * Show favicon in head
     */
    public static function show_favicon()
    {
        if (!function_exists('get_home_path')) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
        }
        $url = site_url('/') . 'favicon.ico';
        $path = get_home_path() . '/favicon.ico';
        if (!is_file($path)) {
            $url = THEME_URI . '/favicon.ico';
            $path = THEME_PATH . '/favicon.ico';
        }
        if (is_file($path)) {
            echo '<link rel="icon" href="' . esc_url($url) . '" type="image/x-icon">' . "\n";
            echo '<link rel="shortcut icon" href="' . esc_url($url) . '" type="image/x-icon">' . "\n";
        }
    }

    /**
     * Add class to html tag
     * @param string $class
     */
    public static function add_html_class($class)
    {
        add_filter('language_attributes', function ($output) use ($class) {
            echo (!empty($output) ? $output . ' ' : '') . 'class="' . $class . '"';
        });
    }

    /**
     * Set classes for body tag
     * @param array|string $classes
     */
    public static function set_body_classes($classes)
    {
        if (!is_array($classes)) {
            $classes = preg_split('/\s+/', $classes, -1, PREG_SPLIT_NO_EMPTY);
        }
        add_filter('body_class', function () use ($classes) {
            return $classes;
        });
    }

    /**
     * Set header image
     * @param integer $postId
     * @return string
     */
    public static function setHeaderImageViaStyle($postId)
    {
        $featuredImageBgStyle = '';

        $featuredImageBg = wp_get_attachment_url(get_post_thumbnail_id($postId));

        if ($featuredImageBg) {
            $featuredImageBgStyle = ' style="background-image: url(' . esc_url($featuredImageBg) . ')"';
        }

        return $featuredImageBgStyle;
    }

    /**
     * Show the recent posts in the footer
     */
    public static function showFooterRecentPosts($count = 2)
    {
        $args = array(
            'numberposts' => $count,
            'orderby' => 'post_date',
            'order' => 'DESC',
            'post_type' => 'post',
            'post_status' => 'publish',
            'suppress_filters' => true
        );

        $recentPosts = wp_get_recent_posts($args, ARRAY_A);

        if ($recentPosts) {
            echo '<aside class="col-lg-3 col-md-6">' . "\n";
            echo '<h6>RECENT POSTS</h6>' . "\n";
                foreach ($recentPosts as $recentPost) {
                    $commentsCount = __('No comments', 'orcascars');

                    if ($recentPost['comment_count'] > 0) {
                        $commentsCount = $recentPost['comment_count'] . ' ' . __('Comment', 'orcascars');
                    }

                    echo '<div class="footer-top__posts">' . "\n";
                        echo '<p>' . wp_trim_words($recentPost['post_title'], 13, '...') . '</p>' . "\n";
                        echo '<a href="' . get_the_permalink($recentPost['ID']) . '"><i class="fa fa-commenting-o" aria-hidden="true"></i>' . $commentsCount . '</a>' . "\n";

                    echo '</div>' . "\n";
                }
            echo '</aside>' . "\n";
        }
    }
}
