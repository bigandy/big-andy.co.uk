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
	register_rest_route( 'bigandy/v1', '/weight/', array(
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


/**
 * Adds wp-json/bigandy/v1/pages/ endpoint with date, weight and comments information.
 * This avoids sending too much information and saves users and server bandwidth.
 */
function ah_register_posts_pages_endpoints() {
	register_rest_route( 'bigandy/v1', '/posts-pages/', array(
		'methods' => 'GET',
		'callback' => 'ah_get_posts_pages_data',
	) );
}
add_action( 'rest_api_init', 'ah_register_posts_pages_endpoints' );

/**
 * Attach the data to the posts-pages endpoint.
 */
function ah_get_posts_pages_data() {
	$post_data = get_transient( 'ah_posts_data' );

	// If there is no transient, grab the posts data and put into array.
	if ( false === $post_data ) {
		$post_args = array(
			'post_type' 		=> 'post',
			'posts_per_page' 	=> 10,
		);

		$post_loop = new WP_Query( $post_args );

		$post_data = array();

		foreach( $post_loop->posts as $post ) {
			array_push(
				$post_data,
				array(
					'date' 	=> $post->post_date,
					'id' 	=> $post->ID,
					'link' 	=> get_the_permalink( $post->ID ),
					'content' => $post->post_content,
					'excerpt' 	=> $post->post_excerpt,
					'slug'		=> $post->post_name,
					'title'		=> $post->post_title,
				)
			);
		}
		wp_reset_postdata();

		// cache for 24 hours.
		set_transient( 'ah_posts_data', $post_data, 60 * 60 * 24 );
	}

	$page_data = get_transient( 'ah_pages_data' );

	// If there is no transient, grab the pages data and put into array.
	if ( false === $page_data ) {
		$page_args = array(
			'post_type' 		=> 'page',
		);

		$page_loop = new WP_Query( $page_args );

		$page_data = array();

		foreach( $page_loop->posts as $page ) {
			if ( 'style-guide' !== $page->post_name ) {
				array_push(
					$page_data,
					array(
						'date' 	=> $page->post_date,
						'id' 	=> $page->ID,
						'link' 	=> get_the_permalink( $page->ID ),
						'content' => $page->post_content,
						'excerpt' 	=> $page->post_excerpt,
						'slug'		=> $page->post_name,
						'title'		=> $page->post_title,
					)
				);
			}


		}
		wp_reset_postdata();

		// cache for 24 hours.
		set_transient( 'ah_pages_data', $page_data, 60 * 60 * 24 );
	}

	$result = array(
		'pages' => $page_data,
		'posts' => $post_data,
	);

	return $result;
}
