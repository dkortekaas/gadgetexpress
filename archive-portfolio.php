<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Gadget Express
 */

get_header();

$p_style = gadgetexpress_get_option( 'portfolio_layout' );
$nav_type = gadgetexpress_get_option( 'portfolio_type_nav' );
$css = 'list-portfolio';

if ( $p_style == 'grid' ) {
	$css .= ' row';
} elseif ( 'carousel' == $p_style ) {
	$css .= ' swiper-wrapper';
}

?>

<div id="primary" class="content-area <?php gadgetexpress_content_columns() ?>">
	<main id="main" class="site-main">

		<?php
		/* gadgetexpress_portfolio_categories
		 *
		 */
		do_action( 'gadgetexpress_before_portfolio_list' );
		?>

		<?php if ( 'carousel' == $p_style ) : ?>
			<div class="swiper-container">
		<?php endif; ?>

		<?php if ( have_posts() ) : ?>

			<?php /* Start the Loop */ ?>

			<div class="<?php echo esc_attr( $css ) ?>">

				<?php while ( have_posts() ) : the_post(); ?>

					<?php
					/* Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'parts/content', 'portfolio' );
					?>

				<?php endwhile; ?>

			</div>

			<?php
			/* gadgetexpress_footer_portfolio_carousel
			 *
			 */
			do_action( 'gadgetexpress_after_portfolio_content' );
			?>

			<?php gadgetexpress_numeric_pagination(); ?>

		<?php else : ?>

			<?php get_template_part( 'parts/content', 'none' ); ?>

		<?php endif; ?>

		<?php if ( 'carousel' == $p_style ) : ?>
			</div>
		<?php endif; ?>

	</main><!-- #main -->
</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
