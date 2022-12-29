<% if ( depth == 0 ) { %>
<a href="#" class="media-menu-item active" data-title="<?php esc_attr_e( 'Mega Menu Content', 'gadget' ) ?>" data-panel="mega"><?php esc_html_e( 'Mega Menu', 'gadget' ) ?></a>
<a href="#" class="media-menu-item" data-title="<?php esc_attr_e( 'Mega Menu Background', 'gadget' ) ?>" data-panel="background"><?php esc_html_e( 'Background', 'gadget' ) ?></a>
<div class="separator"></div>
<% } else if ( depth == 1 ) { %>
<a href="#" class="media-menu-item active" data-title="<?php esc_attr_e( 'Menu Content', 'gadget' ) ?>" data-panel="content"><?php esc_html_e( 'Menu Content', 'gadget' ) ?></a>
<a href="#" class="media-menu-item" data-title="<?php esc_attr_e( 'Menu General', 'gadget' ) ?>" data-panel="general"><?php esc_html_e( 'General', 'gadget' ) ?></a>
<% } else { %>
<a href="#" class="media-menu-item active" data-title="<?php esc_attr_e( 'Menu General', 'gadget' ) ?>" data-panel="general"><?php esc_html_e( 'General', 'gadget' ) ?></a>
<% } %>
