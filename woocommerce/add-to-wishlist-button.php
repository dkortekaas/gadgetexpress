<?php
/**
 * Add to wishlist button template
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Wishlist
 * @version 2.0.8
 */

if ( ! defined( 'YITH_WCWL' ) ) {
    exit;
} // Exit if accessed directly

global $product;
?>

<a
    href="<?php echo esc_url( add_query_arg( 'add_to_wishlist', $product_id ) )?>"
    data-product-id="<?php echo esc_attr( $product_id ) ?>" data-product-type="<?php echo esc_attr( $product_type )?>"
    class="<?php echo esc_attr( $link_classes ) ?>"
    data-original-title="<?php esc_attr_e( 'Save', 'gadget' ) ?>"
    data-rel="tooltip"
    >
    <?php echo '' . $icon; ?>
    <span class="indent-text"><?php echo '' . $label; ?></span>
</a>
<span class="ajax-loading" style="visibility:hidden">
	<span class="fa-spin loading-icon"></span>
</span>