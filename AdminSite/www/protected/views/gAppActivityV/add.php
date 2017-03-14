<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写配置信息</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<label>数据接口版本</label> 
				<input  type="hidden" name="in_version" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo Yii::app()->request->getParam('in_version','2.0');?>"/>
				<label><?php echo Yii::app()->request->getParam('in_version','');?></label>
				<br/>
				<label>活动名称</label> 
				<input  name="activity_name" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<br/>
				<label>平台类型</label> 
				<input  type="hidden" class="os_type" name="os_type" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo Yii::app()->request->getParam('os_type','');?>"/>
				<label><?php echo Yii::app()->request->getParam('os_type','')==1?"Android":"iOS";?></label>
				<br/>
				<label>游戏名称</label> 
			 	<select name="gameid">
					<?php 
					$rowsHtmlTag=<<<EndOfRowTag
					<option value="%s">%s</option>
EndOfRowTag;
				$os_type=Yii::app()->request->getParam('os_type',1);
				$in_version=Yii::app()->request->getParam('in_version','2.0');
				$sql="select gameid,game_name from t_game_config where in_version=".$in_version." and os_type=".$os_type.";";
				$game_list=LunaPdo::GetInstance('GApi')->query_with_prepare($sql,array(),PDO::FETCH_ASSOC);
				$game_name_list=array();
			 	foreach ($game_list as $gameItem){
			 		$game_name_list[$gameItem['gameid']]=$gameItem['game_name'];
			 		$rowHtml=sprintf($rowsHtmlTag,
			 				$gameItem['gameid'],
			 				$gameItem['game_name']);
			 		echo $rowHtml;
			 	}
					?>
				</select>
				<label>活动标题</label> 
				<input  name="activity_title" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<br/>
				<label>活动内容</label> 
				<input  name="activity_content" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<br/>
				<label>活动图片</label> 
				<input  name="activity_img" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<br/>
				<label>游戏标识<span style="font-size: 10px;margin-left:5px">（选择“热门推荐”将在游戏推荐下方展示等模块展示）</span></label>  
				<select name="flag">
					<option value=""></option>
					<option value="hot" style="color:red">热门推荐</option>
					<option value="new">new标识 </option>
					<option value="first">首发 </option>
				</select>
				<br/>
				<label>活动详情页</label> 
				<input  name="activity_detail_url" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<br/>
				<label>活动详情页链接打开方式</label> 
				<select name="activity_url_type" id="activity_url_type">
					<option value="1">webView方式</option>
					<option value="2">APP原生</option>
					<option value="3">浏览器</option>
					<option value="4">webView方式(盛大账号方式)</option>
					<option value="5">APP原生(盛大账号方式)</option>
					<option value="6">浏览器(盛大账号方式)</option>
				</select>
				<br/>
				<div class="act_android_ext app_entrance app_package" style="display:none">
					<h5></h5>
					<label>APP入口地址</label> 
					<input  name="app_entrance" rows="5" class="input-xlarge"  value=""/>
					<br/>
					<label>包名</label> 
					<input  name="pack_name" rows="5" class="input-xlarge"  value=""/>
					<br/>
					<label>下载地址</label> 
					<input  name="pack_download_url" rows="5" class="input-xlarge"  value=""/>
					<br/>
				</div>
				<div class="act_ios_ext" id="act_ios_ext" style="display:none">
					<h5>iOS APP原生方式打开，附加信息</h5>
					<label>Schema</label> 
					<input  name="schema_info" rows="5" class="input-xlarge" value=""/>
					<br/>
					<label>AppStore URL</label> 
					<input  name="appstore_url" rows="5" class="input-xlarge"  value=""/>
					<br/>
					<br/>
				</div>

				<label>活动序号(活动列表中排列序号)</label> 
				<input  name="activity_no" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<br/>
				<label>活动开始时间</label> 
				<input  id="start_date" name="activity_start_time" rows="5" class="input-xlarge" readonly="readonly" class="hasDatepicker" required="true" autofocus="true" value=""/>
				<br/>
				<label>活动结束时间</label> 
				<input  id="end_date" name="activity_end_time" rows="5" class="input-xlarge" required="true" autofocus="true" value=""/>
				<!-- 
				<br/>
				<label>活动时间范围(例如，每天8点到10点)</label> 
				 -->
				<input  type="hidden" id="activity_time_range" name="activity_time_range" rows="5" class="input-xlarge" required="true" autofocus="true" value="1"/>
				<br/>
				<label>关注数<span style="font-size: 10px;margin-left:5px">(可设置初始值，实际下载数依次累加)</span></label>
				<input  name="focus_count" rows="5" class="input-xlarge" autofocus="true" value=""/>
				<br/>
				<label>app_id<span style="font-size: 10px;margin-left:5px">(G家APP客户端做特殊用途,例如ticket校验等)</span></label> 
				<input  name="app_id" rows="5" class="input-xlarge" autofocus="true" value=""/>
				<br/>
				<label>状态</label>
				<select name="state">
					<option value="1"  >灰度</option>
					<option value="2" >上架</option>
					<option value="0" >下架</option>
				</select>
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
<script>
function show_ext(){
	jQuery(".act_ios_ext").hide();
	jQuery(".act_android_ext").hide();
	var os_type=jQuery(".os_type").val();
	var type=jQuery("#activity_url_type").val();
	if(os_type==1){
		switch(type){
			case '2':
				jQuery(".act_android_ext.app_package").show();
				jQuery(".act_android_ext h5").text('Android APP原生，附加信息');
				break;
			case '5':
				jQuery(".act_android_ext.app_entrance").show();
				jQuery(".act_android_ext h5").text('Android APP原生(盛大账号方式)，附加信息');
				break;
			default:
				break;
		}
	}else{
		switch(type){
			case '2':
				jQuery(".act_ios_ext").show();
				break;
			default:
				break;
		}
	}
}
show_ext();
jQuery("#activity_url_type").bind("change",function(){
	show_ext();
});



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
jQuery('#start_date').val(showtime);
jQuery('#end_date').val(showtime);
jQuery(function () {
	var myDate = new Date();
    // 时间设置
    jQuery('#start_date').datetimepicker({
        timeFormat: "HH:mm:ss",
        dateFormat: "yy-mm-dd"
    });
	jQuery('#end_date').datetimepicker({
        timeFormat: "HH:mm:ss",
        dateFormat: "yy-mm-dd"
    });

});

</script>
