<?php
/**
 * Template name: Contact Us
 */

wp_enqueue_script('googleapis-js', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDip1rjUt_mapLF585PbXrimIHxAs0sMeo&callback', false, '1.0', 'all');
\app\helpers\AssetsHelper::enqueue_js('contact-us-js', 'js/contact-us-page.js', ['jquery']);

get_header();
?>

<?php while (have_posts()): the_post();
    $featuredImageBgStyle = \app\helpers\TemplatesHelper::setHeaderImageViaStyle(get_the_ID());
    ?>

    <div class="main-wrapper bg-white">
        <div class="header-bg"<?php echo $featuredImageBgStyle; ?>>
            <div class="container">
                <h1><?php the_title(); ?></h1>
            </div>
        </div>

        <?php get_template_part('template-parts/content', 'breadcrumbs'); ?>

        <!-- BEGIN form-->
        <div class="container">
            <div class="contact-us">
                <?php the_content(); ?>
            </div>
        </div>
        <!-- END form -->

        <!-- BEGIN google maps-->
        <div id="contact-up__map"></div>
        <!-- END google maps-->
    </div>

<?php endwhile; ?>

<?php
get_footer();