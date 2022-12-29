<?php
/**
 * Template part for displaying header v1.
 *
 * @package Gadget Express
 */

?>
<div class="gadgetexpress-container">
	<div class="header-main">
		<div class="header-row">
			<div class="menu-socials s-left">
				<?php
				$header_social = gadgetexpress_get_option( 'header_socials' );

				ob_start();
				gadgetexpress_get_socials_html( $header_social, 'icon' );
				$socials = ob_get_clean();

				echo apply_filters('gadgetexpress_menu_socials',$socials);
				?>
			</div>
			<div class="container s-center menu-main">
				<div class="menu-nav">
					<div class="menu-extra menu-search">
						<ul>
							<?php gadgetexpress_extra_search(); ?>
						</ul>
					</div>
					<div class="menu-logo">
						<div class="site-logo">
							<?php get_template_part( 'parts/logo' ); ?>
						</div>
					</div>
					<div class="menu-extra">
						<ul>
							<?php gadgetexpress_extra_account(); ?>
							<?php gadgetexpress_extra_wishlist(); ?>
							<?php gadgetexpress_extra_cart(); ?>

						</ul>
					</div>
				</div>
			</div>
			<div class="menu-extra s-right">
				<ul>
					<?php gadgetexpress_extra_sidebar(); ?>
					<?php gadgetexpress_menu_mobile(); ?>
				</ul>
			</div>
		</div>
	</div>
</div>
