
  <script type='text/javascript' src='http://www.google.com/jsapi'></script>
	 <script type="text/javascript">
	//加载Google的报表类库
		google.load('visualization', '1', {'packages':['annotatedtimeline']});
	</script>
	<script type="text/javascript" src="<?php echo $this->webroot?>js/chart.js"></script>

<div id="title">
  <h1><?php echo __('manage')?>&gt;&gt;
    <?php echo __('monitor')?>
  </h1>
</div>

	<div id="project">
	
	 <div id="context_table_div">
	 
	   <div id="context_div">
	   
				<div id="context_right_div">
				
					<div class="content_right_div">
							<input type="hidden" name="timeType" id="timeType" value="sevenday">
							<div style="padding: 10px;">
									<div id="context_right_nav_div">
										 	<?php if ($stats < 10) {?>
										 		 <span class="selecttab"><a id="egressAttr" href="<?php echo $this->webroot?>monitors/monitor/1"><?php echo __('globals')?></a></span>
										 	<?php } else {?>
										 		 <span class="tab"><a href="<?php echo $this->webroot?>monitors/monitor/1"><?php echo __('globals')?></a></span>
										 	<?php }?>
										 			
										 	<?php if ($stats > 10 && $stats < 20) {?>
										 		 <label class="selecttab"><a id="egressAttr" href="<?php echo $this->webroot?>monitors/monitor/12"><?php echo __('productstat')?></a></label>
										 	<?php } else {?>
										 			<label class="tab"><a href="<?php echo $this->webroot?>monitors/monitor/12"><?php echo __('productstat')?></a></label>
										 	<?php }?>
										 			
										 	<?php if ($stats > 20) {?>
										 			<label class="selecttab"><a id="egressAttr" href="<?php echo $this->webroot?>monitors/monitor/21" ><?php echo __('resstat')?></a></label>
										 	<?php } else {?>
										 			<label class="tab"><a href="<?php echo $this->webroot?>monitors/monitor/21"><?php echo __('resstat')?></a></label>
										 	<?php }?>
										   
												<div style="clear: both;border-top:2px solid #6694E3"></div>
										</div>
										
										<?php if ($stats == 1) {include('globals.php');}
														else if ($stats == 12) {include('product.php');}
														else if ($stats == 11) {include ('prefix.php');}
														else if ($stats == 21 || $stats == 22) {include ('resource.php');}
														else if ($stats == 23) {include('resource_gress.php');}
														else if ($stats == 24) {include('ipreport.php');};
										?>
								</div>
						</div>
						
				</div>
				
		</div>
		
	</div>
	
</div>

<div id="shapeDiv" class=" jsp_monis_style_2">
	<table cellspacing="0px" cellpadding="0px" >
		<tr>
			<td width="6px" rowspan="2" height="7px" class=" jsp_monis_style_3">
				<img src="<?php echo $this->webroot?>images/pixel.gif" align="left"/>
			</td>
			
			<td id="keyTd" rowspan="2" width="480px" class=" jsp_monis_style_4">
				<img src="<?php echo $this->webroot?>images/pixel.gif" align="left"/>
			</td>
			
			<td width="7px"  class=" jsp_monis_style_5">
				<img src="<?php echo $this->webroot?>images/shade_tr.png" border="0px" align="left"/>
			</td>
		</tr>
		
		<tr>
			<td id="keyTds" background="./images/shade_mr.png" height="77">
				<img src="<?php echo $this->webroot?>images/pixel.gif"  align="left"/>
			</td>
		</tr>

		<tr>
			<td valign="top" height="7px">
				<img src="<?php echo $this->webroot?>images/shade_bl.png" align="left"/>
			</td>
			
			<td id="keyTdt" align="left"  >
				<img src="<?php echo $this->webroot?>images/pixel.gif" align="left"/>
			</td>
			
			<td valign="top">
				<img src="<?php echo $this->webroot?>images/shade_br.png" align="left"/>
			</td>
		</tr>

	</table>
</div>
