<?php

class Toffe_Dassen_Social_Links_Widget extends WP_Widget {
	/**
	 * Holds widget settings defaults, populated in constructor.
	 *
	 * @var array
	 */
	protected $default;

	/**
	 * List of supported socials
	 *
	 * @var array
	 */
	protected $socials;

	/**
	 * Constructor
	 */
	function __construct() {
		$socials = array(
			'facebook'   => esc_html__( 'Facebook', 'gadget' ),
			'twitter'    => esc_html__( 'Twitter', 'gadget' ),
			'googleplus' => esc_html__( 'Google Plus', 'gadget' ),
			'youtube'    => esc_html__( 'Youtube', 'gadget' ),
			'tumblr'     => esc_html__( 'Tumblr', 'gadget' ),
			'linkedin'   => esc_html__( 'Linkedin', 'gadget' ),
			'pinterest'  => esc_html__( 'Pinterest', 'gadget' ),
			'flickr'     => esc_html__( 'Flickr', 'gadget' ),
			'instagram'  => esc_html__( 'Instagram', 'gadget' ),
			'dribbble'   => esc_html__( 'Dribbble', 'gadget' ),
			'skype'      => esc_html__( 'Skype', 'gadget' ),
			'rss'        => esc_html__( 'RSS', 'gadget' )
		);

		$this->socials = apply_filters( 'gadgetexpress_social_media', $socials );
		$this->default = array(
			'title' => '',
			'style' => 'style-1',
		);

		foreach ( $this->socials as $k => $v ) {
			$this->default["{$k}_title"] = $v;
			$this->default["{$k}_url"]   = '';
		}

		parent::__construct(
			'gadgetexpress-social-links-widget',
			esc_html__( 'Gadget Express - Social Links', 'gadget' ),
			array(
				'classname'   => 'gadgetexpress-social-links-widget',
				'description' => esc_html__( 'Display links to social media networks.', 'gadget' ),
			),
			array( 'width' => 600 )
		);
	}

	/**
	 * Outputs the HTML for this widget.
	 *
	 * @param array $args An array of standard parameters for widgets in this theme
	 * @param array $instance An array of settings for this widget instance
	 *
	 * @return void Echoes it's output
	 */
	function widget( $args, $instance ) {
		$instance = wp_parse_args( $instance, $this->default );

		echo wp_kses_post( $args['before_widget'] );

		if ( $title = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base ) ) {
			echo wp_kses_post( $args['before_title'] ) . $title . wp_kses_post( $args['after_title'] );
		}

		echo '<ul class="socials-list ' . esc_attr( $instance['style'] ) . '">';
		foreach ( $this->socials as $social => $label ) {
			if ( ! empty( $instance[ $social . '_url' ] ) ) {
				echo sprintf(
					'<li><a href="%s" class="share-%s tooltip-enable social" rel="nofollow" title="%s" data-toggle="tooltip" data-placement="top" target="_blank"><i class="social social_%s"></i></a></li>',
					esc_url( $instance[ $social . '_url' ] ),
					esc_attr( $social ),
					esc_attr( $instance[ $social . '_title' ] ),
					esc_attr( $social )
				);
			}
		}
		echo '</ul>';

		echo wp_kses_post( $args['after_widget'] );
	}

	/**
	 * Displays the form for this widget on the Widgets page of the WP Admin area.
	 *
	 * @param array $instance
	 *
	 * @return string|void
	 */
	function form( $instance ) {
		$instance = wp_parse_args( $instance, $this->default );
		$style    = array(
			'style-1' => esc_html__( 'Style 1', 'gadget' ),
			'style-2' => esc_html__( 'Style 2', 'gadget' ),
		);
		?>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'gadget' ); ?></label>
            <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"
                   name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
                   value="<?php echo esc_attr( $instance['title'] ); ?>"/>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>"><?php esc_html_e( 'Style', 'gadget' ); ?></label>
            <select name="<?php echo esc_attr( $this->get_field_name( 'style' ) ); ?>"
                    id="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>" class="widefat">
				<?php foreach ( $style as $name => $value ) : ?>
                    <option value="<?php echo esc_attr( $name ) ?>" <?php selected( $name, $instance['style'] ) ?>><?php echo esc_attr( $value ) ?></option>
				<?php endforeach; ?>
            </select>
        </p>

		<?php
		foreach ( $this->socials as $social => $label ) {
			printf(
				'<div style="width: 280px; float: left; margin-right: 10px;">
					<label>%s</label>
					<p><input type="text" class="widefat" name="%s" placeholder="%s" value="%s"></p>
				</div>',
				$label,
				$this->get_field_name( $social . '_url' ),
				esc_html__( 'URL', 'gadget' ),
				$instance[ $social . '_url' ]
			);
		}
	}
}
