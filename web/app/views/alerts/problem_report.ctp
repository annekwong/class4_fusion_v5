<script src="<?php echo $this->webroot?>js/ajaxTable.js" type="text/javascript"></script>

<ul class="breadcrumb">
    <li><?php __('You are here'); ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Monitoring') ?></li>
    <li class="divider"><i class="icon-caret-right"></i></li>
    <li><?php __('Problem Ingress Trunk') ?></li>
</ul>

<div class="heading-buttons">
    <h4 class="heading"><?php __('Problem Ingress Trunk') ?></h4>
    <div class="clearfix"></div>
</div>
<div class="separator bottom"></div>


<div class="innerLR">

    <div class="widget widget-tabs widget-body-white">
        <div class="widget-head">

<?php 
$res_type = array_keys_value($this->params,'pass.0');
$res_type = empty($res_type) ? 1 : $res_type;
$element_arr = Array('Disabled Ingress Trunk'=>Array('url'=>'alerts/report/1?res_type=1', 'icon' => 'left_arrow'), 'Disabled Egress Trunk'=>Array('url'=>'alerts/report/1?res_type=2', 'icon' => 'right_arrow'));
if ($res_type == 1)
{
	$element_arr['Problem Ingress Trunk'] = Array('url'=>'alerts/problem_report/1', 'active'=>true, 'icon' => 'unshare');
	$element_arr['Problem Egress Trunk'] = Array('url'=>'alerts/problem_report/2', 'icon' => 'share');
}
else
{
	$element_arr['Problem Ingress Trunk'] = Array('url'=>'alerts/problem_report/1', 'icon' => 'unshare');
	$element_arr['Problem Egress Trunk'] = Array('url'=>'alerts/problem_report/2', 'active'=>true, 'icon' => 'share');
}
$element_arr['Priority Trunk'] = Array('url'=>'alerts/priority_report', 'icon' => 'retweet_2');
$element_arr['Alternative Route Trunk'] = Array('url'=>'alerts/alternative_route_report', 'icon' => 'spade');
$element_arr['No Destination'] = Array('url'=>'alerts/no_destination_report', 'icon' => 'pin');
echo $this->element('tabs', array('tabs'=>$element_arr));

?>
        </div>
        <div class="widget-body">
          <div class="widget-body">
            <div class="filter-bar">
                <form method="get" id="queryform">
                    <input type="hidden" name="adv_search" value="1" class="input in-hidden">
                    <input type="hidden" id="isDelete" name="isDelete" value="0" class="input in-hidden" />
                    <!-- Filter -->
                    <div>
                        <label><?php __('Rule:'); ?></label>
                        <select class="input-medium" name="s_rule">
                            <option></option>
                            <?php foreach($name_join_arr['rule'] as $k => $v): ?>
                            <option value="<?php echo $k; ?>" <?php if(isset($_GET['s_rule']) && $_GET['s_rule'] == $k) echo 'selected'; ?>><?php echo $v; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <label><?php __('Carrier:'); ?></label>
                        <select class="input-medium" name="s_client">
                            <option></option>
                            <?php foreach($name_join_arr['client'] as $k => $v): ?>
                            <option value="<?php echo $k; ?>" <?php if(isset($_GET['s_client']) && $_GET['s_client'] == $k) echo 'selected'; ?>><?php echo $v; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <label><?php __('Trunk:'); ?></label>
                        <select class="input-medium" name="s_trunk">
                            <option></option>
                            <?php foreach($name_join_arr['resource'] as $k => $v): ?>
                            <option value="<?php echo $k; ?>" <?php if(isset($_GET['s_trunk']) && $_GET['s_trunk'] == $k) echo 'selected'; ?>><?php echo $v; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <label><?php __('Begin Date:'); ?></label>
                        <input class="input-medium" type="text" class="wdate input in-text in-input" type="text" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" name="start_date" value="<?php echo isset($_GET['start_date']) ? $_GET['start_date'] : ''; ?>" />
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <label><?php __('End Date:'); ?></label>
                        <input class="input-medium" type="text" class="wdate input in-text in-input" type="text" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss',lang:'en'})" name="end_date" value="<?php echo isset($_GET['end_date']) ? $_GET['end_date'] : ''; ?>" />
                    </div>
                    <!-- // Filter END -->
                    <!-- Filter -->
                    <div>
                        <button name="submit" class="btn query_btn"><?php __('Query'); ?></button>
                    </div>
                    <!-- // Filter END -->
                </form>
                <div class="clearfix"></div>
            </div>   
              <div class="separator bottom"></div>
            
<?php 			$mydata =$p->getDataArray();
			$loop = count($mydata); 
			if(empty($mydata)){
			?>
<h2 class="msg center"><?php echo __('no_data_found',true);?></h2>
<?php }else{

?>
<div class="clearfix"></div>
<table class="list  footable table table-striped dynamicTable tableTools table-bordered  table-white table-primary">
<!--
<col width="9%">
<col width="9%">
<col width="9%">
<col width="9%">
<col width="9%">
<col width="9%">
<col width="9%">
<col width="9%">
<col width="9%">
<col width="9%">
<col width="9%">
-->
<thead>
<tr>
 			<th ><?php echo __('Rule',true);?></th>
		 <th > <?php echo __('Trunk',true); ?>  </th>
                 <th > <?php echo 'Is Disabled'; ?>  </th>
		 <th > <?php echo __('Host'); ?>  </th>
		 <th > <?php echo __('action',true);?>  </th>
		 <th > <?php echo __('Executing Time'); ?>  </th>
		 <th > <?php echo __('Code'); ?>  </th>
		 <th > <?php echo __('Old Priority'); ?>  </th>
		 <th > <?php echo __('New Priority'); ?>  </th>
		</tr>
</thead>
<tbody>
		<?php 

			for ($i=0;$i<$loop;$i++){
		?>
		<tr class="row-1">
		  <td > <?php echo $mydata[$i][0]['rule_name']; ?>  </td>
		  <td > <?php echo $mydata[$i][0]['alias']; ?>  </td>
                  <td > <?php echo $mydata[$i][0]['bool']; ?>  </td>
		 <td > <?php echo $mydata[$i][0]['host_id']==0?'All':$mydata[$i][0]['host_id']; ?>  </td>
		 <td > <?php echo !empty($name_join_arr['action_info'][$mydata[$i][0]['alert_action_id']]) ? $name_join_arr['action'][$mydata[$i][0]['alert_action_id']] : ''; ?>  </td>
		 <td > <?php echo $mydata[$i][0]['event_time']; ?>  </td>
		 
		 <td> <?php echo !empty($mydata[$i][0]['route_strategy_id']) && !empty($name_join_arr['route'][$mydata[$i][0]['route_strategy_id']]['digits']) ? $name_join_arr['route'][$mydata[$i][0]['route_strategy_id']]['digits'] : ''; ?>  </td>	
		 <td > <?php echo $mydata[$i][0]['old_priority']; ?>  </td>
		 <td > <?php echo $mydata[$i][0]['new_priority']; ?>  </td>	
		</tr>
			<?php }?>
		</tbody>
		</table>
	</div>
<div class="row-fluid">
                <div class="pagination pagination-large pagination-right margin-none">
                    <?php echo $this->element('page'); ?>
                </div> 
            </div>
            <div class="clearfix"></div>
<?php }?>
</div>
</div>

<script type="text/javascript">
$(function() {
    $('#deleteall').click(function() {
        $('#isDelete').val('1');
        $('#queryform').submit();
    });
});
</script>