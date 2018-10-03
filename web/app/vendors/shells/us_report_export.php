<?php

class UsReportExportShell extends Shell
{
    var $uses = array('UsExportLog','Rate','CdrsRead');

    function main()
    {
        $type = (int) $this->args[0];
        $log_id = (int) $this->args[1];
        $flg = $this->change_status($log_id,1);
        if(!$flg)
            return false;
        $log_info = $this->UsExportLog->findById($log_id);
        if (empty($log_info))
        {
            echo "log id is not exist";
            return false;
        }

        $report_type = $log_info['UsExportLog']['report_type'];
        switch ($report_type)
        {
            case 1:
                $sql = $this->us_return_code_report($log_info);
                if (empty($sql))
                    $this->save_error_info($log_id,'Rate TABLE type error');
                $file_name = $log_info['UsExportLog']['file_name'];
                $result = $this->do_download($log_id,$sql, $file_name);
                if($result)
                    $this->change_status($log_id,4);
                break;
            case 2:
                $sql = $this->us_lcr_report($log_info);
                if (empty($sql))
                    $this->save_error_info($log_id,'Rate TABLE type error');
                $file_name = $log_info['UsExportLog']['file_name'];
                $result = $this->do_download($log_id,$sql, $file_name);
                if($result)
                    $this->change_status($log_id,4);
                break;
            case 3:
                $sql = $this->us_lcr_vendor_report($log_info);
                if (empty($sql))
                    $this->save_error_info($log_id,'Rate TABLE type error');
                $file_name = $log_info['UsExportLog']['file_name'];
                $result = $this->do_download($log_id,$sql, $file_name);
                if($result)
                    $this->change_status($log_id,4);
                break;
            case 4:
                $sql = $this->us_termination_vendor_report($log_info);
                if (empty($sql))
                    $this->save_error_info($log_id,'Rate TABLE type error');
                $file_name = $log_info['UsExportLog']['file_name'];
                $result = $this->do_download_termination_vendor_report($log_id,$sql, $file_name);
                if($result)
                    $this->change_status($log_id,4);
                break;
            case 5:
            case 6:
                $sql = $this->us_frequent_report($log_info);
                if (empty($sql))
                    $this->save_error_info($log_id,'Rate TABLE type error');
                $file_name = $log_info['UsExportLog']['file_name'];
                $result = $this->do_download($log_id,$sql, $file_name);
                if($result)
                    $this->change_status($log_id,4);
                break;
            default:
                $this->save_error_info($log_id,'REPORT TYPE not exist');
        }

    }

    function us_return_code_report($log_info)
    {
        $start_time = $log_info['UsExportLog']['start_time'];
        $end_time = $log_info['UsExportLog']['end_time'];
        $route_plan_id = $log_info['UsExportLog']['route_plan_id'];
        $rate_table_id = $log_info['UsExportLog']['rate_table_id'];
        $rate_table_info = $this->Rate->find('first',array(
            'fields' => array('rate_type','jur_type'),
            'conditions' => array(
                'rate_table_id' => $rate_table_id
            ),
        ));
//        0 DNIS; 1 ANI
//        $rate_type = $rate_table_info['Rate']['rate_type'];
        $rate_type = $log_info['UsExportLog']['bill_method'];
//        1 non-JD; 2 JD
        $jur_type = $rate_table_info['Rate']['jur_type'];
        $table_like = 'us_return_code_report%2';
        $report_date_arr = $this->UsExportLog->_get_date_result_admin($start_time,$end_time,$table_like);
        $where = "WHERE rate_table_id = {$rate_table_id} AND route_plan = {$route_plan_id}
            AND orig_jur_type = {$jur_type} AND ingress_dnis_type = {$rate_type} AND report_time BETWEEN '{$start_time}' AND '{$end_time}'";
        if ($jur_type == 1)
        {
            $union_arr = array();
            foreach ($report_date_arr as $report_date_item)
            {
                $report_table = "us_return_code_report".$report_date_item;
                $union_arr[] = "SELECT npanxx, cause200, cause400, cause482, cause487, cause503, cause_other FROM {$report_table} {$where}";
            }
            $union_sql = implode(" UNION ALL ",$union_arr);
            $sql = "SELECT tmp.npanxx as code,sum(tmp.cause200) as cause200,sum(tmp.cause400) as cause400,sum(tmp.cause482) as cause482
,sum(tmp.cause487) as cause487,sum(tmp.cause503) as cause503,sum(tmp.cause_other) as Ohters FROM (" . $union_sql . ") as tmp GROUP BY npanxx ORDER BY npanxx ASC";
            return $sql;
        }
        else if($jur_type == 2 && $rate_type = 1)
        {
            $union_arr = array();
            foreach ($report_date_arr as $report_date_item)
            {
                $report_table = "us_return_code_report" . $report_date_item;
                $union_arr[] = "SELECT npanxx, cause200, cause400, cause482, cause487, cause503, cause_other,ingress_rate_type FROM {$report_table} {$where}";
            }
            $union_sql = implode(" UNION ALL ", $union_arr);
            $sql = <<<str
SELECT tmp.npanxx as code,
sum(case when ingress_rate_type = 1 THEN cause200 ELSE 0 END) as Inter200,
sum(case when ingress_rate_type = 1 THEN cause400 ELSE 0 END) as Inter400,
sum(case when ingress_rate_type = 1 THEN cause482 ELSE 0 END) as Inter482,
sum(case when ingress_rate_type = 1 THEN cause487 ELSE 0 END) as Inter487,
sum(case when ingress_rate_type = 1 THEN cause503 ELSE 0 END) as Inter503,
sum(case when ingress_rate_type = 1 THEN cause_other ELSE 0 END) as InterOthers,

sum(case when ingress_rate_type = 2 THEN cause200 ELSE 0 END) as Intra200,
sum(case when ingress_rate_type = 2 THEN cause400 ELSE 0 END) as Intra400,
sum(case when ingress_rate_type = 2 THEN cause482 ELSE 0 END) as Intra482,
sum(case when ingress_rate_type = 2 THEN cause487 ELSE 0 END) as Intra487,
sum(case when ingress_rate_type = 2 THEN cause503 ELSE 0 END) as Intra503,
sum(case when ingress_rate_type = 2 THEN cause_other ELSE 0 END) as "IntraOthers",

sum(case when ingress_rate_type = 5 THEN cause200 ELSE 0 END) as Local200,
sum(case when ingress_rate_type = 5 THEN cause400 ELSE 0 END) as Local400,
sum(case when ingress_rate_type = 5 THEN cause482 ELSE 0 END) as Local482,
sum(case when ingress_rate_type = 5 THEN cause487 ELSE 0 END) as Local487,
sum(case when ingress_rate_type = 5 THEN cause503 ELSE 0 END) as Local503,
sum(case when ingress_rate_type = 5 THEN cause_other ELSE 0 END) as LocalOthers,

sum(case when ingress_rate_type = 3 THEN cause200 ELSE 0 END) as IJ200,
sum(case when ingress_rate_type = 3 THEN cause400 ELSE 0 END) as IJ400,
sum(case when ingress_rate_type = 3 THEN cause482 ELSE 0 END) as IJ483,
sum(case when ingress_rate_type = 3 THEN cause487 ELSE 0 END) as IJ487,
sum(case when ingress_rate_type = 3 THEN cause503 ELSE 0 END) as IJ503,
sum(case when ingress_rate_type = 3 THEN cause_other ELSE 0 END) as IJOthers
FROM( $union_sql ) as tmp GROUP BY npanxx ORDER BY npanxx ASC
str;
            return $sql;
        }
        else
            return "";
    }


    function us_lcr_report($log_info)
    {
        $start_time = $log_info['UsExportLog']['start_time'];
        $end_time = $log_info['UsExportLog']['end_time'];
        $route_plan_id = $log_info['UsExportLog']['route_plan_id'];
        $rate_table_id = $log_info['UsExportLog']['rate_table_id'];
        $rate_table_info = $this->Rate->find('first',array(
            'fields' => array('rate_type','jur_type'),
            'conditions' => array(
                'rate_table_id' => $rate_table_id
            ),
        ));
//        0 DNIS; 1 ANI
//        $rate_type = $rate_table_info['Rate']['rate_type'];
        $rate_type = $log_info['UsExportLog']['bill_method'];
//        1 non-JD; 2 JD
        $jur_type = $rate_table_info['Rate']['jur_type'];
        $table_like = 'us_lcr_report%2';
        $report_date_arr = $this->UsExportLog->_get_date_result_admin($start_time,$end_time,$table_like);
        $where = "WHERE rate_table_id = {$rate_table_id} AND route_plan = {$route_plan_id}
            AND orig_jur_type = {$jur_type} AND ingress_dnis_type = {$rate_type} AND report_time BETWEEN '{$start_time}' AND '{$end_time}'";
        if ($jur_type == 1)
        {
            $union_arr = array();
            foreach ($report_date_arr as $report_date_item)
            {
                $report_table = "us_lcr_report".$report_date_item;
                $union_arr[] = "SELECT * FROM {$report_table} {$where}";
            }
            $union_sql = implode(" UNION ALL ",$union_arr);
            $sql = <<<str
SELECT tmp.npanxx as code,tmp.ingress_client_rate as sell_rate,
    tmp.lcr1_rate,sum(tmp.lcr1_total_calls) as lcr1_attempt,(case when sum(tmp.lcr1_total_calls) != 0 THEN round(trunc(sum(tmp.lcr1_not_zero_calls),2)/trunc(sum(tmp.lcr1_total_calls),2),4)*100 ELSE 0 END) as lcr1_abr,
tmp.lcr2_rate,sum(tmp.lcr2_total_calls) as lcr2_attempt,(case when sum(tmp.lcr2_total_calls) != 0 THEN round(trunc(sum(tmp.lcr2_not_zero_calls),2)/trunc(sum(tmp.lcr2_total_calls),2),4)*100 ELSE 0 END) as lcr2_abr,
tmp.lcr3_rate,sum(tmp.lcr3_total_calls) as lcr3_attempt,(case when sum(tmp.lcr3_total_calls) != 0 THEN round(trunc(sum(tmp.lcr3_not_zero_calls),2)/trunc(sum(tmp.lcr3_total_calls),2),4)*100 ELSE 0 END) as lcr3_abr,
tmp.lcr4_rate,sum(tmp.lcr4_total_calls) as lcr4_attempt,(case when sum(tmp.lcr4_total_calls) != 0 THEN round(trunc(sum(tmp.lcr4_not_zero_calls),2)/trunc(sum(tmp.lcr4_total_calls),2),4)*100 ELSE 0 END) as lcr4_abr
FROM( $union_sql ) as tmp GROUP BY npanxx,ingress_client_rate,lcr1_rate,lcr2_rate,lcr3_rate,lcr4_rate ORDER BY npanxx ASC
str;
            return $sql;
        }
        else if($jur_type == 2 && $rate_type = 1)
        {
            $union_arr = array();
            foreach ($report_date_arr as $report_date_item)
            {
                $report_table = "us_lcr_report" . $report_date_item;
                $union_arr[] = "SELECT * FROM {$report_table} {$where}";
            }
            $union_sql = implode(" UNION ALL ", $union_arr);
            $sql = <<<str
SELECT tmp.npanxx as code,tmp.ingress_client_rate as sell_rate,
inter_lcr1_rate,sum(inter_lcr1_total_calls) as inter_lcr1_attempt,
(case when sum(inter_lcr1_total_calls) != 0 THEN round(trunc(sum(inter_lcr1_not_zero_calls),2)/trunc(sum(inter_lcr1_total_calls),2),4)*100 ELSE 0 END) as inter_lcr1_abr,
inter_lcr2_rate,sum(inter_lcr2_total_calls) as inter_lcr2_attempt,
(case when sum(inter_lcr2_total_calls) != 0 THEN round(trunc(sum(inter_lcr2_not_zero_calls),2)/trunc(sum(inter_lcr2_total_calls),2),4)*100 ELSE 0 END) as inter_lcr2_abr,
inter_lcr3_rate,sum(inter_lcr3_total_calls) as inter_lcr3_attempt,
(case when sum(inter_lcr3_total_calls) != 0 THEN round(trunc(sum(inter_lcr3_not_zero_calls),2)/trunc(sum(inter_lcr3_total_calls),2),4)*100 ELSE 0 END) as inter_lcr3_abr,
inter_lcr4_rate,sum(inter_lcr4_total_calls) as inter_lcr4_attempt,
(case when sum(inter_lcr4_total_calls) != 0 THEN round(trunc(sum(inter_lcr4_not_zero_calls),2)/trunc(sum(inter_lcr4_total_calls),2),4)*100 ELSE 0 END) as inter_lcr4_abr,
intra_lcr1_rate,sum(intra_lcr1_total_calls) as intra_lcr1_attempt,
(case when sum(intra_lcr1_total_calls) != 0 THEN round(trunc(sum(intra_lcr1_not_zero_calls),2)/trunc(sum(intra_lcr1_total_calls),2),4)*100 ELSE 0 END) as intra_lcr1_abr,
intra_lcr2_rate,sum(intra_lcr2_total_calls) as intra_lcr2_attempt,
(case when sum(intra_lcr2_total_calls) != 0 THEN round(trunc(sum(intra_lcr2_not_zero_calls),2)/trunc(sum(intra_lcr2_total_calls),2),4)*100 ELSE 0 END) as intra_lcr2_abr,
intra_lcr3_rate,sum(intra_lcr3_total_calls) as intra_lcr3_attempt,
(case when sum(intra_lcr3_total_calls) != 0 THEN round(trunc(sum(intra_lcr3_not_zero_calls),2)/trunc(sum(intra_lcr3_total_calls),2),4)*100 ELSE 0 END) as intra_lcr3_abr,
intra_lcr4_rate,sum(intra_lcr4_total_calls) as intra_lcr4_attempt,
(case when sum(intra_lcr4_total_calls) != 0 THEN round(trunc(sum(intra_lcr4_not_zero_calls),2)/trunc(sum(intra_lcr4_total_calls),2),4)*100 ELSE 0 END) as intra_lcr4_abr,
ij_lcr1_rate,sum(ij_lcr1_total_calls) as ij_lcr1_attempt,
(case when sum(ij_lcr1_total_calls) != 0 THEN round(trunc(sum(ij_lcr1_not_zero_calls),2)/trunc(sum(ij_lcr1_total_calls),2),4)*100 ELSE 0 END) as ij_lcr1_abr,
ij_lcr2_rate,sum(ij_lcr2_total_calls) as ij_lcr2_attempt,
(case when sum(ij_lcr2_total_calls) != 0 THEN round(trunc(sum(ij_lcr2_not_zero_calls),2)/trunc(sum(ij_lcr2_total_calls),2),4)*100 ELSE 0 END) as ij_lcr2_abr,
ij_lcr3_rate,sum(ij_lcr3_total_calls) as ij_lcr3_attempt,
(case when sum(ij_lcr3_total_calls) != 0 THEN round(trunc(sum(ij_lcr3_not_zero_calls),2)/trunc(sum(ij_lcr3_total_calls),2),4)*100 ELSE 0 END) as ij_lcr3_abr,
ij_lcr4_rate,sum(ij_lcr4_total_calls) as ij_lcr4_attempt,
(case when sum(ij_lcr4_total_calls) != 0 THEN round(trunc(sum(ij_lcr4_not_zero_calls),2)/trunc(sum(ij_lcr4_total_calls),2),4)*100 ELSE 0 END) as ij_lcr4_abr
FROM( $union_sql ) as tmp GROUP BY npanxx,ingress_client_rate,inter_lcr1_rate,inter_lcr2_rate,inter_lcr3_rate,inter_lcr4_rate,
intra_lcr1_rate,intra_lcr2_rate,intra_lcr3_rate,intra_lcr4_rate,ij_lcr1_rate,ij_lcr2_rate,ij_lcr3_rate,ij_lcr4_rate ORDER BY npanxx ASC
str;
            return $sql;
        }
        else
            return "";
    }


    function us_lcr_vendor_report($log_info)
    {
        $start_time = $log_info['UsExportLog']['start_time'];
        $end_time = $log_info['UsExportLog']['end_time'];
        $route_plan_id = $log_info['UsExportLog']['route_plan_id'];
        $rate_table_id = $log_info['UsExportLog']['rate_table_id'];
        $rate_table_info = $this->Rate->find('first',array(
            'fields' => array('rate_type','jur_type'),
            'conditions' => array(
                'rate_table_id' => $rate_table_id
            ),
        ));
//        0 DNIS; 1 ANI
//        $rate_type = $rate_table_info['Rate']['rate_type'];
        $rate_type = $log_info['UsExportLog']['bill_method'];
//        1 non-JD; 2 JD
        $jur_type = $rate_table_info['Rate']['jur_type'];
        $table_like = 'us_lcr_vendor_report%2';
        $report_date_arr = $this->UsExportLog->_get_date_result_admin($start_time,$end_time,$table_like);
        $where = "WHERE rate_table_id = {$rate_table_id} AND route_plan = {$route_plan_id}
            AND orig_jur_type = {$jur_type} AND ingress_dnis_type = {$rate_type} AND report_time BETWEEN '{$start_time}' AND '{$end_time}'";
        if ($jur_type == 1)
        {
            $union_arr = array();
            foreach ($report_date_arr as $report_date_item)
            {
                $report_table = "us_lcr_vendor_report".$report_date_item;
                $union_arr[] = "SELECT npanxx,of_routes,of_working,of_blocked FROM {$report_table} {$where}";
            }
            $union_sql = implode(" UNION ALL ",$union_arr);
            $sql = <<<str
select npanxx as code,of_routes,of_working,of_blocked
FROM( $union_sql ) as tmp group by npanxx,of_routes,of_working,of_blocked
str;
            return $sql;
        }
        else if($jur_type == 2 && $rate_type = 1)
        {
            $union_arr = array();
            foreach ($report_date_arr as $report_date_item)
            {
                $report_table = "us_lcr_vendor_report" . $report_date_item;
                $union_arr[] = "SELECT * FROM {$report_table} {$where}";
            }
            $union_sql = implode(" UNION ALL ", $union_arr);
            $sql = <<<str
SELECT tmp.npanxx as code,
inter_of_routes,inter_of_working,inter_of_blocked,intra_of_routes,intra_of_working,intra_of_blocked
,local_of_routes,local_of_working,local_of_blocked,ij_of_routes,ij_of_working,ij_of_blocked
FROM( $union_sql ) as tmp GROUP BY npanxx,inter_of_routes,inter_of_working,inter_of_blocked,intra_of_routes,intra_of_working,
intra_of_blocked,local_of_routes,local_of_working,local_of_blocked,ij_of_routes,ij_of_working,ij_of_blocked ORDER BY npanxx ASC
str;
            return $sql;
        }
        else
            return "";
    }

    function us_termination_vendor_report($log_info)
    {
        $start_time = $log_info['UsExportLog']['start_time'];
        $end_time = $log_info['UsExportLog']['end_time'];
        $route_plan_id = $log_info['UsExportLog']['route_plan_id'];
        $rate_table_id = $log_info['UsExportLog']['rate_table_id'];
        $egress_id = $log_info['UsExportLog']['trunk_id'];
        $rate_table_info = $this->Rate->find('first',array(
            'fields' => array('rate_type','jur_type'),
            'conditions' => array(
                'rate_table_id' => $rate_table_id
            ),
        ));
//        0 DNIS; 1 ANI
//        $rate_type = $rate_table_info['Rate']['rate_type'];
        $rate_type = $log_info['UsExportLog']['bill_method'];
//        1 non-JD; 2 JD
        $jur_type = $rate_table_info['Rate']['jur_type'];
        $table_like = 'us_termination_vendor_report2%';
        $report_date_arr = $this->UsExportLog->_get_date_result_admin($start_time,$end_time,$table_like);
        $where = "WHERE rate_table_id = {$rate_table_id} AND route_plan = {$route_plan_id} AND egress_id = {$egress_id}
            AND orig_jur_type = {$jur_type} AND ingress_dnis_type = {$rate_type} AND report_time BETWEEN '{$start_time}' AND '{$end_time}'";
        if ($jur_type == 1)
        {
            $union_arr = array();
            foreach ($report_date_arr as $report_date_item)
            {
                $report_table = "us_termination_vendor_report".$report_date_item;
                $union_arr[] = "SELECT lcr1_num,lcr2_num,lcr3_num,lcr1_200_num,lcr2_200_num,lcr3_200_num FROM {$report_table} {$where}";
            }
            $union_sql = implode(" UNION ALL ",$union_arr);
            $sql = <<<str
select sum(lcr1_num) as lcr1_num,sum(lcr2_num) as lcr2_num,sum(lcr3_num) as lcr3_num,
sum(lcr1_200_num) as lcr1_200_num,sum(lcr2_200_num) as lcr2_200_num,sum(lcr3_200_num) as lcr3_200_num
FROM( $union_sql ) as tmp
str;
            return $sql;
        }
        else if($jur_type == 2 && $rate_type = 1)
        {
            $union_arr = array();
            foreach ($report_date_arr as $report_date_item)
            {
                $report_table = "us_termination_vendor_report" . $report_date_item;
                $union_arr[] = "SELECT inter_lcr1_num,inter_lcr2_num,inter_lcr3_num,inter_lcr1_200_num,inter_lcr2_200_num,
inter_lcr3_200_num,intra_lcr1_num,intra_lcr2_num,intra_lcr3_num,intra_lcr1_200_num,intra_lcr2_200_num,intra_lcr3_200_num,
ij_lcr1_num,ij_lcr2_num,ij_lcr3_num,ij_lcr1_200_num,ij_lcr2_200_num,ij_lcr3_200_num FROM {$report_table} {$where}";
            }
            $union_sql = implode(" UNION ALL ", $union_arr);
            $sql = <<<str
SELECT
 sum(inter_lcr1_num) as inter_lcr1_num,sum(inter_lcr2_num) as inter_lcr2_num,sum(inter_lcr3_num) as inter_lcr3_num,
sum(inter_lcr1_200_num) as inter_lcr1_200_num,sum(inter_lcr2_200_num) as inter_lcr2_200_num,sum(inter_lcr3_200_num) as inter_lcr3_200_num,
sum(intra_lcr1_num) as intra_lcr1_num,sum(intra_lcr2_num) as intra_lcr2_num,sum(intra_lcr3_num) as intra_lcr3_num,
sum(intra_lcr1_200_num) as intra_lcr1_200_num,sum(intra_lcr2_200_num) as intra_lcr2_200_num,sum(intra_lcr3_200_num) as intra_lcr3_200_num,
sum(ij_lcr1_num) as ij_lcr1_num,sum(ij_lcr2_num) as ij_lcr2_num,sum(ij_lcr3_num) as ij_lcr3_num,
sum(ij_lcr1_200_num) as ij_lcr1_200_num,sum(ij_lcr2_200_num) as ij_lcr2_200_num,sum(ij_lcr3_200_num) as ij_lcr3_200_num
 FROM( $union_sql ) as tmp
str;
            return $sql;
        }
        else
            return "";
    }

    function us_frequent_report($log_info)
    {
        $type = $log_info['UsExportLog']['report_type'];
        $start_time = $log_info['UsExportLog']['start_time'];
        $end_time = $log_info['UsExportLog']['end_time'];
        $route_plan_id = $log_info['UsExportLog']['route_plan_id'];
        $ingress_id = $log_info['UsExportLog']['trunk_id'];
        if($type == 5)
            $number_type = 1;
        if($type == 6)
            $number_type = 2;
        $table_like = 'us_frequent_number_report2%';
        $report_date_arr = $this->UsExportLog->_get_date_result_admin($start_time,$end_time,$table_like);
        $where = "WHERE route_plan = {$route_plan_id} AND ingress_id = {$ingress_id}
            AND number_type = {$number_type} AND report_time BETWEEN '{$start_time}' AND '{$end_time}'";
        $union_arr = array();
        foreach ($report_date_arr as $report_date_item)
        {
            $report_table = "us_frequent_number_report" . $report_date_item;
            $union_arr[] = "SELECT * FROM {$report_table} {$where}";
        }
        $union_sql = implode(" UNION ALL ", $union_arr);
        $sql = <<<str
SELECT
  number_code,sum(total_calls) as attempts
,(case when sum(total_calls) != 0 THEN round(trunc(sum(duration),2)/trunc(sum(total_calls),2)/60,4) ELSE 0 END) as ACD
,(case when (sum(busy_calls) + sum(cancel_calls) + sum(not_zero_calls)) != 0 THEN round(trunc(sum(not_zero_calls),2)/trunc((sum(busy_calls) + sum(cancel_calls) + sum(not_zero_calls)),2)*100,2) ELSE 0 END) as ASR
FROM( $union_sql ) as tmp GROUP BY number_code
str;
        return $sql;
    }

    function change_status($log_id, $status)
    {
        $save_arr = array(
            'id' => $log_id,
            'status' => $status
        );
        $flg = $this->UsExportLog->save($save_arr);
        if($flg === false)
        {
            $this->save_error_info($log_id,"DATABASE error");
            return false;
        }
        return true;
    }

    function save_error_info($log_id,$error_msg)
    {
        print $error_msg."\n";
        $save_arr = array(
            'id' => $log_id,
            'error_msg' => $error_msg,
            'status' => -1
        );
        $this->UsExportLog->save($save_arr);
    }

    function do_download($log_id,$sql, $file_name)
    {
        $this->change_status($log_id,2);
        Configure::load('myconf');
        $database_export_path = Configure::read('database_export_path') . '/us_report_download/';
        if (!is_dir($database_export_path))
        {
            $flg = mkdir($database_export_path, 0777);
            if($flg === false)
            {
                $this->save_error_info($log_id,__('download path is not exist',true));
                return false;
            }
        }
        $copy_file = $database_export_path . $file_name;
        $copy_sql = "\COPY ($sql)  TO   '$copy_file'  CSV HEADER ";
        echo "<br />".$copy_sql."<br />";
        $this->CdrsRead->_get_psql_cmd($copy_sql);
        $cmd = "wc -l " .$copy_file;
        $cmd_result = shell_exec($cmd);
        $cmd_result_arr = explode(" ",$cmd_result);
        $num_of_row = false;
        if ($cmd_result_arr[0] !== '')
        {
            if ($cmd_result_arr[0] > 0)
                $num_of_row = $cmd_result_arr[0] - 1;
            else
                $num_of_row = $cmd_result_arr[0];
        }
        if($num_of_row === false)
        {
            $this->save_error_info($log_id,__('File created fails',true));
            return false;
        }
//        $flg = $this->UsExportLog->query("UPDATE us_export_log set num_of_row = $num_of_row WHERE id = $log_id");
        $this->change_status($log_id,3);
        $compress_cmd = "cat $copy_file | bzip2 -s $copy_file";
        shell_exec($compress_cmd);
        return true;
    }

    function do_download_termination_vendor_report($log_id,$sql, $file_name)
    {
        $this->change_status($log_id,2);
        Configure::load('myconf');
        $database_export_path = Configure::read('database_export_path') . '/us_report_download/';
        if (!is_dir($database_export_path))
        {
            $flg = mkdir($database_export_path, 0777);
            if($flg === false)
            {
                $this->save_error_info($log_id,__('download path is not exist',true));
                return false;
            }
        }
        $copy_file = $database_export_path . $file_name;
        $result = $this->CdrsRead->query($sql);
        $lcr1_num = isset($result[0][0]['lcr1_num']) ? $result[0][0]['lcr1_num'] : 0;
        $lcr2_num = isset($result[0][0]['lcr2_num']) ? $result[0][0]['lcr2_num'] : 0;
        $lcr3_num = isset($result[0][0]['lcr3_num']) ? $result[0][0]['lcr3_num'] : 0;
        $lcr1_200_num = isset($result[0][0]['lcr1_200_num']) ? $result[0][0]['lcr1_200_num'] : 0;
        $lcr2_200_num = isset($result[0][0]['lcr2_200_num']) ? $result[0][0]['lcr2_200_num'] : 0;
        $lcr3_200_num = isset($result[0][0]['lcr3_200_num']) ? $result[0][0]['lcr3_200_num'] : 0;

        $inter_lcr1_num = isset($result[0][0]['inter_lcr1_num']) ? $result[0][0]['inter_lcr1_num'] : 0;
        $inter_lcr2_num = isset($result[0][0]['inter_lcr2_num']) ? $result[0][0]['inter_lcr2_num'] : 0;
        $inter_lcr3_num = isset($result[0][0]['inter_lcr3_num']) ? $result[0][0]['inter_lcr3_num'] : 0;
        $inter_lcr1_200_num = isset($result[0][0]['inter_lcr1_200_num']) ? $result[0][0]['inter_lcr1_200_num'] : 0;
        $inter_lcr2_200_num = isset($result[0][0]['inter_lcr2_200_num']) ? $result[0][0]['inter_lcr2_200_num'] : 0;
        $inter_lcr3_200_num = isset($result[0][0]['inter_lcr3_200_num']) ? $result[0][0]['inter_lcr3_200_num'] : 0;

        $intra_lcr1_num = isset($result[0][0]['intra_lcr1_num']) ? $result[0][0]['intra_lcr1_num'] : 0;
        $intra_lcr2_num = isset($result[0][0]['intra_lcr2_num']) ? $result[0][0]['intra_lcr2_num'] : 0;
        $intra_lcr3_num = isset($result[0][0]['intra_lcr3_num']) ? $result[0][0]['intra_lcr3_num'] : 0;
        $intra_lcr1_200_num = isset($result[0][0]['intra_lcr1_200_num']) ? $result[0][0]['intra_lcr1_200_num'] : 0;
        $intra_lcr2_200_num = isset($result[0][0]['intra_lcr2_200_num']) ? $result[0][0]['intra_lcr2_200_num'] : 0;
        $intra_lcr3_200_num = isset($result[0][0]['intra_lcr3_200_num']) ? $result[0][0]['intra_lcr3_200_num'] : 0;

        $ij_lcr1_num = isset($result[0][0]['ij_lcr1_num']) ? $result[0][0]['ij_lcr1_num'] : 0;
        $ij_lcr2_num = isset($result[0][0]['ij_lcr2_num']) ? $result[0][0]['ij_lcr2_num'] : 0;
        $ij_lcr3_num = isset($result[0][0]['ij_lcr3_num']) ? $result[0][0]['ij_lcr3_num'] : 0;
        $ij_lcr1_200_num = isset($result[0][0]['ij_lcr1_200_num']) ? $result[0][0]['ij_lcr1_200_num'] : 0;
        $ij_lcr2_200_num = isset($result[0][0]['ij_lcr2_200_num']) ? $result[0][0]['ij_lcr2_200_num'] : 0;
        $ij_lcr3_200_num = isset($result[0][0]['ij_lcr3_200_num']) ? $result[0][0]['ij_lcr3_200_num'] : 0;
        require_once dirname(__FILE__) . '/../phpexcel/Classes/PHPExcel.php';
        $resultPHPExcel = new PHPExcel();
        $objWriter = new PHPExcel_Writer_Excel5($resultPHPExcel);
        $resultPHPExcel->setActiveSheetIndex(0);
        if(count($result[0][0]) > 6)
        {
            $resultPHPExcel->getActiveSheet()->setCellValue('B1', 'Inter');
            $resultPHPExcel->getActiveSheet()->setCellValue('C1', 'Intra');
            $resultPHPExcel->getActiveSheet()->setCellValue('D1', 'IJ');
            $resultPHPExcel->getActiveSheet()->setCellValue('A2', 'First LCR');
            $resultPHPExcel->getActiveSheet()->setCellValue('A3', '2nd LCR');
            $resultPHPExcel->getActiveSheet()->setCellValue('A4', '3rd LCR');
            $resultPHPExcel->getActiveSheet()->setCellValue('A5', 'First LCR with 200 OK');
            $resultPHPExcel->getActiveSheet()->setCellValue('A6', '2nd LCR with 200 OK');
            $resultPHPExcel->getActiveSheet()->setCellValue('A7', '3rd LCR with 200 OK');

            $resultPHPExcel->getActiveSheet()->setCellValue('B2', $inter_lcr1_num);
            $resultPHPExcel->getActiveSheet()->setCellValue('B3', $inter_lcr2_num);
            $resultPHPExcel->getActiveSheet()->setCellValue('B4', $inter_lcr3_num);
            $resultPHPExcel->getActiveSheet()->setCellValue('B5', $inter_lcr1_200_num);
            $resultPHPExcel->getActiveSheet()->setCellValue('B6', $inter_lcr2_200_num);
            $resultPHPExcel->getActiveSheet()->setCellValue('B7', $inter_lcr3_200_num);

            $resultPHPExcel->getActiveSheet()->setCellValue('C2', $intra_lcr1_num);
            $resultPHPExcel->getActiveSheet()->setCellValue('C3', $intra_lcr2_num);
            $resultPHPExcel->getActiveSheet()->setCellValue('C4', $intra_lcr3_num);
            $resultPHPExcel->getActiveSheet()->setCellValue('C5', $intra_lcr1_200_num);
            $resultPHPExcel->getActiveSheet()->setCellValue('C6', $intra_lcr2_200_num);
            $resultPHPExcel->getActiveSheet()->setCellValue('C7', $intra_lcr3_200_num);

            $resultPHPExcel->getActiveSheet()->setCellValue('D2', $ij_lcr1_num);
            $resultPHPExcel->getActiveSheet()->setCellValue('D3', $ij_lcr2_num);
            $resultPHPExcel->getActiveSheet()->setCellValue('D4', $ij_lcr3_num);
            $resultPHPExcel->getActiveSheet()->setCellValue('D5', $ij_lcr1_200_num);
            $resultPHPExcel->getActiveSheet()->setCellValue('D6', $ij_lcr2_200_num);
            $resultPHPExcel->getActiveSheet()->setCellValue('D7', $ij_lcr3_200_num);
        }
        else
        {
            $resultPHPExcel->getActiveSheet()->setCellValue('A1', 'First LCR');
            $resultPHPExcel->getActiveSheet()->setCellValue('A2', '2nd LCR');
            $resultPHPExcel->getActiveSheet()->setCellValue('A3', '3rd LCR');
            $resultPHPExcel->getActiveSheet()->setCellValue('A4', 'First LCR with 200 OK');
            $resultPHPExcel->getActiveSheet()->setCellValue('A5', '2nd LCR with 200 OK');
            $resultPHPExcel->getActiveSheet()->setCellValue('A6', '3rd LCR with 200 OK');

            $resultPHPExcel->getActiveSheet()->setCellValue('B1', $lcr1_num);
            $resultPHPExcel->getActiveSheet()->setCellValue('B2', $lcr2_num);
            $resultPHPExcel->getActiveSheet()->setCellValue('B3', $lcr3_num);
            $resultPHPExcel->getActiveSheet()->setCellValue('B4', $lcr1_200_num);
            $resultPHPExcel->getActiveSheet()->setCellValue('B5', $lcr2_200_num);
            $resultPHPExcel->getActiveSheet()->setCellValue('B6', $lcr3_200_num);
        }
        $objWriter->save($copy_file);
        $this->change_status($log_id,3);
        $compress_cmd = "cat $copy_file | bzip2 -s $copy_file";
        shell_exec($compress_cmd);
        return true;
    }

}
