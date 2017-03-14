<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写游戏配置信息</a></li>
	</ul>
	
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<label>标题</label> 
				<input  name="title" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $tbl_event_info['title'];?>"/>
				<br/>
				<label>介绍</label> 
				<input  name="info" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $tbl_event_info['info'];?>"/>
				<br/>
				<label>应用ID</label> 
				<input  name="app_id" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $tbl_event_info['app_id'];?>"/>
				<br/>
				<label id="lab_begin_time">开始时间</label> 
				<input id="begin_time" name="begin_time" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $tbl_event_info['begin_time'];?>"/>
				<label id="lab_end_time" >结束时间</label> 
				<input id="end_time" name="end_time" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $tbl_event_info['end_time'];?>"/>
				<label>奖励id,JSON格式</label> 
				<input  name="item" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $tbl_event_info['item'];?>"/>
				<label>status</label> 
				<select name="status">
					<option value="0" <?php echo $tbl_event_info["status"]==0?"selected":"";?> >正常</option>
					<option value="1" <?php echo $tbl_event_info["status"]==1?"selected":"";?>>删除</option>
					<option value="2" <?php echo $tbl_event_info["status"]==2?"selected":"";?>>暂停</option>
				</select>
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
<?php $this->widget('application.widget.JsonEditor'); ?>
<script type="text/javascript">
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

showtime= new Date().format("yyyy-MM-dd hh:mm");
function showtime(time){
	return 
}
var begin_time='<?php echo date('Y-m-d H:i:s',$tbl_event_info['begin_time']);?>';
var end_time='<?php echo date('Y-m-d H:i:s',$tbl_event_info['end_time']);?>';

jQuery('#lab_begin_time').text('开始时间 '+begin_time);
jQuery('#lab_end_time').text('结束时间 '+end_time); 
/* jQuery(function () {
    // 时间设置
     jQuery('#begin_time').datetimepicker({
        timeFormat: "HH:mm:ss",
        dateFormat: "yy-mm-dd"
    });
    jQuery('#end_time').datetimepicker({
        timeFormat: "HH:mm:ss",
        dateFormat: "yy-mm-dd"
    }); 

}); */

</script>
