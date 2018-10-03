<div id="title">
   <h1><?php echo __('Task Schedule')?>&gt;&gt;<?php echo __('Edit Schedule')?>
         <font  class="editname" title="Name">   <?php echo empty($p['name'])||$p['name']==''?'':"[".$p['name']."]" ?></font>
   
   </h1>
   <ul id="title-menu">
		<li>
			<?php echo $this->element('xback',Array('backUrl'=>'tasks/view'))?>
		</li>
  	</ul>
</div>
<div class="container">

<?php $id=array_keys_value($this->params,'pass.0')?>
<?php echo $form->create ('Task', array ('action' =>'edit_task/'.$id, 'onsubmit'=>"return save_task();"));?>
	<table width="100%">
		<col width="100%"/>		
		<tbody>
			<tr>
				<td class="first" style="vertical-align:top;">
    				<table class="list list-form">
    					<tbody>
    						<tr class="row-1">
        						<td class="label"><?php echo __('Task Schedule name')?>:</td>
        						<td class="value">
        						<input  type="hidden"   id="task_id" name="task_id" value="<?php echo array_keys_value($this->params,'pass.0'); ?>">  
        										<b>
        							<?php echo empty($p['name'])?'':$p['name'];?></b>
        							</td>
        							<td class="help last"><?php __('not null')?></td>
    						</tr>
    						<tr class="row-2">
        						<td class="label"><?php echo __('Active')?>:</td>
        						<td class="value">
        						<?php 
 							 $tmp=array('0'=>'Disable','1'=>'Enable');
         		echo $form->input('flag',
 		array('options'=>$tmp, 'label'=>false , 'div'=>false, 'type'=>'select', 'class'=>'input in-text', 'selected'=>empty($p['flag'])?0:intval($p['flag'])));
    ?>
    									</td>
    									<td class="help last"></td>
    						</tr>
    						<tr class="row-1">
        						<td class="label"><?php echo __('Minute')?>:</td>
        						<td class="value">
        				<?php echo $form->input('cron_minute',array('label'=>false ,'div'=>false,'type'=>'text','maxLength'=>'66', 'value'=>empty($p['cron_minute'])?'':$p['cron_minute']));?>
											</td>
											<td class="help last">0-59</td>
    						</tr>
    						<tr class="row-2">
        						<td class="label"><?php echo __('Hour')?>:</td>
        						<td class="value">
        				<?php echo $form->input('cron_hour',array('label'=>false ,'div'=>false,'type'=>'text','maxLength'=>'66', 'value'=>empty($p['cron_hour'])?'':$p['cron_hour']));?>
											</td>
											<td class="help last">0-23</td>
    						</tr>
    						<tr class="row-1">
        						<td class="label"><?php echo __('Day of Month')?>:</td>
        						<td class="value">
        				<?php echo $form->input('cron_day',array('label'=>false ,'div'=>false,'type'=>'text','maxLength'=>'66', 'value'=>empty($p['cron_day'])?'':$p['cron_day']));?>
											</td>
											<td class="help last">1-31</td>
    						</tr>
    						<tr class="row-2">
        						<td class="label"><?php echo __('Month')?>:</td>
        						<td class="value">
        				<?php echo $form->input('cron_month',array('label'=>false ,'div'=>false,'type'=>'text','maxLength'=>'66', 'value'=>empty($p['cron_month'])?'':$p['cron_month']));?>
											</td>
											<td class="help last">1-12</td>
    						</tr>
    						<tr class="row-1">
        						<td class="label"><?php echo __('Day of Week')?>:</td>
        						<td class="value">
        				<?php echo $form->input('cron_week',array('label'=>false ,'div'=>false,'type'=>'text','maxLength'=>'66', 'value'=>empty($p['cron_week'])?'':$p['cron_week']));?>
											</td>
											<td class="help last">0-6(Sunday=0)</td>
    						</tr>
    						
    						
						</tbody>
					</table>
				</td>				
    						
			</tr>
		</tbody>
	</table>
<div id="form_footer">
            <input type="submit" value="<?php echo __('submit')?>" class="input in-submit"/>
</div>
<?php echo $form->end();?>
<script type="text/javascript">
jQuery(document).ready(function(){
			
    //jQuery('#AlertAddConditionForm').submit(function(){ 
   //     return true;
     //});
  


 });     

function save_task()
{
	var flag = true;
	
	var cron_minute = $("#TaskCronMinute").val();
	if(!/^[0-9\/\*\-]+$/.exec(cron_minute)){
		$("#TaskCronMinute").attr('class','invalid');
		jGrowl_to_notyfy('Minute must be 0-59,*,/; search cron help',{theme:'jmsg-alert'});
		flag=false ;
	}
	else
	{
		$("#TaskCronMinute").attr('class','input in-text');
	}
	var cron_hour = $("#TaskCronHour").val();
	if(!/^[0-9\/\*\-]+$/.exec(cron_hour)){
		$("#TaskCronHour").attr('class','invalid');
		jGrowl_to_notyfy('Hour must be 0-23,*,/; search cron help',{theme:'jmsg-alert'});
		flag=false ;
	}
	else
	{
		$("#TaskCronHour").attr('class','input in-text');
	}
	var cron_day = $("#TaskCronDay").val();
	if(!/^[0-9\/\*\-]+$/.exec(cron_day)){
		$("#TaskCronDay").attr('class','invalid');
		jGrowl_to_notyfy('Day of Month must be 1-31,*,/; search cron help',{theme:'jmsg-alert'});
		flag=false ;
	}
	else
	{
		$("#TaskCronDay").attr('class','input in-text');
	}
	var cron_month = $("#TaskCronMonth").val();
	if(!/^[0-9\/\*\-]+$/.exec(cron_month)){
		$("#TaskCronMonth").attr('class','invalid');
		jGrowl_to_notyfy('Month must be 1-12,*,/; search cron help',{theme:'jmsg-alert'});
		flag=false ;
	}
	else
	{
		$("#TaskCronMonth").attr('class','input in-text');
	}
	var cron_week = $("#TaskCronWeek").val();
	if(!/^[0-9\/\*\-]+$/.exec(cron_week)){
		$("#TaskCronWeek").attr('class','invalid');
		jGrowl_to_notyfy('Day of Week must be 0-6,*,/; search cron help',{theme:'jmsg-alert'});
		flag=false ;
	}
	else
	{
		$("#TaskCronWeek").attr('class','input in-text');
	}
	
	return flag;
}

</script>
			
</div>