<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! class_exists( 'WC_Widget' ) ) {
	return;
}

/**
 * Tag Cloud Widget.
 *
 */
class Toffe_Dassen_Widget_Product_Cat extends WC_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {


		$this->widget_cssclass    = 'woocommerce widget_product_categories gadgetexpress_widget_product_categories';
		$this->widget_description = esc_html__( 'A list of product categories.', 'gadget' );
		$this->widget_id          = 'gadgetexpress_product_cat';
		$this->widget_name        = esc_html__( 'Gadget Express Product Categories', 'gadget' );
		$this->settings           = array(
			'title' => array(
				'type'  => 'text',
				'std'   => esc_html__( 'Product Categories', 'gadget' ),
				'label' => esc_html__( 'Title', 'gadget' )
			),
			'style' => array(
				'type'    => 'select',
				'std'     => '1',
				'options' => array(
					'1' => esc_html__( 'Style 1', 'gadget' ),
					'2' => esc_html__( 'Style 2', 'gadget' )
				),
				'label'   => esc_html__( 'Style', 'gadget' )
			),
			'height' => array(
				'type'  => 'text',
				'std'   => '',
				'label' => esc_html__( 'Height', 'gadget' )
			),
		);

		parent::__construct();
	}

	/**
	 * Output widget.
	 *
	 * @see WP_Widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		$this->widget_start( $args, $instance );
		$current_taxonomy = 'product_cat';

		$list_args = array(
			'taxonomy' => $current_taxonomy,
			'title_li' => ''
		);

		if ( empty( $instance['title'] ) ) {
			$taxonomy          = get_taxonomy( $current_taxonomy );
			$instance['title'] = $taxonomy->labels->name;
		}

		$attr = '';

		if ( isset( $instance['height'] ) && $instance['height'] ) {
			$height = $instance['height'];

			$attr = 'data-height="' . intval( $height ) . '"';
		}

		if ( isset( $instance['style'] ) && $instance['style'] != '2' ) {
			$term_id        = 0;
			$queried_object = get_queried_object();
			if ( $queried_object && isset ( $queried_object->term_id ) ) {
				$term_id = $queried_object->term_id;
			}

			$terms  = get_terms( $current_taxonomy );
			$found  = false;
			$output = array();
			if ( $terms ) {

				foreach ( $terms as $term ) {

					$css_class = 'cat-item cat-item-' . $term->term_id;
					if ( $term_id == $term->term_id ) {
						$css_class .= ' chosen';
						$found = true;
					}

					$output[] = sprintf( '<li class="%s"><a href="%s">%s</a></li>', esc_attr( $css_class ), esc_url( get_term_link( $term ) ), $term->name );
				}

			}

			$css_class = $found ? '' : 'chosen';

			printf(
				'<ul class="product-categories" %s>' .
				'<li class="%s"><a href="%s">%s</a></li>' .
				'%s' .
				'</ul>',
				$attr,
				esc_attr( $css_class ),
				esc_url( esc_url( get_permalink( get_option( 'woocommerce_shop_page_id' ) ) ) ),
				esc_html__( 'All', 'gadget' ),
				implode( ' ', $output )
			);
		} else {
			echo '<ul class="product-categories" ' . $attr . '>';

			wp_list_categories( apply_filters( 'woocommerce_product_categories_widget_args', $list_args ) );

			echo '</ul>';
		}

		$this->widget_end( $args );
	}

	/**
	 * Returns topic count text.
	 *
	 * @since 2.6.0
	 *
	 * @param int $count
	 *
	 * @return string
	 */
	public function _topic_count_text( $count ) {
		/* translators: %s: product count */
		return sprintf( _n( '%s product', '%s products', $count, 'gadget' ), number_format_i18n( $count ) );
	}
}
