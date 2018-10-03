<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<div id="cover"></div> 
<?php echo $this->element("jur_country/title")?>
<div id="container">
<ul class="tabs">
    <li  class="active"><a href="<?php echo $this->webroot?>jurisdictioncountrys/view"><img width="16" height="16" src="<?php echo $this->webroot ?>images/menuIcon.gif"><?php echo __('List',true);?></a></li>
    <li  ><a href="<?php echo $this->webroot?>uploads/jur_country"><img width="16" height="16" src="<?php echo $this->webroot ?>images/import.png"> <?php echo __('import',true);?></a></li> 
    <li><a href="<?php echo $this->webroot?>downloads/jur_country"><img width="16" height="16" src="<?php echo $this->webroot ?>images/export.png"> <?php echo __('export',true);?></a></li>   
</ul>
<?php echo $this->element("jur_country/container")?>
<script type="text/javascript">
    jQuery(document).ready(function(){
          jQuery('input[rel]').xkeyvalidate({type:'strNum'});
          eTpl.remove();
         });
    	jQuery(document).ready(function(){
   		 jQuery('#sub').click(function(){
		        var re=true;
		        jQuery('#objectForm').find('input[rel=format_number]').map(
									function(){
										jQuery(this).removeClass('invalid');
										if(jQuery(this).val()=='')
										{
											jQuery(this).addClass('invalid');
											jGrowl_to_notyfy('Name cannot be NULL!',{theme:'jmsg-error'});
											re=false;
										}
									}
		                	);
		        jQuery('#objectForm').find('input[rel=format_number]').map(function(){
			        		jQuery(this).removeClass('invalid');
		            if(/\W/.test(jQuery(this).val())){
		          	   jQuery(this).addClass('invalid');
							         jGrowl_to_notyfy('Prefix is uqine!',{theme:'jmsg-error'});
						         	re=false;
		                                     }
		                         })
		    			if(re)
		    				{
		    				jQuery(this).attr('disabled',true);
		    		   jQuery('#objectForm').submit();
		    				}
				    
		        	}
		    	);
        	});    
   
</script>
</div>
