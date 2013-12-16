var require;

require.config({
	'baseUrl': 'content/themes/v4/js',
	'paths': {
		'jQuery': 'bower-components/jQuery/jquery.min',
	},
	'shim': {
		'jQuery': {
			'exports': 'jQuery'
		}
	},
	waitSeconds: 20

});

require(
	['jQuery'], function ($) {
		'use strict';
		var h1 = $('h1');
		console.log(h1);
		console.log('this is awesome');
	}
);