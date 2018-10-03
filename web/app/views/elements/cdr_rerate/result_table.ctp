	<fieldset><legend><?php __('Cdr Rerate')?></legend>
<?php 		
			$mydata = $p->getDataArray();
			$loop = count($mydata); 
			if(empty($mydata)){
			?>
<div class="msg"><?php echo __('no_data_found',true);?></div>
<?php 
			}else{
?>
<form action="rerate_cdr" method="post" id="statistics-form">

<div id="toppage"></div>
<table class="list">
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
 			<td >
 			<input type="checkbox" class="input in-checkbox" name="selector-1" value="1" onchange="switchSelection();" id="selector-1">
 			<?php echo $appCommon->show_order('id', __('Id',true));?> </td>
		 <td > <?php echo __('Ani'); ?>  </td>
		 <td > <?php echo __('dnis',true);?>  </td>
		 <td > <?php echo __('begin_time',true);?>  </td>
		 <td > <?php echo __('end_time',true);?>  </td>
		 <td > <?php echo __('Duration'); ?>  </td>
		 <td colspan=2> <?php echo __('Old Term Rate'); ?>  </td>
		 <td colspan=2> <?php echo __('New Term Rate'); ?>  </td>
		 <td colspan=2> <?php echo __('Old Orig Rate'); ?>  </td>
		 <td colspan=2> <?php echo __('New Orig Rate'); ?>  </td>
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
		<?php 
		for ($i=0;$i<$loop;$i++){
			$cdr_arr_orig = explode(";", $mydata[$i][0]['orig_info']);//var_dump($cdr_arr_orig);echo "<br />";
			$cdr_arr_term = explode(";", $mydata[$i][0]['term_info']);//var_dump($cdr_arr_term);
		?>
		<tr class="row-1">
		  <td align="center">
		  <input type="checkbox" class="input in-checkbox" name="sel_id[]" value="<?php echo $mydata[$i][0]['id'];?>">
		  <?php echo $mydata[$i][0]['id'];?>
			</td>
		 <td><?php echo $mydata[$i][0]['origination_source_number'];?></td>		
		 <td ><?php echo $mydata[$i][0]['origination_destination_number'];?></td>
		 <td ><?php echo date("Y-m-d H:i:s", $mydata[$i][0]['start_time_of_date']/1000);?></td>
		 <td ><?php echo date("Y-m-d H:i:s", $mydata[$i][0]['release_tod']/1000);?></td>
		 <td ><?php echo $mydata[$i][0]['call_duration'];?></td>
		 <td> <?php echo isset($name_join_arr['rate_table'][$mydata[$i][0]['egress_rate_table_id']]) ? $name_join_arr['rate_table'][$mydata[$i][0]['egress_rate_table_id']] : ''; ?>  </td>
		 <td> <?php echo $mydata[$i][0]['egress_cost']; ?>  </td>
		 <td> <?php echo isset($cdr_arr_term[10]) ? $cdr_arr_term[10] : ''; ?>  </td>
		 <td> <?php echo isset($cdr_arr_term[11]) ? $cdr_arr_term[11] : ''; ?>  </td>
		 <td> <?php echo isset($name_join_arr['rate_table'][$mydata[$i][0]['ingress_client_rate_table_id']]) ? $name_join_arr['rate_table'][$mydata[$i][0]['ingress_client_rate_table_id']] : ''; ?>  </td>
		 <td> <?php echo $mydata[$i][0]['ingress_client_cost']; ?>  </td>
		 <td> <?php echo isset($cdr_arr_orig[12]) ? $cdr_arr_orig[12] : ''; ?>  </td>
		 <td> <?php echo isset($cdr_arr_orig[13]) ? $cdr_arr_orig[13] : ''; ?>  </td>
		</tr>
			<?php }?>
</tbody>
</table>
<div class="form-buttons">
<input type="hidden" name="term_rate_table" value="<?php echo isset($_REQUEST['term_rate_table']) ? $_REQUEST['term_rate_table'] : '';?>" />
<input type="hidden" name="orig_rate_table" value="<?php echo isset($_REQUEST['orig_rate_table']) ? $_REQUEST['orig_rate_table'] : '';?>" />
<input type="submit" value="Process" />
</div>
    </form>
    </fieldset>
<div id="tmppage"><?php echo $this->element('page');?></div>

<?php }?>