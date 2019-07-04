<?php

namespace app\helpers;

/**
 * Class AssetsHelper
 * Only for css and js files from theme
 */
class AssetsHelper
{
    /**
     * @param string $handle
     * @param string $src
     * @param array $deps
     * @param string $media
     */
    public static function register_css($handle, $src, $deps = [], $media = 'all')
    {
        $src = '/' . ltrim($src, '/');
        if (is_file(THEME_PATH . $src)) {
            $handle = self::process_handle($handle);
            wp_register_style($handle, THEME_URI . $src, $deps, filemtime(THEME_PATH . $src), $media);
        }
    }

    /**
     * @param string $handle
     * @param string $src
     * @param array $deps
     * @param string $media
     */
    public static function enqueue_css($handle, $src, $deps = [], $media = 'all')
    {
        $src = '/' . ltrim($src, '/');
        if (is_file(THEME_PATH . $src)) {
            $handle = self::process_handle($handle);
            wp_enqueue_style($handle, THEME_URI . $src, $deps, filemtime(THEME_PATH . $src), $media);
        }
    }

    /**
     * @param string $handle
     * @param string $src
     * @param array $deps
     * @param bool $in_footer
     */
    public static function register_js($handle, $src, $deps = [], $in_footer = true)
    {
        $src = '/' . ltrim($src, '/');
        if (is_file(THEME_PATH . $src)) {
            $handle = self::process_handle($handle);
            wp_register_script($handle, THEME_URI . $src, $deps, filemtime(THEME_PATH . $src), $in_footer);
        }
    }

    /**
     * @param string $handle
     * @param string $src
     * @param array $deps
     * @param bool $in_footer
     */
    public static function enqueue_js($handle, $src, $deps = [], $in_footer = true)
    {
        $src = '/' . ltrim($src, '/');
        if (is_file(THEME_PATH . $src)) {
            $handle = self::process_handle($handle);
            wp_enqueue_script($handle, THEME_URI . $src, $deps, filemtime(THEME_PATH . $src), $in_footer);
        }
    }

    /**
     * @param string $handle
     * @return string
     */
    private static function process_handle($handle)
    {
        return strpos($handle, THEME_NAME . '-') === 0 || strpos($handle, 'oc-') === 0 ? $handle : THEME_NAME . '-' . $handle;
    }
}