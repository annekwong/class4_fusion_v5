<div id="title">
	<h1>
		<?php __('Configuration') ?>
		&gt;&gt;
		<?php __('Trigger') ?>
	</h1>
</div>

<div id="container">
    
    <ul class="tabs">
        <li>
            <a href="<?php echo $this->webroot ?>backend_job/schedule">
                <img width="16" height="16" src="<?php echo $this->webroot ?>images/list.png">Schedule 		
            </a>
        </li>
        <li class="active">
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
    
    <table class="list">
        <thead>
            <tr>
                <th>Job Name</th>
                <th>Current Execution Count</th>
                <th>Action</th>
            </tr>
        </thead>
        
        <tbody>
            <?php foreach($jobs as $key => $job):  ?>
            <tr>
                <td><?php echo $job['name'] ?></td>
                <td><?php echo $job['count'] ?></td>
                <td>
                    <?php if($job['executable']): ?>
                    <a href="<?php echo $this->webroot ?>backend_job/<?php echo $job['url'] ?>" title="<?php echo $job['name'] ?>">
                        <img src="<?php echo $this->webroot ?>images/executable.png" />
                    </a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
</div>
