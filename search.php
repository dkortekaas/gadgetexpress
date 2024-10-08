<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package Gadget Express
 */

get_header(); ?>

	<section id="primary" class="content-area <?php gadgetexpress_content_columns(); ?>">
		<main id="main" class="site-main">

		<?php if ( have_posts() ) : ?>

			<!-- <header class="page-header">
				<h1 class="page-title"><?php printf( esc_html__( 'Search Results for: %s', 'gadget' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
			</header> -->

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php
				/**
				 * Run the loop for the search to output the results.
				 * If you want to overload this in a child theme then include a file
				 * called content-search.php and that will be used instead.
				 */
				get_template_part( 'parts/content', 'search' );
				?>

			<?php endwhile; ?>

			<?php gadgetexpress_numeric_pagination(); ?>

		<?php else : ?>

			<?php get_template_part( 'parts/content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
