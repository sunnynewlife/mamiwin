<?php if (isset($alert_message)) echo $alert_message;?>
<div class="well">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#home" data-toggle="tab">请填写评测题信息</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane active in" id="home">
			<form id="tab" method="post" action="">
				<table>
					<tr>
						<td style="width:100px;">评测题类型</td>
						<td style="width:550px;" colspan=6>
						<select name="Question_Set">
							<?php 
								foreach ($EvaluationQuestionsSet_List as $rowItem){						
									echo sprintf("<option value=\"%s\" %s>%s</option>\n",$rowItem["IDX"],($Set_IDX==$rowItem["IDX"]?" selected ":" "),$rowItem["Set_Name"]);				
								}
							?>				
							</select>
						</td>
					</tr>
					<tr>
						<td style="width:100px;">智能属性</td>
						<td style="width:550px;" colspan=6>
						<select name="Ability_Type_ID">
							<?php 
								foreach ($Ability_Type as $rowItem){						
									echo sprintf("<option value=\"%s\" %s>%s</option>\n",$rowItem["IDX"],($Set_IDX==$rowItem["IDX"]?" selected ":" "),$rowItem["Ability_Name"]);				
								}
							?>				
							</select>
						</td>
					</tr>
					<tr>						
						<td >评测题标题</td>
						<td colspan=6>
							<input type="text" maxlength="50" name="Question_Stems" value="" class="input-xlarge" required="true" autofocus="true" style="width:660px;" />
						</td>
						<td style="width:100px;"></td>
						<td>													
						</td>											
					</tr>
					<tr>						
						<td >选项A</td>
						<td colspan=2>
							<input type="text" maxlength="50" name="Option_A" value="" class="input-xlarge" required="true" autofocus="true" style="width:450px;" />
						</td>
						<td style="width:100px;">选项A分值</td>
						<td>
							<input type="text"  name="Point_A" value="1" class="input-xlarge" required="true" autofocus="true"  style="width:50px;"/>							
						</td>											
					</tr>
					<tr>						
						<td >选项B</td>
						<td colspan=2>
							<input type="text" maxlength="50" name="Option_B" value="" class="input-xlarge" required="true" autofocus="true" style="width:450px;" />
						</td>
						<td style="width:100px;">选项B分值</td>
						<td>
							<input type="text"  name="Point_B" value="0" class="input-xlarge" required="true" autofocus="true"  style="width:50px;"/>							
						</td>											
					</tr>
					<tr>						
						<td >选项C</td>
						<td colspan=2>
							<input type="text" maxlength="50" name="Option_C" value="" class="input-xlarge" autofocus="true" style="width:450px;" />
						</td>
						<td style="width:100px;">选项C分值</td>
						<td>
							<input type="text"  name="Point_C" value="0" class="input-xlarge"  autofocus="true"  style="width:50px;"/>							
						</td>											
					</tr>
					<tr>						
						<td >选项D</td>
						<td colspan=2>
							<input type="text" maxlength="50" name="Option_D" value="" class="input-xlarge" autofocus="true" style="width:450px;" />
						</td>
						<td style="width:100px;">选项D分值</td>
						<td>
							<input type="text"  name="Point_D" value="0" class="input-xlarge" autofocus="false"  style="width:50px;"/>							
						</td>											
					</tr>	
					<tr>						
						<td >选项E</td>
						<td colspan=2>
							<input type="text" maxlength="50" name="Option_E" value="" class="input-xlarge" autofocus="true" style="width:450px;" />
						</td>
						<td style="width:100px;">选项E分值</td>
						<td>
							<input type="text"  name="Point_E" value="0" class="input-xlarge" autofocus="false"  style="width:50px;"/>							
						</td>											
					</tr>
					<tr>						
						<td >选项F</td>
						<td colspan=2>
							<input type="text" maxlength="50" name="Option_F" value="" class="input-xlarge" autofocus="true" style="width:450px;" />
						</td>
						<td style="width:100px;">选项F分值</td>
						<td>
							<input type="text"  name="Point_F" value="0" class="input-xlarge" autofocus="false"  style="width:50px;"/>							
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
