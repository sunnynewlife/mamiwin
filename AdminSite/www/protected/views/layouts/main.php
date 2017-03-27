<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>首页 - Home Platform Management</title>
    <meta content="text/html; charset=utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link rel="stylesheet" href="/static/js/lib/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="/static/css/stylesheets_<?php echo Yii::app()->params['skin'];?>/theme.css">
    <link rel="stylesheet" href="/static/css/font-awesome/css/font-awesome.css">
	<link rel="stylesheet" href="/static/css/other.css">
	<link rel="stylesheet" href="/static/css/jquery-ui.css" />
	<link rel="stylesheet" href="/static/css/jquery-ui-timepicker-addon.css" />
	
    <script src="/static/js/lib/jquery.js" ></script>
    
	<script src="/static/js/lib/bootstrap/js/bootbox.min.js"></script>
	<script src="/static/js/lib/bootstrap/js/bootstrap-modal.js"></script>
	<script src="/static/js/lib/bootstrap/js/bootstrap.js"></script>
	<script src="/static/js/other.js"></script>
	<script src="/static/js/jquery-ui.js"></script>
	<script src="/static/js/lib/jquery-ui-timepicker-addon.js"></script>
	<script src="/static/js/layer.min.js"></script>
	<script src="/static/js/jquery-query.js"></script>	
    <!-- Demo page code -->

    <style type="text/css">
        #line-chart {
            height:300px;
            width:800px;
            margin: 0px auto;
            margin-top: 1em;
        }
        .brand { font-family: georgia, serif; }
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

  <body class=""> 
  <!--<![endif]-->
<?php $this->widget('application.widget.NavMenu'); ?>
<!--- 以上为左侧菜单栏 sidebar --->
<?php $this->widget('application.widget.SidaBar',array('currentUrl'=>Yii::app()->params['currentUrl'])); ?>
<!--- sidebarEND -->
<!-- php $this->widget('application.widget.QuickNoteWidget'); -->	
 <?php echo $content; ?>
<footer>
    <hr>
    <p class="pull-right">
    	<a href="#" target="_blank">Home Platform Management.</a> Power By <a href="#" target="_blank">LUNA SDK</a>&copy; 2014
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