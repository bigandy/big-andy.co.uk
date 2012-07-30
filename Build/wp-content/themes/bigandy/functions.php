<?php 

// This theme uses wp_nav_menu() in one location.
register_nav_menu( 'primary', __( 'Primary Menu', 'bigandy' ) );

// Add support for a variety of post formats
add_theme_support( 'post-formats', array( 'aside', 'link', 'gallery', 'status', 'quote', 'image' ) );



// http://www.wprecipes.com/how-to-remove-menus-in-wordpress-dashboard
	// see also : http://noeltock.com/wcuk12/

function remove_menus () {
global $menu;
	$restricted = array( __('Media'), __('Links'), __('Tools'), __('Users'), __('Comments'), __('Plugins'));
	end ($menu);
	while (prev($menu)){
		$value = explode(' ',$menu[key($menu)][0]);
		if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
	}
}
add_action('admin_menu', 'remove_menus');