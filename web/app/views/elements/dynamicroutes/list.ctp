 <?php $w = $session->read('writable');?>
<?php if(empty($this->data)) {?>
<div class="msg"><?php echo __('no_data_found')?></div>
<?php } else {?>
<div>	
	<table class="list">
		<thead>
			<tr>
			 	<td style="width:20%"><?php echo __('findegress')?></td>
	    		<td style="width:20%"> <?php echo $appCommon->show_order('dynamic_route_id','ID')?></td>
	    		<td style="width:20%"> <?php echo $appCommon->show_order('name','Name')?></td>
		      	<td style="width:20%"> <?php echo $appCommon->show_order('routing_rule','Routing rule')?></td>
		      	<td style="width:20%" class="last"  style="text-align: center;"><?php __('action')?></td>
			</tr>
		</thead>
		<?php $i=0;?>
		<?php foreach($this->data as $list) {?>
			<tr>
				<td  align="center"  style="font-weight: bold;">
				   	<img onclick="jQuery(this).parent().parent().next().find('#ipInfo').slideToggle();if(jQuery(this).attr('src')=='<?php echo $this->webroot?>images/+.gif'){jQuery(this).attr('src','<?php echo $this->webroot?>images/-.gif')}else{jQuery(this).attr('src','<?php echo $this->webroot?>images/+.gif')}" src="<?php echo $this->webroot?>images/+.gif" title="<?php echo __('findegress')?>"/>
				</td >
		    	<td  align="center">
			    	<a href="<?php echo $this->webroot?>dynamicroutes/edit/<?php echo $list['DynamicRoute']['dynamic_route_id']?>"  title="<?php echo __('edit')?>">
			    		<?php echo $list['DynamicRoute']['dynamic_route_id']?>	
			    	</a>
		    	</td>
		     	<td  align="center"  style="font-weight: bold;"><?php echo $list['DynamicRoute']['name']?></td >
			    <td align="center">
			    	<?php echo $OptionsRouteingRule[$list['DynamicRoute']['routing_rule']] ?>
			    </td>
         		<td style="text-align: center;">
            		<?php if ($w == true) {?>
                            <a title="Edit" href="<?php echo $this->webroot?>dynamicroutes/edit/<?php echo  $list['DynamicRoute']['dynamic_route_id']?>"  title="edit">
                  		<<i class="icon-edit"></i>
		    		</a>
		    		<?php }?>
        			<?php if ($w == true) {?> 
        			<a onclick="return confirm('Note:Do you want to delete this object ?');" href="<?php echo $this->webroot?>dynamicroutes/del/<?php echo $list['DynamicRoute']['dynamic_route_id']?>/<?php echo $list['DynamicRoute']['name']?>" title="<?php echo __('delete')?>">
	        			<i class='icon-remove'></i>
	      			</a><?php }?>
	      		</td>
				</tr>
				<tr style="height:auto">
					<td colspan=5>
						<div id="ipInfo" class="jsp_resourceNew_style_2" style="padding:5px;display:none">
							<table>
								<tbody>
									<tr height="23">
										<th style="width:20%"><?php echo __('host',true);?></th>
										<th style="width:20%"><?php echo __('IP FQDN/Netmask',true);?></th>
										<th style="width:20%"><?php echo __('port',true);?></th>
										<th style="width:20%"><?php echo __('Call Limit',true);?></th>
										<th style="width:20%"><?php echo __('CPS Limit',true);?></th>
									</tr>
									<?php foreach($list['ResourceOther'] as $ResourceOther){?>
									<tr height="23">
										<td style="width:20%"><?php echo $ResourceOther['ip']?></td>
										<td style="width:20%"><?php echo $ResourceOther['fqdn']?></td>
										<td style="width:20%"><?php echo$ResourceOther['port']?></td>
										<td style="width:20%"><?php echo$ResourceOther['call']?></td>
										<td style="width:20%"><?php echo$ResourceOther['cps']?></td>
									</tr>
									<?php }?>
								</tbody>
							</table>
						</div>
					</td>
				</tr>
<?php $i++?>
<?php }?>
</table>
</div>
<?php //*****************************************循环输出的动态部分*************************************?>	
<?php echo $this->element('xpage');?>
</div>
<div>
<?php }?>