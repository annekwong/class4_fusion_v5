
                            
                            <tr>
                           <td>

</td>
                            <td class="value value2">
                              <a id="addHost" onclick="return false;" href="#">
                                  <i class="icon-plus"></i> <?php echo __('Add Authorized Access IP',true);?>
</a>                         
<table class="list list-form table table-condensed"  id="host_table">
   <thead>
     <!--tr>
       <th >
          <span rel="helptip" class="helptip" id="ht-100002"><?php echo __('ip',true);?></span>
          <span class="tooltip" id="ht-100002-tooltip"></span>
       </th>
       
       <th  style="display:none">
          <span rel="helptip" class="helptip" id="ht-100003"><?php echo __('Netmask',true);?></span>
          <span class="tooltip" id="ht-100003-tooltip"></span>
       </th>
       

       <td  class="last"></td>
     </tr-->
  </thead>
  <tbody class="rows" id="rows-ip">
     <?php if(isset($hosts) && is_array($hosts)){
     
     	?>
       <?php foreach($hosts as $host){?>
       <?php
            $ip = '';
       		if( array_keys_value($host,'0.ip')){
       				$ip=array_keys_value($host,'0.ip');
       		
       		}
       			 ?>
       <?php $ip=split("/",$ip)?>
          <tr>
            <td style="width: 150px;" class="value">
            		<input type="text" style="width: 150px;" class="validate[custom[ipv4]]" name="accounts[ip][]" id="ip" value="<?php echo array_keys_value($ip,0)?>">
            </td>
           
            <td style="width: 80px;display:none">
            		<?php echo $xform->input('need_register.',Array('value'=>array_keys_value($ip,1),'name'=>'accounts[need_register][]','autocomplete'=>'off','options'=>array('32'=>32,'31'=>31,'30'=>30,'29'=>29,'28'=>28,'27'=>27,'26'=>26,'25'=>25,'24'=>24),'style'=>'width:50px'))?>
            </td>
        		
			        
             <td style="width: 50px;" class="value last">
                	<a href="#" title="Delete" rel="Delete" onclick="$(this).closest('tr').remove();">
                            <i class="icon-remove"></i>
                  </a>
             </td>
           </tr>
         <?php }?>
         <?php }?>
           <tr id="mb" style="">
              <td class="value">
                <input type="text"  name="accounts[ip][]" class="validate[custom[ipv4]]"  id="ip">
              </td>
              
	            <td style="display:none">
	            		<?php echo $xform->input('need_register.',Array('name'=>'accounts[need_register][]','options'=>array('32'=>32,'31'=>31,'30'=>30,'29'=>29,'28'=>28,'27'=>27,'26'=>26,'25'=>25,'24'=>24),'style'=>'width:50px'))?>
	            </td>
        			
              
              <td  class="value last">
                <a href="#" title="Delete" rel="Delete" onclick="$(this).closest('tr').remove();">
                    <i class="icon-remove"></i>
                </a>
              </td>
             </tr>
            </tbody>
           </table>
			<script type="text/javascript">
							jQuery(document).ready(function(){
        				var mb=jQuery('#mb').remove();
				        jQuery('#addHost').click(function(){
				           mb.clone(true).appendTo('#host_table tbody');
				           return false;
				        			});
				        jQuery('input[id=port]').xkeyvalidate({type:'Num'});
				        jQuery('form[id^=Gatewaygroup]').submit(function(){
               var re=true;
               if(jQuery('input[id=alias]:last').val()==''){
                 jQuery('input[id=alias]:last').jGrowlError('Name, cannot be Null！');
                 re=false;
                        			}
              jQuery('input[id=port]').each(function(){
                 if(jQuery(this).val()!='' && isNaN(jQuery(this).val())){
                   jQuery(this).jGrowlError('Port,must be whole number! ');
                   re=false;
                                         }
                        			});
             	jQuery('input[id=ip]').each(function(){
                if(jQuery(this).val().indexOf('.')==-1 || jQuery(this).val().indexOf('/')!=-1){
                  jQuery(this).jGrowlError('Invalid IP Address.');
                  re=false;
                                        }
                        			});
            		var arr=Array();
             	jQuery('#host_table tr').each(function(){
	             	for(var i in arr){
	               	if(jQuery(this).find('input[id=ip]').val()==arr[i].ip ){
	               		jQuery.jGrowlError('Ip '+arr[i].ip+" is Repeat!");
	               	 re=false;
	                	return;
	                                     	 }
	                                	}
	            	 if(jQuery(this).find('input[id=ip]').val()!=''){
											arr.push({ip:jQuery(this).find('input[id=ip]').val(),port:jQuery(this).find('input[id=port]').val()});
	                                	}
                       				});
   							if(re){
										var arr=Array();
										jQuery('#host_table tr').each(function(){
											if(jQuery(this).find('#ip').size()>0){
							        arr.push(jQuery(this).find('#ip').val()+'/'+jQuery(this).find('#GatewaygroupNeedRegister').val());
											}
						               	});
						      arr=arr.join(',');
							  		var data=jQuery.ajaxData("<?php echo $this->webroot?>ajaxvalidates/ip4r/noDomain?ip="+arr);
							  		data='['+data+']';
							  		data=eval(data);
							  		data=data[0];
							  		for(var i in data){
								  		if(data[i]==false){
									  			var eq=parseInt(i)+1;
									  			jQuery('#host_table tr').eq(eq).find('#ip,#GatewaygroupNeedRegister').jGrowlError(jQuery('#host_table tr').eq(eq).find('#ip').val()+'/'+jQuery('#host_table tr').eq(eq).find('#GatewaygroupNeedRegister').val()+' is not ip!');
									  			re=false;
								  			}
							  			}
   									}
             	return re;
        						});
							});
</script>

							</td>					
                            </tr>
						