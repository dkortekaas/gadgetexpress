<?php
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * @package Toffedassen
 */

if ( ! defined( 'ABSPATH' ) ) :
	exit;
endif;

/**
 * Removing the edit_themes, edit_plugins, and edit_files capabilities and
 * remove the functionality for clients to update themes or install plugins for all users except Admin
 */
if ( !is_admin() ) :

	define( 'DISALLOW_FILE_EDIT', true );

	define( 'DISALLOW_FILE_MODS', true );

endif;

if ( ! function_exists( 'gadgetexpress_start' ) ) :

	function gadgetexpress_start() {

		// remove WP versionnr.
		add_filter( 'the_generator', 'gadgetexpress_no_generator' );

		// launching operation cleanup
		add_action( 'init', 'gadgetexpress_head_cleanup' );

		// Show less info to users on failed login for security
		add_filter( 'login_errors', 'gadgetexpress_show_less_login_info' );

		// Remove WP Version From styles and scripts
		add_filter( 'style_loader_src', 'gadgetexpress_remove_ver_css_js', 9999 );
		add_filter( 'script_loader_src', 'gadgetexpress_remove_ver_css_js', 9999 );

		// Disable emoticons
		add_action( 'init', 'gadgetexpress_disable_wp_emojicons' );

	}

	add_action( 'after_setup_theme', 'gadgetexpress_start', 16 );

endif;

/**
 * Removes the generator tag with WP version numbers.
 * Hackers will use this to find weak and old WP installs.
 *
 * @return string
 */
if ( ! function_exists( 'gadgetexpress_no_generator' ) ) :

	function gadgetexpress_no_generator() {

		return '';

	}

endif;

/*
 * Clean up wp_head() from unused or unsecure stuff
 */
if ( ! function_exists( 'gadgetexpress_head_cleanup' ) ) :

	function gadgetexpress_head_cleanup() {

		// Remove WP version
		remove_action( 'wp_head', 'wp_generator' );

		// Remove EditURI link
		remove_action( 'wp_head', 'rsd_link' );

		// Remove Windows live writer
		remove_action( 'wp_head', 'wlwmanifest_link' );

		// Remove index link
		remove_action( 'wp_head', 'index_rel_link' );

		// Remove post and comment feeds
		remove_action( 'wp_head', 'feed_links', 2 );

		// Remove category feeds
		remove_action( 'wp_head', 'feed_links_extra', 3 );

		// Remove links for adjacent posts
		remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );

		// Remove shortlink
		remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );

		// Remove injected CSS for recent comments widget
		if ( has_filter( 'wp_head', 'wp_widget_recent_comments_style' ) ) :
			remove_filter( 'wp_head', 'wp_widget_recent_comments_style' );
		endif;

	}

endif;

/**
 * Remove WP Version From styles and scripts.
 *
 * @return string
 */
if ( ! function_exists( 'gadgetexpress_remove_ver_css_js' ) ) :

	function gadgetexpress_remove_ver_css_js( $src ) {

		if ( strpos( $src, 'ver=' ) ) :
			$src = remove_query_arg( 'ver', $src );
		endif;

		return $src;

	}

endif;

/**
 * Disable emojicons introduced with WP 4.2
 *
 * @return string
 */
if ( ! function_exists( 'gadgetexpress_disable_wp_emojicons' ) ) :

	function gadgetexpress_disable_wp_emojicons() {

		remove_action( 'admin_print_styles', 'print_emoji_styles' );

		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );

		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );

		remove_action( 'wp_print_styles', 'print_emoji_styles' );

		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );

		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );

		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

		add_filter( 'tiny_mce_plugins', 'disable_emoji_tinymce' );

	}

endif;

/**
 * Remove dashicons in frontend for unauthenticated users.
 */
if ( ! function_exists( 'gadgetexpress_dequeue_dashicons' ) ) :

	function gadgetexpress_dequeue_dashicons() {

		if ( ! is_user_logged_in() ) :
			wp_deregister_style( 'dashicons' );
		endif;

	}

	add_action( 'wp_enqueue_scripts', 'gadgetexpress_dequeue_dashicons' );

endif;

/**
 * Remove injected CSS from recent comments widget
 */
if ( ! function_exists( 'gadgetexpress_remove_recent_comments_style' ) ) :

	function gadgetexpress_remove_recent_comments_style() {

		global $wp_widget_factory;

		if ( isset( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'] ) ) :

			remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );

		endif;

	}

endif;

/**
 * Filter to remove TinyMCE emojis
 */
if ( ! function_exists( 'disable_emoji_tinymce' ) ) :

	function disable_emoji_tinymce( $plugins ) {

		if ( is_array( $plugins ) ) :

			return array_diff( $plugins, array( 'wpemoji' ) );

		else :

			return array();

		endif;

	}

endif;
