<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">缓存数据管理</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
				<label>缓存配置NodeName</label>
				<input type="text"  id="cacheNodeName" name="cacheNodeName" value="" class="input-xlarge">
				
				<label>缓存配置PrefixKey</label>
				<input type="text"  id="cachePrefixKey" name="cachePrefixKey" value="" class="input-xlarge">
				
				<label>缓存Key<span class="label label-important" id="spanTips">参数无</span></label>
				<input type="text"  id="cacheKey" name="cacheKey" value="" class="input-xlarge" required="true">
				
				<div class="btn-toolbar">
					<button type="button" class="btn btn-primary" onclick="javascript:query();">
						<strong>查询当前缓存值</strong>
					</button>
					<button type="button" class="btn btn-primary" onclick="javascript:cacheDelete();">
						<strong>删除当前缓存值</strong>
					</button>
					<button type="button" class="btn btn-primary" onclick="javascript:queryProfile();">
						<strong>状态性能查询</strong>
					</button>
					<button type="button" class="btn btn-primary" onclick="javascript:querySlabProfile();">
						<strong>其他Stat命令查询</strong>
					</button>					
										
				</div>
				<textarea id="cacheResult" name="cacheResult" rows="20" class="input-xlarge" style="width:800px;"></textarea>
		</div>
	</div>
</div>
<script type="text/javascript">
function query()
{
	$("#cacheResult").html("");
	var url="/evnCache/query";
	var pvKey=$("#cacheKey").val();
	var pvNodeName=$("#cacheNodeName").val();	
	var pvPrefixKey=$("#cachePrefixKey").val();		
	$.ajax({
        url:url,
        data:{key:pvKey,cacheNodeName:pvNodeName,cachePrefixKey:pvPrefixKey},
        success: function(json){
	        if(json.return_code=="0"){
		        $("#cacheResult").html(json.data);
	        }else{
		        alert("Error");
	        }
        },
        timeout:10000,
        error:function(XMLHttpRequest, textStatus, errorThrown){
        	alert("超时");
        },
        dataType:"json"
    });
}
function cacheDelete()
{
	var url="/evnCache/delete";
	var pvKey=$("#cacheKey").val();	
	var pvNodeName=$("#cacheNodeName").val();	
	var pvPrefixKey=$("#cachePrefixKey").val();			
	$.ajax({
        url:url,
        data:{key:pvKey,cacheNodeName:pvNodeName,cachePrefixKey:pvPrefixKey},
        success: function(json){
	        if(json.return_code=="0"){
		        $("#cacheResult").html(json.data);
	        }else{
		        alert("Error");
	        }
        },
        timeout:10000,
        error:function(XMLHttpRequest, textStatus, errorThrown){
        	alert("超时");
        },
        dataType:"json"
    });
}
function queryProfile()
{
	var url="/evnCache/queryProfile";
	var pvKey=$("#cacheKey").val();	
	var pvNodeName=$("#cacheNodeName").val();	
	var pvPrefixKey=$("#cachePrefixKey").val();			
	$.ajax({
        url:url,
        data:{key:pvKey,cacheNodeName:pvNodeName,cachePrefixKey:pvPrefixKey},
        success: function(json){
	        if(json.return_code=="0"){
		        $("#cacheResult").html(json.data);
	        }else{
		        alert("Error");
	        }
        },
        timeout:10000,
        error:function(XMLHttpRequest, textStatus, errorThrown){
        	alert("超时");
        },
        dataType:"json"
    });
}
function querySlabProfile()
{
	var url="/evnCache/queryProfile";
	var pvKey=$("#cacheKey").val();		
	var pvNodeName=$("#cacheNodeName").val();	
	var pvPrefixKey=$("#cachePrefixKey").val();	
	if(pvKey!=""){		
		$.ajax({
	        url:url,
	        data:{key:pvKey,cacheNodeName:pvNodeName,cachePrefixKey:pvPrefixKey},
	        success: function(json){
		        if(json.return_code=="0"){
			        $("#cacheResult").html(json.data);
		        }else{
			        alert("Error");
		        }
	        },
	        timeout:10000,
	        error:function(XMLHttpRequest, textStatus, errorThrown){
	        	alert("超时");
	        },
	        dataType:"json"
	    });
	}
}
</script>