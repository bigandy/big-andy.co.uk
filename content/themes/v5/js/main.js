/* global localStorage, window, document, XMLHttpRequest */

var links = document.getElementsByClassName('article__link'),
	linksLength = links.length,
	i = linksLength,
	ajax = new XMLHttpRequest();


// http://joelcalifa.com/blog/revisiting-visited
localStorage.setItem('visited-' + window.location.pathname, true);

while (i--) {
	var link = links[i];
	if (link.host === window.location.host && localStorage.getItem('visited-' + link.pathname)) {

		// there's got to be a better way of getting the containing article
		link.parentNode.parentNode.parentNode.dataset.visited = true;
	}
}

ajax.open('GET', '/content/themes/v5/build/svg/svg.svg', true);
ajax.send();
ajax.onload = function () {
	var div = document.createElement('div');
	div.innerHTML = ajax.responseText;
	div.className = 'svg-sprite';
	document.body.insertBefore(div, document.body.childNodes[0]);
};
