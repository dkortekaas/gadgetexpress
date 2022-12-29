<?php
/**
 * Template part for displaying header v3.
 *
 * @package Gadget Express
 */

?>
<div class="gadgetexpress-container">
	<div class="header-main">
		<div class="row header-row">
			<div class="menu-logo col-lg-2 col-md-6 col-sm-6 col-xs-6">
				<div class="site-logo">
					<?php get_template_part( 'parts/logo' ); ?>
				</div>
			</div>
			<div class="menu-main col-lg-10 col-md-6 col-sm-6 col-xs-6">
				<div class="menu-extra">
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
</div>