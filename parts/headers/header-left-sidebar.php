<?php
/**
 * Template part for displaying header left sidebar.
 *
 * @package Gadget Express
 */

$header_copyright = get_post_meta( get_the_ID(), 'header_copyright', true );

?>
<div class="header-main menu-sidebar-panel">
	<div class="menu-panel-header">
		<div class="menu-logo">
			<div class="site-logo">
				<?php get_template_part( 'parts/logo' ); ?>
			</div>
			<div class="menu-extra menu-sidebar">
				<ul>
					<?php gadgetexpress_extra_sidebar(); ?>
					<?php gadgetexpress_menu_mobile(); ?>
				</ul>
			</div>
		</div>
		<div class="menu-extra menu-search">
			<ul><?php gadgetexpress_extra_search(); ?></ul>
		</div>
	</div>

	<div class="menu-panel-body">
		<div class="menu-main">
			<nav class="primary-nav nav">
				<?php gadgetexpress_nav_menu(); ?>
				<?php gadgetexpress_extra_language_switcher(); ?>
			</nav>
		</div>
	</div>
	<div class="menu-panel-extra">
		<div class="menu-extra">
			<ul>
				<?php gadgetexpress_extra_account(); ?>
				<?php gadgetexpress_extra_wishlist(); ?>
				<?php gadgetexpress_extra_cart(); ?>
			</ul>
		</div>
	</div>
	<div class="menu-panel-footer">
		<div class="header-social">
			<?php
			$header_social = get_post_meta( get_the_ID(), 'header_socials', true );

			gadgetexpress_get_socials_html( $header_social, 'icon' );
			?>
		</div>
		<div class="copyright"><?php echo wp_kses( $header_copyright, wp_kses_allowed_html( 'post' ) ); ?></div>
	</div>
</div>
