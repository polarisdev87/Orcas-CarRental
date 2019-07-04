<aside class="blog__post">
    <?php if (has_post_thumbnail()): ?>
        <div class="blog__img">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('blog-post-loop-thumb'); ?>
            </a>
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
    <div class="blog__main">
        <h1 class="blog__tittle">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h1>
        <p class="blog__text"><?php the_excerpt();?></p>
        <a href="<?php the_permalink(); ?>" class="blog__btn"><?php esc_html_e('Read more...', 'orcascars'); ?></a>
    </div>
</aside>
