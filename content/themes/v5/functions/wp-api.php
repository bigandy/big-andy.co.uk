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
