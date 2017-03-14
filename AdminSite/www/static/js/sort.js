var js=document.scripts;
var sort = {
		init_sort:function(domain,key){
			if(typeof jQuery == 'undefined') {
				util.include('jquery-1.8.1.min.js', window.adDomain + "/static/js/lib/", function() {
					_init_sort();
				});
			} else{
				_init_sort();
			}
		},
		_init_sort:function(){
			
		},
		click_img:function(_this,_selHtml){
			var _html ='<select class="_item_list">'+_selHtml+'</select>';
			var dlg=$(_html).dialog({ 
				//autoOpen: false, 
				resizable: false, 
				modal: true, 
				show: { 
				effect: 'fade', 
				duration: 300 
				}, 
				title:  "提示信息", 
				buttons: { 
				"确定": function() { 
					var key='gameid';
					var data=$('._item_list').find("option:selected").last().data();
					$(_this).find('img').attr('src',data.img);
					$(_this).attr('title',data.title);
					$.each(data, function(index, element) {
						$(_this).attr('data-'+index,element);
					});
					
					var dlg = $(this).dialog("close"); 
				} 
				} 
			});
		},
		update_sort:function(_url,_data){
		    $.ajax({
		        type:"GET",
		        dataType:"json",
	            url:_url,
	            data:_data,
	            success:function(msg){
 	                if(msg.return_code != 0){
	                    alert(msg.return_msg);
	                    return;
	                } else{
		                alert('修改排序成功');
	                }
	            }
	        });
		},
};