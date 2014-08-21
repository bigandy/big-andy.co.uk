<?php
function ah_enque_scripts() {
	$build = get_stylesheet_directory_uri() . '/build/js/';

	wp_register_script( 'main', $build . 'script.min.js', false, null, true );

	wp_enqueue_script( 'main' );

	// wp_register_style( 'main', get_stylesheet_uri() );
	// wp_enqueue_style( 'main' );

	if ( ! is_front_page() ) {
		if ( is_page_template( 'templates/template-picture.php' ) || has_category( 'picture' ) ) {
			wp_register_script( 'picturefill', $build . 'picturefill.min.js', false, null, true );
			wp_enqueue_script( 'picturefill' );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'ah_enque_scripts' );
