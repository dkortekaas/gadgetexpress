<?php
/**
 * Custom functions that act in the footer.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Gadget Express
 */

/**
 * Show footer
 */

if ( ! function_exists( 'gadgetexpress_show_footer' ) ) :
	function gadgetexpress_show_footer() {
		if ( is_page_template( 'template-coming-soon-page.php' ) ) {
			return;
		}

		$footer_layout = 1;
		get_template_part( 'parts/footers/layout', $footer_layout );
	}

endif;

add_action( 'gadgetexpress_footer', 'gadgetexpress_show_footer', 20 );

/**
 *  Display footer newsletter
 */
function gadgetexpress_footer_newsletter() {
	if ( ! intval( gadgetexpress_get_option( 'footer_newsletter' ) ) ) {
		return;
	}

	if ( intval( gadgetexpress_get_option( 'footer_newsletter_home' ) ) && ! is_front_page() ) {
		return;
	}

	if ( is_page_template( 'template-coming-soon-page.php' ) || is_page_template( 'template-home-no-footer.php' ) ) {
		return;
	}

	if ( ! gadgetexpress_get_option( 'newsletter_form' ) ) {
		return;
	}

	$style = gadgetexpress_get_option( 'newsletter_style' );
	$shape = gadgetexpress_get_option( 'newsletter_shape' );
	$title = gadgetexpress_get_option( 'newsletter_title' );
	$desc  = gadgetexpress_get_option( 'newsletter_desc' );
	$bg    = gadgetexpress_get_option( 'newsletter_background_color' );
	$form  = do_shortcode( wp_kses( gadgetexpress_get_option( 'newsletter_form' ), wp_kses_allowed_html( 'post' ) ) );

	if ( $style == 'space-between' ) {
		$title_col = 'col-md-5 col-xs-12 col-sm-12';
		$form_col  = 'col-md-7 col-xs-12 col-sm-12';
	} else {
		$title_col = $form_col = 'col-md-12 col-xs-12 col-sm-12';
	}

	$classes = array(
		'footer-newsletter gadgetexpress-newsletter',
		$style . '-style',
		'form-' . $shape,
		$bg ? 'has-bg' : ''
	);

	?>
    <div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
        <div class="container">
            <div class="row form-row">
                <div class="title-area <?php echo esc_attr( $title_col ); ?>">
					<?php
					if ( $title ) {
						echo sprintf( '<h2 class="title">%s</h2>', $title );
					}
					?>

					<?php
					if ( $desc ) {
						echo sprintf( '<div class="subtitle">%s</div>', $desc );
					}
					?>
                </div>
                <div class="form-area <?php echo esc_attr( $form_col ); ?>">
					<?php echo '' . $form; ?>
                </div>
            </div>
        </div>
    </div>
	<?php
}

add_action( 'gadgetexpress_before_footer', 'gadgetexpress_footer_newsletter', 10 );

/**
 *  Display Newsletter Form on Home Full Slider
 */

function gadgetexpress_footer_newsletter_full_slider() {

	if ( ! is_page_template( 'template-home-no-footer.php' ) ) {
		return;
	}

	if ( intval( get_post_meta( get_the_ID(), 'hide_newsletter', true ) ) ) {
		return;
	}

	if ( ! get_post_meta( get_the_ID(), 'form', true ) ) {
		return;
	}

	$title = get_post_meta( get_the_ID(), 'form_title', true );
	$desc  = get_post_meta( get_the_ID(), 'form_subtitle', true );

	?>
    <div class="footer-newsletter">
        <div class="container form-row">
            <div class="title-area">
				<?php
				if ( $title ) {
					echo sprintf( '<h2 class="title">%s</h2>', $title );
				}
				?>

				<?php
				if ( $desc ) {
					echo sprintf( '<div class="subtitle">%s</div>', $desc );
				}
				?>
            </div>
            <div class="form-area">
				<?php echo do_shortcode( wp_kses( get_post_meta( get_the_ID(), 'form', true ), wp_kses_allowed_html( 'post' ) ) ); ?>
            </div>
        </div>
    </div>
	<?php
}

add_action( 'gadgetexpress_before_footer', 'gadgetexpress_footer_newsletter_full_slider', 20 );

/**
 * Display back to top
 *
 * @since 1.0.0
 */
function gadgetexpress_back_to_top() {
	if ( ! intval( gadgetexpress_get_option( 'back_to_top' ) ) ) {
		return;
	}
	?>
    <a id="scroll-top" class="backtotop" href="#">
        <i class="icon-arrow-up"></i>
    </a>
	<?php
}

add_action( 'wp_footer', 'gadgetexpress_back_to_top' );

/**
 * Adds photoSwipe dialog element
 */
function gadgetexpress_gallery_images_lightbox() {

	if ( ! is_singular() ) {
		return;
	}

	?>
    <div id="pswp" class="pswp" tabindex="-1" role="dialog" aria-hidden="true">

        <div class="pswp__bg"></div>

        <div class="pswp__scroll-wrap">

            <div class="pswp__container">
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
            </div>

            <div class="pswp__ui pswp__ui--hidden">

                <div class="pswp__top-bar">


                    <div class="pswp__counter"></div>

                    <button class="pswp__button pswp__button--close"
                            title="<?php esc_attr_e( 'Close (Esc)', 'gadget' ) ?>"></button>

                    <button class="pswp__button pswp__button--share"
                            title="<?php esc_attr_e( 'Share', 'gadget' ) ?>"></button>

                    <button class="pswp__button pswp__button--fs"
                            title="<?php esc_attr_e( 'Toggle fullscreen', 'gadget' ) ?>"></button>

                    <button class="pswp__button pswp__button--zoom"
                            title="<?php esc_attr_e( 'Zoom in/out', 'gadget' ) ?>"></button>

                    <div class="pswp__preloader">
                        <div class="pswp__preloader__icn">
                            <div class="pswp__preloader__cut">
                                <div class="pswp__preloader__donut"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                    <div class="pswp__share-tooltip"></div>
                </div>

                <button class="pswp__button pswp__button--arrow--left"
                        title="<?php esc_attr_e( 'Previous (arrow left)', 'gadget' ) ?>">
                </button>

                <button class="pswp__button pswp__button--arrow--right"
                        title="<?php esc_attr_e( 'Next (arrow right)', 'gadget' ) ?>">
                </button>

                <div class="pswp__caption">
                    <div class="pswp__caption__center"></div>
                </div>

            </div>

        </div>

    </div>
	<?php
}

add_action( 'wp_footer', 'gadgetexpress_gallery_images_lightbox' );


/**
 * Adds preloader container at the bottom of the site
 */
function gadgetexpress_preloader() {
	if ( ! intval( gadgetexpress_get_option( 'preloader' ) ) ) {
		return;
	}

	get_template_part( 'parts/preloader' );
}

add_action( 'gadgetexpress_after_footer', 'gadgetexpress_preloader' );

/**
 * Add off canvas shopping cart to footer
 *
 * @since 1.0.0
 */

if ( ! function_exists( 'gadgetexpress_off_canvas_menu_sidebar' ) ) :
	function gadgetexpress_off_canvas_menu_sidebar() {

		if ( is_page_template( 'template-coming-soon-page.php' ) ) {
			return;
		}


		?>
        <div id="menu-sidebar-panel" class="menu-sidebar gadgetexpress-off-canvas-panel">
            <div class="widget-canvas-content">
                <div class="widget-panel-header">
                    <a href="#" class="close-canvas-panel"><span aria-hidden="true" class="icon-cross2"></span></a>
                </div>
                <div class="widget-panel-content hidden-md hidden-sm hidden-xs">
					<?php
					$sidebar = 'menu-sidebar';
					if ( is_active_sidebar( $sidebar ) ) {
						dynamic_sidebar( $sidebar );
					}
					?>
                </div>

                <div class="widget-panel-content hidden-lg">
					<?php
					$sidebar = 'mobile-menu-sidebar';
                    if ( ! is_active_sidebar( $sidebar ) ) {
	                    $sidebar = 'menu-sidebar';
                    }
					if ( is_active_sidebar( $sidebar ) ) {
						dynamic_sidebar( $sidebar );
					} else {
						gadgetexpress_nav_menu();
					}
					?>
					<ul class="language-switcher"><?php gadgetexpress_extra_language_switcher( 'hidden-l' ); ?></ul>
                </div>
                <div class="widget-panel-footer">
                </div>
            </div>
        </div>
		<?php
	}

endif;

add_action( 'wp_footer', 'gadgetexpress_off_canvas_menu_sidebar' );

/**
 * Display a layer to close canvas panel everywhere inside page
 *
 * @since 1.0.0
 */

if ( ! function_exists( 'gadgetexpress_site_canvas_layer' ) ) :
	function gadgetexpress_site_canvas_layer() {
		?>
        <div id="off-canvas-layer" class="gadgetexpress-off-canvas-layer"></div>
		<?php
	}

endif;

add_action( 'wp_footer', 'gadgetexpress_site_canvas_layer' );

/**
 * Add off canvas shopping cart to footer
 *
 * @since 1.0.0
 */

if ( ! function_exists( 'gadgetexpress_off_canvas_cart' ) ) :
	function gadgetexpress_off_canvas_cart() {

		if ( is_page_template( 'template-coming-soon-page.php' ) ) {
			return;
		}

		if ( ! function_exists( 'woocommerce_mini_cart' ) ) {
			return;
		}

		$extras = gadgetexpress_get_menu_extras();

		if ( empty( $extras ) || ! in_array( 'cart', $extras ) ) {
			return '';
		}

		?>
        <div id="cart-panel" class="cart-panel woocommerce mini-cart gadgetexpress-off-canvas-panel">
            <div class="widget-canvas-content">
                <div class="widget-cart-header  widget-panel-header">
                    <a href="#" class="close-canvas-panel"><span aria-hidden="true" class="icon-cross2"></span></a>
                </div>
                <div class="widget_shopping_cart_content">
					<?php woocommerce_mini_cart(); ?>
                </div>
            </div>
            <div class="mini-cart-loading"><span class="gadgetexpress-loader"></span></div>
        </div>
		<?php
	}

endif;

add_action( 'wp_footer', 'gadgetexpress_off_canvas_cart' );

/**
 * Add search modal to footer
 */
if ( ! function_exists( 'gadgetexpress_search_modal' ) ) :
	function gadgetexpress_search_modal() {

		if ( is_page_template( 'template-coming-soon-page.php' ) ) {
			return;
		}

		?>
        <div id="search-modal" class="search-modal gadgetexpress-modal" tabindex="-1" role="dialog">
            <div class="modal-content">
                <h2 class="modal-title"><?php esc_html_e( 'Search', 'gadget' ); ?></h2>

                <div class="container">
                    <form method="get" class="instance-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
						<?php
						$number = apply_filters( 'gadgetexpress_product_cats_search_number', 4 );
						$cats   = '';
						if ( gadgetexpress_get_option( 'search_content_type' ) == 'products' ) {
							$args = array(
								'number'       => $number,
								'orderby'      => 'count',
								'order'        => 'desc',
								'hierarchical' => false,
								'taxonomy'     => 'product_cat',
							);
							$cats = get_terms( $args );
						}
						?>
						<?php if ( $cats && ! is_wp_error( $cats ) ) : ?>
                            <div class="product-cats">
                                <label>
                                    <input type="radio" name="product_cat" value="" checked="checked">
                                    <span class="line-hover"><?php esc_html_e( 'All', 'gadget' ) ?></span>
                                </label>

								<?php foreach ( $cats as $cat ) : ?>
                                    <label>
                                        <input type="radio" name="product_cat"
                                               value="<?php echo esc_attr( $cat->slug ); ?>">
                                        <span class="line-hover"><?php echo esc_html( $cat->name ); ?></span>
                                    </label>
								<?php endforeach; ?>
                            </div>
						<?php endif; ?>

                        <div class="search-fields">
                            <input type="text" name="s" placeholder="<?php esc_attr_e( 'Search', 'gadget' ); ?>"
                                   class="search-field" autocomplete="off">
							<?php if ( gadgetexpress_get_option( 'search_content_type' ) == 'products' ) { ?>
                                <input type="hidden" name="post_type" value="product">
							<?php } ?>
                            <input type="submit" class="btn-submit">
                            <span class="search-submit">
						</span>
                        </div>
                    </form>

                    <div class="search-results">
                        <div class="text-center loading">
                            <span class="gadgetexpress-loader"></span>
                        </div>
                        <div class="woocommerce"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="close-modal">
                    <span class="hidden-md hidden-sm hidden-xs"><?php esc_html_e( 'Close', 'gadget' ) ?></span>
                    <i class="hidden-lg icon-cross"></i>
                </a>
            </div>
        </div>
		<?php
	}

endif;

add_action( 'wp_footer', 'gadgetexpress_search_modal' );

/**
 * Adds quick view modal to footer
 */
if ( ! function_exists( 'gadgetexpress_quick_view_modal' ) ) :
	function gadgetexpress_quick_view_modal() {
		if ( is_page_template( 'template-coming-soon-page.php' ) ) {
			return;
		}
		?>

        <div id="quick-view-modal" class="quick-view-modal gadgetexpress-modal woocommerce" tabindex="-1" role="dialog">
            <div class="modal-header">
                <a href="#" class="close-modal">
                    <i class="icon-cross"></i>
                </a>
            </div>

            <div class="modal-content">
                <div class="container">
                    <div class="gadgetexpress-product-content">
                        <div class="product">
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12 product-images-wrapper">
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12  product-summary">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="gadgetexpress-loader"></div>
        </div>

		<?php
	}

endif;

add_action( 'wp_footer', 'gadgetexpress_quick_view_modal' );


/**
 * Add login modal to footer
 */

if ( ! function_exists( 'gadgetexpress_login_modal' ) ) :
	function gadgetexpress_login_modal() {

		if ( is_page_template( 'template-coming-soon-page.php' ) ) {
			return;
		}

		if ( ! shortcode_exists( 'woocommerce_my_account' ) ) {
			return;
		}

		if ( is_user_logged_in() ) {
			return;
		}
		?>

        <div id="login-modal" class="login-modal gadgetexpress-modal woocommerce-account" tabindex="-1" role="dialog">
            <div class="modal-content">
                <div class="container">
					<?php echo do_shortcode( '[woocommerce_my_account]' ) ?>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="close-modal"><?php esc_html_e( 'Close', 'gadget' ) ?></a>
            </div>
        </div>

		<?php
	}

endif;

add_action( 'wp_footer', 'gadgetexpress_login_modal' );

/**
 * Add newsletter modal to footer
 */

if ( ! function_exists( 'gadgetexpress_newsletter_modal' ) ) :
	function gadgetexpress_newsletter_modal() {
		if ( ! is_page_template( 'template-home-no-footer.php' ) ) {
			return;
		}

		if ( intval( get_post_meta( get_the_ID(), 'hide_newsletter', true ) ) ) {
			return;
		}

		if ( ! get_post_meta( get_the_ID(), 'form', true ) ) {
			return;
		}

		?>
        <span id="gadgetexpress-newsletter-icon" class="newsletter-icon icon-paper-plane hidden-lg"></span>
        <div id="footer-newsletter-modal" class="footer-newsletter-modal gadgetexpress-modal">
            <div class="form-wrapper-modal">
				<?php gadgetexpress_footer_newsletter_full_slider(); ?>
            </div>

            <a href="#" class="close-modal">
                <span><?php esc_html_e( 'Close', 'gadget' ) ?></span>
                <i class="icon-cross"></i>
            </a>
        </div>
		<?php
	}
endif;

add_action( 'wp_footer', 'gadgetexpress_newsletter_modal' );
