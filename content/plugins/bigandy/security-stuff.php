<?php
/* remove version numbers from end of css and js files
* http://wordpress.org/support/topic/get-rid-of-ver-on-the-end-of-cssjs-files#post-1892166
*/
function ah_remove_cssjs_ver( $src ) {
	if ( strpos( $src, '?ver=' ) )
		$src = remove_query_arg( 'ver', $src );
	return $src;
}
add_filter( 'style_loader_src', 'ah_remove_cssjs_ver', 10, 2 );
add_filter( 'script_loader_src', 'ah_remove_cssjs_ver', 10, 2 );

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

/* Hide WP version info from header */
remove_action( 'wp_head', 'wp_generator' );
