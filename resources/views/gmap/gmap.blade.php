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
	<div id="mrtSelector">

	</div>
	<div id="map-canvas"></div>

	<script id="mrt-template" type="text/x-handlebars-template">
		<select>
		@{{#each .}}
			 <option value="@{{latitude}},@{{longitude}}">@{{name}}</option>
		@{{/each}}
		</select>
	</script>

	<script src="{{ asset("js/3rd-party/jquery-2.1.4.min.js") }}" charset="utf-8"></script>
	<script src="{{ asset("js/3rd-party/handlebars-v3.0.3.js") }}" charset="utf-8"></script>
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDwibvTIKkJFEfmx8gQuwZgGsPridiX08I"> </script>
	<script src="{{ asset("js/jgmap.js") }}" charset="utf-8"></script>


	<script charset="utf-8">
		var jgmap = JGMAP;
		// jgmap.init();
		jgmap.buildTaipeiMrtSelector();
	</script>
</body>
</html>
