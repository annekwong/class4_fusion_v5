

	<?php if (empty($p)) {?>
<div class="msg"><?php echo __('no_data_found')?></div>
<?php } else {?>
	<?php if($_SESSION['login_type']==1){?>
<div id="toppage"></div>
<div id="cover"></div>
<?php }?>
		<div style="min-width:980px;">	
			<table class="list"  style="border:1px solid #809DBA;height: 14px;">

	<col style="width: 8%;"/>
	<col style="width: 8%;"/>
	<col style="width: 8%;"/>
	<col style="width: 5%;"/>
	<col style="width: 5%;"/>
	<col style="width: 18%;"/>
	<col style="width: 18%;"/>
	<col style="width: 18%;"/>
	<col style="width: 12%;"/>
			
			<thead>
			<tr>
			<td> <?php echo $appCommon->show_order('code',__('Code',true))?></td>
			<td> <?php echo $appCommon->show_order('code_name',__('code name',true))?></td>
			<td> <?php echo $appCommon->show_order('country',__('Country',true))?></td>
			<td> <?php echo $appCommon->show_order('rate',__('Rate',true))?></td>
			<td> <?php echo $appCommon->show_order('setup_fee',__('Setup Fee',true))?></td>
			<td> <?php echo $appCommon->show_order('effective_date',__('Effective Date',true))?></td>
			<td> <?php echo $appCommon->show_order('end_date',__('End Date',true))?></td>
			
				<td> <?php echo $appCommon->show_order('min_time',__('Min Time',true))?></td>
					<td> <?php echo $appCommon->show_order('interval',__('Interval',true))?></td>
						<td> <?php echo $appCommon->show_order('grace_time',__('Grace Time',true))?></td>
							<td> <?php echo $appCommon->show_order('seconds',__('Seconds',true))?></td>
								<td> <?php echo $appCommon->show_order('time_profile_id',__('Profile',true))?></td>

	 </tr>
		 </thead>
		<?php 	$mydata =$p->getDataArray();	$loop = count($mydata); for ($i=0;$i<$loop;$i++) {?>
				<tr id="tpl">
				 <td class="value"><?php echo  $mydata[$i][0]['code']?> </td>
	    <td class="value"><?php echo  $mydata[$i][0]['code_name']?> </td>
				<td class="value"><?php echo  $mydata[$i][0]['country']?> </td>
				<td class="value"><?php echo  $mydata[$i][0]['rate']?> </td>
				
						<td class="value"><?php echo  $mydata[$i][0]['setup_fee']?> </td>
								<td class="value"><?php echo  $mydata[$i][0]['effective_date']?> </td>
										<td class="value"><?php echo  $mydata[$i][0]['end_date']?> </td>
											<td class="value"><?php echo  $mydata[$i][0]['min_time']?> </td>
												<td class="value"><?php echo  $mydata[$i][0]['interval']?> </td>
													<td class="value"><?php echo  $mydata[$i][0]['grace_time']?> </td>
											<td class="value"><?php echo  $mydata[$i][0]['seconds']?> </td>
											<td class="value"><?php echo  $mydata[$i][0]['time_profile_name']?> </td>
	    
	 </tr>
	 
			
			

				<?php }?>
	</table>
	</div>
	
<div id="tmppage">
<?php echo $this->element('page');?>
</div>

<?php }?>