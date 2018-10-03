
   <script type="text/javascript" src="<?php echo $this->webroot?>js/My97DatePicker/WdatePicker.js"></script>

	<div id="title">
    <h1><?php echo __('editcard')?></h1>
    <ul id="title-menu">
    		<li>
    			<a class="link_back" href="<?php echo $this->webroot?>refillpools/cards_list/<?php echo $card[0][0]['credit_card_series_id']?>">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('goback')?>
    			</a>
    		</li>
  		</ul>
	</div>
	
	<div class="container">
		<form method="post" action="">
		<input type="hidden" value="<?php echo $card[0][0]['credit_card_id']?>" name="credit_card_id" id="credit_card_id"/>
		<input type="hidden" value="<?php echo $card[0][0]['credit_card_series_id']?>" id="credit_card_series_id" name="credit_card_series_id"/>
			<table class="form" style="margin-left:15%;">
				<tbody>
					<tr>
					   <td class="label label2"><?php echo __('card_number')?>:</td>
					   <td class="value value2">
					    		<input type="text" readonly style="float:left;width:300px;" id="card_number" value="<?php echo $card[0][0]['card_number']?>" name="card_number" class="input in-text">
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
					    		<script>document.getElementById("rate").value="<?php echo $card[0][0]['rate']?>";</script>
					   </td>
					</tr>-->
					
					<tr>
					   <td class="label label2"><?php echo __('cardvalue')?>:</td>
					   <td class="value value2">
					    		<input type="text" style="float:left;width:300px;" id="value" value="<?php echo $card[0][0]['value']?>"  name="value" class="input in-text">
					   </td>
					</tr>
					
					<tr>
					   <td class="label label2"><?php echo __('expiredate')?>:</td>
					   <td class="value value2">
					    		<input type="text" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" value="<?php echo $card[0][0]['expire_date']?>" readonly style="float:left;width:300px;" id="expire_date"  name="expire_date" class="input in-text">
					   </td>
					</tr>
					
					<tr>
					   <td class="label label2"><?php echo __('Reseller')?>:</td>
					   <td class="value value2">
					    		<select id="reseller_id" name="reseller_id" style="float:left;width:300px;">
					    		<option value="" selected>--<?php echo __('select')?>--</option>
					    					<?php 
					    							$loop = count($reseller);
					    							for ($i = 0;$i<$loop;$i++) {
					    						?>
					    									<option value="<?php echo $reseller[$i][0]['reseller_id']?>"><?php echo $reseller[$i][0]['name']?></option>
					    					<?php 
					    								}
					    						?>
					    		</select>
					    		<script>document.getElementById("reseller_id").value="<?php echo $card[0][0]['reseller_id']?>";</script>
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
