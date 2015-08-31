
<head>
<!--  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/d3/3.5.5/d3.min.js"></script>  -->
<script src="http://jobfinder/js/3rd-party/jquery-2.1.4.min.js" charset="utf-8"></script>
<script type="text/javascript" src="/js/Chart.js"></script>
</head>
<body>
<div style="width:60%">
<canvas id="canvas_bar" width="450" height="600"></canvas>
</div>

</div>
</body>
<script type="text/javascript">
<?php 
	///*
	foreach($data as $language => $total)
	{
		$language_list[] = $language;
		$char_data[]     = $total;
	}
	
	$r = rand(0, 255);
	$g = rand(0, 255);
	$b = rand(0, 255);
	$a = (rand(0, 10) / 10);
	$bar_dataset = array(
			"labels"             => $language_list,
			"datasets"           => array(
				array(
					"fillColor"         => "rgba({$r},{$g},{$b},{$a})",
					"strokeColor"       => "rgba({$r},{$g},{$b},{$a})",
					"highlightFill"     => "rgba({$r},{$g},{$b},{$a})",
					"highlightStroke"   => "rgba({$r},{$g},{$b},{$a})",
					"data"              => $char_data
				)
			),
	);
	
	//*/
?>

var MonthChartData = <?php echo json_encode($bar_dataset, JSON_PRETTY_PRINT)?>;


window.onload = function(){
var ctx = document.getElementById("canvas_bar").getContext("2d");
window.myLine = new Chart(ctx).Bar(MonthChartData, {
	responsive: true
});
}
 
</script>