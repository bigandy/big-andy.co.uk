<?php
function ah_rest_prepare_post( $data, $post, $request ) {
	$_data = $data->data;
	$thumbnail_id = get_post_thumbnail_id( $post->ID );
	$thumbnail = wp_get_attachment_image_src( $thumbnail_id, 'full' );

	$_data['featured_image_full_url'] = $thumbnail[0];
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
