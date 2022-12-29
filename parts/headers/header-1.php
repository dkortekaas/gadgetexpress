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
			<div class="menu-logo s-left">
				<div class="site-logo" itemscope itemtype="http://schema.org/Organization">
					<?php get_template_part( 'parts/logo' ); ?>
				</div>
			</div>
			<div class="container s-center menu-main">
				<div class="menu-nav">
					<nav class="primary-nav nav">
						<?php gadgetexpress_nav_menu(); ?>
					</nav>
					<div class="menu-extra menu-extra-au">
						<ul class="no-flex">
							<?php gadgetexpress_extra_search(); ?>
						</ul>
					</div>
				</div>
			</div>
			<div class="menu-extra s-right">
				<ul>
					<?php gadgetexpress_extra_language_switcher( 'hidden-md hidden-sm hidden-xs' ); ?>
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
