<script>
var code="<?php echo $code;?>";
var state=<?php echo $state;?>;

$(document).ready(
    function login()
    {

    	 $.ajax({
             async:false,
             url: "/interface/wechatLogin",
             type: "GET",
             dataType: 'json',
             data: { 
                 	"code":code,
                 	"userinfo":(state=="2"?"1":"0")
             },
             success: function (data) {
                var url = "http://m.fumuwin.com/?from=redirect";
                window.location.href= url  ;          		
             }
         });
    }
);

</script>