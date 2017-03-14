<div class="tips">
	<p>
		<i class="icon_alert"></i>已有<span class="color_red"><?php echo $income["count"];?></span>人获利，累计返现<span class="color_red"><?php echo $income["income_summary"]; ?></span>元
	</p>
</div>
<div class="tn_box">
	<ul>
<?php
$rowsHtmlTag = <<<EndOfRowTag
	<li id="EVN_ID_%s" onclick="javascript:location.href='/wx/evn?idx=%s'">
		<div class="tn_item">
			<div class="tn_l">
				<div class="tn_img">
					<span class="tn_flag%s"></span> 
						<a href="/wx/evn?idx=%s"><img src="%s" alt="" width="150"></a>
						<div class="tn_img_b">%s</div>
				</div>
				<div class="tn_time" style="text-align:center;">
					%s~%s
				</div>
			</div>
			<div class="tn_info">
				<h3>%s</h3>
				<div class="tn_bar">
					<div class="tn_per" style="width:%s"></div>
					<span>已参与：%s/%s人</span>
				</div>
				%s
			</div>
		</div>
	</li>
EndOfRowTag;
	foreach ($evn_list as $row){
		$spanClass="";
		$randJoinQty=$row["EvnJoinRandQty"];
		if($row["EvnJoinRandQty"]>=$row["EvnQty"]){
			$spanClass=" tn_flag_suc";
			$randJoinQty=$row["EvnQty"];
		}
		$currPercent=bcdiv($randJoinQty*100,$row["EvnQty"],0)."%";
		$userAction="";
		if($row["UserJoined"]){
			$userAction='<a class="btn btn_evn_end" href="/wx/evn?idx='.$row["idx"].'">已参加</a>';
		}else{
			if(empty($spanClass)){
				$userAction='<a href="/wx/evn?idx='.$row["idx"].'" class="btn" style="color:white;">我要参加</a>';
			}
			else{
				$userAction='<a class="btn btn_evn_end" href="/wx/evn?idx='.$row["idx"].'">查看</a>';
			}
		}
		echo sprintf($rowsHtmlTag,
				$row["idx"],$row["idx"],
				$spanClass,
				$row["idx"],$row["PicUrl"],$row["EvnName"],
				$row["EvnStart"],$row["EvnEnd"],$row["EvnIntro"],
  				$currPercent,$randJoinQty,$row["EvnQty"],$userAction
			); 
	}
?>	
	</ul>
</div>
