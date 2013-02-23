<?php

// http://stackoverflow.com/questions/5222140/remove-li-class-id-for-menu-items-and-pages-list

add_filter( 'nav_menu_css_class', 'my_css_attributes_filter', 100, 1 );

function my_css_attributes_filter( $var ) {
	return is_array( $var ) ? array_intersect( $var, array( 'current-menu-item', 'current_page_parent' ) ) : '';
}


// http://vayu.dk/highlighting-wp_nav_menu-ancestor-children-custom-post-types/
// on custom post-types removes class current_page_parent from blog

add_filter('nav_menu_css_class', 'current_type_nav_class', 10, 2);
function current_type_nav_class($classes, $item) {
    // Get post_type for this post
    $post_type = get_query_var('post_type');

    // Removes current_page_parent class from blog menu item
    if ( get_post_type() == $post_type )
        $classes = array_filter($classes, "get_current_value" );

    // Go to Menus and add a menu class named: {custom-post-type}-menu
    // This adds a current_page_parent class to the parent menu item
    if( in_array( $post_type.'-menu-item', $classes ) )
        array_push($classes, 'current_page_parent');

    return $classes;
}
function get_current_value( $element ) {
    return ( $element != "current_page_parent" );
}