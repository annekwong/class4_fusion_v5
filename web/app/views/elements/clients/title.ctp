<div id="title">
 <h1><?php  __('Finance')?> &gt;&gt;<?php echo __('client')?></h1>
	<?php echo $this->element("search")?>
	<ul id="title-menu">
	<?php 
	$login_type=$_SESSION['login_type'];
	if($login_type==1){?>
		<li>
	 		<?php echo $this->element("createnew",Array('url'=>'clients/add'))?>
	 	</li>
	 	
	
		<?php if($this->params['hasGet']){?>
	    <li>
	    	<a class="link_back" href="<?php echo $this->webroot?>clients/index">
	    		<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png"><?php echo __('goback')?>
	    	</a>
	    </li>
	    <?php }?>
	    <li>
       		<a class="list-export" href="<?php echo $this->webroot?>downloads/carrier"><!--<img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/export.png">--> <?php echo __('export',true);?></a>
      </li>
      
       	<?php }?>
	</ul>
</div>