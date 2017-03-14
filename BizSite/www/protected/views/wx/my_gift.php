<!-- gift_box -->
<div class="gift_box">
	<div class="gift_list">
		<ul class="gift_list_ul"  style="margin-bottom: 12px;">
<?php
$rowsHtmlTag = <<<EndOfRowTag
			<li class="my_gift_item %s" id="GIFT_ID_%s" style="padding-bottom:7px;">
				<div class="info_box" style="padding:7px 0 6px 6px;">
					<div class="r">
						%s
					</div>
					%s
					<div class="info">
						<h3>
							%s %s %s %s 
						</h3>
						<p>有效期：%s</p>
					</div>
				</div>
					<div class="pop_box" style="padding:0 5px;">
						<span class="pop_arrow"></span>
						<div class="pop_main">
							<p %s style="overflow:hidden;text-overflow: ellipsis;white-space: nowrap;text-overflow:ellipsis;display:block;clear:both;text-align:center">%s</p>
						</div>
					</div>				
			</li>
EndOfRowTag;

foreach ($gift_list as $row) {
	$IDX = $row['IDX'];
	$Name = $row['Name'];
	$FileId  = $row['FileId'];
	$FileUrl  = $row['FileUrl'];
	$appConfig=LunaConfigMagt::getInstance()->getAppConfig();
	$ImageUrl = $appConfig["FApp_game_domain"] . $FileUrl;

	$AppId  = $row['AppId'];
	$StartDt  = $row['StartDt'];
	$EndDt  = $row['EndDt'];
	$showCss = "";	
	$showLinkBtn = '<a href="/wx/giftDetail?giftid='.$IDX.'&BACK_URL='.urlencode($CURRENT_URL."#GIFT_ID_".$IDX).'" class="btn">查看</a>';
	$showLinkImg = '<a href="/wx/gameDetail?AppId='.$AppId.'&BACK_URL='.urlencode($CURRENT_URL."#GIFT_ID_".$IDX).'"><img src="'.$ImageUrl.'" alt="" width="50" class="l"></a>';
	$ContentClickStr=sprintf(" onclick=\"javascript:location='/wx/giftDetail?giftid=%s&BACK_URL=%s';\" ",$IDX,urlencode($CURRENT_URL."#GIFT_ID_".$IDX));
	if(!empty($EndDt)){
		$EndDt = substr($EndDt, 0,10);
		$ifExpires = strtotime(date("Y-m-d")) - strtotime($EndDt); 
		if($ifExpires > 0){
			$showCss = "gift_out";	
			$showLinkBtn = '<span class="flag_out"></span>';
			$showLinkImg = '<img src="'.$ImageUrl.'" alt="" width="50" class="l">';
			$ContentClickStr="";
		}
	}else{
		$EndDt = "永久有效";
	}
	

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
	$Category  = $row['Category'];
	
	echo sprintf($rowsHtmlTag,
			$showCss,$IDX,$showLinkBtn,$showLinkImg,$Name, 
			$TagType1Name,$TagType2Name,$TagType3Name,$EndDt,$ContentClickStr,
			$row["Code"]
		);		
}
?>			
		</ul>
	</div>
</div>
<!-- /gift_box -->