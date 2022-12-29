<?php

/**
 *  Display footer widgets
 */
function gadgetexpress_footer_widgets() {
	if (
		is_active_sidebar( 'footer-sidebar-1' ) == false &&
		is_active_sidebar( 'footer-sidebar-2' ) == false &&
		is_active_sidebar( 'footer-sidebar-3' ) == false &&
		is_active_sidebar( 'footer-sidebar-4' ) == false 
	) {
		return;
	}


	if ( ! intval( gadgetexpress_get_option( 'footer_widgets' ) ) ) {
		return;
	}

	$columns = max( 1, absint( gadgetexpress_get_option( 'footer_widgets_columns' ) ) );
	$col     = 'col-xs-12 col-sm-6';

	if ( 4 == $columns ) {
		$col .= ' col-md-3';
	} else {
		$col .= ' col-md-' . 12 / $columns;
	}

	?>
	<div class="footer-widget columns-<?php echo esc_attr( $columns ) ?>">
		<div class="container">
			<div class="row">
				<?php for ( $i = 1; $i <= $columns; $i ++ ) : ?>

					<div class="footer-sidebar footer-<?php echo esc_attr( $i ) ?> <?php echo esc_attr( $col ) ?>">
						<?php
						ob_start();
						dynamic_sidebar( "footer-sidebar-$i" );
						$output = ob_get_clean();
						echo apply_filters('gadgetexpress_footer_widget_content',$output,$i);
						?>
					</div>

				<?php endfor; ?>
			</div>
		</div>
	</div>
	<?php
}

/**
 *  Display footer copyright
 */
function gadgetexpress_footer_copyright() {
	if (
		is_active_sidebar( 'footer-copyright-1' ) == false &&
		is_active_sidebar( 'footer-copyright-2' ) == false &&
		is_active_sidebar( 'footer-copyright-3' ) == false
	) {
		return;
	}

	if ( ! intval( gadgetexpress_get_option( 'footer_copyright' ) ) ) {
		return;
	}

	$columns = max( 1, absint( gadgetexpress_get_option( 'footer_copyright_columns' ) ) );
	$col     = 'col-md-12';

	if ( 5 == $columns ) {
		$col .= ' col-lg-1-5';
	} else {
		$col .= ' col-lg-' . 12 / $columns;
	}

	$classes = array(
		'footer-copyright',
		'columns-' . $columns,
		'style-' . gadgetexpress_get_option( 'footer_copyright_menu_style' )
	);

	?>
	<div class="<?php echo esc_attr( implode( ' ', $classes ) ) ?>">
		<div class="container">
			<div class="row footer-copyright-row">
				<?php for ( $i = 1; $i <= $columns; $i ++ ) : ?>

					<div class="footer-sidebar footer-<?php echo esc_attr( $i ) ?> <?php echo esc_attr( $col ) ?>">
						<?php
						ob_start();
						dynamic_sidebar( "footer-copyright-$i" );
						$output = ob_get_clean();
						echo apply_filters('gadgetexpress_footer_copyright_content',$output,$i);
						?>

					</div>

				<?php endfor; ?>

				
				<div class="footer-sidebar footer-social">
					<ul class="list-inline social-media">
					<?php
					$theme_settings_options = get_option( 'theme_settings_option_name' ); // Array of All Options
					$tiktok_0 = $theme_settings_options['tiktok_0'];
					$instagram_1 = $theme_settings_options['instagram_1']; // Instagram
					$facebook_2 = $theme_settings_options['facebook_2']; // Facebook
					$youtube_3 = $theme_settings_options['youtube_3']; // Youtube

					if ( $tiktok_0 ) {
						echo '<li><a href="'. $tiktok_0 .'" target="_blank" title="'. __('Follow us on TikTok', 'gadget') .'" data-rel="tooltip" data-placement="top"><i class="fa-brands fa-tiktok"></i></a></li>';
					}
					if ( $instagram_1 ) {
						echo '<li><a href="'. $instagram_1 .'" target="_blank" title="'. __('Follow us on Instagram', 'gadget') .'" data-rel="tooltip" data-placement="top"><i class="fa-brands fa-instagram"></i></a></li>';
					}
					if ( $facebook_2 ) {
						echo '<li><a href="'. $facebook_2 .'" target="_blank" title="'. __('Follow us on Facebook', 'gadget') .'" data-rel="tooltip" data-placement="top"><i class="fa-brands fa-facebook"></i></a></li>';
					}
					if ( $youtube_3 ) {
						echo '<li><a href="'. $youtube_3 .'" target="_blank" title="'. __('Follow us on YouTube', 'gadget') .'" data-rel="tooltip" data-placement="top"><i class="fa-brands fa-youtube"></i></a></li>';
					}
					?>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<?php
}