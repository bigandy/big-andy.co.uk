<?php 

// http://stackoverflow.com/questions/5222140/remove-li-class-id-for-menu-items-and-pages-list

add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1);

function my_css_attributes_filter($var) {
  return is_array($var) ? array_intersect($var, array('current-menu-item', 'current_page_parent')) : '';
}

// Strip Classes, except for .current-menu-item
// http://stackoverflow.com/a/8777624/965191

// commenting out as currently doesn't work with more than one class

// add_filter ('wp_nav_menu','strip_empty_classes');
 // function strip_empty_classes($menu) {
     // $menu = preg_replace('/ class=(["\'])(?!current-menu-item ).*?\1/','',$menu);
     // return $menu;
// }