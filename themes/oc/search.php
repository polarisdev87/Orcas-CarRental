<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package orcascars
 */

get_header();
?>
    <div class="main-wrapper">

        <div class="header-bg">
            <div class="container">
                <h1><?php printf( esc_html__( 'Search Results for: %s', 'orcascars' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
            </div>
        </div>

        <?php get_template_part('template-parts/content', 'breadcrumbs'); ?>

        <!-- Blog -->
        <div class="blog">
            <div class="container">
                <div class="row">

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
