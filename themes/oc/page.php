<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package orcascars
 */

get_header();
?>

<?php while (have_posts()): the_post();
    $featuredImageBgStyle = \app\helpers\TemplatesHelper::setHeaderImageViaStyle(get_the_ID());
    ?>

    <div class="main-wrapper">
        <div class="header-bg"<?php echo $featuredImageBgStyle; ?>>
            <div class="container">
                <h1><?php the_title(); ?></h1>
            </div>
        </div>

        <?php get_template_part('template-parts/content', 'breadcrumbs'); ?>

        <!-- Begin works -->
        <div class="works">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <?php the_content(); ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- END works -->
    </div>

<?php endwhile; ?>

<?php
get_footer();