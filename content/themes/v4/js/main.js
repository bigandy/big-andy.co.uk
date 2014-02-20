var require;

require.config({
	'baseUrl': 'content/themes/v4/bower_components',
	'paths': {
		'jQuery': 'jQuery/jquery.min',
		'backbone': 'backbone/backbone-min',
		'underscore': 'underscore/underscore-min'
	},
	'shim': {
		'jQuery': {
			'exports': 'jQuery'
		},
		'backbone': {
			'exports': 'backbone'
		},
		'underscore': {
			'exports': 'underscore'
		}
	},
	waitSeconds: 20

});

require(
	[
		'jQuery',
		'underscore',
		'backbone'
	], function ($) {
		'use strict';
		var h1 = $('h1');
		console.log(h1);
		console.log('this is awesome');
	}
);