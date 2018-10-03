
<div id="cover"></div>
  <div id="title"><h1>
<?php echo __('account')?>&gt;&gt;
    	<?php echo __('payment')?>
  </h1>
  <ul id="title-menu">
    		<li>
    			<a  class="link_back" href="<?php echo $this->webroot?>chargereports/charge_reports">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('goback')?>
    			</a>
    		</li>
  		</ul>
  </div>
<div id="container">
<div id="toppage"></div>
<table class="list">
	<col style="width: 20%;">
	<col style="width: 20%;">
	<col style="width: 20%;">
	<col style="width: 20%;">
	<col style="width: 20%;">
	<thead>
		<tr>
    <td><a href="javascript:void(0)" onclick="my_sort('payment_time','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('payment_time')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('payment_time','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('amount','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('amount')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('amount','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><?php echo __('payment_method')?></td>
    <td><?php echo __('result')?></td>
    <td class="last"><?php echo __('cause')?></td>
		</tr>
	</thead>
	<tbody id="servicetab">
		<?php 
			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			for ($i=0;$i<$loop;$i++) {?>
				<tr class="row-1">
		    <td><?php echo $mydata[$i][0]['payment_time']?></td>
		    <td style="color:green"><?php echo $mydata[$i][0]['amount']?></td>
		    <td>
		    			<?php
		    					if ($mydata[$i][0]['payment_method']==0) echo __('systemadmin');
		    					else if ($mydata[$i][0]['payment_method']==1) echo __('Reseller'); 
		    					else if ($mydata[$i][0]['payment_method']==2) echo __('bycard'); 
		    					else if ($mydata[$i][0]['payment_method']==3) echo __('yeepay'); 
		    					else if ($mydata[$i][0]['payment_method']==4) echo __('kq'); 
		    					else if ($mydata[$i][0]['payment_method']==5) echo __('alipay');
		    					else if ($mydata[$i][0]['payment_method']==6) echo __('PayPal');  
		    				?>
		    </td>
		    <td><?php echo $mydata[$i][0]['result']==1?__('success',true):__('failed',true)?></td>
		    <td><?php echo $mydata[$i][0]['cause']?></td>
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
<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>