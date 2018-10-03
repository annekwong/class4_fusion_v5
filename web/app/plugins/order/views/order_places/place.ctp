<?php if (isset ( $exception_msg ) && $exception_msg) :	?>
	<?php	echo $this->element ( 'common/exception_msg' );?>		
<?php else : ?>		
<div id="title">
	<h1><?php if($do_action=='buy')echo 'Buy ' ;else echo 'Sell'?>&gt;&gt;Place <?php echo Inflector::humanize($do_action)?></h1>
</div>
	<div class="container"> 
		<form id="OrderForm" method="POST" action="" onsubmit="return App.Order.OrderPlaces.onOrderSubmit()" autocomplate="off">
			<?php echo $this->element("order_places/order_form")?>
			<center><input type="submit" value="submit" /></center>
		</form>
	</div>
	<script type="text/javascript">
	(function($){
			$(document).ready(function(){
	
				var bindRegionEvent = function(){
					$("#regions input[name='data[Order][Regions][]']").checkAll(function(region){
						return "#regions input[name='data[OrderCode]["+region.attr('rel')+"][Codes][]']";
					});
				}
				bindRegionEvent();
				$('#Country').bind('change',function(){				
					App.Common.updateDivByAjax(App.WEBROOT + "/order/order_places/ajax_regions","#regions",{'is_update':'<?php echo $is_update?>','country':this.value,'order_id':'<?php echo array_keys_value($this->data,"$table.id")?>'},{success: function(){
						bindRegionEvent();
					}});
				});
				jQuery('#BuyOrderRate,#SellOrderRate').xkeyvalidate({type:'Money'});		
				<?php $order_country = array_keys_value($this->data,"$table.country")?>
				<?php if(empty($order_country)):?>
					App.Common.updateDivByAjax(App.WEBROOT + "/order/order_places/ajax_regions","#regions",{'country':$('#Country').val(),'order_id':'<?php echo array_keys_value($this->data,"$table.id")?>'},{success: function(){
						bindRegionEvent();
					}});
					
				<?php endif;?>	
			});
	}
)(jQuery);
	</script>		
<?php endif;?>

