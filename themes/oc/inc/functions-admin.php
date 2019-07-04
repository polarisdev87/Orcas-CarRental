<?php
// customize admin bar
if (!is_admin() && is_admin_bar_showing()) {

    // hide admin bar
    //add_filter('show_admin_bar', '__return_false');

    // remove original styles for html & body
    add_action('get_header', function () {
        remove_action('wp_head', '_admin_bar_bump_cb');
    });

    // add own styles
    add_action('wp_head', function () {
        ?>
        <style type="text/css" media="screen">
            body { padding-top: 32px; }
            /*#header { top: 32px; }*/
            @media screen and ( max-width: 782px ) {
                body { padding-top: 46px; }
                #header { top: 46px; }
            }
            @media screen and (max-width: 600px) {
                #wpadminbar { position: fixed; }
            }
        </style>
        <?php
    });
}