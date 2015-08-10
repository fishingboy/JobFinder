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
	<div id="mrtContainer">

	</div>
	<div id="map-canvas"></div>

	<script id="mrt-template" type="text/x-handlebars-template">
		<select id="mrtSelector">
		@{{#each .}}
			 <option value="@{{latitude}},@{{longitude}}">@{{name}}</option>
		@{{/each}}
		</select>
	</script>

	<script type="text/javascript" src="{{ asset("js/vendor.js") }}"></script>
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDwibvTIKkJFEfmx8gQuwZgGsPridiX08I"> </script>
	<script type="text/javascript" src="{{ asset("js/jgmap.js") }}"></script>

	<script type="text/javascript" charset="utf-8">
		var jgmap = JGMAP;
		jgmap.init();
		jgmap.buildTaipeiMrtSelector();
		jgmap.bindMrtSelector(mrtSelector);

	</script>
</body>
</html>
