<div id="title" style="height: 65px;">
	<ul>
	<h1 style="margin-bottom: 10px;"><?php if($do_action=='buy')echo 'Buy' ;else echo 'Sell'?> &gt;&gt; <?php __('Manage')?><?php echo Inflector::humanize($do_action)?> <?php __('order')?></h1></ul>

		
		<li style="margin-top: 45px;text-align: right; list-style-type:none">
			<div id="optional_col" style="text-align:right; display: inline; ">
				<span><input type="checkbox" value="is_commit" id="optional_col_commit" <?php echo $appOrderManages->show_order_list_col_show('is_commit',true) ? 'checked' : '';?> ><label for="optional_col_commit"><?php __('commit')?></label></span>
				<span><input type="checkbox" value="commit_minutes" id="optional_col_commit_minutes" <?php echo $appOrderManages->show_order_list_col_show('commit_minutes',true) ? 'checked' : '';?> ><label for="optional_col_commit_minutes"><?php __('commit minutes')?></label></span>
				<span><input type="checkbox" value="acd" id="optional_col_acd" <?php echo $appOrderManages->show_order_list_col_show('acd',true) ? 'checked' : '';?> ><label for="optional_col_acd"><?php echo __('acd',true);?></label></span>
				<span><input type="checkbox" value="asr" id="optional_col_asr" <?php echo $appOrderManages->show_order_list_col_show('asr',true) ? 'checked' : '';?>><label for="optional_col_asr"><?php echo __('asr',true);?></label></span>
				<span><input type="checkbox" value="cli" id="optional_col_cli" <?php echo $appOrderManages->show_order_list_col_show('cli',false) ? 'checked' : '';?>><label for="optional_col_cli"><?php echo __('cli',true);?></label></span>
				<span><input type="checkbox" value="g729" id="optional_col_g729" <?php echo $appOrderManages->show_order_list_col_show('g729',false) ? 'checked' : '';?>><label for="optional_col_g729"><?php echo __('G729',true);?></label></span>
				<span><input type="checkbox" value="fax" id="optional_col_fax" <?php echo $appOrderManages->show_order_list_col_show('fax',false) ? 'checked' : '';?>><label for="optional_col_fax"><?php echo __('fax',true);?></label></span>
				<span><input type="checkbox" value="create_time" id="optional_col_create_time" <?php echo $appOrderManages->show_order_list_col_show('create_time',true) ? 'checked' : '';?> ><label for="optional_col_create_time"><?php echo __('start_time',true);?></label></span>
				<span><input type="checkbox" value="update_time" id="optional_col_update_time" <?php echo $appOrderManages->show_order_list_col_show('update_time',true) ? 'checked' : '';?>><label for="optional_col_update_time"><?php echo __('update_time',true);?></label></span>
				<span><input type="checkbox" value="expire_time" id="optional_col_expire_time" <?php echo $appOrderManages->show_order_list_col_show('expire_time',true) ? 'checked' : '';?>><label for="optional_col_expire_time"><td><?php echo __('Expire Time',true);?></td></label></span>
			</div>
		</li>
	</ul>	
</div>
<div class="container" id="order_list" style="margin-top: 0px;">
	<?php echo $this->element("order_manages/order_list")?>
</div>
<script type="text/javascript">
(function($){
		$(document).ready(function(){
			$('#optional_col input[type=checkbox]').bind('click',function(){
				if(this.checked){
					$("td[rel=order_list_col_"+this.value+"]").show();
				}else{
					$("td[rel=order_list_col_"+this.value+"]").hide();
				}
				var val = this.checked ? 'true' : 'false';
				var col = this.value;
				App.Common.updateDivByAjax("<?php echo Router::url(array('plugin'=> $this->plugin,'controller'=>$this->params['controller'],'action'=>'ajax_def_col'))?>","none",{'action':'browsers','col_name':col,'value':val});
			});	
		});
	}
)(jQuery);
</script>
