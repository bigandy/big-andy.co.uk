<?php

if ( $options['admin'] == "Y" ) {
	function ah_admin_bar_render() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu( 'comments' );
	}
	add_action( 'wp_before_admin_bar_render', 'ah_admin_bar_render' );



	function ah_change_admin_cap() {

		$role = get_role( 'administrator' );

		$role->add_cap( 'upload_files' );
		$role->add_cap( 'manage_galleries' );
		$role->remove_cap( 'export' );
		$role->remove_cap( 'import' );
		$role->remove_cap( 'manage_links' );
		$role->remove_cap( 'moderate_comments' );
		$role->remove_cap( 'edit_comments' );
		$role->remove_cap( 'create_users' );
		$role->remove_cap( 'list_users' );
		$role->remove_cap( 'add_users' );
		$role->remove_cap( 'remove_users' );
		$role->remove_cap( 'promote_users' );
	}

	add_action( 'admin_init', 'ah_change_admin_cap' );

	function ah_remove_menus() {
		global $menu;

		$restricted = array(
			__( 'Users' ),
			__( 'Comments' ),
			__( 'Pages' ),
			__( 'Tools' ),
			__( 'Appearance' ),
			__( 'Profile' ),
			__( 'Media' ),
		);
		end( $menu );
		while ( prev( $menu ) ) {
			$value = explode( ' ', $menu[key( $menu )][0] );
			if ( in_array( $value[0] != NULL ? $value[0] : "" , $restricted ) ) {
				unset( $menu[key( $menu )] );
			}
		}

		remove_menu_page( 'wpseo_dashboard' );
	}
	add_action( 'admin_menu', 'ah_remove_menus' );

} else {
	function ah_change_admin_cap() {

		$role = get_role( 'administrator' );

		$role->add_cap( 'upload_files' );
		$role->add_cap( 'manage_galleries' );
		$role->add_cap( 'export' );
		$role->add_cap( 'import' );
		$role->add_cap( 'manage_links' );
		$role->add_cap( 'moderate_comments' );
		$role->add_cap( 'edit_comments' );
		$role->add_cap( 'create_users' );
		$role->add_cap( 'list_users' );
		$role->add_cap( 'add_users' );
		$role->add_cap( 'remove_users' );
		$role->add_cap( 'promote_users' );
	}

	add_action( 'admin_init', 'ah_change_admin_cap' );
}


/**
 * function ba_remove_dashboard_widgets()
 *
 * Remove Dashboard Widgets from Admin Area
 *
 * Reference: http://www.wpbeginner.com/wp-tutorials/how-to-remove-wordpress-dashboard-widgets/
 * Reference: http://codex.wordpress.org/Dashboard_Widgets_API
 *
 * @package WordPress
 * @since 2.7
 *
 */

function ba_remove_dashboard_widgets() {
	global $wp_meta_boxes;

	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_drafts']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
}
add_action('wp_dashboard_setup', 'ba_remove_dashboard_widgets' );

/**
 * function ba_replace_howdy()
 *
 */

function ba_replace_howdy( $wp_admin_bar ) {
    $my_account=$wp_admin_bar->get_node( 'my-account' );
    $newtitle = str_replace( 'Howdy,', 'Logged in as', $my_account->title );
    $wp_admin_bar->add_node(
    	[
	        'id' => 'my-account',
	        'title' => $newtitle,
    	]
    );
}
add_filter( 'admin_bar_menu', 'ba_replace_howdy', 25 );
