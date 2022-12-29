<?php
/**
 * Custom login setup.
 *
 * @package Toffedassen
 */

if ( ! defined( 'ABSPATH' ) ) :
	exit;
endif;

/**
 * Sets the custom login style.
 */

function gadgetexpress_custom_login_style() {
	echo '<link rel="stylesheet" type="text/css" href="' . get_bloginfo('stylesheet_directory') . '/assets/css/login.css" />';
}

add_action( 'login_head', 'gadgetexpress_custom_login_style' );


/**
 * Sets the custom login Url.
 */	

function gadgetexpress_custom_login_url() {
	return 'https://weblogiq.nl';
}

add_filter( 'login_headerurl', 'gadgetexpress_custom_login_url' );


/**
 * Sets the custom login Url title.
 */	

function gadgetexpress_custom_login_url_title() {
	return esc_html__( 'Developed by internetbureau Weblogiq', 'gadget' );
}

add_filter( 'login_headertext', 'gadgetexpress_custom_login_url_title' );