<?php
require 'vendor/autoload.php';

function ah_add_random_image_new_post( $post_id, $post, $update ) {
	// Below initialization will create a  phpredis client, or a TinyRedisClient depending on what is installed
	$redis = new Predis\Client( array(
	    'host' => '127.0.0.1',
	    'port' => 6379
	) );

	$data = [
		id => $post_id,
		post => $post,
		update => $update,
	];

	// $is_new = $post->post_date === $post->post_modified;

	// if the form has been submitted by API the viaAPI will be true
	// This will prevent an infinite loop when save_post gets fired every time
	// either the API or browser editor saves the post.
	if ( is_admin() ) {
		$redis->publish( 'WordPress published new post', json_encode( $data ) );
	}
}
add_action( 'save_post', 'ah_add_random_image_new_post', 10, 3 );
// add_action( 'publish_post', 'wpdocs_run_on_publish_only', 10, 3 );

// FOR TESTING, uncomment the below:

// $redis = new Predis\Client(array(
// 	// 'scheme' => 'tcp',
// 	'host' => '127.0.0.1',
// 	'port' => 6379
// ));
//
// $redis->publish( 'thing', 'yo' );
