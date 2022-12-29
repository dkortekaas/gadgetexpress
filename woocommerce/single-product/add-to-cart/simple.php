<?php
/**
 * Simple product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product->is_purchasable() ) {
	return;
}

if ( $product->is_in_stock() ) : ?>

	<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

	<form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
		<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

		<?php
		do_action( 'woocommerce_before_add_to_cart_quantity' );

		woocommerce_quantity_input( array(
			'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
			'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
			'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
		) );

		do_action( 'woocommerce_after_add_to_cart_quantity' );
		?>

		<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button button alt"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>

		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
	</form>

	<ul class="checked-list">
	<?php if( $product->is_in_stock() ) : ?>
		<li><?php _e('In Stock', 'gadget'); ?></li>
	<?php else : ?>
		<li><?php _e('Soon in stock', 'gadget'); ?></li>
	<?php endif; ?>
		<li><?php _e('FREE shipping within the Netherlands', 'gadget'); ?></li>
		<li><?php _e('100% satisfaction guarantee (return within 14 days)', 'gadget'); ?></li>
	</ul>

	<div class="share-links">
		<span class="share-links__title"><?php _e('SHARE', 'gadget'); ?>:</span>
			<a class="share-links__link" href="http://www.facebook.com/sharer.php?u=<?php echo $product->get_permalink(); ?>&amp;p=<?php echo $product->get_title(); ?>" target="_blank">
				<i class="ion-social-facebook"></i>
			</a>
			<a class="share-links__link" href="http://twitter.com/share?text=<?php _e('View this', 'gadget'); ?>%20<?php echo $product->get_title(); ?>%20<?php _e('at', 'gadget'); ?>%20&amp;url=<?php echo $product->get_permalink(); ?>" target="_blank">
				<i class="ion-social-twitter"></i>
			</a>
			<a class="share-links__link" href="http://pinterest.com/pin/create/button/?url=<?php echo $product->get_permalink(); ?>&amp;media=<?php echo get_the_post_thumbnail_url($product->get_id()); ?>&amp;description=<?php echo $product->get_title(); ?>" target="_blank">
				<i class="ion-social-pinterest"></i>
			</a>
			<a class="share-links__link" href="whatsapp://send?text=<?php _e('View this', 'gadget'); ?>%20<?php echo $product->get_title(); ?>%20<?php _e('at', 'gadget'); ?>%20<?php echo $product->get_permalink(); ?>" data-action="share/whatsapp/share" target="_blank">
				<i class="ion-social-whatsapp"></i>
			</a>
		</div>
	</div>

	<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

<?php endif; ?>
