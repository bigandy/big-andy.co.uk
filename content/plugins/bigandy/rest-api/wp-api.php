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


function ah_rest_prepare_health( $data, $post, $request ) {
	$post_meta = get_post_meta($post->ID);

	$post_id = $post->ID;

	$comments = get_post_meta( $post_id, '_ah_health_comments', true );
	$weight = get_post_meta( $post_id, '_ah_health_weight', true );

	$_data['_ah_health_comments'] = $weight ? $weight : null;
	$_data['_ah_health_weight'] = $comments ? $comments : null;
	$_data['date'] = get_the_date( 'd.m.Y', $post_id );

	$data->data += $_data;
	return $data;
}
add_filter( 'rest_prepare_health', 'ah_rest_prepare_health', 10, 3 );

add_action( 'rest_api_init', 'ah_register_weight' );
function ah_register_weight() {
	register_meta(
		'health',
		'_ah_health_weight',
		array(
			'show_in_rest' => true,
			'singular' => true,
		)
	);
	register_meta(
		'health',
		'_ah_health_comments',
		array(
			'show_in_rest' => true,
			'singular' => true,
		)
	);
  register_rest_field( 'health',
      '_ah_health_weight',
      array(
			'get_callback'    => 'ah_get_meta',
          'update_callback' => 'ah_update_meta',
          'schema'          => null,
      )
  );

	register_rest_field( 'health',
      '_ah_health_comments',
      array(
			'get_callback'    => 'ah_get_meta',
          'update_callback' => 'ah_update_meta',
          'schema'          => null,
      )
  );
}

function ah_get_meta( $object, $field_name, $request ) {
	return get_post_meta( $object[ 'id' ], $field_name )[0];
}

function ah_update_meta( $value, $object, $field_name ) {
  if ( ! $value ) {
      return;
  }

  return update_post_meta( $object->ID, $field_name, $value );
}

add_filter( 'rest_endpoints', function( $endpoints ){
    if ( isset( $endpoints['/wp/v2/users'] ) ) {
        unset( $endpoints['/wp/v2/users'] );
    }
    if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) ) {
        unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
    }
    return $endpoints;
});
