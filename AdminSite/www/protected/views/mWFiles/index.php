<div class="btn-toolbar">
	<a href="/mWFiles/add" class="btn btn-primary"><i class="icon-plus"></i>新增素材</a>
</div>
<div class="block">
	<a href="#page-stats" class="block-heading" data-toggle="collapse">素材查看列表</a>
	<div id="search" class="in collapse">
		<form class="form_search"  method="POST" style="margin-bottom:0px">
			<div style="float:left;margin-right:5px;margin-left:15px;margin-top:25px;">
				<table class="tableApp" style="width:815px;">
					<tr>
						<td style="width:120px;">资料文件类型：</td>
						<td>
							<select name="File_Type">
								<option value="">全部</option>
								<option value="1">文本</option>
								<option value="2">音频</option>
								<option value="3">视频</option>								
							</select>						
						</td>
						<td style="width:120px;">下载标识：</td>
						<td><input type='text'  name="Download_Id" /></td>
					</tr>																		
					<tr>
						<td style="width:120px;">内容标题：</td>
						<td style="width:120px;"><input type='text'  name="File_Title" /></td>
						<td><button type="submit" class="btn btn-primary">查  询</button></td>
						<td></td>
					</tr>
				</table>			
				<input type="hidden" name="search" value="1" >
			</div>
			<div style="clear:both;"></div>
		</form>
	</div>
	<div id="page-stats" class="block-body collapse in">
		<table class="table table-striped">
			<thead>
				<tr>
					<th width="60px;">素材ID</th>
					<th width="40px;">类型</th>
					<th width="90px;">位置</th>
					<th>内容标题</th>
					<th width="140px;">原始文件名称</th>
					<th width="80px;">文件大小</th>
					<th width="270px;">下载标识</th>
					<th width="60px">操作</th>
				</tr>
			</thead>
			<tbody>
			<?php
$rowsHtmlTag=<<<EndOfRowTag
<tr>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
	<td>%s</td>
	<td>
		<a href="/mWFiles/modify?IDX=%s" title="修改"><i class="icon-pencil"></i></a>&nbsp;
		<a href="/mWMaterial/add?Matrial_IDX=%s" title="用此素材创建任务"><i class="icon-file"></i></a>&nbsp;		
		<a data-toggle="modal" href="#myModal" title="删除这个素材文件"><i class="icon-remove" href="/mWFiles/del?IDX=%s"></i></a>
	</td>
<tr>
EndOfRowTag;
			$titleLen=20;
			foreach ($Material_Files as $item){
				$title=$item["File_Title"];
				if(mb_strlen($title,"utf-8")>$titleLen){
					$title=mb_substr($title, 0,$titleLen,"utf-8")."..";
				}
				echo sprintf($rowsHtmlTag,
					$item["IDX"],
					DictionaryData::Material_Files_File_Type[$item["File_Type"]],
					DictionaryData::Material_Files_Location_Type[$item["Location_Type"]],
					$title,
					$item["Original_Name"],
					$item["File_Size"],
					$item["Download_Id"],
					$item["IDX"],$item["IDX"],$item["IDX"]
				);
			}
			?>
			</tbody>
		</table>
		<?php echo $page;?>
	</div>
</div>
<script type="text/javascript">

	$('.icon-remove').click(
		function() 
		{
			var href=$(this).attr('href');
			bootbox.confirm('确定要删除这个素材吗？', 
					function(result) 
					{
						if(result){
							location.replace(href);
						}
					});		
		});
</script>
