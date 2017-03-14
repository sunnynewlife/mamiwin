<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>首页 - 管理后台 - SDG Game Event Platform Management</title>
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
      <script src="http://osadmin.wida.com//assets/js/html5.js"></script>
    <![endif]-->
</head>

<!--[if lt IE 7 ]> <body class="ie ie6"> <![endif]-->
<!--[if IE 7 ]> <body class="ie ie7 "> <![endif]-->
<!--[if IE 8 ]> <body class="ie ie8 "> <![endif]-->
<!--[if IE 9 ]> <body class="ie ie9 "> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->

<body class="simple_body">
	<div class="navbar">
		<div class="navbar-inner">
			<ul class="nav pull-right">
			</ul>
			<a class="brand" href="/"><span class="first"></span> <span
				class="second">SDG Game Event Platform Management</span></a>
		</div>
	</div>
	<div>
		<div class="container-fluid">
			<div class="row-fluid">
				<div class="http-error">
				
				<?php if ($type == "success") {?>
				<h1>Yep!</h1>
				<?php } elseif ($type == "error") {?>
				<h1>Oops!</h1>
				<?php } else  {?>
				<h1>O~!</h1>
				<?php }?>
				<p class="info"><?php echo $message_detail; ?></p>
					<h2>
						返回 <a href="<?php echo $forward_url;?>"><?php echo $forward_title; ?></a>
					</h2>
				</div>
				<div>
					<footer>
						<hr>
						<p class="pull-right">
							<a href="#" target="_blank">SDG Game Event Platform Management.</a> Power By <a href="#" target="_blank">LUNA SDK</a>&copy; 2014							
						</p>
					</footer>
				</div>
			</div>
		</div>
		<!--- + -快捷方式的提示 --->
		<script type="text/javascript">	
			alertDismiss("alert-success",3);
			alertDismiss("alert-info",10);
			listenShortCut("icon-plus");
			listenShortCut("icon-minus");
	</script>
</body>
</html>