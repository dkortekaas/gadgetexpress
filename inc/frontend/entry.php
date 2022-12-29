<?php
/**
 * Hooks for template archive
 *
 * @package Gadget Express
 */


/**
 * Sets the authordata global when viewing an author archive.
 *
 * This provides backwards compatibility with
 * http://core.trac.wordpress.org/changeset/25574
 *
 * It removes the need to call the_post() and rewind_posts() in an author
 * template to print information about the author.
 *
 * @since 1.0
 * @global WP_Query $wp_query WordPress Query object.
 * @return void
 */
function gadgetexpress_setup_author() {
	global $wp_query;

	if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
		$GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
	}
}

add_action( 'wp', 'gadgetexpress_setup_author' );

/**
 * Add CSS classes to posts
 *
 * @param array $classes
 *
 * @return array
 */
function gadgetexpress_post_class( $classes ) {

	$classes[] = has_post_thumbnail() ? '' : 'no-thumb';

	return $classes;
}

add_filter( 'post_class', 'gadgetexpress_post_class' );

/**
 * Open tag after start site content
 */
if ( ! function_exists( 'gadgetexpress_site_content_open' ) ) :

	function gadgetexpress_site_content_open() {
		$container      = 'container';
		$product_layout = gadgetexpress_get_option( 'single_product_layout' );
		$portfolio_style   = gadgetexpress_get_option( 'portfolio_layout' );

		if ( is_front_page() ) :
			$container = 'container-fluid';
		endif;

		if ( gadgetexpress_is_page_template() ) {
			$container = 'container-fluid';
		}

		if (  function_exists( 'is_product' ) && is_product() ) {
			if ( in_array( $product_layout, array( '2', '5' ) ) ) {
				$container = 'container-fluid';
			}
		}

		if ( gadgetexpress_is_catalog() && intval( gadgetexpress_get_option( 'catalog_full_width' ) ) ) {
			$container = 'gadgetexpress-catalog-container';
		}

		if ( is_singular( 'portfolio' ) ) {
			$container = 'container-fluid';
		}

		if ( gadgetexpress_is_portfolio() && $portfolio_style == 'masonry' ) {
			$container = 'container-fluid';
		}

		echo '<div class="' . esc_attr( apply_filters( 'gadgetexpress_site_content_container_class', $container ) ) . '">';
		echo '<div class="row">';
	}

endif;

add_action( 'gadgetexpress_site_content_open', 'gadgetexpress_site_content_open', 20 );

/**
 * Close tag before end site content
 */
if ( ! function_exists( 'gadgetexpress_site_content_close' ) ) :

	function gadgetexpress_site_content_close() {
		echo '</div>';
		echo '</div>';
	}

endif;

add_action( 'gadgetexpress_site_content_close', 'gadgetexpress_site_content_close', 100 );


/**
 * Add blog categories
 *
 * @since  1.0
 *
 *
 */
if ( ! function_exists( 'gadgetexpress_blog_text_categories' ) ) :
	function gadgetexpress_blog_text_categories() {
		$blog_style = gadgetexpress_get_option( 'blog_style' );
		$cat_filter = intval( gadgetexpress_get_option( 'blog_cat_filter' ) );

		if ( ! $cat_filter ) {
			return;
		}

		if ( $blog_style == 'masonry' ) {
			echo '<div class="container">';
		}

		gadgetexpress_taxs_list();

		if ( $blog_style == 'masonry' ) {
			echo '</div>';
		}
	}
endif;

add_action( 'gadgetexpress_before_post_list', 'gadgetexpress_blog_text_categories' );
add_action( 'gadgetexpress_before_archive_post_list', 'gadgetexpress_blog_text_categories' );

/**
 * Add blog categories
 *
 * @since  1.0
 *
 *
 */
if ( ! function_exists( 'gadgetexpress_portfolio_categories' ) ) :
	function gadgetexpress_portfolio_categories() {
		$p_style = gadgetexpress_get_option( 'portfolio_layout' );
		$cat_filter = intval( gadgetexpress_get_option( 'portfolio_category_filter' ) );

		if ( ! $cat_filter ) {
			return;
		}

		gadgetexpress_taxs_list( 'portfolio_category' );
	}

endif;

add_action( 'gadgetexpress_before_portfolio_list', 'gadgetexpress_portfolio_categories' );
add_action( 'gadgetexpress_before_taxonomy_portfolio_list', 'gadgetexpress_portfolio_categories' );

/**
 * Add Coming Soon Newsletter
 *
 * @since  1.0
 *
 *
 */
if ( ! function_exists( 'gadgetexpress_coming_soon_newsletter' ) ) :
	function gadgetexpress_coming_soon_newsletter() {
		$newsletter = intval( gadgetexpress_get_option( 'coming_soon_newsletter' ) );
		$form       = do_shortcode( wp_kses( gadgetexpress_get_option( 'coming_soon_newsletter_form' ), wp_kses_allowed_html( 'post' ) ) );
		if ( ! intval( $newsletter ) ) {
			return;
		};

		?>
        <div class="coming-soon-newsletter">
            <div class="container">
                <div class="coming-soon-form"><?php echo '' . $form; ?></div>
            </div>
        </div>
		<?php
	}
endif;

add_action( 'gadgetexpress_coming_soon_page_content', 'gadgetexpress_coming_soon_newsletter', 10 );


if ( ! function_exists( 'gadgetexpress_coming_soon_socials' ) ) :
	function gadgetexpress_coming_soon_socials() {

		if ( ! intval( gadgetexpress_get_option( 'show_coming_soon_social_share' ) ) ) {
			return;
		}

		$project_social = (array) gadgetexpress_get_option( 'coming_soon_socials' );

		if ( $project_social ) {

			$socials = (array) gadgetexpress_get_socials();

			printf( '<div class="gadgetexpress-coming-soon-socials-share">' );
			printf( '<div class="container">' );
			printf( '<ul class="socials-inline">' );
			foreach ( $project_social as $social ) {
				foreach ( $socials as $name => $label ) {
					$link_url = $social['link_url'];

					if ( preg_match( '/' . $name . '/', $link_url ) ) {

						if ( $name == 'google' ) {
							$name = 'googleplus';
						}

						printf( '<li><a href="%s" target="_blank"><i class="social_%s"></i></a></li>', esc_url( $link_url ), esc_attr( $name ) );
						break;
					}
				}
			}
			printf( '</ul>' );
			printf( '</div>' );
			printf( '</div>' );
		}

	}

endif;

add_action( 'gadgetexpress_coming_soon_page_content', 'gadgetexpress_coming_soon_socials', 40 );

/**
 * Filter to archive title and add page title for singular pages
 *
 * @param string $title
 *
 * @return string
 */
function gadgetexpress_the_archive_title( $title ) {
	if ( is_search() ) {
		$title = esc_html__( 'Search Results', 'gadget' );
	} elseif ( is_404() ) {
		$title = esc_html__( 'Page Not Found', 'gadget' );

	} elseif ( is_page() ) {
		$title = get_the_title();
	} elseif ( function_exists( 'is_shop' ) && is_shop() ) {
		$title = get_the_title( wc_get_page_id( 'shop' ) );
	} elseif ( function_exists( 'is_product' ) && is_product() ) {
		$cats = get_the_terms( get_the_ID(), 'product_cat' );
		if ( ! is_wp_error( $cats ) && $cats ) {
			$title = $cats[0]->name;
		} else {
			$title = get_the_title( get_option( 'woocommerce_shop_page_id' ) );
		}

	} elseif ( is_tax() || is_category() ) {
		$title = single_term_title( '', false );

	} elseif ( is_singular( 'post' ) ) {
		$terms = get_the_category();
		if ( $terms && ! is_wp_error( $terms ) ) {
			$title = $terms[0]->name;
		} else {
			$title = get_the_title( get_option( 'page_for_posts' ) );
		}
	} elseif ( gadgetexpress_is_portfolio() ) {
		$title = wp_kses( gadgetexpress_get_option( 'portfolio_page_header_title' ), wp_kses_allowed_html( 'post' ) );
		if ( ! $title ) {
			if ( get_option( 'gadgetexpress_portfolio_page_id' ) ) {
				$title = get_the_title( get_option( 'gadgetexpress_portfolio_page_id' ) );
			} else {
				$title = esc_html__( 'Portfolio', 'gadget' );
			}
		}
	} elseif ( is_home() && is_front_page() ) {
		$title = esc_html__( 'The Latest Posts', 'gadget' );

	} elseif ( is_home() && ! is_front_page() ) {
		$title = wp_kses_post( gadgetexpress_get_option( 'blog_page_header_title' ) );

		if ( empty( $title ) ) {
			$title = get_the_title( get_option( 'page_for_posts' ) );
		}
	}

	if ( get_option( 'woocommerce_shop_page_id' ) ) {
		if ( is_front_page() && ( get_option( 'woocommerce_shop_page_id' ) == get_option( 'page_on_front' ) ) ) {
			$title = get_the_title( get_option( 'woocommerce_shop_page_id' ) );
		}
	}

	return $title;
}

add_filter( 'get_the_archive_title', 'gadgetexpress_the_archive_title' );

/**
 * Open wrapper for catalog content sidebar
 */
function gadgetexpress_open_wrapper_catalog_content_sidebar() {
	if ( ! gadgetexpress_is_catalog() ) {
		return;
	}

	?>
	<div class="widget-canvas-content">
		<div class="widget-panel-header hidden-lg">
			<a href="#" class="close-canvas-panel"><span class="icon-cross2"></span></a>
		</div>
		<div class="widget-panel-content">
	<?php
}

add_action( 'gadgetexpress_before_sidebar_content', 'gadgetexpress_open_wrapper_catalog_content_sidebar', 10 );

/**
 * Close wrapper for catalog content sidebar
 */
function gadgetexpress_close_wrapper_catalog_content_sidebar() {
	if ( ! gadgetexpress_is_catalog() ) {
		return;
	}

	?>
	</div> <!-- .widget-panel-content -->
	</div> <!-- .widget-canvas-content -->
	<?php
}

add_action( 'gadgetexpress_after_sidebar_content', 'gadgetexpress_close_wrapper_catalog_content_sidebar', 100 );

/**
 * Footer Portfolio Carousel
 */
function gadgetexpress_footer_portfolio_carousel() {
	$p_style = gadgetexpress_get_option( 'portfolio_layout' );

	if ( 'carousel' != $p_style ) {
		return;
	}

	?>
	<!-- If we need navigation buttons -->
	<div class="swiper-button-prev"><span class="icon-chevron-left"></span></div>
	<div class="swiper-button-next"><span class="icon-chevron-right"></span></div>

	<!-- If we need scrollbar -->
	<div class="swiper-scrollbar container"></div>

	<?php
}

add_action( 'gadgetexpress_after_portfolio_content', 'gadgetexpress_footer_portfolio_carousel' );