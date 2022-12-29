<?php
/**
 * Template part for displaying header 5.
 *
 * @package Gadget Express
 */

?>
<div class="container">
	<div class="header-main">
		<div class="header-row">
			<div class="menu-logo s-left">
				<div class="site-logo">
					<?php get_template_part( 'parts/logo' ); ?>
				</div>
			</div>
			<div class="s-center menu-main">
				<div class="menu-nav">
					<nav class="primary-nav nav">
						<?php gadgetexpress_nav_menu(); ?>
					</nav>
				</div>
			</div>
			<div class="menu-extra s-right">
				<ul>
					<?php gadgetexpress_extra_search(); ?>
					<?php gadgetexpress_extra_account(); ?>
					<?php gadgetexpress_extra_wishlist(); ?>
					<?php gadgetexpress_extra_cart(); ?>
					<?php gadgetexpress_extra_sidebar(); ?>
					<?php gadgetexpress_menu_mobile(); ?>
				</ul>
			</div>
		</div>
	</div>
</div>
