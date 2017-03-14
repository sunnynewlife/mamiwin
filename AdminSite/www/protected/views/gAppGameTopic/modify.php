<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写游戏配置信息</a></li>
	</ul>
	
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<label>专题名称</label> 
				<input  name="topic_name" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $t_game_topic["topic_name"];?>"/>
				<br/>
				<label>专题英文名称</label> 
				<input  name="topic_name_en" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $t_game_topic["topic_name_en"];?>"/>
				<br/>
				<label>状态</label> 
				<select name="state">
					<option value="1" <?php echo $t_game_topic["state"]==1?"selected":"";?> >灰度</option>
					<option value="2" <?php echo $t_game_topic["state"]==2?"selected":"";?>>上架</option>
					<option value="0" <?php echo $t_game_topic["state"]==0?"selected":"";?>>下架</option>
				</select>
				<br/>
				<label>序号</label> 
				<input  name="topic_no" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $t_game_topic["topic_no"];?>"/>
				<br/>
				<label>归类展示(例如在热门推荐模块展示,请选择“热门推荐”)</label> 
				<select name="flag">
					<option value="">Null</option>
					<option value="hot" <?php echo $t_game_topic["flag"]=='hot'?"selected":"";?> >热门推荐</option>
					<!--<option value="new" <?php echo $t_game_topic["flag"]=='new'?"selected":"";?> >新上架</option>  -->
				</select>
				<!-- 
				<label>开始时间(当前时间不早于该设置时间时，该信息才被视为有效数据)</label>  
				<input  id="topic_start_time" name="topic_start_time" rows="5" class="input-xlarge"  class="hasDatepicker" required="true" autofocus="true" value="<?php echo $t_game_topic["topic_start_time"];?>"/>
				<br/>
				<label>结束时间(请注意把结束时间合理设置，在结束时间外，该信息将视为无效数据)</label> 
				<input  id="topic_end_time" name="topic_end_time" rows="5" class="input-xlarge" class="hasDatepicker" required="true" autofocus="true" value="<?php echo $t_game_topic["topic_end_time"];?>"/>
				 -->
				<input type="hidden" name="update_time" value="<?php echo date("Y-m-d H:i:s");?>" />
				<input type="hidden" name="os_type" value="<?php echo $t_game_topic["os_type"];?>"> 
				<input type="hidden" name="in_version" value="<?php echo $t_game_topic["in_version"];?>"> 
				<br/>
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

showtime = new Date().format("yyyy-MM-dd hh:mm:ss");
jQuery(function () {
	var myDate = new Date();
    // 时间设置
    jQuery('#topic_start_time').datetimepicker({
        timeFormat: "HH:mm:ss",
        dateFormat: "yy-mm-dd"
    });
	jQuery('#topic_end_time').datetimepicker({
        timeFormat: "HH:mm:ss",
        dateFormat: "yy-mm-dd"
    });

});

</script>