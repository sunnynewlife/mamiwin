<!-- <option value="1" class="input-xlarge option" id="DropDownTimezone-1">超级管理员组</option>
<option value="2" class="input-xlarge option" id="DropDownTimezone-2">默认账号组</option> -->
<?php 

	foreach ($modules as $val)
	{
		$selected = '';
		if ($current_module == $val['module_id'] )
		{
			$selected = ' selected ';
		}
		echo '<option value="'.$val['module_id'].'" class="input-xlarge option" id="DropDownTimezone-'.$val['module_id'].'"' . $selected .'>'.$val['module_name'].'</option>';
	}
?>