<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<div id="title">
   <h1><?php __('Cdr Rerate')?>&gt;&gt;<?php echo __('List')?></h1>  
		<ul id="title-search">
        	<!--<li>
        		<?php //Pr($searchkey);    //****************************模糊搜索**************************?>
        		<form  action="<?php echo $this->webroot;?>cdrrerates/view"  method="get">
        			<input type="text" id="search-_q_j" class="in-search default-value input in-text defaultText" title="<?php echo __('search')?>..." value="<?php if(isset($searchkey)){echo $searchkey;}else{ echo __('pleaseinputkey');}?>"  onclick="this.value=''" name="searchkey">
        		</form>
        	</li>
       -->
       <li title="advanced search »" onclick="advSearchToggle();" id="title-search-adv" style="display: list-item;" class="opened"></li>
       </ul>
       <ul id="title-menu">
        	<?php if (isset($edit_return)) {?>
        	<li>
    			<a href="<?php echo $this->webroot;?>cdrrerates/view">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/rerating_queue.png">
    				&nbsp;<?php echo __('goback')?>
    			</a>
    		</li>
        	<?php }?>
        	<!--<li>
        		<a title="<?php echo __('creat rerate cdr')?>"  href="<?php echo $this->webroot?>cdrrerates/add_reratecdr">
       				<img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"><?php echo __('createnew')?>
       			</a>
       		</li>
       --></ul>
    </div>
<div id="container">

<fieldset style="margin-left: 1px; width: 100%; display: <?php echo isset($url_get['advsearch']) ? 'block' :'block';?>;" id="advsearch" class="title-block">
	<form method="get" action="">
		<input type="hidden" name="advsearch" class="input in-hidden">
		<table style="width:100%">
		 <tr>
		 				<td style="width:5%;text-align: right;">Start Time:</td>
		 				<td style="text-align: left;">
				      <input type="text" class="input in-text wdate" name="cdr_start" value="" style="width:120px;" id="start_date" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" readonly="">
		    			</td>
		    			<td style="width:5%;text-align: right;">End Time:</td>
		    			<td>	
		    			 <input type="text" class="wdate input in-text" name="cdr_end" value="" style="width: 120px;" id="end_date" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'});" readonly="">
		       </td>
		      <td style="width:5%;text-align: right;">Orig Carriers</td>
		      <td style="text-align: left;">	
				      <input class="input in-hidden" name="query[orig_id_clients]" value="" id="query-orig_id_clients" type="hidden">
		       		<input type="text" id="query-orig_id_clients_name" style="width:auto;" onclick="showClients('orig')" readonly="1" value="" name="query[orig_id_clients_name]" class="input in-text">        
		        	<img width="25" height="25" onclick="showClients('orig')" class="img-button" src="<?php echo $this->webroot?>images/search-small.png">
		        	<img width="25" height="25" onclick="ss_clear('client', _ss_ids_client_orig)" class="img-button" src="<?php echo $this->webroot?>images/delete-small.png">
    		   </td>
    		   <td style="width:5%;text-align: right;">Orig Trunk</td>
		      <td style="text-align: left;">	
				      <?php echo $form->input('orig_res',
 		array('id'=>'orig_res','name'=>'orig_res','options'=>$name_join_arr['resource'],'selected'=>'','label'=>false ,
 		'div'=>false,'type'=>'select'));?>
    		   </td>
    		   <td style="width:5%;text-align: right;">Orig Host</td>
		      <td style="text-align: left;">	
				       <?php echo $form->input('orig_host',
 		array('id'=>'orig_host','name'=>'orig_host','options'=>$name_join_arr['host'],'selected'=>'','label'=>false ,
 		'div'=>false,'type'=>'select'));?>
    		   </td>
    		   
   				<td>
		      <input type="submit" class="input in-submit" value="Submit">
		      </td>
		 </tr>
		 <tr>
		      <td style="width:5%;text-align: right;">Term Carriers</td>
		      <td style="text-align: left;">	
				      <input class="input in-hidden" name="query[term_id_clients]" value="" id="query-term_id_clients" type="hidden">
		       		<input type="text" id="query-term_id_clients_name" style="width:auto;" onclick="showClients('term')" readonly="1" value="" name="query[term_id_clients_name]" class="input in-text">        
		        	<img width="9" height="9" onclick="showClients('term')" class="img-button" src="<?php echo $this->webroot?>images/search-small.png">
		        	<img width="9" height="9" onclick="ss_clear('client', _ss_ids_client_term)" class="img-button" src="<?php echo $this->webroot?>images/delete-small.png">
    		   </td>
    		   <td style="width:5%;text-align: right;">Term Trunk</td>
		      <td style="text-align: left;">	
				      <?php echo $form->input('term_res',
 		array('id'=>'term_res','name'=>'term_res','options'=>$name_join_arr['resource'],'selected'=>'','label'=>false ,
 		'div'=>false,'type'=>'select'));?>
    		   </td>
    		   <td style="width:5%;text-align: right;">Term Host</td>
		      <td style="text-align: left;">	
				       <?php echo $form->input('term_host',
 		array('id'=>'term_host','name'=>'term_host','options'=>$name_join_arr['host'],'selected'=>'','label'=>false ,
 		'div'=>false,'type'=>'select'));?>
    		   </td> 
    		   
    		   <td style="width:5%;text-align: right;">Orig Rate Table</td>
		      <td style="text-align: left;">	
				      <?php echo $form->input('orig_rate_table',
 		array('id'=>'orig_rate_table','name'=>'orig_rate_table','options'=>$name_join_arr['rate_table'],'selected'=>isset($_REQUEST['orig_rate_table']) ? $_REQUEST['orig_rate_table'] : '','label'=>false ,
 		'div'=>false,'type'=>'select'));?>
    		   </td>
    		   <td style="width:5%;text-align: right;">Term Rate Table</td>
		      <td style="text-align: left;">	
				       <?php echo $form->input('term_rate_table',
 		array('id'=>'term_rate_table','name'=>'term_rate_table','options'=>$name_join_arr['rate_table'],'selected'=>isset($_REQUEST['term_rate_table']) ? $_REQUEST['term_rate_table'] : '','label'=>false ,
 		'div'=>false,'type'=>'select'));?>
    		   </td> 
		 </tr>
		
		<tr>
		      <td style="width:5%;text-align: right;">Rerate Mothod</td>
		      <td style="text-align: left;">	
				      <select id="rerate_mothod" name="invoices_separate" class="input in-select">
    <option selected="selected" value="1">New ORIG Rate Table</option>
    <option value="2">New TERM Rate Table</option>
    <option value="3">No Rate Handling</option></select>
    		   </td>
    		   <td style="width:5%;text-align: right;">Output Mothod</td>
		      <td style="text-align: left;">	
				      <select id="output_mothod" name="output_mothod" class="input in-select">
    <option selected="selected" value="html">HTML</option>
    <option value="csv">CSV</option></select>
    		   </td>
    		   <td style="width:5%;text-align: right;"></td>
		      <td style="text-align: left;">	     
    		   </td>     		   
    		   <td style="width:5%;text-align: right;"></td>
		      <td style="text-align: left;"></td>
    		   <td style="width:5%;text-align: right;"></td>
		      <td style="text-align: left;"></td> 
		 </tr>
		
		</table>
		
		
	
	</form>
</fieldset>
	<script language="JavaScript" type="text/javascript">
	
			var _ss_ids_client_orig = {'orig_id_clients': 'query-orig_id_clients', 'orig_id_clients_name': 'query-orig_id_clients_name', 'account': 'query-account', 'tz': 'query-tz', 'id_currencies': 'query-id_currencies'};
			var _ss_ids_client_term = {'term_id_clients': 'query-term_id_clients', 'term_id_clients_name': 'query-term_id_clients_name', 'account': 'query-account', 'tz': 'query-tz', 'id_currencies': 'query-id_currencies'};
			function showClients (orig_term)
			{
						if ('orig' == orig_term)
						{
			    			ss_ids_custom['client'] = _ss_ids_client_orig;
						}
						else
						{
							ss_ids_custom['client'] = _ss_ids_client_term;
						}
			    winOpen('<?php echo $this->webroot?>clients/ss_client?types=2&type=0&orig_term='+orig_term, 500, 530);
			
			}
			function switchSelection()
			{
			    var t = $('#container .list thead :checkbox');
			    t.closest('table').find('tbody :checkbox')
			     .attr('checked', t.attr('checked'));
			}
			
	</script>
<ul class="tabs">
	    <li  class="active">
	    	<a href="<?php echo $this->webroot?>cdrrerates/view">
	    		<img width="16" height="16" src="<?php echo $this->webroot ?>images/menuIcon.gif">List
	    	</a>
	    </li>
	    <!--<li>
	    	<a href="<?php echo $this->webroot?>uploads/cdrrerate">
	    		<img width="16" height="16" src="<?php echo $this->webroot ?>images/import.png"> <?php echo __('import',true);?>
	    	</a>
	    </li> 
	    <li>
	    	<a href="<?php echo $this->webroot?>downloads/cdrrerate">
	    		<img width="16" height="16" src="<?php echo $this->webroot ?>images/export.png"> <?php echo __('export',true);?>
	    	</a>
	    </li>   
	--></ul>
	
	
	<fieldset><legend>Cdr Rerate</legend>
<?php 		
			$mydata = $p->getDataArray();
			$loop = count($mydata); 
			if(empty($mydata)){
			?>
<div class="msg"><?php  echo __('no_data_found') ?>.</div>
<?php 
			}else{
?>
<form action="rerate_cdr" method="post" id="statistics-form">
<div>
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
</div>
<?php }?>
</div>
<script type="text/javascript">
//<![CDATA[
function switchSelection()
{
	var t1;
	t1 = $('#container .list :checkbox');  
	t1.closest('table').find('tbody :checkbox').attr('checked',t1.attr('checked'));
}

//]]>
</script> 
