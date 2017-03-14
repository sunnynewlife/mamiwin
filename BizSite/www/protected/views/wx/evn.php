<div class="event_box">
	<div class="detail_wrap">
		<div class="detail_box">
			<div class="d_img">
				<a href="/wx/gameDetail?AppId=<?php echo $app_id;?>&BACK_URL=<?php echo urlencode($CURRENT_URL);?>"><img src="<?php echo $AppLogoImg;?>" alt="" width="60"></a>
			</div>
			<div class="d_info">
				<a  id="downLoadGame" data-href="<?php echo $download;?>" target="_blank" class="btn r" >下载</a>
				<div class="d_act">
					<span>活动对象：<?php echo $join_user;?></span>
					<span>保底返现：<?php echo $base_prorate;?>%</span>
					<span>当前返现：<i class="color_red"><?php echo $curr_prorate;?>%</i></span>
					<span>适用系统：Android（安卓）</span>
				</div>
			<?php
				if($join_status=="1"){
					echo '<div class="flag_joined"></div>';
				}				 
			?>
			</div>
			<?php
				if($join_status=="2"){
					echo '<a href="/wx/evnJoin?idx='.$evn_idx.'" class="btn btn_get">我要参加</a>';
				} 
			?>						
		</div>
	</div>
	<div class="my_list_box" style="display:<?php echo count($gift_list)>0?"":"none"; ?>;">
		<ul class="my_list">
<?php
$rowsHtmlTag = <<<EndOfRowTag
				<li class="item %s" style="display:%s;">
					<a href="/wx/giftDetail?giftid=%s&BACK_URL=%s" style="overflow:hidden;text-overflow: ellipsis;white-space: nowrap;text-overflow:ellipsis;display:block;clear:both;"><span class="%s r">%s<i class="icon_jt"></i></span><i class="icon_gift"></i>礼包%s：%s</a>
				</li>
EndOfRowTag;
			$giftIndex=1;
			foreach ($gift_list as $row){
				$displayRow="";
				$moreRowClassName="";
				if($giftIndex>2){
					$displayRow="none";
					$moreRowClassName="ShowMore";
				}
				if($row["gift_getten"]=="1"){
					$actionName="已领取";
					$actionClass="color_green";
				}
				else if($row["gift_getten"]=="2"){
					$actionName="已抢完";
					$actionClass="color_gray";
				}
				else{
					$actionName="速领";
					$actionClass="color_red";
				}
				echo sprintf($rowsHtmlTag,$moreRowClassName,$displayRow,
						$row["gift_idx"],urlencode($CURRENT_URL),$actionClass,$actionName,$giftIndex++,$row["gift_name"]);
			}
?>		
		</ul>
		<a href="javascript:toggleMoreGift();" class="my_list_more" style="display:<?php echo count($gift_list)>2?"":"none";?>;">更多<span class="open"><em></em></span></a>
	</div>
	<div class="event_des">
		<h3>活动详情</h3>
		<div class="event_detail">
			活动进度：已参与 <span class="color_red"><?php echo $count_current;?></span>/<?php echo $need_count;?>人<br>
			活动时间：<?php echo $start_dt;?>~<?php echo $end_dt;?><br>
			活动内容：<br/>
			<?php echo $evn_content;?>
		</div>
	</div>
</div>
<script type="text/javascript">
var PageMoreShown=false;			
function toggleMoreGift()
{
	if(PageMoreShown){
		$(".my_list_more").html("更多<span class='open'><em></em></span>");
	} else{
		$(".my_list_more").html("收起<span class='fold'><em></em></span>");
	}
	$(".item.ShowMore").each(function(){
		if(PageMoreShown){
			$(this).hide();
		}else{
			$(this).show();
		}
	  });
	PageMoreShown=!PageMoreShown;
}
<?php
	if(empty($join_msg)==false){
		echo "alert('".$join_msg."');";
	} 
?>
</script>
