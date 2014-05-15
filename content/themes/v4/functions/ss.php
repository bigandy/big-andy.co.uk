<?php
function ba_enQ_scripts() {
	$r = get_stylesheet_directory_uri() . '/build/js/';

	// wp_register_script( 'fmodernizr', $r . 'vendor/custom.modernizr.js', null, null, true);
	wp_register_script( 'main', $r . 'script.min.js', array('jquery'), null, true);


	// wp_register_script( 'main', $r . 'build/app.min.js', null, null, true);
	wp_enqueue_script('jquery');
	wp_enqueue_script('main');

	wp_register_style( 'main', get_stylesheet_uri() );
	wp_enqueue_style( 'main' );
}
add_action( 'wp_enqueue_scripts', 'ba_enQ_scripts' );


