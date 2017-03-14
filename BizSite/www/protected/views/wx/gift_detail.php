
<?php
	$TotalCount = $gift_detail['TotalCount'];
	$RestCount = $gift_detail['RestCount'];
	$PageRestCount  = $gift_detail['PageRestCount'];
	$GiftCount = $gift_detail['GiftCount'];
	if(is_null($GiftCount)){
		$GiftCount = 0 ;
	}
	if(is_null($PageRestCount) || empty($PageRestCount)){
		$PageRestCount = $GiftCount;
	}
	if(is_null($TotalCount) || empty($TotalCount)){
		$TotalCount = $GiftCount;
	}
	$DrawCount = $gift_detail['DrawCount'];
	if(is_null($DrawCount)){
		$DrawCount = 0 ;
	}
	$ShowRestCount = $PageRestCount - $DrawCount ; 
	if(!is_numeric($ShowRestCount) || !is_numeric($RestCount) || $RestCount <= 0 || !is_numeric($GiftCount) || $GiftCount <= 0 || $ShowRestCount < 0 ){
		$ShowRestCount = 0 ;
	}

	$gift_endDt = $gift_detail['EndDt'];
	if(!empty($gift_endDt) && substr($gift_endDt, 0,4) != "0000"){
		$gift_endDt = substr($gift_endDt, 0,10);
	}else{
		$gift_endDt = "永久有效";
	}
	
	$FileUrl  = $gift_detail['FileUrl'];
	$appConfig=LunaConfigMagt::getInstance()->getAppConfig();
	$ImageUrl = $appConfig["FApp_game_domain"] . $FileUrl;

	$download = $gift_detail['download'];
	$cur_appId = $gift_detail['AppId'];
?>
<div class="detail_wrap">
	<div class="detail_box">
		<div class="d_img">
		
			<a href="/wx/gameDetail?AppId=<?php echo $cur_appId;?>&BACK_URL=<?php echo urlencode($CURRENT_URL);?>"><img src="<?php echo($ImageUrl);?>" alt="" width="60"></a>
		</div>
		<div class="d_info">
			<a  id="downLoadGame" data-href="<?php echo $download;?>" target="_blank" class="btn r">下载</a>
			<h3 class="d_title"><?php echo($gift_detail['Name']);?></h3>
			<div class="d_act">
				<span>剩余：<i class="color_red"><?php echo($ShowRestCount);?></i>/<?php echo($TotalCount);?>
				</span> <span>有效期：<?php echo($gift_endDt);?></span>
			</div>
		</div>
<?php
	if($gift_code){
		$code = $gift_code[0]['Code'];
		$code_status = '<a href="#" class="btn btn_num">'. $code .'</a>';
	}else if($ShowRestCount <= 0){
		$code_status = '<a href="#" class="btn btn_gray">已抢完</a>';
	}else{
		$code_status = '<a href="/wx/giftCodeDraw?giftid='. $giftid .'" class="btn btn_get">立即领取</a> ';
	}
echo sprintf($code_status);		
?>
	</div>

</div>
<div class="des_box">
	<div class="tab_box">
		<ul>
			<li class="itab_item"><a href="javascript:void(0);" id="i_link_1"
				class="cur">礼包详情</a></li>
			<li class="itab_item"><a href="javascript:void(0);" id="i_link_2">使用方法</a>
			</li>
		</ul>
	</div>
	<!-- 礼包详情 -->
	<div class="des_content" style="display: none;" id="i_con_1">
		<div class="all_gift">
			<?php echo($gift_detail['Guide']);?>
		</div>
	</div>
	<!-- /礼包详情 -->
	<!-- 使用方法 -->
	<div class="des_content" id="i_con_2">
		<div class="intro_box">
			<div class="intro_m">
				<?php echo($gift_detail['Content']);?>
				</div>
		</div>
	</div>
	<!-- /使用方法 -->
</div>
<div id="relativegift" class="relate_list">
	<h3>相关礼包</h3>
	<ul>
<?php
$rowsHtmlTag = <<<EndOfRowTag
<li>%s %s %s %s %s</li>
EndOfRowTag;
// 这里展示的相关礼包是同一个游戏其他礼包的信息
$relativegifti = 0 ;
foreach ($gift_list as $row) {
	$IDX = $row['IDX'];
	$Name = $row['Name'];
	$FileId  = $row['FileId'];
	$FileUrl  = $row['FileUrl'];
	$appConfig=LunaConfigMagt::getInstance()->getAppConfig();
	$ImageUrl = $appConfig["FApp_game_domain"] . $FileUrl;

	$AppId  = $row['AppId'];
	$TotalCount  = $row['TotalCount'];
	$RestCount  = $row['RestCount'];
	$PageRestCount  = $row['PageRestCount'];
	$GiftCount = $row['GiftCount'];
	if(is_null($GiftCount)){
		$GiftCount = 0 ;
	}
	if(is_null($PageRestCount) || empty($PageRestCount)){
		$PageRestCount = $GiftCount;
	}
	$DrawCount = $row['DrawCount'];
	if(is_null($DrawCount)){
		$DrawCount = 0 ;
	}
	$ShowRestCount = $PageRestCount - $DrawCount ; 
	if(!is_numeric($ShowRestCount) || !is_numeric($RestCount) || $RestCount <= 0 || !is_numeric($GiftCount) || $GiftCount <= 0 || $ShowRestCount < 0 ){
		$ShowRestCount = 0 ;
	}
	
	$giftCodeCount  = $row['giftCodeCount'];
	if($giftCodeCount > 0 ){
		$drawLink = '<a href="/wx/giftDetail?giftid='.$IDX. '&BACK_URL='.urlencode($CURRENT_URL).'" class="list_item"><span class="color_green r">已领取</span>';
	}else if($ShowRestCount <= 0 ){
		$drawLink = '<a href="/wx/giftDetail?giftid='.$IDX. '&BACK_URL='.urlencode($CURRENT_URL).'" class="list_item"><span class="color_gray r">已抢完</span>';
	}else{
		$drawLink = '<a href="/wx/giftDetail?giftid='.$IDX. '&BACK_URL='.urlencode($CURRENT_URL).'" class="list_item"><span class="color_red r">速领</span>';
	}	
	$StartDt  = $row['StartDt'];
	$EndDt  = substr($row['EndDt'], 0,10);
	$Content  = $row['Content'];
	$Guide  = $row['Guide'];
	$TagType1  = $row['TagType1'];
	$TagType1Name = "";
	if($TagType1 == "1"){
		$TagType1Name = '<span class="tab tab_blue">' . WxController::$fieldListMap['gift_tagtype']['TagType1'] . "</span>";
	}
	$TagType2  = $row['TagType2'];
	$TagType2Name = "";
	if($TagType2 == "1"){
		$TagType2Name = '<span class="tab tab_green">' . WxController::$fieldListMap['gift_tagtype']['TagType2'] . "</span>";
	}
	$TagType3  = $row['TagType3'];
	$TagType3Name = "";
	if($TagType3 == "1"){
		$TagType3Name = '<span class="tab tab_ora">' . WxController::$fieldListMap['gift_tagtype']['TagType3'] . "</span>";
	}
	$linkUrl = "/wx/giftDetail?giftid=".$IDX;
	$GiftOrder  = $row['GiftOrder'];
	$Category  = $row['Category'];

	if($cur_appId == $AppId && $giftid != $IDX ){
		if(empty($EndDt)==true || (empty($EndDt)==false && strtotime($EndDt) >= strtotime(date("y-m-d")))){
			$relativegifti++;
			echo sprintf($rowsHtmlTag,
					$drawLink,$Name,$TagType1Name,$TagType2Name,$TagType3Name
				);		
		}
	}
}
?>		
	</ul>
</div>

<script type="text/javascript">
  $(function(){
    $("#i_link_1").on("tap",function(){
        $(this).parent().siblings(".itab_item").find("a").removeClass("cur");
        $(this).addClass("cur");
        $("#i_con_1").hide();
        $("#i_con_2").show();
    });
    $("#i_link_2").on("tap",function(){
        $(this).parent().siblings(".itab_item").find("a").removeClass("cur");
        $(this).addClass("cur");
        $("#i_con_1").show();
        $("#i_con_2").hide();
    });
  	<?php
  		if($relativegifti <= 0 ){
  			echo('$("#relativegift").hide();');
  		}
  	?>
})
 </script>