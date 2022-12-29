<?php
/**
 * Hooks for template header
 *
 * @package Gadget Express
 */

/**
 * Enqueue scripts and styles.
 *
 * @since 1.0
 */
function gadgetexpress_enqueue_scripts() {
	/**
	 * Register and enqueue styles
	 */
	wp_register_style( 'gadgetexpress-fonts', gadgetexpress_fonts_url(), array(), '20180307' );
	//wp_register_style( 'gadgetexpress-style', get_template_directory_uri() . '/assets/css/style.css', array(), '1.2.0' );
	wp_register_style( 'bootstrap', get_template_directory_uri() . '/assets/css/bootstrap.min.css', array(), '3.3.7' );
	wp_register_style( 'eleganticons', get_template_directory_uri() . '/assets/css/eleganticons.min.css', array(), '1.0.0' );
	wp_register_style( 'linearicons', get_template_directory_uri() . '/assets/css/linearicons.min.css', array(), '1.0.0' );
	wp_register_style( 'ionicons', get_template_directory_uri() . '/assets/css/ionicons.min.css', array(), '2.0.0' );
	wp_register_style( 'photoswipe', get_template_directory_uri() . '/assets/css/photoswipe.css', array(), '4.1.1' );
	wp_register_style( 'fontawesome', get_template_directory_uri() . '/assets/css/font-awesome.min.css', array(), '6.2.1' );

	wp_enqueue_style(
		'gadget', get_template_directory_uri() . '/assets/css/style.css', array(
		'gadgetexpress-fonts',
		'bootstrap',
		'eleganticons',
		'linearicons',
		'ionicons',
		'photoswipe',
		'fontawesome'
	), '20161025'
	);

	//wp_add_inline_style( 'gadget', gadgetexpress_customize_css() );

	/**
	 * Register and enqueue scripts
	 */
	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_enqueue_script( 'html5shiv', get_template_directory_uri() . '/assets/js/plugins/html5shiv.min.js', array(), '3.7.2' );
	wp_script_add_data( 'html5shiv', 'conditional', 'lt IE 9' );

	wp_enqueue_script( 'respond', get_template_directory_uri() . '/assets/js/plugins/respond.min.js', array(), '1.4.2' );
	wp_script_add_data( 'respond', 'conditional', 'lt IE 9' );

	wp_register_script( 'photoswipe', get_template_directory_uri() . '/assets/js/plugins/photoswipe.min.js', array(), '4.1.1', true );
	wp_register_script( 'photoswipe-ui', get_template_directory_uri() . '/assets/js/plugins/photoswipe-ui.min.js', array( 'photoswipe' ), '4.1.1', true );

	$lightbox = 'no';
	if ( is_singular() ) {

		wp_enqueue_style( 'photoswipe' );
		wp_enqueue_script( 'photoswipe-ui' );

		$photoswipe_skin = 'photoswipe-default-skin';
		if ( wp_style_is( $photoswipe_skin, 'registered' ) && ! wp_style_is( $photoswipe_skin, 'enqueued' ) ) {
			wp_enqueue_style( $photoswipe_skin );
			$lightbox = 'yes';
		}
	}

	wp_register_script( 'bootstrap', get_template_directory_uri() . '/assets/js/plugins/bootstrap.min.js', array( 'jquery' ), '3.3.7', true );
	wp_register_script( 'slick', get_template_directory_uri() . '/assets/js/plugins/slick.min.js', array( 'jquery' ), '2.0.2', true );
	wp_register_script( 'isotope', get_template_directory_uri() . '/assets/js/plugins/isotope.pkgd.min.js', array( 'jquery' ), '2.2.2', true );
	wp_register_script( 'parallax', get_template_directory_uri() . '/assets/js/plugins/jquery.parallax.min.js', array(), '1.0', true );
	wp_register_script( 'flipclock', get_template_directory_uri() . '/assets/js/plugins/flipclock.min.js', array(), '1.0', true );
	wp_register_script( 'sticky-kit', get_template_directory_uri() . '/assets/js/plugins/sticky-kit.min.js', array( 'jquery' ), '1.1.3', true );
	wp_register_script( 'tabs', get_template_directory_uri() . '/assets/js/plugins/jquery.tabs.js', array(), '1.0', true );
	wp_register_script( 'notify', get_template_directory_uri() . '/assets/js/plugins/notify.min.js', array(), '1.0.0', true );
	wp_register_script( 'tooltip', get_template_directory_uri() . '/assets/js/plugins/jquery-tooltip.js', array(), '2.1.1', true );
	wp_register_script( 'viewport', get_template_directory_uri() . '/assets/js/plugins/isInViewport.min.js', array(), '1.0', true );
	wp_register_script( 'nprogress', get_template_directory_uri() . '/assets/js/plugins/nprogress.js', array(), '1.0.0', true );
	wp_register_script( 'swiper', get_template_directory_uri() . '/assets/js/plugins/swiper.min.js', array(), '4.3.2', true );

	$script_name = 'wc-add-to-cart-variation';
	if ( wp_script_is( $script_name, 'registered' ) && ! wp_script_is( $script_name, 'enqueued' ) ) {
		wp_enqueue_script( $script_name );
	}

	wp_enqueue_script(
		'gadget', get_template_directory_uri() . "/assets/js/scripts$min.js", array(
		'jquery',
		'bootstrap',
		'slick',
		'imagesloaded',
		'isotope',
		'parallax',
		'flipclock',
		'sticky-kit',
		'tabs',
		'notify',
		'tooltip',
		'viewport',
		'swiper',
		'nprogress'
	), '20180307', true
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	$product_thumb_slider     = 0;
	$product_thumb_vertical   = 0;
	$product_gallery_carousel = 0;
	if ( in_array( gadgetexpress_get_option( 'single_product_layout' ), array( '1', '2' ) ) ) {
		$product_thumb_slider = 1;

		if ( gadgetexpress_get_option( 'single_product_layout' ) == '2' ) {
			$product_thumb_vertical = 1;
		}
	} elseif ( in_array( gadgetexpress_get_option( 'single_product_layout' ), array( '5', '6' ) ) ) {
		$product_gallery_carousel = 1;
	}

	wp_localize_script(
		'gadget', 'toffedassenData', array(
			'ajax_url'            => admin_url( 'admin-ajax.php' ),
			'nonce'               => wp_create_nonce( '_gadgetexpress_nonce' ),
			'menu_animation'      => gadgetexpress_get_option( 'menu_animation' ),
			'ajax_search'         => intval( gadgetexpress_get_option( 'header_ajax_search' ) ),
			'search_content_type' => gadgetexpress_get_option( 'search_content_type' ),
			'shop_nav_type'       => gadgetexpress_get_option( 'shop_nav_type' ),
			'add_to_cart_ajax'    => intval( gadgetexpress_get_option( 'product_add_to_cart_ajax' ) ),
			'add_to_cart_action'  => gadgetexpress_get_option( 'add_to_cart_action' ),
			'product'             => array(
				'thumb_slider'     => $product_thumb_slider,
				'thumb_vertical'   => $product_thumb_vertical,
				'gallery_carousel' => $product_gallery_carousel,
				'lightbox'         => $lightbox,
			),
			'l10n'                => array(
				'added_to_cart_notice'  => intval( gadgetexpress_get_option( 'added_to_cart_notice' ) ),
				'notice_text'           => esc_html__( 'has been added to your cart.', 'gadget' ),
				'notice_texts'          => esc_html__( 'have been added to your cart.', 'gadget' ),
				'cart_text'             => esc_html__( 'View Cart', 'gadget' ),
				'cart_link'             => function_exists( 'wc_get_cart_url' ) ? esc_url( wc_get_cart_url() ) : '',
				'cart_notice_auto_hide' => intval( gadgetexpress_get_option( 'cart_notice_auto_hide' ) ) > 0 ? intval( gadgetexpress_get_option( 'cart_notice_auto_hide' ) ) * 1000 : 0,
			),
			'isRTL'               => is_rtl(),
		)
	);
}

add_action( 'wp_enqueue_scripts', 'gadgetexpress_enqueue_scripts' );

/**
 * Enqueues front-end CSS for theme customization
 */
function gadgetexpress_customize_css() {
	$css = '';

	$css .= gadgetexpress_get_page_custom_css();
	$css .= gadgetexpress_header_css();

	// Logo
	$logo_size_width = intval( gadgetexpress_get_option( 'logo_width' ) );
	$logo_css        = $logo_size_width ? 'width:' . $logo_size_width . 'px; ' : '';

	$logo_size_height = intval( gadgetexpress_get_option( 'logo_height' ) );
	$logo_css .= $logo_size_height ? 'height:' . $logo_size_height . 'px; ' : '';

	$logo_margin = gadgetexpress_get_option( 'logo_position' );
	$logo_css .= $logo_margin['top'] ? 'margin-top:' . $logo_margin['top'] . '; ' : '';
	$logo_css .= $logo_margin['right'] ? 'margin-right:' . $logo_margin['right'] . '; ' : '';
	$logo_css .= $logo_margin['bottom'] ? 'margin-bottom:' . $logo_margin['bottom'] . '; ' : '';
	$logo_css .= $logo_margin['left'] ? 'margin-left:' . $logo_margin['left'] . '; ' : '';

	if ( ! empty( $logo_css ) ) {
		$css .= '.site-header .logo img ' . ' {' . $logo_css . '}';
	}

	// Coming Soon Background Image
	$c_background = gadgetexpress_get_option( 'coming_soon_background' );
	$c_bg_color   = gadgetexpress_get_option( 'coming_soon_background_color' );

	if ( $c_background ) {
		$css .= '.page-template-template-coming-soon-page { background-image: url( ' . esc_url( $c_background ) . ' ) } ';
	}

	if ( $c_bg_color ) {
		$css .= '.page-template-template-coming-soon-page:before { background-color: ' . $c_bg_color . '; }';
	}

	// Newsletter
	$n_color = gadgetexpress_get_option( 'newsletter_text_color' );
	$n_bg    = gadgetexpress_get_option( 'newsletter_background_color' );

	if ( $n_bg ) {
		$css .= '.footer-newsletter .mc4wp-form .mc4wp-form-fields { background-color:' . $n_bg . '; }';
	}

	if ( $n_color ) {
		$css .= '.footer-newsletter.gadgetexpress-newsletter .mc4wp-form input[type="email"] { color:' . $n_color . '; }';
		$css .= '.footer-newsletter.gadgetexpress-newsletter .mc4wp-form input[type="submit"] { color:' . $n_color . '; }';
		$css .= '.footer-newsletter.gadgetexpress-newsletter .mc4wp-form .mc4wp-form-fields:after { color:' . $n_color . '; }';
		$css .= '.footer-newsletter.gadgetexpress-newsletter .mc4wp-form ::-webkit-input-placeholder { color:' . $n_color . '; }';
		$css .= '.footer-newsletter.gadgetexpress-newsletter .mc4wp-form :-moz-placeholder { color:' . $n_color . '; }';
		$css .= '.footer-newsletter.gadgetexpress-newsletter .mc4wp-form ::-moz-placeholder { color:' . $n_color . '; }';
		$css .= '.footer-newsletter.gadgetexpress-newsletter .mc4wp-form :-ms-input-placeholder { color:' . $n_color . '; }';
	}

	// Footer
	$footer_copyright_top_spacing    = gadgetexpress_get_option( 'footer_copyright_top_spacing' );
	$footer_copyright_bottom_spacing = gadgetexpress_get_option( 'footer_copyright_bottom_spacing' );
	$footer_copyright_css            = '';

	if ( $footer_copyright_top_spacing ) {
		$footer_copyright_css = 'padding-top:' . $footer_copyright_top_spacing . 'px;';
	}

	if ( $footer_copyright_bottom_spacing ) {
		$footer_copyright_css = 'padding-bottom:' . $footer_copyright_bottom_spacing . 'px;';
	}

	$css .= '.site-footer .footer-copyright {' . $footer_copyright_css . '}';

	// Single Product Background
	$single_product_bg = gadgetexpress_get_option( 'single_product_background_color' );

	if ( $single_product_bg ) {
		$css .= '.woocommerce.single-product-layout-2 .site-header { background-color:' . $single_product_bg . '; }';
		$css .= '.woocommerce.single-product-layout-2 .product-toolbar { background-color:' . $single_product_bg . '; }';
		$css .= '.woocommerce.single-product-layout-2 div.product .gadgetexpress-single-product-detail { background-color:' . $single_product_bg . '; }';
		$css .= '.woocommerce.single-product-layout-2 .su-header-minimized { background-color:' . $single_product_bg . '; }';
	}

	/* Color Scheme */
	$color_scheme_option = gadgetexpress_get_option( 'color_scheme' );

	if ( intval( gadgetexpress_get_option( 'custom_color_scheme' ) ) ) {
		$color_scheme_option = gadgetexpress_get_option( 'custom_color' );
	}

	// Don't do anything if the default color scheme is selected.
	if ( $color_scheme_option ) {
		$css .= gadgetexpress_get_color_scheme_css( $color_scheme_option );
	}

	// Topbar
	$topbar_bg           = gadgetexpress_get_option( 'topbar_background_color' );
	$topbar_color        = gadgetexpress_get_option( 'topbar_color' );
	$topbar_custom_color = gadgetexpress_get_option( 'topbar_custom_color' );

	if ( $topbar_bg ) {
		$css .= '.topbar { background-color:' . $topbar_bg . '; }';
	}

	if ( $topbar_color == 'custom' && $topbar_custom_color ) {
		$css .= '.topbar { color:' . $topbar_custom_color . '; }';
		$css .= '
		.topbar a,
		.topbar .widget_categories li a,
		.topbar .widget_categories li a:hover,
		.topbar .widget_recent_comments li a,
		.topbar .widget_recent_comments li a:hover,
		.topbar .widget_rss li a,
		.topbar .widget_rss li a:hover,
		.topbar .widget_pages li a,
		.topbar .widget_pages li a:hover,
		.topbar .widget_archive li a,
		.topbar .widget_archive li a:hover,
		.topbar .widget_nav_menu li a,
		.topbar .widget_nav_menu li a:hover,
		.topbar .widget_recent_entries li a,
		.topbar .widget_recent_entries li a:hover,
		.topbar .widget_meta li a,
		.topbar .widget_meta li a:hover,
		.topbar .widget-recent-comments li a,
		.topbar .widget-recent-comments li a:hover,
		.topbar .gadgetexpress-social-links-widget .socials-list a,
		.topbar .gadgetexpress-social-links-widget .socials-list a:hover,
		.topbar .widget_search .search-form:before,
		.topbar .widget_search .search-form label input { color:' . $topbar_custom_color . '; }
		';

		$css .= '.topbar .widget_search .search-form ::-webkit-input-placeholder { color:' . $topbar_custom_color . '; }';
		$css .= '.topbar .widget_search .search-form .mc4wp-form :-moz-placeholder { color:' . $topbar_custom_color . '; }';
		$css .= '.topbar .widget_search .search-form .mc4wp-form ::-moz-placeholder { color:' . $topbar_custom_color . '; }';
		$css .= '.topbar .widget_search .search-form .mc4wp-form :-ms-input-placeholder { color:' . $topbar_custom_color . '; }';

		$css .= '
		.topbar .widget_categories li a:after,
		.topbar .widget_recent_comments li a:after,
		.topbar .widget_rss li a:after,
		.topbar .widget_pages li a:after,
		.topbar .widget_archive li a:after,
		.topbar .widget_nav_menu li a:after,
		.topbar .widget_recent_entries li a:after,
		.topbar .widget_meta li a:after,
		.topbar .widget-recent-comments li a:after,
		.topbar .topbar-widgets .widget:after{ background-color:' . $topbar_custom_color . '; }
		';
	}

	$boxed_bg_color = gadgetexpress_get_option( 'boxed_background_color' );
	$boxed_bg_image = gadgetexpress_get_option( 'boxed_background_image' );
	$boxed_bg_h     = gadgetexpress_get_option( 'boxed_background_horizontal' );
	$boxed_bg_v     = gadgetexpress_get_option( 'boxed_background_vertical' );
	$boxed_bg_r     = gadgetexpress_get_option( 'boxed_background_repeat' );
	$boxed_bg_a     = gadgetexpress_get_option( 'boxed_background_attachment' );
	$boxed_bg_s     = gadgetexpress_get_option( 'boxed_background_size' );

	$boxed_style = array(
		! empty( $boxed_bg_color ) ? 'background-color: ' . $boxed_bg_color . ';' : '',
		! empty( $boxed_bg_image ) ? 'background-image: url( ' . esc_url( $boxed_bg_image ) . ' );' : '',
		! empty( $boxed_bg_h ) ? 'background-position-x: ' . $boxed_bg_h . ';' : '',
		! empty( $boxed_bg_v ) ? 'background-position-y: ' . $boxed_bg_v . ';' : '',
		! empty( $boxed_bg_r ) ? 'background-repeat: ' . $boxed_bg_r . ';' : '',
		! empty( $boxed_bg_a ) ? 'background-attachment:' . $boxed_bg_a . ';' : '',
		! empty( $boxed_bg_s ) ? 'background-size: ' . $boxed_bg_s . ';' : '',
	);

	if ( ! empty( $boxed_style ) ) {
		$css .= '.gadgetexpress-boxed-layout ' . ' {' . implode( '', $boxed_style ) . '}';
	}

	$css .= gadgetexpress_typography_css();

	return $css;
}

/**
 * Display header
 */
function gadgetexpress_show_header() {
	$header_layout = gadgetexpress_get_option( 'header_layout' );

	if ( is_page_template( 'template-coming-soon-page.php' ) ) {
		echo '<div class="container">';
		get_template_part( 'parts/logo' );
		echo '</div>';
	} elseif ( is_page_template( 'template-home-left-sidebar.php' ) ) {
		get_template_part( 'parts/headers/header-left-sidebar' );
	} else {
		get_template_part( 'parts/headers/header', $header_layout );
	}
}

add_action( 'gadgetexpress_header', 'gadgetexpress_show_header' );

/**
 * Display topbar on top of site
 *
 * @since 1.0.0
 */
function gadgetexpress_show_topbar() {
	if ( ! intval( gadgetexpress_get_option( 'topbar_enable' ) ) ) {
		return;
	}

	if ( is_active_sidebar( 'topbar-left' ) == false &&
		is_active_sidebar( 'topbar-right' ) == false
	) {
		return;
	}

	$layout = gadgetexpress_get_option( 'topbar_layout' );
	$border = intval( gadgetexpress_get_option( 'topbar_border_bottom' ) );

	$class = 'topbar-layout-' . $layout;

	$class .= $border ? ' has-border' : '';

	$container = $layout == '1' ? 'container' : 'gadgetexpress-container';

	?>
	<div id="topbar" class="topbar hidden-md hidden-sm hidden-xs <?php echo esc_attr( $class ); ?>">
		<div class="<?php echo esc_attr( $container ); ?>">
			<div class="row-flex">
				<?php if ( is_active_sidebar( 'topbar-left' ) ) : ?>

					<div class="topbar-left topbar-widgets text-left row-flex">
						<?php
						ob_start();
						dynamic_sidebar( 'topbar-left' );
						$output = ob_get_clean();

						echo apply_filters( 'gadgetexpress_topbar_left', $output );
						?>
					</div>
				<?php endif; ?>

				<?php if ( is_active_sidebar( 'topbar-right' ) ) : ?>
					<div class="topbar-right topbar-widgets text-right row-flex">
						<?php
						ob_start();
						dynamic_sidebar( 'topbar-right' );
						$output = ob_get_clean();

						echo apply_filters( 'gadgetexpress_topbar_right', $output );
						?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<?php
}

add_action( 'gadgetexpress_before_header', 'gadgetexpress_show_topbar' );

/**
 * Display topbar on top of site
 *
 * @since 1.0.0
 */
function gadgetexpress_show_topbar_mobile() {
	if ( ! intval( gadgetexpress_get_option( 'topbar_enable' ) ) ) {
		return;
	}

	if ( is_active_sidebar( 'topbar-mobile' ) == false ) {
		return;
	}

	$topbar_flex = gadgetexpress_get_option( 'topbar_mobile_content' );

	$style_wrapper = 'justify-content:' . $topbar_flex . ';';

	?>
	<div class="topbar topbar-mobile hidden-lg">
		<div class="container">
			<div class="topbar-widgets row-flex" style="<?php echo esc_attr( $style_wrapper ) ?>">
				<?php
				ob_start();
				dynamic_sidebar( 'topbar-mobile' );
				$output = ob_get_clean();

				echo apply_filters( 'gadgetexpress_topbar_mobile', $output );
				?>
			</div>
		</div>
	</div>
	<?php
}

add_action( 'gadgetexpress_before_header', 'gadgetexpress_show_topbar_mobile' );

/**
 * Display the header minimized
 *
 * @since 1.0.0
 */
function gadgetexpress_header_minimized() {
	if ( gadgetexpress_get_option( 'header_sticky' ) == false ) {
		return;
	}

	if ( is_page_template( 'template-home-left-sidebar.php' ) ) {
		return;
	}

	$css_class = 'su-header-' . gadgetexpress_get_option( 'header_layout' );

	printf( '<div id="su-header-minimized" class="su-header-minimized %s"></div>', esc_attr( $css_class ) );

}

add_action( 'gadgetexpress_before_header', 'gadgetexpress_header_minimized' );

/**
 * Show page header
 *
 * @since 1.0.0
 */
function gadgetexpress_show_page_header() {

	if ( gadgetexpress_is_home() ||
		is_page_template( 'template-coming-soon-page.php' )
	) {
		return;
	}

	if ( gadgetexpress_is_catalog() ) {
		return;
	}

	if ( is_singular( 'portfolio' ) ) {
		return;
	}

	$page_header = gadgetexpress_get_page_header();

	if ( ! $page_header ) {
		return;
	}

	$layout = 1;

	if ( $page_header && isset( $page_header['layout'] ) ) {
		$layout = $page_header['layout'];
	}

	if ( gadgetexpress_is_blog() ) {
		get_template_part( 'parts/page-headers/blog', $layout );
	} elseif ( is_front_page() ) {

	} else {
		get_template_part( 'parts/page-headers/default' );
	}

	?>
	<?php
}

add_action( 'gadgetexpress_after_header', 'gadgetexpress_show_page_header', 20 );

/**
 * Returns CSS for the color schemes.
 *
 *
 * @param array $colors Color scheme colors.
 *
 * @return string Color scheme CSS.
 */
function gadgetexpress_get_color_scheme_css( $colors ) {
	return <<<CSS

	/* Background Color */

	.slick-dots li:hover,.slick-dots li.slick-active,
	.owl-nav div:hover,
	.owl-dots .owl-dot.active span,.owl-dots .owl-dot:hover span,
	#nprogress .bar,
	.primary-background-color,
	.site-header .menu-extra .menu-item-cart .mini-cart-counter,.site-header .menu-extra .menu-item-wishlist .mini-cart-counter,
	.nav ul.menu.primary-color > li:hover > a:after,.nav ul.menu.primary-color > li.current-menu-item > a:after,.nav ul.menu.primary-color > li.current_page_item > a:after,.nav ul.menu.primary-color > li.current-menu-ancestor > a:after,.nav ul.menu.primary-color > li.current-menu-parent > a:after,.nav ul.menu.primary-color > li.active > a:after,
	.woocommerce div.product div.images .product-gallery-control .item-icon span,
	.woocommerce .widget_price_filter .ui-slider .ui-slider-range,
	span.mb-siwc-tag,
	.gadgetexpress-products-grid.style-2 a.ajax-load-products .button-text,
	.gadgetexpress-banner-grid.btn-style-2 .banner-btn,
	.gadgetexpress-socials.socials-border a:hover,
	.footer-layout.dark-skin .gadgetexpress-social-links-widget .socials-list.style-2 a:hover,
	.blog-page-header h1:after{background-color: $colors}

	/* Border Color */

	.slick-dots li,
	.owl-nav div:hover,
	.owl-dots .owl-dot span,
	.gadgetexpress-social-links-widget .socials-list.style-2 a:hover,
	.gadgetexpress-socials.socials-border a:hover{border-color: $colors}

	/* Color */
	.search-modal .product-cats label span:hover,
	.search-modal .product-cats input:checked + span,
	.search-modal .search-results ul li .search-item:hover .title,
	blockquote cite,
	blockquote cite a,
	.primary-color,
	.nav ul.menu.primary-color > li:hover > a,.nav ul.menu.primary-color > li.current-menu-item > a,.nav ul.menu.primary-color > li.current_page_item > a,.nav ul.menu.primary-color > li.current-menu-ancestor > a,.nav ul.menu.primary-color > li.current-menu-parent > a,.nav ul.menu.primary-color > li.active > a,
	.nav .menu .is-mega-menu .dropdown-submenu .menu-item-mega > a:hover,
	.blog-wrapper .entry-metas .entry-cat,
	.blog-wrapper.sticky .entry-title:before,
	.single-post .entry-cat,
	.gadgetexpress-related-posts .blog-wrapper .entry-cat,
	.error404 .error-404 .page-content a,
	.error404 .error-404 .page-content .error-icon,
	.list-portfolio .portfolio-wrapper .entry-title:hover,
	.list-portfolio .portfolio-wrapper .entry-title:hover a,
	.single-portfolio-entry-meta .socials a:hover,
	.widget-about a:hover,
	.gadgetexpress-social-links-widget .socials-list a:hover,
	.gadgetexpress-social-links-widget .socials-list.style-2 a:hover,
	.gadgetexpress-language-currency .widget-lan-cur ul li.actived a,
	.shop-widget-info .w-icon,
	.woocommerce ul.products li.product.product-category:hover .woocommerce-loop-category__title,.woocommerce ul.products li.product.product-category:hover .count,
	.woocommerce div.product div.images .product-gallery-control .item-icon:hover:before,
	.woocommerce-checkout table.shop_table .order-total .woocommerce-Price-amount,
	.woocommerce-account .woocommerce .woocommerce-Addresses .woocommerce-Address .woocommerce-Address-edit .edit:hover,
	.woocommerce-account .customer-login .form-row-password .lost-password,
	.gadgetexpress-icons-box i,
	.gadgetexpress-banner-grid-4 .banner-grid__banner .banner-grid__link:hover .banner-title,
	.gadgetexpress-product-banner .banner-url:hover .title,
	.gadgetexpress-product-banner3 .banner-wrapper:hover .banner-title,
	.gadgetexpress-sale-product.style-2 .flip-clock-wrapper .flip-wrapper .inn,
	.gadgetexpress-faq_group .g-title,
	.wpcf7-form .require{color: $colors}

	/* Other */
	.gadgetexpress-loader:after,
	.gadgetexpress-sliders:after,
	.gadgetexpress-sliders:after,
	.woocommerce .blockUI.blockOverlay:after { border-color: $colors $colors $colors transparent }

	.woocommerce div.product div.images .product-gallery-control .item-icon span:before { border-color: transparent transparent transparent $colors; }

	.woocommerce.single-product-layout-6 div.product div.images .product-gallery-control .item-icon span:before { border-color: transparent $colors transparent transparent; }

	#nprogress .peg {
		-webkit-box-shadow: 0 0 10px $colors, 0 0 5px $colors;
			  box-shadow: 0 0 10px $colors, 0 0 5px $colors;
	}
CSS;
}

if ( ! function_exists( 'gadgetexpress_typography_css' ) ) :
	/**
	 * Get typography CSS base on settings
	 *
	 * @since 1.1.6
	 */
	function gadgetexpress_typography_css() {
		$css        = '';
		$properties = array(
			'font-family'    => 'font-family',
			'font-size'      => 'font-size',
			'variant'        => 'font-weight',
			'line-height'    => 'line-height',
			'letter-spacing' => 'letter-spacing',
			'color'          => 'color',
			'text-transform' => 'text-transform',
			'text-align'     => 'text-align',
		);

		$settings = array(
			'body_typo'        => 'body',
			'heading1_typo'    => 'h1',
			'heading2_typo'    => 'h2',
			'heading3_typo'    => 'h3',
			'heading4_typo'    => 'h4',
			'heading5_typo'    => 'h5',
			'heading6_typo'    => 'h6',
			'menu_typo'        => '.nav a, .nav .menu .is-mega-menu .dropdown-submenu .menu-item-mega > a',
			'sub_menu_typo'    => '.nav li li a, .gadgetexpress-language-currency .widget-lan-cur ul li a',
			'footer_text_typo' => '.site-footer',
		);

		foreach ( $settings as $setting => $selector ) {
			$typography = gadgetexpress_get_option( $setting );
			$default    = (array) gadgetexpress_get_option_default( $setting );
			$style      = '';

			foreach ( $properties as $key => $property ) {
				if ( isset( $typography[$key] ) && ! empty( $typography[$key] ) ) {
					if ( isset( $default[$key] ) && strtoupper( $default[$key] ) == strtoupper( $typography[$key] ) ) {
						continue;
					}

					$value = $typography[$key];

					if ( 'font-family' == $key ) {
						if (
							trim( $typography[$key] ) != '' &
							trim( $typography[$key] ) != ',' &
							strtolower( $typography[$key] ) !== 'cerebri sans'
						) {
							$value = '"' . rtrim( trim( $typography[$key] ), ',' ) . '"';

							$style .= 'font-family:' . $value . ', Arial, sans-serif;';
						}
					} else {
						$value = 'variant' == $key ? str_replace( 'regular', '400', $value ) : $value;

						if ( $value ) {
							$style .= $property . ': ' . $value . ';';
						}
					}
				}
			}

			if ( ! empty( $style ) ) {
				$css .= $selector . '{' . $style . '}';
			}
		}

		return $css;
	}
endif;
