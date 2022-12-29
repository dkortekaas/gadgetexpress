<?php
/**
 * Toffedassen theme Sidebars
 *
 * @package Toffedassen
 */


/**
 * Register widgetized area and update sidebar with default widgets.
 *
 * @since 1.0
 *
 * @return void
 */
function gadgetexpress_register_sidebar() {
	$sidebars = array(
		'topbar-left'     => esc_html__( 'Topbar Left', 'gadget' ),
		'topbar-right'    => esc_html__( 'Topbar Right', 'gadget' ),
		'topbar-mobile'   => esc_html__( 'Mobile Topbar', 'gadget' ),
		'blog-sidebar'    => esc_html__( 'Blog Sidebar', 'gadget' ),
		'menu-sidebar'    => esc_html__( 'Menu Sidebar', 'gadget' ),
		'catalog-sidebar' => esc_html__( 'Catalog Sidebar', 'gadget' ),
		'product-sidebar' => esc_html__( 'Product Sidebar', 'gadget' ),
		'catalog-filter'  => esc_html__( 'Catalog Filter', 'gadget' ),
	);

	// Register sidebars
	foreach ( $sidebars as $id => $name ) {
		register_sidebar(
			array(
				'name'          => $name,
				'id'            => $id,
				'description'   => esc_html__( 'Add widgets here in order to display on pages', 'gadget' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			)
		);
	}

	register_sidebar(
		array(
			'name'          => esc_html__( 'Mobile Menu Sidebar', 'gadget' ),
			'id'            => 'mobile-menu-sidebar',
			'description'   => esc_html__( 'Add widgets here in order to display menu sidebar on mobile', 'gadget' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="widget-title">',
			'after_title'   => '</h4>',
		)
	);

	// Register footer sidebars
	for ( $i = 1; $i <= 5; $i ++ ) {
		register_sidebar(
			array(
				'name'          => esc_html__( 'Footer Widget', 'gadget' ) . " $i",
				'id'            => "footer-sidebar-$i",
				'description'   => esc_html__( 'Add widgets here in order to display on footer', 'gadget' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			)
		);
	}

	// Register footer sidebars
	for ( $i = 1; $i <= 3; $i ++ ) {
		register_sidebar(
			array(
				'name'          => esc_html__( 'Footer Copyright', 'gadget' ) . " $i",
				'id'            => "footer-copyright-$i",
				'description'   => esc_html__( 'Add widgets here in order to display on footer', 'gadget' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4 class="widget-title">',
				'after_title'   => '</h4>',
			)
		);
	}
}

add_action( 'widgets_init', 'gadgetexpress_register_sidebar' );