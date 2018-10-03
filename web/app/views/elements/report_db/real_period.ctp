<?php if(isset($is_cdr)): ?>
	<style type="text/css">
		#stats-extra li{
			line-height: 50px;
			display: inline-block;
		}
	</style>
<?php endif; ?>
<ul id="stats-extra"
	style="font-weight: bolder; font-size: 1.1em; color: #6694E3; visibility: visible; height: 18px;">
	<li id="stats-period" style="position: relative; visibility: visible;display: inline-block;">
			<span rel="helptip" class="helptip" id="ht-100012"><?php __('Report Period')?></span>
			<span><?php  echo $start;?></span> &mdash; <span><?php echo $end?></span>
			<span style="width: 20px;">&nbsp;&nbsp;&nbsp;&nbsp;</span>
	</li>
	<?php if(isset($is_cdr)): ?>
		<li class="pull-right" style="line-height:53px;"><span  style="width: 20px;">&nbsp;&nbsp;&nbsp;&nbsp;</span><?php __('Auto Update')?>:
            <label style="top:13px;"class="switch" title ="<?php __('Auto Update');?>">
				<input type="checkbox" class="is_auto_update_cdr" checked/>
                <span class="slider round background-primary"></span>
            </label>
        </li>
	<?php endif; ?>
	<li class="pull-right" id="stats-time">
		<?php __('QueryTime')?>: <?php echo $quey_time?> <?php __('ms')?>
	</li>
	<input type="hidden" name="currentdate" id="currentdate" value="<?php  echo $start;?>" />
</ul>
<?php if(isset($is_cdr)): ?>
	<script type="text/javascript">
		function refresh_cdr(){
			var current_url = window.location.href;
			window.location.href = current_url + '&is_auto_update_cdr=1';
		}
		$(function(){
			var timeID;
			<?php if(isset($_GET['is_auto_update_cdr'])): ?>
			$(".is_auto_update_cdr").attr('checked',true);
			<?php endif; ?>
			var is_auto_update = $(".is_auto_update_cdr").is(":checked");
			if (is_auto_update){
				timeID = window.setInterval('refresh_cdr();', 1000 * 60 );
			}
			$(".is_auto_update_cdr").change(function(){
				var is_auto_update = $(".is_auto_update_cdr").is(":checked");
				if (is_auto_update){
                    $(this).closest('label').find('span').addClass('background-primary');
					refresh_cdr();
				}else{
					clearInterval(timeID);
                    $(this).closest('label').find('span').removeClass('background-primary');
				}

			});
		});
	</script>
<?php endif; ?>