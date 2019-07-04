<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package orcascars
 */

get_header();
?>
    <div class="main-wrapper">

        <div class="header-bg">
            <div class="container">
                <h1><?php the_archive_title(); ?></h1>
                <?php the_archive_description('<p>', '</p>'); ?>
            </div>
        </div>

        <?php get_template_part('template-parts/content', 'breadcrumbs'); ?>

        <!-- Blog -->
        <div class="blog">
            <div class="container">
                <div class="row">
                    <!-- Loop -->
                    <div class="col-lg-9 col-md-12">
                        <?php if (have_posts()):
                            while (have_posts()): the_post();

                                get_template_part('template-parts/content', 'loop');

                            endwhile;
                            custom_pagination();
                        else:
                            get_template_part('template-parts/content', 'none');
                        endif;
                        ?>
                    </div>
                    <!-- END Loop -->

                    <!-- Sidebar -->
                    <div class="col-lg-3 col-md-12 sitebar">
                        <?php get_sidebar(); ?>
                    </div>
                    <!-- END Sidebar -->

                </div>
            </div>
        </div>
        <!-- END Blog -->
    </div>

<?php
get_footer();
