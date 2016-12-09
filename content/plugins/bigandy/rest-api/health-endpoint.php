<?php

add_action( 'rest_api_init', 'ah_register_health_endpoint' );
function ah_register_health_endpoint() {
	// Add deep-thoughts/v1/get-all-post-ids route
	register_rest_route( 'bigandy/v1', '/health/', array(
		'methods' => 'GET',
		'callback' => 'ah_get_all_weights',
	) );
}

// Return all post IDs
function ah_get_all_weights() {
	// if ( false === ( $health_data_array = get_transient( 'ah_all_health_data' ) ) ) {

	//     // cache for 2 hours
	//     set_transient( 'ah_all_health_data', $health_data_array, 60 * 60 * 2 );
	// }

	$health_data_array = ah_get_weights();

	return $health_data_array;
}

function ah_get_weights() {
	$health_args = [
		'post_type' => 'health',
		'posts_per_page' => -1,
		'orderby' => 'date',
		'order' => 'ASC',
	];

	$health_data = new WP_Query( $health_args );
	$health_data_array = [];

	if ( $health_data->have_posts() ) {
		while ( $health_data->have_posts() ) {
			$health_data->the_post();

			$post = $health_data->post;
			$post_id = $post->ID;

			$comments = get_post_meta( $post_id, '_ah_health_comments', true );
			$weight = get_post_meta( $post_id, '_ah_health_weight', true );

			$health_data_array[] = [
				'date' => get_the_date( 'd.m.Y', $post_id ),
				'weight' => ( $weight ) ? $weight : null,
				'comments' => ( $comments ) ? $comments : null,
			];
			// the_title();
		}
	}
	wp_reset_postdata();

	return $health_data_array;
}
