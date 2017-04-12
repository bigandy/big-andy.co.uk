<?php
// Button to refresh service Worker
function ba_refresh_sw_button( $wp_admin_bar ) {
	$args = array(
		'id' => 'refresh-service-worker',
		'title' => 'Refresh SW',
		'href' => '#',
	);

	$wp_admin_bar->add_node( $args );
}
add_action( 'admin_bar_menu', 'ba_refresh_sw_button', 50 );

add_action( 'wp_ajax_refresh_serviceworker', 'ah_add_serviceworker_in_root' );
