<?php
class Cdrs extends AppModel
{

    var $name = 'Cdrs';
    var $useTable = 'client_cdr';
    var $primaryKey = 'id';

    public function get_ingress_clients()
    {
        $sst_user_id = $_SESSION['sst_user_id'];
        $agent_where = '';
        if ($_SESSION['login_type'] == 2){
            $agent_id = (int) $_SESSION['sst_agent_info']['Agent']['agent_id'];
            $agent_where = " OR exists(select 1 from agent_clients WHERE agent_id = {$agent_id} and client_id =client.client_id )";
        }
        $sql = <<<SQL
SELECT DISTINCT client.client_id, client.name FROM resource INNER JOIN client ON resource.client_id = client.client_id WHERE
(exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id=client.client_id) OR
exists (SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id
WHERE users.user_id = {$sst_user_id} and (role_name = 'admin' or sys_role.view_all = true))
$agent_where
)
AND ingress = true ORDER BY client.name ASC
SQL;
        $result = $this->query($sql);
        return $result;
    }

    public function get_qos_cdrs_two($sql1, $sql2, $type, $orders, $show_fields)
    {

        $fields = implode(', ', $show_fields);
        $group_by = count($show_fields) ? "group by {$fields}" : '';
        $the_fields = '';
        if (!empty($fields))
            $the_fields = $fields . ',';
        if ($type == 1)
        {
            $total_fields = "sum(duration) as duration, sum(bill_time) as bill_time,  
sum(total_calls) as total_calls, sum(not_zero_calls) as not_zero_calls, 
sum(success_calls) as success_calls, sum(busy_calls) as busy_calls, sum(lrn_calls) as lrn_calls, 
sum(pdd) as pdd, sum(cancel_calls) as cancel_calls";
        }
        else
        {
            $total_fields = "sum(duration) as duration, sum(bill_time) as bill_time,
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

    public function get_rate_tables()
    {
        $sql = "select rate_table_id as id, name from rate_table order by name asc";
        $result = $this->query($sql);
        return $result;
    }

    public function get_routing_plans()
    {
        $sql = "select route_strategy_id as id, name from route_strategy where is_virtual is not true order by name asc";
        $result = $this->query($sql);
        return $result;
    }

    public function get_egress_clients()
    {
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

    public function get_switch_ip()
    {
        $sql = "select ip from server_platform";
        $result = $this->query($sql);
        return $result;
    }

    public function get_ingress_trunks($client_id = "")
    {
        $client_sql = "";
        if($client_id)
        {
            $client_sql = " AND client_id = {$client_id}";
        }
        $sql = "select resource_id,alias from resource where ingress=true {$client_sql} order by alias asc";
        $result = $this->query($sql);
        return $result;
    }

    public function get_egress_trunks($client_id = "")
    {
        $client_sql = "";
        if($client_id)
        {
            $client_sql = " AND client_id = {$client_id}";
        }
        $sql = "select resource_id,alias from resource where egress=true {$client_sql} order by alias asc";
        $result = $this->query($sql);
        return $result;
    }

    public function get_qos_cdrs($start_date, $end_date, $fields, $out_fields, $groups, $orders, $wheres, $type, $table_name)
    {
        $sst_user_id = $_SESSION['sst_user_id'];
        if (!empty($groups))
        {
            $filter_client = "and
(exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id={$table_name}.ingress_client_id) OR exists 
(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id} 
and (role_name = 'admin' or sys_role.view_all = true)))";
        }
        else
        {
            $filter_client = '';
        }



        //分表
        $date_arr = $this->_app_get_date_result_admin($start_date,$end_date,$table_name.'2%');

        if ($type == 1){
            $org_sql ='';
            if(!empty($fields)){
                $filter_fields_arr = array('duration','ingress_bill_time','ingress_total_calls','
                not_zero_calls','ingress_success_calls','ingress_busy_calls','
                lrn_calls','pdd','ingress_cancel_calls');
                $fields_arr = explode(',',trim($fields,','));
                $fields_arr = array_diff($fields_arr,$filter_fields_arr);
                if(!empty($fields_arr)){
                    $fields = implode(',',$fields_arr).', ';
                } else {
                    $fields = '';
                }
            }

            foreach($date_arr as $value){
                $table_date = $table_name.$value;

                $union = "";
                if(!empty($org_sql)){
                    $union = " union all ";
                }

                $org_sql .= " {$union}  SELECT
{$fields} duration,ingress_bill_time,ingress_total_calls,
not_zero_calls,ingress_success_calls,ingress_busy_calls,
lrn_calls,pdd,ingress_cancel_calls
from {$table_date}
where report_time between '{$start_date}' and '{$end_date}'
{$filter_client}
{$wheres}

";
                $org_sql = str_replace($table_name.".", $table_date.".", $org_sql);

            }

            $sql = "SELECT
{$out_fields}
sum(duration) as duration,
sum(ingress_bill_time) as bill_time,
sum(ingress_total_calls) as total_calls,
sum(not_zero_calls) as not_zero_calls,
sum(ingress_success_calls) as success_calls,
sum(ingress_busy_calls) as busy_calls,
sum(lrn_calls) as lrn_calls,
sum(pdd) as pdd,
sum(ingress_cancel_calls) as cancel_calls
from  ( {$org_sql} ) as tr
{$groups} {$orders}";
        } else if($type == 2) {
            $org_sql ='';
            if(!empty($fields)){
                $filter_fields_arr = array('duration','egress_bill_time','egress_total_calls','not_zero_calls','egress_success_calls','egress_busy_calls','
pdd','egress_cancel_calls');
                $fields_arr = explode(',',trim($fields,','));
                $fields_arr = array_diff($fields_arr,$filter_fields_arr);
                if(!empty($fields_arr)){
                    $fields = implode(',',$fields_arr).', ';
                } else {
                    $fields = '';
                }
            }

            foreach($date_arr as $value){
                $table_date = $table_name.$value;

                $union = "";
                if(!empty($org_sql)){
                    $union = " union all ";
                }

                $org_sql .= " {$union}  SELECT
{$fields} duration,egress_bill_time,egress_total_calls,not_zero_calls,egress_success_calls,egress_busy_calls,
pdd,egress_cancel_calls
from {$table_date}
where report_time between '{$start_date}' and '{$end_date}'
{$filter_client}
{$wheres}

";
                $org_sql = str_replace($table_name.".", $table_date.".", $org_sql);

            }

            $sql = "SELECT
{$out_fields}
sum(duration) as duration,
sum(egress_bill_time) as bill_time,
sum(egress_total_calls) as total_calls,
sum(not_zero_calls) as not_zero_calls,
sum(egress_success_calls) as success_calls,
sum(egress_busy_calls) as busy_calls,
sum(pdd) as pdd,
sum(egress_cancel_calls) as cancel_calls
from (
{$org_sql}
) as tr  {$groups} {$orders}";
        }

        /*
        if ($type == 1)
        {
            $sql = "SELECT
{$fields}
sum(duration) as duration,
sum(ingress_bill_time) as bill_time,
sum(ingress_total_calls) as total_calls,
sum(not_zero_calls) as not_zero_calls,
sum(ingress_success_calls) as success_calls,
sum(ingress_busy_calls) as busy_calls,
sum(lrn_calls) as lrn_calls,
sum(pdd) as pdd,
sum(ingress_cancel_calls) as cancel_calls
from {$table_name}
where report_time between '{$start_date}' and '{$end_date}' 
{$filter_client}
{$wheres}  {$groups} {$orders}";
        }
        else if ($type == 2)
        {
            $sql = "SELECT
{$fields}
sum(duration) as duration,
sum(egress_bill_time) as bill_time,
sum(egress_total_calls) as total_calls,
sum(not_zero_calls) as not_zero_calls,
sum(egress_success_calls) as success_calls,
sum(egress_busy_calls) as busy_calls,
sum(pdd) as pdd,
sum(egress_cancel_calls) as cancel_calls
from {$table_name} 
where report_time between '{$start_date}' and '{$end_date}' 
{$filter_client}
{$wheres}  {$groups} {$orders}";
        }
        */

        //echo $sql;
        //$result = $this->query($sql);
        //return $result;
        return $sql;
    }

    public function get_cdrs($start_date, $end_date, $fields,$out_fields, $groups, $orders, $wheres, $type, $table_name,$is_user = false)
    {
        $sst_user_id = $_SESSION['sst_user_id'];
        if (!empty($groups) && !$is_user)
        {
            $filter_client = "and
(exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id={$table_name}.ingress_client_id) OR exists 
(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id} 
and (role_name = 'admin' or sys_role.view_all = true)))";
        }
        else
        {
            $filter_client = '';
        }

        //分表
        $date_arr = $this->_app_get_date_result_admin($start_date,$end_date,$table_name.'2%');

        if ($type == 1){
            $org_sql ='';
            if(!empty($fields)){
                $filter_fields_arr = array('duration','ingress_bill_time','ingress_call_cost','lnp_cost','ingress_total_calls','
not_zero_calls','ingress_success_calls','ingress_busy_calls','
lrn_calls','pdd','ingress_cancel_calls','ingress_call_cost_intra','ingress_call_cost_inter','q850_cause_count','release_cause');
                $fields_arr = explode(',',trim($fields,','));
                $fields_arr = array_diff($fields_arr,$filter_fields_arr);
                if(!empty($fields_arr)){
                    $fields = implode(',',$fields_arr).', ';
                } else {
                    $fields = '';
                }
            }
            foreach($date_arr as $value){
                $table_date = $table_name.$value;

                $union = "";

                if(!empty($org_sql)){
                    $union = " union all ";
                }

                $org_sql .= " {$union}  SELECT
{$fields} duration,ingress_bill_time,ingress_call_cost,lnp_cost,ingress_total_calls,
not_zero_calls,ingress_success_calls,ingress_busy_calls,npr_count,
lrn_calls,pdd,ingress_cancel_calls,ingress_call_cost_intra,ingress_call_cost_inter,q850_cause_count,release_cause
from {$table_date}
where report_time between '{$start_date}' and '{$end_date}'
{$filter_client}
{$wheres}

";
                $org_sql = str_replace($table_name.".", $table_date.".", $org_sql);

            }

            $sql = "SELECT
{$out_fields}
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
sum(ingress_cancel_calls) as cancel_calls,
sum(ingress_call_cost_intra) as inter_cost,
sum(ingress_call_cost_inter) as intra_cost,
sum(q850_cause_count) as q850_cause_count,
sum(npr_count) as npr_value
from  ( {$org_sql} ) as t1
{$groups} {$orders}";
        } else if($type == 2) {
            $org_sql ='';
            if(!empty($fields)){
                $filter_fields_arr = array('duration','egress_bill_time','egress_call_cost','egress_total_calls','not_zero_calls','egress_success_calls','egress_busy_calls','
pdd','egress_cancel_calls','egress_call_cost_inter','egress_call_cost_intra','q850_cause_count','release_cause');
                $fields_arr = explode(',',trim($fields,','));
                $fields_arr = array_diff($fields_arr,$filter_fields_arr);
                if(!empty($fields_arr)){
                    $fields = implode(',',$fields_arr).', ';
                } else {
                    $fields = '';
                }
            }
            foreach($date_arr as $value){
                $table_date = $table_name.$value;

                $union = "";
                if(!empty($org_sql)){
                    $union = " union all ";
                }

                $org_sql .= " {$union}  SELECT
{$fields} duration,egress_bill_time,egress_call_cost,egress_total_calls,not_zero_calls,egress_success_calls,egress_busy_calls,
pdd,egress_cancel_calls,egress_call_cost_inter,egress_call_cost_intra,q850_cause_count,release_cause
from {$table_date}
where report_time between '{$start_date}' and '{$end_date}'
{$filter_client}
{$wheres}

";
                $org_sql = str_replace($table_name.".", $table_date.".", $org_sql);

            }

            $sql = "SELECT
{$out_fields}
sum(duration) as duration,
sum(egress_bill_time) as bill_time,
sum(egress_call_cost) as call_cost,
sum(egress_total_calls) as total_calls,
sum(not_zero_calls) as not_zero_calls,
sum(egress_success_calls) as success_calls,
sum(egress_busy_calls) as busy_calls,
sum(pdd) as pdd,
sum(egress_cancel_calls) as cancel_calls,
sum(egress_call_cost_inter) as inter_cost,
sum(egress_call_cost_intra) as intra_cost,
sum(q850_cause_count) as q850_cause_count
from (
{$org_sql}
) as t1  {$groups} {$orders}";
        }

        /* if ($type == 1)
         {
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
 sum(ingress_cancel_calls) as cancel_calls,
 sum(ingress_call_cost_intra) as inter_cost,
 sum(ingress_call_cost_inter) as intra_cost,
 sum(q850_cause_count) as q850_cause_count,
 sum(case when release_cause = 13 then 1 else 0 end) as npr_value
 from {$table_name}
 where report_time between '{$start_date}' and '{$end_date}'
 {$filter_client}
 {$wheres}  {$groups} {$orders}";
         }
         else if ($type == 2)
         {
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
 sum(egress_cancel_calls) as cancel_calls,
 sum(egress_call_cost_inter) as inter_cost,
 sum(egress_call_cost_intra) as intra_cost,
 sum(q850_cause_count) as q850_cause_count,
 sum(case when release_cause = 13 then 1 else 0 end) as npr_value
 from {$table_name}
 where report_time between '{$start_date}' and '{$end_date}'
 {$filter_client}
 {$wheres}  {$groups} {$orders}";
         }

        */

//        pr($out_fields);pr($sql);exit;
        //$result = $this->query($sql);
        //return $result;
        return $sql;
    }

    public function get_did_report($start_date, $end_date,$date_arr, $fields, $outer_fields, $groups, $orders, $wheres)
    {
        $sst_user_id = $_SESSION['sst_user_id'];
        $table_exist = false;

        //分表]
        $sql='';
        foreach($date_arr as $key=>$value) {
            $table_name = "did_report" . $value;
            $is_exist = $this->query("select count(*) from pg_class where relname='{$table_name}'");
            if (!$is_exist[0][0]['count']) {
                continue;
            }
            $table_exist = true;
            if (!empty($groups))
            {
                $filter_client = "and
(exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id={$table_name}.ingress_client_id) OR exists
(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id}
and (role_name = 'admin' or sys_role.view_all = true)))";
            }
            else
            {
                $filter_client = '';
            }

            $union = "";
            if (!empty($sql)) {
                $union = " union all ";
            } else {
                $union = '';
            }

            $sql .= <<<EOD
                        {$union}  select {$fields} duration,ingress_bill_time,egress_bill_time,ingress_call_cost,egress_call_cost,
                        ingress_total_calls,not_zero_calls,ingress_success_calls,ingress_busy_calls,egress_busy_calls,
                        ingress_cancel_calls,egress_cancel_calls,pdd
                         from
                        {$table_name}
                        where report_time between '{$start_date}' and '{$end_date}'
{$filter_client}
{$wheres}
EOD;
        }


        if(!$table_exist) {
            return [];
        }


        $sql = "SELECT
{$outer_fields}
sum(duration) as duration,
sum(ingress_bill_time) as ingress_bill_time,
sum(egress_bill_time) as egress_bill_time,
sum(ingress_call_cost) as ingress_call_cost,
sum(egress_call_cost) as egress_call_cost,
sum(ingress_total_calls) as ingress_total_calls,
sum(not_zero_calls) as not_zero_calls,
sum(ingress_success_calls) as ingress_success_calls,
sum(ingress_busy_calls) as ingress_busy_calls,
sum(egress_busy_calls) as egress_busy_calls,
sum(ingress_cancel_calls) as ingress_cancel_calls,
sum(egress_cancel_calls) as egress_cancel_calls,
sum(pdd) as pdd
from (
$sql
) as tmp  {$groups} {$orders}";
        return $this->query($sql);
    }

    public function get_bandwidth($start_date, $end_date, $fields, $groups, $orders, $wheres, $table_name)
    {
        $sst_user_id = $_SESSION['sst_user_id'];
        $sql = "SELECT
{$fields}
sum(incoming_bandwidth) as incoming_bandwidth,
sum(outgoing_bandwidth) as outgoing_bandwidth,
sum(not_zero_calls) as calls
from {$table_name}
where report_time between '{$start_date}' and '{$end_date}' 

and

(exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id={$table_name}.ingress_client_id) OR exists 

(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id} 

and (role_name = 'admin' or sys_role.view_all = true))) 

{$wheres}  {$groups} {$orders}";

        //echo $sql;
        //$result = $this->query($sql);
        //return $result;
        return $sql;
    }

    public function get_location_from_two($sql1, $sql2, $orders, $show_fields)
    {
        $fields = implode(', ', $show_fields);
        $the_fields = '';
        $group_by = count($show_fields) ? "group by {$fields}" : '';
        if (!empty($fields))
            $the_fields = $fields . ',';
        $total_fields = "sum(inbound_call_cost) as inbound_call_cost,
sum(outbound_call_cost) as outbound_call_cost, sum(duration) as duration, sum(total_calls) as total_calls";

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

    public function get_location($start_date, $end_date, $fields, $out_fields, $groups, $orders, $wheres, $table_name)
    {
        //分表

        $date_arr = $this->_app_get_date_result_admin($start_date,$end_date,$table_name.'2%');


        if(!empty($fields)){
            $filter_fields_arr = array('ingress_call_cost','egress_call_cost','duration','ingress_total_calls');
            $fields_arr = explode(',',trim($fields,','));
            $fields_arr = array_diff($fields_arr,$filter_fields_arr);
            if(!empty($fields_arr)){
                $fields = implode(',',$fields_arr).', ';
            } else {
                $fields = '';
            }
        }
        $org_sql ='';
        foreach($date_arr as $value){
            $table_date = $table_name.$value;

            $union = "";
            if(!empty($org_sql)){
                $union = " union all ";
            }

            $org_sql .= " {$union}  SELECT
{$fields}
ingress_call_cost,egress_call_cost,duration,ingress_total_calls
from {$table_date}
where report_time between '{$start_date}' and '{$end_date}' {$wheres}";
        }


        $sql = "select
{$out_fields}
sum(ingress_call_cost) as inbound_call_cost,
sum(egress_call_cost) as outbound_call_cost,
sum(duration) as duration,
sum(ingress_total_calls) as total_calls
from ( {$org_sql} ) as tl
 {$groups} {$orders}";


        /*
                $sql = "select
        {$fields}
        sum(ingress_call_cost) as inbound_call_cost,
        sum(egress_call_cost) as outbound_call_cost,
        sum(duration) as duration,
        sum(ingress_total_calls) as total_calls
        from {$table_name}
        where report_time between '{$start_date}' and '{$end_date}' {$wheres}  {$groups} {$orders}";
                */

        return $sql;
    }

    public function get_location_from_client_cdr($start_date, $end_date)
    {
        $field_arr = array();
        $out_field_arr = array();
        $group_arr = array();
        $show_fields = array();
        $order_num = 0;
        $where_arr = array();
        $group_select_arr = array();


        if (isset($_GET['group_by_date']) && !empty($_GET['group_by_date']))
        {
            array_push($out_field_arr, "group_time");
            array_push($field_arr, "to_char(time, '{$_GET['group_by_date']}') as group_time");
            $show_fields['group_time'] = "group_time";
            array_push($group_arr, "group_time");
            $order_num++;
        }


        if (isset($_GET['route_prefix']) && $_GET['route_prefix'] != 'all')
        {
            array_push($where_arr, "route_prefix = '{$_GET['route_prefix']}'");
        }

        if (isset($_GET['ingress_rate_table']) && $_GET['ingress_rate_table'] != 'all')
        {
            array_push($where_arr, "ingress_client_rate_table_id = {$_GET['ingress_rate_table']}");
        }

        if (isset($_GET['ingress_routing_plan']) && $_GET['ingress_routing_plan'] != 'all')
        {

            array_push($where_arr, "route_plan = {$_GET['ingress_routing_plan']}");
        }

        if (isset($_GET['orig_rate_type']) && $_GET['orig_rate_type'] != '0')
        {
            if ($_GET['orig_rate_type'] == '1')
            {
                array_push($where_arr, "orig_jur_type = 0");
            }
            elseif ($_GET['orig_rate_type'] == '2')
            {
                array_push($where_arr, "orig_jur_type in (1, 2)");
            }
            elseif ($_GET['orig_rate_type'] == '3')
            {
                array_push($where_arr, "orig_jur_type in (3, 4)");
            }
        }

        if (isset($_GET['term_rate_type']) && $_GET['term_rate_type'] != '0')
        {
            if ($_GET['term_rate_type'] == '1')
            {
                array_push($where_arr, "term_jur_type = 0");
            }
            elseif ($_GET['term_rate_type'] == '2')
            {
                array_push($where_arr, "term_jur_type in (1, 2)");
            }
            elseif ($_GET['term_rate_type'] == '3')
            {
                array_push($where_arr, "term_jur_type in (3, 4)");
            }
        }

        if (isset($_GET['ingress_client_id']) && !empty($_GET['ingress_client_id']))
            array_push($where_arr, "ingress_client_id = {$_GET['ingress_client_id']}");
        if (isset($_GET['ingress_id']) && !empty($_GET['ingress_id']))
            array_push($where_arr, "ingress_id = {$_GET['ingress_id']}");
        if (isset($_GET['orig_country']) && !empty($_GET['orig_country']))
            array_push($where_arr, "orig_country = '{$_GET['orig_country']}'");
        if (isset($_GET['orig_code_name']) && !empty($_GET['orig_code_name']))
            array_push($where_arr, "orig_code_name = '{$_GET['orig_code_name']}'");
        if (isset($_GET['orig_code']) && !empty($_GET['orig_code']))
            array_push($where_arr, "orig_code::prefix_range <@  '{$_GET['orig_code']}'");
        if (isset($_GET['egress_client_id']) && !empty($_GET['egress_client_id']))
            array_push($where_arr, "egress_client_id = {$_GET['egress_client_id']}");
        if (isset($_GET['egress_id']) && !empty($_GET['egress_id']))
            array_push($where_arr, "egress_id = {$_GET['egress_id']}");
        if (isset($_GET['term_country']) && !empty($_GET['term_country']))
            array_push($where_arr, "term_country = '{$_GET['term_country']}'");
        if (isset($_GET['term_code_name']) && !empty($_GET['term_code_name']))
            array_push($where_arr, "term_code_name = '{$_GET['term_code_name']}'");
        if (isset($_GET['term_code']) && !empty($_GET['term_code']))
            array_push($where_arr, "term_code::prefix_range <@ '{$_GET['term_code']}'");

        if (isset($_GET['group_select']) && !empty($_GET['group_select']))
        {
            $group_select_arr = $_GET['group_select'];
            foreach ($group_select_arr as $group_select)
            {
                if (!empty($group_select) && !in_array($group_select, $group_arr))
                {
                    array_push($group_arr, $group_select);
                    if ($group_select == 'ingress_client_id'){

                        array_push($out_field_arr, "(SELECT name FROM client WHERE client_id = ingress_client_id) AS ingress_client_id");
                        array_push($field_arr, "ingress_client_id");
                    }
                    elseif ($group_select == 'egress_client_id'){

                        array_push($out_field_arr, "(SELECT name FROM client WHERE client_id = egress_client_id) AS egress_client_id");
                        array_push($field_arr, "egress_client_id");
                    }
                    elseif ($group_select == 'ingress_id'){

                        array_push($out_field_arr, "(SELECT alias FROM resource WHERE resource_id = ingress_id) AS ingress_id");
                        array_push($field_arr, "ingress_id");
                    }
                    elseif ($group_select == 'egress_id'){

                        array_push($out_field_arr, "(SELECT alias FROM resource WHERE resource_id = egress_id) AS egress_id");
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

        $group_arr = array_unique($group_arr);
        $field_arr = array_unique($field_arr);
        $out_field_arr = array_unique($out_field_arr);

        $fields = "";
        $out_fields = "";
        $groups = "";
        $orders = "";
        $wheres = "";
        if (count($field_arr))
        {
            $fields = implode(',', $field_arr) . ",";
        }
        if (count($out_field_arr))
        {
            $out_fields = implode(',', $out_field_arr) . ",";
        }
        if (count($group_arr))
        {
            $groups = "GROUP BY " . implode(',', $group_arr);
        }

        if ($order_num > 0)
        {
            $orders = "ORDER BY " . implode(',', range(1, $order_num));
        }


        if (count($where_arr))
        {
            $wheres = " and " . implode(' and ', $where_arr);
        }

        $sst_user_id = $_SESSION['sst_user_id'];
        if (!empty($groups))
        {
            $filter_client = "and
(exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id=client_cdr.ingress_client_id) OR exists 
(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id} 
and (role_name = 'admin' or sys_role.view_all = true)))";
        }
        else
        {
            $filter_client = '';
        }

        //分表

        $date_arr = $this->_app_get_date_result_admin($start_date,$end_date,'client_cdr2%');





        if(!empty($fields)){
            $filter_fields_arr = array('ingress_client_cost','egress_cost','call_duration');
            $fields_arr = explode(',',trim($fields,','));
            $fields_arr = array_diff($fields_arr,$filter_fields_arr);
            if(!empty($fields_arr)){
                $fields = implode(',',$fields_arr).', ';
            } else {
                $fields = '';
            }
        }
        $org_sql ='';
        foreach($date_arr as $value){
            $table_name = "client_cdr".$value;

            $union = "";
            if(!empty($org_sql)){
                $union = " union all ";
            }

            $org_sql .= " {$union}  select {$fields}
             ingress_client_cost,egress_cost,call_duration
             from   {$table_name}
             where time between '{$start_date}' and '{$end_date}' {$wheres}  and is_final_call = 1 {$filter_client} ";
            $org_sql = str_replace('client_cdr.', $table_name.".", $org_sql);

        }

        $sql = "SELECT
{$out_fields}
sum(ingress_client_cost) as inbound_call_cost,
sum(egress_cost) as outbound_call_cost,
sum(call_duration) as duration,
count(*) as total_calls
from ( $org_sql ) as tmp {$groups} {$orders}";

        //pr($sql);exit;



        /*    $sql = "select
    {$fields}
    sum(ingress_client_cost) as inbound_call_cost,
    sum(egress_cost) as outbound_call_cost,
    sum(call_duration) as duration,
    count(*) as total_calls
    from client_cdr
    where   time between '{$start_date}' and '{$end_date}' {$wheres}  and is_final_call = 1 {$filter_client} {$groups} {$orders}"; */


        return $sql;
    }

    public function  get_inout_cdrs($start_date, $end_date, $fields, $out_fields, $groups, $orders, $wheres, $table_name)
    {
        $sst_user_id = $_SESSION['sst_user_id'];
        if (!empty($groups))
        {
            $filter_client = "and
(exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id={$table_name}.ingress_client_id) OR exists 
(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id} 
and (role_name = 'admin' or sys_role.view_all = true)))";
        }
        else
        {
            $filter_client = '';
        }


        //分组
        $date_arr = $this->_app_get_date_result_admin($start_date,$end_date,$table_name.'2%');


        if(!empty($fields)){
            $filter_fields_arr = array('ingress_bill_time','ingress_call_cost','egress_bill_time','egress_call_cost','duration','ingress_total_calls',
                'not_zero_calls','ingress_success_calls','ingress_busy_calls','pdd');
            $fields_arr = explode(',',trim($fields,','));
            $fields_arr = array_diff($fields_arr,$filter_fields_arr);
            if(!empty($fields_arr)){
                $fields = implode(',',$fields_arr).', ';
            } else {
                $fields = '';
            }
        }
        $org_sql ='';
        foreach($date_arr as $value){
            $table_date = $table_name.$value;

            $union = "";
            if(!empty($org_sql)){
                $union = " union all ";
            }

            $org_sql .= " {$union}  SELECT
{$fields}
ingress_bill_time,ingress_call_cost,egress_bill_time,egress_call_cost,duration,ingress_total_calls,
not_zero_calls,ingress_success_calls,ingress_busy_calls,pdd
from {$table_date}
where report_time between '{$start_date}' and '{$end_date}' {$wheres} {$filter_client}";
            $org_sql = str_replace($table_name.'.',$table_date.'.',$org_sql);
        }


        $sql = "select
{$out_fields}
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
from ( {$org_sql} ) as ti
 {$groups} {$orders}";




        /*  $sql = "select
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
  where report_time between '{$start_date}' and '{$end_date}' {$wheres} {$filter_client}  {$groups} {$orders}"; */

//        pr($sql);exit;
        //$result = $this->query($sql);
        //return $result;
        return $sql;
    }

    public function get_usagereport($start_date, $end_date, $fields,$out_fields, $groups, $orders, $wheres, $type, $table_name)
    {
        $sst_user_id = $_SESSION['sst_user_id'];
        if (!empty($groups))
        {
            $filter_client = "and
(exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id={$table_name}.ingress_client_id) OR exists 
(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id} 
and (role_name = 'admin' or sys_role.view_all = true)))";
        }
        else
        {
            $filter_client = '';
        }

        if ($type == 1)
        {
            $cdr_count_field = "ingress_total_calls";
        }
        else
        {
            $cdr_count_field = "egress_total_calls";
        }

        //分表
        $date_arr = $this->_app_get_date_result_admin($start_date,$end_date,$table_name.'2%');

        $org_sql ='';
        foreach($date_arr as $value){
            $table_date = $table_name.$value;

            $union = "";
            if(!empty($org_sql)){
                $union = " union all ";
            }

            // unknown field "+0000"
//            $org_sql .= " {$union}  SELECT
//{$fields}
//{$cdr_count_field},
//duration
//from {$table_date}
//where report_time between '{$start_date}' and '{$end_date}' {$wheres} {$filter_client}";

            $org_sql .= " {$union}  SELECT 
{$cdr_count_field},
duration
from {$table_date}
where report_time between '{$start_date}' and '{$end_date}' {$wheres} {$filter_client}";
            $org_sql = str_replace($table_name.'.', $table_date.".", $org_sql);

        }


        $sql = <<<EOT
SELECT 
{$out_fields}
sum({$cdr_count_field}) as cdr_count,
sum(duration) as duration
from ({$org_sql}) as tmp
 {$groups} {$orders}
EOT;
//pr($sql);

        $result = $this->query($sql);
        return $result;
    }

    public function get_ingress_options($ingress_id)
    {
        $sql = "select id, tech_prefix from resource_prefix where resource_id = {$ingress_id}";
        $prefixes = $this->query($sql);
        $sql = "select distinct (select name from rate_table where rate_table.rate_table_id = resource_prefix.
rate_table_id) as rate_table_name, rate_table_id from resource_prefix where resource_id = {$ingress_id}";
        $rate_tables = $this->query($sql);
        $sql = "select distinct (select name from route_strategy where route_strategy_id 
= resource_prefix.route_strategy_id) as route_strategy_name, route_strategy_id from resource_prefix ";
        $routing_plans = $this->query($sql);

        return array(
            'prefixes' => $prefixes,
            'rate_tables' => $rate_tables,
            'routing_plans' => $routing_plans,
        );
    }

    public function get_qos_summary_report_from_client_cdr($start_date, $end_date, $type)
    {
        $field_arr = array();
        $out_field_arr = array();
        $group_arr = array();
        $show_fields = array();
        $order_num = 0;
        $where_arr = array();
        $group_select_arr = array();


        if (isset($_GET['group_by_date']) && !empty($_GET['group_by_date']))
        {
            array_push($out_field_arr, "group_time");
            array_push($field_arr, "to_char(time, '{$_GET['group_by_date']}') as group_time");
            $show_fields['group_time'] = "group_time";
            array_push($group_arr, "group_time");
            $order_num++;
        }


        if (isset($_GET['route_prefix']) && $_GET['route_prefix'] != 'all')
        {
            array_push($where_arr, "route_prefix = '{$_GET['route_prefix']}'");
        }

        if (isset($_GET['ingress_rate_table']) && $_GET['ingress_rate_table'] != 'all')
        {
            array_push($where_arr, "ingress_client_rate_table_id = {$_GET['ingress_rate_table']}");
        }

        if (isset($_GET['ingress_routing_plan']) && $_GET['ingress_routing_plan'] != 'all')
        {

            array_push($where_arr, "route_plan = {$_GET['ingress_routing_plan']}");
        }

        if (isset($_GET['orig_rate_type']) && $_GET['orig_rate_type'] != '0')
        {
            if ($_GET['orig_rate_type'] == '1')
            {
                array_push($where_arr, "orig_jur_type = 0");
            }
            elseif ($_GET['orig_rate_type'] == '2')
            {
                array_push($where_arr, "orig_jur_type in (1, 2)");
            }
            elseif ($_GET['orig_rate_type'] == '3')
            {
                array_push($where_arr, "orig_jur_type in (3, 4)");
            }
        }

        if (isset($_GET['term_rate_type']) && $_GET['term_rate_type'] != '0')
        {
            if ($_GET['term_rate_type'] == '1')
            {
                array_push($where_arr, "term_jur_type = 0");
            }
            elseif ($_GET['term_rate_type'] == '2')
            {
                array_push($where_arr, "term_jur_type in (1, 2)");
            }
            elseif ($_GET['term_rate_type'] == '3')
            {
                array_push($where_arr, "term_jur_type in (3, 4)");
            }
        }

        if (isset($_GET['ingress_client_id']) && !empty($_GET['ingress_client_id']))
            array_push($where_arr, "ingress_client_id = {$_GET['ingress_client_id']}");
        if (isset($_GET['ingress_id']) && !empty($_GET['ingress_id']))
            array_push($where_arr, "ingress_id = {$_GET['ingress_id']}");
        if (isset($_GET['orig_country']) && !empty($_GET['orig_country']))
            array_push($where_arr, "orig_country = '{$_GET['orig_country']}'");
        if (isset($_GET['orig_code_name']) && !empty($_GET['orig_code_name']))
            array_push($where_arr, "orig_code_name = '{$_GET['orig_code_name']}'");
        if (isset($_GET['orig_code']) && !empty($_GET['orig_code']))
            array_push($where_arr, "orig_code::prefix_range <@  '{$_GET['orig_code']}'");
        if (isset($_GET['egress_client_id']) && !empty($_GET['egress_client_id']))
            array_push($where_arr, "egress_client_id = {$_GET['egress_client_id']}");
        if (isset($_GET['egress_id']) && !empty($_GET['egress_id']))
            array_push($where_arr, "egress_id = {$_GET['egress_id']}");
        if (isset($_GET['term_country']) && !empty($_GET['term_country']))
            array_push($where_arr, "term_country = '{$_GET['term_country']}'");
        if (isset($_GET['term_code_name']) && !empty($_GET['term_code_name']))
            array_push($where_arr, "term_code_name = '{$_GET['term_code_name']}'");
        if (isset($_GET['term_code']) && !empty($_GET['term_code']))
            array_push($where_arr, "term_code::prefix_range <@ '{$_GET['term_code']}'");

        if (isset($_GET['group_select']) && !empty($_GET['group_select']))
        {
            $group_select_arr = $_GET['group_select'];
            foreach ($group_select_arr as $group_select)
            {
                if (!empty($group_select) && !in_array($group_select, $group_arr))
                {
                    array_push($group_arr, $group_select);
                    if ($group_select == 'ingress_client_id'){

                        array_push($out_field_arr, "(SELECT name FROM client WHERE client_id = ingress_client_id) AS ingress_client_id");
                        array_push($field_arr, "ingress_client_id");
                    }
                    elseif ($group_select == 'egress_client_id'){

                        array_push($out_field_arr, "(SELECT name FROM client WHERE client_id = egress_client_id) AS egress_client_id");
                        array_push($field_arr, "egress_client_id");
                    }
                    elseif ($group_select == 'ingress_id'){

                        array_push($out_field_arr, "(SELECT alias FROM resource WHERE resource_id = ingress_id) AS ingress_id");
                        array_push($field_arr, "ingress_id");
                    }
                    elseif ($group_select == 'egress_id'){

                        array_push($out_field_arr, "(SELECT alias FROM resource WHERE resource_id = egress_id) AS egress_id");
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
        if (count($field_arr))
        {
            $fields = implode(',', $field_arr) . ",";
        }
        if (count($out_field_arr))
        {
            $out_fields = implode(',', $out_field_arr) . ",";
        }
        if (count($group_arr))
        {
            $groups = "GROUP BY " . implode(',', $group_arr);
        }

        if ($order_num > 0)
        {
            $orders = "ORDER BY " . implode(',', range(1, $order_num));
        }


        if (count($where_arr))
        {
            $wheres = " and " . implode(' and ', $where_arr);
        }

        $sst_user_id = $_SESSION['sst_user_id'];
        if (!empty($groups))
        {
            $filter_client = "and
(exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id=client_cdr.ingress_client_id) OR exists 
(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id} 
and (role_name = 'admin' or sys_role.view_all = true)))";
        }
        else
        {
            $filter_client = '';
        };

        //分表
        $date_arr = $this->_app_get_date_result_admin($start_date,$end_date,'client_cdr2%');

        if ($type == 1) {
            $org_sql = '';
            if(!empty($fields)){
                $filter_fields_arr = array('call_duration','ingress_client_bill_time','egress_id','
binary_value_of_release_cause_from_protocol_stack','lrn_number_vendor','pdd');
                $fields_arr = explode(',',trim($fields,','));
                $fields_arr = array_diff($fields_arr,$filter_fields_arr);
                if(!empty($fields_arr)){
                    $fields = implode(',',$fields_arr).', ';
                } else {
                    $fields = '';
                }
            }

            foreach ($date_arr as $value) {
                $table_name = 'client_cdr' . $value;

                $union = "";
                if (!empty($org_sql)) {
                    $union = " union all ";
                }

                $org_sql .= " {$union}  SELECT
{$fields}
call_duration,ingress_client_bill_time,egress_id,
binary_value_of_release_cause_from_protocol_stack,lrn_number_vendor,pdd
from {$table_name}
where  time between '{$start_date}' and '{$end_date}'
and is_final_call=1
{$wheres} {$filter_client}

";
                $org_sql = str_replace('client_cdr.', $table_name . ".", $org_sql);

            }

            $sql = "SELECT
{$out_fields}
sum(call_duration) as duration,
sum(ingress_client_bill_time) as bill_time,count(*) as
total_calls, count(case when call_duration > 0 then 1 else null end)
as not_zero_calls, count(case when egress_id is not null then 1 else null end)
as success_calls,count(case when
binary_value_of_release_cause_from_protocol_stack like '486%' then 1
else null end) as busy_calls,count(case when lrn_number_vendor != 0
then 1 else null end) as lrn_calls, sum(case when call_duration > 0
then pdd else 0 end) as pdd,count( case when
binary_value_of_release_cause_from_protocol_stack like '487%' then 1
else null end ) as cancel_calls
from ( {$org_sql} ) as tc
{$groups} {$orders}";

        } else if($type == 2) {
            $org_sql = '';
            $filter_fields_arr = array('call_duration','egress_bill_time','egress_id','
release_cause_from_protocol_stack','pdd');

            if(!empty($fields)){
                $fields_arr = explode(',',trim($fields,','));
                $fields_arr = array_diff($fields_arr,$filter_fields_arr);
                if(!empty($fields_arr)){
                    $fields = implode(',',$fields_arr).', ';
                } else {
                    $fields = '';
                }
            }
            foreach ($date_arr as $value) {
                $table_name = 'client_cdr' . $value;

                $union = "";
                if (!empty($org_sql)) {
                    $union = " union all ";
                }

                $org_sql .= " {$union}  SELECT
{$fields}
call_duration,egress_bill_time,egress_id,
release_cause_from_protocol_stack,pdd
from {$table_name}
where time between '{$start_date}' and '{$end_date}'
and is_final_call=1 {$wheres} {$filter_client}

";
                $org_sql = str_replace('client_cdr.', $table_name . ".", $org_sql);

            }

            $sql = "SELECT
{$out_fields}
sum(call_duration) as duration,
sum(egress_bill_time) as bill_time,count(*) as total_calls,
count(case when call_duration > 0 then 1 else null end) as
not_zero_calls, count(case when egress_id is not null then 1 else null end) as
success_calls,count(case when release_cause_from_protocol_stack like
'486%' then 1 else null end ) as busy_calls,sum(case when call_duration
> 0 then pdd else 0 end) as pdd,count( case when
release_cause_from_protocol_stack like '487%' then 1 else null end ) as
cancel_calls
from ( {$org_sql} ) as tc
 {$groups} {$orders}";

        }

//        pr($sql);exit;

        /*if ($type == 1)
        {
            $sql = "SELECT
{$fields}        
sum(call_duration) as duration,
sum(ingress_client_bill_time) as bill_time,count(*) as
total_calls, count(case when call_duration > 0 then 1 else null end)
as not_zero_calls, count(case when egress_id is not null then 1 else null end)
as success_calls,count(case when
binary_value_of_release_cause_from_protocol_stack like '486%' then 1
else null end) as busy_calls,count(case when lrn_number_vendor != 0
then 1 else null end) as lrn_calls, sum(case when call_duration > 0
then pdd else 0 end) as pdd,count( case when
binary_value_of_release_cause_from_protocol_stack like '487%' then 1
else null end ) as cancel_calls
from client_cdr where  time between '{$start_date}' and '{$end_date}' 

and is_final_call=1 


{$wheres} {$filter_client}

{$groups} {$orders}";
        }
        else if ($type == 2)
        {
            $sql = "SELECT
{$fields}   
sum(call_duration) as duration,
sum(egress_bill_time) as bill_time,count(*) as total_calls,
count(case when call_duration > 0 then 1 else null end) as
not_zero_calls, count(case when egress_id is not null then 1 else null end) as
success_calls,count(case when release_cause_from_protocol_stack like
'486%' then 1 else null end ) as busy_calls,sum(case when call_duration
> 0 then pdd else 0 end) as pdd,count( case when
release_cause_from_protocol_stack like '487%' then 1 else null end ) as
cancel_calls
from client_cdr where time between '{$start_date}' and '{$end_date}' 
{$wheres}  {$filter_client} {$groups} {$orders}";
        }
        */


        return $sql;
    }

    public function get_summary_report_from_client_cdr($start_date, $end_date, $type)
    {
        $field_arr = array();
        $out_field_arr = array();
        $group_arr = array();
        $show_fields = array();
        $order_num = 0;
        $where_arr = array();
        $group_select_arr = array();


        if (isset($_GET['group_by_date']) && !empty($_GET['group_by_date']))
        {
            array_push($out_field_arr, "group_time");
            array_push($field_arr, "to_char(time, '{$_GET['group_by_date']}') as group_time");
            $show_fields['group_time'] = "group_time";
            array_push($group_arr, "group_time");
            $order_num++;
        }


        if (isset($_GET['route_prefix']) && $_GET['route_prefix'] != 'all')
        {
            array_push($where_arr, "route_prefix = '{$_GET['route_prefix']}'");
        }

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

            array_push($where_arr, "origination_destination_host_name = '{$_GET['server_ip']}'");
        }

        if (isset($_GET['orig_rate_type']) && $_GET['orig_rate_type'] != '0')
        {
            if ($_GET['orig_rate_type'] == '1')
            {
                array_push($where_arr, "orig_jur_type = 0");
            }
            elseif ($_GET['orig_rate_type'] == '2')
            {
                array_push($where_arr, "orig_jur_type in (1, 2)");
            }
            elseif ($_GET['orig_rate_type'] == '3')
            {
                array_push($where_arr, "orig_jur_type in (3, 4)");
            }
        }

        if (isset($_GET['term_rate_type']) && $_GET['term_rate_type'] != '0')
        {
            if ($_GET['term_rate_type'] == '1')
            {
                array_push($where_arr, "term_jur_type = 0");
            }
            elseif ($_GET['term_rate_type'] == '2')
            {
                array_push($where_arr, "term_jur_type in (1, 2)");
            }
            elseif ($_GET['term_rate_type'] == '3')
            {
                array_push($where_arr, "term_jur_type in (3, 4)");
            }
        }

        if (isset($_GET['ingress_client_id']) && !empty($_GET['ingress_client_id']))
            array_push($where_arr, "ingress_client_id = {$_GET['ingress_client_id']}");
        if (isset($_GET['ingress_id']) && !empty($_GET['ingress_id']))
            array_push($where_arr, "ingress_id = {$_GET['ingress_id']}");
        if (isset($_GET['orig_country']) && !empty($_GET['orig_country']))
            array_push($where_arr, "orig_country = '{$_GET['orig_country']}'");
        if (isset($_GET['orig_code_name']) && !empty($_GET['orig_code_name']))
            array_push($where_arr, "orig_code_name = '{$_GET['orig_code_name']}'");
        if (isset($_GET['orig_code']) && !empty($_GET['orig_code']))
            array_push($where_arr, "orig_code::prefix_range <@  '{$_GET['orig_code']}'");
        if (isset($_GET['egress_client_id']) && !empty($_GET['egress_client_id']))
            array_push($where_arr, "egress_client_id = {$_GET['egress_client_id']}");
        if (isset($_GET['egress_id']) && !empty($_GET['egress_id']))
            array_push($where_arr, "egress_id = {$_GET['egress_id']}");
        if (isset($_GET['term_country']) && !empty($_GET['term_country']))
            array_push($where_arr, "term_country = '{$_GET['term_country']}'");
        if (isset($_GET['term_code_name']) && !empty($_GET['term_code_name']))
            array_push($where_arr, "term_code_name = '{$_GET['term_code_name']}'");
        if (isset($_GET['term_code']) && !empty($_GET['term_code']))
            array_push($where_arr, "term_code::prefix_range <@ '{$_GET['term_code']}'");

        if (isset($_GET['group_select']) && !empty($_GET['group_select']))
        {
            $group_select_arr = $_GET['group_select'];
            foreach ($group_select_arr as $group_select)
            {
                if (!empty($group_select) && !in_array($group_select, $group_arr))
                {
                    array_push($group_arr, $group_select);
                    if ($group_select == 'ingress_client_id'){

                        array_push($out_field_arr, "(SELECT name FROM client WHERE client_id = ingress_client_id) AS ingress_client_id");
                        array_push($field_arr, "ingress_client_id");
                    }
                    elseif ($group_select == 'egress_client_id'){

                        array_push($out_field_arr, "(SELECT name FROM client WHERE client_id = egress_client_id) AS egress_client_id");
                        array_push($field_arr, "egress_client_id");
                    }
                    elseif ($group_select == 'ingress_id'){

                        array_push($out_field_arr, "(SELECT alias FROM resource WHERE resource_id = ingress_id) AS ingress_id");
                        array_push($field_arr, "ingress_id");
                    }
                    elseif ($group_select == 'egress_id'){

                        array_push($out_field_arr, "(SELECT alias FROM resource WHERE resource_id = egress_id) AS egress_id");
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
            $orders = "ORDER BY " . implode(',', range(1, $order_num));
        }


        if (count($where_arr))
        {
            $wheres = " and " . implode(' and ', $where_arr);
        }

        $sst_user_id = $_SESSION['sst_user_id'];
        if (!empty($groups))
        {
            $filter_client = "and
(exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id=client_cdr.ingress_client_id) OR exists 
(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id} 
and (role_name = 'admin' or sys_role.view_all = true)))";
        }
        else
        {
            $filter_client = '';
        };




        //分表
        $date_arr = $this->_app_get_date_result_admin($start_date,$end_date,'client_cdr2%');

        if ($type == 1){
            $org_sql ='';
            if(!empty($fields)){
                $filter_fields_arr = array('call_duration','ingress_client_bill_time','ingress_client_cost','lnp_dipping_cost','egress_id','
binary_value_of_release_cause_from_protocol_stack','lrn_number_vendor','pdd','ingress_rate_type','q850_cause','release_cause');
                $fields_arr = explode(',',trim($fields,','));
                $fields_arr = array_diff($fields_arr,$filter_fields_arr);
                if(!empty($fields_arr)){
                    $fields = implode(',',$fields_arr).', ';
                } else {
                    $fields = '';
                }
            }
            foreach($date_arr as $value){
                $table_name = 'client_cdr'.$value;

                $union = "";
                if(!empty($org_sql)){
                    $union = " union all ";
                }

                $org_sql .= " {$union}  SELECT
{$fields}
call_duration,ingress_client_bill_time,ingress_client_cost,lnp_dipping_cost,egress_id,egress_erro_string,
binary_value_of_release_cause_from_protocol_stack,lrn_number_vendor,pdd,ingress_rate_type,q850_cause,release_cause
from {$table_name}
where time between '{$start_date}' and '{$end_date}'
and is_final_call=1 {$wheres} {$filter_client}

";
                $org_sql = str_replace('client_cdr.', $table_name.".", $org_sql);

            }

            $sql = "SELECT
{$out_fields}
sum(call_duration) as duration,
sum(ingress_client_bill_time) as bill_time,
sum(ingress_client_cost) as call_cost,
sum(lnp_dipping_cost) as lnp_cost, count(*) as
total_calls, count(case when call_duration > 0 then 1 else null end)
as not_zero_calls, count(case when egress_id is not null then 1 else null end)
as success_calls,count(case when
binary_value_of_release_cause_from_protocol_stack like '486%' then 1
else null end) as busy_calls,count(case when lrn_number_vendor != 0
then 1 else null end) as lrn_calls, sum(case when call_duration > 0
then pdd else 0 end) as pdd,count( case when
binary_value_of_release_cause_from_protocol_stack like '487%' then 1
else null end ) as cancel_calls,
sum(case when ingress_rate_type = 1
then ingress_client_cost else 0 end) as inter_cost,
sum(case when ingress_rate_type = 2
then ingress_client_cost else 0 end) as intra_cost,
count(case when q850_cause in (16,17,18,19,21) then 1 else null end) as q850_cause_count,
sum(rate_overflow(egress_erro_string)) as npr_value
from ( {$org_sql} ) as tmp
{$groups} {$orders}";
        } else if ($type == 2) {
            $org_sql ='';
            if(!empty($fields)){
                $filter_fields_arr = array('call_duration','egress_bill_time','egress_cost','egress_id','
release_cause_from_protocol_stack','pdd','egress_rate_type','q850_cause','release_cause');
                $fields_arr = explode(',',trim($fields,','));
                $fields_arr = array_diff($fields_arr,$filter_fields_arr);
                if(!empty($fields_arr)){
                    $fields = implode(',',$fields_arr).', ';
                } else {
                    $fields = '';
                }
            }
            foreach($date_arr as $value){
                $table_name = 'client_cdr'.$value;

                $union = "";
                if(!empty($org_sql)){
                    $union = " union all ";
                }

                $org_sql .= " {$union}  SELECT
{$fields}
call_duration,egress_bill_time,egress_cost,egress_id,
release_cause_from_protocol_stack,pdd,egress_rate_type,q850_cause,release_cause
from {$table_name}
where time between '{$start_date}' and '{$end_date}' {$wheres} {$filter_client}

";
                $org_sql = str_replace('client_cdr.', $table_name.".", $org_sql);

            }


            $sql = "SELECT
{$out_fields}
sum(call_duration) as duration,
sum(egress_bill_time) as bill_time,
sum(egress_cost) as call_cost, count(*) as total_calls,
count(case when call_duration > 0 then 1 else null end) as
not_zero_calls, count(case when egress_id is not null then 1 else null end) as
success_calls,count(case when release_cause_from_protocol_stack like
'486%' then 1 else null end ) as busy_calls,sum(case when call_duration
> 0 then pdd else 0 end) as pdd,count( case when
release_cause_from_protocol_stack like '487%' then 1 else null end ) as
cancel_calls,
sum(case when egress_rate_type = 1
then egress_cost else 0 end) as inter_cost,
sum(case when egress_rate_type = 2
then egress_cost else 0 end) as intra_cost,
count(case when q850_cause in (16,17,18,19,21) then 1 else null end) as q850_cause_count
from ( {$org_sql} ) as tmp
{$groups} {$orders}";
        }

//pr($type);pr($sql);





        /* if ($type == 1)
         {
             $sql = "SELECT
 {$fields}
 sum(call_duration) as duration,
 sum(ingress_client_bill_time) as bill_time,
 sum(ingress_client_cost) as call_cost,
 sum(lnp_dipping_cost) as lnp_cost, count(*) as
 total_calls, count(case when call_duration > 0 then 1 else null end)
 as not_zero_calls, count(case when egress_id is not null then 1 else null end)
 as success_calls,count(case when
 binary_value_of_release_cause_from_protocol_stack like '486%' then 1
 else null end) as busy_calls,count(case when lrn_number_vendor != 0
 then 1 else null end) as lrn_calls, sum(case when call_duration > 0
 then pdd else 0 end) as pdd,count( case when
 binary_value_of_release_cause_from_protocol_stack like '487%' then 1
 else null end ) as cancel_calls,
 sum(case when ingress_rate_type = 1
 then ingress_client_cost else 0 end) as inter_cost,
 sum(case when ingress_rate_type = 2
 then ingress_client_cost else 0 end) as intra_cost,
 count(case when q850_cause in (16,17,18,19,21) then 1 else null end) as q850_cause_count,
 sum(case when release_cause = 13 then 1 else 0 end) as npr_value
 from client_cdr where  time between '{$start_date}' and '{$end_date}'

 and is_final_call=1


 {$wheres} {$filter_client}

 {$groups} {$orders}";
         }
         else if ($type == 2)
         {
             $sql = "SELECT
 {$fields}
 sum(call_duration) as duration,
 sum(egress_bill_time) as bill_time,
 sum(egress_cost) as call_cost, count(*) as total_calls,
 count(case when call_duration > 0 then 1 else null end) as
 not_zero_calls, count(case when egress_id is not null then 1 else null end) as
 success_calls,count(case when release_cause_from_protocol_stack like
 '486%' then 1 else null end ) as busy_calls,sum(case when call_duration
 > 0 then pdd else 0 end) as pdd,count( case when
 release_cause_from_protocol_stack like '487%' then 1 else null end ) as
 cancel_calls,
 sum(case when egress_rate_type = 1
 then egress_cost else 0 end) as inter_cost,
 sum(case when egress_rate_type = 2
 then egress_cost else 0 end) as intra_cost,
 count(case when q850_cause in (16,17,18,19,21) then 1 else null end) as q850_cause_count
 from client_cdr where time between '{$start_date}' and '{$end_date}'
 {$wheres}  {$filter_client} {$groups} {$orders}";
         }
        */


        return $sql;
    }

    public function get_bandwidth_from_cdr($start_date, $end_date)
    {
        $field_arr = array();
        $group_arr = array();
        $show_fields = array();
        $order_num = 0;
        $where_arr = array();
        $group_select_arr = array();


        if (isset($_GET['group_by_date']) && !empty($_GET['group_by_date']))
        {
            array_push($field_arr, "to_char(time, '{$_GET['group_by_date']}') as group_time");
            $show_fields['group_time'] = "group_time";
            array_push($group_arr, "to_char(time, '{$_GET['group_by_date']}')");
            $order_num++;
        }


        if (isset($_GET['route_prefix']) && $_GET['route_prefix'] != 'all')
        {
            array_push($where_arr, "route_prefix = '{$_GET['route_prefix']}'");
        }

        if (isset($_GET['ingress_rate_table']) && $_GET['ingress_rate_table'] != 'all')
        {
            array_push($where_arr, "ingress_client_rate_table_id = {$_GET['ingress_rate_table']}");
        }

        if (isset($_GET['ingress_routing_plan']) && $_GET['ingress_routing_plan'] != 'all')
        {

            array_push($where_arr, "route_plan = {$_GET['ingress_routing_plan']}");
        }

        if (isset($_GET['orig_rate_type']) && $_GET['orig_rate_type'] != '0')
        {
            if ($_GET['orig_rate_type'] == '1')
            {
                array_push($where_arr, "orig_jur_type = 0");
            }
            elseif ($_GET['orig_rate_type'] == '2')
            {
                array_push($where_arr, "orig_jur_type in (1, 2)");
            }
            elseif ($_GET['orig_rate_type'] == '3')
            {
                array_push($where_arr, "orig_jur_type in (3, 4)");
            }
        }

        if (isset($_GET['term_rate_type']) && $_GET['term_rate_type'] != '0')
        {
            if ($_GET['term_rate_type'] == '1')
            {
                array_push($where_arr, "term_jur_type = 0");
            }
            elseif ($_GET['term_rate_type'] == '2')
            {
                array_push($where_arr, "term_jur_type in (1, 2)");
            }
            elseif ($_GET['term_rate_type'] == '3')
            {
                array_push($where_arr, "term_jur_type in (3, 4)");
            }
        }

        if (isset($_GET['ingress_client_id']) && !empty($_GET['ingress_client_id']))
            array_push($where_arr, "ingress_client_id = {$_GET['ingress_client_id']}");
        if (isset($_GET['ingress_id']) && !empty($_GET['ingress_id']))
            array_push($where_arr, "ingress_id = {$_GET['ingress_id']}");
        if (isset($_GET['orig_country']) && !empty($_GET['orig_country']))
            array_push($where_arr, "orig_country = '{$_GET['orig_country']}'");
        if (isset($_GET['orig_code_name']) && !empty($_GET['orig_code_name']))
            array_push($where_arr, "orig_code_name = '{$_GET['orig_code_name']}'");
        if (isset($_GET['orig_code']) && !empty($_GET['orig_code']))
            array_push($where_arr, "orig_code::prefix_range <@  '{$_GET['orig_code']}'");
        if (isset($_GET['egress_client_id']) && !empty($_GET['egress_client_id']))
            array_push($where_arr, "egress_client_id = {$_GET['egress_client_id']}");
        if (isset($_GET['egress_id']) && !empty($_GET['egress_id']))
            array_push($where_arr, "egress_id = {$_GET['egress_id']}");
        if (isset($_GET['term_country']) && !empty($_GET['term_country']))
            array_push($where_arr, "term_country = '{$_GET['term_country']}'");
        if (isset($_GET['term_code_name']) && !empty($_GET['term_code_name']))
            array_push($where_arr, "term_code_name = '{$_GET['term_code_name']}'");
        if (isset($_GET['term_code']) && !empty($_GET['term_code']))
            array_push($where_arr, "term_code::prefix_range <@ '{$_GET['term_code']}'");

        if (isset($_GET['group_select']) && !empty($_GET['group_select']))
        {
            $group_select_arr = $_GET['group_select'];
            foreach ($group_select_arr as $group_select)
            {
                if (!empty($group_select) && !in_array($group_select, $group_arr))
                {
                    array_push($group_arr, $group_select);
                    if ($group_select == 'ingress_client_id')
                        array_push($field_arr, "(SELECT name FROM client WHERE client_id = ingress_client_id) AS ingress_client_id");
                    elseif ($group_select == 'egress_client_id')
                        array_push($field_arr, "(SELECT name FROM client WHERE client_id = egress_client_id) AS egress_client_id");
                    elseif ($group_select == 'ingress_id')
                        array_push($field_arr, "(SELECT alias FROM resource WHERE resource_id = ingress_id) AS ingress_id");
                    elseif ($group_select == 'egress_id')
                        array_push($field_arr, "(SELECT alias FROM resource WHERE resource_id = egress_id) AS egress_id");
                    elseif ($group_select == 'ingress_country')
                        array_push($field_arr, "orig_country as ingress_country");
                    elseif ($group_select == 'ingress_code_name')
                        array_push($field_arr, "orig_code_name as ingress_code_name");
                    elseif ($group_select == 'ingress_code')
                        array_push($field_arr, "orig_code as ingress_code");
                    elseif ($group_select == 'egress_country')
                        array_push($field_arr, "term_country as egress_country");
                    elseif ($group_select == 'egress_code_name')
                        array_push($field_arr, "term_code_name as egress_code_name");
                    elseif ($group_select == 'egress_code')
                        array_push($field_arr, "term_code as egress_code");
                    elseif ($group_select == 'ingress_rate')
                        array_push($field_arr, 'ingress_client_rate as ingress_rate');
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
            $orders = "ORDER BY " . implode(',', range(1, $order_num));
        }


        if (count($where_arr))
        {
            $wheres = " and " . implode(' and ', $where_arr);
        }
        $sst_user_id = $_SESSION['sst_user_id'];
        if (!empty($groups))
        {
            $filter_client = "and
(exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id=client_cdr.ingress_client_id) OR exists 
(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id} 
and (role_name = 'admin' or sys_role.view_all = true)))";
        }
        else
        {
            $filter_client = '';
        }

        $sql = "SELECT
{$fields}   
sum(origination_ingress_packets +termination_ingress_packets) as incoming_bandwidth,
sum(origination_egress_packets +termination_egress_packets) as outgoing_bandwidth,count(id) as calls 
from client_cdr".date("Ymd")." where time between '{$start_date}' and '{$end_date}' {$wheres} {$filter_client}  {$groups} {$orders}";

        return $sql;
    }

    public function get_cdrs_two($sql1, $sql2, $type, $orders, $show_fields)
    {

        $fields = implode(', ', $show_fields);
        $group_by = count($show_fields) ? "group by {$fields}" : '';
        $the_fields = '';
        if (!empty($fields))
            $the_fields = $fields . ',';
        if ($type == 1)
        {
            $total_fields = "sum(duration) as duration, sum(bill_time) as bill_time, sum(call_cost) as call_cost, 
sum(lnp_cost) as lnp_cost, sum(total_calls) as total_calls, sum(not_zero_calls) as not_zero_calls, 
sum(success_calls) as success_calls, sum(busy_calls) as busy_calls, sum(lrn_calls) as lrn_calls, 
sum(pdd) as pdd, sum(cancel_calls) as cancel_calls,sum(inter_cost) as inter_cost, sum(intra_cost) as intra_cost,sum(q850_cause_count) as q850_cause_count,sum(npr_value) as npr_value";
        }
        else
        {
            $total_fields = "sum(duration) as duration, sum(bill_time) as bill_time, sum(call_cost) as call_cost,
sum(total_calls) as total_calls, sum(not_zero_calls) as not_zero_calls, sum(success_calls) as success_calls,
sum(busy_calls) as busy_calls, sum(pdd) as pdd, sum(cancel_calls) as cancel_calls,sum(inter_cost) as inter_cost, sum(intra_cost) as intra_cost,sum(q850_cause_count) as q850_cause_count";
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
        // pr($sql1);pr($sql2);pr($sql);exit;
        return $sql;
    }

    public function get_bandwidth_two($sql1, $sql2, $orders, $show_fields)
    {

        $fields = implode(', ', $show_fields);
        $group_by = count($show_fields) ? "group by {$fields}" : '';
        $the_fields = '';
        if (!empty($fields))
            $the_fields = $fields . ',';
        $total_fields = "sum(incoming_bandwidth) as incoming_bandwidth, sum(outgoing_bandwidth) as outgoing_bandwidth, sum(calls) as calls";
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

    public function get_inout_from_two($sql1, $sql2, $orders, $show_fields)
    {
        $fields = implode(', ', $show_fields);
        $the_fields = '';
        $group_by = count($show_fields) ? "group by {$fields}" : '';
        if (!empty($fields))
            $the_fields = $fields . ',';
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
        return $sql;
    }

    public function get_inout_from_client_cdr($start_date, $end_date)
    {
        $field_arr = array();
        $out_field_arr = array();
        $group_arr = array();
        $show_fields = array();
        $order_num = 0;
        $where_arr = array();
        $group_select_arr = array();


        if (isset($_GET['group_by_date']) && !empty($_GET['group_by_date']))
        {
            array_push($out_field_arr, "group_time");
            array_push($field_arr, "to_char(time, '{$_GET['group_by_date']}') as group_time");
            $show_fields['group_time'] = "group_time";
            array_push($group_arr, "group_time");
            $order_num++;
        }


        if (isset($_GET['route_prefix']) && $_GET['route_prefix'] != 'all')
        {
            array_push($where_arr, "route_prefix = '{$_GET['route_prefix']}'");
        }

        if (isset($_GET['ingress_rate_table']) && $_GET['ingress_rate_table'] != 'all')
        {
            array_push($where_arr, "ingress_client_rate_table_id = {$_GET['ingress_rate_table']}");
        }

        if (isset($_GET['ingress_routing_plan']) && $_GET['ingress_routing_plan'] != 'all')
        {

            array_push($where_arr, "route_plan = {$_GET['ingress_routing_plan']}");
        }

        if (isset($_GET['orig_rate_type']) && $_GET['orig_rate_type'] != '0')
        {
            if ($_GET['orig_rate_type'] == '1')
            {
                array_push($where_arr, "orig_jur_type = 0");
            }
            elseif ($_GET['orig_rate_type'] == '2')
            {
                array_push($where_arr, "orig_jur_type in (1, 2)");
            }
            elseif ($_GET['orig_rate_type'] == '3')
            {
                array_push($where_arr, "orig_jur_type in (3, 4)");
            }
        }

        if (isset($_GET['term_rate_type']) && $_GET['term_rate_type'] != '0')
        {
            if ($_GET['term_rate_type'] == '1')
            {
                array_push($where_arr, "term_jur_type = 0");
            }
            elseif ($_GET['term_rate_type'] == '2')
            {
                array_push($where_arr, "term_jur_type in (1, 2)");
            }
            elseif ($_GET['term_rate_type'] == '3')
            {
                array_push($where_arr, "term_jur_type in (3, 4)");
            }
        }

        if (isset($_GET['ingress_client_id']) && !empty($_GET['ingress_client_id']))
            array_push($where_arr, "ingress_client_id = {$_GET['ingress_client_id']}");
        if (isset($_GET['ingress_id']) && !empty($_GET['ingress_id']))
            array_push($where_arr, "ingress_id = {$_GET['ingress_id']}");
        if (isset($_GET['orig_country']) && !empty($_GET['orig_country']))
            array_push($where_arr, "orig_country = '{$_GET['orig_country']}'");
        if (isset($_GET['orig_code_name']) && !empty($_GET['orig_code_name']))
            array_push($where_arr, "orig_code_name = '{$_GET['orig_code_name']}'");
        if (isset($_GET['orig_code']) && !empty($_GET['orig_code']))
            array_push($where_arr, "orig_code::prefix_range <@  '{$_GET['orig_code']}'");
        if (isset($_GET['egress_client_id']) && !empty($_GET['egress_client_id']))
            array_push($where_arr, "egress_client_id = {$_GET['egress_client_id']}");
        if (isset($_GET['egress_id']) && !empty($_GET['egress_id']))
            array_push($where_arr, "egress_id = {$_GET['egress_id']}");
        if (isset($_GET['term_country']) && !empty($_GET['term_country']))
            array_push($where_arr, "term_country = '{$_GET['term_country']}'");
        if (isset($_GET['term_code_name']) && !empty($_GET['term_code_name']))
            array_push($where_arr, "term_code_name = '{$_GET['term_code_name']}'");
        if (isset($_GET['term_code']) && !empty($_GET['term_code']))
            array_push($where_arr, "term_code::prefix_range <@ '{$_GET['term_code']}'");

        if (isset($_GET['group_select']) && !empty($_GET['group_select']))
        {
            $group_select_arr = $_GET['group_select'];
            foreach ($group_select_arr as $group_select)
            {
                if (!empty($group_select) && !in_array($group_select, $group_arr))
                {
                    array_push($group_arr, $group_select);
                    if ($group_select == 'ingress_client_id'){

                        array_push($out_field_arr, "(SELECT name FROM client WHERE client_id = ingress_client_id) AS ingress_client_id");
                        array_push($field_arr, "ingress_client_id");
                    }
                    elseif ($group_select == 'egress_client_id'){

                        array_push($out_field_arr, "(SELECT name FROM client WHERE client_id = egress_client_id) AS egress_client_id");
                        array_push($field_arr, "egress_client_id");
                    }
                    elseif ($group_select == 'ingress_id'){

                        array_push($out_field_arr, "(SELECT alias FROM resource WHERE resource_id = ingress_id) AS ingress_id");
                        array_push($field_arr, "ingress_id");
                    }
                    elseif ($group_select == 'egress_id'){

                        array_push($out_field_arr, "(SELECT alias FROM resource WHERE resource_id = egress_id) AS egress_id");
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

        $fields = "";
        $out_fields = "";
        $groups = "";
        $orders = "";
        $wheres = "";
        if (count($field_arr))
        {
            $fields = implode(',', $field_arr) . ",";
        }
        if (count($out_field_arr))
        {
            $out_fields = implode(',', $out_field_arr) . ",";
        }
        if (count($group_arr))
        {
            $groups = "GROUP BY " . implode(',', $group_arr);
        }

        if ($order_num > 0)
        {
            $orders = "ORDER BY " . implode(',', range(1, $order_num));
        }


        if (count($where_arr))
        {
            $wheres = " and " . implode(' and ', $where_arr);
        }

        $sst_user_id = $_SESSION['sst_user_id'];
        if (!empty($groups))
        {
            $filter_client = "and
(exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id=client_cdr.ingress_client_id) OR exists 
(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id} 
and (role_name = 'admin' or sys_role.view_all = true)))";
        }
        else
        {
            $filter_client = '';
        }


        //分表
        $date_arr = $this->_app_get_date_result_admin($start_date,$end_date,'client_cdr2%');


        if(!empty($fields)){
            $filter_fields_arr = array('ingress_client_bill_time','ingress_client_cost','egress_bill_time','egress_cost','call_duration',
                'egress_id','release_cause_from_protocol_stack','pdd');
            $fields_arr = explode(',',trim($fields,','));
            $fields_arr = array_diff($fields_arr,$filter_fields_arr);
            if(!empty($fields_arr)){
                $fields = implode(',',$fields_arr).', ';
            } else {
                $fields = '';
            }
        }

        $org_sql ='';
        foreach($date_arr as $value){
            $table_name = "client_cdr".$value;

            $union = "";
            if(!empty($org_sql)){
                $union = " union all ";
            }

            $org_sql .= " {$union}  select {$fields}
             ingress_client_bill_time,ingress_client_cost,egress_bill_time,egress_cost,call_duration,egress_id,
             release_cause_from_protocol_stack,pdd
             from   {$table_name}
             where time between '{$start_date}' and '{$end_date}' {$wheres} {$filter_client}  and is_final_call = 1 ";
            $org_sql = str_replace('client_cdr.', $table_name.".", $org_sql);

        }

        $sql = "SELECT
{$out_fields}
sum(ingress_client_bill_time) as inbound_bill_time,
sum(ingress_client_cost) as inbound_call_cost,
sum(egress_bill_time) as outbound_bill_time,
sum(egress_cost) as outbound_call_cost,
sum(call_duration) as duration,
count(*) as total_calls,
count(case when call_duration > 0 then 1 else null end) as not_zero_calls,
count(case when egress_id is not null then 1 else null end) as success_calls,
count(case when release_cause_from_protocol_stack like
'486%' then 1 else null end) as busy_calls,
sum(case when call_duration > 0 then pdd else 0 end) as pdd
from ( $org_sql ) as tmp {$groups} {$orders}";

//pr($sql);exit;

        /* $sql = "select
 {$fields}
 sum(ingress_client_bill_time) as inbound_bill_time,
 sum(ingress_client_cost) as inbound_call_cost,
 sum(egress_bill_time) as outbound_bill_time,
 sum(egress_cost) as outbound_call_cost,
 sum(call_duration) as duration,
 count(*) as total_calls,
 count(case when call_duration > 0 then 1 else null end) as not_zero_calls,
 count(case when egress_id is not null then 1 else null end) as success_calls,
 count(case when release_cause_from_protocol_stack like
 '486%' then 1 else null end) as busy_calls,
 sum(case when call_duration > 0 then pdd else 0 end) as pdd
 from client_cdr
 where   time between '{$start_date}' and '{$end_date}' {$wheres} {$filter_client}  and is_final_call = 1 {$groups} {$orders}"; */


        return $sql;
    }

    public function get_usage_from_client_cdr($start_date, $end_date, $type)
    {


        $field_arr = array();
        $group_arr = array();
        $show_fields = array();
        $order_num = 0;
        $where_arr = array();
        $group_select_arr = array();
        $field_out_arr = array();


        if (isset($_GET['group_by_date']) && !empty($_GET['group_by_date']))
        {
            array_push($field_arr, "to_char(time, '{$_GET['group_by_date']}') as group_time");
            array_push($field_out_arr, "group_time");

            $show_fields['group_time'] = "group_time";
            array_push($group_arr, "group_time");
            $order_num++;
        }


        if (isset($_GET['route_prefix']) && $_GET['route_prefix'] != 'all')
        {
            array_push($where_arr, "route_prefix = '{$_GET['route_prefix']}'");
        }

        if (isset($_GET['ingress_rate_table']) && $_GET['ingress_rate_table'] != 'all')
        {
            array_push($where_arr, "ingress_client_rate_table_id = {$_GET['ingress_rate_table']}");
        }

        if (isset($_GET['ingress_routing_plan']) && $_GET['ingress_routing_plan'] != 'all')
        {

            array_push($where_arr, "route_plan = {$_GET['ingress_routing_plan']}");
        }

        if (isset($_GET['orig_rate_type']) && $_GET['orig_rate_type'] != '0')
        {
            if ($_GET['orig_rate_type'] == '1')
            {
                array_push($where_arr, "orig_jur_type = 0");
            }
            elseif ($_GET['orig_rate_type'] == '2')
            {
                array_push($where_arr, "orig_jur_type in (1, 2)");
            }
            elseif ($_GET['orig_rate_type'] == '3')
            {
                array_push($where_arr, "orig_jur_type in (3, 4)");
            }
        }

        if (isset($_GET['term_rate_type']) && $_GET['term_rate_type'] != '0')
        {
            if ($_GET['term_rate_type'] == '1')
            {
                array_push($where_arr, "term_jur_type = 0");
            }
            elseif ($_GET['term_rate_type'] == '2')
            {
                array_push($where_arr, "term_jur_type in (1, 2)");
            }
            elseif ($_GET['term_rate_type'] == '3')
            {
                array_push($where_arr, "term_jur_type in (3, 4)");
            }
        }

        if (isset($_GET['ingress_client_id']) && !empty($_GET['ingress_client_id']))
            array_push($where_arr, "ingress_client_id = {$_GET['ingress_client_id']}");
        if (isset($_GET['ingress_id']) && !empty($_GET['ingress_id']))
            array_push($where_arr, "ingress_id = {$_GET['ingress_id']}");
        if (isset($_GET['orig_country']) && !empty($_GET['orig_country']))
            array_push($where_arr, "orig_country = '{$_GET['orig_country']}'");
        if (isset($_GET['orig_code_name']) && !empty($_GET['orig_code_name']))
            array_push($where_arr, "orig_code_name = '{$_GET['orig_code_name']}'");
        if (isset($_GET['orig_code']) && !empty($_GET['orig_code']))
            array_push($where_arr, "orig_code::prefix_range <@  '{$_GET['orig_code']}'");
        if (isset($_GET['egress_client_id']) && !empty($_GET['egress_client_id']))
            array_push($where_arr, "egress_client_id = {$_GET['egress_client_id']}");
        if (isset($_GET['egress_id']) && !empty($_GET['egress_id']))
            array_push($where_arr, "egress_id = {$_GET['egress_id']}");
        if (isset($_GET['term_country']) && !empty($_GET['term_country']))
            array_push($where_arr, "term_country = '{$_GET['term_country']}'");
        if (isset($_GET['term_code_name']) && !empty($_GET['term_code_name']))
            array_push($where_arr, "term_code_name = '{$_GET['term_code_name']}'");
        if (isset($_GET['term_code']) && !empty($_GET['term_code']))
            array_push($where_arr, "term_code::prefix_range <@ '{$_GET['term_code']}'");

        if (isset($_GET['group_select']) && !empty($_GET['group_select']))
        {
            $group_select_arr = $_GET['group_select'];
            foreach ($group_select_arr as $group_select)
            {
                if (!empty($group_select) && !in_array($group_select, $group_arr))
                {
                    array_push($group_arr, $group_select);
                    if ($group_select == 'ingress_client_id'){

                        array_push($field_out_arr, "(SELECT name FROM client WHERE client_id = ingress_client_id) AS ingress_client_id");
                        array_push($field_arr, "ingress_client_id");
                    }
                    elseif ($group_select == 'egress_client_id'){

                        array_push($field_out_arr, "(SELECT name FROM client WHERE client_id = egress_client_id) AS egress_client_id");
                        array_push($field_arr, "egress_client_id");
                    }
                    elseif ($group_select == 'ingress_id'){

                        array_push($field_out_arr, "(SELECT alias FROM resource WHERE resource_id = ingress_id) AS ingress_id");
                        array_push($field_arr, "ingress_id");
                    }
                    elseif ($group_select == 'egress_id'){

                        array_push($field_out_arr, "(SELECT alias FROM resource WHERE resource_id = egress_id) AS egress_id");
                        array_push($field_arr, "egress_id");
                    }
                    elseif ($group_select == 'ingress_country'){

                        array_push($field_out_arr, "orig_country as ingress_country");
                        array_push($field_arr, "orig_country");
                    }
                    elseif ($group_select == 'ingress_code_name'){

                        array_push($field_out_arr, "orig_code_name as ingress_code_name");
                        array_push($field_arr, "orig_code_name");
                    }
                    elseif ($group_select == 'ingress_code'){

                        array_push($field_out_arr, "orig_code as ingress_code");
                        array_push($field_arr, "orig_code");
                    }
                    elseif ($group_select == 'egress_country'){

                        array_push($field_out_arr, "term_country as egress_country");
                        array_push($field_arr, "term_country");
                    }
                    elseif ($group_select == 'egress_code_name'){

                        array_push($field_out_arr, "term_code_name as egress_code_name");
                        array_push($field_arr, "term_code_name");
                    }
                    elseif ($group_select == 'egress_code'){

                        array_push($field_out_arr, "term_code as egress_code");
                        array_push($field_arr, "term_code");
                    }
                    elseif ($group_select == 'ingress_rate'){

                        array_push($field_out_arr, 'ingress_client_rate as ingress_rate');
                        array_push($field_arr, 'ingress_client_rate');
                    }
                    else{

                        array_push($field_out_arr, $group_select);
                        array_push($field_arr, $group_select);
                    }
                    $show_fields[$group_select] = $group_select;
                    $order_num++;
                }
            }
        }

        $fields = "";
        $out_fields = "";
        $groups = "";
        $orders = "";
        $wheres = "";
        if (count($field_arr))
        {
            $fields = implode(',', $field_arr) . ",";
        }
        if (count($field_out_arr))
        {
            $out_fields = implode(',', $field_out_arr) . ",";
        }
        if (count($group_arr))
        {
            $groups = "GROUP BY " . implode(',', $group_arr);
        }

        if ($order_num > 0)
        {
            $orders = "ORDER BY " . implode(',', range(1, $order_num));
        }


        if (count($where_arr))
        {
            $wheres = " and " . implode(' and ', $where_arr);
        }

        $sst_user_id = $_SESSION['sst_user_id'];
        if (!empty($groups))
        {
            $filter_client = "and
(exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id=client_cdr.ingress_client_id) OR exists 
(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id} 
and (role_name = 'admin' or sys_role.view_all = true)))";
        }
        else
        {
            $filter_client = '';
        }


        //分表
        $date_arr = $this->_app_get_date_result_admin($start_date,$end_date,'client_cdr2%');

        if($type == 1){
            $filter_type = 'and is_final_call=1';
        } else if($type == 2){
            $filter_type ='';
        }


        $org_sql ='';
        foreach($date_arr as $value){
            $table_name = "client_cdr".$value;

            $union = "";
            if(!empty($org_sql)){
                $union = " union all ";
            }

            $org_sql .= " {$union}  select $fields call_duration  from   {$table_name}  where time between '{$start_date}' and '{$end_date}' {$wheres} {$filter_client} {$filter_type} ";
            $org_sql = str_replace('client_cdr.', $table_name.".", $org_sql);

        }

        $sql = "SELECT
{$out_fields}
sum(call_duration) as duration,
count(*) as cdr_count from ( $org_sql ) as tmp {$groups} {$orders}";
//pr($sql);


        /*        if ($type == 1)
                {
                    $sql = "SELECT
        {$fields}
        sum(call_duration) as duration,
        count(*) as cdr_count
        from client_cdr where time between '{$start_date}' and '{$end_date}' {$wheres} {$filter_client} and is_final_call=1  {$groups} {$orders}";
                }
                else if ($type == 2)
                {
                    $sql = "SELECT
        {$fields}
        sum(call_duration) as duration,
        count(*) as cdr_count
        from client_cdr where time between '{$start_date}' and '{$end_date}' {$wheres} {$filter_client}  {$groups} {$orders}";
                }
        */

        //echo $sql;
        $result = $this->query($sql);
        return $result;
    }

    public function get_profit_from_client_cdr($start_date, $end_date, $type)
    {
        $field_arr = array();
        $out_field_arr = array();
        $group_arr = array();
        $show_fields = array();
        $order_num = 0;
        $where_arr = array();
        $group_select_arr = array();


        if (isset($_GET['group_by_date']) && !empty($_GET['group_by_date']))
        {
            array_push($out_field_arr, "group_time");
            array_push($field_arr, "to_char(time, '{$_GET['group_by_date']}') as group_time");
            $show_fields['group_time'] = "group_time";
            array_push($group_arr, "group_time");
            $order_num++;
        }


        if (isset($_GET['route_prefix']) && $_GET['route_prefix'] != 'all')
        {
            array_push($where_arr, "route_prefix = '{$_GET['route_prefix']}'");
        }

        if (isset($_GET['ingress_rate_table']) && $_GET['ingress_rate_table'] != 'all')
        {
            array_push($where_arr, "ingress_client_rate_table_id = {$_GET['ingress_rate_table']}");
        }

        if (isset($_GET['ingress_routing_plan']) && $_GET['ingress_routing_plan'] != 'all')
        {

            array_push($where_arr, "route_plan = {$_GET['ingress_routing_plan']}");
        }

        if (isset($_GET['orig_rate_type']) && $_GET['orig_rate_type'] != '0')
        {
            if ($_GET['orig_rate_type'] == '1')
            {
                array_push($where_arr, "orig_jur_type = 0");
            }
            elseif ($_GET['orig_rate_type'] == '2')
            {
                array_push($where_arr, "orig_jur_type in (1, 2)");
            }
            elseif ($_GET['orig_rate_type'] == '3')
            {
                array_push($where_arr, "orig_jur_type in (3, 4)");
            }
        }

        if (isset($_GET['term_rate_type']) && $_GET['term_rate_type'] != '0')
        {
            if ($_GET['term_rate_type'] == '1')
            {
                array_push($where_arr, "term_jur_type = 0");
            }
            elseif ($_GET['term_rate_type'] == '2')
            {
                array_push($where_arr, "term_jur_type in (1, 2)");
            }
            elseif ($_GET['term_rate_type'] == '3')
            {
                array_push($where_arr, "term_jur_type in (3, 4)");
            }
        }

        if (isset($_GET['ingress_client_id']) && !empty($_GET['ingress_client_id']))
            array_push($where_arr, "ingress_client_id = {$_GET['ingress_client_id']}");
        if (isset($_GET['ingress_id']) && !empty($_GET['ingress_id']))
            array_push($where_arr, "ingress_id = {$_GET['ingress_id']}");
        if (isset($_GET['orig_country']) && !empty($_GET['orig_country']))
            array_push($where_arr, "orig_country = '{$_GET['orig_country']}'");
        if (isset($_GET['orig_code_name']) && !empty($_GET['orig_code_name']))
            array_push($where_arr, "orig_code_name = '{$_GET['orig_code_name']}'");
        if (isset($_GET['orig_code']) && !empty($_GET['orig_code']))
            array_push($where_arr, "orig_code::prefix_range <@  '{$_GET['orig_code']}'");
        if (isset($_GET['egress_client_id']) && !empty($_GET['egress_client_id']))
            array_push($where_arr, "egress_client_id = {$_GET['egress_client_id']}");
        if (isset($_GET['egress_id']) && !empty($_GET['egress_id']))
            array_push($where_arr, "egress_id = {$_GET['egress_id']}");
        if (isset($_GET['term_country']) && !empty($_GET['term_country']))
            array_push($where_arr, "term_country = '{$_GET['term_country']}'");
        if (isset($_GET['term_code_name']) && !empty($_GET['term_code_name']))
            array_push($where_arr, "term_code_name = '{$_GET['term_code_name']}'");
        if (isset($_GET['term_code']) && !empty($_GET['term_code']))
            array_push($where_arr, "term_code::prefix_range <@ '{$_GET['term_code']}'");

        if (isset($_GET['group_select']) && !empty($_GET['group_select']))
        {
            $group_select_arr = $_GET['group_select'];
            foreach ($group_select_arr as $group_select)
            {
                if (!empty($group_select) && !in_array($group_select, $group_arr))
                {
                    array_push($group_arr, $group_select);
                    if ($group_select == 'ingress_client_id'){

                        array_push($out_field_arr, "(SELECT name FROM client WHERE client_id = ingress_client_id) AS ingress_client_id");
                        array_push($field_arr, "ingress_client_id");
                    }
                    elseif ($group_select == 'egress_client_id'){

                        array_push($out_field_arr, "(SELECT name FROM client WHERE client_id = egress_client_id) AS egress_client_id");
                        array_push($field_arr, "egress_client_id");
                    }
                    elseif ($group_select == 'ingress_id'){
                        array_push($out_field_arr, "(SELECT alias FROM resource WHERE resource_id = ingress_id) AS ingress_id");
                        array_push($field_arr, "ingress_id");

                    }
                    elseif ($group_select == 'egress_id'){

                        array_push($out_field_arr, "(SELECT alias FROM resource WHERE resource_id = egress_id) AS egress_id");
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

        $fields = "";
        $out_fields = "";
        $groups = "";
        $orders = "";
        $wheres = "";
        if (count($field_arr))
        {
            $fields = implode(',', $field_arr) . ",";
        }
        if (count($out_field_arr))
        {
            $out_fields = implode(',', $out_field_arr) . ",";
        }
        if (count($group_arr))
        {
            $groups = "GROUP BY " . implode(',', $group_arr);
        }

        if ($order_num > 0)
        {
            $orders = "ORDER BY " . implode(',', range(1, $order_num));
        }


        if (count($where_arr))
        {
            $wheres = " and " . implode(' and ', $where_arr);
        }
        if ($type == 1)
        {
            $bill_time = "ingress_client_bill_time";
        }
        else
        {
            $bill_time = "egress_bill_time";
        }

        $sst_user_id = $_SESSION['sst_user_id'];
        if (!empty($groups))
        {
            $filter_client = "and
(exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id=client_cdr.ingress_client_id) OR exists 
(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id} 
and (role_name = 'admin' or sys_role.view_all = true)))";
        }
        else
        {
            $filter_client = '';
        }


        //分表
        $date_arr = $this->_app_get_date_result_admin($start_date,$end_date,'client_cdr2%');


        $org_sql ='';
        if(!empty($fields)){
            $filter_fields_arr = array('ingress_client_cost','egress_cost',$bill_time,'call_duration','egress_id');
            $fields_arr = explode(',',trim($fields,','));
            $fields_arr = array_diff($fields_arr,$filter_fields_arr);
            if(!empty($fields_arr)){
                $fields = implode(',',$fields_arr).', ';
            } else {
                $fields = '';
            }

        }
        foreach($date_arr as $value){
            $table_name = 'client_cdr'.$value;

            $union = "";
            if(!empty($org_sql)){
                $union = " union all ";
            }

            $org_sql .= " {$union}  SELECT
{$fields}ingress_client_cost,egress_cost,{$bill_time},call_duration,egress_id
from {$table_name}
where time between '{$start_date}' and '{$end_date}' {$wheres} {$filter_client}  and is_final_call = 1
";
            $org_sql = str_replace('client_cdr.', $table_name.".", $org_sql);

        }

        $sql = "SELECT
{$out_fields}
sum(ingress_client_cost) as inbound_call_cost,
sum(egress_cost) as outbound_call_cost,
sum(call_duration) as duration,
sum({$bill_time}) as bill_time,
count(*) as total_calls,
count(case when call_duration > 0 then 1 else null end) as not_zero_calls,
count(case when egress_id is not null then 1 else null end) as success_calls
from ( {$org_sql} ) as tmp
{$groups} {$orders}";
//        pr($sql);exit;

        /*  $sql = "select
  {$fields}
  sum(ingress_client_cost) as inbound_call_cost,
  sum(egress_cost) as outbound_call_cost,
  sum(call_duration) as duration,
  sum({$bill_time}) as bill_time,
  count(*) as total_calls,
  count(case when call_duration > 0 then 1 else null end) as not_zero_calls,
  count(case when egress_id is not null then 1 else null end) as success_calls
  from client_cdr
  where   time between '{$start_date}' and '{$end_date}' {$wheres} {$filter_client}  and is_final_call = 1 {$groups} {$orders}"; */

        return $sql;
    }

    public function get_premature_from_client_cdr($start_date, $end_date,$ingress_id,$count,$type)
    {
        $field_arr = array();
        $group_arr = array();
        $show_fields = array();
        $order_num = 0;
        $where_arr = array();
        $group_select_arr = array();
        //$ingress_id = 413;
        array_push($where_arr, "ingress_id =  {$ingress_id} ");

        $fields = "";
        $groups = "";
        $orders = "";
        $wheres = "";
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
            $orders = "ORDER BY " . implode(',', range(1, $order_num));
        }


        if (count($where_arr))
        {
            $wheres = " and " . implode(' and ', $where_arr);
        }

        $sst_user_id = $_SESSION['sst_user_id'];
        if (!empty($groups))
        {
            $filter_client = "and
(exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id=client_cdr.ingress_client_id) OR exists 
(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id} 
and (role_name = 'admin' or sys_role.view_all = true)))";
        }
        else
        {
            $filter_client = '';
        }

        //分表
        $date_arr = $this->_app_get_date_result_admin($start_date,$end_date,'client_cdr2%');




        if($type == 'no'){

            $min_count = ($count - 1)*1000 ;
            $max_count = $count*1000;



            $sql = "select
count(*) as count
from client_cdr
where   time between '{$start_date}' and '{$end_date}' {$wheres}   and call_duration = 0 and {$min_count} < pdd  and pdd <=  {$max_count}  and is_final_call = 1";

        }else{
            $sql = "select
count(*) as  count
from client_cdr
where   time between '{$start_date}' and '{$end_date}' {$wheres}   and call_duration =  {$count}  and is_final_call = 1";
        }


        if($count == 0){
            //sleep(5);
            if($type == 'no'){
                $sql = "select
    count(*) as count
    from client_cdr
    where   time between '{$start_date}' and '{$end_date}' {$wheres}   and call_duration = 0  and is_final_call = 1";

            }else{
                $sql = "select
    count(*) as  count
    from client_cdr
    where   time between '{$start_date}' and '{$end_date}' {$wheres}   and call_duration > 0   and is_final_call = 1";
            }
        }



        $org_sql ='';
        foreach($date_arr as $value){
            $table_name = "client_cdr".$value;

            $sql_tmp = str_replace("client_cdr",$table_name,$sql);

            $union = "";
            if(!empty($org_sql)){
                $union = " union all ";
            }



            $org_sql .= " {$union}
            $sql_tmp ";


        }

        $sql = "select sum(count) as count from ( {$org_sql} ) as tmp ";



        /*
                if($type == 'no'){

                    $min_count = ($count - 1)*1000 ;
                    $max_count = $count*1000;
                    $sql = "select
        count(*) as count
        from client_cdr
        where   time between '{$start_date}' and '{$end_date}' {$wheres} {$filter_client}  and call_duration = 0 and {$min_count} < pdd  and pdd <=  {$max_count}  and is_final_call = 1 {$groups} {$orders}";

                }else{
                    $sql = "select
        count(*) as  count
        from client_cdr
        where   time between '{$start_date}' and '{$end_date}' {$wheres} {$filter_client}  and call_duration =  {$count}  and is_final_call = 1 {$groups} {$orders}";
                }


                if($count == 0){
                    //sleep(5);
                    if($type == 'no'){
                        $sql = "select
            count(*) as count
            from client_cdr
            where   time between '{$start_date}' and '{$end_date}' {$wheres} {$filter_client}  and call_duration = 0  and is_final_call = 1 {$groups} {$orders}";

                    }else{
                        $sql = "select
            count(*) as  count
            from client_cdr
            where   time between '{$start_date}' and '{$end_date}' {$wheres} {$filter_client}  and call_duration > 0   and is_final_call = 1 {$groups} {$orders}";
                    }
                }
        */

        $data = $this->query($sql);

        return $data[0][0]['count'];


    }



    public function get_profit_cdrs($start_date, $end_date, $fields, $out_fields, $groups, $orders, $wheres, $type, $table_name)
    {
        if ($type == 1)
        {
            $bill_time = "ingress_bill_time";
        }
        else
        {
            $bill_time = "egress_bill_time";
        }
        $sst_user_id = $_SESSION['sst_user_id'];
        if (!empty($groups))
        {
            $filter_client = "and
(exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id={$table_name}.ingress_client_id) OR exists 
(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id} 
and (role_name = 'admin' or sys_role.view_all = true)))";
        }
        else
        {
            $filter_client = '';
        }


        //分表
        $date_arr = $this->_app_get_date_result_admin($start_date,$end_date,$table_name.'2%');


        $org_sql ='';
        if(!empty($fields)){
            $filter_fields_arr = array('duration','ingress_bill_time','ingress_call_cost','lnp_cost','ingress_total_calls','
not_zero_calls','ingress_success_calls','ingress_busy_calls','
lrn_calls','pdd','ingress_cancel_calls','ingress_call_cost_intra','ingress_call_cost_inter','q850_cause_count','release_cause');
            $fields_arr = explode(',',trim($fields,','));
            $fields_arr = array_diff($fields_arr,$filter_fields_arr);
            if(!empty($fields_arr)){
                $fields = implode(',',$fields_arr).', ';
            } else {
                $fields = '';
            }
        }
        foreach($date_arr as $value){
            $table_date = $table_name.$value;

            $union = "";

            if(!empty($org_sql)){
                $union = " union all ";
            }

            $org_sql .= " {$union}  SELECT
{$fields} ingress_call_cost,egress_call_cost,duration,{$bill_time},ingress_total_calls,not_zero_calls,ingress_success_calls
from {$table_date}
where report_time between '{$start_date}' and '{$end_date}' {$wheres} {$filter_client}

";
            $org_sql = str_replace($table_name.".", $table_date.".", $org_sql);

        }

        $sql = "SELECT
{$out_fields}
sum(ingress_call_cost) as inbound_call_cost,
sum(egress_call_cost) as outbound_call_cost,
sum(duration) as duration,
sum({$bill_time}) as bill_time,
sum(ingress_total_calls) as total_calls,
sum(not_zero_calls) as not_zero_calls,
sum(ingress_success_calls) as success_calls
from  ( {$org_sql} ) as t1
{$groups} {$orders}";





        /*  $sql = "select
  {$fields}
  sum(ingress_call_cost) as inbound_call_cost,
  sum(egress_call_cost) as outbound_call_cost,
  sum(duration) as duration,
  sum({$bill_time}) as bill_time,
  sum(ingress_total_calls) as total_calls,
  sum(not_zero_calls) as not_zero_calls,
  sum(ingress_success_calls) as success_calls
  from {$table_name}
  where report_time between '{$start_date}' and '{$end_date}' {$wheres} {$filter_client}  {$groups} {$orders}"; */
        return $sql;
    }

    public function get_profit_from_two($sql1, $sql2, $type, $orders, $show_fields)
    {

        $fields = implode(', ', $show_fields);
        $group_by = count($show_fields) ? "group by {$fields}" : '';
        $the_fields = '';
        if (!empty($fields))
            $the_fields = $fields . ',';
        if ($type == 1)
        {
            $total_fields = "sum(inbound_call_cost) as inbound_call_cost,  
sum(outbound_call_cost) as outbound_call_cost, sum(duration) as duration, sum(total_calls) as total_calls, 
sum(not_zero_calls) as not_zero_calls, sum(success_calls) as success_calls, sum(bill_time) as bill_time";
        }
        else
        {
            $total_fields = "sum(inbound_call_cost) as inbound_call_cost,  
sum(outbound_call_cost) as outbound_call_cost, sum(duration) as duration, sum(total_calls) as total_calls, 
sum(not_zero_calls) as not_zero_calls, sum(success_calls) as success_calls, sum(bill_time) as bill_time";
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
        return $sql;
    }

    public function get_traffic_profile_reports($start_time, $end_time, $time_zone)
    {
        $sql = "select max(cps), server_ip, res_id from qos_resource where report_time between '$start_time $time_zone' and '$end_time $time_zone'group by server_ip, res_id";

        $result = $this->query($sql);
        return $result;
    }

    public function get_user_summary_report_from_client_cdr($start_date, $end_date, $type)
    {
        $field_arr = array();
        $group_arr = array();
        $show_fields = array();
        $order_num = 0;
        $where_arr = array();
        $group_select_arr = array();

        if (isset($_GET['group_select']) && !empty($_GET['group_select']))
        {
            $group_select_arr = $_GET['group_select'];
            foreach ($group_select_arr as $group_select)
            {
                if (!empty($group_select) && !in_array($group_select, $group_arr))
                {
                    array_push($group_arr, $group_select);
                    if ($group_select == 'ingress_country')
                        array_push($field_arr, "orig_country as ingress_country");
                    elseif ($group_select == 'ingress_code_name')
                        array_push($field_arr, "orig_code_name as ingress_code_name");
                    elseif ($group_select == 'egress_country')
                        array_push($field_arr, "term_country as egress_country");
                    elseif ($group_select == 'egress_code_name')
                        array_push($field_arr, "term_code_name as egress_code_name");
                    else
                        array_push($field_arr, $group_select);
                    $show_fields[$group_select] = $group_select;
                    $order_num++;
                }
            }
        }

        if (isset($_GET['group_by_date']) && !empty($_GET['group_by_date']))
        {
            array_push($field_arr, "to_char(time, '{$_GET['group_by_date']}') as group_time");
            $show_fields['group_time'] = "group_time";
            array_push($group_arr, "to_char(time, '{$_GET['group_by_date']}')");
            $order_num++;
        }

        if($type == 1){
            array_push($where_arr, "ingress_client_id = {$_SESSION['sst_client_id']}");
            if (isset($_GET['ingress_id']) && !empty($_GET['ingress_id']))
                array_push($where_arr, "ingress_id = {$_GET['ingress_id']}");
        } else{
            array_push($where_arr, "egress_client_id = {$_SESSION['sst_client_id']}");
            if (isset($_GET['egress_id']) && !empty($_GET['egress_id']))
                array_push($where_arr, "egress_id = {$_GET['egress_id']}");
        }

        $fields = "";
        $groups = "";
        $orders = "";
        $wheres = "";
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
            $orders = "ORDER BY " . implode(',', range(1, $order_num));
        }
        if (count($where_arr))
        {
            $wheres = " and " . implode(' and ', $where_arr);
        }
        $sst_user_id = $_SESSION['sst_user_id'];
        $filter_client = '';
//        if (!empty($groups))
//        {
//            $filter_client = "and
//(exists(SELECT id FROM users_limit WHERE user_id = {$sst_user_id} AND client_id=client_cdr.ingress_client_id) OR exists
//(SELECT users.user_id FROM users LEFT JOIN sys_role ON users.role_id = sys_role.role_id WHERE users.user_id = {$sst_user_id}
//and (role_name = 'admin' or sys_role.view_all = true)))";
//        }
//        else
//        {
//            $filter_client = '';
//        };
        if ($type == 1)
        {
            $sql = "SELECT
{$fields}
sum(call_duration) as duration,
sum(ingress_client_bill_time) as bill_time,
sum(ingress_client_cost) as call_cost,
sum(lnp_dipping_cost) as lnp_cost, count(*) as
total_calls, count(case when call_duration > 0 then 1 else null end)
as not_zero_calls, count(case when egress_id is not null then 1 else null end)
as success_calls,count(case when
binary_value_of_release_cause_from_protocol_stack like '486%' then 1
else null end) as busy_calls,count(case when lrn_number_vendor != 0
then 1 else null end) as lrn_calls, sum(case when call_duration > 0
then pdd else 0 end) as pdd,count( case when
binary_value_of_release_cause_from_protocol_stack like '487%' then 1
else null end ) as cancel_calls,
sum(case when ingress_rate_type = 1
then ingress_client_cost else 0 end) as inter_cost,
sum(case when ingress_rate_type = 2
then ingress_client_cost else 0 end) as intra_cost,
sum(rate_overflow(egress_erro_string)) as npr_value,
count(case when q850_cause in (16,17,18,19,21) then 1 else null end) as q850_cause_count
from client_cdr".date("Ymd")." where time between '{$start_date}' and '{$end_date}'

and is_final_call=1


{$wheres} {$filter_client}

{$groups} {$orders}";
        }
        else if ($type == 2)
        {
            $sql = "SELECT
{$fields}
sum(call_duration) as duration,
sum(egress_bill_time) as bill_time,
sum(egress_cost) as call_cost, count(*) as total_calls,
count(case when call_duration > 0 then 1 else null end) as
not_zero_calls, count(case when egress_id is not null then 1 else null end) as
success_calls,count(case when release_cause_from_protocol_stack like
'486%' then 1 else null end ) as busy_calls,sum(case when call_duration
> 0 then pdd else 0 end) as pdd,count( case when
release_cause_from_protocol_stack like '487%' then 1 else null end ) as
cancel_calls,
sum(case when egress_rate_type = 1
then egress_cost else 0 end) as inter_cost,
sum(case when egress_rate_type = 2
then egress_cost else 0 end) as intra_cost,
count(case when q850_cause in (16,17,18,19,21) then 1 else null end) as q850_cause_count
from client_cdr".date("Ymd")." where time between '{$start_date}' and '{$end_date}'
{$wheres} {$filter_client} {$groups} {$orders}";
        }
        return $sql;
    }





    function _get_date_result_admin($start_time,$end_time){
        $data = array();
        $sql = "select TABLE_NAME as name from INFORMATION_SCHEMA.TABLES where TABLE_NAME like '" . CDR_TABLE . "2%' order by TABLE_NAME   limit 1";

        $res = $this->query($sql);
        $last_table_name = $res[0][0]['name'];
        $last_table_name = explode(CDR_TABLE, $last_table_name);

        if(strtotime($start_time) < strtotime($last_table_name[1])){
            $start_time = date("Y-m-d H:i:sO",strtotime($last_table_name[1]));
        }

        if(strtotime($end_time) > time()){
            $end_time = date('Y-m-d H:i:s');
        }

        while(strtotime($start_time) <= strtotime($end_time)){
            $start_time1 = $start_time;

            $data[] = date('Ymd',strtotime($start_time));

            $start_time = date("Y-m-d H:i:sO",strtotime('+1 day',strtotime($start_time1)));
        }

        if(empty($data))
            date('Ymd',strtotime(date('Y-m-d H:i:sO')));

        return $data;
    }

}

?>
