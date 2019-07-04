<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package orcascars
 */

if (!is_active_sidebar('default')) {
    return;
}
?>

<?php dynamic_sidebar('default'); ?>
