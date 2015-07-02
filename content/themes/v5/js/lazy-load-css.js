var cb = function() {
	var l = document.createElement('link'); l.rel = 'stylesheet';
	l.href = '/content/themes/v4/style.css';
	var h = document.getElementsByTagName('head')[0]; h.appendChild(l);
};
var raf = requestAnimationFrame || mozRequestAnimationFrame ||
	webkitRequestAnimationFrame || msRequestAnimationFrame;
if (raf) raf(cb);
else window.addEventListener('load', cb);
