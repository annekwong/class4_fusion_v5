<div id="title"><h1><?php __('Configuration')?>&gt;&gt;<?php echo __('cdrbackup')?></h1></div>
<div id="container">
<?php if (isset ( $exception_msg ) && $exception_msg) :	?>
	<?php	echo $this->element ( 'common/exception_msg' );?>		
<?php else : ?>	
<form method="get" action="">
	<fieldset class="query-box">
	<legend><?php echo __('cdrbackup')?></legend>
		<table class="form" style="text-align: center;">
		<tr>
		<td class="value value2" style="text-align: center;">
		
		<?php echo $appCommon->filter_date_range_ti('start_time_of_date','</td></tr><tr><td style="text-align: center;" class="value value2">&nbsp;&nbsp;')?>
		</td>
		</tr>
		<tr>
		<td class="value value2" style="text-align: center;"">
		<input type="submit" style="width:80px;" value="<?php echo __('startbackup')?>"class="input in-submit" />
		</td>
		</tr>
		</table>
	</fieldset>
</form>
<?php endif;?>
</div>

