<?php
/**
 * Template name: Reservation
 */

get_header();
?>

<?php while (have_posts()): the_post(); ?>

    <div class="main-wrapper">

        <?php get_template_part('template-parts/content', 'breadcrumbs'); ?>

        <div class="works">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <?php the_content(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php endwhile; ?>

<?php
get_footer();