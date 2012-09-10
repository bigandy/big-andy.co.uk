<?php

/*
Plugin Name: Speaker Deck Embed
Description: Embed Speaker Deck slideshows
Version: 1.0
Author: Matt Wiebe
Author URI: http://somadesign.ca/
*/


	wp_oembed_add_provider( 'http://speakerdeck.com/u/*/p/*', 'http://speakerdeck.com/oembed.json' );
