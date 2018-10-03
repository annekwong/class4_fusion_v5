<?php

class Cdrs extends AppModel{
    var $name = 'Cdrs';
    var $useTable = 'client_cdr';
    var $primaryKey = 'id';
    
    public function get_ingress_clients() {
        $sst_user_id = $_SESSION['sst_user_id'];
        $sql = "SELECT DISTINCT client.client_id, client.name FROM resource 
            INNER JOIN client ON resource.client_id = client.client_id WHERE 
            (exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id=client.client_id) 
OR 
exists 
(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id} and (role_name = 'admin'
or sys_role.view_all = true)))
                AND
            ingress = true ORDER BY client.name ASC";
        $result = $this->query($sql);
        return $result;
    }
    
    public function get_egress_clients() {
        $sst_user_id = $_SESSION['sst_user_id'];
        $sql = "SELECT DISTINCT client.client_id, client.name FROM resource 
            INNER JOIN client ON resource.client_id = client.client_id WHERE
(exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id=client.client_id) 
OR 
exists 
(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id} and (role_name = 'admin'
or sys_role.view_all = true)))
                AND
            egress = true ORDER BY client.name ASC";
        $result = $this->query($sql);
        return $result;
    }
    
    public function get_switch_ip() {
        $sql = "select ip from server_platform";
        $result = $this->query($sql);
        return $result;
    }
    
    public function get_ingress_trunks() {
        $sql = "select resource_id,alias from resource where ingress=true order by alias asc";
        $result = $this->query($sql);
        return $result;
    }
    
    public function get_egress_trunks() {
        $sql = "select resource_id,alias from resource where egress=true order by alias asc";
        $result = $this->query($sql);
        return $result;
    }
    
    public function get_cdrs($start_date, $end_date,  $fields, $groups, $orders, $wheres, $type, $table_name) {
        if($type == 1) {
        $sql = "SELECT
{$fields}
sum(duration) as duration,
sum(ingress_bill_time) as bill_time,
sum(ingress_call_cost) as call_cost,
sum(lnp_cost) as lnp_cost,
sum(ingress_total_calls) as total_calls,
sum(not_zero_calls) as not_zero_calls,
sum(ingress_success_calls) as success_calls,
sum(ingress_busy_calls) as busy_calls,
sum(lrn_calls) as lrn_calls,
sum(pdd) as pdd,
sum(ingress_cancel_calls) as cancel_calls
from {$table_name}
where {$wheres} report_time between '{$start_date}' and '{$end_date}' {$groups} {$orders}";
        } else if($type == 2) {
        $sql = "SELECT
{$fields}
sum(duration) as duration,
sum(egress_bill_time) as bill_time,
sum(egress_call_cost) as call_cost,
sum(egress_total_calls) as total_calls,
sum(not_zero_calls) as not_zero_calls,
sum(egress_success_calls) as success_calls,
sum(egress_busy_calls) as busy_calls,
sum(pdd) as pdd,
sum(egress_cancel_calls) as cancel_calls
from {$table_name} 
where {$wheres} report_time between '{$start_date}' and '{$end_date}' {$groups} {$orders}";
        }
        
        //echo $sql;
        //$result = $this->query($sql);
        //return $result;
        return $sql;
    }
    
    public function get_inout_cdrs($start_date, $end_date, $fields, $groups, $orders, $wheres, $table_name) {
        $sql = "select
{$fields}
sum(ingress_bill_time) as inbound_bill_time,
sum(ingress_call_cost) as inbound_call_cost,
sum(egress_bill_time) as outbound_bill_time,
sum(egress_call_cost) as outbound_call_cost,
sum(duration) as duration,
sum(ingress_total_calls) as total_calls,
sum(not_zero_calls) as not_zero_calls,
sum(ingress_success_calls) as success_calls,
sum(ingress_busy_calls) as busy_calls,
sum(pdd) as pdd
from {$table_name}
where $wheres report_time between '{$start_date}' and '{$end_date}' {$groups} {$orders}";
        //$result = $this->query($sql);
        //return $result;
        return $sql;
    }
    
    public function get_usagereport($start_date, $end_date, $fields, $groups, $orders, $wheres, $type, $table_name) {
        if($type == 1) {
            $cdr_count_field = "ingress_total_calls";
        } else {
            $cdr_count_field = "egress_total_calls";
        }
        $sql = <<<EOT
SELECT 
{$fields}    
sum({$cdr_count_field}) as cdr_count,
sum(duration) as duration
from {$table_name} 
where {$wheres} report_time between '{$start_date}' and '{$end_date}' {$groups} {$orders}
EOT;
        $result = $this->query($sql);
        return $result;
    }
    
    public function get_summary_report_from_client_cdr($start_date, $end_date, $type) {
        
        
        $field_arr = array();
        $group_arr = array();
        $show_fields = array();
        $order_num = 0;
        $where_arr = array();
        $group_select_arr = array();
        
       
        if(isset($_GET['group_by_date']) && !empty($_GET['group_by_date'])) {
            array_push($field_arr, "to_char(time, '{$_GET['group_by_date']}') as group_time");
            $show_fields['group_time'] = "group_time";
            array_push($group_arr, "to_char(time, '{$_GET['group_by_date']}')");
            $order_num++;
        }
        
        
         if(isset($_GET['route_prefix']) && $_GET['route_prefix'] != 'all'){
            array_push($where_arr, "route_prefix = '{$_GET['route_prefix']}'");  
        }
        
        if(isset($_GET['ingress_client_id']) && !empty($_GET['ingress_client_id']))
            array_push($where_arr, "ingress_client_id = '{$_GET['ingress_client_id']}'");
        if(isset($_GET['ingress_id']) && !empty($_GET['ingress_id']))
            array_push($where_arr, "ingress_id = '{$_GET['ingress_id']}'");    
        if(isset($_GET['orig_country']) && !empty($_GET['orig_country']))
            array_push($where_arr, "orig_country = '{$_GET['orig_country']}'");  
        if(isset($_GET['orig_code_name']) && !empty($_GET['orig_code_name']))
            array_push($where_arr, "orig_code_name = '{$_GET['orig_code_name']}'");  
        if(isset($_GET['orig_code']) && !empty($_GET['orig_code']))
            array_push($where_arr, "orig_code::prefix_range <@  '{$_GET['orig_code']}'");
        if(isset($_GET['egress_client_id']) && !empty($_GET['egress_client_id']))
            array_push($where_arr, "egress_client_id = '{$_GET['egress_client_id']}'");
        if(isset($_GET['egress_id']) && !empty($_GET['egress_id']))
            array_push($where_arr, "egress_id = '{$_GET['egress_id']}'");    
        if(isset($_GET['term_country']) && !empty($_GET['term_country']))
            array_push($where_arr, "term_country = '{$_GET['term_country']}'");  
        if(isset($_GET['term_code_name']) && !empty($_GET['term_code_name']))
            array_push($where_arr, "term_code_name = '{$_GET['term_code_name']}'");  
        if(isset($_GET['term_code']) && !empty($_GET['term_code']))
            array_push($where_arr, "term_code::prefix_range <@ '{$_GET['term_code']}'");
            
        if(isset($_GET['group_select']) && !empty($_GET['group_select'])) {
            $group_select_arr = $_GET['group_select'];
            foreach($group_select_arr as $group_select) {
                if(!empty($group_select)) {
                    array_push($group_arr, $group_select);
                    if($group_select == 'ingress_client_id')
                        array_push($field_arr, "(SELECT name FROM client WHERE client_id::text = ingress_client_id) AS ingress_client_id"); 
                    elseif ($group_select == 'egress_client_id')
                        array_push($field_arr, "(SELECT name FROM client WHERE client_id::text = egress_client_id) AS egress_client_id");
                    elseif ($group_select == 'ingress_id')
                        array_push($field_arr, "(SELECT alias FROM resource WHERE resource_id::text = ingress_id) AS ingress_id"); 
                    elseif ($group_select == 'egress_id')
                        array_push($field_arr, "(SELECT alias FROM resource WHERE resource_id::text = egress_id) AS egress_id"); 
                    elseif($group_select == 'ingress_country')
                        array_push($field_arr, "orig_country as ingress_country");
                    elseif($group_select == 'ingress_code_name')
                        array_push($field_arr, "orig_code_name as ingress_code_name");
                    elseif($group_select == 'ingress_code')
                        array_push($field_arr, "orig_code as ingress_code");
                    elseif($group_select == 'ingress_rate')
                        array_push($field_arr, "ingress_client_rate as ingress_rate");
                    elseif($group_select == 'egress_country')
                        array_push($field_arr, "term_country as egress_country");
                    elseif($group_select == 'egress_code_name')
                        array_push($field_arr, "term_code_name as egress_code_name");
                    elseif($group_select == 'egress_code')
                        array_push($field_arr, "term_code as egress_code");
                    else
                        array_push($field_arr, $group_select); 
                    $show_fields[$group_select] = $group_select;
                    $order_num++;
                }
            }
        }
        
        $fields = "";
        $groups = "";
        $orders = "";
        $wheres = "";
        if(count($field_arr)) {
            $fields = implode(',', $field_arr) . ",";
        }
        if(count($group_arr)) {
            $groups = "GROUP BY " . implode(',', $group_arr);
        }
        
        if($order_num > 0) {
            $orders = "ORDER BY " . implode(',', range(1, $order_num));
        }
        
        
        if(count($where_arr)) {
            $wheres = implode(' and ', $where_arr) . " and ";
        }
        
        
        if($type == 1) {
        $sql = "SELECT
{$fields}        
sum(call_duration::integer) as duration,
sum(ingress_client_bill_time::integer) as bill_time,
sum(ingress_client_cost::numeric(10,4)) as call_cost,
sum(lnp_dipping_cost::numeric(10,4)) as lnp_cost, count(*) as
total_calls, count(case when call_duration > '0' then 1 else null end)
as not_zero_calls, count(case when egress_id != '' then 1 else null end)
as success_calls,count(case when
binary_value_of_release_cause_from_protocol_stack like '486%' then 1
else null end) as busy_calls,count(case when lrn_number_vendor != '0'
then 1 else null end) as lrn_calls, sum(case when call_duration > '0'
then pdd::integer else 0 end) as pdd,count( case when
binary_value_of_release_cause_from_protocol_stack like '487%' then 1
else null end ) as cancel_calls
from client_cdr".date("Ymd")." where {$wheres} is_final_call='1' and time between '{$start_date}' and '{$end_date}' {$groups} {$orders}";
        } else if($type == 2) {
        $sql = "SELECT
{$fields}   
sum(call_duration::integer) as duration,
sum(egress_bill_time::integer) as bill_time,
sum(egress_cost::numeric(10,4)) as call_cost, count(*) as total_calls,
count(case when call_duration > '0' then 1 else null end) as
not_zero_calls, count(case when egress_id != '' then 1 else null end) as
success_calls,count(case when release_cause_from_protocol_stack like
'486%' then 1 else null end ) as busy_calls,sum(case when call_duration
> '0' then pdd::integer else 0 end) as pdd,count( case when
release_cause_from_protocol_stack like '487%' then 1 else null end ) as
cancel_calls
from client_cdr".date("Ymd")." where {$wheres} time between '{$start_date}' and '{$end_date}' {$groups} {$orders}";
        }
        
        //echo $sql;
        //$result = $this->query($sql);
        //return $result;
        return $sql;
        
    }
    
    public function get_qos_cdrs_two($sql1, $sql2, $type, $orders, $show_fields) {
        
        $fields = implode(', ', $show_fields);
        $group_by = count($show_fields) ? "group by {$fields}" : '';
        $the_fields = '';
        if(!empty($fields)) $the_fields = $fields . ',';
        if($type == 1) {
            $total_fields = "sum(duration) as duration, sum(bill_time) as bill_time, sum(call_cost) as call_cost, 
sum(lnp_cost) as lnp_cost, sum(total_calls) as total_calls, sum(not_zero_calls) as not_zero_calls, 
sum(success_calls) as success_calls, sum(busy_calls) as busy_calls, sum(lrn_calls) as lrn_calls, 
sum(pdd) as pdd, sum(cancel_calls) as cancel_calls";
        } else {
            $total_fields = "sum(duration) as duration, sum(bill_time) as bill_time, sum(call_cost) as call_cost,
sum(total_calls) as total_calls, sum(not_zero_calls) as not_zero_calls, sum(success_calls) as success_calls,
sum(busy_calls) as busy_calls, sum(pdd) as pdd, sum(cancel_calls) as cancel_calls";
        }
        $sql = <<<EOT
SELECT 
$the_fields
$total_fields
FROM 
(        
(
$sql1
)

union
(
$sql2
)
)  
as t 
$group_by $orders   
EOT;
        return $this->query($sql);
    }
    
    public function get_cdrs_two($sql1, $sql2, $type, $orders, $show_fields) {
        
        $fields = implode(', ', $show_fields);
        $group_by = count($show_fields) ? "group by {$fields}" : '';
        $the_fields = '';
        if(!empty($fields)) $the_fields = $fields . ',';
        if($type == 1) {
            $total_fields = "sum(duration) as duration, sum(bill_time) as bill_time, sum(call_cost) as call_cost, 
sum(lnp_cost) as lnp_cost, sum(total_calls) as total_calls, sum(not_zero_calls) as not_zero_calls, 
sum(success_calls) as success_calls, sum(busy_calls) as busy_calls, sum(lrn_calls) as lrn_calls, 
sum(pdd) as pdd, sum(cancel_calls) as cancel_calls";
        } else {
            $total_fields = "sum(duration) as duration, sum(bill_time) as bill_time, sum(call_cost) as call_cost,
sum(total_calls) as total_calls, sum(not_zero_calls) as not_zero_calls, sum(success_calls) as success_calls,
sum(busy_calls) as busy_calls, sum(pdd) as pdd, sum(cancel_calls) as cancel_calls";
        }
        $sql = <<<EOT
SELECT 
$the_fields
$total_fields
FROM 
(        
(
$sql1
)

union
(
$sql2
)
)  
as t 
$group_by $orders   
EOT;
        return $this->query($sql);
    }
    
    public function get_inout_from_two($sql1, $sql2,  $orders, $show_fields) {
        $fields = implode(', ', $show_fields);
        $the_fields = '';
        $group_by = count($show_fields) ? "group by {$fields}" : '';
        if(!empty($fields)) $the_fields = $fields . ',';
        $total_fields = "sum(inbound_bill_time) as inbound_bill_time, sum(inbound_call_cost) as inbound_call_cost, sum(outbound_bill_time) as outbound_bill_time, 
sum(outbound_call_cost) as outbound_call_cost, sum(duration) as duration, sum(total_calls) as total_calls, 
sum(not_zero_calls) as not_zero_calls, sum(success_calls) as success_calls, sum(busy_calls) as busy_calls, 
sum(pdd) as pdd";
        
        $sql = <<<EOT
SELECT 
$the_fields
$total_fields
FROM 
(        
(
$sql1
)

union
(
$sql2
)
)  
as t 
$group_by $orders   
EOT;
        return $this->query($sql);
    }
    
    
    public function get_inout_from_client_cdr($start_date, $end_date) {
        
        
        $field_arr = array();
        $group_arr = array();
        $show_fields = array();
        $order_num = 0;
        $where_arr = array();
        $group_select_arr = array();
        
       
        if(isset($_GET['group_by_date']) && !empty($_GET['group_by_date'])) {
            array_push($field_arr, "to_char(time, '{$_GET['group_by_date']}') as group_time");
            $show_fields['group_time'] = "group_time";
            array_push($group_arr, "to_char(time, '{$_GET['group_by_date']}')");
            $order_num++;
        }
        
        
         if(isset($_GET['route_prefix']) && $_GET['route_prefix'] != 'all'){
            array_push($where_arr, "route_prefix = '{$_GET['route_prefix']}'");  
        }
        
        if(isset($_GET['ingress_client_id']) && !empty($_GET['ingress_client_id']))
            array_push($where_arr, "ingress_client_id = '{$_GET['ingress_client_id']}'");
        if(isset($_GET['ingress_id']) && !empty($_GET['ingress_id']))
            array_push($where_arr, "ingress_id = '{$_GET['ingress_id']}'");    
        if(isset($_GET['orig_country']) && !empty($_GET['orig_country']))
            array_push($where_arr, "orig_country = '{$_GET['orig_country']}'");  
        if(isset($_GET['orig_code_name']) && !empty($_GET['orig_code_name']))
            array_push($where_arr, "orig_code_name = '{$_GET['orig_code_name']}'");  
        if(isset($_GET['orig_code']) && !empty($_GET['orig_code']))
            array_push($where_arr, "orig_code::prefix_range <@  '{$_GET['orig_code']}'");
        if(isset($_GET['egress_client_id']) && !empty($_GET['egress_client_id']))
            array_push($where_arr, "egress_client_id = '{$_GET['egress_client_id']}'");
        if(isset($_GET['egress_id']) && !empty($_GET['egress_id']))
            array_push($where_arr, "egress_id = '{$_GET['egress_id']}'");    
        if(isset($_GET['term_country']) && !empty($_GET['term_country']))
            array_push($where_arr, "term_country = '{$_GET['term_country']}'");  
        if(isset($_GET['term_code_name']) && !empty($_GET['term_code_name']))
            array_push($where_arr, "term_code_name = '{$_GET['term_code_name']}'");  
        if(isset($_GET['term_code']) && !empty($_GET['term_code']))
            array_push($where_arr, "term_code::prefix_range <@ '{$_GET['term_code']}'");
            
        if(isset($_GET['group_select']) && !empty($_GET['group_select'])) {
            $group_select_arr = $_GET['group_select'];
            foreach($group_select_arr as $group_select) {
                if(!empty($group_select)) {
                    array_push($group_arr, $group_select);
                    if($group_select == 'ingress_client_id')
                        array_push($field_arr, "(SELECT name FROM client WHERE client_id::text = ingress_client_id) AS ingress_client_id"); 
                    elseif ($group_select == 'egress_client_id')
                        array_push($field_arr, "(SELECT name FROM client WHERE client_id::text = egress_client_id) AS egress_client_id");
                    elseif ($group_select == 'ingress_id')
                        array_push($field_arr, "(SELECT alias FROM resource WHERE resource_id::text = ingress_id) AS ingress_id"); 
                    elseif ($group_select == 'egress_id')
                        array_push($field_arr, "(SELECT alias FROM resource WHERE resource_id::text = egress_id) AS egress_id"); 
                    elseif($group_select == 'ingress_country')
                        array_push($field_arr, "orig_country as ingress_country");
                    elseif($group_select == 'ingress_code_name')
                        array_push($field_arr, "orig_code_name as ingress_code_name");
                    elseif($group_select == 'ingress_code')
                        array_push($field_arr, "orig_code as ingress_code");
                    elseif($group_select == 'ingress_rate')
                        array_push($field_arr, "ingress_client_rate as ingress_rate");
                    elseif($group_select == 'egress_country')
                        array_push($field_arr, "term_country as egress_country");
                    elseif($group_select == 'egress_code_name')
                        array_push($field_arr, "term_code_name as egress_code_name");
                    elseif($group_select == 'egress_code')
                        array_push($field_arr, "term_code as egress_code");
                    else
                        array_push($field_arr, $group_select); 
                    $show_fields[$group_select] = $group_select;
                    $order_num++;
                }
            }
        }
        
        $fields = "";
        $groups = "";
        $orders = "";
        $wheres = "";
        if(count($field_arr)) {
            $fields = implode(',', $field_arr) . ",";
        }
        if(count($group_arr)) {
            $groups = "GROUP BY " . implode(',', $group_arr);
        }
        
        if($order_num > 0) {
            $orders = "ORDER BY " . implode(',', range(1, $order_num));
        }
        
        
        if(count($where_arr)) {
            $wheres = implode(' and ', $where_arr) . " and ";
        }
        
        
        $sql = "select
{$fields}
sum(ingress_client_bill_time::integer) as inbound_bill_time,
sum(ingress_client_cost::real) as inbound_call_cost,
sum(egress_bill_time::integer) as outbound_bill_time,
sum(egress_cost::real) as outbound_call_cost,
sum(call_duration::integer) as duration,
count(*) as total_calls,
count(case when call_duration > '0' then 1 else null end) as not_zero_calls,
count(case when egress_id != '' then 1 else null end) as success_calls,
count(case when release_cause_from_protocol_stack like
'486%' then 1 else null end) as busy_calls,
sum(case when call_duration > '0' then pdd::integer else 0 end) as pdd
from client_cdr
where $wheres  time between '{$start_date}' and '{$end_date}' and is_final_call = '1' {$groups} {$orders}";
        
        return $sql;
        //$result = $this->query($sql);
        //return $result;
        
        
    }
    
    public function get_usage_from_client_cdr($start_date, $end_date, $type) {
        
        
        $field_arr = array();
        $group_arr = array();
        $show_fields = array();
        $order_num = 0;
        $where_arr = array();
        $group_select_arr = array();
        
       
        if(isset($_GET['group_by_date']) && !empty($_GET['group_by_date'])) {
            array_push($field_arr, "to_char(time, '{$_GET['group_by_date']}') as group_time");
            $show_fields['group_time'] = "group_time";
            array_push($group_arr, "to_char(time, '{$_GET['group_by_date']}')");
            $order_num++;
        }
        
        
         if(isset($_GET['route_prefix']) && $_GET['route_prefix'] != 'all'){
            array_push($where_arr, "route_prefix = '{$_GET['route_prefix']}'");  
        }
        
        if(isset($_GET['ingress_client_id']) && !empty($_GET['ingress_client_id']))
            array_push($where_arr, "ingress_client_id = '{$_GET['ingress_client_id']}'");
        if(isset($_GET['ingress_id']) && !empty($_GET['ingress_id']))
            array_push($where_arr, "ingress_id = '{$_GET['ingress_id']}'");    
        if(isset($_GET['orig_country']) && !empty($_GET['orig_country']))
            array_push($where_arr, "orig_country = '{$_GET['orig_country']}'");  
        if(isset($_GET['orig_code_name']) && !empty($_GET['orig_code_name']))
            array_push($where_arr, "orig_code_name = '{$_GET['orig_code_name']}'");  
        if(isset($_GET['orig_code']) && !empty($_GET['orig_code']))
            array_push($where_arr, "orig_code::prefix_range <@  '{$_GET['orig_code']}'");
        if(isset($_GET['egress_client_id']) && !empty($_GET['egress_client_id']))
            array_push($where_arr, "egress_client_id = '{$_GET['egress_client_id']}'");
        if(isset($_GET['egress_id']) && !empty($_GET['egress_id']))
            array_push($where_arr, "egress_id = '{$_GET['egress_id']}'");    
        if(isset($_GET['term_country']) && !empty($_GET['term_country']))
            array_push($where_arr, "term_country = '{$_GET['term_country']}'");  
        if(isset($_GET['term_code_name']) && !empty($_GET['term_code_name']))
            array_push($where_arr, "term_code_name = '{$_GET['term_code_name']}'");  
        if(isset($_GET['term_code']) && !empty($_GET['term_code']))
            array_push($where_arr, "term_code::prefix_range <@ '{$_GET['term_code']}'");
            
        if(isset($_GET['group_select']) && !empty($_GET['group_select'])) {
            $group_select_arr = $_GET['group_select'];
            foreach($group_select_arr as $group_select) {
                if(!empty($group_select)) {
                    array_push($group_arr, $group_select);
                    if($group_select == 'ingress_client_id')
                        array_push($field_arr, "(SELECT name FROM client WHERE client_id::text = ingress_client_id) AS ingress_client_id"); 
                    elseif ($group_select == 'egress_client_id')
                        array_push($field_arr, "(SELECT name FROM client WHERE client_id::text = egress_client_id) AS egress_client_id"); 
                    elseif ($group_select == 'ingress_id')
                        array_push($field_arr, "(SELECT alias FROM resource WHERE resource_id::text = ingress_id) AS ingress_id"); 
                    elseif ($group_select == 'egress_id')
                        array_push($field_arr, "(SELECT alias FROM resource WHERE resource_id::text = egress_id) AS egress_id"); 
                    else
                        array_push($field_arr, $group_select); 
                    $show_fields[$group_select] = $group_select;
                    $order_num++;
                }
            }
        }
        
        $fields = "";
        $groups = "";
        $orders = "";
        $wheres = "";
        if(count($field_arr)) {
            $fields = implode(',', $field_arr) . ",";
        }
        if(count($group_arr)) {
            $groups = "GROUP BY " . implode(',', $group_arr);
        }
        
        if($order_num > 0) {
            $orders = "ORDER BY " . implode(',', range(1, $order_num));
        }
        
        
        if(count($where_arr)) {
            $wheres = implode(' and ', $where_arr) . " and ";
        }
        
        
        if($type == 1) {
        $sql = "SELECT
{$fields}        
sum(call_duration::integer) as duration,
count(*) as cdr_count
from client_cdr".date("Ymd")." where {$wheres} is_final_call='1' and time between '{$start_date}' and '{$end_date}' {$groups} {$orders}";
        } else if($type == 2) {
        $sql = "SELECT
{$fields}   
sum(call_duration::integer) as duration,
count(*) as cdr_count
from client_cdr".date("Ymd")." where {$wheres} time between '{$start_date}' and '{$end_date}' {$groups} {$orders}";
        }
        
        //echo $sql;
        $result = $this->query($sql);
        return $result;
        
    }
        
}

?>
