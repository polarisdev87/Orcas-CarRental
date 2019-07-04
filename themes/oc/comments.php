<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package orcascars
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}

?>

<div id="comments" class="comments-area">

	<?php
	// You can start editing here -- including this comment!
	if ( have_comments() ) :
		?>
		<h2 class="comments-title"><?php comments_number(); ?></h2><!-- .comments-title -->

		<ul class="comment-list list-unstyled">
            <?php
                wp_list_comments( array(
                    'style'       => 'ul',
                    'short_ping'  => true,
                    'avatar_size' => 80,
                    'callback'    => 'custom_comment'
                ) );
            ?>
		</ul><!-- .comment-list -->

        <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) { ?>
            <nav class="navigation comment-navigation" role="navigation">
                <h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'orcascars' ); ?></h2>
                <div class="nav-links">
                    <?php
                    if ( $prev_link = get_previous_comments_link( __( 'Older Comments', 'orcascars' ) ) ) {
                        printf( '<div class="nav-previous">%s</div>', $prev_link );
                    }
                    if ( $next_link = get_next_comments_link( __( 'Newer Comments', 'orcascars' ) ) ) {
                        printf( '<div class="nav-next">%s</div>', $next_link );
                    }
                    ?>
                </div>
            </nav>
        <?php }

	endif; // Check for have_comments().
    ?>

    <?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) { ?>
        <p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'orcascars' ); ?></p>
    <?php }

    comment_form( array(
		'comment_notes_before' => '',
		'comment_notes_after' => ''
	) );
	?>

</div><!-- #comments -->