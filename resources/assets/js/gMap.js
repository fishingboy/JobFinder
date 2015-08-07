var location = {};
var myStorage = localStorage;
console.log(myStorage);
// if ("geolocation" in navigator) {
// 	navigator.geolocation.getCurrentPosition(function(
// 		position) {
// 		location.latitude = position.coords.latitude;
// 		location.longitude = position.coords.longitude;
// 		console.log(location);
// 		var myLatlng = new google.maps.LatLng(latlng.latitude, latlng.longitude);
// 		console.log(myLatlng);
// 		// initialize(myLatlng);
// 	});
// }
//
// function initializeGMap(myLatlng) {
// 	var mapOptions = {
// 		center: myLatlng,
// 		zoom: 8
// 	};
// 	var map = new google.maps.Map(document.getElementById('map-canvas'),
// 		mapOptions);
// 	var marker = new google.maps.Marker({
// 		position: myLatlng,
// 		map: map,
// 		title: 'Hello World!'
// 	});
// }
// google.maps.event.addDomListener(window, 'load', initialize);
