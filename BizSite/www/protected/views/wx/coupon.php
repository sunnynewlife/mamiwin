<div class="tips">
	<p>说明：每笔充值仅限兑换一张礼券</p>
</div>
<div class="cp_list">
	<ul class="cp_list_ul">
<?php
$rowsUsingHtmlTag = <<<EndOfRowTag
		<li>
			<div class="cp_item %s">
				<div class="cp_box">
					<div class="r">
						<h3>%s礼券</h3>
						<p>
							%s
						</p>
						<a href="/wx/couponUsing?idx=%s" class="btn">兑现</a>
					</div>
					<div class="cp_main">
						<h3>单笔充值大于等于%s元即可获得</h3>
						<span class="num">¥<em>%s</em></span>
						<p>%s</p>
					</div>
				</div>
			</div>
			<div class="c">%s</div>
		</li>
EndOfRowTag;
	foreach ($coupon_using as $item){
		$className= ($item["type"]=="2"?"cp_item_100":"");
		$typeName=	($item["type"]=="2"?"专属":"通用");
		$appName=	$item["shown_app_name"];
		if($item["shown_app_count"]=="-1"){
			$appName="全部游戏可使用";
		} else if($item["shown_app_count"]==1){
			$appName="限".$appName."使用";
		}
		else {
			$appName="<a href='javascript:showAppInfo(\"".$appName."\",". $item["shown_app_count"] .");' class='link'>限<span class='color_red'>".$item["shown_app_count"]."</span>款游戏使用<i class='arrow_1'></i></a>";
		}
		$payDateRange="";
		if(isset($item["pay_start_dt"]) && empty($item["pay_start_dt"])==false &&  isset($item["pay_end_dt"]) && empty($item["pay_end_dt"])==false){
			$payDateRange=sprintf("限%s到%s之间的充值可使用",substr($item["pay_start_dt"],0,10),substr($item["pay_end_dt"],0,10));
		} else if(isset($item["pay_start_dt"]) && empty($item["pay_start_dt"])==false){
			$payDateRange=sprintf("限%s之后的充值可使用",substr($item["pay_start_dt"],0,10));
		} else if(isset($item["pay_end_dt"]) && empty($item["pay_end_dt"])==false){
			$payDateRange=sprintf("限%s之前的充值可使用",substr($item["pay_end_dt"],0,10));
		} else{
			$payDateRange="任意时间的充值均可使用";
		}
		echo sprintf($rowsUsingHtmlTag,
			$className,$typeName,$appName,$item["idx"],$item["pay_amout"],$item["return_amout"],$item["shown_end"],$payDateRange);		
	}	
	
$rowsUsedHtmlTag = <<<EndOfUsedRowTag
		<li>
			<div class="cp_item cp_item_out %s">					
				<div class="cp_box">
					<div class="r">
						<h3>%s礼券</h3>
						<p>
							%s
						</p>
						<span>已兑现</span>
					</div>
					<div class="cp_main">
						<h3>单笔充值大于等于%s元即可获得</h3>
						<span class="num">¥<em>%s</em></span>
						<p>%s</p>
					</div>
				</div>
				<div class="over_lay"></div>
			</div>
			<div class="c" style="overflow:hidden;text-overflow: ellipsis;white-space: nowrap;text-overflow:ellipsis;display:block;clear:both;text-align:center">%s</div>
		</li>
EndOfUsedRowTag;
		foreach ($coupon_used as $usedItem){
			$className= ($usedItem["type"]=="2"?"cp_item_100":"");
			$typeName=	($usedItem["type"]=="2"?"专属":"通用");
			$appName=	$usedItem["shown_app_name"];
			if($usedItem["shown_app_count"]=="-1"){
				$appName="全部游戏可使用";
			}else if($usedItem["shown_app_count"]==1){
				$appName="限".$appName."使用";
			}
			else {
				$appName="<a href='javascript:void();' class='link'>限<span class='color_red'>".$usedItem["shown_app_count"]."</span>款游戏使用<i class='arrow_1'></i></a>";
			}
			$payDateRange="";
			if(isset($usedItem["pay_start_dt"]) && empty($usedItem["pay_start_dt"])==false &&  isset($usedItem["pay_end_dt"]) && empty($usedItem["pay_end_dt"])==false){
				$payDateRange=sprintf("限%s到%s之间的充值可使用",substr($usedItem["pay_start_dt"],0,10),substr($usedItem["pay_end_dt"],0,10));
			} else if(isset($usedItem["pay_start_dt"]) && empty($usedItem["pay_start_dt"])==false){
				$payDateRange=sprintf("限%s之后的充值可使用",substr($usedItem["pay_start_dt"],0,10));
			} else if(isset($usedItem["pay_end_dt"]) && empty($usedItem["pay_end_dt"])==false){
				$payDateRange=sprintf("限%s之前的充值可使用",substr($usedItem["pay_end_dt"],0,10));
			} else{
				$payDateRange="任意时间的充值均可使用";
			}
			$payDateRange=$usedItem["pay_used_rec"];
			echo sprintf($rowsUsedHtmlTag,$className,$typeName,$appName,$usedItem["pay_amout"],$usedItem["return_amout"],$usedItem["shown_end"],$payDateRange);
		}

$rowsOutHtmlTag = <<<EndOfOutRowTag
		<li>
			<div class="cp_item cp_item_out %s">					
				<div class="cp_box">
					<div class="r">
						<h3>%s礼券</h3>
						<div class="flag_out"></div>
					</div>
					<div class="cp_main">
						<h3>单笔充值大于等于%s元即可获得</h3>
						<span class="num">¥<em>%s</em></span>
						<p>%s</p>
					</div>
				</div>
				<div class="over_lay"></div>
			</div>
			<div class="c">%s</div>		
		</li>
EndOfOutRowTag;
		foreach ($coupon_out as $outItem){
			$className= ($outItem["type"]=="2"?"cp_item_100":"");
			$typeName=	($outItem["type"]=="2"?"专属":"通用");
			$payDateRange="";
			if(isset($outItem["pay_start_dt"]) && empty($outItem["pay_start_dt"])==false &&  isset($outItem["pay_end_dt"]) && empty($outItem["pay_end_dt"])==false){
				$payDateRange=sprintf("限%s到%s之间的充值可使用",substr($outItem["pay_start_dt"],0,10),substr($outItem["pay_end_dt"],0,10));
			} else if(isset($outItem["pay_start_dt"]) && empty($outItem["pay_start_dt"])==false){
				$payDateRange=sprintf("限%s之后的充值可使用",substr($outItem["pay_start_dt"],0,10));
			} else if(isset($outItem["pay_end_dt"]) && empty($outItem["pay_end_dt"])==false){
				$payDateRange=sprintf("限%s之前的充值可使用",substr($outItem["pay_end_dt"],0,10));
			} else{
				$payDateRange="任意时间的充值均可使用";
			}
			echo sprintf($rowsOutHtmlTag,$className,$typeName,$outItem["pay_amout"],$outItem["return_amout"],$outItem["shown_end"],$payDateRange);
		}
?>	
	</ul>
</div>

<script type="text/javascript">
function showAppInfo(pvApps,pvCounts)
{
	var txt="您当前选择的礼券为【专属礼券】，限定以下<span class='color_red'> "+pvCounts+"</span> 款游戏使用："+pvApps;
	UIToast(txt,function(){},false);
}
var dialogToast;
var dialog_auto_close=false;
var UIToast = window.UIDialog = function(msg,fn,autoCloseDialog){
	dialog_auto_close=autoCloseDialog;
    var modalTPL = '<div class="ui-dialog">\
        <div class="ui-dialog-content">\
            <span class="close L_sure_btn"></span>\
            <h2 class="title">信息提示</h2>\
            <div id="dialog1" title="登陆提示" style="display: block; padding-top:20px; text-align:center; color:#353535">\
                <p>' + msg + '</p>\
            </div>\
        </div>\
        <div class="ui-dialog-btns">\
            <a class="ui-btn ui-btn-1 L_sure_btn" data-key="">确认</a>\
        </div>\
    </div>';

    dialogToast = Notification.confirm('', modalTPL, function() {});
    dialogToast.show();
    $('.L_modal_close').on('tap', function(e) {
        e && e.preventDefault();
        dialogToast.hide();
    });
    $('.L_sure_btn').on("click",function(e){
        e && e.preventDefault();
        dialogToast.hide();
        if(!IsEmpty(fn)){
            fn();
        }
    });
};

<?php
	if(empty($using_coupon_msg)==false){
		// echo "window.setTimeout(function(){ if(dialog_auto_close) dialogToast.hide();},3000);";
		echo "UIToast('".$using_coupon_msg."',function(){},true);";
	} 
?>
</script>
