<?php
/**
 * Template part for displaying header v2.
 *
 * @package Gadget Express
 */

?>
<div class="container">
	<div class="header-main">
		<div class="header-row">
			<div class="menu-extra menu-search">
				<ul><?php gadgetexpress_extra_search(); ?></ul>
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
					<?php gadgetexpress_extra_sidebar(); ?>
					<?php gadgetexpress_menu_mobile(); ?>
				</ul>
			</div>
		</div>
	</div>
</div>