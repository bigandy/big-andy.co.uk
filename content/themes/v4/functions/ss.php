<?php
function ah_enqueue_scripts() {
	$build = TEMPLATEURI . 'build/js/';

	if ( ! is_front_page() ) {
		if ( is_page_template( 'templates/template-picture.php' ) || is_singular() ) {
			wp_register_script( 'picturefill', $build . 'picturefill.min.js', false, null, true );
			wp_enqueue_script( 'picturefill' );
		}
	}


	// if ( is_admin() ) {
	// 	wp_enqueue_script( 'jquery' );
	// }
}
add_action( 'wp_enqueue_scripts', 'ah_enqueue_scripts' );
