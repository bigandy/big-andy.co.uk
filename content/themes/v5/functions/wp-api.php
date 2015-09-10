<?php
function ah_rest_prepare_post( $data, $post, $request ) {
	global $_wp_additional_image_sizes;

	if ( empty( $_wp_additional_image_sizes ) ) {
		return $sizes;
	}
	$_data = $data->data;
	$thumbnail_id = get_post_thumbnail_id( $post->ID );

	$featured_image_urls = [];
	foreach ( $_wp_additional_image_sizes as $size => $value ) {
		$thumbnail_info = wp_get_attachment_image_src( $thumbnail_id, $size );

		$featured_image_urls[str_replace( '-', '_', $size )] = $thumbnail_info[0];
	}

	$_data['featured_image_full_urls'] = $featured_image_urls;
	$data->data = $_data;
	return $data;
}
add_filter( 'rest_prepare_post', 'ah_rest_prepare_post', 10, 3 );

function ah_title_from_id( $data ) {
	$posts = get_posts( array(
		'author' => $data['id'],
	) );

	if ( empty( $posts ) ) {
		return new WP_Error( 'no_author', 'Invalid author', array( 'status' => 404 ) );
	}

	return $posts[0]->post_title;
}

add_action( 'rest_api_init', function () {
	register_rest_route( 'ah/v1', '/author/(?P<id>.+)', array(
		'methods' => 'GET',
		'callback' => 'ah_title_from_id',
		'args' => array(
			'id' => array(
				'validate_callback' => function ( $val ) { return is_numeric( $val ); },
			),
		),
		'permission_callback' => function () {
			return current_user_can( 'edit_others_posts' );
		}
	) );
} );
