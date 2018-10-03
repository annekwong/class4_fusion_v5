
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
		 <script type="text/javascript" src="<?php echo $this->webroot?>js/My97DatePicker/WdatePicker.js"></script>
    
    <script type="text/javascript">
    //<![CDATA[
    var currentTime = 1278411630;
    var L = {"loadingPanel":"Please Wait...","deleteConfirm":"Are you sure to delete this item?","hide-all":"hide all"};
    //]]>
    </script>
    
    
    <script type="text/javascript">
    		function checkForm(){
        var tab1 = document.getElementById("giftamounttab");
        var errorArr = [];
        var amount_reg = /^[0-9]+(\.[0-9]{1,3})?$/;
        for (var i = 1;i<tab1.rows.length;i++) {
            var r = tab1.rows[i];
            if (!r.cells[0].getElementsByTagName("input")[0].value){
              errorArr.push(getMessage('giftamounttab')+":"+getMessage('di')+i+getMessage('row')+":"+getMessage('enterrefillamount'));
            } else {
               if (!amount_reg.test(r.cells[0].getElementsByTagName("input")[0].value)){
            	   errorArr.push(getMessage('giftamounttab')+":"+getMessage('di')+i+getMessage('row')+":"+getMessage('refillamountformaterror'));
                   						}	
            					}

			if (!r.cells[1].getElementsByTagName("input")[0].value
						&& r.cells[2].getElementsByTagName("input")[0].value
						&& r.cells[3].getElementsByTagName("input")[0].value
					) {
				errorArr.push(getMessage('giftamounttab')+":"+getMessage('di')+i+getMessage('row')+":"+getMessage('allnullerror'));
				} else {
						if (r.cells[1].getElementsByTagName("input")[0].value){
							if (!amount_reg.test(r.cells[1].getElementsByTagName("input")[0].value)) {
								errorArr.push(getMessage('giftamounttab')+":"+getMessage('di')+i+getMessage('row')+":"+getMessage('basicamountformaterror'));
							}
						}

						if (r.cells[2].getElementsByTagName("input")[0].value){
							if (!amount_reg.test(r.cells[2].getElementsByTagName("input")[0].value)) {
								errorArr.push(getMessage('giftamounttab')+":"+getMessage('di')+i+getMessage('row')+":"+getMessage('giftamountformaterror'));
							} else {
									if (r.cells[4].getElementsByTagName("select")[0].value == ""){
										errorArr.push(getMessage('giftamounttab')+":"+getMessage('di')+i+getMessage('row')+":"+getMessage('giftrt'));
									}
							}
						}

						if (r.cells[3].getElementsByTagName("input")[0].value){
							if (!/^\d+$/.test(r.cells[3].getElementsByTagName("input")[0].value)) {
								errorArr.push(getMessage('giftamounttab')+":"+getMessage('di')+i+getMessage('row')+":"+getMessage('scoreintergeronly'));
							}
						}
				}
           			 }

		 var tab2 = document.getElementById("scoretab");

		 for (var i = 1;i<tab2.rows.length;i++) {
			 	var r = tab2.rows[i];
			 	if (!r.cells[0].getElementsByTagName("input")[0].value) {
			 		errorArr.push(getMessage('scoretoamounttab')+":"+getMessage('di')+i+getMessage('row')+":"+getMessage('enterscore'));
				 } else {
						if (!/^\d+$/.test(r.cells[0].getElementsByTagName("input")[0].value)) {
							errorArr.push(getMessage('scoretoamounttab')+":"+getMessage('di')+i+getMessage('row')+":"+getMessage('scoreintergeronly'));
						}
					}

				if (!r.cells[1].getElementsByTagName("input")[0].value){
					errorArr.push(getMessage('scoretoamounttab')+":"+getMessage('di')+i+getMessage('row')+":"+getMessage('enteramountofscore'));
				} else {
					if (!amount_reg.test(r.cells[1].getElementsByTagName("input")[0].value)){
						errorArr.push(getMessage('scoretoamounttab')+":"+getMessage('di')+i+getMessage('row')+":"+getMessage('scoretoamountform'));
					}
				}
		}

		 if (errorArr.length > 0){
			 for (var i = 0;i<errorArr.length;i++){
				 	jGrowl_to_notyfy(errorArr[i],{theme:'jmsg-alert'});
			   }
			 	return false;
			 }

		 
        		}


    		function getMessage(key){
        		var msg = {
        				enterrefillamount : "<?php echo __('enterrefillamount')?>",
        				di : "<?php echo __('di')?>",
        				row : "<?php echo __('row')?>",
        				refillamountformaterror : "<?php echo __('refillamountformaterror')?>",
        				allnullerror : "<?php echo __('allnullerror')?>",
        				basicamountformaterror : "<?php echo __('basicamountformaterror')?>",
        				giftamountformaterror : "<?php echo __('giftamountformaterror')?>",
        				scoreintergeronly : "<?php echo __('scoreintergeronly')?>",
        				giftrt : "<?php echo __('giftrt')?>",
        				enteramountofscore : "<?php echo __('enteramountofscore')?>",
        				scoretoamountform : "<?php echo __('scoretoamountform')?>",
        				enterscore : "<?php echo __('enterscore')?>",
        				giftamounttab : "<?php echo __('giftamounttab')?>",
        				scoretoamounttab : "<?php echo __('scoretoamounttab')?>"
                		};
        		return msg[key];
        		}
    </script>

	<div id="title">
    <h1><?php echo __('addsalesstrategy')?></h1>
    <ul id="title-menu">
    		<li>
    			<a class="link_back" href="<?php echo $this->webroot?>salestrategs/view">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png"/>
    				&nbsp;<?php echo __('goback')?>
    			</a>
    		</li>
  		</ul>
	</div>
<div class="container"> 

	<?php echo $form->create ('Salestrateg', array ('action' => 'add' ,'onsubmit'=>'return checkForm();' ));?>

<table class="cols"><col width="40%"/><col width="60%"/><tr>
<td><!-- COLUMN 1 -->

<fieldset><legend></legend>
<table class="form">


<tr>
    <td><?php echo __('route_name')?>:</td>
    <td>
   		<?php echo $form->input('name',
 		array('label'=>false ,'div'=>false,'type'=>'text'));?>
    </td>
</tr>


<tr>
    <td><?php echo __('ratetableafterpayment')?>:</td>
    <td>
   		<?php 
   		
   	
   		echo $form->input('new_rate_table_id',
 		array('options'=>$ratetable,'empty'=>__('selectratetable',true),'label'=>false ,
 	
 		'div'=>false,'type'=>'select'));?>
    </td>
</tr>

<tr>
    <td><?php echo __('paymentexpiredratetable')?>:</td>
    <td>
   		<?php 
   		
   	
   		echo $form->input('expire_rate_table_id',
 		array('options'=>$ratetable,'empty'=>__('selectratetable',true),'label'=>false ,
 	
 		'div'=>false,'type'=>'select'));?>
    </td>
</tr>

<tr>
    <td><?php echo __('oldcustomeruser')?>:</td>
    <td>
       		<?php 
       		
       		$pw=array("true"=>__('yes',true),"false"=>__('no',true));
       		echo $form->input('regular_customer',
 		array('options'=>$pw,'label'=>false ,
 	
 		'div'=>false,'type'=>'select'));?>
    </td>
</tr>




<tr>
    <td><?php echo __('newcustomeruser')?>:</td>
    <td>
       		<?php 
       		
       		$pw=array("true"=>__('yes',true),"false"=>__('no',true));
       		echo $form->input('new_customer',
 		array('options'=>$pw,'label'=>false ,
 	
 		'div'=>false,'type'=>'select'));?>
    </td>
</tr>


<!--  <tr>
    <td><?php echo __('maxbalance')?>:</td>
    <td>
   		<?php echo $form->input('max_balance',
 		array('label'=>false ,'div'=>false,'type'=>'text','value'=>'0.000'));?>
    </td>
</tr>-->

<tr>
    <td><?php echo __('reward_type')?>:</td>
    <td>
   		<?php 
   		
   	$tmp=array('1'=>__('Each calculation',true),'2'=>__('Cumulative',true));
   		echo $form->input('reward_type',
 		array('options'=>$tmp,'label'=>false ,
 	
 		'div'=>false,'type'=>'select'));?>
    </td>
</tr>


<tr>
    <td><?php echo __('extended_days')?>:</td>
    <td>
   		<?php echo $form->input('extended_days',
 		array('label'=>false ,'div'=>false,'type'=>'text','maxlength'=>'3','style'=>'float:left;width: 237px;'));?><?php __('days')?>
    </td>
</tr>

<tr>
    <td><?php echo __('start_time',true);?>:</td>
    <td>
    			<input id="SalestrategStartTime" class="Wdate" name="data[Salestrateg][start_time]" value="<?php echo date('Y-m-d H:i:s',time()+6*60*60) ?>" readonly onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
    </td>
</tr>

<tr>
    <td><?php echo __('end_time',true);?>:</td>
    <td>
    			<input id="SalestrategEndTime" class="Wdate" name="data[Salestrateg][end_time]"  readonly onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
    </td>
</tr>
</table>

</fieldset>



<!-- / COLUMN 1 --></td><td><!-- COLUMN 2 -->
<?php //***************************************对接设置************************************************************?>
<fieldset><legend> 
    <a class="orange" onclick="javascript:creategift_amount();" href="javascript:;"   title="<?php echo __('addgiftGoupon')?>">
     <img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"/><?php echo __('addgiftGoupon')?></a>
</legend>
<table id="giftamounttab" cellspacing="0" cellpadding="0" width="100%"  >
			<tbody><tr>
		
				<td height="25px" class="leftAlign1 rightBorder"><?php echo __('refill_amount')?>(<?php __('money')?>)</td>
				<td class="leftAlign rightBorder"><?php echo __('gift',true).__('basic_amount',true)?>(<?php __('money')?>)</td>
				<td class="leftAlign rightBorder"><?php echo __('gift',true).__('gift_amount',true)?>(<?php __('money')?>)</td>
				<td class="leftAlign rightBorder"><?php echo __('bonus_credit',true)?>(<?php __('a')?>)</td>
			
				<td class="leftAlign rightBorder"  style="width: 28px;"></td>
				
			</tr>

					<tr>

						<td height="28px" class="leftAlign rightBorder">
						<input style="width:100px;" type="text" name="refill_amount[]" class="digit0 input in-text"  id="refill_amount0" /></td>
						
						
						<td class="leftAlign rightBorder">
						<input style="width:100px;" type="text" class="match0 input in-text" name="basic_amount[]"  id="basic_amount0"/></td>
						
						
						<td class="leftAlign rightBorder">
						<input style="width:100px;" type="text" class="match0 input in-text" name="gift_amount[]"  id="gift_amount0"/></td>

						<td class="leftAlign rightBorder">
						
						<input style="width:100px;" type="text" name="bonus_credit[]" class="digit0 input in-text"  id="bonus_credit0" /></td>
						  

						<td align="left" width="60px" class="backGround leftAlign  topBorder"><!--
						<a onclick="deletegift_amount(this);" href="javascript:;" class=" resource_add_Edit_style_24">删除</a>--></td>
					</tr>
					
							</tbody>
							
							<tbody id="gift_amounttimeBody"></tbody>
			<tbody>
		</tbody></table>
</fieldset>


<fieldset><legend> 
    <a class="orange" onclick="javascript:creategift_point();" href="javascript:;"   title="<?php echo __('addpointsto')?>">
     <img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"/><?php echo __('addpointsto')?></a>
</legend>
<table id="scoretab" cellspacing="0" cellpadding="0" width="100%"  style="width: 582px;">
			<tbody><tr>
		
				<td height="25px" class="leftAlign1 rightBorder"><?php echo __('bonus',true)?>(<?php __('a')?>)</td>
				<td class="leftAlign rightBorder"><?php echo __('gift',true).__('gift_amount',true)?>(<?php __('money')?>)</td>
				<td class="leftAlign rightBorder"  style="width: 28px;"></td>
				
			</tr>

					<tr>

						<td height="28px" class="leftAlign rightBorder">
						<input type="text" name="bonus_credit_point[]" class="digit0 input in-text" size="20" id="bonus_credit_point0" /></td>
						
						<td class="leftAlign rightBorder">
						<input type="text" class="match0 input in-text" name="gift_amount_point[]" size="15" id="gift_amount_point0"/></td>

						  
						<td align="left" width="60px" class="backGround leftAlign  topBorder"><!--
						<a onclick="deletegift_amount(this);" href="javascript:;" class=" resource_add_Edit_style_24">删除</a>--></td>
					</tr>
					
							</tbody>
							
							<tbody id="gift_pointtimeBody"></tbody>
			<tbody>
		</tbody></table>
</fieldset>








<!-- / COLUMN 2 --></td><td><!-- COLUMN 3 -->







<!-- / COLUMN 3 -->
</td></tr></table>

<div id="footer">
            <input type="submit" value="<?php echo __('submit')?>" />

    <input type="reset" value="<?php echo __('reset')?>" />
    </div>
		<?php echo $form->end();?>



</div>
<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
