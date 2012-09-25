<?php

function ah_styles()
{
    // Register the style like this for a plugin:
    wp_register_style( 'custom-style', plugins_url( 'css/bigandy.css', __FILE__ ) );
    wp_enqueue_style( 'custom-style' );
}
add_action( 'admin_enqueue_scripts', 'ah_styles' );

// echo plugins_url();

// echo plugins_url( 'css/bigandy.css' , __FILE__ );