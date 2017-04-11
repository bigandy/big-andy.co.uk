<?php
function ah_add_serviceworker_in_root() {
	$template_uri = trailingslashit( get_stylesheet_directory_uri() );
	$home_url = trailingslashit( get_home_url() );
	$posts_urls = '';
	$posts_args = [
		'posts_per_page'	=> 10,
		'post_type'			=> [
			'post',
		],
		'post_status'		=> 'publish',
	];

	$posts_loop = new WP_Query( $posts_args );
	if ( $posts_loop->have_posts() ) {
		while ( $posts_loop->have_posts() ) {
			$posts_loop->the_post();
			$posts_urls .= "'" . get_the_permalink() . "',\n\t\t\t\t";
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
			$page_urls .= "'" . get_the_permalink() . "',\n\t\t\t\t";
		}
	}
	wp_reset_postdata();

	$data = "
importScripts('" . esc_url( $template_uri ) . "build/js/sw-toolbox.min.js');
importScripts('" . esc_url( $template_uri ) . "js/async-waituntil.js');

var cacheName = 'ahsw-" . date( 'd-m-Y-H-i-s', filemtime( SITEROOT . 'serviceWorker.js' ) ) . "';

// https://ponyfoo.com/articles/serviceworker-revolution
self.addEventListener('activate', (event) => {
	event.waitUntil(
		caches.keys().then((keys) => {
			return Promise.all(
				keys
					.filter((key) => key.indexOf(cacheName) !== 0)
					.map((key) => caches.delete(key))
			);
		})
	);
});

toolbox.precache([
	// Assets
	'" . esc_url( get_stylesheet_uri() ). "',
	'" . esc_url( $template_uri ) . "build/js/script.js',
	'" . esc_url( $template_uri ) . "build/js/singular.min.js',
	'" . esc_url( $template_uri ) . "fonts/open-sans-v13-latin-regular.woff2',
	'" . esc_url( $template_uri ) . "fonts/open-sans-v13-latin-regular.woff',
	'" . esc_url( $template_uri ) . "fonts/open-sans-v13-latin-800.woff2',
	'" . esc_url( $template_uri ) . "fonts/open-sans-v13-latin-800.woff',
	'" . esc_url( $template_uri ) . "images/ba.png',
	'" . esc_url( $template_uri ) . "manifest.json',
]);

self.addEventListener('install', (e) => {
	e.waitUntil(
		caches.open(cacheName).then((cache) => {
			return cache.addAll([
				// Posts
				" . $posts_urls . "
				// Pages
				" . $page_urls . "
			]).then(() => {
				return self.skipWaiting();
			});
		})
	);
});

self.addEventListener('activate', (event) => event.waitUntil(self.clients.claim()));

self.addEventListener('fetch', (event) => {
	// console.log(event.request.url);

	// This service worker won't touch the admin area and preview pages
	// https://justmarkup.com/log/2016/01/add-service-worker-for-wordpress/
	if (event.request.url.match(/wp-admin/) || event.request.url.match(/preview=true/)) {
		return;
	}

	event.respondWith(
		caches.match(event.request).then((response) => response || fetch(event.request))
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

if ( ! is_user_logged_in() ) {
	add_action( 'wp_footer', 'ah_add_service_worker_to_footer' );
}
