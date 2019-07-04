<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package orcascars
 */

get_header();
?>

    <?php while ( have_posts() ) : the_post(); ?>

    <div class="main-wrapper">

        <div class="header-bg">
            <div class="container">
                <h1><?php the_title(); ?></h1>
            </div>
        </div>

        <?php get_template_part('template-parts/content', 'breadcrumbs'); ?>

        <!-- Blog -->
        <div class="blog">
            <div class="container">
                <div class="row">

                    <!-- Begin Sidebar -->
                    <div class="col-lg-3 col-md-12 sitebar">
                        <?php get_sidebar(); ?>
                    </div>
                    <!-- END Single -->

                    <!-- Begin Single -->
                    <div class="col-lg-9 col-md-12">
                        <aside class="single">
                            <h1 class="blog__tittle"><?php the_title(); ?></h1><!-- todo: Double H1? -->

                            <?php if (has_post_thumbnail()): ?>
                                <div class="blog__img">
                                    <?php the_post_thumbnail('blog-post-loop-thumb'); ?>
                                </div>
                                <div class="blog__info">
                                    <div class="blog__info--left">
                                        <span class="blog__info--item"><i class="stm-icon-date"></i><time datetime="<?php echo get_the_date('c'); ?>"><?php echo get_the_date('F j, Y'); ?></time></span>
                                        <span class="blog__info--item"><i class="stm-icon-author"></i><span><?php esc_html_e('Posted by:', 'orcascars'); ?> <?php the_author(); ?></span></span>
                                    </div>
                                    <div class="blog__info--right">
                                        <a href="<?php comments_link(); ?>"><i class="stm-icon-message"></i><?php comments_number(); ?></a>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="single__post">
                                <?php the_content(); ?>
                            </div>
                            <div class="single__info">
                                <div class="single__category">
                                    <span><?php esc_html_e('CATEGORY:', 'orcascars'); ?></span><?php the_category(', '); ?>
                                </div>
                                <?php the_tags('<div class="single__tags"><span>TAGS:</span> ', ', ', '</div>'); ?>
                            </div>

                            <?php
                            if (comments_open() || get_comments_number()) {
                                comments_template();
                            }
                            ?>

                        </aside>
                    </div>
                    <!-- END Single -->

                </div>
            </div>
        </div>
        <!-- END Blog -->

    </div>

    <?php endwhile; ?>

<?php
get_footer();
