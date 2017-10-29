<?php

// $categories = array( 210, 200 );
// array( 'categories' => $categories );

// function ah_micropub_plugin_override( $input, $wp_args ) {
// 	$wp_args = array( 'categories' => $categories );
// 	// ah_preit( $input, $wp_args );
// }

// add_action( 'after_micropub', 'ah_micropub_plugin_override' );

function ah_before_micropub( $input ) {
	return 'this cool' . $input;
}

add_action( 'before_micropub' 'ah_before_micropub', 1 );
