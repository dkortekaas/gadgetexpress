<?php
/**
 * Wishlist page template
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Wishlist
 * @version 2.0.12
 */

if ( ! defined( 'YITH_WCWL' ) ) {
	exit;
} // Exit if accessed directly
?>

<?php do_action( 'yith_wcwl_before_wishlist_form', $wishlist_meta ); ?>

    <form id="yith-wcwl-form" action="<?php echo esc_url( $form_action ) ?>" method="post" class="woocommerce">

		<?php wp_nonce_field( 'yith-wcwl-form', 'yith_wcwl_form_nonce' ) ?>

        <!-- TITLE -->
		<?php
		do_action( 'yith_wcwl_before_wishlist_title', $wishlist_meta );

		do_action( 'yith_wcwl_before_wishlist', $wishlist_meta ); ?>

        <!-- WISHLIST TABLE -->
        <table class="shop_table cart wishlist_table" data-pagination="<?php echo esc_attr( $pagination ) ?>"
               data-per-page="<?php echo esc_attr( $per_page ) ?>" data-page="<?php echo esc_attr( $current_page ) ?>"
               data-id="<?php echo esc_attr( $wishlist_id ) ?>" data-token="<?php echo esc_attr( $wishlist_token ) ?>">

			<?php $column_count = 2; ?>

            <thead>
            <tr>
				<?php if ( $show_cb ) : ?>

                    <th class="product-checkbox">
                        <input type="checkbox" value="" name="" id="bulk_add_to_cart"/>
                    </th>

					<?php
					$column_count ++;
				endif;
				?>

				<?php if ( $is_user_owner ): ?>
                    <th class="product-remove"></th>
					<?php
					$column_count ++;
				endif;
				?>

                <th class="product-thumbnail"></th>

                <th class="product-name">
                    <span class="nobr"><?php echo apply_filters( 'yith_wcwl_wishlist_view_name_heading', esc_html__( 'Product', 'gadget' ) ) ?></span>
                </th>

				<?php if ( $show_price ) : ?>

                    <th class="product-price hidden-sm hidden-xs">
                    <span class="nobr">
                        <?php echo apply_filters( 'yith_wcwl_wishlist_view_price_heading', esc_html__( 'Unit Price', 'gadget' ) ) ?>
                    </span>
                    </th>

					<?php
					$column_count ++;
				endif;
				?>

				<?php if ( $show_stock_status ) : ?>

                    <th class="product-stock-status hidden-sm hidden-xs">
                    <span class="nobr">
                        <?php echo apply_filters( 'yith_wcwl_wishlist_view_stock_heading', esc_html__( 'Stock Status', 'gadget' ) ) ?>
                    </span>
                    </th>

					<?php
					$column_count ++;
				endif;
				?>

				<?php if ( $show_last_column ) : ?>

                    <th class="product-add-to-cart hidden-xs"></th>

					<?php
					$column_count ++;
				endif;
				?>
            </tr>
            </thead>

            <tbody>
			<?php
			if ( count( $wishlist_items ) > 0 ) :
				$added_items = array();
				foreach ( $wishlist_items as $item ) :
					global $product;

					$item['prod_id'] = yit_wpml_object_id( $item['prod_id'], 'product', true );

					if ( in_array( $item['prod_id'], $added_items ) ) {
						continue;
					}

					$added_items[] = $item['prod_id'];
					$product       = wc_get_product( $item['prod_id'] );
					$availability  = $product->get_availability();
					$stock_status  = isset( $availability['class'] ) ? $availability['class'] : false;

					if ( $product && $product->exists() ) :
						?>
                        <tr id="yith-wcwl-row-<?php echo esc_attr( $item['prod_id'] ) ?>"
                            data-row-id="<?php echo esc_attr( $item['prod_id'] ) ?>">
							<?php if ( $show_cb ) : ?>
                                <td class="product-checkbox">
                                    <input type="checkbox" value="<?php echo esc_attr( $item['prod_id'] ) ?>"
                                           name="add_to_cart[]" <?php echo ( ! $product->is_type( 'simple' ) ) ? 'disabled="disabled"' : '' ?>/>
                                </td>
							<?php endif ?>

							<?php if ( $is_user_owner ): ?>
                                <td class="product-remove">
                                    <div>
                                        <a href="<?php echo esc_url( add_query_arg( 'remove_from_wishlist', $item['prod_id'] ) ) ?>"
                                           class="remove remove_from_wishlist"
                                           title="<?php esc_html_e( 'Remove this product', 'gadget' ) ?>">&times;</a>
                                    </div>
                                </td>
							<?php endif; ?>

                            <td class="product-thumbnail">
                                <a href="<?php echo esc_url( get_permalink( apply_filters( 'woocommerce_in_cart_product', $item['prod_id'] ) ) ) ?>">
									<?php echo wp_kses_post( $product->get_image() ); ?>
                                </a>
                            </td>

                            <td class="product-name">
                                <a href="<?php echo esc_url( get_permalink( apply_filters( 'woocommerce_in_cart_product', $item['prod_id'] ) ) ) ?>"><?php echo apply_filters( 'woocommerce_in_cartproduct_obj_title', $product->get_title(), $product ) ?></a>
								<?php do_action( 'yith_wcwl_table_after_product_name', $item ); ?>

                                <!-- Price on mobile -->
                                <p class="m-price hidden-lg hidden-md">
									<?php
									$base_product = $product->is_type( 'variable' ) ? $product->get_variation_regular_price( 'max' ) : $product->get_price();
									if ( $base_product ) {
										echo wp_kses_post( $product->get_price_html() );
									} else {
										echo apply_filters( 'yith_free_text', esc_html__( 'Free!', 'gadget' ) );
									}
									?>
                                </p>

                                <!-- Stock status -->
                                <p class="m-stock-status hidden-lg hidden-md">
									<?php
									if ( $stock_status == 'out-of-stock' ) {
										echo '<span class="wishlist-out-of-stock">' . esc_html__( 'Out of Stock', 'gadget' ) . '</span>';
									} else {
										echo '<span class="wishlist-in-stock">' . esc_html__( 'In Stock', 'gadget' ) . '</span>';
									} ?>
                                </p>
                            </td>

							<?php if ( $show_price ) : ?>
                                <td class="product-price hidden-sm hidden-xs">
									<?php
									$base_product = $product->is_type( 'variable' ) ? $product->get_variation_regular_price( 'max' ) : $product->get_price();
									if ( $base_product ) {
										echo wp_kses_post( $product->get_price_html() );
									} else {
										echo apply_filters( 'yith_free_text', esc_html__( 'Free!', 'gadget' ) );
									}
									?>
                                </td>
							<?php endif ?>

							<?php if ( $show_stock_status ) : ?>
                                <td class="product-stock-status hidden-sm hidden-xs">
									<?php if ( $stock_status == 'out-of-stock' ) {
										echo '<span class="wishlist-out-of-stock">' . esc_html__( 'Out of Stock', 'gadget' ) . '</span>';
									} else {
										echo '<span class="wishlist-in-stock">' . esc_html__( 'In Stock', 'gadget' ) . '</span>';
									} ?>
                                </td>
							<?php endif ?>

							<?php if ( $show_last_column ): ?>
                                <td class="product-add-to-cart hidden-xs">
                                    <!-- Date added -->
									<?php
									if ( $show_dateadded && isset( $item['dateadded'] ) ):
										echo '<span class="dateadded">' . sprintf( esc_html__( 'Added on : %s', 'gadget' ), date_i18n( get_option( 'date_format' ), strtotime( $item['dateadded'] ) ) ) . '</span>';
									endif;
									?>

                                    <!-- Add to cart button -->
									<?php if ( $show_add_to_cart && isset( $stock_status ) && $stock_status != 'out-of-stock' ): ?>
										<?php woocommerce_template_loop_add_to_cart(); ?>
									<?php endif ?>

                                    <!-- Change wishlist -->
									<?php if ( $available_multi_wishlist && is_user_logged_in() && count( $users_wishlists ) > 1 && $move_to_another_wishlist && $is_user_owner ): ?>
                                        <select class="change-wishlist selectBox">
                                            <option value=""><?php esc_html_e( 'Move', 'gadget' ) ?></option>
											<?php
											foreach ( $users_wishlists as $wl ):
												if ( $wl['wishlist_token'] == $wishlist_meta['wishlist_token'] ) {
													continue;
												}

												?>
                                                <option value="<?php echo esc_attr( $wl['wishlist_token'] ) ?>">
													<?php
													$wl_title = ! empty( $wl['wishlist_name'] ) ? esc_html( $wl['wishlist_name'] ) : esc_html( $default_wishlsit_title );
													if ( $wl['wishlist_privacy'] == 1 ) {
														$wl_privacy = esc_html__( 'Shared', 'gadget' );
													} elseif ( $wl['wishlist_privacy'] == 2 ) {
														$wl_privacy = esc_html__( 'Private', 'gadget' );
													} else {
														$wl_privacy = esc_html__( 'Public', 'gadget' );
													}

													echo sprintf( '%s - %s', $wl_title, $wl_privacy );
													?>
                                                </option>
											<?php
											endforeach;
											?>
                                        </select>
									<?php endif; ?>

                                    <!-- Remove from wishlist -->
									<?php if ( $is_user_owner && $repeat_remove_button ): ?>
                                        <a href="<?php echo esc_url( add_query_arg( 'remove_from_wishlist', $item['prod_id'] ) ) ?>"
                                           class="remove_from_wishlist button"
                                           title="<?php esc_html_e( 'Remove this product', 'gadget' ) ?>"><?php esc_html_e( 'Remove', 'gadget' ) ?></a>
									<?php endif; ?>
                                </td>
							<?php endif; ?>
                        </tr>
					<?php
					endif;
				endforeach;
			else: ?>
                <tr>
                    <td colspan="<?php echo esc_attr( $column_count ) ?>"
                        class="wishlist-empty"><?php echo apply_filters( 'yith_wcwl_no_product_to_remove_message', esc_html__( 'No products were added to the wishlist', 'gadget' ) ) ?></td>
                </tr>
			<?php
			endif;

			if ( ! empty( $page_links ) ) : ?>
                <tr class="pagination-row">
                    <td colspan="<?php echo esc_attr( $column_count ) ?>"><?php echo wp_kses_post( $page_links ); ?></td>
                </tr>
			<?php endif ?>
            </tbody>

            <tfoot>
            <tr>
                <td colspan="<?php echo esc_attr( $column_count ) ?>">
					<?php if ( $show_cb ) : ?>
                        <div class="custom-add-to-cart-button-cotaniner">
                            <a href="<?php echo esc_url( add_query_arg( array(
								'wishlist_products_to_add_to_cart' => '',
								'wishlist_token'                   => $wishlist_token
							) ) ) ?>" class="button alt"
                               id="custom_add_to_cart"><?php echo apply_filters( 'yith_wcwl_custom_add_to_cart_text', esc_html__( 'Add the selected products to the cart', 'gadget' ) ) ?></a>
                        </div>
					<?php endif; ?>
	                <?php if ( $is_user_owner && $show_ask_estimate_button && $count > 0 ): ?>
                        <div class="ask-an-estimate-button-container">

                            <a href="<?php echo ( $additional_info || ! is_user_logged_in() ) ? '#ask_an_estimate_popup' : esc_url( $ask_estimate_url ) ?>"
                               class="btn button ask-an-estimate-button" <?php echo ( $additional_info ) ? 'data-rel="prettyPhoto[ask_an_estimate]"' : '' ?> >
				                <?php echo apply_filters( 'yith_wcwl_ask_an_estimate_icon', '<i class="fa fa-shopping-cart"></i>' ) ?>
				                <?php echo apply_filters( 'yith_wcwl_ask_an_estimate_text', esc_html__( 'Ask for an estimate', 'gadget' ) ) ?>
                            </a>
                        </div>
	                <?php endif; ?>


					<?php
					// do_action( 'yith_wcwl_before_wishlist_share', $wishlist_meta );

					// if ( is_user_logged_in() && $is_user_owner && ! $is_private && $share_enabled ) {
					// 	//yith_wcwl_get_template( 'share.php', $share_atts );
					// }

					// do_action( 'yith_wcwl_after_wishlist_share', $wishlist_meta );
					?>
                </td>
            </tr>
            </tfoot>

        </table>

		<?php wp_nonce_field( 'yith_wcwl_edit_wishlist_action', 'yith_wcwl_edit_wishlist' ); ?>

		<?php if ( ! $is_default ): ?>
            <input type="hidden" value="<?php echo esc_attr( $wishlist_token ) ?>" name="wishlist_id" id="wishlist_id">
		<?php endif; ?>

		<?php do_action( 'yith_wcwl_after_wishlist', $wishlist_meta ); ?>

    </form>

<?php do_action( 'yith_wcwl_after_wishlist_form', $wishlist_meta ); ?>

<?php if ( $show_ask_estimate_button && ( ! is_user_logged_in() || $additional_info ) ): ?>
    <div id="ask_an_estimate_popup">
        <form action="<?php echo esc_url( $ask_estimate_url ); ?>" method="post" class="wishlist-ask-an-estimate-popup">
			<?php if ( ! is_user_logged_in() ): ?>
                <label for="reply_email"><?php echo apply_filters( 'yith_wcwl_ask_estimate_reply_mail_label', esc_html__( 'Your email', 'gadget' ) ) ?></label>
                <input type="email" value="" name="reply_email" id="reply_email">
			<?php endif; ?>
			<?php if ( ! empty( $additional_info_label ) ): ?>
                <label for="additional_notes"><?php echo esc_html( $additional_info_label ) ?></label>
			<?php endif; ?>
            <textarea id="additional_notes" name="additional_notes"></textarea>

            <button class="btn button ask-an-estimate-button ask-an-estimate-button-popup">
				<?php echo apply_filters( 'yith_wcwl_ask_an_estimate_icon', '<i class="fa fa-shopping-cart"></i>' ) ?>
				<?php echo apply_filters( 'yith_wcwl_ask_an_estimate_text', esc_html__( 'Ask for an estimate', 'gadget' ) ) ?>
            </button>
        </form>
    </div>
<?php endif; ?>