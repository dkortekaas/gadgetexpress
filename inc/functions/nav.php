<?php
/**
 * Custom functions for nav menu
 *
 * @package Gadget Express
 */


/**
 * Display numeric pagination
 *
 * @since 1.0
 * @return void
 */
function gadgetexpress_numeric_pagination() {
	global $wp_query;

	if ( $wp_query->max_num_pages < 2 ) {
		return;
	}

	$type_nav  = gadgetexpress_get_option( 'portfolio_nav_type' );
	$p_style   = gadgetexpress_get_option( 'portfolio_layout' );
	$view_more = apply_filters( 'gadgetexpress_portfolio_nav_text', esc_html__( 'Discover More', 'gadget' ) );

	$next_html = sprintf(
		'<span id="gadgetexpress-portfolio-ajax" class="nav-previous-ajax">
			<span class="nav-text">%s</span>
			<span class="loading-icon">
				<span class="loading-text">%s</span>
				<span class="icon_loading gadgetexpress-spin su-icon"></span>
			</span>
		</span>',
		$view_more,
		esc_html__( 'Loading', 'gadget' )
	);

	?>
	<nav class="navigation paging-navigation numeric-navigation">
		<?php
		add_filter( 'number_format_i18n', 'gadgetexpress_paginate_links_prefix' );

		$big  = 999999999;
		$args = array(
			'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'total'     => $wp_query->max_num_pages,
			'current'   => max( 1, get_query_var( 'paged' ) ),
			'prev_text' => '<i class="icon-chevron-left"></i>',
			'next_text' => '<i class="icon-chevron-right"></i>',
			'type'      => 'plain'
		);

		if ( gadgetexpress_is_portfolio() && $p_style != 'carousel' && $type_nav == 'ajax' ) {
			$args['prev_text'] = '';
			$args['next_text'] = $next_html;
		}

		echo paginate_links( $args );
		remove_filter( 'number_format_i18n', 'gadgetexpress_paginate_links_prefix' );
		?>
	</nav>
	<?php
}

/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @since 1.0
 * @return void
 */
function gadgetexpress_paging_nav() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}
	$css       = '';
	$type_nav  = gadgetexpress_get_option( 'type_nav' );
	$style     = gadgetexpress_get_option( 'view_more_style' );
	$view_more = wp_kses( gadgetexpress_get_option( 'view_more_text' ), wp_kses_allowed_html( 'post' ) );

	if ( $type_nav == 'view_more' ) {
		$css .= ' blog-view-more style-' . $style;
	}

	?>
	<nav class="navigation paging-navigation <?php echo esc_attr( $css ); ?>">
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
				<?php if ( $type_nav == 'view_more' ) : ?>
					<div id="gadgetexpress-blog-previous-ajax" class="nav-previous-ajax">
						<?php next_posts_link( sprintf( '%s', $view_more ) ); ?>
						<span class="loading-icon">
							<span class="bubble">
								<span class="dot"></span>
							</span>
							<span class="bubble">
								<span class="dot"></span>
							</span>
							<span class="bubble">
								<span class="dot"></span>
							</span>
						</span>
					</div>
				<?php else : ?>
					<div class="nav-previous"><?php next_posts_link( sprintf( '<span class="meta-nav"><i class="icon-arrow-left"></i> </span> %s', esc_html__( 'Older posts', 'gadget' ) ) ); ?></div>
				<?php endif; ?>

			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
				<?php if ( $type_nav != 'view_more' ) : ?>
					<div class="nav-next"><?php previous_posts_link( sprintf( '%s <span class="meta-nav"><i class="icon-arrow-right"></i></span>', esc_html__( 'Newer posts', 'gadget' ) ) ); ?></div>
				<?php endif; ?>
			<?php endif; ?>

		</div>
		<!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}

/**
 * Display navigation to next/previous post when applicable.
 *
 * @since 1.0
 * @return void
 */
function gadgetexpress_post_nav() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}
	?>
	<nav class="navigation post-navigation">
		<div class="nav-links">
			<?php
			previous_post_link( '<div class="nav-previous">%link</div>', _x( '<span class="meta-nav"><i class="fa fa-chevron-left" aria-hidden="true"></i></span>' . esc_html__( 'Prev', 'gadget' ), 'Previous post link', 'gadget' ) );
			next_post_link( '<div class="nav-next">%link</div>', _x( esc_html__( 'Next', 'gadget' ) . '<span class="meta-nav"><i class="fa fa-chevron-right" aria-hidden="true"></i></span>', 'Next post link', 'gadget' ) );
			?>
		</div>
		<!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}

/* Filter function to be used with number_format_i18n filter hook */
if ( ! function_exists( 'gadgetexpress_paginate_links_prefix' ) ) :
	function gadgetexpress_paginate_links_prefix( $format ) {
		$number = intval( $format );
		if ( intval( $number / 10 ) > 0 ) {
			return $format;
		}

		return '0' . $format;
	}
endif;

function gadgetexpress_single_portfolio_nav() {
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}

	$next_icon = '<span>' . esc_html__( 'Next', 'gadget' ) . '</span><i class="icon-arrow-right"></i>';
	$prev_icon = '<i class="icon-arrow-left"></i><span>' . esc_html__( 'Prev', 'gadget' ) . '</span>';

	?>
	<nav class="navigation portfolio-navigation">
		<div class="container">
			<div class="nav-links">
				<div class="nav-previous">
					<?php previous_post_link( '%link', $prev_icon ); ?>
				</div>

				<a class="portfolio-link" href="<?php echo esc_url( get_post_type_archive_link( 'portfolio' ) ); ?>"><i class="icon_grid-3x3"></i></a>

				<div class="nav-next">
					<?php next_post_link( '%link', $next_icon ); ?>
				</div>
			</div>
			<!-- .nav-links -->
		</div>
	</nav><!-- .navigation -->
	<?php
}
