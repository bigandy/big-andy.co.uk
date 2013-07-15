<?php

function jdump( $input, $title ) {
	if( exists( $title ) ) {
		echo "<h2>" . $title . "</h2>";
	}

	echo "<pre>";
	var_dump( $input );
	echo "</pre>";

	return $html;
}

