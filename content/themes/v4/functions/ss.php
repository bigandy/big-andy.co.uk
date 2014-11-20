<?php
function ah_enque_scripts() {
	$build = TEMPLATEURI . '/build/js/';

	// if the user is not logged in, show the google analytics script
	if ( ! is_user_logged_in() ) {
		wp_register_script( 'main', $build . 'script.min.js', false, null, true );
		wp_enqueue_script( 'main' );
	}

	if ( ! is_front_page() ) {
		if ( is_page_template( 'templates/template-picture.php' ) || is_singular() ) {
			wp_register_script( 'picturefill', $build . 'picturefill.min.js', false, null, true );
			wp_enqueue_script( 'picturefill' );
		}
	}
}
add_action( 'wp_enqueue_scripts', 'ah_enque_scripts' );
