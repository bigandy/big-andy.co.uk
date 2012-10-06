<?php

function ah_plugin_styles()
{
    // Register the style like this for a plugin:
    wp_register_style( 'custom-style', plugins_url( 'css/bigandy.css', __FILE__ ) );
    wp_enqueue_style( 'custom-style' );
}
add_action( 'admin_enqueue_scripts', 'ah_plugin_styles' );


function ah_plugin_script()
{
    // Register the style like this for a plugin:
    wp_register_script( 'custom-style', plugins_url( 'js/bigandy.js', __FILE__ ) );
    wp_enqueue_script( 'custom-style' );
}
add_action( 'admin_enqueue_scripts', 'ah_plugin_script' );

