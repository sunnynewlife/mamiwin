<div class="event_box">
	<div class="detail_wrap">
		<div class="detail_box">
			<div class="d_img"><img src="<?php echo $GameIcon;?>" alt="" width="80"></div>
			<div class="d_info" style="margin-left: 90px;">
				<div class="r">
					<a  id="downLoadGame" data-href="<?php echo $download;?>" target="_blank" class="btn r" >下载</a>
					<p><?php echo $Size;?>M</p>
				</div>
				<div class="d_act">
					<h3>
						<?php echo $AppName;?>
						<em class="tab tab_green" style="display:<?php echo $IsFirst=="1"?"":"none";?>">首发</em>
						<em class="tab tab_green" style="background-color:#bc4ff6;display:<?php echo $IsSingle=="1"?"":"none";?>">独家</em>
						<em class="tab tab_green" style="background-color:#a5d600;display:<?php echo $IsOther=="1"?"":"none";?>">其他</em>
					</h3>
                    <span style="display:<?php echo empty($BaseProrate)?"none":""?>;">基础返现：<?php echo $BaseProrate;?>%<br><?php echo $ProrateStr;?></span>
					<span>
						推荐指数：<span class="rec_box"><span class="rec_per" style="width:<?php echo $RecommendIndex;?>%;"></span></span>
					</span>
					<?php if(empty($Publisher)==false) {
						echo sprintf("<span>运营商：%s</span>",$Publisher);
					}?>
				</div>
			</div>
		</div>

	</div>
	<div class="my_list_box" style="display:<?php echo count($gift_list)>0?"":"none"; ?>;">
		<ul class="my_list" style="display:<?php echo count($gift_list)>0?"":"none"; ?>;">
<?php
$rowsHtmlTag = <<<EndOfRowTag
				<li class="item %s" style="display:%s;">
					<a href="/wx/giftDetail?giftid=%s&BACK_URL=%s" style="overflow:hidden;text-overflow: ellipsis;white-space: nowrap;text-overflow:ellipsis;display:block;clear:both;"><span class="%s r">%s<i class="icon_jt"></i></span><span class="tab tab_ora">礼包%s</span>%s</a>
				</li>
EndOfRowTag;
			$giftIndex=1;
			foreach ($gift_list as $row){
				$displayRow="";
				$moreRowClassName="";
				if($giftIndex>2){
					$displayRow="none";
					$moreRowClassName="ShowMore";
				}
				if($row["gift_getten"]=="1"){
					$actionName="已领取";
					$actionClass="color_green";
				}
				else if($row["gift_getten"]=="2"){
					$actionName="已抢完";
					$actionClass="color_gray";
				}
				else{
					$actionName="速领";
					$actionClass="color_red";
				}
				echo sprintf($rowsHtmlTag,$moreRowClassName,$displayRow,
					$row["gift_idx"],urlencode($CURRENT_URL),$actionClass,$actionName,$giftIndex++,$row["gift_name"]);
			}
?>
		</ul>
		<a href="javascript:toggleMoreGift();" id="my_gfit_list_more" class="my_list_more" style="display:<?php echo count($gift_list)>2?"":"none";?>;">更多<span class="open"><em></em></span></a>
	</div>
	<div class="my_list_box" style="display:<?php echo count($EvnList)>0?"":"none"; ?>;">
          <ul class="my_list">
<?php
$evnRowHtmlTag = <<<EndOfEvnRowTag
               <li class="item %s" style="display:%s;">
                 <a href="%s" style="overflow:hidden;text-overflow: ellipsis;white-space: nowrap;text-overflow:ellipsis;display:block;clear:both;"><span class="btn btn_gray_1 r">查看</span><span class="tab tab_green">活动%s</span>%s</a>
               </li>
EndOfEvnRowTag;

			$evnIndex=1;
			foreach ($EvnList as $evnItem){
				$displayRow="";
				$moreRowClassName="";
				if($evnIndex>2){
					$displayRow="none";
					$moreRowClassName="ShowEvnMore";
				}
				echo sprintf($evnRowHtmlTag,$moreRowClassName,$displayRow,
						$evnItem["url"],$evnIndex++,$evnItem["title"]);
			}
?>          
          </ul>
          <a href="javascript:toggleMoreEvn();" id="my_evn_list_more" class="my_list_more" style="display:<?php echo count($EvnList)>2?"":"none";?>;">更多<span class="open"><em></em></span></a>
	</div>
	<div class="des_box">
		<div class="des_content" id="i_con_1">
			<div class="game_intro">
				<div class="game_tab">
					标签：<span class="color_red"><?php echo $GameType;?></span>
<?php
				foreach ($LabelTag as $tagRow){
					if(empty($tagRow)==false){
						echo sprintf('<span class="color_ora">、%s</span>',$tagRow);
					}
				} 
?>					
				</div>
				<div class="marks_wrap" style="display:none;">
					<ul>
						<li class="marks_item">流行指数：<span class="mark_box"><span class="mark_per" style="width:<?php echo $PopularIndex;?>%;"></span></span></span></li>
						<li class="marks_item marks_item_1">留存指数：<span class="mark_box"><span class="mark_per" style="width:<?php echo $RemainIndex;?>%;"></span></span></span></li>
						<li class="marks_item">新手难度：<span class="mark_box"><span class="mark_per" style="width:<?php echo $BeginnerLevel;?>%;"></span></span></span></li>
						<li class="marks_item marks_item_1">付费指数：<span class="mark_box"><span class="mark_per" style="width:<?php echo $PayIndex;?>%;"></span></span></span></li>
					</ul>
				</div>
				<div id="JGamePic" class="game_pic">
					<div class="game_pic_scroll clearfix" style="width: 1050px">
<?php
				foreach ($GamePics as $picRow){
					echo sprintf('<img src="%s">',$picRow);
				} 
?>					
					</div>
				</div>
				<!-- 
				<div class="box after" id="dotIntro"><?php //echo $AppDetail;?></div>
				 -->
				<p><?php echo $AppDetail;?></p>
				<br/>
				<br/>
			</div>
		</div>
		<div id="i_con_2" class="des_content" style="display:none;">
			<div class="iframe_box">
			</div>
		</div>
	</div>
</div>
<!-- 
<style type="text/css" media="all">
	div.box 
	{
		overflow: hidden;
		height: 60px;
	}
	div.box.opened
	{
		height: auto;
	}
	div.box .toggle .close,
	div.box.opened .toggle .open
	{
		display: none;
	}
	div.box .toggle .opened,
	div.box.opened .toggle .close
	{
		display: inline;
	}
</style>
<script type="text/javascript" src="/static/js/jquery-1.8.1.min.js"></script>
<script type="text/javascript" src="/static/js/jquery.dotdotdot.min.js"></script>
 -->
<script type="text/javascript">  
	var PageMoreShown=false;			
	function toggleMoreGift()
	{
		if(PageMoreShown){
			$("#my_gfit_list_more").html("更多<span class='open'><em></em></span>");
		} else{
			$("#my_gfit_list_more").html("收起<span class='fold'><em></em></span>");
		}
		$(".item.ShowMore").each(function(){
			if(PageMoreShown){
				$(this).hide();
			}else{
				$(this).show();
			}
		  });
		PageMoreShown=!PageMoreShown;
  }

  var PageEvnMoreShown=false;
  function toggleMoreEvn()
  {
		if(PageEvnMoreShown){
			$("#my_evn_list_more").html("更多<span class='open'><em></em></span>");
		} else{
			$("#my_evn_list_more").html("收起<span class='fold'><em></em></span>");
		}
		$(".item.ShowEvnMore").each(function(){
			if(PageEvnMoreShown){
				$(this).hide();
			}else{
				$(this).show();
			}
		  });
		PageEvnMoreShown=!PageEvnMoreShown;
  }

  $(function(){
	  /*
		var $divIntro = $('#dotIntro');
		$divIntro.append( '<a class="toggle" href="#"><span class="open" style="top:0px;width:32px;height:16px;background-color:#f2f2f2;color:blue;">[展开]</span><span class="close" style="top:0px;width:32px;height:16px;background-color:#f2f2f2;position:relative;color:blue;">[收起]</span></a>' );
		
		function createDots()
		{
			$divIntro.dotdotdot({
				after: 'a.toggle'
			});
		}
		function destroyDots() {
			$divIntro.trigger( 'destroy' );
		}
		
		createDots();
			
		$divIntro.on(
				'click',
				'a.toggle',
				function() {
					$divIntro.toggleClass( 'opened' );

					if ( $divIntro.hasClass( 'opened' ) ) {
						destroyDots();
					} else {
						createDots();
					}
					return false;
				}
		);
*/
	  
    //处理详细页图片左右拖动
    function isTouchDevice(){
        try
        {
            document.createEvent("TouchEvent");
            return true;
        } 
        catch(err)
        {
            return false;
        }
    }
    function touchScroll(id){
        if(isTouchDevice()){ //if touch events exist...
            var el=document.getElementById(id);
            var scrollStartPos=0;

            document.getElementById(id).addEventListener("touchstart", function(event) {
                scrollStartPos=this.scrollLeft+event.touches[0].pageX;

            },false);

            document.getElementById(id).addEventListener("touchmove", function(event) {

                this.scrollLeft=scrollStartPos-event.touches[0].pageX;


            },false);
        }
    }
     
    touchScroll("JGamePic");
    $("#i_link_1").tap(function(){
        $(this).parent().siblings(".itab_item").find("a").removeClass("cur");
        $(this).addClass("cur");
        $("#i_con_2").hide();
        $("#i_con_1").show();
    	});
    $("#i_link_2").tap(function(){
        $(this).parent().siblings(".itab_item").find("a").removeClass("cur");
        $(this).addClass("cur");
        $("#i_con_1").hide();
        $("#i_con_2").show();
    	});
})
  
</script>