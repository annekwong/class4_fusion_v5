<!--导入所有reoprt页面的input和select样式文件-->
<?php echo $this->element('magic_css_three');?>
<div id="title">
    <h1><?php  __('Finance')?>&gt;&gt;<?php __('Transaction')?></h1>
		<ul id="title-search">
		  <?php //********************模糊搜索**************************?>
		   	<form   id="like_form"  action=""  method="get">
            <li>
		        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch')?>" value="<?php if (!empty($search)) echo $search;?>" name="search" style="width:200px;">
		    
		  </li>
          <input type="submit" name="submit" value="" class="search_submit"/>
          </form>
		 	 <!--
             <li title="<?php echo __('advancedsearch')?> »" onclick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>
             -->
		</ul>
		<ul id="title-menu">
		<?php if (isset($extraSearch)) {?>
		  <li>
		    <a class="link_back" href="<?php echo $extraSearch?>" onclick="history.go(-1)">
		    <img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
		    		&nbsp;<?php echo __('goback')?>
		    </a>
		  </li>
		<?php }?><!--
			 <li>
		    	<a href="<?php echo $this->webroot?>resclis/make_payment/transaction" >
		    			<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/add.png">&nbsp;<?php __('createnew')?>
		    	</a>
		   </li>
		    <li>
	       		<a class="list-export" href="<?php echo $this->webroot?>transactions/client_tran_download"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/export.png"><?php  echo __('Download')?></a>
	       	</li>-->
		</ul>
<?php  
	$action=isset($_SESSION['sst_statis_smslog'])?$_SESSION['sst_statis_smslog']:'';
 $w=isset($action['writable'])?$action['writable']:'';
 ?>
</div>
<div id="container">

<?php $d = $p->getDataArray();if (count($d) == 0) {?>
	<div class="msg"><?php echo __('no_data_found')?></div>
<?php } else {?>
	<div id="toppage"></div>
		<table class="list">
			<thead>
				<tr>
    			<td style="width:13%">	<?php echo $appCommon->show_order('time',__('Transaction Date',true))?>  </td>
    				<td style="width:13%">	<?php echo $appCommon->show_order('client_name',__('Carriers',true))?>  </td>
    				<td style="width:13%">	<?php echo $appCommon->show_order('client_cost',__('Sum',true))?>  </td>
    				<td style="width:13%">	<?php echo $appCommon->show_order('tran_type',__('Type',true))?>  </td>
						<!--<td style="width:13%"><?php __("EnteredBy")?></td>-->
						<td style="width:13%"><?php echo $appCommon->show_order('time',__("EnteredDate",true))?> </td>
						<td style="width:13%;">	<?php echo $appCommon->show_order('current_balance',__('Balance',true))?>  </td>
   				<?php  if ($_SESSION['role_menu']['Finance']['transactions']['model_w']) {?>
                <td style="text-align:center; width:13%;"><?php __('action')?></td>
                <?php }?>
				</tr>
			</thead>
			<tbody id="tran_tab">
	<?php 
					$mydata =$p->getDataArray();
					$loop = count($mydata); 

					for ($i=0;$i<$loop;$i++) {?>
		<tr id="i_<?php echo $loop-$i?>"   >
    	<td align="center"><?php echo $mydata[$i][0]['time']?></td>
     <td align="center"><?php echo $mydata[$i][0]['client_name']?></td>
     <td align="center">
	     <?php 
	       $cost=number_format($mydata[$i][0]['client_cost'], 3);
						  echo $cost;
			     ?>
			</td>
     <td align="center">
					<?php
					 	if( $mydata[$i][0]['tran_type']==1){echo "<b  style='color:blue'>".__('payment',true)."</b>";} 
		 					if( $mydata[$i][0]['tran_type']==2){echo "<b  style='color:green'>".__('credit',true)."</b>";}
          if( $mydata[$i][0]['tran_type']==4){echo "<b  style='color:gray'>".__('Debit',true)."</b>";} 
          if( $mydata[$i][0]['tran_type']==5){echo "<b  style='color:red'>".__('Invoice',true)."</b>";} 
  					?>
  			</td>
  			<!--<td>
  					<?php echo $appTransactions->payment_method($mydata[$i][0]['payment_method'],$user)?>
  			</td>
  			--><td><?php echo date('Y-m-d h:i:s',strtotime($mydata[$i][0]['time']))?></td>
      <td style="text-align:center;">
					<?php 
							if(empty($mydata[$i][0]['current_balance'])){echo '0.000';}else{
							 $cost=number_format($mydata[$i][0]['current_balance'], 3);
					 	 		echo $cost;
							}
					?>
      </td>
     <?php  if ($_SESSION['role_menu']['Finance']['transactions']['model_w']) {?>
      <td>
       	<?php  if($mydata[$i][0]['approved'] == false) {?>
       		<a href="<?php echo $this->webroot?>transactions/approved_client_tran_view/3/<?php echo $mydata[$i][0]['client_payment_id']?>/<?php echo $mydata[$i][0]['client_id'];?>"; title="<?php echo __('approved')?>" >
       			<img src="<?php echo $this->webroot?>images/approve.gif"/>
       		</a>
       	<?php }else{?>
       		<a style="margin-right:10px"> 
       			<img src="#"  style="display:none"/>
       		</a>
       	<?php }	if( $mydata[$i][0]['tran_type']==1||$mydata[$i][0]['tran_type']==2){?>
       	
       	
        	<a href="<?php echo $this->webroot?>transactions/edit_payment/<?php echo $mydata[$i][0]['client_payment_id']?>"; 
        		title="<?php echo __('edit')?>">
       			<i class="icon-edit"></i>
       		</a>
       		<?php }?>
       </td>
       <?php }?>
</tr>
<tr style="display:none;"  >
	<td>
		<dl id="i_<?php echo $loop-$i?>-tooltip" class=".">
		    <dd>
				    <?php __('trans_type')?>:
				    	<?php
											if( $mydata[$i][0]['tran_type']==2){echo "<b  style='color:green'>".__('Paymentmanual',true)."</b>";}
				          if( $mydata[$i][0]['tran_type']==3){echo "<b  style='color:red'>".__('Chargecalls',true)."</b>";} 
				     		?>
		     </dd>
		    <dd></dd>
		    <dd><div class="note">.</div></dd>
		    <dt><?php echo __('note')?>:</dt>
		    <dd></dd>
		    <dt></dt>
		    <dd><?php echo $_SESSION['sst_user_name']?> (<em>#5)</em></dd>
		</dl>
	</td>
</tr>
<?php }?>
</tbody></table>
<div id="tmppage">
<?php echo $this->element('page');?>
</div>
<?php }?>
<?php //*********************  条件********************************?>
  <fieldset class="query-box">

<div class="search_title"><img src="<?php
echo $this->webroot?>images/search_title_icon.png" />
    <?php __('search')?>
  </div>

<script type="text/javascript">

//设置每个字段所对应的隐藏域
var _ss_ids_client = {'id_clients': 'query-id_clients', 'id_clients_name': 'query-id_clients_name', 'account': 'query-account', 'tz': 'query-tz', 'id_currencies': 'query-id_currencies'};
var _ss_ids_reseller = {'id_resellers': 'query-id_resellers', 'id_resellers_name': 'query-id_resellers_name', 'account': 'query-account', 'tz': 'query-tz', 'id_currencies': 'query-id_currencies'};

</script>
	<form action="" method="get">
	<input name="advsearch" type="hidden"/>
<table class="form">
	<tbody>
		<tr class="period-block">
    <td class="label"><?php __('time')?>:</td>
    <td colspan="5" class="value">
    <table class="in-date"><tbody>
    	<tr>
    		<td>
    			<table class="in-date">
						<tbody>
							<tr>
								<td style="padding-right: 15px;">
 								<?php
										$r=array(
												'custom'=>__('custom',true),
												'curDay'=>__('today',true),
												'prevDay'=>__('yesterday',true),
												'curWeek'=>__('currentweek',true),
												'prevWeek'=>__('previousweek',true),
												'curMonth'=>__('currentmonth',true),
												'prevMonth'=>__('previousmonth',true),
												'curYear'=>__('currentyear',true),
												'prevYear'=>__('previousyear',true)
										); 	
										if(!empty($_POST)){
											if(isset($_POST['smartPeriod'])){
												$s=$_POST['smartPeriod'];
											}else{
												$s='curDay';
											}
										}else{
											$s='curDay';
										}
										echo $form->input('smartPeriod',
											array('options'=>$r,'label'=>false ,
										 	'onchange'=>'setPeriod(this.value)','id'=>'query-smartPeriod','style'=>'width: 130px;','name'=>'smartPeriod',
										 	'div'=>false,'type'=>'select','selected'=>$s));
								?>
								</td>
						    <td>
						    		<input type="text" id="query-start_date-wDt" class="wdate in-text input" onchange="setPeriod('custom')"   readonly="readonly"  onkeydown="setPeriod('custom')" value="" name="start_date"  style="margin-left: 0px; width: 120px;">
						    </td>
						    <td></td>
								</tr>
							</tbody>
						</table>
    			</td>
    			<td>
    				<input type="text" id="query-start_time-wDt" onchange="setPeriod('custom')" onkeydown="setPeriod('custom')" readonly="readonly" style="width: 60px;" value="00:00:00" name="start_time" class="input in-text">
		    		<script type="text/javascript">
		    					jQuery(document).ready(function(){
												jQuery('input[name=start_date]').val('<?php echo array_keys_value($this->params,'url.start_date',date('Y-m-d'))?>');
		        					});
		    		</script>
    			</td>
    			<td>&mdash;</td>
    			<td>
    				<table class="in-date">
							<tbody>
								<tr>
			    				<td>
			    					<input type="text" id="query-stop_date-wDt" class="wdate in-text input"  style="width: 120px;"    onchange="setPeriod('custom')" readonly="readonly" onkeydown="setPeriod('custom')" value="" name="stop_date">
			    				</td>
			    				<td></td>
								</tr>
							</tbody>
						</table>
    			</td>
    			<td>
    					<input type="text" id="query-stop_time-wDt" onchange="setPeriod('custom')"
    						readonly="readonly" 
     					onkeydown="setPeriod('custom')" style="width: 60px;" value="23:59:59" name="stop_time" class="input in-text">
     		</td>
     		<td style="padding: 0pt 10px;">in</td>
     		<td><select id="query-tz" style="width: 100px;" name="query[tz]" class="input in-select">
     <option value="-1200">GMT -12:00</option>
     <option value="-1100">GMT -11:00</option>
     <option value="-1000">GMT -10:00</option>
     <option value="-0900">GMT -09:00</option>
     <option value="-0800">GMT -08:00</option>
     <option value="-0700">GMT -07:00</option>
     <option value="-0600">GMT -06:00</option>
     <option value="-0500">GMT -05:00</option>
     <option value="-0400">GMT -04:00</option>
     <option value="-0300">GMT -03:00</option>
     <option value="-0200">GMT -02:00</option>
     <option value="-0100">GMT -01:00</option>
     <option value="+0000">GMT +00:00</option>
     <option value="+0100">GMT +01:00</option>
     <option selected="selected" value="+0200">GMT +02:00</option>
     <option value="+0300">GMT +03:00</option>
     <option value="+0330">GMT +03:30</option><option value="+0400">GMT +04:00</option><option value="+0500">GMT +05:00</option><option value="+0600">GMT +06:00</option><option value="+0700">GMT +07:00</option><option value="+0800">GMT +08:00</option><option value="+0900">GMT +09:00</option><option value="+1000">GMT +10:00</option><option value="+1100">GMT +11:00</option><option value="+1200">GMT +12:00</option></select></td>
    </tr></tbody></table>
</td>
    <td class="buttons"><input type="submit" value="<?php echo __('Search')?>" class="input in-submit"></td>
</tr>
<tr>

     <td class="label">
		 		<?php echo __('Amount',true);?>:</td>
    <td class="value" >
				<input type="text" id="start_amount" style="width:60px;"  name="start_amount" class="input in-text" value="<?php echo $appCommon->_get('start_amount');?>">
    				--
    		<input type="text" id="end_amount" style="width:60px;" name="end_amount" class="input in-text" value="<?php echo $appCommon->_get('end_amount')?>">
    </td>
    
    
    
    <td class="label"style="display:none; "><?php  __('Carriers')?>:</td>
    <td id="client_cell" class="value"style="display:none; ">
 		<input class="input in-hidden" name="query[id_clients]" value="" id="query-id_clients" type="hidden">
      <input type="text" id="query-id_clients_name" onclick="showClients()" style="width: 53%;" readonly="1" value="" name="query[id_clients_name]" class="input in-text">        
      <img width="9" height="9" onclick="showClients()" class="img-button" src="<?php echo $this->webroot?>images/search-small.png">
      <img width="9" height="9" onclick="ss_clear('client', _ss_ids_client)" class="img-button" src="<?php echo $this->webroot?>images/delete-small.png">
    </td>

    
    
     <td class="label"style="width:100px;"><?php echo __('trans_type')?></td>
    <td class="value">
			<select id="tran_type" name="tran_type" style="width:120px;">
    				<option value=""><?php echo __('select')?></option>
    				
    				<option value="1"><?php __('payment')?></option>
    				<option value="2"><?php __('credit')?></option>
    				<option value="4"><?php __('Debit')?> </option>
    				<option value="5"><?php __('Invoice')?></option>
    		</select>
    </td>

       
    <td style="display:none;"><label><?php echo __('status')?></label></td>
    <td class="value" style="display:none;">
              <select id="tran_status" name="tran_status">
    				<option value=""><?php echo __('select')?></option>
    				<option value="true"><?php echo __('approvedd')?></option>
    				<option value="false"><?php echo __('noapprovedd')?></option>
    		</select></td>
     <td class="label"></td>
    <td class="value" style="width:200px;">
			<?php  __('Carriers')?>
    <input type="text" id="client_name" style="width:100px;" value="<?php if(isset($_GET['client_name'])) echo $_GET['client_name'];?>" name="client_name" class="input in-text">
    </td>
</tr>

</tbody></table>
</form>
</fieldset>


</div>

<div>

</div>
<script type="text/javascript">
var _ss_ids_client = {'id_clients': 'query-id_clients', 'id_clients_name': 'query-id_clients_name', 'account': 'query-account', 'tz': 'query-tz', 'id_currencies': 'query-id_currencies'};
function showClients ()
{
    ss_ids_custom['client'] = _ss_ids_client;
    winOpen('<?php echo $this->webroot?>clients/ss_client?types=2&type=0', 500, 530);
}
</script>
<script type="text/javascript">
/*
 * @params 这里是用来保存搜索条件 滴>-<!!
 */
jQuery(document).ready(
		function (){
			jQuery('#start_amount').val('<?php echo $appCommon->_get('start_amount')?>');
			jQuery('#end_amount').val('<?php echo $appCommon->_get('end_amount')?>');
			jQuery('#start_date').val('<?php echo $appCommon->_get('start_date')?>');
			jQuery('#end_date').val('<?php echo $appCommon->_get('end_date')?>');
			jQuery('#tran_type').val('<?php echo $appCommon->_get('tran_type')?>');
			jQuery('#advsearch form').submit(function(){
				re=true;
				jQuery('#start_amount,#end_amount').map(
					function(){
						if(/\D/.test(jQuery(this).val())){
							jQuery(this).addClass('invalid');
							jGrowl_to_notyfy('must contain numeric characters only',{theme:'jmsg-error'});
								re=false;
						}
					}
				);
				return re;
			});
		}
);
</script>
	