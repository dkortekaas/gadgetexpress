<?php
/**
 * Custom functions for displaying comments
 *
 * @package Gadget Express
 */

/**
 * Comment callback function
 *
 * @param object $comment
 * @param array  $args
 * @param int    $depth
 */
function gadgetexpress_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	extract( $args, EXTR_SKIP );

	if ( 'div' == $args['style'] ) {
		$add_below = 'comment';
	} else {
		$add_below = 'div-comment';
	}
	?>

	<li <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
	<?php if ( 'div' != $args['style'] ) : ?>
		<article id="div-comment-<?php comment_ID() ?>" class="comment-body clearfix">
	<?php endif; ?>

	<div class="comment-author vcard">
		<?php echo get_avatar( $comment, 70 );?>
	</div>
	<div class="comment-meta commentmetadata clearfix">
		<div class="comment-header">
		<?php printf( '<cite class="author-name">%s</cite>', get_comment_author_link() ); ?>

		<?php
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() )
		);

		echo '<div class="comment-date">' . $time_string . '</div>';
		?>

		<?php if ( $comment->comment_approved == '0' ) : ?>
			<em class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'gadget' ); ?></em>
		<?php endif; ?>
		</div>
		<div class="comment-content">
			<?php comment_text(); ?>
		</div>

		<?php
		comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'], 'reply_text' => esc_html__( 'Reply', 'gadget' ) ) ) );
		edit_comment_link( esc_html__( 'Edit', 'gadget' ), '  ', '' );
		?>
	</div>
	<?php if ( 'div' != $args['style'] ) : ?>
		</article>
	<?php endif; ?>
    </li>
	<?php
}

/*
 *  Custom comment form
 */
function gadgetexpress_comment_form( $fields ) {
	$commenter = wp_get_current_commenter();
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );

	$fields['author'] = '<p class="comment-form-author">
						<input id="author" placeholder="' . esc_attr__( 'Name', 'gadget' ) . '" required name="author" type="text" value="' .
		esc_attr( $commenter['comment_author'] ) . '" size="30" />'.
		'</p>';
	$fields['email'] = '<p class="comment-form-email">
						<input id="email" placeholder="' . esc_attr__( 'Email', 'gadget' ) . '" required name="email" type="email" value="' . esc_attr(  $commenter['comment_author_email'] ) .
		'" size="30" />'  .
		'</p>';
	$fields['url'] = '<p class="comment-form-url">
					 <input id="url" name="url" placeholder="' . esc_attr__( 'Website', 'gadget' ) . '" type="text" size="30" /> ' .
		'</p>';
	$fields['clear'] = '<div class="clearfix"></div>';

	return $fields;
}

add_filter('comment_form_default_fields','gadgetexpress_comment_form');
