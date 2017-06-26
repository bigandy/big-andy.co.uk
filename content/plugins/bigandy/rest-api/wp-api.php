<?php
/**
 * Rest API Functionality
 *
 * @package WordPress
 * @subpackage bigandy
 * @since 1.0
 */

/**
 * Adds Featured Image sizes to Posts endpoint data.
 *
 * @param obj $data The data.
 * @param obj $post The post.
 */
function ah_rest_prepare_post( $data, $post ) {
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
add_filter( 'rest_prepare_post', 'ah_rest_prepare_post', 10, 2 );

/**
 * Register Custom Fields that are also Available via REST API
 */
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
				'type'        => 'string',
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
				'type'        => 'string',
			),
		)
	);
}
add_action( 'rest_api_init', 'ah_register_rest_fields' );

/**
 * Hide Users Endpoints
 *
 * @param obj $endpoints The existing WordPress endpoints.
 */
add_filter( 'rest_endpoints', function( $endpoints ) {
	if ( isset( $endpoints['/wp/v2/users'] ) ) {
		unset( $endpoints['/wp/v2/users'] );
	}
	if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) ) {
		unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
	}
	return $endpoints;
} );


/**
 * Adds wp-json/weight/v1/all/ endpoint with date, weight and comments information.
 * This avoids sending too much information and saves users and server bandwidth.
 */
function ah_register_weight_endpoints() {
	register_rest_route( 'weight/v1', '/all/', array(
		'methods' => 'GET',
		'callback' => 'ah_get_weight_data',
	) );
}
add_action( 'rest_api_init', 'ah_register_weight_endpoints' );

/**
 * Attach the data to the endpoint.
 */
function ah_get_weight_data() {
	$weight_data = get_transient( 'ah_weight_data' );

	// If There is no transient, grab the weight data and put into array.
	if ( false === $weight_data ) {
		$health_args = array(
			'post_type' => 'health',
			'nopaging' => true,
		);

		$health_loop = new WP_Query( $health_args );

		$weight_data = array();

		foreach( $health_loop->posts as $post ) {
			$custom = get_post_custom( $post->ID );

			array_push( $weight_data, array(
				'date' => $post->post_date,
				'weight' => $custom['_ah_health_weight'][0],
				'comments' => $custom['_ah_health_comments'][0],
				)
			);
		}

		// cache for 24 hours.
		set_transient( 'ah_weight_data', $weight_data, 60 * 60 * 24 );
	}

	return $weight_data;
}
