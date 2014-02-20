var require;

require.config({
	'baseUrl': 'content/themes/v4/',
	'paths': {
		'jQuery': 'bower_components/jQuery/jquery.min',
		'backbone': 'bower_components/backbone/backbone-min',
		'underscore': 'bower_components/underscore/underscore-min'
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
		// 'underscore',
		// 'backbone'
	], function ($) {
		'use strict';
		var h1 = $('h1');
		console.log(h1);
		console.log('this is awesome');
	}
);