<?php
/**
 * ah_get_extra_thumbnail_sizes()
 * outputs non-standard wordpress image sizes.
 */
function ah_get_extra_thumbnail_sizes() {
	global $_wp_additional_image_sizes;

	$sizes = array();
	$reduced_sizes = array();
	$all_sizes = get_intermediate_image_sizes();

	// check if there are additional image sizes
	if ( isset( $_wp_additional_image_sizes ) ) {
		foreach ( $all_sizes as $size ) {
			// compile array of image sizes beginning with pic-
			if ( 'pic-' === substr( $size, 0, 4 ) ) {
				array_push( $reduced_sizes, $size );
			}
		}
	}

	// check that there are image sizes beginning with pic-
	if ( ! empty( $reduced_sizes ) ) {
		foreach ( $reduced_sizes as $s ){
			$sizes[ $s ] = array();

			if ( isset( $_wp_additional_image_sizes[ $s ] ) ) {
				$sizes[ $s ] = $_wp_additional_image_sizes[ $s ]['width'];
			}
		}
	}
	return $sizes;
}

function ah_get_output_picture( $id, $class = '' ) {
	$sizes = ah_get_extra_thumbnail_sizes();

	if ( $class ) {
		$picture_class = 'class="' . $class . '"';
	} else {
		$picture_class = '';
	}

	$html = '<picture ' . $picture_class . '>';
		foreach ( $sizes as $size => $key ) {
			$thumb = wp_get_attachment_image_src( $id, $size );
			$html .= '<source media="(min-width: ' . $key . 'px)" srcset="' . $thumb[0] . '">';
		}

		$fallback_thumb = wp_get_attachment_image_src( $id, 'large' );
		$html .= '<img src="' . $fallback_thumb[0] . '" />';
	$html .= '</picture>';
	return $html;
}

function ah_output_picture( $id, $class = '' ) {
	echo ah_get_output_picture( $id, $class );
}

function ah_featured_picture_replacement() {
	$post_thumbnail_id = get_post_thumbnail_id();
	ah_output_picture( $post_thumbnail_id );
}

/**
 * ah_picture_shortcode
 * Shortcode to utilise the id of the image
 */
function ah_picture_shortcode( $atts, $content ) {
	extract( shortcode_atts( array(
		'id' => '',
	), $atts ) );

	if ( $id ) {
		return ah_output_picture( $id );
	} else {
		preg_match_all( '<img (.*?)class=\"((.*?)wp-image-(\d+)(.*?))\"(.*?)>', $content, $matches );

		foreach ( $matches[0] as $key => $imgstring ) {
			$id = $matches[4][ $key ];
			$class = $matches[2][ $key ];

			return ah_get_output_picture( $id, $class );
		}
	}
}
add_shortcode( 'picture', 'ah_picture_shortcode' );

function ah_replace_content_img_with_picture( $content ) {
	if ( is_page_template( 'templates/template-picture.php' ) || is_singular() ) {

		preg_match_all( '<img (.*?)class=\"((.*?)wp-image-(\d+)(.*?))\"(.*?)>', $content, $matches );

		foreach ( $matches[0] as $key => $imgstring ) {
			$id = $matches[4][ $key ];
			$class = $matches[2][ $key ];

			// the string with <img>
			$img = '<' . $matches[0][ $key ] . ' />';

			// string with <picture>
			$picture = ah_get_output_picture( $id );

			// replace <img> with <picture>
			$content = str_replace( $img, $picture, $content );

		}

		return $content;
	}
	return $content;
}
// make sure it is run before the picture shortcode runs
add_filter( 'the_content', 'ah_replace_content_img_with_picture', 11 );
