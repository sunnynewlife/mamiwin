<style>
<?php echo $Style;?>
</style>
<div class="detail_wrap">
	<div class="detail_box">
		<div class="d_img">
			<img src="<?php echo $GameIcon;?>" alt="" width="80">
		</div>
		<div class="d_info">
			<h3 class="d_title">
				<a href="<?php echo $DownloadUrl;?>" class="btn download-button">下载</a><?php echo $GameName;?>
			</h3>
			<div class="d_act">
				<span>类型：<?php echo $GameType;?></span> <span>大小：<?php echo $Size;?>M</span>
			</div>
			<div class="d_act">
				<span>适用系统：安卓手机</span>
			</div>			
		</div>
	</div>
	<div class="context_box">
		<p><?php echo $AppDetail;?></p>
		<div class="swipe"  id="slider">
			<div class="swipe-wrap">
			<?php 
				$posIndicator="";
				$classAddName=" active";
				foreach ($GamePics as $picKey => $pic){
					echo sprintf("<div><a href='#'><img src='%s' %s></a></div>",$pic,"width='100%'");
					$posIndicator.=sprintf("<a class='bullet%s' href='#'></a>",$classAddName);
					$classAddName="";
				}
			?>
			</div>
		</div>
		<div class="slidePosIndicator" id="featuredMobileInd">
			<?php echo $posIndicator;?>
		</div>		
	</div>
</div>
<script>
	document.title="<?php echo $PageTitle;?>";
    var shareImgUrl 	= "<?php echo $PageShareImg;?>";
    var sharePageUrl	= window.location.href;
    var shareDesc		= "<?php echo $PageDesc; ?>";
    var shareTitle = "<?php echo $PageTitle;?>";
    var appid = '';

function shareFriend() 
{
	try
	{
        WeixinJSBridge.invoke('sendAppMessage',{
            "appid": appid,
            "img_url": shareImgUrl,
            "img_width": "200",
            "img_height": "200",
            "link": sharePageUrl,
            "desc": shareDesc,
            "title": shareTitle
        	}, function(res) {
        });
    }
    catch(ex){}
}
function shareTimeline() 
{
	try
	{
        WeixinJSBridge.invoke('shareTimeline',{
            "img_url": shareImgUrl,
            "img_width": "200",
            "img_height": "200",
            "link": sharePageUrl,
            "desc": shareDesc,
            "title": shareTitle
        	}, function(res) {
        });
	}
	catch(ex){}
}
function shareWeibo() 
{
	try
	{
        WeixinJSBridge.invoke('shareWeibo',{
            "content": shareDesc,
            "url": sharePageUrl,
        }, function(res) {
        });
	}
	catch(ex){}
}

try
{
    document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
        WeixinJSBridge.on('menu:share:appmessage', function(argv){
            shareFriend();
        });
        WeixinJSBridge.on('menu:share:timeline', function(argv){
            shareTimeline();
        });
        WeixinJSBridge.on('menu:share:weibo', function(argv){
            shareWeibo();
        });
    }, false);	
}
catch(ex){}
</script>