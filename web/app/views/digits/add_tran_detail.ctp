	<div id="title">
    <h1><?php echo __('adddetail')?></h1>
    <ul id="title-menu">
    		<li>
    			<a href="<?php echo $this->webroot?>digits/translation_details/<?php echo $id?>">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/rerating_queue.png">
    				&nbsp;<?php echo __('goback')?>
    			</a>
    		</li>
  		</ul>
	</div>
	<div class="container">
		<form id="submitForm" method="post" action="<?php echo $this->webroot?>digits/add_tran_detail">
			<input type="hidden" value="<?php echo $id?>" name="translation_id" id="translation_id"/>
			<table class="form"  style="margin-left:15%;">
				<tbody>
					<tr>
					   <td class="label label2"><?php echo __('origani');?>:</td>
					   <td class="value value2">
					    		<input type="text" maxLength="32" style="float:left;width:300px;" id="ani" value="" name="ani" class="input in-text">
					   </td>
					</tr>
					<tr>
						  <td class="label label2"><?php echo __('origdnis')?>:</td>
					   <td class="value value2">
					   	<input type="text" maxLength="32" style="float:left;width:300px;" id="dnis" value="" name="dnis" class="input in-text">
					   </td>
					</tr>
					<tr>
					   <td class="label label2"><?php echo __('translatedani')?>:</td>
					   <td class="value value2">
					   	<input type="text" style="float:left;width:300px;"  maxlength="29" id="action_ani" value="" name="action_ani" class="input in-text">
					   </td>
					</tr>
					<tr>
					  	<td class="label label2"><?php echo __('translateddnis')?>:</td>
					   <td class="value value2"><input type="text" style="float:left;width:300px;" maxlength="29"id="action_dnis" value="" name="action_dnis" class="input in-text"></td>
					</tr>
					<tr>
					    <td class="label label2"><?php echo __('aniaction')?>:</td>
					    <td class="value value2">
					    			<select   style="float:left;width:300px;" name="ani_method" id="ani_method">
					          <option selected="selected" value="0"><?php echo __('ignore')?></option>
					          <option value="1"><?php echo __('compare')?></option>
					          <option value="2"><?php echo __('replace')?></option>
					        </select>
					    </td>
					</tr>
					<tr>
					    <td class="label label2"><?php echo __('dnisaction')?>:</td>
					    <td class="value value2">
					    			<select style="float:left;width:300px;" name="dnis_method" id="dnis_method">
					          <option selected="selected" value="0"><?php echo __('ignore')?></option>
					          <option value="1"><?php echo __('compare')?></option>
					          <option value="2"><?php echo __('replace')?></option>
					        </select>
					    	</td>
					</tr>
				</tbody>
			</table>
			<div id="form_footer">
		  	<input type="submit" value="<?php echo __('submit')?>" class="input in-submit">
		  	<input type="reset" value="<?php echo __('reset')?>" class="input in-submit">
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
					<script type="text/javascript">
						//<![CDATA[
							document.getElementById("<?php echo $k?>").value = "<?php echo $backform[$k]?>";							
						//]]>
					</script>
<?php
			  }
			}
?>

<script type="text/javascript">
  jQuery('#submitForm').submit(
	function(){
		re=true;
   var ani=jQuery('#ani').val();
   var dnis=jQuery('#dnis').val();
   var action_ani=jQuery('#action_ani').val();
   var action_dnis=jQuery('#action_dnis').val();
		if(ani==''){
			jQuery('#ani').error('ANI is not null');
			re=false;
		}
		if(dnis==''){
			jQuery('#dnis').error('DNIS is not null');
			re=false;
		}
		if(action_ani==''){
			jQuery('#action_ani').error('Translated ANI is not null');
			re=false;
		}
		if(action_dnis==''){
			jQuery('#action_dnis').error('Translated DNIS is not null');
			re=false;
		}
//		if(jQuery.ajaxData("<?php echo $this->webroot?>digits/valireport?ani="+ani+"&dnis="+dnis+"&action_ani="+action_ani+"&action_dnis="+action_dnis)=='false'){
//			jQuery.error('The Digit is report !');
//			re=false;
//		}
		return re;
	}
  );
</script>
<script type="text/javascript">
<!--
  jQuery(document).ready(function(){
      jQuery('#ani,#dnis,#action_ani,#action_dnis').xkeyvalidate({type:'Num'});
	   });
//-->
</script>


