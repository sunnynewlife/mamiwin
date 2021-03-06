<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写任务信息</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<table>
					<tr>
						<td style="width:100px;">任务类型</td>
						<td style="width:250px;">
							<select name="Task_Type"  id="Task_Type" onchange="javascript:TaskTypeChanged();">
								<option value="1">学习任务</option>
								<option value="2">陪伴任务</option>
							</select>
						</td>
						<td style="width:100px;">学习时间</td>
						<td>
							<input type="text"  name="Min_Time" value="20" class="input-xlarge" required="true" autofocus="true"  style="width:50px;"/>
							~
							<input type="text"  name="Max_Time" value="60" class="input-xlarge" required="true" autofocus="true" style="width:50px;" />
							分钟
						</td>
						<td style="width:100px;">年龄段</td>
						<td>
							<input type="text" maxlength="50" name="Min_Age" value="2" class="input-xlarge" required="true" autofocus="true" style="width:50px;" />
							~
							<input type="text" maxlength="50" name="Max_Age" value="6" class="input-xlarge" required="true" autofocus="true"  style="width:50px;"/>
							岁
						</td>	
					</tr>
					<tr id="trTask_TypeExtendAttr" style="display:none;">
						<td>季节</td>
						<td>
							<select name="Task_Type_Season">
								<option value="1">不限</option>
								<option value="2">春季</option>
								<option value="3">夏季</option>
								<option value="4">秋季</option>
								<option value="5">冬季</option>
							</select>
						</td>
						<td>环境</td>
						<td><select name="Task_Type_Env">
								<option value="1">不限</option>
								<option value="2">室内</option>
								<option value="3">室外</option>
								<option value="4">水中</option>
								<option value="5">野外</option>
							</select>
						</td>
						<td>人数</td>
						<td>
							<select name="Task_Type_Person">
								<option value="1">不限</option>
								<option value="2">独自</option>	
								<option value="3">需父母</option>
								<option value="4">需小伙伴</option>				
							</select>
						</td>						
					</tr>
					<tr>
						<td>孩子性别</td>
						<td>
							<select name="Child_Gender">
								<option value="1">不限制</option>
								<option value="2">女孩</option>
								<option value="3">男孩</option>
							</select>
						</td>
						<td>父母性别</td>
						<td><select name="Parent_Gender">
								<option value="1">不限制</option>
								<option value="2">母亲</option>
								<option value="3">父亲</option>
							</select>
						</td>
						<td>父母婚姻状况</td>
						<td>
							<select name="Parent_Marriage">
								<option value="1">不限</option>
								<option value="2">单亲</option>					
							</select>
						</td>						
					</tr>
					<tr>
						<td>是否独生</td>
						<td>
							<select name="Only_Children">
								<option value="1">不限</option>
								<option value="2">独生小孩</option>
							</select>	
						</td>
						<td >任务标题</td>
						<td colspan=5>
							<input type="text" maxlength="50" name="Task_Title" value="<?php echo $Matrial_Data["File_Title"]?>" class="input-xlarge" required="true" autofocus="true" style="width:530px;" />
						</td>											
					</tr>
					<tr>
						<td>任务文件</td>
						<td colspan=3>
							<select name="Matrial_IDX" style="width:500px;">
								<option value="">请选择任务使用的素材文件</option>
<?php 
					foreach ($Material_Files as $rowItem){						
						echo sprintf("<option value=\"%s\" %s>%s</option>\n",$rowItem["IDX"],($Matrial_IDX==$rowItem["IDX"]?" selected ":" "),$rowItem["File_Title"]);				
					}
?>								
							</select>						
						</td>
						<td>任务状态</td>
						<td>
							<select name="Task_Status">
								<option value="1">未发布</option>
								<option value="2">公开</option>
								<option value="3">灰度</option>
							</select>	
						</td>								
					</tr>
					<tr>
						<td>智能属性</td>
						<td colspan=5>
							<table style="width:600px;">
<?php 
					$row_name="";
					$row_vale="";
					foreach ($Ability_Type as $rowItem){
						$row_name.=sprintf("<td style=\"border:1px solid #000000;background-color:#4D5B76;color:#fff;text-align:center; \">%s</td>",$rowItem["Ability_Name"]);
						$row_vale.=sprintf("<td style=\"border:1px solid #000000;text-align:center;\"><input type=\"checkbox\" name=\"Ability_%d\" value=\"1\" /></td>",$rowItem["IDX"]);
					}
					echo "<tr>".$row_name."</tr>\n";
					echo "<tr>".$row_vale."</tr>\n";
?>								
																		
								
							</table>
						</td>					
					</tr>
				</table>

				<input type="hidden" name="submit" value="1" />
				<div class="btn-toolbar">
					<button type="submit" class="btn btn-primary">
						<strong>提交</strong>
					</button>
				</div>
			</form>			
		</div>
	</div>
</div>
<script type="text/javascript">
function TaskTypeChanged()
{
	if($("#Task_Type").val()=="2"){
		$("#trTask_TypeExtendAttr").show();
	}else{
		$("#trTask_TypeExtendAttr").hide();
	}
}
</script>
