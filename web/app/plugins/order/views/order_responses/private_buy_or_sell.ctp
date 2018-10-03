<style>
.list tbody .row-active td {
	background:none;
}
.list tbody .row-hover td {
	background:none;
}
</style>
<div id="title">
	<h1>Private <?php echo Inflector::humanize($do_action)  ?></h1>
</div>
<div class="container">
<?php if (isset ( $exception_msg ) && $exception_msg) :	?>
	<?php	echo $this->element ( 'common/exception_msg' );?>		
<?php else : ?>		
	<?php echo $this->element ( "order_responses/order_table" )?>
	<?php echo $this->element ( "order_responses/order_code" )?>
	<?php echo $this->element ( "order_responses/private_order_list" )?>
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
<?php endif;?>
</div>
