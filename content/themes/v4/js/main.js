/* global localStorage, window, document */

// http://joelcalifa.com/blog/revisiting-visited
localStorage.setItem('visited-' + window.location.pathname, true);

var links = document.getElementsByClassName('article__link'),
	linksLength = links.length,
	i = linksLength;


while (i--) {
	var link = links[i];
	if (link.host === window.location.host && localStorage.getItem('visited-' + link.pathname)) {
		link.dataset.visited = true;
	}
}



