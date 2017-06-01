<?php

require 'vendor/autoload.php';

function ah_add_random_image_new_post( $post_id, $post, $update ) {
	// Below initialization will create a  phpredis client, or a TinyRedisClient depending on what is installed
	$emitter = new SocketIO\Emitter(
		array(
			'port' => '6379',
			'host' => '127.0.0.1',
		)
	);
	// broadcast can be replaced by any of the other flags
	if ( true === $update ) {
		$emitter->emit( 'WordPress published new post', $post_id, json_encode( $post ), $update );
	}
}
add_action( 'save_post', 'ah_add_random_image_new_post', 10, 3 );
// add_action( 'publish_post', 'wpdocs_run_on_publish_only', 10, 3 );

function ah_is_edit_page() {
    global $pagenow;

    //make sure we are on the backend
    if ( ! is_admin() ) {
		return false;
	}

    return in_array( $pagenow, array( 'post.php', 'post-new.php' ) );
}


if ( current_user_can( 'update_core' ) && is_user_logged_in() ) {
	function ah_admin_header_function() {
		if ( ah_is_edit_page() ) {
			?>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.1/socket.io.js"></script>
			<script>
				var socket = io('http://localhost:3001');
				var complete = false;

				socket.on('connect', () => console.log('connected to socket server'));


				socket.on('WordPress published new post', (id, post, update) => {
					socket.emit('browser published new post', id, post, update );
				});

				socket.on('the post complete', (msg) => console.log('complete'));
			</script>
			<?php
		}
	}
	add_action( 'admin_head', 'ah_admin_header_function' );
}
