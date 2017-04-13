document.addEventListener("DOMContentLoaded", function(event) {
	refreshSW();
});

const refreshSW = () => {
	const button = document.querySelector('#wp-admin-bar-refresh-service-worker a');

	button.addEventListener('click', (e) => {
		e.preventDefault();

		const xhr = new XMLHttpRequest();

		xhr.open('POST', `${ajaxurl}?action=refresh_serviceworker`, true);
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhr.onload = function() {
			console.log('Status code: ', xhr.status);
		    if (xhr.status === 200) {
		        console.log('Success! ', xhr.responseText);
		    }
		};
		xhr.send();
	});
};
