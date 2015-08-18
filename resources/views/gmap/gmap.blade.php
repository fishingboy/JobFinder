<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<link rel="stylesheet" type="text/css" href="{{ asset("compile/css/common.css") }}" />
	<link rel="stylesheet" type="text/css" href="{{ asset("compile/css/3rd-party/font-awesome.min.css") }}" />
	
</head>
<body>
	<div id="mrtContainer">

	</div>
	<div id="map-canvas"></div>
	
	<script id="mrt-template" type="text/x-handlebars-template">
		<select id="mrtSelector" class="mrt-selector">
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
