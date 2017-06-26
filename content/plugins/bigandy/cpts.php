<?php
function ah_cpts_init(){
	$health_labels = array(
		'name' 					=> _x( 'Health', 'post type general name' ),
		'singular_name' 		=> _x( 'Health', 'post type singular name' ),
		'add_new' 				=> _x( 'Add New', 'Health' ),
		'add_new_item' 			=> __( 'Add New ' . 'Health' ),
		'edit_item' 			=> __( 'Edit ' . 'Health' ),
		'new_item' 				=> __( 'New ' . 'Health' ),
		'view_item' 			=> __( 'View ' . 'Health' ),
		'search_items' 			=> __( 'Search ' . 'Health' ),
		'not_found' 			=> __( 'No ' . 'Health' . ' found' ),
		'not_found_in_trash' 	=> __( 'No ' . 'Health' . ' found in Trash' ),
		'parent_item_colon' 	=> '',
	);

	$health_args = array(
		'labels' 				=> $health_labels,
		'public' 				=> false,
		'publicly_queryable' 	=> false,
		'show_ui' 				=> true,
		'show_in_menu' 			=> true,
		'query_var' 			=> true,
		'rewrite' 				=> false,
		'capability_type' 		=> 'post',
		'has_archive' 			=> false,
		'hierarchical' 			=> false,
		'menu_position' 		=> null,
		'menu_icon' 			=> 'dashicons-chart-area', // https://developer.wordpress.org/resource/dashicons/
		'supports' 				=> [
									'custom-fields',
									'title',
								],
		'map_meta_cap' 			=> true,
		'show_in_rest'			=> true,
		// 'rest_controller_class' => 'AH_REST_Link_Controller',
	);

	register_post_type( 'health', $health_args );
}

/*
 * Initiate the custom post type.
 */
add_action( 'init', 'ah_cpts_init' );

function ah_change_weight_cpt_title( $data, $postarr ) {
	if ( 'health' === $data['post_type']  ) {
		if ( isset( $_POST['_ah_health_weight'] ) ) {
			$weight = $_POST['_ah_health_weight'];

			$data['post_title'] = $weight . 'kg - ' .  get_the_time( 'd/m/Y', $postarr['ID'] );
		}
	}

	return $data;
}
add_filter( 'wp_insert_post_data', 'ah_change_weight_cpt_title', 99, 2 );


function ah_register_weight_endpoints() {
	register_rest_route( 'weight/v1', '/all/', array(
		'methods' => 'GET',
		'callback' => 'ah_get_weight_data',
	) );
}
add_action( 'rest_api_init', 'ah_register_weight_endpoints' );

/**
 * Only output the weight data required.
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
