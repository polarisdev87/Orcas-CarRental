<?php
if (!defined('THEME_NAME')) exit;

get_header();
?>

    <main>
        <?php
        if (have_posts()) {
            if (is_home() && !is_front_page()) {
                ?>
                <h1><?php single_post_title(); ?></h1>
                <?php
            }
            while (have_posts()) {
                the_post();
                if (is_single()) {
                    the_title('<h1>', '</h1>');
                } else {
                    the_title(sprintf('<h2><a href="%s">', esc_url(get_permalink())), '</a></h2>');
                }
            }
            the_posts_pagination();
        } else {
            ?>
            <h1>Nothing Found</h1>
            <?php
        }
        ?>
    </main>

<?php
get_footer();
?>