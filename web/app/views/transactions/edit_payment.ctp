<div id="title">
	<h1><?php  __('Finance')?>&gt;&gt;<?php echo __('Editing transaction',true);?></h1>
	<ul id="title-menu">
		<li>
			<a class="link_back" href="#" onclick="history.go(-1)">
				<img width="16" height="16" src="<?php echo $this->webroot?>images/icon_back_white.png" alt="">
				&nbsp;<?php echo __('goback',true);?>
			</a>
		</li>
	</ul>
</div>
<div class="container">
	<?php $urls=split('/',$this->params['url']['url'],2)?>
	<?php echo $form->create ('Transaction', array ('action' =>$urls[1]));?>
	<table class="form">
		<tbody>
			<tr>
	    		<td class="label label2"><?php echo __('Date',true);?>:</td>
	    		<td class="value value2 in-date">
						
									<?php  
										echo $form->input('payment_time',
	 									array('onfocus'=>"WdatePicker({dateFmt:'yyyy-MM-dd'});", 
	 									'style'=>'width:210px;height:20px;',
	 									'class'=>"input in-text wdate",'readonly'=>true,'label'=>false ,'div'=>false,'type'=>'text'));?>
	   							
	    		</td>
			</tr>
			<tr>
			    <td class="label label2"><?php  __('Carriers')?></td>
			    <td class="value value2">
			        <?php echo $form->input('client_id',Array('label'=>false,'style'=>'width:210px;','div'=>false,'options'=>$appTransactions->_get_select_options($ClientList,'Client','client_id','name')))?>
			    </td>
			</tr>
<tr>
    <td class="label label2"><?php echo __('Sum',true);?>:</td>
    <td class="value value2">
     <?php echo $form->input('amount',array('id'=>'sum','label'=>false,'div'=>false,'style'=>'width:210px;height: 20px;','type'=>'text'))?>
		<div style=" margin-right: 10px; margin-top: 4px;">
			<?php
				empty($post[0][0]['approved'])?$au='false':$au='checked';
				echo $form->checkbox('approved',array('checked'=>$au))
			?>
           <label for="status"><?php echo __('Approved',true);?></label>
		</div>
    </td>
</tr>
<tr>
    <td class="label label2"></td>
    <td class="value value2">
    	<small class="note">
    		<?php __("Positive sum is added to client's balance")?>,<br><?php __("negative - withdrawn from client's balance")?>
    	</small>
    </td>
</tr>
<tr>
    <td class="label label2"><?php echo __('Comments',true);?>:</td>
    <td class="value value2">
    	<?php echo $form->input('description',Array('div'=>false,'label'=>false,'style'=>'width:210px'))?>
   </td>
</tr>
</tbody></table>
<?php  if ($_SESSION['role_menu']['Management']['transactions']['model_w']) {?>
<div id="form_footer">
        <input type="submit" value="<?php echo __('submit',true);?>" class="input in-submit">
    <input type="button" value="Cancel" onclick="winClose();" class="input in-button">
    
    </div>
    <?php }?>
	<?php echo $form->end();?>
</div>
<script type="text/javascript">
//<![CDATA[
var _ss_ids_client = {'id_clients': 'query-id_clients', 'id_clients_name': 'query-id_clients_name', 'account': 'query-account', 'tz': 'query-tz', 'id_currencies': 'query-id_currencies'};


function showClients ()
{
    ss_ids_custom['client'] = _ss_ids_client;
    winOpen('<?php echo $this->webroot?>clients/ss_client?types=2&type=0', 500, 530);

}



//]]>
</script>