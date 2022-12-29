<?php
/**
 * Register plugins.
 *
 * @link https://github.com/TGMPA/TGM-Plugin-Activation
 *  
 * @package Gadget Express
 */

if ( ! defined( 'ABSPATH' ) ) :
	exit;
endif;

/**
 * Register the required plugins for this theme.
 *
 * The variables passed to the `tgmpa()` function should be:
 * - an array of plugin arrays;
 * - optionally a configuration array.
 * If you are not changing anything in the configuration array, you can remove the array and remove the
 * variable from the function call: `tgmpa( $plugins );`.
 * In that case, the TGMPA default settings will be used.
 *
 * This function is hooked into `tgmpa_register`, which is fired on the WP `init` action on priority 10.
 */
if ( ! function_exists( 'gadgetexpress_register_required_plugins' ) ) :

	function gadgetexpress_register_required_plugins() {
		/*
		* Array of plugin arrays. Required keys are name and slug.
		* If the source is NOT from the .org repo, then source is also required.
		*/
		$plugins = array(

			// PREMIUM Plugins - bundled
			array(
				'name'               => 'WP Rocket', // The plugin name.
				'slug'               => 'wp-rocket', // The plugin slug (typically the folder name).
				'source'             => get_stylesheet_directory() . '/inc/tgm/plugins/wp-rocket_2.10.5.zip', // The plugin source.
				'required'           => true, // If false, the plugin is only 'recommended' instead of required.
				'version'            => '2.10.5', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
				'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
				'force_deactivation' => true, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
				'external_url'       => '', // If set, overrides default API URL and points to an external URL.
				'is_callable'        => '', // If set, this callable will be be checked for availability to determine if a plugin is active.
			),
			array(
				'name'               => 'Advanced Custom Fields PRO', // The plugin name.
				'slug'               => 'advanced-custom-fields-pro', // The plugin slug (typically the folder name).
				'source'             => get_stylesheet_directory() . '/inc/tgm/plugins/advanced-custom-fields-pro.zip', // The plugin source.
				'required'           => true, // If false, the plugin is only 'recommended' instead of required.
				'version'            => '5.5.14', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
				'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
				'force_deactivation' => true, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
				'external_url'       => '', // If set, overrides default API URL and points to an external URL.
				'is_callable'        => '', // If set, this callable will be be checked for availability to determine if a plugin is active.
			),

			// Woocommerce combo only
			array(
				'name'               => 'WooCommerce Nice Urls', // The plugin name.
				'slug'               => 'woocommerce-nice-urls', // The plugin slug (typically the folder name).
				'source'             => get_stylesheet_directory() . '/inc/tgm/plugins/woocommerce-nice-urls.zip', // The plugin source.
				'required'           => false, // If false, the plugin is only 'recommended' instead of required.
				'version'            => '1.0.1', // E.g. 1.0.0. If set, the active plugin must be this version or higher. If the plugin version is higher than the plugin version installed, the user will be notified to update the plugin.
				'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
				'force_deactivation' => true, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
				'external_url'       => '', // If set, overrides default API URL and points to an external URL.
				'is_callable'        => '', // If set, this callable will be be checked for availability to determine if a plugin is active.
			),

			// WordPress Plugin Repository
			array(
				'name'               => 'Contact Form 7',
				'slug'               => 'contact-form-7',
				'required'           => true,
				'force_activation'   => true,
				'force_deactivation' => false,
			),
			array(
				'name'               => 'Yoast SEO',
				'slug'               => 'wordpress-seo',
				'required'           => true,
				'force_activation'   => true,
				'force_deactivation' => false,
			),
			array(
				'name'               => 'Title and Nofollow For Links',
				'slug'               => 'title-and-nofollow-for-links',
				'required'           => true,
				'force_activation'   => true,
				'force_deactivation' => true,
			),
			array(
				'name'               => 'Simple Page Ordering',
				'slug'               => 'simple-page-ordering',
				'required'           => true,
				'force_activation'   => true,
				'force_deactivation' => true,
			),
			array(
				'name'               => 'Post Types Order',
				'slug'               => 'post-types-order',
				'required'           => true,
				'force_activation'   => true,
				'force_deactivation' => true,
			),
			array(
				'name'               => 'Anti-spam',
				'slug'               => 'anti-spam',
				'required'           => true,
				'force_activation'   => true,
				'force_deactivation' => true,
			),			
			array(
				'name'               => 'ShortPixel Image Optimizer',
				'slug'               => 'shortpixel-image-optimiser',
				'required'           => false,
				'force_activation'   => false,
				'force_deactivation' => false,
			),
			array(
				'name'     => 'Regenerate Thumbnails',
				'slug'     => 'regenerate-thumbnails',
				'required'           => false,
				'force_activation'   => false,
				'force_deactivation' => false,
			),
			array(
				'name'     => 'MCE Table Buttons',
				'slug'     => 'mce-table-buttons',
				'required'           => false,
				'force_activation'   => false,
				'force_deactivation' => false,
			),
			array(
				'name'     => 'Tabby Responsive Tabs',
				'slug'     => 'tabby-responsive-tabs',
				'required'           => false,
				'force_activation'   => false,
				'force_deactivation' => false,
			),
			array(
				'name'     => '404page – your smart custom 404 error page',
				'slug'     => '404page',
				'required'           => false,
				'force_activation'   => false,
				'force_deactivation' => false,
			),
			array(
				'name'     => 'Wordfence Security – Firewall & Malware Scan',
				'slug'     => 'wordfence',
				'required'           => false,
				'force_activation'   => false,
				'force_deactivation' => false,
			),
			array(
				'name'               => esc_html__( 'Gadget Express Addons', 'gadget' ),
				'slug'               => 'gadgetexpress-addons',
				'source'             => get_template_directory() . '/inc/tgm/plugins/gadgetexpress-addons.zip',
				'required'           => true,
				'force_activation'   => false,
				'force_deactivation' => false,
				'version'            => '1.0.0',
			),			

			// Woocommerce combo only
			array(
				'name'               => esc_html__( 'WooCommerce', 'gadget' ),
				'slug'               => 'woocommerce',
				'required'           => false,
				'force_activation'   => false,
				'force_deactivation' => false,
			),			
			array(
				'name'               => 'Enhanced Ecommerce Google Analytics Plugin for WooCommerce',
				'slug'               => 'enhanced-e-commerce-for-woocommerce-store',
				'required'           => false,
				'force_activation'   => false,
				'force_deactivation' => false,
			),
			array(
				'name'               => esc_html__( 'YITH WooCommerce Wishlist', 'gadget' ),
				'slug'               => 'yith-woocommerce-wishlist',
				'required'           => false,
				'force_activation'   => false,
				'force_deactivation' => false,
			),
			array(
				'name'               => esc_html__( 'Variation Swatches for WooCommerce', 'gadget' ),
				'slug'               => 'variation-swatches-for-woocommerce',
				'required'           => false,
				'force_activation'   => false,
				'force_deactivation' => false,
			),

		);

		$config = array(
			'id'           => 'gadget',                 // Unique ID for hashing notices for multiple instances of TGMPA.
			'default_path' => '',                      // Default absolute path to bundled plugins.
			'menu'         => 'tgmpa-install-plugins', // Menu slug.
			'parent_slug'  => 'themes.php',            // Parent menu slug.
			'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
			'has_notices'  => true,                    // Show admin notices or not.
			'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
			'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
			'is_automatic' => false,                   // Automatically activate plugins after installation or not.
			'message'      => '',                      // Message to output right before the plugins table.
			'strings'      => array(
				'page_title'                      => esc_html__( 'Install Required Plugins', 'gadget' ),
				'menu_title'                      => esc_html__( 'Install Plugins', 'gadget' ),
				'installing'                      => esc_html__( 'Installing Plugin: %s', 'gadget' ),
				'oops'                            => esc_html__( 'Something went wrong with the plugin API.', 'gadget' ),
				'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.', 'gadget' ),
				'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.', 'gadget' ),
				'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.', 'gadget' ),
				'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.', 'gadget' ),
				'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.', 'gadget' ),
				'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.', 'gadget' ),
				'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.', 'gadget' ),
				'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.', 'gadget' ),
				'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins', 'gadget' ),
				'activate_link'                   => _n_noop( 'Activate installed plugin', 'Activate installed plugins', 'gadget' ),
				'return'                          => esc_html__( 'Return to Required Plugins Installer', 'gadget' ),
				'plugin_activated'                => esc_html__( 'Plugin activated successfully.', 'gadget' ),
				'complete'                        => esc_html__( 'All plugins installed and activated successfully. %s', 'gadget' ),
				'nag_type'                        => 'updated'
			)
		);

		tgmpa( $plugins, $config );

	}

	add_action( 'tgmpa_register', 'gadgetexpress_register_required_plugins' );

endif;
