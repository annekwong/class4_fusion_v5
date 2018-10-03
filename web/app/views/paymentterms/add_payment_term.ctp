<!-- 	<div id="title">
    <h1><?php echo __('newpaymentterm')?></h1>
    <ul id="title-menu">
    		<li>
    			<a class="link_back" href="<?php echo $this->webroot?>paymentterms/payment_term">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('goback')?>
    			</a>
    		</li>
  		</ul>
	</div>
	<div class="container">
		<form method="post" action="">
			<table class="form" style="margin-left:15%;">
				<tbody>
					<tr>
					   <td class="label label2"><?php echo __('termname')?>:</td>
					   <td class="value value2">
					    	<input type="text" id="name" name="name" class="input in-text" maxLength=16>
					   </td>
					</tr>
					<tr>
					   <td class="label label2"><?php echo __('paymenttype')?>:</td>
					   <td class="value value2">
					    		<select id="type" name="type" style="float:left;width:300px;">
					    				<option value="1"><?php echo __('everyxdays')?></option>
					    				<option value="2"><?php echo __('onxdayofmonth')?></option>
					    		</select>
					   </td>
					</tr>
					<tr>
					   <td class="label label2"><?php echo __('date')?>:</td>
					   <td class="value value2">
					    		<input type="text" style="float:left;width:300px;" id="days" value="" name="days" class="input in-text">
					    		<span style="float:left"><?php echo __('days')?></span>
					   </td>
					</tr>
					<tr>
					   <td class="label label2"><?php echo __('gracedays')?>:</td>
					   <td class="value value2">
					    		<input type="text" style="float:left;width:300px;" id="grace_days" value="" name="grace_days" class="input in-text"><span style="float:left"><?php echo __('days')?></span>
					   </td>
					</tr>
					
					<tr>
					   <td class="label label2"><?php echo __('notify')?>:</td>
					   <td class="value value2">
					    		<input type="text" style="float:left;width:300px;" id="notify_days" value="" name="notify_days" class="input in-text"><span style="float:left"><?php echo __('days')?></span>
					   </td>
					</tr>
				</tbody>
			</table>
    		<script type="text/javascript">
    					jQuery(document).ready(
									function(){
										jQuery('#days,#grace_days,#notify_days').keyup(function(){
											while(/[^0-9]/.test(jQuery(this).val()))
									 		{
										 		var val=jQuery(this).val().replace(/[^0-9]+/,'');
										 		jQuery(this).val(val);
										 		showMessages("[{'field':'#select','code':'101','msg':'Can only be number prefix'}]");
										 		jQuery(this).focus();
										 	}
										});
									}
		    				);
				</script>
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
<script type="text/javascript">
<!--
  jQuery(document).ready(function(){
      jQuery('#name').xkeyvalidate({type:'strNum'})
   
     });     
//-->
</script>
 -->
