<?php

class EditView 
{
	
	public static function getHtml($operator="",$errorMsg="",$xmlContent="",$Url="")
	{
$htmlContent= <<< End_Of_Html
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title>LUNA Configurations Management</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<script type="text/javascript">
	</script>
</head>
<body style="TEXT-ALIGN:center;">
	<form name="loginForm" method="post" action="%s">
		<div id="mainContainer" style="">
			<div>
				<span>Good %s %s,welcome to LUNA configuration management.<span>
			</div>
			<div style="TEXT-left;">
				<textarea name="txtCfgXML" id="txtCfgXML" wrap="off" style="width:1200px;height:700px;">%s</textarea>
				<br /><br />
				<input type="submit" name="btnLogin" value="保存配置" id="btnLogin" class="LoginButton" />
			</div>
			<div id="div_Message">
				<br/>
		    	<span id="lblReceiveMsg" style="color:#ff0000;font-weight:bolder">%s</span>
			</div>		
		</div>
	</form>
</body>
</html>	
End_Of_Html;
		
		return sprintf($htmlContent,$Url,self::getTimeStr(),$operator,$xmlContent,$errorMsg);
	}
	public static function getTimeStr()
	{
		$hour=date("H");
		if($hour>=23 || $hour<7){
			return "night";
		}else if($hour>=19){
			return "evening";
		}else if($hour>=12){
			return "afternoon";
		}else {
			return "morning";
		} 
	}
}

?>