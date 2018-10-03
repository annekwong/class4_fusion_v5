<div id="title">
  <h1><?php echo __('modifyingdetail')?>&gt;&gt;
  <font class="editname" title="Name">
        <?php echo empty($name)||$name==''?"":"[".$name."]" ?>
  </font>
  </h1>
	<ul id="title-menu">
  	 <li>
    <a class="link_back" href="<?php echo $this->webroot?>digits/translation_details/<?php echo $detail[0][0]['translation_id']?>"> <img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    &nbsp;<?php echo __('goback')?>
  	 		</a>
  	 </li>
  	 </ul>
</div>
	<div class="container">
		<form id="submitForm" method="post" action="<?php echo $this->webroot?>digits/edit_tran_detail">
			<input type="hidden" value="<?php echo $detail[0][0]['translation_id']?>" name="translation_id" id="translation_id"/>
			<input type="hidden" value="<?php echo $detail[0][0]['ref_id']?>" name="ref_id" id="ref_id"/>
			<table class="form" style="margin-left:15%;">
				<tbody>
						<tr>
						    <td class="label label2"><?php echo __('origani')?>:</td>
						    <td class="value value2">
						    		<input type="text" style="float:left;width:300px;" id="ani" value="<?php if (!empty($detail[0][0]['ani'])) echo $detail[0][0]['ani'];?>" name="ani" class="input in-text">
						    </td>
						</tr>
						<tr>
						    <td class="label label2"><?php echo __('origdnis')?>:</td>
						    <td class="value value2"><input type="text" style="float:left;width:300px;" id="dnis" value="<?php if (!empty($detail[0][0]['dnis'])) echo $detail[0][0]['dnis'];?>" name="dnis" class="input in-text"></td>
						</tr>
						<tr>
						    <td class="label label2"><?php echo __('translatedani')?>:</td>
						    <td class="value value2"><input type="text" style="float:left;width:300px;" id="action_ani" value="<?php if (!empty($detail[0][0]['action_ani'])) echo $detail[0][0]['action_ani'];?>" name="action_ani" class="input in-text"></td>
						</tr>
						<tr>
						    <td class="label label2"><?php echo __('translateddnis')?>:</td>
						    <td class="value value2"><input type="text" style="float:left;width:300px;" id="action_dnis" value="<?php if (!empty($detail[0][0]['action_dnis'])) echo $detail[0][0]['action_dnis'];?>" name="action_dnis" class="input in-text"></td>
						</tr>
						<tr>
						    <td class="label label2"><?php echo __('aniaction')?>:</td>
						    <td class="value value2">
						    			<select   style="float:left;width:300px;" name="ani_method" id="ani_method">
						           <option selected="selected" value="0"><?php echo __('ignore')?></option>
						           <option value="1"><?php echo __('compare')?></option>
						           <option value="2"><?php echo __('replace')?></option>
						        </select>
						        <script>selected('ani_method','<?php echo $detail[0][0]['ani_method']?>');//选中下拉框</script>
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
						        <script>selected('dnis_method','<?php echo $detail[0][0]['dnis_method']?>');//选中下拉框</script>
						    	</td>
						</tr>
				</tbody>
		</table>
	
			<div id="form_footer">
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
					<script type="text/javascript">
						//<![CDATA]
							document.getElementById("<?php echo $k?>").value = "<?php echo $backform[$k]?>";
						//]]>
					</script>
<?php 
			  }
			}
?>
<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery('#submitForm').submit(
			function(){
				var id="<?php echo $detail[0][0]['ref_id']?>";
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
				if(jQuery.ajaxData("<?php echo $this->webroot?>digits/valireport?ani="+ani+"&dnis="+dnis+"&action_ani="+action_ani+"&action_dnis="+action_dnis+"&id="+id)=='false'){
					jQuery.error('The Digit is report !');
					re=false;
				}
				return re;
			}
		);
	});
</script>
