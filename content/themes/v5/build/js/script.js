var cb=function(){var e=document.createElement("link");e.rel="stylesheet",e.href="/content/themes/v5/style.2b03d5f4.css";var t=document.getElementsByTagName("head")[0];t.appendChild(e)},raf=requestAnimationFrame||mozRequestAnimationFrame||webkitRequestAnimationFrame||msRequestAnimationFrame;raf?raf(cb):window.addEventListener("load",cb);
var links=document.getElementsByClassName("article__link"),linksLength=links.length,host=window.location.host,i=linksLength,pathname=window.location.pathname;for("/"===pathname&&"/about"===pathname&&"/cv"===pathname&&"/style-guide"===pathname||localStorage.setItem("visited-"+window.location.pathname,!0);i--;){var link=links[i];link.host===host&&localStorage.getItem("visited-"+link.pathname)&&(link.parentNode.parentNode.parentNode.dataset.visited=!0)}