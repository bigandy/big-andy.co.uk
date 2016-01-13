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

		$featured_image_urls[ str_replace( '-', '_', $size ) ] = $thumbnail_info[0];
	}

	$_data['featured_image_full_urls'] = $featured_image_urls;
	$data->data = $_data;
	return $data;
}
add_filter( 'rest_prepare_post', 'ah_rest_prepare_post', 10, 3 );


function ah_add_meta_to_json( $data, $post, $request ){
	$response_data = $data->get_data();

	if ( 'view' !== $request['context']  || is_wp_error( $data ) ) {
		return $data;
	}

	$weight = get_post_meta( $post->ID, '_ah_health_weight', true );

	if ( empty( $weight ) ) {
		$weight = '';
	}

	if (  'health' === $post->post_type  ) {
		$response_data['health_meta'] = array(
			'weight' => $weight,
		);
	}

	$data->set_data( $response_data );

	return $data;
}
add_filter( 'rest_prepare_health', 'ah_add_meta_to_json', 10, 3 );
