
	<div id="title">
    <h1><?php echo __('newclientgroup')?></h1>
    <ul id="title-menu">
    		<li>
    			<a class="link_back" href="<?php echo $this->webroot?>clientgroups/group_list">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('goback')?>
    			</a>
    		</li>
  		</ul>
	</div>
	
	<div class="container">
		<form method="post" action="<?php echo $this->webroot?>clientgroups/add_client_group">
			<table class="form" style="margin-left:15%;">
				<tbody>
					<tr>
					   <td class="label label2"><?php echo __('groupname')?>:</td>
					   <td class="value value2">
					    		<input type="text" style="float:left;width:300px;" id="name" value="" name="name" class="input in-text">
					   </td>
					</tr>
					
					<tr>
					   <td class="label label2"><?php echo __('memebercallrate')?>:</td>
					   <td class="value value2">
					    		<select id="rate_table_id" name="rate_table_id" style="float:left;width:300px;">
					    		<?php for ($i=0;$i<count($rates);$i++) {?>
					    				<option value="<?php echo $rates[$i][0]['rate_table_id']?>"><?php echo $rates[$i][0]['name']?></option>
					    		<?php }?>
					    		</select>
					   </td>
					</tr>
					
					<tr>
					   <td class="label label2"><?php echo __('invoicenote')?>:</td>
					   <td class="value value2">
					    		<textarea id="invoice_note" name="invoice_note" style="width:300px;float:left;"></textarea>
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
