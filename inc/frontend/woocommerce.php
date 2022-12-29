<?php

/**
 * Class for all WooCommerce template modification
 *
 * @version 1.0
 */
class Toffe_Dassen_WooCommerce {
	/**
	 * @var string Layout of current page
	 */
	public $layout;

	/**
	 * @var string shop view
	 */
	public $new_duration;

	/**
	 * @var string shop view
	 */
	public $shop_view;

	/**
	 * Construction function
	 *
	 * @since  1.0
	 * @return Toffe_Dassen_WooCommerce
	 */
	function __construct() {
		add_action( 'wp_ajax_gadgetexpress_search_products', array( $this, 'instance_search_result' ) );
		add_action( 'wp_ajax_nopriv_gadgetexpress_search_products', array( $this, 'instance_search_result' ) );

		// Check if Woocomerce plugin is actived
		if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			return;
		}

		// Define all hook
		add_action( 'template_redirect', array( $this, 'hooks' ) );

		// Need an early hook to ajaxify update mini shop cart
		add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'add_to_cart_fragments' ) );

		// Remove catalog ordering
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

		// Remove shop result count
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );

		// Wrap product loop content
		add_action( 'woocommerce_before_shop_loop_item', array( $this, 'open_product_inner' ), 1 );
		add_action( 'woocommerce_after_shop_loop_item', array( $this, 'close_product_inner' ), 50 );

		// Wrap product categories thumb
		add_action( 'woocommerce_before_subcategory', array( $this, 'open_product_categories_thumb' ), 20 );
		add_action( 'woocommerce_before_subcategory_title', array( $this, 'close_product_categories_thumb' ), 100 );

		// Remove product link
		remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );

		// Remove badges
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash' );

		// Add product thumbnail
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail' );
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'product_content_thumbnail' ) );

		// Add product title link
		remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
		add_action( 'woocommerce_shop_loop_item_title', array( $this, 'products_title' ), 10 );

		add_action( 'woocommerce_shop_loop_item_title', array( $this, 'open_product_details' ), 5 );
		add_action( 'woocommerce_after_shop_loop_item', array( $this, 'close_product_details' ), 100 );

		add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_single_excerpt', 30 );
		add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'shop_view_list_button' ), 40 );

		// remove add to cart link
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );

		add_filter( 'posts_search', array( $this, 'product_search_sku' ), 9 );

		// add product attribute
		add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'product_attribute' ), 15 );
	}

	/**
	 * Hooks to WooCommerce actions, filters
	 *
	 * @since  1.0
	 * @return void
	 */
	function hooks() {
		$this->layout       = gadgetexpress_get_layout();
		$this->new_duration = gadgetexpress_get_option( 'product_newness' );
		$this->shop_view    = isset( $_COOKIE['shop_view'] ) ? $_COOKIE['shop_view'] : gadgetexpress_get_option( 'shop_view' );

		// Socials Log
		if ( function_exists( 'wsl_render_auth_widget_in_wp_login_form' ) ) {
			add_action( 'woocommerce_login_form_end', 'wsl_render_auth_widget_in_wp_login_form' );
		}

		// WooCommerce Styles
		add_filter( 'woocommerce_enqueue_styles', array( $this, 'wc_styles' ) );

		// Remove breadcrumb, use theme's instead
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

		// Remove badges
		remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash' );

		// Remove shop page title
		add_filter( 'woocommerce_show_page_title', '__return_false' );

		// Add Bootstrap classes
		add_filter( 'post_class', array( $this, 'product_class' ), 30, 3 );

		add_filter( 'product_cat_class', array( $this, 'product_cat_class' ), 30, 3 );


		// Remove catalog ordering
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

		// Remove shop result count
		remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );

		// Add Shop Toolbar
		add_action( 'gadgetexpress_page_header_shop_toolbar', array( $this, 'shop_toolbar' ), 20 );

		// Add Shop Topbar
		//add_action( 'woocommerce_archive_description', array( $this, 'shop_topbar' ), 25 );
		add_action( 'woocommerce_before_shop_loop', array( $this, 'shop_topbar' ), 30 );


		// Adds breadcrumb and product navigation on top of product
		if ( function_exists('is_product')  && is_product() ) {
			add_action( 'woocommerce_before_main_content', array( $this, 'product_toolbar' ), 5 );
		}

		// Remove Product Meta in Product Summary
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
		add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 4 );

		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
		add_action( 'woocommerce_single_product_summary', array( $this, 'product_meta' ), 45 );

		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
		add_action( 'woocommerce_single_product_summary', array( $this, 'product_header_summary' ), 10 );

		add_action( 'woocommerce_single_product_summary', array( $this, 'product_sku' ), 7 );

		// Single Product Button
		add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'yith_button' ), 50 );
		add_action( 'woocommerce_before_add_to_cart_button', array( $this, 'open_button_group' ), 10 );
		add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'close_button_group' ), 1000 );
		remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation', 10 );
		add_action( 'woocommerce_before_add_to_cart_button', 'woocommerce_single_variation', 5 );

		// Remove default tab title
		add_filter( 'woocommerce_product_reviews_tab_title', array( $this, 'gadgetexpress_product_review_tab_title' ) );
		add_filter(
			'woocommerce_product_additional_information_tab_title', array(
				$this,
				'gadgetexpress_product_additional_information_tab_title'
			)
		);

		// Add share buttons
		//add_action( 'woocommerce_after_add_to_cart_form', array( $this, 'gadgetexpress_share' ), 15 );

		// remove description heading
		add_filter( 'woocommerce_product_description_heading', '__return_false' );

		if ( ! intval( gadgetexpress_get_option( 'product_related' ) ) ) {
			remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
		}

		// remove products upsell display
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
		// Add product upsell
		if ( intval( gadgetexpress_get_option( 'product_upsells' ) ) ) {
			add_action( 'woocommerce_after_single_product_summary', array( $this, 'upsell_products' ), 15 );
		}

		// Change possition cross sell
		remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
		add_action( 'woocommerce_after_cart', 'woocommerce_cross_sell_display' );

		// Change columns and total of cross sell
		add_filter( 'woocommerce_cross_sells_columns', array( $this, 'cross_sells_columns' ) );
		add_filter( 'woocommerce_cross_sells_total', array( $this, 'cross_sells_numbers' ) );

		// Change number of related products
		add_filter( 'woocommerce_output_related_products_args', array( $this, 'related_products' ) );

		// Change variable text
		add_filter(
			'woocommerce_dropdown_variation_attribute_options_args', array(
				$this,
				'variation_attribute_options'
			)
		);

		// Orders account
		add_action( 'woocommerce_account_dashboard', 'woocommerce_account_orders', 5 );
		// Add orders title
		add_action( 'woocommerce_before_account_orders', array( $this, 'orders_title' ), 10, 1 );

		// billing address
		add_action( 'woocommerce_account_dashboard', 'woocommerce_account_edit_address', 15 );

		remove_action( 'woocommerce_before_single_product', 'wc_print_notices', 10 );
		add_action( 'gadgetexpress_before_single_product', 'wc_print_notices', 10 );

		// Remove Social Share
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
		add_action( 'woocommerce_single_product_summary', array( $this, 'single_product_socials' ), 50 );

		// Add div before shop loop
		add_action( 'woocommerce_before_shop_loop', array( $this, 'before_shop_loop' ), 40 );

		// Add div after shop loop
		add_action( 'woocommerce_after_shop_loop', array( $this, 'after_shop_loop' ), 20 );

		// Add loading icon ajax
		add_action( 'woocommerce_before_shop_loop', array( $this, 'shop_loading' ), 60 );

		// Change label orderby option default
		add_filter( 'woocommerce_catalog_orderby', array( $this, 'gadgetexpress_catalog_orderby_options' ) );
		add_filter( 'woocommerce_default_catalog_orderby', array( $this, 'catalog_orderby_default' ) );

		// Catalog Page Header
		add_action( 'woocommerce_before_main_content', array( $this, 'catalog_page_header' ), 50 );

		// Wrap product summary for sticky description on product style 1
		if ( '3' == gadgetexpress_get_option( 'single_product_layout' ) ) {
			add_action( 'woocommerce_single_product_summary', array( $this, 'open_sticky_description' ), 0 );
			add_action( 'woocommerce_single_product_summary', array( $this, 'close_sticky_description' ), 1000 );
		}

		if ( in_array( gadgetexpress_get_option( 'single_product_layout' ), array( '3', '4', '5', '6' ) ) ) {
			add_filter( 'woocommerce_single_product_flexslider_enabled', '__return_false' );
		}

		if ( in_array( gadgetexpress_get_option( 'single_product_layout' ), array( '3', '4', '5', '6' ) ) ) {
			add_filter(
				'woocommerce_gallery_image_size', array(
					$this,
					'gallery_image_size'
				)
			);
		}

		if ( in_array( gadgetexpress_get_option( 'single_product_layout' ), array( '1', '2' ) ) ) {
			add_filter( 'woocommerce_get_image_size_gallery_thumbnail', array( $this, 'gallery_thumb_size' ) );
		}

		add_filter( 'woocommerce_single_product_zoom_enabled', array( $this, 'single_product_zoom_enabled' ) );

		// Display user name in Address Details
		add_action( 'woocommerce_before_edit_account_form', array( $this, 'display_user_name' ) );

		// Checkout form wrapper
		add_action( 'woocommerce_before_checkout_form', array( $this, 'open_checkout_form_wrapper' ), 5 );
		add_action( 'woocommerce_before_checkout_form', array( $this, 'close_checkout_form_wrapper' ), 150 );

		add_action( 'gadgetexpress_home_products', array( $this, 'gadgetexpress_product' ), 10 );

		// Add additonal information below product info
		function woocommerce_template_product_additionalinformation() {
			wc_get_template( 'single-product/tabs/additional-information.php' );
		}
		add_action( 'woocommerce_single_product_summary', 'woocommerce_template_product_additionalinformation', 20 );

		// Remove the additional information tab
		function woocommerce_remove_product_tabs( $tabs ) {
    		unset( $tabs['additional_information'] );
    		return $tabs;
		}
		add_filter( 'woocommerce_product_tabs', 'woocommerce_remove_product_tabs', 98 );
	
	}

	/**
	 * Ajaxify update cart viewer
	 *
	 * @since 1.0
	 *
	 * @param array $fragments
	 *
	 * @return array
	 */
	function add_to_cart_fragments( $fragments ) {
		global $woocommerce;

		if ( empty( $woocommerce ) ) {
			return $fragments;
		}

		ob_start();
		$icon_cart = '<i class="t-icon icon-cart"></i>';
		$icon_cart = apply_filters( 'gadgetexpress_icon_cart', $icon_cart );

		$cart_html = esc_html( get_post_meta( get_the_ID(), 'header_cart_text', true ) );

		$cart_html = $cart_html ? $cart_html : esc_html__( 'Shopping Cart', 'gadget' );

		?>

        <a href="<?php echo esc_url( wc_get_cart_url() ) ?>" class="cart-contents" id="icon-cart-contents">
			<?php echo wp_kses_post( $icon_cart ); ?>
			<span class="label-item cart-label"><?php echo wp_kses( $cart_html, wp_kses_allowed_html( 'post' ) ); ?></span>
            <span class="mini-cart-counter"><?php echo intval( $woocommerce->cart->cart_contents_count ); ?></span>
        </a>

		<?php
		$fragments['a.cart-contents'] = ob_get_clean();

		return $fragments;
	}


	/**
	 * Remove default woocommerce styles
	 *
	 * @since  1.0
	 *
	 * @param  array $styles
	 *
	 * @return array
	 */
	function wc_styles( $styles ) {
		unset( $styles['woocommerce-layout'] );
		unset( $styles['woocommerce-smallscreen'] );

		return $styles;
	}

	/**
	 * Add product title link
	 *
	 * @since  1.0
	 *
	 * @return array
	 */
	function products_title() {
		printf( '<h3><a href="%s">%s</a></h3>', esc_url( get_the_permalink() ), get_the_title() );
	}

	/**
	 * Display orders tile
	 *
	 * @since 1.0
	 */
	function orders_title( $has_orders ) {
		if ( $has_orders ) {
			printf( '<h2 class="orders-title">%s</h2>', esc_html__( 'Orders History', 'gadget' ) );
		}
	}

	/**
	 * Open product detail
	 *
	 * @since  1.0
	 *
	 *
	 * @return array
	 */
	function open_product_details() {
		echo '<div class="un-product-details">';
	}

	/**
	 * Close product detail
	 *
	 * @since  1.0
	 *
	 *
	 * @return array
	 */
	function close_product_details() {
		echo '</div>';
	}

	/**
	 * Open product detail
	 *
	 * @since  1.0
	 *
	 *
	 * @return array
	 */
	function open_checkout_form_wrapper() {
		echo '<div class="gadgetexpress-checkout-form-wrapper clearfix">';
	}

	/**
	 * Close product detail
	 *
	 * @since  1.0
	 *
	 *
	 * @return array
	 */
	function close_checkout_form_wrapper() {
		echo '</div>';
	}

	/**
	 * WooCommerce Loop Product Content Thumbs
	 *
	 * @since  1.0
	 *
	 * @return string
	 */
	function product_content_thumbnail() {
		global $product;
		$attachment_ids  = $product->get_gallery_image_ids();
		$secondary_thumb = intval( gadgetexpress_get_option( 'disable_secondary_thumb' ) );
		$shop_view       = gadgetexpress_get_option( 'shop_view' );

		printf( '<div class="un-product-thumbnail">' );

		printf( '<a href ="%s" class="">', esc_url( get_the_permalink() ) );

		if ( function_exists( 'woocommerce_get_product_thumbnail' ) ) {
			echo woocommerce_get_product_thumbnail();
		}

		if ( ! $secondary_thumb && $attachment_ids ) {
			echo wp_get_attachment_image( $attachment_ids[0], 'shop_catalog', '', array( 'class' => 'image-hover' ) );
		}

		$this->product_ribbons();

		echo '</a>';

		echo '<div class="footer-button">';

		if ( function_exists( 'woocommerce_template_loop_add_to_cart' ) ) {
			woocommerce_template_loop_add_to_cart();
		}

		echo '<div class="actions-button">';

		if ( shortcode_exists( 'yith_wcwl_add_to_wishlist' ) ) {
			echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
		}

		echo '<a href="' . $product->get_permalink() . '" data-id="' . esc_attr( $product->get_id() ) . '"  class="gadgetexpress-product-quick-view hidden-sm hidden-xs" data-original-title="' . esc_attr__( 'Quick View', 'gadget' ) . '" data-rel="tooltip"><i class="icon-frame-expand"></i></a>';

		echo '</div>'; // .actions-button
		echo '</div>'; // .footer-button
		echo '</div>'; // .un-product-thumbnail

	}

	function shop_view_list_button() {
		global $product;

		echo '<div class="footer-button footer-button-shop-list">';

		if ( function_exists( 'woocommerce_template_loop_add_to_cart' ) ) {
			woocommerce_template_loop_add_to_cart();
		}

		echo '<div class="actions-button">';

		if ( shortcode_exists( 'yith_wcwl_add_to_wishlist' ) ) {
			echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
		}

		echo '<a href="' . $product->get_permalink() . '" data-id="' . esc_attr( $product->get_id() ) . '"  class="gadgetexpress-product-quick-view hidden-sm hidden-xs" data-original-title="' . esc_attr__( 'Quick View', 'gadget' ) . '" data-rel="tooltip"><i class="icon-frame-expand"></i></a>';

		echo '</div>'; // .actions-button
		echo '</div>'; // .footer-button.footer-button-shop-list
	}

	/**
	 * Display badge for new product or featured product
	 *
	 * @since 1.0
	 */
	function product_ribbons() {
		global $product;


		if ( intval( gadgetexpress_get_option( 'show_badges' ) ) ) {
			$output = array();
			$badges = gadgetexpress_get_option( 'badges' );
			// Change the default sale ribbon

			$custom_badges = maybe_unserialize( get_post_meta( $product->get_id(), 'custom_badges_text', true ) );
			if ( $custom_badges ) {
				$output[] = '<span class="custom ribbon">' . esc_html( $custom_badges ) . '</span>';

			} else {

				if ( $product->get_stock_status() == 'outofstock' && in_array( 'outofstock', $badges ) ) {
					$outofstock = esc_html__( 'Out Of Stock', 'gadget' );
					$output[] = '<span class="out-of-stock ribbon">' . esc_html( $outofstock ) . '</span>';
				} elseif ( $product->is_on_sale() && in_array( 'sale', $badges ) ) {
					// Text
					$sale = esc_html__( 'Sale', 'gadget' );

					// Discount
					$percentage = 0;
					$save       = 0;
					if ( $product->get_type() == 'variable' ) {
						$available_variations = $product->get_available_variations();
						$percentage           = 0;
						$save                 = 0;

						for ( $i = 0; $i < count( $available_variations ); $i ++ ) {
							$variation_id     = $available_variations[ $i ]['variation_id'];
							$variable_product = new WC_Product_Variation( $variation_id );
							$regular_price    = $variable_product->get_regular_price();
							$sales_price      = $variable_product->get_sale_price();
							if ( empty( $sales_price ) ) {
								continue;
							}
							$max_percentage = $regular_price ? round( ( ( ( $regular_price - $sales_price ) / $regular_price ) * 100 ) ) : 0;
							$max_save       = $regular_price ? $regular_price - $sales_price : 0;

							if ( $percentage < $max_percentage ) {
								$percentage = $max_percentage;
							}

							if ( $save < $max_save ) {
								$save = $max_save;
							}
						}
					} elseif ( $product->get_type() == 'simple' || $product->get_type() == 'external' ) {
						$percentage = round( ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100 );
						$save       = $product->get_regular_price() - $product->get_sale_price();
					}

					if ( $percentage && gadgetexpress_get_option( 'sale_behaviour' ) == 'percentage' ) {
						$sale_html = '<span class="onsale ribbon"><span class="sep">-</span>' . $percentage . '%' . '</span>';
					} else {
						$sale_html = '<span class="onsale ribbon">' . esc_html( $sale ) . '</span>';
					}

					$output[] = $sale_html;

				} elseif ( $product->is_featured() && in_array( 'hot', $badges ) ) {
					$hot = gadgetexpress_get_option( 'hot_text' );
					if ( ! $hot ) {
						$hot = esc_html__( 'Hot', 'gadget' );
					}
					$output[] = '<span class="featured ribbon">' . esc_html( $hot ) . '</span>';
				} elseif ( ( time() - ( 60 * 60 * 24 * $this->new_duration ) ) < strtotime( get_the_time( 'Y-m-d' ) ) && in_array( 'new', $badges ) ||
				           get_post_meta( $product->get_id(), '_is_new', true ) == 'yes'
				) {
					$new = esc_html__( 'New', 'gadget' );
					$output[] = '<span class="newness ribbon">' . esc_html( $new ) . '</span>';
				}
			}

			if ( $output ) {
				printf( '<span class="ribbons">%s</span>', implode( '', $output ) );
			}


		}
	}

	function product_meta() {
		global $product;
		$meta = gadgetexpress_get_option( 'show_product_meta' );
		$cats = $tags = '';

		if ( ! in_array( 'categories', $meta ) && ! in_array( 'tags', $meta ) ) {
			return;
		}

		if ( in_array( 'categories', $meta ) ) {
			$cats = wc_get_product_category_list( $product->get_id(), ', ', '<div class="posted_in"><strong>' . _n( 'Category: ', 'Categories: ', count( $product->get_category_ids() ), 'gadget' ) . '</strong>', '</div>' );
		}

		if ( in_array( 'tags', $meta ) ) {
			$tags = wc_get_product_tag_list( $product->get_id(), ', ', '<div class="tagged_as"><strong>' . _n( 'Tag: ', 'Tags: ', count( $product->get_tag_ids() ), 'gadget' ) . '</strong>', '</div>' );
		}

		echo '<div class="product_meta">' . $cats . $tags . '</div>';
	}

	function product_sku() {
		$meta = gadgetexpress_get_option( 'show_product_meta' );
		if ( ! in_array( 'sku', $meta ) ) {
			return;
		}

		global $product;

		if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) {
			?>
            <div class="sku_wrapper"><?php esc_html_e( 'SKU:', 'gadget' ); ?>
                <span class="sku">
                    <?php
                    if( $sku = $product->get_sku() ) {
                        echo wp_kses_post( $sku );
                    } else {
	                    echo esc_html__( 'N/A', 'gadget' );
                    }
                  ?>
                </span>
            </div>
			<?php
		}
	}

	function product_header_summary() {
		global $product;
		?>
        <div class="header-summary">
            <p class="price"><?php echo wp_kses_post($product->get_price_html()); ?></p>
			<?php
			if ( $product->get_type() == 'simple' ) {
				echo wc_get_stock_html( $product );
			}
			?>
        </div>
		<?php
	}

	/**
	 * Display wishlist_button
	 *
	 * @since 1.0
	 */
	function yith_button() {
		echo '<div class="actions-button">';
		if ( shortcode_exists( 'yith_wcwl_add_to_wishlist' ) ) {
			echo '<div class="gadgetexpress-wishlist-button">';
			echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
			echo '</div>';
		}

		echo '</div>';
	}

	/**
	 * Add Bootstrap's column classes for product
	 *
	 * @since 1.0
	 *
	 * @param array $classes
	 * @param string $class
	 * @param string $post_id
	 *
	 * @return array
	 */
	function product_class( $classes, $class = '', $post_id = '' ) {
		if ( ! $post_id || get_post_type( $post_id ) !== 'product' ) {
			return $classes;
		}

		global $product;

		if ( ! is_single( $post_id ) ) {
			global $woocommerce_loop;

			$sm_class = 'col-sm-4';

			if ( $woocommerce_loop['columns'] == 2 ) {
				$sm_class = 'col-sm-6';
			}


			if ( ! is_search() ) {
				$classes[] = 'col-xs-6 ' . $sm_class;

				if ( $woocommerce_loop['columns'] != 5 ) {
					$classes[] = 'col-md-' . ( 12 / $woocommerce_loop['columns'] );
				} else {
					$classes[] = 'col-md-1-5';
				}

				$classes[] = 'un-' . $woocommerce_loop['columns'] . '-cols';
			} else {
				if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
					$classes[] = 'col-xs-6 ' . $sm_class;

					if ( $woocommerce_loop['columns'] != 5 ) {
						$classes[] = 'col-md-' . ( 12 / $woocommerce_loop['columns'] );
					} else {
						$classes[] = 'col-md-1-5';
					}

					$classes[] = 'un-' . $woocommerce_loop['columns'] . '-cols';
				}
			}
		} else {
			$classes[]      = 'gadgetexpress-single-product';
			$product_layout = gadgetexpress_get_option( 'single_product_layout' );
			$classes[]      = 'gadgetexpress-product-layout-' . $product_layout;

			if ( in_array( $product_layout, array( '1', '2', '5', '6' ) ) ) {
				$classes[] = 'gadgetexpress-product-slider';
			}

			$columns = wc_get_default_products_per_row();

			if ( $columns == '5' ) {
				$classes[] = 'gadgetexpress-product-slider';
			}
		}

		return $classes;
	}

	/**
	 * Add Bootstrap's column classes for product cat
	 *
	 * @since 1.0
	 *
	 * @param array $classes
	 * @param string $class
	 * @return array
	 */
	function product_cat_class( $classes, $class = '', $category = '' ) {
		if ( is_search() ) {
			return $classes;
		}

		global $woocommerce_loop;

		$sm_class = 'col-sm-4';

		if ( $woocommerce_loop['columns'] == 2 ) {
			$sm_class = 'col-sm-6';
		}

		$classes[] = 'col-xs-6 ' . $sm_class;
		$classes[] = 'col-md-' . ( 12 / $woocommerce_loop['columns'] );
		$classes[] = 'un-' . $woocommerce_loop['columns'] . '-cols';

		return $classes;
	}

	/**
	 * Wrap product content
	 * Open a div
	 *
	 * @since 1.0
	 */
	function open_product_inner() {
		echo '<div class="product-inner  clearfix">';
	}

	/**
	 * Wrap product content
	 * Close a div
	 *
	 * @since 1.0
	 */
	function close_product_inner() {
		echo '</div>';
	}

	/**
	 * Wrap product categories thumb
	 * Open a div
	 *
	 * @since 1.0
	 */
	function open_product_categories_thumb() {
		echo '<span class="gadgetexpress-product-categories-thumb">';
	}

	/**
	 * Wrap product categories thumb
	 * Close a div
	 *
	 * @since 1.0
	 */
	function close_product_categories_thumb() {
		echo '</span>';
	}

	/**
	 * Wrap button group
	 * Open a div
	 *
	 * @since 1.0
	 */
	function open_button_group() {
		echo '<div class="single-button-wrapper">';
	}

	/**
	 * Wrap button group
	 * Close a div
	 *
	 * @since 1.0
	 */
	function close_button_group() {
		echo '</div>';
	}

	/**
	 * Display a tool bar on top of product archive
	 *
	 * @since 1.0
	 */
	function shop_toolbar() {
		if ( ! gadgetexpress_is_catalog() ) {
			return;
		}

//echo '$elements: '. $elements;

		// $elements = gadgetexpress_get_option( 'shop_toolbar' );
		// if ( ! $elements ) {
		// 	return;
		// }

		$elements = array();

		$elements[] = 'result';
		$elements[] = 'filter';
		$elements[] = 'sort_by';
		$elements[] = 'shop_view';

		$css_class = '';

		$output = array();

		if ( in_array( 'result', $elements ) ) {
			$found = '';

			global $wp_query;
			if ( $wp_query && isset( $wp_query->found_posts ) ) {
				if ( $wp_query->found_posts > 1 ) {
					$label = esc_html__( ' Products', 'gadget' );
				} else {
					$label = esc_html__( ' Product', 'gadget' );
				}
				$found = '<span>' . $wp_query->found_posts . ' </span>' . $label . ' ' . esc_html__( 'Found', 'gadget' );
			}

			if ( $found ) {
				$output[] = sprintf( '<div class="shop-toolbar-el product-found">%s</div>', $found );
			}
		}

		if ( in_array( 'filter', $elements ) ) {
			$output[] = '<div id="gadgetexpress-catalog-filter" class="shop-toolbar-el gadgetexpress-catalog-filter">
							<a href="#">' . esc_html__( 'Filter', 'gadget' ) . '<i class="arrow_carrot-down"></i>

							</a>
						</div>';
		}

		if ( in_array( 'sort_by', $elements ) ) {
			ob_start();
			woocommerce_catalog_ordering();
			$output[] = ob_get_clean();

		}

		if ( in_array( 'shop_view', $elements ) ) {
			$list_current = $this->shop_view == 'list' ? 'current' : '';
			$grid_current = $this->shop_view == 'grid' ? 'current' : '';

			$output[] = sprintf(
				'<div id="gadgetexpress-shop-view" class="shop-toolbar-el shop-view">' .
				'<a href="#" class="view-grid %s" data-view="grid"><i class="icon-icons2"></i></a>' .
				'<a href="#" class="view-list %s" data-view="list"><i class="icon-list4"></i></a>' .
				'</div>',
				esc_attr( $grid_current ),
				esc_attr( $list_current )
			);
		}

		if ( in_array( 'filter', $elements ) ) {
			$output[] = '<div id="gadgetexpress-catalog-filter-mobile" class="shop-toolbar-el gadgetexpress-catalog-filter-mobile">
							<a href="#"><i class="icon-equalizer"></i></a>
						</div>';
		}

		if ( $output ) {
			?>
            <div id="gadgetexpress-shop-toolbar" class="shop-toolbar <?php echo esc_attr( $css_class ); ?>">
				<?php echo implode( ' ', $output ); ?>
            </div>
			<?php
		}
	}

	/**
	 * Display a top bar on top of product archive
	 *
	 * @since 1.0
	 */
	function shop_topbar() {
		if ( ! gadgetexpress_is_catalog() ) {
			return;
		}

		$this->shop_filter_content();
	}

	/**
	 * Display a top bar on top of product archive
	 *
	 * @since 1.0
	 */
	function shop_filter_content() {
		if ( ! gadgetexpress_is_catalog() ) {
			return;
		}

		$elements = gadgetexpress_get_option( 'shop_toolbar' );
		if ( ! in_array( 'filter', $elements ) ) {
			return;
		}

		?>
        <div id="un-shop-topbar" class="widgets-area shop-topbar">
			<div class="widget-panel-header hidden-lg">
				<a href="#" class="close-canvas-panel"><span aria-hidden="true" class="icon-cross2"></span></a>
			</div>
            <div class="shop-topbar-content">
				<?php
				$sidebar = 'catalog-filter';
				if ( is_active_sidebar( $sidebar ) ) {
					dynamic_sidebar( $sidebar );
				}
				?>
            </div>

            <div class="shop-filter-actived">
				<?php
				$link = gadgetexpress_get_page_base_url();

				if ( $_GET ) {
					printf( '<a href="%s" id="remove-filter-actived" class="remove-filter-actived"><i class="icon-cross2"></i>%s</a>', esc_url( $link ), esc_html__( 'Clear All Filter', 'gadget' ) );
				}
				?>
            </div>
        </div>

		<?php
	}

	/**
	 * Shop loading
	 *
	 * @since  1.0.0
	 * @return string
	 */
	function before_shop_loop() {
		if ( ! gadgetexpress_is_catalog() ) {
			return;
		}
		echo '<div id="gadgetexpress-shop-content" class="gadgetexpress-shop-content">';
	}

	/**
	 * Shop loading
	 *
	 * @since  1.0.0
	 * @return string
	 */
	function after_shop_loop() {
		if ( ! gadgetexpress_is_catalog() ) {
			return;
		}
		echo '</div>';
	}

	/**
	 * Shop loading
	 *
	 * @since  1.0.0
	 * @return string
	 */
	function shop_loading() {
		if ( ! gadgetexpress_is_catalog() ) {
			return;
		}
		echo '<div class="gadgetexpress-catalog-loading">
				<span class="gadgetexpress-loader"></span>
			</div>';
	}

	/**
	 * Display upsell products
	 *
	 * @since 1.0
	 */
	function upsell_products() {
		$upsell_numbers = intval( gadgetexpress_get_option( 'upsells_products_numbers' ) );
		$upsell_columns = intval( gadgetexpress_get_option( 'upsells_products_columns' ) );

		if ( $upsell_columns && $upsell_numbers && function_exists( 'woocommerce_upsell_display' ) ) {
			woocommerce_upsell_display( $upsell_numbers, $upsell_columns );
		}

	}

	/**
	 * Change related products args to display in correct grid
	 *
	 * @param  array $args
	 *
	 * @return array
	 */
	function related_products( $args ) {

		$args['posts_per_page'] = intval( gadgetexpress_get_option( 'related_products_numbers' ) );;
		$args['columns'] = intval( gadgetexpress_get_option( 'related_products_columns' ) );

		return $args;
	}

	/**
	 * Change variation text
	 *
	 * @since 1.0
	 */
	function variation_attribute_options( $args ) {
		$attribute = $args['attribute'];
		if ( function_exists( 'wc_attribute_label' ) && $attribute ) {
			$args['show_option_none'] = esc_html__( 'Select', 'gadget' ) . ' ' . wc_attribute_label( $attribute );
		}

		return $args;
	}

	/**
	 * Change number of columns when display cross sells products
	 *
	 * @return int
	 */
	function cross_sells_columns( $cross_columns ) {
		return apply_filters( 'gadgetexpress_cross_sells_columns', 4 );
	}

	/**
	 * Change number of columns when display cross sells products
	 *
	 * @return int
	 */
	function cross_sells_numbers( $cross_numbers ) {
		return apply_filters( 'gadgetexpress_cross_sells_total', 4 );
	}

	/**
	 * Display product toolbar on single product page
	 */
	public function product_toolbar() {
		$toolbar = gadgetexpress_get_option( 'single_product_toolbar' );

		if ( ! $toolbar ) {
			return;
		}

		$args = array(
			'delimiter' => '<span class="circle"></span>',
		);
		?>

        <div class="product-toolbar clearfix">
            <div class="container">
				<?php

				if ( in_array( 'breadcrumb', $toolbar ) ) {
					$yoast = get_option( 'wpseo_internallinks' );

					if ( function_exists( 'yoast_breadcrumb' ) && $yoast && $yoast['breadcrumbs-enable'] ) {
						yoast_breadcrumb( '<div class="breadcrumb">', '</div>' );
					} else {
						woocommerce_breadcrumb( $args );
					}
				}

				if ( in_array( 'navigation', $toolbar ) ) {
					the_post_navigation(
						array(
							'screen_reader_text' => esc_html__( 'Product navigation', 'gadget' ),
							'prev_text'          => _x( '<i class="arrow_carrot-left"></i><span class="screen-reader-text">%title</span>', 'Previous post link', 'gadget' ),
							'next_text'          => _x( '<span class="screen-reader-text">%title</span><i class="arrow_carrot-right"></i>', 'Next post link', 'gadget' ),
						)
					);
				}
				?>
            </div>
        </div>

		<?php
	}

	/**
	 * Display sharing socials for products
	 *
	 * @since 1.0
	 */
	function single_product_socials() {

		if ( ! intval( gadgetexpress_get_option( 'show_product_socials' ) ) ) {
			return;
		}

		global $product;
		$image_id   = $product->get_image_id();
		$image_link = '';
		if ( $image_id ) {
			$image_link = wp_get_attachment_url( $image_id );
		}

		if ( function_exists( 'gadgetexpress_addons_share_link_socials' ) ) {
			echo gadgetexpress_addons_share_link_socials( $product->get_title(), $product->get_permalink(), $image_link );
		}
	}

	/**
	 * Add product tabs to product pages.
	 *
	 * @return string
	 */
	function gadgetexpress_product_review_tab_title() {
		global $product;

		$title = sprintf( '%s <span class="count">(%s)</span>', esc_html__( 'Reviews', 'gadget' ), $product->get_review_count() );

		return $title;
	}

	/**
	 * Add product tabs to product pages.
	 *
	 * @return string
	 */
	function gadgetexpress_product_additional_information_tab_title() {
		$title = esc_html__( 'More Information', 'gadget' );

		return $title;
	}

	function gadgetexpress_catalog_orderby_options( $catalog_orderby_options = '' ) {
		$catalog_orderby_options['menu_order'] = esc_html__( 'Default', 'gadget' );
		$catalog_orderby_options['popularity'] = esc_html__( 'Popularity', 'gadget' );
		$catalog_orderby_options['rating']     = esc_html__( 'Average rating', 'gadget' );
		$catalog_orderby_options['date']       = esc_html__( 'Newness', 'gadget' );
		$catalog_orderby_options['price']      = esc_html__( 'Price: low to high', 'gadget' );
		$catalog_orderby_options['price-desc'] = esc_html__( 'Price: high to low', 'gadget' );

		return $catalog_orderby_options;
	}

	function catalog_orderby_default( $orderby ) {
		$orderby = empty( $orderby ) ? 'menu_order' : $orderby;

		return $orderby;
	}

	function catalog_page_header() {
		if ( ! intval( gadgetexpress_get_option( 'catalog_page_header' ) ) ) {
			return false;
		}

		if (  function_exists('is_product') && is_product() ) {
			return false;
		}

		if ( is_home() && ! is_front_page() ) {
			return false;
		}

		get_template_part( 'parts/page-headers/catalog' );
	}

	/**
	 * Open sticky product summary container
	 */
	public function open_sticky_description() {
		echo '<div class="sticky-summary">';
	}

	/**
	 * Close sticky product summary container
	 */
	public function close_sticky_description() {
		echo '</div>';
	}

	/**
	 *
	 * Display User Name in Address Detail
	 */
	public function display_user_name() {
		$user = get_user_by( 'ID', get_current_user_id() );

		if ( $user ) {
			printf( '<h3 class="m-title">%s %s</h3>', esc_html__( 'Hello!', 'gadget' ), $user->display_name );
		}

	}

	/**
	 * Search products
	 *
	 * @since 1.0
	 */
	public function instance_search_result() {
		if ( apply_filters( 'gadgetexpress_check_ajax_referer', true ) ) {
			check_ajax_referer( '_gadgetexpress_nonce', 'nonce' );
		}

		$response = array();

		if ( isset( $_POST['search_type'] ) && $_POST['search_type'] == 'all' ) {
			$response = $this->instance_search_every_things_result();
		} else {
			$response = $this->instance_search_products_result();
		}

		if ( empty( $response ) ) {
			$response[] = sprintf( '<li>%s</li>', esc_html__( 'Nothing found', 'gadget' ) );
		}

		$output = sprintf( '<ul>%s</ul>', implode( ' ', $response ) );

		wp_send_json_success( $output );
		die();
	}

	function instance_search_products_result() {
		$response = array();
		$args_sku = array(
			'post_type'        => 'product',
			'posts_per_page'   => 30,
			'meta_query'       => array(
				array(
					'key'     => '_sku',
					'value'   => trim( $_POST['term'] ),
					'compare' => 'like',
				),
			),
			'suppress_filters' => 0,
		);

		$args_variation_sku = array(
			'post_type'        => 'product_variation',
			'posts_per_page'   => 30,
			'meta_query'       => array(
				array(
					'key'     => '_sku',
					'value'   => trim( $_POST['term'] ),
					'compare' => 'like',
				),
			),
			'suppress_filters' => 0,
		);

		$args = array(
			'post_type'        => 'product',
			'posts_per_page'   => 30,
			's'                => trim( $_POST['term'] ),
			'suppress_filters' => 0
		);

		if ( function_exists( 'wc_get_product_visibility_term_ids' ) ) {
			$product_visibility_term_ids = wc_get_product_visibility_term_ids();
			$args['tax_query'][]         = array(
				'taxonomy' => 'product_visibility',
				'field'    => 'term_taxonomy_id',
				'terms'    => $product_visibility_term_ids['exclude-from-search'],
				'operator' => 'NOT IN',
			);
		}
		if ( isset( $_POST['cat'] ) && ! empty( $_POST['cat'] ) ) {
			$args['tax_query'][] = array(
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => $_POST['cat'],
			);

			$args_sku['tax_query'] = array(
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => $_POST['cat'],
				),

			);
		}

		$products_sku           = get_posts( $args_sku );
		$products_s             = get_posts( $args );
		$products_variation_sku = get_posts( $args_variation_sku );

		$products    = array_merge( $products_sku, $products_s, $products_variation_sku );
		$product_ids = array();
		foreach ( $products as $product ) {
			$id = $product->ID;
			if ( ! in_array( $id, $product_ids ) ) {
				$product_ids[] = $id;

				$productw   = new WC_Product( $id );
				$response[] = sprintf(
					'<li>' .
					'<a class="search-item" href="%s">' .
					'%s' .
					'<span class="title">%s</span>' .
					'</a>' .
					'</li>',
					esc_url( $productw->get_permalink() ),
					$productw->get_image( 'shop_thumbnail' ),
					$productw->get_title()
				);
			}
		}

		return $response;
	}

	function instance_search_every_things_result() {
		$response = array();
		$args     = array(
			'post_type'        => 'any',
			'posts_per_page'   => 30,
			's'                => trim( $_POST['term'] ),
			'suppress_filters' => 0,
		);

		$posts    = get_posts( $args );
		$post_ids = array();
		foreach ( $posts as $post ) {
			$id = $post->ID;
			if ( ! in_array( $id, $post_ids ) ) {
				$post_ids[] = $id;

				$response[] = sprintf(
					'<li>' .
					'<a class="search-item" href="%s">' .
					'%s' .
					'<span class="title">%s</span>' .
					'</a>' .
					'</li>',
					esc_url( get_the_permalink( $id ) ),
					get_the_post_thumbnail( $id ),
					$post->post_title
				);
			}
		}

		return $response;
	}

	function gallery_image_size() {
		return 'woocommerce_single';
	}

	function gallery_thumb_size() {
		$size = array(
			'width'  => 150,
			'height' => 150
		);

		return $size;
	}

	function single_product_zoom_enabled() {

		if ( in_array( gadgetexpress_get_option( 'single_product_layout' ), array( '3', '4', '5', '6' ) ) ) {
			return false;
		}

		return gadgetexpress_get_option( 'product_zoom' );
	}

	function gadgetexpress_get_item_data( $cart_item, $flat = false ) {
		$item_data = array();

		if ( $cart_item['data']->is_type( 'variation' ) && is_array( $cart_item['variation'] ) ) {
			foreach ( $cart_item['variation'] as $name => $value ) {
				$taxonomy = wc_attribute_taxonomy_name( str_replace( 'attribute_pa_', '', urldecode( $name ) ) );

				if ( taxonomy_exists( $taxonomy ) ) {
					// If this is a term slug, get the term's nice name.
					$term = get_term_by( 'slug', $value, $taxonomy );
					if ( ! is_wp_error( $term ) && $term && $term->name ) {

						// check if TA Swatches activate
						if ( function_exists( 'TA_WCVS' ) ) {
							$attr = TA_WCVS()->get_tax_attribute( $taxonomy );
							switch ( $attr->attribute_type ) {
								case 'color':
									$color = get_term_meta( $term->term_id, 'color', true );
									$html  = sprintf(
										'<span class="swatch swatch-color swatch-%s" style="background-color:%s;" title="%s"></span>',
										esc_attr( $term->slug ),
										esc_attr( $color ),
										esc_attr( $term->name )
									);

									break;

								case 'image':
									$image = get_term_meta( $term->term_id, 'image', true );
									$image = $image ? wp_get_attachment_image_src( $image ) : '';
									$image = $image ? $image[0] : WC()->plugin_url() . '/assets/images/placeholder.png';
									$html  = sprintf(
										'<span class="swatch swatch-image swatch-%s" title="%s"><img src="%s" alt="%s"></span>',
										esc_attr( $term->slug ),
										esc_attr( $term->name ),
										esc_url( $image ),
										esc_attr( $term->name )
									);
									break;

								case 'label':
									$label = get_term_meta( $term->term_id, 'label', true );
									$label = $label ? $label : $term->name;
									$html  = sprintf(
										'<span class="swatch swatch-label swatch-%s" title="%s">%s</span>',
										esc_attr( $term->slug ),
										esc_attr( $label ),
										esc_html( $label )
									);
									break;

								default :
									$html = sprintf(
										'<span class="swatch swatch-select swatch-%s" title="%s">%s</span>',
										esc_attr( $term->slug ),
										esc_attr( $term->name ),
										esc_html( $term->name )
									);
									break;
							}

							$value = $html;

						} else {
							$value = $term->name;
						}
					}

					$label = wc_attribute_label( $taxonomy );

				} else {
					// If this is a custom option slug, get the options name.
					$value = apply_filters( 'woocommerce_variation_option_name', $value );
					$label = wc_attribute_label( str_replace( 'attribute_', '', $name ), $cart_item['data'] );
				}

				// Check the nicename against the title.
				if ( '' === $value || wc_is_attribute_in_product_name( $value, $cart_item['data']->get_name() ) ) {
					continue;
				}

				$item_data[] = array(
					'key'   => $label,
					'value' => $value,
				);
			}
		}

		// Format item data ready to display.
		foreach ( $item_data as $key => $data ) {
			// Set hidden to true to not display meta on cart.
			if ( ! empty( $data['hidden'] ) ) {
				unset( $item_data[ $key ] );
				continue;
			}
			$item_data[ $key ]['key']     = ! empty( $data['key'] ) ? $data['key'] : $data['name'];
			$item_data[ $key ]['display'] = ! empty( $data['display'] ) ? $data['display'] : $data['value'];
		}

		// Output flat or in list format.
		if ( count( $item_data ) > 0 ) {
			ob_start();

			if ( $flat ) {
				foreach ( $item_data as $data ) {
					echo esc_html( $data['key'] ) . ': ' . wp_kses_post( $data['display'] ) . "\n";
				}
			} else {
				wc_get_template( 'cart/cart-item-data.php', array( 'item_data' => $item_data ) );
			}

			return ob_get_clean();
		}

		return '';
	}

	/**
	 * Search SKU
	 *
	 * @since 1.0
	 */
	function product_search_sku( $where ) {
		global $pagenow, $wpdb, $wp;

		if ( ( is_admin() && 'edit.php' != $pagenow )
		     || ! is_search()
		     || ! isset( $wp->query_vars['s'] )
		     || ( isset( $wp->query_vars['post_type'] ) && 'product' != $wp->query_vars['post_type'] )
		     || ( isset( $wp->query_vars['post_type'] ) && is_array( $wp->query_vars['post_type'] ) && ! in_array( 'product', $wp->query_vars['post_type'] ) )
		) {
			return $where;
		}
		$search_ids = array();
		$terms      = explode( ',', $wp->query_vars['s'] );

		foreach ( $terms as $term ) {
			//Include the search by id if admin area.
			if ( is_admin() && is_numeric( $term ) ) {
				$search_ids[] = $term;
			}
			// search for variations with a matching sku and return the parent.

			$sku_to_parent_id = $wpdb->get_col( $wpdb->prepare( "SELECT p.post_parent as post_id FROM {$wpdb->posts} as p join {$wpdb->postmeta} pm on p.ID = pm.post_id and pm.meta_key='_sku' and pm.meta_value LIKE '%%%s%%' where p.post_parent <> 0 group by p.post_parent", wc_clean( $term ) ) );

			//Search for a regular product that matches the sku.
			$sku_to_id = $wpdb->get_col( $wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key='_sku' AND meta_value LIKE '%%%s%%';", wc_clean( $term ) ) );

			$search_ids = array_merge( $search_ids, $sku_to_id, $sku_to_parent_id );
		}

		$search_ids = array_filter( array_map( 'absint', $search_ids ) );

		if ( sizeof( $search_ids ) > 0 ) {
			$where = str_replace( ')))', ") OR ({$wpdb->posts}.ID IN (" . implode( ',', $search_ids ) . "))))", $where );
		}

		return $where;
	}

	/**
	 * Display product attribute
	 *
	 * @since 1.0
	 */
	function product_attribute() {

		$default_attribute = sanitize_title( gadgetexpress_get_option( 'product_attribute' ) );

		if ( $default_attribute == '' || $default_attribute == 'none' ) {
			return;
		}

		$default_attribute = 'pa_' . $default_attribute;

		global $product;
		$attributes         = maybe_unserialize( get_post_meta( $product->get_id(), '_product_attributes', true ) );
		$product_attributes = maybe_unserialize( get_post_meta( $product->get_id(), 'attributes_extra', true ) );

		if ( $product_attributes == 'none' ) {
			return;
		}

		if ( $product_attributes == '' ) {
			$product_attributes = $default_attribute;
		}

		$variations = $this->get_variations( $product_attributes );

		if ( ! $attributes ) {
			return;
		}

		foreach ( $attributes as $attribute ) {


			if ( $product->get_type() == 'variable' ) {
				if ( ! $attribute['is_variation'] ) {
					continue;
				}
			}

			if ( sanitize_title( $attribute['name'] ) == $product_attributes ) {

				echo '<div class="un-attr-swatches">';
				if ( $attribute['is_taxonomy'] ) {
					$post_terms = wp_get_post_terms( $product->get_id(), $attribute['name'] );

					$attr_type = '';

					if ( function_exists( 'TA_WCVS' ) ) {
						$attr = TA_WCVS()->get_tax_attribute( $attribute['name'] );
						if ( $attr ) {
							$attr_type = $attr->attribute_type;
						}
					}
					$found = false;
					foreach ( $post_terms as $term ) {
						$css_class = '';
						if ( is_wp_error( $term ) ) {
							continue;
						}
						if ( $variations && isset( $variations[ $term->slug ] ) ) {
							$attachment_id = $variations[ $term->slug ];
							$attachment    = wp_get_attachment_image_src( $attachment_id, 'shop_catalog' );
							$image_srcset  = wp_get_attachment_image_srcset( $attachment_id, 'shop_catalog' );

							if ( $attachment_id == get_post_thumbnail_id() && ! $found ) {
								$css_class .= ' selected';
								$found     = true;
							}

							if ( $attachment ) {
								$css_class .= ' un-swatch-variation-image';
								$img_src   = $attachment[0];
								echo '' . $this->swatch_html( $term, $attr_type, $img_src, $css_class, $image_srcset );
							}

						}
					}
				}
				echo '</div>';
				break;
			}
		}

	}

	/**
	 * Print HTML of a single swatch
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function swatch_html( $term, $attr_type, $img_src, $css_class, $image_srcset ) {

		$html = '';
		$name = $term->name;

		switch ( $attr_type ) {
			case 'color':
				$color = get_term_meta( $term->term_id, 'color', true );
				list( $r, $g, $b ) = sscanf( $color, "#%02x%02x%02x" );
				$html = sprintf(
					'<span class="swatch swatch-color %s" data-src="%s" data-src-set="%s" title="%s"><span class="sub-swatch" style="background-color:%s;color:%s;"></span> </span>',
					esc_attr( $css_class ),
					esc_url( $img_src ),
					esc_attr( $image_srcset ),
					esc_attr( $name ),
					esc_attr( $color ),
					"rgba($r,$g,$b,0.5)"
				);
				break;

			case 'image':
				$image = get_term_meta( $term->term_id, 'image', true );
				if ( $image ) {
					$image = wp_get_attachment_image_src( $image );
					$image = $image ? $image[0] : WC()->plugin_url() . '/assets/images/placeholder.png';
					$html  = sprintf(
						'<span class="swatch swatch-image %s" data-src="%s" data-src-set="%s" title="%s"><img src="%s" alt="%s"></span>',
						esc_attr( $css_class ),
						esc_url( $img_src ),
						esc_attr( $image_srcset ),
						esc_attr( $name ),
						esc_url( $image ),
						esc_attr( $name )
					);
				}

				break;

			default:
				$label = get_term_meta( $term->term_id, 'label', true );
				$label = $label ? $label : $name;
				$html  = sprintf(
					'<span class="swatch swatch-label %s" data-src="%s" data-src-set="%s" title="%s">%s</span>',
					esc_attr( $css_class ),
					esc_url( $img_src ),
					esc_attr( $image_srcset ),
					esc_attr( $name ),
					esc_html( $label )
				);
				break;

		}

		return $html;
	}

	/**
	 * Get variations
	 *
	 * @since  1.0.0
	 * @return string
	 */
	function get_variations( $default_attribute ) {
		global $product;

		$variations = array();
		if ( $product->get_type() == 'variable' ) {
			$args = array(
				'post_parent' => $product->get_id(),
				'post_type'   => 'product_variation',
				'orderby'     => 'menu_order',
				'order'       => 'ASC',
				'fields'      => 'ids',
				'post_status' => 'publish',
				'numberposts' => - 1,
			);

			if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
				$args['meta_query'][] = array(
					'key'     => '_stock_status',
					'value'   => 'instock',
					'compare' => '=',
				);
			}

			$thumbnail_id = get_post_thumbnail_id();

			$posts = get_posts( $args );

			foreach ( $posts as $post_id ) {
				$attachment_id = get_post_thumbnail_id( $post_id );
				$attribute     = $this->get_variation_attributes( $post_id, 'attribute_' . $default_attribute );

				if ( ! $attachment_id ) {
					$attachment_id = $thumbnail_id;
				}

				if ( $attribute ) {
					$variations[ $attribute[0] ] = $attachment_id;
				}

			}

		}

		return $variations;
	}

	/**
	 * Get variation attribute
	 *
	 * @since  1.0.0
	 * @return string
	 */
	public function get_variation_attributes( $child_id, $attribute ) {
		global $wpdb;

		$values = array_unique(
			$wpdb->get_col(
				$wpdb->prepare(
					"SELECT meta_value FROM {$wpdb->postmeta} WHERE meta_key = %s AND post_id IN (" . $child_id . ")",
					$attribute
				)
			)
		);

		return $values;
	}
}

function gadgetexpress_product( $_product ) {
	//$attachment_ids  = $_product->get_gallery_image_ids();
	//$secondary_thumb = intval( gadgetexpress_get_option( 'disable_secondary_thumb' ) );
	$shop_view       = gadgetexpress_get_option( 'shop_view' );
	$new_duration    = gadgetexpress_get_option( 'product_newness' );

	echo '<div class="un-product-thumbnail">';
		echo '<a href ="'. esc_url( $_product->get_permalink() ) .'" class="">';

		echo get_the_post_thumbnail($_product->get_id());

		if ( intval( gadgetexpress_get_option( 'show_badges' ) ) ) :
			$output = array();
			$badges = gadgetexpress_get_option( 'badges' );
			// Change the default sale ribbon

			$custom_badges = maybe_unserialize( get_post_meta( $_product->get_id(), 'custom_badges_text', true ) );
			if ( $custom_badges ) :
				$output[] = '<span class="custom ribbon">' . esc_html( $custom_badges ) . '</span>';
			else :
				if ( $_product->get_stock_status() == 'outofstock' && in_array( 'outofstock', $badges ) ) :
					$outofstock = esc_html__( 'Out Of Stock', 'gadget' );
					$output[] = '<span class="out-of-stock ribbon">' . esc_html( $outofstock ) . '</span>';
				elseif ( $_product->is_on_sale() && in_array( 'sale', $badges ) ) :
					$sale = esc_html__( 'Sale', 'gadget' );
					$output[] = '<span class="onsale ribbon">' . esc_html( $sale ) . '</span>';
				elseif ( $_product->is_featured() && in_array( 'hot', $badges ) ) :
					$hot = esc_html__( 'Hot', 'gadget' );
					$output[] = '<span class="featured ribbon">' . esc_html( $hot ) . '</span>';
				elseif ( ( time() - ( 60 * 60 * 24 * $new_duration ) ) < strtotime( get_the_time( 'Y-m-d' ) ) && in_array( 'new', $badges ) ||
						get_post_meta( $_product->get_id(), '_is_new', true ) == 'yes' ) :
					$new = esc_html__( 'New', 'gadget' );
					$output[] = '<span class="newness ribbon">' . esc_html( $new ) . '</span>';
				endif;
			endif;

			if ( $output ) :
				printf( '<span class="ribbons">%s</span>', implode( '', $output ) );
			endif;

		endif;

		echo '</a>';

		echo '<div class="footer-button">';

			// Add to Cart button
			$args = array();

			$defaults = array(
                'quantity'   => 1,
                'class'      => implode( ' ', array_filter( array(
                    'button',
                    'product_type_' . $_product->get_type(),
                    $_product->is_purchasable() && $_product->is_in_stock() ? 'add_to_cart_button' : '',
                    $_product->supports( 'ajax_add_to_cart' ) ? 'ajax_add_to_cart' : '',
                ) ) ),
                'attributes' => array(
                    'data-product_id'  => $_product->get_id(),
                    'data-product_sku' => $_product->get_sku(),
                    'aria-label'       => $_product->add_to_cart_description(),
                    'rel'              => 'nofollow',
                ),
            );

            $args = apply_filters( 'woocommerce_loop_add_to_cart_args', wp_parse_args( $args, $defaults ), $_product );

			echo apply_filters( 'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
			sprintf( '<a href="%s" data-quantity="%s" data-title="%s" class="%s" %s>%s</a>',
				esc_url( $_product->add_to_cart_url() ),
				esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
				esc_html($_product->get_title()),
				esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
				isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
				esc_html( $_product->add_to_cart_text() )
			),
			$_product, $args );

			echo '<div class="actions-button">';

				// Add to wishlist (YITH)
				echo do_shortcode( '[yith_wcwl_add_to_wishlist product_id="'. $_product->get_id(). '"]' );

				// Quick view (YITH)
				echo '<a href="' . $_product->get_permalink() . '" data-id="' . esc_attr( $_product->get_id() ) . '"  class="gadgetexpress-product-quick-view hidden-sm hidden-xs" data-original-title="' . esc_attr__( 'Quick View', 'gadget' ) . '" data-rel="tooltip"><i class="icon-frame-expand"></i></a>';

			echo '</div>';
		echo '</div>';
	echo '</div>';

	echo '<div class="un-product-details">';
		printf( '<h3><a href="%s">%s</a></h3>', esc_url( $_product->get_permalink() ), $_product->get_title() );
		?>
		<span class="price"><?php echo wp_kses_post($_product->get_price_html()); ?></span>
		<?php
		echo '<div class="footer-button footer-button-shop-list">';

			if ( function_exists( 'woocommerce_template_loop_add_to_cart' ) ) :
				woocommerce_template_loop_add_to_cart();
			endif;

			echo '<div class="actions-button">';

				if ( shortcode_exists( 'yith_wcwl_add_to_wishlist' ) ) :
					echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
				endif;

				echo '<a href="' . $_product->get_permalink() . '" data-id="' . esc_attr( $_product->get_id() ) . '"  class="gadgetexpress-product-quick-view hidden-sm hidden-xs" data-original-title="' . esc_attr__( 'Quick View', 'gadget' ) . '" data-rel="tooltip"><i class="icon-frame-expand"></i></a>';

			echo '</div>';
		echo '</div>';
	echo '</div>';
}


// function gadgetexpress_share() {

// 	echo 'Share:';
// }