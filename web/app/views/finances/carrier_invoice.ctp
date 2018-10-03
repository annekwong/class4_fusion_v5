<?php $action_type=empty($_GET['action_type'])?'2':$_GET['action_type'];?>
<div id="title">
  <h1>
   <?php  __('Finance')?>
    &gt;&gt;
    <?php __('Carrier Invoice')?>
  </h1>
  <ul id="title-search">
    <li>
      <?php //********************模糊搜索**************************?>
      <form   id="like_form"  action=""  method="get">
        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch')?>" value="<?php if (!empty($search)) echo $search;?>" name="search">
        
        <input type="submit" name="submit" value="" class="search_submit"/>
        <input type="hidden" name="action_type" value="<?php echo $action_type;?>" />
        
      </form>
    </li>
    <li title="<?php echo __('advancedsearch')?> »" onClick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>
  </ul>
  <ul id="title-menu">
    
   <a class="link_btn" rel="popup" href="javascript:void(0)" onclick="GenerateInvoiceSelected();"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"> <?php echo __('Generate Invoice')?></a></li>
    
  
  </ul>
  <?php  
	$action=isset($_SESSION['sst_statis_smslog'])?$_SESSION['sst_statis_smslog']:'';
 $w=isset($action['writable'])?$action['writable']:'';
 ?>
</div>
<div id="container">

  <?php //*********************  条件********************************?>
  <!--<fieldset class="title-block" id="advsearch"  style="width: 98%;display:block;">
    <form action="" method="get">
      <input name="advsearch" type="hidden"/>
      <input name="action_type" type="hidden" value="<?php echo $action_type?>"/>
      <table style="width:100%">
        <tbody>
          <tr>
            
            <td><label><?php echo __('Transaction Date',true);?> :</label>
              <input type="text" readonly onFocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" id="start_date" style="width:120px;" name="start_date" class="input in-text wdate" value="<?php echo !empty($_REQUEST['start_date']) ? $_REQUEST['start_date'] : ''; ?>">
              --
              <input type="text" readonly onFocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" id="end_date" style="width:120px;"  name="end_date" class="wdate input in-text" value="<?php echo !empty($_REQUEST['end_date']) ? $_REQUEST['end_date'] : ''; ?>"></td>
           
              <td><label><?php echo __('status',true);?>:</label>
              <select id="tran_status" name="tran_status">
                <option value=""><?php echo __('select')?></option>
                <option value="1" <?php echo (!empty($_REQUEST['tran_status']) && $_REQUEST['tran_status'] == 1) ? 'selected' : '';?>>Waiting</option>
                <option value="2" <?php echo (!empty($_REQUEST['tran_status']) && $_REQUEST['tran_status'] == 2) ? 'selected' : '';?>>Completed</option>
             			<?php if ($action_type == 1):?>
                <option value="0" <?php echo (isset($_REQUEST['tran_status']) && $_REQUEST['tran_status']  == 0) ? 'selected' : '';?>>Confirmed</option>
                <option value="3" <?php echo (!empty($_REQUEST['tran_status']) && $_REQUEST['tran_status'] == 3) ? 'selected' : '';?>>Refused</option>
                <?php endif;?>
              </select></td>
            <td><input type="submit" value="<?php echo __('submit')?>" class="input in-submit"></td>
          </tr>
        </tbody>
      </table>
    </form>
  </fieldset>-->
 
 <!-- <div id="toppage"></div>-->
 <?php 			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			if(empty($mydata)){
			?>
<div class="msg"><?php  echo __('no_data_found') ?>.</div>
<?php }else{

?>
  <div id="tmppage"> 
  <?php echo $this->element('page');?>
  </div>
  <table class="list">
      <thead>
        <tr>
          <td><?php echo $appCommon->show_order('name', __('Carrier',true));?></td>
          <td><?php echo $appCommon->show_order('invoice_number', __('Invoice Number',true));?></td>
          <td><?php echo $appCommon->show_order('total_amount', __('Invoice Amount',true));?></td>
          <td><?php echo __('Period',true);?></td>
          <td></td>
          <td></td>
          <td><?php echo $appCommon->show_order('due_date', __('Dua Date',true));?></td>
          <td><?php __('Action')?></td>
          
        </tr>
      </thead>
      <tbody id="producttab">
      <?php 
      for ($i=0;$i<$loop;$i++){
      	$status_val = array(0=>'Confirmed', 1=>'Waiting', 2=>'<font style="color:#FF6D06;">Complete</font>', 3=>'<font style="color:#FF0000;">Refused</font>');
      			?>
        <tr>
          <td>
          	<?php echo $mydata[$i][0]['name'] ?>
          </td>
          <td><?php echo $mydata[$i][0]['invoice_number'] ?></td>
          <td><?php echo $mydata[$i][0]['total_amount'] ?></td>
          <td><?php echo $mydata[$i][0]['invoice_start'] ?>
              <?php echo $mydata[$i][0]['invoice_end'] ?>
          </td>
          <td></td>
          <td></td>
          <td><?php echo $mydata[$i][0]['due_date'] ?></td>
          <td>
               <a href="<?php echo $this->webroot?>finances/carrier_invoice/<?php echo $mydata[$i][0]['invoice_id'];?>" title="send mail">
                    <img src="<?php echo $this->webroot;?>images/send.png" />
               </a>
          </td>
        </tr>
        <?php }?>
      </tbody>
    </table>
  <div id="tmppage"> 
  <?php echo $this->element('page');?>
  </div>
  <?php } ?>
</div>
