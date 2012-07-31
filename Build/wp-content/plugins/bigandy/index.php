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

//[foobar]
function ah_address_shortcode(){
 return '<div id="hcard-andrew-hudson" class="vcard">
 <div class="adr">
  <div class="street-address">19 Cambridge Street</div>
  <span class="locality">Reading</span> 
  <span class="region">Berkshire</span> 
  <span class="postal-code">RG1 7PA</span>
 </div>
</div>';
}
add_shortcode( 'address', 'ah_address_shortcode' );

