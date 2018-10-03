<style>
.tdfond{
 	font-weight: bold;
  width:40%;
}
td{
	margin-bottom: 50px;
	padding-bottom: 10px;
	height: 30px;
}

</style>
<table class="cols" style="width:100%">
	<tr>
		<td>
			<fieldset><legend><?php echo __('resource',true);?></legend>
				<table class="form "> 
					<tr> 
						<td class="tdfond"style="font-size:1.0em;"><?php echo __('Public/Private',true);?>:</td> 
						<td style="width:20%"><?php echo $form->input("$table.is_private",array('separator' => "<br/>",'div'=>false ,'legend' => false,'type'=>'radio','options' => array(false=>" Public",true=>" Private") ,'disabled' => $is_update))?></td> 
						<td></td>
					</tr>	
					<tr> 
						<td class="tdfond"style="font-size:1.0em;"><?php echo __('start_time',true);?>:</td> 
						<td><?php echo $form->input("$table.create_time",array('value' => $appOrderPlaces->format_wdatepicker_time(array_keys_value($this->data,"$table.create_time")),'onfocus'=>"WdatePicker({dateFmt:'yyyy-MM-dd HH:00:00'});", 'class'=>"input in-text wdate",'readonly'=>true,'label'=>false ,'div'=>false,'type'=>'text' ,'disabled' => $is_update))?>(GMT+00)</td>
						<td></td> 
					</tr> 			
					<tr id="is_commit_tr" <?php echo $this->data[$table]['is_private'] ? '' : 'style="display:none"' ?>> 
						<td class="tdfond"style="font-size:1.0em;"><?php echo __('commit',true);?>:</td> 
						<td><?php echo $form->input("$table.is_commit",array('label'=>false,'div'=>false ,'legend' => false,'type'=>'radio','options' => array(true=>"Yes",false=>"No"),'disabled' => $is_update))?></td> 
						<td></td>
					</tr>
					<tr id="commit_minutes_tr" <?php echo $this->data[$table]['is_private'] && $this->data[$table]['is_commit'] ? '' : 'style="display:none"'?> > 
						<td class="tdfond"style="font-size:1.0em;"><?php echo __('commit minutes',true);?>:</td> 
						<td><?php echo $form->input("$table.commit_minutes",array('disabled' => !$this->data[$table]['is_commit'],'label'=>false,'div'=>false ,'maxlength'=>12,'disabled' => $is_update))?></td> 
						<td></td>
					</tr>	
					<tr id="expire_time_tr" <?php echo $this->data[$table]['is_private'] && $this->data[$table]['is_commit'] ? '' : 'style="display:none"'?>> 
						<td class="tdfond"style="font-size:1.0em;"><?php echo __('Expire Time',true);?>:</td> 
						<td><?php echo $form->input("$table.expire_time",array('value' => $appOrderPlaces->format_wdatepicker_time(array_keys_value($this->data,"$table.expire_time")),'onfocus'=>"WdatePicker({dateFmt:'yyyy-MM-dd HH:00:00'});", 'class'=>"input in-text wdate",'readonly'=>true,'label'=>false ,'div'=>false,'type'=>'text','disabled' => $is_update))?>(GMT+00)</td> 
						<td></td>
					</tr> 
					<tr> 
						<td class="tdfond" style="font-size:1.0em;"><?php echo __('rate',true);?>:</td> 
						<td><?php echo $form->input("$table.rate",array('class'=>'in-text' ,'value' => $appOrderPlaces->format_price(array_keys_value($this->data,"$table.rate")),'label'=>false,'div'=>false , 'maxlength'=>10));?></td> 
						<td></td>
					</tr>
					<tr> 
						<td class="tdfond" style="font-size:1.0em;"><?php echo __('resource',true);?>:</td> 
						<td><?php echo $form->input("$table.resource_id",array('options' => $appOrderPlaces->format_resources_options($resources),'label'=>false,'div'=>false,'type' => "select",'disabled' => $is_update));?></td> 
						<td></td>
					</tr>
					<tr> 
						<td class="tdfond" style="font-size:1.0em;"><?php echo __('country',true);?>:</td> 
						<td><?php echo $form->input("$table.country",array('id'=>'Country','options' => $appOrderPlaces->format_country_options($countries),'label'=>false,'div'=>false,'type' => "select",'disabled' => $is_update));?></td> 
						<td></td>
					</tr>
					<tr> 
						<td class="tdfond"style="font-size:1.0em;">Region:</td> 
						<td id="regions" colspan=2><?php echo $this->element("order_places/regions")?></td> 
					</tr>									 
				</table>
			</fieldset>			
		</td>		
</table>
