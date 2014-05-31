<?php

add_theme_support('post-thumbnails');

add_image_size('pic-max', 1400, 9999, false);
add_image_size('pic-large', 1000, 9999, false);
add_image_size('pic-medium', 750, 9999, false);
add_image_size('pic-small', 500, 9999, false);

/**
 * Adds the medium and the full to the image size list in the editor, so people can
 * only insert them into pages and articles.
 */
function ba_add_additional_image_sizes( $sizes ) {
	global $_wp_additional_image_sizes;
	if ( empty($_wp_additional_image_sizes) ) {
		return $sizes;
	}

	foreach ( $_wp_additional_image_sizes as $id => $data ) {
		if ( !isset($sizes[$id]) ) {
			$sizes[$id] = ucfirst( str_replace( '-', ' ', $id ) );
		}
	}

	return $sizes;
}

add_filter( 'image_size_names_choose', 'ba_add_additional_image_sizes' );


if (function_exists('picturefill_wp_add_image_size')) {
	picturefill_wp_add_image_size('size1', 1050, 999, false, 'medium');
	picturefill_wp_add_image_size('size2', 1550, 999, false, 'large');
	picturefill_wp_add_image_size('size3', 550, 999, false, 'small');
}

add_theme_support('html5', array( 'gallery' ));




function ba_get_extra_thumbnail_sizes(){
     global $_wp_additional_image_sizes;
     	$sizes = array();
 		foreach( get_intermediate_image_sizes() as $s ){
 			$sizes[ $s ] = array( 0, 0 );
 			if( in_array( $s, array( 'thumbnail', 'medium', 'large' ) ) ){
 				$sizes[ $s ] = get_option( $s . '_size_w' );
 			} else {
				if ( isset( $_wp_additional_image_sizes ) && isset( $_wp_additional_image_sizes[ $s ] ) ) {
	 					$sizes[ $s ] = $_wp_additional_image_sizes[ $s ]['width'];
				}
			}
		}

		// remove first 3 i.e. the default sizes 'thumbnail', 'medium', and 'large'
		$sliced_sizes = array_slice($sizes, 3);
 		// ba_preit($sliced_sizes);
 		// $sizes = array_shift($sizes);

 		return $sliced_sizes;
 }
