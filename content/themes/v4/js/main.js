var require;

require.config({
	'baseUrl': 'content/themes/v4/js',
	'paths': {
		'jQuery': 'bower-components/jQuery/jquery.min',
		'backbone': 'bower-components/backbone/backbone-min',
		'underscore': 'bower-components/underscore/underscore-min'
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