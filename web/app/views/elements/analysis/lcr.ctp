<style type="text/css">
.moreg {
	cursor:pointer;display:block;
}
.moreg {
	cursor:pointer;display:block;
}
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
			<td style="width:12%"><?php echo __('country',true);?></td>
				<td style="width:12%"><?php echo __('name',true);?></td>
				<td style="width:6%"><?php echo __('code',true);?></td>
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
			foreach ($mydata[0] as $val) {
	?>
	<tr valign="top">
		<?php
				foreach($val as $v) {
		?>
		<td><?php echo $v; ?></td>
		<?php
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
jQuery(document).ready(function() {
	$('a.moreg').click(function() {
		var num = $(this).parent().prev().prev().text();
		var url = "<?php echo $this->webroot . 'analysis/getrable/'; ?>" + num;
		window.open (url, 'newwindow', 'height=600, width=700, top=0, left=0, toolbar=no, menubar=no, scrollbars=yes, resizable=no,location=no, status=no') 
	});
	$('a.moree').click(function() {
		var num = $(this).parent().prev().prev().text();
		var url = "<?php echo $this->webroot . 'analysis/getegress/'; ?>" + num;
		window.open (url, 'newwindow', 'height=600, width=700, top=0, left=0, toolbar=no, menubar=no, scrollbars=yes, resizable=no,location=no, status=no') 
	});
});
</script>

<script type="text/javascript">
$(document).ready(function() {
$('#exportcsv').click(function() {
			var url = window.location.href;
			$url = $.base64.encode(url);
			location.href = "<?php echo $this->webroot?>analysis/convert_to_csv/" + $url;
	});
});
</script>
