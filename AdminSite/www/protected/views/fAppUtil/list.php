<ul class="mes_list">
<?php
$rowsHtmlTag=<<<EndOfRowTag
	<li><span class="time_box">%s</span> 
		<a href="%s">
			<h2>%s</h2>
			<div class="sub_title">
				<span class="time">%s</span>
			</div>
			<div class="context">
				%s
				<p>
					%s
				</p>
			</div>
			<div class="action">
				%s
			</div>
		</a>
	</li>
EndOfRowTag;
	foreach ($msg_list as $item){
		$createDate=date_create($item["CreateDt"]);
		$url=$item["DetailUrl"];
		if(empty($url)){
			$url="#";
		}else{
			if(stripos($url,"?")){
				$url.="&userToken=".$userToken;
			}else{
				$url.="?userToken=".$userToken;
			}
		}	
		$imgHtml="";
		if(empty($item["Image_Path"])==false){
			$imgHtml=sprintf("<img src=\"%s\">",$item["Image_Path"]);
		}
		$showFullHtml="";
		if(empty($item["DetailUrl"])==false){
			$showFullHtml="<span class=\"arrow\">></span>阅读全文";
		}
		echo sprintf($rowsHtmlTag,
				date_format($createDate, "y-m-d H:i"),
				$url,
				htmlentities($item["Title"],ENT_COMPAT,"utf-8"),
				date_format($createDate, "m月d日"),
				$imgHtml,
				htmlentities($item["Abstract"],ENT_COMPAT,"utf-8"),
				$showFullHtml
				);
	}
?>
</ul>
<?php
 	if(count($msg_list)<=0){
$noMsgTag=<<<EndOfMoMsgRowTag
        <div class="error_box" style="">
            <img src="/static/img/pic_mes.png" width="80%">
            <p>
                对不起，您目前暂时没有消息！
			<br>
            </p>
        </div>     
EndOfMoMsgRowTag;
		echo $noMsgTag;
	}
?>