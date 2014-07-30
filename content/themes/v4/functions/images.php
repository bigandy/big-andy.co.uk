<?php
add_image_size( 'pic-max', 1400, 9999, false );
add_image_size( 'pic-large', 1000, 9999, false );
add_image_size( 'pic-medium', 750, 9999, false );
add_image_size( 'pic-small', 500, 9999, false );

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
		if ( ! isset($sizes[ $id ]) ) {
			$sizes[ $id ] = ucfirst( str_replace( '-', ' ', $id ) );
		}
	}

	return $sizes;
}

add_filter( 'image_size_names_choose', 'ah_add_additional_image_sizes' );


// this is called only when the theme has been switched
function ah_after_theme_switch() {
	/* default option to not link to media */
	update_option( 'image_default_link_type', 'none' );

	/* Add html5 capability */
	add_theme_support( 'html5',
		array(
			'gallery',
		)
	);

	add_theme_support( 'post-thumbnails' );
}
add_action( 'after_switch_theme', 'ah_after_theme_switch' );
