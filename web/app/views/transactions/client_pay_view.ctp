<!--导入所有reoprt页面的input和select样式文件-->
<?php echo $this->element('magic_css_three');?>
<div id="title">
  <h1>
    <?php  __('Finance')?>
    &gt;&gt;
    <?php __('payment')?>
  </h1>
  <form   id="like_form"  action=""  method="get">
    <ul id="title-search">
      <li>
        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText in-input" title="<?php echo __('namesearch')?>" value="<?php if (!empty($search)) echo $search;?>" name="search">
        <input type="submit" name="submit" value="" class="search_submit"/>
      </li>
     <!--
      <li title="<?php echo __('advancedsearch')?> »" onclick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>
      -->
    </ul>
  </form>
  <ul id="title-menu">
    <?php if (isset($extraSearch)) {?>
    <li> <a class="link_back" href="<?php echo $backurl?>" > <img width="16" height="16" alt="<?php echo __('goback',true);?>" src="<?php echo $this->webroot?>images/icon_back_white.png"> &nbsp;<?php echo __('goback',true);?> </a> </li>
    <?php }?>
    <?php  if ($_SESSION['role_menu']['Finance']['resclis']['model_w']) {?>
    <li> <a class="link_btn" id="add" href="<?php echo $this->webroot?>resclis/create_payment" > <img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/add.png">&nbsp;
      <?php __('createnew')?>
      </a> </li>
    <?php }?>
    <!--<li>
	       		<a class="list-export" href="<?php echo $this->webroot?>transactions/client_pay_download"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/export.png"> <?php echo __('download',true);?></a>
	       	</li>
    -->
  </ul>
  <?php  $action=isset($_SESSION['sst_statis_smslog'])?$_SESSION['sst_statis_smslog']:'';
    $w=isset($action['writable'])?$action['writable']:'';?>
</div>
<div id="container">
  <ul class="tabs">
        <li class="active">
            <a href="<?php echo $this->webroot; ?>transaction/client_pay_view">
                <img width="16" height="16" src="/Class4/images/list.png"><?php __('Incoming')?>				
            </a>
        </li>
        <li>
            <a href="/Class4/monitorsreports/productstats">
                 <img width="16" height="16" src="/Class4/images/menuIcon.gif"><?php __('Outgoing')?>			
            </a>  
        </li>
 </ul>
  <?php $d = $p->getDataArray();if (count($d) == 0) {?>
  <div class="msg"><?php echo __('no_data_found')?></div>
  <?php } else {?>
  <?php
	if (!empty($searchForm)) {
		$d = array_keys($searchForm);
		foreach($d as $k) {?>
  <script type="text/javascript">
				if (document.getElementById("<?php echo $k?>")){
					document.getElementById("<?php echo $k?>").value = "<?php echo $searchForm[$k]?>";
				}
			</script>
  <?php 
		}
?>
  <script type="text/javascript">document.getElementById("advsearch").style.display="block";</script>
  <?php }?>
  <?php //*********************查询条件********************************?>
  <div id="toppage"></div>
  <table class="list">
    <!--		<col width=3%">-->
    <col width="9%">
    <col width="12%">
    <col width="8%">
    <col width="8%">
    <?php if($_SESSION['login_type']==1){?>
    <col width="6%">
    <col width="6%">
    <?php }?>
    <thead>
      <tr> 
        <!--			<td><?php echo $appCommon->show_order('client_payment_id','ID')?></td>-->
        <td ><?php echo $appCommon->show_order('payment_time',__('Transaction',true))?></td>
        <td >&nbsp;
          <?php __('Carriers')?>
          &nbsp;</td>
        <td ><?php echo $appCommon->show_order('amount')?></td>
        <td ><?php echo __('type')?></td>
        <?php if($_SESSION['login_type']==1){?>
        <td style="text-align:center;"><?php __('TransactionResult')?></td>
        <td style="text-align:center;"><?php __('action')?></td>
        <?php }?>
      </tr>
    </thead>
    <tbody id="tran_tab">
      <?php $mydata =$p->getDataArray();$loop = count($mydata);for ($i=0;$i<$loop;$i++) {?>
      <tr id="i_<?php echo $loop-$i?>"   > 
        <!--				<td>
					<?php echo  $mydata[$i][0]['client_payment_id']; ?>
				</td>-->
        <td align="center"><?php echo $mydata[$i][0]['payment_time']?></td>
        <td align="center"><?php echo $mydata[$i][0]['client_name']?></td>
        <td align="center"><?php 
       $cost=number_format($mydata[$i][0]['amount'], 5);
					  if( $mydata[$i][0]['payment_type']==1){echo "<b  style='color:green'>$cost</b>";}
          if( $mydata[$i][0]['payment_type']==2){echo "<b  style='color:#FD6D06'>$cost</b>";} 
          if( $mydata[$i][0]['payment_type']==3){echo "<b  style='color:#0000CC !important'>$cost</b>";} 
							if( $mydata[$i][0]['payment_type']==4){echo "<b  style='color:red'>$cost</b>";}
			     ?></td>
        <td align="center"><?php
		 			if( $mydata[$i][0]['payment_type']==1){echo "<b  style='color:green'>".__('Paymentmanual',true)."</b>";}
        if( $mydata[$i][0]['payment_type']==2){echo "<b  style='color:#FD6D06'>Credit</b>";} 
        if( $mydata[$i][0]['payment_type']==3){echo "<b  style='color:#0000CC !important'>offset</b>";} 
        if( $mydata[$i][0]['payment_type']==4){echo "<b  style='color:red'>Payment for invoice </b>";} 
  				?></td>
        <?php if($_SESSION['login_type']==1){?>
        <td style="text-align:center;"><?php 
       	  if($mydata[$i][0]['approved'] == false) {echo __('noapprovedd',true);}
       	  else{if( $mydata[$i][0]['result']=='true' or $mydata[$i][0]['result']=='1'){echo "<b  style='color:green'>".__('TransactionSuccess',true)."</b>";}}
       		   ?></td>
        <td style="text-align:center;"><?php  if($mydata[$i][0]['approved'] == false) {?>
          <a href="<?php echo $this->webroot?>transactions/approved/3/<?php echo $mydata[$i][0]['client_payment_id']?>/<?php echo $mydata[$i][0]['client_id'];?>" title="<?php echo __('approved')?>" > <img src="<?php echo $this->webroot?>images/approve.gif"/> </a> 
          <script type="text/javascript">$('#i_<?php echo $loop-$i?>').css('color','red');</script>
          <?php }else{?>
          <img width="16" height="16" src="<?php echo $this->webroot?>images/status_closed.gif" alt="">
          <?php }?></td>
        <?php }?>
      </tr>
      <tr style="display:none">
        <td><dl id="i_<?php echo $loop-$i?>-tooltip" class="tooltip">
            <dd>
              <?php __('trans_type')?>
              :
              <?php if( $mydata[$i][0]['payment_type']==1){echo  'Payment';}?>
            </dd>
            <dd> </dd>
            <dd>
              <div class="note">.</div>
            </dd>
            <dt><?php echo __('note')?>:</dt>
            <dd></dd>
            <dt>:</dt>
            <dd><?php echo $_SESSION['sst_user_name']?> (<em>#5)</em></dd>
          </dl></td>
      </tr>
      <?php }?>
    </tbody>
  </table>
  <div id="tmppage"> <?php echo $this->element('page');?> </div>
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
     		<td style="padding: 0pt 10px;"><?php __('in')?></td>
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
    <td class="label"> <?php echo __('Amount',true);?>: </td>
    <td class="value" >
        <input type="text" id="start_amount" style="width:80px;" value="<?php if(isset($_GET['start_amount'])) echo $_GET['start_amount'];?>" name="start_amount" class="input in-text">
              --
              <input type="text" id="end_amount" style="width:80px;" value="<?php if(isset($_GET['end_amount'])) echo $_GET['end_amount'];?>" name="end_amount" class="input in-text">
    </td>
   
 		
    
     <td class="label" style="display:none;"> <?php  __('Carriers')?>  </td>
    <td class="value" style="display:none;" >
			<input class="input in-hidden" name="query[id_clients]" value="" id="query-id_clients" type="hidden">
              <input type="text" id="query-id_clients_name" onclick="showClients()" style="width: 53%;" readonly="1" value="" name="query[id_clients_name]" class="input in-text">
              <img width="9" height="9" onclick="showClients()" class="img-button" src="<?php echo $this->webroot?>images/search-small.png"> <img width="9" height="9" onclick="ss_clear('client', _ss_ids_client)" class="img-button" src="<?php echo $this->webroot?>images/delete-small.png">
    </td>
    <td><label><?php echo __('status')?></label></td>
    <td class="value">
              <select id="tran_status" name="tran_status" style="width:120px;">
                <option value=""><?php echo __('select')?></option>
                <option value="true"><?php echo __('approvedd')?></option>
                <option value="false"><?php echo __('noapprovedd')?></option>
              </select></td>
      <td class="value" style="width:200px;"><?php  __('Carriers')?>
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
//<![CDATA[
var _ss_ids_client = {'id_clients': 'query-id_clients', 'id_clients_name': 'query-id_clients_name', 'account': 'query-account', 'tz': 'query-tz', 'id_currencies': 'query-id_currencies'};
function showClients ()
{
    ss_ids_custom['client'] = _ss_ids_client;
    winOpen('<?php echo $this->webroot?>clients/ss_client?types=2&type=0', 500, 530);
}
//]]>
</script> 
<script type="text/javascript" src="<?php echo $this->webroot?>js/My97DatePicker/WdatePicker.js"></script> 
<script type="text/javascript">
<!--
 jQuery(document).ready(function(){
    jQuery('#start_amount,#end_amount') .xkeyvalidate({type:'Money'});         
   } );
//-->
</script> 
