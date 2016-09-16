<footer>
	<div class="row footer__row content-container">
		<p class="left">&copy; 2006 - <?php echo esc_attr( date( 'Y' ) ); ?> Andrew JD Hudson</p>
		<a class="right" href="/style-guide" class="right footer__link link--dark">Style Guide</a>
	</div>
</footer>

<?php
if ( ! is_user_logged_in() ) {
	?>
	<script async src="/content/themes/v5/build/js/script.min.js"></script>
	<?php
}

$font_location = '/content/themes/v5/build/css/merriweather.css';
?>
<script type="text/javascript">!function(){"use strict";function e(e,t,n){e.addEventListener?e.addEventListener(t,n,!1):e.attachEvent&&e.attachEvent("on"+t,n)}function t(e){return window.localStorage&&localStorage.font_css_cache&&localStorage.font_css_cache_file===e}function n(){if(window.localStorage&&window.XMLHttpRequest)if(t(o))c(localStorage.font_css_cache);else{var n=new XMLHttpRequest;n.open("GET",o,!0),e(n,"load",function(){4===n.readyState&&(c(n.responseText),localStorage.font_css_cache=n.responseText,localStorage.font_css_cache_file=o)}),n.send()}else{var a=document.createElement("link");a.href=o,a.rel="stylesheet",a.type="text/css",document.getElementsByTagName("head")[0].appendChild(a),document.cookie="font_css_cache"}}function c(e){var t=document.createElement("style");t.innerHTML=e,document.getElementsByTagName("head")[0].appendChild(t)}var o="<?php echo $font_location; ?>";window.localStorage&&localStorage.font_css_cache||document.cookie.indexOf("font_css_cache")>-1?n():e(window,"load",n)}();</script><noscript><link rel="stylesheet" href="<?php echo $font_location; ?>"></noscript>
<?php wp_footer(); ?>
</body>
</html>
