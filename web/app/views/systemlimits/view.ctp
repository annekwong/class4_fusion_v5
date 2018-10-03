<link href="<?php echo $this->webroot?>images/favicon.ico" type="image/x-icon" rel="shortcut Icon">
<script type="text/javascript">
	function save_device(){
		var nm = document.getElementById("username").value;
		var pw = document.getElementById("password").value;
		var id = document.getElementById("res").value;
		jQuery.post("<?php echo $this->webroot?>testdevices/save_device",{n:nm,p:pw,r_id:id},function(d){
			jGrowl_to_notyfy(d,{theme:'jmsg-alert'});
		});
	}
</script>
<style>
    .form td{
        border-width:1px;
        border-style:solid;
        border-color:rgb(204, 204, 204) rgb(227, 229, 230) rgb(204, 204, 204) rgb(204, 204, 204);
    }
    
</style>
<ul class="breadcrumb">
    <li><?php echo __('You are here') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Switch') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php echo __('Capacity') ?></li>
</ul>


<div class="heading-buttons">
    <h4 class="heading"><?php echo __('Capacity') ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>


<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        
        <div class="widget-body">
  <table class="list footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary" >
      <thead>
          <tr >
          <th>
              <?php echo __('Expiration Date') ?>:<?php echo date("Y-m-d H:i:s", intval(substr(strip_tags($date[4]), 0, 10)));?>
          </th>
          
          <th>
              <?php echo __('Self-Defined Limit') ?>
          </th>
          
          
          <th>
              <?php echo __('License Limit') ?>
          </th>
          
      </tr>
      </thead>
    <tbody>
        
      
      <tr>
        <td ><?php echo __('Call limit')?>:</td>
        <td ><input style="width:150px;" type="text" id="ingrLimit" 
    value="<?php echo strip_tags($date[0]);?>"  name="ingrLimit" class="input in-text"></td>
        <td style="text-align:center">
            <?php echo strip_tags($date[2]);?>
            <input  value="<?php echo strip_tags($date[2]);?>" disabled="true " style="width:150px" type="hidden" id="capLicense">
        </td>
      </tr>
      <tr>
        <td><?php echo __('CPS Limit')?>:</td>
        <td ><input style="width:150px;" type="text" id="ingrpLimit" 
    		
    		    value="<?php echo strip_tags($date[1]);?>" 
    		name="ingrpLimit" class="input in-text"></td>
        <td style="text-align:center">
           <?php echo strip_tags($date[3]);?>
            <input value="<?php echo strip_tags($date[3]);?>" disabled="true "  style="width:150px" type="hidden" id="cpsLicense">
        </td>
      </tr>
    </tbody>
  </table>
            <hr class="separator">
				
				<!-- Form actions -->
				<div class="form-actions">
                                          <?php  if ($_SESSION['role_menu']['Switch']['systemlimits']['model_w']) {?>
					<button onClick="javascript:postLimit();return false;" class="btn btn-icon btn-primary glyphicons circle_ok" type="submit"><i></i><?php __('Save')?></button>
					<?php }?>
				</div>
				<!-- // Form actions END -->
           
        </div></div>
    
    <div class="widget widget-heading-simple widget-body-gray">
		
			<!-- Widget heading -->
			<div class="widget-head">
				<h4 class="heading"><?php __('Upload License')?></h4>
			</div>
			<!-- // Widget heading END -->
			
			<div class="widget-body">
			
				<!-- Row -->
				<div class="row-fluid">
				
					<!-- Column -->
					<div class="span6">
					
						<!-- Group -->
						<div class="control-group">
							<label for="firstname" class="control-label"><?php __('Choose license')?></label>
							<div class="controls">
                                                            <form enctype="multipart/form-data" action="<?php echo $this->webroot; ?>systemlimits/upload_license" method="post">
                                                            <input type="file" name="license"/>
                                                            <input type="submit" class="btn" value="<?php __('Upload')?>">
                                                            </form>
                                                        </div>
						</div>
						<!-- // Group END -->
						
					</div>
					<!-- // Column END -->
					
					<!-- Column -->
					<div class="span6">
					
						<!-- Group -->
						<div class="control-group">
							<label for="password" class="control-label"><?php __('Download License')?></label>
							<div class="controls">
                                                             <a href="<?php echo $this->webroot ?>systemlimits/down_license_key" title="Download License Key">
                                                                <img src="<?php echo $this->webroot?>images/license.png" />
                                                            </a>
                                                        </div>
						</div>
						<!-- // Group END -->
						
						
						
					</div>
					<!-- // Column END -->
					
				</div>
				<!-- // Row END -->
				
				
				
			</div>
		</div>
</div>   

  
<script type="text/javascript">
        function postLimit(){
                                var ingrl = $('#ingrLimit').val();
                                var ingrp = $('#ingrpLimit').val();


                                var capLin = $('#capLicense').val();
                                var cpsLin = $('#cpsLicense').val();
                                
                                //alert(ingrl+"::"+ingrp+"::"+capLin+"::"+cpsLin);
                                
                                if(ingrl > capLin){
                                    showMessages("[{'field':'#ingrLimit','code':'101','msg':'Self-Defined Limit can not be greater than License Limit '}]");
                                    return false;
                                }
                                
                               
                                if((cpsLin - ingrp)<0){
                                    showMessages("[{'field':'#ingrLimit','code':'101','msg':'Self-Defined Limit can not be  greater than License Limit '}]");
                                    return false;
                                }
                                
                               
                                
                                var pattern = /^[1-9]{1}[0-9]*$/;

                                if(!pattern.test(ingrl)){
                                        showMessages("[{'field':'#ingrLimit','code':'101','msg':'<?php echo
__('calllimitinvalid',true)?>'}]");
                                        //alert('The Call Limit is invalid!');
                                        return false ;
                                }
                                
                                

                                if(!pattern.test(ingrp)){
                                        showMessages("[{'field':'#ingrpLimit','code':'101','msg':'<?php
echo __('ingrpLimitinvalid',true)?>'}]");
                                        //alert('The CPS Limit is invalid!');
                                        return false ;
                                }
                                
                                
                                if(parseInt(ingrl) > 60000) {
                                        showMessages("[{'field':'#ingrLimit','code':'101','msg':'The `Call limit` must be less or equal than 60000'}]");
                                        return false ;
                                }
                                
                                if(parseInt(ingrp) > 2000) {
                                        showMessages("[{'field':'#ingrpLimit','code':'101','msg':'The `CPS Limit:` must be less or equal than 2000'}]");
                                        return false ;
                                }

                                $.ajax({
                                                url:"<?php echo $this->webroot?>systemlimits/ajax_update.json",
                                                data:{ingressC:ingrl,ingressP:ingrp},
                                                type:'POST',
                                                async:true,

success:function(text){

if(text==1){
	showMessages("[{'field':'','code':'201','msg':'Succeeded!'}]");

	
}
                                                	},

error:function(XmlHttpRequest){showMessages("[{'field':'#ingrLimit','code':'101','msg':'"+XmlHttpRequest.responseText+"'}]");}
                                });
                                        
                }
        
        
        
        </script> 
<script type="text/javascript">
jQuery(document).ready(
		function(){
				jQuery('#ingrLimit,#ingrpLimit').xkeyvalidate({type:'Num'});		
		}
);
</script>