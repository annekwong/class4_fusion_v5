
    <script type="text/javascript">
    				function generatedcards(){
        			var nums = document.getElementById("nums").value;
        			var pool_id = document.getElementById("hiddenid").value;
        			jQuery.post('<?php echo $this->webroot?>refillpools/generated',{id:pool_id,num:nums},function(data){
            		var tmp = data.split("|");
            		if (tmp[1].trim() == 'false') {
                	jGrowl_to_notyfy(tmp[0],{theme:'jmsg-alert'});
               } else {
            	   closeCover('generatec');
            	   jGrowl_to_notyfy(tmp[0],{theme:'jmsg-success',life:100,beforeClose:function(){location.reload();}});
                    					}
            					});
        				}
    </script>
<div id="cover"></div>
<?php $w = $session->read('writable');?>
<div id="title">
  <h1><?php echo __('retail')?>&gt;&gt;
    	<?php echo __('refillcardseries')?>
   <!--  <a title="add to smartbar" href="/admin/_view/sbAdd?link=/rate_tables/list">
   	<img width="10" height="10" alt="+" src="<?php echo $this->webroot?>images/qb-plus.png" />
   </a>-->
  </h1>
  <ul id="title-search">
    <li>
   <form   id="like_form"  action=""  method="post">
        <input type="text" id="search-_q" class="in-search default-value input in-text defaultText" title="<?php echo __('namesearch')?>" value="<?php if (!empty($search)) echo $search;?>" name="search">
        </form>
    </li>
      <li title="<?php echo __('advancedsearch')?> »" onclick="advSearchToggle();" id="title-search-adv" style="display: list-item;"></li>
  </ul>
    
  <ul id="title-menu">
  <?php if (isset($edit_return)) {?>
        <li>
    			<a class="link_back" href="<?php echo $this->webroot?>refillpools/pools_list">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('gobackall')?>
    			</a>
    		</li>
        <?php }?>
    <?php if ($w == true) {?><li><a class="link_btn" href="<?php echo $this->webroot?>refillpools/add_card_series"><img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"> <?php echo __('createnew')?></a></li><?php }?>
    <li><a class="link_btn" rel="popup" href="javascript:void(0)" onclick="deleteAll('<?php echo $this->webroot?>refillpools/delete_all');" ><i class="icon-remove"></i> <?php echo __('deleteall')?></a></li>
    <li><a class="link_btn" rel="popup" href="javascript:void(0)" onclick="deleteSelected('producttab','<?php echo $this->webroot?>refillpools/delete_selected');"><i class="icon-remove"></i> <?php echo __('deleteselected')?></a></li>
  </ul>
</div>
<div id="container">
<?php //*********************  条件********************************?>
<fieldset class="query-box" id="advsearch"  style="width: 100%;">
<form action=""  method="post">
<table    style="text-align: right;">
<tbody>
<tr>

<td>充值卡号池名称      <input   type="text"    name="name"/> </td>
<td>前缀    <input   type="text"       name="prefix"/> </td>
     <td>卡金额:
     <input   type="hidden"   value="searchkey"    name="searchkey"/>

	<input type="text" id="start_amount" style="width:84px;height:20px;" value="<?php if(isset($_POST['start_amount'])){echo $_POST['start_amount'];}?>" name="start_amount" class="input in-text">
    		--
    		<input type="text" id="end_amount" style="width:84px;height:20px;" value="" name="end_amount" class="input in-text">
    </td>
    <td><?php __('timeprofile')?>:
	<input type="text" readonly onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" id="start_date" style="width:120px;height:20px;" value="" name="start_date" class="input in-text wdate">
    		--
    		<input type="text" readonly onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" id="end_date" style="width:120px;height:20px;" value="" name="end_date" class="wdate input in-text">
    </td>
    
    <td>
开始编号
 
<input   type="text"     name="start_num"/>
 <td class="buttons"><input type="submit" value="<?php echo __('search',true);?>" class="input in-submit"></td>
    </td>
   
</tr>
</tbody></table>
</form></fieldset>


<div id="toppage"></div>
<dl style="display:none;position: absolute; left: 40%; top: 30%; width: 300px; height: 100px; z-index: 99;" class="tooltip-styled" id="generatec">
<div style="text-align: center; width: 100%; height: 25px; font-size: 16px;"><?php echo __('generatedcards')?></div>
	<div style="margin-top: 10px;"><?php echo __('generatednums')?>:<input id="nums" class="input in-text">
	</div>
	<input id="hiddenid" style="display:none"/>
	<div style="margin-top: 10px; margin-left: 25%; width: 150px; height: auto;">
		<input type="button" class="input in-button" value="<?php echo __('submit')?>" onclick="generatedcards();">
		<input type="button" class="input in-button" value="<?php echo __('cancel')?>" onclick="closeCover('generatec');">
	</div>
</dl>
		<?php $d = $p->getDataArray();if (count($d) == 0) {?>
<div class="msg"><?php echo __('no_data_found')?></div>
<?php } else {?>
<table class="list">
	<col style="width: 4%;">
	<col style="width: 5%;">
	<col style="width: 10%;">
	<col style="width: 12%;">
	<col style="width: 12%;">
	<col style="width: 12%;">
	<col style="width: 12%;">
	<col style="width: 10%;">
	<col style="width: 10%;">
	<thead>
		<tr>
		 <td><input type="checkbox" onclick="checkAllOrNot(this,'producttab');" value=""/></td>
    <td><a href="javascript:void(0)" onclick="my_sort('credit_card_series_id','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;ID&nbsp;<a href="javascript:void(0)" onclick="my_sort('credit_card_series_id','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('name','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('seriesname')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('name','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('value','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('cardvalue')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('value','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('cards','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('ofcards')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('cards','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('prefix','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('cardseriesprefix')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('prefix','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('expire_date','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('expiredate')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('expire_date','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td><a href="javascript:void(0)" onclick="my_sort('start_num','asc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-asc.png"></a>&nbsp;<?php echo __('startnum')?>&nbsp;<a href="javascript:void(0)" onclick="my_sort('start_num','desc')"><img width="10" height="10" src="<?php echo $this->webroot?>images/list-sort-desc.png"></a></td>
    <td class="last"><?php echo __('action')?></td>
		</tr>
	</thead>
	<tbody id="producttab">
		<?php 
			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			for ($i=0;$i<$loop;$i++) {?>
				<tr class="row-1">
					<td style="text-align:center"><input type="checkbox" value="<?php echo $mydata[$i][0]['credit_card_series_id']?>"/></td>
		    <td><?php echo $mydata[$i][0]['credit_card_series_id']?></td>
		    <td style="font-weight: bold;"><?php echo $mydata[$i][0]['name']?></td>
		    <td><?php echo $mydata[$i][0]['value']?></td>
		    <td><a href="<?php echo $this->webroot?>refillpools/cards_list/<?php echo $mydata[$i][0]['credit_card_series_id']?>"><?php echo $mydata[$i][0]['cards']?></a></td>
		    <td><?php echo $mydata[$i][0]['prefix']?></td>
		    <td><?php echo $mydata[$i][0]['expire_date']?></td>
		    <td><?php echo $mydata[$i][0]['start_num']?></td>
		    <td >
		    		<a title="<?php echo __('viewcard')?>" style="float:left;margin-left:15px;" href="<?php echo $this->webroot?>refillpools/cards_list/<?php echo $mydata[$i][0]['credit_card_series_id']?>">
		    			<img src="<?php echo $this->webroot?>images/title-search.png" />
		    		</a>
		    		<?php if ($w == true) {?><a title="<?php echo __('generatedcards')?>" style="float:left;margin-left:10px;" href="javascript:void(0)" onclick="cover('generatec');document.getElementById('hiddenid').value='<?php echo $mydata[$i][0]['credit_card_series_id']?>';">
		    			<img src="<?php echo $this->webroot?>images/menuIcon_025.gif" />
		    		</a>
		    		<a title="<?php echo __('edit')?>" style="float:left;margin-left:10px;" href="<?php echo $this->webroot?>refillpools/edit_card_series/<?php echo $mydata[$i][0]['credit_card_series_id']?>">
		    			<i class="icon-edit"></i>
		    		</a>
		    		<a title="<?php echo __('del')?>" style="float:left;margin-left:10px;" href="javascript:void(0)" onclick="delConfirm(this,'<?php echo $this->webroot?>refillpools/del_card_series/<?php echo $mydata[$i][0]['credit_card_series_id']?>');">
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
</div>
<div id="livemargins_control" style="position: absolute; display: none; z-index: 9999;"><img width="77" height="5" style="position: absolute; left: -77px; top: -5px;" src="chrome://livemargins/skin/monitor-background-horizontal.png">	<img style="position: absolute; left: 0pt; top: -5px;" src="chrome://livemargins/skin/monitor-background-vertical.png">	<img style="position: absolute; left: 1px; top: 0pt; opacity: 0.5; cursor: pointer;" onmouseout="this.style.opacity=0.5" onmouseover="this.style.opacity=1" src="chrome://livemargins/skin/monitor-play-button.png" id="monitor-play-button"></div>
	<script type="text/javascript" src="<?php echo $this->webroot?>js/My97DatePicker/WdatePicker.js"></script>
