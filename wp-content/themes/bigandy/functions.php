<?php 

	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'primary', __( 'Primary Menu', 'bigandy' ) );

	// Add support for a variety of post formats
	add_theme_support( 'post-formats', array( 'aside', 'link', 'gallery', 'status', 'quote', 'image' ) );