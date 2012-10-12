<?php

// http://stackoverflow.com/questions/5222140/remove-li-class-id-for-menu-items-and-pages-list

add_filter( 'nav_menu_css_class', 'my_css_attributes_filter', 100, 1 );

function my_css_attributes_filter( $var ) {
	return is_array( $var ) ? array_intersect( $var, array( 'current-menu-item', 'current_page_parent' ) ) : '';
}