<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写配置信息</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<label>专题中文名称</label> 
				<input  name="topic_name" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<br/>
				<label>专题英文名称</label> 
				<input  name="topic_name_en" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<br/>
<!-- 				<label>游戏推荐列表图片</label> 
				<input  name="game_img" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<br/> -->
				<label>状态</label> 
				<select name="state">
					<option value="1">灰度</option>
					<option value="2">上架</option>
					<option value="0">下架</option>
				</select>
				<br/>
				<label>序号</label> 
				<input  name="topic_no" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<br/>
				<label>归类展示(例如在热门推荐模块展示,请选择“热门推荐”)</label> 
				<select name="flag">
				    <option value="">Null</option>
					<option value="hot">热门推荐</option>
					<!--<option value="new">新上架</option>-->
				</select>
				<br/>
<!-- 				<label>游戏宣传照(支持多图,用半角逗号　,　分割)</label> 
				<textarea name="adv_pics" rows="5" class="input-xlarge" required="true" autofocus="true" value=""></textarea>
				<br/> -->
<!-- 				<label>开始时间(当前时间不早于该设置时间时，该信息才被视为有效数据)</label> 
				<input  id="topic_start_time" name="topic_start_time" rows="5" class="input-xlarge"  class="hasDatepicker" required="true" autofocus="true" value=""/>
				<br/>
				<label>结束时间(请注意把结束时间合理设置，在结束时间外，该信息将视为无效数据)</label> 
				<input  id="topic_end_time" name="topic_end_time" rows="5" class="input-xlarge" class="hasDatepicker" required="true" autofocus="true" value=""/> -->
				<input type="hidden" name="update_time" value="<?php echo date("Y-m-d H:i:s");?>" />
				<input type="hidden" name="os_type" value="<?php echo Yii::app()->request->getParam('os_type',1);?>"> 
				<input type="hidden" name="in_version" value="<?php echo Yii::app()->request->getParam('in_version','2.0');?>"> 
				<input type="hidden" name="submit" value="1" />
				<div class="btn-toolbar">
					<button type="submit" class="btn btn-primary" onclick="doConvert();">
						<strong>提交</strong>
					</button>
				</div>
			
			</form>
		</div>
	</div>
</div>
<?php $this->widget('application.widget.JsonEditor'); ?>
<script type="text/javascript">
/* function jsonFormatChange()
{
	if($("#jsonFormater").attr("checked")=="checked"){
		$("#btnJsonEdit").show();
	}else{
		$("#mask").hide();
		$("#btnJsonEdit").hide();
	}
}
function doJsonEdit()
{
	showJsonEditor($("#config_value").val());   
} */
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
//jQuery('#topic_start_time').val(showtime);
//jQuery('#topic_end_time').val(showtime);
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
