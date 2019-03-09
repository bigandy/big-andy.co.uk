<?php
/**
 * Functions.php
 *
 * @package bigandy
 */

$functions = get_template_directory() . '/functions/';

require $functions . 'misc.php';
require $functions . 'script-style.php';
require $functions . 'images.php';
require $functions . 'responsive-images.php';
require $functions . 'nav.php';
require $functions . 'customizer.php';
require $functions . 'admin.php';
require $functions . 'service-worker.php';

if ( true === $publish_post ) {
	require $functions . 'publish-post.php';
}

require $functions . 'micropub.php';

