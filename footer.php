<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Gadget Express
 */

?>
	<?php

	/*
	 *  gadgetexpress_site_content_close - 100
	 */
	do_action( 'gadgetexpress_site_content_close' );
	?>
</div><!-- #content -->

	<?php
	/*
	 * gadgetexpress_footer_newsletter - 10
	 * gadgetexpress_footer_newsletter_fix -20
	 */
	do_action( 'gadgetexpress_before_footer' );
	?>

	<footer id="colophon" class="site-footer">
		<?php do_action( 'gadgetexpress_footer' ) ?>
	</footer><!-- #colophon -->

	<div class="whatsapp-chat">
		<a class="icon" href="https://api.whatsapp.com/send?phone=31682826039" rel="nofollow" title="<?php _e( 'Hello, how may we help you? Just send us a message now to get assistance.', 'gadget' ) ?>" data-rel="tooltip" data-placement="left">
			<i class="fa-brands fa-whatsapp"></i>
		</div>
	</div>

	<?php do_action( 'gadgetexpress_after_footer' ) ?>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
