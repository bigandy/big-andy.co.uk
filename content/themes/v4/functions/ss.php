<?php
function ba_enQ_scripts() {
	$r = get_stylesheet_directory_uri() . '/build/js/';

	// wp_register_script( 'fmodernizr', $r . 'vendor/custom.modernizr.js', null, null, true);
	wp_register_script( 'main', $r . 'script.min.js', false, null, true);

	wp_deregister_script('jquery');
	// wp_register_script('jquery', "//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js", false, null, true);
	// wp_enqueue_script('jquery');
	wp_enqueue_script('main');

	wp_register_style( 'main', get_stylesheet_uri() );
	wp_enqueue_style( 'main' );

	if (is_page_template('templates/template-picture.php')) {
		wp_register_script( 'picturefill', $r . 'picturefill.min.js', false, null, true);
		wp_enqueue_script('picturefill');
		// wp_register_script( $handle, $src, $deps, $ver, $in_footer );

	}
}
add_action( 'wp_enqueue_scripts', 'ba_enQ_scripts' );


