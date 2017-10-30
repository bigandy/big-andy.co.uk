<?php

$categories = array( 210, 200 );
// array( 'categories' => $categories );

// function ah_micropub_plugin_override( $input, $wp_args ) {
// 	$wp_args = array( 'categories' => $categories );
// 	// ah_preit( $input, $wp_args );
// }

// add_action( 'after_micropub', 'ah_micropub_plugin_override', 10, 2 );

function ah_after_micropub( $input, $args ) {
	$args = array(
		'post_category' => $categories,
		'post_type' => 'created by micropub',
	);
	return $args;
}

add_action( 'after_micropub', 'ah_after_micropub', 10, 2 );
