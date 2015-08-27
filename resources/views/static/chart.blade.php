
<head>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/d3/3.5.5/d3.min.js"></script>
</head>
 <body>
 
 </body>
 <script type="text/javascript">
 var death_rate = [['越南',24.26],['阿魯巴',17.48],['關島',10.01],['澳門',5.84]];
 var div_data_bind = d3.select("body").selectAll("div").data(death_rate);
 div_set = div_data_bind.enter().append("div");
 div_data_bind.exit().remove();
 div_set.text(function(d,i) {
   return i + " / " + d[0];
 });
 </script>