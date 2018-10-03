<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<script type="text/javascript"">
	//选择代理商或者客户或者卡  由子页面调用
	function choose(tr){
	document.getElementById('res_name').value = tr.cells[1].innerHTML.trim();
	document.body.removeChild(document.getElementById("infodivv"));
	closeCover('cover_tmp');
		}
</script>
<div id="cover"></div> 
<div id="cover_tmp"></div>
<div id="title">
            <h1>
        <span></span>
    代理商 交易明细   
      <!--  <a title="add to smartbar" href="<?php echo $this->webroot?>clients/view">
      <img width="10" height="10" alt="+" src="<?php echo $this->webroot?>images/qb-plus.png"></a>-->
                        </h1>
        

    
<ul id="title-search">
        <li>
        <?php //****************************模糊搜索**************************?>
   <form   id="like_form"  action=""  method="post">
        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch')?>" value="<?php if (!empty($search)) echo $search;?>" name="search">
        </form>
        </li>
        
        <li title="<?php echo __('advancedsearch')?> »" onclick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>
    </ul>
    
    <ul id="title-menu">
    <?php if (isset($extraSearch)) {?>
    		<li>
    			<a class="link_back" href="<?php echo $backurl?>">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('goback')?>
    			</a>
    		</li>
    <?php }?>
    

    
    
    <!--
    <li>
    			<a class="linnk_btn" href="<?php echo $this->webroot?>transactions/approve_selected/<?php echo $user_type?>/all">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/status_closed.gif">
    				&nbsp;<?php echo __('approveall')?>
    			</a>
    		</li>
    		
    		<li>
    			<a class="link_btn" href="javascript:void(0)" onclick="approvedSelected('tran_tab','<?php echo $this->webroot?>transactions/approve_selected/<?php echo $user_type?>');">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/status_closed.gif">
    				&nbsp;<?php echo __('approveselect')?>
    			</a>
    		</li>
    		   		--><li>
    			<a class="link_btn" href="<?php echo $this->webroot?>resclis/make_payment_account" >
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/add.png">
    				&nbsp;<?php echo __('createnew',true);?>
    			</a>
    		</li>
    
    </ul>
    
    
    
    <?php  $action=isset($_SESSION['sst_statis_smslog'])?$_SESSION['sst_statis_smslog']:'';
    $w=isset($action['writable'])?$action['writable']:'';?><!--
        <ul id="title-menu">
        <?php if(!empty($w)){ 	?>
<li><a class="link_btn" href="<?php echo $this->webroot  ?>transactions/export_csv"><img width="16" height="16" alt="" src="<?php echo  $this ->webroot?>images/export.png"> 
导出
</a></li>

<li><a  class="link_btn" href="<?php echo $this->webroot  ?>transactions/add"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"> <?php __('createnew')?></a></li>
        <?php }?></ul>
        

    --></div>

<div id="container">

<?php $d = $p->getDataArray();if (count($d) == 0) {?>
<div class="msg"><?php echo __('no_data_found')?></div>
<?php } else {?>

<?php //*********************  条件********************************?>
<fieldset class="title-block" id="advsearch"  style="margin-left:1px;width: 100%;">
	<form action="" method="post">
	<input name="advsearch" type="hidden"/>
<table>
<tbody>
<tr>

 <td><label   ><?php __('tranamount')?>:</label>
	<input type="text" id="start_amount" style="width:60px;height:20px;" value="" name="start_amount" class="input in-text">
    		--
    		<input type="text" id="end_amount" style="width:60px;height:20px;" value="" name="end_amount" class="input in-text">
    </td>
    <td><label   ><?php __('timeprofile')?>:</label>
	<input type="text" readonly onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" id="start_date" style="width:120px;height:20px;" value="" name="start_date" class="input in-text wdate">
    		--
    		<input type="text" readonly onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" id="end_date" style="width:120px;height:20px;" value="" name="end_date" class="wdate input in-text">
    </td>
   
    <td><label>&nbsp;</label>  代理商 </td>
    <td id="client_cell">
    <label>&nbsp;</label>
        <input type="text" id="query-id_resellers_name" onclick="showRsellers()" style="width: 150px;" readonly="1" value="" name="query[id_resellers_name]" class="input in-text">        
        <input class="input in-hidden" name="query[id_resellers]" value="" id="query-id_resellers" type="hidden">
        <img width="9" height="9" onclick="showRsellers()" class="img-button" src="<?php echo $this->webroot?>images/search-small.png">
        <img width="9" height="9" onclick="ss_clear('reseller', _ss_ids_reseller)" class="img-button" src="<?php echo $this->webroot?>images/delete-small.png">
    </td>
    
     <td>
    		<label><?php echo __('trans_type')?></label>
    		<select id="tran_type" name="tran_type">
    				<option value=""><?php echo __('select')?></option>
    				<option value="payment"><?php echo __('supplement')?></option>
    				<option value="cdr"><?php echo __('cdrcost')?></option>
    		</select>
    </td>
    
    
	    <td>
	    		<label><?php echo __('status')?></label>
	    		<select id="tran_status" name="tran_status">
	    				<option value=""><?php echo __('select')?></option>
	    				<option value="true"><?php echo __('approvedd')?></option>
	    				<option value="false"><?php echo __('noapprovedd')?></option>
	    		</select>
	    </td>
   
    <td class="buttons"><input type="submit" value="<?php echo __('submit')?>" class="input in-submit"></td>
</tr>
</tbody></table>
</form></fieldset>

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
<col width=3%">
<col width=9%">
<col width="12%">
<col width="8%">
<col width="8%">
<col width="6%">

<col width="6%">
<thead>
<tr>
<td><input type="checkbox" onclick="checkAll(this,'tran_tab');"/></td>
    <td >&nbsp;交易日期&nbsp;</td>
    <td >&nbsp;代理商&nbsp;</td>
    <td >&nbsp;交易<?php echo __('amounts')?></td>
    <td ><?php echo __('trans_type')?></td>
     <td style="text-align:center;">当前余额</td>
    
      <td style="text-align:center;">交易结果</td>

          
    
  
</tr>
 
</thead>
<tbody id="tran_tab">




	<?php 
					$mydata =$p->getDataArray();
					$loop = count($mydata); 
					for ($i=0;$i<$loop;$i++) {?>
<tr id="i_<?php echo $loop-$i?>"   rel="tooltip">
			<td>
			<?php echo $i; ?>
					
	
			</td>
    <td align="center"><?php echo $mydata[$i][0]['time']?></td>
     <td align="center"><?php echo $mydata[$i][0]['reseller_name']?></td>
     <td align="center">
     <?php 
     
       $cost=number_format($mydata[$i][0]['amount'], 3);
     if( $mydata[$i][0]['tran_type']==1){echo "<b  style='color:red'>－$cost</b>";}
					   if( $mydata[$i][0]['tran_type']==2){echo "<b  style='color:green'>+$cost</b>";}
          if( $mydata[$i][0]['tran_type']==3){echo "<b  style='color:red'>－$cost</b>";} 
        
     ?>
</td>
    
    
     <td align="center">
     
					<?php

					    
					   if( $mydata[$i][0]['tran_type']==1){echo  '来电显示租金';}
					   if( $mydata[$i][0]['tran_type']==2){echo  '系统管理员手工充值';}
					   if( $mydata[$i][0]['tran_type']==3){
					    if($mydata[$i][0]['cost_type']==1){	echo  '增值业务扣费';}
					    if($mydata[$i][0]['cost_type']==2){	echo  '语音套餐租金';}
					    if($mydata[$i][0]['cost_type']==3){	echo  '自定义';}
					 
					   	
					   }
			

					   
     ?></td>
     
     
     
       <td style="text-align:center;">
       		<?php echo number_format($mydata[$i][0]['balance'],3);?>

       </td>


       <td style="text-align:center;">
       		<?php if( $mydata[$i][0]['result']=='true' or $mydata[$i][0]['result']=='1'){echo "<b  style='color:green'>交易成功</b>";}?>
       </td>
                
  

</tr>
<dl id="i_<?php echo $loop-$i?>-tooltip" class="tooltip">
    <dl><?php __('trans_type')?>:<?php

					 if( $mydata[$i][0]['tran_type']==2){echo  '系统管理员手工充值';}
					   
     ?></dl>
    <dd>					</dd>
    <dd><div class="note">.</div></dd>
        <dt><?php echo __('note')?>:</dt>
    <dd></dd>
        <dt>充值人员:</dt>
    <dd><?php echo $_SESSION['sst_user_name']?> (<em>#5)</em></dd>
</dl>

	<?php }?>

</tbody><tbody>
</tbody></table>

<div id="tmppage">
<?php echo $this->element('page');?>


</div>

</div>

<div>

<?php }?>

</div>

<script type="text/javascript">

//设置每个字段所对应的隐藏域
var _ss_ids_client = {'id_clients': 'query-id_clients', 'id_clients_name': 'query-id_clients_name', 'account': 'query-account', 'tz': 'query-tz', 'id_currencies': 'query-id_currencies'};
var _ss_ids_reseller = {'id_resellers': 'query-id_resellers', 'id_resellers_name': 'query-id_resellers_name', 'account': 'query-account', 'tz': 'query-tz', 'id_currencies': 'query-id_currencies'};


var _ss_ids_card = {'id_cards': 'query-id_cards', 'id_cards_name': 'query-id_cards_name', 	'account': 'query-account', 'tz': 'query-tz', 'id_currencies': 'query-id_currencies'};



</script>

<script type="text/javascript">
//<![CDATA[

  function showCards ()
  {
      ss_ids_custom['card'] = _ss_ids_card;
     // val = $('#query-client_type').val();//客户类型
      //tz = $('#query-tz').val();

      winOpen('<?php echo $this->webroot?>clients/ss_card?type=2&types=8', 500, 530);

  }
tz = $('#query-tz').val();
function showClients ()
{
    ss_ids_custom['client'] = _ss_ids_client;
    winOpen('<?php echo $this->webroot?>clients/ss_client?types=2&type=0', 500, 530);

}

function showRsellers()
{
    ss_ids_custom['reseller'] = _ss_ids_reseller;
    winOpen('<?php echo $this->webroot?>resellers/ss_reseller?type=2&types=8', 500, 530);

}

function repaintOutput() {
    if ($('#query-output').val() == 'web') {
        $('#output-sub').show();
    } else {
        $('#output-sub').hide();
    }
}
repaintOutput();
//]]>
</script>

	
	<script type="text/javascript" src="<?php echo $this->webroot?>js/My97DatePicker/WdatePicker.js"></script>