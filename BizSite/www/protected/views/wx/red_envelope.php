<div class="tips">
<?php echo $envelope_intro;?>
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
