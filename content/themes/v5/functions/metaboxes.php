<?php
/*
 * File name - metaboxes.php
 * Meta box functionality has been created by Rilwis @ http://www.deluxeblogtips.com and can be downloaded as a plugin from the wordpress repository.
 * Use underscore (_) at the beginning to make keys hidden, for example $prefix = '_es_';
 */
$prefix = '_ah_';

global $meta_boxes;

// Set up the array to store all the meta boxes data.
$meta_boxes = array();

include_once( 'metaboxes/page.php' );


/*
 * Metabox Helpers
 */
include_once( 'metaboxes/helpers/register-metabox.php' );
