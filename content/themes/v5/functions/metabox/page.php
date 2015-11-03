<?php

$meta_boxes[] = array(
	'id' => 'page-meta',
	'title' => 'Page Meta',
	'pages' => array( 'page' ),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		// Page Title
		array(
			'name'		=> 'Include on ServiceWorker?',
			'id'		=> $prefix . 'page_service_worker',
			'type'		=> 'checkbox',
		),
	)
);
