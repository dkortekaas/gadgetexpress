<?php
$breadcrumb = gadgetexpress_get_option( 'catalog_page_header_breadcrumbs' );
$layout = gadgetexpress_get_option( 'catalog_page_header_layout' );
$ph_class = $breadcrumb ? '' : 'no-breadcrumb';
$ph_class .= ' layout-' . $layout;
?>
<div class="page-header page-header-catalog <?php echo esc_attr( $ph_class ) ?>">
	<div class="page-header-wrapper">
		<div class="page-header-title">
			<?php
			the_archive_title( '<h1>', '</h1>' );
			gadgetexpress_get_breadcrumbs();
			?>
		</div>
		<div class="page-header-shop-toolbar">
			<?php do_action( 'gadgetexpress_page_header_shop_toolbar' ) ?>
		</div>
	</div>
</div>