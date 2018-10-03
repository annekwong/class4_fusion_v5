
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
<div id="container">
<div id="toppage"></div>
<table class="list">
	<col style="width: 4%;">
	<col style="width: 6%;">
	<col style="width: 5%;">
	<col style="width: 5%;">
	<col style="width: 15%;">
	<col style="width: 15%;">
	<col style="width: 10%;">
	<col style="width: 10%;">
	<col style="width: 10%;">
	<col style="width: 10%;">
	<col style="width: 10%;">
	<thead>
		<tr>
		 <td><a href="javascript:void(0)" onclick="my_sort('rate_id','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('id',true);?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('rate_id','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('code','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('prefix')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('code','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('rate','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('Rates')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('rate','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('setup_fee','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('setup_fee')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('setup_fee','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('effective_date','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('effective_date')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('effective_date','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('end_date','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('end_date')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('end_date','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('min_time','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('min_time')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('min_time','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('interval','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('interval')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('interval','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('tf','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('time_profile_id')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('tf','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('seconds','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('seconds')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('seconds','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('grace_time','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('grace_time')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('grace_time','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
		</tr>
	</thead>
	<tbody id="ratetab">
		<?php 
			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			for ($i=0;$i<$loop;$i++) {?>
				<tr class="row-1">
		    <td class="in-decimal" style="text-align:center"><?php echo $mydata[$i][0]['rate_id']?></td>
		    <td style="font-weight: bold;"><?php echo $mydata[$i][0]['code']?></td>
		    <td style="color:green"><?php echo $mydata[$i][0]['rate']?></td>
		    <td style="color:green"><?php echo $mydata[$i][0]['setup_fee']?></td>
		    <td><?php echo $mydata[$i][0]['effective_date']?></td>
		    <td><?php echo $mydata[$i][0]['end_date']?></td>
		    <td><?php echo $mydata[$i][0]['min_time']?></td>
		    <td><?php echo $mydata[$i][0]['interval']?></td>
		    <td><?php echo $mydata[$i][0]['tf']?></td>
		    <td><?php echo $mydata[$i][0]['seconds']?></td>
		    <td><?php echo $mydata[$i][0]['grace_time']?></td>
				</tr>
		<?php }?>
		</tbody>
	<tbody>
</tbody>
</table>
<div id="tmppage">
<?php echo $this->element('page');?>
</div>
</div>