<div id="gameProfit" style="height:300px; width:100%;"></div>
<?php
	$yValues=array();
	foreach ($profit as $row){
		$yValues[]=$row["v"];
	}
	$yAxisValue=implode(",",$yValues);
?>
<script class="code" type="text/javascript">
$(document).ready(function(){
  var plot1 = $.jqplot ('gameProfit', 
			[[<?php echo $yAxisValue;?>]],

		  	{ 
	  			title:{text:'<?php echo $AppName;?> 近期日充指数',color:'#FF0000'},
	  			series:[{color:'#FF0000'}],
	  			axes:{
		  			xaxis:{
		  				show:true,
		  				ticks:[
<?php
			$posIndex=1;
			foreach ($profit as $row){
				echo sprintf("%s[%s,\"%s\"]",($posIndex==1?"":","),$posIndex,$row["s"]);
				$posIndex++;
			}
?>
				  			  ]
		  			},
		  			yaxis:{
		  				show:false,
		  				tickOptions:{showMark:true,showLabel:false,showGridline:true}
			  		}
	  			}
			}
		);
});
</script>