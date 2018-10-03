<?php $w = $session->read('writable')?>
<div id="toppage"></div>
<dl id="addproduct" class="tooltip-styled" style="display:none;position:absolute;left:40%;top:30%;z-idnex:99;width:300px;height:100px;">
<dd style="text-align:center;width:100%;height:25px;font-size: 16px;"><?php echo __('addproduct')?></dd>
	<dd style="margin-top:10px;"><?php echo __('produname')?>:<input class="input in-text" id="pname"/></dd>
	<dd style="margin-top:10px; margin-left:25%;width:150px;height:auto;">
		<input type="button" onclick="add('pname','<?php echo $this->webroot?>products/add_product');" value="<?php echo __('submit')?>" class="input in-button">
		<input type="button" onclick="closeCover('addproduct');" value="<?php echo __('cancel')?>" class="input in-button">
	</dd>
</dl>
<div id="swapproduct" style="display:none;background:buttonface;position:absolute;left:40%;top:30%;z-idnex:99;width:400px;height:140px;border:2px solid lightgray;">
	<div style="background:lightblue;width:100%;height:25px;font-size: 16px;"><?php echo __('swaping')?>&nbsp;<span style="color:red;float:right;" id="loading"></</span></div>&nbsp;
	<div style="margin-top:10px;margin-left:10px;">
		<?php echo __('tobeswapped')?>:&nbsp;&nbsp;&nbsp;&nbsp;
		<select style="width:150px;" class="in-select" id="productA">
		</select>
	</div>
	<div style="margin-top:10px;margin-left:10px;">
		<?php echo __('selectapro')?>:&nbsp;&nbsp;&nbsp;&nbsp;
		<select style="width:150px;" class="in-select" id="productB">
		</select>
	</div>
	<div style="margin-top:10px; margin-left:30%;width:150px;height:auto;">
		<input type="button" onclick="swapproducts();" value="<?php echo __('submit')?>" class="input in-button">
		<input type="button" onclick="closeCover('swapproduct');" value="<?php echo __('cancel')?>" class="input in-button">
	</div>
</div>
	<?php $d = $p->getDataArray();if (count($d) == 0) {?>
<div class="msg"><?php echo __('no_data_found')?></div>
<?php } else {?>
<table class="list">
	<col style="width: 10%;">
	<col style="width: 7%;">
	<col style="width: 13%;">
	<col style="width: 22%;">
	<col style="width: 24%;">
	<col style="width: 10%;">
	<col style="width: 14%;">
	<thead>
	<tr>
		<td><input type="checkbox" onclick="checkAllOrNot(this,'producttab');" value=""/></td>
   <td><a href="javascript:void(0)" onclick="my_sort('product_id','asc');"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('id',true);?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('product_id','desc');"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
   <td><a href="javascript:void(0)" onclick="my_sort('name','asc');"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('produname')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('name','desc');"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
   <td><a href="javascript:void(0)" onclick="my_sort('modify_time','asc');"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('updateat')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('modify_time','desc');"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
   <td><a href="javascript:void(0)" onclick="my_sort('routes','asc');"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('ofroutes')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('routes','desc');"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
   <td><a href="javascript:void(0)" onclick="my_sort('ingress','asc');"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('ofingress')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('ingress','desc');"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
   <td class="last"><?php echo __('action')?></td>
	</tr>
	</thead>
	<tbody id="producttab">
		<?php 
			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			for ($i=0;$i<$loop;$i++) {?>
				<tr class="row-1">
					<td style="text-align:center"><input type="checkbox" value="<?php echo $mydata[$i][0]['id']?>"/></td>
		    <td class="in-decimal"  style="text-align: center;"><?php echo $mydata[$i][0]['id']?></td>
		    <td style="font-weight: bold;">
		    		<?php echo $mydata[$i][0]['name']?>
		    </td>
		    <td><?php echo $mydata[$i][0]['m_time']?></td>
		    <td><a href="<?php echo $this->webroot?>products/route_info/<?php echo $mydata[$i][0]['id']?>"><?php echo $mydata[$i][0]['routes']?></a></td>
		    <td align="center"><?php echo $mydata[$i][0]['ingress']?></td>
		    <td >
		    	<?php if ($w == true) {?>	<a title="<?php echo __('edit')?>" style="float:left;margin-left:50px;" href="javascript:void(0)" onclick="modifyName('<?php echo $this->webroot?>',this,'products','<?php echo __('pro_update_success')?>',3);">
		    			<i class="icon-edit"></i>
		    		</a>
		    		<a title="<?php echo __('del')?>" style="float:left;margin-left:20px;" href="javascript:void(0)" onclick="delConfirm(this,'<?php echo $this->webroot?>products/delbyid/<?php echo $mydata[$i][0]['id']?>');">
		    			<i class="icon-remove"></i>
		    		</a><?php }?>
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
