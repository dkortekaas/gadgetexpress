<?php
/**
 * Registering meta boxes
 *
 * All the definitions of meta boxes are listed below with comments.
 *
 * For more information, please visit:
 * @link http://www.deluxeblogtips.com/meta-box/
 */


/**
 * Enqueue script for handling actions with meta boxes
 *
 * @since 1.0
 *
 * @param string $hook
 */
function gadgetexpress_meta_box_scripts( $hook ) {
	// Detect to load un-minify scripts when WP_DEBUG is enable
	$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	if ( in_array( $hook, array( 'post.php', 'post-new.php' ) ) ) {
		wp_enqueue_script( 'gadgetexpress-meta-boxes', get_template_directory_uri() . "/js/backend/meta-boxes$min.js", array( 'jquery' ), '20161025', true );
	}
}

add_action( 'admin_enqueue_scripts', 'gadgetexpress_meta_box_scripts' );

/**
 * Registering meta boxes
 *
 * Using Meta Box plugin: http://www.deluxeblogtips.com/meta-box/
 *
 * @see http://www.deluxeblogtips.com/meta-box/docs/define-meta-boxes
 *
 * @param array $meta_boxes Default meta boxes. By default, there are no meta boxes.
 *
 * @return array All registered meta boxes
 */
function gadgetexpress_register_meta_boxes( $meta_boxes ) {
	$post_id = isset( $_GET['post'] ) ? intval( $_GET['post'] ) : false;

	// Post format's meta box
	$meta_boxes[] = array(
		'id'       => 'post-format-settings',
		'title'    => esc_html__( 'Format Details', 'gadget' ),
		'pages'    => array( 'post' ),
		'context'  => 'normal',
		'priority' => 'high',
		'autosave' => true,
		'fields'   => array(
			array(
				'name'             => esc_html__( 'Image', 'gadget' ),
				'id'               => 'image',
				'type'             => 'image_advanced',
				'class'            => 'image',
				'max_file_uploads' => 1,
			),
			array(
				'name'  => esc_html__( 'Gallery', 'gadget' ),
				'id'    => 'images',
				'type'  => 'image_advanced',
				'class' => 'gallery',
			),
			array(
				'name'  => esc_html__( 'Video', 'gadget' ),
				'id'    => 'video',
				'type'  => 'textarea',
				'cols'  => 20,
				'rows'  => 2,
				'class' => 'video',
			),
		),
	);

	// Header Setting
	$meta_boxes[] = array(
		'id'       => 'header-settings',
		'title'    => esc_html__( 'Header Settings', 'gadget' ),
		'pages'    => array( 'page' ),
		'context'  => 'normal',
		'priority' => 'high',
		'fields'   => array(
			array(
				'name' => esc_html__( 'Custom Header', 'gadget' ),
				'id'   => 'custom_header',
				'type' => 'checkbox',
				'std'  => false,
			),
			array(
				'name'  => esc_html__( 'Disable Header Transparent', 'gadget' ),
				'id'    => 'disable_header_transparent',
				'type'  => 'checkbox',
				'std'   => false,
				'class' => 'disable-header-transparent',
			),
			array(
				'name'  => esc_html__( 'Disable Header Sticky', 'gadget' ),
				'id'    => 'disable_header_sticky',
				'type'  => 'checkbox',
				'std'   => false,
				'class' => 'disable-header-sticky',
			),
			array(
				'name'  => esc_html__( 'Hide Border', 'gadget' ),
				'id'    => 'header_border',
				'type'  => 'checkbox',
				'std'   => false,
				'class' => 'header-border',
			),
			array(
				'name'    => esc_html__( 'Header Text Color', 'gadget' ),
				'id'      => 'header_text_color',
				'type'    => 'select',
				'std'     => 'dark',
				'options' => array(
					'dark'   => esc_html__( 'Dark', 'gadget' ),
					'light'  => esc_html__( 'Light', 'gadget' ),
					'custom' => esc_html__( 'Custom', 'gadget' ),
				),
				'class'   => 'header-text-color',
			),
			array(
				'name'  => esc_html__( 'Color', 'gadget' ),
				'id'    => 'header_color',
				'type'  => 'color',
				'class' => 'header-color',
			),
		),
	);

	// Home Left Sidebar
	$meta_boxes[] = array(
		'id'       => 'home-left-sidebar-settings',
		'title'    => esc_html__( 'Header Left Sidebar Settings', 'gadget' ),
		'pages'    => array( 'page' ),
		'context'  => 'normal',
		'priority' => 'high',
		'fields'   => array(
			array(
				'name' => esc_html__( 'Header Account Text', 'gadget' ),
				'id'   => 'header_account_text',
				'type' => 'text',
			),
			array(
				'name' => esc_html__( 'Header Wishlist Text', 'gadget' ),
				'id'   => 'header_wishlist_text',
				'type' => 'text',
			),
			array(
				'name' => esc_html__( 'Header Cart Text', 'gadget' ),
				'id'   => 'header_cart_text',
				'type' => 'text',
			),
			array(
				'name' => esc_html__( 'Header Copyright', 'gadget' ),
				'id'   => 'header_copyright',
				'type' => 'textarea',
			),
			array(
				'type' => 'heading',
				'name' => esc_html__( 'Socials', 'gadget' ),
			),
			array(
				'name'  => esc_html__( 'Header Socials', 'gadget' ),
				'id'    => 'header_socials',
				'type'  => 'text',
				'clone' => true,
				'desc'  => esc_html__( 'Enter socials URL', 'gadget' ),
			),
		),
	);

	// Home Full Slider
	$meta_boxes[] = array(
		'id'       => 'home-full-slider-settings',
		'title'    => esc_html__( 'Newsletter Settings', 'gadget' ),
		'pages'    => array( 'page' ),
		'context'  => 'normal',
		'priority' => 'high',
		'fields'   => array(
			array(
				'name' => esc_html__( 'Hide Newsletter', 'gadget' ),
				'id'   => 'hide_newsletter',
				'type' => 'checkbox',
				'std'  => false,
			),
			array(
				'name' => esc_html__( 'Form Title', 'gadget' ),
				'id'   => 'form_title',
				'type' => 'text',
			),
			array(
				'name' => esc_html__( 'Form Subtitle', 'gadget' ),
				'id'   => 'form_subtitle',
				'type' => 'textarea',
			),
			array(
				'name' => esc_html__( 'Newsletter Form', 'gadget' ),
				'id'   => 'form',
				'type' => 'textarea',
				'desc' => esc_html__( 'Go to MailChimp for WP &gt; Form to get shortcode', 'gadget' )
			),
		),
	);

	if ( ! $post_id || ( 'page' == get_option( 'show_on_front' ) && $post_id != get_option( 'page_for_posts' ) ) ) {
		// Page Background Settings
		$meta_boxes[] = array(
			'id'       => 'page-background-settings',
			'title'    => esc_html__( 'Page Background Settings', 'gadget' ),
			'pages'    => array( 'page' ),
			'context'  => 'normal',
			'priority' => 'high',
			'autosave' => true,
			'fields'   => array(
				array(
					'name' => esc_html__( 'Background Color', 'gadget' ),
					'id'   => 'color',
					'type' => 'color',
				),
				array(
					'name'             => esc_html__( 'Background Image', 'gadget' ),
					'id'               => 'image',
					'type'             => 'image_advanced',
					'class'            => 'image',
					'max_file_uploads' => 1,
				),
				array(
					'name'    => esc_html__( 'Background Horizontal', 'gadget' ),
					'id'      => 'background_horizontal',
					'type'    => 'select',
					'std'     => '',
					'options' => array(
						''       => esc_html__( 'None', 'gadget' ),
						'left'   => esc_html__( 'Left', 'gadget' ),
						'center' => esc_html__( 'Center', 'gadget' ),
						'right'  => esc_html__( 'Right', 'gadget' ),
					),
				),
				array(
					'name'    => esc_html__( 'Background Vertical', 'gadget' ),
					'id'      => 'background_vertical',
					'type'    => 'select',
					'std'     => '',
					'options' => array(
						''       => esc_html__( 'None', 'gadget' ),
						'top'    => esc_html__( 'Top', 'gadget' ),
						'center' => esc_html__( 'Center', 'gadget' ),
						'bottom' => esc_html__( 'Bottom', 'gadget' ),
					),
				),
				array(
					'name'    => esc_html__( 'Background Repeat', 'gadget' ),
					'id'      => 'background_repeat',
					'type'    => 'select',
					'std'     => '',
					'options' => array(
						''          => esc_html__( 'None', 'gadget' ),
						'no-repeat' => esc_html__( 'No Repeat', 'gadget' ),
						'repeat'    => esc_html__( 'Repeat', 'gadget' ),
						'repeat-y'  => esc_html__( 'Repeat Vertical', 'gadget' ),
						'repeat-x'  => esc_html__( 'Repeat Horizontal', 'gadget' ),
					),
				),
				array(
					'name'    => esc_html__( 'Background Attachment', 'gadget' ),
					'id'      => 'background_attachment',
					'type'    => 'select',
					'std'     => '',
					'options' => array(
						''       => esc_html__( 'None', 'gadget' ),
						'scroll' => esc_html__( 'Scroll', 'gadget' ),
						'fixed'  => esc_html__( 'Fixed', 'gadget' ),
					),
				),
				array(
					'name'    => esc_html__( 'Background Size', 'gadget' ),
					'id'      => 'background_size',
					'type'    => 'select',
					'std'     => '',
					'options' => array(
						''        => esc_html__( 'None', 'gadget' ),
						'auto'    => esc_html__( 'Auto', 'gadget' ),
						'cover'   => esc_html__( 'Cover', 'gadget' ),
						'contain' => esc_html__( 'Contain', 'gadget' ),
					),
				),
			),
		);

		//Page Header Settings
		$meta_boxes[] = array(
			'id'       => 'page-header-settings',
			'title'    => esc_html__( 'Page Header Settings', 'gadget' ),
			'pages'    => array( 'page' ),
			'context'  => 'normal',
			'priority' => 'high',
			'fields'   => array(
				array(
					'name'  => esc_html__( 'Hide Page Header', 'gadget' ),
					'id'    => 'hide_page_header',
					'type'  => 'checkbox',
					'std'   => false,
					'class' => 'hide-page-header',
				),
				array(
					'name'  => esc_html__( 'Hide Breadcrumbs', 'gadget' ),
					'id'    => 'hide_breadcrumbs',
					'type'  => 'checkbox',
					'std'   => false,
					'class' => 'hide-breadcrumbs',
				),
				array(
					'name' => esc_html__( 'Custom Layout', 'gadget' ),
					'id'   => 'page_header_custom_layout',
					'type' => 'checkbox',
					'std'  => false,
				),
				array(
					'name'    => esc_html__( 'Text Color', 'gadget' ),
					'id'      => 'text_color',
					'type'    => 'select',
					'std'     => 'dark',
					'options' => array(
						'dark'  => esc_html__( 'Dark', 'gadget' ),
						'light' => esc_html__( 'Light', 'gadget' ),
					),
					'class'   => 'page-header-text-color',
				),
				array(
					'name'             => esc_html__( 'Background Image', 'gadget' ),
					'id'               => 'page_header_bg',
					'type'             => 'image_advanced',
					'max_file_uploads' => 1,
					'std'              => false,
					'class'            => 'page-header-bg',
				),
				array(
					'name'  => esc_html__( 'Enable Parallax', 'gadget' ),
					'id'    => 'parallax',
					'type'  => 'checkbox',
					'std'   => false,
					'class' => 'page-header-parallax',
				),
			),
		);
	}

	// Product Videos
	$meta_boxes[] = array(
		'id'       => 'product-videos',
		'title'    => esc_html__( 'Product Video', 'gadget' ),
		'pages'    => array( 'product' ),
		'context'  => 'side',
		'priority' => 'low',
		'fields'   => array(
			array(
				'name' => esc_html__( 'Video URL', 'gadget' ),
				'id'   => 'video_url',
				'type' => 'oembed',
				'std'  => false,
				'desc' => esc_html__( 'Enter URL of Youtube or Vimeo or specific filetypes such as mp4, m4v, webm, ogv, wmv, flv.', 'gadget' ),
			),
			array(
				'name' => esc_html__( 'Video Width(px)', 'gadget' ),
				'id'   => 'video_width',
				'type' => 'number',
				'desc' => esc_html__( 'Enter the width of video.', 'gadget' ),
				'std'  => 1024
			),
			array(
				'name' => esc_html__( 'Video Height(px)', 'gadget' ),
				'id'   => 'video_height',
				'type' => 'number',
				'desc' => esc_html__( 'Enter the height of video.', 'gadget' ),
				'std'  => 768
			),
		),
	);

	return $meta_boxes;
}

add_filter( 'rwmb_meta_boxes', 'gadgetexpress_register_meta_boxes' );
