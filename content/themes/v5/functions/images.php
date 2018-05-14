<?php
add_image_size( 'pic-max', 1400, 9999, false );
add_image_size( 'pic-large', 1000, 9999, false );
add_image_size( 'pic-medium', 750, 9999, false );
add_image_size( 'pic-small', 500, 9999, false );
add_image_size( 'blog-listing', 300, 900, false );

/**
 * Adds the medium and the full to the image size list in the editor, so people can
 * only insert them into pages and articles.
 */
function ah_add_additional_image_sizes( $sizes ) {
	global $_wp_additional_image_sizes;
	if ( empty( $_wp_additional_image_sizes ) ) {
		return $sizes;
	}

	foreach ( $_wp_additional_image_sizes as $id => $data ) {
		if ( ! isset( $sizes[ $id ] ) ) {
			$sizes[ $id ] = ucfirst( str_replace( '-', ' ', $id ) );
		}
	}

	return $sizes;
}

add_filter( 'image_size_names_choose', 'ah_add_additional_image_sizes' );

/* Add html5 capability */
add_theme_support( 'html5',
	array(
		'gallery',
	)
);

add_theme_support( 'post-thumbnails' );


// Featured Image Cloudinary Support
add_filter( 'post_thumbnail_html', 'ah_modify_post_thumbnail_html', 99, 5 );
function ah_modify_post_thumbnail_html( $html, $post_id, $image_id, $sizing, $attr ) {
	$src = wp_get_attachment_image_src( $image_id, $sizing ); // gets the image url specific to the passed in size

	$thumb_src = str_replace( 'image/upload/', 'image/upload/f_auto,q_auto/', $src[0] );

	$html = '<img src="' . $thumb_src . '" />';

    return $html;
}
