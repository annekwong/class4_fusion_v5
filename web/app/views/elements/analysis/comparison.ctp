<style type="text/css">
#expcsv {
	margin:10px;
}
</style>
<?php $mydata =$p->getDataArray();	$loop = count($mydata);
if($loop==0){?>
<center>
<div class="msg"><?php echo __('no_data_found',true);?></div>
</center>
<?php }else{?>
<div id="toppage"></div>
<table class="list">
	<thead>
	<tr>
				<td><?php echo __('country',true);?></td>
				<td><?php echo __('name',true);?></td>
				<td style="width:6%"><?php echo __('code',true);?></td>
				<td><?php echo __('max',true);?></td>
				<td><?php echo __('min',true);?></td>
				<td><?php echo __('avg',true);?></td>
				<?php
					foreach($mydata[1] as $val) {
			
			?>
				<td><?php echo $val; ?></td>
				<?php
					}
				?>
	</tr>
	</thead>
	
	<?php 
			foreach ($mydata[0] as  $key => $val) {
	?>
	<tr valign="top">
		
	
		<?php
				foreach($val as $k => $v) {
						if($k == 'code') {
								foreach($mydata[2][$key] as $math) {
									echo '<td>'.$math.'</td>';
								}
						} else {
		?>
		<td><?php echo $v; ?></td>
		<?php
						}
			}
		?>
	</tr>
	
	
	<?php
		}
	?>
	
	
</table>
<div id="tmppage"><?php echo $this->element('page');?></div>
<div id="expcsv"><input type="button" id="exportcsv" value="Export CSV" /></div>
<?php }?>
<script src="<?php echo $this->webroot?>js/jquery.base64.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
$('#exportcsv').click(function() {
			var url = window.location.href;
			$url = $.base64.encode(url);
			location.href = "<?php echo $this->webroot?>analysis/convert_to_csv/" + $url;
	});
});
</script>
