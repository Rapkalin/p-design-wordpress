if ($('#map').length > 0) {

	$script('https://maps.googleapis.com/maps/api/js?key=AIzaSyB_fg8zbb3B_fztRJh5hlV8sKQ5MhTVPNc', () => {

		const myLatLng = { lat: 48.817550, lng: 2.392830 };

		const map = new google.maps.Map(document.getElementById("map"), {
		  zoom: 16,
		  center: myLatLng,
		});

		new google.maps.Marker({
		  position: myLatLng,
		  map,
		});

	});

}