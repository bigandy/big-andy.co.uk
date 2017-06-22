<?php
if ( $options['admin'] === "Y" ) {
	// Remove Comments from WordPress admin Bar
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

	// Hide Links Admin Menu
	if ( ! function_exists( 'ah_remove_menu_pages' ) ) {
		function ah_remove_menu_pages() {
			remove_menu_page( 'link-manager.php' );
		}
		add_action( 'admin_menu', 'ah_remove_menu_pages' );
	}

	/**
	 * Remove Items from the admin bar
	 *
	 * @param  WP_Admin_Bar $wp_admin_bar [description]
	 * @return [type]                     [description]
	 */
	function ah_admin_bar_remove_items( WP_Admin_Bar $wp_admin_bar ) {
		// bail if current user doesnt have cap
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// remove items from menu bar
		$remove_array = [
			'customize',
			'comments',
			'wp-logo',
			'wpseo-menu',
			'backwpup',
			'new-post',
			'new-media',
			'new-page',
			'new-user',
		];

		foreach ( $remove_array as $item ) {
			$wp_admin_bar->remove_node( $item );
		}
	}
	add_action( 'admin_bar_menu', 'ah_admin_bar_remove_items', 9999 );

	function ah_remove_menus() {
		global $menu;

		$restricted = [
			'Users',
			'Comments',
			'Tools',
			'Appearance',
			'Profile',
			'Media',
		];
		end( $menu );

		while ( prev( $menu ) ) {
			$value = explode( ' ', $menu[key( $menu )][0] );
			if ( in_array( $value[0] != NULL ? $value[0] : "" , $restricted ) ) {
				unset( $menu[key( $menu )] );
			}
		}

		remove_menu_page( 'wpseo_dashboard' );

    	remove_submenu_page( 'edit.php', 'edit-tags.php?taxonomy=category' );
		remove_submenu_page( 'edit.php', 'edit-tags.php?taxonomy=post_tag' );
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
 *
 */

function ba_remove_dashboard_widgets() {
	global $wp_meta_boxes;

	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press'] );
	unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links'] );
	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts'] );
	unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins'] );
	unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_drafts'] );
	unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments'] );
	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_primary'] );
	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary'] );
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


function ba_custom_admin_logo() {
  echo '<style>
  			:root {
				--red: #C1272D;
			}

          	#login h1 a {
				background-image: none;
				background-color: var(--red);
				display: flex;
				justify-content: center;
				align-items: center;
				padding: 1em;
				clip-path: polygon(30% 0%, 70% 0%, 100% 30%, 100% 70%, 70% 100%, 30% 100%, 0% 70%, 0% 30%);
				color: white;
				font-weight: bold;
				text-indent: 0;
  			}

  			#login [type="submit"] {
				background-color: var(--red);
				border-color: transparent;
				box-shadow: none;
				border-radius: 0;
				text-shadow: none;
  			}

  			#login input:focus {
  				box-shadow: none;
  				border-color: var(--red);
  			}
        </style>';
}
add_action( 'login_enqueue_scripts', 'ba_custom_admin_logo' );
