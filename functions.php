<?php
/**
 * Gadget Express functions and definitions
 *
 * @package Gadget Express
 */


/**
 * Load theme
 */

// Theme setup
require get_template_directory() . '/inc/backend/theme-setup.php';

// Sidebars
require get_template_directory() . '/inc/functions/sidebars.php';

// Cleanup and secure WP
require get_template_directory() . '/inc/functions/cleanup.php';

// Widgets
require get_template_directory() . '/inc/widgets/widgets.php';

// Customizer
require get_template_directory() . '/inc/backend/customizer.php';

require get_template_directory() . '/inc/functions/layout.php';

// Woocommerce hooks
require get_template_directory() . '/inc/frontend/woocommerce.php';

// Custom login
require get_template_directory() . '/inc/backend/custom-login.php';

if ( is_admin() ) {
	require get_template_directory() . '/inc/libs/class-tgm-plugin-activation.php';
	require get_template_directory() . '/inc/tgm/plugins.php';

	require get_template_directory() . '/inc/backend/theme-options.php';
	//require get_template_directory() . '/inc/backend/meta-boxes.php';
	//require get_template_directory() . '/inc/mega-menu/class-mega-menu.php';
	//require get_template_directory() . '/inc/backend/product-meta-box-data.php';
} else {
	// Frontend functions and shortcodes
	require get_template_directory() . '/inc/functions/media.php';
	require get_template_directory() . '/inc/functions/nav.php';
	require get_template_directory() . '/inc/functions/entry.php';
	require get_template_directory() . '/inc/functions/header.php';
	require get_template_directory() . '/inc/functions/comments.php';
	require get_template_directory() . '/inc/functions/breadcrumbs.php';
	require get_template_directory() . '/inc/functions/footer.php';

	// Frontend hooks
	require get_template_directory() . '/inc/frontend/layout.php';
	require get_template_directory() . '/inc/frontend/header.php';
	require get_template_directory() . '/inc/frontend/footer.php';
	require get_template_directory() . '/inc/frontend/nav.php';
	require get_template_directory() . '/inc/frontend/entry.php';
	//require get_template_directory() . '/inc/mega-menu/class-mega-menu-walker.php';
}

// Display GA Price Field
function woo_add_ean_code() {
    global $woocommerce, $post;

    echo '<div class="options_group">';
        woocommerce_wp_text_input( 
            array( 
                'id'            => '_ean_code', 
                'label'         => __( 'EAN Code', 'gadget' ), 
                'placeholder'   => '', 
                'description'   => __( 'Enter the EAN code here.', 'gadget' ),
                'type'          => 'text', 
                'desc_tip'      => 'true'
            )
        );
    echo '</div>';
}
add_action( 'woocommerce_product_options_general_product_data', 'woo_add_ean_code' );

// Save GA Price Field
function woo_add_ean_code_save( $post_id ){
    $ean_code_field = $_POST['_ean_code'];
    if( !empty( $ean_code_field ) ) :
        update_post_meta( $post_id, '_ean_code', esc_attr( $ean_code_field ) );
    endif;
}
add_action( 'woocommerce_process_product_meta', 'woo_add_ean_code_save' );