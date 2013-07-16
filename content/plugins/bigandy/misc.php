<?php

/*
* function: ah_dump()
* Useful for debugging.
* Taken from Jenny Wong's WordCamp Lancaster 2013 presentation on debugging PHP */

if ( !function_exists( ah_dump ) ) {
	function ah_dump( $input, $title = "" ) {
		if( !empty( $title ) ) {
			echo "<h2>" . $title . "</h2>";
		}

		echo "<pre>";
		var_dump( $input );
		echo "</pre><hr />";
		return;
	}
}
