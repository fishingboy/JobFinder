<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>JobFinder - 功能列表</title>
<style type="text/css">
.canvas {
	width:46%;
	float:left;
	margin:2%;
}
</style>
</head>
<body>
<ul>
	<li><a target='_blank' href='/statistics/update'>更新上月資料</a></li>
<ul>

<div class="canvas">
<h2> 今年語言趨勢</h2>
<a target='_blank' href='/statistics/trend_line/'>放大觀看</a>
<canvas id="canvas_line" ></canvas>
</div>

<div class="canvas">
<h2>上月各語言排名</h2>
<a target='_blank' href='/statistics/rank_bar'>放大觀看</a>
<canvas id="canvas_bar"></canvas>
</div>

<div class="canvas">
<h2>上月語言種類的比例</h2>
<a target='_blank' href='/statistics/skill_pie'>放大觀看</a>
<canvas id="canvas_pie"></canvas>
</div>
</body>

<script type="text/javascript">
<?php 

	foreach($data['trend_line'] as $lang => $char_data)
	{
		$r = rand(0, 255);
		$g = rand(0, 255);
		$b = rand(0, 255);
		$a = (rand(0, 10) / 10);
		$trend_line[] = array(
				"label"                => $lang,
				"strokeColor"          => "rgba({$r},{$g},{$b},{$a})",
				"pointColor"           => "rgba({$r},{$g},{$b},{$a})",
				"pointStrokeColor"     => "#fff",
				"pointHighlightFill"   => "#fff",
				"pointHighlightStroke" => "rgba({$r},{$g},{$b},{$a})",
				"data"                 => $char_data
		);
	}
	
	foreach($data['bar_char'] as $lang => $total)
	{
		$language_list[] = $lang;
		$bar_data[]     = $total;
	}
	
	$r = rand(0, 255);
	$g = rand(0, 255);
	$b = rand(0, 255);
	$a = (rand(0, 10) / 10);
	$bar_char = array(
			"labels"             => $language_list,
			"datasets"           => array(
					array(
							"fillColor"         => "rgba({$r},{$g},{$b},{$a})",
							"strokeColor"       => "rgba({$r},{$g},{$b},{$a})",
							"highlightFill"     => "rgba({$r},{$g},{$b},{$a})",
							"highlightStroke"   => "rgba({$r},{$g},{$b},{$a})",
							"data"              => $bar_data
					)
			),
	);
	
	foreach($data['pie_char'] as $skill => $total)
	{
		$label = (is_numeric($skill)) ? $language[$skill] :  $skill ;
		$r = rand(0, 255);
		$g = rand(0, 255);
		$b = rand(0, 255);
		$a = (rand(0, 10) / 10);
		$pie_char[] = array(
				"label"        => $label,
				"color"         => "rgba({$r},{$g},{$b},{$a})",
				"highlight"     => "rgba({$r},{$g},{$b},{$a})",
				"value"         => $total
				
		);
	}
?>

//line chart
var LineDataSet = <?php echo json_encode($trend_line)?>;
var LanguageChartData = {
	labels : ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
	datasets : LineDataSet
}

var pieData = <?php echo json_encode($pie_char, JSON_PRETTY_PRINT)?>;

var barData = <?php echo json_encode($bar_char, JSON_PRETTY_PRINT)?>;

window.onload = function(){

	var ctx = document.getElementById("canvas_pie").getContext("2d");
	window.myLine = new Chart(ctx).Pie(pieData, {
		responsive: true
	});
	
	var ctx = document.getElementById("canvas_bar").getContext("2d");
	window.myLine = new Chart(ctx).Bar(barData, {
		responsive: true
	});
	
	var ctx = document.getElementById("canvas_line").getContext("2d");
	window.myLine = new Chart(ctx).Line(LanguageChartData, {
		responsive: true
	});
}
 
</script>

<script src="http://jobfinder/js/3rd-party/jquery-2.1.4.min.js" charset="utf-8"></script>
<script type="text/javascript" src="/js/Chart.js"></script>

</html>
