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
     充值失败记录
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
    
    
    
    <?php  $action=$_SESSION['sst_payment'];
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

<dl id="update_record" class="tooltip-styled" style="display:none;position:absolute;left:40%;top:30%;z-idnex:99;width:300px;height:auto;">
<div style="text-align:center;width:100%;height:25px;font-size: 16px;"><?php echo __('updatepayment')?></div>
<form method="post" action="">
<div><?php echo __('newpayment')?>:<input id="newpayment" name="newpayment"/></div>
<input id="u_user_type" name="user_type" type="hidden"/>
<input id="u_user_id" name="user_id" type="hidden"/>
<input id="oldmoney" name="oldmoney" type="hidden"/>
<div style="margin-top:10px;width:auto;height:auto;">
		<input type="submit" value="<?php echo __('submit')?>" class="input in-button">
		<input type="button" onclick="closeCover('update_record');" value="<?php echo __('cancel')?>" class="input in-button">
	</div>
	</form>
</dl>

<?php //*********************  条件********************************?>
<fieldset class="query-box" id="advsearch"  style="width: 100%;">
	<?php echo $form->create ('Transaction', array ('action' => 'refill_view' ,'onsubmit'=>""));?>
<table    style="text-align: right;">
<tbody>
<tr>
    
    <td id="client_cell" class="value"  style="325px;">
     <?php  __('Carriers')?>
        <input type="text" id="query-id_clients_name" onclick="showClients()" style="width: 150px;" readonly="1" value="" name="query[id_clients_name]" class="input in-text">        
        
        <img width="9" height="9" onclick="showClients()" class="img-button" src="<?php echo $this->webroot?>images/search-small.png">
        <img width="9" height="9" onclick="ss_clear('client', _ss_ids_client)" class="img-button" src="<?php echo $this->webroot?>images/delete-small.png">
    </td>
       
    <td id="client_cell" class="value"  style="text-align: left;width: 293px;">
    帐号卡
        <input type="text" id="query-id_cards_name" onclick="showCards()" style="width: 150px;" readonly="1" value="" name="query[id_cards_name]" class="input in-text">        
        
        <img width="9" height="9" onclick="showCards()" class="img-button" src="<?php echo $this->webroot?>images/search-small.png">
        <img width="9" height="9" onclick="ss_clear('card', _ss_ids_card)" class="img-button" src="<?php echo $this->webroot?>images/delete-small.png">
    </td>
    

    
   
    <td id="client_cell" class="value"  style="width: 252px;">
      代理商 
        <input type="text" id="query-id_resellers_name" onclick="showRsellers()" style="width: 150px;" readonly="1" value="" name="query[id_resellers_name]" class="input in-text">        
        
        <img width="9" height="9" onclick="showRsellers()" class="img-button" src="<?php echo $this->webroot?>images/search-small.png">
        <img width="9" height="9" onclick="ss_clear('reseller', _ss_ids_reseller)" class="img-button" src="<?php echo $this->webroot?>images/delete-small.png">
    </td>
 <td class="buttons"><input type="submit" value="<?php echo __('Search',true);?>" class="input in-submit"></td>
</tr>
<tr>


     <td><?php __('tranamount')?>:
     <input   type="hidden"   value="searchkey"    name="searchkey"/>
 <input class="input in-hidden" name="query[id_clients]" value="" id="query-id_clients" type="hidden">
<input class="input in-hidden" name="query[id_resellers]" value="" id="query-id_resellers" type="hidden">
<input class="input in-hidden" name="query[account]" value="" id="query-account" type="hidden">
<input class="input in-hidden" name="query[id_cards]" value="" id="query-id_cards" type="hidden">
	<input type="text" id="start_amount" style="width:84px;height:20px;" value="<?php if(isset($_POST['start_amount'])){echo $_POST['start_amount'];}?>" name="start_amount" class="input in-text">
    		--
    		<input type="text" id="end_amount" style="width:84px;height:20px;" value="" name="end_amount" class="input in-text">
    </td>
    <td><?php __('timeprofile')?>:
	<input type="text" readonly onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" id="start_date" style="width:120px;height:20px;" value="" name="start_date" class="input in-text wdate">
    		--
    		<input type="text" readonly onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" id="end_date" style="width:120px;height:20px;" value="" name="end_date" class="wdate input in-text">
    </td>
    
    <td>
 充值方式
 
  		<?php 
  		$trantype=array('0'=>'系统充值卡','1'=>'快钱','2'=>'系统管理员(手工充值的)','3'=>'易宝');
  		echo $form->input('trantype',
 		array('options'=>$trantype,'empty'=>'请选择充值方式','label'=>false ,'div'=>false,'type'=>'select','style'=>'width:180px;'));?>

    </td>
   
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


		<?php $d = $p->getDataArray();if (count($d) == 0) {?>
<div class="msg"><?php echo __('no_data_found')?></div>
<?php } else {?>
		<div id="toppage"></div>
<table class="list">
<col width="3%">
<col width="6%">
<col width=9%">
<col width="6%">
<col width="8%">
<col width="8%">
<col width="6%">
<col width="6%">

<thead>
<tr>
<td><?php __('ID')?></td>
<td >&nbsp;用户类型&nbsp;</td>
    <td >&nbsp;名称&nbsp;</td>
    <td >&nbsp;充值金额&nbsp;</td>
    <td >&nbsp;充值日期</td>
    <td >充值结果</td>
     <td>充值平台</td>
     <td><?php echo __('action')?></td>

          
    
  
</tr>
 
</thead>
<tbody>




	<?php 
					$mydata =$p->getDataArray();
					$loop = count($mydata); 
					for ($i=0;$i<$loop;$i++) {?>
<tr id="i_<?php echo $loop-$i?>"   rel="tooltip">
<td><?php echo $mydata[$i][0]['id']?></td>
    <td align="center"><?php  if($mydata[$i][0]['user_type']=='2'){echo  "<b  style='color:red'> 代理商 </b>";}?>
    <?php  if($mydata[$i][0]['user_type']=='3'){echo  "<b  style='color:green'> 路由伙伴 </b>";}?>
    <?php  if($mydata[$i][0]['user_type']=='4'){echo  "<b  style='color:blue'> 帐号卡 </b>";}?>
    </td>
     <td align="center"><?php   if(empty($mydata[$i][0]['name'])){echo __('Unknown',true);}else{  echo $mydata[$i][0]['name'];}?></td>
     <td align="center"><?php   echo number_format($mydata[$i][0]['amount'], 3);?></td>
    
    
      <td align="center"><?php echo $mydata[$i][0]['create_time']?></td>
       <td align="center"><?php 
       if($mydata[$i][0]['result']=='1'){echo "充值成功";}else{echo "充值失败";} ?></td>
     <td align="center">
     
					<?php

         if( $mydata[$i][0]['tran_type']==0){echo '系统充值卡';}
					   if( $mydata[$i][0]['tran_type']==1){echo '快钱';}
					   if( $mydata[$i][0]['tran_type']==2){echo  '系统管理员代充';}
					   if( $mydata[$i][0]['tran_type']==3){echo  '易宝';}
			
					   
     ?>
      </td>
  

<td>
		<?php if (($mydata[$i][0]['user_type'] == 2 || $mydata[$i][0]['user_type'] == 3)) {?>
				<a style="float:left;margin-left:45%" href="javascript:void(0)" onclick="$('#oldmoney').val('<?php echo $mydata[$i][0]['amount'];?>');$('#u_user_type').val('<?php echo $mydata[$i][0]['user_type']?>');$('#u_user_id').val('<?php echo $mydata[$i][0]['id']?>');cover('update_record');">
		   			<i class="icon-edit"></i>
		   </a>
		<?php }?>
</td>
                
  

</tr>
<dl id="i_<?php echo $loop-$i?>-tooltip" class="tooltip">
    <dl><?php __('trans_type')?>:</dl>
    <dd>					<?php

					      if( $mydata[$i][0]['tran_type']==0){echo '系统充值卡';}
					   if( $mydata[$i][0]['tran_type']==1){echo '快钱';}
					   if( $mydata[$i][0]['tran_type']==2){echo  '系统管理员';}
					   if( $mydata[$i][0]['tran_type']==3){echo  '易宝';}
					   
     ?></dd>
    <dd><div class="note">.</div></dd>
        <dt><?php echo __('note')?>:</dt>
    <dd></dd>
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
<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	
<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">
	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>
	<script type="text/javascript" src="<?php echo $this->webroot?>js/My97DatePicker/WdatePicker.js"></script>
