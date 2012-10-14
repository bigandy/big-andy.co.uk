<?php
function ah_footer() {
	$ah_options = get_option( 'ah_plugin_options' );
	$ga_code = $ah_options['output'];

	if ( $ga_code != '' ) {

		echo "<!-- google analytics code -->
		<script type='text/javascript'>
		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', '".$ga_code."']);
		  _gaq.push(['_trackPageview']);

		  (function() {
		    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();
		</script>";
	}
}
add_action( 'wp_footer', 'ah_footer' );