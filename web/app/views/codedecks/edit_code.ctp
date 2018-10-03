
   	<script type="text/javascript">
   			//<![CDATA[
   	function loadInfo(){
					setup();
					<?php $backform = $session->read('backform');if (!empty($backform)){?>
						document.getElementById("country").value = "<?php echo $backform['country']?>";
						change(1);
						document.getElementById("state").value = "<?php echo $backform['state']?>";
						change(2);
						document.getElementById("city").value = "<?php echo $backform['city']?>";
					<?php } else {?>
						document.getElementById("country").value = "<?php echo $code[0][0]['country']?>";
						change(1);
						document.getElementById("state").value = "<?php echo $code[0][0]['state']?>";
						change(2);
						document.getElementById("city").value = "<?php echo $code[0][0]['city']?>";
					<?php }?>
			}
					//]]>
   	</script>
	<div id="title">
    <h1><?php echo __('editcode')?></h1>
    <ul id="title-menu">
    		<li>
    			<a class="link_back" href="<?php echo $this->webroot?>codedecks/codes_list/<?php echo $code[0][0]['code_deck_id']?>">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('goback')?>
    			</a>
    		</li>
  		</ul>
	</div>
	
	<div class="container">
		<form method="post" action="<?php echo $this->webroot?>codedecks/edit_code">
		<input type="hidden" value="<?php echo $code[0][0]['code_deck_id']?>" id="code_deck_id" name="code_deck_id"/>
		<input type="hidden" value="<?php echo $code[0][0]['code_id']?>" id="code_id" name="code_id"/>
			<table class="form" style="margin-left:15%;">
				<tbody>
					<tr>
					   <td class="label label2"><?php echo __('code')?>:</td>
					   <td class="value value2">
					    		<input type="text" style="float:left;width:300px;" id="code22" value="<?php echo $code[0][0]['code']?>" name="code" class="input in-text">
					   </td>
					</tr>
					
					<tr>
					   <td class="label label2"><?php echo __('code name')?>:</td>
					   <td class="value value2">
					    		<input type="text" style="float:left;width:300px;" id="name" value="<?php echo $code[0][0]['name']?>" name="name" class="input in-text">
					
					
					   </td>
					</tr>
					
					<tr>
					    <td class="label label2"><?php echo __('Country',true)?>:</td>
					    <td class="value value2">
					    
					    					    		<input type="text" style="float:left;width:300px;" id="country" value="<?php echo $code[0][0]['country']?>" name="country" class="input in-text"><!--
					
			  <?php 
    echo $form->input('status',array('options'=>$country,
               'selected'=>$code[0][0]['country'],
    'style'=>'width:300px;','id'=>'country','name'=>'country','label'=>false,'div'=>false,'type'=>'select'))?>
					    --></td>
					</tr><!--
					
					<tr>
					    <td class="label label2"><?php echo __('state')?>:</td>
					    <td class="value value2">
					    			<select style="float:left;width:300px;" name="state" id="state">
					        </select>
					    </td>
					</tr>
					
					<tr>
					    <td class="label label2"><?php echo __('city')?>:</td>
					    <td class="value value2">
					    			<select   style="float:left;width:300px;" name="city" id="city">
					        </select>
					    </td>
					</tr>
				--></tbody>
			</table>

			<div id="form_footer">
		  	<input type="submit" value="<?php echo __('submit')?>" class="input in-submit">
		  	<input type="reset" value="<?php echo __('reset')?>" class="input in-button">
			</div>
			
			</form>
		</div>

<!-- 如果验证没通过  将用户输入的表单信息重新显示 -->
<?php
			if (!empty($backform)) {
				$session->del('backform');//清除错误信息
				//将用户刚刚输入的数据显示到页面上
?>
			<script type="text/javascript">
						//<![CDATA[
							document.getElementById("code").value = "<?php echo $backform['code']?>";							
						//]]>
			</script>
<?php }?>
