<?php
/**
 * Custom functions for entry.
 *
 * @package Gadget Express
 */


/**
 * Prints HTML with meta information for the social share and tags.
 *
 * @since 1.0.0
 */
function gadgetexpress_entry_footer() {
	if ( ! has_tag() && ! intval( gadgetexpress_get_option( 'show_post_social_share' ) ) ) {
		return;
	}

	$layout = gadgetexpress_get_option( 'single_post_layout' );
	$col    = 'col-md-12';

	if ( 'full-content' == $layout ) {
		$col = 'col-md-8 col-md-offset-2';
	}


	echo '<footer class="entry-footer">' .
	     '<div class="row">' .
	     '<div class="' . esc_attr( $col ) . ' col-xs-12 col-sm-12">' .
	     '<div class="entry-footer-wrapper">';

	if ( has_tag() ) :
		the_tags( '<div class="tag-list"><span class="tag-title">' . esc_html__( 'Tags: ', 'gadget' ) . '</span>', ', ', '</div>' );
	endif;

	if ( intval( gadgetexpress_get_option( 'show_post_social_share' ) ) ) {
		echo '<div class="gadgetexpress-single-post-socials-share">';
		echo gadgetexpress_addons_share_link_socials( get_the_title(), get_the_permalink(), get_the_post_thumbnail() );
		echo '</div>';
	};

	echo '</div></div></div></footer>';

}


/**
 * Get or display limited words from given string.
 * Strips all tags and shortcodes from string.
 *
 * @since 1.0.0
 *
 * @param integer $num_words The maximum number of words
 * @param string $more More link.
 * @param bool $echo Echo or return output
 *
 * @return string|void Limited content.
 */
function gadgetexpress_content_limit( $num_words, $more = "&hellip;" ) {
	$content = get_the_excerpt();

	// Strip tags and shortcodes so the content truncation count is done correctly
	$content = strip_tags( strip_shortcodes( $content ), apply_filters( '
	gadgetexpress_content_limit_allowed_tags', '<script>,<style>' ) );

	// Remove inline styles / scripts
	$content = trim( preg_replace( '#<(s(cript|tyle)).*?</\1>#si', '', $content ) );

	// Truncate $content to $max_char
	$content = wp_trim_words( $content, $num_words );

	if ( $more ) {
		$output = sprintf(
			'<p>%s <a href="%s" class="more-link" title="%s">%s</a></p>',
			$content,
			get_permalink(),
			sprintf( esc_html__( 'Continue reading &quot;%s&quot;', 'gadget' ), the_title_attribute( 'echo=0' ) ),
			esc_html( $more )
		);
	} else {
		$output = sprintf( '<p>%s</p>', $content );
	}

	return $output;

}


/**
 * Show entry thumbnail base on its format
 *
 * @since  1.0
 */
function gadgetexpress_entry_thumbnail( $size = 'thumbnail' ) {
	$html = '';

	$css_post = '';

	if ( $post_format = get_post_format() ) {
		$css_post = 'format-' . $post_format;
	}

	if ( get_post_format() != 'gallery' && get_post_format() != 'video' ) {
		$css_post = 'format-default';
	}

	switch ( get_post_format() ) {
		case 'gallery':
			$images = get_post_meta( get_the_ID(), 'images' );

			$gallery = array();
			if ( empty( $images ) ) {
				$thumb = get_the_post_thumbnail( get_the_ID(), $size );

				$html .= '<div class="single-image">' . $thumb . '</div>';
			} else {
				foreach ( $images as $image ) {
					$thumb = wp_get_attachment_image( $image, $size );
					if ( $thumb ) {
						$gallery[] = sprintf( '<div class="item-gallery">%s</div>', $thumb );
					}
				}

				$html .= implode( '', $gallery );
			}

			break;

		case 'video':
			$video = get_post_meta( get_the_ID(), 'video', true );
			if ( is_singular( 'post' ) ) {
				if ( ! $video ) {
					break;
				}

				// If URL: show oEmbed HTML
				if ( filter_var( $video, FILTER_VALIDATE_URL ) ) {
					if ( $oembed = @wp_oembed_get( $video, array( 'width' => 1170 ) ) ) {
						$html .= $oembed;
					} else {
						$atts = array(
							'src'   => $video,
							'width' => 1170,
						);

						if ( has_post_thumbnail() ) {
							$atts['poster'] = get_the_post_thumbnail_url( get_the_ID(), 'full' );
						}
						$html .= wp_video_shortcode( $atts );
					}
				} // If embed code: just display
				else {
					$html .= $video;
				}
			} else {
				$image_src = get_the_post_thumbnail( get_the_ID(), $size );
				if ( $video ) {
					$html = sprintf( '<a href="%s">%s</a>', esc_url( $video ), $image_src );
				} else {
					$html = $image_src;
				}
			}

			break;

		default:
			$html = get_the_post_thumbnail( get_the_ID(), $size );

			$thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), $size );

			if ( ! is_singular( 'post' ) ) {
				$html = sprintf( '<a href="%s">%s</a>', esc_url( get_the_permalink() ), $html );
			} else {
				if ( $thumbnail_url ) {
					$html = sprintf( '<div class="featured-image" style="background-image: url(%s)"></div>', esc_url( $thumbnail_url ) );
				}
			}

			break;
	}

	if ( $html ) {
		$html = sprintf( '<div  class="entry-format %s">%s</div>', esc_attr( $css_post ), $html );
	}

	echo apply_filters( __FUNCTION__, $html, get_post_format() );
}

/**
 * Get author meta
 *
 * @since  1.0
 *
 */
function gadgetexpress_author_box() {
	if ( gadgetexpress_get_option( 'show_author_box' ) == 0 ) {
		return;
	}

	if ( ! get_the_author_meta( 'description' ) ) {
		return;
	}

	$layout = gadgetexpress_get_option( 'single_post_layout' );
	$col    = 'col-md-12';

	if ( 'full-content' == $layout ) {
		$col = 'col-md-8 col-md-offset-2';
	}

	?>
    <div class="post-author">
        <div class="row">
            <div class="<?php echo esc_attr( $col ) ?> col-xs-12 col-sm-12">
                <div class="post-author-box clearfix">
                    <div class="post-author-avatar">
						<?php echo get_avatar( get_the_author_meta( 'ID' ), 70 ); ?>
                    </div>
                    <div class="post-author-info">
                        <h3 class="author-name"><?php the_author_meta( 'display_name' ); ?></h3>

                        <p><?php the_author_meta( 'description' ); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<?php
}

/**
 * Get blog entry meta
 *
 * @since  1.0
 *
 */
function gadgetexpress_entry_meta() {
	$fields = (array) gadgetexpress_get_option( 'blog_entry_meta' );

	if ( empty ( $fields ) ) {
		return;
	}

	echo '<div class="entry-metas">';

	foreach ( $fields as $field ) {
		switch ( $field ) {

			case 'cat':
				$category = get_the_terms( get_the_ID(), 'category' );

				if ( ! is_wp_error( $category ) && $category ) {
					echo sprintf( '<a href="%s" class="entry-meta entry-cat">%s</a>', esc_url( get_term_link( $category[0], 'category' ) ), esc_html( $category[0]->name ) );
				}

				break;

			case 'date':
				$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

				$time_string = sprintf(
					$time_string,
					esc_attr( get_the_date( 'c' ) ),
					esc_html( get_the_date() )
				);

				$archive_year  = get_the_time( 'Y' );
				$archive_month = get_the_time( 'm' );
				$archive_day   = get_the_time( 'd' );

				echo sprintf(
					'<span class="entry-meta entry-date">' .
					'<a href="%s">%s</a></span>',
					esc_url( get_day_link( $archive_year, $archive_month, $archive_day ) ),
					$time_string
				);

				break;
		}
	}

	echo '</div>';
}

/**
 * Get single entry meta
 *
 * @since  1.0
 *
 */
function gadgetexpress_single_entry_meta() {
	$fields = (array) gadgetexpress_get_option( 'post_entry_meta' );

	if ( empty ( $fields ) ) {
		return;
	}

	echo '<div class="entry-metas">';

	foreach ( $fields as $field ) {
		switch ( $field ) {

			case 'author':
				echo sprintf(
					'<span class="entry-meta entry-author">' .
					'<label>%s</label>' .
					'<a class="url fn n" href="%s">%s</a></span>',
					esc_html__( 'Author:', 'gadget' ),
					esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
					esc_html( get_the_author() )
				);

				break;

			case 'date':
				$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

				$time_string = sprintf(
					$time_string,
					esc_attr( get_the_date( 'c' ) ),
					esc_html( get_the_date() )
				);

				$archive_year  = get_the_time( 'Y' );
				$archive_month = get_the_time( 'm' );
				$archive_day   = get_the_time( 'd' );

				echo sprintf(
					'<span class="entry-meta entry-date">' .
					'<label>%s</label>' .
					'<a href="%s">%s</a></span>',
					esc_html__( 'Published in:', 'gadget' ),
					esc_url( get_day_link( $archive_year, $archive_month, $archive_day ) ),
					$time_string
				);

				break;
		}
	}

	echo '</div>';
}

/**
 * Entry single portfolio
 */
function gadgetexpress_entry_meta_single_portfolio() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

	$time_string = sprintf(
		$time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() )
	);

	$archive_year  = get_the_time( 'Y' );
	$archive_month = get_the_time( 'm' );
	$archive_day   = get_the_time( 'd' );

	echo '<div class="entry-metas">';

	echo sprintf(
		'<span class="entry-meta entry-date">' .
		'<a href="%s">%s</a></span>',
		esc_url( get_day_link( $archive_year, $archive_month, $archive_day ) ),
		$time_string
	);

	$category = get_the_terms( get_the_ID(), 'portfolio_category' );

	if ( ! is_wp_error( $category ) && $category ) {
		echo sprintf( '<a href="%s" class="entry-meta entry-cat">%s</a>', esc_url( get_term_link( $category[0], 'portfolio_category' ) ), esc_html( $category[0]->name ) );
	}

	echo '</div>';
}

/**
 * Print HTML of language switcher
 * It requires plugin WPML installed
 */

if ( ! function_exists( 'gadgetexpress_language_switcher' ) ) :
	function gadgetexpress_language_switcher( $show_name = false ) {
		$language_dd = '';
		if ( function_exists( 'icl_get_languages' ) ) {
			$languages = icl_get_languages();
			if ( $languages ) {
				$lang_list = array();
				$current   = '';
				foreach ( (array) $languages as $code => $language ) {
					if ( ! $language['active'] ) {
						$lang_list[] = sprintf(
							'<li class="%s"><a href="%s">%s</a></li>',
							esc_attr( $code ),
							esc_url( $language['url'] ),
							$show_name ? esc_html( $language['translated_name'] ) : esc_html( $code )
						);
					} else {
						$current = $language;
						array_unshift(
							$lang_list, sprintf(
								'<li class="active %s"><a href="%s">%s</a></li>',
								esc_attr( $code ),
								esc_url( $language['url'] ),
								$show_name ? esc_html( $language['translated_name'] ) : esc_html( $code )
							)
						);
					}
				}

				$language_dd = sprintf(
					'<span class="current">%s<span class="toggle-children i-icon arrow_carrot-down"></span></span>' .
					'<ul>%s</ul>',
					$show_name ? esc_html( $current['translated_name'] ) : esc_html( $current['language_code'] ),
					implode( "\n\t", $lang_list )
				);
			}
		}

		return $language_dd;
		?>

		<?php
	}
endif;

/**
 * Print HTML of currency switcher
 * It requires plugin WooCommerce Currency Switcher installed
 */
if ( ! function_exists( 'gadgetexpress_currency_switcher' ) ) :
	function gadgetexpress_currency_switcher( $show_desc = false ) {
		$currency_dd = '';
		if ( class_exists( 'WOOCS' ) ) {
			global $WOOCS;

			$key_cur = 'name';
			if ( $show_desc ) {
				$key_cur = 'description';
			}

			$currencies    = $WOOCS->get_currencies();
			$currency_list = array();
			foreach ( $currencies as $key => $currency ) {
				if ( $WOOCS->current_currency == $key ) {
					array_unshift(
						$currency_list, sprintf(
							'<li class="actived"><a href="#" class="woocs_flag_view_item woocs_flag_view_item_current" data-currency="%s">%s</a></li>',
							esc_attr( $currency['name'] ),
							esc_html( $currency[ $key_cur ] )
						)
					);
				} else {
					$currency_list[] = sprintf(
						'<li><a href="#" class="woocs_flag_view_item" data-currency="%s">%s</a></li>',
						esc_attr( $currency['name'] ),
						esc_html( $currency[ $key_cur ] )
					);
				}
			}

			$currency_dd = sprintf(
				'<span class="current">%s<span class="toggle-children i-icon arrow_carrot-down"></span></span>' .
				'<ul>%s</ul>',
				$currencies[ $WOOCS->current_currency ][ $key_cur ],
				implode( "\n\t", $currency_list )
			);


		}

		return $currency_dd;
	}

endif;

/**
 * Get socials
 *
 * @since  1.0.0
 *
 *
 * @return string
 */
function gadgetexpress_get_socials() {
	$socials = array(
		'facebook'   => esc_html__( 'Facebook', 'gadget' ),
		'twitter'    => esc_html__( 'Twitter', 'gadget' ),
		'google'     => esc_html__( 'Google', 'gadget' ),
		'tumblr'     => esc_html__( 'Tumblr', 'gadget' ),
		'flickr'     => esc_html__( 'Flickr', 'gadget' ),
		'vimeo'      => esc_html__( 'Vimeo', 'gadget' ),
		'youtube'    => esc_html__( 'Youtube', 'gadget' ),
		'linkedin'   => esc_html__( 'LinkedIn', 'gadget' ),
		'pinterest'  => esc_html__( 'Pinterest', 'gadget' ),
		'dribbble'   => esc_html__( 'Dribbble', 'gadget' ),
		'spotify'    => esc_html__( 'Spotify', 'gadget' ),
		'instagram'  => esc_html__( 'Instagram', 'gadget' ),
		'tumbleupon' => esc_html__( 'Tumbleupon', 'gadget' ),
		'wordpress'  => esc_html__( 'WordPress', 'gadget' ),
		'rss'        => esc_html__( 'Rss', 'gadget' ),
		'deviantart' => esc_html__( 'Deviantart', 'gadget' ),
		'share'      => esc_html__( 'Share', 'gadget' ),
		'skype'      => esc_html__( 'Skype', 'gadget' ),
		'behance'    => esc_html__( 'Behance', 'gadget' ),
		'apple'      => esc_html__( 'Apple', 'gadget' ),
		'yelp'       => esc_html__( 'Yelp', 'gadget' ),
	);

	$socials = apply_filters( 'gadgetexpress_header_socials', $socials );

	return $socials;
}

// Rating reviews

function gadgetexpress_rating_stars( $score ) {
	$score     = min( 10, abs( $score ) );
	$full_star = $score / 2;
	$half_star = $score % 2;
	$stars     = array();

	for ( $i = 1; $i <= 5; $i ++ ) {
		if ( $i <= $full_star ) {
			$stars[] = '<i class="fa fa-star"></i>';
		} elseif ( $half_star ) {
			$stars[]   = '<i class="fa fa-star-half-o"></i>';
			$half_star = false;
		} else {
			$stars[] = '<i class="fa fa-star-o"></i>';
		}
	}

	echo join( "\n", $stars );
}

/**
 * Check is blog
 *
 * @since  1.0
 */

if ( ! function_exists( 'gadgetexpress_is_blog' ) ) :
	function gadgetexpress_is_blog() {
		if ( ( is_archive() || is_author() || is_category() || is_home() || is_tag() ) && 'post' == get_post_type() ) {
			return true;
		}

		return false;
	}

endif;

/**
 * Check is catalog
 *
 * @return bool
 */
if ( ! function_exists( 'gadgetexpress_is_catalog' ) ) :
	function gadgetexpress_is_catalog() {

		if ( function_exists( 'is_shop' ) && ( is_shop() || is_product_category() || is_product_tag() ) ) {
			return true;
		}

		return false;
	}
endif;

/**
 * Check is portfolio
 *
 * @since  1.0
 */

if ( ! function_exists( 'gadgetexpress_is_portfolio' ) ) :
	function gadgetexpress_is_portfolio() {
		if ( is_post_type_archive( 'portfolio' ) || is_tax( 'portfolio_category' ) ) {
			return true;
		}

		return false;
	}
endif;

/**
 * Check is homepage
 *
 * @since  1.0
 */

if ( ! function_exists( 'gadgetexpress_is_home' ) ) :
	function gadgetexpress_is_home() {
		if ( is_page_template( 'template-homepage.php' ) ||
		     is_page_template( 'template-home-boxed.php' ) ||
		     is_page_template( 'template-home-left-sidebar.php' ) ||
		     is_page_template( 'template-home-no-footer.php' )
		) {
			return true;
		}

		return false;
	}

endif;

/**
 * Check is homepage
 *
 * @since  1.0
 */

if ( ! function_exists( 'gadgetexpress_is_page_template' ) ) :
	function gadgetexpress_is_page_template() {
		if ( is_page_template( 'template-fullwidth.php' ) ||
		     is_page_template( 'template-coming-soon-page.php' ) ||
		     gadgetexpress_is_home()
		) {
			return true;
		}

		return false;
	}

endif;

/**
 *  Check Header Transparent
 */

if ( ! function_exists( 'gadgetexpress_header_transparent' ) ) :

	function gadgetexpress_header_transparent() {
		$header_transparent  = gadgetexpress_get_option( 'header_transparent' );
		$custom_header       = gadgetexpress_get_post_meta( 'custom_header' );
		$disable_transparent = gadgetexpress_get_post_meta( 'disable_header_transparent' );

		if ( is_page_template( 'template-home-left-sidebar.php' ) ) {
			return false;
		}

		if ( gadgetexpress_is_catalog() || ( function_exists( 'is_product' ) && is_product() ) ) {
			return false;
		}

		if ( intval( $header_transparent ) ) {
			if ( $custom_header && $disable_transparent ) {
				return false;
			}

			$page_header    = gadgetexpress_get_page_header();
			$page_header_bg = $page_header['bg_image'];

			if ( $page_header_bg ) {
				return true;
			}

			if ( gadgetexpress_is_home() ) {
				return true;
			}
		}

		return false;
	}

endif;

/**
 * Check Header Sticky
 */

/**
 * show taxonomy filter
 *
 * @return string
 */

if ( ! function_exists( 'gadgetexpress_header_sticky' ) ) :
	function gadgetexpress_header_sticky() {
		$header_sticky         = gadgetexpress_get_option( 'header_sticky' );
		$custom_header         = gadgetexpress_get_post_meta( 'custom_header' );
		$disable_header_sticky = gadgetexpress_get_post_meta( 'disable_header_sticky' );

		if ( is_page_template( 'template-home-left-sidebar.php' ) ) {
			return false;
		}

		if ( intval( $header_sticky ) ) {
			if ( $custom_header && $disable_header_sticky ) {
				return false;
			}

			return true;
		}

		return false;
	}

endif;

/**
 * show taxonomy filter
 *
 * @return string
 */

if ( ! function_exists( 'gadgetexpress_taxs_list' ) ) :
	function gadgetexpress_taxs_list( $taxonomy = 'category' ) {

		$term_id   = 0;
		$cats      = $output = '';
		$found     = false;
		$number    = intval( gadgetexpress_get_option( 'blog_categories_numbers' ) );
		$cats_slug = wp_kses_post( gadgetexpress_get_option( 'blog_categories' ) );
		$id        = 'gadgetexpress-taxs-list';

		if ( is_tax( $taxonomy ) || is_category() ) {

			$queried_object = get_queried_object();
			if ( $queried_object ) {
				$term_id = $queried_object->term_id;
			}
		}

		if ( $cats_slug ) {
			$cats_slug = explode( ',', $cats_slug );

			foreach ( $cats_slug as $slug ) {
				$cat = get_term_by( 'slug', $slug, $taxonomy );

				if ( $cat ) {
					$cat_selected = '';
					if ( $cat->term_id == $term_id ) {
						$cat_selected = 'selected';
						$found        = true;
					}

					$cats .= sprintf( '<li><a href="%s" class="%s">%s</a></li>', esc_url( get_term_link( $cat ) ), esc_attr( $cat_selected ), esc_html( $cat->name ) );
				}
			}

		} else {
			$args = array(
				'number'  => $number,
				'orderby' => 'count',
				'order'   => 'DESC',

			);

			$categories = get_terms( $taxonomy, $args );
			if ( ! is_wp_error( $categories ) && $categories ) {
				foreach ( $categories as $cat ) {
					$cat_selected = '';
					if ( $cat->term_id == $term_id ) {
						$cat_selected = 'selected';
						$found        = true;
					}

					$cats .= sprintf( '<li><a href="%s" class="%s">%s</a></li>', esc_url( get_term_link( $cat ) ), esc_attr( $cat_selected ), esc_html( $cat->name ) );
				}
			}
		}

		$cat_selected = $found ? '' : 'selected';

		$text = esc_html__( 'All Categories', 'gadget' );

		if ( gadgetexpress_is_portfolio() ) {
			$text = esc_html__( 'All', 'gadget' );
		}

		if ( $cats ) {
			$url = get_page_link( get_option( 'page_for_posts' ) );
			if ( 'posts' == get_option( 'show_on_front' ) ) {
				$url = home_url();
			} elseif ( gadgetexpress_is_catalog() ) {
				$url = get_permalink( wc_get_page_id( 'shop' ) );
			} elseif ( gadgetexpress_is_portfolio() ) {
				$url = get_post_type_archive_link( 'portfolio' );
			}

			$output = sprintf(
				'<ul>
					<li><a href="%s" class="%s">%s</a></li>
					 %s
				</ul>',
				esc_url( $url ),
				esc_attr( $cat_selected ),
				$text,
				$cats
			);
		}

		if ( $output ) {
			$output = apply_filters( 'gadgetexpress_tax_html', $output );

			printf( '<div id="%s" class="gadgetexpress-taxs-list">%s</div>', esc_attr( $id ), $output );
		}
	}

endif;


/**
 * Get blog description
 *
 * @since  1.0
 */

if ( ! function_exists( 'gadgetexpress_blog_description' ) ) :
	function gadgetexpress_blog_description() {
		if ( ! gadgetexpress_is_blog() ) {
			return;
		}

		$blog_text = gadgetexpress_get_option( 'blog_page_header_subtitle' );

		if ( is_category() ) {
			$blog_text = category_description();
		}

		if ( is_tag() ) {
			$blog_text = tag_description();
		}

		if ( empty( $blog_text ) ) {
			return;
		}

		printf( '<h4>%s</h4>', wp_kses_post( $blog_text ) );
	}

endif;

/**
 * Get blog meta
 *
 * @since  1.0
 *
 * @return string
 */
function gadgetexpress_get_post_meta( $meta ) {

	if ( is_home() && ! is_front_page() ) {
		$post_id = get_queried_object_id();

		return get_post_meta( $post_id, $meta, true );
	}

	if ( function_exists( 'is_shop' ) && is_shop() ) {
		$post_id = intval( get_option( 'woocommerce_shop_page_id' ) );

		return get_post_meta( $post_id, $meta, true );
	}

	if ( is_post_type_archive( 'portfolio_project' ) ) {
		$post_id = intval( get_option( 'drf_portfolio_page_id' ) );

		return get_post_meta( $post_id, $meta, true );
	}

	if ( ! is_singular() || is_singular( 'product' ) ) {
		return false;
	}

	return get_post_meta( get_the_ID(), $meta, true );

}

/**
 * Get page header layout
 *
 * @return array
 */

if ( ! function_exists( 'gadgetexpress_get_page_header' ) ) :
	function gadgetexpress_get_page_header() {
		if ( is_singular( 'product' ) || is_singular( 'post' ) || is_404() ) {
			return false;
		}

		if ( gadgetexpress_get_post_meta( 'hide_page_header' ) ) {
			return false;
		}

		$page_header = array(
			'layout'     => 1,
			'bg_image'   => '',
			'parallax'   => false,
			'text_color' => 'dark'
		);

		if ( gadgetexpress_is_blog() ) {
			if ( ! intval( gadgetexpress_get_option( 'blog_page_header' ) ) ) {
				return false;
			}

			$pg_layout             = gadgetexpress_get_option( 'blog_page_header_layout' );
			$page_header['layout'] = $pg_layout;
			if ( in_array( $pg_layout, array( '2', '3' ) ) ) {
				$page_header['bg_image']   = gadgetexpress_get_option( 'blog_page_header_background' );
				$page_header['parallax']   = intval( gadgetexpress_get_option( 'blog_page_header_parallax' ) );
				$page_header['text_color'] = gadgetexpress_get_option( 'blog_page_header_text_color' );
			}

		} elseif ( is_page() ) {
			if ( ! intval( gadgetexpress_get_option( 'page_header' ) ) ) {
				return false;
			}

			$bg_image   = gadgetexpress_get_option( 'page_header_background' );
			$parallax   = intval( gadgetexpress_get_option( 'page_header_parallax' ) );
			$text_color = gadgetexpress_get_option( 'page_header_text_color' );

			if ( get_post_meta( get_the_ID(), 'page_header_custom_layout', true ) ) {
				if ( $custom_bg = get_post_meta( get_the_ID(), 'page_bg', true ) ) {

					$bg_image = wp_get_attachment_url( $custom_bg );
				}

				$parallax = intval( get_post_meta( get_the_ID(), 'parallax', true ) );
			}

			$page_header['bg_image']   = $bg_image;
			$page_header['parallax']   = $parallax;
			$page_header['text_color'] = $text_color;
		} elseif ( gadgetexpress_is_portfolio() ) {
			if ( ! intval( gadgetexpress_get_option( 'portfolio_page_header' ) ) ) {
				return false;
			}

			$page_header['bg_image']   = gadgetexpress_get_option( 'portfolio_page_header_background' );
			$page_header['parallax']   = intval( gadgetexpress_get_option( 'portfolio_page_header_parallax' ) );
			$page_header['text_color'] = gadgetexpress_get_option( 'portfolio_page_header_text_color' );
		}

		return $page_header;
	}

endif;


/**
 * Get breadcrumbs
 *
 * @since  1.0.0
 *
 * @return string
 */

if ( ! function_exists( 'gadgetexpress_get_breadcrumbs' ) ) :
	function gadgetexpress_get_breadcrumbs() {
		if ( gadgetexpress_is_blog() ) {
			if ( ! intval( gadgetexpress_get_option( 'blog_page_header_breadcrumbs' ) ) ) {
				return;
			}

		} elseif ( gadgetexpress_is_catalog() ) {
			if ( ! intval( gadgetexpress_get_option( 'catalog_page_header_breadcrumbs' ) ) ) {
				return;
			}

		} elseif ( gadgetexpress_is_portfolio() ) {
			if ( ! intval( gadgetexpress_get_option( 'portfolio_breadcrumb' ) ) ) {
				return;
			}

		} else {
			if ( ! intval( gadgetexpress_get_option( 'page_header_breadcrumbs' ) ) ) {
				return;
			}

			if ( get_post_meta( get_the_ID(), 'hide_breadcrumbs', true ) ) {
				return;
			}
		}

		ob_start();
		?>
        <nav class="breadcrumbs">
			<?php
			gadgetexpress_breadcrumbs(
				array(
					'before'   => '',
					'taxonomy' => function_exists( 'is_woocommerce' ) && is_woocommerce() ? 'product_cat' : 'category',
				)
			);
			?>
        </nav>
		<?php
		echo ob_get_clean();
	}

endif;

/**
 * Get current page URL for layered nav items.
 * @return string
 */
if ( ! function_exists( 'gadgetexpress_get_page_base_url' ) ) :
	function gadgetexpress_get_page_base_url() {
		if ( defined( 'SHOP_IS_ON_FRONT' ) ) {
			$link = home_url();
		} elseif ( is_post_type_archive( 'product' ) || is_page( wc_get_page_id( 'shop' ) ) ) {
			$link = get_post_type_archive_link( 'product' );
		} elseif ( is_product_category() ) {
			$link = get_term_link( get_query_var( 'product_cat' ), 'product_cat' );
		} elseif ( is_product_tag() ) {
			$link = get_term_link( get_query_var( 'product_tag' ), 'product_tag' );
		} else {
			$queried_object = get_queried_object();
			$link           = get_term_link( $queried_object->slug, $queried_object->taxonomy );
		}

		return $link;
	}
endif;

/**
 * Get product video featured
 * @return string
 */
if ( ! function_exists( 'gadgetexpress_get_product_video' ) ) :
	function gadgetexpress_get_product_video() {
		global $product;
		$video_width  = intval( get_post_meta( $product->get_id(), 'video_width', true ) );
		$video_height = intval( get_post_meta( $product->get_id(), 'video_height', true ) );
		$video_url    = get_post_meta( $product->get_id(), 'video_url', true );
		$video_html   = '';
		// If URL: show oEmbed HTML
		if ( filter_var( $video_url, FILTER_VALIDATE_URL ) ) {
			$atts = array(
				'width'  => $video_width,
				'height' => $video_height
			);
			if ( $oembed = wp_oembed_get( $video_url, $atts ) ) {
				$video_html = $oembed;
			} else {
				$atts = array(
					'src'    => $video_url,
					'width'  => $video_width,
					'height' => $video_height
				);

				$video_html = wp_video_shortcode( $atts );
			}
		}

		if ( $video_html ) {
			$video_html = '<div class="video-wrapper">' . $video_html . '</div>';

			$video_html = sprintf(
				' <a href="#" data-href="%s" class="video-item-icon item-icon"><span>%s</span></a>',
				esc_attr( $video_html ),
				esc_html__( 'Play Video', 'gadget' )
			);

		}

		return $video_html;
	}
endif;


/**
 * Get product zoom
 * @return string
 */
if ( ! function_exists( 'gadgetexpress_get_product_zoom' ) ) :
	function gadgetexpress_get_product_zoom() {
		if ( in_array( gadgetexpress_get_option( 'single_product_layout' ), array( '3', '4', '5', '6' ) ) ) {
			return;
		}

		if ( ! intval( gadgetexpress_get_option( 'product_images_lightbox' ) ) ) {
			return;
		}

		return sprintf(
			'<a href="#" class="gallery-item-icon item-icon"><span>%s</span></a>',
			esc_html__( 'Click to enlarge', 'gadget' )
		);
	}
endif;