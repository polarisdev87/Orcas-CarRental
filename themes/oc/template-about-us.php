<?php
/**
 * Template name: About us
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
                        <?php if (have_rows('about_us_persons')): ?>
                            <div class="about-us__persons">
                                <?php while (have_rows('about_us_persons')) : the_row(); ?>
                                    <div class="about-us__person">
                                        <?php $personImage = get_sub_field('image'); ?>
                                        <?php if ($personImage): ?>
                                            <img class="about-us__person--image" src="<?php echo $personImage['sizes']['about-us-person']; ?>"
                                                 alt="<?php echo $personImage['alt']; ?>"/>
                                        <?php endif; ?>
                                        <?php the_sub_field('content'); ?>
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