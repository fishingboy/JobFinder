
<head>
<!--  <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/d3/3.5.5/d3.min.js"></script>  -->
<script src="http://jobfinder/js/3rd-party/jquery-2.1.4.min.js" charset="utf-8"></script>
<script type="text/javascript" src="/js/Chart.js"></script>
</head>
<body>
<div style="width:60%">
<canvas id="canvas" width="450" height="600"></canvas>
</div>

</div>
</body>
<script type="text/javascript">
<?php 

	///*
	foreach($data as $skill => $total)
	{
		$label = (is_numeric($skill)) ? $language[$skill] :  $skill ;
		$r = rand(0, 255);
		$g = rand(0, 255);
		$b = rand(0, 255);
		$a = (rand(0, 10) / 10);
		$dataset[] = array(
				"label"        => $label,
				"color"         => "rgba({$r},{$g},{$b},{$a})",
				"highlight"     => "rgba({$r},{$g},{$b},{$a})",
				"value"         => $total
				
		);
	}
	
	
	
	//*/
?>

var pieData = <?php echo json_encode($dataset, JSON_PRETTY_PRINT)?>;


window.onload = function(){
var ctx = document.getElementById("canvas").getContext("2d");
window.myLine = new Chart(ctx).Pie(pieData, {
	responsive: true
});
}
 
</script>