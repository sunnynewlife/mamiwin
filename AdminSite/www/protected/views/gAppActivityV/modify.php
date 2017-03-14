<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写游戏配置信息</a></li>
	</ul>
	
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<label>数据接口版本</label> 
				<input  type="hidden" name="in_version" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $t_game_activity["in_version"];?>"/>
				<label><?php echo $t_game_activity["in_version"];?></label>
				<br/>
				<label>活动名称</label> 
				<input  name="activity_name" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $t_game_activity["activity_name"];?>"/>
				<br/>
				<label>活动ID(activity_id)</label> 
				<input  disabled="disabled" name="activity_id" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $t_game_activity["activity_id"];?>"/>
				<br/>
				<label>游戏名称</label> 
			 	<select name="gameid">
					<?php 
					$rowsHtmlTag=<<<EndOfRowTag
					<option value="%s" %s>%s</option>
EndOfRowTag;
				$os_type=$t_game_activity['os_type'];
				$in_version=$t_game_activity['in_version'];
				$sql="select gameid,game_name from t_game_config where os_type=".$os_type." and in_version='".$in_version."';";
				$game_list=LunaPdo::GetInstance('GApi')->query_with_prepare($sql,array(),PDO::FETCH_ASSOC);
				$game_name_list=array();
			 	foreach ($game_list as $gameItem){
			 		$game_name_list[$gameItem['gameid']]=$gameItem['game_name'];
			 		$rowHtml=sprintf($rowsHtmlTag,
			 				$gameItem['gameid'],
			 				$t_game_activity['gameid']==$gameItem['gameid']?'selected':'',
			 				$gameItem['game_name']);
			 		echo $rowHtml;
			 	}
					?>
				</select>
				<br/>
				<label>平台类型</label> 
				<input  type="hidden" class="os_type" name="os_type" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $t_game_activity["os_type"];?>"/>
				<label><?php echo $t_game_activity["os_type"]==1?"Android":"iOS";?></label>
				<label>活动标题</label> 
				<input  name="activity_title" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $t_game_activity["activity_title"];?>"/>
				<br/>
				<label>活动内容</label> 
				<input  name="activity_content" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $t_game_activity["activity_content"];?>"/>
				<br/>
				<label>活动图片</label> 
				<input  name="activity_img" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $t_game_activity["activity_img"];?>"/>
				<br/>
				<label>游戏标识<span style="font-size: 10px;margin-left:5px">（选择“热门推荐”将在游戏推荐下方展示）</span></label>  
				<select name="flag">
					<option value=""></option>
					<option value="hot" <?php echo $t_game_activity["flag"]=="hot"?"selected":"";?> style="color:red">热门推荐</option>
					<option value="new" <?php echo $t_game_activity["flag"]=="new"?"selected":"";?> >new标识 </option>
					<option value="first" <?php echo $t_game_activity["flag"]=="first"?"selected":"";?> >首发 </option>
				</select>
				<br/>
				<label>活动详情页</label> 
				<input  name="activity_detail_url" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $t_game_activity["activity_detail_url"];?>"/>
				<br/>
				<label>活动详情页链接打开方式</label> 
				<select name="activity_url_type" id="activity_url_type">
					<option value="1" <?php echo $t_game_activity["activity_url_type"]==1?"selected":"";?>>webView方式</option>
					<option value="2" <?php echo $t_game_activity["activity_url_type"]==2?"selected":"";?>>APP原生</option>
					<option value="3" <?php echo $t_game_activity["activity_url_type"]==3?"selected":"";?>>浏览器</option>
					<option value="4" <?php echo $t_game_activity["activity_url_type"]==4?"selected":"";?>>webView方式(盛大账号方式)</option>
					<option value="5" <?php echo $t_game_activity["activity_url_type"]==5?"selected":"";?>>APP原生(盛大账号方式)</option>
					<option value="6" <?php echo $t_game_activity["activity_url_type"]==6?"selected":"";?>>浏览器(盛大账号方式)</option>
				</select>
				<div class="act_android_ext app_entrance app_package" style="display:none">
					<h5><?php echo $t_game_activity["activity_url_type"]==5?"Android APP原生(盛大账号方式)，附加信息":"Android APP原生，附加信息";?></h5>
					<label>APP入口地址</label> 
					<input  name="app_entrance" rows="5" class="input-xlarge"  value="<?php echo $t_game_activity['app_entrance'];?>"/>
					<br/>
					<label>包名</label> 
					<input  name="pack_name" rows="5" class="input-xlarge"  value="<?php echo $t_game_activity['pack_name'];?>"/>
					<br/>
					<label>下载地址</label> 
					<input  name="pack_download_url" rows="5" class="input-xlarge"  value="<?php echo $t_game_activity["pack_download_url"];?>"/>
					<br/>
				</div>
				<div class="act_ios_ext" id="act_ios_ext" style="display:none">
					<h5>iOS APP原生方式打开，附加信息</h5>
					<label>Schema</label> 
					<input  name="schema_info" rows="5" class="input-xlarge"  value="<?php echo $t_game_activity["schema_info"];?>"/>
					<br/>
					<label>AppStore URL</label> 
					<input  name="appstore_url" rows="5" class="input-xlarge"  value="<?php echo $t_game_activity["appstore_url"];?>"/>
					<br/>
					<br/>
				</div>
				<br/>
				<label>活动序号(活动列表中排列序号,递增排序)</label> 
				<input  name="activity_no" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $t_game_activity["activity_no"];?>"/>
				<br/>
				<label>活动开始时间</label> 
				<input  id="start_date" name="activity_start_time" rows="5" class="input-xlarge" readonly="readonly" class="hasDatepicker" required="true" autofocus="true" value="<?php echo $t_game_activity["activity_start_time"];?>"/>
				<br/>
				<label>活动结束时间</label> 
				<input  id="end_date" name="activity_end_time" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $t_game_activity["activity_end_time"];?>"/>
				<br/>
				<label>关注数</label> 
				<input  name="focus_count" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $t_game_activity["focus_count"];?>"/>
				<br/>
				<label>app_id</label> 
				<input  name="app_id" rows="5" class="input-xlarge"  autofocus="true" value="<?php echo $t_game_activity["app_id"];?>"/>
				
				<!-- 
				<br/>
				<label>活动时间范围</label> -->
				<input  type="hidden" id="activity_time_range" name="activity_time_range" rows="5" class="input-xlarge" required="true" autofocus="true" value="<?php echo $t_game_activity["activity_time_range"];?>"/>
				<br/>
				<label>状态</label> 
				<select name="state">
					<option value="1" <?php echo $t_game_activity["state"]==1?"selected":"";?> >灰度</option>
					<option value="2" <?php echo $t_game_activity["state"]==2?"selected":"";?>>上架</option>
					<option value="0" <?php echo $t_game_activity["state"]==0?"selected":"";?>>下架</option>
				</select>
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

showtime = new Date().format("yyyy-MM-dd hh:mm");
/* jQuery('#start_date').val(showtime);
jQuery('#end_date').val(showtime); */
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
