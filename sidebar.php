<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package Gadget Express
 */
if ( 'full-content' == gadgetexpress_get_layout() ) {
	return;
}

$sidebar = 'blog-sidebar';

if ( gadgetexpress_is_catalog() ) {
	$sidebar = 'catalog-sidebar';
}

if ( is_singular('product') && gadgetexpress_get_option( 'single_product_layout' ) == '1' ) {
	$sidebar = 'product-sidebar';
}

?>
<aside id="primary-sidebar" class="widgets-area primary-sidebar <?php echo esc_attr( $sidebar ) ?> col-xs-12 col-sm-12 col-md-3">
	<?php
	/*
	 * gadgetexpress_open_wrapper_catalog_content_sidebar -10
	 *
	 */
	do_action( 'gadgetexpress_before_sidebar_content' );

	if (is_active_sidebar($sidebar)) {
		dynamic_sidebar($sidebar);
	}

	/*
	 * gadgetexpress_open_wrapper_catalog_content_sidebar -100
	 *
	 */
	do_action( 'gadgetexpress_after_sidebar_content' );

	?>
</aside><!-- #secondary -->
