<?php $mydata =$p->getDataArray();	$loop = count($mydata);
if($loop==0){?>
<center>
<div class="msg"><?php echo __('no_data_found',true);?></div>
</center>
<?php }else{?>
<div id="toppage"></div>
<div style="width:100%;overflow-x:scroll">
<table class="list nowrap with-fields" style="width: 100%">
<col width="7%">
<col width="7%">
<col width="7%">
<col width="8%">
<col width="8%">
<col width="7%">
<col width="7%">
<col width="7%">
<col width="7%">
<col width="7%">
<col width="7%">
<col width="7%">
<col width="7%">
<col width="7%">
<thead>
		<tr>
 			<td ><?php echo $appCommon->show_order('id', __('Id',true));?> </td>
		 <td > <?php echo __('Ani'); ?>  </td>
		 <td > <?php echo __('dnis',true);?>  </td>
		 <td > <?php echo __('begin_time',true);?>  </td>
		 <td > <?php echo __('end_time',true);?>  </td>
		 <td > <?php echo __('Duration'); ?>  </td>
		 
		  <td colspan=2> <?php echo __('Old Orig Rate'); ?>  </td>
		 <td colspan=2> <?php echo __('Old Term Rate'); ?>  </td>
		 <td colspan=2> <?php echo __('New Orig Rate'); ?>  </td>
		 <td colspan=2> <?php echo __('New Term Rate'); ?>  </td>
		
		 
		</tr>
		<tr>
 			<td ></td>
		 <td ></td>
		 <td ></td>
		 <td ></td>
		 <td ></td>
		 <td ></td>
		 <td> <?php echo __('Rate'); ?>  </td>
		 <td> <?php echo __('Price'); ?>  </td>
		 <td> <?php echo __('Rate'); ?>  </td>
		 <td> <?php echo __('Price'); ?>  </td>
		 <td> <?php echo __('Rate'); ?>  </td>
		 <td> <?php echo __('Price'); ?>  </td>
		 <td> <?php echo __('Rate'); ?>  </td>
		 <td> <?php echo __('Price'); ?>  </td>
		</tr>
</thead>
	<tbody>
		<?php 	 for ($i=0;$i<$loop;$i++) { ?>
		<tr class="row-1">
		  <td align="center"> <?php  $appCdr->render_rerate_data($mydata[$i][0],$rerate_type) ; echo $appCdr->get_rerate_data('id');?></td>
		 <td><?php echo $appCdr->get_rerate_data('ani');?></td>		
		 <td ><?php echo $appCdr->get_rerate_data('dnis');?></td>
		 <td ><?php echo $appCdr->get_rerate_data('begin_time');?></td>
		 <td ><?php echo $appCdr->get_rerate_data('end_time');?></td>
		 <td ><?php echo $appCdr->get_rerate_data('duration');?></td>
		 
		 <td> <?php echo $appCdr->get_rerate_data('old_orig_rate'); ?>  </td>
		 <td> <?php echo $appCdr->get_rerate_data('old_orig_rate_cost');?>  </td>
		 <td> <?php echo $appCdr->get_rerate_data('old_term_rate'); ?>  </td>
		 <td> <?php echo $appCdr->get_rerate_data('old_term_rate_cost'); ?>  </td>
		 
		 <td> <?php echo $appCdr->get_rerate_data('new_orig_rate'); ?>  </td>
		 <td> <?php echo $appCdr->get_rerate_data('new_orig_rate_cost'); ?>  </td>
		 <td> <?php echo $appCdr->get_rerate_data('new_term_rate'); ?>  </td>
		 <td> <?php echo $appCdr->get_rerate_data('new_term_rate_cost'); ?>  </td>
		</tr>
  <?php }?>
 </tbody>
</table>
</div>
<div id="tmppage"><?php echo $this->element('page');?></div>
<?php }?>