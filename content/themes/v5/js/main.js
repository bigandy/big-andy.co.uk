/* global localStorage, window, document, XMLHttpRequest */

var links = document.getElementsByClassName('article__link'),
	linksLength = links.length,
	host = window.location.host,
	i = linksLength;

// http://joelcalifa.com/blog/revisiting-visited

var pathname = window.location.pathname;

// Don't want to track whether we're on a page, only posts need to be tracked
if (pathname !== '/' || pathname !== '/about' || pathname !== '/cv' || pathname !== '/style-guide') {
	localStorage.setItem('visited-' + window.location.pathname, true);
}

while (i--) {
	var link = links[i];

	if (link.host === host && localStorage.getItem('visited-' + link.pathname)) {

		// there's got to be a better way of getting the containing article
		link.parentNode.parentNode.parentNode.dataset.visited = true;
	}
}
