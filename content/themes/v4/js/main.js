/* global localStorage, window, document */

// http://joelcalifa.com/blog/revisiting-visited
// localStorage.clear();
localStorage.setItem('visited-' + window.location.pathname, true);
var links = document.getElementsByTagName('a'),
	linksLength = links.length,
	i = linksLength;


while (i--) {
	var link = links[i];
	if (link.host === window.location.host && localStorage.getItem('visited-' + link.pathname)) {
		link.dataset.visited = true;
	}
}



