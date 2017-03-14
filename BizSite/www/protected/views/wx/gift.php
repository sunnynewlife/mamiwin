<div class="gift_wrap">
	<!-- gift_box -->
	<div id="category1" class="gift_box">
		<div class="gift_t">
			<h3>热门礼包</h3>
		</div>
		<div class="gift_list">
			<ul>
<?php
$backUrl =  urlencode($CURRENT_URL);
$rowsHtmlTag = <<<EndOfRowTag
				<li id="GIFT_ID%s" %s>
					<div class="info_box">
						<div class="r">
							%s
						</div>
						<a href="/wx/gameDetail?AppId=%s&BACK_URL=%s"><img src="%s" alt="" width="50" class="l"></a>
						<div class="info">
							<h3>
								<a href="/wx/giftDetail?giftid=%s">%s</a> %s %s %s
							</h3>
							<p>剩余：%s</p>
						</div>
					</div>
					<div class="pop_box">
						<span class="pop_arrow"></span>
						<div class="pop_main">
							<p onclick="javascript:location='/wx/giftDetail?giftid=%s';" style="overflow:hidden;text-overflow: ellipsis;white-space: nowrap;text-overflow:ellipsis;display:block;clear:both;">%s</p>
						</div>
					</div>
				</li>
EndOfRowTag;
$category1i = 1 ;
$lastHotIdx="";
foreach ($gift_list as $rowTheRow) {
	if($rowTheRow['Category'] == WxController::$fieldListMap['gift_category']['Category1']  && $category1i <= WxController::$fieldListMap['gift_category1_count']){
		$category1i++;
		$lastHotIdx=$rowTheRow['IDX'];
	}
}

$category1i = 1 ;
$category1Ids = array();
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
	if(!is_numeric($ShowRestCount) || !is_numeric($RestCount) || $RestCount <= 0|| !is_numeric($GiftCount) || $GiftCount <= 0 || $ShowRestCount < 0 ){
		$ShowRestCount = 0 ;
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
	$GiftOrder  = $row['GiftOrder'];

	$giftCodeCount  = $row['giftCodeCount'];
	if($giftCodeCount > 0 ){
		$drawLink = '<a href="#" class="btn btn_1" style="color: #00b021;border:1px solid #00b021;">已领取</a>';
	}else if($ShowRestCount <= 0 ){
		$drawLink = '<span class="flag_suc"></span>';
	}else{
		$drawLink = '<a href="/wx/giftDetail?giftid='.$IDX.'" class="btn">速领</a>';
	}

	$Category  = $row['Category'];
	
	if($Category == WxController::$fieldListMap['gift_category']['Category1']  && $category1i <= WxController::$fieldListMap['gift_category1_count']){
		$category1i++;
		$category1Ids[] = $IDX;
		$removeLineStyle="";
		if($IDX==$lastHotIdx){
			$removeLineStyle="style=\"border-bottom:none;\"";
		}
		echo sprintf($rowsHtmlTag,
				$IDX,$removeLineStyle,$drawLink,$AppId,$backUrl,$ImageUrl,$IDX,$Name,
				$TagType1Name,$TagType2Name,$TagType3Name,
				$ShowRestCount,$IDX,$Content
			);
				
	}
}
?>
			</ul>
		</div>
	</div>
	<!-- /gift_box -->
	<!-- gift_box -->
	<div class="gift_box">
		<div id="category2" class="gift_t">
			<h3>其他礼包</h3>
		</div>
		<div class="gift_list">
			<ul>
<?php
$rowsHtmlTag = <<<EndOfRowTag
				<li id="GIFT_ID%s" %s>
					<div class="info_box">
						<div class="r">
							%s
						</div>
						<a href="/wx/gameDetail?AppId=%s&BACK_URL=%s"><img src="%s" alt="" width="50" class="l"></a>
						<div class="info">
							<h3>
								<a href="/wx/giftDetail?giftid=%s">%s</a> %s %s %s
							</h3>
							<p>剩余：%s</p>
						</div>
					</div>
					<div class="pop_box">
						<span class="pop_arrow"></span>
						<div class="pop_main">
							<p onclick="javascript:location='/wx/giftDetail?giftid=%s';" style="overflow:hidden;text-overflow: ellipsis;white-space: nowrap;text-overflow:ellipsis;display:block;clear:both;">%s</p>
						</div>
					</div>
				</li>
EndOfRowTag;

$lastOtherIdx="";
foreach ($gift_list as $rowTheOtherRow) {
	if($rowTheOtherRow['Category'] == WxController::$fieldListMap['gift_category']['Category2'] || !in_array($rowTheOtherRow["IDX"], $category1Ids)){
		$lastOtherIdx=$rowTheOtherRow['IDX'];
	}
}

$category2i = 0 ;
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
	$GiftOrder  = $row['GiftOrder'];
	$giftCodeCount  = $row['giftCodeCount'];
	if($giftCodeCount > 0 ){
		$drawLink = '<a href="#" class="btn btn_1" style="color: #00b021;border:1px solid #00b021;">已领取</a>';
	}else if($ShowRestCount <= 0 ){
		$drawLink = '<span class="flag_suc"></span>';
	}else{
		$drawLink = '<a href="/wx/giftDetail?giftid='.$IDX.'" class="btn">速领</a>';
	}

	$Category  = $row['Category'];
	if($Category == WxController::$fieldListMap['gift_category']['Category2'] || !in_array($IDX, $category1Ids)){
		$category2i++;
		$removeLineStyle="";
		if($IDX==$lastOtherIdx){
			$removeLineStyle="style=\"border-bottom:none;\"";
		}
		echo sprintf($rowsHtmlTag,
				$IDX,$removeLineStyle,$drawLink,$AppId,$backUrl,$ImageUrl,$IDX,$Name,
				$TagType1Name,$TagType2Name,$TagType3Name,
				$ShowRestCount,$IDX,$Content
			);		
	}

}
?>

			</ul>
		</div>
	</div>
	<!-- /gift_box -->
</div>
<script type="text/javascript">
  $(function(){
  	<?php
  		if($category1i <= 1 ){
  			echo('$("#category1").hide();');
  		}
  		if($category2i <= 0 ){
  			echo('$("#category2").hide();');
  		}
  	?>
    
})
 </script>