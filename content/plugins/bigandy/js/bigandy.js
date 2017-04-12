(function($) {
	$( document ).ready(function() {
		const button = $('#wp-admin-bar-refresh-service-worker a');

		button.on('click', () => {
			console.log('have been clicked!');

			var data = {
				'action': 'refresh_serviceworker',
			};

			// when button is clicked fire the post request with action refresh_serviceworker
			// this meanse that refresh-service-worker.js can call the ah_add_serviceworker_in_root() function
			$.post(ajaxurl, data, (response) => {
				console.log('Got this from the server: ' + response);
			});
		});
	});
})( jQuery );
