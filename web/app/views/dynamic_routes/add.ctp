	<div id="title">
    <h1><?php echo __('adddynamicroute')?></h1>
    <ul id="title-menu">
    		<li>
    			<a class="link_back" href="<?php echo $this->webroot?>dynamicroutes/view">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/rerating_queue.png"/>
    				&nbsp;<?php echo __('goback')?>
    			</a>
    		</li>
  		</ul>
	</div>
<div class="container">    <script type="text/javascript">winResize(940, 690);</script>
	<form action="" method="POST" autocomplete='Off' id="addForm">
		<table class="cols"><col width="35%"/><col width="38%"/><col width="27%"/><tr>
			<td><!-- COLUMN 1 -->
			<fieldset>
				<legend></legend>
					<table class="form">
						<tr>
					    <td><?php echo __('route_name')?>:</td>
					    <td>
					   		<?php echo $form->input('Dynamicroute.name',array('label'=>false ,'div'=>false,'type'=>'text','maxLength'=>'16'));?>
					    </td>
						</tr>
						<tr>
						    <td><?php echo __('routingrule')?>:</td>
						    <td>
						   		<?php 
						   			$arr1=array('4'=>__('routerule1',true),'5'=>__('routerule2',true),'6'=>__('routerule3',true));
						   			echo $form->input('Dynamicroute.routing_rule',array('options'=>$arr1,'empty'=>__('pleaseselectrouterule',true),'label'=>false ,'div'=>false,'type'=>'select'));
						   				?>
						    </td>
						</tr>
						<tr>
									<td><?php echo __('timeprofile')?>:</td>
									<td>
										<?php echo $form->input('Dynamicroute.time_profile_id',	array('options'=>$user,'label'=>false ,'div'=>false,'type'=>'select'));?>
									</td>
						</tr>
					</table>
				</fieldset>
			</td>
			<td>
<?php //***************************************对接设置************************************************************?>
<fieldset>
<legend></legend>
<table id="egress_table" class="form">
	<tr>
	    <td  style="text-align: left;"></td>
	    <td  style="text-align: left;"><a onclick="newItem();return false;" href="#"><img width="10" height="10" src="<?php echo $this->webroot?>images/add-small.png"> <?php echo __('createnew',true);?></a></td>
	</tr>
	<tr>
    <td  style="text-align: center;"></td>
    <td  style="text-align: left;">
    	<div id="egress_div"></div> 
    </td>
	</tr>
</table>
</fieldset>
</td>
</tr>
</table>
	<div id="form_footer">
   <input type="submit" value="<?php echo __('submit')?>"class="input in-submit" />
   <input type="reset" value="<?php echo __('reset')?>" class="input in-submit" />
	</div>
		</form>
</div>
<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>

 <script type="text/javascript">      
    var newItem = (function(){
    	var resources = <?php echo json_encode($appDynamicRoute->format_client_and_resource_options($clients),JSON_FORCE_OBJECT)?>;
    	var egressDiv = jQuery('#egress_div');
    	var count =  0;
    	var index =  0;
    	var changeEgress = function(clientSelect,egressSelect){
        	return function(){
            	var v = clientSelect.val();
            	egressSelect.html('');
    			if(resources[v]){
	            	jQuery.each(resources[v]['egress'],function(k,v){
	            		egressSelect.append('<option value="'+k+'">'+v+'</option>');
	                });          	    
            	}
        		}
        	};
    	var newItem = function(info){
        	if(count < 8){
	    		index = index + 1;
	    		count = count + 1;
	        	var clientSelect = jQuery('<select style="width:150px;margin: 4px 2px;"></select>');
	        	var egressSelect = jQuery('<select name="engress_res_id[]" style="width:150px;margin: 4px 2px;" title="select egress trunk."></select>');
	        	clientSelect.bind('change',changeEgress(clientSelect,egressSelect));
	        	clientSelect.append('<option value="">please select carrier</option>');        	 
	        	jQuery.each(resources,function(k,v){
		        	if(info && info['client_id'] && info['client_id'] == v['id']){
		        		clientSelect.append('<option value="'+v['id']+'" selected="selected">'+v['name']+'</option>');
		        		egressSelect.html('');
		    			if(v['egress']){
			            	jQuery.each(v['egress'],function(rk,rv){
			            		if(info['resource_id'] && info['resource_id'] == rk){
			            			egressSelect.append('<option value="'+rk+'" selected="selected">'+rv+'</option>');
			            		}else{
			            			egressSelect.append('<option value="'+rk+'">'+rv+'</option>');
			            		}
			                });          	    
		            	}
		        	}else{
	        			clientSelect.append('<option value="'+v['id']+'">'+v['name']+'</option>');
		        	}
	            });
	            var newDiv = jQuery('<div  id="egress_div_'+index+'"></div>');
	            newDiv.append(clientSelect);
	            newDiv.append(egressSelect);
	            if(count > 1){// 第一个不能删除
		            var delButton = jQuery('<a href="#">remove</a>');
		            delButton.bind('click',(function(i){return function(){count = count - 1;jQuery('#egress_div_'+i).remove()}})(index));
	            }else{
	            	var delButton = jQuery('<span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>');
	            }
	            newDiv.append(delButton);
	        	egressDiv.append(newDiv);
        	}else{
        		jQuery.jGrowl.defaults.position = 'top-center';
        		jGrowl_to_notyfy("Egress can only add 8",{theme:'jmsg-alert'}); 
        		return ;
        	}
    	};
    	return newItem;
    })();
    <?php if(isset($res_dynamic) && !empty($res_dynamic) && isset($egresses)):?>
		<?php
			foreach($res_dynamic as $res_egress_id):
			$egress = $appDynamicRoute->find_egress($res_egress_id,$egresses);
			if($egress):
		?>		
		newItem({'client_id' : '<?php echo $egress['Resource']['client_id']?>','resource_id' : '<?php echo $egress['Resource']['resource_id']?>' });
		<?php
				endif; 
			endforeach;
		?>    
    <?php else:?>
    	newItem();
    <?php endif;?>

</script>
<script type="text/javascript">
    jQuery(document).ready(function(){
       jQuery('#addForm').submit(function(){
          if(jQuery('#DynamicrouteName').val()==''){
        	     jQuery('#DynamicrouteName').addClass('invalid');
        	     jGrowl_to_notyfy('Strategy Name cannot be NULL!',{theme:'jmsg-error'});
              return false;
                         }
         return true;
                          
                 });
   jQuery('#DynamicrouteName').xkeyvalidate({type:'strNum'});
    });
</script>