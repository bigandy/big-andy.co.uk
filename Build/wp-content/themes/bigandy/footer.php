<footer id="footer">
	<!--END-->
	<p>&copy;2004-<?php echo date('Y');?> Andrew JD Hudson</p>
	<p>Powered by <a href="http://wordpress.org" target="_blank" title="link opens in new window - WordPress">WordPress</a></p>
	</footer><!--end of footer-->
</div><!--end of container-->
<!--google tracking javascript-->
<script src="<?php bloginfo( 'template_url' ); ?>/js/typekit.min.js"></script>
<script>
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-6954334-3']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
<?php
   /* Always have wp_footer() just before the closing </body>
    * tag of your theme, or you will break many plugins, which
    * generally use this hook to reference JavaScript files.
    */

    wp_footer();
?>
</body>
</html>