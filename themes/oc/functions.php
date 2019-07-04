<?php
define('THEME_NAME', basename(__DIR__));
define('THEME_PATH', __DIR__); // get_template_directory()
define('THEME_URI', get_template_directory_uri());

/* <input type="hidden" name="<?php echo THEME_NONCE_NAME; ?>" value="<?php echo wp_create_nonce(THEME_NONCE_ACTION); ?>"> */
define('THEME_NONCE_NAME', 'token');
define('THEME_NONCE_ACTION', 'web-oc-token');

require __DIR__ . '/vendor/autoload.php';

require __DIR__ . '/inc/functions-theme-setup.php';
require __DIR__ . '/inc/functions-admin.php';
require __DIR__ . '/inc/functions-meta-fields.php';
require __DIR__ . '/inc/functions-common.php';