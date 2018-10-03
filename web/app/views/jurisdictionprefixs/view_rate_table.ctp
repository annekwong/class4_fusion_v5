<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<div id="cover"></div> 
<?php echo $this->element("jur_prefix/title")?>
<div id="container">
	<ul class="tabs">
	    <li  class="active">
	    	<a href="<?php echo $this->webroot?>jurisdictionprefixs/view">
	    		<img width="16" height="16" src="<?php echo $this->webroot ?>images/menuIcon.gif"><?php echo __('List',true);?>
	    	</a>
	    </li>
	    <li>
	    	<a href="<?php echo $this->webroot?>uploads/jur_country">
	    		<img width="16" height="16" src="<?php echo $this->webroot ?>images/import.png"> <?php echo __('import',true);?>
	    	</a>
	    </li> 
	    <li>
	    	<a href="<?php echo $this->webroot?>downloads/jur_country">
	    		<img width="16" height="16" src="<?php echo $this->webroot ?>images/export.png"> <?php echo __('export',true);?>
	    	</a>
	    </li>   
	</ul>
<?php echo $this->element("jur_prefix/container")?>
</div>
