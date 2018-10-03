<?php
class CdrMem extends AppModel
{
 
    var $name = 'CdrMem';
    var $useTable = 'client_cdr';
    var $primaryKey = 'id';
    var $useDbConfig = 'mem_mysql';


    public function __construct(){
        Configure::load('myconf');
        parent::__construct();
    }


    public function get_trunk_id($name){

        $sql = "select id from trunk where name = '$name'";
        $data = $this->query($sql);
        return $data[0]['trunk']['id'];
    }

    public function get_busy_call_code($type){

        if ($type){
            $field = "release_cause_from_protocol_stack";
        }else{
            $field = "binary_value_of_release_cause_from_protocol_stack";
        }
        $sql = "select id from cause where name like '486%'";
        $data = $this->query($sql);
        $tmp_arr = array();
        foreach ($data as $item){
            $code = $item['cause']['id'];
            $tmp_arr[] = "when $code then 1";
        }
        if (empty($tmp_arr)){
            return "0 as busy_calls";
        }
        return "sum(case $field " . implode(' ',$tmp_arr) . " else 0 end) as busy_calls";
    }

    public function get_cancel_call_code($type){

        if ($type){
            $field = "release_cause_from_protocol_stack";
        }else{
            $field = "binary_value_of_release_cause_from_protocol_stack";
        }
        $sql = "select id from cause where name like '487%'";
        $data = $this->query($sql);
        $tmp_arr = array();
        foreach ($data as $item){
            $code = $item['cause']['id'];
            $tmp_arr[] = "when $code then 1";
        }
        if (empty($tmp_arr)){
            return "0 as cancel_calls";
        }
        return "sum(case $field " . implode(' ',$tmp_arr) . " else 0 end) as cancel_calls";
    }


    public function _get_params($start_date, $end_date, $type,$egress_trunk_name,$ingress_trunk_name){
        $field_arr = array();
        $out_field_arr = array();
        $group_arr = array();
        $show_fields = array();
        $order_num = 0;
        $where_arr = array();
        $group_select_arr = array();

//        echo "<pre >";var_dump($_GET);die;
        if (isset($_GET['group_by_date']) && !empty($_GET['group_by_date']))
        {
            $time_format = str_replace(array('YYYY','MM','DD','HH24'),array('%Y','%m','%d','%H'),$_GET['group_by_date']);
            array_push($field_arr, "DATE_FORMAT(FROM_UNIXTIME(`start_time_of_date` div 1000000), '$time_format') as group_time");
            array_push($out_field_arr, "DATE_FORMAT(FROM_UNIXTIME(`start_time_of_date` div 1000000), '$time_format') as group_time");
            $show_fields['group_time'] = "group_time";
            array_push($group_arr, "group_time");
            $order_num++;
        }


//        if (isset($_GET['route_prefix']) && $_GET['route_prefix'] != 'all')
//        {
//            array_push($where_arr, "route_prefix = '{$_GET['route_prefix']}'");
//        }

        if (isset($_GET['ingress_rate_table']) && $_GET['ingress_rate_table'] != 'all')
        {
            array_push($where_arr, "ingress_client_rate_table_id = {$_GET['ingress_rate_table']}");
        }

        if (isset($_GET['ingress_routing_plan']) && $_GET['ingress_routing_plan'] != 'all')
        {

            array_push($where_arr, "route_plan = {$_GET['ingress_routing_plan']}");
        }

        if (isset($_GET['server_ip']) && $_GET['server_ip'] != '')
        {
            array_push($where_arr, "exists (select 1 from hostname where name = '{$_GET['server_ip']}' and id = $this->useTable.origination_destination_host_name)");
        }

//        if (isset($_GET['orig_rate_type']) && $_GET['orig_rate_type'] != '0')
//        {
//            if ($_GET['orig_rate_type'] == '1')
//            {
//                array_push($where_arr, "orig_jur_type = 0");
//            }
//            elseif ($_GET['orig_rate_type'] == '2')
//            {
//                array_push($where_arr, "orig_jur_type in (1, 2)");
//            }
//            elseif ($_GET['orig_rate_type'] == '3')
//            {
//                array_push($where_arr, "orig_jur_type in (3, 4)");
//            }
//        }
//
//        if (isset($_GET['term_rate_type']) && $_GET['term_rate_type'] != '0')
//        {
//            if ($_GET['term_rate_type'] == '1')
//            {
//                array_push($where_arr, "term_jur_type = 0");
//            }
//            elseif ($_GET['term_rate_type'] == '2')
//            {
//                array_push($where_arr, "term_jur_type in (1, 2)");
//            }
//            elseif ($_GET['term_rate_type'] == '3')
//            {
//                array_push($where_arr, "term_jur_type in (3, 4)");
//            }
//        }

        if (isset($_GET['ingress_client_id']) && !empty($_GET['ingress_client_id']))
            array_push($where_arr, "ingress_client_id = {$_GET['ingress_client_id']}");

        if (isset($_GET['orig_country']) && !empty($_GET['orig_country']))
            array_push($where_arr, "orig_country = '{$_GET['orig_country']}'");
        if (isset($_GET['orig_code_name']) && !empty($_GET['orig_code_name']))
            array_push($where_arr, "orig_code_name = '{$_GET['orig_code_name']}'");
//        if (isset($_GET['orig_code']) && !empty($_GET['orig_code']))
//            array_push($where_arr, "orig_code::prefix_range <@  '{$_GET['orig_code']}'");
        if (isset($_GET['egress_client_id']) && !empty($_GET['egress_client_id']))
            array_push($where_arr, "egress_client_id = {$_GET['egress_client_id']}");

        if (isset($_GET['term_country']) && !empty($_GET['term_country']))
            array_push($where_arr, "term_country = '{$_GET['term_country']}'");
        if (isset($_GET['term_code_name']) && !empty($_GET['term_code_name']))
            array_push($where_arr, "term_code_name = '{$_GET['term_code_name']}'");
//        if (isset($_GET['term_code']) && !empty($_GET['term_code']))
//            array_push($where_arr, "term_code::prefix_range <@ '{$_GET['term_code']}'");

        if (isset($_GET['egress_id']) && !empty($_GET['egress_id']))
        {
//            $trunk_id = $this->get_trunk_id($egress_trunk_name);
            array_push($where_arr, "egress_id = {$_GET['egress_id']}");
        }

        if (isset($_GET['ingress_id']) && !empty($_GET['ingress_id']))
        {
//            $trunk_id = $this->get_trunk_id($ingress_trunk_name);
//            array_push($where_arr, "trunk_id_origination = $trunk_id");
            array_push($where_arr, "ingress_id = {$_GET['ingress_id']}");
        }


        if (isset($_GET['group_select']) && !empty($_GET['group_select']))
        {
            $group_select_arr = $_GET['group_select'];
            foreach ($group_select_arr as $group_select)
            {
                if (!empty($group_select) && !in_array($group_select, $group_arr))
                {
                    array_push($group_arr, $group_select);
//                    if ($group_select == 'ingress_id'){
//                        array_push($group_arr, 'trunk_id_origination');
//                    }else{
//                        array_push($group_arr, $group_select);
//                    }
                    if ($group_select == 'ingress_client_id'){

                        array_push($out_field_arr, "ingress_client_id");
                        array_push($field_arr, "ingress_client_id");
                    }
                    elseif ($group_select == 'egress_client_id'){

                        array_push($out_field_arr, "egress_client_id");
                        array_push($field_arr, "egress_client_id");
                    }
                    elseif ($group_select == 'ingress_id'){

                        array_push($out_field_arr, "ingress_id");
                        array_push($field_arr, "ingress_id");
                    }
                    elseif ($group_select == 'egress_id'){

                        array_push($out_field_arr, "egress_id");
                        array_push($field_arr, "egress_id");
                    }
                    elseif ($group_select == 'ingress_country'){

                        array_push($out_field_arr, "orig_country as ingress_country");
                        array_push($field_arr, "orig_country");
                    }
                    elseif ($group_select == 'ingress_code_name'){

                        array_push($out_field_arr, "orig_code_name as ingress_code_name");
                        array_push($field_arr, "orig_code_name");
                    }
                    elseif ($group_select == 'ingress_code'){

                        array_push($out_field_arr, "orig_code as ingress_code");
                        array_push($field_arr, "orig_code");
                    }
                    elseif ($group_select == 'egress_country'){

                        array_push($out_field_arr, "term_country as egress_country");
                        array_push($field_arr, "term_country");
                    }
                    elseif ($group_select == 'egress_code_name'){

                        array_push($out_field_arr, "term_code_name as egress_code_name");
                        array_push($field_arr, "term_code_name");
                    }
                    elseif ($group_select == 'egress_code'){

                        array_push($out_field_arr, "term_code as egress_code");
                        array_push($field_arr, "term_code");
                    }
                    elseif ($group_select == 'ingress_rate'){

                        array_push($out_field_arr, 'ingress_client_rate as ingress_rate');
                        array_push($field_arr, 'ingress_client_rate');
                    }
                    else{

                        array_push($out_field_arr, $group_select);
                        array_push($field_arr, $group_select);
                    }
                    $show_fields[$group_select] = $group_select;
                    $order_num++;
                }
            }
        }

        if (isset($_GET['rate_display_as']) && $_GET['rate_display_as'] == 1)
        {
            if ($type == 1)
            {
                array_push($group_arr, 'ingress_client_rate');
                array_push($out_field_arr, 'ingress_client_rate as actual_rate');
                array_push($field_arr, 'ingress_client_rate');
            }
            else
            {
                array_push($group_arr, 'egress_rate');
                array_push($out_field_arr, 'egress_rate as actual_rate');
                array_push($field_arr, 'egress_rate');
            }
        }

        $fields = "";
        $out_fields = "";
        $groups = "";
        $orders = "";
        $wheres = "";

        //cascade_summary group by carrier
        if(isset($_GET['cascade_summary_group_by']) && $_GET['cascade_summary_group_by'] == 'carrier'){
            $out_field_arr = array_reverse($out_field_arr);
            $field_arr = array_reverse($field_arr);
            $group_arr = array_reverse($group_arr);
        }

        if (count($out_field_arr))
        {
            $out_fields = implode(',', $out_field_arr) . ",";
        }
        if (count($field_arr))
        {
            $fields = implode(',', $field_arr) . ",";
        }

        if (count($group_arr))
        {
            $groups = "GROUP BY " . implode(',', $group_arr);
        }

        if ($order_num > 0)
        {
//            $orders = "ORDER BY " . implode(',', range(1, $order_num));
        }


        if (count($where_arr))
        {
            $wheres = " and " . implode(' and ', $where_arr);
        }

        $sst_user_id = $_SESSION['sst_user_id'];

//      查询权限

        $cancel_call_sql = $this->get_cancel_call_code($type);
        $busy_call_sql = $this->get_busy_call_code($type);

        $start_time = strtotime($start_date) * 1000000;
        $end_time = strtotime($end_date) * 1000000;

        return array($out_fields,$busy_call_sql,$cancel_call_sql,$groups,$orders,$start_time,$end_time,$wheres);
    }


    public function get_summary_report_from_client_cdr($start_date, $end_date, $type,$egress_trunk_name = '',$ingress_trunk_name = '')
    {
        list($out_fields,$busy_call_sql,$cancel_call_sql,$groups,$orders,$start_time,$end_time,$wheres) = $this->_get_params($start_date, $end_date, $type,$egress_trunk_name,$ingress_trunk_name);

        if ($type == 1){
            $now = date('Ymd');

            $sql = <<<SQL
SELECT
$out_fields
sum(call_duration) as duration,
sum(ingress_client_bill_time) as bill_time,
sum(ingress_client_cost) as call_cost,
sum(egress_cost) as egress_cost,
count(*) as total_calls,
count(case when call_duration > 0 then 1 else null end)as not_zero_calls,
count(case when egress_id is not null then 1 else null end) as success_calls,
$busy_call_sql,
sum(case when call_duration > 0 then pdd else 0 end) as pdd,
$cancel_call_sql,
sum(case when ingress_rate_type = 1 then ingress_client_cost else 0 end) as inter_cost,
sum(case when ingress_rate_type = 2 then ingress_client_cost else 0 end) as intra_cost,
sum(case when ingress_rate_type = 3 then ingress_client_cost else 0 end) as ij_cost,
sum(case when ingress_rate_type = 5 then ingress_client_cost else 0 end) as local_cost,
count(call_duration <= 12) as sd_count,
count(CASE WHEN release_cause = 13 THEN 1 ELSE NULL END) AS nrf_count,
count(CASE WHEN release_cause = 6 THEN 1 ELSE NULL END) AS call_limit,
count(CASE WHEN release_cause = 7 THEN 1 ELSE NULL END) AS cps_limit
from cdr_report_detail{$now} where start_time_of_date between '$start_time' and  '$end_time' and final_route_indication = 'F' and o_trunk_type2!=1 $wheres
$groups $orders
SQL;
        } else if ($type == 2) {

            $sql = <<<SQL
SELECT
$out_fields
sum(call_duration) as duration,
sum(egress_bill_time) as bill_time,
sum(egress_cost) as call_cost,
count(*) as total_calls,
count(case when call_duration > 0 then 1 else null end) as not_zero_calls,
count(case when egress_id is not null then 1 else null end) as success_calls,
$busy_call_sql,
sum(case when call_duration> 0 then pdd else 0 end) as pdd,
$cancel_call_sql,
sum(case when egress_rate_type = 1 then egress_cost else 0 end) as inter_cost,
sum(case when egress_rate_type = 2 then egress_cost else 0 end) as intra_cost,
sum(case when egress_rate_type = 3 then egress_cost else 0 end) as ij_cost,
sum(case when egress_rate_type = 5 then egress_cost else 0 end) as local_cost,
count(call_duration <= 12) as sd_count,
count(CASE WHEN release_cause = 6 THEN 1 ELSE NULL END) AS call_limit,
count(CASE WHEN release_cause = 7 THEN 1 ELSE NULL END) AS cps_limit
from $this->useTable where start_time_of_date between '$start_time' and '$end_time' $wheres
$groups $orders
SQL;
        }
        return $sql;
    }


    public function get_inout_from_client_cdr($start_date, $end_date,$egress_trunk_name = '',$ingress_trunk_name = '')
    {
        list($out_fields,$busy_call_sql,$cancel_call_sql,$groups,$orders,$start_time,$end_time,$wheres) = $this->_get_params($start_date, $end_date, 1,$egress_trunk_name,$ingress_trunk_name);

        $sql = <<<SQL
SELECT
$out_fields
sum(ingress_client_bill_time) as inbound_bill_time,
sum(ingress_client_cost) as inbound_call_cost,
sum(egress_bill_time) as outbound_bill_time,
sum(egress_cost) as outbound_call_cost,
sum(call_duration) as duration,
count(*) as total_calls,
count(case when call_duration > 0 then 1 else null end) as not_zero_calls,
count(case when egress_id is not null then 1 else null end) as success_calls,
$busy_call_sql,
sum(case when call_duration > 0 then pdd else 0 end) as pdd
from $this->useTable where start_time_of_date between '$start_time' and  '$end_time' and final_route_indication = 'F' $wheres
$groups $orders
SQL;
        return $sql;
    }


    public function get_daily_data($max_report_time){
        $min_time = (strtotime($max_report_time) + 3600) * 1000000;
        $sql = <<<SQL
SELECT sum(egress_cost) as egress_cost, sum(ingress_client_cost) as ingress_cost,sum(ingress_client_bill_time) as call_second
from $this->useTable WHERE start_time_of_date >= $min_time
SQL;

        return $this->query($sql);
//        return $sql;
    }

    public function get_dashboard_text1($max_report_time,$data){
        if (!$max_report_time){
            $min_time = (time() - 3600)  * 1000000;
        }else{
            $min_time = (strtotime($max_report_time) + 3600) * 1000000;
        }
        $cancel_call_sql = $this->get_cancel_call_code(1);
        $busy_call_sql = $this->get_busy_call_code(1);
        $sql = <<<SQL
SELECT
sum(ingress_client_bill_time) as bill_time,
sum(ingress_client_cost) as ingress_cost,
sum(egress_cost) as egress_cost,
$cancel_call_sql,
$busy_call_sql,
count(case when call_duration > 0 then 1 else null end) as not_zero_calls
from $this->useTable WHERE  start_time_of_date > $min_time and final_route_indication = 'F'
SQL;
        $cdr_data = $this->query($sql);
        $return_data['non_zero_calls'] = $cdr_data[0][0]['not_zero_calls'] + $data['non_zero_calls'];
        $return_data['bill_time'] = $cdr_data[0][0]['bill_time'] + $data['bill_time'];
        $return_data['ingress_cost'] = $cdr_data[0][0]['ingress_cost'] + $data['ingress_cost'];
        $return_data['egress_cost'] = $cdr_data[0][0]['egress_cost'] + $data['egress_cost'];
        $return_data['cancel_calls'] = $cdr_data[0][0]['cancel_calls'] + $data['cancel_calls'];
        $return_data['busy_calls'] = $cdr_data[0][0]['busy_calls'] + $data['busy_calls'];


        $return_data['acd'] = $return_data['non_zero_calls'] != 0 ? number_format($return_data['bill_time'] / $return_data['non_zero_calls'] / 60, 2) : 0;
        $asr_ = intval($return_data['busy_calls']) + intval($return_data['cancel_calls']) + intval($return_data['non_zero_calls']);
        $return_data['asr'] = $asr_ ? number_format($return_data['non_zero_calls'] / $asr_ * 100, 2) : 0;
        $tem = $return_data['ingress_cost'] - $return_data['egress_cost'];
        $return_data['revenue'] = $return_data['ingress_cost'];
        $return_data['profitability'] = $return_data['ingress_cost'] ? number_format($tem / $return_data['ingress_cost'] * 100, 2) : 0;
        $return_data['revenue'] = number_format($return_data['revenue'], 2);
        return $return_data;

    }


    public function get_profit_from_client_cdr($start_date, $end_date, $type,$egress_trunk_name = '',$ingress_trunk_name = ''){
        list($out_fields,$busy_call_sql,$cancel_call_sql,$groups,$orders,$start_time,$end_time,$wheres) = $this->_get_params($start_date, $end_date, $type,$egress_trunk_name,$ingress_trunk_name);

        if ($type == 1) {
            $bill_time = "ingress_client_bill_time";
        } else {
            $bill_time = "egress_bill_time";
        }

        $sql = <<<SQL
SELECT
$out_fields
sum(ingress_client_cost) as inbound_call_cost,
sum(egress_cost) as outbound_call_cost,
sum(call_duration) as duration,
count(*) as total_calls,
sum($bill_time) as bill_time,
count(case when call_duration > 0 then 1 else null end) as not_zero_calls,
count(case when egress_id is not null then 1 else null end) as success_calls
from $this->useTable where start_time_of_date between '$start_time' and  '$end_time' and final_route_indication = 'F' $wheres
$groups $orders
SQL;
        return $sql;
    }

    public function get_location_from_client_cdr($start_date, $end_date,$egress_trunk_name = '',$ingress_trunk_name = ''){

        list($out_fields,$busy_call_sql,$cancel_call_sql,$groups,$orders,$start_time,$end_time,$wheres) = $this->_get_params($start_date, $end_date, 1,$egress_trunk_name,$ingress_trunk_name);

        $sql = <<<SQL
SELECT
$out_fields
sum(ingress_client_cost) as inbound_call_cost,
sum(egress_cost) as outbound_call_cost,
sum(call_duration) as duration,
count(*) as total_calls
from $this->useTable where start_time_of_date between '$start_time' and  '$end_time' and final_route_indication = 'F' $wheres
$groups $orders
SQL;
        return $sql;

    }

    public function get_qos_summary_report_from_client_cdr($start_date, $end_date, $type,$egress_trunk_name = '',$ingress_trunk_name = ''){

        list($out_fields,$busy_call_sql,$cancel_call_sql,$groups,$orders,$start_time,$end_time,$wheres) = $this->_get_params($start_date, $end_date, $type,$egress_trunk_name,$ingress_trunk_name);

        if ($type == 1){

            $sql = <<<SQL
SELECT
$out_fields
sum(call_duration) as duration,
sum(ingress_client_bill_time) as bill_time,
count(*) as total_calls,
count(case when call_duration > 0 then 1 else null end)as not_zero_calls,
count(case when egress_id is not null then 1 else null end) as success_calls,
$busy_call_sql,
sum(case when call_duration > 0 then pdd else 0 end) as pdd,
$cancel_call_sql
from $this->useTable where start_time_of_date between '$start_time' and  '$end_time' and final_route_indication = 'F' $wheres
$groups $orders
SQL;
        } else if ($type == 2) {

            $sql = <<<SQL
SELECT
$out_fields
sum(call_duration) as duration,
sum(egress_bill_time) as bill_time,
count(*) as total_calls,
count(case when call_duration > 0 then 1 else null end)as not_zero_calls,
count(case when egress_id is not null then 1 else null end) as success_calls,
$busy_call_sql,
sum(case when call_duration > 0 then pdd else 0 end) as pdd,
$cancel_call_sql
from $this->useTable where start_time_of_date between '$start_time' and  '$end_time' and final_route_indication = 'F' $wheres
$groups $orders
SQL;
        }
        return $sql;

    }

    public function get_usage_from_client_cdr($start_date, $end_date, $type,$egress_trunk_name = '',$ingress_trunk_name = ''){

        list($out_fields,$busy_call_sql,$cancel_call_sql,$groups,$orders,$start_time,$end_time,$wheres) = $this->_get_params($start_date, $end_date, $type,$egress_trunk_name,$ingress_trunk_name);
        $is_final_where = '';
        if ($type == 1) {
            $is_final_where = " and final_route_indication = 'F'";
        }

        $sql = <<<SQL
SELECT
$out_fields
sum(call_duration) as duration,
count(*) as cdr_count
from $this->useTable where start_time_of_date between '$start_time' and  '$end_time' $is_final_where $wheres
$groups $orders
SQL;

        return $sql;
    }

    public function getDailyDateUsage($start_date, $end_date, $ingress_client_id)
    {
        $start_time = strtotime($start_date) * 1000000;
        $end_time = strtotime($end_date) * 1000000;
        $wheres = "where start_time_of_date between '$start_time' and  '$end_time' and ingress_client_id = {$ingress_client_id} ";

        $sql = <<<SQL
        SELECT sum(call_duration) as total_time, sum(ingress_client_bill_time) as bill_time, 
        (SELECT count(*) FROM $this->useTable as t2 $wheres and t2.ingress_client_id = t1.ingress_client_id and t2.call_duration > 0) as not_zero_calls,
        (SELECT count(*) FROM $this->useTable as t2 $wheres and t2.ingress_client_id = t1.ingress_client_id and t2.final_route_indication = 'F') as total_calls
        from $this->useTable as t1 $wheres
        GROUP BY ingress_client_id, ingress_id  ORDER BY 1,2
SQL;
        return $this->query($sql);
    }

    public function getDailyUsageOrig($start_date, $end_date, $type,$egress_trunk_name = '',$ingress_trunk_name = '')
    {
        $start_time = strtotime($start_date) * 1000000;
        $end_time = strtotime($end_date) * 1000000;
        $wheres = "where start_time_of_date between '$start_time' and  '$end_time'";

        $sql = <<<SQL
        SELECT
        ingress_id, ingress_client_id, '{$end_date}' as report_time, sum(call_duration) as total_time, sum(ingress_client_bill_time) as bill_time, 
        (SELECT count(*) FROM $this->useTable as t2 $wheres and t2.ingress_client_id = t1.ingress_client_id and t2.call_duration > 0) as not_zero_calls,
        (SELECT count(*) FROM $this->useTable as t2 $wheres and t2.ingress_client_id = t1.ingress_client_id and t2.call_duration >= 6) as calls_6,
        (SELECT count(*) FROM $this->useTable as t2 $wheres and t2.ingress_client_id = t1.ingress_client_id and t2.call_duration > 30) as calls_30,
        (SELECT count(*) FROM $this->useTable as t2 $wheres and t2.ingress_client_id = t1.ingress_client_id and t2.final_route_indication = 'F') as total_calls
        from $this->useTable as t1 $wheres
        GROUP BY ingress_client_id, ingress_id   ORDER BY 1,2
SQL;

//        $sql = <<<SQL
//        SELECT start_time_of_date, from_unixtime(start_time_of_date / 1000000) as report_time
//        from $this->useTable as t1 $wheres
//SQL;

        return $sql;
    }

    public function where_converter( $start, $end, $where)
    {
        //fix time
        $time_start = strtotime($start) * 1000000;
        $time_end = strtotime($end) * 1000000;
        $mem_where = str_replace('time ', 'start_time_of_date ' , $where);
        $mem_where = str_replace(local_time_to_gmt($start), $time_start, $mem_where);
        $mem_where = str_replace(local_time_to_gmt($end), $time_end, $mem_where);
        $mem_where = str_replace("::prefix_range <@ '", " like '%", $mem_where);
        $mem_where = 'WHERE ' . $mem_where;

        return $mem_where;
    }

    public function get_earliest_cdr_time()
    {
        $sql = "SELECT DATE_FORMAT(FROM_UNIXTIME(MAX(`start_time_of_date`) div 1000000),'%Y-%m-%d %H:%i:%s') AS `last_time` FROM `{$this->useTable}`";
        $data = $this->query($sql);

        return $data[0][0]['last_time'];
    }


    public function get_count_cdr_for_summary_report($where = '')
    {
        $sql = "SELECT COUNT(*) as `count_cdr` FROM `{$this->useTable}` {$where}";
        $data = $this->query($sql);

        return $data[0][0]['count_cdr'];
    }

    public function get_cdr_for_summary_report($limit = 1, $offset = 0 , $where = '')
    {
        $sql = "SELECT * FROM `{$this->useTable}` {$where} ORDER BY `start_time_of_date` DESC LIMIT {$limit} OFFSET {$offset}";
        $data = $this->query($sql);

        $result = array();
        if (isset($data) && is_array($data)) {
            foreach ($data as $row) {
                $result[] = array(0 => $row['demo_cdr']);
            }
        }
        return $result;
    }


}

