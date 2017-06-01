<?php

require 'vendor/autoload.php';

function ah_add_random_image_new_post( $post_id, $post, $update ) {

	// ah_preit( $post_id );
	// ah_preit( $post );
	// ah_preit( $update );
	// echo '<p>status is new' . $new_status . '</p>';
	// echo '<p>status is old' . $old_status . '</p>';
	// echo '<p>id is ' . $post->ID . '</p>';

	// Below initialization will create a  phpredis client, or a TinyRedisClient depending on what is installed
	$emitter = new SocketIO\Emitter(
		array(
			'port' => '6379',
			'host' => '127.0.0.1',
		)
	);
	// broadcast can be replaced by any of the other flags
	if ( true === $update ) {
		$emitter->emit( 'published new post', $post_id, json_encode( $post ), $update );
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

				socket.on('connect', function(msg) {
					console.log('i connected');
				});

				let isDone = false;

				socket.on('post complete', function() {
					console.log('complete');
					isDone = true;
				});

				socket.on('published new post', function(id, post, update) {
					if (isDone === false) {
						socket.emit('browser published new post', id, post, update );
					}

					console.log('is it done yet?', isDone);
				});


			</script>
			<?php
		}
	}
	add_action( 'admin_head', 'ah_admin_header_function' );
}
