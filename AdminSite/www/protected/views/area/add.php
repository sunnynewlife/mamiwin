<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写开服时间信息</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<label>活动 Code</label> 
				<input type="text" maxlength="20" name="evn_code" value="" class="input-xlarge" required="true" autofocus="true" />
				
				<label>开服区域名</label>
				<input type="text" maxlength="20" name="area_name" value="" class="input-xlarge" required="true" autofocus="true" />

				<label>区域ID</label>
				<input type="text" name="area_id" maxlength="10"  value="" class="input-xlarge" required="true" autofocus="true" />
				  
				
				<label>开服时间</label> 
				<input type="text" id="start_date" name="start_time" value="" readonly="readonly" />

				<label>天数</label> 
				<input type="text" maxlength="5" id="within_days" name="within_days" value="0" class="input-xlarge" required="true"  autofocus="true"  onkeyup="this.value=this.value.replace(/[^\d]/g,'')" />
				
				
				<input type="hidden" name="submit" value="1" />
				<div class="btn-toolbar">
					<button type="submit" class="btn btn-primary">
						<strong>提交</strong>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>


<script>

Date.prototype.format = function(format)  
{  
   var o = {  
     "M+" : this.getMonth()+1, //month  
     "d+" : this.getDate(),    //day  
     "h+" : this.getHours(),   //hour  
     "m+" : this.getMinutes(), //minute  
     "s+" : this.getSeconds(), //second  
     "q+" : Math.floor((this.getMonth()+3)/3), //quarter  
     "S" : this.getMilliseconds() //millisecond  
   }  
   if(/(y+)/.test(format)) format=format.replace(RegExp.$1,  
     (this.getFullYear()+"").substr(4 - RegExp.$1.length));  
   for(var k in o)if(new RegExp("("+ k +")").test(format))  
     format = format.replace(RegExp.$1,  
       RegExp.$1.length==1 ? o[k] :   
         ("00"+ o[k]).substr((""+ o[k]).length));  
   return format;  
}  

showtime = new Date().format("yyyy-MM-dd hh:mm");
jQuery('#start_date').val(showtime);
jQuery(function () {
	var myDate = new Date();
    // 时间设置
    jQuery('#start_date').datetimepicker({
        timeFormat: "HH:mm",
        dateFormat: "yy-mm-dd"
    });

});

</script>