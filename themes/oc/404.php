<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package orcascars
 */

get_header();
?>

    <div class="main-wrapper">
        <div class="header-bg">
            <div class="container">
                <h1><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'orcascars' ); ?></h1>
            </div>
        </div>

        <?php get_template_part('template-parts/content', 'breadcrumbs'); ?>

        <div class="works">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <p><?php esc_html_e( 'It looks like nothing was found at this location.', 'orcascars' ); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
get_footer();
