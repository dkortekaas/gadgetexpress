<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Gadget Express
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="p:domain_verify" content="075c8b89d50282792e355acbaf91faff"/>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/assets/favicons/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_template_directory_uri(); ?>/assets/favicons/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_template_directory_uri(); ?>/assets/favicons/favicon-16x16.png">
	<link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/assets/favicons/site.webmanifest">
	<link rel="mask-icon" href="<?php echo get_template_directory_uri(); ?>/assets/favicons/safari-pinned-tab.svg" color="#5bbad5">
	<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/assets/favicons/favicon.ico">
	<meta name="msapplication-TileColor" content="#5d8aa5">
	<meta name="msapplication-config" content="<?php echo get_template_directory_uri(); ?>/assets/favicons/browserconfig.xml">
	<meta name="theme-color" content="#ffffff">

	<?php wp_head(); ?>
</head>

<body <?php body_class( 'sidebar-content filter-mobile-enable' ); ?> itemscope itemtype="http://schema.org/WebPage">
<div id="page" class="hfeed site">

	<?php do_action( 'gadgetexpress_before_header' ); ?>

	<header id="masthead" class="site-header">
		<?php do_action( 'gadgetexpress_header' ); ?>
	</header><!-- #masthead -->

	<?php
	/*
	 *  gadgetexpress_show_page_header - 20
	 */
	do_action( 'gadgetexpress_after_header' );
	?>

	<div id="content" class="site-content">
		<?php
		/*
		 *	gadgetexpress_single_post_format - 10
		 *  gadgetexpress_site_content_open - 20
		 *
		 */
		do_action( 'gadgetexpress_site_content_open' );
		?>
