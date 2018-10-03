
<div id="cover"></div>
<?php $w = $session->read('writable');?>
<div id="title">
  <h1><?php echo __('systemc')?>&gt;&gt;
    	<?php echo __('services')?>
   </a>
  </h1>
  <ul id="title-search">
    <li>
	    	<form>
	    		<input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch')?>" value="<?php if (!empty($search)) echo $search;?>" name="search">
	    	</form>
    </li>
  </ul>
    
  <ul id="title-menu">
   <?php if (isset($edit_return)) {?>
        <li>
    			<a class="link_back" href="<?php echo $this->webroot?>services/services_list">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('gobackall')?>
    			</a>
    		</li>
        <?php }?>
    <li><a class="link_btn" href="<?php echo $this->webroot?>services/add"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"> <?php echo __('createnew')?></a></li>
    
    <!--  <li>
  <form action="<?php echo $this->webroot?>products/upload" enctype="multipart/form-data" method="post">
  <input type="submit" value="Upload" class="input in-button"/>
  			<input type="file" class="input in-text" name="MyFile"/>
  </form>
  	 </li>-->
  </ul>
</div>
<div id="container">
<?php $d = $p->getDataArray();if (count($d) == 0) {?>
<div class="msg"><?php echo __('no_data_found')?></div>
<?php } else {?>
<div id="toppage"></div>
<table class="list">
	<col style="width: 5%;">
	<col style="width: 10%;">
	<col style="width: 12%;">
	<col style="width: 12%;">
	<col style="width: 12%;">
	<col style="width: 12%;">
	<col style="width: 12%;">
	<col style="width: 9%;">
	<thead>
		<tr>
    <td><a href="javascript:void(0)" onclick="my_sort('service_id','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('id',true);?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('service_id','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('service_name','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('service_name')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('service_name','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('cost','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('cc')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('cost','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('effective_amount','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('effective_amount')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('effective_amount','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('status','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('status')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('status','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('reseller','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('Reseller')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('reseller','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td class="last"><?php echo __('billinterval')?></td>
    <td class="last"><?php echo __('action')?></td>
		</tr>
	</thead>
	<tbody id="servicetab">
		<?php 
			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			for ($i=0;$i<$loop;$i++) {?>
				<tr class="row-1">
		    <td><?php echo $mydata[$i][0]['service_id']?></td>
		    <td style="font-weight: bold;"><?php echo $mydata[$i][0]['service_name']?></td>
		    <td style="color:green"><?php echo $mydata[$i][0]['cost']?></td>
		    <td style="color:green"><?php echo empty($mydata[$i][0]['effective_amount'])?'0.000':$mydata[$i][0]['effective_amount']?></td>
		    <td><?php echo $mydata[$i][0]['status']==1?__('active',true):__('inactive',true);?></td>
		    <td><?php echo $mydata[$i][0]['reseller']?></td>
		    <td>
		    			<?php 
		    					echo $mydata[$i][0]['billing_time'];
		    					if ($mydata[$i][0]['billing_type'] == 1) echo __('days',true);
		    					else if ($mydata[$i][0]['billing_type'] == 2) echo __('weekly',true);
		    					else if ($mydata[$i][0]['billing_type'] == 3) echo __('months',true);
		    					echo "/".__('ci',true);
		    				?>
		    </td>
		    <td >
		    		<a title="<?php echo __('edit')?>" style="float:left;margin-left:33px;" href="<?php echo $this->webroot?>services/edit/<?php echo $mydata[$i][0]['service_id']?>">
		    			<i class="icon-edit"></i>
		    		</a>
		    		<a title="<?php echo __('del')?>" style="float:left;margin-left:10px;" href="javascript:void(0)" onclick="delConfirm(this,'<?php echo $this->webroot?>services/del_service/<?php echo $mydata[$i][0]['service_id']?>');">
		    			<i class="icon-remove"></i>
		    			</a>
		    </td>
				</tr>
		<?php }?>		
	</tbody>
	<tbody>
</tbody>
</table>
<div id="tmppage">
<?php echo $this->element('page');?>
</div>
<?php }?>
</div>
<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>