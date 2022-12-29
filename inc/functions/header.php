<?php
/**
 * Custom functions for header.
 *
 * @package Gadget Express
 */

/**
 * Get nav menu
 *
 * @since  1.0.0
 *
 *
 * @return string
 */
if ( ! function_exists( 'gadgetexpress_nav_menu' ) ) :
	function gadgetexpress_nav_menu() {
		$color   = gadgetexpress_get_option( 'menu_hover_color' );
		$class   = array( 'menu', $color );
		$classes = implode( ' ', $class );

		if ( has_nav_menu( 'primary' ) ) {
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					//'walker'         => new Toffe_Dassen_Mega_Menu_Walker(),
					'menu_class'     => $classes,
				)
			);
		}
	}
endif;

/**
 * Get Menu extra cart
 *
 * @since  1.0.0
 *
 *
 * @return string
 */
if ( ! function_exists( 'gadgetexpress_extra_cart' ) ) :
	function gadgetexpress_extra_cart() {
		$extras = gadgetexpress_get_menu_extras();

		if ( empty( $extras ) || ! in_array( 'cart', $extras ) ) {
			return '';
		}

		if ( ! function_exists( 'woocommerce_mini_cart' ) ) {
			return '';
		}
		global $woocommerce;

		$icon_cart = '<i class="t-icon icon-cart"></i>';
		$icon_cart = apply_filters( 'gadgetexpress_icon_cart', $icon_cart );

		$cart_html = esc_html( get_post_meta( get_the_ID(), 'header_cart_text', true ) );

		$cart_html = $cart_html ? $cart_html : esc_html__( 'Shopping Cart', 'gadget' );

		printf(
			'<li class="menu-item-cart extra-menu-item">
				<a class="cart-contents" id="icon-cart-contents" href="%s">
					%s
					<span class="label-item cart-label">%s</span>
					<span class="mini-cart-counter">%s</span>
				</a>
			</li>',
			esc_url( wc_get_cart_url() ),
			wp_kses_post( $icon_cart ),
			wp_kses( $cart_html, wp_kses_allowed_html( 'post' ) ),
			intval( $woocommerce->cart->cart_contents_count )
		);

	}
endif;

/**
 * Get Menu extra search
 *
 * @since  1.0.0
 *
 *
 * @return string
 */
if ( ! function_exists( 'gadgetexpress_extra_search' ) ) :
	function gadgetexpress_extra_search() {
		$extras = gadgetexpress_get_menu_extras();
		$layout = gadgetexpress_get_option( 'header_layout' );

		if ( empty( $extras ) ) {
			return;
		}

		if ( ! in_array( 'search', $extras ) ) {
			return;
		}

		$items          = '<a href="#" class="menu-extra-search"><i class="t-icon icon-magnifier"></i></a>';
		$css_class      = '';
		$post_type_html = '';

		if ( gadgetexpress_get_option( 'search_content_type' ) == 'products' && function_exists( 'is_woocommerce' ) ) {
			$post_type_html = '<input type="hidden" name="post_type" value="product">';
		}

		if ( $layout != '3' && $layout != '5' && $layout != '6' ) {
			$css_class = 'search-modal';
		}

		$items .= sprintf(
			'<form method="get" class="instance-search" action="%s">' .
			'<input type="text" name="s" placeholder="%s..." class="search-field" autocomplete="off">' .
			'%s' .
			'<i class="t-icon icon-magnifier"></i>' .
			'</form>' .
			'<div class="loading">' .
			'<span class="gadgetexpress-loader"></span>' .
			'</div>' .
			'<div class="search-results">' .
			'<div class="woocommerce"></div>' .
			'</div>',
			esc_url( home_url( '/' ) ),
			esc_html__( 'Search gadgets', 'gadget' ),
			$post_type_html
		);

		echo sprintf(
			'<li class="extra-menu-item menu-item-search %s">%s</li>',
			esc_attr( $css_class ),
			$items
		);

	}

endif;

/**
 * Get Menu extra Account
 *
 * @since  1.0.0
 *
 * @return string
 */
if ( ! function_exists( 'gadgetexpress_extra_account' ) ) :
	function gadgetexpress_extra_account() {
		$items  = '';
		if ( ! class_exists( 'WooCommerce' ) ) {
			return $items;
		}

		$extras = gadgetexpress_get_menu_extras();


		if ( empty( $extras ) || ! in_array( 'account', $extras ) ) {
			return $items;
		}

		$wishlist = '';
		if ( shortcode_exists( 'yith_wcwl_add_to_wishlist' ) ) {
			$wishlist = sprintf(
				'<li>
					<a href="%s">%s</a>
				</li>',
				esc_url( get_permalink( get_option( 'yith_wcwl_wishlist_page_id' ) ) ),
				esc_html__( 'My Wishlist', 'gadget' )
			);
		}

		$login_id = '';

		if ( ! is_user_logged_in() ) {
			$login_id = 'menu-extra-login';
		}

		$acc_label = esc_html( get_post_meta( get_the_ID(), 'header_account_text', true ) );

		$acc_label = $acc_label ? $acc_label : esc_html__( 'My Account', 'gadget' );

		$icon_acc = '<i class="t-icon icon-user"></i>';
		$icon_acc = apply_filters( 'gadgetexpress_icon_account', $icon_acc );

		$acc_html = sprintf(
			'<a id="%s" href="%s">%s<span class="label-item acc-label">%s</span></a>',
			esc_attr( $login_id ),
			esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ),
			$icon_acc,
			esc_html( $acc_label )
		);

		if ( is_user_logged_in() ) {
			$orders  = get_option( 'woocommerce_myaccount_orders_endpoint', 'orders' );
			$account = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );

			if ( $orders ) {
				$account .= $orders;
			}

			if ( has_nav_menu( 'user_logged' ) ) {
				$user_menu = wp_nav_menu(
					array(
						'theme_location' => 'user_logged',
						'container'      => false,
						'echo'           => 0,
					)
				);
			} else {
				$user_menu = sprintf(
					'<ul>
						%s
						<li>
							<a href="%s">%s</a>
						</li>
						<li>
							<a href="%s">%s</a>
						</li>
					</ul>',
					$wishlist,
					esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ),
					esc_html__( 'Account Settings', 'gadget' ),
					esc_url( $account ),
					esc_html__( 'Orders History', 'gadget' )
				);
			}

			echo sprintf(
				'<li class="extra-menu-item menu-item-account logined">
					%s
					<div class="wrapper dropdown-submenu">
						%s
						<ul>
							<li>
								<a href="%s">%s</a>
							</li>
						</ul>
					</div>
				</li>',
				$acc_html,
				$user_menu,
				esc_url( wp_logout_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ) ),
				esc_html__( 'Logout', 'gadget' )
			);
		} else {

			echo sprintf(
				'<li class="extra-menu-item menu-item-account">
					%s
				</li>',
				$acc_html
			);
		}
	}

endif;

/**
 * Get Menu extra WishList
 *
 * @since  1.0.0
 *
 * @return string
 */
if ( ! function_exists( 'gadgetexpress_extra_wishlist' ) ) :
	function gadgetexpress_extra_wishlist() {
		$extras = gadgetexpress_get_menu_extras();
		$items  = '';

		if ( empty( $extras ) || ! in_array( 'wishlist', $extras ) ) {
			return $items;
		}

		if ( ! function_exists( 'YITH_WCWL' ) ) {
			return '';
		}

		$wishlist_label = esc_html( get_post_meta( get_the_ID(), 'header_wishlist_text', true ) );

		$wishlist_label = $wishlist_label ? $wishlist_label : esc_html__( 'My Wishlist', 'gadget' );

		$icon_wishlist = '<i class="t-icon icon-heart"></i>';
		$icon_wishlist = apply_filters( 'gadgetexpress_icon_wishlist', $icon_wishlist );

		if ( ! shortcode_exists( 'yith_wcwl_add_to_wishlist' ) ) {
			return '';
		}

		echo sprintf(
			'<li class="extra-menu-item menu-item-wishlist">
					<a href="%s">
					%s
					<span class="label-item wishlist-label">%s</span>
					</a>
				</li>',
			esc_url( get_permalink( get_option( 'yith_wcwl_wishlist_page_id' ) ) ),
			$icon_wishlist,
			$wishlist_label
		);

	}

endif;


/**
 * Get Menu extra sidebar
 *
 * @since  1.0.0
 *
 *
 * @return string
 */
if ( ! function_exists( 'gadgetexpress_extra_sidebar' ) ) :
	function gadgetexpress_extra_sidebar() {
		$extras = gadgetexpress_get_menu_extras();

		if ( empty( $extras ) || ! in_array( 'sidebar', $extras ) ) {
			return '';
		}

		$icon_cart  = '<i class="t-icon icon-menu"></i>';
		$menu_class = '';
		$icon_cart  = apply_filters( 'gadgetexpress_icon_menu', $icon_cart );

		$menu_text = '';

		if ( ! is_page_template( 'template-home-left-sidebar.php' ) ) {
			$menu_text = esc_html( gadgetexpress_get_option( 'header_menu_text' ) );
			if ( ! empty( $menu_text ) ) {
				$icon_cart = '<span class="hidden-xs">' . $menu_text . '</span>' . $icon_cart;
			}
		}

		printf(
			'<li class="extra-menu-item menu-item-sidebar %s hidden-md hidden-sm hidden-xs">
				<a class="menu-sidebar" id="icon-menu-sidebar" href="#">
					%s
				</a>
			</li>',
			esc_attr( $menu_class ),
			wp_kses_post( $icon_cart )
		);

	}

endif;


/**
 * Get Menu extra language switcher
 *
 * @since  1.0.0
 *
 *
 * @return string
 */
if ( ! function_exists( 'gadgetexpress_extra_language_switcher' ) ) :
	function gadgetexpress_extra_language_switcher ( $langclass ) {
		$languages = icl_get_languages('skip_missing=0&orderby=code');

		if ( !empty ( $languages ) ) :
			echo '<li class="extra-menu-item menu-item-sidebar menu-item-language-switcher ' . $langclass . '">';
			foreach ( $languages as $l) :
				if ( !$l['active'] ) echo '<a href="'.$l['url'].'" title="'.$l['native_name'].'">';
				echo '<img src="'.$l['country_flag_url'].'" height="12" alt="'.$l['language_code'].'" width="18" />';
				if ( !$l['active'] ) echo '</a>';
			endforeach;
			echo '</li>';
		endif;
	}
endif;

/**
 * Get Menu extra sidebar
 *
 * @since  1.0.0
 *
 *
 * @return string
 */
if ( ! function_exists( 'gadgetexpress_menu_mobile' ) ) :
	function gadgetexpress_menu_mobile() {
		$has_menu_mobile = false;
		$sidebar         = 'mobile-menu-sidebar';
		if ( is_active_sidebar( $sidebar ) ) {
			$has_menu_mobile = true;
		} else {
			$sidebar = 'menu-sidebar';
			if ( is_active_sidebar( $sidebar ) ) {
				$has_menu_mobile = true;
			} else {
				if ( has_nav_menu( 'primary' ) ) {
					$has_menu_mobile = true;
				}
			}
		}

		if ( ! $has_menu_mobile ) {
			return;
		}

		?>
		<li class="extra-menu-item menu-item-sidebar hidden-lg">
			<a class="menu-sidebar" id="icon-menu-mobile" href="#">
				<i class="t-icon icon-menu"></i>
			</a>
		</li>

		<?php

	}

endif;

/**
 * Display socials in footer
 *
 * @since 1.0.0
 */

if ( ! function_exists( 'gadgetexpress_get_socials_html' ) ) :
	function gadgetexpress_get_socials_html( $socials_options, $display = 'icon' ) {

		if ( ! $socials_options ) {
			return;
		}

		$socials = gadgetexpress_get_socials();

		if ( $socials_options ) {

			printf( '<div class="socials">' );
			foreach ( $socials_options as $social ) {
				foreach ( $socials as $name => $label ) {
					if ( isset( $social['link_url'] ) ) {
						$link_url = $social['link_url'];
					} else {
						$link_url = $social;
					}

					if ( preg_match( '/' . $name . '/', $link_url ) ) {

						if ( $display == 'name' ) {
							if ( preg_match( '/' . $name . '/', $link_url ) ) {
								printf( '<a class="share-social share-%s" href="%s" target="_blank">%s</a>', esc_attr( $name ), esc_url( $link_url ), strtoupper( $name ) );
							}

						} else {
							if ( $name == 'google' ) {
								$name = 'googleplus';
							}

							if ( $name == 'vk' ) {
								printf( '<a href="%s" target="_blank" class="font-fa share-social share-%s"><i class="social fa fa-%s"></i></a>', esc_url( $link_url ), esc_attr( $name ), esc_attr( $name ) );
							} else {
								printf( '<a class="share-social share-%s" href="%s" target="_blank"><i class="social social_%s"></i></a>', esc_attr( $name ), esc_url( $link_url ), esc_attr( $name ) );
							}
						}

						break;

					}
				}
			}
		}
		printf( '</div>' );
	}

endif;

/**
 * Get Menu extra
 *
 * @since  1.0.0
 *
 *
 * @return string
 */
if ( ! function_exists( 'gadgetexpress_get_menu_extras' ) ) :
	function gadgetexpress_get_menu_extras() {
		return gadgetexpress_get_option( 'menu_extras' );
	}

endif;

/**
 * Get Page Css
 *
 * @since  1.0.0
 *
 *
 * @return string
 */
if ( ! function_exists( 'gadgetexpress_get_page_custom_css' ) ) :
	function gadgetexpress_get_page_custom_css() {
		$id            = get_post_meta( get_the_ID(), 'image', true );
		$bg_color      = get_post_meta( get_the_ID(), 'color', true );
		$bg_horizontal = get_post_meta( get_the_ID(), 'background_horizontal', true );
		$bg_vertical   = get_post_meta( get_the_ID(), 'background_vertical', true );
		$bg_repeat     = get_post_meta( get_the_ID(), 'background_repeat', true );
		$bg_attachment = get_post_meta( get_the_ID(), 'background_attachment', true );
		$bg_size       = get_post_meta( get_the_ID(), 'background_size', true );

		$url = wp_get_attachment_image_src( $id, 'full' );

		$class = '.page-id-' . get_the_ID();

		$bg_css = ! empty( $bg_color ) ? "background-color: {$bg_color};" : '';
		$bg_css .= ! empty( $url ) ? "background-image: url( " . esc_url( $url[0] ) . " );" : '';

		$bg_css .= ! empty( $bg_repeat ) ? "background-repeat: {$bg_repeat};" : '';

		if ( ! empty( $bg_horizontal ) || ! empty( $bg_vertical ) ) {
			$bg_css .= "background-position: {$bg_horizontal} {$bg_vertical};";
		}

		$bg_css .= ! empty( $bg_attachment ) ? "background-attachment: {$bg_attachment};" : '';

		$bg_css .= ! empty( $bg_size ) ? "background-size: {$bg_size};" : '';

		if ( $bg_css ) {
			$bg_css = $class . '{' . $bg_css . '}';
			$bg_css .= '@media (max-width: 1199px) { ' . $class . ' { background: #fff; } }';
		}

		return $bg_css;
	}

endif;

/**
 * Get Header Css
 *
 * @since  1.0.0
 *
 *
 * @return string
 */
if ( ! function_exists( 'gadgetexpress_header_css' ) ) :
	function gadgetexpress_header_css() {
		$css = '';

		// Header Color
		$header_color = gadgetexpress_get_option( 'header_text_color' );
		$h_color      = '';

		if ( get_post_meta( get_the_ID(), 'custom_header', true ) ) {
			$header_color = get_post_meta( get_the_ID(), 'header_text_color', true );
		}

		if ( $header_color == 'custom' ) {
			$h_color = gadgetexpress_get_option( 'header_text_custom_color' );

			if ( get_post_meta( get_the_ID(), 'custom_header', true ) ) {
				$h_color = get_post_meta( get_the_ID(), 'header_color', true );
			}
		}

		if ( $h_color ) {
			$css .= '.header-color-custom .nav ul.menu > li > a,
			 		.header-color-custom .menu-extra ul > li > a,
			 		.header-color-custom .site-header .menu-extra .menu-item-search .t-icon { color: ' . $h_color . '; }';
			$css .= '.header-color-custom .nav ul.menu > li > a:after { background-color: ' . $h_color . '; }';
		}

		// Menu Hover Custom Color
		$menu_color = gadgetexpress_get_option( 'menu_hover_color' );
		$m_color    = '';

		if ( $menu_color == 'custom-color' ) {
			$m_color = gadgetexpress_get_option( 'menu_hover_custom_color' );
		}

		if ( $m_color ) {
			$css .= '.nav ul.menu.custom-color > li:hover > a,
				.nav ul.menu.custom-color > li.current-menu-item > a,
				.nav ul.menu.custom-color > li.current_page_item > a,
				.nav ul.menu.custom-color > li.current-menu-ancestor > a,
				.nav ul.menu.custom-color > li.current-menu-parent > a,
				.nav ul.menu.custom-color > li.active > a { color: ' . $m_color . '; }';
			$css .= '.nav ul.menu.custom-color > li:hover > a:after,
				.nav ul.menu.custom-color > li.current-menu-item > a:after,
				.nav ul.menu.custom-color > li.current_page_item > a:after,
				.nav ul.menu.custom-color > li.current-menu-ancestor > a:after,
				.nav ul.menu.custom-color > li.current-menu-parent > a:after,
				.nav ul.menu.custom-color > li.active > a:after { background-color: ' . $m_color . '; }';
		}

		return $css;
	}

endif;