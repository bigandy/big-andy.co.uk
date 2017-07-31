<?php
function ah_enqueue_scripts() {
	// $build = TEMPLATEURI . 'build/js/';
	//
	// if ( ! is_front_page() ) {
	// 	if ( is_page_template( 'templates/template-picture.php' ) || is_singular() ) {
	// 		wp_register_script( 'singular', $build . 'singular.min.js', false, null, true );
	// 		wp_enqueue_script( 'singular' );
	// 	}
	// }
	//
	// // don't want the script on anywhere
	// wp_deregister_script( 'wp-embed' );
	//
	// if ( is_user_logged_in() ) {
	// 	wp_register_script( 'main', $build . 'script.min.js', false, null, true );
	// 	wp_enqueue_script( 'main' );
	// }

	// Add stylesheet
	wp_enqueue_style( 'style', get_stylesheet_uri() );

}
add_action( 'wp_enqueue_scripts', 'ah_enqueue_scripts' );
