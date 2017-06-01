<?php

require 'vendor/autoload.php';

function ah_add_random_image_new_post( $id, $post ) {
	// Below initialization will create a  phpredis client, or a TinyRedisClient depending on what is installed
	$emitter = new SocketIO\Emitter(
		array(
			'port' => '6379',
			'host' => '127.0.0.1',
		)
	);
	// broadcast can be replaced by any of the other flags
	// $date = the_date( 'Y-m-d' );
	// ah_preit($date);
	// $emitter->broadcast->emit( 'new post', $id + $post );
	$emitter->broadcast->emit( 'published new post', $id );
}
add_action( 'publish_post', 'ah_add_random_image_new_post', 10, 2 );

if ( current_user_can( 'update_core' ) && is_user_logged_in() ) {

	function ah_is_edit_page() {
	    global $pagenow;

	    //make sure we are on the backend
	    if ( ! is_admin() ) {
			return false;
		}

        return in_array( $pagenow, array( 'post.php', 'post-new.php' ) );
	}

	function ah_admin_header_function() {
		if ( ah_is_edit_page() ) {
			?>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.1/socket.io.js"></script>
			<script>
				var socket = io('http://localhost:3001');
				var complete = false;

				socket.on('connect', function(msg) {
					console.log('i connected');
				});

				if ( true !== complete ) {
					socket.on('published new post', function(data) {
						console.log('new post', data);
						socket.emit('published new post', data);
					});
				}

				socket.on('post complete', function() {
					complete = true;
					console.log('complete')
				});
			</script>
			<?php
		}
	}
	add_action( 'admin_head', 'ah_admin_header_function' );
}
