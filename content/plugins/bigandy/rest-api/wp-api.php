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

// Register Custom Fields that are also Available via REST API
function ah_register_rest_fields() {
  register_rest_field(
    'health',
    '_ah_health_weight',
    array(
      'get_callback' => function ( $data ) {
        return get_post_meta( $data['id'], '_ah_health_weight', true );
      },
      'update_callback' => function ( $value, $post ) {
        $value = sanitize_text_field( $value );
        update_post_meta( $post->ID, '_ah_health_weight', wp_slash( $value ) );
      },
      'schema' => array(
        'description' => __( 'description for _ah_health_weight' ),
        'type'        => 'string'
      ),
    )
  );

  register_rest_field(
    'health',
    '_ah_health_comments',
    array(
      'get_callback' => function ( $data ) {
        return get_post_meta( $data['id'], '_ah_health_comments', true );
      },
      'update_callback' => function ( $value, $post ) {
        $value = sanitize_text_field( $value );
        update_post_meta( $post->ID, '_ah_health_comments', wp_slash( $value ) );
      },
      'schema' => array(
        'description' => __( 'description for _ah_health_weight' ),
        'type'        => 'string'
      ),
    )
  );
}
add_action( 'rest_api_init', 'ah_register_rest_fields' );

// Hide Users Endpoints
add_filter( 'rest_endpoints', function( $endpoints ) {
    if ( isset( $endpoints['/wp/v2/users'] ) ) {
        unset( $endpoints['/wp/v2/users'] );
    }
    if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) ) {
        unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
    }
    return $endpoints;
} );
