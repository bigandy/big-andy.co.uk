<?php 
/* 
* Plugin Name: bigandy functionality
* Version : 1.0
* Description : Shortcode for address, plus other stuff
*/
// http://www.wprecipes.com/how-to-remove-menus-in-wordpress-dashboard
	// see also : http://noeltock.com/wcuk12/

function remove_menus () {
global $menu;
	$restricted = array( __('Media'), __('Links'), __('Tools'), __('Users'), __('Comments'));
	end ($menu);
	while (prev($menu)){
		$value = explode(' ',$menu[key($menu)][0]);
		if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
	}
}
add_action('admin_menu', 'remove_menus');

// [address] using microformats : http://microformats.org/code/hcard/creator
function ah_address_shortcode(){
 
 return '<div class="adr">
  <span class="street-address">19 Cambridge Street</span>
  <span class="locality">Reading</span> 
  <span class="region">Berkshire</span> 
  <span class="postal-code">RG1 7PA</span>
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
	
	
 return '<a class="email" href="'. $content .'">'. $content .'</a>';
}
add_shortcode( 'email', 'ah_email_shortcode' );

// [name]
function ah_name_shortcode( $atts, $content = null ){
	extract( shortcode_atts( array(
		'name' => 'Andrew JD Hudson'		
	), $atts ) );
 return '<span class="fn">'. $name .'</span>';
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

// [vcard]
function ah_vcard_shortcode( $atts, $content = null ){
 return '<div class="vcard">'. do_shortcode($content) .'</div>';
}
add_shortcode( 'vcard', 'ah_vcard_shortcode' );









