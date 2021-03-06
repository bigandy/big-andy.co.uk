<?php
function ah_md5_file( $file, $prefix ) {
	$substr = substr( md5( $file . $prefix ), 0, 10 );

	if ( '' !== $substr ) {
		$substr = '.' . $substr;
	}

	return $file . $substr . $prefix;
}

function ah_enqueue_scripts() {
	$build = TEMPLATEURI . 'build/js/';

	if ( ! is_front_page() ) {
		if ( is_page_template( 'templates/template-picture.php' ) || is_singular() ) {
			wp_register_script( 'singular', $build . 'singular.min.js', false, null, true );
			wp_enqueue_script( 'singular' );
		}
	}

	// don't want the script on front-page or pages.
	wp_deregister_script( 'wp-embed' );
	wp_deregister_style( 'wp-blocks' );

	if ( is_user_logged_in() ) {
		wp_register_script( 'main', ah_md5_file( TEMPLATEURI . 'build/js/script', '.js' ), false, null, true );
		wp_enqueue_script( 'main' );
	}

}
add_action( 'wp_enqueue_scripts', 'ah_enqueue_scripts' );
