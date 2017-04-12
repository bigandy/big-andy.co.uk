(function($) {
	$( document ).ready(function() {
		const button = $('#wp-admin-bar-refresh-service-worker a');

		button.on('click', (e) => {
			e.preventDefault();
			console.log('have been clicked!');

			const data = {
				'action': 'refresh_serviceworker',
			};

			// when button is clicked fire the post request with action refresh_serviceworker
			// this meanse that refresh-service-worker.js can call the ah_add_serviceworker_in_root() function
			$.post(ajaxurl, data, (response) => {
				console.log('Got this from the server: ' + response);
			});

			// console.log(ajaxurl);
			// var http = new XMLHttpRequest();
			// http.open('POST', ajaxurl, true);
			// // // request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
			//
			// //Send the proper header information along with the request
			// http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			// // http.setRequestHeader("Content-length", params.length);
			// // http.setRequestHeader("Connection", "close");
			//
			// http.onreadystatechange = function() {//Call a function when the state changes.
			// 	console.log(http.status, http.readyState);
			// 	if (http.readyState == 4 && http.status == 200) {
			// 		console.log(http.responseText);
			// 	}
			// }
			// http.send(data);

		});
	});
})( jQuery );
