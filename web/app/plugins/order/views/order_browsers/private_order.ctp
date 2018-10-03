<div id="title">
	<h1><?php echo Inflector::humanize($do_action)?>&gt;&gt;Browser Private <?php echo Inflector::humanize($do_action)?></h1>	
</div>
<div class="container" id="order_list">
<div id="toppage"></div>
	<div style="margin-top:10px">
		<div id="filter_div" style="float:left;width:50%;">
		<form action="" method="POST">
			<?php echo $appOrderBrowsers->filter_country()?>&nbsp;&nbsp;&nbsp;&nbsp;
			<?php echo $appOrderBrowsers->filter_asr()?>&nbsp;&nbsp;&nbsp;&nbsp;
			<?php echo $appOrderBrowsers->filter_acd()?>&nbsp;&nbsp;&nbsp;&nbsp;
			<?php echo $form->submit('',array('label'=>false,'div'=>false,'class'=>"input in-submit"))?>
		</form>
		</div>
		<div id="optional_col" style="text-align:right;float:right;width:50%;">
			<span><input type="checkbox" value="asr" id="optional_col_asr" <?php echo $appOrderBrowsers->show_order_list_col_show('asr',true) ? 'checked' : '';?>><label for="optional_col_asr"><?php echo __('asr',true);?></label></span>
			<span><input type="checkbox" value="acd" id="optional_col_acd" <?php echo $appOrderBrowsers->show_order_list_col_show('acd',true) ? 'checked' : '';?> ><label for="optional_col_acd"><?php echo __('acd',true);?></label></span>
			<span><input type="checkbox" value="cli" id="optional_col_cli" <?php echo $appOrderBrowsers->show_order_list_col_show('cli',false) ? 'checked' : '';?>><label for="optional_col_cli"><?php echo __('cli',true);?></label></span>
			<span><input type="checkbox" value="g729" id="optional_col_g729" <?php echo $appOrderBrowsers->show_order_list_col_show('g729',false) ? 'checked' : '';?>><label for="optional_col_g729"><?php echo __('G729',true);?></label></span>
			<span><input type="checkbox" value="fax" id="optional_col_fax" <?php echo $appOrderBrowsers->show_order_list_col_show('fax',false) ? 'checked' : '';?>><label for="optional_col_fax"><?php echo __('fax',true);?></label></span>
			<span><input type="checkbox" value="create_time" id="optional_col_create_time" <?php echo $appOrderBrowsers->show_order_list_col_show('create_time',true) ? 'checked' : '';?> ><label for="optional_col_create_time"><?php echo __('start_time',true);?></label></span>
			<!--span><input type="checkbox" value="update_time" id="optional_col_update_time" <?php echo $appOrderBrowsers->show_order_list_col_show('update_time',true) ? 'checked' : '';?>><label for="optional_col_update_time"><?php echo __('update_time',true);?></label></span-->
		</div>
	</div>	
	<?php echo $this->element("order_browsers/order_list",array('is_private' => true))?>
	<div id="tmppage">
	<?php echo $this->element('page');?>
	</div>
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