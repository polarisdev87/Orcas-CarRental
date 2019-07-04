<?php

add_filter('comment_form_default_fields', 'custom_comment_form_fields');
function custom_comment_form_fields($fields)
{
    $commenter = wp_get_current_commenter();
    $req = get_option('require_name_email');
    $aria_req = ($req ? " aria-required='true'" : '');
    $html5 = current_theme_supports('html5', 'comment-form') ? 1 : 0;
    $fields = array(
        'author' => '<div class="row post-comment__inner">
							<div class="col-md-4 col-sm-4 col-xs-12">
								<div class="form-group post-comment__author">
			            			<input placeholder="' . sprintf(esc_attr__('Name %s', 'orcascars'), ($req ? '*' : '')) . '" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30"' . $aria_req . ' />
		                        </div>
		                    </div>',
        'email' => '<div class="col-md-4 col-sm-4 col-xs-12">
							<div class="form-group post-comment__email">
								<input placeholder="' . sprintf(esc_attr__('E-mail %s', 'orcascars'), ($req ? '*' : '')) . '" name="email" ' . ($html5 ? 'type="email"' : 'type="text"') . ' value="' . esc_attr($commenter['comment_author_email']) . '" size="30"' . $aria_req . ' />
							</div>
						</div>',
        'url' => '<div class="col-md-4 col-sm-4 col-xs-12">
						<div class="form-group post-comment__url">
							<input placeholder="' . esc_attr__('Website', 'orcascars') . '" name="url" type="text" value="' . esc_attr($commenter['comment_author_url']) . '" size="30" />
						</div>
					</div></div>'
    );
    return $fields;
}

add_filter('comment_form_defaults', 'custom_comment_form');
function custom_comment_form($args)
{
    $args['comment_field'] = '<div class="form-group post-comment__text">
			 <textarea placeholder="' . esc_attr__('Message *', 'orcascars') . '" name="comment" rows="9" aria-required="true"></textarea>
	    </div>';
    $args['submit_field'] = '<div class="btn">%1$s %2$s</div>';
    $args['class_submit'] = 'btn';
    return $args;
}

// Comments
function custom_comment($comment, $args, $depth)
{
    $GLOBALS['comment'] = $comment;
    extract($args, EXTR_SKIP);
    if ('div' == $args['style']) {
        $tag = 'div ';
        $add_below = 'comment';
    } else {
        $tag = 'li ';
        $add_below = 'div-comment';
    }
    ?>
    <<?php echo esc_attr($tag) ?><?php comment_class(empty($args['has_children']) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
    <?php if ('div' != $args['style']) { ?>
    <div id="div-comment-<?php comment_ID() ?>" class="comment-body clearfix">
<?php } ?>
    <?php if ($args['avatar_size'] != 0) { ?>
    <div class="comment-avatar">
        <?php echo get_avatar($comment, 80); ?>
    </div>
<?php } ?>
    <div class="comment-info-wrapper">
        <div class="comment-info">
            <div class="clearfix">
                <div class="comment-author pull-left"><span
                            class="h5"><?php echo get_comment_author_link(); ?></span></div>
                <div class="comment-meta commentmetadata pull-right">
                    <a class="comment-date"
                       href="<?php echo esc_url(htmlspecialchars(get_comment_link($comment->comment_ID))); ?>">
                        <?php printf(__('%1$s', 'orcascars'), get_comment_date()); ?>
                    </a>
                    <span class="comment-meta-data-unit">
							<?php comment_reply_link(array_merge($args, array(
                                'reply_text' => __('<span class="comment-divider">/</span><i class="fa fa-reply"></i> Reply', 'orcascars'),
                                'add_below' => $add_below,
                                'depth' => $depth,
                                'max_depth' => $args['max_depth']
                            ))); ?>
						</span>
                    <span class="comment-meta-data-unit">
							<?php edit_comment_link(__('<span class="comment-divider">/</span><i class="fa fa-pencil-square-o"></i> Edit', 'orcascars'), '  ', ''); ?>
						</span>
                </div>
            </div>
            <?php if ($comment->comment_approved == '0') { ?>
                <em class="comment-awaiting-moderation"><?php esc_html_e('Your comment is awaiting moderation.', 'orcascars'); ?></em>
            <?php } ?>
        </div>
        <div class="comment-text">
            <?php comment_text(); ?>
        </div>
    </div>

    <?php if ('div' != $args['style']) { ?>
    </div>
<?php } ?>
    <?php
}

//Pagination
function custom_pagination()
{
    global $wp_query;
    $show_pagination = true;
    if (!empty($wp_query->found_posts) and !empty($wp_query->query_vars['posts_per_page'])) {
        if ($wp_query->found_posts <= $wp_query->query_vars['posts_per_page']) {
            $show_pagination = false;
        }
    }
    if ($show_pagination): ?>
        <div class="row">
            <div class="col-md-12">
                <div class="custom-blog-pagination">
                    <?php if (get_previous_posts_link()) { ?>
                        <div class="custom-prev-next custom-prev-btn">
                            <?php previous_posts_link('<i class="fa fa-angle-left"></i>'); ?>
                        </div>
                    <?php } else { ?>
                        <div class="custom-prev-next custom-prev-btn disabled"><i class="fa fa-angle-left"></i></div>
                    <?php }
                    echo paginate_links(array(
                        'type' => 'list',
                        'prev_next' => false
                    ));
                    if (get_next_posts_link()) { ?>
                        <div class="custom-prev-next custom-next-btn">
                            <?php next_posts_link('<i class="fa fa-angle-right"></i>'); ?>
                        </div>
                    <?php } else { ?>
                        <div class="custom-prev-next custom-next-btn disabled"><i class="fa fa-angle-right"></i></div>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php endif;
}

function getLocationsList()
{
    global $wpdb;

    $locations = [];

    $table_name = $wpdb->prefix . 'car_rental_locations';
    $sql = "SELECT location_id, location_name FROM `{$table_name}` ORDER BY location_order ASC";

    $offices = $wpdb->get_results($sql);

    if (count($offices) > 0) {
        foreach ($offices as $office) {
            $locations[$office->location_id] = $office->location_name;
        }
    }

    return $locations;
}