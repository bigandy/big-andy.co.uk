<?php

if ( $options['admin'] == "Y" ) {
	// http://www.wprecipes.com/how-to-remove-menus-in-wordpress-dashboard
	function ah_remove_menus() {
		global $menu;
		$restricted = array( __( 'Users' ), __( 'Comments' ) );
		end( $menu );
		while ( prev( $menu ) ) {
			$value = explode( ' ', $menu[key( $menu )][0] );
			if ( in_array( $value[0] != NULL?$value[0]:"" , $restricted ) ) {unset( $menu[key( $menu )] );}
		}
	}
	add_action( 'admin_menu', 'ah_remove_menus' );
	// remove comment moderation from admin bar
	// http://wpmu.org/how-to-add-or-remove-links-from-the-wordpress-3-1-admin-bar/
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
