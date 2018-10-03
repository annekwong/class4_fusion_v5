<link href="<?php echo $this->webroot?>images/favicon.ico" type="image/x-icon" rel="shortcut Icon">
  <script type="text/javascript">
  	//<![CDATA[
  		var webroot = "<?php echo $this->webroot?>";//全局路径
		//]]>
  </script>
	<div id="cover"></div> 
	<ul style="filter:alpha(opacity=70);-moz-opacity:0.7;opacity: 0.7;background:lightgray;border:2px solid lightblue;margin-top:10px;display:none;list-style:none;" id="showMenu">
			<li style="width:120px;height:23px;" onmouseover="bgimage(this)" onmouseout="noimage(this);">
				<a class="link_btn" rel="popup" href="javascript:void(0)" onclick="cover('addtranslation')">
					<img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png">
					<?php echo __('createnew')?>
				</a>
			</li>
		
		<li style="width:120px;height:23px;" onmouseover="bgimage(this)" onmouseout="noimage(this);">
			<a  class="link_btn"rel="popup" href="javascript:void(0)" onclick="deleteAll('<?php echo $this->webroot?>digits/delalltrans');" >
				<i class="icon-remove"></i> 
				<?php echo __('deleteall')?>
			</a>
		</li>
		<li style="width:120px;height:23px;" onmouseover="bgimage(this)" onmouseout="noimage(this);">
			<a class="link_btn" rel="popup" href="javascript:void(0)" onclick="deleteSelected('digittab','<?php echo $this->webroot?>digits/delselectedtrans');">
				<i class="icon-remove"></i>
				<?php echo __('deleteselected')?>
			</a>
		</li>
	</ul>
	<div id="title">
  	<h1><?php __('Routing')?>&gt;&gt;
    <?php echo __('digitmapping')?>      
  	</h1>
  	
  <ul id="title-search">
    <li>
	    	<form>
	    		<input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch')?>" value="<?php if (!empty($search)) echo $search;?>" name="search">
	    	</form>
    </li>
  </ul>
    
    <?php $w = $session->read('writable');?>
  <ul id="title-menu">
    <?php if ($w == true) {?><li><a class="link_btn" rel="popup" href="javascript:void(0)" onclick="cover('addtranslation')"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"><?php echo __('createnew')?></a></li>
    <li><a class="link_bnt" rel="popup" href="javascript:void(0)" onclick="deleteAll('<?php echo $this->webroot?>digits/delalltrans');" ><i class="icon-remove"></i> <?php echo __('deleteall')?></a></li>
    <li><a class="link_btn" rel="popup" href="javascript:void(0)" onclick="deleteSelected('digittab','<?php echo $this->webroot?>digits/delselectedtrans');"><i class="icon-remove"></i> <?php echo __('deleteselected')?></a></li>
    <?php }?>
  </ul>
  
	</div>

	<div id="container">
	<div id="toppage"></div>
		<div id="addtranslation"  class="tooltip-styled" style="z-index:99;display:none;position:absolute;left:40%;top:30%;width:300px;height:100px;">
			<div style="text-align:center;width:100%;height:25px;font-size: 16px;"><?php echo __('addtrans')?></div>
			<div style="text-align:center;margin-top:10px;"><?php echo __('tranname')?>:<input class="input in-text" id="tran_name"/>
			</div>
			<div style="margin-top:10px; margin-left:25%;width:150px;height:auto;">
				<input type="button" onclick="add('tran_name','<?php echo $this->webroot?>digits/add_translation');" value="<?php echo __('submit')?>" class="input in-submit">
				<input type="button" onclick="closeCover('addtranslation');" value="<?php echo __('cancel')?>" class="input in-submit">
			</div>
		</div>
			<?php $d = $p->getDataArray();if (count($d) == 0) {?>
<div class="msg"><?php echo __('no_data_found')?></div>
<?php } else {?>
		<table class="list">
			<col style="width: 8%;">
			<col style="width: 8%;">
			<col style="width: 9%;">
			<col style="width: 20%;">
			<col style="width: 28%;">
			<col style="width: 17%;">
			<thead>
				<tr>
				<td><input type="checkbox" onclick="checkAllOrNot(this,'digittab');" value=""/></td>
		   <td>
		    	<a href="javascript:void(0)" onclick="my_sort('id','asc');">
		    		<img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png">
		    	</a>&nbsp;<?php Echo __('tranid')?>&nbsp;
		    	
		    	<a href="javascript:void(0)" onclick="my_sort('id','desc');">
		    			<img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png">
		    	</a>
		    </td>
		    
		    <td>
		    	<a href="javascript:void(0)" onclick="my_sort('trans','asc');">
		    		<img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png">
		    	</a>&nbsp;<?php echo __('oftrans')?>&nbsp;
		    	
		    	<a href="javascript:void(0)" onclick="my_sort('trans','desc');">
		    			<img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png">
		    	</a>
		    </td>
		    	
		    <td>
		    		<a href="javascript:void(0)" onclick="my_sort('name','asc');">
		    				<img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png">
		    		</a>&nbsp;<?php echo __('tranname')?>&nbsp;
		    		<a href="javascript:void(0)" onclick="my_sort('name','desc');">
		    				<img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png">
		    		</a>
		    </td>
		    
		    <td>
		    		<a href="javascript:void(0)" onclick="my_sort('updateat','asc');">
		    				<img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png">
		    		</a>&nbsp;<?php echo __('updateat')?>&nbsp;
		    		<a href="javascript:void(0)" onclick="my_sort('updateat','desc');">
		    				<img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png">
		    		</a>
		    	</td>
		    	
		    <td class="last"><?php echo __('action')?></td>
				</tr>
			</thead>
			<tbody id="digittab">
				<?php 
					$mydata =$p->getDataArray();
					$loop = count($mydata); 
					for ($i=0;$i<$loop;$i++) {?>
						<tr class="row-1">
							<td style="text-align:center"><input type="checkbox" value="<?php echo $mydata[$i][0]['id']?>"/></td>
				    <td class="in-decimal"><?php echo $mydata[$i][0]['id']?></td>
				    <td class="in-decimal"><a title="View Details" href="<?php echo $this->webroot?>digits/translation_details/<?php echo $mydata[$i][0]['id']?>"><?php echo $mydata[$i][0]['trans']?></a></td>
				    <td style="font-weight: bold;"><?php echo $mydata[$i][0]['name']?></td>
				    <td><?php if (!empty($mydata[$i][0]['updateat'])) echo $mydata[$i][0]['updateat']?></td>
				    <td >
				    		<?php if ($w ==  true) {?><a title="<?php echo __('edit')?>" style="float:left;margin-left:120px;" href="javascript:void(0)" onclick="modifyName('<?php echo $this->webroot?>',this,'digits','<?php echo __('tran_update_success')?>',4);">
				    			<i class="icon-edit"></i>
				    		</a>
				    		<a title="<?php echo __('del')?>" style="float:left;margin-left:20px;" href="javascript:void(0)" onclick="delConfirm(this,'<?php echo $this->webroot?>digits/delbyid/<?php echo $mydata[$i][0]['id']?>');">
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
<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div></body></html>

<!-- 添加不成功时 显示原来输入的名称 -->
<?php
	$n = $session->read('tran_name');
	if (!empty($n)) {
		$session->del('tran_name');
?>
	<script type="text/javascript">
		//<![CDATA[
			cover('addtranslation');
			document.getElementById("tran_name").value="<?php echo $n?>";
		//]]>			
	</script>
<?php
	}
	?>
s
