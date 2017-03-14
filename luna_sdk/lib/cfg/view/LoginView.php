<?php

class LoginView 
{
	public static function getHtml($msg="",$Url="")
	{
$htmlContent= <<< End_Of_Html
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title>LUNA Configurations Management</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>
<body style="TEXT-ALIGN:center;">
	<form name="loginForm" method="post" action="%s">
		<div id="mainContainer" style="margin-left:auto;margin-right:auto;margin-top:250px;">
			<div id="loginPanel">
				<span>用户</span>
				<input name="txtUserName" type="text" id="txtUserName" />
				<br /><br />
				<span>密码</span>
                <input name="txtPassword" type="password" id="txtPassword" />
				<br /><br />
				<input type="submit" name="btnLogin" value="登陆Luna配置管理" id="btnLogin" class="LoginButton" />
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
		
		return sprintf($htmlContent,$Url,$msg);
	}	
}

?>