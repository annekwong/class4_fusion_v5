<?php $w = $session->read('writable');?>
<div id="cover"></div>
	<div id="cover_tmp"></div>
<div id="title">
 		<h1><?php  __('Finance')?>&gt;&gt;
		<?php __('Invoices')?></h1> 
     <ul id="title-search">
        <li>
		        <form   id="like_form"  action=""  method="get">
		       		 <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch')?>" value="<?php if (!empty($search)) echo $search;?>" name="search">
		        </form>
        </li>
        <li title="advanced search »" onclick="advSearchToggle();" id="title-search-adv" style="display: list-item;" class=" "></li>
    	 </ul>
     <ul id="title-menu">
     <?php if ($w == true) {if($create_type=='1'){?>
        <li>
        		<a class="link_btn" href="<?php echo $this->webroot?>invoices/add">
        			<img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"><?php __('createnew')?>
        		</a>
        </li>
        		<?php }}?>
     </ul>
</div>
<div id="container">
<fieldset style="margin-left: 1px; width: 100%; display: none;" id="advsearch" class="title-block">
	<form method="get" action="">
		<input type="hidden" name="advsearch" class="input in-hidden">
		<table style="width:100%">
			<tbody>
				<tr>
 					<td>
 						<label><?php echo __('Invoice No',true);?>:</label>
						<input type="text" class="input in-text" name="invoice_number" value="" style="width: 60px;" id="invoice_number">
    			</td>
     		<td><label><?php echo __('type',true);?>:</label>
 						<select id="type" onchange="updateDirection()" name="type" class="input in-select">
   					 <option value="0"><?php __('Outgoing invoice')?></option>
   					 <option value="1"><?php __('Incoming invoice')?></option>
   				</select>
    			</td>
    			<td>
    				<label><?php echo __('Invoice Date',true);?>:</label>
						<input type="text" class="input in-text wdate" name="invoice_start" value="" style="width:60px;" id="start_date" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" readonly="">
    					--
    			 <input type="text" class="wdate input in-text" name="invoice_end" value="" style="width: 60px;" id="end_date" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" readonly="">
    			</td>
		   
	     <td>
	    		<label><?php echo __('status',true);?></label>
	    		<select id="state" name="state" class="input in-select">
				    <option value="0"><?php __('normal')?></option>
				    <option value="1"><?php __('to send')?></option>
				    <option value="2"><?php __('to verify')?></option>
				    <option value="3"><?php __('send failure')?></option>
				    <option value="9"><?php __('sended')?></option>
				    <option selected="selected" value=""><?php __('All')?></option>
				  </select>
	     </td>
	     <td>
	    		<label><?php echo __('Amt Paid',true);?></label>
	    		<select id="paid" name="paid" class="input in-select">
				    <option selected="selected" value="0"><?php __('All')?></option>
				    <option value="false"><?php __('no paid')?></option>
				    <option value="true"><?php __('already paid')?></option>
				  </select>
	    </td>
     	<td class="buttons"><input type="submit" class="input in-submit" value="<?php echo __('submit',true);?>"></td>
			</tr>
			
						<tr align="left">
						<td colspan="5"><table ><TR>
			   <td align="right">
	    		<label><?php echo __('Dispute',true);?></label></td><td>
	    		<select id="disputed" name="disputed" class="input in-select">
				    <option selected="selected" value="0"><?php __('Non-Disputed')?></option>
				    <option value="1"><?php __('Disputed')?></option>
				    <option value="2"><?php __('Dispute Resolved')?></option>
				  </select>
	     </td>
	     
	     
	     <td style="text-align: right;">
 						<label><?php echo __('Number of Day Overdue',true);?>:</label>
 						</td><td style="text-align: left;">
 						<select id="due_inteval_type" name="due_inteval_type" class="input in-select">
				    <option value=">=">&gt;=</option>
				    <option value="<=">&lt;= </option>
				    <option selected="selected" value=""><?php __('All')?></option>
				    
				  </select>
						<input type="text" class="input in-text" name="due_inteval" value="" style="width: 60px;" id="due_inteval">
    			</td>
    			<td id="client_cell">
    			   <label>&nbsp; <?php echo __('Carriers',true);?> </label> 
    				<input class="input in-hidden" name="query[id_clients]" value="" id="query-id_clients" type="hidden">
       		<input type="text" id="query-id_clients_name" onclick="showClients()" readonly="1" value="" name="query[id_clients_name]" class="input in-text">        
        	<img width="9" height="9" onclick="showClients()" class="img-button" src="<?php echo $this->webroot?>images/search-small.png">
        	<img width="9" height="9" onclick="ss_clear('client', _ss_ids_client)" class="img-button" src="<?php echo $this->webroot?>images/delete-small.png">
    		 </td>
    	   </TR>
    		 </table>
    		 
    		 <td></td>
 			</tr>
		</tbody>
	</table>
	</form>
</fieldset>
<?php if($_SESSION['login_type']=='1'){?>
	<ul class="tabs">
	 <li <?php if($create_type=='0'){echo "class='active'";}?> ><a href="<?php echo $this->webroot?>invoices/view/0"><img width="16" height="16" src="<?php echo $this->webroot?>images/bDR.gif"><?php echo __('Auto-generated Invoice')?></a></li>   
	 <li <?php if($create_type=='1'){echo "class='active'";}?> ><a href="<?php echo $this->webroot?>invoices/view/1"><img width="16" height="16" src="<?php echo $this->webroot?>images/menuIcon.gif"> <?php echo __('Manual Invoice')?></a></li> 
	</ul>
<?php }?>
<?php $d = $p->getDataArray();if (count($d) == 0) {?>
	<div class="msg"><?php echo __('no_data_found')?></div>
<?php } else {?>
	<div id="toppage"></div>

<form     id='download_invoice_form' method="post" action="<?php echo $this->webroot?>invoices/mass_update/<? echo $create_type;?>">
<table class="list">
	<col style="width: 4%;">
	<col style="width: 16%;">
	<col style="width: 4%;">
	<col style="width: 4%;">
	<col style="width: 6%;">
	<col style="width: 10%;">
		<col style="width: 10%;">
	<col style="width: 16%;">
	<col style="width: 11%;">
	<col style="width: 4%;">
	<col style="width: 7%;">
	<col style="width: 4%;">
	<?php if ($_SESSION['login_type']==1) {?>
	<col style="width: 4%;">
	<col style="width: 4%;">
	<?php }?>
<thead>
    <tr>
        <td rowspan="2"><input type="checkbox" id="selector-1" onchange="switchSelection();" value="1" name="selector" class="input in-checkbox"></td>
        <td> <?php echo $appCommon->show_order('invoice_number',__('InvoiceNo',true))?></td>
        <td rowspan="2">&nbsp;</td>
        <td rowspan="2"> <?php echo $appCommon->show_order('paid',__('Status',true))?>   </td>
        <td rowspan="2"> <?php echo $appCommon->show_order('type',__('Type',true))?>   </td>
        <td rowspan="2"> <?php echo $appCommon->show_order('client',__('Carriers',true))?>   </td>
         <td rowspan="2"> <?php echo $appCommon->show_order('disputed',__('Disputed',true))?>   </td>
        <td rowspan="2"> <?php echo $appCommon->show_order('invoice_start',__('Period',true))?>   </td>
        <td rowspan="2"> <?php echo $appCommon->show_order('total_amount',__('Amt Gross',true))?></td>
   
        <td rowspan="2">&nbsp;<?php echo __('Amt Paid')?></td>
        <td rowspan="2"> <?php echo $appCommon->show_order('due_date',__('Due Date',true))?></td>
        <td rowspan="2">&nbsp;</td>
         <?php if ($_SESSION['login_type']==1) {?>
        <td rowspan="2" class="last">&nbsp;</td><?php }?>
    </tr>
    <tr>
        <td class="last">
        <?php echo $appCommon->show_order('invoice_time',__('Invoice Date',true))?>
        </td>
    </tr>
</thead>
<tbody>
	<?php
		$mydata = $p->getDataArray();
		$loop = count($mydata);
		for ($i=0;$i<$loop;$i++) { 
	?>
		<tr class="row-1">
    <td align="center">
	    <input type="checkbox" 
	    value="<?php echo $mydata[$i][0]['invoice_id']?>"
	    id="ids-<?php echo $mydata[$i][0]['invoice_id']?>" name="ids[]" class="input in-checkbox">
    </td>
    <td rel="tooltip" id="ci_<?php echo $i?>"><a  href="<?php echo $this->webroot?>invoices/edit/<?php echo  $mydata[$i][0]['invoice_id']?>" ><b><?php echo $mydata[$i][0]['invoice_number']?></b></a><br><small title=""><?php echo $mydata[$i][0]['invoice_time']?></small></td>
    <td>
    
    <a title="<?php echo __('download')?>" target="_blank" href="<?php echo $this->webroot?>invoices/createpdf_invoice/<?php echo $mydata[$i][0]['invoice_number']?>">
    	<img width="16" height="16" src="<?php echo $this->webroot?>images/download.png">
    </a>
    </td>
    <td align="center">&nbsp;<?php $state = $mydata[$i][0]['state'];
    			if ($state==0)echo __('normal');
    			else if ($state==1) echo 'to send state';
    			else if ($state==2) echo 'to verify state';
    				else if ($state==3) echo 'sent failure';
    				else if ($state == 9) echo 'sended';?></td>
    <td align="center"><img width="16" height="14" src="<?php echo $this->webroot?><?php echo $mydata[$i][0]['type']==0?'images/t-out.gif':'images/t-in.gif'?>"> <?php echo $mydata[$i][0]['type']==0?"out":"in"?></td>    
    <td style="text-align:left;">
    			<?php if (empty($mydata[$i][0]['res'])) {?>
    				<img width="16" height="16" title="Client" src="<?php echo $this->webroot?>images/menuIcon.gif"> <?php echo $mydata[$i][0]['client']?> </td>
    			<?php } else {
    									echo $mydata[$i][0]['res'];
    								}
    				?>
    				
    				
    				
    				    <td align="center">&nbsp;<?php $state = $mydata[$i][0]['disputed'];
    			if ($state==0)echo __('Non-Disputed');
    			else if ($state==1) echo 'Disputed';
    			else if ($state==2) echo 'Dispute Resolved';?></td>
    <td align="center">
            <small>
            <?php echo $mydata[$i][0]['invoice_start']?><br>
            <?php echo $mydata[$i][0]['invoice_end']?>            </small>
    </td>
    <td align="right">
        <strong><?php echo round($mydata[$i][0]['total'],4)?></strong>
        <br><small title="Paid" class="zero"><?php echo __('Credit',true);?>: <?php echo empty($mydata[$i][0]['client_credit'])?'0':$mydata[$i][0]['client_credit'];?></small>
        <br><small title="Paid" class="zero"><?php __('Paid')?>: <?php echo empty($mydata[$i][0]['invoice_payment'])?'0':$mydata[$i][0]['invoice_payment'];?></small>  
        <br><small title="Paid" class="zero"><?php __('Owe')?>: 
        <?php echo empty($mydata[$i][0]['invoice_payment'])?$mydata[$i][0]['total_amount']:number_format($mydata[$i][0]['total_amount']-$mydata[$i][0]['invoice_payment'],5,'.',',');?>
        </small> 
        <br><small title="Paid" class="zero"><?php __('Balance')?>: <?php echo empty($mydata[$i][0]['balance']) ? 0 : $mydata[$i][0]['balance'];?></small>            
    </td>
    <td align="center">	
    		<?php if ($mydata[$i][0]['paid'] == false) {?>
    				<img width="16" height="16" title="Invoice is not paid" src="<?php echo $this->webroot?>images/status_notclosed.gif">
    		<?php } else {?>
    				<img width="16" height="16" title="Invoice is not paid" src="<?php echo $this->webroot?>images/status_closed.gif">
    		<?php }?>
    </td>
    <td align="center">
       <span class="warn">
        <?php 
        	if($mydata[$i][0]['due_inteval']<0){
        	 echo abs($mydata[$i][0]['due_inteval']);
        			 ?>
        days ago
        <?php }?>
  			 </span>
    		 <br><small><?php echo $mydata[$i][0]['due_date']?></small>
	   </td>
    <td>
    	 <?php  if(!empty($mydata[$i][0]['invoice_show_details'])) {?>
       <a title="Download CDR file" href="<?php echo $this->webroot?>invoices/download_cdr/<?php echo $mydata[$i][0]['invoice_id']?>">
   		 <img width="16" height="16" src="<?php echo $this->webroot?>images/attached_cdr.gif"></a>
      <?php }?>
     </td>
  		<?php if ($_SESSION['login_type']==1) {?>
  	   <td class="last">
    		 <a  href="<?php echo $this->webroot?>clientpayments/add_payment/<?php echo $mydata[$i][0]['client_id']?>?invoice_no=<?php echo $mydata[$i][0]['invoice_number'];?>">
		   		<img width="16" height="16" src="<?php echo $this->webroot?>images/balanceOperations.gif">
		    </a>
      </td>
  		<?php }?>
	 </tr>
	 <tr style="display:none"><td>
		<dl id="ci_<?php echo $i?>-tooltip" class="tooltip">
    <dd><b><?php echo __('State',true);?>:</b></dd>
    <dd><?php $state = $mydata[$i][0]['state'];
    			if ($state==0)echo 'normal';
    			else if ($state==1) echo 'send';
    			else if ($state==2) echo 'vertify';
    			else if ($state==3) echo 'sent failure';
    			else if ($state==9) echo 'sended';?></dd>
    <dt><b><?php echo __('Total',true);?>:</b></dt>
    <dd>
                <?php echo $mydata[$i][0]['total_amount']?> <?php echo __('RMB',true);?>    </dd>
    <dt><b><?php echo __('Paid',true);?>:</b></dt>
    <dd> <?php echo empty($mydata[$i][0]['paid'])?'0.000':$mydata[$i][0]['paid']?>  <?php echo __('RMB',true);?></dd>
    <dt><b><?php echo __('Period',true);?>:</b></dt>
    <dd>
        <?php echo $mydata[$i][0]['invoice_start']?><br>
        <?php echo $mydata[$i][0]['invoice_end']?>    </dd>
		</dl>
		</td></tr>
	<?php } ?>
</tbody>
</table>
 <div id="tmppage">
    <?php echo $this->element('page')?>
    </div>
<?php if ($w == true) {?>
<div style="margin-bottom: 10px;">
<?php echo __('action',true);?>: <select id="action" name="action" class="input in-select"  style="width: auto">
<option value=""></option>
<option value="0"><?php __('set to normal state')?></option>
<option value="1"><?php __('set to send state')?></option>
<option value="2"><?php __('set to verify state')?></option>
<option value="3"><?php __('delete invoices')?></option>
<option value="4"><?php __('Non-Disputed')?></option>
<option value="5"><?php __('Disputed')?></option>
<option value="6"><?php __('Dispute Resolved')?></option>
</select>
 <input type="submit" value="<?php echo __('Update',true);?>" class="input in-submit"></div>
<?php }?>
</form>

    <!-- DYNAREA -->
   
    <?php }?>
</div>
<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>
	<script language="JavaScript" type="text/javascript">
	
			var _ss_ids_client = {'id_clients': 'query-id_clients', 'id_clients_name': 'query-id_clients_name', 'account': 'query-account', 'tz': 'query-tz', 'id_currencies': 'query-id_currencies'};
			function showClients ()
			{
			    ss_ids_custom['client'] = _ss_ids_client;
			    winOpen('<?php echo $this->webroot?>clients/ss_client?types=2&type=0', 500, 530);
			
			}
			function switchSelection()
			{
			    var t = $('#container .list thead :checkbox');
			    t.closest('table').find('tbody :checkbox')
			     .attr('checked', t.attr('checked'));
			}


	</script>
<?php echo  $appCommon->show_search_value();?>