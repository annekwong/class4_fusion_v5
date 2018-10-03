<link href="<?php echo $this->webroot?>images/favicon.ico" type="image/x-icon" rel="shortcut Icon">
	<div id="infodiv" class="tooltip" style="position:absolute;width:300px;height:18px;border:2px solid lightgray;background:lightblue">
		<?php echo __('startdate')?>:<span id="st" style="color:yellow;"></span>&nbsp;--&nbsp;<?php echo __('enddate')?>:<span id="et" style="color:yellow;"></span>
	</div>
<div id="title">
  <h1><?php echo __('addroutes')?>&gt;&gt;</h1>
  <ul id="title-menu">
    <li><a class="link_back" href="<?php echo $this->webroot?>products/route_info/<?php echo $pid?>"> <img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">&nbsp;<?php echo __('goback')?></a></li>
  </ul>
</div>
<div class="container">
<?php echo $session->read('strategy');$session->del('strategy');?>
<form method="post" id="add_rou"  onsubmit="whensubmit();" action="<?php echo $this->webroot?>products/add_route">
<input type="hidden" id="totalpercentages" name="totalpercentages"/>
<input type="hidden" id="product_id" name="product_id" value="<?php echo $pid?>"/>
<table class="cols"><col width="44%"><col width="56%">
	<tbody>
		<tr>
			<td class="first">
			<!-- COLUMN 1 -->
				 <fieldset>
				 		<legend><?php echo __('basicinfo')?></legend>
						<table class="form">
							<col style="width: 35%;">
							<col style="width: 65%;">
							<tbody>
								 <tr>
								    <td class="label"><?php echo __('prefix')?>:</td>
								    <td class="value">
								    		<input type="text" id="digits" maxLength="32" value="" name="digits" class="input in-text">
								    </td>
									</tr>
									<tr>
								    <td class="label"><?php echo __('approach')?>:</td>
								    <td class="value">
								    		<select id="strategy" onchange="bypercentage(this);" name="strategy" class="input in-select">
								    				<option value="1" selected>»&nbsp;<?php echo __('topdown')?></option>
								    				<option value="0" >»&nbsp;<?php echo __('bypercentage')?></option>
								    				<option value="2">»&nbsp;<?php echo __('roundrobin')?></option>
								    		</select>
								    </td>
									</tr>
									<tr>
								    <td class="label"><?php echo __('timeprofile')?>:</td>
								    <td class="value">
								    		<select id="time_profile_id" name="time_profile_id" class="input in-select">
								    		<option value=""></option>
							    			<?php
							    				$loop = count($timeprofiles); 
							    				for ($i = 0;$i<$loop;$i++) {
							    				?>
								    		<option value="<?php echo $timeprofiles[$i][0]['time_id']?>">
								    				<?php echo $timeprofiles[$i][0]['name']?>
								    		</option>
								    		<?php }?>
								    		</select>
								    </td>
									</tr>
							</tbody>
						</table>
					</fieldset>
				</td>
				<td class="last">
					<fieldset>
						<legend>
								<a href="#" onclick="add_route_resource('Trunk','Percentage 1');">
										<img src="<?php echo $this->webroot?>images/add.png"> <?php __('Add New')?>
								</a><?php echo __('alias')?>
						</legend>
						<table class="form" id="byother">
							<tbody id="resourcetab">
									<tr id="resourceconfig">
										 <td class="label"  style="width:50px;vertical-align:middle;"><?php echo __('Carriers',true);?></td>
										 <td style="width:120px;">
										 		<?php echo $form->input('client_id',Array('div'=>false,'label'=>false,'name'=>'','style'=>'width:80px','options'=>Array()))?>
										 </td>
								    <td class="label"  style="width:50px;vertical-align:middle;"><?php echo __('route1')?>:</td>
								    <td style="width:900px">
								    		<select style="width:80px;" class="input in-select" id="resource_id_1" name="resource_id_1">
								    				<option value=""></option>
						    						<?php 
						    								$loop = count($resource);
						    								for ($i = 0;$i<$loop;$i++) {
						    							?>
								    				<option value="<?php echo $resource[$i][0]['resource_id']?>"><?php echo $resource[$i][0]['name']?></option>
								    				<?php }?>
								    		</select>
								    		<span class="dd" style="display: none;">
								    				<?php echo __('Percentage')?>:<input disabled style="width:150px;text-align:right;" name="percentage_1" id="percentage_1"/>
								    		</span>
								    		<a title='delete' href='' onclick="del_route(this);return false;">
								    				<i class='icon-remove'></i>
								    		</a>
								    </td>
									</tr>
								</tbody>
							</table>
					</fieldset>
				</td>
		  </tr>
	</tbody>
</table>
<div id="form_footer">
	  <input type="submit" value="<?php echo __('submit')?>" class="input in-submit">
	  <input type="reset" value="<?php echo __('reset')?>" class="input in-submit">
</div>
</form>
</div>
<script type="text/javascript">
jQuery('document').ready(function(){
		var data=jQuery.ajaxData("<?php echo $this->webroot?>clients/client_options");
		jQuery('#client_id').html(jQuery(data).html());
		jQuery('#client_id').change(
			function(){
				var data=jQuery.ajaxData("<?php echo $this->webroot?>trunks/ajax_options?filter_id="+jQuery(this).val());
				data=eval(data);
				var select=jQuery(this).parent().parent().find('select[id^=resource_id]').html('');
				for(var i in data){
					jQuery('<option/>').html(data[i].alias).val(data[i].resource_id).appendTo(select);
				}
			}
		).change();
		jQuery('#byother tr').each(function(){jQuery(this).find('a[title=delete]').hide()});
		jQuery('#byother tr:last').find('a[title=delete]').show();
		jQuery('#strategy').change();
});
</script>
<script type="text/javascript">
		jQuery('#strategy').change(function(){
       if(jQuery(this).val()==1||jQuery(this).val()==2){
           jQuery('.dd').attr('style','display:none');
                   }
       if(jQuery(this).val()==0){
           jQuery('.dd').attr('style','display:inline');
                  }
		});
</script>
<!-- 如果验证没通过  将用户输入的表单信息重新显示 -->
<?php
			$backform = $session->read('backform');//用户刚刚输入的表单数据
			if (!empty($backform)) {
				$session->del('backform');//清除错误信息
		
				//将用户刚刚输入的数据显示到页面上
				$d = array_keys($backform);
			 foreach($d as $k) {?>
						<script>if (document.getElementById("<?php echo $k?>"))document.getElementById("<?php echo $k?>").value = "<?php echo $backform[$k]?>";</script>
<?php }?>
		<script>bypercentage(document.getElementById("strategy"));</script>
<?php 
			if ($backform && array_keys_value($backform,'totalpercentages') != 1) {
				for ($i = 1;$i<array_keys_value($backform,'totalpercentages');$i++) {
?>
					<script>add_route_resource('<?php echo __('route1')?>','<?php echo __('weight1')?>','<?php echo $backform['resource_id_'.($i+1)]?>','<?php echo $backform['percentage_'.($i+1)]?>');</script>
	<?php } ?>
<?php }}?>
<script type="text/javascript">
   jQuery(document).ready(function(){
         jQuery('#add_rou').submit(function(){
					     		for (var i = 1;i<=8;i++) {
					    			var se = document.getElementById("percentage_"+i);
					    			if (se) {
					    				if (se.disabled == true) {
					    					   se.disabled = false;
					    				    	document.getElementById("percentage_"+i).value = "";
								    				}
								    			}
								    		}
					         var flag=true;
					         var f=0;
					         var arr = new Array();
					      		$('#byother').find('select[name*=resource_id]').each(function (){
						      		if($(this).val()==null ||$(this).val()==''){
													f=1;
													flag=false;
							      		}
					      			arr.push($(this).val());
					          		});
					       var arr2=$.uniqueArray(arr);
					      		if(arr.length!=arr2.length){
					         		$('#list_table').find('select[name*=resource_id]').each(function (){
					         			jQuery(this).addClass('invalid');
					         			flag=false;
					              		});
					        		jGrowl_to_notyfy('The Trunk  Happen  Repeat.',{theme:'jmsg-error'});
					        		flag=false;
					          		}
									if(f==1){
										flag=false;
										jGrowl_to_notyfy('The Trunk  is   NULL.',{theme:'jmsg-error'});
									}
					    		if (document.getElementById("totalpercentages")) {
					    			document.getElementById("totalpercentages").value = document.getElementById("resourcetab").rows.length;
					    	     	}
    		        if(jQuery('#digits').val()==''){
    		        	jQuery('#digits').addClass('invalid');
						       jGrowl_to_notyfy('Prefix cannot be NULL!',{theme:'jmsg-error'});
							   	flag=false;
        		                      }   
              return flag;
                        });
	   			jQuery('#digits').xkeyvalidate({type:'Num'});
	   });
   function add_route_resource(){
	   var size=jQuery('#resourceconfig').parent().find('tr').size();
	   if(size>7){
		   		return;
	   		}
	   if(jQuery('#byother tr:nth-child(1)').css('display')=='none'){
		   jQuery('#byother tr').show();
	   	return;
 			}
	   var tr=jQuery('#resourceconfig').clone(true).appendTo(jQuery('#resourceconfig').parent());
	   tr.find('select[id^=resource_id]').attr('id','resource_id_'+(size+1)).attr('name','resource_id_'+(size+1)).change();
			jQuery('#byother tr').each(function(){jQuery(this).find('a[title=delete]').hide()});
			jQuery('#byother tr:last').find('a[title=delete]').show();
   		}
		function del_route(that){
			var size=jQuery('#resourceconfig').parent().find('tr').size();
	   if(size==1){
				jQuery(that).parent().parent().hide().find('input,select').val('');
		   return;
	   		}
			jQuery(that).parent().parent().remove();
			jQuery('#byother tr:last').find('a[title=delete]').show();
			return false;
		}
</script>




	
