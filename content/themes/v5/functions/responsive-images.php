<?php
/**
 * ah_get_extra_thumbnail_sizes()
 * outputs non-standard wordpress image sizes.
 */

function ah_get_extra_thumbnail_sizes( $small_screen = 0 ) {
	global $_wp_additional_image_sizes;

	$sizes = array();
	$reduced_sizes = array();
	$all_sizes = get_intermediate_image_sizes();

	// check if there are additional image sizes
	if ( isset( $_wp_additional_image_sizes ) ) {
		foreach ( $all_sizes as $size ) {
			// compile array of image sizes beginning with pic-
			if ( 'pic-' === substr( $size, 0, 4 ) ) {
				// checks to see if we're on a singular page, then removes
				if ( 1 === $small_screen ) {
					// only adds pic-small and pic-medium to array
					if ( ! in_array( $size, array( 'pic-large', 'pic-max' ), true ) ) {
						array_push( $reduced_sizes, $size );
					}
				} else {
					array_push( $reduced_sizes, $size );
				}
			}
		}
	}

	// check that there are image sizes beginning with pic-
	if ( ! empty( $reduced_sizes ) ) {
		foreach ( $reduced_sizes as $s ) {
			$sizes[ $s ] = array();

			if ( isset( $_wp_additional_image_sizes[ $s ] ) ) {
				$sizes[ $s ] = $_wp_additional_image_sizes[ $s ]['width'];
			}
		}
	}
	return $sizes;
}

function ah_get_output_resp_image( $id, $class = '', $singular = false, $lazyload = false, $width = false, $height = false ) {
	$sizes = ah_get_extra_thumbnail_sizes( $singular );

	if ( $class ) {
		$image_class = ' class="' . $class . '"';
	} else {
		$image_class = '';
	}

	if ( $height ) {
		$output_height = 'height="' . $height . '"';
	} else {
		$output_height = '';
	}

	if ( $width ) {
		$output_width = 'width="' . $width . '"';
	} else {
		$output_width = '';
	}

	$fallback_thumb = wp_get_attachment_image_src( $id, 'large' );

	if ( false === $lazyload ) {
		$html = '<img src="' . $fallback_thumb[0] . '" srcset="';
	} else {
		$html = '<img data-src="' . $fallback_thumb[0] . '" data-srcset="';
	}

	$count = 0;
	foreach ( $sizes as $size => $key ) {
		$thumb = wp_get_attachment_image_src( $id, $size );

		$divider = ($count !== 0) ? ', '  : '';

		$html .= $divider .  $thumb[0] . ' ' . $key . 'w';
		$count++;
	}

	$html .= '" sizes="(min-width: 995px) 1000px, 100vw"' . $image_class . $output_height . $output_width;
	$html .= ' />';
	return $html;
}


function ah_output_resp_img( $id, $class = '' ) {
	echo balanceTags( ah_get_output_resp_image( $id, $class ) );
}

function ah_featured_resp_image_replacement( $class ) {
	$post_thumbnail_id = get_post_thumbnail_id();
	ah_output_resp_img( $post_thumbnail_id, $class );
}

/**
 * ah_picture_shortcode
 * Shortcode to utilise the id of the image
 */
function ah_picture_shortcode( $atts, $content ) {
	$atts = shortcode_atts( array(
		'id' => '',
	), $atts, 'picture' );

	if ( $atts['id'] ) {
		return ah_output_picture( $atts['id'] );
	} else {
		preg_match_all( '<img (.*?)class=\"((.*?)wp-image-(\d+)(.*?))\"(.*?)>', $content, $matches );

		foreach ( $matches[0] as $key => $imgstring ) {
			$picture_id = $matches[4][ $key ];
			$picture_class = $matches[2][ $key ];

			return ah_get_output_resp_image( $picture_id, $picture_class );
		}
	}
}
add_shortcode( 'picture', 'ah_picture_shortcode' );

function ah_replace_content_img_with_resp_image( $content ) {
	$template_picture = is_page_template( 'templates/template-picture.php' );
	$cat_picture = has_category( 'picture' );

	// if we're on a picture template, or single i.e. blog post page.
	if ( is_singular() ) {

		if ( $template_picture || $cat_picture ) {
			$small_screen = 0;
		} else {
			$small_screen = 1;
		}

		preg_match_all( '<img (.*?)width=\"((.*?)(\d+)(.*?))\"(.*?)(.*?)height=\"((.*?)(\d+)(.*?))\"(.*?)(.*?)class=\"((.*?)wp-image-(\d+)(.*?))\"(.*?)>', $content, $matches );

		foreach ( $matches[0] as $key => $imgstring ) {
			$id = $matches[16][ $key ];
			$class = $matches[15][ $key ];
			$height = $matches[8][ $key ];
			$width = $matches[4][ $key ];

			// the string with <img>
			$img = '<' . $matches[0][ $key ] . ' />';

			// string with <img srcset>
			$resp_img = ah_get_output_resp_image( $id, $class, $small_screen, true, $width, $height );

			// replace <img> with <img srcset...>
			$content = str_replace( $img, $resp_img, $content );
		}

		return $content;
	}
	return $content;
}
// make sure it is run before the picture shortcode runs
add_filter( 'the_content', 'ah_replace_content_img_with_resp_image', 11 );
