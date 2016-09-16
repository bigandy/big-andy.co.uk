<?php
function ah_add_serviceworker_in_root() {
	$post_urls = '';
	$posts_args = [
		'posts_per_page'	=> 20,
		'post_type'			=> [
			'post',
		],
		'post_status'		=> 'publish',
	];

	$posts_loop = new WP_Query( $posts_args );
	if ( $posts_loop->have_posts() ) {
		while ( $posts_loop->have_posts() ) {
			$posts_loop->the_post();
			$posts_urls .= "'" . get_the_permalink() . "',\n\t\t";
		}
	}
	wp_reset_postdata();

	$page_urls = '';
	$page_args = [
		'posts_per_page'	=> 25,
		'post_type'			=> [
			'page',
		],
		'meta_query'		=> array(
			array(
				'key'		=> '_ah_page_service_worker',
				'value'		=> 1,
			),
		),
		'post_status'		=> 'publish',
	];

	$page_loop = new WP_Query( $page_args );
	if ( $page_loop->have_posts() ) {
		while ( $page_loop->have_posts() ) {
			$page_loop->the_post();
			$page_urls .= "'" . get_the_permalink() . "',\n\t\t";
		}
	}
	wp_reset_postdata();

	$data = "
importScripts('" . esc_url( TEMPLATEURI ) . "build/js/sw-toolbox.min.js');
importScripts('" . esc_url( TEMPLATEURI ) . "js/cache-polyfill.js');

var cacheName = 'wpo-cache-" . date( 'd-m-Y-H-i-s', filemtime( SITEROOT . 'serviceWorker.js' ) ) . "';

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

toolbox.precache([
	// Assets
	'" . esc_url( HOMEURL ) . "wp/wp-includes/js/wp-embed.min.js',
	'" . esc_url( get_stylesheet_uri() ). "',
	'" . esc_url( TEMPLATEURI ) . "build/js/script.min.js',
	'" . esc_url( TEMPLATEURI ) . "build/js/singular.min.js',
	'" . esc_url( TEMPLATEURI ) . "build/css/font.css',
	'" . esc_url( TEMPLATEURI ) . "images/ba.png',
	'" . esc_url( TEMPLATEURI ) . "manifest.json',
]);

self.addEventListener('install', function(e) {
	e.waitUntil(
		caches.open(cacheName).then(function(cache) {
			return cache.addAll([
				// Posts
				' . $posts_urls . '
				// Pages
				' . $page_urls . '
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
	file_put_contents( SITEROOT . 'serviceWorker.js', $data );
}

add_action( 'publish_post', 'ah_add_serviceworker_in_root' );
add_action( 'publish_page', 'ah_add_serviceworker_in_root' );



function ah_add_service_worker_to_footer() {
	$html = "
	<script>
		if('serviceWorker' in navigator) {
			navigator.serviceWorker.register( '" . esc_url( HOMEURL ) . "serviceWorker.js', { scope: '" . esc_url( HOMEURL ) . "' });
		}
	</script>";
	echo $html;
}

if ( ! is_user_logged_in() && (AHDEBUG === false)) {
	add_action( 'wp_footer', 'ah_add_service_worker_to_footer' );
}
