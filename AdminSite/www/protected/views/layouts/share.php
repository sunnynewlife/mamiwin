<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8" />
<title></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<style>
*{padding: 0;margin:0;}
a{color: #3699dc;text-decoration: none;}
h2{font-size: 16px;margin-bottom: 6px;display: block;}
.color_red{color: #f33637;}
.color_blue{color: #3598dc;}
.detail_wrap .detail_box{overflow: hidden;border-bottom: 1px solid #eeefef;padding-bottom: 16px;}
.detail_wrap .detail_box .d_img{float: left;}
.detail_wrap .detail_box .d_info{ margin-left: 94px;font-size: 13px;color: #888;}
.detail_wrap .detail_box .d_info .d_title{color: #353535;font-size: 20px;margin-bottom: 14px;}
.detail_wrap .detail_box .d_info span{display: block;}
.detail_wrap .detail_box .d_info .d_act{display: block;line-height: 1.2;}
.context_box{padding-top: 16px;}
.context_box img{display: block;margin-bottom: 10px;}
.context_box p{margin-bottom: 16px;}
.btn{background-color: #f33637;border-radius: 3px;color: #fff;
font-size: 16px;text-align: center;display: block;float: right;padding: 3px 26px;font-style: normal;font-weight: normal;}
.wechat-overlay{position:fixed;top:0;left:0;width:100%;height:100%;z-index:1000;background:rgba(0,0,0,0.5)}.wechat-dialog{font-size:16px;position:absolute;top:0;left:0;width:100%;padding:50px 0;text-align:center;background:#fff}.wechat-highlight{color:#1da053}.wechat-arrow{position:absolute;top:30px;right:-10px;width:150px;height:60px;border:2px dashed rgba(0,0,0,0);border-radius:50%;border-bottom-color:#000;-webkit-transform:rotate(-60deg)}.wechat-arrow:after{content:"";position:absolute;width:20px;height:20px;border:2px solid;bottom:-2px;right:2px;border-left-color:rgba(0,0,0,0);border-top-color:rgba(0,0,0,0);-webkit-transform:rotate(-70deg)}.wechat-overlay{display:none}@media(max-height:480px){.promotion img{width:auto!important;height:400px}}
.swipe {overflow: hidden;visibility: hidden;position: relative;width: 100%;margin: 0 auto;}
.swipe-wrap {overflow: hidden;position: relative;}
.swipe-wrap > div {float: left;width: 100%;position: relative;overflow: hidden; max-height: auto;}
.swipe-wrap a {display: inline-block;width: 100%;height: auto;}
.swipe-wrap a img {width: 100%;height:auto;}
.slidePosIndicator {text-align: center;padding: 8px 0;}
.slidePosIndicator a {display: inline-block;}
.slidePosIndicator a {background: #6d6d6d;border-radius: 50% 50% 50% 50%;height: 8px;width: 8px;margin: 0 4px;}
.slidePosIndicator .active {background: #f76706;}

</style>
<script type="text/javascript" src="/static/js/swipe.js"></script>
</head>
<body>
<div class="wraper">
    <div class="main_box">
    	<?php echo $content; ?>    	
    </div>
</div>

<div class="wechat-overlay" style="display:none;">
    <div class="wechat-dialog">
        <h1>链接打不开?</h1>
        <div>由于微信限制，请点击右上角</div>
        <div>选择<span class="wechat-highlight">"在浏览器中打开"</span></div>
        <div class="wechat-arrow"></div>
    </div>
</div>

<script type="text/javascript">
    var browser = (function() {
    var a = navigator.userAgent;
	    return {
	        wechat: (function() {
	            return /MicroMessenger/.test(a)
	        })()
	    }
	})();
	(function(b) {
	    if (b.wechat) {
	        var a = document.querySelector(".download-button"),
	            c = document.querySelector(".wechat-overlay");
	        a.addEventListener("click", function(d) {
	            d.preventDefault();
	            d.stopPropagation();
	            c.style.display = "block"
	        }, false);
	        c.addEventListener("click", function(d) {
	            if (d.target == c) {
	                c.style.display = "none"
	            } else {
	                d.stopPropagation()
	            }
	        }, false)
	    }
	})(browser);

	function initSlider() {
        var bullets = document.getElementById('featuredMobileInd').getElementsByTagName('a');
        window.mySwipe = new Swipe(document.getElementById('slider'), {
            // startSlide: 2,   
            // speed: 400,
            auto: 3000,
            continuous: false
            // disableScroll: false,
            // stopPropagation: false,
            // callback: function(index, elem) {},
            // transitionEnd: function(index, elem) {}
            ,
            callback: function(pos) {
                var i = bullets.length;
                while (i--) {
                    bullets[i].className = '';
                }
                bullets[pos].className = 'active';
            }
            
        });
    }

    // 初始化滑动
    initSlider();	
</script>
<script type="text/javascript">
		(function(d, s) {
		  	window.config ={
		  		bw_enabled:false,
		  		bw_base:"http://static.sdg-china.com/yxzm/pic/",
		  		siteid:"SDG-08213-01"
			};
			var js =d.createElement(s);
			var sc = d.getElementsByTagName(s)[0];
			js.src="http://static.sdg-china.com/yxzm/js/ac.js";
			sc.parentNode.insertBefore(js, sc);
		}(document, "script"));
</script>
</body>
</html>
