<div id="title">
	<h1>
		<?php __('Configuration') ?>
		&gt;&gt;
		<?php __('Schedule') ?>
	</h1>
	<ul id="title-menu">
		<li>
			<a href="###" title="Create New Cron Job" id="add" class="link_btn"> 
				<img width="16" height="16" src="<?php echo $this->webroot ?>images/add.png" alt="">Create New Cron Job
			</a>
		</li>
	</ul>
</div>

<div id="container">

    <ul class="tabs">
        <li class="active">
            <a href="<?php echo $this->webroot ?>backend_job/schedule">
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/list.png">Schedule 		
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot ?>backend_job/trigger">
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/list.png">Trigger  		
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot ?>backend_job/logging">
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/list.png">Log  		
            </a>
        </li>
    </ul>

    <form name="myfrm" method="post" id="myfrm">
	<table class="list">
		<thead>
			<th>Minutes[0-59]</th>
			<th>Hours[0-23]</th>
			<th>Days[1-31]</th>
			<th>Months[1-12]</th>
			<th>Day of the Week[0-7]</th>
			<th>Command</th>
			<th>Action</th>
		</thead>
		<tbody>
			<?php foreach($data as $item): ?>
			<tr>
				<td>
					<input type="text" name="minutes[]" value="<?php echo $item[0] ?>" style="width:20px;" />
				</td>
				<td>
					<input type="text" name="hours[]" value="<?php echo $item[1] ?>" style="width:20px;" />
				</td>
				<td>
					<input type="text" name="days[]" value="<?php echo $item[2] ?>" style="width:20px;" />
				</td>
				<td>
					<input type="text" name="months[]" value="<?php echo $item[3] ?>" style="width:20px;" />
				</td>
				<td>
					<input type="text" name="weeks[]" value="<?php echo $item[4] ?>" style="width:20px;" />
				</td>
				<td>
					<input type="text" name="commands[]" value="<?php echo $item[5] ?>" style="width:500px;" />
				</td>
				<td>
					<a href="###" class="delete" title="Delete">
	                    <i class='icon-remove'></i>
	                </a>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="7">
					<input type="submit" value="Submit" />
				</td>
			</tr>
		</tfoot>
	</table>
	</form>
</div>

<script>
$(function() {
	var table_body = $('table.list tbody');
	
	$('a.delete').live('click', function() {
		$(this).parent().parent().remove();
	});

	$('#add').click(function() {
		var arr = new Array();
		arr.push('<tr>');
		arr.push('<td><input type="text" name="minutes[]" class="input in-text in-input" style="width:20px;" /></td>');
		arr.push('<td><input type="text" name="hours[]" class="input in-text in-input" style="width:20px;" /></td>');
		arr.push('<td><input type="text" name="days[]" class="input in-text in-input" style="width:20px;" /></td>');
		arr.push('<td><input type="text" name="months[]" class="input in-text in-input" style="width:20px;" /></td>');
		arr.push('<td><input type="text" name="weeks[]" class="input in-text in-input" style="width:20px;" /></td>');
		arr.push('<td><input type="text" name="commands[]" class="input in-text in-input" style="width:500px;" /></td>');
		arr.push('<td><a href="###" class="delete" class="input in-text in-input" title="Delete"><i class='icon-remove'></i></a></td>');
		arr.push('</tr>');
		var html = arr.join('');
		table_body.prepend(html);
	});

	$('#myfrm').submit(function() {
		var flag = true;
		$('input:text', table_body).each(function(index, value) {
			if($.trim($(this).val()) == '')
			{
				flag = false;
			}
		});

		if(!flag)
			jGrowl_to_notyfy("Cant not empty!",{theme:'jmsg-error'});
		
		return flag;
	});
});
</script>