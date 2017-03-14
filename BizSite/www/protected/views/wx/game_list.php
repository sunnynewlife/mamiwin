<div class="swipe_box">
	<div class="swipe" id="slider">
		<div class="swipe-wrap">
<?php
	$slidPosHtml="";
	$addExtClassName=" active";
	foreach ($ad as $adItem){
		echo sprintf("<div><a href='%s'><img src=\"%s\" height=\"156px;\" /></a></div>",$adItem["url"],$adItem["pic"]);
		$slidPosHtml.=sprintf("<a class=\"bullet%s\" href=\"#\"></a>",$addExtClassName);
		$addExtClassName="";
	} 
?>		
		</div>
	</div>
	<div class="slidePosIndicator" id="featuredMobileInd">
		<?php  echo $slidPosHtml;?>
	</div>
</div>
<div class="gift_box index">
	<div class="index_t">
		<a href="/wx/home" <?php echo $sort=="default"?" class=\"color_red\"":"";?> >默认排序</a> 
		<a href="/wx/home?sort=desc" <?php echo $sort=="desc"?" class=\"color_red\"":"";?>>返现比例<i class="icon_down"></i></a> 
		<a href="/wx/home?sort=asc" <?php echo $sort=="asc"?" class=\"color_red\"":"";?>>返现比例<i class="icon_up"></i></a>
	</div>
	
	<div class="gift_list">
		<ul>
<?php
$rowsHtmlTag = <<<EndOfRowTag
	<li onclick="javascript:location.href='%s'" >			
		<div class="info_box">
			<div class="l">
				<div class="flag">
					<h4>返现</h4>
					<p>%s<span>%s</span></p><span class="arrow"></span>
				</div>
			</div>
			<div class="r">
					<a href="%s" class="btn btn_1">下载</a>
			</div>
			<a href="%s"><img src="%s" alt="" width="50" class="l"></a>
			<div class="info">
				<h3>%s</h3>
				<p>%s<em>%sM</em></p>
				<span class="tab tab_blue" style="display:%s;">活动</span>
				<span class="tab tab_ora" style="display:%s;">礼包</span>
				<span class="tab tab_green" style="display:%s;">首发</span>
				<span class="tab tab_green" style="background-color:#bc4ff6;display:%s;">独家</span>
				<span class="tab tab_green" style="background-color:#a5d600;display:%s;">其他</span>
				<span class="tab tab_green" style="background-color:#FFFFFF;"></span>
			</div>
			<span class="leftt">%s</span>
		</div>
	</li>
EndOfRowTag;

	foreach($games as $game){
		$gameDetailUrl=sprintf("/wx/gameDetail?AppId=%s&BACK_URL=%s",$game["AppId"],urlencode("/wx/home"));
		echo sprintf($rowsHtmlTag,
			$gameDetailUrl,
			$game["Prorate"],"%",$gameDetailUrl,$gameDetailUrl,$game["GameIcon"],
			$game["AppName"],$game["AppType"],$game["Size"],
			$game["having_evn"]=="1"?"":"none",$game["having_gift"]=="1"?"":"none",$game["IsFirst"]=="1"?"":"none",
			$game["IsSingle"]=="1"?"":"none",$game["IsOther"]=="1"?"":"none",
			$game["PromotionStr"]);
	}
?>			
		</ul>
	</div>
</div>
<script type="text/javascript" src="/static/js/swipe.js"></script>
<script type="text/javascript">
function initSlider() 
{
	var bullets = document.getElementById('featuredMobileInd').getElementsByTagName('a');
    window.mySwipe = new Swipe(document.getElementById('slider'), {
                // startSlide: 2,   
                // speed: 400,
                auto: 3000,
                continuous: true
                // disableScroll: false,
                // stopPropagation: false,
                // callback: function(index, elem) {},
                // transitionEnd: function(index, elem) {}
                ,
                callback: function(pos) {
                    var i = bullets.length;
                    while (i--) {
                        bullets[i].className = '';
                    }
                    bullets[pos].className = 'active';
                }
            });
}

// 初始化滑动
initSlider();
</script>