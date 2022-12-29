<?php
$css        = '';
$image      = gadgetexpress_get_option( 'page_header_background' );
$parallax   = gadgetexpress_get_option( 'page_header_parallax' );
$text_color = gadgetexpress_get_option( 'page_header_text_color' );

if ( get_post_meta( get_the_ID(), 'page_header_custom_layout', true ) ) {
	if ( $custom_bg = get_post_meta( get_the_ID(), 'page_header_bg', true ) ) {

		$image = wp_get_attachment_url( $custom_bg );
	}

	$text_color = get_post_meta( get_the_ID(), 'text_color', true );
	$parallax   = intval( get_post_meta( get_the_ID(), 'parallax', true ) );
}

if ( ! $image ) {
	$css .= ' no-bg';
} else {
	if ( intval( $parallax ) ) {
		$css .= ' parallax';
	}
}

$css .= ' text-' . $text_color;

?>
<div class="page-header page-header-default text-center <?php echo esc_attr( $css ); ?>">
	<div class="feature-image" style="background-image:url( <?php echo esc_url( $image ) ?> );"></div>
	<div class="container">
		<?php
		the_archive_title( '<h1>', '</h1>' );
		gadgetexpress_get_breadcrumbs();
		?>
	</div>
</div>