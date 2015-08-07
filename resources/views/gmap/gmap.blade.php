<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<style type="text/css">
		html, body, #map-canvas { height: 100%; margin: 0; padding: 0;}
	</style>
</head>
<body>

	<div id="map-canvas"></div>

	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDwibvTIKkJFEfmx8gQuwZgGsPridiX08I"> </script>
	<script src="{{ asset("js/jgmap.js") }}" charset="utf-8"></script>
	<script charset="utf-8">
		var jgmap = JGMAP;
		jgmap.init();
	</script>
</body>
</html>
