
<?php $w = $session->read('writable');?>
<div id="title">
  <h1><?php __('System')?>&gt;&gt;<?php echo __('Service Charge',true);?></h1>
	<ul id="title-search">
    <li>
	    	<form  method="get">
	    		<input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch')?>" 
	    		value="<?php if (!empty($search)) {echo $search;}else{echo '';}?>" name="search">
	    	</form>
    </li>
    <!--<li title="advanced search »" onclick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>-->
  </ul>
  <ul id="title-menu">
       <?php if ($w == true) {?><li>
       <?php echo $this->element("createnew",Array('url'=>'servicecharges/add'))?>
       </li><?php }?>
       <?php if (isset($extraSearch)) {?>
       <li>
    			<a class="link_back" href="<?php echo $this->webroot?>resellers/reseller_list">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('goback')?>
    			</a>
    		</li>
       <?php }?>
       <?php if (isset($edit_return)) {?>
        <li>
    			<a  class="link_back"href="<?php echo $this->webroot?>clients/view">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('gobackall')?>
    			</a>
    		</li>
        <?php }?>
        </ul>
    </div>

<div id="container">

<div id="cover"></div>
<div id="cover_tmp"></div>

<dl id="editbalance" class="tooltip-styled" style="display:none;position:absolute;left:40%;top:30%;z-idnex:99;width:300px;height:100px;">
<div style="text-align:center;width:100%;height:25px;font-size: 16px;"><?php echo __('editbalance')?></div>
	<div style="margin-top:10px;">
						<select style="width:100px;height:18px;"  style="width:120px;" id="rate_per_min_action" name="rate_per_min_action" class="input in-select">
        			<option value="set"><?php echo __('setto')?></option>
        			<option value="inc"><?php echo __('incfor')?></option>
        			<option value="dec"><?php echo __('decfor')?></option>
        			<option value="perin"><?php echo __('persinc')?></option>
        			<option value="perde"><?php echo __('persdec')?></option>
        		</select>
        		<input type="text" id="rate_per_min_value" class="in-decimal input in-text" value="0.000" name="rate_per_min_value" style="height:18px;float:right;width:100px;">
	</div>
	<input style="display:none" id="tmpcliid"/>
	<div style="margin-top:10px; margin-left:20%;width:150px;height:auto;">
		<input type="button" onclick="edit_balance();" value="<?php echo __('submit')?>" class="input in-button">
		<input type="button" onclick="closeCover('editbalance');" value="<?php echo __('cancel')?>" class="input in-button">
	</div>
</dl>

	<?php //*********************  条件********************************?>
<fieldset class="title-block" id="advsearch"  style="display:none;width: 100%;">
	<form method="get">
	<input type="hidden" name="adv_search" value="1"/>
<table  style="width: 550px;">
<tbody>
<tr>
    <td><label><?php echo __('Name')?>:</label>
 
     <input name="name" id="name"/>
  </td>

   

    <td class="buttons"><input type="submit" value="<?php echo __('submit')?>" class="input in-submit"></td>
</tr>

</tbody></table>
</form></fieldset>

	<?php //*********************查询条件********************************?>
<?php $d = $p->getDataArray();if (count($d) == 0) {?>
<div class="msg"><?php echo __('no_data_found')?></div>
<?php } else {?>
<div id="toppage"></div>
<div id="cover"></div>



<?php //*********************表格头*************************************?>
		<div>	
			<table class="list" >
				<col style="width: 5.8%;">
				<col style="width: 10%;">
					<col style="width: 9%;">
				<col style="width: 9.4%;">
				<col style="width: 9.4%;">
				<col style="width: 9.4%;">
				<col style="width: 9%;">
	    <col style="width: 9.5%;">
	     
				<col style="width: 13.5%;">

			<thead>
				<tr>

    			<td><!--	
    			 						<a onclick="my_sort('service_charge_id','asc')" href="javascript:void(0)"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>
    					
    			<?php echo __('id',true);?>
    			 						<a onclick="my_sort('service_charge_id','desc')" href="javascript:void(0)"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a>
    					
    			-->
    			 <?php echo $appCommon->show_order('service_charge_id','ID') ?>
    			</td>
    		<td >
    		    <?php echo $appCommon->show_order('name','Name') ?>
    		<!--
  
					<a onclick="my_sort('name','asc')" href="javascript:void(0)"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>
    					
             <?php echo __('Name')?>

					<a onclick="my_sort('name','desc')" href="javascript:void(0)"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a>
    					
--></td>
    <td>
        <?php echo $appCommon->show_order('buy_rate','Buy Rate') ?>
    					<!--<a onclick="my_sort('buy_rate','asc')" href="javascript:void(0)"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>
    			
     Buy Rate
     					<a onclick="my_sort('buy_rate','desc')" href="javascript:void(0)"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a>
    			
     --></td>
    <td> 
      <?php echo $appCommon->show_order('less_buy_rate_fee','Less Buy Rate Fee') ?>
    
    		<!--<a onclick="my_sort('less_buy_rate_fee','asc')" href="javascript:void(0)"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>
    Less Buy Rate Fee
     
     	<a onclick="my_sort('less_buy_rate_fee','desc')" href="javascript:void(0)"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a>-->
     </td>
  <td>
      <?php echo $appCommon->show_order('greater_buy_rate_fee','Greater Buy Rate Fee'); ?>
             <!--
  
       					<a onclick="my_sort('greater_buy_rate_fee','asc')" href="javascript:void(0)"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>
    			
    Greater Buy Rate Fee
    
         					<a onclick="my_sort('greater_buy_rate_fee','desc')" href="javascript:void(0)"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a>
    			
    --></td>
   
 <td> 
   <?php echo $appCommon->show_order('sell_rate',' Sell Rate') ?>
 
 	<!--<a onclick="my_sort('sell_rate','asc')" href="javascript:void(0)"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>
 Sell Rate
 	<a onclick="my_sort('sell_rate','desc')" href="javascript:void(0)"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a>-->
 </td>
 
 
 	<td  >
 	<?php echo $appCommon->show_order('less_sell_rate_fee',' Less Sell Rate Fee&nbsp') ?>
 	<!--<a onclick="my_sort('less_sell_rate_fee','asc')" href="javascript:void(0)"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a> 
 			Less Sell Rate Fee&nbsp;
 	
 		<a onclick="my_sort('less_sell_rate_fee','desc')" href="javascript:void(0)"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a>--></td>
 <td>
 <?php echo $appCommon->show_order('greater_sell_rate_fee','Greater Sell Rate Fee') ?>
 <!--
  	<a onclick="my_sort('greater_sell_rate_fee','asc')" href="javascript:void(0)"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>
  Greater Sell Rate Fee 
 	<a onclick="my_sort('greater_sell_rate_fee','desc')" href="javascript:void(0)"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a>-->
 	</td>
 
   <td class="last">&ensp; &ensp; <?php echo __('action')?>&ensp; &ensp; </td>
   
    
    
		</tr>
			</thead>
			<?php 	$mydata =$p->getDataArray();	$loop = count($mydata); for ($i=0;$i<$loop;$i++) {?>
			
			
					<tr>
		    <td  align="center">
		    
		       <a  href="<?php echo $this->webroot?>clients/edit/<?php    echo $mydata[$i][0]['service_charge_id']?>">
		    	<?php echo $mydata[$i][0]['service_charge_id']?>	
		    		</a>
		    </td>
		    
		    <td  align="center"  >   <a  href="<?php echo $this->webroot?>clients/edit/<?php echo $mydata[$i][0]['service_charge_id']?>">
		    	<?php echo $mydata[$i][0]['name']?>	
		    		</a></td >
		   		  
		     <td >
		     <?php   $my_pi = number_format($mydata[$i][0]['buy_rate'], 3);  echo  $my_pi;?>
		    
		     </td>
		  
		     <td >
		     <?php   $my_pi = number_format($mydata[$i][0]['less_buy_rate_fee'], 3);  echo  $my_pi;?></td>


		     <td >
		     <?php   $my_pi = number_format($mydata[$i][0]['greater_buy_rate_fee'], 3);  echo  $my_pi;?></td>
		
		       <td >
		     <?php   $my_pi = number_format($mydata[$i][0]['sell_rate'], 3);  echo  $my_pi;?></td>
		
       <td >
		     <?php   $my_pi = number_format($mydata[$i][0]['less_sell_rate_fee'], 3);  echo  $my_pi;?></td>
		
		
		      <td >
		     <?php   $my_pi = number_format($mydata[$i][0]['greater_sell_rate_fee'], 3);  echo  $my_pi;?></td>
                <td class="last" style="width:100px;">
  <label>
 <?php if ($w == true) {?>

    
       <a title="<?php echo __('edit')?>"  href="<?php echo $this->webroot?>servicecharges/edit/<?php echo $mydata[$i][0]['service_charge_id']?>">
      <i class="icon-edit"></i></a>
    <a title="<?php echo __('del')?>"  href="<?php echo $this->webroot?>servicecharges/del/<?php echo $mydata[$i][0]['service_charge_id']?>">
    <i class="icon-remove"></i></a><?php }?>
       </label>
          </td>
          

				</tr>
				


				<?php }?>

	</table>
	</div>


<?php //*****************************************循环输出的动态部分*************************************?>	

	

			<div id="tmppage">
<?php echo $this->element('page');?>

</div>
<?php }?>
</div>


<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	
<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">
	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>
	



<script type="text/javascript">

var _ss_ids_rate = {'id_rates': 'query-id_rates', 'id_rates_name': 'query-id_rates_name',	'account': 'query-account', 'tz': 'query-tz', 'id_currencies': 'query-id_currencies'};


</script>
