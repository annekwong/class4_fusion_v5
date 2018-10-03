<?php foreach ($show_fields as $field): ?><?php echo $replace_fields[$field]; ?>,<?php endforeach; ?><?php if($type){ ?><?php __('Egress Carrier')?>,<?php __('Egress Trunk')?>,<?php }else{ ?><?php __('Ingress Carrier')?>,<?php __('Ingress Trunk')?>,<?php } ?><?php __('Carrier Limit')?>,,<?php __('Trunk Limit')?>,,<?php __('Call Attempt')?>,<?php __('Failure Cause');echo "\n";?>
<?php for($i=0;$i<count($show_fields);$i++):?>,<?php endfor; ?>,,<?php __('Call Limit')?>,<?php __('CPS Limit')?>,<?php __('Call Limit')?>,<?php __('CPS Limit')?>,,<?php __('Carrier Call Limit')?>,<?php __('Carrier CPS Limit')?>,<?php __('Trunk Call Limit')?>,<?php __('Trunk CPS Limit');echo "\n";?>
<?php
$total_call = 0;
$total_carrier_call_limit = 0;
$total_carrier_cps_limit = 0;
$total_trunk_call_limit = 0;
$total_trunk_cps_limit = 0;
foreach ($data as $item){
    $total_call += $item[0]['total_call'];
    $total_carrier_call_limit += $item[0]['carrier_call_limit'];
    $total_carrier_cps_limit += $item[0]['carrier_cps_limit'];
    $total_trunk_call_limit += $item[0]['trunk_call_limit'];
    $total_trunk_cps_limit += $item[0]['trunk_cps_limit'];
    foreach (array_keys($show_fields) as $key){ echo $item[0][$key];echo ',';}
    echo isset($client_limit[$item[0]['client_id']]) ? $client_limit[$item[0]['client_id']]['name'] : '--';echo ',';
    echo  isset($trunk_limit[$item[0]['trunk_id']]) ? $trunk_limit[$item[0]['trunk_id']]['name'] : '--';echo ',';
    echo  isset($client_limit[$item[0]['client_id']]) ? $client_limit[$item[0]['client_id']]['call_limit'] : '--';echo ',';
    echo  isset($client_limit[$item[0]['client_id']]) ? $client_limit[$item[0]['client_id']]['cps_limit'] : '--';echo ',';
    echo  isset($trunk_limit[$item[0]['trunk_id']]) ? $trunk_limit[$item[0]['trunk_id']]['call_limit'] : '--';echo ',';
    echo  isset($trunk_limit[$item[0]['trunk_id']]) ? $trunk_limit[$item[0]['trunk_id']]['cps_limit'] : '--';echo ',';
    echo $item[0]['total_call'];echo ',';
    echo $item[0]['carrier_call_limit'];echo ',';
    echo $item[0]['carrier_cps_limit'];echo ',';
    echo $item[0]['trunk_call_limit'];echo ',';
    echo $item[0]['trunk_cps_limit'];echo "\n";
}
__('Total');echo ',';for($i=0;$i<count($show_fields)+1;$i++){echo ",";}
echo "--,--,--,--,";
echo $total_call;echo ',';
echo $total_carrier_call_limit;echo ',';
echo $total_carrier_cps_limit;echo ',';
echo $total_trunk_call_limit;echo ',';
echo $total_trunk_cps_limit;echo "\n";
?>
