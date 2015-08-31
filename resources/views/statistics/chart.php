
<head>
<!--  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/d3/3.5.5/d3.min.js"></script>  -->
<script src="http://jobfinder/js/3rd-party/jquery-2.1.4.min.js" charset="utf-8"></script>
<script type="text/javascript" src="/js/Chart.js"></script>
</head>
<body>
<div style="width:60%">
<canvas id="canvas_line" width="450" height="600"></canvas>
</div>

<div style="width:40%">
<canvas id="canvas_bar" width="450" height="600"></canvas>
</div>
</body>
<script type="text/javascript">
var randomScalingFactor = function(){ return Math.round(Math.random()*255)};
<?php 
	///*
	foreach($data as $language => $char_data)
	{
		$r = rand(0, 255);
		$g = rand(0, 255);
		$b = rand(0, 255);
		$a = rand(1, 2);
		$line_dataset[] = array(
				"label"                => $language,
				"strokeColor"          => "rgba({$r},{$g},{$b},{$a})",
				"pointColor"           => "rgba({$r},{$g},{$b},{$a})",
				"pointStrokeColor"     => "#fff",
				"pointHighlightFill"   => "#fff",
				"pointHighlightStroke" => "rgba({$r},{$g},{$b},{$a})",
				"data"                 => $char_data
		);
	}
	
	//*/
?>

var languageDataSet = <?php echo json_encode($line_dataset)?>;
var java = <?php echo json_encode($data['java'])?>;
var php = <?php echo json_encode($data['php'])?>;
var LanguageChartData = {
	labels : ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
	datasets : languageDataSet
}

window.onload = function(){
var ctx = document.getElementById("canvas_line").getContext("2d");
window.myLine = new Chart(ctx).Line(LanguageChartData, {
	responsive: true
});
}
 
</script>