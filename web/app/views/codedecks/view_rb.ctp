    <style type="text/css">
				.width100{width:100px;}
			</style>
<div id="cover"></div> 
<div id="title">
  <h1>
   <?php echo __('Usage Rate Table',true);?>&gt;&gt; 
   <font color="editname" title='Name'> <?php echo empty($name)||$name==''?'':"[".$name."]"?></font>
  </h1>
  <ul id="title-menu">
    		<li>
    			<a class="link_back" href="<?php echo $this->webroot?>codedecks/codedeck_list">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('goback')?>
    			</a>
    		</li>
  		</ul>
</div>
<div id="container">
<?php $d = $p->getDataArray();if (count($d) == 0) {?>
<div class="msg"><?php echo __('no_data_found')?> </div>

<?php } else {?>

<div id="cover"></div>
<div id="toppage"></div>
<table class="list">
	<col style="width: 4%;">
	<col style="width: 6%;">
	<col style="width: 10%;">
	<col style="width: 10%;">

	<col style="width: 10%;">
	<col style="width: 11%;">
	<thead>
		<tr>
		 <td><input type="checkbox" onclick="checkAllOrNot(this,'ratetab');" value=""/></td>
    <td><a href="javascript:void(0)" onclick="my_sort('rate_table_id','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('id',true);?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('rate_table_id','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('name','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('ratetmpname')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('name','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>

    <td><a href="javascript:void(0)" onclick="my_sort('code_deck','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('codedeckname')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('code_deck','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('currency','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('currency')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('currency','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><?php echo __('usage')?></td>
		</tr>
	</thead>
	<tbody id="ratetab">
		<?php 
			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			for ($i=0;$i<$loop;$i++) {?>
				<tr class="row-1" rel="tooltip" id="i_<?php echo $loop-$i?>">
				
				<td style="text-align:center"><input type="checkbox" value="<?php echo $mydata[$i][0]['rate_table_id']?>"/></td>
		    <td class="in-decimal" style="text-align:center"><?php echo $mydata[$i][0]['rate_table_id']?></td>
		    <td style="font-weight: bold;"><?php echo $mydata[$i][0]['name']?></td>
	
		    <td><?php echo $mydata[$i][0]['code_deck']?></td>
		    <td><?php echo $mydata[$i][0]['currency']?></td>
		    <td><?php echo $mydata[$i][0]['client_rate']?></td>
				</tr>
				<tr style="display:none;">
					<td>
							<dl id="i_<?php echo $loop-$i?>-tooltip" class="tooltip">
					    <dd><?php echo  __('createdate')?>:</dd>
					    <dd><?php echo $mydata[$i][0]['create_time']?></dd>
					    <dd><?php echo __('updateat')?>:</dd>
					    <dd><?php echo $mydata[$i][0]['modify_time']?></dd>
							</dl>
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