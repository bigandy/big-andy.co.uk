<?php
function ah_enqueue_scripts() {
	$build = TEMPLATEURI . 'build/js/';

	if ( ! is_front_page() ) {
		if ( is_page_template( 'templates/template-picture.php' ) || is_singular() ) {
			wp_register_script( 'singular', $build . 'singular.min.js', false, null, true );
			wp_enqueue_script( 'singular' );
		}
	}

	if ( is_user_logged_in() ) {
		wp_register_script( 'main', $build . 'script.min.js', false, null, true );
		wp_enqueue_script( 'main' );
	}

}
add_action( 'wp_enqueue_scripts', 'ah_enqueue_scripts' );

function ah_remove_emojis() {
	// remove emoji styles
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}
add_action( 'init', 'ah_remove_emojis' );
