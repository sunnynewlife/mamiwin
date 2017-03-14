<!--- START 以上内容不需更改，保证该TPL页内的标签匹配即可 --->
<div style="border:0px;padding-bottom:5px;height:auto">
	<form action="" method="GET" style="margin-bottom:0px">
	<div style="float:left;margin-right:5px">
		<label>请选择操作记录类型</label>
		<select name="class_name" id="DropDownTimezone">
			<option value="" id="DropDownTimezone-0">全部</option>
			<option value="User" id="DropDownTimezone-1">用户</option>
			<option value="UserGroup" id="DropDownTimezone-2">账号组</option>
			<option value="Module" id="DropDownTimezone-3">菜单模块</option>
			<option value="MenuUrl" id="DropDownTimezone-4">功能</option>
			<option value="GroupRole" id="DropDownTimezone-5">权限</option>
			<option value="QuickNote" id="DropDownTimezone-6">QuickNote</option>
		</select>
	</div>
	<div style="float:left;margin-right:5px">
		<label> 选择起始时间 </label>
		<input type="text" id="start_date" name="start_date" value="<?php echo $start_date;?>" placeholder="起始时间" >
	</div>
	<div style="float:left;margin-right:5px">
		<label>选择结束时间</label>	
		<input type="text" id="end_date" name="end_date" value="<?php echo $end_date;?>" placeholder="结束时间" > 
	</div>
	<div style="float:left;margin-right:5px">
		<label>用户名，查询所有用户请留空</label>
		<input type="text" name="user_name" value="<?php echo $user_name;?>" placeholder="输入用户名" > 
	</div>
		<div class="btn-toolbar" style="padding-top:25px;padding-bottom:0px;margin-bottom:0px">
		<button type="submit" class="btn btn-primary"><strong>检索</strong></button>
	</div>
	<div style="clear:both;"></div>
	</form>
</div>
    <div class="block">
        <a href="#page-stats" class="block-heading" data-toggle="collapse">操作记录</a>
        <div id="page-stats" class="block-body collapse in">
               <table class="table table-striped">
              <thead>
                <tr>
					<th style="width:30px">#</th>
					<th style="width:50px">操作员</th>
					<th style="width:35px">行为</th>
					<th style="width:35px">类型</th>
					<th style="width:150px">请求参数</th>
					<th style="width:150px">操作结果</th>
					<th style="width:50px">操作时间</th>
                </tr>
              </thead>
              <tbody>							  
                <?php foreach($sys_logs as $sys_log ){ ?>
					<tr>
					<td><?php echo $sys_log['op_id']; ?></td>
					<td><?php echo $sys_log['user_name']; ?></td>
					<td><?php echo $sys_log['action']; ?></td>
					<td><?php echo $sys_log['class_name']; ?></td>
					<td><?php $result = json_decode($sys_log['inputParams'],true);
						if(is_array($result) && count($result)>0){
							$temp = null;
							foreach($result as $key => $value){
								if(is_array($value)){
                                    $temp[] = "$key=>".implode(',', array_values($value));
								}else{
									$temp[] = "$key=>$value";
								}
							}
							$str=implode(';',$temp);
						}else{
							$str=$result;
						}
						echo $str;
					?></td>
					<td style = "word-break: break-all; word-wrap:break-word;">
					<?php $result = json_decode($sys_log['result'],true); 

						if(is_array($result)){
							$temp = null;
							foreach($result as $key => $value){
								if(is_array($value)){
									$temp[] = "$key=>".implode(',', array_values($value));
								}else{
									$temp[] = "$key=>$value";
								}
							}
							$str=implode(';',$temp);
						}else{
							$str=$result;
						}
						echo $str;
					?></td>
					<td><?php echo date('Y-m-d H:i:s',$sys_log['op_time']); ?></td>
					</tr>
				<?php }?>
              </tbody>
            </table>
				<!--- START 分页模板 --->
               <?php echo $page?>
			   <!--- END --->
        </div>
    </div>

<script>
$(function() {
	var date=$( "#start_date" );
	date.datepicker({ dateFormat: "yy-mm-dd" });
	date.datepicker( "option", "firstDay", 1 );
});
$(function() {
	var date=$( "#end_date" );
	date.datepicker({ dateFormat: "yy-mm-dd" });
	date.datepicker( "option", "firstDay", 1 );
});
</script>