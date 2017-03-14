<style type="text/css">
    body,html {
        margin:0;
        padding:0;
        background-color: #f2f2f2;
        color: #353535;
        font-size: 14px;
        font: 14px/1.5 "Microsoft YaHei",Helvetica,Arial,sans-serif;
        height: 100%;
        -webkit-box-sizing: border-box;
        -ms-box-sizing: border-box;
        box-sizing: border-box;
        -webkit-overflow-scrolling: touch;
        -webkit-tap-highlight-color: rgba(0,0,0,0);
        -ms-text-size-adjust: 100%; 
        -webkit-text-size-adjust: 100%; 
    }
    div,p,ul,li,h1,h2,h3{
      padding: 0;
      margin:0;
    }
    a:active,
    a:hover,a {
      outline: 0;
      text-decoration: none;
    }
    h1,h2,h3{
        font-weight: normal;
    }
    i,em{font-style: normal;}
    img {
      border: 0;
      width: 100%;
      display: block;
    }
    .follow_box{text-align: center;position: relative;}
    .follow_box .fl_box{
        position: absolute;
        width: 270px;
        height: 98px;
        left: 50%;
        top: 50%;
        margin: -24px 0 0 -134px;
        text-align: center;
        font-size: 14px;
    }
    .btn_follow{
        width: 270px;
        height: 98px;
        display: block;
        background: url(/static/img/btn_follow.png) no-repeat;
        background-size: 270px auto;
        
    }
    .follow_box p{padding-top: 10px;line-height: 1.8;}
    .follow_box .c_red{
        color:#cf2021;
        font-size: 16px;
        font-weight: bold;
        padding: 0 4px;
    }
    table {
        border-collapse: collapse;
        border-spacing: 0;
        margin-left: 6px;
        width: 300px;
        margin: 0 auto;
    }

    td,
    th {
        padding:4px 6px;
        text-align: center;
    }
    .play_box{
        background-color: #ffdf4b;
        width: 100%;
        text-align: center;
        margin: 0 auto;
    }
    .play_box table{text-align: center;}
    .play_box .item{
        background: url(/static/img/gift_bg.png) no-repeat;
        background-size: 91px auto;
        width: 91px;
        height: 62px;
        display: block;
        text-align: center;
        padding-top: 2px;
    }
    .play_box .item img{    
        vertical-align: middle;
        display: inline;
    }
    .btn_get{
       background: url(/static/img/btn_get.png) no-repeat;
        background-size: 80px auto;
        width: 80px;
        height: 80px;
        display: inline-block;
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        line-height: 80px;
        color: #fff;
        margin-left: 2px;
    }
    .btn_get:hover{
        -moz-transform: rotate(360deg); 
        -webkit-transform: rotate(360deg); 
        -ms-transform: rotate(360deg); 
        transform: rotate(360deg); 
    }
    .get_list{
        padding:15px 12px 20px;
    }
    .get_list .get_t{
        background-color: #f33738;
        height: 30px;
        line-height: 30px;
        color: #fff;
        text-align: center;
        font-size: 14px;
    }
    .get_list .get_m{
        background-color: #922122;
        color: #fff;
        padding: 6px 0;
        height: 120px;
        overflow: hidden;
    
    }
    .get_list .get_m ul {
        text-align: center;
    }
    #marquee_award {
        height: 132px;
    }

    .get_list .get_m li{
        width: 50%;
        float:left;
        display: inline-block;
        font-size: 12px;
        
    }
    .get_list .get_m span{padding:0 8px;}
    .guid_box{background-color: #f2f2f2; padding-bottom: 50px}
    .guid_box .guid_t{
        background: url(/static/img/bg_t.png) repeat-x;
        background-size: auto 30px;
        height: 30px;
        line-height: 30px;
        text-align: center;
        color: #fff;
    }
    .guid_box .guid_m{
        padding: 10px;
        line-height: 1.6;
        text-align: left;
    }
    .mask{position: fixed;height: 100%;width: 100%;background: rgba(0,0,0,0.7); z-index: 1000;left: 0;top: 0; z-index:999;}
    .pp_wrap{
    background: #f2f2f2;width: 290px; position: absolute;left: 50%;top: 30%; z-index: 1001;margin-left: -145px;
    border-radius: 8px;
    }
    .pp_close{
        background: url(/static/img/close.png) no-repeat;
        width: 15px;
        height: 15px;
        background-size: 15px auto;
        position: absolute;
        right: 12px;
        top: 12px;
        display: block;
    }
    .pp_box{
        padding: 10px 15px 25px;
        overflow: hidden;

    }
    .pp_box .pp_t{
        border-bottom: 1px solid #dee0e1;
        text-align: center;
        padding: 10px 0 15px;
    }
    .pp_box .pp_t h2{font-size: 18px;text-align: center;color: #353535;}
    .pp_box .pp_t h3{font-size: 12px;}
    .pp_box .pp_m .send_share,.pp_box .pp_m .send_share .list_box{background: transparent;}
    .pp_box .pp_m .list_box ul li a.info_link .info_m h3{
        font-size: 14px;
    }
    .pp_box .pp_m .text_box{
        border:1px solid #dee0e1;
        padding: 10px;
        margin-bottom: 10px;
    }
    .pp_box .pp_m .pp_text h3{font-size: 16px;display: block;margin-bottom: 10px;}
    .pp_box .pp_m .pp_text{
        text-align: left;
        padding: 20px 0 10px;
        font-size: 14px;
        line-height: 1.4;
        color: #353535;
    }
    .pp_box .pp_b .btn{height: 34px;line-height: 34px; }
    /*tc_box*/
    .tc_box{
        position: fixed;
        width: 270px;
        height: 170px;
        right: 10px;
        top: 40px;
        z-index: 9999;
    }
    .tc_box .tc_arrow{
        width: 46px;
        height: 38px;
        background:url(/static/img/icon_po.png) no-repeat;
        background-size: 46px auto;
        position: absolute;
        right: 14px;
        top: -37px;
    }
    .tc_box .tc_main{
        color: #fff;
        line-height: 1.8;
        background-color: #f33738;
        font-size: 18px;
        padding: 20px 30px;
        -webkit-border-radius: 6px;
        -moz-border-radius: 6px;
        border-radius: 6px;
    }
    
    #lottery table td.active {
        background-color: #f33738;
    }
    </style>
<?php
    //9:没关注 ；1：已关注没领取 ；2：关注已领取；3：送现金活动已结束 ；
    if($focus_state == 2){
        $showNotice = '您已关注了“逗逗游戏”公众号，奖励可在公众号中的<span class="c_red"><a hr ef="/wx/index">我的-礼金/礼券</a></span>中查看';
    }else if($focus_state == 3){
        $showNotice = '奖品被领完了~~，您还可以参加下方的转发抽奖活动。逗逗后续还会推出更多有奖活动，请多关注“逗逗游戏”公众号哦~~';
    }else if($focus_state == 4){
        $showNotice = '限在活动期间首次关注“逗逗游戏”公众号的用户参加！';
    }else if($focus_state == 1){
        $showNotice = '您已关注了“逗逗游戏”公众号，奖励可在公众号中的<span class="c_red"><a hr ef="/wx/index">我的-礼金/礼券</a></span>中查看';
    }else if($focus_state == 9){
        $showNotice = '关注逗逗游戏公众号 现金就在公众号里哦<br>方法：查找公众号→输入“逗逗游戏”→关注';
    }else if($focus_state == 5){
        $showNotice = '本次活动已结束，已获奖的用户请于9月10日24点前在“逗逗游戏”公众号—我的礼券/礼金 中领取您的奖励。';
    }
?>    
    <img src="/static/img/img_1.jpg" alt="">
<div class="follow_box">
	<img src="/static/img/img_2.jpg" alt="">
	<div class="fl_box">
		<a href="javascript:void(0);" class="btn_follow" style="display:none;"></a>
		    <?php echo($showNotice);?>
	</div>
</div>
<img src="/static/img/img_3.jpg" alt="">
<div class="play_box">
    <!-- lottery -->
    <div id="lottery">
    	<table border="0">
    		<tr>
    			<td  class="lottery-unit lottery-unit-0">
    				<div class="item"><img src="/static/img/pic_1.jpg" alt="" style="width:90%"></div>
    			</td>
    			<td  class="lottery-unit lottery-unit-1"><div class="item"><img src="/static/img/pic_6.jpg" alt="" style="width:90%"></div></td>
    			<td  class="lottery-unit lottery-unit-2"><div class="item"><img src="/static/img/pic_5.jpg" alt="" style="width:90%"></div></td>
    		</tr>
    		<tr>
                <td  class="lottery-unit lottery-unit-7">
                    <div class="item"><img src="/static/img/pic_7.jpg" alt="" style="width:90%"></div>
                </td>
                <td><a href="javascript:void(0);" class="btn_get" id="btn_get" >抽奖</a></td>
                <td  class="lottery-unit lottery-unit-3"><div class="item"><img src="/static/img/pic_8.jpg" alt="" style="width:90%"></div></td>
            </tr>
            <tr>
                <td  class="lottery-unit lottery-unit-6">
                    <div class="item"><img src="/static/img/pic_2.jpg" alt="" style="width:90%"></div>
                </td>
                <td  class="lottery-unit lottery-unit-5"><div class="item"><img src="/static/img/pic_3.jpg" alt="" style="width:90%"></div></td>
                <td  class="lottery-unit lottery-unit-4"><div class="item"><img src="/static/img/pic_4.jpg" alt="" style="width:90%"></div></td>
            </tr>
    	</table>
    </div>
    <!-- /lottery -->
    <!--  -->
    <div class="get_list">
        <div class="get_t">
            <h3>中奖信息</h3>
        </div>
        <div class="get_m">
          <marquee id="marquee_award" direction="up" align="middle" scrollamount="3"  >
            <ul>             
<?php
$rowsUsedHtmlTag = <<<EndOfUsedRowTag
                <li>
                    %s<span>%s</span>  
                </li>
EndOfUsedRowTag;
foreach ($lottery_playerlist as $item) {
    $PhoneNo = $item['PhoneNo'];
    $PhoneNo = substr($PhoneNo, 0,3) . "******" . substr($PhoneNo ,-3);
    $Award = $item['Award'];
    echo sprintf($rowsUsedHtmlTag,$PhoneNo,$Award);
}
?>     
            </ul>
          </marquee>  
        </div>
    </div>
    <!--  -->
    <!--  -->
    <div class="guid_box" style="margin-bottom:35px">
        <div class="guid_t">
            <h3>活动规则</h3>
        </div>
        <div class="guid_m">
            <p>
            <?php echo $lottery_rule_msg;?>
            </p>
        </div>
    </div>
    <!--  -->
</div>
<!-- pp_wrap -->
<div id="lottery_mask" class="mask" style="display:none;"></div>
<div id="lottery_tc_box2"  class="pp_wrap" style="display:none;">
    <div class="pp_box">
        <a id="lottery_pp_close"  href="javascript:void(0);" class="pp_close"></a>
        <div class="pp_t">
            <h2>分享赢大礼</h2>
            <h3>话费、现金券，一个都不能少</h3>
        </div>
        <div class="pp_m">
            <div class="pp_text">
                <p id="lottery_tc_content2" class="c" >
                    恭喜您获得XXX，您可在”逗逗游戏“公众号内”我的“-”礼金/礼券“中查看并使用。
                </p>
                
            </div>
        </div>       
    </div>
</div>
<!-- /pp_wrap -->
<!-- tc_box -->
<div id="lottery_tc_box1" class="tc_box" style="display:none;">
  <span class="tc_arrow"></span>
  <div class="tc_main">
    <p id="lottery_tc_content">
      转发到朋友圈就能抽奖咯，奖品都到碗里来！
    </p>
  </div>
</div>
<!-- /tc_box -->
    <script type="text/javascript" src="/static/js/jquery-1.8.1.min.js"></script>
    <script type="text/javascript">  
        (function($){

            $.fn.myScroll = function(options){
            //默认配置
            var defaults = {
                speed:40,  //滚动速度,值越大速度越慢
                rowHeight:24 //每行的高度
            };
            
            var opts = $.extend({}, defaults, options),intId = [];
            
            function marquee(obj, step){
            
                obj.find("ul").animate({
                    marginTop: '-=1'
                },0,function(){
                        var s = Math.abs(parseInt($(this).css("margin-top")));
                        if(s >= step){
                            $(this).find("li").slice(0, 1).appendTo($(this));
                            $(this).css("margin-top", 0);
                        }
                    });
                }
                
                this.each(function(i){
                    var sh = opts["rowHeight"],speed = opts["speed"],_this = $(this);
                    intId[i] = setInterval(function(){
                        if(_this.find("ul").height()<=_this.height()){
                            clearInterval(intId[i]);
                        }else{
                            marquee(_this, sh);
                        }
                    }, speed);

                });

            }

        })(jQuery);
    </script>
</body>
</html>