<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<script language = JavaScript  type="text/javascript">
	 function setSession(value,type){
		 jQuery.get("<?php echo $this->webroot?>monitors/writesession/"+value+"/"+type);
	  }

	 onload = function(){
		 var chx = document.getElementById("hideInactive");
		 var type = "<?php $session->read('res_hide_egress');?>";
		 myHideInactivePro(chx,'resourcesinfo',type==null?"res_hide_ingress":type);
	   }

	 function myHideInactivePro(obj,tid,type){
		   var v = hideInactivePro(obj,tid);
		   if (v == "none")
			   setSession("true",type);
		   else 
			   setSession("false",type);
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
			if ($grs == 'egress') {
					$eh = $session->read('res_hide_egress');
					if (empty($eh) || $eh == 'false')
						echo "<input type='checkbox'  id='hideInactive' onclick='myHideInactivePro(this,\"resourcesinfo\",\"res_hide_egress\");' /> ";
					else 
					  echo "<input type='checkbox'  id='hideInactive' onclick='myHideInactivePro(this,\"resourcesinfo\",\"res_hide_egress\");'   checked='checked' /> ";


 				} else {
					$eh = $session->read('res_hide_ingress');
					if (empty($eh) || $eh == 'false')
						echo "<input type='checkbox'  id='hideInactive' onclick='myHideInactivePro(this,\"resourcesinfo\",\"res_hide_ingress\");' /> ";
					else 
					  echo "<input type='checkbox'  id='hideInactive' onclick='myHideInactivePro(this,\"resourcesinfo\",\"res_hide_ingress\");'   checked='checked' /> ";
             }
?>
</span>
    </div>
  </div>
  <div style='margin-left:150px;z-index:1;margin-top: -20px;'>
    <div id="proStatsSearch0">
    		<script type="text/javascript">
    				//<![CDATA[
							function res_search(){
								var name = document.getElementById("diskey").value;
								var f = 21;
								if (document.getElementById("ingressAttr").style.backgroundColor=="lightgray")
									f = 22;
								location = "<?php echo $this->webroot?>monitors/monitor/"+f+"?search="+name;
							}
						//]]>
    		</script>
				<input id="diskey" style="width:200px;" onfocus="this.value=''" type="text" size="10" value="<?php if (!empty($search)) echo $search; else echo __('search')?>" class="form" name="keyword"/>
				<input type="button" onclick="res_search();"value="<?php echo __('submit')?>"/>
    </div>
  </div>
</div>

		 <div id="context_right_nav_div">
				 <?php
				 	$eg = __('egress',true);
				 	$ing = __('ingress',true); 
				 if ($gressIndex == 1)
					 					echo "<label class='selecttab'><a style='background:lightgray' id='egressAttr' href='<?php echo $this->webroot?>monitors/monitor/21'><span>$eg</span></a></label>
						    				 		<label class='tab'><a id='ingressAttr' href='<?php echo $this->webroot?>monitors/monitor/22'><span>$ing</span></a></label>";
					 				else 
					 						echo "<label class='tab'><a id='egressAttr' href='<?php echo $this->webroot?>monitors/monitor/21'><span>$eg</span></a></label>
						    				 			<label class='selecttab'><a style='background:lightgray' id='ingressAttr' href='<?php echo $this->webroot?>monitors/monitor/22'><span>$ing</span></a></label>";
				  ?>
					<div style="clear: both;border-top:2px solid #6694E3"></div>
		</div>
<div id="context_right_table_div">
				    <table id="show_table">
					       <tr height="30">
							      <th>&nbsp;</th>
										 <!--  <th class="bcess_td" bgcolor='#f9fbf1' ><?php echo __('currently')?></th>-->
										 <th class="bcess_td" bgcolor='#f1fbf8'>15 <?php echo __('minutes')?></th>
										 <th class="bcess_td" bgcolor='#f2f1fb'>1 <?php echo __('hour')?></th>
										 <th class="bcess_td" bgcolor='#f8e9ee'>24 <?php echo __('hour')?></th>
						   	</tr>
						   	<tr height="28">
						      <td width="200px" class="bcess_td">
						        <span>
						        
						          <a href="javascript:;" onclick="changeResT('${param.grs}')">
						      		  ID
						          </a>
						        </span>
						      
							    </td>
							   <!--   <td class="bcess_td" bgcolor='#f9fbf1' ><div class=" monitor_product_style_8">Calls</div><div class=" monitor_product_style_9">CPS</div></td>-->
							    <td class="bcess_td" bgcolor='#f1fbf8'><div class=" monitor_product_style_10"><?php __('ACD')?></div ><div class=" monitor_product_style_11"><?php __('ASR')?></div ><div class=" monitor_product_style_11"><?php __('CA')?></div > <div class=" monitor_product_style_12"><?php __('PDD')?></div></td>
							    <td class="bcess_td" bgcolor='#f2f1fb'><div class=" monitor_product_style_13"><?php __('ACD')?></div><div class=" monitor_product_style_11"><?php __('ASR')?></div ><div class=" monitor_product_style_11"><?php __('CA')?></div > <div class=" monitor_product_style_12"><?php __('PDD')?></div></td>
							    <td class="bcess_td" bgcolor='#f8e9ee'><div class=" monitor_product_style_10"><?php __('ACD')?></div ><div class=" monitor_product_style_11"><?php __('ASR')?></div ><div class=" monitor_product_style_11"><?php __('CA')?></div> <div class=" monitor_product_style_18"><?php __('PDD')?></div></td>
						   </tr>
						   <tbody id="resourcesinfo">
						   				<?php foreach ($p->getDataArray() as $r) {?>
						   	<tr style="display:table-row">
						   					<td height="28" class="bcess_td">
						   						<span>
							   						<a href="<?php echo $this->webroot?>monitors/monitor/23/<?php echo $r[0][0][0]['res_id']?>/<?php echo $gressIndex?>" class=" monitor_product_style_19">
							   								<?php echo $r[0][0][0]['res_name']?>
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
								   							<div class=" monitor_product_style_10"><?php echo empty($t[0]['acd'])?0:$t[0]['acd']?></div>
								   							<div class=" monitor_product_style_11"><?php echo empty($t[0]['asr'])?0:$t[0]['asr']?></div>
								   							<div class=" monitor_product_style_12"><?php echo empty($t[0]['ca'])?0:$t[0]['ca']?></div>
								   							<div class=" monitor_product_style_13"><?php echo empty($t[0]['pdd'])?0:$t[0]['pdd']?></div>
								   					</td>
						   						<?php  $i++;}?>
						   					<?php }?>
						   					
						   					<td style="display:none"><?php echo  $r[0][0][0]['totalcall']==null?0:$r[0][0][0]['totalcall'];?></td>
						   	</tr>
							   <?php }?>
						   </tbody>
						</table>
</div>

<!-- <div class='quotes'>

<span style='font-size:10pt;color:#aaaaaa;' >Per page:</span>

 <a href="javascript:;" onclick="javascript:changeRes(${resMoniPageInfo.totalPage},'${param.grs }')">&gt;</a>
<input type="hidden" id="totalCounts" value="${resMoniPageInfo.total }"></input>
&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;

</div>-->
<?php echo $this->element('page');?>


<div  class='monitor49' >
<span class="groupBy"><?php echo __('refreshtime')?>:</span>
<span>
<input type='radio'  name="reTime" onclick="changeTime()" value='0' checked>3 <?php echo __('minutes')?>
<input type='radio'  name="reTime" onclick="changeTime()" value='1' >5 <?php echo __('minutes')?>
<input type='radio'  name="reTime" onclick="changeTime()" value='2' >15 <?php echo __('minutes')?>
</span>
<input type="button" onclick="javascript:changeTime();return false;" value="<?php echo __('refresh')?>" class="in-button">
<script type="text/javascript">
var gress = $("#gressValue").val();

var time = window.setInterval("refreshPro()",180000);

function refreshPro(){
	var d = document.getElementById("context_right_nav_div");
	var lbs = d.getElementsByTagName("lable");
	var url = "<?php echo $this->webroot?>monitors/monitor/21";
	for (var i = 0;i<lbs.length; i++) {
			if (lbs[i].className == "selecttab") {
					url = lbs[i].getElementsByTagName("a")[0].href;
					break;
			}
	}
	location = url;
}
</script>
</div>

