<?php
/**
 * Gadget Express theme customizer
 *
 * @package Gadget Express
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Toffe_Dassen_Customize {
	/**
	 * Customize settings
	 *
	 * @var array
	 */
	protected $config = array();

	/**
	 * The class constructor
	 *
	 * @param array $config
	 */
	public function __construct( $config ) {
		$this->config = $config;

		if ( ! class_exists( 'Kirki' ) ) {
			return;
		}

		$this->register();
	}

	/**
	 * Register settings
	 */
	public function register() {
		/**
		 * Add the theme configuration
		 */
		if ( ! empty( $this->config['theme'] ) ) {
			Kirki::add_config(
				$this->config['theme'], array(
					'capability'  => 'edit_theme_options',
					'option_type' => 'theme_mod',
				)
			);
		}

		/**
		 * Add panels
		 */
		if ( ! empty( $this->config['panels'] ) ) {
			foreach ( $this->config['panels'] as $panel => $settings ) {
				Kirki::add_panel( $panel, $settings );
			}
		}

		/**
		 * Add sections
		 */
		if ( ! empty( $this->config['sections'] ) ) {
			foreach ( $this->config['sections'] as $section => $settings ) {
				Kirki::add_section( $section, $settings );
			}
		}

		/**
		 * Add fields
		 */
		if ( ! empty( $this->config['theme'] ) && ! empty( $this->config['fields'] ) ) {
			foreach ( $this->config['fields'] as $name => $settings ) {
				if ( ! isset( $settings['settings'] ) ) {
					$settings['settings'] = $name;
				}

				Kirki::add_field( $this->config['theme'], $settings );
			}
		}
	}

	/**
	 * Get config ID
	 *
	 * @return string
	 */
	public function get_theme() {
		return $this->config['theme'];
	}

	/**
	 * Get customize setting value
	 *
	 * @param string $name
	 *
	 * @return bool|string
	 */
	public function get_option( $name ) {

		$default = $this->get_option_default( $name );

		return get_theme_mod( $name, $default );
	}

	/**
	 * Get default option values
	 *
	 * @param $name
	 *
	 * @return mixed
	 */
	public function get_option_default( $name ) {
		if ( ! isset( $this->config['fields'][$name] ) ) {
			return false;
		}

		return isset( $this->config['fields'][$name]['default'] ) ? $this->config['fields'][$name]['default'] : false;
	}
}

/**
 * This is a short hand function for getting setting value from customizer
 *
 * @param string $name
 *
 * @return bool|string
 */
function gadgetexpress_get_option( $name ) {
	global $gadgetexpress_customize;

	if ( empty( $gadgetexpress_customize ) ) {
		return false;
	}

	if ( class_exists( 'Kirki' ) ) {
		$value = Kirki::get_option( $gadgetexpress_customize->get_theme(), $name );
	} else {
		$value = $gadgetexpress_customize->get_option( $name );
	}

	return apply_filters( 'gadgetexpress_get_option', $value, $name );
}

/**
 * Get default option values
 *
 * @param $name
 *
 * @return mixed
 */
function gadgetexpress_get_option_default( $name ) {
	global $gadgetexpress_customize;

	if ( empty( $gadgetexpress_customize ) ) {
		return false;
	}

	return $gadgetexpress_customize->get_option_default( $name );
}

/**
 * Move some default sections to `general` panel that registered by theme
 *
 * @param object $wp_customize
 */
function gadgetexpress_customize_modify( $wp_customize ) {
	$wp_customize->get_section( 'title_tagline' )->panel     = 'general';
	$wp_customize->get_section( 'static_front_page' )->panel = 'general';
}

add_action( 'customize_register', 'gadgetexpress_customize_modify' );


/**
 * Get product attributes
 *
 * @return string
 */
function gadgetexpress_product_attributes() {
	$output = array();
	if ( function_exists( 'wc_get_attribute_taxonomies' ) ) {
		$attributes_tax = wc_get_attribute_taxonomies();
		if ( $attributes_tax ) {
			$output['none'] = esc_html__( 'None', 'gadget' );

			foreach ( $attributes_tax as $attribute ) {
				$output[$attribute->attribute_name] = $attribute->attribute_label;
			}

		}
	}

	return $output;
}

function gadgetexpress_customize_settings() {
	/**
	 * Customizer configuration
	 */

	$settings = array(
		'theme' => 'gadget',
	);

	$panels = array(
		'general'     => array(
			'priority' => 5,
			'title'    => esc_html__( 'General', 'gadget' ),
		),
		'typography'  => array(
			'priority' => 10,
			'title'    => esc_html__( 'Typography', 'gadget' ),
		),
		// Styling
		'styling'     => array(
			'title'    => esc_html__( 'Styling', 'gadget' ),
			'priority' => 10,
		),
		'header'      => array(
			'priority' => 10,
			'title'    => esc_html__( 'Header', 'gadget' ),
		),
		'page'        => array(
			'title'      => esc_html__( 'Page', 'gadget' ),
			'priority'   => 10,
			'capability' => 'edit_theme_options',
		),
		'blog'        => array(
			'title'      => esc_html__( 'Blog', 'gadget' ),
			'priority'   => 10,
			'capability' => 'edit_theme_options',
		),
		'woocommerce' => array(
			'priority' => 10,
			'title'    => esc_html__( 'Woocommerce', 'gadget' ),
		),
		'portfolio'   => array(
			'title'      => esc_html__( 'Portfolio', 'gadget' ),
			'priority'   => 10,
			'capability' => 'edit_theme_options',
		),
		'footer'      => array(
			'title'    => esc_html__( 'Footer', 'gadget' ),
			'priority' => 50,
		),
		'mobile'      => array(
			'title'      => esc_html__( 'Mobile', 'gadget' ),
			'priority'   => 50,
			'capability' => 'edit_theme_options',
		),
	);

	$sections = array(
		'body_typo'                   => array(
			'title'       => esc_html__( 'Body', 'gadget' ),
			'description' => '',
			'priority'    => 210,
			'capability'  => 'edit_theme_options',
			'panel'       => 'typography',
		),
		'heading_typo'                => array(
			'title'       => esc_html__( 'Heading', 'gadget' ),
			'description' => '',
			'priority'    => 210,
			'capability'  => 'edit_theme_options',
			'panel'       => 'typography',
		),
		'header_typo'                 => array(
			'title'       => esc_html__( 'Header', 'gadget' ),
			'description' => '',
			'priority'    => 210,
			'capability'  => 'edit_theme_options',
			'panel'       => 'typography',
		),
		'footer_typo'                 => array(
			'title'       => esc_html__( 'Footer', 'gadget' ),
			'description' => '',
			'priority'    => 210,
			'capability'  => 'edit_theme_options',
			'panel'       => 'typography',
		),
		'topbar'                      => array(
			'title'       => esc_html__( 'Topbar', 'gadget' ),
			'description' => '',
			'priority'    => 5,
			'capability'  => 'edit_theme_options',
			'panel'       => 'header',
		),
		'header'                      => array(
			'title'       => esc_html__( 'Header', 'gadget' ),
			'description' => '',
			'priority'    => 10,
			'capability'  => 'edit_theme_options',
			'panel'       => 'header',
		),
		'logo'                        => array(
			'title'       => esc_html__( 'Logo', 'gadget' ),
			'description' => '',
			'priority'    => 10,
			'capability'  => 'edit_theme_options',
			'panel'       => 'header',
		),
		'backtotop'                   => array(
			'title'       => esc_html__( 'Back to Top', 'gadget' ),
			'description' => '',
			'priority'    => 210,
			'capability'  => 'edit_theme_options',
			'panel'       => 'styling',
		),
		'preloader'                   => array(
			'title'       => esc_html__( 'Preloader', 'gadget' ),
			'description' => '',
			'priority'    => 210,
			'capability'  => 'edit_theme_options',
			'panel'       => 'styling',
		),
		'color_scheme'                => array(
			'title'       => esc_html__( 'Color Scheme', 'gadget' ),
			'description' => '',
			'priority'    => 210,
			'capability'  => 'edit_theme_options',
			'panel'       => 'styling',
		),
		'boxed_layout'                => array(
			'title'       => esc_html__( 'Boxed Layout', 'gadget' ),
			'description' => '',
			'priority'    => 10,
			'capability'  => 'edit_theme_options',
			'panel'       => 'styling',
		),
		'page_header'                 => array(
			'title'       => esc_html__( 'Page Header', 'gadget' ),
			'description' => '',
			'priority'    => 10,
			'capability'  => 'edit_theme_options',
			'panel'       => 'page',
		),
		'coming_soon'                 => array(
			'title'       => esc_html__( 'Coming Soon', 'gadget' ),
			'description' => '',
			'priority'    => 10,
			'capability'  => 'edit_theme_options',
			'panel'       => 'page',
		),
		'blog_page_header'            => array(
			'title'       => esc_html__( 'Blog Page Header', 'gadget' ),
			'description' => '',
			'priority'    => 10,
			'capability'  => 'edit_theme_options',
			'panel'       => 'blog',
		),
		'blog_page'                   => array(
			'title'       => esc_html__( 'Blog Page', 'gadget' ),
			'description' => '',
			'priority'    => 10,
			'capability'  => 'edit_theme_options',
			'panel'       => 'blog',
		),
		'single_post'                 => array(
			'title'       => esc_html__( 'Single Post', 'gadget' ),
			'description' => '',
			'priority'    => 10,
			'capability'  => 'edit_theme_options',
			'panel'       => 'blog',
		),
		'catalog_page_header'         => array(
			'title'       => esc_html__( 'Catalog Page Header', 'gadget' ),
			'description' => '',
			'priority'    => 10,
			'capability'  => 'edit_theme_options',
			'panel'       => 'woocommerce',
		),
		'woocommerce_product_catalog' => array(
			'title'       => esc_html__( 'Product Catalog', 'gadget' ),
			'description' => '',
			'priority'    => 10,
			'panel'       => 'woocommerce',
			'capability'  => 'edit_theme_options',
		),
		'shop_badge'                  => array(
			'title'       => esc_html__( 'Badges', 'gadget' ),
			'description' => '',
			'priority'    => 40,
			'panel'       => 'woocommerce',
			'capability'  => 'edit_theme_options',
		),
		'single_product'              => array(
			'title'       => esc_html__( 'Single Product', 'gadget' ),
			'description' => '',
			'priority'    => 90,
			'panel'       => 'woocommerce',
			'capability'  => 'edit_theme_options',
		),
		'portfolio_page_header'       => array(
			'title'       => esc_html__( 'Portfolio Page Header', 'gadget' ),
			'description' => '',
			'priority'    => 90,
			'panel'       => 'portfolio',
			'capability'  => 'edit_theme_options',
		),
		'portfolio'                   => array(
			'title'       => esc_html__( 'Portfolio', 'gadget' ),
			'description' => '',
			'priority'    => 90,
			'panel'       => 'portfolio',
			'capability'  => 'edit_theme_options',
		),
		'single_portfolio'            => array(
			'title'       => esc_html__( 'Single Portfolio', 'gadget' ),
			'description' => '',
			'priority'    => 90,
			'panel'       => 'portfolio',
			'capability'  => 'edit_theme_options',
		),
		'footer_newsletter'           => array(
			'title'       => esc_html__( 'Footer Newsletter', 'gadget' ),
			'description' => '',
			'priority'    => 10,
			'capability'  => 'edit_theme_options',
			'panel'       => 'footer',
		),
		'footer_layout'               => array(
			'title'       => esc_html__( 'Footer Layout', 'gadget' ),
			'description' => '',
			'priority'    => 10,
			'capability'  => 'edit_theme_options',
			'panel'       => 'footer',
		),
		'footer_widgets'              => array(
			'title'       => esc_html__( 'Footer Widgets', 'gadget' ),
			'description' => '',
			'priority'    => 10,
			'capability'  => 'edit_theme_options',
			'panel'       => 'footer',
		),
		'footer_copyright'            => array(
			'title'       => esc_html__( 'Footer Copyright', 'gadget' ),
			'description' => '',
			'priority'    => 10,
			'capability'  => 'edit_theme_options',
			'panel'       => 'footer',
		),
		'menu_mobile'                 => array(
			'title'       => esc_html__( 'Menu Sidebar', 'gadget' ),
			'description' => '',
			'priority'    => 10,
			'capability'  => 'edit_theme_options',
			'panel'       => 'mobile',
		),
		'catalog_mobile'              => array(
			'title'       => esc_html__( 'Catalog Mobile', 'gadget' ),
			'description' => '',
			'priority'    => 10,
			'capability'  => 'edit_theme_options',
			'panel'       => 'mobile',
		),
	);

	$fields = array(
		// Typography
		'body_typo'                        => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Body', 'gadget' ),
			'section'  => 'body_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Cerebri Sans',
				'variant'        => 'regular',
				'font-size'      => '16px',
				'line-height'    => '1.6',
				'letter-spacing' => '0',
				'subsets'        => array( 'latin-ext' ),
				'color'          => '#777777',
				'text-transform' => 'none',
			),
		),
		'heading1_typo'                    => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Heading 1', 'gadget' ),
			'section'  => 'heading_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Cerebri Sans',
				'variant'        => '700',
				'font-size'      => '36px',
				'line-height'    => '1.2',
				'letter-spacing' => '0',
				'subsets'        => array( 'latin-ext' ),
				'color'          => '#222222',
				'text-transform' => 'none',
			),
		),
		'heading2_typo'                    => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Heading 2', 'gadget' ),
			'section'  => 'heading_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Cerebri Sans',
				'variant'        => '700',
				'font-size'      => '30px',
				'line-height'    => '1.2',
				'letter-spacing' => '0',
				'subsets'        => array( 'latin-ext' ),
				'color'          => '#222222',
				'text-transform' => 'none',
			),
		),
		'heading3_typo'                    => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Heading 3', 'gadget' ),
			'section'  => 'heading_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Cerebri Sans',
				'variant'        => '700',
				'font-size'      => '24px',
				'line-height'    => '1.2',
				'letter-spacing' => '0',
				'subsets'        => array( 'latin-ext' ),
				'color'          => '#222222',
				'text-transform' => 'none',
			),
		),
		'heading4_typo'                    => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Heading 4', 'gadget' ),
			'section'  => 'heading_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Cerebri Sans',
				'variant'        => '700',
				'font-size'      => '18px',
				'line-height'    => '1.2',
				'letter-spacing' => '0',
				'subsets'        => array( 'latin-ext' ),
				'color'          => '#222222',
				'text-transform' => 'none',
			),
		),
		'heading5_typo'                    => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Heading 5', 'gadget' ),
			'section'  => 'heading_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Cerebri Sans',
				'variant'        => '700',
				'font-size'      => '16px',
				'line-height'    => '1.2',
				'letter-spacing' => '0',
				'subsets'        => array( 'latin-ext' ),
				'color'          => '#222222',
				'text-transform' => 'none',
			),
		),
		'heading6_typo'                    => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Heading 6', 'gadget' ),
			'section'  => 'heading_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Cerebri Sans',
				'variant'        => '700',
				'font-size'      => '12px',
				'line-height'    => '1.2',
				'letter-spacing' => '0',
				'subsets'        => array( 'latin-ext' ),
				'color'          => '#222222',
				'text-transform' => 'none',
			),
		),
		'menu_typo'                        => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Menu', 'gadget' ),
			'section'  => 'header_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Cerebri Sans',
				'variant'        => 'regular',
				'subsets'        => array( 'latin-ext' ),
				'font-size'      => '16px',
				'color'          => '#222222',
				'text-transform' => 'none',
			),
		),
		'sub_menu_typo'                    => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Sub Menu', 'gadget' ),
			'section'  => 'header_typo',
			'priority' => 10,
			'default'  => array(
				'font-family'    => 'Cerebri Sans',
				'variant'        => 'regular',
				'subsets'        => array( 'latin-ext' ),
				'font-size'      => '15px',
				'color'          => '#999999',
				'text-transform' => 'none',
			),
		),
		'footer_text_typo'                 => array(
			'type'     => 'typography',
			'label'    => esc_html__( 'Footer Text', 'gadget' ),
			'section'  => 'footer_typo',
			'priority' => 10,
			'default'  => array(
				'font-family' => 'Cerebri Sans',
				'variant'     => 'regular',
				'subsets'     => array( 'latin-ext' ),
				'font-size'   => '15px',
			),
		),

		// Topbar
		'topbar_enable'                    => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Show topbar', 'gadget' ),
			'section'  => 'topbar',
			'default'  => 1,
			'priority' => 10,
		),
		'topbar_layout'                    => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Topbar Layout', 'gadget' ),
			'section'  => 'topbar',
			'default'  => '1',
			'priority' => 10,
			'choices'  => array(
				'1' => esc_html__( 'Layout 1', 'gadget' ),
				'2' => esc_html__( 'Layout 2', 'gadget' ),
			),
		),
		'topbar_border_bottom'             => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Show border bottom', 'gadget' ),
			'section'  => 'topbar',
			'default'  => 0,
			'priority' => 10,
		),
		'topbar_background_color'          => array(
			'type'     => 'color',
			'label'    => esc_html__( 'Background Color', 'gadget' ),
			'default'  => '',
			'section'  => 'topbar',
			'priority' => 10,
		),
		'topbar_color'                     => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Color', 'gadget' ),
			'section'  => 'topbar',
			'default'  => 'default',
			'priority' => 10,
			'choices'  => array(
				'default' => esc_html__( 'Default', 'gadget' ),
				'custom'  => esc_html__( 'Custom', 'gadget' ),
			),
		),
		'topbar_custom_color'              => array(
			'type'            => 'color',
			'label'           => esc_html__( 'Custom Color', 'gadget' ),
			'default'         => '',
			'section'         => 'topbar',
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'topbar_color',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
		),
		'topbar_custom_field_1'            => array(
			'type'    => 'custom',
			'section' => 'topbar',
			'default' => '<hr/>',
		),

		'topbar_mobile_content'            => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Topbar Mobile justify content', 'gadget' ),
			'section'  => 'topbar',
			'default'  => 'flex-start',
			'priority' => 10,
			'choices'  => array(
				'flex-start'    => esc_html__( 'Flex Start', 'gadget' ),
				'flex-end'      => esc_html__( 'Flex End', 'gadget' ),
				'center'        => esc_html__( 'Center', 'gadget' ),
				'space-between' => esc_html__( 'Space Between', 'gadget' ),
			),
		),

		// Header layout
		'header_layout'                    => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Header Layout', 'gadget' ),
			'section'  => 'header',
			'default'  => '1',
			'priority' => 10,
			'choices'  => array(
				'1' => esc_html__( 'Layout 1', 'gadget' ),
				'2' => esc_html__( 'Layout 2', 'gadget' ),
				'3' => esc_html__( 'Layout 3', 'gadget' ),
				'4' => esc_html__( 'Layout 4', 'gadget' ),
				'5' => esc_html__( 'Layout 5', 'gadget' ),
				'6' => esc_html__( 'Layout 6', 'gadget' ),
			),
		),

		'header_sticky'                    => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Header Sticky', 'gadget' ),
			'default'  => 1,
			'section'  => 'header',
			'priority' => 10,
		),

		'header_transparent'               => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Header Transparent', 'gadget' ),
			'default'     => 1,
			'section'     => 'header',
			'priority'    => 10,
			'description' => esc_html__( 'Check this to enable header transparent in homepage only.', 'gadget' ),
		),

		'header_text_color'                => array(
			'type'            => 'select',
			'label'           => esc_html__( 'Header Text Color', 'gadget' ),
			'description'     => esc_html__( 'This option apply for homepage only', 'gadget' ),
			'section'         => 'header',
			'default'         => 'dark',
			'choices'         => array(
				'dark'   => esc_html__( 'Dark', 'gadget' ),
				'light'  => esc_html__( 'Light', 'gadget' ),
				'custom' => esc_html__( 'Custom', 'gadget' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'header_transparent',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'header_text_custom_color'         => array(
			'type'            => 'color',
			'label'           => esc_html__( 'Color', 'gadget' ),
			'default'         => '',
			'section'         => 'header',
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'header_text_color',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
		),
		'menu_extras'                      => array(
			'type'     => 'multicheck',
			'label'    => esc_html__( 'Menu Extras', 'gadget' ),
			'section'  => 'header',
			'default'  => array( 'search', 'account', 'wishlist', 'cart' ),
			'priority' => 10,
			'choices'  => array(
				'search'   => esc_html__( 'Search', 'gadget' ),
				'account'  => esc_html__( 'Account', 'gadget' ),
				'wishlist' => esc_html__( 'Wishlist', 'gadget' ),
				'cart'     => esc_html__( 'Cart', 'gadget' ),
				'sidebar'  => esc_html__( 'Sidebar', 'gadget' ),
			),
		),
		'header_menu_text'                 => array(
			'type'     => 'text',
			'label'    => esc_html__( 'Menu Text', 'gadget' ),
			'section'  => 'header',
			'default'  => '',
			'priority' => 10,
		),
		'header_socials'                   => array(
			'type'            => 'repeater',
			'label'           => esc_html__( 'Socials', 'gadget' ),
			'section'         => 'header',
			'priority'        => 10,
			'row_label'       => array(
				'type'  => 'text',
				'value' => esc_attr__( 'Social', 'gadget' ),
			),
			'fields'          => array(
				'link_url' => array(
					'type'        => 'text',
					'label'       => esc_html__( 'Social URL', 'gadget' ),
					'description' => esc_html__( 'Enter the URL for this social', 'gadget' ),
					'default'     => '',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'header_layout',
					'operator' => 'in',
					'value'    => array( '4' ),
				),
			),
		),
		'header_custom_field_1'            => array(
			'type'    => 'custom',
			'section' => 'header',
			'default' => '<hr/>',
		),
		'menu_animation'                   => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Menu Hover Animation', 'gadget' ),
			'section'  => 'header',
			'default'  => 'fade',
			'priority' => 30,
			'choices'  => array(
				'none'  => esc_html__( 'No Animation', 'gadget' ),
				'fade'  => esc_html__( 'Fade', 'gadget' ),
				'slide' => esc_html__( 'Slide', 'gadget' ),
			),
		),
		'menu_hover_color'                 => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Menu Hover Color', 'gadget' ),
			'section'  => 'header',
			'default'  => 'none',
			'priority' => 30,
			'choices'  => array(
				'none'          => esc_html__( 'None', 'gadget' ),
				'primary-color' => esc_html__( 'Primary Color', 'gadget' ),
				'custom-color'  => esc_html__( 'Custom', 'gadget' ),
			),
		),
		'menu_hover_custom_color'          => array(
			'type'            => 'color',
			'label'           => esc_html__( 'Color', 'gadget' ),
			'default'         => '',
			'section'         => 'header',
			'priority'        => 30,
			'active_callback' => array(
				array(
					'setting'  => 'menu_hover_color',
					'operator' => '==',
					'value'    => 'custom-color',
				),
			),
		),
		'header_ajax_search'               => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'AJAX Search', 'gadget' ),
			'section'     => 'header',
			'default'     => 1,
			'priority'    => 90,
			'description' => esc_html__( 'Check this option to enable AJAX search in the header', 'gadget' ),
		),
		'search_content_type'              => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Search Content Type', 'gadget' ),
			'section'  => 'header',
			'default'  => 'all',
			'priority' => 90,
			'choices'  => array(
				'all'      => esc_html__( 'All', 'gadget' ),
				'products' => esc_html__( 'Only products', 'gadget' ),
			),
		),
		// Logo
		'logo'                             => array(
			'type'     => 'image',
			'label'    => esc_html__( 'Logo', 'gadget' ),
			'section'  => 'logo',
			'default'  => '',
			'priority' => 10,
		),
		'logo_light'                       => array(
			'type'     => 'image',
			'label'    => esc_html__( 'Logo Light', 'gadget' ),
			'section'  => 'logo',
			'default'  => '',
			'priority' => 10,
		),
		'logo_width'                       => array(
			'type'     => 'number',
			'label'    => esc_html__( 'Logo Width', 'gadget' ),
			'section'  => 'logo',
			'default'  => '',
			'priority' => 10,
		),
		'logo_height'                      => array(
			'type'     => 'number',
			'label'    => esc_html__( 'Logo Height', 'gadget' ),
			'section'  => 'logo',
			'default'  => '',
			'priority' => 10,
		),
		'logo_position'                    => array(
			'type'     => 'spacing',
			'label'    => esc_html__( 'Logo Margin', 'gadget' ),
			'section'  => 'logo',
			'priority' => 10,
			'default'  => array(
				'top'    => '0',
				'bottom' => '0',
				'left'   => '0',
				'right'  => '0',
			),
		),

		// Styling
		'back_to_top'                      => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Back to Top', 'gadget' ),
			'section'  => 'backtotop',
			'default'  => 1,
			'priority' => 10,
		),
		'preloader'                        => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Preloader', 'gadget' ),
			'section'  => 'preloader',
			'default'  => 0,
			'priority' => 10,
		),
		// Color Scheme
		'color_scheme'                     => array(
			'type'     => 'palette',
			'label'    => esc_html__( 'Base Color Scheme', 'gadget' ),
			'default'  => '',
			'section'  => 'color_scheme',
			'priority' => 10,
			'choices'  => array(
				''        => array( '#f68872' ),
				'#7cafca' => array( '#7cafca' ),
			),
		),
		'custom_color_scheme'              => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Custom Color Scheme', 'gadget' ),
			'default'  => 0,
			'section'  => 'color_scheme',
			'priority' => 10,
		),
		'custom_color'                     => array(
			'type'            => 'color',
			'label'           => esc_html__( 'Color', 'gadget' ),
			'default'         => '',
			'section'         => 'color_scheme',
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'custom_color_scheme',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),

		// Page
		'boxed_layout'                     => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Boxed Layout', 'gadget' ),
			'description' => esc_html__( 'It just apply for home page', 'gadget' ),
			'section'     => 'boxed_layout',
			'default'     => 0,
			'priority'    => 10,
		),
		'boxed_background_color'           => array(
			'type'            => 'color',
			'label'           => esc_html__( 'Background Color', 'gadget' ),
			'default'         => '',
			'section'         => 'page_layout',
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'page_boxed_layout',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'boxed_background_image'           => array(
			'type'            => 'image',
			'label'           => esc_html__( 'Background Image', 'gadget' ),
			'default'         => '',
			'section'         => 'boxed_layout',
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'page_boxed_layout',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'boxed_background_horizontal'      => array(
			'type'            => 'select',
			'label'           => esc_html__( 'Background Horizontal', 'gadget' ),
			'section'         => 'boxed_layout',
			'default'         => '',
			'priority'        => 10,
			'choices'         => array(
				''       => esc_html__( 'None', 'gadget' ),
				'left'   => esc_html__( 'Left', 'gadget' ),
				'center' => esc_html__( 'Center', 'gadget' ),
				'right'  => esc_html__( 'Right', 'gadget' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'page_boxed_layout',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'boxed_background_vertical'        => array(
			'type'            => 'select',
			'label'           => esc_html__( 'Background Vertical', 'gadget' ),
			'section'         => 'boxed_layout',
			'default'         => '',
			'priority'        => 10,
			'choices'         => array(
				''       => esc_html__( 'None', 'gadget' ),
				'top'    => esc_html__( 'Top', 'gadget' ),
				'center' => esc_html__( 'Center', 'gadget' ),
				'bottom' => esc_html__( 'Bottom', 'gadget' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'page_boxed_layout',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'boxed_background_repeat'          => array(
			'type'            => 'select',
			'label'           => esc_html__( 'Background Repeat', 'gadget' ),
			'section'         => 'boxed_layout',
			'default'         => '',
			'priority'        => 10,
			'choices'         => array(
				''          => esc_html__( 'None', 'gadget' ),
				'no-repeat' => esc_html__( 'No Repeat', 'gadget' ),
				'repeat'    => esc_html__( 'Repeat', 'gadget' ),
				'repeat-y'  => esc_html__( 'Repeat Vertical', 'gadget' ),
				'repeat-x'  => esc_html__( 'Repeat Horizontal', 'gadget' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'page_boxed_layout',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'boxed_background_attachment'      => array(
			'type'            => 'select',
			'label'           => esc_html__( 'Background Attachment', 'gadget' ),
			'section'         => 'boxed_layout',
			'default'         => '',
			'priority'        => 10,
			'choices'         => array(
				''       => esc_html__( 'None', 'gadget' ),
				'scroll' => esc_html__( 'Scroll', 'gadget' ),
				'fixed'  => esc_html__( 'Fixed', 'gadget' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'page_boxed_layout',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'boxed_background_size'            => array(
			'type'            => 'select',
			'label'           => esc_html__( 'Background Size', 'gadget' ),
			'section'         => 'boxed_layout',
			'default'         => '',
			'priority'        => 10,
			'choices'         => array(
				''        => esc_html__( 'None', 'gadget' ),
				'auto'    => esc_html__( 'Auto', 'gadget' ),
				'cover'   => esc_html__( 'Cover', 'gadget' ),
				'contain' => esc_html__( 'Contain', 'gadget' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'page_boxed_layout',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		// Coming Soon
		'coming_soon_logo'                 => array(
			'type'     => 'image',
			'label'    => esc_html__( 'Logo', 'gadget' ),
			'section'  => 'coming_soon',
			'default'  => '',
			'priority' => 10,
		),
		'coming_soon_background'           => array(
			'type'     => 'image',
			'label'    => esc_html__( 'Background Image', 'gadget' ),
			'section'  => 'coming_soon',
			'default'  => '',
			'priority' => 10,
		),
		'coming_soon_background_color'     => array(
			'type'     => 'color',
			'label'    => esc_html__( 'Background Color', 'gadget' ),
			'default'  => '',
			'section'  => 'coming_soon',
			'priority' => 10,
		),
		'show_coming_soon_social_share'    => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Show Socials Share', 'gadget' ),
			'description' => esc_html__( 'Check this option to show socials share in the single post page.', 'gadget' ),
			'section'     => 'coming_soon',
			'default'     => 0,
			'priority'    => 10,
		),

		'coming_soon_socials'              => array(
			'type'            => 'repeater',
			'label'           => esc_html__( 'Socials', 'gadget' ),
			'section'         => 'coming_soon',
			'priority'        => 10,
			'default'         => array(
				array(
					'link_url' => 'https://facebook.com/toffedassen',
				),
				array(
					'link_url' => 'https://twitter.com/toffedassen',
				),
				array(
					'link_url' => 'https://plus.google.com/toffedassen',
				),
				array(
					'link_url' => 'https://dribbble.com/',
				),
			),
			'fields'          => array(
				'link_url' => array(
					'type'        => 'text',
					'label'       => esc_html__( 'Social URL', 'gadget' ),
					'description' => esc_html__( 'Enter the URL for this social', 'gadget' ),
					'default'     => '',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'show_coming_soon_social_share',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'coming_soon_newsletter'           => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Newsletter', 'gadget' ),
			'section'  => 'coming_soon',
			'default'  => 1,
			'priority' => 10,
		),
		'coming_soon_newsletter_form'      => array(
			'type'            => 'textarea',
			'label'           => esc_html__( 'Newsletter Form', 'gadget' ),
			'section'         => 'coming_soon',
			'default'         => '',
			'priority'        => 10,
			'description'     => esc_html__( 'Go to MailChimp for WP/Form to get shortcode', 'gadget' ),
			'active_callback' => array(
				array(
					'setting'  => 'coming_soon_newsletter',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),

		// Page Header of Page
		'page_header'                      => array(
			'type'        => 'toggle',
			'default'     => 1,
			'label'       => esc_html__( 'Enable Page Header', 'gadget' ),
			'section'     => 'page_header',
			'description' => esc_html__( 'Enable to show a page header for page below the site header', 'gadget' ),
			'priority'    => 10,
		),
		'page_header_breadcrumbs'          => array(
			'type'            => 'toggle',
			'default'         => 1,
			'label'           => esc_html__( 'Enable Breadcrumbs', 'gadget' ),
			'section'         => 'page_header',
			'description'     => esc_html__( 'Enable to show a breadcrumbs for page below the site header', 'gadget' ),
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'page_header',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'page_header_text_color'           => array(
			'type'            => 'select',
			'label'           => esc_html__( 'Page Header Text Color', 'gadget' ),
			'section'         => 'page_header',
			'default'         => 'dark',
			'choices'         => array(
				'dark'  => esc_html__( 'Dark', 'gadget' ),
				'light' => esc_html__( 'Light', 'gadget' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'page_header',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'page_header_background'           => array(
			'type'            => 'image',
			'label'           => esc_html__( 'Background Image', 'gadget' ),
			'section'         => 'page_header',
			'default'         => '',
			'priority'        => 20,
			'active_callback' => array(
				array(
					'setting'  => 'page_header',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'page_header_parallax'             => array(
			'type'            => 'toggle',
			'label'           => esc_html__( 'Enable Parallax', 'gadget' ),
			'section'         => 'page_header',
			'default'         => 1,
			'priority'        => 20,
			'active_callback' => array(
				array(
					'setting'  => 'page_header',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		// Blog
		'blog_page_header'                 => array(
			'type'        => 'toggle',
			'default'     => 1,
			'label'       => esc_html__( 'Enable Page Header', 'gadget' ),
			'section'     => 'blog_page_header',
			'description' => esc_html__( 'Enable to show a page header for blog page below the site header', 'gadget' ),
			'priority'    => 10,
		),
		'blog_page_header_breadcrumbs'     => array(
			'type'            => 'toggle',
			'default'         => 1,
			'label'           => esc_html__( 'Enable Breadcrumbs', 'gadget' ),
			'section'         => 'blog_page_header',
			'description'     => esc_html__( 'Enable to show a breadcrumbs for blog page below the site header', 'gadget' ),
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'blog_page_header',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'blog_page_header_layout'          => array(
			'type'            => 'select',
			'label'           => esc_html__( 'Page Header Layout', 'gadget' ),
			'section'         => 'blog_page_header',
			'default'         => '1',
			'priority'        => 10,
			'choices'         => array(
				'1' => esc_html__( 'Layout 1', 'gadget' ),
				'2' => esc_html__( 'Layout 2', 'gadget' ),
				'3' => esc_html__( 'Layout 3', 'gadget' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'blog_page_header',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'blog_page_header_subtitle'        => array(
			'type'            => 'text',
			'label'           => esc_html__( 'Blog SubTitle', 'gadget' ),
			'section'         => 'blog_page_header',
			'default'         => '',
			'priority'        => 10,
			'description'     => esc_html__( 'Enter Blog SubTitle', 'gadget' ),
			'active_callback' => array(
				array(
					'setting'  => 'blog_page_header',
					'operator' => '==',
					'value'    => 1,
				),
				array(
					'setting'  => 'blog_page_header_layout',
					'operator' => '==',
					'value'    => '1',
				),
			),
		),
		'blog_page_header_title'           => array(
			'type'            => 'textarea',
			'label'           => esc_html__( 'Blog Title', 'gadget' ),
			'section'         => 'blog_page_header',
			'default'         => '',
			'priority'        => 10,
			'description'     => esc_html__( 'Enter Blog Title', 'gadget' ),
			'active_callback' => array(
				array(
					'setting'  => 'blog_page_header',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'blog_page_header_text_color'      => array(
			'type'            => 'select',
			'label'           => esc_html__( 'Page Header Text Color', 'gadget' ),
			'section'         => 'blog_page_header',
			'default'         => 'dark',
			'choices'         => array(
				'dark'  => esc_html__( 'Dark', 'gadget' ),
				'light' => esc_html__( 'Light', 'gadget' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'blog_page_header',
					'operator' => '==',
					'value'    => 1,
				),
				array(
					'setting'  => 'blog_page_header_layout',
					'operator' => 'in',
					'value'    => array( '2', '3' ),
				),
			),
		),
		'blog_page_header_parallax'        => array(
			'type'            => 'toggle',
			'label'           => esc_html__( 'Enable Parallax', 'gadget' ),
			'section'         => 'blog_page_header',
			'default'         => 1,
			'priority'        => 20,
			'active_callback' => array(
				array(
					'setting'  => 'blog_page_header',
					'operator' => '==',
					'value'    => 1,
				),
				array(
					'setting'  => 'blog_page_header_layout',
					'operator' => 'in',
					'value'    => array( '2', '3' ),
				),
			),
		),
		'blog_page_header_background'      => array(
			'type'            => 'image',
			'label'           => esc_html__( 'Background Image', 'gadget' ),
			'section'         => 'blog_page_header',
			'default'         => '',
			'priority'        => 20,
			'active_callback' => array(
				array(
					'setting'  => 'blog_page_header',
					'operator' => '==',
					'value'    => 1,
				),
				array(
					'setting'  => 'blog_page_header_layout',
					'operator' => 'in',
					'value'    => array( '2', '3' ),
				),
			),
		),

		'blog_style'                       => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Blog Style', 'gadget' ),
			'section'  => 'blog_page',
			'default'  => 'list',
			'priority' => 10,
			'choices'  => array(
				'grid'    => esc_html__( 'Grid', 'gadget' ),
				'list'    => esc_html__( 'List', 'gadget' ),
				'masonry' => esc_html__( 'Masonry', 'gadget' ),
			),
		),
		'blog_layout'                      => array(
			'type'            => 'select',
			'label'           => esc_html__( 'Blog Grid Layout', 'gadget' ),
			'section'         => 'blog_page',
			'default'         => 'content-sidebar',
			'priority'        => 10,
			'description'     => esc_html__( 'Select default sidebar for blog classic.', 'gadget' ),
			'choices'         => array(
				'content-sidebar' => esc_html__( 'Right Sidebar', 'gadget' ),
				'sidebar-content' => esc_html__( 'Left Sidebar', 'gadget' ),
				'full-content'    => esc_html__( 'Full Content', 'gadget' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'blog_style',
					'operator' => '==',
					'value'    => 'grid',
				),
			),
		),
		'blog_entry_meta'                  => array(
			'type'     => 'multicheck',
			'label'    => esc_html__( 'Entry Metas', 'gadget' ),
			'section'  => 'blog_page',
			'default'  => array( 'cat', 'date' ),
			'choices'  => array(
				'cat'  => esc_html__( 'Category', 'gadget' ),
				'date' => esc_html__( 'Date', 'gadget' ),
			),
			'priority' => 10,
		),
		'excerpt_length'                   => array(
			'type'            => 'number',
			'label'           => esc_html__( 'Excerpt Length', 'gadget' ),
			'section'         => 'blog_page',
			'default'         => '20',
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'blog_style',
					'operator' => '==',
					'value'    => 'list',
				),
			),
		),
		'blog_custom_field_1'              => array(
			'type'    => 'custom',
			'section' => 'blog_page',
			'default' => '<hr/>',
		),
		'blog_cat_filter'                  => array(
			'type'     => 'toggle',
			'default'  => 0,
			'label'    => esc_html__( 'Categories Filter', 'gadget' ),
			'section'  => 'blog_page',
			'priority' => 10,
		),
		'blog_categories_numbers'          => array(
			'type'            => 'number',
			'label'           => esc_html__( 'Categories Numbers', 'gadget' ),
			'section'         => 'blog_page',
			'default'         => '5',
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'blog_cat_filter',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'blog_categories'                  => array(
			'type'            => 'textarea',
			'label'           => esc_html__( 'Custom Categories', 'gadget' ),
			'section'         => 'blog_page',
			'default'         => '',
			'priority'        => 10,
			'description'     => esc_html__( 'Enter categories slug you want to display. Each slug is separated by comma character ",". If empty, it will display default', 'gadget' ),
			'active_callback' => array(
				array(
					'setting'  => 'blog_cat_filter',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),

		// Single Posts
		'single_post_layout'               => array(
			'type'        => 'select',
			'label'       => esc_html__( 'Single Post Layout', 'gadget' ),
			'section'     => 'single_post',
			'default'     => 'sidebar-content',
			'priority'    => 5,
			'description' => esc_html__( 'Select default sidebar for the single post page.', 'gadget' ),
			'choices'     => array(
				'content-sidebar' => esc_html__( 'Right Sidebar', 'gadget' ),
				'sidebar-content' => esc_html__( 'Left Sidebar', 'gadget' ),
				'full-content'    => esc_html__( 'Full Content', 'gadget' ),
			),
		),
		'post_entry_meta'                  => array(
			'type'     => 'multicheck',
			'label'    => esc_html__( 'Entry Meta', 'gadget' ),
			'section'  => 'single_post',
			'default'  => array( 'author', 'scat', 'date' ),
			'choices'  => array(
				'scat'   => esc_html__( 'Category', 'gadget' ),
				'author' => esc_html__( 'Author', 'gadget' ),
				'date'   => esc_html__( 'Date', 'gadget' ),
			),
			'priority' => 10,
		),
		'post_custom_field_1'              => array(
			'type'    => 'custom',
			'section' => 'single_post',
			'default' => '<hr/>',
		),

		'show_post_social_share'           => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Show Socials Share', 'gadget' ),
			'description' => esc_html__( 'Check this option to show socials share in the single post page.', 'gadget' ),
			'section'     => 'single_post',
			'default'     => 0,
			'priority'    => 10,
		),

		'post_socials_share'               => array(
			'type'            => 'multicheck',
			'label'           => esc_html__( 'Socials Share', 'gadget' ),
			'section'         => 'single_post',
			'default'         => array( 'facebook', 'twitter', 'google', 'tumblr' ),
			'choices'         => array(
				'facebook'  => esc_html__( 'Facebook', 'gadget' ),
				'twitter'   => esc_html__( 'Twitter', 'gadget' ),
				'google'    => esc_html__( 'Google Plus', 'gadget' ),
				'tumblr'    => esc_html__( 'Tumblr', 'gadget' ),
				'pinterest' => esc_html__( 'Pinterest', 'gadget' ),
				'linkedin'  => esc_html__( 'Linkedin', 'gadget' ),
			),
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'show_post_social_share',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'show_author_box'                  => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Show Author Box', 'gadget' ),
			'section'  => 'single_post',
			'default'  => 1,
			'priority' => 10,
		),
		'post_custom_field_2'              => array(
			'type'    => 'custom',
			'section' => 'single_post',
			'default' => '<hr/>',
		),
		'related_posts'                    => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Related Posts', 'gadget' ),
			'section'     => 'single_post',
			'description' => esc_html__( 'Check this option to show related posts in the single post page.', 'gadget' ),
			'default'     => 1,
			'priority'    => 20,
		),
		'related_posts_title'              => array(
			'type'            => 'text',
			'label'           => esc_html__( 'Related Posts Title', 'gadget' ),
			'section'         => 'single_post',
			'default'         => esc_html__( 'You may also like', 'gadget' ),
			'priority'        => 20,

			'active_callback' => array(
				array(
					'setting'  => 'related_post',
					'operator' => '==',
					'value'    => true,
				),
			),
		),
		'related_posts_numbers'            => array(
			'type'            => 'number',
			'label'           => esc_html__( 'Related Posts Numbers', 'gadget' ),
			'section'         => 'single_post',
			'default'         => '2',
			'priority'        => 20,
			'active_callback' => array(
				array(
					'setting'  => 'related_post',
					'operator' => '==',
					'value'    => true,
				),
			),
		),
		// Catalog
		'catalog_custom'                   => array(
			'type'     => 'custom',
			'section'  => 'woocommerce_product_catalog',
			'default'  => '<hr>',
			'priority' => 70,
		),
		'catalog_layout'                   => array(
			'type'        => 'select',
			'label'       => esc_html__( 'Catalog Layout', 'gadget' ),
			'default'     => 'full-content',
			'section'     => 'woocommerce_product_catalog',
			'priority'    => 70,
			'description' => esc_html__( 'Select layout for catalog.', 'gadget' ),
			'choices'     => array(
				'sidebar-content' => esc_html__( 'Left Sidebar', 'gadget' ),
				'content-sidebar' => esc_html__( 'Right Sidebar', 'gadget' ),
				'full-content'    => esc_html__( 'Full Content', 'gadget' ),
			),
		),
		'catalog_full_width'               => array(
			'type'            => 'toggle',
			'label'           => esc_html__( 'Catalog Full Width', 'gadget' ),
			'default'         => '0',
			'section'         => 'woocommerce_product_catalog',
			'priority'        => 70,
			'active_callback' => array(
				array(
					'setting'  => 'catalog_layout',
					'operator' => 'in',
					'value'    => array( 'sidebar-content', 'content-sidebar', 'full-content' ),
				),
			),
		),
		'shop_view'                        => array(
			'type'            => 'select',
			'label'           => esc_html__( 'Catalog View', 'gadget' ),
			'description'     => esc_html__( 'Select Catalog View', 'gadget' ),
			'section'         => 'woocommerce_product_catalog',
			'priority'        => 70,
			'default'         => 'grid',
			'choices'         => array(
				'grid' => esc_html__( 'Grid', 'gadget' ),
				'list' => esc_html__( 'List', 'gadget' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'catalog_layout',
					'operator' => 'in',
					'value'    => array( 'sidebar-content', 'content-sidebar', 'full-content' ),
				),
			),
		),
		'product_attribute'                => array(
			'type'        => 'select',
			'label'       => esc_html__( 'Product Attribute', 'gadget' ),
			'section'     => 'woocommerce_product_catalog',
			'default'     => 'none',
			'priority'    => 20,
			'choices'     => gadgetexpress_product_attributes(),
			'description' => esc_html__( 'Show product attribute for each item listed under the item name.', 'gadget' ),
		),
		'catalog_custom_2'                 => array(
			'type'     => 'custom',
			'section'  => 'woocommerce_product_catalog',
			'default'  => '<hr>',
			'priority' => 70,
		),
		'add_to_cart_action'               => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Catalog Add to Cart Action', 'gadget' ),
			'section'  => 'woocommerce_product_catalog',
			'priority' => 70,
			'default'  => 'notice',
			'choices'  => array(
				'notice' => esc_html__( 'Show Notice', 'gadget' ),
				'cart'   => esc_html__( 'Show Cart Sidebar', 'gadget' ),
			),
		),
		'catalog_page_header_custom'       => array(
			'type'     => 'custom',
			'section'  => 'woocommerce_product_catalog',
			'default'  => '<hr>',
			'priority' => 70,
		),
		// Catalog Page Header
		'catalog_page_header'              => array(
			'type'            => 'toggle',
			'default'         => 1,
			'label'           => esc_html__( 'Enable Page Header', 'gadget' ),
			'section'         => 'woocommerce_product_catalog',
			'description'     => esc_html__( 'Enable to show a page header for catalog page below the site header', 'gadget' ),
			'priority'        => 70,
			'active_callback' => array(
				array(
					'setting'  => 'catalog_layout',
					'operator' => 'in',
					'value'    => array( 'sidebar-content', 'content-sidebar', 'full-content' ),
				),
			),
		),
		'catalog_page_header_breadcrumbs'  => array(
			'type'            => 'toggle',
			'default'         => 1,
			'label'           => esc_html__( 'Enable Breadcrumbs', 'gadget' ),
			'section'         => 'woocommerce_product_catalog',
			'description'     => esc_html__( 'Enable to show a breadcrumbs for catalog page below the site header', 'gadget' ),
			'priority'        => 70,
			'active_callback' => array(
				array(
					'setting'  => 'catalog_page_header',
					'operator' => '==',
					'value'    => 1,
				),
				array(
					'setting'  => 'catalog_layout',
					'operator' => 'in',
					'value'    => array( 'sidebar-content', 'content-sidebar', 'full-content' ),
				),
			),
		),
		'catalog_page_header_layout'       => array(
			'type'            => 'select',
			'label'           => esc_html__( 'Page Header Layout', 'gadget' ),
			'section'         => 'woocommerce_product_catalog',
			'default'         => '1',
			'priority'        => 70,
			'choices'         => array(
				'1' => esc_html__( 'Layout 1', 'gadget' ),
				'2' => esc_html__( 'Layout 2', 'gadget' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'catalog_page_header',
					'operator' => '==',
					'value'    => 1,
				),
				array(
					'setting'  => 'catalog_layout',
					'operator' => 'in',
					'value'    => array( 'sidebar-content', 'content-sidebar', 'full-content' ),
				),
			),
		),
		'shop_toolbar'                     => array(
			'type'            => 'multicheck',
			'label'           => esc_html__( 'Shop Toolbar', 'gadget' ),
			'section'         => 'woocommerce_product_catalog',
			'default'         => array( 'result', 'sort_by', 'shop_view' ),
			'priority'        => 70,
			'choices'         => array(
				'result'    => esc_html__( 'Result', 'gadget' ),
				'filter'    => esc_html__( 'Filter', 'gadget' ),
				'sort_by'   => esc_html__( 'Sort By', 'gadget' ),
				'shop_view' => esc_html__( 'Shop View', 'gadget' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'catalog_page_header',
					'operator' => '==',
					'value'    => 1,
				),
				array(
					'setting'  => 'catalog_layout',
					'operator' => 'in',
					'value'    => array( 'sidebar-content', 'content-sidebar', 'full-content' ),
				),
			),
			'description'     => esc_html__( 'Select which elements you want to show on shop toolbar', 'gadget' ),
		),
		'added_to_cart_notice_custom'      => array(
			'type'     => 'custom',
			'section'  => 'woocommerce_product_catalog',
			'default'  => '<hr>',
			'priority' => 70,
		),

		'added_to_cart_notice'             => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Added to Cart Notification', 'gadget' ),
			'description' => esc_html__( 'Display a notification when a product is added to cart', 'gadget' ),
			'section'     => 'woocommerce_product_catalog',
			'priority'    => 70,
			'default'     => 1,
		),
		'cart_notice_auto_hide'            => array(
			'type'        => 'number',
			'label'       => esc_html__( 'Cart Notification Auto Hide', 'gadget' ),
			'description' => esc_html__( 'How many seconds you want to hide the notification.', 'gadget' ),
			'section'     => 'woocommerce_product_catalog',
			'priority'    => 70,
			'default'     => 3,
		),
		'catalog_ajax_filter_custom'       => array(
			'type'     => 'custom',
			'section'  => 'woocommerce_product_catalog',
			'default'  => '<hr>',
			'priority' => 70,
		),
		'catalog_ajax_filter'              => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Ajax For Filtering', 'gadget' ),
			'section'     => 'woocommerce_product_catalog',
			'description' => esc_html__( 'Check this option to use ajax for filtering in the catalog page.', 'gadget' ),
			'default'     => 1,
			'priority'    => 70
		),
		'disable_secondary_thumb'          => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Disable Secondary Product Thumbnail', 'gadget' ),
			'section'     => 'woocommerce_product_catalog',
			'default'     => 0,
			'priority'    => 70,
			'description' => esc_html__( 'Check this option to disable secondary product thumbnail when hover over the main product image.', 'gadget' ),
		),
		'shop_nav_type'                    => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Type of Navigation', 'gadget' ),
			'section'  => 'woocommerce_product_catalog',
			'default'  => 'numbers',
			'priority' => 90,
			'choices'  => array(
				'numbers'  => esc_html__( 'Page Numbers', 'gadget' ),
				'ajax'     => esc_html__( 'Ajax Loading', 'gadget' ),
				'infinite' => esc_html__( 'Infinite Scroll', 'gadget' ),
			),
		),

		//Badge
		'show_badges'                      => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Show Badges', 'gadget' ),
			'section'     => 'shop_badge',
			'default'     => 1,
			'priority'    => 20,
			'description' => esc_html__( 'Check this to show badges on every products.', 'gadget' ),
		),
		'badges'                           => array(
			'type'            => 'multicheck',
			'label'           => esc_html__( 'Badges', 'gadget' ),
			'section'         => 'shop_badge',
			'default'         => array( 'hot', 'new', 'sale', 'outofstock' ),
			'priority'        => 20,
			'choices'         => array(
				'hot'        => esc_html__( 'Hot', 'gadget' ),
				'new'        => esc_html__( 'New', 'gadget' ),
				'sale'       => esc_html__( 'Sale', 'gadget' ),
				'outofstock' => esc_html__( 'Out Of Stock', 'gadget' ),
			),
			'description'     => esc_html__( 'Select which badges you want to show', 'gadget' ),
			'active_callback' => array(
				array(
					'setting'  => 'show_badges',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'sale_behaviour'                   => array(
			'type'            => 'radio',
			'label'           => esc_html__( 'Sale Behaviour', 'gadget' ),
			'default'         => 'text',
			'section'         => 'shop_badge',
			'priority'        => 20,
			'choices'         => array(
				'text'       => esc_attr__( 'Show Text', 'gadget' ),
				'percentage' => esc_attr__( 'Show Percentage Discount', 'gadget' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'badges',
					'operator' => 'contains',
					'value'    => 'sale',
				),
			),
		),
		'sale_text'                        => array(
			'type'            => 'text',
			'label'           => esc_html__( 'Custom Sale Text', 'gadget' ),
			'section'         => 'shop_badge',
			'default'         => 'Sale',
			'priority'        => 20,
			'active_callback' => array(
				array(
					'setting'  => 'show_badges',
					'operator' => '==',
					'value'    => 1,
				),
				array(
					'setting'  => 'sale_behaviour',
					'operator' => '==',
					'value'    => 'text',
				),
				array(
					'setting'  => 'badges',
					'operator' => 'contains',
					'value'    => 'sale',
				),
			),
		),
		'hot_text'                         => array(
			'type'            => 'text',
			'label'           => esc_html__( 'Custom Hot Text', 'gadget' ),
			'section'         => 'shop_badge',
			'default'         => 'Hot',
			'priority'        => 20,
			'active_callback' => array(
				array(
					'setting'  => 'show_badges',
					'operator' => '==',
					'value'    => 1,
				),
				array(
					'setting'  => 'badges',
					'operator' => 'contains',
					'value'    => 'hot',
				),
			),
		),
		'outofstock_text'                  => array(
			'type'            => 'text',
			'label'           => esc_html__( 'Custom Out Of Stock Text', 'gadget' ),
			'section'         => 'shop_badge',
			'default'         => 'Out Of Stock',
			'priority'        => 20,
			'active_callback' => array(
				array(
					'setting'  => 'show_badges',
					'operator' => '==',
					'value'    => 1,
				),
				array(
					'setting'  => 'badges',
					'operator' => 'contains',
					'value'    => 'outofstock',
				),
			),
		),
		'new_text'                         => array(
			'type'            => 'text',
			'label'           => esc_html__( 'Custom New Text', 'gadget' ),
			'section'         => 'shop_badge',
			'default'         => esc_html__( 'New', 'gadget' ),
			'priority'        => 20,
			'active_callback' => array(
				array(
					'setting'  => 'show_badges',
					'operator' => '==',
					'value'    => 1,
				),
				array(
					'setting'  => 'badges',
					'operator' => 'contains',
					'value'    => 'new',
				),
			),
		),
		'product_newness'                  => array(
			'type'            => 'number',
			'label'           => esc_html__( 'Product Newness', 'gadget' ),
			'section'         => 'shop_badge',
			'default'         => 3,
			'priority'        => 20,
			'description'     => esc_html__( 'Display the "New" badge for how many days?', 'gadget' ),
			'active_callback' => array(
				array(
					'setting'  => 'show_badges',
					'operator' => '==',
					'value'    => 1,
				),
				array(
					'setting'  => 'badges',
					'operator' => 'contains',
					'value'    => 'new',
				),
			),
		),
		// Single Product
		'single_product_layout'            => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Single Product Layout', 'gadget' ),
			'section'  => 'single_product',
			'default'  => '1',
			'priority' => 10,
			'choices'  => array(
				'1' => esc_html__( 'Layout 1', 'gadget' ),
				'2' => esc_html__( 'Layout 2', 'gadget' ),
				'3' => esc_html__( 'Layout 3', 'gadget' ),
				'4' => esc_html__( 'Layout 4', 'gadget' ),
				'5' => esc_html__( 'Layout 5', 'gadget' ),
				'6' => esc_html__( 'Layout 6', 'gadget' ),
			),
		),
		'single_product_sidebar'           => array(
			'type'            => 'select',
			'label'           => esc_html__( 'Single Product Sidebar', 'gadget' ),
			'section'         => 'single_product',
			'default'         => 'full-content',
			'priority'        => 10,
			'choices'         => array(
				'sidebar-content' => esc_html__( 'Left Sidebar', 'gadget' ),
				'content-sidebar' => esc_html__( 'Right Sidebar', 'gadget' ),
				'full-content'    => esc_html__( 'Full Content', 'gadget' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'single_product_layout',
					'operator' => '==',
					'value'    => '1',
				),
			),
		),
		'single_product_background_color'  => array(
			'type'            => 'color',
			'label'           => esc_html__( 'Background Color', 'gadget' ),
			'default'         => '#f2f1f0',
			'section'         => 'single_product',
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'single_product_layout',
					'operator' => '==',
					'value'    => '2',
				),
			),
		),
		'product_add_to_cart_ajax'         => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Add to cart with AJAX', 'gadget' ),
			'section'     => 'single_product',
			'default'     => 1,
			'priority'    => 40,
			'description' => esc_html__( 'Check this option to enable add to cart with AJAX on the product page.', 'gadget' ),
		),

		'product_add_to_cart_sticky'         => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Add to cart Sticky', 'gadget' ),
			'section'     => 'single_product',
			'default'     => 1,
			'priority'    => 40,
			'description' => esc_html__( 'Check this option to enable add to cart sticky on the product page on mobile.', 'gadget' ),
		),

		'product_zoom'                     => array(
			'type'            => 'toggle',
			'label'           => esc_html__( 'Product Zoom', 'gadget' ),
			'section'         => 'single_product',
			'default'         => 0,
			'description'     => esc_html__( 'Check this option to show a bigger size product image on mouseover', 'gadget' ),
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'single_product_layout',
					'operator' => 'in',
					'value'    => array( '1', '2' ),
				),
			),
		),
		'product_images_lightbox'          => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Product Images Gallery', 'gadget' ),
			'section'     => 'single_product',
			'default'     => 1,
			'description' => esc_html__( 'Check this option to open product gallery images in a lightbox', 'gadget' ),
			'priority'    => 10,
		),
		'show_product_socials'             => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Show Product Socials', 'gadget' ),
			'section'  => 'single_product',
			'default'  => 1,
			'priority' => 10,
		),
		'single_product_socials_share'     => array(
			'type'            => 'multicheck',
			'label'           => esc_html__( 'Socials Share', 'gadget' ),
			'section'         => 'single_product',
			'default'         => array( 'facebook', 'twitter', 'pinterest' ),
			'choices'         => array(
				'facebook'  => esc_html__( 'Facebook', 'gadget' ),
				'twitter'   => esc_html__( 'Twitter', 'gadget' ),
				'google'    => esc_html__( 'Google Plus', 'gadget' ),
				'tumblr'    => esc_html__( 'Tumblr', 'gadget' ),
				'pinterest' => esc_html__( 'Pinterest', 'gadget' ),
				'linkedin'  => esc_html__( 'Linkedin', 'gadget' ),
			),
			'priority'        => 10,
			'active_callback' => array(
				array(
					'setting'  => 'show_product_socials',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'show_product_meta'                => array(
			'type'        => 'multicheck',
			'label'       => esc_html__( 'Show Product Meta', 'gadget' ),
			'section'     => 'single_product',
			'default'     => array( 'sku', 'categories', 'tags' ),
			'priority'    => 40,
			'choices'     => array(
				'sku'        => esc_html__( 'SKU', 'gadget' ),
				'categories' => esc_html__( 'Categories', 'gadget' ),
				'tags'       => esc_html__( 'Tags', 'gadget' ),
			),
			'description' => esc_html__( 'Select which product meta you want to show in single product page', 'gadget' ),
		),
		'single_product_toolbar'           => array(
			'type'        => 'multicheck',
			'label'       => esc_html__( 'Product Toolbar', 'gadget' ),
			'section'     => 'single_product',
			'default'     => array( 'breadcrumb', 'navigation' ),
			'priority'    => 40,
			'choices'     => array(
				'breadcrumb' => esc_html__( 'Breadcrumb', 'gadget' ),
				'navigation' => esc_html__( 'Navigation', 'gadget' ),
			),
			'description' => esc_html__( 'Select element you want to show on product toolbar in single product page', 'gadget' ),
		),
		'product_related_custom'           => array(
			'type'     => 'custom',
			'section'  => 'single_product',
			'default'  => '<hr>',
			'priority' => 40,
		),
		'product_related'                  => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Show Related Products', 'gadget' ),
			'section'  => 'single_product',
			'default'  => 1,
			'priority' => 40,
		),
		'product_related_title'            => array(
			'type'            => 'text',
			'label'           => esc_html__( 'Related Products Title', 'gadget' ),
			'section'         => 'single_product',
			'default'         => esc_html__( 'Related products', 'gadget' ),
			'priority'        => 40,
			'active_callback' => array(
				array(
					'setting'  => 'product_related',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'related_products_columns'         => array(
			'type'            => 'select',
			'label'           => esc_html__( 'Related Products Columns', 'gadget' ),
			'section'         => 'single_product',
			'default'         => '4',
			'priority'        => 40,
			'description'     => esc_html__( 'Specify how many columns of related products you want to show on single product page', 'gadget' ),
			'choices'         => array(
				'3' => esc_html__( '3 Columns', 'gadget' ),
				'4' => esc_html__( '4 Columns', 'gadget' ),
				'5' => esc_html__( '5 Columns', 'gadget' ),
				'6' => esc_html__( '6 Columns', 'gadget' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'product_related',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'related_products_numbers'         => array(
			'type'            => 'number',
			'label'           => esc_html__( 'Related Products Numbers', 'gadget' ),
			'section'         => 'single_product',
			'default'         => 4,
			'priority'        => 40,
			'description'     => esc_html__( 'Specify how many numbers of related products you want to show on single product page', 'gadget' ),
			'active_callback' => array(
				array(
					'setting'  => 'product_related',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'product_upsells_custom'           => array(
			'type'     => 'custom',
			'section'  => 'single_product',
			'default'  => '<hr>',
			'priority' => 40,
		),
		'product_upsells'                  => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Show Up-sells Products', 'gadget' ),
			'section'  => 'single_product',
			'default'  => 0,
			'priority' => 40,
		),
		'product_upsells_title'            => array(
			'type'            => 'text',
			'label'           => esc_html__( 'Up-sells Products Title', 'gadget' ),
			'section'         => 'single_product',
			'default'         => esc_html__( 'You may also like', 'gadget' ),
			'priority'        => 40,
			'active_callback' => array(
				array(
					'setting'  => 'product_upsells',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'upsells_products_columns'         => array(
			'type'            => 'select',
			'label'           => esc_html__( 'Up-sells Products Columns', 'gadget' ),
			'section'         => 'single_product',
			'default'         => '4',
			'priority'        => 40,
			'description'     => esc_html__( 'Specify how many columns of up-sells products you want to show on single product page', 'gadget' ),
			'choices'         => array(
				'3' => esc_html__( '3 Columns', 'gadget' ),
				'4' => esc_html__( '4 Columns', 'gadget' ),
				'5' => esc_html__( '5 Columns', 'gadget' ),
				'6' => esc_html__( '6 Columns', 'gadget' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'product_upsells',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),
		'upsells_products_numbers'         => array(
			'type'            => 'number',
			'label'           => esc_html__( 'Up-sells Products Numbers', 'gadget' ),
			'section'         => 'single_product',
			'default'         => 4,
			'priority'        => 40,
			'description'     => esc_html__( 'Specify how many numbers of up-sells products you want to show on single product page', 'gadget' ),
			'active_callback' => array(
				array(
					'setting'  => 'product_upsells',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),

		// Portfolio Page Header
		'portfolio_page_header'            => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Enable Page Header', 'gadget' ),
			'section'     => 'portfolio_page_header',
			'description' => esc_html__( 'Enable to show a page header for portfolio below the site header', 'gadget' ),
			'default'     => 1,
			'priority'    => 10,
		),
		'portfolio_breadcrumb'             => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Enable Breadcrumb', 'gadget' ),
			'section'     => 'portfolio_page_header',
			'description' => esc_html__( 'Enable to show a breadcrumb on page header', 'gadget' ),
			'default'     => 1,
			'priority'    => 10,
		),
		'portfolio_page_header_text_color' => array(
			'type'    => 'select',
			'label'   => esc_html__( 'Page Header Text Color', 'gadget' ),
			'section' => 'page_header',
			'default' => 'dark',
			'choices' => array(
				'dark'  => esc_html__( 'Dark', 'gadget' ),
				'light' => esc_html__( 'Light', 'gadget' ),
			),
		),
		'portfolio_page_header_background' => array(
			'type'     => 'image',
			'label'    => esc_html__( 'Background Image', 'gadget' ),
			'section'  => 'page_header',
			'default'  => '',
			'priority' => 20,
		),
		'portfolio_page_header_parallax'   => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Enable Parallax', 'gadget' ),
			'section'  => 'page_header',
			'default'  => 1,
			'priority' => 20,
		),

		// Portfolio
		'portfolio_layout'                 => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Portfolio Layout', 'gadget' ),
			'section'  => 'portfolio',
			'default'  => 'grid',
			'priority' => 10,
			'choices'  => array(
				'grid'     => esc_html__( 'Grid', 'gadget' ),
				'masonry'  => esc_html__( 'Masonry', 'gadget' ),
				'carousel' => esc_html__( 'Carousel', 'gadget' ),
			),
		),

		'portfolio_category_filter'        => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Category Filter', 'gadget' ),
			'description' => esc_html__( 'Check this option to display Category Filter in the portfolio page.', 'gadget' ),
			'section'     => 'portfolio',
			'default'     => 1,
			'priority'    => 10,
		),

		'portfolio_nav_type'               => array(
			'type'            => 'radio',
			'label'           => esc_html__( 'Portfolio Navigation Type', 'gadget' ),
			'section'         => 'portfolio',
			'default'         => 'ajax',
			'priority'        => 10,
			'choices'         => array(
				'ajax'    => esc_html__( 'Loading Ajax', 'gadget' ),
				'numeric' => esc_html__( 'Numeric', 'gadget' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'portfolio_layout',
					'operator' => 'in',
					'value'    => array( 'grid', 'masonry' ),
				),
			),
		),

		'portfolio_text_color'             => array(
			'type'            => 'select',
			'label'           => esc_html__( 'Portfolio Text Color', 'gadget' ),
			'section'         => 'portfolio',
			'default'         => 'dark',
			'choices'         => array(
				'dark'  => esc_html__( 'Dark', 'gadget' ),
				'light' => esc_html__( 'Light', 'gadget' ),
			),
			'active_callback' => array(
				array(
					'setting'  => 'portfolio_layout',
					'operator' => '==',
					'value'    => 'carousel',
				),
			),
		),

		// Single Portfolio
		'single_portfolio_show_socials'    => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Show Socials Share', 'gadget' ),
			'description' => esc_html__( 'Check this option to show socials share in the single portfolio page.', 'gadget' ),
			'section'     => 'single_portfolio',
			'default'     => 0,
			'priority'    => 10,
		),
		'single_portfolio_socials'         => array(
			'type'            => 'repeater',
			'label'           => esc_html__( 'Socials', 'gadget' ),
			'section'         => 'single_portfolio',
			'priority'        => 10,
			'default'         => array(
				array(
					'link_url' => 'https://facebook.com/toffedassen',
				),
				array(
					'link_url' => 'https://twitter.com/toffedassen',
				),
				array(
					'link_url' => 'https://plus.google.com/toffedassen',
				),
				array(
					'link_url' => 'https://dribbble.com/',
				),
			),
			'fields'          => array(
				'link_url' => array(
					'type'        => 'text',
					'label'       => esc_html__( 'Social URL', 'gadget' ),
					'description' => esc_html__( 'Enter the URL for this social', 'gadget' ),
					'default'     => '',
				),
			),
			'active_callback' => array(
				array(
					'setting'  => 'single_portfolio_show_socials',
					'operator' => '==',
					'value'    => 1,
				),
			),
		),

		// Footer Newsletter
		'footer_newsletter'                => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Newsletter', 'gadget' ),
			'section'  => 'footer_newsletter',
			'default'  => 0,
			'priority' => 10,
		),
		'footer_newsletter_home'           => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Show on HomePage', 'gadget' ),
			'section'     => 'footer_newsletter',
			'default'     => 1,
			'priority'    => 10,
			'description' => esc_html__( 'Just show newsletter on HomePage', 'gadget' ),
		),
		'newsletter_style'                 => array(
			'type'        => 'select',
			'label'       => esc_html__( 'Style', 'gadget' ),
			'section'     => 'footer_newsletter',
			'default'     => 'space-between',
			'priority'    => 10,
			'choices'     => array(
				'space-between' => esc_html__( 'Space Between', 'gadget' ),
				'center'        => esc_html__( 'Center', 'gadget' ),
			),
			'description' => esc_html__( 'Select Style for Newsletter', 'gadget' ),
		),
		'newsletter_shape'                 => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Shape', 'gadget' ),
			'section'  => 'footer_newsletter',
			'default'  => 'square',
			'priority' => 10,
			'choices'  => array(
				'square' => esc_html__( 'Square', 'gadget' ),
				'round'  => esc_html__( 'Round', 'gadget' ),
			),
		),
		'newsletter_title'                 => array(
			'type'     => 'text',
			'label'    => esc_html__( 'Newsletter Title', 'gadget' ),
			'section'  => 'footer_newsletter',
			'default'  => esc_html__( 'Keep Connected', 'gadget' ),
			'priority' => 10,
		),
		'newsletter_desc'                  => array(
			'type'     => 'textarea',
			'label'    => esc_html__( 'Newsletter Description', 'gadget' ),
			'section'  => 'footer_newsletter',
			'default'  => esc_html__( 'Get updates by subscribe our weekly newsletter', 'gadget' ),
			'priority' => 10,
		),
		'newsletter_form'                  => array(
			'type'        => 'textarea',
			'label'       => esc_html__( 'Newsletter Form', 'gadget' ),
			'section'     => 'footer_newsletter',
			'default'     => '',
			'priority'    => 10,
			'description' => esc_html__( 'Go to MailChimp for WP/Form to get shortcode', 'gadget' ),
		),
		'newsletter_text_color'            => array(
			'type'     => 'color',
			'label'    => esc_html__( 'Color', 'gadget' ),
			'default'  => '',
			'section'  => 'footer_newsletter',
			'priority' => 30,
		),
		'newsletter_background_color'      => array(
			'type'     => 'color',
			'label'    => esc_html__( 'Background Color', 'gadget' ),
			'default'  => '',
			'section'  => 'footer_newsletter',
			'priority' => 30,
		),

		// Footer
		'footer_layout'                    => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Footer Layout', 'gadget' ),
			'section'  => 'footer_layout',
			'default'  => '1',
			'priority' => 10,
			'choices'  => array(
				'1' => esc_html__( 'Layout 1', 'gadget' ),
				'2' => esc_html__( 'Layout 2', 'gadget' ),
				'3' => esc_html__( 'Layout 3', 'gadget' ),
			),
		),
		'footer_skin'                      => array(
			'type'     => 'radio',
			'label'    => esc_html__( 'Footer Skin', 'gadget' ),
			'section'  => 'footer_layout',
			'priority' => 10,
			'default'  => 'light',
			'choices'  => array(
				'light' => esc_html__( 'Light', 'gadget' ),
				'dark'  => esc_html__( 'Dark', 'gadget' ),
			),
		),
		'footer_background_color'          => array(
			'type'     => 'color',
			'label'    => esc_html__( 'Background Color', 'gadget' ),
			'default'  => '',
			'section'  => 'footer_layout',
			'priority' => 10,
		),
		'footer_background_image'          => array(
			'type'     => 'image',
			'label'    => esc_html__( 'Background Image', 'gadget' ),
			'default'  => '',
			'section'  => 'footer_layout',
			'priority' => 10,
		),
		'footer_background_horizontal'     => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Background Horizontal', 'gadget' ),
			'section'  => 'footer_layout',
			'default'  => '',
			'priority' => 10,
			'choices'  => array(
				''       => esc_html__( 'None', 'gadget' ),
				'left'   => esc_html__( 'Left', 'gadget' ),
				'center' => esc_html__( 'Center', 'gadget' ),
				'right'  => esc_html__( 'Right', 'gadget' ),
			),
		),
		'footer_background_vertical'       => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Background Vertical', 'gadget' ),
			'section'  => 'footer_layout',
			'default'  => '',
			'priority' => 10,
			'choices'  => array(
				''       => esc_html__( 'None', 'gadget' ),
				'top'    => esc_html__( 'Top', 'gadget' ),
				'center' => esc_html__( 'Center', 'gadget' ),
				'bottom' => esc_html__( 'Bottom', 'gadget' ),
			),
		),
		'footer_background_repeat'         => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Background Repeat', 'gadget' ),
			'section'  => 'footer_layout',
			'default'  => '',
			'priority' => 10,
			'choices'  => array(
				''          => esc_html__( 'None', 'gadget' ),
				'no-repeat' => esc_html__( 'No Repeat', 'gadget' ),
				'repeat'    => esc_html__( 'Repeat', 'gadget' ),
				'repeat-y'  => esc_html__( 'Repeat Vertical', 'gadget' ),
				'repeat-x'  => esc_html__( 'Repeat Horizontal', 'gadget' ),
			),
		),
		'footer_background_attachment'     => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Background Attachment', 'gadget' ),
			'section'  => 'footer_layout',
			'default'  => '',
			'priority' => 10,
			'choices'  => array(
				''       => esc_html__( 'None', 'gadget' ),
				'scroll' => esc_html__( 'Scroll', 'gadget' ),
				'fixed'  => esc_html__( 'Fixed', 'gadget' ),
			),
		),
		'footer_background_size'           => array(
			'type'     => 'select',
			'label'    => esc_html__( 'Background Size', 'gadget' ),
			'section'  => 'footer_layout',
			'default'  => '',
			'priority' => 10,
			'choices'  => array(
				''        => esc_html__( 'None', 'gadget' ),
				'auto'    => esc_html__( 'Auto', 'gadget' ),
				'cover'   => esc_html__( 'Cover', 'gadget' ),
				'contain' => esc_html__( 'Contain', 'gadget' ),
			),
		),

		// Footer Widgets

		'footer_widgets'                   => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Footer Widgets', 'gadget' ),
			'section'  => 'footer_widgets',
			'default'  => 1,
			'priority' => 10,
		),
		'footer_widgets_columns'           => array(
			'type'        => 'select',
			'label'       => esc_html__( 'Footer Widgets Columns', 'gadget' ),
			'section'     => 'footer_widgets',
			'default'     => '4',
			'priority'    => 10,
			'choices'     => array(
				'1' => esc_html__( '1 Columns', 'gadget' ),
				'2' => esc_html__( '2 Columns', 'gadget' ),
				'3' => esc_html__( '3 Columns', 'gadget' ),
				'4' => esc_html__( '4 Columns', 'gadget' ),
			),
			'description' => esc_html__( 'Go to Appearance/Widgets/Footer Widget 1, 2, 3, 4, 5 to add widgets content.', 'gadget' ),
		),

		// Footer Copyright

		'footer_copyright'                 => array(
			'type'     => 'toggle',
			'label'    => esc_html__( 'Footer Copyright', 'gadget' ),
			'section'  => 'footer_copyright',
			'default'  => 1,
			'priority' => 10,
		),
		'footer_copyright_columns'         => array(
			'type'        => 'select',
			'label'       => esc_html__( 'Footer Copyright Columns', 'gadget' ),
			'section'     => 'footer_copyright',
			'default'     => '3',
			'priority'    => 10,
			'choices'     => array(
				'1' => esc_html__( '1 Columns', 'gadget' ),
				'2' => esc_html__( '2 Columns', 'gadget' ),
				'3' => esc_html__( '3 Columns', 'gadget' ),
			),
			'description' => esc_html__( 'Go to Appearance/Widgets/Footer Copyright 1, 2, 3 to add widgets content.', 'gadget' ),
		),
		'footer_copyright_menu_style'      => array(
			'type'        => 'select',
			'label'       => esc_html__( 'Menu style', 'gadget' ),
			'section'     => 'footer_copyright',
			'default'     => '1',
			'priority'    => 10,
			'choices'     => array(
				'1' => esc_html__( 'Capitalize', 'gadget' ),
				'2' => esc_html__( 'Uppercase', 'gadget' ),
			),
			'description' => esc_html__( 'Select text transform for menu on footer copyright', 'gadget' ),
		),
		'footer_copyright_top_spacing'     => array(
			'type'        => 'number',
			'label'       => esc_html__( 'Top Spacing', 'gadget' ),
			'description' => esc_html__( 'Adjust the top spacing.', 'gadget' ),
			'section'     => 'footer_copyright',
			'default'     => '20',
			'js_vars'     => array(
				array(
					'element'  => '.site-footer .footer-copyright',
					'property' => 'padding-top',
					'units'    => 'px',
				),
			),
			'transport'   => 'postMessage',
		),
		'footer_copyright_bottom_spacing'  => array(
			'type'        => 'number',
			'label'       => esc_html__( 'Bottom Spacing', 'gadget' ),
			'description' => esc_html__( 'Adjust the bottom spacing.', 'gadget' ),
			'section'     => 'footer_copyright',
			'default'     => '10',
			'js_vars'     => array(
				array(
					'element'  => '.site-footer .footer-copyright',
					'property' => 'padding-bottom',
					'units'    => 'px',
				),
			),
			'transport'   => 'postMessage',
		),

		// Mobile
		'menu_mobile_behaviour'            => array(
			'type'    => 'radio',
			'label'   => esc_html__( 'Menu Mobile Icon Behaviour', 'gadget' ),
			'default' => 'icon',
			'section' => 'menu_mobile',
			'choices' => array(
				'icon' => esc_attr__( 'Open sub menu by click on icon', 'gadget' ),
				'item' => esc_attr__( 'Open sub menu by click on item', 'gadget' ),
			),
		),
		// Catalog Mobile
		'catalog_mobile_columns'           => array(
			'type'        => 'select',
			'label'       => esc_html__( 'Catalog Columns', 'gadget' ),
			'default'     => '1',
			'section'     => 'catalog_mobile',
			'priority'    => 70,
			'description' => esc_html__( 'Select catalog columns on mobile.', 'gadget' ),
			'choices'     => array(
				'1' => esc_html__( '1 Column', 'gadget' ),
				'2' => esc_html__( '2 Columns', 'gadget' ),
			),
		),
		'catalog_filter_mobile'            => array(
			'type'        => 'toggle',
			'label'       => esc_html__( 'Filter Mobile Sidebar', 'gadget' ),
			'default'     => '0',
			'section'     => 'catalog_mobile',
			'priority'    => 70,
			'description' => esc_html__( 'The Catalog filter display as sidebar', 'gadget' ),
		),
	);

	$settings['panels']   = apply_filters( 'gadgetexpress_customize_panels', $panels );
	$settings['sections'] = apply_filters( 'gadgetexpress_customize_sections', $sections );
	$settings['fields']   = apply_filters( 'gadgetexpress_customize_fields', $fields );

	return $settings;
}

$gadgetexpress_customize = new Toffe_Dassen_Customize( gadgetexpress_customize_settings() );