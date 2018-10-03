<script language = JavaScript  type="text/javascript">
	 function setSession(value){
		 jQuery.get("<?php echo $this->webroot?>monitors/writesession/"+value+"/pro_hide");
	  }

	 onload = function(){
		 var chx = document.getElementById("hideInactive");
		 myHideInactivePro(chx,'productinfos');
	   };

	 function myHideInactivePro(obj,tid){
		   var v = hideInactivePro(obj,tid);
		   if (v == "none")
			   		setSession("true");
		   else 
			   		setSession("false");
      }
</script>

<script type="text/javascript">

var time = window.setInterval("refreshPro()",180000);

/*
 * 刷新数据
 */
function refreshPro() {
	location = "<?php echo $this->webroot?>monitors/monitor/12";
}

</script>

<div style="margin-bottom: 5px; height: 20px; padding-left: 8px;">
  <div style='z-index:1;margin-top: 60px;'>
    <div style='z-index:1;'>
    </div>
    <div style="margin-top:4px;">

			<span>
			
			  <span style="font-weight:bold;font-size:13;"><?php echo __('hideinactive')?> :</span>
			  <?php 
			  		$h = $session->read('pro_hide'); 
			  		if (empty($h) || $h == 'false') {?>
			   	 	<input type="checkbox"  id="hideInactive" onclick="myHideInactivePro(this,'productinfos')" > 
			  <?php 
			  		} else {?>
			    	<input type="checkbox"  id="hideInactive" onclick="myHideInactivePro(this,'productinfos')"  checked="checked">
			  <?php }?>
			  
			</span>
			
		</div>
  </div>
  <div style='margin-left:150px;z-index:1;margin-top: -20px;'>
    <div id="proStatsSearch0">
    		<script type="text/javascript">
    				//<![CDATA[
							function pro_search(){
								var name = document.getElementById("diskey").value;
								location = "<?php echo $this->webroot?>monitors/monitor/12?search="+name;
							}
						//]]>
    		</script>
				<input id="diskey" style="width:200px;" onfocus="this.value=''" type="text" size="10" value="<?php if (!empty($search)) echo $search; else echo __('search')?>" class="form" name="keyword"/>
				<input type="button" onclick="pro_search();"value="<?php echo __('submit')?>"/>
    </div>
  </div>
</div>



<div id="proStatsDiv">
				    <table id="show_table">
					       <tr height="30">
						      <th style="width:20px">&nbsp;</th>
							 <!--  <th class="bcess_td" bgcolor='#f9fbf1' ><?php echo __('currently')?></th>-->
							 <th   class="bcess_td" bgcolor='#f1fbf8'>15 <?php echo __('minutes')?></th>
							 <th  class="bcess_td" bgcolor='#f2f1fb'>1 <?php echo __('hour')?></th>
							 <th  class="bcess_td" bgcolor='#f8e9ee'>24 <?php echo __('hour')?></th>
						   </tr>
						   <tr height="28">
						      <td width="200px" class="bcess_td">
						        <span>
						        <a href="javascript:;" onclick="changeProT()">
						        <?php echo __('proname')?>
						        </a>
						        </span>
							    
							    </td>
							    <!--  <td class="bcess_td" bgcolor='#f9fbf1' >
							    		<div class=" monitor_product_style_8">Calls</div>
							    		<div class=" monitor_product_style_9">CPS</div>
							    	</td>-->
							    <td class="bcess_td" bgcolor='#f1fbf8'>
								<div class=" monitor_product_style_10"><?php __('ACD')?></div>
                                                                                                                                                <div class=" monitor_product_style_11"><?php __('ASR')?></div>
                                                                                                                                                <div class=" monitor_product_style_11"><?php __('CA')?></div>
                                                                                                                                                <div class=" monitor_product_style_12"><?php __('PDD')?></div>
							    </td>
							    <td class="bcess_td" bgcolor='#f2f1fb'>
							    	<div class=" monitor_product_style_13"><?php __('ACD')?></div>
                                                                                                                                                <div class=" monitor_product_style_11"><?php __('ASR')?></div>
                                                                                                                                                <div class=" monitor_product_style_11"><?php __('CA')?></div>
                                                                                                                                                <div class=" monitor_product_style_12"><?php __('PDD')?></div>
							    </td>
							    <td class="bcess_td" bgcolor='#f8e9ee'>
							    	<div class=" monitor_product_style_10"><?php __('ACD')?></div>
                                                                                                                                                <div class=" monitor_product_style_11"><?php __('ASR')?></div>
                                                                                                                                                <div class=" monitor_product_style_11"><?php __('CA')?></div>
                                                                                                                                                <div class=" monitor_product_style_18"><?php __('PDD')?></div>
							    </td>
						   </tr>
						   
						   <tbody id="productinfos">
						   				<?php foreach ($p->getDataArray() as $r) {?>
						   	<tr style="display:table-row">
						   					<td height="28" class="bcess_td">
						   						<span>
							   						<a href="<?php echo $this->webroot?>monitors/monitor/11/<?php echo $r[0][0][0]['pro_id']?>" class=" monitor_product_style_19">
							   								<?php echo $r[0][0][0]['pro_name']?>
							   						</a>
						   						</span>
						   					</td>
						   					
						   					<!--  <td class="bcess_td" bgcolor='#f9fbf1'>
						   						<div class=" monitor_product_style_20">0</div>
						   						<div class=" monitor_product_style_21">0</div>
						   					</td>-->
						   					<?php $i=1; foreach($r as $d) {?>	
						   						<?php  foreach ($d as $t) { ?>
								   					<td class="bcess_td" bgcolor="<?php if ($i == 1) echo '#f1fbf8'; else if ($i == 2) echo '#f2f1fb'; else echo '#f8e9ee';?>">
								   							<div class=" monitor_product_style_22"><?php echo empty($t[0]['acd'])?0:$t[0]['acd']?></div>
								   							<div class=" monitor_product_style_23"><?php echo empty($t[0]['asr'])?0:$t[0]['asr']?></div>
								   							<div class=" monitor_product_style_23"><?php echo empty($t[0]['ca'])?0:$t[0]['ca']?></div>
								   							<div class=" monitor_product_style_24"><?php echo empty($t[0]['pdd'])?0:$t[0]['pdd']?></div>
								   					</td>
						   						<?php  $i++;}?>
						   					<?php }?>
						   					
						   					<td style="display:none"><?php echo  $r[0][0][0]['totalcall']==null?0:$r[0][0][0]['totalcall'];?></td>
						   	</tr>
							   <?php }?>
						   </tbody>
						</table>
</div>

<div>
<?php echo $this->element('page');?>
</div>

<!--  <div class='quotes'>

<span style='font-size:10pt;color:#aaaaaa;' >Per page:</span>
<input type="hidden" value="${proMoniPageInfo.limit}">
<span style="margin-right:280px;">&nbsp;&nbsp;</span>

  <a href="javascript:;" onclick="javascript:changePro(1)">&lt;</a>
<a href="javascript:;" onclick="javascript:changePro(jQuery{proMoniPageInfo.totalPage})">&gt;</a>
&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;
<input type="hidden" id="totalCounts" value="${proMoniPageInfo.total }"></input>
</div>-->

<div  class='monitor49' >
<span class="groupBy"><?php echo __('refreshtime')?>:</span>
<span>
<input type='radio'  name="reTime" onclick="changeTime()" value='0' checked>3 <?php echo __('minutes')?>
<input type='radio'  name="reTime" onclick="changeTime()" value='1' >5 <?php echo __('minutes')?>
<input type='radio'  name="reTime" onclick="changeTime()" value='2' >15 <?php echo __('minutes')?>	
</span>
<input type="button" onclick="changeTime();" value="<?php echo __('refresh')?>"/>

</div>



