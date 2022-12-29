<?php
/**
 * Template part for displaying front page content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Toffedassen
 */

get_header();

	get_template_part( 'parts/home/content', 'hero' );
?>
	<div class="container">
		<div class="row">
		<?php
		get_template_part( 'parts/home/content', 'cta' );

		get_template_part( 'parts/home/content', 'lead' );

		get_template_part( 'parts/home/products', 'home' );
		?>
		</div>
	</div>
<?php
get_footer();
