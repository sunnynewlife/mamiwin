<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8" />
<title></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta content="telephone=no,email=no" name="format-detection" />
<link rel="stylesheet" type="text/css" href="/static/css/style.css?_t=20150812001">
<script src="/static/js/zepto-1.1.4.js"></script>
<script type="text/javascript" src="/static/js/notification.js"></script>
<script type="text/javascript" src="/static/js/bind_phone.js"></script>
<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
</head>
<body>
	<div class="wraper">
		<div class="main_box">
			<?php echo $content; ?>
		</div>
	</div>
	
	<?php $this->widget('application.widget.Footer'); ?>
	
	<div id="tipFloat" class="mask" style="display:none;"></div>
	<div id="tipFloat_box" class="tc_box" style="display:none;">
  		<span class="tc_arrow"></span>
  		<div class="tc_main">
    		<p>
      			点击右上角，选择“在浏览器中打开”就能马上下载游戏咯~~
    		</p>
  		</div>
	</div>
	<div id="tipFloat2" class="mask2" style="display:none;"></div>
<script type="text/javascript" src="/static/js/app.js?_t=20150723005"></script>
</body>
</html>
