<?php 
/* 
* Plugin Name: bigandy functionality
* Version : 1.0
* Description : Shortcode for address, plus other stuff
*/

// [address] using microformats : http://microformats.org/code/hcard/creator
function ah_address_shortcode($atts){
 extract( shortcode_atts( array(
		'street' => '19 Cambridge Street',
		'town' => 'Reading',
		'county' => 'Berkshire',
		'postcode' => 'RG1 7PA',		
	), $atts ) );
 return '<div class="adr">
  <span class="street-address">'. $street .'</span>,
  <span class="locality">'. $town .'</span>,
  <span class="region">'. $county .'</span>, 
  <span class="postal-code">'. $postcode .'</span>
 </div>';
}
add_shortcode( 'address', 'ah_address_shortcode' );

// [telephone]
function ah_telephone_shortcode( $atts, $content = null ){
 	extract( shortcode_atts( array(
		'telephone' => '077 36063 671',
		'label' => ''		
	), $atts ) );
 return '<div class="tel">'. $label . $telephone .'</div>';
}
add_shortcode( 'telephone', 'ah_telephone_shortcode' );

// [email]
function ah_email_shortcode( $atts, $content = null ){
 	extract( shortcode_atts( array(
		'email' => 'andy@big-andy.co.uk'		
	), $atts ) );
 return '<a class="email" href="mailto:'. $email .'">'. $email .'</a>';
 }
add_shortcode( 'email', 'ah_email_shortcode' );

// [name]
function ah_name_shortcode( $atts, $content = null ){
	extract( shortcode_atts( array(
		'name' => 'Andrew JD Hudson',
		'wrapper' => 'span'		
	), $atts ) );
 return '<'. $wrapper .' class="fn">'. $name .'</'. $wrapper .'>';
}
add_shortcode( 'name', 'ah_name_shortcode' );


// [website]
function ah_website_shortcode( $atts, $content = null ){
 
 extract( shortcode_atts( array(
		'url' => 'big-andy.co.uk'
	), $atts ) );
	
	return '<a class="url" href="http://'.$url.'">'.$url.'</a>';
}
add_shortcode( 'website', 'ah_website_shortcode' );

// [vcard] :: allows for the other shortcodes to be within this shortcode!
function ah_vcard_shortcode( $atts, $content = null ){
 return '<div class="vcard">'. do_shortcode($content) .'</div>';
}
add_shortcode( 'vcard', 'ah_vcard_shortcode' );



// http://www.wprecipes.com/how-to-remove-menus-in-wordpress-dashboard
	

function ah_remove_menus () {
global $menu;
	$restricted = array( __('Tools'), __('Users'), __('Comments'));
	end ($menu);
	while (prev($menu)){
		$value = explode(' ',$menu[key($menu)][0]);
		if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
	}
}
add_action('admin_menu', 'ah_remove_menus');

// see also : http://noeltock.com/wcuk12/
function change_admin_cap() {

    $role = get_role('administrator');
			$role->remove_cap('upload_files'); 
				// $role->add_cap('upload_files');
    	$role->remove_cap('manage_galleries');
			$role->remove_cap('export');
			$role->remove_cap('import');
			$role->remove_cap('manage_links'); // remove links. Woop!
			$role->remove_cap('moderate_comments');
			$role->remove_cap('edit_comments');
			$role->remove_cap('update_core');
				// $role->add_cap('update_core');
			$role->remove_cap('create_users');
			$role->remove_cap('list_users');
			$role->remove_cap('add_users');
			$role->remove_cap('remove_users');
			$role->remove_cap('promote_users');		
}

// remove comment moderation from admin bar 
	// http://wpmu.org/how-to-add-or-remove-links-from-the-wordpress-3-1-admin-bar/
function ah_admin_bar_render() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('comments');
}
add_action( 'wp_before_admin_bar_render', 'ah_admin_bar_render' );


// http://codex.wordpress.org/Function_Reference/add_menu
function mytheme_admin_bar_render() {
	global $wp_admin_bar;
        $wp_admin_bar->add_menu( array(
	        'parent' => false,
	        'id' => 'ext-link',
	        'title' => __('BBC'),
	        'href' => 'http://bbc.co.uk',
	        'meta' => array(
							'target' => '_blank',
							'title' => 'Link opens in new window - BBC'
						)),
					$wp_admin_bar->add_menu( array(
	        'parent' => 'ext-link',
	        'id' => 'ext-link-child',
	        'title' => __('BBC Sport'),
	        'href' => 'http://bbc.co.uk/sport',
	        'meta' => array(
							'target' => '_blank',
							'title' => 'Link opens in new window - BBC Sport'
						))
        
     ));
}
add_action( 'wp_before_admin_bar_render', 'mytheme_admin_bar_render' );



