<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>
<div id="title">
   <h1><?php __('Alert')?>&gt;&gt;<?php echo __('View Execution Log')?></h1>  
		<ul id="title-search"><!--
        	<li>
        		<?php //Pr($searchkey);    //****************************模糊搜索**************************?>
        		<form  action="<?php echo $this->webroot;?>alerts/view_log"  method="get">
        			<input type="text" id="search-_q_j" class="in-search default-value input in-text defaultText" title="<?php echo __('search')?>..." value="<?php if(isset($searchkey)){echo $searchkey;}else{ echo __('pleaseinputkey');}?>"  onclick="this.value=''" name="searchkey">
        		</form>
        	</li>
       --></ul>
       <ul id="title-menu">
        	<?php if (1) {?>
        	<li>
    			<a class="link_back" href="<?php echo $this->webroot;?>alerts/view_log">
    				<img width="16" height="16"  alt="" src="<?php echo $this->webroot?>images/icon_back_white.png">
    				&nbsp;<?php echo __('goback')?>
    			</a>
    		</li>
        	<?php }?>
        	<!--<li>
        		<a title="<?php echo __('creataction')?>"  href="<?php echo $this->webroot?>alerts/add_action">
       				<img width="16" height="16" alt="" src="<?php echo $this->webroot?>images/add.png"><?php echo __('createnew')?>
       			</a>
       		</li>
       --></ul>
    </div>
<div id="container">
<?php 			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			if(empty($mydata)){
			?>
<div class="msg"><?php echo __('no_data_found',true);?></div>
<?php }else{

?>
<div id="toppage"></div>
<table class="list">
<col width="20%">
<col width="20%">
<col width="60%">
<thead>
<tr>
 			<td ><?php echo __('Trunk',true);?> </td>
 			<td ><?php echo __('host',true);?> </td>
		 <td > <?php echo __('Action',true); ?>  </td>
		</tr>
</thead>
<tbody>
		<?php 
date_default_timezone_set('UTC');
			for ($i=0;$i<$loop;$i++){
		?>
		<tr class="row-1">
		  <td align="center">	
		 <?php echo empty($name_join_arr['resource'][$mydata[$i][0]['res_id']]) ? '' : $name_join_arr['resource'][$mydata[$i][0]['res_id']];?>	
			</td>
		  <td align="center">	
		<?php echo empty($name_join_arr['host'][$mydata[$i][0]['host_id']]) ? 'ALL' : $name_join_arr['host'][$mydata[$i][0]['host_id']]; ?>		
			</td>
			<td align="center">		
			<?php 
			
			switch($mydata[$i][0]['event_type'])
			{
				case 1:
					$duration = empty($name_join_arr['action_info'][$mydata[$i][0]['alert_action_id']]['disable_duration']) ? 0 : $name_join_arr['action_info'][$mydata[$i][0]['alert_action_id']]['disable_duration'];					
					echo "Disable Trunk until ",date("m/d/Y H:i:s", strtotime($mydata[$i][0]['event_time'])+$duration*60);					
					break;
				case 2:
					$duration = empty($name_join_arr['action_info'][$mydata[$i][0]['alert_action_id']]['disable_duration']) ? 0 : $name_join_arr['action_info'][$mydata[$i][0]['alert_action_id']]['disable_duration'];					
					echo "Disable Host until ",date("m/d/Y H:i:s", strtotime($mydata[$i][0]['event_time'])+$duration*60);					
					break;
				case 3:
					echo "Enable Trunk";
					break;
				case 4:
					echo "Enable Host";
					break;
				case 5:
					$duration = empty($name_join_arr['action_info'][$mydata[$i][0]['alert_action_id']]['code_trunk_disable_duration']) ? 0 : $name_join_arr['action_info'][$mydata[$i][0]['alert_action_id']]['code_trunk_disable_duration'];	
					echo "Disable for code ", empty($name_join_arr['product'][$mydata[$i][0]['product_prefix_id']]) ? 'ALL' : $name_join_arr['product'][$mydata[$i][0]['product_prefix_id']], date("m/d/Y H:i:s", strtotime($mydata[$i][0]['event_time'])+$duration*60);
					break;
				case 6:
					echo "Enable Code Trunk";	
					break;
				case 7:
					echo "Change Priority from {$mydata[$i][0]['old_priority']} to {$mydata[$i][0]['new_priority']}";
					break;
				case 8:
					echo "Email alert to ", $mydata[$i][0]['email_addr'];
					break;
				default:
					echo '';
			}
			?>	
			</td>
		</tr>
			<?php }?>
		</tbody>
		</table>
	</div>
<div>
<div id="tmppage">

<?php echo $this->element('page');?>



</div>

<?php }?>
</div>
