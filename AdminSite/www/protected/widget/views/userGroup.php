<option value="0" class="input-xlarge option" id="DropDownTimezone-0">全部</option>
<!-- <option value="1" class="input-xlarge option" id="DropDownTimezone-1">超级管理员组</option>
<option value="2" class="input-xlarge option" id="DropDownTimezone-2">默认账号组</option> -->
<?php 

	foreach ($groups as $val)
	{
		$selected = '';
		if ($current_user_group ==$val['group_id'] )
		{
			$selected = ' selected ';
		}
		echo '<option value="'.$val['group_id'].'" class="input-xlarge option" id="DropDownTimezone-'.$val['group_id'].'"' . $selected .'>'.$val['group_name'].'</option>';
	}
?>