<?php

class Clientcdr extends AppModel {
    
    var $name = 'Clientcdr';
    var $useTable = "client_cdr";
    var $primaryKey = "id";  
    
    
    public function get_report_maxtime($time) {
//        $sql = "SELECT max(report_time) + interval '1 hour' as end_time FROM lrn_report WHERE report_time between {$time}";
//        $result = $this->query($sql);
//        return $result[0][0]['end_time'];
    }
    
    public function lrn_report_prepare_count($start_date_time, $end_date_time, $isorder, $ingress_trunk, $time_group_by)
    {
        if ($time_group_by == 0)
        {
            $time_group = "to_char(report_time, 'YYYY-MM-DD') ";
        }
        else
        {
            $time_group = "to_char(report_time, 'YYYY-MM')";
        }
        if ($isorder)
            $ingress_id = ' ,ingress_id';
        else
            $ingress_id = '';
//        $sql =<<<EOT
//select
//count($time_group)
//from lrn_report where report_time between '$start_date_time' and '$end_date_time' $ingress_trunk group by {$time_group}{$ingress_id}
//EOT;
//        $results = $this->query($sql);
//        return $results[0][0]['count'];
        return 0;
    }
    
    public function lrn_report_client_cdr_count($report_max_time, $end_date_time, $isorder, $ingress_trunk, $time_group_by)
    {
        if ($time_group_by == 0)
        {
            $time_group = "to_char(time, 'YYYY-MM-DD') ";
        }
        else
        {
            $time_group = "to_char(time, 'YYYY-MM')";
        }
        if ($isorder)
            $ingress_id = ' ,ingress_id';
        else
            $ingress_id = '';

        //分表
        $date_arr = $this->_app_get_date_result_admin($report_max_time,$end_date_time,'client_cdr2%');

        $org_sql ='';
        foreach($date_arr as $value){
            $table_date = 'client_cdr'.$value;

            $union = "";
            if(!empty($org_sql)){
                $union = " union all ";
            }

            $org_sql .= " {$union}  SELECT
time::date {$ingress_id}
from {$table_date}
where time between '$report_max_time' and '$end_date_time'
 and is_final_call=1 $ingress_trunk
";

        }



        $sql =<<<EOT
SELECT count(distinct time) as sum from ({$org_sql}) as tmp group by time{$ingress_id}
EOT;
        $results = $this->query($sql);
        if(! $results)
        {
            return 0;
        }
        return $results[0][0]['sum'];
    }
    
    
    public function lrn_report_two_count($start_date_time, $report_max_time, $end_date_time, $isorder, $ingress_trunk, $time_group_by)
    {
        if ($time_group_by == 0)
        {
            $time_group1 = "to_char(time, 'YYYY-MM-DD') ";
            $time_group2 = "to_char(report_time, 'YYYY-MM-DD') ";
        }
        else
        {
            $time_group1 = "to_char(time, 'YYYY-MM')";
            $time_group2 = "to_char(report_time, 'YYYY-MM') ";
        }
        if ($isorder)
            $ingress_id = ' ,ingress_id';
        else
            $ingress_id = '';

        //分表
        $date_arr = $this->_app_get_date_result_admin($report_max_time,$end_date_time,'client_cdr2%');

        $org_sql ='';
        foreach($date_arr as $value){
            $table_date = 'client_cdr'.$value;

            $union = "";
            if(!empty($org_sql)){
                $union = " union all ";
            }

            $org_sql .= " {$union}  SELECT
{$time_group1} as time {$ingress_id}
from {$table_date}
where time between '$report_max_time' and '$end_date_time'
 and is_final_call=1 $ingress_trunk
";

        }

        $sql =<<<EOT
SELECT time from ({$org_sql}) as tmp group by time{$ingress_id}
EOT;



//        $sql =<<<EOT
//select
//count(distinct time)
//from
//(
//select
//{$time_group2} as time
//from lrn_report where report_time between '$start_date_time' and '$report_max_time' $ingress_trunk group by {$time_group2}{$ingress_id}
//union
//
//(
//{$sql}
//)
//) as t
//EOT;

        /*
         * $sql =<<<EOT
select
count(distinct time)
from
(
select
{$time_group2} as time, sum(lrn_server_count) as lrn_server_count,

sum(client_count) as client_count, sum(cache_count) as cache_count, sum(total_count) as total_count,
sum(lrn_no_response) as lrn_no_response,
sum(lnp_charge) as lnp_charge,sum(lrn_same) as lrn_same
from lrn_report where report_time between '$start_date_time' and '$report_max_time' $ingress_trunk group by {$time_group2}{$ingress_id}
union

SELECT {$time_group1} as time, count(case when lrn_number_vendor=2 then 1 else null end)
 as lrn_server_count, count(case when lrn_number_vendor=1 then 1 else null end) as client_count,
 count(case when lrn_number_vendor=3 then 1 else null end) as cache_count,
 count(case when lrn_number_vendor in ( 1, 2, 3) then 1 else null end) as total_count,
 count(case when release_cause=28  then 1 else null end) as lrn_no_response,
 sum(lnp_dipping_cost) as lnp_charge, count(case when origination_destination_number=lrn_dnis
 then 1 else null end) as lrn_same from client_cdr where time between '$report_max_time'
 and '$end_date_time' $ingress_trunk
 and is_final_call=1  group by time{$ingress_id}
) as t
EOT;
         * */

//        $results = $this->query($sql);
//        return $results[0][0]['count'];
        return 0;
    }
    
    
    public function lrn_report_prepare($start_date_time, $end_date_time, $isorder, $ingress_trunk, $pageSize, $offset, $time_group_by)
    {
        if ($time_group_by == 0)
        {
            $time_group = "to_char(report_time, 'YYYY-MM-DD') ";
        }
        else
        {
            $time_group = "to_char(report_time, 'YYYY-MM')";
        }
        if ($isorder)
            $ingress_id = ' ,ingress_id';
        else
            $ingress_id = '';
//        $sql =<<<EOT
//select
//$time_group as time{$ingress_id}, sum(lrn_server_count) as lrn_server_count,
//sum(client_count) as client_count, sum(cache_count) as cache_count, sum(total_count) as total_count,
//sum(lrn_no_response) as lrn_no_response,
//sum(lnp_charge) as lnp_charge,sum(lrn_same) as lrn_same
//from lrn_report where report_time between '$start_date_time' and '$end_date_time' $ingress_trunk group by {$time_group}{$ingress_id}  order by 1  limit $pageSize offset $offset
//EOT;
//        $results = $this->query($sql);
//        return $results;

        return array();
    }
    
    public function lrn_report_client_cdr($report_max_time, $end_date_time, $isorder, $ingress_trunk, $pageSize, $offset, $time_group_by)
    {
        if ($time_group_by == 0)
        {
            $time_group = "to_char(time, 'YYYY-MM-DD') ";
        }
        else
        {
            $time_group = "to_char(time, 'YYYY-MM')";
        }
        if ($isorder)
            $ingress_id = ' ,ingress_id';
        else
            $ingress_id = '';

        //分表
        $date_arr = $this->_app_get_date_result_admin($report_max_time,$end_date_time,'client_cdr2%');

        $org_sql ='';
        foreach($date_arr as $value){
            $table_date = 'client_cdr'.$value;

            $union = "";
            if(!empty($org_sql)){
                $union = " union all ";
            }

            $org_sql .= " {$union}  SELECT
{$time_group}  as time{$ingress_id},lrn_number_vendor,release_cause,lnp_dipping_cost,origination_destination_number,lrn_dnis
from {$table_date}
where time between '$report_max_time' and '$end_date_time' $ingress_trunk
 and is_final_call=1
";

        }


        $sql =<<<EOT
SELECT
time{$ingress_id}, count(case when lrn_number_vendor=2 then 1 else null end)
 as lrn_server_count, count(case when lrn_number_vendor=1 then 1 else null end) as client_count,
 count(case when lrn_number_vendor=3 then 1 else null end) as cache_count,
 count(case when lrn_number_vendor in (1, 2, 3) then 1 else null end) as total_count,
 count(case when release_cause=55  then 1 else null end) as lrn_no_response,
 sum(lnp_dipping_cost) as lnp_charge, count(case when origination_destination_number=lrn_dnis
 then 1 else null end) as lrn_same
from ( {$org_sql} ) as tmp
group by time {$ingress_id}  order by 1  limit $pageSize offset $offset
EOT;

//        pr($sql);exit;

     /*   $sql =<<<EOT
SELECT 
{$time_group}  as time{$ingress_id}, count(case when lrn_number_vendor=2 then 1 else null end)
 as lrn_server_count, count(case when lrn_number_vendor=1 then 1 else null end) as client_count, 
 count(case when lrn_number_vendor=3 then 1 else null end) as cache_count, 
 count(case when lrn_number_vendor in (1, 2, 3) then 1 else null end) as total_count, 
 count(case when release_cause=28  then 1 else null end) as lrn_no_response, 
 sum(lnp_dipping_cost) as lnp_charge, count(case when origination_destination_number=lrn_dnis 
 then 1 else null end) as lrn_same
from client_cdr where time between '$report_max_time' and '$end_date_time' $ingress_trunk
 and is_final_call=1  group by {$time_group}{$ingress_id}  order by 1  limit $pageSize offset $offset
EOT;
*/
        $results = $this->query($sql);
        return $results;
    }
    
    
    public function lrn_report_two($start_date_time, $report_max_time, $end_date_time, $isorder, $ingress_trunk, $pageSize, $offset, $time_group_by)
    {
        if ($time_group_by == 0)
        {
            $time_group1 = "to_char(time, 'YYYY-MM-DD') ";
            $time_group2 = "to_char(report_time, 'YYYY-MM-DD') ";
        }
        else
        {
            $time_group1 = "to_char(time, 'YYYY-MM')";
            $time_group2 = "to_char(report_time, 'YYYY-MM') ";
        }
        if ($isorder)
            $ingress_id = ' ,ingress_id';
        else
            $ingress_id = '';


        //分表
        $date_arr = $this->_app_get_date_result_admin($report_max_time,$end_date_time,'client_cdr2%');

        $org_sql ='';
        foreach($date_arr as $value){
            $table_date = 'client_cdr'.$value;

            $union = "";
            if(!empty($org_sql)){
                $union = " union all ";
            }

            $org_sql .= " {$union}  SELECT
{$time_group1}  as time{$ingress_id},lrn_number_vendor,release_cause,lnp_dipping_cost,origination_destination_number,lrn_dnis
from {$table_date}
where time between '$report_max_time' and '$end_date_time' $ingress_trunk
 and is_final_call=1
";

        }

        $sql =<<<EOT
SELECT
time{$ingress_id}, count(case when lrn_number_vendor=2 then 1 else null end)
 as lrn_server_count, count(case when lrn_number_vendor=1 then 1 else null end) as client_count,
 count(case when lrn_number_vendor=3 then 1 else null end) as cache_count,
 count(case when lrn_number_vendor in (1, 2, 3) then 1 else null end) as total_count,
 count(case when release_cause=28  then 1 else null end) as lrn_no_response,
 sum(lnp_dipping_cost) as lnp_charge, count(case when origination_destination_number=lrn_dnis
 then 1 else null end) as lrn_same
from ( {$org_sql} ) as tmp
group by time {$ingress_id}
EOT;


//        $sql =<<<EOT
//select
//time as time{$ingress_id}, sum(lrn_server_count) as lrn_server_count,
//
//sum(client_count) as client_count, sum(cache_count) as cache_count, sum(total_count) as total_count,
//sum(lrn_no_response) as lrn_no_response,
//sum(lnp_charge) as lnp_charge,sum(lrn_same) as lrn_same
//from
//(
//select
//$time_group2 as time{$ingress_id}, sum(lrn_server_count) as lrn_server_count,
//
//sum(client_count) as client_count, sum(cache_count) as cache_count, sum(total_count) as total_count,
//sum(lrn_no_response) as lrn_no_response,
//sum(lnp_charge) as lnp_charge,sum(lrn_same) as lrn_same
//from lrn_report where report_time between '$start_date_time' and '$report_max_time' $ingress_trunk group by {$time_group2}{$ingress_id}
//union
//
//( {$sql} )
//) as t
//
//group by time{$ingress_id}   order by 1 limit $pageSize offset $offset
//EOT;
//        $results = $this->query($sql);
//        return $results;
        return array();
    }
    
    
    
    public function lrn_report_count($time, $ingress_trunk, $isorder) {
        
        $position_1 = "";
        $position_2 = "";
        
        if($isorder) {
            $position_1 = "trunk_id_origination as ingress_trunk,";
            $position_2 = ",trunk_id_origination";
        }

        $today_date = date("Ymd");
        $sql = <<<EOT
        SELECT count(*) as c FROM (SELECT

        time::date,

        $position_1

        count(case when lrn_number_vendor=2 then 1 else null end)
as lrn_server_count,

        count(case when lrn_number_vendor=1 then 1 else null end) as client_count,

        count(case when lrn_number_vendor=3 then 1 else null end) as cache_count,
        
        count(case when release_cause=28 then 1 else null end) as lrn_no_response,

        sum(lnp_dipping_cost) as lnp_charge from client_cdr{$today_date} where time between $time $ingress_trunk  and is_final_call='1'

        group by time::date$position_2$position_2

        order by 1) as t;
EOT;
        $results = $this->query($sql);
        return $results[0][0]['c'];
    }
    
    public function lrn_report($limit, $offset, $time, $ingress_trunk, $isorder) {
        $position_1 = "";
        $position_2 = "";
        if($isorder) {
            $position_1 = "trunk_id_origination as ingress_trunk,";
            $position_2 = ",trunk_id_origination ";
        }
        $today_date = date("Ymd");
        $sql =<<<EOT
        SELECT 

        time::date,

        $position_1

        count(case when lrn_number_vendor=2 then 1 else null end)
as lrn_server_count,

        count(case when lrn_number_vendor=1 then 1 else null end) as client_count,

        count(case when lrn_number_vendor=3 then 1 else null end) as cache_count,
        
        count(case when lrn_number_vendor in (0, 1, 2, 3) then 1 else null end) as total_count,
        
        count(case when release_cause=28 or lrn_number_vendor = 0 then 1 else null end) as lrn_no_response,

        sum(lnp_dipping_cost) as lnp_charge,
        
        count(case when origination_destination_number=lrn_dnis then 1 else null end) as lrn_same
        
        from client_cdr{$today_date} where time between $time $ingress_trunk and is_final_call=1

        group by time::date$position_2
        
        order by 1 limit $limit offset $offset;
EOT;
        $results = $this->query($sql);
        return $results;
    }
    
    public function ingress_trunk() {
        $sql = "SELECT resource_id, alias FROM resource where ingress = true order by alias";
        $results = $this->query($sql);
        $arr = array();
        foreach($results as $item)
        {
            $arr[$item[0]['resource_id']] = $item[0]['alias'];
        }
        return $arr;
    }
    
    public function download($time, $ingress_trunk, $isorder) {
        $position_1 = "";
        $position_2 = "";
        if($isorder) {
            $position_1 = "trunk_id_origination as ingress_trunk,";
            $position_2 = ",trunk_id_origination ";
        }
        $copy_db_path = Configure::read('database_actual_export_path');
        $today_date = date("Ymd");
        $sql =<<<EOT
        COPY(
            SELECT 

        time::date,

        $position_1

        count(case when lrn_number_vendor='2' then 1 else null end)
as lrn_server_count,

        count(case when lrn_number_vendor='1' then 1 else null end) as client_count,

        count(case when lrn_number_vendor='3' then 1 else null end) as cache_count,
        
        count(case when release_cause='28' then 1 else null end) as lrn_no_response,

        sum(lnp_dipping_cost::real) as lnp_charge 
        
        from client_cdr{$today_date} where time between $time $ingress_trunk and is_final_call='1'

        group by time::date$position_2
        
        order by 1 
        ) TO '" . $copy_db_path ."/lrn_report.csv' delimiter as ',' csv header;
EOT;
        $this->query($sql);
    }
    
    public function troubletickets_report_count($start, $end,$gmt,$call_count, $asr, $acd, $pdd) {
        $sql = <<<EOT
   SELECT count(*) AS c FROM (
SELECT 

term_code_name as code_name, 

trunk_id_termination as egress_trunk, 

term_client.name as client_name, 

sum(egress_ca) as call_count,

(sum(not_zero_calls)::numeric *100/NULLIF(sum(egress_ca),0)) as asr,

(sum (call_duration)/ NULLIF( sum(not_zero_calls),0) ) as acd,

(sum (pdd::integer)/ NULLIF( sum(not_zero_calls),0) ) as pdd

from

statistic_cdr

left join client term_client 
on term_client.client_id::text=statistic_cdr.egress_client_id 

where  time between '$start $gmt' and '$end $gmt'  $call_count   $asr   $acd   $pdd

group by term_code_name,egress_trunk,client_name order by term_code_name,egress_trunk,client_name) AS t
EOT;
        $results = $this->query($sql);
        return $results[0][0]['c'];
    }
    
    public function troubletickets_report($start, $end,$gmt,$call_count, $asr, $acd, $pdd ,$currPage, $pageSize) {
        $sql = <<<EOT
SELECT 

term_code_name as code_name, 

trunk_id_termination as egress_trunk, 

term_client.name as client_name, 

sum(egress_ca) as call_count,

(sum(not_zero_calls)::numeric *100/NULLIF(sum(egress_ca),0)) as asr,

(sum (call_duration)/ NULLIF( sum(not_zero_calls),0) ) as acd,

(sum (pdd::integer)/ NULLIF( sum(not_zero_calls),0) ) as pdd

from

statistic_cdr

left join client term_client 
on term_client.client_id::text=statistic_cdr.egress_client_id 

where  time between '$start $gmt' and '$end $gmt'  $call_count   $asr   $acd   $pdd

group by term_code_name,egress_trunk,client_name order by term_code_name,egress_trunk,client_name limit '$pageSize' offset '$currPage'
EOT;
        return $this->query($sql);
    }
    
}

?>
