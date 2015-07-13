/* global localStorage, window, document */

var links = document.getElementsByClassName('article__link'),
	linksLength = links.length,
	i = linksLength;


// http://joelcalifa.com/blog/revisiting-visited
localStorage.setItem('visited-' + window.location.pathname, true);

while (i--) {
	var link = links[i];
	if (link.host === window.location.host && localStorage.getItem('visited-' + link.pathname)) {

		// there's got to be a better way of getting the containing article
		link.parentNode.parentNode.parentNode.dataset.visited = true;
	}
}
