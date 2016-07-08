/* global localStorage, window, document, XMLHttpRequest */

const links = document.getElementsByClassName('article__link'),
	linksLength = links.length,
	host = window.location.host;

let i = linksLength;

// http://joelcalifa.com/blog/revisiting-visited
localStorage.setItem('visited-' + window.location.pathname, true);

while (i--) {
	let link = links[i];

	if (link.host === host && localStorage.getItem('visited-' + link.pathname)) {

		// there's got to be a better way of getting the containing article
		link.parentNode.parentNode.parentNode.dataset.visited = true;
	}
}
