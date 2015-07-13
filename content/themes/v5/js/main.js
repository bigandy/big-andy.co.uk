/* global localStorage, window, document */

(function ($) {
	'use strict';



	var links = document.getElementsByClassName('article__link'),
		linksLength = links.length,
		i = linksLength,
		homeWeather = $('.home__weather');


	// http://joelcalifa.com/blog/revisiting-visited
	localStorage.setItem('visited-' + window.location.pathname, true);

	while (i--) {
		var link = links[i];
		if (link.host === window.location.host && localStorage.getItem('visited-' + link.pathname)) {

			// there's got to be a better way of getting the containing article
			link.parentNode.parentNode.parentNode.dataset.visited = true;
		}
	}



	function getWeather() {
		// using units=metric for celcius - can be changed
		var weather = 'http://api.openweathermap.org/data/2.5/weather?q=oxford,uk&units=metric&APPID=d8c7ee9216b8e2039669c7b012fcc66e'; // jshint ignore:line

		$.ajax({
			dataType: 'jsonp',
			url: weather,
			success: function (data) {
				homeWeather.text(data.weather[0].main + 'y');
			}
		});
	}

	getWeather();

}(jQuery));
