'use strict';

/* global mozRequestAnimationFrame, requestAnimationFrame, msRequestAnimationFrame, webkitRequestAnimationFrame */

var cb = function cb() {
	var l = document.createElement('link');l.rel = 'stylesheet';
	l.href = '/content/themes/v5/style.css';
	var h = document.getElementsByTagName('head')[0];h.appendChild(l);
};
var raf = requestAnimationFrame || mozRequestAnimationFrame || webkitRequestAnimationFrame || msRequestAnimationFrame;
if (raf) raf(cb);else window.addEventListener('load', cb);
//# sourceMappingURL=lazy-load-css.js.map