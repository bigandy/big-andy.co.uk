<?php

function ba_scpt_demo() {
    if ( ! class_exists( 'Super_Custom_Post_Type' ) )
        return;

    // All your SuperCPT magic goes here!


    $meta = new Super_Custom_Post_Meta( 'post' );

    $meta->add_meta_box( array(
	    'id' => 'post_meta', // the title is converted from 'id', if there's no 'title' parameter
	    'title' => 'Post Meta',
	    'fields' => array(
	        'hide_front_page' => array( 'label' => __( 'Hide from Front Page?', 'my-locale' ), 'type' => 'radio', 'options' => array( 'Yes' ) ),
	    ),
	    'context' => 'side'
	) );
}
add_action( 'after_setup_theme', 'ba_scpt_demo' );