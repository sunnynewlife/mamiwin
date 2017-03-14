<div class="acc_info">
	<div class="acc_des">
		<span class="acc_logo"><img src="/static/img/logo.png" alt="" width="90%"></span>
		<div class="acc_name">
			<a href="javascript:showAcctHelp();"><i class="icon_wen"></i></a>逗逗账号：<?php echo $phone;?>
		</div>
	</div>
</div>
<div class="mod_tab">
	<ul>
		<li>累计礼金（元）<br>
		<span class="num"><?php echo $IncomeSummary;?></span>
		</li>
		<li>礼金金额（元）<br>
		<span class="num"><?php echo $NetAmount;?></span>
		</li>
	</ul>
</div>
<div class="gift_tips">
	<h3 class="tips_t">礼金小贴士</h3>
	<p>
		<?php echo $envelope_intro;?>
	</p>
</div>
<div class="my_gift_get">
	<ul>
<?php if($show_rest_envelope) { ?>
		<li>
			<div class="my_gift_item">
				<p>礼金余额</p>
				<h2><?php echo $amount;?><span>元</span></h2>
				<a href="#" class="btn"><span class="color_red">亲，您的礼金余额不足，今日不可领取哦！</span></a>
			</div>
		</li>

<?php  } 
$rowsHtmlTag = <<<EndOfRowTag
		<li>
			<div class="my_gift_item">
				<p>今日可领</p>
				<h2>%s<span>元</span></h2>
				<a href="/wx/sendRedEnvelope?type=%s" class="btn"><span class="color_red">立即领取</span></a>
			</div>
		</li>
EndOfRowTag;
		foreach ($envelope_to_send as $itemRow){
			echo sprintf($rowsHtmlTag,$itemRow["Amount"],$itemRow["Type"]);
		}
$rowsSentHtmlTag = <<<EndOfRowSentTag
		<li class="disabled">
			<div class="my_gift_item">
				<p>%s</p>
				<h2>%s<span>元</span></h2>
				<a href="#" class="btn">%s</a>
			</div>
		</li>
EndOfRowSentTag;
		foreach ($envelope_sent as $sentItem){
			echo sprintf($rowsSentHtmlTag,$sentItem["title"],$sentItem["amount"],$sentItem["foot"]);
		}		
?>	
	</ul>
	<br/>
	<br/>
</div>
<script type="text/javascript">
function showAcctHelp()
{
	var txt="这里显示的【逗逗账号】就是您绑定的手机号；您一定要使用这个号码去玩逗逗游戏，才能获得礼金和兑换礼券哦~~~";
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
            <a class="ui-btn ui-btn-1 L_sure_btn" data-key="">嗯，了解了</a>\
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
</script>