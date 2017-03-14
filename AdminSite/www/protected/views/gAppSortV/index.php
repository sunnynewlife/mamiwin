
<div id="search">
<form class="form_search"  action="/gAppSortV/index" method="GET" style="margin-bottom:0px">
	<div style="float:left;margin-right:5px">
		<input type="hidden" name="in_version" value="<?php echo Yii::app()->request->getParam('in_version','2.0');?>"> 
		<input type="hidden" name="os_type" value="<?php echo Yii::app()->request->getParam('os_type',1);?>"> 
		<input type="hidden" name="flag" value="<?php echo Yii::app()->request->getParam('flag','');?>"> 
		<input type="hidden" name="search" value="1" >
		<label>游戏类型</label> 
		<select name="game_type">
			<option value="1" <?php echo Yii::app()->request->getParam('game_type',1)==1?"selected":"";?>>手游</option>
			<option value="2" <?php echo Yii::app()->request->getParam('game_type',1)==2?"selected":"";?>>PC游戏</option>
		</select>
	</div>
	<div class="btn-toolbar" style="padding-top:25px;padding-bottom:0px;margin-bottom:0px">
		<button type="submit" class="btn btn-primary">检索</button>
	</div>
	<div style="clear:both;"></div>
</form>
</div>
<div class="block">
	<?php $os_type=Yii::app()->request->getParam('os_type',1);?>
	<a href="#page-stats" class="block-heading" data-toggle="collapse"><?php echo Yii::app()->request->getParam('os_type',1)==1?"对Android":"对Ios";?><?php echo Yii::app()->request->getParam('flag','')=='not null'?'推荐游戏':'所有游戏';?>进行排序</a>
	<div>
		<div class="banner" style="height:600px;position:inherit;">
			<?php
$rowsHtmlTag=<<<EndOfRowTag
<div class="ban" data-gameid="%s" data-type="%s" style="width:40px;" data-img="%s" title="%s" ><img src="%s"/></div>
EndOfRowTag;
$gameHtmlTag=<<<EndOfRowTag
<option value="%s" data-gameid="%s" data-type="%s" data-img="%s" data-title="%s">%s</option>
EndOfRowTag;
$gameHtml='';
				$os_type=Yii::app()->request->getParam('os_type',1);
				$in_version=Yii::app()->request->getParam('in_version',1);
				$flag=Yii::app()->request->getParam('flag','');
				$game_type=Yii::app()->request->getParam('game_type',1);
				$order=" order by recommend_no asc;";
				if($flag=='not null'){
					$flag_where=" flag <> 'null' and ";
				}else if(false==empty($flag)&&$flag!='all'){
					$flag_where=" flag = '".$flag."' and ";
				}else{
					$order=" order by index_no asc;";
					$flag_where=' ';
				}
				
				$sql="select gameid,game_name,game_logo from t_game_config where".$flag_where."  os_type=".$os_type." and in_version=".$in_version." and game_type=".$game_type.$order;
				$game_list=LunaPdo::GetInstance('GApi')->query_with_prepare($sql,array(),PDO::FETCH_ASSOC);
				$game_name_list=array();
				if(false==empty($game_list)){
					foreach ($game_list as $gameItem){
						$game_name_list[$gameItem['gameid']]=array('id'=>$gameItem['gameid'],'name'=>$gameItem['game_name'],'game_logo'=>$gameItem['game_logo'],'type'=>'gameid');
					}
				}				
				$state=array('0'=>'下架','1'=>'灰度','2'=>'上架');
				$index=0;
				$games=array();
			 	foreach ($game_name_list as $item){
			 		$index++;
			 		$gameHtml.=sprintf($gameHtmlTag,$item["id"],$item["id"],$item["type"],$item["game_logo"],$item["name"],$item["name"]);
			 		$rowHtml=sprintf($rowsHtmlTag,
			 				$item["id"],
			 				$item["type"],
			 				$item["game_logo"],
			 				$item["name"],
			 				$item["game_logo"]
			 				);
			 		echo $rowHtml;
				}
			?>
			</div>
			<div id="selector" class="selector"></div>
			<button id="create_sort">生成排序</button>
			
<!-- 			</tbody>
		</table> -->
		<?php echo $page;?>
	</div>
</div>
<script src="/static/js/sort.js"></script>
<script>
	$('#create_sort').click(
		function(){
			var index=0;
			var os_type=<?php echo $os_type;?>;
			var flag="<?php echo Yii::app()->request->getParam('flag','');?>";
			var _game ={};
		  	$(".ban").each(function(i,val){
		  		index++;
		  		_game[$(this).data('gameid')]={'gameid':$(this).data('gameid'),'type':$(this).data('type'),'no':index};
			  	
			});
			var _data={'flag':flag,'os_type':os_type,'games':_game,count:index};
			return sort.update_sort('/gAppSortV/sort',_data);
		}

	);
	$('.ban').click(
			function(){
				var _gameHtml='<?php echo $gameHtml;?>';
				return sort.click_img($(this),_gameHtml);
			}
		
	);
	$('.icon-remove').click(
		function() 
		{
			var href=$(this).attr('href');
			bootbox.confirm('确定要这样做吗？', 
					function(result) 
					{
						if(result){
							location.replace(href);
						}
					});		
		});
</script>
