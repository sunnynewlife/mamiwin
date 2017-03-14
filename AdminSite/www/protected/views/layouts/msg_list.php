<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8" />
<title>消息列表</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<style>
*{padding: 0;margin:0;}
ul,li{list-style:none;}
body{background-color: #f2f2f2;font-size:14px;color: #353535;padding:15px;line-height: 1.6;font-family: "microsoft yahei"}
.context{margin-top:10px;text-align: left;}
p{margin-bottom: 10px;color: #696d78;text-indent: 28px;}
a{color: #3699dc;text-decoration: none;}
h2{font-size: 16px;margin-bottom: 6px;display: block;font-weight:normal;}
.color_red{color: #f33637;}
.color_blue{color: #3598dc;}
.sub_title{font-size: 12px;margin-bottom: 10px;overflow: hidden;}
.time{float: left;margin-right: 10px;color: #888;}
img{display: block;margin: 10px auto;width:100%;}
.mes_list a{ display: block;padding: 10px;color: #353535;border:1px solid #dddddd;background-color: #fff;border-radius: 3px;text-align: left;}
.mes_list .action{border-top: 1px solid #e6e6e6;padding-top: 10px;color: #353535;}
.mes_list li{text-align: center;margin-bottom: 20px;}
.mes_list .action .arrow{float: right;font-size: 16px;font-weight: bold;color: #d2d2d2;padding: 0 8px;}
.time_box{text-align: center;display: block;padding: 3px 10px; background-color: #a8aab0;border-radius: 16px;display: inline-block;margin: 0 auto;margin-bottom: 10px;color: #fff;}
.error_box{text-align: center;padding:60px 30px 30px;}
</style>
</head>
<body>
<div class="wraper">
    <div class="main_box">
        <?php echo $content; ?>
    </div>
</div>
</body>
</html>
        