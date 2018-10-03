<div id="title">
	<h1>
		<?php __('Configuration') ?>
		&gt;&gt;
		<?php __('Schedule') ?>
	</h1>
	<ul id="title-search">
		<li>
			<form name="myform" method="get">
			Job Name:
			<select name="job_name">
				<option value="">All</option>
				<option <?php echo isset($_GET['job_name']) && $_GET['job_name'] == '1' ? "selected='selected'":'' ?> value="1">create cdr/report table</option>
				<option <?php echo isset($_GET['job_name']) && $_GET['job_name'] == '2' ? "selected='selected'":'' ?> value="2">update db record</option>
				<option <?php echo isset($_GET['job_name']) && $_GET['job_name'] == '3' ? "selected='selected'":'' ?> value="3">upload check</option>
				<option <?php echo isset($_GET['job_name']) && $_GET['job_name'] == '4' ? "selected='selected'":'' ?> value="4">alert route</option>
				<option <?php echo isset($_GET['job_name']) && $_GET['job_name'] == '5' ? "selected='selected'":'' ?> value="5">cdr report</option>
				<option <?php echo isset($_GET['job_name']) && $_GET['job_name'] == '6' ? "selected='selected'":'' ?> value="6">create invoice</option>
				<option <?php echo isset($_GET['job_name']) && $_GET['job_name'] == '7' ? "selected='selected'":'' ?> value="7">qos report</option>
				<option <?php echo isset($_GET['job_name']) && $_GET['job_name'] == '8' ? "selected='selected'":'' ?> value="8">ftp cdr</option>
				<option <?php echo isset($_GET['job_name']) && $_GET['job_name'] == '9' ? "selected='selected'":'' ?> value="9">rerate cdr</option>
				<option <?php echo isset($_GET['job_name']) && $_GET['job_name'] == '10' ? "selected='selected'":'' ?> value="10">cdr import</option>
				<option <?php echo isset($_GET['job_name']) && $_GET['job_name'] == '11' ? "selected='selected'":'' ?> value="11">dns dig</option>
                                <option <?php echo isset($_GET['job_name']) && $_GET['job_name'] == '12' ? "selected='selected'":'' ?> value="12">finance transaction</option>
                                <option <?php echo isset($_GET['job_name']) && $_GET['job_name'] == '13' ? "selected='selected'":'' ?> value="13">summary report</option>
                                <option <?php echo isset($_GET['job_name']) && $_GET['job_name'] == '14' ? "selected='selected'":'' ?> value="14">low balance alert</option>
                                <option <?php echo isset($_GET['job_name']) && $_GET['job_name'] == '15' ? "selected='selected'":'' ?> value="15">real cdr</option>
                                <option <?php echo isset($_GET['job_name']) && $_GET['job_name'] == '16' ? "selected='selected'":'' ?> value="16">cdr download</option>
			</select>
                Period:
                <input type="text" class="input in-text in-input" name="start_time" style="width:120px;" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" value="<?php echo date('Y-m-d 00:00:00') ?>">
                ~
                <input type="text" class="input in-text in-input" name="end_time" style="width:120px;" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" value="<?php echo date('Y-m-d 23:59:59') ?>">
                <input type="submit" value="Query" class="input in-submit query_btn">
            </form>
		</li>
	</ul>
</div>

<div id="container">

	<ul class="tabs">
        <li>
            <a href="<?php echo $this->webroot ?>backend_job/schedule">
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/list.png">Schedule 		
            </a>
        </li>
        <li>
            <a href="<?php echo $this->webroot ?>backend_job/trigger">
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/list.png">Trigger  		
            </a>
        </li>
        <li class="active">
            <a href="<?php echo $this->webroot ?>backend_job/logging">
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/list.png">Log  		
            </a>
        </li>
    </ul>
	<?php if(empty($this->data)):  ?>
    <div class="msg"><?php echo __('no_data_found',true);?></div>
    <?php else: ?>
    <?php echo $this->element("xpage")?>
	<table class="list">
		<thead>
			<th>Job Name</th>
			<th>Start Time</th>
			<th>End Time</th>
		</thead>
		<tbody>
			<?php foreach($this->data as $item): ?>
			<tr>
				<td><?php echo $jobs_names[(int)$item['Class4Log']['run_type']] ?></td>
				<td><?php echo $item['Class4Log']['start_time'] ?></td>
				<td><?php echo $item['Class4Log']['end_time'] ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php echo $this->element("xpage")?>
	<?php endif; ?>
</div>
