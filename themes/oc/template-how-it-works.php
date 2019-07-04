<?php
/**
 * Template name: How It Works
 */

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

        <!-- Begin works -->
        <div class="works">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <?php the_content(); ?>

                        <?php if (have_rows('how_it_works_advantages')): ?>
                            <div class="how-it-works__advantages">
                                <?php while (have_rows('how_it_works_advantages')): the_row(); ?>
                                    <div class="how-it-works__advantage col-sm-6">
                                        <div class="how-it-works__advantage--icon">
                                            <i class="fa <?php the_sub_field('font_awesome_class'); ?> fa-4x" aria-hidden="true"></i>
                                        </div>
                                        <div class="how-it-works__advantage--content">
                                            <div class="how-it-works__advantage--title"><?php the_sub_field('title'); ?></div>
                                            <?php the_sub_field('content'); ?>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
        <!-- END works -->
    </div>

<?php endwhile; ?>

<?php
get_footer();