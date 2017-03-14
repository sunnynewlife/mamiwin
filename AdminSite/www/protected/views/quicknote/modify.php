<?php if (isset($alert_message)) echo $alert_message;?>  
<div class="well">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#home" data-toggle="tab">请填写quick note</a></li>
    </ul>	
	
	<div id="myTabContent" class="tab-content">
		  <div class="tab-pane active in" id="home">

           <form id="tab" method="post" action="/quicknote/modify">
				<label><span class="label label-info">不支持HTML代码</span></label>
				<textarea name="note_content" rows="3" class="input-xlarge" required="true"><?php echo $note['note_content'];?></textarea>
				<input type="hidden" name="submit" value="1" />
				<input type="hidden" name="note_id" value="<?php echo $note_id;?>" />
				<div class="btn-toolbar">
					<button type="submit" class="btn btn-primary"><strong>提交</strong></button>
				</div>
			</form>
        </div>
    </div>
</div>