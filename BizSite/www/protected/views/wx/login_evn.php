<div class="gf_box">
<?php

      $A3BtnStatusClass="gf_over";
      $A3BtnClickHref="javascript:void(0);";
      $A3BtnText="立即领取";

      $A5BtnStatusClass="gf_over";
      $A5BtnClickHref="javascript:void(0);";
      $A5BtnText="立即领取";
      
      $A7BtnStatusClass="gf_over";
      $A7BtnClickHref="javascript:void(0);";
      $A7BtnText="立即领取";
      
	  if($A3_days["gotten"]==1){
	  	$A3BtnStatusClass="gf_get";
	  	$A3BtnText="已领取";
	  }else if($A3_days["rest_count"]<=0){
	  	$A3BtnText="已抢完";
	  }else if($A3_days["can_get"]==1){
	  	$A3BtnStatusClass="gf_nor";
	  	$A3BtnClickHref="javascript:doGetLoginEvn('3_days');";
	  }
	  
	  if($A5_days["gotten"]==1){
	  	$A5BtnStatusClass="gf_get";
	  	$A5BtnText="已领取";
	  }else if($A5_days["rest_count"]<=0){
	  	$A5BtnText="已抢完";
	  }else if($A5_days["can_get"]==1){
  		$A5BtnStatusClass="gf_nor";
  		$A5BtnClickHref="javascript:doGetLoginEvn('5_days');";
	  }
	  
	  if($A7_days["gotten"]==1){
	  	$A7BtnStatusClass="gf_get";
	  	$A7BtnText="已领取";
	  }else if($A7_days["rest_count"]<=0){
	  	$A7BtnText="已抢完";
	  }else if($A7_days["can_get"]==1){
	  	$A7BtnStatusClass="gf_nor";
	  	$A7BtnClickHref="javascript:doGetLoginEvn('7_days');";
	  }
?>

    <div class="gf_item <?php echo $A3BtnStatusClass;?>">
        <div class="lj_box">
            <div class="num num_1"></div>
            <p>剩余<span><?php echo $A3_days["rest_count"];?></span>份</p>
        </div>
        <a href="<?php echo $A3BtnClickHref;?>" class="btn"><?php echo $A3BtnText;?></a>
    </div>
    
    <div class="gf_item gf_center <?php echo $A7BtnStatusClass;?>">
        <div class="lj_box">
            <div class="num num_4"></div>
            <p>剩余<span><?php echo $A7_days["rest_count"];?></span>份</p>
        </div>
        <a href="<?php echo $A7BtnClickHref;?>" class="btn"><?php echo $A7BtnText;?></a>
    </div>
    
    <div class="gf_item gf_right <?php echo $A5BtnStatusClass;?>">
        <div class="lj_box">
            <div class="num num_2"></div>
            <p>剩余<span><?php echo $A5_days["rest_count"];?></span>份</p>
        </div>
        <a href="<?php echo $A5BtnClickHref;?>" class="btn"><?php echo $A5BtnText;?></a>
    </div>
    
    <div class="tips_box">
        <div class="tips">
            <div class="tips_c">
                <img src="/static/img/logo_s.png" alt="" width="60">
                <span><?php echo $login_info;?></span>
            </div>
        </div>
    </div>
</div>
<div class="m">
    <img src="/static/img/pic_02.png" alt="">
</div>
<div class="guid_box">
    <p>
    	<?php echo  $evn_rule;?>
    </p>
    <img src="/static/img/line.png" alt="">    
    <div class="scan">
        	扫描二维码，关注“逗逗游戏”
    </div>
    <div class="b">
        <div class="code_box">
            <img src="/static/img/code.jpg" style="width:160px">
        </div>
        <img src="/static/img/pic_03.png" alt="">
    </div>
</div>
<br/>
<br/>
<script type="text/javascript" src="/static/js/jquery-1.8.1.min.js?_t=20150723005"></script>
<script type="text/javascript">
function doGetLoginEvn(actId)
{
	var url="/wx/getEvn1Item";
	$.ajax({
        url:url,
        data:{ActId:actId},
        success: function(json){
	        if(json.return_code=="0"){
	        	UIToast(json.return_message,function(){ location.reload(true); },false);
	        }else{
		        UIToast(json.return_message,function(){},false);
	        }
        },
        timeout:10000,
        error:function(XMLHttpRequest, textStatus, errorThrown){
        	UIToast("网络超时，请稍后尝试！",function(){},false);
        },
        dataType:"json"
    });	
}
var dialogToast;
var dialog_auto_close=false;
var UIToast = window.UIDialog = function(msg,fn,autoCloseDialog){
	dialog_auto_close=autoCloseDialog;
    var modalTPL = '<div class="ui-dialog">\
        <div class="ui-dialog-content">\
            <span class="close L_sure_btn"></span>\
            <h2 class="title">信息提示</h2>\
            <div id="dialog1" title="登陆提示" style="display: block; padding-top:20px; text-align:center; color:#353535">\
                <p>' + msg + '</p>\
            </div>\
        </div>\
        <div class="ui-dialog-btns">\
            <a class="ui-btn ui-btn-1 L_sure_btn" data-key="">嗯，了解了</a>\
        </div>\
    </div>';

    dialogToast = Notification.confirm('', modalTPL, function() {});
    dialogToast.show();
    $('.L_modal_close').on('tap', function(e) {
        e && e.preventDefault();
        dialogToast.hide();
    });
    $('.L_sure_btn').on("click",function(e){
        e && e.preventDefault();
        dialogToast.hide();
        if(!IsEmpty(fn)){
            fn();
        }
    });
};
</script>
