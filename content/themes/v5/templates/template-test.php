<?php
get_header();
/*
 * Template Name: Test Remote Get
 */
$api_url = 'http://api.wordpress.org/secret-key/1.0/';
$response = wp_remote_get( $api_url );
$header = wp_remote_retrieve_headers( $response );

var_dump( $header );

get_footer();
