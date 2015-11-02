<?php

/* Hide WP version info from header */
remove_action( 'wp_head', 'wp_generator' );


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
