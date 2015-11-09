<?php
function ah_set_weather() {
	$response_args = [
		'timeout' => 120,
	];

	$transient_name = 'weather'; // Name of value in database.
	$cache_time = 20; // Time in minutes between updates.

	// If the transient is not set
	if ( false === ( $weather = get_transient( $transient_name ) ) ) {
	   // Get new $response from the weather api
	   $response = wp_remote_get( 'https://api.forecast.io/forecast/c3a6795997c7501f0e1e115b3600eb40/51.628284,-1.296668?units=uk', $response_args );
	   // Check if response is an array
	   if ( is_array( $response ) ) {
			// Get response body
			$response_body = json_decode( $response['body'] );
			// Get Current Weather Summary
			$weather = strtolower( $response_body->currently->summary );
			// Set the transient
			set_transient( $transient_name, $weather, 60 * $cache_time );
		}
	}

	return $weather;
}



