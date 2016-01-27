'use strict';

/* global localStorage, window, document, XMLHttpRequest */

var links = document.getElementsByClassName('article__link');
var linksLength = links.length,
    i = linksLength,
    host = window.location.host;

// http://joelcalifa.com/blog/revisiting-visited
localStorage.setItem('visited-' + window.location.pathname, true);

while (i--) {
	var link = links[i];

	if (link.host === host && localStorage.getItem('visited-' + link.pathname)) {

		// there's got to be a better way of getting the containing article
		link.parentNode.parentNode.parentNode.dataset.visited = true;
	}
}

// let test = (message = 'This is my message', author = 'Andrew') => {
// 	console.log(`${message} by ${author}`);
// }

// test('Testing 1 2 3...');
//# sourceMappingURL=main.js.map