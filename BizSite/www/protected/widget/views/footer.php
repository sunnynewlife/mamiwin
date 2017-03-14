<?php 
if($MenuShow){
?>
<div class="footer">
	<ul class="menu">
		<li><a href="<?php echo $A1["url"];?>" class="<?php echo $A1["class"];?>">游戏</a></li>
		<li><a href="<?php echo $A4["url"];?>" class="<?php echo $A4["class"];?>">活动</a></li>
		<li><a href="<?php echo $A2["url"];?>" class="<?php echo $A2["class"];?>">礼包</a></li>
		<li><a  id="mineMenu" href="javascript:void(0)" class="<?php echo $A3["class"];?>" ><i class="icon_my"></i>我的</a></li>
	</ul>
</div>

<!--“我的”子菜单-->
<div id="upPop" class="upPop" style="display:none" >
	<div class="gift"><a class="<?php echo $A31["class"];?>" href="<?php echo $A31["url"];?>">我的礼金</a></div>
	<div class="package"><a class="<?php echo $A32["class"];?>" href="<?php echo $A32["url"];?>">我的礼包</a></div>
	<div class="package"><a class="<?php echo $A33["class"];?>" href="<?php echo $A33["url"];?>">我的礼券</a></div>
<?php
	if($DevelopingFunc){
?>
	<div class="package"><a class="menu_item" href="/wx/loginEvn">登录送测试</a></div>
<?php
	} 
?>
</div>
<?php
	} 
?>

<script type="text/javascript">
var _JSSDK_CONFIG_DATA={
	"appId":"<?php echo $JSSDKConfig["appId"];?>",
	"timestamp":"<?php echo $JSSDKConfig["timestamp"];?>",
	"nonceStr":"<?php echo $JSSDKConfig["nonceStr"];?>",
	"signature":"<?php echo $JSSDKConfig["signature"];?>"
}

var _JSSDK_SHARE_DATA={
	"img":"<?php echo $JSSDKShareInfo["img"];?>",
	"share":"<?php echo $JSSDKShareInfo["share"];?>",
	"send_title":"<?php echo $JSSDKShareInfo["send_title"];?>",
	"send_desc":"<?php echo $JSSDKShareInfo["send_desc"];?>",
	"page_id":"<?php echo $JSSDKShareInfo["page_id"];?>"	
}
</script>

<script type="text/javascript">
(function(d, s) {
    window.config ={
        bw_enabled:false,
        bw_base:"http://static.sdg-china.com/yxzm/pic/",
        siteid:"SDG-08227-01"
    };
    var js =d.createElement(s);
    var sc = d.getElementsByTagName(s)[0];
    js.src="http://static.sdg-china.com/yxzm/js/ac.js";
    sc.parentNode.insertBefore(js, sc);
}(document, "script"));
</script>
