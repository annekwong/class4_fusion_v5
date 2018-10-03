<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<script type="text/javascript"">
	//选择代理商或者客户或者卡  由子页面调用
	function choose(tr){
	document.getElementById('query-id_clients_name').value = tr.cells[1].innerHTML.trim();
	document.body.removeChild(document.getElementById("infodivv"));
	closeCover('cover_tmp');
		}
</script>

<div id="cover"></div> 
<div id="cover_tmp"></div>
<div id="title">
            <h1>
      路由伙伴交易记录  
      <!--  <a title="add to smartbar" href="<?php echo $this->webroot?>clients/view">
      <img width="10" height="10" alt="+" src="<?php echo $this->webroot?>images/qb-plus.png"></a>-->
                        </h1>
        

    
<ul id="title-search">
        <li>
        <?php //****************************模糊搜索**************************?>
        <form   id="like_form"  action=""  method="post">
        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('search')?>..." 
        value="<?php if(isset($searchkey)){echo $searchkey;}else{ }?>" 
        
       
         name="searchkey">
        </form>
        </li>
        
        <li title="<?php echo __('advancedsearch')?> »" onclick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>
    </ul>
    
    <?php if (isset($extraSearch)) {?>
    		<ul id="title-menu">
    		<li>
    			<a  class="link_back"href="<?php echo $this->webroot?>resellers/reseller_list">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('goback')?>
    			</a>
    		</li>
  		</ul>
    <?php }?>
    
    
    
    <?php  $action=$_SESSION['sst_statis_smslog'];
    $w=$action['writable'];?><!--
        <ul id="title-menu">
        <?php if(!empty($w)){ 	?>
<li><a class="link_btn" href="<?php echo $this->webroot  ?>transactions/export_csv"><img width="16" height="16" alt="" src="<?php echo  $this ->webroot?>images/export.png"> 
导出
</a></li>

<li><a class="link_btn" href="<?php echo $this->webroot  ?>transactions/add"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"> <?php __('createnew')?></a></li>
        <?php }?></ul>
        

    --></div>

<div id="container">

<?php //*********************  条件********************************?>
<fieldset class="title-block" id="advsearch"  style="width: 100%;">
	<?php echo $form->create ('Transaction', array ('action' => 'view' ,'onsubmit'=>""));?>
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
   
    <td><label>&nbsp;</label><select onchange="if(this.value.length>0)$('#query-id_clients_name').removeAttr('disabled');else $('#query-id_clients_name').attr('disabled',true);" class="input in-select" name="query[client_type]" style="width: 90%;"  id="query-client_type"><option value="" selected><?php echo __('select')?></option><option value="0"><?php echo __('client')?></option><option value="2"><?php echo __('Reseller')?></option><option value="1"><?php echo __('account')?></option></select></td>
    <td id="client_cell">
    <label>&nbsp;</label>
        <input class="input in-text" name="query[id_clients_name]" value="" readonly="1" disabled style="float:left;width: 120px;height:20px;" onclick="showClients()" id="query-id_clients_name" type="text"><img width="9" height="9" onclick="ss_clear('client', _ss_ids_client)" style="float:left;paddint-top:10px;" class="img-button" src="<?php echo $this->webroot?>images/delete-small.png">
    </td>
    
    <td>
    		<label><?php echo __('trans_type')?></label>
    		<select id="trantype" name="tran_type">
    				<option value=""><?php echo __('select')?></option>
    				<option value="11"><?php echo __('cdrcost')?></option>
    				<option value="12"><?php echo __('packagecost')?></option>
    				<option value="13"><?php echo __('msgcost')?></option>
    				<option value="15"><?php echo __('refills')?></option>
    		</select>
    </td>
    <td class="buttons"><input type="submit" value="Search" class="input in-submit"></td>
</tr>
</tbody></table>
<?php echo $form->end();?></fieldset>

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
		}}
?>
	<?php //*********************查询条件********************************?>


		<div id="toppage"></div>
<table class="list">
<col width="6%">
<col width="12%">
<col width="12%">
<col width="8%">
<col width="8%">

<col width="6%">

<thead>
<tr>
<td >&nbsp;<?php echo __('id',true);?>&nbsp;</td>
    <td >&nbsp;交易日期&nbsp;</td>
    <td >&nbsp;<?php  __('Carriers')?>&nbsp;</td>
    <td >&nbsp;交易<?php echo __('amounts')?></td>
    <td ><?php echo __('trans_type')?></td>
     <td><?php echo __('balance')?></td>

          
    
  
</tr>
 
</thead>
<tbody>




	<?php 
					$mydata =$p->getDataArray();
					$loop = count($mydata); 
					for ($i=0;$i<$loop;$i++) {?>
<tr id="i_<?php echo $loop-$i?>"   rel="tooltip">
<td align="center"><?php echo $loop-$i?></td>
    <td align="center"><?php echo $mydata[$i][0]['create_time']?></td>
     <td align="center"><?php echo $mydata[$i][0]['name']?></td>
     <td align="center">
     <?php if( $mydata[$i][0]['tran_type']==11){echo "-";}
     if( $mydata[$i][0]['tran_type']==12){echo "-";}
     if( $mydata[$i][0]['tran_type']==13){echo "-";} 
         if( $mydata[$i][0]['tran_type']==0){echo '';}
     ?>
					
					
					<?php   echo number_format($mydata[$i][0]['amount'], 3);?></td>
    
    
     <td align="center">
     
					<?php

         if( $mydata[$i][0]['tran_type']==0){echo  '系统充值卡';}
					   if( $mydata[$i][0]['tran_type']==1){echo  '话务扣费';}
					   if( $mydata[$i][0]['tran_type']==2){echo  __('systemrefillcard');}
					   if( $mydata[$i][0]['tran_type']==3){echo  __('yibao');}
					   if( $mydata[$i][0]['tran_type']==4){echo  __('kuaiqian');}
					   if( $mydata[$i][0]['tran_type']==5){echo  __('zhifubao');}
					   if( $mydata[$i][0]['tran_type']==6){echo  'PayPal';}
					   if( $mydata[$i][0]['tran_type']==11){echo  __('cdrcost');}
     if( $mydata[$i][0]['tran_type']==12){echo  __('packagecost');}
     if( $mydata[$i][0]['tran_type']==13){echo  __('smscost');} 
					   
     ?></td>
     
     
     
       <td align="center"><?php echo $mydata[$i][0]['balance']?></td>

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
					   if( $mydata[$i][0]['tran_type']==6){echo  'PayPal';}
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



</div>
<div>
<div id="tmppage">
<?php echo $this->element('page');?>


</div>
</div>

<script type="text/javascript">
var _ss_ids_client = {'id_clients': 'query-id_clients', 'id_clients_name': 'query-id_clients_name', 'account': 'query-account', 'tz': 'query-tz', 'id_currencies': 'query-id_currencies'};
var _ss_ids_code = {'code': 'query-code', 'id_code_decks': 'query-id_code_decks'};
var _ss_ids_code_name = {'code_name': 'query-code_name', 'id_code_decks': 'query-id_code_decks'};
</script>

<script type="text/javascript">
//<![CDATA[
tz = $('#query-tz').val();
function showClients ()
{
	ss_ids_custom['client'] = _ss_ids_client;
    val = $('#query-client_type').val();
    tz = $('#query-tz').val(); 
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
<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	
<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">
	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>
	<script type="text/javascript" src="<?php echo $this->webroot?>js/My97DatePicker/WdatePicker.js"></script>