<?php
/**
 * Template name: Partners
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

                        <?php if (have_rows('partners')) : ?>
                            <div class="our-partners__list row">
                                <?php while (have_rows('partners')) : the_row();
                                    $image = get_sub_field('image');
                                    ?>
                                    <div class="our-partners__partner col-sm-4">
                                        <a class="our-partners__partner--logotype" href="<?php the_sub_field('url'); ?>"
                                           target="_blank">
                                            <img src="<?php echo $image['sizes']['partners-logotype']; ?>" alt="<?php echo $image['alt']; ?>"/>
                                        </a>
                                        <span class="our-partners__partner--name"><?php the_sub_field('business_name'); ?></span>
                                        <a class="our-partners__partner--url" href="<?php the_sub_field('url'); ?>" target="_blank"><?php the_sub_field('url'); ?></a>
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