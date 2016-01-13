<?php

$meta_boxes[] = array(
	'id' => 'health-meta',
	'title' => 'Health Data',
	'pages' => array( 'health' ),
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
		// Weight
		array(
			'name'		=> 'Weight',
			'id'		=> $prefix . 'health_weight',
			'type'		=> 'number',
		),
		// Comments
		array(
			'name'		=> 'Comments',
			'id'		=> $prefix . 'health_comments',
			'type'		=> 'wysiwyg',
		),
	)
);
