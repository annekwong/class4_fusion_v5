<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<script type="text/javascript">
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

      交易记录    
      <!--  <a title="add to smartbar" href="<?php echo $this->webroot?>clients/view">
      <img width="10" height="10" alt="+" src="<?php echo $this->webroot?>images/qb-plus.png"></a>-->
                        </h1>
        

    
<ul id="title-search">
        <li>
        <?php //****************************模糊搜索**************************?>
        	<form>
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
    
  
    <li>
    			<a class="link_btn" href="<?php echo $this->webroot?>transactions/approve_selected/<?php echo $user_type?>/all">
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
    		
    </ul>
    
    
    
    <?php  $action=isset($_SESSION['sst_statis_smslog'])?$_SESSION['sst_statis_smslog']:'';
    $w=isset($action['writable'])?$action['writable']:'';?><!--
        <ul id="title-menu">
        <?php if(!empty($w)){ 	?>
<li><a class="link_btn" href="<?php echo $this->webroot  ?>transactions/export_csv"><img width="16" height="16" alt="" src="<?php echo  $this ->webroot?>images/export.png"> 
导出
</a></li>

<li><a  href="<?php echo $this->webroot  ?>transactions/add"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"> <?php __('createnew')?></a></li>
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

 <td><label><?php __('tranamount')?>:</label>
	<input type="text" id="start_amount" style="width:60px;height:20px;" value="" name="start_amount" class="input in-text">
    		--
    		<input type="text" id="end_amount" style="width:60px;height:20px;" value="" name="end_amount" class="input in-text">
    </td>
    <td><label   ><?php __('timeprofile')?>:</label>
	<input type="text" readonly onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" id="start_date" style="width:120px;height:20px;" value="" name="start_date" class="input in-text wdate">
    		--
    		<input type="text" readonly onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" id="end_date" style="width:120px;height:20px;" value="" name="end_date" class="wdate input in-text">
    </td>
   
    <td><label>&nbsp;</label><select  class="input in-select" name="res_type" style="width: 90%;"  id="res_type">
	    <?php if ($user_type==3) {?><option value="0"><?php echo __('client')?></option><?php }?>
	    <?php if ($user_type==2) {?><option value="2"><?php echo __('Reseller')?></option><?php }?>
	    <?php if ($user_type==4) {?><option value="1"><?php echo __('account')?></option><?php }?>
    </select></td>
    <td id="client_cell">
    <label>&nbsp;</label>
        <input class="input in-text" name="res_name" value="" readonly style="float:left;width: 120px;height:20px;" onclick="showClients()" id="res_name" type="text"><img width="9" height="9" onclick="$('#res_name').val('');" style="float:left;paddint-top:10px;" class="img-button" src="<?php echo $this->webroot?>images/delete-small.png">
    </td>
    
     <td>
    		<label><?php echo __('trans_type')?></label>
    		<select id="tran_type" name="tran_type">
    				<option value=""><?php echo __('select')?></option>
    				<option value="payment"><?php echo __('supplement')?></option>
    				<option value="cdr"><?php echo __('cdrcost')?></option>
    		</select>
    </td>
    
    <?php if ($user_type != 4) {?>
	    <td>
	    		<label><?php echo __('status')?></label>
	    		<select id="tran_status" name="tran_status">
	    				<option value=""><?php echo __('select')?></option>
	    				<option value="true"><?php echo __('approvedd')?></option>
	    				<option value="false"><?php echo __('noapprovedd')?></option>
	    		</select>
	    </td>
    <?php }?>
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

<thead>
<tr>
<td><input type="checkbox" onclick="checkAll(this,'tran_tab');"/></td>
    <td >&nbsp;交易日期&nbsp;</td>
    <td >&nbsp;<?php if ($user_type==2) {echo __('Reseller');} else if ($user_type==3){echo __('client');} else {echo __('account');}?>&nbsp;</td>
    <td >&nbsp;<?php echo __('amounts')?></td>
    <td ><?php echo __('trans_type')?></td>
     <td style="text-align:center;"><?php echo __('balance')?></td>

          
    
  
</tr>
 
</thead>
<tbody id="tran_tab">




	<?php 
					$mydata =$p->getDataArray();
					$loop = count($mydata); 
					for ($i=0;$i<$loop;$i++) {?>
<tr id="i_<?php echo $loop-$i?>"   rel="tooltip">
			<td>
			<?php if (($mydata[$i][0]['user_type'] == 2 || $mydata[$i][0]['user_type'] == 3)
       		&& $mydata[$i][0]['approved'] == false) {?>
					<input type="checkbox" value="<?php echo $mydata[$i][0]['payment_id']?>"/>
			<?php }?>
			</td>
    <td align="center"><?php echo $mydata[$i][0]['create_time']?></td>
     <td align="center"><?php echo $mydata[$i][0]['name']?></td>
     <td align="center">
     <?php if( $mydata[$i][0]['tran_type']==11){echo "-";}
     if( $mydata[$i][0]['tran_type']==12){echo "-";}
     if( $mydata[$i][0]['tran_type']==1){echo "<b  style='color:red'>--</b>";} 
         if( $mydata[$i][0]['tran_type']==0){echo "<b  style='color:green'>+</b>";}
     ?>
					
					
					<?php   echo number_format($mydata[$i][0]['amount'], 3);?></td>
    
    
     <td align="center">
     
					<?php

					      if( $mydata[$i][0]['tran_type']==0){echo  '系统充值';}
					   if( $mydata[$i][0]['tran_type']==1){echo  '话务扣费';}
					   if( $mydata[$i][0]['tran_type']==2){echo  __('Prepaid card system');}
					   if( $mydata[$i][0]['tran_type']==3){echo  __('yibao');}
					   if( $mydata[$i][0]['tran_type']==4){echo  __('kuaiqian');}
					   if( $mydata[$i][0]['tran_type']==5){echo  __('zhifubao');}
					   if( $mydata[$i][0]['tran_type']==6){echo  __('PayPal');}
					   if( $mydata[$i][0]['tran_type']==11){echo  __('cdrcost');}
     if( $mydata[$i][0]['tran_type']==12){echo  __('packagecost');}
     if( $mydata[$i][0]['tran_type']==13){echo  __('smscost');} 
					   
     ?></td>
     
     
     
       <td style="text-align:center;">
       		<?php echo $mydata[$i][0]['balance']?>
       		<?php if (($mydata[$i][0]['user_type'] == 2 || $mydata[$i][0]['user_type'] == 3)
       		&& $mydata[$i][0]['approved'] == false) {?>
       		<a href="<?php echo $this->webroot?>transactions/approved/<?php echo $mydata[$i][0]['user_type']?>/<?php echo $mydata[$i][0]['payment_id']?>/<?php echo $mydata[$i][0]['id']?>"; title="<?php echo __('approved')?>" style="float:left;margin-left:45%;">
       			<img src="<?php echo $this->webroot?>images/approve.gif"/>
       		</a>
       		<script type="text/javascript">$('#i_<?php echo $loop-$i?>').css('color','red');</script>
       		<?php }?>
       </td>

  


                
  

</tr>
<dl id="i_<?php echo $loop-$i?>-tooltip" class="tooltip">
    <dl><?php __('trans_type')?>:</dl>
    <dd>					<?php

         if( $mydata[$i][0]['tran_type']==0){echo  __('systemadmin');}
					   if( $mydata[$i][0]['tran_type']==1){echo  __('routseller');}
					   if( $mydata[$i][0]['tran_type']==2){echo  __('systemrefillcard');}
					   if( $mydata[$i][0]['tran_type']==3){echo  __('yibao');}
					   if( $mydata[$i][0]['tran_type']==4){echo  __('kuaiqian');}
					   if( $mydata[$i][0]['tran_type']==5){echo  __('zhifubao');}
					   if( $mydata[$i][0]['tran_type']==6){echo  __('PayPal');}
					   if( $mydata[$i][0]['tran_type']==11){echo  __('cdrcost');}
     if( $mydata[$i][0]['tran_type']==12){echo  __('packagecost');}
     if( $mydata[$i][0]['tran_type']==13){echo  __('smscost');} 
					   
     ?></dd>
    <dd><div class="note"><?php echo $mydata[$i][0]['description']?>.</div></dd>
        <dt><?php echo __('note')?>:</dt>
    <dd><?php echo $mydata[$i][0]['description']?></dd>
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
var _ss_ids_client = {'id_clients': 'query-id_clients', 'id_clients_name': 'query-id_clients_name', 'account': 'query-account', 'tz': 'query-tz', 'id_currencies': 'query-id_currencies'};
var _ss_ids_code = {'code': 'query-code', 'id_code_decks': 'query-id_code_decks'};
var _ss_ids_code_name = {'code_name': 'query-code_name', 'id_code_decks': 'query-id_code_decks'};
</script>

<script type="text/javascript">
//<![CDATA[
function showClients ()
{
    val = $('#res_type').val();
    var url = null;
    if (val == "1")
        url = "<?php echo $this->webroot?>cdrs/choose_cards";
    else if (val == "0") 
    			url = "<?php echo $this->webroot?>cdrs/choose_clients";
    else if (val == "2")
    			url = "<?php echo $this->webroot?>cdrs/choose_resellers";

    cover('cover_tmp');
		 loadPage(url,500,400);
}
//]]>
</script>

	<script type="text/javascript" src="<?php echo $this->webroot?>js/My97DatePicker/WdatePicker.js"></script>