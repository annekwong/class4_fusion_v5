    <script type="text/javascript" src="<?php echo $this->webroot?>js/My97DatePicker/WdatePicker.js"></script>
    <script language="JavaScript" type="text/javascript">
    				function rate_search(){
    					var ids = '';
    					var chx = document.getElementById('tab').getElementsByTagName("input");
    					var loop = chx.length;
    					for ( var i = 0; i < loop; i++) {
    						var c = chx[i];
    						if (c.type == "checkbox") {
    							if (c.checked == true && c.value != '') {
        					if (c.value != 'on')
    									ids += c.value + ",";
    								}
    							}
    						}
							if (ids == '') {
								jGrowl_to_notyfy('You have to specify at least one rate table',{theme:'jmsg-alert'});
								return false;
							}

							document.getElementById("ids").value = ids.substring(0,ids.length -1);
							var c = document.getElementById("matchall");
    					document.getElementById("ismatchall").value = c.checked?'true':'false';
    					loading();	
        				}
    </script>
<div id="title">
	<h1><?php __('Tools')?>&gt;&gt;<?php __('RatesAnalysis')?></h1>
</div>
<div class="container">
<?php if (isset($CodeList)) {?>
<?php echo $this->element("xpage")?>
		<?php if(array_keys_value($this->data,'report_type')==2){?>
				<?php echo $this->element("rateanalyzes/lcr_list")?>
		<?php }else{?>
				<?php echo $this->element("rateanalyzes/comparison")?>
		<?php }?>
<?php echo $this->element("xpage")?>
<?php } else if(!empty($search)) {?>
	<div class="msg"><?php echo __('nodata')?></div>
<?php }?>
<fieldset class="query-box"><legend><?php echo __('rateanalyze')?></legend>
<form method="post" onsubmit="return rate_search();">
<input type="hidden" id="ids" name="ids"/>
<input type="hidden" id="search" name="search" value="true"/>
<input type="hidden" id="ismatchall" name="ismatchall"/>
	<table class="form" style="width: 1000px;">
		<col style="width: 120px;">
		<col style="width: 240px;">
		<col style="width: 70px;">
		<col style="width: 210px;">
		<col style="width: 70px;">
		<col style="width: 130px;">
		<col style="width: 140px;">
		<tbody id="rate">
		<tr>
				<td class="label"><?php echo __('rate table')?>:</td>
				<td class="value"><?php echo $form->input('rate_table_id',Array('div'=>false,'label'=>false,'empty'=>'','options'=>$app->_get_select_options($RateList,'Rate','rate_table_id','name')))?></td>
				<td class="label"><?php echo __("Code Deck")?></td>
				<td class="value"><?php echo $form->input('code_deck_id',Array('div'=>false,'label'=>false,'empty'=>'','options'=>$app->_get_select_options($CodedeckList,'Codedeck','code_deck_id','name')))?></td>
				<td class="label"><?php echo __('Actual_on_date')?></td>
				<td class="value">
						<?php echo $form->input('actual_on_start_date',Array('div'=>false,'label'=>false,'style'=>'width:55px','onfocus'=>"WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})",'readOnly'=>'readOnly','class'=>'wdate'))?>
						-
						<?php echo $form->input('actual_on_end_date',Array('div'=>false,'label'=>false,'style'=>'width:55px','onfocus'=>"WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})",'readOnly'=>'readOnly','class'=>'wdate'))?>
				</td>
				<td class="value"><input style="width:60px;" type="submit" value="<?php echo __('submit')?>" class="input in-submit"/></td>
		</tr>
		<tr>
				<td class="label"><?php echo __('Currency')?>:</td>
				<td class="value"><?php echo $form->input('currency_id',Array('div'=>false,'label'=>false,'empty'=>'','options'=>$app->_get_select_options($CurrencyList,'Currency','currency_id','code')))?></td>
				<td class="label"><?php echo __("Get Margin For")?></td>
				<td class="value"><?php echo $form->input('Get_margin_for',Array('div'=>false,'label'=>false,'empty'=>'','options'=>$app->_get_select_options($ResourceList,'Resource','resource_id','alias')))?></td>
				<td class="label"><?php echo __('Report Type')?></td>
				<td class="value"><?php echo $form->input('report_type',Array('div'=>false,'label'=>false,'style'=>'width:130px','options'=>Array('1'=>'LCR Table','2'=>'Rate Comparison')))?></td>
				<td class="value"></td>
		</tr>
		<tr id="select_code">
				<td class="label"><?php echo __('Country')?>:</td>
				<td class="value"><?php echo $form->input('country',Array('div'=>false,'label'=>false,'options'=>Array()))?></td>
				<td class="label"><?php echo __("Code Name")?></td>
				<td class="value"><?php echo $form->input('code_name',Array('div'=>false,'label'=>false,'options'=>Array()))?></td>
				<td class="label"><?php echo __('Code')?></td>
				<td class="value"><?php echo $form->input('code',Array('div'=>false,'label'=>false,'style'=>'width:130px','options'=>Array()))?></td>
				<td class="value"></td>
		</tr>
		</tbody>
		</table>
</form>
</fieldset>
<script type="text/javascript">
jQuery('#code_deck_id').change(function(){
		if(jQuery(this).val()==''){
			jQuery('#select_code').hide();
		}else{
			var code_deck_id=jQuery(this).val();
			var data=jQuery.ajaxData("<?php echo $this->webroot?>codedecks/find_code?code_deck_id="+code_deck_id);
			var arr_country_temp={};
			var arr_code_name_temp={};
			data=eval(data);
			jQuery('<option/>').val('').html('').appendTo(jQuery('#code').html(''));
			jQuery('<option/>').val('').html('').appendTo(jQuery('#code_name').html(''));
			jQuery('<option/>').val('').html('').appendTo(jQuery('#country').html(''));
			for(var i in data){
				var obj=data[i];
				if(!arr_country_temp[obj.Code.country]){
					jQuery('<option/>').html(obj.Code.country).appendTo('#country');
					arr_country_temp[obj.Code.country]=true;
				}
				if(!arr_code_name_temp[obj.Code.name]){
					jQuery('<option/>').html(obj.Code.name).appendTo('#code_name');
					arr_code_name_temp[obj.Code.name]=true;
				}
				jQuery('<option/>').val(obj.Code.code_id).html(obj.Code.code).appendTo('#code');
			}
			jQuery('#select_code').show();
		}
}).change();
jQuery('#country').change(function(){
	var data=jQuery.ajaxData("<?php echo $this->webroot?>codedecks/find_code?country="+jQuery(this).val()+"&code_deck_id="+jQuery('#code_deck_id').val());
	data=eval(data);
	jQuery('<option/>').val('').html('').appendTo(jQuery('#code').html(''));
	jQuery('<option/>').val('').html('').appendTo(jQuery('#code_name').html(''));
	arr_code_name_temp={};
	for(var i in data){
		var obj=data[i];
		if(!arr_code_name_temp[obj.Code.name]){
			jQuery('<option/>').html(obj.Code.name).appendTo('#code_name');
			arr_code_name_temp[obj.Code.name]=true;
		}
		jQuery('<option/>').val(obj.Code.code_id).html(obj.Code.code).appendTo('#code');
	}
});
jQuery('#code_name').change(function(){
	var data=jQuery.ajaxData("<?php echo $this->webroot?>codedecks/find_code?country="+jQuery('#country').val()+"&code_deck_id="+jQuery('#code_deck_id').val()+'&code_name='+jQuery('#code_name').val());
	jQuery('<option/>').val('').html('').appendTo(jQuery('#code').html(''));
	data=eval(data);
	for(var i in data){
		var obj=data[i];
		jQuery('<option/>').val(obj.Code.code_id).html(obj.Code.code).appendTo('#code');
	}
});
</script>
</div>