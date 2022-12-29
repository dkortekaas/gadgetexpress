<?php
$classes = array(
	'footer-layout',
	'footer-layout-' . gadgetexpress_get_option( 'footer_layout' ),
	gadgetexpress_get_option( 'footer_skin' ) . '-skin'
);

if ( ! intval( gadgetexpress_get_option( 'footer_copyright' ) ) ) {
	$classes[] = 'no-footer-copyright';
}

$bg_color = gadgetexpress_get_option( 'footer_background_color' );
$bg_image = gadgetexpress_get_option( 'footer_background_image' );
$bg_h     = gadgetexpress_get_option( 'footer_background_horizontal' );
$bg_v     = gadgetexpress_get_option( 'footer_background_vertical' );
$bg_r     = gadgetexpress_get_option( 'footer_background_repeat' );
$bg_a     = gadgetexpress_get_option( 'footer_background_attachment' );
$bg_s     = gadgetexpress_get_option( 'footer_background_size' );

$style = array(
	! empty( $bg_color ) ? 'background-color: ' . $bg_color . ';' : '',
	! empty( $bg_image ) ? 'background-image: url( ' . esc_url( $bg_image ) . ' );' : '',
	! empty( $bg_h ) ? 'background-position-x: ' . $bg_h . ';' : '',
	! empty( $bg_v ) ? 'background-position-y: ' . $bg_v . ';' : '',
	! empty( $bg_r ) ? 'background-repeat: ' . $bg_r . ';' : '',
	! empty( $bg_a ) ? 'background-attachment:' . $bg_a . ';' : '',
	! empty( $bg_s ) ? 'background-size: ' . $bg_s . ';': '',
);

?>
<nav class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" style="<?php echo esc_attr( implode( '', $style ) ); ?>">
	<?php gadgetexpress_footer_widgets(); ?>
	<?php gadgetexpress_footer_copyright(); ?>
</nav>