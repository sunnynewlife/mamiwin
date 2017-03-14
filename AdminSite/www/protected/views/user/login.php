<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>登入 - Home Platform Management</title>
	<meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="stylesheet" href="/static/js/lib/bootstrap/css/bootstrap.css">
	<link rel="stylesheet" href="/static/css/stylesheets_default/theme.css">
	<link rel="stylesheet" href="/static/css/font-awesome/css/font-awesome.css">
	<link rel="stylesheet" href="/static/css/other.css">
	<script src="/static/js/lib/jquery-1.8.1.min.js"></script>
	<script src="/static/js/other.js"></script>
	<style type="text/css">
	#line-chart {
		height: 300px;
		width: 800px;
		margin: 0px auto;
		margin-top: 1em;
	}
	
	.brand {
		font-family: georgia, serif;
	}
	
	.brand .first {
		color: #ccc;
		font-style: italic;
	}
	
	.brand .second {
		color: #fff;
		font-weight: bold;
	}
	</style>
<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>
<body class="simple_body">
	<div class="navbar">
		<div class="navbar-inner">
			<ul class="nav pull-right">
			</ul>
			<a class="brand" href="/">
				<span class="first"></span> <span class="second">Home Platform Managment</span>
			</a>
		</div>
	</div>
	<div>
		<div class="container-fluid">
			<div class="row-fluid">
				<div class="dialog">
		<?php if (isset($alert_message)) echo $alert_message;?>
        			<div class="block">
						<p class="block-heading">登入</p>
						<div class="block-body">
							<form name="loginForm" method="post" action="/user/login">
								<label>账号</label> <input type="text" class="span12" name="user_name" value="" required="true" autofocus="true"> 
								<label>密码</label>
								<input type="password" class="span12" name="password" value="" required="true"> 
								<input type="hidden" class="span12" name="submit" value="1"> 
								<input type="submit" class="btn btn-primary pull-right" style="float:none;" name="loginSubmit" value="登入" />
							</form>
						</div>
					</div>
				</div>
				<p class="pull-right" style="">
					<a href="/" target="blank"></a>
				</p>
			</div>
			<footer>
				<hr>
				<p class="pull-right">
					<a href="#" target="_blank">Home Platform Management.</a> Power By <a href="#" target="_blank">LUNA SDK</a>&copy; 2017
				</p>
			</footer>
		</div>
	</div>
	<script type="text/javascript">	
		alertDismiss("alert-success",3);
		alertDismiss("alert-info",10);
		listenShortCut("icon-plus");
		listenShortCut("icon-minus");
	</script>
</body>
</html>