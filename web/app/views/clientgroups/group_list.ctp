
<div id="cover"></div>
<?php $w = $session->read('writable')?>
<div id="title">
  <h1><?php echo __('manage')?>&gt;&gt;
    	<?php echo __('clientgruplist')?>
   <!--  <a title="add to smartbar" href="/admin/_view/sbAdd?link=/rate_tables/list">
   	<img width="10" height="10" alt="+" src="<?php echo $this->webroot?>images/qb-plus.png" />
   </a>-->
  </h1>
  <ul id="title-search">
    <li>
	    	<form>
	    		<input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch')?>" value="<?php if (!empty($search)) echo $search;?>" name="search">
	    	</form>
    </li>
    <!--  <li title="advanced search »" onclick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>-->
  </ul>
    
  <ul id="title-menu">
  <?php if (isset($edit_return)) {?>
        <li>
    			<a class="link_back" href="<?php echo $this->webroot?>clientgroups/group_list">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('gobackall')?>
    			</a>
    		</li>
        <?php }?>
    <?php if ($w == true) {?><li><a class="link_btn" href="<?php echo $this->webroot?>clientgroups/add_client_group"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"> <?php echo __('createnew')?></a></li><?php }?>
    
    <li><a class="link_btn" rel="popup" href="javascript:void(0)" onclick="deleteAll('<?php echo $this->webroot?>clientgroups/del_client_group/all');" ><i class="icon-remove"></i> <?php echo __('deleteall')?></a></li>
    <li><a class="link_btn" rel="popup" href="javascript:void(0)" onclick="deleteSelected('producttab','<?php echo $this->webroot?>clientgroups/del_client_group/selected');"><i class="icon-remove"></i> <?php echo __('deleteselected')?></a></li>
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
	<col style="width: 5%;">
	<col style="width: 13%;">
	<col style="width: 10%;">
	<col style="width: 32%;">
	<col style="width: 23%;">
	<thead>
		<tr>
		<td><input type="checkbox" onclick="checkAllOrNot(this,'producttab');" value=""/></td>
    <td><a href="javascript:void(0)" onclick="my_sort('client_group_id','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;ID&nbsp;<a href="javascript:void(0)" onclick="my_sort('client_group_id','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('name','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('groupname')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('name','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('rate','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('rate')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('rate','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td>&nbsp;<?php echo __('invoicenote')?>&nbsp;</td>
    <td class="last"><?php echo __('action')?></td>
		</tr>
	</thead>
	<tbody id="producttab">
		<?php 
			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			for ($i=0;$i<$loop;$i++) {?>
				<tr class="row-1">
				<td style="text-align:center"><input type="checkbox" value="<?php echo $mydata[$i][0]['client_group_id']?>"/></td>
		    <td><?php echo $mydata[$i][0]['client_group_id']?></td>
		    <td style="font-weight: bold;"><?php echo $mydata[$i][0]['name']?></td>
		    <td><?php echo $mydata[$i][0]['rate']?></td>
		    <td><?php echo $mydata[$i][0]['invoice_note']?></td>
		    <td >
		    	<?php if ($w == true) {?>
		    		<a title="<?php echo __('edit')?>" style="float:left;margin-left:110px;" href="<?php echo $this->webroot?>clientgroups/edit_client_group/<?php echo $mydata[$i][0]['client_group_id']?>">
		    			<i class="icon-edit"></i>
		    		</a>
		    		<a title="<?php echo __('del')?>" style="float:left;margin-left:20px;" href="javascript:void(0)" onclick="delConfirm(this,'<?php echo $this->webroot?>clientgroups/del_client_group/<?php echo $mydata[$i][0]['client_group_id']?>');">
		    			<i class="icon-remove"></i>
		    		</a>
		    		<?php }?>
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