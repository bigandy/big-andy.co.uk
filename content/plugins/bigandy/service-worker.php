<?php


function ah_add_serviceworker_in_root() {
	$urls = '';
	$posts_args = [
		'posts_per_page'	=> -1,
		'post_type'			=> [
			'post',
			'page'
		]
	];

	$posts_loop = new WP_Query( $posts_args );
	if ( $posts_loop->have_posts() ) {
		while ( $posts_loop->have_posts() ) {
			$posts_loop->the_post();
			$urls .= "'" . get_the_permalink() . "',\n\t\t";
		}
	}
	wp_reset_postdata();

	$data = "
importScripts('" . esc_url( HOMEURL ) . "cache-polyfill.js');

var cacheName = 'wpo-cache-" . date( "d-m-Y-H-i-s" ) . "';

// https://ponyfoo.com/articles/serviceworker-revolution
self.addEventListener('activate', function activator (event) {
	event.waitUntil(
		caches.keys().then(function (keys) {
			return Promise.all(keys
				.filter(function (key) {
					return key.indexOf(cacheName) !== 0;
				})
				.map(function (key) {
					return caches.delete(key);
				})
			);
		})
	);
});

self.addEventListener('install', function(e) {
	e.waitUntil(
		caches.open(cacheName).then(function(cache) {
			return cache.addAll([
				'" . esc_url( HOMEURL ) . "',
				'" . esc_url( get_stylesheet_uri() ). "',
				'" . esc_url( HOMEURL ) . "wp-includes/js/jquery/jquery.js' ,
				'" . esc_url( HOMEURL ) . "wp-includes/js/jquery/jquery-migrate.min.js',
				'" . esc_url( TEMPLATEURI ) . "build/js/script.min.js',
				" . $urls . "
			]).then(function() {
				return self.skipWaiting();
			});
		})
	);
});

self.addEventListener('activate', function(event) {
  event.waitUntil(self.clients.claim());
});

self.addEventListener('fetch', function(event) {
  console.log(event.request.url);

  event.respondWith(
	caches.match(event.request).then(function(response) {
	  return response || fetch(event.request);
	})
  );
});
	";

	file_put_contents( ABSPATH . 'serviceWorker.js', $data );
}

add_action( 'publish_post', 'ah_add_serviceworker_in_root' );
add_action( 'publish_page', 'ah_add_serviceworker_in_root' );
add_action( 'edit_post', 'ah_add_serviceworker_in_root' );
add_action( 'edit_page', 'ah_add_serviceworker_in_root' );

function ah_add_service_worker_to_footer() {
	$html = "
	<script>
		if('serviceWorker' in navigator) {
			navigator.serviceWorker.register( '" . esc_url( HOMEURL ) . "serviceWorker.js', { scope: '" . esc_url( HOMEURL ) . "' })
				.then(function(registration) {
					console.log('Service Worker Registered');
				});

			navigator.serviceWorker.ready.then(function(registration) {
				console.log('Service Worker Ready');
			});
		}
	</script>";
	echo $html;
}



function ah_service_worker() {
	if ( ! is_user_logged_in() ) {
		add_action( 'wp_footer', 'ah_add_service_worker_to_footer' );
	}
}
add_action( 'init', 'ah_service_worker' );


