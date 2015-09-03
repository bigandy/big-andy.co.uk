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

		$featured_image_urls[$size] = $thumbnail_info[0];
	}

	$_data['featured_image_full_urls'] = $featured_image_urls;
	$data->data = $_data;
	return $data;
}
add_filter( 'rest_prepare_post', 'ah_rest_prepare_post', 10, 3 );

function ah_rest_remove_garbage( $data, $post, $request ) {
	$_data = $data->data;

	$remove_array = [
		'id',
		'date',
		'guid',
		'modified',
		'modified_gmt',
		'slug',
		'type',
		'featured_image',
		'ping_status',
		'sticky',
		'format',
		'author',
		'comment_status',
	];

	foreach ( $remove_array as $remove ) {
		unset($_data[$remove]);
	}

	$data->data = $_data;
	return $data;
}
add_filter( 'rest_prepare_post', 'ah_rest_remove_garbage', 10, 3 );
