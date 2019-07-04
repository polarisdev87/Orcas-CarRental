<?php
get_header();
?>
    <div class="main-wrapper">

        <div class="header-bg">
            <div class="container">
                <h1><?php single_post_title(); ?></h1>
            </div>
        </div>

        <?php get_template_part('template-parts/content', 'breadcrumbs'); ?>

        <!-- Blog -->
        <div class="blog">
            <div class="container">
                <div class="row">
                    <!-- Post -->
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
                    <!-- END Post -->

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