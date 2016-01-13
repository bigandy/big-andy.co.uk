<?php
function ah_cpts_init(){
	$contact_labels = array(
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
	// You can rewrite the slug on the front end by adding this to the key => Value on line 42 below.

	$contact_args = array(
		'labels' 				=> $contact_labels,
		'public' 				=> false,
		'publicly_queryable' 	=> false,
		'show_ui' 				=> true,
		'show_in_menu' 			=> true,
		'query_var' 			=> false,
		'rewrite' 				=> false, // You can use $rewrite VAR above here.
		'capability_type' 		=> 'post',
		'has_archive' 			=> false,
		'hierarchical' 			=> false,
		'menu_position' 		=> null,
		'menu_icon' 			=> 'dashicons-chart-area', // https://developer.wordpress.org/resource/dashicons/
		'supports' 				=> false,
		'map_meta_cap' 			=> true,

	);

	register_post_type( 'health', $contact_args );
}

/*
 * Initiate the custom post type.
 */
add_action( 'init', 'ah_cpts_init' );

function ah_change_weight_cpt_title( $data, $postarr ) {
	if ( $data['post_type'] === 'health' ) {
		if ( isset( $_POST['_ah_health_weight'] ) ) {
			$weight = $_POST['_ah_health_weight'];

			// die($weight);
			$data['post_title'] = $weight . 'kg - ' .  get_the_time( 'd/m/Y', $postarr['ID']);
		}
	}

	return $data;
}
add_filter( 'wp_insert_post_data', 'ah_change_weight_cpt_title', 99, 2 );



