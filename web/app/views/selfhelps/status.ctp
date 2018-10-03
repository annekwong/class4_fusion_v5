
    <link href="/static/favicon.ico" type="image/x-icon" rel="shortcut Icon">
    
    <link href="<?php echo $this->webroot?>css/base.css?v-3.0.6" media="all" rel="stylesheet" type="text/css">
    <link href="<?php echo $this->webroot?>css/popup.css?v-3.0.6" media="all" rel="stylesheet" type="text/css">
    <link href="<?php echo $this->webroot?>css/shared.css?v-3.0.6" media="all" rel="stylesheet" type="text/css">
    <link href="<?php echo $this->webroot?>css/jquery.jgrowl.css?v-3.0.6" media="all" rel="stylesheet" type="text/css">
    <link href="<?php echo $this->webroot?>css/print.css?v-3.0.6" media="print" rel="stylesheet" type="text/css">
	<link href="<?php echo $this->webroot?>calendar/calendar.css" type="text/css" rel="stylesheet">
        <link href="<?php echo $this->webroot?>css/styles.css?v-3.0.6" media="all" rel="stylesheet" type="text/css">
        
    <script src="<?php echo $this->webroot?>js/jquery-1.4.1.js" type="text/javascript"></script>
    <script src="<?php echo $this->webroot?>js/jquery.tooltip.js" type="text/javascript"></script>
    <script src="<?php echo $this->webroot?>js/jquery.jgrowl.js" type="text/javascript"></script>
    <script src="<?php echo $this->webroot?>js/bb-functions.js?v-3.0.6" type="text/javascript"></script>
    <script src="<?php echo $this->webroot?>js/bb-interface.js?v-3.0.6" type="text/javascript"></script>
    
    <script type="text/javascript">
    //&lt;![CDATA[
    var currentTime = 1281602534;
    var L = {"loadingPanel":"Please Wait...","deleteConfirm":"Are you sure to delete this item?","hide-all":"hide all"};
    //]]&gt;
    </script> 

<table class="list">
	<col style="width: 12%;">
	<col style="width: 12%;">
	<col style="width: 12%;">
	<col style="width: 12%;">
	<col style="width: 12%;">
	<col style="width: 12%;">
	<col style="width: 12%;">
	<col style="width: 12%;">
	<thead>
		<tr>
    <td>&nbsp;<?php echo __('accountcard')?>&nbsp;</td>
    <td>&nbsp;<?php echo __('balance')?>&nbsp;<?php echo __('RMB',true);?></td>
    <td>&nbsp;<?php echo __('Reseller')?>&nbsp;</td>
    <td>&nbsp;<?php echo __('effective_date')?>&nbsp;</td>
    <td>&nbsp;<?php echo __('expiredate')?>&nbsp;</td>
    <td>&nbsp;<?php echo __('month_cost')?>&nbsp;</td>
    <td>&nbsp;<?php echo __('month_durations')?>&nbsp;</td>
    <td>&nbsp;<?php echo __('bonus')?>&nbsp;</td>
		</tr>
	</thead>
	<tbody id="statustab">
		<tr>
			<td><span style="color:red"><?php echo $status[0][0]['card_number']?></span></td>
			<td><span style="color:green"><?php echo empty($status[0][0]['balance'])?'0.000':$status[0][0]['balance']?></span></td>
			<td><?php echo $status[0][0]['reseller']?></td>
			<td><?php echo date('Y-m-d H:i:s',strtotime($status[0][0]['effective_date']))?></td>
			<td><?php echo date('Y-m-d H:i:s',strtotime($status[0][0]['expire_date']))?></td>
			<td><span style="color:green"><?php echo empty($status[0][0]['month_cost'])?'0.000':$status[0][0]['month_cost']?></span></td>
			<td><?php echo empty($status[0][0]['month_durations'])?'0':$status[0][0]['month_durations']/60?><?php echo __('minutes')?></td>
			<td><span style="color:green"><?php echo empty($status[0][0]['point'])?'0':$status[0][0]['point']?></span></td>
		</tr>
	</tbody>
	<tbody>
</tbody>
</table>
