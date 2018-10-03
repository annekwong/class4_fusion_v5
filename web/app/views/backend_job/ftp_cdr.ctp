<div id="title">
	<h1>
		<?php __('Configuration') ?>
		&gt;&gt;
		<?php __('Trigger') ?>
	</h1>
    
    <ul id="title-menu">
        <li>
            <a class="link_back_new" href="<?php echo $this->webroot ?>backend_job/trigger"> 
                <img width="16" height="16" alt="Back" src="<?php echo $this->webroot ?>images/icon_back_white.png">&nbsp;Back 
            </a>
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
    
    <div style="text-align:center;">
        <form method="post" name="myfrm">
            <input type="text" name="start_time" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" />
            <input type="text" name="end_time" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" />
            <input type="submit" value="Execute" />
        </form>
    </div>
    
</div>
