<div id="title">
 <h1><?php  __('Management')?> &gt;&gt;<?php echo __('client')?></h1>
	<?php echo $this->element("search")?>
	<ul id="title-menu">
	<?php 
	$login_type=$_SESSION['login_type'];
	if($login_type==1){?>
	<?php  if ($_SESSION['role_menu']['Management']['clients']['model_w']) {?>
            
            <li>
	    	<a class="link_btn" href="<?php echo $this->webroot?>ratemailhistorys">
	    		<?php echo __('Rate Amendment History',true);?>
	    	</a>
	    </li>
            
		<li>
	 		<?php echo $this->element("createnew",Array('url'=>'clients/add'))?>
	 	</li>
	 	<?php }?>
	
		<?php if($this->params['hasGet']){?>
	    <li>
	    	<a class="link_back" href="<?php echo $this->webroot?>clients/index">
	    		<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">&nbsp;<?php echo __('goback',true);?>
	    	</a>
	    </li>
	    <?php }?>
	    <?php  if ($_SESSION['role_menu']['Management']['clients']['model_x']) {?>
	    <li>
       		<a class="list-export" href="<?php echo $this->webroot?>downloads/carrier"><!--<img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/export.png">--> Export</a>
      </li>
     <?php  }?>
       	<?php }?>
	</ul>
</div>
<div id="container">
<?php echo $this->element("clients/search")?>
<div id="list_div">
<?php if($_SESSION['login_type']==1){?>
<?php echo $this->element("clients/list")?>
<?php }else{?>
<?php echo $this->element("clients/list_one")?>
<?php }?>
</div>
<?php echo $this->element("clients/update_password")?>
</div>
<script type="text/javascript">
jQuery('#show_static').change(
		function(){
			   if(jQuery(this).attr('checked')){
			   	location="?filter_status=true";
			   }else{
			     location="?";
			         }
			}
);

</script>
