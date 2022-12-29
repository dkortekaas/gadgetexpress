<?php
/**
 * Hooks for frontend display
 *
 * @package Gadget Express
 */


/**
 * Adds custom classes to the array of body classes.
 *
 * @since 1.0
 *
 * @param array $classes Classes for the body element.
 *
 * @return array
 */
function gadgetexpress_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	$header_layout = gadgetexpress_get_option( 'header_layout' );
	$custom_header = gadgetexpress_get_post_meta( 'custom_header' );

	if ( intval( gadgetexpress_get_option( 'topbar_enable' ) ) ) {
		$classes[] = 'topbar-enable';
	}

	if ( is_page_template( 'template-home-left-sidebar.php' ) ) {
		$classes[] = 'header-left-sidebar';
	} else {
		$classes[] = 'header-layout-' . $header_layout;
	}

	if ( is_singular( 'post' ) ) {
		$classes[] = gadgetexpress_get_option( 'single_post_layout' );

	} elseif ( gadgetexpress_is_blog() ) {
		$classes[] = 'gadgetexpress-blog-page';
		$classes[] = 'blog-' . gadgetexpress_get_option( 'blog_style' );
		$classes[] = gadgetexpress_get_option( 'blog_layout' );

	} else {
		$classes[] = gadgetexpress_get_layout();
	}

	if ( gadgetexpress_is_catalog() ) {
		$classes[] = 'gadgetexpress-catalog-page';
		$classes[] = 'gadgetexpress-catalog-mobile-' . intval( gadgetexpress_get_option( 'catalog_mobile_columns' ) ) . '-columns';

		$view      = isset( $_COOKIE['shop_view'] ) ? $_COOKIE['shop_view'] : gadgetexpress_get_option( 'shop_view' );
		$classes[] = 'shop-view-' . $view;

		if ( intval( gadgetexpress_get_option( 'catalog_ajax_filter' ) ) ) {
			$classes[] = 'catalog-ajax-filter';
		}

		if ( intval( gadgetexpress_get_option( 'catalog_full_width' ) ) ) {
			$classes[] = 'catalog-full-width-layout';
		}

		if ( intval( gadgetexpress_get_option( 'catalog_filter_mobile' ) ) ) {
			$classes[] = 'filter-mobile-enable';
		}
	}

	if ( function_exists( 'is_product' ) && is_product() ) {
		$classes[] = 'single-product-layout-' . gadgetexpress_get_option( 'single_product_layout' );

		if ( '1' == gadgetexpress_get_option( 'single_product_layout' ) ) {
			$classes[] = gadgetexpress_get_option( 'single_product_sidebar' );
		}
	}

	if ( gadgetexpress_header_transparent() ) {
		$classes[] = 'header-transparent';

		if ( $custom_header ) {
			$text_color = gadgetexpress_get_post_meta( 'header_text_color' );
		} else {
			$text_color = gadgetexpress_get_option( 'header_text_color' );
		}

		$classes[] = 'header-color-' . $text_color;
	}

	if ( gadgetexpress_header_sticky() ) {
		$classes[] = 'header-sticky';
	}

	$header_border = gadgetexpress_get_post_meta( 'header_border' );

	if (
		( gadgetexpress_is_home() && ! is_page_template( 'template-home-left-sidebar.php' ) ) ||
		( $custom_header && $header_border )
	) {
		$classes[] = 'header-no-border';
	}

	if ( is_page_template( 'template-home-boxed.php' ) ) {
		$id       = get_post_meta( get_the_ID(), 'image', true );
		$bg_color = get_post_meta( get_the_ID(), 'color', true );

		if ( ! $id && ! $bg_color ) {
			$classes[] = 'no-background';
		}
	}

	if ( gadgetexpress_is_home() && ! is_page_template( 'template-home-left-sidebar.php' ) && intval( gadgetexpress_get_option( 'boxed_layout' ) ) ) {
		$classes[] = 'gadgetexpress-boxed-layout';
	}

	$p_style    = gadgetexpress_get_option( 'portfolio_layout' );
	$p_nav_type = gadgetexpress_get_option( 'portfolio_nav_type' );

	if ( gadgetexpress_is_portfolio() ) {
		$classes[] = 'portfolio-' . $p_style;
		$classes[] = 'portfolio-' . $p_nav_type;
		$classes[] = 'gadgetexpress-portfolio-page';
	}

	return $classes;
}

add_filter( 'body_class', 'gadgetexpress_body_classes' );
