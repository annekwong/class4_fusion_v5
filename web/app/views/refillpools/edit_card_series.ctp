
   <script type="text/javascript" src="<?php echo $this->webroot?>js/My97DatePicker/WdatePicker.js"></script>

	<div id="title">
    <h1><?php echo __('editrefillseries')?></h1>
    <ul id="title-menu">
    		<li>
    			<a class="link_back" href="<?php echo $this->webroot?>refillpools/pools_list">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('goback')?>
    			</a>
    		</li>
  		</ul>
	</div>
	
	<div class="container">
		<form method="post" action="">
		<input type="hidden" value="<?php echo $cardpool[0][0]['credit_card_series_id']?>" id="credit_card_series_id" name="credit_card_series_id"/>
			<table class="form" style="margin-left:15%;">
				<tbody>
					<tr>
					   <td class="label label2"><?php echo __('seriesname')?>:</td>
					   <td class="value value2">
					    		<input type="text" style="float:left;width:300px;" id="name" value="<?php echo $cardpool[0][0]['name']?>" name="name" class="input in-text">
					   </td>
					</tr>
					
					<!--  <tr>
					   <td class="label label2"><?php echo __('cardseriesrate')?>:</td>
					   <td class="value value2">
					    		<select id="rate" name="rate" style="float:left;width:300px;">
					    				<?php
					    					$loop = count($rates);
					    					for ($i=0;$i<$loop;$i++){ 
					    					?>
					    							<option value="<?php echo $rates[$i][0]['rate_table_id']?>"><?php echo $rates[$i][0]['name']?></option>
					    				<?php 
					    						}
					    					?>
					    		</select>
					    		<script>document.getElementById("rate").value="<?php echo $cardpool[0][0]['rate']?>";</script>
					   </td>
					</tr>-->
					
					<tr>
					   <td class="label label2"><?php echo __('cardvalue')?>:</td>
					   <td class="value value2">
					    		<input type="text" style="float:left;width:300px;" id="value" name="value" value="<?php echo $cardpool[0][0]['value']?>" class="input in-text">
					   </td>
					</tr>
					
					
					<tr>
					   <td class="label label2"><?php echo __('cardseriesprefix')?>:</td>
					   <td class="value value2">
					    		<input type="text" readonly style="float:left;width:300px;" id="prefix" value="<?php echo $cardpool[0][0]['prefix']?>" name="prefix" class="input in-text">
					   </td>
					</tr>
					
					<!--  <tr>
					   <td class="label label2"><?php echo __('expiretype')?>:</td>
					   <td class="value value2">
					    		<select id="expire_type" name="expire_type" style="float:left;width:200px;">
					    				<option value="1"><?php echo __('fromgenerated')?></option>
					    				<option value="2"><?php echo __('fromstarted')?></option>
					    		</select>
					    		<input tye="text" style="float:left;width:100px;" id="expire_days" value="<?php echo $cardpool[0][0]['expire_days']?>" name="expire_days" class="input in-text"><span style="float:left"><?php echo __('days')?></span>
					    		<script>document.getElementById("expire_type").value="<?php echo $cardpool[0][0]['expire_type']?>";</script>
					   </td>
					</tr>-->
					
					<tr>
					   <td class="label label2"><?php echo __('expiredate')?>:</td>
					   <td class="value value2">
					    		<input type="text" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" value="<?php echo $cardpool[0][0]['expire_date']?>" style="float:left;width:300px;" id="expire_date"  name="expire_date" class="input in-text wdate">
					   </td>
					</tr>
					
					<tr>
					   <td class="label label2"><?php echo __('startnum')?>:</td>
					   <td class="value value2">
					    		<input type="text" style="float:left;width:300px;" id="start_num" value="<?php echo $cardpool[0][0]['start_num']?>" name="start_num" class="input in-text">
					   </td>
					</tr>
					
					<tr>
					   <td class="label label2"><?php echo __('passlen')?>:</td>
					   <td class="value value2">
					    		<input type="text" style="float:left;width:300px;" id="password_length" value="<?php echo $cardpool[0][0]['password_length'] ?>" name="password_length" value="<?php echo $cardpool[0][0]['password_length']?>"  class="input in-text">
					   </td>
					</tr>
				</tbody>
			</table>

			<div id="footer">
		  	<input type="submit" value="<?php echo __('submit')?>" class="input in-submit">
		  	<input type="reset" value="<?php echo __('reset')?>" class="input in-button">
			</div>
			</form>
		</div>

<!-- 如果验证没通过  将用户输入的表单信息重新显示 -->
<?php
			$backform = $session->read('backform');//用户刚刚输入的表单数据
			if (!empty($backform)) {
				$session->del('backform');//清除错误信息
		
				//将用户刚刚输入的数据显示到页面上
				$d = array_keys($backform);
			 foreach($d as $k) {?>
						<script>document.getElementById("<?php echo $k?>").value = "<?php echo $backform[$k]?>";</script>
<?php }?>
<?php }?>
