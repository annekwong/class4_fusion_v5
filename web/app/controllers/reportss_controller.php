<?php

class ReportssController extends AppController
{

    var $name = 'Reportss';
    var $uses = array('Reports', 'Cdr');

    public function beforeFilter()
    {

    }


    public function get_ingress_data()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;

        $timezone = $_GET['timezone'];
        $start_time = $_GET['start_time'];
        $end_time = $_GET['end_time'];


        $start_date = $start_time . $timezone;
        $end_date = $end_time . $timezone;

        $report_max_time = $this->Cdr->get_report_maxtime($start_date, $end_date);


        $select_time_end = strtotime($end_date);

        $is_from_client_cdr = false;

        if (empty($report_max_time)) {
            $is_from_client_cdr = true;
            $report_max_time = $start_date;
        }
        $system_max_end = strtotime($report_max_time);

        $country_search = isset($_GET['country_search']) ? $_GET['country_search'] : '';
        $destination = isset($_GET['destination']) ? $_GET['destination'] : '';
        $ingress_trunk = isset($_GET['ingress_trunk']) ? $_GET['ingress_trunk'] : '';
        $egress_trunk = isset($_GET['egress_trunk']) ? $_GET['egress_trunk'] : '';

        $where = array();
        $where2 = array();

        if (!empty($country_search)) {
            array_push($where, "ingress_country = '{$country_search}'");
            array_push($where2, "orig_country = '{$country_search}'");
        }

        if (!empty($destination)) {
            array_push($where, "ingress_code_name = '{$destination}'");
            array_push($where2, "orig_code_name = '{$destination}'");
        }

        if (!empty($ingress_trunk)) {
            array_push($where, "ingress_id = {$ingress_trunk}");
            array_push($where2, "ingress_id = {$ingress_trunk}");
        }

        if (!empty($egress_trunk)) {
            array_push($where, "egress_id = {$egress_trunk}");
            array_push($where2, "egress_id = {$egress_trunk}");
        }

        $data = array(
            array(
                'id' => 0,
                'originator' => 'All',
            )
        );
        $database_conf = new DATABASE_CONFIG;
        $dbh = new PDO("pgsql:host={$database_conf->default['host']};port={$database_conf->default['port']};dbname={$database_conf->default['database']};user={$database_conf->default['login']};password={$database_conf->default['password']}");
        $today_date = date("Ymd");
        if ($select_time_end > $system_max_end) {
            if ($is_from_client_cdr) {
                array_push($where2, "time between '$report_max_time' and '$end_date'");
                array_push($where2, "is_final_call=1");
                $where2_str = implode(" and ", $where2);
                $sql = "select
sum(egress_cost) as egress_call_cost,
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
count(case when call_duration <= 6 then 1 else null end) as call_6s,
count(case when call_duration <= 12 then 1 else null end) as call_12s,
count(case when call_duration <= 18 then 1 else null end) as call_18s,
count(case when call_duration <= 24 then 1 else null end) as call_24s,
count(case when call_duration <= 30 then 1 else null end) as call_30s,
count(case when call_duration >= (7200) then 1 else null end) as call_2h,
count(case when call_duration >= (10800) then 1 else null end) as call_3h,
count(case when call_duration >= (14400) then 1 else null end) as call_4h
from client_cdr{$today_date} where $where2_str";
            } else {
                array_push($where, "report_time between '$start_date' and '$report_max_time'");
                array_push($where2, "time between '$report_max_time' and '$end_date'");
                array_push($where2, "is_final_call=1");
                $where_str = implode(" and ", $where);
                $where2_str = implode(" and ", $where2);
                $sql1 = "SELECT
        sum(egress_call_cost) as egress_call_cost,
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
        sum(not_zero_calls_6) as call_6s,
        sum(call_12s) as call_12s,
        sum(call_18s) as call_18s,
        sum(call_24s) as call_24s,
        sum(not_zero_calls_30) as call_30s,
        sum(call_2h) as call_2h,
        sum(call_3h) as call_3h,
        sum(call_4h) as call_4h
        from " . CDR_TABLE . "
        where $where_str";
                $sql2 = "select
sum(egress_cost) as egress_call_cost,
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
count(case when call_duration <= 6 then 1 else null end) as call_6s,
count(case when call_duration <= 12 then 1 else null end) as call_12s,
count(case when call_duration <= 18 then 1 else null end) as call_18s,
count(case when call_duration <= 24 then 1 else null end) as call_24s,
count(case when call_duration <= 30 then 1 else null end) as call_30s,
count(case when call_duration >= (7200) then 1 else null end) as call_2h,
count(case when call_duration >= (10800) then 1 else null end) as call_3h,
count(case when call_duration >= (14400) then 1 else null end) as call_4h
from client_cdr{$today_date} where $where2_str";
                $sql = "SELECT sum(egress_call_cost) as egress_call_cost,
sum(duration) as duration , sum(bill_time) as bill_time,  sum(call_cost) as call_cost, sum(lnp_cost) as lnp_cost, sum(not_zero_calls) as not_zero_calls,
sum(total_calls) as total_calls, sum(success_calls) as success_calls, sum(busy_calls) as busy_calls, 
sum(lrn_calls) as lrn_calls, sum(pdd) as pdd, sum(cancel_calls) as cancel_calls,
sum(call_6s) as call_6s, sum(call_12s) as call_12s, sum(call_18s) as call_18s, sum(call_24s) as call_24s, sum(call_30s) as call_30s, sum(call_2h) as call_2h, 
sum(call_3h) as call_3h, sum(call_4h) as call_4h
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
as t";
            }
        } else {
            array_push($where, "report_time between '$start_date' and '$end_date'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT
        sum(egress_call_cost) as egress_call_cost,
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
        sum(not_zero_calls_6) as call_6s,
        sum(call_12s) as call_12s,
        sum(call_18s) as call_18s,
        sum(call_24s) as call_24s,
        sum(not_zero_calls_30) as call_30s,
        sum(call_2h) as call_2h,
        sum(call_3h) as call_3h,
        sum(call_4h) as call_4h
        from " . CDR_TABLE . "
        where $where_str";
        }
        $stmt = $dbh->query($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $row = $stmt->fetch();
        $data[0]['children'][0] = array(
            'id' => 1,
            'originator' => 'Ingress Trunk',
            'atts' => number_format($row['total_calls']),
            'cc' => number_format($row['not_zero_calls']),
            'mins' => round($row['bill_time'] / 60, 2),
            'asr' => ($row['not_zero_calls']) == 0 ? 0 : round($row['not_zero_calls'] / $row['total_calls'] * 100, 2),
            'acd' => round($row['not_zero_calls'] == 0 ? 0 : $row['duration'] / $row['not_zero_calls'] / 60, 2),
            'pdd' => round($row['not_zero_calls'] == 0 ? 0 : $row['pdd'] / $row['not_zero_calls']),
//            'cps'        => '-',
            'rev' => number_format($row['call_cost'] + $row['lnp_cost'], 5),
            'cost' => number_format($row['egress_call_cost'], 5),
            'margin' => number_format($row['call_cost'] - $row['egress_call_cost'], 5),
            'call_6s' => $row['call_6s'],
            'call_12s' => $row['call_12s'],
            'call_18s' => $row['call_18s'],
            'call_24s' => $row['call_24s'],
            'call_30s' => $row['call_30s'],
            'call_2h' => $row['call_2h'],
            'call_3h' => $row['call_3h'],
            'call_4h' => $row['call_4h'],
            'max_channel_usage' => '-',
            'max_channel_allowed' => '-',
            'percentage_of_trunk_usage' => '-',
        );


        if ($select_time_end > $system_max_end) {
            if ($is_from_client_cdr) {
                //array_push($where2, "time between '$report_max_time' and '$end_date'");
                //$where2_str = implode(" and ", $where2);
                $sql = "select
(SELECT alias FROM resource WHERE resource_id = ingress_id) AS ingress_name,
ingress_id,
sum(egress_cost) as egress_call_cost,
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
(SELECT max(call) FROM qos_resource WHERE res_id = client_cdr.ingress_id AND report_time between '$start_time' and '$end_time') as max_channel_usage,
(SELECT capacity FROM resource where resource_id = client_cdr.ingress_id) as max_channel_allowed,
count(case when call_duration <= 6 then 1 else null end) as call_6s,
count(case when call_duration <= 12 then 1 else null end) as call_12s,
count(case when call_duration <= 18 then 1 else null end) as call_18s,
count(case when call_duration <= 24 then 1 else null end) as call_24s,
count(case when call_duration <= 30 then 1 else null end) as call_30s,
count(case when call_duration >= (7200) then 1 else null end) as call_2h,
count(case when call_duration >= (10800) then 1 else null end) as call_3h,
count(case when call_duration >= (14400) then 1 else null end) as call_4h
from client_cdr{$today_date} where $where2_str group by ingress_id";
            } else {
                //array_push($where, "report_time between '$start_date' and '$report_max_time'");
                //array_push($where2, "time between '$report_max_time' and '$end_date'");
                //$where_str = implode(" and ", $where);
                //$where2_str = implode(" and ", $where2);
                $sql1 = "SELECT
        ingress_id,
        sum(egress_call_cost) as egress_call_cost,
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
        sum(not_zero_calls_6) as call_6s,
        sum(call_12s) as call_12s,
        sum(call_18s) as call_18s,
        sum(call_24s) as call_24s,
        sum(not_zero_calls_30) as call_30s,
        sum(call_2h) as call_2h,
        sum(call_3h) as call_3h,
        sum(call_4h) as call_4h
        from " . CDR_TABLE . "
        where $where_str group by ingress_id";
                $sql2 = "select
ingress_id,
sum(egress_cost) as egress_call_cost,
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
count(case when call_duration <= 6 then 1 else null end) as call_6s,
count(case when call_duration <= 12 then 1 else null end) as call_12s,
count(case when call_duration <= 18 then 1 else null end) as call_18s,
count(case when call_duration <= 24 then 1 else null end) as call_24s,
count(case when call_duration <= 30 then 1 else null end) as call_30s,
count(case when call_duration >= (7200) then 1 else null end) as call_2h,
count(case when call_duration >= (10800) then 1 else null end) as call_3h,
count(case when call_duration >= (14400) then 1 else null end) as call_4h
from client_cdr{$today_date} where $where2_str group by ingress_id";
                $sql = "SELECT 
(SELECT alias FROM resource WHERE resource_id = ingress_id) AS ingress_name,
ingress_id, sum(egress_call_cost) as egress_call_cost,
sum(duration) as duration , sum(bill_time) as bill_time,  sum(call_cost) as call_cost, sum(lnp_cost) as lnp_cost, sum(not_zero_calls) as not_zero_calls,
sum(total_calls) as total_calls, sum(success_calls) as success_calls, sum(busy_calls) as busy_calls, 
sum(lrn_calls) as lrn_calls, sum(pdd) as pdd, sum(cancel_calls) as cancel_calls,
(SELECT max(call) FROM qos_resource WHERE res_id = t.ingress_id AND report_time between '$start_time' and '$end_time') as max_channel_usage,
(SELECT capacity FROM resource where resource_id = t.ingress_id) as max_channel_allowed,
sum(call_6s) as call_6s, sum(call_12s) as call_12s, sum(call_18s) as call_18s, sum(call_24s) as call_24s, sum(call_30s) as call_30s, sum(call_2h) as call_2h, 
sum(call_3h) as call_3h, sum(call_4h) as call_4h
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
as t group by ingress_id";
            }
        } else {
            //array_push($where, "report_time between '$start_date' and '$end_date'");
            //$where_str = implode(" and ", $where);
            $sql = "SELECT
        (SELECT alias FROM resource WHERE resource_id = ingress_id) AS ingress_name,
        ingress_id,
        sum(egress_call_cost) as egress_call_cost,
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
(SELECT max(call) FROM qos_resource WHERE res_id = " . CDR_TABLE . ".ingress_id AND report_time between '$start_time' and '$end_time') as max_channel_usage,
(SELECT capacity FROM resource where resource_id = " . CDR_TABLE . ".ingress_id) as max_channel_allowed,
        sum(not_zero_calls_6) as call_6s,
        sum(call_12s) as call_12s,
        sum(call_18s) as call_18s,
        sum(call_24s) as call_24s,
        sum(not_zero_calls_30) as call_30s,
        sum(call_2h) as call_2h,
        sum(call_3h) as call_3h,
        sum(call_4h) as call_4h
        from " . CDR_TABLE . "
        where $where_str GROUP BY ingress_id";
        }
        $stmt = $dbh->query($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $stmt->fetch()) {
            $data[0]['children'][0]['children'][] = array(
                'id' => uniqid(),
                'originator' => $row['ingress_name'],
                'atts' => number_format($row['total_calls']),
                'cc' => number_format($row['not_zero_calls']),
                'mins' => round($row['bill_time'] / 60, 2),
                'asr' => ($row['busy_calls'] + $row['cancel_calls'] + $row['not_zero_calls']) == 0 ? 0 : round($row['not_zero_calls'] / ($row['busy_calls'] + $row['cancel_calls'] + $row['not_zero_calls']) * 100, 2),
                'abr' => round($row['total_calls'] == 0 ? 0 : $row['not_zero_calls'] / $row['total_calls'] * 100, 2),
                'acd' => round($row['not_zero_calls'] == 0 ? 0 : $row['duration'] / $row['not_zero_calls'] / 60, 2),
                'pdd' => round($row['not_zero_calls'] == 0 ? 0 : $row['pdd'] / $row['not_zero_calls']),
//                'cps'        => '-',
                'rev' => number_format($row['call_cost'] + $row['lnp_cost'], 5),
                'cost' => number_format($row['egress_call_cost'], 5),
                'margin' => number_format($row['call_cost'] + $row['lnp_cost'] - $row['egress_call_cost'], 5),
                'ingress_id' => $row['ingress_id'],
                'state' => 'closed',
                'call_6s' => $row['call_6s'],
                'call_12s' => $row['call_12s'],
                'call_18s' => $row['call_18s'],
                'call_24s' => $row['call_24s'],
                'call_30s' => $row['call_30s'],
                'call_2h' => $row['call_2h'],
                'call_3h' => $row['call_3h'],
                'call_4h' => $row['call_4h'],
                'max_channel_usage' => $row['max_channel_usage'] == NULL ? '-' : $row['max_channel_usage'],
                'max_channel_allowed' => $row['max_channel_allowed'] == NULL ? 'Unlimited' : $row['max_channel_allowed'],
                'percentage_of_trunk_usage' => $row['max_channel_allowed'] == NULL || $row['max_channel_allowed'] == NULL ? '-' : round($row['max_channel_usage'] / $row['max_channel_allowed'] * 100, 2) . '%',
            );
        }


        $stmt->closeCursor();
        $dbh = NULL;

        echo json_encode($data);
    }


    public function get_ingress_data1()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;

        $ingress_id = $_GET['ingress_id'];

        $timezone = $_GET['timezone'];
        $start_time = $_GET['start_time'];
        $end_time = $_GET['end_time'];
        $country_search = isset($_GET['country_search']) ? $_GET['country_search'] : '';
        $destination = isset($_GET['destination']) ? $_GET['destination'] : '';
        $ingress_trunk = isset($_GET['ingress_trunk']) ? $_GET['ingress_trunk'] : '';
        $egress_trunk = isset($_GET['egress_trunk']) ? $_GET['egress_trunk'] : '';


        $start_date = $start_time . $timezone;
        $end_date = $end_time . $timezone;

        $report_max_time = $this->Cdr->get_report_maxtime($start_date, $end_date);


        $select_time_end = strtotime($end_date);

        $is_from_client_cdr = false;

        if (empty($report_max_time)) {
            $is_from_client_cdr = true;
            $report_max_time = $start_date;
        }
        $system_max_end = strtotime($report_max_time);

        $where = array();
        $where2 = array();

        if (!empty($country_search)) {
            array_push($where, "ingress_country = '{$country_search}'");
            array_push($where2, "orig_country = '{$country_search}'");
        }

        if (!empty($destination)) {
            array_push($where, "ingress_code_name = '{$destination}'");
            array_push($where2, "orig_code_name = '{$destination}'");
        }

        if (!empty($ingress_trunk)) {
            array_push($where, "ingress_id = {$ingress_trunk}");
            array_push($where2, "ingress_id = {$ingress_trunk}");
        }

        if (!empty($egress_trunk)) {
            array_push($where, "egress_id = {$egress_trunk}");
            array_push($where2, "egress_id = {$egress_trunk}");
        }


        $data = array();

        $database_conf = new DATABASE_CONFIG;
        $dbh = new PDO("pgsql:host={$database_conf->default['host']};port={$database_conf->default['port']};dbname={$database_conf->default['database']};user={$database_conf->default['login']};password={$database_conf->default['password']}");

        $today_date = date("Ymd");
        if ($select_time_end > $system_max_end) {
            if ($is_from_client_cdr) {
                array_push($where2, "time between '$report_max_time' and '$end_date'");
                array_push($where2, "is_final_call=1");
                $where2_str = implode(" and ", $where2);
                $sql = "select
orig_country as ingress_country,
sum(egress_cost) as egress_call_cost,
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
count(case when call_duration <= 6 then 1 else null end) as call_6s,
count(case when call_duration <= 12 then 1 else null end) as call_12s,
count(case when call_duration <= 18 then 1 else null end) as call_18s,
count(case when call_duration <= 24 then 1 else null end) as call_24s,
count(case when call_duration <= 30 then 1 else null end) as call_30s,
count(case when call_duration >= (7200) then 1 else null end) as call_2h,
count(case when call_duration >= (10800) then 1 else null end) as call_3h,
count(case when call_duration >= (14400) then 1 else null end) as call_4h
from client_cdr{$today_date} where $where2_str and ingress_id = {$ingress_id} group by orig_country";
            } else {
                array_push($where, "report_time between '$start_date' and '$report_max_time'");
                array_push($where2, "time between '$report_max_time' and '$end_date'");
                $where_str = implode(" and ", $where);
                $where2_str = implode(" and ", $where2);
                $sql1 = "SELECT
        ingress_country,
        sum(egress_call_cost) as egress_call_cost,
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
        sum(not_zero_calls_6) as call_6s,
        sum(call_12s) as call_12s,
        sum(call_18s) as call_18s,
        sum(call_24s) as call_24s,
        sum(not_zero_calls_30) as call_30s,
        sum(call_2h) as call_2h,
        sum(call_3h) as call_3h,
        sum(call_4h) as call_4h
        from " . CDR_TABLE . "
        where $where_str and ingress_id = {$ingress_id} group by ingress_country";
                $sql2 = "select
orig_country as ingress_country,
sum(egress_cost) as egress_call_cost,
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
count(case when call_duration <= 6 then 1 else null end) as call_6s,
count(case when call_duration <= 12 then 1 else null end) as call_12s,
count(case when call_duration <= 18 then 1 else null end) as call_18s,
count(case when call_duration <= 24 then 1 else null end) as call_24s,
count(case when call_duration <= 30 then 1 else null end) as call_30s,
count(case when call_duration >= (7200) then 1 else null end) as call_2h,
count(case when call_duration >= (10800) then 1 else null end) as call_3h,
count(case when call_duration >= (14400) then 1 else null end) as call_4h
from client_cdr{$today_date} where $where2_str and ingress_id = {$ingress_id} group by orig_country";
                $sql = "SELECT 
ingress_country, sum(egress_call_cost) as egress_call_cost,
sum(duration) as duration , sum(bill_time) as bill_time,  sum(call_cost) as call_cost, sum(lnp_cost) as lnp_cost, sum(not_zero_calls) as not_zero_calls,
sum(total_calls) as total_calls, sum(success_calls) as success_calls, sum(busy_calls) as busy_calls, 
sum(lrn_calls) as lrn_calls, sum(pdd) as pdd, sum(cancel_calls) as cancel_calls,
sum(call_6s) as call_6s, sum(call_12s) as call_12s, sum(call_18s) as call_18s, sum(call_24s) as call_24s, sum(call_30s) as call_30s, sum(call_2h) as call_2h, 
sum(call_3h) as call_3h, sum(call_4h) as call_4h
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
as t group by ingress_country";
            }
        } else {
            array_push($where, "report_time between '$start_date' and '$end_date'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT
        ingress_country,
        sum(egress_call_cost) as egress_call_cost,
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
        sum(not_zero_calls_6) as call_6s,
        sum(call_12s) as call_12s,
        sum(call_18s) as call_18s,
        sum(call_24s) as call_24s,
        sum(not_zero_calls_30) as call_30s,
        sum(call_2h) as call_2h,
        sum(call_3h) as call_3h,
        sum(call_4h) as call_4h
        from " . CDR_TABLE . "
        where $where_str and ingress_id = {$ingress_id} GROUP BY ingress_country";
        }
        $stmt = $dbh->query($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);


        while ($row = $stmt->fetch()) {
            $data[] = array(
                'id' => uniqid(),
                'originator' => $row['ingress_country'],
                'atts' => number_format($row['total_calls']),
                'cc' => number_format($row['not_zero_calls']),
                'mins' => round($row['bill_time'] / 60, 2),
                'asr' => round($row['total_calls'] == 0 ? 0 : $row['not_zero_calls'] / $row['total_calls'] * 100, 2),
                'acd' => round($row['not_zero_calls'] == 0 ? 0 : $row['duration'] / $row['not_zero_calls'] / 60, 2),
                'pdd' => round($row['not_zero_calls'] == 0 ? 0 : $row['pdd'] / $row['not_zero_calls']),
//                'cps'        => '-',
                'rev' => number_format($row['call_cost'] + $row['lnp_cost'], 5),
                'cost' => number_format($row['egress_call_cost'], 5),
                'margin' => number_format($row['call_cost'] + $row['lnp_cost'] - $row['egress_call_cost'], 5),
                'ingress_id' => $ingress_id,
                'type' => 'country',
                'state' => 'closed',
                'call_6s' => $row['call_6s'],
                'call_12s' => $row['call_12s'],
                'call_18s' => $row['call_18s'],
                'call_24s' => $row['call_24s'],
                'call_30s' => $row['call_30s'],
                'call_2h' => $row['call_2h'],
                'call_3h' => $row['call_3h'],
                'call_4h' => $row['call_4h'],
                'max_channel_usage' => '-',
                'max_channel_allowed' => '-',
                'percentage_of_trunk_usage' => '-',
            );
        }

        $stmt->closeCursor();
        $dbh = NULL;

        echo json_encode($data);
    }


    public function get_ingress_data2()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $ingress_id = $_GET['ingress_id'];
        $country = $_GET['country'];

        $timezone = $_GET['timezone'];
        $start_time = $_GET['start_time'];
        $end_time = $_GET['end_time'];
        $country_search = isset($_GET['country_search']) ? $_GET['country_search'] : '';
        $destination = isset($_GET['destination']) ? $_GET['destination'] : '';
        $ingress_trunk = isset($_GET['ingress_trunk']) ? $_GET['ingress_trunk'] : '';
        $egress_trunk = isset($_GET['egress_trunk']) ? $_GET['egress_trunk'] : '';

        $start_date = $start_time . $timezone;
        $end_date = $end_time . $timezone;

        $report_max_time = $this->Cdr->get_report_maxtime($start_date, $end_date);


        $select_time_end = strtotime($end_date);

        $is_from_client_cdr = false;

        if (empty($report_max_time)) {
            $is_from_client_cdr = true;
            $report_max_time = $start_date;
        }
        $system_max_end = strtotime($report_max_time);

        $where = array();
        $where2 = array();

        if (!empty($country_search)) {
            array_push($where, "ingress_country = '{$country_search}'");
            array_push($where2, "orig_country = '{$country_search}'");
        }

        if (!empty($destination)) {
            array_push($where, "ingress_code_name = '{$destination}'");
            array_push($where2, "orig_code_name = '{$destination}'");
        }

        if (!empty($ingress_trunk)) {
            array_push($where, "ingress_id = {$ingress_trunk}");
            array_push($where2, "ingress_id = {$ingress_trunk}");
        }

        if (!empty($egress_trunk)) {
            array_push($where, "egress_id = {$egress_trunk}");
            array_push($where2, "egress_id = {$egress_trunk}");
        }


        $data = array();

        $database_conf = new DATABASE_CONFIG;
        $dbh = new PDO("pgsql:host={$database_conf->default['host']};port={$database_conf->default['port']};dbname={$database_conf->default['database']};user={$database_conf->default['login']};password={$database_conf->default['password']}");
        $today_date = date("Ymd");
        if ($select_time_end > $system_max_end) {
            if ($is_from_client_cdr) {
                array_push($where2, "time between '$report_max_time' and '$end_date'");
                array_push($where2, "is_final_call=1");
                $where2_str = implode(" and ", $where2);
                $sql = "select
orig_code_name as ingress_code_name,
sum(egress_cost) as egress_call_cost,
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
count(case when call_duration <= 6 then 1 else null end) as call_6s,
count(case when call_duration <= 12 then 1 else null end) as call_12s,
count(case when call_duration <= 18 then 1 else null end) as call_18s,
count(case when call_duration <= 24 then 1 else null end) as call_24s,
count(case when call_duration <= 30 then 1 else null end) as call_30s,
count(case when call_duration >= (7200) then 1 else null end) as call_2h,
count(case when call_duration >= (10800) then 1 else null end) as call_3h,
count(case when call_duration >= (14400) then 1 else null end) as call_4h
from client_cdr{$today_date} where $where2_str and ingress_id = {$ingress_id} and orig_country = '{$country}' group by orig_code_name";
            } else {
                array_push($where, "report_time between '$start_date' and '$report_max_time'");
                array_push($where2, "time between '$report_max_time' and '$end_date'");
                array_push($where2, "is_final_call=1");
                $where_str = implode(" and ", $where);
                $where2_str = implode(" and ", $where2);
                $sql1 = "SELECT
        ingress_code_name,
        sum(egress_call_cost) as egress_call_cost,
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
        sum(not_zero_calls_6) as call_6s,
        sum(call_12s) as call_12s,
        sum(call_18s) as call_18s,
        sum(call_24s) as call_24s,
        sum(not_zero_calls_30) as call_30s,
        sum(call_2h) as call_2h,
        sum(call_3h) as call_3h,
        sum(call_4h) as call_4h
        from " . CDR_TABLE . "
        where $where_str and ingress_id = {$ingress_id} and ingress_country = '{$country}' GROUP BY ingress_code_name";
                $sql2 = "select
orig_code_name as ingress_code_name,
sum(egress_cost) as egress_call_cost,
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
count(case when call_duration <= 6 then 1 else null end) as call_6s,
count(case when call_duration <= 12 then 1 else null end) as call_12s,
count(case when call_duration <= 18 then 1 else null end) as call_18s,
count(case when call_duration <= 24 then 1 else null end) as call_24s,
count(case when call_duration <= 30 then 1 else null end) as call_30s,
count(case when call_duration >= (7200) then 1 else null end) as call_2h,
count(case when call_duration >= (10800) then 1 else null end) as call_3h,
count(case when call_duration >= (14400) then 1 else null end) as call_4h
from client_cdr{$today_date} where $where2_str and ingress_id = {$ingress_id} and orig_country = '{$country}' group by orig_code_name";
                $sql = "SELECT 
ingress_code_name, sum(egress_call_cost) as egress_call_cost,
sum(duration) as duration , sum(bill_time) as bill_time,  sum(call_cost) as call_cost, sum(lnp_cost) as lnp_cost, sum(not_zero_calls) as not_zero_calls,
sum(total_calls) as total_calls, sum(success_calls) as success_calls, sum(busy_calls) as busy_calls, 
sum(lrn_calls) as lrn_calls, sum(pdd) as pdd, sum(cancel_calls) as cancel_calls,
sum(call_6s) as call_6s, sum(call_12s) as call_12s, sum(call_18s) as call_18s, sum(call_24s) as call_24s, sum(call_30s) as call_30s, sum(call_2h) as call_2h, 
sum(call_3h) as call_3h, sum(call_4h) as call_4h
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
as t group by ingress_code_name";
            }
        } else {
            array_push($where, "report_time between '$start_date' and '$end_date'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT
        ingress_code_name,
        sum(egress_call_cost) as egress_call_cost,
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
        sum(not_zero_calls_6) as call_6s,
        sum(call_12s) as call_12s,
        sum(call_18s) as call_18s,
        sum(call_24s) as call_24s,
        sum(not_zero_calls_30) as call_30s,
        sum(call_2h) as call_2h,
        sum(call_3h) as call_3h,
        sum(call_4h) as call_4h
        from " . CDR_TABLE . "
        where $where_str and ingress_id = {$ingress_id} and ingress_country = '{$country}' GROUP BY ingress_code_name";
        }

        $stmt = $dbh->query($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $i = 0;
        while ($row = $stmt->fetch()) {
            $data[] = array(
                'id' => uniqid(),
                'originator' => $row['ingress_code_name'],
                'atts' => number_format($row['total_calls']),
                'cc' => number_format($row['not_zero_calls']),
                'mins' => round($row['bill_time'] / 60, 2),
                'asr' => round($row['total_calls'] == 0 ? 0 : $row['not_zero_calls'] / $row['total_calls'] * 100, 2),
                'acd' => round($row['not_zero_calls'] == 0 ? 0 : $row['duration'] / $row['not_zero_calls'] / 60, 2),
                'pdd' => round($row['not_zero_calls'] == 0 ? 0 : $row['pdd'] / $row['not_zero_calls']),
//                'cps'        => '-',
                'rev' => number_format($row['call_cost'] + $row['lnp_cost'], 5),
                'cost' => number_format($row['egress_call_cost'], 5),
                'margin' => number_format($row['call_cost'] + $row['lnp_cost'] - $row['egress_call_cost'], 5),
                'ingress_id' => $ingress_id,
                'country' => $country,
                'type' => 'code_name',
                'state' => 'closed',
                'call_6s' => $row['call_6s'],
                'call_12s' => $row['call_12s'],
                'call_18s' => $row['call_18s'],
                'call_24s' => $row['call_24s'],
                'call_30s' => $row['call_30s'],
                'call_2h' => $row['call_2h'],
                'call_3h' => $row['call_3h'],
                'call_4h' => $row['call_4h'],
                'max_channel_usage' => '-',
                'max_channel_allowed' => '-',
                'percentage_of_trunk_usage' => '-',
            );
            $i++;
        }

        $stmt->closeCursor();
        $dbh = NULL;

        echo json_encode($data);
    }


    public function get_ingress_data3()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $ingress_id = $_GET['ingress_id'];
        $country = $_GET['country'];
        $code_name = $_GET['code_name'];

        $timezone = $_GET['timezone'];
        $start_time = $_GET['start_time'];
        $end_time = $_GET['end_time'];
        $country_search = isset($_GET['country_search']) ? $_GET['country_search'] : '';
        $destination = isset($_GET['destination']) ? $_GET['destination'] : '';
        $ingress_trunk = isset($_GET['ingress_trunk']) ? $_GET['ingress_trunk'] : '';
        $egress_trunk = isset($_GET['egress_trunk']) ? $_GET['egress_trunk'] : '';

        $start_date = $start_time . $timezone;
        $end_date = $end_time . $timezone;

        $report_max_time = $this->Cdr->get_report_maxtime($start_date, $end_date);


        $select_time_end = strtotime($end_date);

        $is_from_client_cdr = false;

        if (empty($report_max_time)) {
            $is_from_client_cdr = true;
            $report_max_time = $start_date;
        }
        $system_max_end = strtotime($report_max_time);

        $where = array();
        $where2 = array();

        if (!empty($country_search)) {
            array_push($where, "ingress_country = '{$country_search}'");
            array_push($where2, "orig_country = '{$country_search}'");
        }

        if (!empty($destination)) {
            array_push($where, "ingress_code_name = '{$destination}'");
            array_push($where2, "orig_code_name = '{$destination}'");
        }

        if (!empty($ingress_trunk)) {
            array_push($where, "ingress_id = {$ingress_trunk}");
            array_push($where2, "ingress_id = {$ingress_trunk}");
        }

        if (!empty($egress_trunk)) {
            array_push($where, "egress_id = {$egress_trunk}");
            array_push($where2, "egress_id = {$egress_trunk}");
        }

        $data = array();

        $database_conf = new DATABASE_CONFIG;
        $dbh = new PDO("pgsql:host={$database_conf->default['host']};port={$database_conf->default['port']};dbname={$database_conf->default['database']};user={$database_conf->default['login']};password={$database_conf->default['password']}");

        $today_date = date("Ymd");
        if ($select_time_end > $system_max_end) {
            if ($is_from_client_cdr) {
                array_push($where2, "time between '$report_max_time' and '$end_date'");
                array_push($where2, "is_final_call=1");
                $where2_str = implode(" and ", $where2);
                $sql = "select
(SELECT alias FROM resource WHERE resource_id = egress_id) as egress_name,
sum(egress_cost) as egress_call_cost,
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
                (SELECT max(call) FROM qos_resource WHERE res_id = client_cdr.egress_id AND report_time between '$start_time' and '$end_time') as max_channel_usage,
        (SELECT capacity FROM resource where resource_id = client_cdr.egress_id) as max_channel_allowed,
count(case when call_duration <= 6 then 1 else null end) as call_6s,
count(case when call_duration <= 12 then 1 else null end) as call_12s,
count(case when call_duration <= 18 then 1 else null end) as call_18s,
count(case when call_duration <= 24 then 1 else null end) as call_24s,
count(case when call_duration <= 30 then 1 else null end) as call_30s,
count(case when call_duration >= (7200) then 1 else null end) as call_2h,
count(case when call_duration >= (10800) then 1 else null end) as call_3h,
count(case when call_duration >= (14400) then 1 else null end) as call_4h
from client_cdr{$today_date} where $where2_str and ingress_id = {$ingress_id} and orig_country = '{$country}'  and orig_code_name = '{$code_name}' GROUP BY egress_id";
            } else {
                array_push($where, "report_time between '$start_date' and '$report_max_time'");
                array_push($where2, "time between '$report_max_time' and '$end_date'");
                array_push($where2, "is_final_call=1");
                $where_str = implode(" and ", $where);
                $where2_str = implode(" and ", $where2);
                $sql1 = "SELECT
        egress_id,
        sum(egress_call_cost) as egress_call_cost,
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
        sum(not_zero_calls_6) as call_6s,
        sum(call_12s) as call_12s,
        sum(call_18s) as call_18s,
        sum(call_24s) as call_24s,
        sum(not_zero_calls_30) as call_30s,
        sum(call_2h) as call_2h,
        sum(call_3h) as call_3h,
        sum(call_4h) as call_4h
        from " . CDR_TABLE . "
        where $where_str and ingress_id = {$ingress_id} and ingress_country = '{$country}' and ingress_code_name = '{$code_name}' GROUP BY egress_id";
                $sql2 = "select
egress_id,
sum(egress_cost) as egress_call_cost,
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
count(case when call_duration <= 6 then 1 else null end) as call_6s,
count(case when call_duration <= 12 then 1 else null end) as call_12s,
count(case when call_duration <= 18 then 1 else null end) as call_18s,
count(case when call_duration <= 24 then 1 else null end) as call_24s,
count(case when call_duration <= 30 then 1 else null end) as call_30s,
count(case when call_duration >= (7200) then 1 else null end) as call_2h,
count(case when call_duration >= (10800) then 1 else null end) as call_3h,
count(case when call_duration >= (14400) then 1 else null end) as call_4h
from client_cdr{$today_date} where $where2_str and ingress_id = {$ingress_id} and orig_country = '{$country}' and orig_code_name = '{$code_name}' GROUP BY egress_id";
                $sql = "SELECT 
(SELECT alias FROM resource WHERE resource_id = egress_id) as egress_name, sum(egress_call_cost) as egress_call_cost,
sum(duration) as duration , sum(bill_time) as bill_time,  sum(call_cost) as call_cost, sum(lnp_cost) as lnp_cost, sum(not_zero_calls) as not_zero_calls,
sum(total_calls) as total_calls, sum(success_calls) as success_calls, sum(busy_calls) as busy_calls, 
sum(lrn_calls) as lrn_calls, sum(pdd) as pdd, sum(cancel_calls) as cancel_calls,
(SELECT max(call) FROM qos_resource WHERE res_id = t.egress_id AND report_time between '$start_time' and '$end_time') as max_channel_usage,
        (SELECT capacity FROM resource where resource_id = t.egress_id) as max_channel_allowed,
sum(call_6s) as call_6s, sum(call_12s) as call_12s, sum(call_18s) as call_18s, sum(call_24s) as call_24s, sum(call_30s) as call_30s, sum(call_2h) as call_2h, 
sum(call_3h) as call_3h, sum(call_4h) as call_4h
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
as t group by egress_id";
            }
        } else {
            array_push($where, "report_time between '$start_date' and '$end_date'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT
        (SELECT alias FROM resource WHERE resource_id = egress_id) as egress_name,
        sum(egress_call_cost) as egress_call_cost,
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
             (SELECT max(call) FROM qos_resource WHERE res_id = " . CDR_TABLE . ".egress_id AND report_time between '$start_time' and '$end_time') as max_channel_usage,
        (SELECT capacity FROM resource where resource_id = " . CDR_TABLE . ".egress_id) as max_channel_allowed,
        sum(not_zero_calls_6) as call_6s,
        sum(call_12s) as call_12s,
        sum(call_18s) as call_18s,
        sum(call_24s) as call_24s,
        sum(not_zero_calls_30) as call_30s,
        sum(call_2h) as call_2h,
        sum(call_3h) as call_3h,
        sum(call_4h) as call_4h
        from " . CDR_TABLE . "
        where $where_str and ingress_id = {$ingress_id} and ingress_country = '{$country}' and ingress_code_name = '{$code_name}' GROUP BY egress_id";
        }

        $stmt = $dbh->query($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $stmt->fetch()) {
            $data[] = array(
                'id' => uniqid(),
                'originator' => $row['egress_name'],
                'atts' => number_format($row['total_calls']),
                'cc' => number_format($row['not_zero_calls']),
                'mins' => round($row['bill_time'] / 60, 2),
                'asr' => round($row['total_calls'] == 0 ? 0 : $row['not_zero_calls'] / $row['total_calls'] * 100, 2),
                'acd' => round($row['not_zero_calls'] == 0 ? 0 : $row['duration'] / $row['not_zero_calls'] / 60, 2),
                'pdd' => round($row['not_zero_calls'] == 0 ? 0 : $row['pdd'] / $row['not_zero_calls']),
//                'cps'        => '-',
                'rev' => number_format($row['call_cost'] + $row['lnp_cost'], 5),
                'cost' => number_format($row['egress_call_cost'], 5),
                'margin' => number_format($row['call_cost'] + $row['lnp_cost'] - $row['egress_call_cost'], 5),
                'call_6s' => $row['call_6s'],
                'call_12s' => $row['call_12s'],
                'call_18s' => $row['call_18s'],
                'call_24s' => $row['call_24s'],
                'call_30s' => $row['call_30s'],
                'call_2h' => $row['call_2h'],
                'call_3h' => $row['call_3h'],
                'call_4h' => $row['call_4h'],
                'max_channel_usage' => $row['max_channel_usage'] == NULL ? '-' : $row['max_channel_usage'],
                'max_channel_allowed' => $row['max_channel_allowed'] == NULL ? 'Unlimited' : $row['max_channel_allowed'],
                'percentage_of_trunk_usage' => $row['max_channel_allowed'] == NULL || $row['max_channel_allowed'] == NULL ? '-' : round($row['max_channel_usage'] / $row['max_channel_allowed'] * 100, 2) . '%',
            );
        }

        $stmt->closeCursor();
        $dbh = NULL;

        echo json_encode($data);
    }


    public function get_egress_data()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;

        $timezone = $_GET['timezone'];
        $start_time = $_GET['start_time'];
        $end_time = $_GET['end_time'];
        $country_search = isset($_GET['country_search']) ? $_GET['country_search'] : '';
        $destination = isset($_GET['destination']) ? $_GET['destination'] : '';
        $ingress_trunk = isset($_GET['ingress_trunk']) ? $_GET['ingress_trunk'] : '';
        $egress_trunk = isset($_GET['egress_trunk']) ? $_GET['egress_trunk'] : '';

        $start_date = $start_time . $timezone;
        $end_date = $end_time . $timezone;

        $report_max_time = $this->Cdr->get_report_maxtime($start_date, $end_date);


        $select_time_end = strtotime($end_date);

        $is_from_client_cdr = false;

        if (empty($report_max_time)) {
            $is_from_client_cdr = true;
            $report_max_time = $start_date;
        }
        $system_max_end = strtotime($report_max_time);

        $where = array();
        $where2 = array();

        if (!empty($country_search)) {
            array_push($where, "egress_country = '{$country_search}'");
            array_push($where2, "term_country = '{$country_search}'");
        }

        if (!empty($destination)) {
            array_push($where, "egress_code_name = '{$destination}'");
            array_push($where2, "term_code_name = '{$destination}'");
        }

        if (!empty($ingress_trunk)) {
            array_push($where, "ingress_id = {$ingress_trunk}");
            array_push($where2, "ingress_id = '{$ingress_trunk}'");
        }

        if (!empty($egress_trunk)) {
            array_push($where, "egress_id = {$egress_trunk}");
            array_push($where2, "egress_id = '{$egress_trunk}'");
        }


        $data = array(
            array(
                'id' => 0,
                'originator' => 'All',
            )
        );
        $database_conf = new DATABASE_CONFIG;
        $dbh = new PDO("pgsql:host={$database_conf->default['host']};port={$database_conf->default['port']};dbname={$database_conf->default['database']};user={$database_conf->default['login']};password={$database_conf->default['password']}");
        $today_date = date("Ymd");
        if ($select_time_end > $system_max_end) {
            if ($is_from_client_cdr) {
                array_push($where2, "time between '$report_max_time' and '$end_date'");
                $where2_str = implode(" and ", $where2);
                $sql = "select
sum(egress_cost) as egress_call_cost,
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
count(case when call_duration <= 6 then 1 else null end) as call_6s,
count(case when call_duration <= 12 then 1 else null end) as call_12s,
count(case when call_duration <= 18 then 1 else null end) as call_18s,
count(case when call_duration <= 24 then 1 else null end) as call_24s,
count(case when call_duration <= 30 then 1 else null end) as call_30s,
count(case when call_duration >= (7200) then 1 else null end) as call_2h,
count(case when call_duration >= (10800) then 1 else null end) as call_3h,
count(case when call_duration >= (14400) then 1 else null end) as call_4h
from client_cdr{$today_date} where $where2_str";
            } else {
                array_push($where, "report_time between '$start_date' and '$report_max_time'");
                array_push($where2, "time between '$report_max_time' and '$end_date'");
                $where_str = implode(" and ", $where);
                $where2_str = implode(" and ", $where2);
                $sql1 = "SELECT
        sum(egress_call_cost) as egress_call_cost,
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
        sum(not_zero_calls_6) as call_6s,
        sum(call_12s) as call_12s,
        sum(call_18s) as call_18s,
        sum(call_24s) as call_24s,
        sum(not_zero_calls_30) as call_30s,
        sum(call_2h) as call_2h,
        sum(call_3h) as call_3h,
        sum(call_4h) as call_4h
        from " . CDR_TABLE . "
        where $where_str";
                $sql2 = "select
sum(egress_cost) as egress_call_cost,
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
count(case when call_duration <= 6 then 1 else null end) as call_6s,
count(case when call_duration <= 12 then 1 else null end) as call_12s,
count(case when call_duration <= 18 then 1 else null end) as call_18s,
count(case when call_duration <= 24 then 1 else null end) as call_24s,
count(case when call_duration <= 30 then 1 else null end) as call_30s,
count(case when call_duration >= (7200) then 1 else null end) as call_2h,
count(case when call_duration >= (10800) then 1 else null end) as call_3h,
count(case when call_duration >= (14400) then 1 else null end) as call_4h
from client_cdr{$today_date} where $where2_str";
                $sql = "SELECT sum(egress_call_cost) as egress_call_cost,
sum(duration) as duration , sum(bill_time) as bill_time,  sum(call_cost) as call_cost, sum(lnp_cost) as lnp_cost, sum(not_zero_calls) as not_zero_calls,
sum(total_calls) as total_calls, sum(success_calls) as success_calls, sum(busy_calls) as busy_calls, 
sum(lrn_calls) as lrn_calls, sum(pdd) as pdd, sum(cancel_calls) as cancel_calls,
sum(call_6s) as call_6s, sum(call_12s) as call_12s, sum(call_18s) as call_18s, sum(call_24s) as call_24s, sum(call_30s) as call_30s, sum(call_2h) as call_2h, 
sum(call_3h) as call_3h, sum(call_4h) as call_4h FROM  
(    
(
$sql1
)

union
(
$sql2
)
)  
as t";
            }
        } else {
            array_push($where, "report_time between '$start_date' and '$end_date'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT
        sum(egress_call_cost) as egress_call_cost,
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
        sum(not_zero_calls_6) as call_6s,
        sum(call_12s) as call_12s,
        sum(call_18s) as call_18s,
        sum(call_24s) as call_24s,
        sum(not_zero_calls_30) as call_30s,
        sum(call_2h) as call_2h,
        sum(call_3h) as call_3h,
        sum(call_4h) as call_4h
        from " . CDR_TABLE . "
        where $where_str";
        }

        $stmt = $dbh->query($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $row = $stmt->fetch();
        $data[0]['children'][0] = array(
            'id' => 1,
            'originator' => 'Egress Trunk',
            'atts' => number_format($row['total_calls']),
            'cc' => number_format($row['not_zero_calls']),
            'mins' => round($row['bill_time'] / 60, 2),
            'asr' => round($row['total_calls'] == 0 ? 0 : $row['not_zero_calls'] / $row['total_calls'] * 100, 2),
            'acd' => round($row['not_zero_calls'] == 0 ? 0 : $row['duration'] / $row['not_zero_calls'] / 60, 2),
            'pdd' => round($row['not_zero_calls'] == 0 ? 0 : $row['pdd'] / $row['not_zero_calls']),
//            'cps'        => '-',
            'rev' => number_format($row['call_cost'], 5),
            'cost' => number_format($row['egress_call_cost'], 5),
            'margin' => number_format($row['call_cost'] + $row['lnp_cost'] - $row['egress_call_cost'], 5),
            'call_6s' => $row['call_6s'],
            'call_12s' => $row['call_12s'],
            'call_18s' => $row['call_18s'],
            'call_24s' => $row['call_24s'],
            'call_30s' => $row['call_30s'],
            'call_2h' => $row['call_2h'],
            'call_3h' => $row['call_3h'],
            'call_4h' => $row['call_4h'],
            'max_channel_usage' => '-',
            'max_channel_allowed' => '-',
            'percentage_of_trunk_usage' => '-',
        );

        if ($select_time_end > $system_max_end) {
            if ($is_from_client_cdr) {
                //array_push($where2, "time between '$report_max_time' and '$end_date'");
                //$where2_str = implode(" and ", $where2);
                $sql = "select
(SELECT alias FROM resource WHERE resource_id = egress_id) AS egress_name,
egress_id,
sum(egress_cost) as egress_call_cost,
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
(SELECT max(call) FROM qos_resource WHERE res_id = client_cdr.egress_id AND report_time between '$start_time' and '$end_time') as max_channel_usage,
(SELECT capacity FROM resource where resource_id = client_cdr.egress_id) as max_channel_allowed,
count(case when call_duration <= 6 then 1 else null end) as call_6s,
count(case when call_duration <= 12 then 1 else null end) as call_12s,
count(case when call_duration <= 18 then 1 else null end) as call_18s,
count(case when call_duration <= 24 then 1 else null end) as call_24s,
count(case when call_duration <= 30 then 1 else null end) as call_30s,
count(case when call_duration >= (7200) then 1 else null end) as call_2h,
count(case when call_duration >= (10800) then 1 else null end) as call_3h,
count(case when call_duration >= (14400) then 1 else null end) as call_4h
from client_cdr{$today_date} where $where2_str group by egress_id";
            } else {
                //array_push($where, "report_time between '$start_date' and '$report_max_time'");
                //array_push($where2, "time between '$report_max_time' and '$end_date'");
                //$where_str = implode(" and ", $where);
                //$where2_str = implode(" and ", $where2);
                $sql1 = "SELECT
        egress_id,
        sum(egress_call_cost) as egress_call_cost,
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
        sum(not_zero_calls_6) as call_6s,
        sum(call_12s) as call_12s,
        sum(call_18s) as call_18s,
        sum(call_24s) as call_24s,
        sum(not_zero_calls_30) as call_30s,
        sum(call_2h) as call_2h,
        sum(call_3h) as call_3h,
        sum(call_4h) as call_4h
        from " . CDR_TABLE . "
        where $where_str group by egress_id";
                $sql2 = "select
egress_id,
sum(egress_cost) as egress_call_cost,
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
count(case when call_duration <= 6 then 1 else null end) as call_6s,
count(case when call_duration <= 12 then 1 else null end) as call_12s,
count(case when call_duration <= 18 then 1 else null end) as call_18s,
count(case when call_duration <= 24 then 1 else null end) as call_24s,
count(case when call_duration <= 30 then 1 else null end) as call_30s,
count(case when call_duration >= (7200) then 1 else null end) as call_2h,
count(case when call_duration >= (10800) then 1 else null end) as call_3h,
count(case when call_duration >= (14400) then 1 else null end) as call_4h
from client_cdr{$today_date} where $where2_str group by egress_id";
                $sql = "SELECT 
(SELECT alias FROM resource WHERE resource_id = egress_id) AS egress_name,
egress_id, sum(egress_call_cost) as egress_call_cost,
sum(duration) as duration , sum(bill_time) as bill_time,  sum(call_cost) as call_cost, sum(lnp_cost) as lnp_cost, sum(not_zero_calls) as not_zero_calls,
sum(total_calls) as total_calls, sum(success_calls) as success_calls, sum(busy_calls) as busy_calls, 
sum(lrn_calls) as lrn_calls, sum(pdd) as pdd, sum(cancel_calls) as cancel_calls,
(SELECT max(call) FROM qos_resource WHERE res_id = t.egress_id AND report_time between '$start_time' and '$end_time') as max_channel_usage,
(SELECT capacity FROM resource where resource_id = t.egress_id) as max_channel_allowed,
sum(call_6s) as call_6s, sum(call_12s) as call_12s, sum(call_18s) as call_18s, sum(call_24s) as call_24s, sum(call_30s) as call_30s, sum(call_2h) as call_2h, 
sum(call_3h) as call_3h, sum(call_4h) as call_4h
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
as t group by egress_id";
            }
        } else {
            //array_push($where, "report_time between '$start_date' and '$end_date'");
            //$where_str = implode(" and ", $where);
            $sql = "SELECT
        (SELECT alias FROM resource WHERE resource_id = egress_id) AS egress_name,
        egress_id,
        sum(egress_call_cost) as egress_call_cost,
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
(SELECT max(call) FROM qos_resource WHERE res_id = " . CDR_TABLE . ".egress_id AND report_time between '$start_time' and '$end_time') as max_channel_usage,
(SELECT capacity FROM resource where resource_id = " . CDR_TABLE . ".egress_id) as max_channel_allowed,
        sum(not_zero_calls_6) as call_6s,
        sum(call_12s) as call_12s,
        sum(call_18s) as call_18s,
        sum(call_24s) as call_24s,
        sum(not_zero_calls_30) as call_30s,
        sum(call_2h) as call_2h,
        sum(call_3h) as call_3h,
        sum(call_4h) as call_4h
        from " . CDR_TABLE . "
        where $where_str GROUP BY egress_id";
        }
        $stmt = $dbh->query($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $stmt->fetch()) {
            $data[0]['children'][0]['children'][] = array(
                'id' => uniqid(),
                'originator' => $row['egress_name'],
                'atts' => number_format($row['total_calls']),
                'cc' => number_format($row['not_zero_calls']),
                'mins' => round($row['bill_time'] / 60, 2),
                'asr' => round($row['total_calls'] == 0 ? 0 : $row['not_zero_calls'] / $row['total_calls'] * 100, 2),
                'acd' => round($row['not_zero_calls'] == 0 ? 0 : $row['duration'] / $row['not_zero_calls'] / 60, 2),
                'pdd' => round($row['not_zero_calls'] == 0 ? 0 : $row['pdd'] / $row['not_zero_calls']),
//                'cps'        => '-',
                'rev' => number_format($row['call_cost'], 5),
                'cost' => number_format($row['egress_call_cost'], 5),
                'margin' => number_format($row['call_cost'] + $row['lnp_cost'] - $row['egress_call_cost'], 5),
                'egress_id' => $row['egress_id'],
                'state' => 'closed',
                'call_6s' => $row['call_6s'],
                'call_12s' => $row['call_12s'],
                'call_18s' => $row['call_18s'],
                'call_24s' => $row['call_24s'],
                'call_30s' => $row['call_30s'],
                'call_2h' => $row['call_2h'],
                'call_3h' => $row['call_3h'],
                'call_4h' => $row['call_4h'],
                'max_channel_usage' => $row['max_channel_usage'] == NULL ? '-' : $row['max_channel_usage'],
                'max_channel_allowed' => $row['max_channel_allowed'] == NULL ? 'Unlimited' : $row['max_channel_allowed'],
                'percentage_of_trunk_usage' => $row['max_channel_allowed'] == NULL || $row['max_channel_allowed'] == NULL ? '-' : round($row['max_channel_usage'] / $row['max_channel_allowed'] * 100, 2) . '%',
            );
        }


        $stmt->closeCursor();
        $dbh = NULL;

        echo json_encode($data);
    }


    public function get_egress_data1()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;

        $egress_id = $_GET['egress_id'];

        $timezone = $_GET['timezone'];
        $start_time = $_GET['start_time'];
        $end_time = $_GET['end_time'];
        $country_search = isset($_GET['country_search']) ? $_GET['country_search'] : '';
        $destination = isset($_GET['destination']) ? $_GET['destination'] : '';
        $ingress_trunk = isset($_GET['ingress_trunk']) ? $_GET['ingress_trunk'] : '';
        $egress_trunk = isset($_GET['egress_trunk']) ? $_GET['egress_trunk'] : '';

        $start_date = $start_time . $timezone;
        $end_date = $end_time . $timezone;

        $report_max_time = $this->Cdr->get_report_maxtime($start_date, $end_date);


        $select_time_end = strtotime($end_date);

        $is_from_client_cdr = false;

        if (empty($report_max_time)) {
            $is_from_client_cdr = true;
            $report_max_time = $start_date;
        }
        $system_max_end = strtotime($report_max_time);

        $where = array();
        $where2 = array();

        if (!empty($country_search)) {
            array_push($where, "egress_country = '{$country_search}'");
            array_push($where2, "term_country = '{$country_search}'");
        }

        if (!empty($destination)) {
            array_push($where, "egress_code_name = '{$destination}'");
            array_push($where2, "term_code_name = '{$destination}'");
        }

        if (!empty($ingress_trunk)) {
            array_push($where, "ingress_id = {$ingress_trunk}");
            array_push($where2, "ingress_id = '{$ingress_trunk}'");
        }

        if (!empty($egress_trunk)) {
            array_push($where, "egress_id = {$egress_trunk}");
            array_push($where2, "egress_id = '{$egress_trunk}'");
        }

        $data = array();

        $database_conf = new DATABASE_CONFIG;
        $dbh = new PDO("pgsql:host={$database_conf->default['host']};port={$database_conf->default['port']};dbname={$database_conf->default['database']};user={$database_conf->default['login']};password={$database_conf->default['password']}");
        $today_date = date("Ymd");
        if ($select_time_end > $system_max_end) {
            if ($is_from_client_cdr) {
                array_push($where2, "time between '$report_max_time' and '$end_date'");
                $where2_str = implode(" and ", $where2);
                $sql = "select
term_country as egress_country,
sum(egress_cost) as egress_call_cost,
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
count(case when call_duration <= 6 then 1 else null end) as call_6s,
count(case when call_duration <= 12 then 1 else null end) as call_12s,
count(case when call_duration <= 18 then 1 else null end) as call_18s,
count(case when call_duration <= 24 then 1 else null end) as call_24s,
count(case when call_duration <= 30 then 1 else null end) as call_30s,
count(case when call_duration >= (7200) then 1 else null end) as call_2h,
count(case when call_duration >= (10800) then 1 else null end) as call_3h,
count(case when call_duration >= (14400) then 1 else null end) as call_4h
from client_cdr{$today_date} where $where2_str and egress_id = {$egress_id} GROUP BY term_country";
            } else {
                array_push($where, "report_time between '$start_date' and '$report_max_time'");
                array_push($where2, "time between '$report_max_time' and '$end_date'");
                $where_str = implode(" and ", $where);
                $where2_str = implode(" and ", $where2);
                $sql1 = "SELECT
        egress_country,
        sum(egress_call_cost) as egress_call_cost,
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
        sum(not_zero_calls_6) as call_6s,
        sum(call_12s) as call_12s,
        sum(call_18s) as call_18s,
        sum(call_24s) as call_24s,
        sum(not_zero_calls_30) as call_30s,
        sum(call_2h) as call_2h,
        sum(call_3h) as call_3h,
        sum(call_4h) as call_4h
        from " . CDR_TABLE . "
        where $where_str and egress_id = {$egress_id} group by egress_country";
                $sql2 = "select
term_country as egress_country,
sum(egress_cost) as egress_call_cost,
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
count(case when call_duration <= 6 then 1 else null end) as call_6s,
count(case when call_duration <= 12 then 1 else null end) as call_12s,
count(case when call_duration <= 18 then 1 else null end) as call_18s,
count(case when call_duration <= 24 then 1 else null end) as call_24s,
count(case when call_duration <= 30 then 1 else null end) as call_30s,
count(case when call_duration >= (7200) then 1 else null end) as call_2h,
count(case when call_duration >= (10800) then 1 else null end) as call_3h,
count(case when call_duration >= (14400) then 1 else null end) as call_4h
from client_cdr{$today_date} where $where2_str and egress_id = {$egress_id} GROUP BY term_country";
                $sql = "SELECT 
egress_country, sum(egress_call_cost) as egress_call_cost,
sum(duration) as duration , sum(bill_time) as bill_time,  sum(call_cost) as call_cost, sum(lnp_cost) as lnp_cost, sum(not_zero_calls) as not_zero_calls,
sum(total_calls) as total_calls, sum(success_calls) as success_calls, sum(busy_calls) as busy_calls, 
sum(lrn_calls) as lrn_calls, sum(pdd) as pdd, sum(cancel_calls) as cancel_calls,
sum(call_6s) as call_6s, sum(call_12s) as call_12s, sum(call_18s) as call_18s, sum(call_24s) as call_24s, sum(call_30s) as call_30s, sum(call_2h) as call_2h, 
sum(call_3h) as call_3h, sum(call_4h) as call_4h
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
as t group by egress_country";
            }
        } else {
            array_push($where, "report_time between '$start_date' and '$end_date'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT
        egress_country,
        sum(egress_call_cost) as egress_call_cost,
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
        sum(not_zero_calls_6) as call_6s,
        sum(call_12s) as call_12s,
        sum(call_18s) as call_18s,
        sum(call_24s) as call_24s,
        sum(not_zero_calls_30) as call_30s,
        sum(call_2h) as call_2h,
        sum(call_3h) as call_3h,
        sum(call_4h) as call_4h
        from " . CDR_TABLE . "
        where $where_str and egress_id = {$egress_id} GROUP BY egress_country";
        }
        $stmt = $dbh->query($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);


        while ($row = $stmt->fetch()) {
            $data[] = array(
                'id' => uniqid(),
                'originator' => $row['egress_country'],
                'atts' => number_format($row['total_calls']),
                'cc' => number_format($row['not_zero_calls']),
                'mins' => round($row['bill_time'] / 60, 2),
                'asr' => round($row['total_calls'] == 0 ? 0 : $row['not_zero_calls'] / $row['total_calls'] * 100, 2),
                'acd' => round($row['not_zero_calls'] == 0 ? 0 : $row['duration'] / $row['not_zero_calls'] / 60, 2),
                'pdd' => round($row['not_zero_calls'] == 0 ? 0 : $row['pdd'] / $row['not_zero_calls']),
//                'cps'        => '-',
                'rev' => number_format($row['call_cost'], 5),
                'cost' => number_format($row['egress_call_cost'], 5),
                'margin' => number_format($row['call_cost'] + $row['lnp_cost'] - $row['egress_call_cost'], 5),
                'egress_id' => $egress_id,
                'type' => 'country',
                'state' => 'closed',
                'call_6s' => $row['call_6s'],
                'call_12s' => $row['call_12s'],
                'call_18s' => $row['call_18s'],
                'call_24s' => $row['call_24s'],
                'call_30s' => $row['call_30s'],
                'call_2h' => $row['call_2h'],
                'call_3h' => $row['call_3h'],
                'call_4h' => $row['call_4h'],
                'max_channel_usage' => '-',
                'max_channel_allowed' => '-',
                'percentage_of_trunk_usage' => '-',
            );
        }

        $stmt->closeCursor();
        $dbh = NULL;

        echo json_encode($data);
    }


    public function get_egress_data2()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $egress_id = $_GET['egress_id'];
        $country = $_GET['country'];

        $timezone = $_GET['timezone'];
        $start_time = $_GET['start_time'];
        $end_time = $_GET['end_time'];
        $country_search = isset($_GET['country_search']) ? $_GET['country_search'] : '';
        $destination = isset($_GET['destination']) ? $_GET['destination'] : '';
        $ingress_trunk = isset($_GET['ingress_trunk']) ? $_GET['ingress_trunk'] : '';
        $egress_trunk = isset($_GET['egress_trunk']) ? $_GET['egress_trunk'] : '';

        $start_date = $start_time . $timezone;
        $end_date = $end_time . $timezone;

        $report_max_time = $this->Cdr->get_report_maxtime($start_date, $end_date);


        $select_time_end = strtotime($end_date);

        $is_from_client_cdr = false;

        if (empty($report_max_time)) {
            $is_from_client_cdr = true;
            $report_max_time = $start_date;
        }
        $system_max_end = strtotime($report_max_time);

        $where = array();
        $where2 = array();

        if (!empty($country_search)) {
            array_push($where, "egress_country = '{$country_search}'");
            array_push($where2, "term_country = '{$country_search}'");
        }

        if (!empty($destination)) {
            array_push($where, "egress_code_name = '{$destination}'");
            array_push($where2, "term_code_name = '{$destination}'");
        }

        if (!empty($ingress_trunk)) {
            array_push($where, "ingress_id = {$ingress_trunk}");
            array_push($where2, "ingress_id = '{$ingress_trunk}'");
        }

        if (!empty($egress_trunk)) {
            array_push($where, "egress_id = {$egress_trunk}");
            array_push($where2, "egress_id = '{$egress_trunk}'");
        }

        $data = array();

        $database_conf = new DATABASE_CONFIG;
        $dbh = new PDO("pgsql:host={$database_conf->default['host']};port={$database_conf->default['port']};dbname={$database_conf->default['database']};user={$database_conf->default['login']};password={$database_conf->default['password']}");
        $today_date = date("Ymd");
        if ($select_time_end > $system_max_end) {
            if ($is_from_client_cdr) {
                array_push($where2, "time between '$report_max_time' and '$end_date'");
                $where2_str = implode(" and ", $where2);
                $sql = "select
term_code_name as egress_code_name,
sum(egress_cost) as egress_call_cost,
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
count(case when call_duration <= 6 then 1 else null end) as call_6s,
count(case when call_duration <= 12 then 1 else null end) as call_12s,
count(case when call_duration <= 18 then 1 else null end) as call_18s,
count(case when call_duration <= 24 then 1 else null end) as call_24s,
count(case when call_duration <= 30 then 1 else null end) as call_30s,
count(case when call_duration >= (7200) then 1 else null end) as call_2h,
count(case when call_duration >= (10800) then 1 else null end) as call_3h,
count(case when call_duration >= (14400) then 1 else null end) as call_4h
from client_cdr{$today_date} where $where2_str and egress_id = {$egress_id} and term_country = '{$country}' group by term_code_name";
            } else {
                array_push($where, "report_time between '$start_date' and '$report_max_time'");
                array_push($where2, "time between '$report_max_time' and '$end_date'");
                $where_str = implode(" and ", $where);
                $where2_str = implode(" and ", $where2);
                $sql1 = "SELECT
        egress_code_name,
        sum(egress_call_cost) as egress_call_cost,
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
        sum(not_zero_calls_6) as call_6s,
        sum(call_12s) as call_12s,
        sum(call_18s) as call_18s,
        sum(call_24s) as call_24s,
        sum(not_zero_calls_30) as call_30s,
        sum(call_2h) as call_2h,
        sum(call_3h) as call_3h,
        sum(call_4h) as call_4h
        from " . CDR_TABLE . "
        where $where_str and egress_id = {$egress_id} and egress_country = '{$country}' GROUP BY egress_code_name";
                $sql2 = "select
term_code_name as egress_code_name,
sum(egress_cost) as egress_call_cost,
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
count(case when call_duration <= 6 then 1 else null end) as call_6s,
count(case when call_duration <= 12 then 1 else null end) as call_12s,
count(case when call_duration <= 18 then 1 else null end) as call_18s,
count(case when call_duration <= 24 then 1 else null end) as call_24s,
count(case when call_duration <= 30 then 1 else null end) as call_30s,
count(case when call_duration >= (7200) then 1 else null end) as call_2h,
count(case when call_duration >= (10800) then 1 else null end) as call_3h,
count(case when call_duration >= (14400) then 1 else null end) as call_4h
from client_cdr{$today_date} where $where2_str and egress_id = {$egress_id} and term_country = '{$country}' group by term_code_name";
                $sql = "SELECT 
egress_code_name, sum(egress_call_cost) as egress_call_cost,
sum(duration) as duration , sum(bill_time) as bill_time,  sum(call_cost) as call_cost, sum(lnp_cost) as lnp_cost, sum(not_zero_calls) as not_zero_calls,
sum(total_calls) as total_calls, sum(success_calls) as success_calls, sum(busy_calls) as busy_calls, 
sum(lrn_calls) as lrn_calls, sum(pdd) as pdd, sum(cancel_calls) as cancel_calls,
sum(call_6s) as call_6s, sum(call_12s) as call_12s, sum(call_18s) as call_18s, sum(call_24s) as call_24s, sum(call_30s) as call_30s, sum(call_2h) as call_2h, 
sum(call_3h) as call_3h, sum(call_4h) as call_4h
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
as t group by egress_code_name";
            }
        } else {
            array_push($where, "report_time between '$start_date' and '$end_date'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT
        egress_code_name,
        sum(egress_call_cost) as egress_call_cost,
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
        sum(not_zero_calls_6) as call_6s,
        sum(call_12s) as call_12s,
        sum(call_18s) as call_18s,
        sum(call_24s) as call_24s,
        sum(not_zero_calls_30) as call_30s,
        sum(call_2h) as call_2h,
        sum(call_3h) as call_3h,
        sum(call_4h) as call_4h
        from " . CDR_TABLE . "
        where $where_str and egress_id = {$egress_id} and egress_country = '{$country}' GROUP BY egress_code_name";
        }

        $stmt = $dbh->query($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $i = 0;
        while ($row = $stmt->fetch()) {
            $data[] = array(
                'id' => uniqid(),
                'originator' => $row['egress_code_name'],
                'atts' => number_format($row['total_calls']),
                'cc' => number_format($row['not_zero_calls']),
                'mins' => round($row['bill_time'] / 60, 2),
                'asr' => round($row['total_calls'] == 0 ? 0 : $row['not_zero_calls'] / $row['total_calls'] * 100, 2),
                'acd' => round($row['not_zero_calls'] == 0 ? 0 : $row['duration'] / $row['not_zero_calls'] / 60, 2),
                'pdd' => round($row['not_zero_calls'] == 0 ? 0 : $row['pdd'] / $row['not_zero_calls']),
//                'cps'        => '-',
                'rev' => number_format($row['call_cost'], 5),
                'cost' => number_format($row['egress_call_cost'], 5),
                'margin' => number_format($row['call_cost'] + $row['lnp_cost'] - $row['egress_call_cost'], 5),
                'egress_id' => $egress_id,
                'country' => $country,
                'type' => 'code_name',
                'state' => 'closed',
                'call_6s' => $row['call_6s'],
                'call_12s' => $row['call_12s'],
                'call_18s' => $row['call_18s'],
                'call_24s' => $row['call_24s'],
                'call_30s' => $row['call_30s'],
                'call_2h' => $row['call_2h'],
                'call_3h' => $row['call_3h'],
                'call_4h' => $row['call_4h'],
                'max_channel_usage' => '-',
                'max_channel_allowed' => '-',
                'percentage_of_trunk_usage' => '-',
            );
            $i++;
        }

        $stmt->closeCursor();
        $dbh = NULL;

        echo json_encode($data);
    }


    public function get_egress_data3()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $egress_id = $_GET['egress_id'];
        $country = $_GET['country'];
        $code_name = $_GET['code_name'];

        $timezone = $_GET['timezone'];
        $start_time = $_GET['start_time'];
        $end_time = $_GET['end_time'];
        $country_search = isset($_GET['country_search']) ? $_GET['country_search'] : '';
        $destination = isset($_GET['destination']) ? $_GET['destination'] : '';
        $ingress_trunk = isset($_GET['ingress_trunk']) ? $_GET['ingress_trunk'] : '';
        $egress_trunk = isset($_GET['egress_trunk']) ? $_GET['egress_trunk'] : '';

        $start_date = $start_time . $timezone;
        $end_date = $end_time . $timezone;

        $report_max_time = $this->Cdr->get_report_maxtime($start_date, $end_date);

        $select_time_end = strtotime($end_date);

        $is_from_client_cdr = false;

        if (empty($report_max_time)) {
            $is_from_client_cdr = true;
            $report_max_time = $start_date;
        }
        $system_max_end = strtotime($report_max_time);

        $where = array();
        $where2 = array();

        if (!empty($country_search)) {
            array_push($where, "egress_country = '{$country_search}'");
            array_push($where2, "term_country = '{$country_search}'");
        }

        if (!empty($destination)) {
            array_push($where, "egress_code_name = '{$destination}'");
            array_push($where2, "term_code_name = '{$destination}'");
        }

        if (!empty($ingress_trunk)) {
            array_push($where, "ingress_id = {$ingress_trunk}");
            array_push($where2, "ingress_id = '{$ingress_trunk}'");
        }

        if (!empty($egress_trunk)) {
            array_push($where, "egress_id = {$egress_trunk}");
            array_push($where2, "egress_id = '{$egress_trunk}'");
        }

        $data = array();

        $database_conf = new DATABASE_CONFIG;
        $dbh = new PDO("pgsql:host={$database_conf->default['host']};port={$database_conf->default['port']};dbname={$database_conf->default['database']};user={$database_conf->default['login']};password={$database_conf->default['password']}");

        $today_date = date("Ymd");
        if ($select_time_end > $system_max_end) {
            if ($is_from_client_cdr) {
                array_push($where2, "time between '$report_max_time' and '$end_date'");
                $where2_str = implode(" and ", $where2);
                $sql = "select
(SELECT alias FROM resource WHERE resource_id = ingress_id) as ingress_name,
sum(egress_cost) as egress_call_cost,
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
(SELECT max(call) FROM qos_resource WHERE res_id = client_cdr.ingress_id AND report_time between '$start_time' and '$end_time') as max_channel_usage,
(SELECT capacity FROM resource where resource_id = client_cdr.ingress_id) as max_channel_allowed,
count(case when call_duration <= 6 then 1 else null end) as call_6s,
count(case when call_duration <= 12 then 1 else null end) as call_12s,
count(case when call_duration <= 18 then 1 else null end) as call_18s,
count(case when call_duration <= 24 then 1 else null end) as call_24s,
count(case when call_duration <= 30 then 1 else null end) as call_30s,
count(case when call_duration >= (7200) then 1 else null end) as call_2h,
count(case when call_duration >= (10800) then 1 else null end) as call_3h,
count(case when call_duration >= (14400) then 1 else null end) as call_4h
from client_cdr{$today_date} where $where2_str and egress_id = {$egress_id} and term_country = '{$country}' and term_code_name = '{$code_name}' group by ingress_id";
            } else {
                array_push($where, "report_time between '$start_date' and '$report_max_time'");
                array_push($where2, "time between '$report_max_time' and '$end_date'");
                $where_str = implode(" and ", $where);
                $where2_str = implode(" and ", $where2);
                $sql1 = "SELECT
        ingress_id,
        sum(egress_call_cost) as egress_call_cost,
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
        sum(not_zero_calls_6) as call_6s,
        sum(call_12s) as call_12s,
        sum(call_18s) as call_18s,
        sum(call_24s) as call_24s,
        sum(not_zero_calls_30) as call_30s,
        sum(call_2h) as call_2h,
        sum(call_3h) as call_3h,
        sum(call_4h) as call_4h
        from " . CDR_TABLE . "
        where $where_str and egress_id = {$egress_id} and egress_country = '{$country}' and egress_code_name = '{$code_name}' group by ingress_id";
                $sql2 = "select
ingress_id,
sum(egress_cost) as egress_call_cost,
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
count(case when call_duration <= 6 then 1 else null end) as call_6s,
count(case when call_duration <= 12 then 1 else null end) as call_12s,
count(case when call_duration <= 18 then 1 else null end) as call_18s,
count(case when call_duration <= 24 then 1 else null end) as call_24s,
count(case when call_duration <= 30 then 1 else null end) as call_30s,
count(case when call_duration >= (7200) then 1 else null end) as call_2h,
count(case when call_duration >= (10800) then 1 else null end) as call_3h,
count(case when call_duration >= (14400) then 1 else null end) as call_4h
from client_cdr{$today_date} where $where2_str and egress_id = {$egress_id} and term_country = '{$country}' and term_code_name = '{$code_name}' group by ingress_id";
                $sql = "SELECT 
(SELECT alias FROM resource WHERE resource_id = ingress_id) as ingress_name, sum(egress_call_cost) as egress_call_cost,
sum(duration) as duration , sum(bill_time) as bill_time,  sum(call_cost) as call_cost, sum(lnp_cost) as lnp_cost, sum(not_zero_calls) as not_zero_calls,
sum(total_calls) as total_calls, sum(success_calls) as success_calls, sum(busy_calls) as busy_calls, 
sum(lrn_calls) as lrn_calls, sum(pdd) as pdd, sum(cancel_calls) as cancel_calls,
(SELECT max(call) FROM qos_resource WHERE res_id = t.ingress_id AND report_time between '$start_time' and '$end_time') as max_channel_usage,
        (SELECT capacity FROM resource where resource_id = t.ingress_id) as max_channel_allowed,
sum(call_6s) as call_6s, sum(call_12s) as call_12s, sum(call_18s) as call_18s, sum(call_24s) as call_24s, sum(call_30s) as call_30s, sum(call_2h) as call_2h, 
sum(call_3h) as call_3h, sum(call_4h) as call_4h
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
as t group by ingress_id";
            }
        } else {
            array_push($where, "report_time between '$start_date' and '$end_date'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT
        (SELECT alias FROM resource WHERE resource_id = ingress_id) as ingress_name,
        sum(egress_call_cost) as egress_call_cost,
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
             (SELECT max(call) FROM qos_resource WHERE res_id = " . CDR_TABLE . ".ingress_id AND report_time between '$start_time' and '$end_time') as max_channel_usage,
        (SELECT capacity FROM resource where resource_id = " . CDR_TABLE . ".ingress_id) as max_channel_allowed,
        sum(not_zero_calls_6) as call_6s,
        sum(call_12s) as call_12s,
        sum(call_18s) as call_18s,
        sum(call_24s) as call_24s,
        sum(not_zero_calls_30) as call_30s,
        sum(call_2h) as call_2h,
        sum(call_3h) as call_3h,
        sum(call_4h) as call_4h
        from " . CDR_TABLE . "
        where $where_str and egress_id = {$egress_id} and egress_country = '{$country}' and egress_code_name = '{$code_name}' group by ingress_id";
        }

        $stmt = $dbh->query($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $stmt->fetch()) {
            $data[] = array(
                'id' => uniqid(),
                'originator' => $row['ingress_name'],
                'atts' => number_format($row['total_calls']),
                'cc' => number_format($row['not_zero_calls']),
                'mins' => round($row['bill_time'] / 60, 2),
                'asr' => round($row['total_calls'] == 0 ? 0 : $row['not_zero_calls'] / $row['total_calls'] * 100, 2),
                'acd' => round($row['not_zero_calls'] == 0 ? 0 : $row['duration'] / $row['not_zero_calls'] / 60, 2),
                'pdd' => round($row['not_zero_calls'] == 0 ? 0 : $row['pdd'] / $row['not_zero_calls']),
//                'cps'        => '-',
                'rev' => number_format($row['call_cost'], 5),
                'cost' => number_format($row['egress_call_cost'], 5),
                'margin' => number_format($row['call_cost'] + $row['lnp_cost'] - $row['egress_call_cost'], 5),
                'call_6s' => $row['call_6s'],
                'call_12s' => $row['call_12s'],
                'call_18s' => $row['call_18s'],
                'call_24s' => $row['call_24s'],
                'call_30s' => $row['call_30s'],
                'call_2h' => $row['call_2h'],
                'call_3h' => $row['call_3h'],
                'call_4h' => $row['call_4h'],
                'max_channel_usage' => $row['max_channel_usage'] == NULL ? '-' : $row['max_channel_usage'],
                'max_channel_allowed' => $row['max_channel_allowed'] == NULL ? 'Unlimited' : $row['max_channel_allowed'],
                'percentage_of_trunk_usage' => $row['max_channel_allowed'] == NULL || $row['max_channel_allowed'] == NULL ? '-' : round($row['max_channel_usage'] / $row['max_channel_allowed'] * 100, 2) . '%',
            );
        }

        $stmt->closeCursor();
        $dbh = NULL;

        echo json_encode($data);
    }


    public function get_dest_data()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;

        $timezone = $_GET['timezone'];
        $start_time = $_GET['start_time'];
        $end_time = $_GET['end_time'];
        $country_search = isset($_GET['country_search']) ? $_GET['country_search'] : '';
        $destination = isset($_GET['destination']) ? $_GET['destination'] : '';
        $ingress_trunk = isset($_GET['ingress_trunk']) ? $_GET['ingress_trunk'] : '';
        $egress_trunk = isset($_GET['egress_trunk']) ? $_GET['egress_trunk'] : '';

        $start_date = $start_time . $timezone;
        $end_date = $end_time . $timezone;

        $report_max_time = $this->Cdr->get_report_maxtime($start_date, $end_date);

        $select_time_end = strtotime($end_date);

        $is_from_client_cdr = false;

        if (empty($report_max_time)) {
            $is_from_client_cdr = true;
            $report_max_time = $start_date;
        }
        $system_max_end = strtotime($report_max_time);

        $where = array();
        $where2 = array();

        if (!empty($country_search)) {
            array_push($where, "egress_country = '{$country_search}'");
            array_push($where2, "term_country = '{$country_search}'");
        }

        if (!empty($destination)) {
            array_push($where, "egress_code_name = '{$destination}'");
            array_push($where2, "term_code_name = '{$destination}'");
        }

        if (!empty($ingress_trunk)) {
            array_push($where, "ingress_id = {$ingress_trunk}");
            array_push($where2, "ingress_id = '{$ingress_trunk}'");
        }

        if (!empty($egress_trunk)) {
            array_push($where, "egress_id = {$egress_trunk}");
            array_push($where2, "egress_id = '{$egress_trunk}'");
        }


        $data = array(
            array(
                'id' => 0,
                'originator' => 'All',
            )
        );

        $today_date = date("Ymd");

        $database_conf = new DATABASE_CONFIG;
        $dbh = new PDO("pgsql:host={$database_conf->default['host']};port={$database_conf->default['port']};dbname={$database_conf->default['database']};user={$database_conf->default['login']};password={$database_conf->default['password']}");
        if ($select_time_end > $system_max_end) {
            if ($is_from_client_cdr) {
                array_push($where2, "time between '$report_max_time' and '$end_date'");
                array_push($where2, "is_final_call=1");
                $where2_str = implode(" and ", $where2);
                $sql = "select
sum(egress_cost) as egress_call_cost,
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
count(case when call_duration <= 6 then 1 else null end) as call_6s,
count(case when call_duration <= 12 then 1 else null end) as call_12s,
count(case when call_duration <= 18 then 1 else null end) as call_18s,
count(case when call_duration <= 24 then 1 else null end) as call_24s,
count(case when call_duration <= 30 then 1 else null end) as call_30s,
count(case when call_duration >= (7200) then 1 else null end) as call_2h,
count(case when call_duration >= (10800) then 1 else null end) as call_3h,
count(case when call_duration >= (14400) then 1 else null end) as call_4h
from client_cdr{$today_date} where $where2_str";
            } else {
                array_push($where, "report_time between '$start_date' and '$report_max_time'");
                array_push($where2, "time between '$report_max_time' and '$end_date'");
                array_push($where2, "is_final_call=1");
                $where_str = implode(" and ", $where);
                $where2_str = implode(" and ", $where2);
                $sql1 = "SELECT
        sum(egress_call_cost) as egress_call_cost,
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
        sum(not_zero_calls_6) as call_6s,
        sum(call_12s) as call_12s,
        sum(call_18s) as call_18s,
        sum(call_24s) as call_24s,
        sum(not_zero_calls_30) as call_30s,
        sum(call_2h) as call_2h,
        sum(call_3h) as call_3h,
        sum(call_4h) as call_4h
        from " . CDR_TABLE . "
        where $where_str";
                $sql2 = "select
sum(egress_cost) as egress_call_cost,
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
count(case when call_duration <= 6 then 1 else null end) as call_6s,
count(case when call_duration <= 12 then 1 else null end) as call_12s,
count(case when call_duration <= 18 then 1 else null end) as call_18s,
count(case when call_duration <= 24 then 1 else null end) as call_24s,
count(case when call_duration <= 30 then 1 else null end) as call_30s,
count(case when call_duration >= (7200) then 1 else null end) as call_2h,
count(case when call_duration >= (10800) then 1 else null end) as call_3h,
count(case when call_duration >= (14400) then 1 else null end) as call_4h
from client_cdr{$today_date} where $where2_str";
                $sql = "SELECT sum(egress_call_cost) as egress_call_cost,
sum(duration) as duration , sum(bill_time) as bill_time,  sum(call_cost) as call_cost, sum(lnp_cost) as lnp_cost, sum(not_zero_calls) as not_zero_calls,
sum(total_calls) as total_calls, sum(success_calls) as success_calls, sum(busy_calls) as busy_calls, 
sum(lrn_calls) as lrn_calls, sum(pdd) as pdd, sum(cancel_calls) as cancel_calls,
sum(call_6s) as call_6s, sum(call_12s) as call_12s, sum(call_18s) as call_18s, sum(call_24s) as call_24s, sum(call_30s) as call_30s, sum(call_2h) as call_2h, 
sum(call_3h) as call_3h, sum(call_4h) as call_4h FROM  
(    
(
$sql1
)

union
(
$sql2
)
)  
as t";
            }
        } else {
            array_push($where, "report_time between '$start_date' and '$end_date'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT
        sum(egress_call_cost) as egress_call_cost,
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
        sum(not_zero_calls_6) as call_6s,
        sum(call_12s) as call_12s,
        sum(call_18s) as call_18s,
        sum(call_24s) as call_24s,
        sum(not_zero_calls_30) as call_30s,
        sum(call_2h) as call_2h,
        sum(call_3h) as call_3h,
        sum(call_4h) as call_4h
        from " . CDR_TABLE . "
        where $where_str";
        }

        $stmt = $dbh->query($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $row = $stmt->fetch();
        $data[0]['children'][0] = array(
            'id' => 1,
            'originator' => 'Country',
            'atts' => number_format($row['total_calls']),
            'cc' => number_format($row['not_zero_calls']),
            'mins' => round($row['bill_time'] / 60, 2),
            'asr' => ($row['not_zero_calls']) == 0 ? 0 : round($row['not_zero_calls'] / ($row['total_calls']) * 100, 2),
            'acd' => round($row['not_zero_calls'] == 0 ? 0 : $row['duration'] / $row['not_zero_calls'] / 60, 2),
            'pdd' => round($row['not_zero_calls'] == 0 ? 0 : $row['pdd'] / $row['not_zero_calls']),
//            'cps'        => '-',
            'rev' => number_format($row['call_cost'], 5),
            'cost' => number_format($row['egress_call_cost'], 5),
            'margin' => number_format($row['call_cost'] + $row['lnp_cost'] - $row['egress_call_cost'], 5),
            'call_6s' => $row['call_6s'],
            'call_12s' => $row['call_12s'],
            'call_18s' => $row['call_18s'],
            'call_24s' => $row['call_24s'],
            'call_30s' => $row['call_30s'],
            'call_2h' => $row['call_2h'],
            'call_3h' => $row['call_3h'],
            'call_4h' => $row['call_4h'],
            'max_channel_usage' => '-',
            'max_channel_allowed' => '-',
            'percentage_of_trunk_usage' => '-',
        );


        if ($select_time_end > $system_max_end) {
            if ($is_from_client_cdr) {
                array_push($where2, "time between '$report_max_time' and '$end_date'");
                $where2_str = implode(" and ", $where2);
                $sql = "select
term_country as egress_country,
sum(egress_cost) as egress_call_cost,
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
count(case when call_duration <= 6 then 1 else null end) as call_6s,
count(case when call_duration <= 12 then 1 else null end) as call_12s,
count(case when call_duration <= 18 then 1 else null end) as call_18s,
count(case when call_duration <= 24 then 1 else null end) as call_24s,
count(case when call_duration <= 30 then 1 else null end) as call_30s,
count(case when call_duration >= (7200) then 1 else null end) as call_2h,
count(case when call_duration >= (10800) then 1 else null end) as call_3h,
count(case when call_duration >= (14400) then 1 else null end) as call_4h
from client_cdr{$today_date} where $where2_str GROUP BY egress_country";
            } else {
                array_push($where, "report_time between '$start_date' and '$report_max_time'");
                array_push($where2, "time between '$report_max_time' and '$end_date'");
                array_push($where2, "is_final_call=1");
                $where_str = implode(" and ", $where);
                $where2_str = implode(" and ", $where2);
                $sql1 = "SELECT
        egress_country,
        sum(egress_call_cost) as egress_call_cost,
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
        sum(not_zero_calls_6) as call_6s,
        sum(call_12s) as call_12s,
        sum(call_18s) as call_18s,
        sum(call_24s) as call_24s,
        sum(not_zero_calls_30) as call_30s,
        sum(call_2h) as call_2h,
        sum(call_3h) as call_3h,
        sum(call_4h) as call_4h
        from " . CDR_TABLE . "
        where $where_str GROUP BY egress_country";
                $sql2 = "select
term_country as egress_country,
sum(egress_cost) as egress_call_cost,
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
count(case when call_duration <= 6 then 1 else null end) as call_6s,
count(case when call_duration <= 12 then 1 else null end) as call_12s,
count(case when call_duration <= 18 then 1 else null end) as call_18s,
count(case when call_duration <= 24 then 1 else null end) as call_24s,
count(case when call_duration <= 30 then 1 else null end) as call_30s,
count(case when call_duration >= (7200) then 1 else null end) as call_2h,
count(case when call_duration >= (10800) then 1 else null end) as call_3h,
count(case when call_duration >= (14400) then 1 else null end) as call_4h
from client_cdr{$today_date} where $where2_str GROUP BY term_country";
                $sql = "SELECT egress_country,sum(egress_call_cost) as egress_call_cost,
sum(duration) as duration , sum(bill_time) as bill_time,  sum(call_cost) as call_cost, sum(lnp_cost) as lnp_cost, sum(not_zero_calls) as not_zero_calls,
sum(total_calls) as total_calls, sum(success_calls) as success_calls, sum(busy_calls) as busy_calls, 
sum(lrn_calls) as lrn_calls, sum(pdd) as pdd, sum(cancel_calls) as cancel_calls,
sum(call_6s) as call_6s, sum(call_12s) as call_12s, sum(call_18s) as call_18s, sum(call_24s) as call_24s, sum(call_30s) as call_30s, sum(call_2h) as call_2h, 
sum(call_3h) as call_3h, sum(call_4h) as call_4h FROM  
(    
(
$sql1
)

union
(
$sql2
)
)  
as t GROUP BY egress_country";
            }
        } else {
            array_push($where, "report_time between '$start_date' and '$end_date'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT
        egress_country,
        sum(egress_call_cost) as egress_call_cost,
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
        sum(not_zero_calls_6) as call_6s,
        sum(call_12s) as call_12s,
        sum(call_18s) as call_18s,
        sum(call_24s) as call_24s,
        sum(not_zero_calls_30) as call_30s,
        sum(call_2h) as call_2h,
        sum(call_3h) as call_3h,
        sum(call_4h) as call_4h
        from " . CDR_TABLE . "
        where $where_str GROUP BY egress_country";
        }
        $stmt = $dbh->query($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $stmt->fetch()) {
            $data[0]['children'][0]['children'][] = array(
                'id' => uniqid(),
                'originator' => $row['egress_country'],
                'atts' => number_format($row['total_calls']),
                'cc' => number_format($row['not_zero_calls']),
                'mins' => round($row['bill_time'] / 60, 2),
                'asr' => round($row['total_calls'] == 0 ? 0 : $row['not_zero_calls'] / $row['total_calls'] * 100, 2),
                'acd' => round($row['not_zero_calls'] == 0 ? 0 : $row['duration'] / $row['not_zero_calls'] / 60, 2),
                'pdd' => round($row['not_zero_calls'] == 0 ? 0 : $row['pdd'] / $row['not_zero_calls']),
//                'cps'        => '-',
                'rev' => number_format($row['call_cost'], 5),
                'cost' => number_format($row['egress_call_cost'], 5),
                'margin' => number_format($row['call_cost'] + $row['lnp_cost'] - $row['egress_call_cost'], 5),
                'egress_country' => $row['egress_country'],
                'state' => 'closed',
                'call_6s' => $row['call_6s'],
                'call_12s' => $row['call_12s'],
                'call_18s' => $row['call_18s'],
                'call_24s' => $row['call_24s'],
                'call_30s' => $row['call_30s'],
                'call_2h' => $row['call_2h'],
                'call_3h' => $row['call_3h'],
                'call_4h' => $row['call_4h'],
                'max_channel_usage' => '-',
                'max_channel_allowed' => '-',
                'percentage_of_trunk_usage' => '-',
            );
        }


        $stmt->closeCursor();
        $dbh = NULL;

        echo json_encode($data);
    }


    public function get_dest_data1()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;

        $egress_country = $_GET['egress_country'];

        $timezone = $_GET['timezone'];
        $start_time = $_GET['start_time'];
        $end_time = $_GET['end_time'];
        $country_search = isset($_GET['country_search']) ? $_GET['country_search'] : '';
        $destination = isset($_GET['destination']) ? $_GET['destination'] : '';
        $ingress_trunk = isset($_GET['ingress_trunk']) ? $_GET['ingress_trunk'] : '';
        $egress_trunk = isset($_GET['egress_trunk']) ? $_GET['egress_trunk'] : '';

        $start_date = $start_time . $timezone;
        $end_date = $end_time . $timezone;

        $report_max_time = $this->Cdr->get_report_maxtime($start_date, $end_date);

        $select_time_end = strtotime($end_date);

        $is_from_client_cdr = false;

        if (empty($report_max_time)) {
            $is_from_client_cdr = true;
            $report_max_time = $start_date;
        }
        $system_max_end = strtotime($report_max_time);

        $where = array();
        $where2 = array();

        if (!empty($country_search)) {
            array_push($where, "egress_country = '{$country_search}'");
            array_push($where2, "term_country = '{$country_search}'");
        }

        if (!empty($destination)) {
            array_push($where, "egress_code_name = '{$destination}'");
            array_push($where2, "term_code_name = '{$destination}'");
        }

        if (!empty($ingress_trunk)) {
            array_push($where, "ingress_id = {$ingress_trunk}");
            array_push($where2, "ingress_id = '{$ingress_trunk}'");
        }

        if (!empty($egress_trunk)) {
            array_push($where, "egress_id = {$egress_trunk}");
            array_push($where2, "egress_id = '{$egress_trunk}'");
        }

        $data = array();

        $database_conf = new DATABASE_CONFIG;
        $dbh = new PDO("pgsql:host={$database_conf->default['host']};port={$database_conf->default['port']};dbname={$database_conf->default['database']};user={$database_conf->default['login']};password={$database_conf->default['password']}");

        $today_date = date("Ymd");

        if ($select_time_end > $system_max_end) {
            if ($is_from_client_cdr) {
                array_push($where2, "time between '$report_max_time' and '$end_date'");
                array_push($where2, "is_final_call=1");
                $where2_str = implode(" and ", $where2);
                $sql = "select
term_code_name as egress_code_name,
sum(egress_cost) as egress_call_cost,
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
count(case when call_duration <= 6 then 1 else null end) as call_6s,
count(case when call_duration <= 12 then 1 else null end) as call_12s,
count(case when call_duration <= 18 then 1 else null end) as call_18s,
count(case when call_duration <= 24 then 1 else null end) as call_24s,
count(case when call_duration <= 30 then 1 else null end) as call_30s,
count(case when call_duration >= (7200) then 1 else null end) as call_2h,
count(case when call_duration >= (10800) then 1 else null end) as call_3h,
count(case when call_duration >= (14400) then 1 else null end) as call_4h
from client_cdr{$today_date} where $where2_str and term_country = '{$egress_country}' GROUP BY term_code_name";
            } else {
                array_push($where, "report_time between '$start_date' and '$report_max_time'");
                array_push($where2, "time between '$report_max_time' and '$end_date'");
                array_push($where2, "is_final_call=1");
                $where_str = implode(" and ", $where);
                $where2_str = implode(" and ", $where2);
                $sql1 = "SELECT
        egress_code_name,
        sum(egress_call_cost) as egress_call_cost,
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
        sum(not_zero_calls_6) as call_6s,
        sum(call_12s) as call_12s,
        sum(call_18s) as call_18s,
        sum(call_24s) as call_24s,
        sum(not_zero_calls_30) as call_30s,
        sum(call_2h) as call_2h,
        sum(call_3h) as call_3h,
        sum(call_4h) as call_4h
        from " . CDR_TABLE . "
        where $where_str and egress_country = '{$egress_country}' GROUP BY egress_code_name";
                $sql2 = "select
term_code_name as egress_code_name,
sum(egress_cost) as egress_call_cost,
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
count(case when call_duration <= 6 then 1 else null end) as call_6s,
count(case when call_duration <= 12 then 1 else null end) as call_12s,
count(case when call_duration <= 18 then 1 else null end) as call_18s,
count(case when call_duration <= 24 then 1 else null end) as call_24s,
count(case when call_duration <= 30 then 1 else null end) as call_30s,
count(case when call_duration >= (7200) then 1 else null end) as call_2h,
count(case when call_duration >= (10800) then 1 else null end) as call_3h,
count(case when call_duration >= (14400) then 1 else null end) as call_4h
from client_cdr{$today_date} where $where2_str and term_country = '{$egress_country}' GROUP BY term_code_name";
                $sql = "SELECT egress_code_name,sum(egress_call_cost) as egress_call_cost,
sum(duration) as duration , sum(bill_time) as bill_time,  sum(call_cost) as call_cost, sum(lnp_cost) as lnp_cost, sum(not_zero_calls) as not_zero_calls,
sum(total_calls) as total_calls, sum(success_calls) as success_calls, sum(busy_calls) as busy_calls, 
sum(lrn_calls) as lrn_calls, sum(pdd) as pdd, sum(cancel_calls) as cancel_calls,
sum(call_6s) as call_6s, sum(call_12s) as call_12s, sum(call_18s) as call_18s, sum(call_24s) as call_24s, sum(call_30s) as call_30s, sum(call_2h) as call_2h, 
sum(call_3h) as call_3h, sum(call_4h) as call_4h FROM  
(    
(
$sql1
)

union
(
$sql2
)
)  
as t GROUP BY egress_code_name";
            }
        } else {
            array_push($where, "report_time between '$start_date' and '$end_date'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT
        egress_code_name,
        sum(egress_call_cost) as egress_call_cost,
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
        sum(not_zero_calls_6) as call_6s,
        sum(call_12s) as call_12s,
        sum(call_18s) as call_18s,
        sum(call_24s) as call_24s,
        sum(not_zero_calls_30) as call_30s,
        sum(call_2h) as call_2h,
        sum(call_3h) as call_3h,
        sum(call_4h) as call_4h
        from " . CDR_TABLE . "
        where $where_str and egress_country = '{$egress_country}' GROUP BY egress_code_name";
        }
        $stmt = $dbh->query($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);


        while ($row = $stmt->fetch()) {
            $data[] = array(
                'id' => uniqid(),
                'originator' => $row['egress_code_name'],
                'atts' => number_format($row['total_calls']),
                'cc' => number_format($row['not_zero_calls']),
                'mins' => round($row['bill_time'] / 60, 2),
                'asr' => round($row['total_calls'] == 0 ? 0 : $row['not_zero_calls'] / $row['total_calls'] * 100, 2),
                'acd' => round($row['not_zero_calls'] == 0 ? 0 : $row['duration'] / $row['not_zero_calls'] / 60, 2),
                'pdd' => round($row['not_zero_calls'] == 0 ? 0 : $row['pdd'] / $row['not_zero_calls']),
//                'cps'        => '-',
                'rev' => number_format($row['call_cost'], 5),
                'cost' => number_format($row['egress_call_cost'], 5),
                'margin' => number_format($row['call_cost'] + $row['lnp_cost'] - $row['egress_call_cost'], 5),
                'egress_country' => $egress_country,
                'egress_code_name' => $row['egress_code_name'],
                'type' => 'code_name',
                'state' => 'closed',
                'call_6s' => $row['call_6s'],
                'call_12s' => $row['call_12s'],
                'call_18s' => $row['call_18s'],
                'call_24s' => $row['call_24s'],
                'call_30s' => $row['call_30s'],
                'call_2h' => $row['call_2h'],
                'call_3h' => $row['call_3h'],
                'call_4h' => $row['call_4h'],
                'max_channel_usage' => '-',
                'max_channel_allowed' => '-',
                'percentage_of_trunk_usage' => '-',
            );
        }

        $stmt->closeCursor();
        $dbh = NULL;

        echo json_encode($data);
    }


    public function get_dest_data2()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;

        $egress_country = $_GET['egress_country'];
        $egress_code_name = $_GET['egress_code_name'];

        $timezone = $_GET['timezone'];
        $start_time = $_GET['start_time'];
        $end_time = $_GET['end_time'];
        $country_search = isset($_GET['country_search']) ? $_GET['country_search'] : '';
        $destination = isset($_GET['destination']) ? $_GET['destination'] : '';
        $ingress_trunk = isset($_GET['ingress_trunk']) ? $_GET['ingress_trunk'] : '';
        $egress_trunk = isset($_GET['egress_trunk']) ? $_GET['egress_trunk'] : '';

        $start_date = $start_time . $timezone;
        $end_date = $end_time . $timezone;

        $report_max_time = $this->Cdr->get_report_maxtime($start_date, $end_date);

        $select_time_end = strtotime($end_date);

        $is_from_client_cdr = false;

        if (empty($report_max_time)) {
            $is_from_client_cdr = true;
            $report_max_time = $start_date;
        }
        $system_max_end = strtotime($report_max_time);

        $where = array();
        $where2 = array();

        if (!empty($country_search)) {
            array_push($where, "egress_country = '{$country_search}'");
            array_push($where2, "term_country = '{$country_search}'");
        }

        if (!empty($destination)) {
            array_push($where, "egress_code_name = '{$destination}'");
            array_push($where2, "term_code_name = '{$destination}'");
        }

        if (!empty($ingress_trunk)) {
            array_push($where, "ingress_id = {$ingress_trunk}");
            array_push($where2, "ingress_id = '{$ingress_trunk}'");
        }

        if (!empty($egress_trunk)) {
            array_push($where, "egress_id = {$egress_trunk}");
            array_push($where2, "egress_id = '{$egress_trunk}'");
        }

        $data = array();

        $today_date = date("Ymd");

        $database_conf = new DATABASE_CONFIG;
        $dbh = new PDO("pgsql:host={$database_conf->default['host']};port={$database_conf->default['port']};dbname={$database_conf->default['database']};user={$database_conf->default['login']};password={$database_conf->default['password']}");

        if ($select_time_end > $system_max_end) {
            if ($is_from_client_cdr) {
                array_push($where2, "time between '$report_max_time' and '$end_date'");
                array_push($where2, "is_final_call=1");
                $where2_str = implode(" and ", $where2);
                $sql = "select
(SELECT alias FROM resource WHERE resource_id = egress_id) as egress_name,                
egress_id,
sum(egress_cost) as egress_call_cost,
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
        (SELECT max(call) FROM qos_resource WHERE res_id = client_cdr.egress_id AND report_time between '$start_time' and '$end_time') as max_channel_usage,
        (SELECT capacity FROM resource where resource_id = client_cdr.egress_id) as max_channel_allowed,
count(case when call_duration <= 6 then 1 else null end) as call_6s,
count(case when call_duration <= 12 then 1 else null end) as call_12s,
count(case when call_duration <= 18 then 1 else null end) as call_18s,
count(case when call_duration <= 24 then 1 else null end) as call_24s,
count(case when call_duration <= 30 then 1 else null end) as call_30s,
count(case when call_duration >= (7200) then 1 else null end) as call_2h,
count(case when call_duration >= (10800) then 1 else null end) as call_3h,
count(case when call_duration >= (14400) then 1 else null end) as call_4h
from client_cdr{$today_date} where $where2_str and term_country = '{$egress_country}' and term_code_name = '{$egress_code_name}' GROUP BY egress_id";
            } else {
                array_push($where, "report_time between '$start_date' and '$report_max_time'");
                array_push($where2, "time between '$report_max_time' and '$end_date'");
                array_push($where2, "is_final_call=1");
                $where_str = implode(" and ", $where);
                $where2_str = implode(" and ", $where2);
                $sql1 = "SELECT
        egress_id,
        sum(egress_call_cost) as egress_call_cost,
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
        sum(not_zero_calls_6) as call_6s,
        sum(call_12s) as call_12s,
        sum(call_18s) as call_18s,
        sum(call_24s) as call_24s,
        sum(not_zero_calls_30) as call_30s,
        sum(call_2h) as call_2h,
        sum(call_3h) as call_3h,
        sum(call_4h) as call_4h
        from " . CDR_TABLE . "
        where $where_str and egress_country = '{$egress_country}' and egress_code_name = '{$egress_code_name}' group by egress_id";
                $sql2 = "select
egress_id,
sum(egress_cost) as egress_call_cost,
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
count(case when call_duration <= 6 then 1 else null end) as call_6s,
count(case when call_duration <= 12 then 1 else null end) as call_12s,
count(case when call_duration <= 18 then 1 else null end) as call_18s,
count(case when call_duration <= 24 then 1 else null end) as call_24s,
count(case when call_duration <= 30 then 1 else null end) as call_30s,
count(case when call_duration >= (7200) then 1 else null end) as call_2h,
count(case when call_duration >= (10800) then 1 else null end) as call_3h,
count(case when call_duration >= (14400) then 1 else null end) as call_4h
from client_cdr{$today_date} where $where2_str and term_country = '{$egress_country}' and term_code_name = '{$egress_code_name}' group by egress_id";
                $sql = "SELECT (SELECT alias FROM resource WHERE resource_id = egress_id) as egress_name,
egress_id,sum(egress_call_cost) as egress_call_cost,
sum(duration) as duration , sum(bill_time) as bill_time,  sum(call_cost) as call_cost, sum(lnp_cost) as lnp_cost, sum(not_zero_calls) as not_zero_calls,
sum(total_calls) as total_calls, sum(success_calls) as success_calls, sum(busy_calls) as busy_calls, 
sum(lrn_calls) as lrn_calls, sum(pdd) as pdd, sum(cancel_calls) as cancel_calls,
        (SELECT max(call) FROM qos_resource WHERE res_id = t.egress_id AND report_time between '$start_time' and '$end_time') as max_channel_usage,
        (SELECT capacity FROM resource where resource_id = t.egress_id) as max_channel_allowed,
sum(call_6s) as call_6s, sum(call_12s) as call_12s, sum(call_18s) as call_18s, sum(call_24s) as call_24s, sum(call_30s) as call_30s, sum(call_2h) as call_2h, 
sum(call_3h) as call_3h, sum(call_4h) as call_4h FROM  
(    
(
$sql1
)

union
(
$sql2
)
)  
as t GROUP BY egress_id";
            }
        } else {
            array_push($where, "report_time between '$start_date' and '$end_date'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT
        (SELECT alias FROM resource WHERE resource_id = egress_id) as egress_name,
        egress_id,
        sum(egress_call_cost) as egress_call_cost,
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
        (SELECT max(call) FROM qos_resource WHERE res_id = " . CDR_TABLE . ".egress_id AND report_time between '$start_time' and '$end_time') as max_channel_usage,
        (SELECT capacity FROM resource where resource_id = " . CDR_TABLE . ".egress_id) as max_channel_allowed,
        sum(not_zero_calls_6) as call_6s,
        sum(call_12s) as call_12s,
        sum(call_18s) as call_18s,
        sum(call_24s) as call_24s,
        sum(not_zero_calls_30) as call_30s,
        sum(call_2h) as call_2h,
        sum(call_3h) as call_3h,
        sum(call_4h) as call_4h
        from " . CDR_TABLE . "
        where $where_str and egress_country = '{$egress_country}' and egress_code_name = '{$egress_code_name}' group by egress_id";
        }

        $stmt = $dbh->query($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $i = 0;
        while ($row = $stmt->fetch()) {
            $data[] = array(
                'id' => uniqid(),
                'originator' => $row['egress_name'],
                'atts' => number_format($row['total_calls']),
                'cc' => number_format($row['not_zero_calls']),
                'mins' => round($row['bill_time'] / 60, 2),
                'asr' => round($row['total_calls'] == 0 ? 0 : $row['not_zero_calls'] / $row['total_calls'] * 100, 2),
                'acd' => round($row['not_zero_calls'] == 0 ? 0 : $row['duration'] / $row['not_zero_calls'] / 60, 2),
                'pdd' => round($row['not_zero_calls'] == 0 ? 0 : $row['pdd'] / $row['not_zero_calls']),
//                'cps'        => '-',
                'rev' => number_format($row['call_cost'], 5),
                'cost' => number_format($row['egress_call_cost'], 5),
                'margin' => number_format($row['call_cost'] + $row['lnp_cost'] - $row['egress_call_cost'], 5),
                'egress_country' => $egress_country,
                'egress_code_name' => $egress_code_name,
                'egress_id' => $row['egress_id'],
                'type' => 'egress_trunk',
                'state' => 'closed',
                'call_6s' => $row['call_6s'],
                'call_12s' => $row['call_12s'],
                'call_18s' => $row['call_18s'],
                'call_24s' => $row['call_24s'],
                'call_30s' => $row['call_30s'],
                'call_2h' => $row['call_2h'],
                'call_3h' => $row['call_3h'],
                'call_4h' => $row['call_4h'],
                'max_channel_usage' => $row['max_channel_usage'] == NULL ? '-' : $row['max_channel_usage'],
                'max_channel_allowed' => $row['max_channel_allowed'] == NULL ? 'Unlimited' : $row['max_channel_allowed'],
                'percentage_of_trunk_usage' => $row['max_channel_allowed'] == NULL || $row['max_channel_allowed'] == NULL ? '-' : round($row['max_channel_usage'] / $row['max_channel_allowed'] * 100, 2) . '%',
            );
            $i++;
        }

        $stmt->closeCursor();
        $dbh = NULL;

        echo json_encode($data);
    }


    public function get_dest_data3()
    {
        Configure::write('debug', 0);
        $this->autoRender = false;
        $this->autoLayout = false;
        $egress_country = $_GET['egress_country'];
        $egress_code_name = $_GET['egress_code_name'];
        $egress_id = $_GET['egress_id'];


        $timezone = $_GET['timezone'];
        $start_time = $_GET['start_time'];
        $end_time = $_GET['end_time'];
        $country_search = isset($_GET['country_search']) ? $_GET['country_search'] : '';
        $destination = isset($_GET['destination']) ? $_GET['destination'] : '';
        $ingress_trunk = isset($_GET['ingress_trunk']) ? $_GET['ingress_trunk'] : '';
        $egress_trunk = isset($_GET['egress_trunk']) ? $_GET['egress_trunk'] : '';

        $start_date = $start_time . $timezone;
        $end_date = $end_time . $timezone;

        $report_max_time = $this->Cdr->get_report_maxtime($start_date, $end_date);

        $select_time_end = strtotime($end_date);

        $is_from_client_cdr = false;

        if (empty($report_max_time)) {
            $is_from_client_cdr = true;
            $report_max_time = $start_date;
        }
        $system_max_end = strtotime($report_max_time);

        $where = array();
        $where2 = array();

        if (!empty($country_search)) {
            array_push($where, "egress_country = '{$country_search}'");
            array_push($where2, "term_country = '{$country_search}'");
        }

        if (!empty($destination)) {
            array_push($where, "egress_code_name = '{$destination}'");
            array_push($where2, "term_code_name = '{$destination}'");
        }

        if (!empty($ingress_trunk)) {
            array_push($where, "ingress_id = {$ingress_trunk}");
            array_push($where2, "ingress_id = '{$ingress_trunk}'");
        }

        if (!empty($egress_trunk)) {
            array_push($where, "egress_id = {$egress_trunk}");
            array_push($where2, "egress_id = '{$egress_trunk}'");
        }

        $data = array();

        $today_date = date("Ymd");

        $database_conf = new DATABASE_CONFIG;
        $dbh = new PDO("pgsql:host={$database_conf->default['host']};port={$database_conf->default['port']};dbname={$database_conf->default['database']};user={$database_conf->default['login']};password={$database_conf->default['password']}");

        if ($select_time_end > $system_max_end) {
            if ($is_from_client_cdr) {
                array_push($where2, "time between '$report_max_time' and '$end_date'");
                array_push($where2, "is_final_call=1");
                $where2_str = implode(" and ", $where2);
                $sql = "select
(SELECT alias FROM resource WHERE resource_id = ingress_id) as ingress_name,
sum(egress_cost) as egress_call_cost,
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
        (SELECT max(call) FROM qos_resource WHERE res_id = client_cdr.ingress_id AND report_time between '$start_time' and '$end_time') as max_channel_usage,
        (SELECT capacity FROM resource where resource_id = client_cdr.ingress_id) as max_channel_allowed,
count(case when call_duration <= 6 then 1 else null end) as call_6s,
count(case when call_duration <= 12 then 1 else null end) as call_12s,
count(case when call_duration <= 18 then 1 else null end) as call_18s,
count(case when call_duration <= 24 then 1 else null end) as call_24s,
count(case when call_duration <= 30 then 1 else null end) as call_30s,
count(case when call_duration >= (7200) then 1 else null end) as call_2h,
count(case when call_duration >= (10800) then 1 else null end) as call_3h,
count(case when call_duration >= (14400) then 1 else null end) as call_4h
from client_cdr{$today_date} where $where2_str and term_country = '{$egress_country}' and term_code_name = '{$egress_code_name}' and egress_id = '{$egress_id}' group by ingress_id";
            } else {
                array_push($where, "report_time between '$start_date' and '$report_max_time'");
                array_push($where2, "time between '$report_max_time' and '$end_date'");
                array_push($where2, "is_final_call=1");
                $where_str = implode(" and ", $where);
                $where2_str = implode(" and ", $where2);
                $sql1 = "SELECT
        ingress_id,
        sum(egress_call_cost) as egress_call_cost,
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
        sum(not_zero_calls_6) as call_6s,
        sum(call_12s) as call_12s,
        sum(call_18s) as call_18s,
        sum(call_24s) as call_24s,
        sum(not_zero_calls_30) as call_30s,
        sum(call_2h) as call_2h,
        sum(call_3h) as call_3h,
        sum(call_4h) as call_4h
        from " . CDR_TABLE . "
        where $where_str and egress_country = '{$egress_country}' and egress_code_name = '{$egress_code_name}' and egress_id = {$egress_id} group by ingress_id";
                $sql2 = "select
ingress_id,
sum(egress_cost) as egress_call_cost,
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
count(case when call_duration <= 6 then 1 else null end) as call_6s,
count(case when call_duration <= 12 then 1 else null end) as call_12s,
count(case when call_duration <= 18 then 1 else null end) as call_18s,
count(case when call_duration <= 24 then 1 else null end) as call_24s,
count(case when call_duration <= 30 then 1 else null end) as call_30s,
count(case when call_duration >= (7200) then 1 else null end) as call_2h,
count(case when call_duration >= (10800) then 1 else null end) as call_3h,
count(case when call_duration >= (14400) then 1 else null end) as call_4h
from client_cdr{$today_date} where $where2_str and term_country = '{$egress_country}' and term_code_name = '{$egress_code_name}' and egress_id = {$egress_id} group by ingress_id";
                $sql = "SELECT 
(SELECT alias FROM resource WHERE resource_id = ingress_id) as ingress_name,sum(egress_call_cost) as egress_call_cost,
sum(duration) as duration , sum(bill_time) as bill_time,  sum(call_cost) as call_cost, sum(lnp_cost) as lnp_cost, sum(not_zero_calls) as not_zero_calls,
sum(total_calls) as total_calls, sum(success_calls) as success_calls, sum(busy_calls) as busy_calls, 
sum(lrn_calls) as lrn_calls, sum(pdd) as pdd, sum(cancel_calls) as cancel_calls,
        (SELECT max(call) FROM qos_resource WHERE res_id = t.ingress_id AND report_time between '$start_time' and '$end_time') as max_channel_usage,
        (SELECT capacity FROM resource where resource_id = t.ingress_id) as max_channel_allowed,
sum(call_6s) as call_6s, sum(call_12s) as call_12s, sum(call_18s) as call_18s, sum(call_24s) as call_24s, sum(call_30s) as call_30s, sum(call_2h) as call_2h, 
sum(call_3h) as call_3h, sum(call_4h) as call_4h FROM  
(    
(
$sql1
)

union
(
$sql2
)
)  
as t GROUP BY ingress_id";
            }
        } else {
            array_push($where, "report_time between '$start_date' and '$end_date'");
            $where_str = implode(" and ", $where);
            $sql = "SELECT
        (SELECT alias FROM resource WHERE resource_id = ingress_id) as ingress_name,
        sum(egress_call_cost) as egress_call_cost,
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
        (SELECT max(call) FROM qos_resource WHERE res_id = " . CDR_TABLE . ".ingress_id AND report_time between '$start_time' and '$end_time') as max_channel_usage,
        (SELECT capacity FROM resource where resource_id = " . CDR_TABLE . ".ingress_id) as max_channel_allowed,
        sum(not_zero_calls_6) as call_6s,
        sum(call_12s) as call_12s,
        sum(call_18s) as call_18s,
        sum(call_24s) as call_24s,
        sum(not_zero_calls_30) as call_30s,
        sum(call_2h) as call_2h,
        sum(call_3h) as call_3h,
        sum(call_4h) as call_4h
        from " . CDR_TABLE . "
        where $where_str and egress_country = '{$egress_country}' and egress_code_name = '{$egress_code_name}' and egress_id = {$egress_id} group by ingress_id";
        }

        $stmt = $dbh->query($sql);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        while ($row = $stmt->fetch()) {
            $data[] = array(
                'id' => uniqid(),
                'originator' => $row['ingress_name'],
                'atts' => number_format($row['total_calls']),
                'cc' => number_format($row['not_zero_calls']),
                'mins' => round($row['bill_time'] / 60, 2),
                'asr' => round($row['total_calls'] == 0 ? 0 : $row['not_zero_calls'] / $row['total_calls'] * 100, 2),
                'acd' => round($row['not_zero_calls'] == 0 ? 0 : $row['duration'] / $row['not_zero_calls'] / 60, 2),
                'pdd' => round($row['not_zero_calls'] == 0 ? 0 : $row['pdd'] / $row['not_zero_calls']),
//                'cps'        => '-',
                'rev' => number_format($row['call_cost'], 5),
                'cost' => number_format($row['egress_call_cost'], 5),
                'margin' => number_format($row['call_cost'] + $row['lnp_cost'] - $row['egress_call_cost'], 5),
                'call_6s' => $row['call_6s'],
                'call_12s' => $row['call_12s'],
                'call_18s' => $row['call_18s'],
                'call_24s' => $row['call_24s'],
                'call_30s' => $row['call_30s'],
                'call_2h' => $row['call_2h'],
                'call_3h' => $row['call_3h'],
                'call_4h' => $row['call_4h'],
                'max_channel_usage' => $row['max_channel_usage'] == NULL ? '-' : $row['max_channel_usage'],
                'max_channel_allowed' => $row['max_channel_allowed'] == NULL ? 'Unlimited' : $row['max_channel_allowed'],
                'percentage_of_trunk_usage' => $row['max_channel_allowed'] == NULL || $row['max_channel_allowed'] == NULL ? '-' : round($row['max_channel_usage'] / $row['max_channel_allowed'] * 100, 2) . '%',
            );
        }

        $stmt->closeCursor();
        $dbh = NULL;

        echo json_encode($data);
    }


}


?>
