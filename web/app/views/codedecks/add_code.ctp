
   <script type="text/javascript"><!--
   				//<![CDATA[
							function loadInfo(){
									setup();
									<?php $backform = $session->read('backform');if (!empty($backform)){?>
										document.getElementById("country").value = "<?php echo $backform['country']?>";
										change(1);
										document.getElementById("state").value = "<?php echo $backform['state']?>";
										change(2);
										document.getElementById("city").value = "<?php echo $backform['city']?>";
									<?php }?>
							}
						//]]>
   --></script>
	<div id="title">
    <h1><?php echo __('addcode')?></h1>
    <ul id="title-menu">
    		<li>
    			<a class="link_back" href="<?php echo $this->webroot?>codedecks/codes_list/<?php echo $id?>">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('goback')?>
    			</a>
    		</li>
  		</ul>
	</div>
    <script type="text/javascript">
	jQuery( '#Country').autocomplete({source:'<?php echo $this->webroot?>codedecks/ajax_options?type=country',width:'auto'});
	</script>
	<div class="container">
	
<ul class="tabs">

      <li><a href="<?php echo $this->webroot?>codedecks/codes_list/<?php echo $code_deck_id;?>"><img width="16" height="16" src="<?php echo $this->webroot?>images/list.png"> <?php __('codeslist')?></a></li>
      
    <li class="active" ><a  id="addcode" href="<?php echo $this->webroot?>codedecks/add_code/<?php echo $code_deck_id?>">
    <img width="16" height="16" src="<?php echo $this->webroot?>images/add.png"> <?php echo __('addcode')?></a> 
       </li>
       

      
       <li  ><a href="<?php echo $this->webroot?>uploads/code_deck/<?php echo $code_deck_id?>"><img width="16" height="16" src="<?php echo $this->webroot?>images/import.png"> <?php __('upload')?></a>        </li>
     
     
       <li ><a href="<?php echo $this->webroot?>downloads/code_deck/<?php echo $code_deck_id?>">
       <img width="16" height="16" src="<?php echo $this->webroot?>images/export.png"> <?php __('download')?></a>    </li>
    
       </ul>
	
		<form method="post" action="<?php echo $this->webroot?>codedecks/add_code">
		<input type="hidden" value="<?php echo $id?>" id="code_deck_id" name="code_deck_id"/>
			<table class="form" style="margin-left:15%;">
				<tbody>
					<tr>
					   <td class="label label2"><?php echo __('code')?>:</td>
					   <td class="value value2">
					    		<input type="text" style="float:left;width:300px;" id="code" value="" name="code" class="input in-text" >
					   </td>
					</tr>
					
					<tr>
					   <td class="label label2"><?php echo __('code name')?>:</td>
					   <td class="value value2">
					    		<input type="text" style="float:left;width:300px;" id="name" value="" name="name" class="input in-text" maxLength="16">
					   </td>
					</tr>
					
					<tr>
					    <td class="label label2"><?php echo __('Country',true)?>:</td>
					    <td class="value value2">
				<input type="text" style="float:left;width:300px;" id="Country" value="<?php echo isset($search_value)?$search_value:''?>" name="country" class="input in-text" maxLength="16" autocomplete="off">
	</td>
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
		  	<input type="reset" value="<?php echo __('reset')?>" class="input in-submit">
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



<script type="text/javascript">
<!--
jQuery(document).ready(function(){
	jQuery(document).ready(function(){
		//增加方法
		jQuery('#addcode').click(
			function(){
				jQuery('#list_div').show();
				jQuery('#msg_div').remove();
				var action=jQuery(this).attr('href');
				jQuery('table.list').trAdd({
					action:action,
					ajax:'<?php echo $this->webroot?>codedecks/js_save',
					onsubmit:function(options){return jsAdd.onsubmit(options);}
				});
				return false;
		});
	
});
//-->
</script>




<script type="text/javascript">
<!--
jQuery(document).ready(function(){
    jQuery('#code').xkeyvalidate({type:'Num'});
    jQuery('#name').xkeyvalidate({type:'strNum'});
    jQuery( '#Country').autocomplete({source:'<?php echo $this->webroot?>codedecks/ajax_options?type=country',width:'auto'});
	
});
//-->
</script>
