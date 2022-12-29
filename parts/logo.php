<?php
/**
 * Hooks for template logo
 *
 * @package Solo
 */

$logo  = gadgetexpress_get_option( 'logo' );

if ( ! $logo ) {
	$logo = get_template_directory_uri() . '/assets/images/logo.png';
}

if ( is_page_template( 'template-coming-soon-page.php' ) ) {
	$logo = gadgetexpress_get_option( 'coming_soon_logo' );

	if ( ! $logo ) {
		$logo = get_template_directory_uri() . '/assets/images/logo.png';
	}
}

?>
	<a href="<?php echo esc_url( home_url() ) ?>" class="logo">
		<img src="<?php echo $logo; ?>" alt="<?php echo get_bloginfo( 'name' ) ?>" />
	</a>
<?php

printf(
	'<%1$s class="site-title" itemprop="name"><a href="%2$s" rel="home" itemprop="url">%3$s</a></%1$s>',
	is_home() || is_front_page() ? 'h1' : 'p',
	esc_url( home_url( '' ) ),
	get_bloginfo( 'name' )
);
?>
<h2 class="site-description" itemprop="description"><?php bloginfo( 'description' ); ?></h2>
