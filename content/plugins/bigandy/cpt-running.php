<?php

add_action( 'init', 'register_cpt_run' );

function register_cpt_run() {

    $labels = array( 
        'name' => _x( 'Running Sessions', 'run' ),
        'singular_name' => _x( 'Run', 'run' ),
        'add_new' => _x( 'Add New Run', 'run' ),
        'add_new_item' => _x( 'Add New Run', 'run' ),
        'edit_item' => _x( 'Edit Run', 'run' ),
        'new_item' => _x( 'New Run', 'run' ),
        'view_item' => _x( 'View Run', 'run' ),
        'search_items' => _x( 'Search Running Sessions', 'run' ),
        'not_found' => _x( 'No running sessions found', 'run' ),
        'not_found_in_trash' => _x( 'No running sessions found in Trash', 'run' ),
        'parent_item_colon' => _x( 'Parent Run:', 'run' ),
        'menu_name' => _x( 'Running', 'run' ),
    );

    $args = array( 
        'labels' => $labels,
        'hierarchical' => false,
        
        'supports' => array( 'title', 'editor', 'custom-fields' ),
        // 'taxonomies' => array( 'category', 'post_tag' ),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 20,
        
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => array( 
        	'slug' => 'running', 
        	'with_front' => false
        ),
        'capability_type' => 'post'
    );

    register_post_type( 'run', $args );
}